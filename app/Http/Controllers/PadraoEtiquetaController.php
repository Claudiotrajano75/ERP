<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloEtiqueta;

class PadraoEtiquetaController extends Controller
{
    public function index(Request $request)
    {
        $query = ModeloEtiqueta::where('empresa_id', null)
        ->when(!empty($request->nome), function ($q) use ($request) {
            return $q->where('nome', 'LIKE', "%$request->nome%");
        })
        ->when(!empty($request->tipo), function ($q) use ($request) {
            return $q->where('tipo', $request->tipo);
        });

        $stats = [
            'total'    => (clone $query)->count(),
            'simples'  => (clone $query)->where('tipo', 'simples')->count(),
            'gondola'  => (clone $query)->where('tipo', 'gondola')->count(),
        ];

        $data = (clone $query)->orderBy('nome', 'asc')
                              ->paginate(env("PAGINACAO", 10));

        return view('padrao_etiqueta.index', compact('data', 'stats'));
    }

    public function create()
    {
        return view('padrao_etiqueta.create');
    }

    public function edit($id)
    {
        $item = ModeloEtiqueta::findOrFail($id);
        return view('padrao_etiqueta.edit', compact('item'));
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'nome_empresa' => $request->nome_empresa ? 1 : 0,
                'nome_produto' => $request->nome_produto ? 1 : 0,
                'valor_produto' => $request->valor_produto ? 1 : 0,
                'codigo_produto' => $request->codigo_produto ? 1 : 0,
                'codigo_barras_numerico' => $request->codigo_barras_numerico ? 1 : 0,
                'empresa_id' => null
            ]);
            
            ModeloEtiqueta::create($request->all());
            session()->flash("flash_success", "Modelo criado com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado: '. $e->getMessage());
        }
        return redirect()->route('padroes-etiqueta.index');
    }

    public function update(Request $request, $id)
    {
        try {
            $item = ModeloEtiqueta::findOrFail($id);

            $request->merge([
                'nome_empresa' => $request->nome_empresa ? 1 : 0,
                'nome_produto' => $request->nome_produto ? 1 : 0,
                'valor_produto' => $request->valor_produto ? 1 : 0,
                'codigo_produto' => $request->codigo_produto ? 1 : 0,
                'codigo_barras_numerico' => $request->codigo_barras_numerico ? 1 : 0,
                'empresa_id' => null
            ]);
            
            $item->fill($request->all())->save();
            session()->flash("flash_success", "Modelo alterado com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado: '. $e->getMessage());
        }
        return redirect()->route('padroes-etiqueta.index');
    }

    public function destroy($id)
    {
        $item = ModeloEtiqueta::findOrFail($id);
        try {
            $item->delete();
            session()->flash("flash_success", "Modelo removido com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado: '. $e->getMessage());
        }
        return redirect()->back();
    }
}
