<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanoEmpresa;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\FinanceiroPlano;

class GerenciarPlanoController extends Controller
{
    public function index(Request $request)
    {
        $empresa_id = $request->empresa;
        $planos = Plano::orderBy('nome', 'asc')->get();
        $query = PlanoEmpresa::when(!empty($empresa_id), function ($query) use ($empresa_id) {
            return $query->where('empresa_id', $empresa_id);
        });

        $stats = [
            'total'       => (clone $query)->count(),
            'ativas'      => (clone $query)->whereDate('data_expiracao', '>=', date('Y-m-d'))->count(),
            'expiradas'   => (clone $query)->whereDate('data_expiracao', '<', date('Y-m-d'))->count(),
            'valor_total' => (clone $query)->sum('valor'),
        ];

        $data = (clone $query)->with(['empresa', 'plano'])->orderBy('id', 'desc')
                              ->paginate(env("PAGINACAO", 10));

        $empresa = null;
        if($empresa_id){
            $empresa = Empresa::find($empresa_id);
        }
        return view('gerencia_planos.index', compact('data', 'planos', 'empresa', 'stats'));
    }

    public function store(Request $request)
    {
        try {
            $plano = Plano::findOrfail($request->plano_id);
            $intervalo = $plano->intervalo_dias;
            $exp = date('Y-m-d', strtotime(date('Y-m-d') . "+ $intervalo days"));

            $planoEmpresa = PlanoEmpresa::create([
                'empresa_id' => $request->empresa_atribuir,
                'plano_id' => $request->plano_id,
                'data_expiracao' => $exp,
                'valor' => __convert_value_bd($request->valor),
                'forma_pagamento' => $request->forma_pagamento
            ]);

            FinanceiroPlano::create([
                'empresa_id' => $request->empresa_atribuir,
                'plano_id' => $request->plano_id,
                'valor' => __convert_value_bd($request->valor),
                'tipo_pagamento' => $request->forma_pagamento,
                'status_pagamento' => $request->status_pagamento,
                'plano_empresa_id' => $planoEmpresa->id
            ]);
            session()->flash("flash_success", "Plano atribuído!");
        } catch (\Exception $e) {
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $item = PlanoEmpresa::findOrFail($id);
        try {
            $financeiro = FinanceiroPlano::where('plano_empresa_id', $item->id)->first();
            if($financeiro){
                $financeiro->delete();
            }
            $item->delete();
            session()->flash("flash_success", "Apagado com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", 'Algo deu errado.', $e->getMessage());
        }
        return redirect()->back();
    }
}
