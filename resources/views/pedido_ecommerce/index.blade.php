@extends('layouts.app', ['title' => 'Pedidos no E-commerce'])

@section('css')
<style>
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    min-height: 105px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.stat-card .stat-icon {
    position: absolute;
    right: 14px;
    bottom: 8px;
    font-size: 52px;
    opacity: .18;
    line-height: 1;
    pointer-events: none;
}
.stat-card .stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    opacity: .88;
}
.stat-card .stat-value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.1;
}
.stat-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
.stat-purple { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); }

/* ─── Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
    padding: 20px !important;
    margin-bottom: 24px;
}
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
    color: #4f46e5;
    margin-right: 6px;
}
.modulo-glass-filter-premium label {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 5px;
}
.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 40px !important;
    border-radius: 9px !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #334155 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}
.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #4f46e5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}
.modulo-glass-filter-premium .btn-limpar {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e2e8f0 !important;
    color: #334155 !important;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar do Cliente ─── */
.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #f0f4ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

/* ─── Badges de Status do Pedido ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
    text-transform: uppercase;
}
.modulo-badge-novo       { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
.modulo-badge-aprovado   { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-preparando { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
.modulo-badge-transporte { background: #f3e8ff; color: #9333ea; border: 1px solid #e9d5ff; }
.modulo-badge-finalizado { background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; }
.modulo-badge-recusado   { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

/* ─── Botões de Ação Squircle ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end; }
.act-btn { 
    width: 34px; 
    height: 34px; 
    border-radius: 10px; 
    border: 1px solid transparent; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 15px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: all .2s ease; 
    padding: 0;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover { background: #e0e7ff; color: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,.2); }
.act-add { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.act-add:hover { background: #dcfce7; color: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.2); }
.act-del { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Alertas de Pagamento ─── */
.payment-alert {
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    padding: 12px 16px;
    margin-bottom: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}
.payment-alert.success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.payment-alert.danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
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

            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
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
                            <a href="{{ route('produtos-ecommerce.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-shopping-bag-3-line"></i> Produtos
                            </a>
                            <a href="{{ route('produtos-ecommerce.categorias') }}" class="dash-btn dash-btn-light">
                                <i class="ri-store-2-line"></i> Categorias
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-indigo">
                                <div>
                                    <div class="stat-label">Total de Pedidos</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <i class="ri-file-list-3-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-green">
                                <div>
                                    <div class="stat-label">Aprovados / Concluídos</div>
                                    <div class="stat-value mt-1">{{ $stats['aprovados'] }}</div>
                                </div>
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-amber">
                                <div>
                                    <div class="stat-label">Em Andamento</div>
                                    <div class="stat-value mt-1">{{ $stats['pendentes'] }}</div>
                                </div>
                                <i class="ri-time-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-purple">
                                <div>
                                    <div class="stat-label">Faturamento Total</div>
                                    <div class="stat-value mt-1">R$ {{ __moeda($stats['faturamento']) }}</div>
                                </div>
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
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
                            <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
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
                    <div class="tb-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;"># Pedido</th>
                                        <th>Data</th>
                                        <th>Cliente</th>
                                        <th>Pagamento</th>
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
                                                <span class="fs-12 text-dark fw-semibold d-block">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                                </span>
                                                <span class="fs-11 text-muted">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="user-avatar">
                                                        <i class="ri-user-line"></i>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark d-block fs-13">
                                                            {{ $item->cliente ? $item->cliente->info : ($item->nome . ' ' . $item->sobre_nome) }}
                                                        </span>
                                                        @if($item->cliente && $item->cliente->telefone)
                                                            <span class="text-muted fs-11">
                                                                <i class="ri-phone-line"></i> {{ $item->cliente->telefone }}
                                                            </span>
                                                        @endif
                                                    </div>
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
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-11 fw-bold">
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
                                                <strong class="text-success fs-14">
                                                    R$ {{ __moeda($item->valor_total) }}
                                                </strong>
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('pedidos-ecommerce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="act-group">
                                                        <a title="Detalhes do Pedido" href="{{ route('pedidos-ecommerce.show', $item->id) }}" class="act-btn act-edit">
                                                            <i class="ri-survey-line"></i>
                                                        </a>
                                                        @can('pedidos_ecommerce_delete')
                                                            <button type="button" class="act-btn act-del btn-delete" title="Remover Pedido">
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

                    <!-- ═══ FOOTER & PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                        <div>
                            <h5 class="m-0 text-dark fs-14">Faturamento da Página: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('valor_total')) }}</strong></h5>
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