<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\Cte;
use App\Models\Mdfe;
use App\Models\EscritorioContabil;
use App\Models\SpedConfig;
use App\Utils\EmailUtil;
use Carbon\Carbon;
use ZipArchive;

class ContadorAdminFechamentoController extends Controller
{
    protected $emailUtil;

    public function __construct(EmailUtil $emailUtil)
    {
        $this->emailUtil = $emailUtil;
        if (!is_dir(public_path('zips'))) {
            mkdir(public_path('zips'), 0777, true);
        }
    }

    private function getEmpresaSelecionada()
    {
        $contador = Empresa::find(request()->empresa_id);
        if (!$contador || !$contador->empresa_selecionada) {
            return null;
        }
        return Empresa::with('cidade')->find($contador->empresa_selecionada);
    }

    public function index(Request $request)
    {
        $empresa = $this->getEmpresaSelecionada();
        if (!$empresa) {
            session()->flash("flash_warning", "Selecione uma empresa no painel do contador primeiro!");
            return redirect()->route('home');
        }

        $mes = (int)$request->get('mes', date('m'));
        $ano = (int)$request->get('ano', date('Y'));

        $dataInicial = Carbon::createFromDate($ano, $mes, 1)->startOfMonth()->format('Y-m-d');
        $dataFinal = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->format('Y-m-d');
        $empresaId = $empresa->id;

        // NF-e Saídas
        $nfeSaidas = Nfe::where('empresa_id', $empresaId)
            ->where('tpNF', 1)
            ->whereDate('data_emissao_saida', '>=', $dataInicial)
            ->whereDate('data_emissao_saida', '<=', $dataFinal)
            ->get();

        // NF-e Entradas (Compras)
        $nfeEntradas = Nfe::where('empresa_id', $empresaId)
            ->where('tpNF', 0)
            ->where('estado', '!=', 'cancelado')
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        // NFC-e
        $nfces = Nfce::where('empresa_id', $empresaId)
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        // CT-e e MDF-e
        $ctes = Cte::where('empresa_id', $empresaId)
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        $mdfes = Mdfe::where('empresa_id', $empresaId)
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        $stats = [
            'nfe_saidas_qtd' => $nfeSaidas->where('estado', 'aprovado')->count(),
            'nfe_saidas_valor' => $nfeSaidas->where('estado', 'aprovado')->sum('total'),
            'nfe_saidas_canc' => $nfeSaidas->where('estado', 'cancelado')->count(),

            'nfe_entradas_qtd' => $nfeEntradas->count(),
            'nfe_entradas_valor' => $nfeEntradas->sum('total'),

            'nfce_qtd' => $nfces->where('estado', 'aprovado')->count(),
            'nfce_valor' => $nfces->where('estado', 'aprovado')->sum('total'),
            'nfce_canc' => $nfces->where('estado', 'cancelado')->count(),

            'cte_qtd' => $ctes->where('estado', 'aprovado')->count(),
            'mdfe_qtd' => $mdfes->where('estado', 'aprovado')->count(),

            'escritorio' => EscritorioContabil::where('empresa_id', $empresaId)->first(),
            'sped_configurado' => SpedConfig::where('empresa_id', $empresaId)->exists(),
        ];

        return view('contador.fechamento', compact('empresa', 'mes', 'ano', 'dataInicial', 'dataFinal', 'stats'));
    }

    public function downloadZip(Request $request)
    {
        $empresa = $this->getEmpresaSelecionada();
        if (!$empresa) {
            session()->flash("flash_warning", "Selecione uma empresa primeiro!");
            return redirect()->route('home');
        }

        $mes = (int)$request->get('mes', date('m'));
        $ano = (int)$request->get('ano', date('Y'));
        $dataInicial = Carbon::createFromDate($ano, $mes, 1)->startOfMonth()->format('Y-m-d');
        $dataFinal = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->format('Y-m-d');

        $zipPath = $this->gerarPacoteZip($empresa, $dataInicial, $dataFinal, $mes, $ano);

        if ($zipPath && file_exists($zipPath)) {
            $mesStr = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $nomeLimpo = preg_replace('/[^A-Za-z0-9_-]/', '_', $empresa->nome);
            $filename = "Fechamento_{$mesStr}_{$ano}_{$nomeLimpo}.zip";
            return response()->download($zipPath, $filename);
        } else {
            session()->flash("flash_error", "Nenhum arquivo fiscal encontrado para o período selecionado.");
            return redirect()->back();
        }
    }

