<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use App\Models\Nfe;
use App\Models\ItemNfe;
use App\Models\Produto;
use App\Models\ManifestoDfe;
use App\Models\ProdutoFornecedor;
use App\Models\Cidade;
use App\Models\ModeloEtiqueta;
use App\Models\RelacaoDadosFornecedor;
use App\Models\ContaPagar;
use App\Models\NaturezaOperacao;
use App\Models\Transportadora;
use App\Models\Empresa;
use App\Models\Contigencia;
use App\Models\FaturaNfe;
use App\Models\Cotacao;
use App\Models\ConfigGeral;
use App\Models\ProdutoUnico;
use App\Utils\EstoqueUtil;
use Illuminate\Support\Facades\DB;
use App\Models\ProdutoLocalizacao;
use App\Services\DFeService;
use NFePHP\NFe\Common\Standardize;
use NFePHP\DA\NFe\Danfe;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $util;

    public function __construct(EstoqueUtil $util)
    {
        $this->util = $util;
        $this->middleware('permission:compras_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:compras_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:compras_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:compras_delete', ['only' => ['destroy']]);
    }

    private function setNumeroSequencial(){
        $docs = Nfe::where('empresa_id', request()->empresa_id)
        ->where('numero_sequencial', null)
        ->get();

        $last = Nfe::where('empresa_id', request()->empresa_id)
        ->orderBy('numero_sequencial', 'desc')
        ->where('numero_sequencial', '>', 0)->first();
        $numero = $last != null ? $last->numero_sequencial : 0;
        $numero++;

        foreach($docs as $d){
            $d->numero_sequencial = $numero;
            $d->save();
            $numero++;
        }
    }

    private function getContigencia($empresa_id){
        $active = Contigencia::
        where('empresa_id', $empresa_id)
        ->where('status', 1)
        ->where('documento', 'NFe')
        ->first();
        return $active;
    }

    private function corrigeNumeros($empresa_id){
        $empresa = Empresa::findOrFail($empresa_id);
        if($empresa->ambiente == 1){
            $numero = $empresa->numero_ultima_nfe_producao;
        }else{
            $numero = $empresa->numero_ultima_nfe_homologacao;
        }
        
        if($numero){
            Nfe::where('estado', 'novo')
            ->where('empresa_id', $empresa_id)
            ->update(['numero' => $numero+1]);
        }
    }

    public function index(Request $request)
    {
        $locais = __getLocaisAtivoUsuario();
        $locais = $locais->pluck(['id']);
        $this->corrigeNumeros($request->empresa_id);

        $fornecedores = Fornecedor::where('empresa_id', request()->empresa_id)->get();

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $fornecedor_id = $request->get('fornecedor_id');
        $estado = $request->get('estado');
        $local_id = $request->get('local_id');

        $this->setNumeroSequencial();

        $data = Nfe::where('empresa_id', request()->empresa_id)
        ->where('tpNF', 0)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when(!empty($fornecedor_id), function ($query) use ($fornecedor_id) {
            return $query->where('fornecedor_id', $fornecedor_id);
        })
        ->when($estado != "", function ($query) use ($estado) {
            return $query->where('estado', $estado);
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(env("PAGINACAO"));

        $contigencia = $this->getContigencia($request->empresa_id);

        $base = Nfe::where('empresa_id', request()->empresa_id)->where('tpNF', 0);
        $stats = [
            'total'     => (clone $base)->count(),
            'aprovadas' => (clone $base)->where('estado', 'aprovado')->count(),
            'canceladas'=> (clone $base)->where('estado', 'cancelado')->count(),
            'valor'     => (clone $base)->where('estado', 'aprovado')->sum('total'),
        ];

        return view('compras.index', compact('data', 'fornecedores', 'contigencia', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }
        $sizeFornecedores = Fornecedor::where('empresa_id', request()->empresa_id)->count();
        if ($sizeFornecedores == 0) {
            session()->flash("flash_warning", "Primeiro cadastre um fornecedor!");
            return redirect()->route('fornecedores.create');
        }
        $produtos = Produto::where('empresa_id', request()->empresa_id)->get();
        if (sizeof($produtos) == 0) {
            session()->flash("flash_warning", "Primeiro cadastre um produto!");
            return redirect()->route('produtos.create');
        }
        $transportadoras = Transportadora::where('empresa_id', request()->empresa_id)->get();
        $cidades = Cidade::all();
        $naturezas = NaturezaOperacao::where('empresa_id', request()->empresa_id)->get();
        if (sizeof($naturezas) == 0) {
            session()->flash("flash_warning", "Primeiro cadastre um natureza de operação!");
            return redirect()->route('natureza-operacao.create');
        }
        $caixa = __isCaixaAberto();
        $empresa = Empresa::findOrFail(request()->empresa_id);
        $empresa = __objetoParaEmissao($empresa, $caixa->local_id);
        
        $numeroNfe = Nfe::lastNumero($empresa);

        $cotacao = null;
        if(isset($request->cotacao_id)){
            $cotacao = Cotacao::findOrfail($request->cotacao_id);
        }

        $isCompra = 1;
        return view(
            'nfe.create', compact('produtos', 'transportadoras', 'cidades', 'naturezas', 'numeroNfe', 'isCompra', 
                'cotacao', 'empresa', 'caixa')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function xml(Request $request)
    {
        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }

        $empresa = Empresa::findOrFail($request->empresa_id);

        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');
        $pesquisa   = $request->get('pesquisa');
        $status     = $request->get('status');

        $query = ManifestoDfe::where('empresa_id', $request->empresa_id)
            ->when(!empty($start_date), function ($q) use ($start_date) {
                return $q->whereDate('data_emissao', '>=', $start_date);
            })
            ->when(!empty($end_date), function ($q) use ($end_date) {
                return $q->whereDate('data_emissao', '<=', $end_date);
            })
            ->when(!empty($pesquisa), function ($q) use ($pesquisa) {
                $pesquisaLimpa = preg_replace('/[^0-9]/', '', $pesquisa);
                return $q->where(function ($sub) use ($pesquisa, $pesquisaLimpa) {
                    $sub->where('nome', 'like', "%{$pesquisa}%")
                        ->orWhere('chave', 'like', "%{$pesquisa}%");
                    if (!empty($pesquisaLimpa)) {
                        $sub->orWhere('documento', 'like', "%{$pesquisaLimpa}%");
                    }
                });
            })
            ->when($status === 'pendentes', function ($q) {
                return $q->where(function ($sub) {
                    $sub->whereNull('compra_id')->orWhere('compra_id', 0);
                });
            })
            ->when($status === 'importadas', function ($q) {
                return $q->where('compra_id', '>', 0);
            });

        $data = (clone $query)->orderBy('data_emissao', 'desc')->paginate(getenv("PAGINACAO") ?: 15);

        $statsQuery = ManifestoDfe::where('empresa_id', $request->empresa_id);
        $stats = [
            'total'      => (clone $statsQuery)->count(),
            'pendentes'  => (clone $statsQuery)->where(function ($q) {
                $q->whereNull('compra_id')->orWhere('compra_id', 0);
            })->count(),
            'importadas' => (clone $statsQuery)->where('compra_id', '>', 0)->count(),
            'valor'      => (clone $statsQuery)->sum('valor'),
        ];

        return view('compras.xml', compact('data', 'stats', 'empresa'));
    }

    public function consultarSefaz(Request $request)
    {
        $empresa = Empresa::findOrFail($request->empresa_id);

        if (!$empresa->arquivo || !$empresa->senha) {
            session()->flash('flash_error', 'Certificado Digital A1 não configurado ou senha inválida no cadastro da empresa!');
            return redirect()->back();
        }

        $cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);

        try {
            $dfe_service = new DFeService([
                "atualizacao" => date('Y-m-d H:i:s'),
                "tpAmb" => 1,
                "razaosocial" => $empresa->nome,
                "siglaUF" => $empresa->cidade ? $empresa->cidade->uf : 'CE',
                "cnpj" => $cnpj,
                "schemes" => "PL_009_V4",
                "versao" => "4.00",
                "tokenIBPT" => "AAAAAAA",
                "CSC" => $empresa->csc,
                "CSCid" => $empresa->csc_id
            ], $empresa);

            $ultimoManifesto = ManifestoDfe::where('empresa_id', $empresa->id)
                ->orderBy('nsu', 'desc')
                ->first();

            $nsu = $ultimoManifesto ? $ultimoManifesto->nsu : 0;
            $docs = $dfe_service->novaConsulta($nsu);

            if (is_array($docs) && !isset($docs['erro'])) {
                $novosCount = 0;
                foreach ($docs as $d) {
                    $existe = ManifestoDfe::where('empresa_id', $empresa->id)
                        ->where('chave', $d['chave'])
                        ->exists();

                    if (!$existe && isset($d['valor']) && $d['valor'] > 0 && !empty($d['nome'])) {
                        ManifestoDfe::create($d);
                        $novosCount++;
                    }
                }

                if ($novosCount > 0) {
                    session()->flash('flash_success', "Consulta realizada com sucesso! {$novosCount} nova(s) nota(s) encontrada(s) na SEFAZ.");
                } else {
                    session()->flash('flash_info', 'Consulta concluída: Nenhuma nova nota fiscal emitida recentemente para esta empresa na SEFAZ.');
                }
            } else {
                $msg = isset($docs['message']) ? $docs['message'] : 'Não foi possível obter retorno da SEFAZ no momento.';
                session()->flash('flash_warning', "Aviso da SEFAZ: {$msg}");
            }
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Erro ao consultar SEFAZ: ' . $e->getMessage());
        }

        return redirect()->route('compras.xml');
    }

    public function importarDfeXml($id)
    {
        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }

        $naturezaPadrao = NaturezaOperacao::where('empresa_id', request()->empresa_id)->first();
        if ($naturezaPadrao == null) {
            session()->flash('flash_error', 'Cadastre pelo menos uma natureza de operação antes de importar notas!');
            return redirect()->route('natureza-operacao.create');
        }

        $empresa = Empresa::findOrFail(request()->empresa_id);
        $dfe = ManifestoDfe::findOrFail($id);

        if ($dfe->compra_id > 0) {
            session()->flash('flash_warning', 'Esta nota fiscal já foi importada anteriormente!');
            return redirect()->route('compras.show', $dfe->compra_id);
        }

        $chave = $dfe->chave;
        $cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);

        if (!is_dir(public_path('xml_entrada'))) {
            mkdir(public_path('xml_entrada'), 0777, true);
        }
        if (!is_dir(public_path('xml_dfe'))) {
            mkdir(public_path('xml_dfe'), 0777, true);
        }

        $xmlPathEntrada = public_path("xml_entrada/{$chave}.xml");
        $xmlPathDfe = public_path("xml_dfe/{$chave}.xml");

        $xmlContent = null;

        if (file_exists($xmlPathEntrada)) {
            $xmlContent = file_get_contents($xmlPathEntrada);
        } elseif (file_exists($xmlPathDfe)) {
            $xmlContent = file_get_contents($xmlPathDfe);
            file_put_contents($xmlPathEntrada, $xmlContent);
        } else {
            // Tenta baixar da SEFAZ
            try {
                $dfe_service = new DFeService([
                    "atualizacao" => date('Y-m-d H:i:s'),
                    "tpAmb" => 1,
                    "razaosocial" => $empresa->nome,
                    "siglaUF" => $empresa->cidade ? $empresa->cidade->uf : 'CE',
                    "cnpj" => $cnpj,
                    "schemes" => "PL_009_V4",
                    "versao" => "4.00",
                    "tokenIBPT" => "AAAAAAA",
                    "CSC" => $empresa->csc,
                    "CSCid" => $empresa->csc_id
                ], $empresa);

                // Se ainda não tiver ciência (tipo = 0), manifesta ciência para liberar o download do XML completo
                if ($dfe->tipo == 0) {
                    $manifestaRes = $dfe_service->manifesta($chave, 1);
                    if (isset($manifestaRes['retEvento']['infEvento']['cStat']) && in_array($manifestaRes['retEvento']['infEvento']['cStat'], ['135', '136'])) {
                        $dfe->tipo = 1;
                        $dfe->sequencia_evento = 1;
                        $dfe->save();
                    }
                }

                $response = $dfe_service->download($chave);
                $stz = new Standardize($response);
                $std = $stz->toStd();

                if ($std != null && $std->cStat != 138) {
                    session()->flash("flash_error", "Documento não retornado pela SEFAZ. [$std->cStat] $std->xMotivo");
                    return redirect()->back();
                }

                if (isset($std->loteDistDFeInt->docZip)) {
                    $zip = $std->loteDistDFeInt->docZip;
                    $xmlContent = gzdecode(base64_decode($zip));
                    file_put_contents($xmlPathDfe, $xmlContent);
                    file_put_contents($xmlPathEntrada, $xmlContent);
                }
            } catch (\Exception $e) {
                session()->flash('flash_error', 'Erro ao baixar XML da SEFAZ: ' . $e->getMessage());
                return redirect()->back();
            }
        }

        if (!$xmlContent || strlen($xmlContent) < 100) {
            session()->flash('flash_error', 'Não foi possível obter o arquivo XML desta nota da SEFAZ. Aguarde alguns instantes e tente novamente.');
            return redirect()->back();
        }

        return $this->processarXmlString($xmlContent, $chave);
    }

    public function danfeDfe($id)
    {
        $dfe = ManifestoDfe::findOrFail($id);
        $empresa = Empresa::findOrFail(request()->empresa_id);
        $chave = $dfe->chave;

        $xmlPathEntrada = public_path("xml_entrada/{$chave}.xml");
        $xmlPathDfe = public_path("xml_dfe/{$chave}.xml");

        $xmlContent = null;
        if (file_exists($xmlPathEntrada)) {
            $xmlContent = file_get_contents($xmlPathEntrada);
        } elseif (file_exists($xmlPathDfe)) {
            $xmlContent = file_get_contents($xmlPathDfe);
        } else {
            $cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);
            $dfe_service = new DFeService([
                "atualizacao" => date('Y-m-d H:i:s'),
                "tpAmb" => 1,
                "razaosocial" => $empresa->nome,
                "siglaUF" => $empresa->cidade ? $empresa->cidade->uf : 'CE',
                "cnpj" => $cnpj,
                "schemes" => "PL_009_V4",
                "versao" => "4.00",
                "tokenIBPT" => "AAAAAAA",
                "CSC" => $empresa->csc,
                "CSCid" => $empresa->csc_id
            ], $empresa);

            try {
                $response = $dfe_service->download($chave);
                $stz = new Standardize($response);
                $std = $stz->toStd();
                if (isset($std->loteDistDFeInt->docZip)) {
                    $zip = $std->loteDistDFeInt->docZip;
                    $xmlContent = gzdecode(base64_decode($zip));
                    file_put_contents($xmlPathDfe, $xmlContent);
                    file_put_contents($xmlPathEntrada, $xmlContent);
                }
            } catch (\Exception $e) {
                session()->flash('flash_error', 'Erro ao buscar DANFE: ' . $e->getMessage());
                return redirect()->back();
            }
        }

        if (!$xmlContent) {
            session()->flash('flash_error', 'XML da nota ainda não disponível para impressão do DANFE.');
            return redirect()->back();
        }

        try {
            $danfe = new Danfe($xmlContent);
            $pdf = $danfe->render();
            return response($pdf)->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Erro ao renderizar DANFE: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function manifestarDfe(Request $request)
    {
        $empresa = Empresa::findOrFail($request->empresa_id);
        $cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);

        $dfe_service = new DFeService([
            "atualizacao" => date('Y-m-d H:i:s'),
            "tpAmb" => 1,
            "razaosocial" => $empresa->nome,
            "siglaUF" => $empresa->cidade ? $empresa->cidade->uf : 'CE',
            "cnpj" => $cnpj,
            "schemes" => "PL_009_V4",
            "versao" => "4.00",
            "tokenIBPT" => "AAAAAAA",
            "CSC" => $empresa->csc,
            "CSCid" => $empresa->csc_id
        ], $empresa);

        $evento = $request->tipo;
        $chave = $request->chave;
        $justificativa = $request->justificativa ?? '';

        $manifesto = ManifestoDfe::where('empresa_id', $request->empresa_id)
            ->where('chave', $chave)
            ->first();

        $numEvento = $manifesto ? ((int)$manifesto->sequencia_evento + 1) : 1;

        try {
            if ($evento == 1) {
                $res = $dfe_service->manifesta($chave, $numEvento);
            } else if ($evento == 2) {
                $res = $dfe_service->confirmacao($chave, $numEvento);
            } else if ($evento == 3) {
                $res = $dfe_service->desconhecimento($chave, $numEvento, $justificativa);
            } else if ($evento == 4) {
                $res = $dfe_service->operacaoNaoRealizada($chave, $numEvento, $justificativa);
            }

            if (isset($res['retEvento']['infEvento']['cStat']) && in_array($res['retEvento']['infEvento']['cStat'], ['135', '136'])) {
                if ($manifesto) {
                    $manifesto->sequencia_evento = $numEvento;
                    $manifesto->tipo = $evento;
                    $manifesto->save();
                }
                session()->flash('flash_success', $res['retEvento']['infEvento']['xMotivo']);
            } else {
                $motivo = $res['retEvento']['infEvento']['xMotivo'] ?? 'Não foi possível registrar o evento na SEFAZ.';
                session()->flash('flash_error', $motivo);
            }
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Erro ao manifestar: ' . $e->getMessage());
        }

        return redirect()->route('compras.xml');
    }

    public function storeXml(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file;
            $xml = simplexml_load_file($request->file);

            if ($xml->NFe->infNFe == null) {
                session()->flash('flash_error', 'Este XML parece inválido!');
                return redirect()->back();
            }

            $chave = substr($xml->NFe->infNFe->attributes()->Id, 3, 44);

            if (!is_dir(public_path('xml_entrada'))) {
                mkdir(public_path('xml_entrada'), 0777, true);
            }

            $file->move(public_path('xml_entrada'), $chave . ".xml");
            $xmlContent = file_get_contents(public_path('xml_entrada/') . $chave . ".xml");

            return $this->processarXmlString($xmlContent, $chave);
        } else {
            session()->flash('flash_error', 'Selecione um arquivo XML válido!');
            return redirect()->back();
        }
    }

    private function processarXmlString($xmlString, $chaveDfe = null)
    {
        $xml = simplexml_load_string($xmlString);

        if ($xml->NFe->infNFe == null) {
            session()->flash('flash_error', 'Este XML parece inválido!');
            return redirect()->back();
        }

        $chave = substr($xml->NFe->infNFe->attributes()->Id, 3, 44);

        if (!is_dir(public_path('xml_entrada'))) {
            mkdir(public_path('xml_entrada'), 0777, true);
        }

        file_put_contents(public_path('xml_entrada/') . $chave . ".xml", $xmlString);

        $cidade = Cidade::where('codigo', $xml->NFe->infNFe->emit->enderEmit->cMun)->first();

        $doc = $xml->NFe->infNFe->emit->CNPJ ? $xml->NFe->infNFe->emit->CNPJ : $xml->NFe->infNFe->emit->CPF;
        $doc = trim($doc);
        $mask = strlen($doc) == 11 ? '###.###.###-##' : '##.###.###/####-##';
        $doc = __mask($doc, $mask);

        $dataFornecedor = [
            'empresa_id'       => request()->empresa_id,
            'razao_social'     => (string)$xml->NFe->infNFe->emit->xNome,
            'nome_fantasia'    => (string)($xml->NFe->infNFe->emit->xFant ?: $xml->NFe->infNFe->emit->xNome),
            'cpf_cnpj'         => $doc,
            'ie'               => (string)$xml->NFe->infNFe->emit->IE,
            'contribuinte'     => $xml->NFe->infNFe->emit->IE != '' ? 1 : 0,
            'consumidor_final' => 0,
            'email'            => (string)($xml->NFe->infNFe->emit->enderEmit->xBairro ?? ''),
            'telefone'         => (string)($xml->NFe->infNFe->emit->enderEmit->fone ?? ''),
            'cidade_id'        => $cidade ? $cidade->id : null,
            'rua'              => (string)$xml->NFe->infNFe->emit->enderEmit->xLgr,
            'cep'              => (string)$xml->NFe->infNFe->emit->enderEmit->CEP,
            'numero'           => (string)$xml->NFe->infNFe->emit->enderEmit->nro,
            'bairro'           => (string)$xml->NFe->infNFe->emit->enderEmit->xBairro,
            'complemento'      => (string)($xml->NFe->infNFe->emit->enderEmit->xCpl ?? '')
        ];

        $fornecedor = $this->cadastraFornecedor($dataFornecedor);
        $vFrete = (float)$xml->NFe->infNFe->total->ICMSTot->vFrete;
        $vDesc = (float)$xml->NFe->infNFe->total->ICMSTot->vDesc;

        $itens = [];
        $contSemRegistro = 0;
        foreach ($xml->NFe->infNFe->det as $item) {
            $produto = Produto::verificaCadastrado(
                $item->prod->cEAN,
                $item->prod->xProd,
                $item->prod->cProd,
                request()->empresa_id
            );

            $vIpi = 0;
            $vICMSST = 0;
            if (isset($item->imposto->IPI)) {
                $valor = (float)($item->imposto->IPI->IPITrib->vIPI ?? 0);
                if ($valor > 0 && (float)$item->prod->qCom > 0)
                    $vIpi = $valor / (float)$item->prod->qCom;
            }

            if (isset($item->imposto->ICMS)) {
                $arr = (array_values((array)$item->imposto->ICMS));
                $cst = isset($arr[0]->CST) ? $arr[0]->CST : ($arr[0]->CSOSN ?? '');
                $valor = (float)($arr[0]->vICMSST ?? 0);
                if ($valor > 0 && (float)$item->prod->qCom > 0)
                    $vICMSST = $valor / (float)$item->prod->qCom;
            }

            $nomeProduto = str_replace("'", "", (string)$item->prod->xProd);
            $codigo = preg_replace('/[^0-9]/', '', (string)$item->prod->cProd);

            if ($produto == null) {
                $contSemRegistro++;
            }

            $prod = new \stdClass();
            $prod->id = $produto != null ? $produto->id : 0;
            $prod->codigo = $codigo;
            $prod->xProd = $produto == null ? $nomeProduto : $produto->nome;
            $prod->ncm = (string)$item->prod->NCM;
            $prod->cest = (string)($item->prod->CEST ?? '');
            $prod->cfop = (string)$item->prod->CFOP;
            $prod->unidade = (string)$item->prod->uCom;
            $prod->valor_unitario = number_format((float)$item->prod->vUnCom + $vIpi + $vICMSST, 2, '.', '');
            $prod->quantidade = (float)$item->prod->qCom;
            $prod->sub_total = $prod->valor_unitario * $prod->quantidade;
            $prod->codigo_barras = (string)($item->prod->cEAN ?? '');
            $prod->valor_venda = $produto == null ? 0 : $produto->valor_venda;
            $prod->valor_compra = $produto == null ? 0 : $produto->valor_compra;

            $arr = (array_values((array)$item->imposto->ICMS));
            $cst = (string)(isset($arr[0]->CST) ? $arr[0]->CST : ($arr[0]->CSOSN ?? ''));
            $pICMS = (float)($arr[0]->pICMS ?? 0);

            $prod->perc_red_bc = 0;
            $prod->perc_icms = $pICMS;
            $prod->cst_csosn = $cst;

            $arr = (array_values((array)$item->imposto->PIS));
            $prod->cst_pis = (string)($arr[0]->CST ?? '');
            $prod->perc_pis = (float)($arr[0]->pPIS ?? 0);

            $arr = (array_values((array)$item->imposto->COFINS));
            $prod->cst_cofins = (string)($arr[0]->CST ?? '');
            $prod->perc_cofins = (float)($arr[0]->pCOFINS ?? 0);

            $prod = $this->relacaoDadosFornecedor($prod);

            $arr = (array_values((array)$item->imposto->IPI));
            if (isset($arr[1])) {
                $cst_ipi = $arr[1]->CST ?? '99';
                $pIPI = $arr[0]->IPI ?? ($arr[0]->pIPI ?? 0);
                if (isset($arr[1]->pIPI)) {
                    $pIPI = $arr[1]->pIPI ?? 0;
                } elseif (isset($arr[4]->pIPI)) {
                    $pIPI = $arr[4]->pIPI;
                }
            } else {
                $cst_ipi = '99';
                $pIPI = 0;
            }

            $prod->perc_ipi = $pIPI;
            $prod->cst_ipi = $cst_ipi;
            $prod->codigo_beneficio_fiscal = '';

            array_push($itens, $prod);
        }

        $dadosXml = [
            'chave'           => $chave,
            'vProd'           => (float)$xml->NFe->infNFe->total->ICMSTot->vNF,
            'indPag'          => (int)($xml->NFe->infNFe->ide->indPag ?? 0),
            'nNf'             => (int)$xml->NFe->infNFe->ide->nNF,
            'vFrete'          => $vFrete,
            'vDesc'           => $vDesc,
            'contSemRegistro' => $contSemRegistro,
            'data_emissao'    => substr($xml->NFe->infNFe->ide->dhEmi[0], 0, 16),
            'itens'           => $itens,
            'chave_dfe'       => $chaveDfe
        ];

        $fatura = [];
        $tPag = !empty($xml->NFe->infNFe->pag->detPag) ? (string)$xml->NFe->infNFe->pag->detPag->tPag : null;

        if (!empty($xml->NFe->infNFe->cobr->dup)) {
            foreach ($xml->NFe->infNFe->cobr->dup as $dup) {
                $fatura[] = [
                    'numero'         => (int)$dup->nDup,
                    'vencimento'     => (string)$dup->dVenc,
                    'valor_parcela'  => number_format((float)$dup->vDup, 2, ".", ""),
                    'rand'           => rand(0, 10000),
                    'tipo_pagamento' => $tPag
                ];
            }
        } else {
            $vencimento = substr($xml->NFe->infNFe->ide->dhEmi[0], 0, 10);
            $fatura[] = [
                'numero'         => 1,
                'vencimento'     => $vencimento,
                'valor_parcela'  => (float)$xml->NFe->infNFe->total->ICMSTot->vProd,
                'rand'           => rand(0, 10000),
                'tipo_pagamento' => $tPag
            ];
        }

        $dadosXml['fatura'] = $fatura;

        $transportadoras = Transportadora::where('empresa_id', request()->empresa_id)->get();
        $cidades = Cidade::all();
        $naturezas = NaturezaOperacao::where('empresa_id', request()->empresa_id)->get();
        if (sizeof($naturezas) == 0) {
            session()->flash("flash_warning", "Primeiro cadastre uma natureza de operação!");
            return redirect()->route('natureza-operacao.create');
        }

        $caixa = __isCaixaAberto();
        $lucroPadraoProduto = 0;
        $configGeral = ConfigGeral::where('empresa_id', request()->empresa_id)->first();
        if ($configGeral != null) {
            $lucroPadraoProduto = $configGeral->percentual_lucro_produto;
        }

        $isCompra = 1;

        return view('compras.import_xml', compact(
            'dadosXml', 'transportadoras', 'cidades', 'naturezas',
            'fornecedor', 'caixa', 'lucroPadraoProduto', 'isCompra'
        ));
    }

    private function getCfopEntrada($cfop)
    {

        $digito = substr($cfop, 0, 1);
        if ($digito == '5') {
            return '1' . substr($cfop, 1, 4);
        } else {
            return '2' . substr($cfop, 1, 4);
        }
    }

    private function relacaoDadosFornecedor($prod){
        // dd($prod);
        $item = relacaoDadosFornecedor::where('cst_csosn_entrada', $prod->cst_csosn)
        ->where('cfop_entrada', $prod->cfop)
        ->where('empresa_id', request()->empresa_id)
        ->first();
        if($item == null){
            $item = relacaoDadosFornecedor::where('cst_csosn_entrada', $prod->cst_csosn)
            ->where('empresa_id', request()->empresa_id)
            ->first();
        }

        if($item == null){
            $item = relacaoDadosFornecedor::where('cfop_entrada', $prod->cfop)
            ->where('empresa_id', request()->empresa_id)
            ->first();
        }

        if($item != null){
            if($item->cst_csosn_saida){
                $prod->cst_csosn = $item->cst_csosn_saida;
            }
            if($item->cfop_saida){
                $prod->cfop = $item->cfop_saida;
            }
        }
        return $prod;
    }

    private function cadastraFornecedor($dataFornecedor)
    {
        $fornecedor = Fornecedor::where('cpf_cnpj', $dataFornecedor['cpf_cnpj'])
        ->where('empresa_id', request()->empresa_id)->first();

        if ($fornecedor == null) {
            $fornecedor = Fornecedor::create($dataFornecedor);
        }

        return $fornecedor;
    }

    /**
     * Cadastra um novo fornecedor a partir dos dados do formulário de importação XML.
     */
    private function cadastrarFornecedor($request)
    {
        $fornecedor = Fornecedor::create([
            'empresa_id' => $request->empresa_id,
            'razao_social' => $request->fornecedor_nome,
            'nome_fantasia' => $request->nome_fantasia,
            'cpf_cnpj' => $request->fornecedor_cpf_cnpj,
            'ie' => $request->ie,
            'contribuinte' => $request->contribuinte,
            'consumidor_final' => $request->consumidor_final,
            'email' => $request->email ?? '',
            'telefone' => $request->telefone ?? '',
            'cidade_id' => $request->fornecedor_cidade,
            'rua' => $request->fornecedor_rua,
            'cep' => $request->cep,
            'numero' => $request->fornecedor_numero,
            'bairro' => $request->fornecedor_bairro,
            'complemento' => $request->complemento ?? ''
        ]);
        return $fornecedor->id;
    }

    public function finishXml(Request $request)
    {
        try {

            $nfe = DB::transaction(function () use ($request) {

                $fornecedor_id = isset($request->fornecedor_id) ? $request->fornecedor_id : null;
                
                if (isset($request->fornecedor_id)) {
                    if ($request->fornecedor_id == null) {
                        $fornecedor_id = $this->cadastrarFornecedor($request);
                    } else {
                        $this->atualizaFornecedor($request);
                    }
                }

                $transportadora_id = $request->transportadora_id;
                if ($request->transportadora_id == null) {
                    $transportadora_id = $this->cadastrarTransportadora($request);
                } else {
                    $this->atualizaTransportadora($request);
                }
                $config = Empresa::find($request->empresa_id);

                $caixa = __isCaixaAberto();
                if ($caixa == null) {
                    throw new \Exception("Abrir caixa antes de continuar!");
                }

                $tipoPagamento = $request->tipo_pagamento;
                $request->merge([
                    'emissor_nome' => $config->nome,
                    'emissor_cpf_cnpj' => $config->cpf_cnpj,
                    'ambiente' => $config->ambiente,
                    'chave' => '',
                    'fornecedor_id' => $fornecedor_id,
                    'transportadora_id' => $transportadora_id,
                    'numero_serie' => $config->numero_serie_nfe ? $config->numero_serie_nfe : 1,
                    'numero' => $request->numero_nfe,
                    'chave_importada' => $request->chave_importada,
                    'estado' => 'novo',
                    'total' => __convert_value_bd($request->valor_total),
                    'desconto' => $request->desconto ? __convert_value_bd($request->desconto) : 0,
                    'acrescimo' => $request->acrescimo ? __convert_value_bd($request->acrescimo) : 0,
                    'valor_produtos' => __convert_value_bd($request->valor_produtos),
                    'valor_frete' => $request->valor_frete ? __convert_value_bd($request->valor_frete) : 0,
                    'caixa_id' => $caixa ? $caixa->id : null,
                    'local_id' => $caixa->local_id,
                    'tipo_pagamento' => isset($request->tipo_pagamento[0]) ? $request->tipo_pagamento[0] : null,
                    'user_id' => \Auth::user()->id
                ]);
                // dd($request->tipo_pagamento[]);
                $nfe = Nfe::create($request->all());
                for ($i = 0; $i < sizeof($request->produto_id); $i++) {
                    if ($request->produto_id[$i] == 0) {
                        //cadastrar produto
                        $product = $this->cadastrarProduto($request, $i, $caixa->local_id);
                    } else {
                        $product = Produto::findOrFail($request->produto_id[$i]);
                    }

                    $quantidade = __convert_value_bd($request->quantidade[$i]);
                    $quantidade = $quantidade * ((float)$request->conversao_estoque[$i] == 0 ? 1 : (float)$request->conversao_estoque[$i]);

                    $valorUnitario = __convert_value_bd($request->valor_unitario[$i]);
                    $valorUnitario = $valorUnitario / ((float)$request->conversao_estoque[$i] == 0 ? 1 : (float)$request->conversao_estoque[$i]);
                    ItemNfe::create([
                        'nfe_id' => $nfe->id,
                        'produto_id' => $product->id,
                        'quantidade' => $quantidade,
                        'valor_unitario' => __convert_value_bd($request->valor_unitario[$i]),
                        'sub_total' => __convert_value_bd($request->sub_total[$i]),
                        'perc_icms' => __convert_value_bd($request->perc_icms[$i]),
                        'perc_pis' => __convert_value_bd($request->perc_pis[$i]),
                        'perc_cofins' => __convert_value_bd($request->perc_cofins[$i]),
                        'perc_ipi' => __convert_value_bd($request->perc_ipi[$i]),
                        'cst_csosn' => $request->cst_csosn[$i],
                        'cst_pis' => $request->cst_pis[$i],
                        'cst_cofins' => $request->cst_cofins[$i],
                        'cst_ipi' => $request->cst_ipi[$i],
                        'perc_red_bc' => $request->perc_red_bc[$i] ? __convert_value_bd($request->perc_red_bc[$i]) : 0,
                        'cfop' => $request->cfop[$i],
                        'ncm' => $request->ncm[$i],
                        'codigo_beneficio_fiscal' => $request->codigo_beneficio_fiscal[$i]
                    ]);

                    ProdutoFornecedor::updateOrCreate([
                        'produto_id' => $product->id,
                        'fornecedor_id' => $fornecedor_id
                    ]);

                    $product->valor_compra = $valorUnitario;
                    $product->save();

                    if ($product->gerenciar_estoque) {
                        $this->util->incrementaEstoque($product->id, $quantidade, null);
                    }
                }

                if(isset($request->chave_dfe)){
                    $dfe = ManifestoDfe::where('chave', $request->chave_dfe)->first();
                    if($dfe){
                        $dfe->compra_id = $nfe->id;
                        $dfe->save();
                    }
                }

                if($tipoPagamento){

                    for ($i = 0; $i < sizeof($tipoPagamento); $i++) {
                        if ($tipoPagamento[$i]) {
                            FaturaNfe::create([
                                'nfe_id' => $nfe->id,
                                'tipo_pagamento' => $tipoPagamento[$i],
                                'data_vencimento' => $request->data_vencimento[$i],
                                'valor' => __convert_value_bd($request->valor_fatura[$i])
                            ]);
                        }

                        if ($request->gerar_conta_pagar) {
                            ContaPagar::create([
                                'empresa_id' => $request->empresa_id,
                                'nfe_id' => $nfe->id,
                                'fornecedor_id' => $fornecedor_id,
                                'valor_integral' => __convert_value_bd($request->valor_fatura[$i]),
                                'tipo_pagamento' => $tipoPagamento[$i],
                                'data_vencimento' => $request->data_vencimento[$i],
                                'local_id' => $caixa->local_id,
                            ]);
                        }
                    }
                }
                return $nfe;
            });
$descricaoLog = $nfe->fornecedor->info . " R$ " . __moeda($nfe->total);
__createLog($request->empresa_id, 'Importação XML', 'cadastrar', $descricaoLog);

session()->flash("flash_success", "Importação cadastrada!");
} catch (\Exception $e) {
    // echo $e->getMessage() . '<br>' . $e->getLine();
    // die;
    __createLog(request()->empresa_id, 'Importação XML', 'erro', $e->getMessage());
    session()->flash("flash_error", 'Algo deu errado ' . $e->getMessage());
}

return redirect()->route('compras.index');
}

