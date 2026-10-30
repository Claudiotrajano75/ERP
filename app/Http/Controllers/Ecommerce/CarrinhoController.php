<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EcommerceConfig;
use App\Models\Produto;
use App\Models\Carrinho;
use App\Models\Cliente;
use App\Models\ItemCarrinho;
use App\Models\ProdutoVariacao;
use App\Models\CategoriaProduto;
use Illuminate\Support\Str;
use App\Utils\CorreioUtil;

class CarrinhoController extends Controller
{

    public function __construct(CorreioUtil $util){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->util = $util;
    }

    public function index(Request $request){
        $item = $this->_getCarrinho();
        $config = EcommerceConfig::findOrfail($request->loja_id);

        $categorias = CategoriaProduto::withCount(['produtos as produtos_count' => function($q){
            $q->where('ecommerce', 1);
        }])
        ->where('ecommerce', 1)
        ->where('empresa_id', $config->empresa_id)->get();
        $clienteLogado = $this->_getClienteLogado();

        $cliente = null;
        $enderecos = [];
        $dataFrete = null;
        if($item == null){
            return redirect()->route('loja.index', 'link='.$config->loja_id);
        }
        if($clienteLogado){
            $cliente = Cliente::findOrFail($clienteLogado);

            $somaPeso = $item->somaPeso();
            $dimensoes = $item->somaDimensoes();
            $cepOrigem = str_replace("-", "", $config->cep);

            $total = $item->valor_total;
            foreach($cliente->enderecosEcommerce as $endereco){
                $cepDestino = str_replace("-", "", $endereco->cep);
                $data = $this->util->getValores($cepOrigem, $cepDestino, $dimensoes['altura'], $dimensoes['largura'], $dimensoes['comprimento'], $somaPeso);
                $dataFrete .= view('loja.partials.calculo_frete_enderecos', compact('data', 'config', 'endereco', 'total'));
            }
        }

        // Sugestões: produtos da mesma categoria dos itens do carrinho
        $sugestoes = [];
        if(sizeof($item->itens) > 0){
            $categoriasDoCarrinho = $item->itens->pluck('produto.categoria_id')->filter()->unique()->values();
            $idsNoCarrinho = $item->itens->pluck('produto_id')->unique()->values();
            if($categoriasDoCarrinho->count() > 0){
                $data = Produto::where('empresa_id', $config->empresa_id)
                ->whereIn('categoria_id', $categoriasDoCarrinho)
                ->whereNotIn('id', $idsNoCarrinho)
                ->where('status', 1)
                ->where('ecommerce', 1)
                ->limit(4)->get();

                foreach($data as $p){
                    if($p->gerenciar_estoque){
                        if($p->estoque && $p->estoque->quantidade > 0){
                            array_push($sugestoes, $p);
                        }
                    }else{
                        array_push($sugestoes, $p);
                    }
                }
            }
        }

        $carrinho = $item;
        return view('loja.carrinho', compact('item', 'carrinho', 'config', 'categorias', 'cliente', 'enderecos', 'dataFrete', 'sugestoes'));
    }

    private function _getCarrinho(){
        $data = null;
        if(isset($_SESSION["session_cart"])){
            $data = Carrinho::with(['itens.produto'])->where('session_cart', $_SESSION["session_cart"])
            ->first();
        }
        return $data;
    }

    public function adicionar(Request $request){

        if(!isset($_SESSION["session_cart"])){
            $session_cart = Str::random(30);
            $_SESSION['session_cart'] = $session_cart;
        }else{
            $session_cart = $_SESSION['session_cart'];
        }

        $config = EcommerceConfig::findOrfail($request->loja_id);
        $produto = Produto::findOrfail($request->produto_id);

        $quantidade = __convert_value_bd($request->quantidade);

        if($produto->gerenciar_estoque){
            if(!$produto->estoque){
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Produto sem estoque disponível no momento.'], 422);
                }
                session()->flash("flash_error", 'Produto sem estoque');
                return redirect()->back();
            }

            if($quantidade > $produto->estoque->quantidade){
                $msg = 'Estoque insuficiente, quantidade máxima de ' . number_format($produto->estoque->quantidade, 0) . ' ' . $produto->unidade .  ' para este item!';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                session()->flash("flash_error", $msg);
                return redirect()->back();
            }
        }

        $carrinho = Carrinho::where('session_cart', $session_cart)
        ->first();

        $produtoVariacao = null;
        if(isset($request->variacao_id)){
            $produtoVariacao = ProdutoVariacao::findOrfail($request->variacao_id);
            $produto->valor_ecommerce = $produtoVariacao->valor;
        }else{
            if($produto->valor_ecommerce == null){
                $produto->valor_ecommerce = $produto->valor_unitario;
            }
        }