    public function enviarEmail(Request $request)
    {
        $empresa = $this->getEmpresaSelecionada();
        if (!$empresa) {
            session()->flash("flash_warning", "Selecione uma empresa primeiro!");
            return redirect()->route('home');
        }

        $mes = (int)$request->get('mes', date('m'));
        $ano = (int)$request->get('ano', date('Y'));
        $dataInicial = Carbon::createFromDate($ano, $mes, 1)->startOfMonth()->format('Y-m-d');
        $dataFinal = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->format('Y-m-d');

        $escritorio = EscritorioContabil::where('empresa_id', $empresa->id)->first();
        if (!$escritorio || empty($escritorio->email)) {
            session()->flash("flash_error", "A empresa não possui Escritório Contábil cadastrado com e-mail válido!");
            return redirect()->back();
        }

        $zipPath = $this->gerarPacoteZip($empresa, $dataInicial, $dataFinal, $mes, $ano);
        if (!$zipPath || !file_exists($zipPath)) {
            session()->flash("flash_error", "Nenhum arquivo fiscal encontrado para empacotar.");
            return redirect()->back();
        }

        $mesStr = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $body = "Prezado(a) Contador(a),<br><br>";
        $body .= "Segue em anexo o pacote completo de documentos fiscais e SPED referente ao mês <strong>{$mesStr}/{$ano}</strong> da empresa <strong>{$empresa->nome}</strong> (CNPJ: {$empresa->cpf_cnpj}).<br><br>";
        $body .= "O arquivo compactado contém os XMLs de Saídas, Entradas, NFC-e e o arquivo do SPED Fiscal gerado.<br><br>";
        $body .= "Atenciosamente,<br><strong>" . config('app.name', 'ERP') . "</strong>";

        $enviado = $this->emailUtil->enviarXmlContadorZip($empresa->id, $zipPath, "Fechamento Fiscal {$mesStr}/{$ano}", $body);

        if ($enviado) {
            session()->flash("flash_success", "Pacote fiscal do mês {$mesStr}/{$ano} enviado com sucesso para {$escritorio->email}!");
        } else {
            session()->flash("flash_error", "Não foi possível enviar o e-mail. Verifique as configurações de SMTP da empresa.");
        }

        return redirect()->back();
    }

    public function gerarPacoteZip(Empresa $empresa, $dataInicial, $dataFinal, $mes, $ano)
    {
        $empresaId = $empresa->id;
        $doc = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);
        $mesStr = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $zipFile = public_path("zips/fechamento_{$doc}_{$mesStr}_{$ano}.zip");

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        $arquivosAdicionados = 0;

        // 1. NF-e Saídas
        $nfeSaidas = Nfe::where('empresa_id', $empresaId)
            ->where('tpNF', 1)
            ->whereDate('data_emissao_saida', '>=', $dataInicial)
            ->whereDate('data_emissao_saida', '<=', $dataFinal)
            ->get();