private function cadastrarProduto($request, $i, $local_id)
{
        // dd($request->all());
    $cfop = $request->cfop[$i];
    $cfopOutroEstado = '';
    $cfopEstado = '';
    $digito = substr($cfop, 0, 1);

    $cfopEstado = '5' . substr($cfop, 1, 4);
    $cfopOutroEstado = '6' . substr($cfop, 1, 4);

    $p = Produto::create([
        'empresa_id' => $request->empresa_id,
        'nome' => $request->nome_produto[$i],
        'ncm' => $request->ncm[$i],
        'codigo_barras' => $request->codigo_barras[$i],
        'gerenciar_estoque' => $request->gerenciar_estoque,
        'unidade' => $request->unidade[$i],
        'valor_unitario' => __convert_value_bd($request->valor_venda[$i]),
        'perc_red_bc' => __convert_value_bd($request->perc_red_bc[$i]),
        'cfop_estadual' => $cfopEstado,
        'cest' => $request->cest[$i],
        'cfop_outro_estado' => $cfopOutroEstado,
        'valor_compra' => __convert_value_bd($request->valor_unitario[$i]),

        'perc_red_bc' => __convert_value_bd($request->perc_red_bc[$i]),
        'cst_csosn' => $request->cst_csosn[$i],
        'cst_pis' => $request->cst_pis[$i],
        'cst_cofins' => $request->cst_cofins[$i],
        'cst_ipi' => $request->cst_ipi[$i],

        'perc_icms' => __convert_value_bd($request->perc_icms[$i]),
        'perc_pis' => __convert_value_bd($request->perc_pis[$i]),
        'perc_cofins' => __convert_value_bd($request->perc_cofins[$i]),
        'perc_ipi' => __convert_value_bd($request->perc_ipi[$i]),

    ]);

    ProdutoLocalizacao::updateOrCreate([
        'produto_id' => $p->id, 
        'localizacao_id' => $local_id
    ]);
    return $p;
}

