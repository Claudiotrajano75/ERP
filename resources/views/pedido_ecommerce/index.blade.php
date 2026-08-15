@extends('layouts.app', ['title' => 'Pedidos no E-commerce'])

@section('css')
    <style>
        /* ─── Header Gradient ─── */
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

        .modulo-header-gradient .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .modulo-header-gradient .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }

        /* ─── Card Principal ─── */
        .modulo-form-card {
            border: 1px solid #eef0f5;
            border-radius: 12px;
            overflow: hidden;
        }

        /* --- Novo Filtro de Pesquisa Premium --- */
        .modulo-glass-filter-premium {
            background: #ffffff;
            border: 1px solid #eef0f6 !important;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            padding: 20px !important;
            margin-bottom: 24px;
        }

        /* Título e Header do Filtro */
        .filtro-premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f3f9;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .filtro-premium-title {
            font-size: 13px;
            font-weight: 700;
            color: #3f3e6a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }

        .filtro-premium-title i {
            color: #5572f5;
            margin-right: 6px;
        }

        /* Customização dos Inputs dentro do Filtro */
        .modulo-glass-filter-premium label {
            font-size: 10px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8c8ca6 !important;
            margin-bottom: 6px !important;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .modulo-glass-filter-premium label i {
            font-size: 12px;
            color: #a8a8c0;
        }

        .modulo-glass-filter-premium .form-control,
        .modulo-glass-filter-premium .form-select {
            height: 38px !important;
            border-radius: 8px !important;
            border: 1px solid #dcdce9 !important;
            font-size: 13px !important;
            padding: 6px 12px !important;
            color: #374151 !important;
            background-color: #fcfdfe !important;
            transition: all 0.2s ease;
        }

        .modulo-glass-filter-premium .form-control:focus,
        .modulo-glass-filter-premium .form-select:focus {
            border-color: #5572f5 !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
        }

        /* Botões do Filtro */
        .modulo-glass-filter-premium .btn-pesquisar {
            background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600 !important;
            height: 38px;
            border-radius: 8px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .modulo-glass-filter-premium .btn-pesquisar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
        }

        .modulo-glass-filter-premium .btn-limpar {
            background: #f1f3f9 !important;
            border: 1px solid #e2e5ec !important;
            color: #5a5a7a !important;
            font-weight: 600 !important;
            height: 38px;
            border-radius: 8px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .modulo-glass-filter-premium .btn-limpar:hover {
            background: #e8ebf3 !important;
            color: #302b63 !important;
        }

        /* ─── Premium Table ─── */
        .modulo-table-wrap {
            border-radius: 12px;
            border: 1px solid #eef0f5;
            overflow: hidden;
        }

        .modulo-table-wrap table {
            margin-bottom: 0;
        }

        .modulo-table-wrap thead th {
            background: #f8f9fc;
            color: #5a5a7a;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 12px 14px;
            border-bottom: 2px solid #e8eaf6;
        }

        .modulo-table-wrap tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f8;
            transition: background 0.15s ease;
            font-size: 13px;
            color: #374151;
        }

        .modulo-table-wrap tbody tr {
            transition: all 0.15s ease;
        }

        .modulo-table-wrap tbody tr:hover {
            background: #f5f6fe;
        }

        .modulo-table-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        /* ─── Badges de Status do Pedido ─── */
        .modulo-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }

        .modulo-badge-novo { background: #e0f2fe; color: #0284c7; }
        .modulo-badge-aprovado { background: #dcfce7; color: #16a34a; }
        .modulo-badge-preparando { background: #fef3c7; color: #d97706; }
        .modulo-badge-transporte { background: #f3e8ff; color: #9333ea; }
        .modulo-badge-finalizado { background: #d1fae5; color: #059669; }
        .modulo-badge-recusado { background: #fee2e2; color: #dc2626; }

        /* ─── Alertas de Pagamento ─── */
        .payment-alert {
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        .payment-alert.success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .payment-alert.danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* ─── Botões de Ação ─── */
        .modulo-action-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            flex-wrap: nowrap;
        }

        .modulo-action-group .btn {
            border-radius: 8px;
            padding: 5px 9px;
            font-size: 12px;
            transition: all 0.15s ease;
        }

        .modulo-action-group .btn:hover {
            transform: translateY(-1px);
        }

        /* ─── Footer ─── */
        .modulo-footer {
            padding: 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .modulo-footer .modulo-total-label {
            font-size: 13px;
            color: #5a5a7a;
            font-weight: 600;
        }

        .modulo-footer .modulo-total-value {
            font-size: 18px;
            font-weight: 800;
            color: #10b981;
            letter-spacing: -0.3px;
        }

        /* ─── Empty State ─── */
        .modulo-empty {
            padding: 48px 20px;
            text-align: center;
        }

        .modulo-empty i {
            font-size: 48px;
            color: #c5cae9;
            margin-bottom: 12px;
            display: block;
        }

        .modulo-empty p {
            color: #9e9eb8;
            font-size: 14px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .modulo-header-gradient .modulo-title {
                font-size: 18px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="col-12">

                {{-- Notificações de Pagamentos Automáticos --}}
                @if(isset($pagamentosAlterados) && count($pagamentosAlterados) > 0)
                    <div class="card border-0 shadow-sm modulo-form-card mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h5 class="mb-0 fs-14 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ri-notification-3-line text-primary"></i> Atualizações Automáticas de Pagamento (Mercado Pago)
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                @foreach($pagamentosAlterados as $p)
                                    <div class="col-md-4 col-12">
                                        <div class="payment-alert {{ $p['status'] == 'approved' ? 'success' : 'danger' }}">
                                            <i class="ri-{{ $p['status'] == 'approved' ? 'checkbox-circle-fill' : 'close-circle-fill' }} fs-18"></i>
                                            <span>Pedido <strong>#{{ $p['hash_pedido'] }}</strong> alterado para: <strong>{{ strtoupper($p['status']) }}</strong></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm modulo-form-card">

                    <!-- ═══ Cabeçalho Premium ═══ -->
                    <div class="card-header modulo-header-gradient py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                    <i class="ri-inbox-archive-fill"></i>
                                    Pedidos do E-commerce
                                </h4>
                                <p class="text-muted mb-0 modulo-subtitle fs-13">
                                    Acompanhe, filtre e gerencie os pedidos de venda gerados pela sua loja virtual.
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('produtos-ecommerce.index') }}" class="btn btn-outline-light btn-sm px-3 text-white">
                                    <i class="ri-shopping-bag-3-line align-middle me-1"></i> Produtos
                                </a>
                                <a href="{{ route('produtos-ecommerce.categorias') }}" class="btn btn-light btn-sm px-3 text-dark">
                                    <i class="ri-store-2-line align-middle me-1"></i> Categorias
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        <!-- ═══ KPI Cards Premium ═══ -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="card widget-icon-box text-bg-info mb-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Pedidos</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['total'] ?? $data->total() }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Pedidos realizados</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-file-list-3-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card widget-icon-box text-bg-success mb-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Aprovados / Concluídos</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['aprovados'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Prontos ou finalizados</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card widget-icon-box text-bg-warning mb-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Em Andamento</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['pendentes'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Novos ou em preparo</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-time-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card widget-icon-box text-bg-dark mb-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Faturamento Total</h4>
                                                <h3 class="my-2 text-white fs-18">R$ {{ __moeda($stats['faturamento'] ?? 0) }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Volume de vendas na loja</p>
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
                        </div>

                        <!-- ═══ Filtros de Busca Premium ═══ -->
                        <div class="modulo-glass-filter-premium">
                            <div class="filtro-premium-header">
                                <h5 class="filtro-premium-title">
                                    <i class="ri-search-line"></i> Filtrar Pedidos do E-commerce
                                </h5>
                            </div>

                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-3">
                                <div class="col-md-3 col-12">
                                    <label class="form-label"><i class="ri-hashtag"></i> Código do Pedido</label>
                                    {!!Form::text('codigo', '')->attrs(['class' => 'form-control', 'placeholder' => 'Nº do pedido...'])!!}
                                </div>
                                <div class="col-md-3 col-12">
                                    <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                                    {!!Form::select('cliente_id', '')
                                        ->options($cliente != null ? [$cliente->id => ($cliente->razao_social . " - " . $cliente->telefone)] : [])
                                        ->attrs(['class' => 'select2 form-select'])
                                    !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label"><i class="ri-checkbox-circle-line"></i> Estado</label>
                                    {!!Form::select('estado', '', ['' => 'Todos os Estados'] + App\Models\PedidoEcommerce::estados())
                                        ->attrs(['class' => 'form-select'])
                                    !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                    {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                    {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                                </div>
                                <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                            <i class="ri-search-line"></i> Buscar
                                        </button>
                                        <a class="btn btn-limpar px-3" href="{{ route('pedidos-ecommerce.index') }}" title="Limpar Filtros">
                                            <i class="ri-eraser-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>

                        <!-- ═══ TABELA PREMIUM ═══ -->
                        <div class="modulo-table-wrap mb-4">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;"># Pedido</th>
                                            <th>Data</th>
                                            <th>Cliente</th>
                                            <th>Forma de Pagamento</th>
                                            <th>Estado do Pedido</th>
                                            <th class="text-center">Itens</th>
                                            <th class="text-end">Frete</th>
                                            <th class="text-end">Desconto</th>
                                            <th class="text-end">Valor Total</th>
                                            <th class="text-end" style="width: 100px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-dark text-white px-2 py-1 fs-12 fw-bold">
                                                        #{{ $item->hash_pedido }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fs-12 text-muted d-block">
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                                    </span>
                                                    <span class="fs-11 text-muted">
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-dark fs-13">
                                                            {{ $item->cliente ? $item->cliente->info : ($item->nome . ' ' . $item->sobre_nome) }}
                                                        </span>
                                                        @if($item->cliente && $item->cliente->telefone)
                                                            <span class="text-muted fs-11">
                                                                <i class="ri-phone-line"></i> {{ $item->cliente->telefone }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border fs-11 px-2 py-1 fw-semibold">
                                                        <i class="ri-bank-card-line me-1 text-primary"></i>
                                                        {{ strtoupper($item->tipo_pagamento ?? 'N/D') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php $est = strtolower($item->estado); @endphp
                                                    @if($est == 'novo')
                                                        <span class="modulo-badge modulo-badge-novo"><i class="ri-flashlight-line"></i> Novo</span>
                                                    @elseif($est == 'aprovado')
                                                        <span class="modulo-badge modulo-badge-aprovado"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                                    @elseif($est == 'preparando')
                                                        <span class="modulo-badge modulo-badge-preparando"><i class="ri-time-line"></i> Preparando</span>
                                                    @elseif($est == 'em_trasporte')
                                                        <span class="modulo-badge modulo-badge-transporte"><i class="ri-truck-line"></i> Em Transporte</span>
                                                    @elseif($est == 'finalizado')
                                                        <span class="modulo-badge modulo-badge-finalizado"><i class="ri-check-double-line"></i> Finalizado</span>
                                                    @else
                                                        <span class="modulo-badge modulo-badge-recusado"><i class="ri-close-circle-line"></i> Recusado</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fs-11 fw-bold">
                                                        {{ sizeof($item->itens) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    @if($item->valor_frete > 0)
                                                        <span class="text-muted fs-12">R$ {{ __moeda($item->valor_frete) }}</span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fs-11">Grátis</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if($item->desconto > 0)
                                                        <span class="text-danger fw-semibold fs-12">- R$ {{ __moeda($item->desconto) }}</span>
                                                    @else
                                                        <span class="text-muted fs-12">--</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold text-success fs-14">
                                                        R$ {{ __moeda($item->valor_total) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('pedidos-ecommerce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                        @method('delete')
                                                        @csrf
                                                        <div class="modulo-action-group justify-content-end">
                                                            <a title="Detalhes do Pedido" href="{{ route('pedidos-ecommerce.show', $item->id) }}" class="btn btn-dark btn-sm text-white">
                                                                <i class="ri-survey-line"></i>
                                                            </a>
                                                            @can('pedidos_ecommerce_delete')
                                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Remover Pedido">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">
                                                    <div class="modulo-empty">
                                                        <i class="ri-inbox-archive-line"></i>
                                                        <p>Nenhum pedido de e-commerce encontrado para os filtros selecionados.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ═══ Footer & Paginação ═══ -->
                        <div class="modulo-footer">
                            <div>
                                <span class="modulo-total-label">Faturamento da Página: </span>
                                <span class="modulo-total-value">R$ {{ __moeda($data->sum('valor_total')) }}</span>
                            </div>
                            <div>
                                {!! $data->appends(request()->all())->links() !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection