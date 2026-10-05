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

        $data = (clone $query)->with(['empresa', 'plano', 'financeiro'])->orderBy('id', 'desc')
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

    public function update(Request $request, $id)
    {
        $item = PlanoEmpresa::findOrFail($id);
        try {
            $data_expiracao = $request->data_expiracao;
            if (!empty($data_expiracao)) {
                if (strpos($data_expiracao, '/') !== false) {
                    $data_expiracao = \Carbon\Carbon::createFromFormat('d/m/Y', $data_expiracao)->format('Y-m-d');
                }
            } else {
                $data_expiracao = $item->data_expiracao;
            }

            $valor = $request->valor !== null ? __convert_value_bd($request->valor) : $item->valor;
            $plano_id = $request->plano_id ?: $item->plano_id;
            $forma_pagamento = $request->forma_pagamento ?: $item->forma_pagamento;

            $item->update([
                'plano_id' => $plano_id,
                'data_expiracao' => $data_expiracao,
                'valor' => $valor,
                'forma_pagamento' => $forma_pagamento,
            ]);

            // Atualiza ou cria o registro financeiro vinculado
            $financeiro = FinanceiroPlano::where('plano_empresa_id', $item->id)->first();
            if ($financeiro) {
                $dadosFinanceiro = [
                    'plano_id' => $plano_id,
                    'valor' => $valor,
                    'tipo_pagamento' => $forma_pagamento,
                ];
                if ($request->filled('status_pagamento')) {
                    $dadosFinanceiro['status_pagamento'] = $request->status_pagamento;
                }
                $financeiro->update($dadosFinanceiro);
            } else if ($request->filled('status_pagamento')) {
                FinanceiroPlano::create([
                    'empresa_id' => $item->empresa_id,
                    'plano_id' => $plano_id,
                    'valor' => $valor,
                    'tipo_pagamento' => $forma_pagamento,
                    'status_pagamento' => $request->status_pagamento,
                    'plano_empresa_id' => $item->id
                ]);
            }

            session()->flash("flash_success", "Atribuição de plano atualizada com sucesso!");
        } catch (\Exception $e) {
            session()->flash("flash_error", "Algo deu errado ao atualizar: " . $e->getMessage());
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