private function cadastrarTransportadora($request)
{
    if ($request->razao_social_transp) {
        $transportadora = Transportadora::create([
            'empresa_id' => $request->empresa_id,
            'razao_social' => $request->razao_social_transp,
            'nome_fantasia' => $request->nome_fantasia_transp,
            'cpf_cnpj' => $request->cpf_cnpj_transp,
            'ie' => $request->ie_transp,
            'antt' => $request->antt,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'cidade_id' => $request->cidade_id,
            'rua' => $request->rua_transp,
            'cep' => $request->cep_transp,
            'numero' => $request->numero_transp,
            'bairro' => $request->bairro_transp,
            'complemento' => $request->complemento_transp
        ]);
        return $transportadora->id;
    }
    return null;
}

private function atualizaTransportadora($request)
{
    if ($request->razao_social_transp) {
        $transportadora = Transportadora::findOrFail($request->transportadora_id);
        $transportadora->update([
            'empresa_id' => $request->empresa_id,
            'razao_social' => $request->razao_social_transp,
            'nome_fantasia' => $request->nome_fantasia_transp ?? '',
            'cpf_cnpj' => $request->cpf_cnpj_transp,
            'ie' => $request->ie_transp,
            'antt' => $request->antt,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'cidade_id' => $request->cidade_id,
            'rua' => $request->rua_transp,
            'cep' => $request->cep_transp,
            'numero' => $request->numero_transp,
            'bairro' => $request->bairro_transp,
            'complemento' => $request->complemento_transp
        ]);
        return $transportadora->id;
    }
    return null;
}

