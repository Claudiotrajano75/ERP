<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Ciot;
use App\Models\CTeDescarga;
use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\InfoDescarga;
use App\Models\LacreTransporte;
use App\Models\LacreUnidadeCarga;
use App\Models\Mdfe;
use App\Models\MunicipioCarregamento;
use App\Models\Nfe;
use App\Models\NFeDescarga;
use App\Models\Percurso;
use App\Models\UnidadeCarga;
use App\Models\ValePedagio;
use App\Models\Veiculo;
use App\Services\MdfeAutoFillService;
use App\Services\MDFeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use NFePHP\DA\MDFe\Daevento;
use NFePHP\DA\MDFe\Damdfe;
use Symfony\Polyfill\Intl\Idn\Info;

class MdfeController extends Controller
{
    public function __construct()
    {
        if (!is_dir(public_path('xml_mdfe'))) {
            mkdir(public_path('xml_mdfe'), 0777, true);
        }
        if (!is_dir(public_path('xml_mdfe_cancelada'))) {
            mkdir(public_path('xml_mdfe_cancelada'), 0777, true);
        }
        if (!is_dir(public_path('xml_mdfe_correcao'))) {
            mkdir(public_path('xml_mdfe_correcao'), 0777, true);
        }

        $this->middleware('permission:mdfe_create', ['only' => ['create', 'store', 'importarXml']]);
        $this->middleware('permission:mdfe_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:mdfe_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:mdfe_delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locais = __getLocaisAtivoUsuario();
        $locais = $locais->pluck(['id']);

        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');
        $estado     = $request->get('estado');
        $local_id   = $request->get('local_id');

        // Query base (com filtro de estado para a listagem)
        $baseQuery = Mdfe::where('empresa_id', request()->empresa_id)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when($estado != "", function ($query) use ($estado) {
            if ($estado == 'encerrado') {
                return $query->where('encerrado', 1);
            }
            return $query->where('estado_emissao', $estado);
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->where(function ($q) use ($locais) {
                $q->whereIn('local_id', $locais)
                  ->orWhereNull('local_id');
            });
        });

        // Query stats (sem filtro de estado para mostrar totais por categoria)
        $statsQuery = Mdfe::where('empresa_id', request()->empresa_id)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->where(function ($q) use ($locais) {
                $q->whereIn('local_id', $locais)
                  ->orWhereNull('local_id');
            });
        });

        $stats = [
            'total'      => (clone $statsQuery)->count(),
            'aprovadas'  => (clone $statsQuery)->where('estado_emissao', 'aprovado')->count(),
            'encerrados' => (clone $statsQuery)->where('encerrado', 1)->count(),
            'canceladas' => (clone $statsQuery)->where('estado_emissao', 'cancelado')->count(),
            'valor'      => (clone $statsQuery)->where('estado_emissao', 'aprovado')->sum('valor_carga'),
        ];

        $data = (clone $baseQuery)->with(['veiculoTracao', 'localizacao', 'percurso'])->orderBy('created_at', 'desc')->paginate(env("PAGINACAO"));
        $cidades = Cidade::all();

