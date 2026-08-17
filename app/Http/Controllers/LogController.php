<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcaoLog;
use App\Models\Empresa;

class LogController extends Controller
{

    public function index(Request $request){

        $empresa_id = $request->empresa_id ?? $request->empresa;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $local = $request->get('local');
        $acao = $request->get('acao');

        $query = AcaoLog::
        when(!empty($empresa_id), function ($q) use ($empresa_id) {
            return $q->where('empresa_id', $empresa_id);
        })
        ->when(!empty($start_date), function ($q) use ($start_date) {
            return $q->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($q) use ($end_date) {
            return $q->whereDate('created_at', '<=', $end_date);
        })
        ->when(!empty($local), function ($q) use ($local) {
            return $q->where('local', $local);
        })
        ->when(!empty($acao), function ($q) use ($acao) {
            return $q->where('acao', $acao);
        });

        $stats = [
            'total'     => (clone $query)->count(),
            'cadastros' => (clone $query)->where('acao', 'cadastrar')->count(),
            'edicoes'   => (clone $query)->where('acao', 'editar')->count(),
            'exclusoes' => (clone $query)->where('acao', 'excluir')->count(),
        ];

        $data = (clone $query)->orderBy('created_at', 'desc')
                              ->paginate(env("PAGINACAO", 10));

        $empresa = null;
        if($empresa_id){
            $empresa = Empresa::find($empresa_id);
        }

        $empresas = Empresa::orderBy('nome')->pluck('nome', 'id')->all();

        return view('logs.index', compact('data', 'empresa', 'empresas', 'stats'));
    }
}