        foreach ($nfeSaidas as $item) {
            $chave = $item->chave;
            if ($item->estado == 'aprovado') {
                if (file_exists(public_path("xml_nfe/{$chave}.xml"))) {
                    $zip->addFile(public_path("xml_nfe/{$chave}.xml"), "01_NFe_Saidas/Autorizadas/{$chave}.xml");
                    $arquivosAdicionados++;
                }
            } elseif ($item->estado == 'cancelado') {
                if (file_exists(public_path("xml_nfe_cancelada/{$chave}.xml"))) {
                    $zip->addFile(public_path("xml_nfe_cancelada/{$chave}.xml"), "01_NFe_Saidas/Canceladas/{$chave}-canc.xml");
                    $arquivosAdicionados++;
                } elseif (file_exists(public_path("xml_nfe/{$chave}.xml"))) {
                    $zip->addFile(public_path("xml_nfe/{$chave}.xml"), "01_NFe_Saidas/Canceladas/{$chave}.xml");
                    $arquivosAdicionados++;
                }
            }

            // Carta de Correção
            if (file_exists(public_path("xml_nfe_correcao/{$chave}.xml"))) {
                $zip->addFile(public_path("xml_nfe_correcao/{$chave}.xml"), "01_NFe_Saidas/Cartas_Correcao/{$chave}-cce.xml");
                $arquivosAdicionados++;
            }
        }

        // 2. NF-e Entradas (Compras)
        $nfeEntradas = Nfe::where('empresa_id', $empresaId)
            ->where('tpNF', 0)
            ->where('estado', '!=', 'cancelado')
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        foreach ($nfeEntradas as $item) {
            $chave = $item->chave_importada ?: $item->chave;
            if (!$chave) continue;

            $paths = [
                public_path("xml_entrada/{$chave}.xml"),
                public_path("xml_dfe/{$chave}.xml"),
                public_path("xml_nfe/{$chave}.xml"),
                public_path("xml_entrada_emitida/{$chave}.xml")
            ];

            foreach ($paths as $p) {
                if (file_exists($p)) {
                    $zip->addFile($p, "02_NFe_Entradas_Compras/{$chave}.xml");
                    $arquivosAdicionados++;
                    break;
                }
            }
        }

        // 3. NFC-e (PDV)
        $nfces = Nfce::where('empresa_id', $empresaId)
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        foreach ($nfces as $item) {
            $chave = $item->chave;
            if ($item->estado == 'aprovado') {
                if (file_exists(public_path("xml_nfce/{$chave}.xml"))) {
                    $zip->addFile(public_path("xml_nfce/{$chave}.xml"), "03_NFCe_PDV/Autorizadas/{$chave}.xml");
                    $arquivosAdicionados++;
                }
            } elseif ($item->estado == 'cancelado') {
                if (file_exists(public_path("xml_nfce_cancelada/{$chave}.xml"))) {
                    $zip->addFile(public_path("xml_nfce_cancelada/{$chave}.xml"), "03_NFCe_PDV/Canceladas/{$chave}-canc.xml");
                    $arquivosAdicionados++;
                }
            }
        }

        // 4. CT-e e MDF-e
        $ctes = Cte::where('empresa_id', $empresaId)
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        foreach ($ctes as $item) {
            if ($item->estado == 'aprovado' && file_exists(public_path("xml_cte/{$item->chave}.xml"))) {
                $zip->addFile(public_path("xml_cte/{$item->chave}.xml"), "04_CTe_MDFe/CTe/{$item->chave}.xml");
                $arquivosAdicionados++;
            }
        }

        $mdfes = Mdfe::where('empresa_id', $empresaId)
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereDate('created_at', '<=', $dataFinal)
            ->get();

        foreach ($mdfes as $item) {
            if ($item->estado == 'aprovado' && file_exists(public_path("xml_mdfe/{$item->chave}.xml"))) {
                $zip->addFile(public_path("xml_mdfe/{$item->chave}.xml"), "04_CTe_MDFe/MDFe/{$item->chave}.xml");
                $arquivosAdicionados++;
            }
        }

        // 5. SPED Fiscal (EFD ICMS/IPI)
        $spedFile = public_path("sped_files/SPED-EFD-{$doc}.txt");
        if (file_exists($spedFile)) {
            $zip->addFile($spedFile, "SPED_Fiscal_EFD_{$mesStr}_{$ano}.txt");
            $arquivosAdicionados++;
        }

        $zip->close();

        return $arquivosAdicionados > 0 ? $zipFile : null;
    }
}
