<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoSuporte;

class VideoSuporteController extends Controller
{
    public function index(Request $request)
    {
        $pagina = $request->pagina ?? $request->nome;
        $query = VideoSuporte::
        when(!empty($pagina), function ($q) use ($pagina) {
            return $q->where('pagina', 'LIKE', "%$pagina%")
                     ->orWhere('url_video', 'LIKE', "%$pagina%")
                     ->orWhere('url_servidor', 'LIKE', "%$pagina%");
        });

        $stats = [
            'total' => (clone $query)->count(),
        ];

        $data = (clone $query)->orderBy('pagina', 'asc')
                              ->paginate(env("PAGINACAO", 10));

        return view('video_suporte.index', compact('data', 'stats'));
    }

    public function create()
    {
        return view('video_suporte.create');
    }

    public function store(Request $request)
    {
        try {
            VideoSuporte::create($request->all());
            session()->flash('flash_success', 'Video cadastrado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Algo deu errado' . $e->getMessage());
        }
        return redirect()->route('video-suporte.index');
    }

    public function edit($id)
    {
        $item = VideoSuporte::findOrFail($id);
        return view('video_suporte.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = VideoSuporte::findOrFail($id);

        try {
            $item->fill($request->all())->save();
            session()->flash('flash_success', 'Video alterado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Algo deu errado: ' . $e->getMessage());
        }
        return redirect()->route('video-suporte.index');
    }

    public function destroy($id)
    {
        $item = VideoSuporte::findOrFail($id);

        try {
            $item->delete();
            session()->flash('flash_success', 'Video removido com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash_warning', 'Algo deu errado: ' .$e->getMessage());
        }
        return redirect()->route('video-suporte.index');
    }

}
