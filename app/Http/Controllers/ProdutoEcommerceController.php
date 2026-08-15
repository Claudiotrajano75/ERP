<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaProduto;
use App\Models\Produto;

class ProdutoEcommerceController extends Controller
{
    public function categorias(Request $request){
        $nome = $request->nome;
        $ecommerce = $request->ecommerce;

        $data = CategoriaProduto::where('empresa_id', $request->empresa_id)
        ->withCount('produtos')
        ->when(!empty($nome), function ($q) use ($nome) {
            return $q->where('nome', 'LIKE', "%$nome%");
        })
        ->when($ecommerce !== null && $ecommerce !== '', function ($q) use ($ecommerce) {
            return $q->where('ecommerce', $ecommerce);
        })
        ->orderBy('nome', 'asc')
        ->paginate(env("PAGINACAO"));

        $stats = [
            'total' => CategoriaProduto::where('empresa_id', $request->empresa_id)->count(),
            'ativas' => CategoriaProduto::where('empresa_id', $request->empresa_id)->where('ecommerce', 1)->count(),
            'inativas' => CategoriaProduto::where('empresa_id', $request->empresa_id)->where(function($q){ $q->where('ecommerce', 0)->orWhereNull('ecommerce'); })->count(),
            'total_produtos' => Produto::where('empresa_id', $request->empresa_id)->where('ecommerce', 1)->count(),
        ];

        return view('ecommerce.categorias.index', compact('data', 'stats'));
    }

    public function index(Request $request){
        $status = $request->status;
        $nome = $request->nome;
        $categoria_id = $request->categoria_id;

        $data = Produto::where('empresa_id', $request->empresa_id)
        ->with('categoria')
        ->when(!empty($nome), function ($q) use ($nome) {
            return $q->where('nome', 'LIKE', "%$nome%");
        })
        ->when($status !== null && $status !== '', function ($q) use ($status) {
            return $q->where('status', $status);
        })
        ->when(!empty($categoria_id), function ($q) use ($categoria_id) {
            return $q->where('categoria_id', $categoria_id);
        })
        ->where('ecommerce', 1)
        ->orderBy('nome', 'asc')
        ->paginate(env("PAGINACAO"));

        $categorias = CategoriaProduto::where('empresa_id', $request->empresa_id)
            ->orderBy('nome', 'asc')
            ->pluck('nome', 'id')
            ->all();

        $stats = [
            'total' => Produto::where('empresa_id', $request->empresa_id)->where('ecommerce', 1)->count(),
            'ativos' => Produto::where('empresa_id', $request->empresa_id)->where('ecommerce', 1)->where('status', 1)->count(),
            'ocultos' => Produto::where('empresa_id', $request->empresa_id)->where('ecommerce', 1)->where('status', 0)->count(),
            'categorias_count' => CategoriaProduto::where('empresa_id', $request->empresa_id)->where('ecommerce', 1)->count(),
        ];

        return view('ecommerce.produtos.index', compact('data', 'categorias', 'stats'));
    }
}
