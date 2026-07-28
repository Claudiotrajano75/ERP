<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PlanoEmpresa;
use App\Models\FinanceiroPlano;
use App\Models\Plano;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpresas = Empresa::where('tipo_contador', 0)->count();
        $totalEmpresasAtivas = Empresa::where('tipo_contador', 0)->where('status', 1)->count();
        $totalEmpresasInativas = Empresa::where('tipo_contador', 0)->where('status', 0)->count();
        $novasEmpresasMes = Empresa::where('tipo_contador', 0)
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        $empresasComPlano = PlanoEmpresa::where('data_expiracao', '>=', date('Y-m-d'))
            ->distinct('empresa_id')
            ->count('empresa_id');

        $empresasPlanoVencido = PlanoEmpresa::where('data_expiracao', '<', date('Y-m-d'))
            ->distinct('empresa_id')
            ->count('empresa_id');

        $faturamentoMes = FinanceiroPlano::where('status_pagamento', 'recebido')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('valor');

        $planosPendentes = FinanceiroPlano::where('status_pagamento', 'pendente')
            ->sum('valor');

        $totalPlanosPendentesCount = FinanceiroPlano::where('status_pagamento', 'pendente')->count();

        $distribuicaoPlanos = Plano::select('planos.id', 'planos.nome', DB::raw('count(plano_empresas.id) as total'))
            ->leftJoin('plano_empresas', 'plano_empresas.plano_id', '=', 'planos.id')
            ->groupBy('planos.id', 'planos.nome')
            ->orderBy('total', 'desc')
            ->get();

        $ultimasEmpresas = Empresa::where('tipo_contador', 0)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $ultimosPagamentos = FinanceiroPlano::with('empresa', 'plano')
            ->where('status_pagamento', 'recebido')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $planosVencendo = PlanoEmpresa::with('empresa', 'plano')
            ->where('data_expiracao', '>=', date('Y-m-d'))
            ->where('data_expiracao', '<=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('data_expiracao', 'asc')
            ->take(10)
            ->get();

        $tributacoes = Empresa::where('tipo_contador', 0)
            ->where('tributacao', '!=', '')
            ->select('tributacao', DB::raw('count(*) as total'))
            ->groupBy('tributacao')
            ->orderBy('total', 'desc')
            ->get();

        $pagamentosPendentes = FinanceiroPlano::with('empresa', 'plano')
            ->where('status_pagamento', 'pendente')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmpresas',
            'totalEmpresasAtivas',
            'totalEmpresasInativas',
            'novasEmpresasMes',
            'empresasComPlano',
            'empresasPlanoVencido',
            'faturamentoMes',
            'planosPendentes',
            'totalPlanosPendentesCount',
            'distribuicaoPlanos',
            'ultimasEmpresas',
            'ultimosPagamentos',
            'planosVencendo',
            'tributacoes',
            'pagamentosPendentes'
        ));
    }

    public function graficoNovasEmpresas(Request $request)
    {
        $meses = 12;
        $data = [];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = date('m', strtotime("-$i months"));
            $ano = date('Y', strtotime("-$i months"));

            $total = Empresa::where('tipo_contador', 0)
                ->whereMonth('created_at', $mes)
                ->whereYear('created_at', $ano)
                ->count();

            $data[] = [
                'label' => $this->getMesNome($mes - 1) . "/$ano",
                'valor' => $total
            ];
        }

        return response()->json($data, 200);
    }

    public function graficoFaturamentoPlanos(Request $request)
    {
        $meses = 12;
        $data = [];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = date('m', strtotime("-$i months"));
            $ano = date('Y', strtotime("-$i months"));

            $total = FinanceiroPlano::where('status_pagamento', 'recebido')
                ->whereMonth('created_at', $mes)
                ->whereYear('created_at', $ano)
                ->sum('valor');

            $pendente = FinanceiroPlano::where('status_pagamento', 'pendente')
                ->whereMonth('created_at', $mes)
                ->whereYear('created_at', $ano)
                ->sum('valor');

            $data[] = [
                'label' => $this->getMesNome($mes - 1) . "/$ano",
                'valor' => (float) $total,
                'pendente' => (float) $pendente
            ];
        }

        return response()->json($data, 200);
    }

    private function getMesNome($m)
    {
        $meses = [
            'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
        ];
        if ($m < 0 || $m > 11) return '---';
        return $meses[$m];
    }
}
