<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitacaoDemonstracao;

class SolicitacaoDemonstracaoController extends Controller
{
    public function index(Request $request)
    {
        $base = SolicitacaoDemonstracao::query();

        $data = (clone $base)
            ->when(!empty($request->busca), function ($q) use ($request) {
                $termo = '%' . $request->busca . '%';
                return $q->where(function ($sub) use ($termo) {
                    $sub->where('nome', 'like', $termo)
                        ->orWhere('email', 'like', $termo)
                        ->orWhere('empresa', 'like', $termo)
                        ->orWhere('telefone', 'like', $termo);
                });
            })
            ->when(!empty($request->status), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->orderByRaw("CASE WHEN status = 'novo' THEN 0 WHEN status = 'em_atendimento' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc')
            ->paginate(env('PAGINACAO', 20));

        $stats = [
            'total'          => (clone $base)->count(),
            'novos'          => (clone $base)->where('status', 'novo')->count(),
            'em_atendimento' => (clone $base)->where('status', 'em_atendimento')->count(),
            'concluidos'     => (clone $base)->where('status', 'concluido')->count(),
        ];

        return view('solicitacoes_demo.index', compact('data', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = SolicitacaoDemonstracao::findOrFail($id);
        $request->validate([
            'status' => 'required|in:novo,em_atendimento,concluido',
        ]);

        $item->status = $request->status;
        if ($request->has('observacao_admin')) {
            $item->observacao_admin = $request->observacao_admin;
        }
        $item->save();

        session()->flash('flash_success', 'Status da solicitação atualizado com sucesso!');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $item = SolicitacaoDemonstracao::findOrFail($id);
        try {
            $item->delete();
            session()->flash('flash_success', 'Solicitação removida com sucesso!');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Erro ao remover: ' . $e->getMessage());
        }
        return redirect()->back();
    }
}

