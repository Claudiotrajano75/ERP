@extends('layouts.app', ['title' => 'Dashboard Admin'])

@section('css')
<style>
    /* Estilos Gerais do Layout */
    .dashboard-title {
        font-size: 24px;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b, #475569);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .dashboard-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-top: 4px;
        margin-bottom: 24px;
    }

    /* Cards Gerais */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06) !important;
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
        padding: 18px 24px !important;
    }

    .card-header h5 {
        margin: 0 !important;
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header h5 i {
        color: #4f46e5;
        font-size: 18px;
    }

    .card-body {
        padding: 24px !important;
    }

    /* Cards de Métricas (Mapeamento Customizado) */
    .widget-icon-box {
        border: none !important;
        border-radius: 16px !important;
        color: #ffffff !important;
        position: relative;
        overflow: hidden;
    }

    .widget-icon-box::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -50px;
        right: -50px;
        pointer-events: none;
    }

    .widget-icon-box h4 {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 600 !important;
        letter-spacing: 0.05em;
        margin-bottom: 8px !important;
    }

    .widget-icon-box h3 {
        font-weight: 700 !important;
        color: #ffffff !important;
        margin: 0 !important;
    }

    .widget-icon-box small {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 12px;
        display: block;
        margin-top: 8px;
        font-weight: 500;
    }

    .widget-icon-box .avatar-title {
        background: rgba(255, 255, 255, 0.18) !important;
        backdrop-filter: blur(4px);
        border-radius: 12px !important;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .card-metric-total-empresas {
        background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
    }
    .card-metric-com-plano {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    .card-metric-planos-vencidos {
        background: linear-gradient(135deg, #ef4444, #f43f5e) !important;
    }
    .card-metric-faturamento {
        background: linear-gradient(135deg, #0ea5e9, #2563eb) !important;
    }
    .card-metric-pendentes {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    }
    .card-metric-ativas {
        background: linear-gradient(135deg, #475569, #334155) !important;
    }

    /* Tabelas Modernizadas */
    .table-responsive {
        border-radius: 0 0 16px 16px;
        overflow-x: auto !important;
    }

    .table {
        margin-bottom: 0 !important;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        padding: 14px 20px !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-top: none !important;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table tbody td {
        padding: 14px 20px !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        color: #334155 !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
        background-color: transparent !important;
    }

    .table tbody tr:last-child td {
        border-bottom: none !important;
    }

    .table a.text-reset {
        color: #1e293b !important;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .table a.text-reset:hover {
        color: #4f46e5 !important;
    }

    /* Badges Modernizados (Pills) */
    .badge {
        padding: 6px 12px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: none !important;
    }

    .bg-success {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
    }

    .bg-danger {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border: 1px solid #fecaca !important;
    }

    .bg-warning {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border: 1px solid #fef3c7 !important;
    }

    .bg-info {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
    }

    .bg-primary {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border: 1px solid #c7d2fe !important;
    }

    .bg-secondary {
        background-color: #f8fafc !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
    }

    /* Barra de Progresso Personalizada */
    .progress {
        height: 8px !important;
        border-radius: 9999px !important;
        background-color: #f1f5f9 !important;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 9999px !important;
        background: linear-gradient(90deg, #10b981, #059669) !important;
        font-size: 0 !important;
    }

    /* Outros Ajustes */
    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 20px 0 !important;
    }

    .btn-primary {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 6px 16px !important;
        transition: all 0.2s ease !important;
    }

    .btn-primary:hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h3 class="dashboard-title">
                    <i class="ri-dashboard-2-fill"></i> Dashboard Administrativo
                </h3>
                <h5 class="dashboard-subtitle">Olá, <strong class="text-primary">{{ get_name_user() }}</strong> — visão geral do sistema</h5>
                <hr>

                {{-- ========================= CARDS ========================= --}}
                <div class="row">
                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box card-metric-total-empresas">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Total Empresas</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $totalEmpresas }}</h3>
                                        <small>{{ $novasEmpresasMes }} novas este mês</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title shadow">
                                            <i class="ri-building-4-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box card-metric-com-plano">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Com Plano</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $empresasComPlano }}</h3>
                                        <small>planos ativos</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title shadow">
                                            <i class="ri-award-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box card-metric-planos-vencidos">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Planos Vencidos</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $empresasPlanoVencido }}</h3>
                                        <small>planos expirados</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title shadow">
                                            <i class="ri-alert-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box card-metric-faturamento">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Faturamento</h4>
                                        <h3 class="my-3" style="font-size: 18px;">R$ {{ __moeda($faturamentoMes) }}</h3>
                                        <small>recebido este mês</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title shadow">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box card-metric-pendentes">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Pendentes</h4>
                                        <h3 class="my-3" style="font-size: 18px;">R$ {{ __moeda($planosPendentes) }}</h3>
                                        <small>{{ $totalPlanosPendentesCount }} pendências</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title shadow">
                                            <i class="ri-hourglass-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 col-xl-2 mb-3">
                        <div class="card widget-icon-box card-metric-ativas">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-13 mt-0">Ativas</h4>
                                        <h3 class="my-3" style="font-size: 22px;">{{ $totalEmpresasAtivas }}</h3>
                                        <small>{{ $totalEmpresasInativas }} inativas</small>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title shadow">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ========================= FIM CARDS ========================= --}}

                {{-- ========================= GRÁFICOS ========================= --}}
                <div class="row mt-2">
                    <div class="col-xl-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5><i class="ri-pie-chart-2-line"></i> Distribuição de Planos</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-distribuicao-planos" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5><i class="ri-bar-chart-2-line"></i> Novas Empresas por Mês</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-novas-empresas" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5><i class="ri-donut-chart-line"></i> Status das Empresas</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-status-empresas" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5><i class="ri-line-chart-line"></i> Faturamento Mensal - Planos</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="grafico-faturamento-planos" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ========================= FIM GRÁFICOS ========================= --}}

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

    // Configurações Globais do Chart.js para layout premium
    Chart.defaults.font.family = "'Inter', 'Outfit', 'Helvetica Neue', 'sans-serif'";
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.borderRadius = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = '#1e293b';

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
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 16,
                            font: { size: 12, weight: '500' }
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
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 16,
                            font: { size: 12, weight: '500' }
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
                
                // Criando gradiente para as barras
                const ctx2d = ctx.getContext('2d');
                const gradient = ctx2d.createLinearGradient(0, 0, 0, 250);
                gradient.addColorStop(0, '#4f46e5');
                gradient.addColorStop(1, '#818cf8');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            label: 'Novas Empresas',
                            data: data.map(d => d.valor),
                            backgroundColor: gradient,
                            hoverBackgroundColor: '#4f46e5',
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: 'rgba(0, 0, 0, 0.04)' },
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 11 }
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
                const ctxElement = document.getElementById('grafico-faturamento-planos');
                const ctx = ctxElement.getContext('2d');
                
                // Criando gradientes canvas
                const gradienteRecebido = ctx.createLinearGradient(0, 0, 0, 250);
                gradienteRecebido.addColorStop(0, 'rgba(16, 185, 129, 0.18)');
                gradienteRecebido.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
                
                const gradientePendente = ctx.createLinearGradient(0, 0, 0, 250);
                gradientePendente.addColorStop(0, 'rgba(245, 158, 11, 0.18)');
                gradientePendente.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

                new Chart(ctxElement, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [
                            {
                                label: 'Recebido',
                                data: data.map(d => d.valor),
                                borderColor: '#10b981',
                                backgroundColor: gradienteRecebido,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointBackgroundColor: '#10b981',
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Pendente',
                                data: data.map(d => d.pendente),
                                borderColor: '#f59e0b',
                                backgroundColor: gradientePendente,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointBackgroundColor: '#f59e0b',
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 12,
                                    font: { size: 12, weight: '500' }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: 'rgba(0, 0, 0, 0.04)' },
                                ticks: {
                                    font: { size: 11 },
                                    callback: function(value) {
                                        return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
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
            '#4f46e5', '#10b981', '#f43f5e', '#f59e0b', '#0ea5e9',
            '#8b5cf6', '#06b6d4', '#ec4899', '#14b8a6', '#f97316',
            '#6366f1', '#198754', '#0dcaf0', '#ff851b', '#605ca8'
        ];
        return paleta.slice(0, qtd);
    }
</script>
@endsection
