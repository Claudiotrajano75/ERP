<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoriaProduto;
use App\Models\EcommerceConfig;
use App\Models\Produto;
use App\Models\Carrinho;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function __construct(){
        session_start();
    }

    private function _validaHash($config){
        $categorias = CategoriaProduto::where('ecommerce', 1)
        ->where('empresa_id', $config->empresa_id)
        ->orderBy('nome', 'asc')
        ->where('hash_delivery', null)
        ->get();

        foreach($categorias as $c){
            $c->hash_ecommerce = Str::random(50);
            $c->save();
        }

        $produtos = Produto::where('empresa_id', $config->empresa_id)
        ->where('status', 1)
        ->where('ecommerce', 1)
        ->where('hash_delivery', null)
        ->get();

        foreach($produtos as $p){
            $p->hash_ecommerce = Str::random(50);
            $p->save();
        }

    }
    
    public function index(Request $request){
        $config = EcommerceConfig::findOrfail($request->loja_id);
        $this->_validaHash($config);

        $categorias = $this->_getCategorias($config->empresa_id);

        $produtosEmDestaque = $this->produtosEmDestaque($config->empresa_id);

        $carrinho = $this->_getCarrinho();

        return view('loja.index', compact('config', 'categorias', 'produtosEmDestaque', 'carrinho'));
    }

    public function politicaPrivacidade(Request $request){
        $config = EcommerceConfig::findOrfail($request->loja_id);
        $categorias = $this->_getCategorias($config->empresa_id);

        $produtosEmDestaque = $this->produtosEmDestaque($config->empresa_id);

        $carrinho = $this->_getCarrinho();

        return view('loja.politica_privacidade', compact('config', 'categorias', 'produtosEmDestaque', 'carrinho'));
    }

    private function _getCategorias($empresa_id){
        return CategoriaProduto::withCount(['produtos as produtos_count' => function($q){
            $q->where('ecommerce', 1);
        }])
        ->where('ecommerce', 1)
        ->where('empresa_id', $empresa_id)
        ->get();
    }

    public function pesquisa(Request $request){

        $categoria_pesquisa = $request->categoria;
        $pesquisa = $request->pesquisa;
        $config = EcommerceConfig::findOrfail($request->loja_id);
        $categorias = $this->_getCategorias($config->empresa_id);

        $carrinho = $this->_getCarrinho();

        $data = Produto::where('produtos.empresa_id', $config->empresa_id)
        ->select('produtos.*')
        ->where('produtos.status', 1)
        ->where('produtos.ecommerce', 1)
        ->when(!empty($categoria_pesquisa), function ($query) use ($categoria_pesquisa) {
            return $query->join('categoria_produtos', 'categoria_produtos.id', '=', 'produtos.categoria_id')
            ->where('categoria_produtos.hash_ecommerce', $categoria_pesquisa);
        })
        ->when(!empty($pesquisa), function ($query) use ($pesquisa) {
            return $query->where('produtos.nome', 'like', "%$pesquisa%");
        })
        ->get();

        $produtos = [];
        foreach($data as $item){
            if($item->gerenciar_estoque){

                if($item->estoque && $item->estoque->quantidade > 0){
                    array_push($produtos, $item);
                }
            }else{
                array_push($produtos, $item);
            }
        }

        return view('loja.pesquisa', compact('config', 'categorias', 'produtos', 'carrinho', 'categoria_pesquisa', 'pesquisa'));
    }

    public function pesquisaAutocomplete(Request $request)
    {
        $pesquisa = $request->pesquisa;
        $config = EcommerceConfig::findOrfail($request->loja_id);

        $produtos = Produto::where('empresa_id', $config->empresa_id)
            ->where('status', 1)
            ->where('ecommerce', 1)
            ->where('nome', 'like', "%$pesquisa%")
            ->limit(6)
            ->get();

        $results = [];
        foreach ($produtos as $p) {
            $results[] = [
                'nome' => $p->nome,
                'img' => $p->img,
                'valor' => $p->valor_ecommerce,
                'hash_ecommerce' => $p->hash_ecommerce
            ];
        }

        return response()->json($results);
    }

    private function _getCarrinho(){
        $data = [];
        if(isset($_SESSION["session_cart"])){
            $data = Carrinho::where('session_cart', $_SESSION["session_cart"])
            ->first();
        }
        return $data;
    }

    public function produtosDaCategoria(Request $request, $hash){

        $config = EcommerceConfig::findOrfail($request->loja_id);
        $categorias = $this->_getCategorias($config->empresa_id);
        $categoria = CategoriaProduto::where('hash_ecommerce' ,$hash)->first();

        if($categoria == null){
            abort(404);
        }
        $data = Produto::where('empresa_id', $config->empresa_id)
        ->where('categoria_id', $categoria->id)
        ->where('status', 1)
        ->where('ecommerce', 1)->get();

        $produtos = [];
        foreach($data as $item){
            if($item->gerenciar_estoque){

                if($item->estoque && $item->estoque->quantidade > 0){
                    array_push($produtos, $item);
                }
            }else{
                array_push($produtos, $item);
            }
        }

        $carrinho = $this->_getCarrinho();

        return view('loja.produtos_categoria', compact('config', 'categorias', 'categoria', 'produtos', 'carrinho'));
    }

    private function produtosEmDestaque($empresa_id){
        $data =  Produto::where('empresa_id', $empresa_id)
        ->where('destaque_ecommerce', 1)
        ->where('status', 1)
        ->where('ecommerce', 1)->get();
        $produtos = [];
        foreach($data as $item){
            if($item->gerenciar_estoque){

                if($item->estoque && $item->estoque->quantidade > 0){
                    array_push($produtos, $item);
                }
            }else{
                array_push($produtos, $item);
            }
        }
        return $produtos;
    }

    public function produtoDetalhe(Request $request, $hash){
        $config = EcommerceConfig::findOrfail($request->loja_id);
        $produto = Produto::where('empresa_id', $config->empresa_id)
        ->where('hash_ecommerce', $hash)
        ->where('status', 1)->first();

        if($produto == null){
            session()->flash("flash_error", "Produto não encontrado!");
            return redirect()->back();
        }

        $categorias = $this->_getCategorias($config->empresa_id);
        $carrinho = $this->_getCarrinho();

        // Produtos relacionados (mesma categoria, excluindo o atual)
        $relacionados = [];
        if($produto->categoria_id){
            $relacionados = Produto::where('empresa_id', $config->empresa_id)
            ->where('categoria_id', $produto->categoria_id)
            ->where('id', '!=', $produto->id)
            ->where('status', 1)
            ->where('ecommerce', 1)
            ->limit(4)->get();
        }

        $produtosRelacionados = [];
        foreach($relacionados as $item){
            if($item->gerenciar_estoque){
                if($item->estoque && $item->estoque->quantidade > 0){
                    array_push($produtosRelacionados, $item);
                }
            }else{
                array_push($produtosRelacionados, $item);
            }
        }

        return view('loja.produtos_detalhe', compact('config', 'categorias', 'produto', 'carrinho', 'produtosRelacionados'));

    }
}