private function atualizaFornecedor($request)
{
    $fornecedor = Fornecedor::findOrFail($request->fornecedor_id);
    $fornecedor->update([
        'razao_social' => $request->fornecedor_nome,
        'nome_fantasia' => $request->nome_fantasia,
        'cpf_cnpj' => $request->fornecedor_cpf_cnpj,
        'ie' => $request->ie,
        'contribuinte' => $request->contribuinte,
        'consumidor_final' => $request->consumidor_final,
        'email' => $request->email ?? '',
        'telefone' => $request->telefone ?? '',
        'cidade_id' => $request->fornecedor_cidade,
        'rua' => $request->fornecedor_rua,
        'cep' => $request->cep,
        'numero' => $request->fornecedor_numero,
        'bairro' => $request->fornecedor_bairro,
        'complemento' => $request->complemento
    ]);
    return $fornecedor->id;
}

public function infoValidade($id)
{
    $compra = Nfe::findOrFail($id);
    $produtos = [];
    foreach ($compra->itens as $i) {
        if ($i->produto->alerta_validade) {
            array_push($produtos, $i);
        }
    }
    return view('compras.info_validade', compact('produtos', 'compra'));
}

public function setarInfoValidade(Request $request)
{
    for ($i = 0; $i < sizeof($request->produto_id); $i++) {

        $item = ItemNfe::findOrFail($request->produto_id[$i]);
        $item->lote = $request->lote[$i];
        $item->data_vencimento = $request->data_vencimento[$i];
        $item->save();
    }
    session()->flash('flash_success', 'Validade definada com sucesso!');
    return redirect()->route('compras.index');
}

