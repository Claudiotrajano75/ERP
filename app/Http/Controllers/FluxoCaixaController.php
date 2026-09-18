<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContaReceber;
use App\Models\ContaPagar;
use App\Models\ContaEmpresa;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\ItemNfe;
use App\Models\ItemNfce;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FluxoCaixaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $empresa_id = request()->empresa_id ?? (auth()->user() ? auth()->user()->empresa_id : null);
        $locais = __getLocaisAtivoUsuario();
        $locaisIds = $locais ? $locais->pluck('id')->toArray() : [];
        $local_id = $request->get('local_id');

        // Filtro de data: padrão são os próximos 30 dias a partir do início do mês atual
        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->get('end_date', Carbon::now()->endOfMonth()->addMonth()->format('Y-m-d'));

        $dataInicio = Carbon::parse($start_date);
        $dataFim = Carbon::parse($end_date);

        // 1. Saldo Atual em Contas Bancárias / Caixa
        $saldoAtualContas = ContaEmpresa::where('empresa_id', $empresa_id)
            ->where('status', 1)
            ->when($local_id, function($q) use ($local_id) {
                return $q->where('local_id', $local_id);
            })
            ->sum('saldo');

        // 2. Contas a Receber no período
        $contasReceber = ContaReceber::where('empresa_id', $empresa_id)
            ->whereBetween('data_vencimento', [$start_date, $end_date])
            ->when($local_id, function($q) use ($local_id) {
                return $q->where('local_id', $local_id);
            })
            ->with('cliente')
            ->orderBy('data_vencimento', 'asc')
            ->get();

        // 3. Contas a Pagar no período
        $contasPagar = ContaPagar::where('empresa_id', $empresa_id)
            ->whereBetween('data_vencimento', [$start_date, $end_date])
            ->when($local_id, function($q) use ($local_id) {
                return $q->where('local_id', $local_id);
            })
            ->with('fornecedor')
            ->orderBy('data_vencimento', 'asc')
            ->get();

        // 4. Montar Projeção Diária
        $periodo = CarbonPeriod::create($dataInicio, $dataFim);
        $projecaoDiaria = [];
        $saldoAcumulado = $saldoAtualContas;

        $totalEntradasProjetadas = 0;
        $totalSaidasProjetadas = 0;

        // Indexar por data
        $receberPorData = $contasReceber->groupBy(function($item) {
            return Carbon::parse($item->data_vencimento)->format('Y-m-d');
        });

        $pagarPorData = $contasPagar->groupBy(function($item) {
            return Carbon::parse($item->data_vencimento)->format('Y-m-d');
        });

        $labelsGrafico = [];
        $entradasGrafico = [];
        $saidasGrafico = [];
        $saldoGrafico = [];

        foreach ($periodo as $date) {
            $dataStr = $date->format('Y-m-d');
            $dataPt = $date->format('d/m');

            $entradasDia = isset($receberPorData[$dataStr]) ? $receberPorData[$dataStr]->sum('valor_integral') : 0;
            $saidasDia = isset($pagarPorData[$dataStr]) ? $pagarPorData[$dataStr]->sum('valor_integral') : 0;

            $saldoDia = $entradasDia - $saidasDia;
            $saldoAcumulado += $saldoDia;

            $totalEntradasProjetadas += $entradasDia;
            $totalSaidasProjetadas += $saidasDia;

            $projecaoDiaria[] = [
                'data' => $dataStr,
                'data_formatada' => $date->format('d/m/Y'),
                'dia_semana' => $date->locale('pt_BR')->isoFormat('ddd'),
                'entradas' => $entradasDia,
                'saidas' => $saidasDia,
                'saldo_dia' => $saldoDia,
                'saldo_acumulado' => $saldoAcumulado,
                'itens_receber' => $receberPorData[$dataStr] ?? [],
                'itens_pagar' => $pagarPorData[$dataStr] ?? []
            ];

            $labelsGrafico[] = $dataPt;
            $entradasGrafico[] = round($entradasDia, 2);
            $saidasGrafico[] = round($saidasDia, 2);
            $saldoGrafico[] = round($saldoAcumulado, 2);
        }

        // 5. Demonstrativo de Resultados (DRE Gerencial) do Mês Selecionado (ou período de start_date até o fim do mesmo mês)
        $dreMesInicio = Carbon::parse($start_date)->startOfMonth()->format('Y-m-d 00:00:00');
        $dreMesFim = Carbon::parse($start_date)->endOfMonth()->format('Y-m-d 23:59:59');

        // Receita de Vendas NF-e (aprovadas, tpNF=1)
        $receitaNfe = Nfe::where('empresa_id', $empresa_id)
            ->where('tpNF', 1)
            ->where('estado', 'aprovado')
            ->whereBetween('created_at', [$dreMesInicio, $dreMesFim])
            ->sum('total');

        // Receita de Vendas NFC-e (aprovadas)
        $receitaNfce = Nfce::where('empresa_id', $empresa_id)
            ->where('estado', 'aprovado')
            ->whereBetween('created_at', [$dreMesInicio, $dreMesFim])
            ->sum('total');

        $receitaBruta = $receitaNfe + $receitaNfce;

        // Deduções (Descontos)
        $descontoNfe = Nfe::where('empresa_id', $empresa_id)
            ->where('tpNF', 1)
            ->where('estado', 'aprovado')
            ->whereBetween('created_at', [$dreMesInicio, $dreMesFim])
            ->sum('desconto');

        $descontoNfce = Nfce::where('empresa_id', $empresa_id)
            ->where('estado', 'aprovado')
            ->whereBetween('created_at', [$dreMesInicio, $dreMesFim])
            ->sum('desconto');

        $deducoes = $descontoNfe + $descontoNfce;
        $receitaLiquida = max(0, $receitaBruta - $deducoes);

        // CMV / Compras de Mercadoria (NFe de entrada com tpNF=0)
        $comprasMes = Nfe::where('empresa_id', $empresa_id)
            ->where('tpNF', 0)
            ->whereBetween('created_at', [$dreMesInicio, $dreMesFim])
            ->sum('total');

        $lucroBruto = $receitaLiquida - $comprasMes;
        $margemBruta = $receitaLiquida > 0 ? ($lucroBruto / $receitaLiquida) * 100 : 0;

        // Despesas Operacionais (Contas pagas no mês)
        $despesasOperacionais = ContaPagar::where('empresa_id', $empresa_id)
            ->where('status', 1)
            ->whereBetween('data_pagamento', [substr($dreMesInicio, 0, 10), substr($dreMesFim, 0, 10)])
            ->sum('valor_pago');

        $resultadoLiquido = $lucroBruto - $despesasOperacionais;
        $margemLiquida = $receitaLiquida > 0 ? ($resultadoLiquido / $receitaLiquida) * 100 : 0;

        $dre = [
            'periodo_nome' => Carbon::parse($start_date)->locale('pt_BR')->translatedFormat('F / Y'),
            'receita_bruta' => $receitaBruta,
            'receita_nfe' => $receitaNfe,
            'receita_nfce' => $receitaNfce,
            'deducoes' => $deducoes,
            'receita_liquida' => $receitaLiquida,
            'cmv_compras' => $comprasMes,
            'lucro_bruto' => $lucroBruto,
            'margem_bruta' => round($margemBruta, 2),
            'despesas_operacionais' => $despesasOperacionais,
            'resultado_liquido' => $resultadoLiquido,
            'margem_liquida' => round($margemLiquida, 2),
        ];

        return view('financeiro.fluxo_caixa', compact(
            'saldoAtualContas',
            'start_date',
            'end_date',
            'projecaoDiaria',
            'totalEntradasProjetadas',
            'totalSaidasProjetadas',
            'labelsGrafico',
            'entradasGrafico',
            'saidasGrafico',
            'saldoGrafico',
            'dre'
        ));
    }
}
