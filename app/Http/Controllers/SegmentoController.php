<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Segmento;


class SegmentoController extends Controller
{

    public function index(Request $request)
    {
        $query = Segmento::when(!empty($request->nome), function ($q) use ($request) {
            return $q->where('nome', 'LIKE', "%$request->nome%");
        });

        $stats = [
            'total'    => (clone $query)->count(),
            'ativos'   => (clone $query)->where('status', 1)->count(),
            'inativos' => (clone $query)->where('status', 0)->count(),
        ];

        $data = (clone $query)->orderBy('nome', 'asc')->paginate(env("PAGINACAO", 10));
        return view('segmentos.index', compact('data', 'stats'));
    }

    public function create()
    {
        return view('segmentos.create');
    }

    public function store(Request $request)
    {
        try {
            Segmento::create($request->all());
            session()->flash('flash_success', 'Cadastrado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Algo deu errado' . $e->getMessage());
        }
        return redirect()->route('segmentos.index');
    }

    public function edit($id)
    {
        $item = Segmento::findOrFail($id);
        return view('segmentos.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Segmento::findOrFail($id);
        try {
            $item->fill($request->all())->save();
            session()->flash('flash_success', 'Alterado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Algo deu errado: ' . $e->getMessage());
        }
        return redirect()->route('segmentos.index');
    }

    public function destroy($id)
    {
        $item = Segmento::findOrFail($id);
        try {
            $item->delete();
            session()->flash('flash_success', 'Removido com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash_warning', 'Marca esta sendo usada em algum produto');
        }
        return redirect()->route('segmentos.index');
    }

}
