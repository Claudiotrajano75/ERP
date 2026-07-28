@extends('layouts.app', ['title' => 'Dashboard Admin'])
@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h3>
                    <i class="ri-dashboard-2-fill"></i> Dashboard Administrativo
                </h3>
                <h5 class="text-muted">Olá, <strong class="text-success">{{ get_name_user() }}</strong> — visão geral do sistema</h5>
                <hr>

                {{-- ========================= CARDS ========================= --}}
                <div class="row">
                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box text-bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Total Empresas</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $totalEmpresas }}</h3>
                                        <small>{{ $novasEmpresasMes }} novas este mês</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-building-4-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box text-bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Com Plano</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $empresasComPlano }}</h3>
                                        <small>planos ativos</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-award-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box text-bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Planos Vencidos</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $empresasPlanoVencido }}</h3>
                                        <small>planos expirados</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-alert-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box text-bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Faturamento</h4>
                                        <h3 class="my-3" style="font-size: 18px;">R$ {{ __moeda($faturamentoMes) }}</h3>
                                        <small>recebido este mês</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box text-bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Pendentes</h4>
                                        <h3 class="my-3" style="font-size: 18px;">R$ {{ __moeda($planosPendentes) }}</h3>
                                        <small>{{ $totalPlanosPendentesCount }} pendências</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-hourglass-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box text-bg-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Ativas</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $totalEmpresasAtivas }}</h3>
                                        <small>{{ $totalEmpresasInativas }} inativas</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ========================= FIM CARDS ========================= --}}

                {{-- ========================= GRÁFICOS LINHA 1 ========================= --}}
                <div class="row mt-2">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-pie-chart-2-line"></i> Distribuição de Planos</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-distribuicao-planos" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-bar-chart-2-line"></i> Novas Empresas por Mês</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-novas-empresas" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-donut-chart-line"></i> Status das Empresas</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-status-empresas" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ========================= FIM GRÁFICOS LINHA 1 ========================= --}}

                {{-- ========================= GRÁFICO FATURAMENTO ========================= --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-line-chart-line"></i> Faturamento Mensal - Planos</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-faturamento-planos" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ========================= FIM GRÁFICO FATURAMENTO ========================= --}}

                {{-- ========================= TABELAS ========================= --}}
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="ri-building-2-line"></i> Últimas Empresas Cadastradas</h5>
                                <a href="{{ route('empresas.index') }}" class="btn btn-sm btn-primary">Ver todas</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Empresa</th>
                                                <th>CNPJ/CPF</th>
                                                <th>Plano</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ultimasEmpresas as $emp)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('empresas.edit', $emp->id) }}" class="text-reset">
                                                        <strong>{{ $emp->nome }}</strong>
                                                    </a>
                                                    <br><small class="text-muted">{{ $emp->nome_fantasia }}</small>
                                                </td>
                                                <td>{{ $emp->cpf_cnpj }}</td>
                                                <td>
                                                    @if($emp->plano)
                                                        <span class="badge bg-success">{{ $emp->plano->plano->nome }}</span>
                                                    @else
                                                        <span class="badge bg-danger">Sem plano</span>
                                                    @endif
                                                </td>
                                                <td>{{ __data_pt($emp->created_at, false) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">Nenhuma empresa cadastrada</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-money-dollar-circle-line"></i> Últimos Pagamentos Recebidos</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Plano</th>
                                                <th>Valor</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ultimosPagamentos as $pag)
                                            <tr>
                                                <td>
                                                    <strong>{{ $pag->empresa ? $pag->empresa->nome : '---' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $pag->plano ? $pag->plano->nome : '---' }}</span>
                                                </td>
                                                <td><strong class="text-success">R$ {{ __moeda($pag->valor) }}</strong></td>
                                                <td>{{ __data_pt($pag->created_at, false) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">Nenhum pagamento recebido</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-alarm-warning-line"></i> Planos Próximos ao Vencimento (30 dias)</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Plano</th>
                                                <th>Vencimento</th>
                                                <th>Dias</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($planosVencendo as $pv)
                                            @php
                                                $diasRestantes = \Carbon\Carbon::parse($pv->data_expiracao)->diffInDays(now());
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $pv->empresa ? $pv->empresa->nome : '---' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $pv->plano ? $pv->plano->nome : '---' }}</span>
                                                </td>
                                                <td>{{ __data_pt($pv->data_expiracao, false) }}</td>
                                                <td>
                                                    @if($diasRestantes <= 5)
                                                        <span class="badge bg-danger">{{ $diasRestantes }} dias</span>
                                                    @elseif($diasRestantes <= 15)
                                                        <span class="badge bg-warning text-dark">{{ $diasRestantes }} dias</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $diasRestantes }} dias</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">Nenhum plano próximo ao vencimento</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-error-warning-line"></i> Pagamentos Pendentes</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Plano</th>
                                                <th>Valor</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pagamentosPendentes as $pp)
                                            <tr>
                                                <td>
                                                    <strong>{{ $pp->empresa ? $pp->empresa->nome : '---' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $pp->plano ? $pp->plano->nome : '---' }}</span>
                                                </td>
                                                <td><strong class="text-danger">R$ {{ __moeda($pp->valor) }}</strong></td>
                                                <td>{{ __data_pt($pp->created_at, false) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">Nenhum pagamento pendente</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-file-list-3-line"></i> Empresas por Tributação</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Regime Tributário</th>
                                                <th class="text-center">Qtd</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($tributacoes as $trib)
                                            <tr>
                                                <td>{{ $trib->tributacao }}</td>
                                                <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $trib->total }}</span></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted py-3">Nenhum dado</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-pie-chart-line"></i> Distribuição de Planos Contratados</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Plano</th>
                                                <th class="text-center">Empresas</th>
                                                <th class="text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($distribuicaoPlanos as $dp)
                                            @php 
                                                $totalPlanos = $distribuicaoPlanos->sum('total');
                                                $percentual = $totalPlanos > 0 ? round(($dp->total / $totalPlanos) * 100, 1) : 0;
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $dp->nome }}</strong></td>
                                                <td class="text-center"><span class="badge bg-success rounded-pill">{{ $dp->total }}</span></td>
                                                <td class="text-center">
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentual }}%;" aria-valuenow="{{ $percentual }}" aria-valuemin="0" aria-valuemax="100">{{ $percentual }}%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Nenhum plano contratado</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ========================= FIM TABELAS ========================= --}}

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $(function() {
        setTimeout(() => {
            iniciaGraficoDistribuicaoPlanos()
            iniciaGraficoStatusEmpresas()
            carregarGraficoNovasEmpresas()
            carregarGraficoFaturamento()
        }, 50)
    })

    // ======================= GRÁFICO PIZZA - DISTRIBUIÇÃO DE PLANOS =======================
    function iniciaGraficoDistribuicaoPlanos() {
        const data = @json($distribuicaoPlanos);
        const labels = data.map(d => d.nome);
        const values = data.map(d => d.total);
        const colors = gerarCores(data.length);

        const ctx = document.getElementById('grafico-distribuicao-planos');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 8,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // ======================= GRÁFICO PIZZA - STATUS EMPRESAS =======================
    function iniciaGraficoStatusEmpresas() {
        const ativas = {{ $totalEmpresasAtivas }};
        const inativas = {{ $totalEmpresasInativas }};
        const ctx = document.getElementById('grafico-status-empresas');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Ativas', 'Inativas'],
                datasets: [{
                    data: [ativas, inativas],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 8,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // ======================= GRÁFICO BARRAS - NOVAS EMPRESAS =======================
    function carregarGraficoNovasEmpresas() {
        $.get(path_url + 'api/admin/dashboard/grafico-novas-empresas')
            .done((data) => {
                const ctx = document.getElementById('grafico-novas-empresas');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            label: 'Novas Empresas',
                            data: data.map(d => d.valor),
                            backgroundColor: '#0d6efd',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            })
            .fail((err) => console.log(err));
    }

    // ======================= GRÁFICO LINHA - FATURAMENTO =======================
    function carregarGraficoFaturamento() {
        $.get(path_url + 'api/admin/dashboard/grafico-faturamento-planos')
            .done((data) => {
                const ctx = document.getElementById('grafico-faturamento-planos');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [
                            {
                                label: 'Recebido',
                                data: data.map(d => d.valor),
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Pendente',
                                data: data.map(d => d.pendente),
                                borderColor: '#ffc107',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'R$ ' + value.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .fail((err) => console.log(err));
    }

    // ======================= UTILITÁRIO CORES =======================
    function gerarCores(qtd) {
        const paleta = [
            '#0d6efd', '#28a745', '#dc3545', '#ffc107', '#17a2b8',
            '#6610f2', '#fd7e14', '#20c997', '#e83e8c', '#6f42c1',
            '#d63384', '#198754', '#0dcaf0', '#ff851b', '#605ca8'
        ];
        return paleta.slice(0, qtd);
    }
</script>
@endsection
