@extends('layouts.app', ['title' => 'Fluxo de Caixa Projetado & DRE'])

@section('css')
<style type="text/css">
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    border: 0;
    border-radius: 16px;
    padding: 18px 20px;
    height: 100%;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
}
.stat-card:hover { transform: translateY(-3px); }
.stat-card::after {
    content: '';
    position: absolute;
    top: -44px;
    right: -44px;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
}
.stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
.stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.32); }
.stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
.stat-cyan   { background: linear-gradient(135deg,#06b6d4,#0891b2); box-shadow: 0 6px 18px rgba(8,145,178,.32); }

.stat-card .st-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: rgba(255,255,255,.85);
}
.stat-card .st-value {
    font-size: 24px;
    font-weight: 800;
    color: #fff;
    margin-top: 4px;
    line-height: 1.1;
}
.stat-card .st-sub {
    font-size: 11.5px;
    color: rgba(255,255,255,.75);
    margin-top: 4px;
}
.stat-card .st-icon {
    width: 46px;
    height: 46px;
    border-radius: 13px;
    background: rgba(255,255,255,.22);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

/* ─── Filtro ─── */
.filter-wrap {
    background: #fff;
    border: 1px solid #e9ecf3;
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(16,24,40,.04);
    padding: 18px 20px;
    margin-bottom: 20px;
}
.filter-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.filter-title i { color: #4f46e5; margin-right: 6px; }
.filter-wrap label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #8c8ca6;
    margin-bottom: 6px;
}
.filter-wrap .form-control {
    height: 40px;
    border-radius: 10px;
    border: 1px solid #dcdce9;
    font-size: 13.5px;
    color: #1f2937;
    background: #fcfdfe;
    transition: all .15s ease;
}
.filter-wrap .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}

/* ─── Tabela e DRE ─── */
.tb-wrap {
    border-radius: 14px;
    border: 1px solid #eef0f5;
    overflow: hidden;
    background: #fff;
}
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8eaf6;
    white-space: nowrap;
}
.tb-wrap tbody td {
    padding: 13px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13.5px;
    color: #374151;
}
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