public function setCodigoUnico($id)
{
    $compra = Nfe::findOrFail($id);
    $produtos = [];
    foreach ($compra->itens as $i) {
        if ($i->produto->tipo_unico) {
            for ($x=0; $x<$i->quantidade; $x++) {
                array_push($produtos, $i);
            }
        }
    }
    return view('compras.set_codigo_unico', compact('produtos', 'compra'));
}

public function setarCodigoUnico(Request $request)
{
    for ($i = 0; $i < sizeof($request->produto_id); $i++) {

        ProdutoUnico::create([
            'nfe_id' => $request->nfe_id,
            'nfce_id' => null,
            'produto_id' => $request->produto_id[$i],
            'codigo' => $request->codigo[$i],
            'observacao' => $request->observacao[$i] ?? '',
            'tipo' => 'entrada',
            'em_estoque' => 1
        ]);
    }
    session()->flash('flash_success', 'Dados definidos com sucesso!');
    return redirect()->route('compras.index');
}

public function show($id){
    $data = Nfe::findOrFail($id);
    __validaObjetoEmpresa($data);

    return view('nfe.show', compact('data'));
}

public function etiqueta($id){
    $item = Nfe::findOrFail($id);
    __validaObjetoEmpresa($item);
    
    $modelos = ModeloEtiqueta::where('empresa_id', $item->empresa_id)->get();
    return view('compras.etiqueta', compact('item', 'modelos'));

}