        if($carrinho == null){
            // novo carrinho
            $clienteLogado = $this->_getClienteLogado();

            $carrinho = Carrinho::create([
                'cliente_id' => $clienteLogado ? $clienteLogado : null,
                'empresa_id' => $config->empresa_id,
                'estado' => 'pendente',
                'valor_total' => $produto->valor_ecommerce * $quantidade,
                'endereco_id' => null,
                'valor_frete' => 0,
                'session_cart' => $session_cart
            ]);

            ItemCarrinho::create([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $request->produto_id,
                'variacao_id' => isset($request->variacao_id) ? $request->variacao_id : null,
                'quantidade' => $quantidade,
                'valor_unitario' => $produto->valor_ecommerce,
                'sub_total' => $produto->valor_ecommerce * $quantidade
            ]);
            session()->flash("flash_success", "Produto adicionado ao carrinho!");
        }else{
            ItemCarrinho::create([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $request->produto_id,
                'variacao_id' => isset($request->variacao_id) ? $request->variacao_id : null,
                'quantidade' => $quantidade,
                'valor_unitario' => $produto->valor_ecommerce,
                'sub_total' => $produto->valor_ecommerce * $quantidade
            ]);

            session()->flash("flash_success", "Produto adicionado ao carrinho!");
        }
        $this->_atualizaValorCarrinho($carrinho->id);

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax') || $request->header('Accept') == 'application/json' || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            return response()->json($this->_formatarCarrinhoJson($carrinho));
        }

        if ($request->comprar_agora == 1) {
            return redirect()->route('loja.pagamento', 'link='.$config->loja_id);
        }
        return redirect()->route('loja.carrinho', 'link='.$config->loja_id);
    }

    private function _formatarCarrinhoJson($carrinho) {
        $carrinho->refresh();
        $carrinho->load('itens.produto');
        return [
            'success' => true,
            'message' => 'Produto adicionado ao carrinho!',
            'total' => (float)$carrinho->valor_total,
            'total_formatado' => __moeda($carrinho->valor_total),
            'count' => count($carrinho->itens),
            'itens' => $carrinho->itens->map(function($ci) {
                return [
                    'id' => $ci->id,
                    'nome' => $ci->produto ? $ci->produto->nome : 'Produto',
                    'img' => $ci->produto ? $ci->produto->img : '/imgs/no-image.png',
                    'quantidade' => (float)$ci->quantidade,
                    'valor_unitario' => __moeda($ci->valor_unitario),
                    'sub_total' => __moeda($ci->sub_total),
                ];
            })->values()
        ];
    }

    private function _getClienteLogado(){
        if(isset($_SESSION['cliente_ecommerce'])){
            return $_SESSION['cliente_ecommerce'];
        }
        return null;
    }

    public function removeItem(Request $request, $id){
        $item = ItemCarrinho::findOrfail($id);
        $carrinhoId = $item->carrinho_id;
        try {
            $item->delete();
            $this->_atualizaValorCarrinho($carrinhoId);

            if ($request->ajax() || $request->wantsJson()) {
                $carrinho = Carrinho::find($carrinhoId);
                return response()->json($this->_formatarCarrinhoJson($carrinho));
            }

            session()->flash("flash_success", "Item removido com sucesso!");
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            session()->flash("flash_error", 'Algo deu errado: ' . $e->getMessage());
        }
        return redirect()->back();
    }

    private function _atualizaValorCarrinho($carrinho_id){
        $item = Carrinho::findOrfail($carrinho_id);
        $item->valor_total = $item->itens->sum('sub_total') + ($item->valor_frete ?? 0);
        $item->save();
    }

    public function atualizaQuantidade(Request $request, $id){
        $item = ItemCarrinho::findOrfail($id);
        try {
            $item->quantidade = (float)$request->quantidade;
            if($item->produto && $item->produto->gerenciar_estoque){
                if($item->produto->estoque && $item->quantidade > $item->produto->estoque->quantidade){
                    $msg = 'Estoque insuficiente, quantidade máxima de ' . number_format($item->produto->estoque->quantidade, 0) . ' ' . $item->produto->unidade .  ' para este item!';
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    session()->flash("flash_error", $msg);
                    return redirect()->back();
                }
            }
            $item->sub_total = $item->valor_unitario * (float)$request->quantidade;
            $item->save();
            $this->_atualizaValorCarrinho($item->carrinho_id);

            if ($request->ajax() || $request->wantsJson()) {
                $carrinho = Carrinho::find($item->carrinho_id);
                return response()->json($this->_formatarCarrinhoJson($carrinho));
            }

            session()->flash("flash_success", "Quantidade atualizada com sucesso!");
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            session()->flash("flash_error", 'Algo deu errado: ' . $e->getMessage());
        }
        return redirect()->back();
    }

    public function setarFrete(Request $request){
        // dd($request->all());
        $item = $this->_getCarrinho();
        $item->valor_frete = $request->valor_frete;
        $item->endereco_id = $request->endereco_id;
        $item->tipo_frete = $request->tipo_frete;
        $item->cep = $request->cep;
        $item->save();

        $this->_atualizaValorCarrinho($item->id);
        $config = EcommerceConfig::findOrfail($request->loja_id);
        $clienteLogado = $this->_getClienteLogado();
        if($clienteLogado == null){
            return redirect()->route('loja.cadastro', 'link='.$config->loja_id);
        }

        return redirect()->route('loja.pagamento', 'link='.$config->loja_id);

    }
    
}
