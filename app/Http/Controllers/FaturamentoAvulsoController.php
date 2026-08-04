<?php

namespace App\Http\Controllers;

use App\Models\Nfe;
use App\Models\ItemNfe;
use App\Models\FaturaNfe;
use App\Models\ContaReceber;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\Transportadora;
use App\Models\NaturezaOperacao;
use App\Models\Empresa;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\EstoqueUtil;

class FaturamentoAvulsoController extends Controller
{
    protected $utilEstoque;

    public function __construct(EstoqueUtil $utilEstoque)
    {
        $this->utilEstoque = $utilEstoque;
        $this->middleware('permission:nfe_create');
    }

    public function create(Request $request)
    {
        $empresa_id = request()->empresa_id;
        $clientes = Cliente::where('empresa_id', $empresa_id)->get();
        $produtos = Produto::where('empresa_id', $empresa_id)->get();
        $transportadoras = Transportadora::where('empresa_id', $empresa_id)->get();
        $naturezas = NaturezaOperacao::where('empresa_id', $empresa_id)->get();
        $cidades = Cidade::all();
        $empresa = Empresa::findOrFail($empresa_id);
        $caixa = __isCaixaAberto();
        
        $local_id = $caixa ? $caixa->local_id : null;
        $empresaObj = __objetoParaEmissao($empresa, $local_id);
        $numeroNfe = Nfe::lastNumero($empresaObj);

        $naturezaPadrao = NaturezaOperacao::where('empresa_id', $empresa_id)
            ->where('padrao', 1)->first();

        return view('faturamento_avulso.create', compact(
            'clientes', 'produtos', 'transportadoras', 'naturezas', 'cidades', 
            'numeroNfe', 'empresa', 'caixa', 'naturezaPadrao'
        ));
    }