        return view('mdfe.index', compact('data', 'stats', 'cidades'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $veiculos = Veiculo::with('funcionario')->where('empresa_id', request()->empresa_id)
        ->where('status', 1)->get();
        $funcionarios = Funcionario::where('empresa_id', request()->empresa_id)
        ->where('status', 1)->get();
        $cidades = Cidade::all();
        $empresa = Empresa::findOrFail(request()->empresa_id);

        $numeroMDFe = Mdfe::lastNumero($empresa);

        return view('mdfe.create', compact('veiculos', 'funcionarios', 'cidades', 'numeroMDFe', 'empresa'));
    }

    /**
     * Lê o XML da NF-e enviado no upload e devolve os dados para pré-preencher a emissão.
     */
    public function importarXml(Request $request)
    {
        $request->validate([
            'xml' => 'required|array',
            'xml.*' => 'file|max:2048',
        ]);

        try {
            $dados = (new MdfeAutoFillService)->fromUploadedFiles($request->file('xml'));

            __createLog($request->empresa_id, 'MDFe', 'importar xml', $dados['notas'] . ' NF-e lida(s) para pré-preencher a emissão');

            return response()->json($dados, 200);
        } catch (\Exception $e) {
            __createLog(request()->empresa_id, 'MDFe', 'erro', 'Importação de XML: ' . $e->getMessage());

            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    /**
     * Grava os relacionamentos da MDF-e: municípios, CIOT, percurso, vales de pedágio e descarregamento.
     */
    private function sincronizaRelacionamentos(Mdfe $mdfe, Request $request)
    {
        $mdfe->municipiosCarregamento()->delete();
        $mdfe->ciots()->delete();
        $mdfe->percurso()->delete();
        $mdfe->valesPedagio()->delete();
        $mdfe->infoDescarga()->delete();

        $municipiosCarregamento = (array) $request->municipiosCarregamento;
        if (empty(array_filter($municipiosCarregamento))) {
            $empresa = Empresa::find($mdfe->empresa_id);
            if ($empresa && $empresa->cidade_id) {
                $municipiosCarregamento = [$empresa->cidade_id];
            }
        }

        foreach ($municipiosCarregamento as $municipioCarregamento) {
            if (!$municipioCarregamento) {
                continue;
            }
            MunicipioCarregamento::create([
                'mdfe_id' => $mdfe->id,
                'cidade_id' => $municipioCarregamento
            ]);
        }

        foreach ((array) $request->codigo_ciot as $i => $codigoCiot) {
            if (!$codigoCiot) {
                continue;
            }
            Ciot::create([
                'mdfe_id' => $mdfe->id,
                'cpf_cnpj' => $request->cpf_cnpj[$i] ?? '',
                'codigo' => $codigoCiot
            ]);
        }

        foreach ((array) $request->uf as $ufPercurso) {
            if (!$ufPercurso) {
                continue;
            }
            Percurso::create([
                'uf' => $ufPercurso,
                'mdfe_id' => $mdfe->id
            ]);
        }

        foreach ((array) $request->cnpj_fornecedor as $i => $cnpjFornecedor) {
            if (!$cnpjFornecedor) {
                continue;
            }
            ValePedagio::create([
                'mdfe_id' => $mdfe->id,
                'cnpj_fornecedor' => $cnpjFornecedor,
                'cnpj_fornecedor_pagador' => $request->cnpj_fornecedor_pagador[$i] ?? '',
                'numero_compra' => $request->numero_compra[$i] ?? 0,
                'valor' => __convert_value_bd($request->valor_pedagio[$i] ?? 0)
            ]);
        }

        foreach ((array) $request->tp_und_transp_row as $i => $tpUnidTransp) {
            if (!$tpUnidTransp || !isset($request->municipio_descarregamento_row[$i])) {
                continue;
            }

            $info = InfoDescarga::create([
                'mdfe_id' => $mdfe->id,
                'tp_unid_transp' => $tpUnidTransp,
                'id_unid_transp' => $request->id_und_transp_row[$i] ?? '',
                'quantidade_rateio' => __convert_value_bd($request->quantidade_rateio_row[$i] ?? 0),
                'cidade_id' => $request->municipio_descarregamento_row[$i]
            ]);

            if ($request->chave_cte_row[$i] ?? null) {
                CTeDescarga::create([
                    'info_id' => $info->id,
                    'chave' => $request->chave_cte_row[$i],
                    'seg_cod_barras' => ''
                ]);
            }

            if ($request->chave_nfe_row[$i] ?? null) {
                NFeDescarga::create([
                    'info_id' => $info->id,
                    'chave' => $request->chave_nfe_row[$i],
                    'seg_cod_barras' => ''
                ]);
            }

            foreach ((array) json_decode($request->lacres_transporte_row[$i] ?? '[]') as $lacre) {
                if ($lacre == '') {
                    continue;
                }
                LacreTransporte::create([
                    'info_id' => $info->id,
                    'numero' => $lacre
                ]);
            }

            foreach ((array) json_decode($request->lacres_unidade_row[$i] ?? '[]') as $lacre) {
                if ($lacre == '') {
                    continue;
                }
                LacreUnidadeCarga::create([
                    'info_id' => $info->id,
                    'numero' => $lacre
                ]);
            }

            if (($request->quantidade_rateio_carga_row[$i] ?? "") != "") {
                UnidadeCarga::create([
                    'info_id' => $info->id,
                    'id_unidade_carga' => $request->id_und_transp_row[$i] ?? '',
                    'quantidade_rateio' => __convert_value_bd($request->quantidade_rateio_carga_row[$i])
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $this->_validate($request);
        try {
            DB::transaction(function () use ($request) {
                $request->merge([
                    'cnpj_contratante' => $request->cnpj_contratante ?? '',
                    'carga_posterior' => $request->carga_posterior ? 1 : 0,
                    'lac_rodo' => $request->lac_rodo ?? '0',
                    'seguradora_nome' => $request->seguradora_nome ?? '',
                    'seguradora_cnpj' => $request->seguradora_cnpj ?? '',
                    'numero_apolice' => $request->numero_apolice ?? '',
                    'numero_averbacao' => $request->numero_averbacao ?? '',
                    'numero_compra' => $request->numero_compra ?? 0,
                    'valor' => $request->valor ?? 0,
                    'encerrado' => false,
                    'estado_emissao' => 'novo',
                    'chave' => '',
                    'seg_cod_barras' => '',
                    'protocolo' => '',
                    'valor_carga' => __convert_value_bd($request->valor_carga),
                    'latitude_carregamento' => $request->latitude_carregamento ?? '',
                    'longitude_carregamento' => $request->longitude_carregamento ?? '',
                    'cep_descarrega' => $request->cep_descarrega ?? '',
                    'latitude_descarregamento' => $request->latitude_descarregamento ?? '',
                    'longitude_descarregamento' => $request->longitude_descarregamento ?? '',
                    'quantidade_rateio' => __convert_value_bd($request->quantidade_rateio),
                    'quantidade_rateio_carga' => __convert_value_bd($request->quantidade_rateio_carga),
                    'quantidade_carga' => __convert_value_bd($request->quantidade_carga),
                    'unidade_medida' => $request->unidade_medida ?? 'KG',
                    'tp_emit' => $request->tp_emit ?? ($request->tp_transp == '2' ? '1' : '2'),
                    'tp_transp' => $request->tp_transp ?? 1,
                    'tipo_modal' => $request->modal_tipo ?? $request->tipo_modal ?? '1',
                    'produto_pred_nome' => $request->produto_pred_nome ?? '',
                    'produto_pred_ncm' => preg_replace('/[^0-9]/', '', $request->produto_pred_ncm ?? ''),
                    'produto_pred_cod_barras' => $request->produto_pred_cod_barras ?? '',
                    'cep_carrega' => $request->cep_carrega ?? '',
                    'tp_carga' => $request->tp_carga ?? '05',
                    'condutor_nome' => $request->condutor_nome ?? '',
                    'condutor_cpf' => $request->condutor_cpf ?? '',
                    'info_complementar' => $request->info_complementar ?? '',
                    'info_adicional_fisco' => $request->info_adicional_fisco ?? '',
                    'local_id' => $request->local_id ? $request->local_id : ($request->filial_id != -1 && $request->filial_id ? $request->filial_id : (__getLocalAtivo() ? __getLocalAtivo()->id : null))
                ]);

                $mdfe = Mdfe::create($request->all());

                $this->sincronizaRelacionamentos($mdfe, $request);

                $descricaoLog = "Número: $mdfe->mdfe_numero - R$ " . __moeda($mdfe->valor_carga);
                __createLog($request->empresa_id, 'MDFe', 'cadastrar', $descricaoLog);
            });
            session()->flash("flash_success", "MDF-e criada com sucesso!");
        } catch (\Exception $e) {
            __createLog(request()->empresa_id, 'MDFe', 'erro', $e->getMessage());
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('mdfe.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Mdfe::with([
            'infoDescarga.nfe',
            'infoDescarga.cte',
            'infoDescarga.cidade',
            'municipiosCarregamento.cidade',
            'percurso',
            'veiculoTracao.funcionario',
            'veiculoReboque'
        ])->findOrFail($id);

        $veiculos = Veiculo::with('funcionario')->where('empresa_id', request()->empresa_id)
        ->where('status', 1)->get();
        $funcionarios = Funcionario::where('empresa_id', request()->empresa_id)
        ->where('status', 1)->get();
        $cidades = Cidade::all();
        $empresa = Empresa::findOrFail(request()->empresa_id);
        return view('mdfe.edit', compact('item', 'veiculos', 'funcionarios', 'cidades', 'empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        // $this->_validate($request);
        $item = Mdfe::findOrFail($id);
        try {
            $request->merge([
                'cnpj_contratante' => $request->cnpj_contratante ?? '',
                'carga_posterior' => $request->carga_posterior ? 1 : 0,
                'lac_rodo' => $request->lac_rodo ?? '0',
                'seguradora_nome' => $request->seguradora_nome ?? '',
                'seguradora_cnpj' => $request->seguradora_cnpj ?? '',
                'numero_apolice' => $request->numero_apolice ?? '',
                'numero_averbacao' => $request->numero_averbacao ?? '',
                'numero_compra' => $request->numero_compra ?? 0,
                'chave' => $request->chave ?? $item->chave ?? '',
                'seg_cod_barras' => $request->seg_cod_barras ?? $item->seg_cod_barras ?? '',
                'protocolo' => $request->protocolo ?? $item->protocolo ?? '',
                'valor_carga' => __convert_value_bd($request->valor_carga),
                'latitude_carregamento' => $request->latitude_carregamento ?? '',
                'longitude_carregamento' => $request->longitude_carregamento ?? '',
                'cep_descarrega' => $request->cep_descarrega ?? '',
                'latitude_descarregamento' => $request->latitude_descarregamento ?? '',
                'longitude_descarregamento' => $request->longitude_descarregamento ?? '',
                'quantidade_rateio' => __convert_value_bd($request->quantidade_rateio),
                'quantidade_rateio_carga' => __convert_value_bd($request->quantidade_rateio_carga),
                'quantidade_carga' => __convert_value_bd($request->quantidade_carga),
                'unidade_medida' => $request->unidade_medida ?? 'KG',
                'tp_emit' => $request->tp_emit ?? ($request->tp_transp == '2' ? '1' : '2'),
                'tp_transp' => $request->tp_transp ?? 1,
                'tipo_modal' => $request->modal_tipo ?? $request->tipo_modal ?? '1',
                'produto_pred_nome' => $request->produto_pred_nome ?? '',
                'produto_pred_ncm' => preg_replace('/[^0-9]/', '', $request->produto_pred_ncm ?? ''),
                'produto_pred_cod_barras' => $request->produto_pred_cod_barras ?? '',
                'cep_carrega' => $request->cep_carrega ?? '',
                'tp_carga' => $request->tp_carga ?? '05',
                'condutor_nome' => $request->condutor_nome ?? '',
                'condutor_cpf' => $request->condutor_cpf ?? '',
                'info_complementar' => $request->info_complementar ?? '',
                'info_adicional_fisco' => $request->info_adicional_fisco ?? '',
                'local_id' => $request->local_id ? $request->local_id : ($request->filial_id != -1 && $request->filial_id ? $request->filial_id : $item->local_id)
            ]);
            $item->fill($request->all())->save();

            $this->sincronizaRelacionamentos($item, $request);

            $descricaoLog = "Número: $item->mdfe_numero - R$ " . __moeda($item->valor_carga);
            __createLog($request->empresa_id, 'MDFe', 'editar', $descricaoLog);
            session()->flash("flash_success", "MDF-e atualizada com sucesso!");
        } catch (\Exception $e) {
            __createLog(request()->empresa_id, 'MDFe', 'erro', $e->getMessage());
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('mdfe.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Mdfe::findOrFail($id);
        try {
            $descricaoLog = "Número: $item->mdfe_numero - R$ " . __moeda($item->valor_carga);

            $item->municipiosCarregamento()->delete();
            $item->ciots()->delete();
            $item->percurso()->delete();
            $item->valesPedagio()->delete();
            $item->infoDescarga()->delete();

            $item->delete();
            __createLog(request()->empresa_id, 'MDFe', 'excluir', $descricaoLog);

            session()->flash("flash_success", "MDFe removida!");
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // die;
            __createLog(request()->empresa_id, 'MDFe', 'erro', $e->getMessage());
            session()->flash("flash_error", 'Algo deu errado.', $e->getMessage());
        }
        return redirect()->route('mdfe.index');
    }

    public function xmlTemp($id)
    {
        $item = Mdfe::findOrFail($id);

        $config = Empresa::where('id', request()->empresa_id)
        ->first();

        $config = __objetoParaEmissao($config, $item->local_id);
        // dd($config);
        $cnpj = preg_replace('/[^0-9]/', '', $config->cpf_cnpj);

        $mdfe_service = new MDFeService([
            "atualizacao" => date('Y-m-d h:i:s'),
            "tpAmb" => (int)$config->ambiente,
            "razaosocial" => $config->nome,
            "siglaUF" => $config->cidade->uf,
            "cnpj" => $cnpj,
            "inscricaomunicipal" => $config->inscricao_municipal,
            "codigomunicipio" => $config->cidade->codigo,
            "schemes" => "PL_MDFe_300a",
            "versao" => '3.00'
        ], $config);
        $mdfe = $mdfe_service->gerar($item);
        if (!isset($mdfe['erros_xml'])) {

            $xml = $mdfe['xml'];
            return response($xml)
            ->header('Content-Type', 'application/xml');
        } else {

            foreach ($mdfe['erros_xml'] as $err) {
                echo $err;
            }
        }
    }

    public function naoEncerrados()
    {
        $config = Empresa::where('id', request()->empresa_id)
        ->first();

        if ($config->arquivo == null) {
            session()->flash("flash_erro", "Configure o certificado!");
            return redirect()->back();
        }

        $cnpj = preg_replace('/[^0-9]/', '', $config->cpf_cnpj);

        $mdfe_service = new MDFeService([
            "atualizacao" => date('Y-m-d H:i:s'),
            "tpAmb" => (int)$config->ambiente,
            "razaosocial" => $config->nome,
            "siglaUF" => $config->cidade->uf,
            "cnpj" => $cnpj,
            "inscricaomunicipal" => $config->inscricao_municipal,
            "codigomunicipio" => $config->cidade->codigo,
            "schemes" => "PL_MDFe_300a",
            "versao" => '3.00'
        ], $config);
        $resultados = $mdfe_service->naoEncerrados();
        $naoEncerrados = [];

        if (is_array($resultados) && isset($resultados['xMotivo']) && $resultados['xMotivo'] != 'Consulta não encerrados não localizou MDF-e nessa situação') {
            if (isset($resultados['infMDFe'])) {
                if (!isset($resultados['infMDFe'][0])) {
                    $array = [
                        'chave' => $resultados['infMDFe']['chMDFe'] ?? '',
                        'protocolo' => $resultados['infMDFe']['nProt'] ?? '',
                        'numero' => 0,
                        'data' => '',
                        'local' => ''
                    ];
                    if ($array['chave']) {
                        array_push($naoEncerrados, $array);
                    }
                } else {
                    foreach ($resultados['infMDFe'] as $inf) {
                        $array = [
                            'chave' => $inf['chMDFe'] ?? '',
                            'protocolo' => $inf['nProt'] ?? '',
                            'numero' => 0,
                            'data' => '',
                            'local' => ''
                        ];
                        if ($array['chave']) {
                            array_push($naoEncerrados, $array);
                        }
                    }
                }
            }
        }
        $data = $this->percorreDatabaseNaoEncerrados($naoEncerrados);
        $cidades = Cidade::all();
        return view('mdfe.nao_encerrados', compact('data', 'cidades'));
    }

    private function percorreDatabaseNaoEncerrados($naoEncerrados)
    {
        for ($aux = 0; $aux < count($naoEncerrados); $aux++) {
            $mdfe = Mdfe::where('chave', $naoEncerrados[$aux]['chave'])
            ->where('empresa_id', request()->empresa_id)
            ->first();

            if ($mdfe != null) {
                $naoEncerrados[$aux]['data'] = $mdfe->created_at;
                $naoEncerrados[$aux]['numero'] = $mdfe->mdfe_numero;
                $naoEncerrados[$aux]['local'] = $mdfe->localizacao ? $mdfe->localizacao->descricao : 'Matriz';
            }
        }
        return $naoEncerrados;
    }

    public function encerrar(Request $request)
    {
        $config = Empresa::where('id', request()->empresa_id)
        ->first();
        $cnpj = preg_replace('/[^0-9]/', '', $config->cpf_cnpj);
        $mdfe_service = new MDFeService([
            "atualizacao" => date('Y-m-d H:i:s'),
            "tpAmb" => (int)$config->ambiente,
            "razaosocial" => $config->nome,
            "siglaUF" => $config->cidade->uf,
            "cnpj" => $cnpj,
            "inscricaomunicipal" => $config->inscricao_municipal,
            "codigomunicipio" => $config->cidade->codigo,
            "schemes" => "PL_MDFe_300a",
            "versao" => '3.00'
        ], $config);

        $mdfe = Mdfe::where('chave', $request->chave)
        ->where('empresa_id', request()->empresa_id)
        ->first();

        $cUF = null;
        $cMun = null;
        if ($request->municipio_encerramento) {
            $cidadeEnc = Cidade::find($request->municipio_encerramento);
            if ($cidadeEnc) {
                $cUF = Empresa::getCodUF($cidadeEnc->uf);
                $cMun = $cidadeEnc->codigo;
            }
        }

        $resp = $mdfe_service->encerrar($request->chave, $request->protocolo, $cUF, $cMun);
        
        $cStat = $resp->infEvento->cStat ?? null;
        $xMotivo = $resp->infEvento->xMotivo ?? 'Sem resposta da SEFAZ';

        if ($cStat != 135) {
            if ($request->ajax()) {
                return response()->json(['status' => 'erro', 'mensagem' => "[$cStat] $xMotivo"], 400);
            }
            session()->flash("flash_error", "[$cStat] $xMotivo");
            return redirect()->back();
        }

        if ($mdfe != null) {
            $mdfe->encerrado = true;
            $mdfe->save();
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'sucesso', 'mensagem' => "[$cStat] $xMotivo"], 200);
        }

        session()->flash("flash_success", "[$cStat] $xMotivo");
        return redirect()->back();
    }

    public function imprimir($id)
    {
        $item = Mdfe::findOrFail($id);
        $xmlPath = public_path('xml_mdfe/') . $item->chave . '.xml';

        if (!file_exists($xmlPath)) {
            // Tenta recuperar/reconstruir o XML autorizado diretamente da SEFAZ
            if ($item->estado_emissao == 'aprovado' && $item->chave) {
                try {
                    $config = Empresa::where('id', $item->empresa_id)->first();
                    $config = __objetoParaEmissao($config, $item->local_id);
                    if ($config) {
                        $cnpj = preg_replace('/[^0-9]/', '', $config->cpf_cnpj);
                        $mdfe_service = new MDFeService([
                            "atualizacao" => date('Y-m-d H:i:s'),
                            "tpAmb" => (int)$config->ambiente,
                            "razaosocial" => $config->nome,
                            "siglaUF" => $config->cidade->uf,
                            "cnpj" => $cnpj,
                            "inscricaomunicipal" => $config->inscricao_municipal,
                            "codigomunicipio" => $config->cidade->codigo,
                            "schemes" => "PL_MDFe_300a",
                            "versao" => '3.00'
                        ], $config);

                        $mdfe_service->recuperarXmlAutorizado($item);
                    }
                } catch (\Exception $e) {
                    // segue para verificação do arquivo
                }
            }
        }

        if (!file_exists($xmlPath)) {
            session()->flash("flash_error", "Arquivo XML do MDF-e não encontrado no servidor ({$item->chave}.xml).");
            return redirect()->back();
        }

        $xml = file_get_contents($xmlPath);
        $config = Empresa::where('id', $item->empresa_id)->first();

        $logo = null;
        if ($config && $config->logo) {
            $logoPath = public_path('uploads/logos/' . $config->logo);
            if (file_exists($logoPath)) {
                $logo = 'data://text/plain;base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        try {
            $damdfe = new Damdfe($xml);
            $pdf = $damdfe->render($logo);
            return response($pdf)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            session()->flash("flash_error", "Erro ao gerar DAMDFe: " . $e->getMessage());
            return redirect()->back();
        }
    }

    public function download($id)
    {
        $item = Mdfe::findOrFail($id);
        $xmlPath = public_path('xml_mdfe/') . $item->chave . '.xml';

        if (!file_exists($xmlPath)) {
            if ($item->estado_emissao == 'aprovado' && $item->chave) {
                try {
                    $config = Empresa::where('id', $item->empresa_id)->first();
                    $config = __objetoParaEmissao($config, $item->local_id);
                    if ($config) {
                        $cnpj = preg_replace('/[^0-9]/', '', $config->cpf_cnpj);
                        $mdfe_service = new MDFeService([
                            "atualizacao" => date('Y-m-d H:i:s'),
                            "tpAmb" => (int)$config->ambiente,
                            "razaosocial" => $config->nome,
                            "siglaUF" => $config->cidade->uf,
                            "cnpj" => $cnpj,
                            "inscricaomunicipal" => $config->inscricao_municipal,
                            "codigomunicipio" => $config->cidade->codigo,
                            "schemes" => "PL_MDFe_300a",
                            "versao" => '3.00'
                        ], $config);

                        $mdfe_service->recuperarXmlAutorizado($item);
                    }
                } catch (\Exception $e) {
                    // segue para verificação
                }
            }
        }

        if (!file_exists($xmlPath)) {
            session()->flash("flash_error", "Arquivo XML do MDF-e não encontrado no servidor.");
            return redirect()->back();
        }

        return response()->download($xmlPath);
    }

    public function createByVendas($ids)
    {
        $ids = explode(",", $ids);
        $nfe = $this->ajustaVendas($ids);

        $empresa = Empresa::where('id', request()->empresa_id)->first();

        $veiculos = Veiculo::where('empresa_id', request()->empresa_id)->where('status', 1)->get();
        if (sizeof($veiculos) == 0) {
            session()->flash("flash_error", "Cadastre um veiculo para criar uma MDFe!");
            return redirect()->route('veiculos.create');
        }

        $cidades = Cidade::all();
        $numeroMDFe = Mdfe::lastNumero($empresa);

        return view('mdfe.importarNfe.create', compact('numeroMDFe', 'veiculos', 'cidades', 'nfe', 'empresa'));
    }

    private function ajustaVendas($ids){
        $empresa = Empresa::where('id', request()->empresa_id)->first();

        $item = [
            'uf_inicio' => $empresa->cidade->uf,
            'uf_fim' => '',
            'cnpj_contratante' => $empresa->cpf_cnpj,
            'quantidade_carga' => 0,
            'valor_carga' => 0,
            'munucipio_carregamento' => $empresa->cidade_id,
            'chave' => '',
            'munucipio_descarregamento' => null
        ];


        foreach($ids as $i){
            $nfe = Nfe::findOrFail($i);
            $item['uf_fim'] = $nfe->cliente->cidade->uf;
            foreach($nfe->itens as $it){
                $item['quantidade_carga'] += $it->quantidade; 
            }

            $item['valor_carga'] += $nfe->total;
            if($nfe->chave){
                $item['chave'] = $nfe->chave;
            }
            $item['munucipio_descarregamento'] = $nfe->cliente->cidade_id;
        }

        return (object)$item;
    }

    public function imprimirCancela($id)
    {
        $item = Mdfe::findOrFail($id);
        $xmlPath = public_path('xml_mdfe_cancelada/') . $item->chave . '.xml';

        if (!file_exists($xmlPath)) {
            session()->flash("flash_error", "Arquivo XML de cancelamento não encontrado no servidor.");
            return redirect()->back();
        }

        $xml = file_get_contents($xmlPath);
        $dadosEmitente = $this->getEmitente($item->empresa);

        try {
            $daevento = new Daevento($xml, $dadosEmitente);
            $daevento->debugMode(true);
            $pdf = $daevento->render();
            return response($pdf)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            session()->flash("flash_error", "Ocorreu um erro durante o processamento: " . $e->getMessage());
            return redirect()->back();
        }
    }

    private function getEmitente($empresa)
    {
        return [
            'razao' => $empresa->nome,
            'logradouro' => $empresa->rua,
            'numero' => $empresa->numero,
            'complemento' => '',
            'bairro' => $empresa->bairro,
            'CEP' => preg_replace('/[^0-9]/', '', $empresa->cep),
            'municipio' => $empresa->cidade->nome,
            'UF' => $empresa->cidade->uf,
            'telefone' => $empresa->telefone,
            'email' => ''
        ];
    }

    public function alterarEstado($id)
    {
        $item = Mdfe::findOrFail($id);
        return view('mdfe.estado_fiscal', compact('item'));
    }

    public function storeEstado(Request $request, $id)
    {
        $item = Mdfe::findOrFail($id);
        try {
            $item->estado_emissao = $request->estado_emissao;
            if ($request->hasFile('file')) {
                $file = $request->file;
                $xml = simplexml_load_file($request->file);

                $chave = substr((string)$xml->MDFe->infMDFe->attributes()->Id, 4, 44);
                $file->move(public_path('xml_mdfe/'), $chave.'.xml');
                $item->chave = $chave;
                $item->mdfe_numero = (string)$xml->MDFe->infMDFe->ide->nMDF;
            }
            $item->save();
            session()->flash("flash_success", "Estado alterado");
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('mdfe.index');
    }
}
