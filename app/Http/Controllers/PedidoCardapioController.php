<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Empresa;
use App\Models\Nfce;
use App\Models\ConfigGeral;
use App\Models\Produto;
use App\Models\ItemAdicional;
use App\Models\ItemPizzaPedido;
use App\Models\Adicional;
use App\Models\Marca;
use App\Models\CategoriaProduto;
use App\Models\ConfiguracaoCardapio;
use App\Models\TamanhoPizza;
use App\Models\Caixa;
use App\Models\Funcionario;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class PedidoCardapioController extends Controller
{
    public function index(Request $request){
        $base = Pedido::where('empresa_id', $request->empresa_id)
            ->where('status', 1);

        $data = (clone $base)
            ->when(!empty($request->comanda), function($q) use ($request){
                return $q->where('comanda', 'like', "%$request->comanda%");
            })
            ->when(!empty($request->mesa), function($q) use ($request){
                return $q->where('mesa', 'like', "%$request->mesa%");
            })
            ->when(!empty($request->cliente), function($q) use ($request){
                return $q->where('cliente_nome', 'like', "%$request->cliente%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total'          => (clone $base)->count(),
            'em_atendimento' => (clone $base)->where('em_atendimento', 1)->count(),
            'para_fechar'    => (clone $base)->where('em_atendimento', 0)->count(),
            'total_valor'    => (clone $base)->sum('total'),
        ];

        return view('pedidos.index', compact('data', 'stats'));
    }

    public function store(Request $request){
        $cliente_id = $request->cliente_id;
        $comanda = $request->comanda;
        $mesa = $request->mesa;
        $clienteNome = $request->cliente_nome;
        $clienteFone = $request->cliente_fone;
        $item = Pedido::where('status', 1)
        ->where('empresa_id', $request->empresa_id)
        ->where('comanda', $comanda)
        ->first();

        if($item != null){
            session()->flash("flash_error", 'Comanda já está aberta');
            return redirect()->back();
        }

        try{
            $data = [
                'cliente_id' => $cliente_id,
                'cliente_nome' => $clienteNome,
                'cliente_fone' => $clienteFone,
                'comanda' => $comanda,
                'mesa' => $mesa,
                'total' => 0,
                'empresa_id' => $request->empresa_id
            ];

            Pedido::create($data);
            session()->flash("flash_success", "Comanda aberta!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado ' . $e->getMessage());
        }
        return redirect()->back();
    }

    public function show($id){
        $item = Pedido::findOrfail($id);
        $tamanhosPizza = TamanhoPizza::where('empresa_id', request()->empresa_id)
        ->get();

        $config = ConfiguracaoCardapio::where('empresa_id', request()->empresa_id)
        ->first();
        return view('pedidos.show', compact('item', 'tamanhosPizza', 'config'));
    }

    public function storeItem(Request $request, $id){
        try {
            DB::transaction(function () use ($request, $id) {

                $adicionais = $request->adicionais;
                $adicionais = explode(",", $adicionais);

                $pedido = Pedido::findOrfail($id);

                $data = [
                    'pedido_id' => $id,
                    'produto_id' => $request->produto_cardapio,
                    'observacao' => $request->observacao,
                    'quantidade' => __convert_value_bd($request->quantidade),
                    'valor_unitario' => __convert_value_bd($request->valor_unitario),
                    'sub_total' => __convert_value_bd($request->sub_total),
                    'estado' => $request->estado,
                    'ponto_carne' => $request->ponto_carne,
                    'tamanho_id' => $request->tamanho_id

                ];
                $itemPedido = ItemPedido::create($data);

                $produto = Produto::findOrFail($request->produto_cardapio);
                if($produto != null){
                    if($produto->categoria && $produto->categoria->tipo_pizza){
                        $pizzas = explode(",", $request->pizzas);
                        foreach($pizzas as $pz){
                            ItemPizzaPedido::create([
                                'item_pedido_id' => $itemPedido->id,
                                'produto_id' => $pz
                            ]);
                        }
                    }
                }
                foreach($adicionais as $a){
                    if($a){
                        $adicional = Adicional::findOrFail($a);
                        $dataItemAdicional = [
                            'item_pedido_id' => $itemPedido->id,
                            'adicional_id' => $adicional->id,
                        ];
                        ItemAdicional::create($dataItemAdicional);

                    }
                }

                $pedido->sumTotal();

            });
            session()->flash("flash_success", "Produto adicionado!");

        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado ' . $e->getMessage());
        }

        return redirect()->back();

    }

    public function destroy($id){
        $item = Pedido::findOrFail($id);
        try {
            foreach($item->itens as $it){
                $it->adicionais()->delete();
                $it->pizzas()->delete();
                $it->delete();
            }
            $item->delete();
            
            session()->flash("flash_success", "Comanda removida!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado '. $e->getMessage());
        }
        return redirect()->back();
    }

    public function destroyItem($id){
        $item = ItemPedido::findOrFail($id);
        try {
            $pedido = $item->pedido;
            $item->adicionais()->delete();
            $item->pizzas()->delete();
            $item->delete();
            $pedido->sumTotal();
            
            session()->flash("flash_success", "Item removido!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado '. $e->getMessage());
        }
        return redirect()->back();
    }

    public function print($id){
        $item = Pedido::with([
            'itens.produto', 
            'itens.adicionais.adicional', 
            'itens.pizzas.sabor', 
            'itens.tamanho', 
            'itens.funcionario'
        ])->findOrFail($id);

        $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();

        // Cálculo dinâmico e seguro da altura do papel (em pontos) para garantir 1 única folha
        // 80mm de largura = 226.77 pt
        $height = 240;

        if (!empty($item->cliente_nome) || !empty($item->cliente_fone)) {
            $height += 28;
        }

        if (!empty($item->observacao)) {
            $height += 20;
        }

        if (!empty($item->tipo_pagamento)) {
            $height += 14;
        }

        if (count($item->itens) == 0) {
            $height += 25;
        } else {
            foreach ($item->itens as $i) {
                $height += 22;
                if (sizeof($i->pizzas) > 0) $height += 16;
                if (sizeof($i->adicionais) > 0) $height += 16;
                if (!empty($i->observacao)) $height += 16;
            }
        }

        // Folga de segurança anti-quebra de folha
        $height += 40;

        $p = view('pedidos.imprimir', compact('config', 'item'))->render();

        $domPdf = new Dompdf(["enable_remote" => true]);
        $domPdf->loadHtml($p);
        $domPdf->setPaper([0, 0, 226.77, $height], 'portrait');
        $domPdf->render();

        return $domPdf->stream("Comanda_{$item->comanda}.pdf", ["Attachment" => false]);
    }

    public function finish($id){
        $pedido = Pedido::findOrFail($id);

        if($pedido->status == 0){
            session()->flash("flash_warning", 'Pedido já esta finalizado');
            return redirect()->back();
        }

        if (!__isCaixaAberto()) {
            session()->flash("flash_warning", "Abrir caixa antes de continuar!");
            return redirect()->route('caixa.create');
        }

        $categorias = CategoriaProduto::where('empresa_id', request()->empresa_id)->get();

        $abertura = Caixa::where('empresa_id', request()->empresa_id)->where('usuario_id', get_id_user())
        ->where('status', 1)
        ->first();

        $config = Empresa::findOrFail(request()->empresa_id);
        if($config == null){
            session()->flash("flash_warning", "Configure antes de continuar!");
            return redirect()->route('config.index');
        }

        if($config->natureza_id_pdv == null){
            session()->flash("flash_warning", "Configure a natureza de operação padrão para continuar!");
            return redirect()->route('config.index');
        }

        $funcionarios = Funcionario::where('empresa_id', request()->empresa_id)->get();

        $itens = $pedido->itens;
        $caixa = __isCaixaAberto();

        $config = ConfigGeral::where('empresa_id', request()->empresa_id)->first();
        $tiposPagamento = Nfce::tiposPagamento();
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

        $isVendaSuspensa = 0;

        $view = 'front_box.create';
        $produtos = [];
        $marcas = [];

        if($config != null && $config->modelo == 'compact'){
            $view = 'front_box.create2';
            $categorias = CategoriaProduto::where('empresa_id', request()->empresa_id)
            ->where('categoria_id', null)
            ->orderBy('nome', 'asc')
            ->paginate(4);

            $marcas = Marca::where('empresa_id', request()->empresa_id)
            ->orderBy('nome', 'asc')
            ->paginate(4);

            $produtos = Produto::select('produtos.*', \DB::raw('sum(quantidade) as quantidade'))
            ->where('empresa_id', request()->empresa_id)
            ->where('produtos.status', 1)
            ->where('status', 1)
            ->leftJoin('item_nfces', 'item_nfces.produto_id', '=', 'produtos.id')
            ->groupBy('produtos.id')
            ->orderBy('quantidade', 'desc')
            ->paginate(12);
        }

        return view($view, compact('categorias', 'abertura', 'funcionarios', 'pedido', 'itens', 'caixa', 'config', 
            'tiposPagamento', 'isVendaSuspensa', 'produtos', 'marcas'));
    }
}