    public function store(Request $request)
    {
        try {
            $nfe = DB::transaction(function () use ($request) {
                $empresa = Empresa::findOrFail($request->empresa_id);
                $caixa = __isCaixaAberto();
                $local_id = $request->local_id ?? ($caixa ? $caixa->local_id : null);
                
                $empresaObj = __objetoParaEmissao($empresa, $local_id);
                
                $nfe = Nfe::create([
                    'empresa_id' => $request->empresa_id,
                    'emissor_nome' => $empresa->nome,
                    'emissor_cpf_cnpj' => $empresa->cpf_cnpj,
                    'cliente_id' => $request->cliente_id,
                    'transportadora_id' => $request->transportadora_id,
                    'chave' => '',
                    'numero_serie' => $empresaObj->numero_serie_nfe ?? 0,
                    'numero' => $request->numero ?? 0,
                    'estado' => 'novo',
                    'total' => __convert_value_bd($request->valor_total),
                    'desconto' => $request->desconto ? __convert_value_bd($request->desconto) : 0,
                    'acrescimo' => $request->acrescimo ? __convert_value_bd($request->acrescimo) : 0,
                    'valor_produtos' => __convert_value_bd($request->valor_produtos),
                    'valor_frete' => $request->valor_frete ? __convert_value_bd($request->valor_frete) : 0,
                    'caixa_id' => $caixa ? $caixa->id : null,
                    'local_id' => $local_id,
                    'tipo_pagamento' => $request->tipo_pagamento ?? '90', // Sem pagamento por padrão
                    'user_id' => \Auth::user()->id,
                    'tpNF' => 1, // Saída
                    'finNFe' => $request->finNFe ?? 1, // Finalidade de emissão
                    'natureza_id' => $request->natureza_id,
                    'observacao' => $request->observacao,
                    'placa' => $request->placa,
                    'uf' => $request->uf,
                    'peso_liquido' => $request->peso_liquido ? __convert_value_bd($request->peso_liquido) : 0,
                    'peso_bruto' => $request->peso_bruto ? __convert_value_bd($request->peso_bruto) : 0,
                    'especie' => $request->especie,
                    'qtd_volumes' => $request->qtd_volumes ? __convert_value_bd($request->qtd_volumes) : 0,
                ]);

                // Itens da Nota
                if ($request->has('item_produto_id')) {
                    for ($i = 0; $i < sizeof($request->item_produto_id); $i++) {
                        $product = Produto::findOrFail($request->item_produto_id[$i]);
                        
                        ItemNfe::create([
                            'nfe_id' => $nfe->id,
                            'produto_id' => (int)$request->item_produto_id[$i],
                            'quantidade' => __convert_value_bd($request->item_quantidade[$i]),
                            'valor_unitario' => __convert_value_bd($request->item_valor_unitario[$i]),
                            'sub_total' => __convert_value_bd($request->item_sub_total[$i]),
                            'perc_icms' => $request->item_perc_icms[$i] ? __convert_value_bd($request->item_perc_icms[$i]) : 0,
                            'perc_pis' => $request->item_perc_pis[$i] ? __convert_value_bd($request->item_perc_pis[$i]) : 0,
                            'perc_cofins' => $request->item_perc_cofins[$i] ? __convert_value_bd($request->item_perc_cofins[$i]) : 0,
                            'perc_ipi' => $request->item_perc_ipi[$i] ? __convert_value_bd($request->item_perc_ipi[$i]) : 0,
                            'perc_ibs' => $request->item_perc_ibs[$i] ? __convert_value_bd($request->item_perc_ibs[$i]) : 0,
                            'perc_cbs' => $request->item_perc_cbs[$i] ? __convert_value_bd($request->item_perc_cbs[$i]) : 0,
                            'cst_csosn' => $request->item_cst_csosn[$i],
                            'cst_pis' => $request->item_cst_pis[$i],
                            'cst_cofins' => $request->item_cst_cofins[$i],
                            'cst_ipi' => $request->item_cst_ipi[$i],
                            'perc_red_bc' => $request->item_perc_red_bc[$i] ? __convert_value_bd($request->item_perc_red_bc[$i]) : 0,
                            'cfop' => $request->item_cfop[$i],
                            'ncm' => $request->item_ncm[$i],
                            'codigo_beneficio_fiscal' => $request->item_codigo_beneficio_fiscal[$i],
                            'variacao_id' => $request->item_variacao_id[$i] ?? null,
                            'cEnq' => $product->cEnq,
                        ]);

                        // Movimentação de estoque (OPCIONAL)
                        if ($request->baixar_estoque && $product->gerenciar_estoque) {
                            $this->utilEstoque->reduzEstoque($product->id, __convert_value_bd($request->item_quantidade[$i]), $request->item_variacao_id[$i] ?? null, $local_id);
                            $this->utilEstoque->movimentacaoProduto($product->id, __convert_value_bd($request->item_quantidade[$i]), 'reducao', $nfe->id, 'venda_nfe', \Auth::user()->id, $request->item_variacao_id[$i] ?? null);
                        }
                    }
                }

                // Financeiro (OPCIONAL)
                if ($request->gerar_financeiro && $request->has('fatura_valor')) {
                    for ($i = 0; $i < sizeof($request->fatura_valor); $i++) {
                        FaturaNfe::create([
                            'nfe_id' => $nfe->id,
                            'tipo_pagamento' => $request->fatura_tipo[$i] ?? '90',
                            'data_vencimento' => $request->fatura_vencimento[$i] ?? date('Y-m-d'),
                            'valor' => __convert_value_bd($request->fatura_valor[$i])
                        ]);

                        ContaReceber::create([
                            'empresa_id' => $request->empresa_id,
                            'nfe_id' => $nfe->id,
                            'cliente_id' => $request->cliente_id,
                            'valor_integral' => __convert_value_bd($request->fatura_valor[$i]),
                            'tipo_pagamento' => $request->fatura_tipo[$i] ?? '90',
                            'data_vencimento' => $request->fatura_vencimento[$i] ?? date('Y-m-d'),
                            'local_id' => $local_id,
                        ]);
                    }
                }

                return $nfe;
            });

            session()->flash("flash_success", "Faturamento avulso gerado com sucesso! Transmita a nota a seguir.");
            return redirect()->route('nfe.index');
        } catch (\Exception $e) {
            session()->flash("flash_error", "Erro ao gerar faturamento avulso: " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