public function etiquetaStore(Request $request, $id){
    if (!is_dir(public_path('barcode'))) {
        mkdir(public_path('barcode'), 0777, true);
    }
    $files = glob(public_path("barcode/*")); 

    foreach($files as $file){ 
        if(is_file($file)) {
            unlink($file); 
        }
    }
    if (!$request->has('produto') || empty($request->produto)) {
        session()->flash('flash_error', 'Selecione pelo menos um produto para gerar as etiquetas.');
        return redirect()->back();
    }

    $selecionados = [];
    foreach ($request->produto as $prod_id) {
        $selecionados[] = $prod_id;
    }

    $item = Nfe::findOrFail($id);
    $data = [];
    $cont = 0;
    foreach($item->itens as $i){
        if(in_array($i->produto_id, $selecionados)){
            $nome = $i->produto->nome;
            $codigo = $i->produto->codigo_barras;
            $valor = $i->valor_unitario;
            $unidade = $i->produto->unidade;

            if($codigo == "" || $codigo == "SEM GTIN" || $codigo == "sem gtin"){
                session()->flash('flash_error', "Produto $nome sem código de barras definido");
                return redirect()->back();
            }

            $rand = rand(1000, 9999);
            // Quantidade: usa o que o usuário digitou no formulário (por produto), caso exista; senão usa a da NF
            $qtd_key = 'qtd_produto_' . $i->produto_id;
            $qtd = ($request->has($qtd_key) && (int)$request->$qtd_key > 0)
                ? (int)$request->$qtd_key
                : (int)$i->quantidade;

            $obj = [
                'nome_empresa'          => $request->nome_empresa ? true : false,
                'nome_produto'          => $request->nome_produto ? true : false,
                'valor_produto'         => $request->valor_produto ? true : false,
                'cod_produto'           => $request->codigo_produto ? true : false,
                'tipo'                  => $request->tipo,
                'codigo_barras_numerico'=> $request->codigo_barras_numerico ? true : false,
                'nome'                  => $nome,
                'codigo'                => $i->produto->codigo ?? $i->produto_id,
                'codigo_barras'         => $codigo,
                'valor'                 => $valor,
                'unidade'               => $unidade,
                'rand'                  => $rand,
                'empresa'               => $item->empresa->nome
            ];

            $generatorPNG = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $bar_code = $generatorPNG->getBarcode($codigo, $generatorPNG::TYPE_EAN_13);
            file_put_contents(public_path("barcode")."/$rand.png", $bar_code);

            for($k = 0; $k < $qtd; $k++){
                array_push($data, $obj);
            }
        }
    }


    $quantidade_por_linhas = $request->etiquestas_por_linha;
    $quantidade = $request->quantidade_etiquetas;
    $altura = $request->altura;
    $largura = $request->largura;
    $distancia_topo = $request->distancia_etiquetas_topo;
    $distancia_lateral = $request->distancia_etiquetas_lateral;
    $tamanho_fonte = $request->tamanho_fonte;
    $tamanho_codigo = $request->tamanho_codigo_barras;
    return view('compras.etiqueta_print', compact('altura', 'largura', 'quantidade', 'distancia_topo',
        'distancia_lateral', 'quantidade_por_linhas', 'tamanho_fonte', 'tamanho_codigo', 'data'));


}

}
