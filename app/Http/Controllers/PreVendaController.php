<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\CategoriaProduto;
use App\Models\Empresa;
use App\Models\FaturaPreVenda;
use App\Models\Funcionario;
use App\Models\ItemPreVenda;
use App\Models\NaturezaOperacao;
use App\Models\PreVenda;
use App\Models\PreVendaAuditoria;
use App\Models\Produto;
use App\Models\Nfce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use StringBackedEnum;
use Svg\Tag\Rect;
use Illuminate\Support\Str;
use NFePHP\DA\NFe\CupomNaoFiscal;

class PreVendaController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pre_venda_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pre_venda_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pre_venda_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:pre_venda_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $locais = __getLocaisAtivoUsuario();
        $locais = $locais->pluck(['id']);

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $cliente_id = $request->get('cliente_id');
        $status = $request->get('status');
        $local_id = $request->get('local_id');

        $item = PreVenda::first();

        $data = PreVenda::where('empresa_id', request()->empresa_id)
        ->when(!empty($cliente_id), function ($query) use ($cliente_id) {
            return $query->where('cliente_id', $cliente_id);
        })
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when(!empty($status), function ($query) use ($status) {
            if ($status == -1) {
                return $query->where('status', '!=', 1);
            } else {
                return $query->where('status', $status);
            }
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->orderBy('id', 'desc')
        ->paginate(env("PAGINACAO"));
        return view('pre_venda.index', compact('data'));
    }


    public function create()
    {
        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }

        $abertura = Caixa::where('usuario_id', get_id_user())
        ->where('status', 1)
        ->first();

        $categorias = CategoriaProduto::where('empresa_id', request()->empresa_id)->get();
        $funcionarios = Funcionario::where('empresa_id', request()->empresa_id)->get();
        $naturezas = NaturezaOperacao::where('empresa_id', request()->empresa_id)->get();
        if (sizeof($naturezas) == 0) {
            session()->flash("flash_warning", "Primeiro cadastre um natureza de operação!");
            return redirect()->route('natureza-operacao.create');
        }
        $caixa = __isCaixaAberto();

        $tiposPagamento = Nfce::tiposPagamento();
        // dd($tiposPagamento);
        $config = Empresa::findOrFail(request()->empresa_id);
        
        if($config != null){
            $config->tipos_pagamento_pdv = $config != null && $config->tipos_pagamento_pdv ? json_decode($config->tipos_pagamento_pdv) : [];
            $temp = [];
            if(sizeof($config->tipos_pagamento_pdv) > 0){
                foreach($tiposPagamento as $key => $t){
                    if(in_array($t, $config->tipos_pagamento_pdv)){
                        $temp[$key] = $t;
                    }
                }
                $tiposPagamento = $temp;
            }
        }

        return view('pre_venda.create', compact('abertura', 'categorias', 'funcionarios', 'naturezas', 'caixa', 'tiposPagamento'));
    }

    public function store(Request $request)
    {
        // dd($request);
        if(!$request->produto_id){
            session()->flash("flash_error", "Inclua ao menos 1 item na pré venda");
            return redirect()->back();
        }
        try {
            // $valor_total = $this->somaItens($request);

            $natureza = NaturezaOperacao::where('empresa_id', request()->empresa_id)->first();
            $caixa = __isCaixaAberto();
            $request->merge([
                'cliente_id' => $request->cliente_id,
                'bandeira_cartao' => $request->bandeira_cartao ?? '',
                'cnpj_cartao' => $request->cnpj_cartao ?? '',
                'cAut_cartao' => $request->cAut_cartao ?? '',
                'descricao_pag_outros' => $request->descricao_pag_outros ?? '',
                'rascunho' => $request->rascunho ?? 0,
                'usuario_id' => get_id_user(),
                'observacao' => $request->observacao ?? '',
                'qtd_volumes' => $request->qtd_volumes ?? 0,
                'peso_liquido' => $request->peso_liquido ?? 0,
                'peso_bruto' => $request->peso_bruto ?? 0,
                'desconto' => $request->desconto ? __convert_value_bd($request->desconto) : 0,
                'valor_total' => __convert_value_bd($request->valor_total),
                'acrescimo' => $request->acrescimo ? __convert_value_bd($request->acrescimo) : 0,
                'natureza_id' => $natureza->id,
                'forma_pagamento' => '',
                'tipo_pagamento' => $request->tipo_pagamento_row ? '99' : $request->tipo_pagamento,
                'nome' => $request->nome,
                'cpf' => $request->cpf ?? '',
                'local_id' => $caixa->local_id,
                'codigo' => Str::random(8)
            ]);

            $preVenda = PreVenda::create($request->all());

            for ($i = 0; $i < sizeof($request->produto_id); $i++) {
                $product = Produto::findOrFail($request->produto_id[$i]);
                $cfop = 0;
                ItemPreVenda::create([
                    'pre_venda_id' => $preVenda->id,
                    'produto_id' => (int)$request->produto_id[$i],
                    'quantidade' => __convert_value_bd($request->quantidade[$i]),
                    'valor' => __convert_value_bd($request->valor_unitario[$i]),
                    'cfop' => $cfop,
                    'observacao' => $request->observacao ?? '',
                ]);
            }

            if ($request->tipo_pagamento_row) {
                for ($i = 0; $i < sizeof($request->tipo_pagamento_row); $i++) {
                    FaturaPreVenda::create([
                        'valor_parcela' => __convert_value_bd($request->valor_integral_row[$i]),
                        'tipo_pagamento' => $request->tipo_pagamento_row[$i],
                        'pre_venda_id' => $preVenda->id,
                        'vencimento' => $request->data_vencimento_row[$i]
                    ]);
                }
            } else {
                FaturaPreVenda::create([
                    'valor_parcela' => __convert_value_bd($request->valor_total),
                    'tipo_pagamento' => $request->tipo_pagamento,
                    'pre_venda_id' => $preVenda->id,
                    'vencimento' => $request->data_vencimento
                ]);
            }
            session()->flash("flash_success", "Pré venda realizada com sucesso!");
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'codigo' => $preVenda->codigo,
                    'message' => 'Pré venda realizada com sucesso!'
                ]);
            }
        } catch (\Exception $e) {
            // echo $e->getMessage() . '<br>' . $e->getLine();
            // die;
            session()->flash("flash_error", "Algo deu errado por aqui: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Algo deu errado: ' . $e->getMessage()
                ], 422);
            }
        }
        $codigo = isset($preVenda) ? $preVenda->codigo : null;
        return redirect()->back()->with(['codigo' => $codigo]);
    }

    public function edit($id)
    {
        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }

        $item = PreVenda::with(['itens.produto', 'cliente', 'vendedor', 'fatura'])
        ->where('empresa_id', request()->empresa_id)
        ->findOrFail($id);

        $this->authorize('update', $item);

        if (!$item->podeSerEditada()) {
            session()->flash("flash_error", "Esta pré-venda foi convertida em venda e não pode mais ser editada!");
            return redirect()->route('pre-venda.index');
        }

        $abertura = Caixa::where('usuario_id', get_id_user())
        ->where('status', 1)
        ->first();

        $categorias = CategoriaProduto::where('empresa_id', request()->empresa_id)->get();
        $funcionarios = Funcionario::where('empresa_id', request()->empresa_id)->get();
        $naturezas = NaturezaOperacao::where('empresa_id', request()->empresa_id)->get();
        if (sizeof($naturezas) == 0) {
            session()->flash("flash_warning", "Primeiro cadastre um natureza de operação!");
            return redirect()->route('natureza-operacao.create');
        }
        $caixa = __isCaixaAberto();

        $tiposPagamento = Nfce::tiposPagamento();
        $config = Empresa::findOrFail(request()->empresa_id);
        if ($config != null) {
            $config->tipos_pagamento_pdv = $config->tipos_pagamento_pdv ? json_decode($config->tipos_pagamento_pdv) : [];
            $temp = [];
            if (sizeof($config->tipos_pagamento_pdv) > 0) {
                foreach ($tiposPagamento as $key => $t) {
                    if (in_array($t, $config->tipos_pagamento_pdv)) {
                        $temp[$key] = $t;
                    }
                }
                $tiposPagamento = $temp;
            }
        }

        // Monta os itens no formato esperado pelo _forms (key, produto, quantidade, valor_unitario)
        $itens = $item->itens->map(function ($i, $key) {
            return (object)[
                'key' => $key,
                'produto' => $i->produto,
                'quantidade' => $i->quantidade,
                'valor_unitario' => $i->valor,
            ];
        });

        $cliente = $item->cliente;
        $funcionario = $item->vendedor;

        return view('pre_venda.edit', compact('item', 'itens', 'cliente', 'funcionario', 'abertura', 'categorias', 'funcionarios', 'naturezas', 'caixa', 'tiposPagamento'));
    }

    public function update(Request $request, $id)
    {
        $preVenda = PreVenda::with(['itens', 'fatura'])
        ->where('empresa_id', request()->empresa_id)
        ->findOrFail($id);

        $this->authorize('update', $preVenda);

        if (!$preVenda->podeSerEditada()) {
            session()->flash("flash_error", "Esta pré-venda foi convertida em venda e não pode mais ser editada!");
            return redirect()->route('pre-venda.index');
        }

        if (!$request->produto_id) {
            session()->flash("flash_error", "Inclua ao menos 1 item na pré venda");
            return redirect()->back();
        }

        // Estado anterior (usado na auditoria)
        $estadoAntes = [
            'desconto' => $preVenda->desconto,
            'acrescimo' => $preVenda->acrescimo,
            'valor_total' => $preVenda->valor_total,
            'cliente_id' => $preVenda->cliente_id,
            'tipo_pagamento' => $preVenda->tipo_pagamento,
            'observacao' => $preVenda->observacao,
            'itens' => $preVenda->itens->map(function ($i) {
                return [
                    'item_id' => $i->id,
                    'produto_id' => $i->produto_id,
                    'quantidade' => $i->quantidade,
                    'valor' => $i->valor,
                ];
            })->values()->toArray(),
        ];

        $usuarioId = get_id_user();
        $empresaId = request()->empresa_id;

        try {
            $natureza = NaturezaOperacao::where('empresa_id', request()->empresa_id)->first();
            $caixa = __isCaixaAberto();

            $request->merge([
                'bandeira_cartao' => $request->bandeira_cartao ?? '',
                'cnpj_cartao' => $request->cnpj_cartao ?? '',
                'cAut_cartao' => $request->cAut_cartao ?? '',
                'descricao_pag_outros' => $request->descricao_pag_outros ?? '',
                'rascunho' => $request->rascunho ?? 0,
                'observacao' => $request->observacao ?? '',
                'qtd_volumes' => $request->qtd_volumes ?? 0,
                'peso_liquido' => $request->peso_liquido ?? 0,
                'peso_bruto' => $request->peso_bruto ?? 0,
                'desconto' => $request->desconto ? __convert_value_bd($request->desconto) : 0,
                'valor_total' => __convert_value_bd($request->valor_total),
                'acrescimo' => $request->acrescimo ? __convert_value_bd($request->acrescimo) : 0,
                'natureza_id' => $natureza->id,
                'forma_pagamento' => '',
                'tipo_pagamento' => $request->tipo_pagamento_row ? '99' : $request->tipo_pagamento,
            ]);

            $preVenda->update($request->all());

            // Itens: recria (padrão do PDV)
            $preVenda->itens()->delete();
            for ($i = 0; $i < sizeof($request->produto_id); $i++) {
                $product = Produto::findOrFail($request->produto_id[$i]);
                $cfop = 0;
                ItemPreVenda::create([
                    'pre_venda_id' => $preVenda->id,
                    'produto_id' => (int)$request->produto_id[$i],
                    'quantidade' => __convert_value_bd($request->quantidade[$i]),
                    'valor' => __convert_value_bd($request->valor_unitario[$i]),
                    'cfop' => $cfop,
                    'observacao' => $request->observacao ?? '',
                ]);
            }

            // Fatura: recria (padrão do PDV)
            $preVenda->fatura()->delete();
            if ($request->tipo_pagamento_row) {
                for ($i = 0; $i < sizeof($request->tipo_pagamento_row); $i++) {
                    FaturaPreVenda::create([
                        'valor_parcela' => __convert_value_bd($request->valor_integral_row[$i]),
                        'tipo_pagamento' => $request->tipo_pagamento_row[$i],
                        'pre_venda_id' => $preVenda->id,
                        'vencimento' => $request->data_vencimento_row[$i]
                    ]);
                }
            } else {
                FaturaPreVenda::create([
                    'valor_parcela' => __convert_value_bd($request->valor_total),
                    'tipo_pagamento' => $request->tipo_pagamento,
                    'pre_venda_id' => $preVenda->id,
                    'vencimento' => $request->data_vencimento
                ]);
            }

            // ─── Auditoria: registra toda alteração em tabela própria ───
            $itensDepois = [];
            for ($i = 0; $i < sizeof($request->produto_id); $i++) {
                $itensDepois[] = [
                    'item_id' => null,
                    'produto_id' => (int)$request->produto_id[$i],
                    'quantidade' => __convert_value_bd($request->quantidade[$i]),
                    'valor' => __convert_value_bd($request->valor_unitario[$i]),
                ];
            }

            $operacoes = PreVendaAuditoria::diffItens($estadoAntes['itens'], $itensDepois);
            $operacoesCabecalho = PreVendaAuditoria::diffCabecalho($estadoAntes, [
                'desconto' => $request->desconto ? __convert_value_bd($request->desconto) : 0,
                'acrescimo' => $request->acrescimo ? __convert_value_bd($request->acrescimo) : 0,
                'valor_total' => __convert_value_bd($request->valor_total),
                'cliente_id' => $request->cliente_id,
                'tipo_pagamento' => $request->tipo_pagamento_row ? '99' : $request->tipo_pagamento,
                'observacao' => $request->observacao ?? '',
            ]);

            foreach (array_merge($operacoes, $operacoesCabecalho) as $op) {
                PreVendaAuditoria::registrar(
                    $preVenda->id,
                    $op['tipo_operacao'],
                    $op['item_id'],
                    $op['valores_antes'],
                    $op['valores_depois'],
                    $empresaId,
                    $usuarioId
                );
            }

            session()->flash("flash_success", "Pré venda atualizada com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", "Algo deu errado por aqui: " . $e->getMessage());
        }        return redirect()->route('pre-venda.index');
    }

    public function auditoria($id)
    {
        $item = PreVenda::where('empresa_id', request()->empresa_id)->findOrFail($id);

        $this->authorize('view', $item);

        $auditorias = PreVendaAuditoria::with('usuario')
        ->where('pre_venda_id', $item->id)
        ->orderBy('data_hora', 'desc')
        ->get();

        return view('pre_venda.auditoria', compact('item', 'auditorias'));
    }

    public function imprimir($codigo){
        $item = PreVenda::with(['itens.produto', 'cliente', 'fatura', 'vendedor'])
        ->where('codigo', $codigo)
        ->where('empresa_id', request()->empresa_id)
        ->first();

        if (!$item) {
            return abort(404, 'Pré-venda não encontrada');
        }
        
        $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();
        
        // Renderiza a view personalizada
        $html = view('pre_venda.cupom', compact('item', 'config'))->render();

        // Instancia Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        
        $dompdf->loadHtml($html);
        
        // Altura dinâmica: Base (~300) + (Itens * ~25)
        $altura = 300 + (count($item->itens) * 25);
        
        // 80mm = ~226.77 pt
        $dompdf->setPaper([0, 0, 226.77, $altura], 'portrait'); 
        
        $dompdf->render();

        return $dompdf->stream('PRE_VENDA.pdf', ["Attachment" => false]);
    }

    private function somaItens($request)
    {
        $valor_total = 0;
        for ($i = 0; $i < sizeof($request->produto_id); $i++) {
            $valor_total += __convert_value_bd($request->subtotal_item[$i]);
        }
        return $valor_total;
    }

    public function destroy($id)
    {
        $item = PreVenda::findOrFail($id);
        try {
            $item->delete();
            session()->flash("flash_success", "Removido com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('pre-venda.index');
    }
}