.dre-table th, .dre-table td {
    padding: 12px 18px;
    vertical-align: middle;
}
.dre-header-row {
    background: #f8fafc;
    font-weight: 700;
    color: #1e293b;
    border-top: 2px solid #e2e8f0;
    border-bottom: 2px solid #e2e8f0;
}
.dre-subitem td {
    padding-left: 36px;
    color: #64748b;
}
.dre-total-positive {
    background: #ecfdf5;
    color: #065f46;
    font-weight: 800;
}
.dre-total-negative {
    background: #fef2f2;
    color: #991b1b;
    font-weight: 800;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-line-chart-line"></i>
                            Fluxo de Caixa Projetado & DRE
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Previsibilidade financeira com saldo futuro acumulado e demonstrativo de resultados.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        <button onclick="window.print()" class="dash-btn dash-btn-light">
                            <i class="ri-printer-line align-middle me-1"></i> Imprimir / PDF
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ Filtro de Período ═══ -->
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="filter-title"><i class="ri-filter-3-line"></i> Período de Projeção</span>
                    </div>
                    <form method="GET" action="{{ route('fluxo-caixa.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4 col-sm-6">
                            <label><i class="ri-calendar-line me-1"></i> Data Início (Projeção)</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><i class="ri-calendar-line me-1"></i> Data Fim (Projeção)</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                        </div>
                        <div class="col-md-4 col-12 d-flex gap-2">
                            <button type="submit" class="dash-btn dash-btn-primary flex-fill justify-content-center">
                                <i class="ri-refresh-line"></i> Atualizar Projeção
                            </button>
                            <a href="{{ route('fluxo-caixa.index') }}" class="dash-btn dash-btn-light" title="Limpar Filtro">
                                <i class="ri-eraser-line"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- ═══ KPIs Cards ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-cyan">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Saldo Atual Contas</div>
                                    <div class="st-value">R$ {{ __moeda($saldoAtualContas) }}</div>
                                    <div class="st-sub">Disponibilidade Imediata</div>
                                </div>
                                <div class="st-icon"><i class="ri-bank-card-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">(+) Entradas Previstas</div>
                                    <div class="st-value">R$ {{ __moeda($totalEntradasProjetadas) }}</div>
                                    <div class="st-sub">Recebimentos no período</div>
                                </div>
                                <div class="st-icon"><i class="ri-arrow-down-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">(-) Saídas Previstas</div>
                                    <div class="st-value">R$ {{ __moeda($totalSaidasProjetadas) }}</div>
                                    <div class="st-sub">Pagamentos no período</div>
                                </div>
                                <div class="st-icon"><i class="ri-arrow-up-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @php
                        $saldoFinalPrevisto = $saldoAtualContas + $totalEntradasProjetadas - $totalSaidasProjetadas;
                    @endphp
                    <div class="col-6 col-lg-3">
                        <div class="stat-card {{ $saldoFinalPrevisto >= 0 ? 'stat-indigo' : 'stat-red' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">(=) Saldo Final Previsto</div>
                                    <div class="st-value">R$ {{ __moeda($saldoFinalPrevisto) }}</div>
                                    <div class="st-sub">Projeção ao fim do período</div>
                                </div>
                                <div class="st-icon"><i class="ri-funds-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Nav Tabs ═══ -->
                <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold px-4 py-2" id="pills-grafico-tab" data-bs-toggle="pill" data-bs-target="#pills-grafico" type="button" role="tab">
                            <i class="ri-bar-chart-2-line me-1"></i> Gráfico de Projeção
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2" id="pills-tabela-tab" data-bs-toggle="pill" data-bs-target="#pills-tabela" type="button" role="tab">
                            <i class="ri-table-line me-1"></i> Tabela Dia a Dia ({{ count($projecaoDiaria) }} dias)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2" id="pills-dre-tab" data-bs-toggle="pill" data-bs-target="#pills-dre" type="button" role="tab">
                            <i class="ri-file-list-3-line me-1"></i> DRE Gerencial ({{ $dre['periodo_nome'] }})
                        </button>
                    </li>
                </ul>

                <!-- ═══ Tab Contents ═══ -->
                <div class="tab-content" id="pills-tabContent">
                    <!-- TAB 1: GRÁFICO -->
                    <div class="tab-pane fade show active" id="pills-grafico" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 text-dark">
                                    <i class="ri-line-chart-fill text-indigo me-1"></i> Evolução de Entradas, Saídas e Saldo Acumulado
                                </h5>
                                <div id="chart-fluxo-caixa" style="min-height: 380px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: TABELA DIA A DIA -->
                    <div class="tab-pane fade" id="pills-tabela" role="tabpanel">
                        <div class="tb-wrap table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Data</th>
                                        <th>Dia</th>
                                        <th class="text-end text-success">(+) Entradas</th>
                                        <th class="text-end text-danger">(-) Saídas</th>
                                        <th class="text-end">(=) Saldo do Dia</th>
                                        <th class="text-end pe-4">Saldo Acumulado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projecaoDiaria as $dia)
                                    @if($dia['entradas'] > 0 || $dia['saidas'] > 0 || $loop->first || $loop->last)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $dia['data_formatada'] }}</td>
                                        <td><span class="badge bg-light text-dark">{{ strtoupper($dia['dia_semana']) }}</span></td>
                                        <td class="text-end text-success fw-bold">
                                            @if($dia['entradas'] > 0)
                                                + R$ {{ __moeda($dia['entradas']) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end text-danger fw-bold">
                                            @if($dia['saidas'] > 0)
                                                - R$ {{ __moeda($dia['saidas']) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold {{ $dia['saldo_dia'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            R$ {{ __moeda($dia['saldo_dia']) }}
                                        </td>
                                        <td class="text-end pe-4 fw-bold {{ $dia['saldo_acumulado'] >= 0 ? 'text-indigo' : 'text-danger' }}">
                                            R$ {{ __moeda($dia['saldo_acumulado']) }}
                                        </td>
                                    </tr>
                                    @endif
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Nenhuma movimentação projetada para o período selecionado.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: DRE GERENCIAL -->
                    <div class="tab-pane fade" id="pills-dre" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0 text-dark">
                                        <i class="ri-file-list-3-line text-primary me-1"></i> Demonstrativo de Resultados do Exercício (DRE)
                                    </h5>
                                    <span class="badge bg-primary px-3 py-2 fs-6">Período: {{ $dre['periodo_nome'] }}</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table dre-table table-bordered mb-0">
                                        <tbody>
                                            <tr class="dre-header-row">
                                                <td><strong>1. RECEITA OPERACIONAL BRUTA</strong></td>
                                                <td class="text-end fw-bold text-success" style="width: 200px;">R$ {{ __moeda($dre['receita_bruta']) }}</td>
                                            </tr>
                                            <tr class="dre-subitem">
                                                <td>Vendas NF-e (Modelo 55)</td>
                                                <td class="text-end">R$ {{ __moeda($dre['receita_nfe']) }}</td>
                                            </tr>
                                            <tr class="dre-subitem">
                                                <td>Vendas NFC-e / PDV (Modelo 65)</td>
                                                <td class="text-end">R$ {{ __moeda($dre['receita_nfce']) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger"><strong>(-) Deduções da Receita Bruta (Descontos)</strong></td>
                                                <td class="text-end text-danger fw-bold">- R$ {{ __moeda($dre['deducoes']) }}</td>
                                            </tr>
                                            <tr class="dre-header-row">
                                                <td><strong>(=) RECEITA OPERACIONAL LÍQUIDA</strong></td>
                                                <td class="text-end fw-bold text-dark">R$ {{ __moeda($dre['receita_liquida']) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger"><strong>(-) Custo das Mercadorias Vendidas / Compras (CMV)</strong></td>
                                                <td class="text-end text-danger fw-bold">- R$ {{ __moeda($dre['cmv_compras']) }}</td>
                                            </tr>
                                            <tr class="{{ $dre['lucro_bruto'] >= 0 ? 'dre-total-positive' : 'dre-total-negative' }}">
                                                <td><strong>(=) LUCRO BRUTO (Margem Bruta: {{ $dre['margem_bruta'] }}%)</strong></td>
                                                <td class="text-end">R$ {{ __moeda($dre['lucro_bruto']) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger"><strong>(-) Despesas Operacionais / Administrativas (Contas Pagas)</strong></td>
                                                <td class="text-end text-danger fw-bold">- R$ {{ __moeda($dre['despesas_operacionais']) }}</td>
                                            </tr>
                                            <tr class="{{ $dre['resultado_liquido'] >= 0 ? 'dre-total-positive' : 'dre-total-negative' }}" style="font-size: 1.15rem;">
                                                <td><strong>(=) RESULTADO LÍQUIDO DO EXERCÍCIO (Margem Líquida: {{ $dre['margem_liquida'] }}%)</strong></td>
                                                <td class="text-end">R$ {{ __moeda($dre['resultado_liquido']) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        series: [
            {
                name: 'Entradas Previstas',
                type: 'column',
                data: {!! json_encode($entradasGrafico) !!}
            },
            {
                name: 'Saídas Previstas',
                type: 'column',
                data: {!! json_encode($saidasGrafico) !!}
            },
            {
                name: 'Saldo Projetado',
                type: 'line',
                data: {!! json_encode($saldoGrafico) !!}
            }
        ],
        chart: {
            height: 380,
            type: 'line',
            toolbar: { show: true },
            animations: { enabled: true }
        },
        stroke: {
            width: [0, 0, 3],
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
                borderRadius: 4
            }
        },
        colors: ['#059669', '#dc2626', '#4f46e5'],
        labels: {!! json_encode($labelsGrafico) !!},
        xaxis: {
            type: 'category'
        },
        yaxis: [
            {
                title: { text: 'Movimentação do Dia (R$)' }
            },
            {
                opposite: true,
                title: { text: 'Saldo Acumulado (R$)' }
            }
        ],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return "R$ " + y.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                    }
                    return y;
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-fluxo-caixa"), options);
    chart.render();
});
</script>
@endsection
