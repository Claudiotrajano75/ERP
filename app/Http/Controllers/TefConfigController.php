<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TefMultiPlusCard;
use App\Models\User;

class TefConfigController extends Controller
{
    public function index(Request $request){
        $data = TefMultiPlusCard::where('empresa_id', $request->empresa_id)
        ->get();

        $stats = [
            'total' => $data->count(),
            'ativos' => $data->where('status', 1)->count(),
            'inativos' => $data->where('status', 0)->count(),
        ];

        return view('tef_config.index', compact('data', 'stats'));
    }

    public function create(Request $request){
        $usuarios = User::where('usuario_empresas.empresa_id', $request->empresa_id)
        ->join('usuario_empresas', 'users.id', '=', 'usuario_empresas.usuario_id')
        ->select('users.*')
        ->get();

        return view('tef_config.create', compact('usuarios'));
    }

    public function edit(Request $request, $id){
        $item = TefMultiPlusCard::findOrFail($id);
        $usuarios = User::where('usuario_empresas.empresa_id', $request->empresa_id)
        ->join('usuario_empresas', 'users.id', '=', 'usuario_empresas.usuario_id')
        ->select('users.*')
        ->get();

        return view('tef_config.edit', compact('usuarios', 'item'));
    }

    public function store(Request $request){
        try{
            TefMultiPlusCard::create($request->all());
            session()->flash("flash_success", "Configuração de TEF salva com sucesso!");
        }catch(\Exception $e){
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('tef-config.index');
    }

    public function update(Request $request, $id){
        $item = TefMultiPlusCard::findOrFail($id);
        try{
            $item->fill($request->all())->save();
            session()->flash("flash_success", "Configuração de TEF atualizada com sucesso!");
        }catch(\Exception $e){
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('tef-config.index');
    }

    public function destroy($id){
        $item = TefMultiPlusCard::findOrFail($id);
        try{
            $item->delete();
            session()->flash("flash_success", "Configuração de TEF removida com sucesso!");
        }catch(\Exception $e){
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('tef-config.index');
    }
}
