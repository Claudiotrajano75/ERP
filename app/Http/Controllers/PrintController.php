<?php

namespace App\Http\Controllers;

use App\Models\ConfigGeral;
use App\Models\Nfce;
use App\Models\Empresa;
use App\Models\Troca;
use App\Models\SangriaCaixa;
use App\Models\SuprimentoCaixa;
use App\Services\PrintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    protected $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    /**
     * Testa a conexao com a impressora termica
     */
    public function testar(Request $request)
    {
        $ip = $request->input('ip');
        $porta = $request->input('porta', 9100);

        if (empty($ip)) {
            return response()->json([
                'success' => false,
                'message' => 'Informe o IP da impressora.'
            ], 422);
        }

        $resultado = $this->printService->testarConexao($ip, $porta);
        
        return response()->json($resultado);
    }

    /**
     * Imprime cupom fiscal (DANFE NFC-e) na impressora termica
     */
    public function imprimirNfce($id)
    {
        try {
            $nfce = Nfce::with(['itens.produto', 'cliente', 'fatura'])->findOrFail($id);
            $configGeral = ConfigGeral::where('empresa_id', $nfce->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            // Modo USB: Abre cupom fiscal termico padronizado com disparo automatico de impressao
            if ($configGeral->isPrinterUsb()) {
                return response()->json([
                    'success' => true,
                    'is_usb'  => true,
                    'url'     => route('print.termico-nfce', ['id' => $id]),
                    'message' => 'Abrindo cupom fiscal NFC-e térmico (USB)...'
                ]);
            }

            $resultado = $this->printService->imprimirNfce($nfce, $configGeral);
            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom fiscal NFC-e: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime cupom nao fiscal na impressora termica
     */
    public function imprimirCupom($id)
    {
        try {
            $item = Nfce::with(['itens.produto', 'cliente', 'fatura'])->findOrFail($id);
            $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();

            // Verifica se a impressora esta configurada
            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                // Sem impressora configurada - retorna flag para usar o comportamento antigo (PDF)
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            // Modo USB: Abre cupom nao fiscal termico padronizado com disparo automatico de impressao
            if ($configGeral->isPrinterUsb()) {
                return response()->json([
                    'success' => true,
                    'is_usb'  => true,
                    'url'     => route('print.termico-cupom', ['id' => $id]),
                    'message' => 'Abrindo cupom não fiscal térmico (USB)...'
                ]);
            }

            $resultado = $this->printService->imprimirCupomNaoFiscal($item, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom nao fiscal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime cupom de troca na impressora termica
     */
    public function imprimirTroca($id)
    {
        try {
            $item = Troca::with(['itens.produto', 'cliente', 'nfce.itens.produto', 'nfce.fatura', 'nfce.cliente'])->findOrFail($id);
            $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();
            $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('trocas.cupom_troca', compact('item', 'config'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom de troca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime cupom de pre-venda na impressora termica
     */
    public function imprimirPreVenda($id)
    {
        try {
            $item = \App\Models\PreVenda::with(['itens.produto', 'cliente'])->findOrFail($id);
            $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();
            $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('pre_venda.cupom', compact('item', 'config'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom de pre-venda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime comprovante de sangria na impressora termica
     */
    public function imprimirSangria($id)
    {
        try {
            $sangria = SangriaCaixa::findOrFail($id);
            $empresa = Empresa::with('cidade')->findOrFail($sangria->empresa_id);
            $configGeral = ConfigGeral::where('empresa_id', $sangria->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('front_box.sangria_print', compact('sangria', 'empresa'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar comprovante de sangria: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime comprovante de suprimento na impressora termica
     */
    public function imprimirSuprimento($id)
    {
        try {
            $suprimento = SuprimentoCaixa::findOrFail($id);
            $empresa = Empresa::with('cidade')->findOrFail($suprimento->empresa_id);
            $configGeral = ConfigGeral::where('empresa_id', $suprimento->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('front_box.suprimento_print', compact('suprimento', 'empresa'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar comprovante de suprimento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna a configuracao atual da impressora para o frontend
     */
    public function configuracao()
    {
        $empresa_id = request()->empresa_id ?: (Auth::user() ? Auth::user()->empresa_id : null);
        $configGeral = $empresa_id ? ConfigGeral::where('empresa_id', $empresa_id)->first() : ConfigGeral::first();

        return response()->json([
            'printer_configured' => $configGeral ? $configGeral->isPrinterConfigured() : false,
            'printer_tipo' => $configGeral->printer_tipo ?? 'rede',
            'printer_nome' => $configGeral->printer_nome ?? null,
            'printer_ip' => $configGeral->printer_ip ?? null,
            'printer_porta' => $configGeral->printer_porta ?? 9100,
            'printer_largura' => $configGeral->printer_largura ?? '80',
        ]);
    }

    /**
     * Renderiza o Cupom Não Fiscal padronizado para impressão térmica (USB / Navegador)
     */
    public function cupomNaoFiscalTermico($id)
    {
        $item = Nfce::with(['itens.produto', 'cliente', 'fatura'])->findOrFail($id);
        $empresa = Empresa::with('cidade')->findOrFail($item->empresa_id);
        $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();
        $largura = $configGeral->printer_largura ?? '80';

        return view('front_box.cupom_nao_fiscal_termico', compact('item', 'empresa', 'configGeral', 'largura'));
    }

    /**
     * Renderiza o DANFE NFC-e padronizado para impressão térmica (USB / Navegador)
     */
    public function cupomFiscalTermico($id)
    {
        $nfce = Nfce::with(['itens.produto', 'cliente', 'fatura'])->findOrFail($id);
        $empresa = Empresa::with('cidade')->findOrFail($nfce->empresa_id);
        $configGeral = ConfigGeral::where('empresa_id', $nfce->empresa_id)->first();
        $largura = $configGeral->printer_largura ?? '80';

        $qrCodeUrl = null;
        $urlChave  = null;
        $xmlPath   = public_path('xml_nfce/') . $nfce->chave . '.xml';
        if (!file_exists($xmlPath)) {
            $xmlPath = public_path('xml_nfce_contigencia/') . $nfce->chave . '.xml';
        }
        if (file_exists($xmlPath)) {
            try {
                $xml = simplexml_load_string(file_get_contents($xmlPath));
                if ($xml && isset($xml->infNFeSupl)) {
                    $qrCodeUrl = (string) $xml->infNFeSupl->qrCode;
                    $urlChave  = (string) $xml->infNFeSupl->urlChave;
                } elseif ($xml && isset($xml->NFe->infNFeSupl)) {
                    $qrCodeUrl = (string) $xml->NFe->infNFeSupl->qrCode;
                    $urlChave  = (string) $xml->NFe->infNFeSupl->urlChave;
                }
            } catch (\Exception $xmlEx) {
                // Silencia excecao de leitura XML
            }
        }

        return view('nfce.cupom_fiscal_termico', compact('nfce', 'empresa', 'qrCodeUrl', 'urlChave', 'configGeral', 'largura'));
    }

    /**
     * Página de teste de impressão térmica USB
     */
    public function paginaTesteTermico()
    {
        $empresa_id = request()->empresa_id ?: (Auth::user() ? Auth::user()->empresa_id : null);
        $empresa = $empresa_id ? Empresa::with('cidade')->find($empresa_id) : Empresa::first();
        $configGeral = $empresa ? ConfigGeral::where('empresa_id', $empresa->id)->first() : null;
        $largura = $configGeral->printer_largura ?? '80';

        return view('print.termico_teste', compact('empresa', 'largura'));
    }
}
