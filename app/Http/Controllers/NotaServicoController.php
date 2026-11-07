<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\NotaServico;
use App\Models\ContaReceber;
use App\Models\Reserva;
use App\Models\ItemNotaServico;
use Illuminate\Support\Facades\DB;
use App\Services\NfseNacionalService;

class NotaServicoController extends Controller
{

    public function __construct()
    {
        if (!is_dir(public_path('xml_nota_servico'))) {
            mkdir(public_path('xml_nota_servico'), 0777, true);
        }
        if (!is_dir(public_path('xml_nota_servico_cancelada'))) {
            mkdir(public_path('xml_nota_servico_cancelada'), 0777, true);
        }

        $this->middleware('permission:nfse_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:nfse_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:nfse_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:nfse_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $tomador = $request->get('tomador');
        $estado = $request->get('estado');

        $empresa = Empresa::findOrFail($request->empresa_id);
        $data = NotaServico::
        where('empresa_id', $request->empresa_id)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when(!empty($tomador), function ($query) use ($tomador) {
            return $query->where('razao_social', 'like', "%$tomador%");
        })
        ->when($estado != "", function ($query) use ($estado) {
            return $query->where('estado', $estado);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(env("PAGINACAO"));

        $baseStats = NotaServico::where('empresa_id', $request->empresa_id);
        $stats = [
            'total' => (clone $baseStats)->count(),
            'aprovadas' => (clone $baseStats)->where('estado', 'aprovado')->count(),
            'canceladas' => (clone $baseStats)->where('estado', 'cancelado')->count(),
            'rejeitadas' => (clone $baseStats)->where('estado', 'rejeitado')->count(),
            'novas' => (clone $baseStats)->where('estado', 'novo')->count(),
            'total_valor' => (clone $baseStats)->where('estado', 'aprovado')->sum('valor_total') ?? 0,
        ];

        return view('nota_servico.index', compact('data', 'stats'));

    }

    public function create(){
        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }
        return view('nota_servico.create');
    }

    public function edit($id){
        $item = NotaServico::findOrFail($id);
        return view('nota_servico.edit', compact('item'));
    }

    public function store(Request $request){
        try{
            DB::transaction(function () use ($request) {

                $config = Empresa::find($request->empresa_id);

                $totalServico = __convert_value_bd($request->valor_servico);
                $nfse = NotaServico::create([
                    'empresa_id' => $request->empresa_id,
                    'valor_total' => $totalServico,
                    'ambiente' => $config->ambiente,
                    'estado' => 'novo',
                    'serie' => '',
                    'codigo_verificacao' => '',
                    'numero_nfse' => 0,
                    'url_xml' => '',
                    'url_pdf_nfse' => '',
                    'url_pdf_rps' => '',
                    'cliente_id' => $request->cliente_id,
                    'natureza_operacao' => $request->natureza_operacao,
                    'documento' => $request->documento,
                    'razao_social' => $request->razao_social,
                    'im' => $request->im ?? '',
                    'ie' => $request->ie ?? '',
                    'cep' => $request->cep ?? '',
                    'rua' => $request->rua,
                    'numero' => $request->numero,
                    'bairro' => $request->bairro,
                    'complemento' => $request->complemento ?? '',
                    'cidade_id' => $request->cidade_id,
                    'email' => $request->email ?? '',
                    'gerar_conta_receber' => $request->gerar_conta_receber,
                    'telefone' => $request->telefone ?? ''
                ]);

                ItemNotaServico::create([
                    'nota_servico_id' => $nfse->id,
                    'discriminacao' => $request->discriminacao,
                    'valor_servico' => __convert_value_bd($request->valor_servico),
                    'servico_id' => $request->servico_id,
                    'codigo_cnae' => $request->codigo_cnae ?? '',
                    'codigo_servico' => $request->codigo_servico ?? '',
                    'codigo_tributacao_nacional' => $request->codigo_tributacao_nacional ?? '',
                    'codigo_tributacao_municipio' => $request->codigo_tributacao_municipio ?? '',
                    'exigibilidade_iss' => $request->exigibilidade_iss,
                    'iss_retido' => $request->iss_retido,
                    'data_competencia' => $request->data_competencia ?? null,
                    'estado_local_prestacao_servico' => $request->estado_local_prestacao_servico ?? '',
                    'cidade_local_prestacao_servico' => $request->cidade_local_prestacao_servico ?? '',
                    'valor_deducoes' => $request->valor_deducoes ? __convert_value_bd($request->valor_deducoes) : 0,
                    'desconto_incondicional' => $request->desconto_incondicional ? __convert_value_bd($request->desconto_incondicional) : 0,
                    'desconto_condicional' => $request->desconto_condicional ? __convert_value_bd($request->desconto_condicional) : 0,
                    'outras_retencoes' => $request->outras_retencoes ? __convert_value_bd($request->outras_retencoes) : 0,
                    'aliquota_iss' => $request->aliquota_iss ? __convert_value_bd($request->aliquota_iss) : 0,
                    'aliquota_pis' => $request->aliquota_pis ? __convert_value_bd($request->aliquota_pis) : 0,
                    'aliquota_cofins' => $request->aliquota_cofins ? __convert_value_bd($request->aliquota_cofins) : 0,
                    'aliquota_inss' => $request->aliquota_inss ? __convert_value_bd($request->aliquota_inss) : 0,
                    'aliquota_ir' => $request->aliquota_ir ? __convert_value_bd($request->aliquota_ir) : 0,
                    'aliquota_csll' => $request->aliquota_csll ? __convert_value_bd($request->aliquota_csll) : 0,
                    'intermediador' => $request->intermediador ?? 'n',
                    'documento_intermediador' => $request->documento_intermediador ?? '',
                    'nome_intermediador' => $request->nome_intermediador ?? '',
                    'im_intermediador' => $request->im_intermediador ?? '',
                    'responsavel_retencao_iss' => $request->responsavel_retencao_iss ?? 1,

                ]);

                if(isset($request->reserva_id)){
                    $reserva = Reserva::findOrFail($request->reserva_id);
                    $reserva->nfse_id = $nfse->id;
                    $reserva->save();
                }

                if($request->data_vencimento){
                    $caixa = __isCaixaAberto();

                    $conta = ContaReceber::create([
                        'empresa_id' => $request->empresa_id,
                        'cliente_id' => $request->cliente_id,
                        'valor_integral' => $totalServico,
                        'data_vencimento' => $request->data_vencimento,
                        'local_id' => $caixa->local_id,
                    ]);

                    $nfse->data_vencimento = $request->data_vencimento;
                    $nfse->conta_receber_id = $conta->id;
                    $nfse->save();
                }
                session()->flash("flash_success", "NFSe criada com sucesso!");

            });
return redirect()->route('nota-servico.index');
}catch (\Exception $e) {
            // echo $e->getMessage();
    session()->flash("flash_error", 'Algo deu errado: ' . $e->getMessage());
    return redirect()->back();
}
}

public function update(Request $request, $id){
    try{
        $item = NotaServico::findOrFail($id);
        DB::transaction(function () use ($request, $item) {

            $totalServico = __convert_value_bd($request->valor_servico);
            $config = Empresa::find($request->empresa_id);

            $item->update([
                'empresa_id' => $request->empresa_id,
                'valor_total' => $totalServico,
                'ambiente' => $config->ambiente,
                'serie' => '',
                'codigo_verificacao' => '',
                'numero_nfse' => 0,
                'url_xml' => '',
                'url_pdf_nfse' => '',
                'url_pdf_rps' => '',
                'cliente_id' => $request->cliente_id,
                'natureza_operacao' => $request->natureza_operacao,
                'documento' => $request->documento,
                'gerar_conta_receber' => $request->gerar_conta_receber,
                'razao_social' => $request->razao_social,
                'im' => $request->im ?? '',
                'ie' => $request->ie ?? '',
                'cep' => $request->cep ?? '',
                'rua' => $request->rua,
                'numero' => $request->numero,
                'bairro' => $request->bairro,
                'complemento' => $request->complemento ?? '',
                'cidade_id' => $request->cidade_id,
                'email' => $request->email ?? '',
                'telefone' => $request->telefone ?? ''
            ]);

            if($item->servico){
                $item->servico->delete();
            }

            ItemNotaServico::create([
                'nota_servico_id' => $item->id,
                'discriminacao' => $request->discriminacao,
                'valor_servico' => __convert_value_bd($request->valor_servico),
                'servico_id' => $request->servico_id,
                'codigo_cnae' => $request->codigo_cnae ?? '',
                'codigo_servico' => $request->codigo_servico ?? '',
                'codigo_tributacao_nacional' => $request->codigo_tributacao_nacional ?? '',
                'codigo_tributacao_municipio' => $request->codigo_tributacao_municipio ?? '',
                'exigibilidade_iss' => $request->exigibilidade_iss,
                'iss_retido' => $request->iss_retido,
                'data_competencia' => $request->data_competencia ?? null,
                'estado_local_prestacao_servico' => $request->estado_local_prestacao_servico ?? '',
                'cidade_local_prestacao_servico' => $request->cidade_local_prestacao_servico ?? '',
                'valor_deducoes' => $request->valor_deducoes ? __convert_value_bd($request->valor_deducoes) : 0,
                'desconto_incondicional' => $request->desconto_incondicional ? __convert_value_bd($request->desconto_incondicional) : 0,
                'desconto_condicional' => $request->desconto_condicional ? __convert_value_bd($request->desconto_condicional) : 0,
                'outras_retencoes' => $request->outras_retencoes ? __convert_value_bd($request->outras_retencoes) : 0,
                'aliquota_iss' => $request->aliquota_iss ? __convert_value_bd($request->aliquota_iss) : 0,
                'aliquota_pis' => $request->aliquota_pis ? __convert_value_bd($request->aliquota_pis) : 0,
                'aliquota_cofins' => $request->aliquota_cofins ? __convert_value_bd($request->aliquota_cofins) : 0,
                'aliquota_inss' => $request->aliquota_inss ? __convert_value_bd($request->aliquota_inss) : 0,
                'aliquota_ir' => $request->aliquota_ir ? __convert_value_bd($request->aliquota_ir) : 0,
                'aliquota_csll' => $request->aliquota_csll ? __convert_value_bd($request->aliquota_csll) : 0,
                'intermediador' => $request->intermediador ?? 'n',
                'documento_intermediador' => $request->documento_intermediador ?? '',
                'nome_intermediador' => $request->nome_intermediador ?? '',
                'im_intermediador' => $request->im_intermediador ?? '',
                'responsavel_retencao_iss' => $request->responsavel_retencao_iss ?? 1,

            ]);

            if($request->data_vencimento){

                $caixa = __isCaixaAberto();

                if($item->contaReceber){
                    $item->contaReceber->delete();
                }

                $conta = ContaReceber::create([
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $request->cliente_id,
                    'valor_integral' => $totalServico,
                    'data_vencimento' => $request->data_vencimento,
                    'local_id' => $caixa->local_id,
                ]);

                $item->data_vencimento = $request->data_vencimento;
                $item->conta_receber_id = $conta->id;
                $item->save();
            }
            session()->flash("flash_success", "NFSe atualizada com sucesso!");

        });
return redirect()->route('nota-servico.index');
}catch (\Exception $e) {
            // echo $e->getMessage();
    session()->flash("flash_error", 'Algo deu errado: ' . $e->getMessage());
    return redirect()->back();
}
}

public function destroy($id)
{
    $item = NotaServico::findOrFail($id);
    try {
        if($item->servico){
            $item->servico->delete();
        }
        $item->delete();
        session()->flash("flash_success", "NFSe removida!");
    } catch (\Exception $e) {
            // echo $e->getLine();
            // die;
        session()->flash("flash_error", 'Algo deu errado: '. $e->getMessage());
    }
    return redirect()->route('nota-servico.index');
}

public function imprimir($id)
{
    $item = NotaServico::findOrFail($id);
    if (!empty($item->url_pdf_nfse)) {
        return redirect($item->url_pdf_nfse);
    }
    if (!empty($item->chave)) {
        $empresa = $item->empresa;
        $baseUrl = ($empresa && $empresa->ambiente == 1)
            ? 'https://adn.nfse.gov.br'
            : 'https://adn.producaorestrita.nfse.gov.br';
        return redirect("{$baseUrl}/danfse/{$item->chave}");
    }
    session()->flash("flash_warning", "O DANFSE oficial da NFS-e ainda não foi gerado pelo Emissor Nacional.");
    return redirect()->back();
}

public function downloadXml($id)
{
    $item = NotaServico::findOrFail($id);
    $pastaXml = public_path('xml_nota_servico');

    // 1. Tenta o XML oficial retornado pela SEFIN Nacional
    if (!empty($item->chave)) {
        $arquivo = "{$pastaXml}/{$item->chave}.xml";
        if (file_exists($arquivo)) {
            return response()->download($arquivo, "NFSe-{$item->numero_nfse}-{$item->chave}.xml");
        }
    }

    // 2. Tenta o XML da DPS assinado
    if (!empty($item->chave_dps)) {
        $arquivoDps = "{$pastaXml}/{$item->chave_dps}.xml";
        if (file_exists($arquivoDps)) {
            return response()->download($arquivoDps, "DPS-{$item->numero_dps}-{$item->chave_dps}.xml");
        }
    }

    // 3. Fallback: conteúdo armazenado no banco
    if (!empty($item->xml_dps)) {
        return response($item->xml_dps, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="DPS-' . ($item->numero_dps ?: $item->id) . '.xml"'
        ]);
    }

    session()->flash("flash_error", "Arquivo XML da NFS-e / DPS não encontrado.");
    return redirect()->back();
}

public function preview($id)
{
    $item = NotaServico::findOrFail($id);
    try {
        $empresa = Empresa::findOrFail($item->empresa_id);
        if (empty($empresa->arquivo) || empty($empresa->senha)) {
            session()->flash('flash_warning', 'Certificado digital A1 não configurado para prévia da DPS.');
            return redirect()->back();
        }

        $service = new NfseNacionalService($empresa);
        $gerado = $service->gerarDpsAssinada($item, false);

        return response($gerado['xmlCru'], 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'inline; filename="DPS-preview-' . $item->id . '.xml"'
        ]);

    } catch (\Exception $e) {
        session()->flash('flash_error', 'Algo deu errado ao gerar prévia da DPS: ' . $e->getMessage());
        return redirect()->back();
    }
}

private function retiraAcentos($texto)
{
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/", "/(ç)/", "/(&)/"),explode(" ","a A e E i I o O u U n N c e"),$texto);
}

}
