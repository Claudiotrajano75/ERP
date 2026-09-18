@extends('layouts.app', ['title' => 'Home'])
@if(!__isContador())
    @section('css')
        <style>
            /* ─── Header Gradiente Premium ─── */
            .modulo-header-gradient {
                background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
                border-radius: 12px 12px 0 0 !important;
                border-bottom: none !important;
            }

            .modulo-header-gradient .modulo-title {
                color: #fff;
                font-weight: 700;
                letter-spacing: -0.3px;
            }

            .modulo-header-gradient .modulo-title i {
                background: rgba(255, 255, 255, 0.12);
                padding: 8px;
                border-radius: 10px;
                color: #a8b5ff;
            }

            .modulo-header-gradient .modulo-subtitle {
                color: rgba(255, 255, 255, 0.6) !important;
                font-weight: 400;
            }

            .modulo-header-gradient .modulo-subtitle strong {
                color: #fff;
            }

            /* ─── Cards de Ação da Home do Caixa ─── */
            .home-action-card {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 22px 18px;
                border-radius: 14px;
                color: #fff;
                height: 100%;
                position: relative;
                overflow: hidden;
                text-decoration: none;
                transition: all 0.25s ease;
            }

            .home-action-card::after {
                content: '';
                position: absolute;
                top: -45px;
                right: -45px;
                width: 130px;
                height: 130px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.08);
                transition: transform 0.3s ease;
            }

            .home-action-card:hover {
                transform: translateY(-4px);
                text-decoration: none;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2) !important;
            }

            .home-action-card:hover::after {
                transform: scale(1.5);
            }

            .home-action-card:hover .home-action-arrow {
                transform: translateX(4px);
                opacity: 1;
            }

            .home-action-icon {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 26px;
            }

            .home-action-card h5 {
                margin: 0;
                font-weight: 700;
                font-size: 16px;
                color: #fff;
                letter-spacing: -0.2px;
            }

            .home-action-card p {
                margin: 4px 0 0;
                font-size: 12px;
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.4;
            }

            .home-action-arrow {
                font-size: 26px;
                opacity: 0.6;
                transition: all 0.25s ease;
                flex-shrink: 0;
            }

            .home-action-blue {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
            }

            .home-action-green {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            }

            .home-action-orange {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
            }

            .home-action-purple {
                background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
                box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
            }

            @media (max-width: 768px) {
                .modulo-header-gradient .modulo-title {
                    font-size: 18px;
                }
            }
        /* ─── Dashboard Premium (skin) ─── */
        .dashboard-head { margin-bottom: 0.75rem; }
        .kpi-card {
            border: 1px solid var(--skin-border);
            border-radius: 16px;
            background: var(--skin-surface);
            padding: 20px;
            height: 100%;
            box-shadow: 0 1px 2px rgba(16,24,40,.04), 0 1px 3px rgba(16,24,40,.03);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(16,24,40,.09); }
        .kpi-icon { width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        .kpi-label { font-size: 11.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--skin-muted); }
        .kpi-value { font-size: 24px; font-weight: 800; color: var(--skin-text); margin-top: 6px; line-height: 1.1; }
        .kpi-sub { font-size: 12px; color: var(--skin-soft); font-weight: 500; }
        .trend-chip { display: inline-flex; align-items: center; gap: 4px; border-radius: 8px; padding: 3px 9px; font-size: 11px; font-weight: 700; }
        .trend-up { background: #dcfce7; color: #15803d; }
        .trend-warn { background: #fee2e2; color: #b91c1c; }
        .trend-info { background: #eef0ff; color: #4f46e5; }
        .panel-title { font-size: 15px; font-weight: 700; color: var(--skin-text); }
        .panel-subtitle { font-size: 12px; color: var(--skin-soft); }

        /* ─── KPI Cards COLORIDOS ─── */
        .kpi-card { position: relative; overflow: hidden; color: #fff; border: 0; }
        .kpi-card::after {
            content: ''; position: absolute; top: -46px; right: -46px;
            width: 150px; height: 150px; border-radius: 50%;
            background: rgba(255,255,255,.12);
        }
        .kpi-card.kpi-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 6px 20px rgba(79,70,229,.35); }
        .kpi-card.kpi-green  { background: linear-gradient(135deg, #24c98a 0%, #109f61 100%); box-shadow: 0 6px 20px rgba(16,185,129,.35); }
        .kpi-card.kpi-red    { background: linear-gradient(135deg, #fb7185 0%, #dc2626 100%); box-shadow: 0 6px 20px rgba(239,68,68,.35); }
        .kpi-card.kpi-blue   { background: linear-gradient(135deg, #4d94ff 0%, #1d4ed8 100%); box-shadow: 0 6px 20px rgba(37,99,235,.35); }
        .kpi-card .kpi-label { color: rgba(255,255,255,.82); }
        .kpi-card .kpi-value { color: #fff; }
        .kpi-card .kpi-sub { color: rgba(255,255,255,.75); }
        .kpi-card .kpi-icon { background: rgba(255,255,255,.22) !important; color: #fff !important; }
        .kpi-card .trend-chip { background: rgba(255,255,255,.2); color: #fff; }
        .kpi-card:hover { box-shadow: 0 12px 28px rgba(16,24,40,.15); }
        /* Donut (anel) em conic-gradient — Contas a receber */
        .donut-ring {
            width: 180px; height: 180px; border-radius: 50%;
            position: relative;
            background: conic-gradient(#4f46e5 0 var(--a, 0%), #10b981 var(--a, 0%) 100%);
        }
        .donut-ring::before {
            content: ''; position: absolute; inset: 26px;
            background: var(--skin-surface); border-radius: 50%;
            box-shadow: 0 0 0 1px var(--skin-border);
        }
        .donut-center {
            position: absolute; inset: 0; z-index: 2;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        </style>

    @endsection

    @section('content')
        <div class="mt-2">
            <div class="row">

                @if(__isAdmin())
                <div class="dashboard-head d-flex flex-wrap align-items-end justify-content-between gap-3">
                    <div>
                        <h1 class="page-title">Dashboard</h1>
                        <div class="page-subtitle">Visão geral da operação · hoje, {{ __data_pt(date('Y-m-d'), 0) }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="seg-control">
                            <button type="button" class="seg-btn" data-periodo="1">Hoje</button>
                            <button type="button" class="seg-btn" data-periodo="7">Semana</button>
                            <button type="button" class="seg-btn active" data-periodo="30">Mês</button>
                            <button type="button" class="seg-btn" data-periodo="365">Ano</button>
                        </div>
                        <input type="hidden" id="inp-periodo" value="30">
                        @if(__countLocalAtivo() > 1)
                            <select id="inp-local_id" class="form-select" style="width: 150px;">
                                <option value="">Todos</option>
                                @foreach(__getLocaisAtivoUsuario()->pluck('descricao', 'id') as $lid => $ld)
                                    <option value="{{ $lid }}">{{ $ld }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" id="inp-local_id" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}">
                        @endif
                        <a href="{{ route('relatorios.index') }}" class="dash-btn dash-btn-light"><i class="ri-download-line"></i> Exportar</a>
                        <a href="{{ route('nfe.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Nova venda</a>
                    </div>
                </div>

                @if($msgPlano != "")
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                        <i class="ri-error-warning-line fs-5"></i>
                        <span>{{ $msgPlano }}</span>
                        <a href="{{ route('payment.index') }}" class="btn btn-sm btn-success ms-2">Contratar Plano</a>
                    </div>
                @endif

                <!-- KPI Cards -->
                <div class="row g-3">
                    <div class="col-6 col-xl-3">
                        <div class="kpi-card kpi-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="kpi-label">Vendas do mês</div>
                                    <div class="kpi-value total-vendas">R$ 0,00</div>
                                </div>
                                <div class="kpi-icon"><i class="ri-shopping-bag-line"></i></div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <span class="trend-chip kpi-trend-vendas"><i class="ri-line-chart-line"></i> Em período</span>
                                <span class="kpi-sub kpi-variacao-vendas">vs. {{ $mes }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="kpi-card kpi-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="kpi-label">Contas a receber</div>
                                    <div class="kpi-value total-receber">R$ 0,00</div>
                                </div>
                                <div class="kpi-icon"><i class="ri-wallet-3-line"></i></div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <span class="trend-chip kpi-trend-receber"><i class="ri-arrow-up-line"></i> No período</span>
                                <span class="kpi-sub kpi-variacao-receber">em aberto</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="kpi-card kpi-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="kpi-label">Contas a pagar</div>
                                    <div class="kpi-value total-pagar">R$ 0,00</div>
                                </div>
                                <div class="kpi-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <span class="trend-chip kpi-trend-pagar"><i class="ri-arrow-down-line"></i> No período</span>
                                <span class="kpi-sub kpi-variacao-pagar">pagamentos</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="kpi-card kpi-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="kpi-label">Clientes no período</div>
                                    <div class="kpi-value total-clientes">0</div>
                                </div>
                                <div class="kpi-icon"><i class="ri-team-line"></i></div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <span class="trend-chip kpi-trend-clientes"><i class="ri-user-heart-line"></i> Base</span>
                                <span class="kpi-sub kpi-variacao-clientes">cadastrados</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row g-3 mt-1">
                    <div class="col-lg-8">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                    <div>
                                        <div class="panel-title">Receitas x Despesas</div>
                                        <div class="panel-subtitle">Vendas e compras de {{ strtolower($mes) }} por dia</div>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <span class="d-flex align-items-center gap-1 fs-12 text-muted"><span class="d-inline-block" style="width:10px;height:10px;border-radius:3px;background:#4f46e5;"></span> Receitas</span>
                                        <span class="d-flex align-items-center gap-1 fs-12 text-muted"><span class="d-inline-block" style="width:10px;height:10px;border-radius:3px;background:#4fd3d7;"></span> Despesas</span>
                                    </div>
                                </div>
                                <div class="position-relative" style="height:290px;">
                                    <canvas id="grafico-receitas-despesas" style="position:absolute;inset:0;width:100%;height:100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="panel-title mb-1">Contas a receber</div>
                                <div class="panel-subtitle mb-3">Pendente × recebido no período</div>
                                <div class="donut-ring mx-auto my-3" id="donut-conta-receber">
                                    <div class="donut-center">
                                        <div class="fs-5 fw-bold" id="donut-value">R$ 0</div>
                                        <div class="fs-12 text-muted" id="donut-total">0 recebido</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between fs-12 mt-3">
                                    <span class="d-flex align-items-center gap-1"><span class="d-inline-block" style="width:9px;height:9px;border-radius:50%;background:#4f46e5;"></span> A receber</span>
                                    <span class="d-flex align-items-center gap-1"><span class="d-inline-block" style="width:9px;height:9px;border-radius:50%;background:#10b981;"></span> Recebido</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom row -->
                <div class="row g-3 mt-1">
                    <div class="col-lg-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="panel-title">Resumo de vendas</div>
                                    <span class="badge bg-primary-subtle text-primary"><i class="ri-arrow-up-line me-1"></i>{{ $mes }}</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="p-3 rounded-3" style="background:#eef0ff;">
                                             <div class="fs-12 text-muted fw-semibold">Total de {{ $mes }}</div>
                                            <div class="fs-4 fw-bold" style="color:var(--skin-text);">R$ {{ __moeda($totalVendasMes) }}</div>
                                        </div>
                                    </div>
                                    @foreach($somaVendasMesesAnteriores as $key => $s)
                                    <div class="col-6">
                                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--skin-border);">
                                            <div class="fs-12 text-muted fw-semibold">{{ $key }}</div>
                                            <div class="fs-4 fw-bold" style="color:var(--skin-text);">R$ {{ __moeda($s) }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="panel-title">Emissões &amp; Metas</div>
                                    <span class="fs-12 text-muted">Volume do mês</span>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc;border:1px solid var(--skin-border);">
                                            <div class="kpi-icon" style="width:38px;height:38px;font-size:18px;background:#eef0ff;color:#4f46e5;"><i class="ri-file-list-3-line"></i></div>
                                            <div>
                                                <div class="fs-5 fw-bold" style="color:var(--skin-text);">R$ {{ __moeda($totalEmitidoMes) }}</div>
                                                <div class="fs-12 text-muted">Emitido no mês</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc;border:1px solid var(--skin-border);">
                                            <div class="kpi-icon" style="width:38px;height:38px;font-size:18px;background:#dcfce7;color:#16a34a;"><i class="ri-focus-3-line"></i></div>
                                            <div>
                                                <div class="fs-5 fw-bold" style="color:var(--skin-text);">{{ $empresa->plano ? $empresa->plano->plano->nome : '—' }}</div>
                                                <div class="fs-12 text-muted">Plano</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $maxNfe   = $empresa->plano ? $empresa->plano->plano->maximo_nfes : 0;
                                    $maxNfce  = $empresa->plano ? $empresa->plano->plano->maximo_nfces : 0;
                                    $maxCte   = $empresa->plano ? $empresa->plano->plano->maximo_ctes : 0;
                                    $maxMdfe  = $empresa->plano ? $empresa->plano->plano->maximo_mdfes : 0;
                                    $metas = [
                                        ['label' => 'NFe',   'total' => $totalNfeCount,  'max' => $maxNfe,  'color' => '#4f46e5'],
                                        ['label' => 'NFCe',  'total' => $totalNfceCount, 'max' => $maxNfce, 'color' => '#10b981'],
                                        ['label' => 'CTe',   'total' => $totalCteCount,  'max' => $maxCte,  'color' => '#0ea5e9'],
                                        ['label' => 'MDFe',  'total' => $totalMdfeCount, 'max' => $maxMdfe, 'color' => '#8b5cf6'],
                                    ];
                                @endphp
                                @foreach($metas as $m)
                                    @php $pct = $m['max'] > 0 ? min(100, round($m['total'] / $m['max'] * 100)) : ($m['total'] > 0 ? 100 : 0); @endphp
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between fs-12 mb-1">
                                            <span class="fw-semibold">{{ $m['label'] }}</span>
                                            <span class="text-muted">{{ $m['total'] }} / {{ $m['max'] }}</span>
                                        </div>
                                        <div class="progress" style="height:8px;border-radius:8px;">
                                            <div class="progress-bar" role="progressbar" style="width:{{ $pct }}%;background:{{ $m['color'] }};"></div>
                                        </div>
                                    </div>
                                @endforeach
                                <a href="{{ route('nfe-all') }}" class="btn btn-sm btn-light border w-100 mt-1"><i class="ri-file-list-line me-1"></i> Ver emissões</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ NOVOS BLOCOS: Top Produtos + Alertas ═══ -->
                <div class="row g-3 mt-1 mb-3" id="bloco-avancado">

                    <!-- Top 5 Produtos -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="panel-title"><i class="ri-bar-chart-2-line me-1 text-primary"></i> Top 5 Produtos Vendidos</div>
                                    <span class="badge bg-primary-subtle text-primary fs-11" id="badge-periodo-top">Mês</span>
                                </div>
                                <div id="lista-top-produtos">
                                    <div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm"></div> Carregando...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alertas: Estoque + Contas -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="panel-title"><i class="ri-alarm-warning-line me-1 text-danger"></i> Central de Alertas</div>
                                    <span class="badge bg-danger-subtle text-danger fs-11" id="badge-alertas-count">0</span>
                                </div>

                                <!-- Alertas financeiros -->
                                <div id="alertas-financeiros" class="mb-3"></div>

                                <!-- Estoque crítico -->
                                <div class="panel-title fs-12 mb-2 text-muted"><i class="ri-box-3-line me-1"></i> Estoque Crítico</div>
                                <div id="lista-alertas-estoque">
                                    <div class="text-center text-muted py-2"><div class="spinner-border spinner-border-sm"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ═══ FIM BLOCOS AVANÇADOS ═══ -->

@else
                    <!-- ═══ Painel Inicial — Home do Caixa ═══ -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header modulo-header-gradient py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                        <i class="ri-home-5-line"></i>
                                        Painel Inicial
                                    </h4>
                                    <p class="text-muted mb-0 modulo-subtitle fs-13">
                                        Olá, <strong>{{ get_name_user() }}</strong>! Selecione uma ação para começar.
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 fs-12">
                                        <i class="ri-calendar-line me-1"></i> {{ __data_pt(date('Y-m-d'), 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('nfe.create') }}" class="home-action-card home-action-blue">
                                        <div class="home-action-icon"><i class="ri-shopping-bag-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Nova Venda</h5>
                                            <p>Emita uma NFe de venda para o cliente</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('pre-venda.create') }}" class="home-action-card home-action-green">
                                        <div class="home-action-icon"><i class="ri-file-list-3-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Nova Pré-Venda</h5>
                                            <p>Crie uma pré-venda para finalizar depois</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('produtos.create') }}" class="home-action-card home-action-orange">
                                        <div class="home-action-icon"><i class="ri-price-tag-3-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Novo Produto</h5>
                                            <p>Cadastre um novo produto no estoque</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('clientes.create') }}" class="home-action-card home-action-purple">
                                        <div class="home-action-icon"><i class="ri-user-add-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Novo Cliente</h5>
                                            <p>Cadastre um novo cliente</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @endsection

    @if(__isAdmin())
        @section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $(function () {
        setTimeout(function () {

            // Segmented period control
            $('.seg-btn').on('click', function () {
                $('.seg-btn').removeClass('active');
                $(this).addClass('active');
                $('#inp-periodo').val($(this).data('periodo'));
                dadosCards();
            });

            $('#inp-local_id').on('change', function () { dadosCards(); });

            dadosCards();
            buscaReceitasDespesas();
            buscaResumoContaReceber();
        }, 10);
    });

    function dadosCards() {
        let periodo = $('#inp-periodo').val();
        let local_id = $('#inp-local_id').val();
        let empresa_id = $('#empresa_id').val();
        let usuario_id = $('#usuario_id').val();

        $.get(path_url + "api/graficos/dados-cards", {
            empresa_id: empresa_id, usuario_id: usuario_id, periodo: periodo, local_id: local_id
        })
            .done((s) => {
                $('.total-vendas').text("R$ " + convertFloatToMoeda(s['vendas']));
                $('.total-receber').text("R$ " + convertFloatToMoeda(s['contas_receber']));
                $('.total-pagar').text("R$ " + convertFloatToMoeda(s['contas_pagar']));
                $('.total-clientes').text(s['clientes'] || 0);
            })
            .fail((err) => console.log(err));

        // Chama também o endpoint avançado
        dadosCardsAvancado(empresa_id, usuario_id, periodo, local_id);
    }

    function dadosCardsAvancado(empresa_id, usuario_id, periodo, local_id) {
        const labels = {1: 'Hoje', 7: 'Semana', 30: 'Mês', 365: 'Ano'};
        $('#badge-periodo-top').text(labels[periodo] || 'Mês');

        $.get(path_url + "api/graficos/dados-cards-avancado", {
            empresa_id: empresa_id, usuario_id: usuario_id, periodo: periodo, local_id: local_id
        })
        .done((s) => {
            // ── Variações % nos KPI cards ──────────────────────────────────
            renderVariacao('.kpi-trend-vendas',   '.kpi-variacao-vendas',   s.variacoes.vendas);
            renderVariacao('.kpi-trend-receber',  '.kpi-variacao-receber',  s.variacoes.receber);
            renderVariacao('.kpi-trend-pagar',    '.kpi-variacao-pagar',    s.variacoes.pagar,   true);
            renderVariacao('.kpi-trend-clientes', '.kpi-variacao-clientes', s.variacoes.clientes);

            // ── Top 5 Produtos ─────────────────────────────────────────────
            let htmlTop = '';
            if (!s.top_produtos || s.top_produtos.length === 0) {
                htmlTop = '<div class="text-muted text-center py-3 fs-13"><i class="ri-inbox-line fs-4 d-block mb-1"></i>Nenhuma venda no período</div>';
            } else {
                const maxVal = s.top_produtos[0]?.total_valor || 1;
                s.top_produtos.forEach((p, i) => {
                    const pct = Math.round((p.total_valor / maxVal) * 100);
                    const cores = ['#4f46e5','#10b981','#0ea5e9','#f59e0b','#8b5cf6'];
                    htmlTop += `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-12 fw-semibold text-truncate" style="max-width:70%;" title="${p.descricao}">
                                    <span class="badge rounded-pill me-1" style="background:${cores[i]};font-size:9px;">${i+1}</span>
                                    ${p.descricao}
                                </span>
                                <span class="fs-12 text-muted">R$ ${convertFloatToMoeda(p.total_valor)}</span>
                            </div>
                            <div class="progress" style="height:6px;border-radius:6px;">
                                <div class="progress-bar" style="width:${pct}%;background:${cores[i]};"></div>
                            </div>
                            <div class="fs-11 text-muted mt-1">${parseFloat(p.total_qtd).toFixed(0)} unid. vendidas</div>
                        </div>`;
                });
            }
            $('#lista-top-produtos').html(htmlTop);

            // ── Alertas Financeiros ────────────────────────────────────────
            let alertasCount = s.alertas_estoque?.length || 0;
            let htmlFin = '';
            if (s.contas_vencidas > 0) {
                alertasCount += s.contas_vencidas;
                htmlFin += `<div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-2" style="background:#fff1f2;border:1px solid #fecdd3;">
                    <i class="ri-error-warning-fill text-danger fs-5"></i>
                    <div class="flex-grow-1">
                        <div class="fs-12 fw-semibold text-danger">${s.contas_vencidas} conta(s) a pagar VENCIDA(S)</div>
                        <div class="fs-11 text-muted">Regularize para evitar juros</div>
                    </div>
                    <a href="/conta-pagar" class="btn btn-sm btn-outline-danger py-0">Ver</a>
                </div>`;
            }
            if (s.contas_vencendo_hoje > 0) {
                alertasCount += s.contas_vencendo_hoje;
                htmlFin += `<div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-2" style="background:#fffbeb;border:1px solid #fde68a;">
                    <i class="ri-time-fill text-warning fs-5"></i>
                    <div class="flex-grow-1">
                        <div class="fs-12 fw-semibold text-warning">${s.contas_vencendo_hoje} conta(s) vencendo HOJE</div>
                        <div class="fs-11 text-muted">Pague hoje para evitar multa</div>
                    </div>
                    <a href="/conta-pagar" class="btn btn-sm btn-outline-warning py-0">Ver</a>
                </div>`;
            }
            $('#alertas-financeiros').html(htmlFin);

            // ── Alertas de Estoque ─────────────────────────────────────────
            let htmlEst = '';
            if (!s.alertas_estoque || s.alertas_estoque.length === 0) {
                htmlEst = '<div class="text-muted fs-12 py-1"><i class="ri-check-double-line text-success me-1"></i>Nenhum produto com estoque crítico</div>';
            } else {
                s.alertas_estoque.forEach(p => {
                    const estoque = parseFloat(p.estoque).toFixed(0);
                    const minimo  = parseFloat(p.estoque_minimo).toFixed(0);
                    const isCrit  = p.estoque <= 0;
                    htmlEst += `<div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                        <span class="fs-12 text-truncate" style="max-width:60%;" title="${p.nome}">${p.nome}</span>
                        <span class="badge ${isCrit ? 'bg-danger' : 'bg-warning text-dark'} rounded-pill fs-10">
                            ${isCrit ? '⚠ Zerado' : estoque + ' / ' + minimo}
                        </span>
                    </div>`;
                });
            }
            $('#lista-alertas-estoque').html(htmlEst);
            $('#badge-alertas-count').text(alertasCount > 0 ? alertasCount : '✓').toggleClass('bg-success-subtle text-success', alertasCount === 0).toggleClass('bg-danger-subtle text-danger', alertasCount > 0);
        })
        .fail((err) => {
            console.log('dadosCardsAvancado error', err);
            $('#lista-top-produtos').html('<div class="text-muted text-center py-3 fs-13"><i class="ri-inbox-line fs-4 d-block mb-1"></i>Nenhuma venda registrada no período</div>');
            $('#lista-alertas-estoque').html('<div class="text-muted fs-12 py-1"><i class="ri-check-double-line text-success me-1"></i>Nenhum alerta de estoque no momento</div>');
        });
    }

    function renderVariacao(trendSel, subSel, data, inverso = false) {
        if (!data) return;
        const v = data.variacao;
        const positivo = inverso ? v < 0 : v > 0;
        const neutro   = v === 0;
        const icon     = positivo ? 'ri-arrow-up-line' : (neutro ? 'ri-minus-line' : 'ri-arrow-down-line');
        const cor      = positivo ? '#10b981' : (neutro ? '#94a3b8' : '#ef4444');
        const sinal    = v > 0 ? '+' : '';
        $(trendSel).html(`<i class="${icon}"></i> <span style="color:${cor};font-weight:700;">${sinal}${v}%</span>`);
        $(subSel).text('vs mês anterior');
    }

    function buscaReceitasDespesas() {
        let empresa_id = $('#empresa_id').val();
        $.when(
            $.get(path_url + "api/graficos/grafico-vendas-mes", { empresa_id: empresa_id }),
            $.get(path_url + "api/graficos/grafico-compras-mes", { empresa_id: empresa_id })
        ).done(function (vendas, compras) {
            iniciaGraficoReceitasDespesas(vendas[0] || [], compras[0] || []);
        });
    }

    function iniciaGraficoReceitasDespesas(vendas, compras) {
        const ctx = document.getElementById('grafico-receitas-despesas');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: vendas.map(x => x.dia),
                datasets: [
                    {
                        label: 'Receitas',
                        data: vendas.map(x => x.valor),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.10)',
                        fill: true, tension: 0.4, borderWidth: 2.5,
                        pointRadius: 0, pointHoverRadius: 4
                    },
                    {
                        label: 'Despesas',
                        data: compras.map(x => x.valor),
                        borderColor: '#4fd3d7',
                        backgroundColor: 'transparent',
                        borderWidth: 2.5, tension: 0.4,
                        pointRadius: 0, pointHoverRadius: 4
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1f2937', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10.5 } } },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#eef1f7' },
                        ticks: { color: '#94a3b8', font: { size: 10.5 }, callback: v => v.toLocaleString('pt-BR') }
                    }
                }
            }
        });
    }

    function buscaResumoContaReceber() {
        let empresa_id = $('#empresa_id').val();
        $.get(path_url + "api/graficos/grafico-conta-receber", { empresa_id: empresa_id })
            .done(function (data) { iniciaDonutContaReceber(data || []); })
            .fail(function (err) { console.log(err); });
    }

    function iniciaDonutContaReceber(data) {
        const ring = document.getElementById('donut-conta-receber');
        if (!ring) return;
        let pendente = data.reduce((a, x) => a + (x.valorPendente || 0), 0);
        let recebido = data.reduce((a, x) => a + (x.valorQuitado || 0), 0);
        let total = pendente + recebido;
        let pct = total > 0 ? (pendente / total) * 100 : 0;
        ring.style.setProperty('--a', pct + '%');
        document.getElementById('donut-value').textContent = 'R$ ' + convertFloatToMoeda(pendente);
        document.getElementById('donut-total').textContent = convertFloatToMoeda(recebido) + ' recebido';
    }
</script>

        @endsection
    @endif
@else

    @include('contador.home')
@endif