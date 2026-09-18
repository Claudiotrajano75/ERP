@extends('layouts.app', ['title' => 'Lista de Pré-vendas'])

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
.tb-wrap tbody tr.clickable { cursor: pointer; }

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

/* ─── Dropdown de Ações Moderno ─── */
.btn-action-trigger {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    transition: all 0.2s ease;
    padding: 0;
}
.btn-action-trigger:hover, 
.btn-action-trigger:focus,
.dropdown-action-menu.show .btn-action-trigger {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
}

.action-dropdown-card {
    min-width: 240px;
    border-radius: 14px !important;
    border: 1px solid rgba(0,0,0,0.06) !important;
    padding: 8px !important;
    background: #ffffff;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 12px rgba(15, 23, 42, 0.06) !important;
    z-index: 1060;
}

.action-menu-item {
    display: flex !important;
    align-items: center;
    gap: 12px;
    padding: 8px 10px !important;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.18s ease;
    background: transparent;
    cursor: pointer;
}
.action-menu-item:hover {
    background-color: #f8fafc !important;
    transform: translateX(2px);
}
.action-menu-item:active {
    background-color: #f1f5f9 !important;
}

.action-item-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    transition: all 0.18s ease;
}

.action-item-icon.icon-primary { background: #eff6ff; color: #2563eb; }
.action-item-icon.icon-info    { background: #f0f9ff; color: #0284c7; }
.action-item-icon.icon-purple  { background: #faf5ff; color: #7c3aed; }
.action-item-icon.icon-teal    { background: #f0fdfa; color: #0d9488; }
.action-item-icon.icon-success { background: #f0fdf4; color: #16a34a; }
.action-item-icon.icon-warning { background: #fffbeb; color: #d97706; }
.action-item-icon.icon-danger  { background: #fef2f2; color: #dc2626; }

.action-menu-item:hover .action-item-icon {
    transform: scale(1.08);
}

.action-item-content {
    display: flex;
    flex-direction: column;
    text-align: left;
    line-height: 1.2;
}
.action-item-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: -0.1px;
}
.action-item-desc {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
    font-weight: 400;
}

.action-menu-item:hover .action-item-title {
    color: #0f172a;
}

.action-menu-item.text-danger:hover {
    background-color: #fef2f2 !important;
}
.action-menu-item.text-danger:hover .action-item-title {
    color: #b91c1c !important;
}

/* ─── Botão Finalizar em Destaque ─── */
.btn-finalizar-destaque {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 11.5px !important;
    padding: 5px 11px !important;
    border-radius: 8px !important;
    border: none !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    height: 32px;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3) !important;
    transition: all 0.2s ease !important;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}
.btn-finalizar-destaque:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.45) !important;
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    color: #ffffff !important;
}
.btn-finalizar-destaque:active {
    transform: translateY(0);
}

/* ─── Status Badges ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.modulo-badge-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-shopping-cart-2-line"></i>
                            Pré-vendas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Visualize, filtre e gerencie pré-vendas antes de finalizá-las como NFe ou NFCe.
                        </p>
                    </div>
                    <div>
                        @can('pre_venda_create')
                        <a href="{{ route('pre-venda.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-circle-line"></i> Nova Pré-venda
                        </a>
                        @endcan
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
                                <div class="stat-label">Total de Pré-vendas</div>
                                <div class="stat-value mt-1">{{ $stats['total_pedidos'] }}</div>
                            </div>
                            <i class="ri-file-list-3-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Recebidas (Finalizadas)</div>
                                <div class="stat-value mt-1">{{ $stats['total_recebidas'] }}</div>
                            </div>
                            <i class="ri-checkbox-circle-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Pendentes</div>
                                <div class="stat-value mt-1">{{ $stats['total_pendentes'] }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-purple">
                            <div>
                                <div class="stat-label">Faturamento Total</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_faturamento']) }}</div>
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
                            <i class="ri-search-line"></i> Filtrar Pré-vendas
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                            {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-hashtag"></i> Código</label>
                            {!!Form::text('codigo', '')->attrs(['class' => 'form-control', 'placeholder' => 'Nº do código'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-checkbox-circle-line"></i> Status</label>
                            {!!Form::select('status', '', [
                                '' => 'Todas as Pré-vendas',
                                '1' => 'Pendentes',
                                '0' => 'Recebidas'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                                {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'select2 form-select'])!!}
                            </div>
                        @endif
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('pre-venda.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA DE PRÉ-VENDAS ═══ -->
                <div class="tb-wrap mb-4">
                    <div class="table-responsive" style="min-height: 280px;">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    {{-- 1ª Coluna: Ações antes de Cliente --}}
                                    <th class="text-center" style="width: 130px;">Ações</th>
                                    <th>Cliente</th>
                                    @if(__countLocalAtivo() > 1)
                                        <th style="width: 110px;">Local</th>
                                    @endif
                                    <th>Data Cadastro</th>
                                    <th>Valor Total</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                    <tr @can('nfce_create') class="clickable" ondblclick="finalizar('{{$item->id}}')" @endcan>
                                        {{-- 1ª Coluna: AÇÕES (Dropdown de Ações + Botão Finalizar ao lado direito) --}}
                                        <td class="text-center" style="white-space: nowrap;">
                                            <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                                <form action="{{ route('pre-venda.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="dropdown dropdown-action-menu d-inline-block">
                                                        <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-start action-dropdown-card shadow-lg">

                                                        {{-- Finalizar Pré-venda --}}
                                                        @if($item->status == 1)
                                                            @can('nfce_create')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="finalizar('{{$item->id}}')">
                                                                    <div class="action-item-icon icon-success">
                                                                        <i class="ri-checkbox-circle-fill"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title text-success">Finalizar Pré-venda</span>
                                                                        <span class="action-item-desc">Faturar como NFe ou NFCe</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider my-1"></li>
                                                            @endcan
                                                        @endif

                                                        {{-- Histórico de Auditoria --}}
                                                        @can('pre_venda_view')
                                                        <li>
                                                            <a class="dropdown-item action-menu-item" href="{{ route('pre-venda.auditoria', $item->id) }}">
                                                                <div class="action-item-icon icon-info">
                                                                    <i class="ri-history-line"></i>
                                                                </div>
                                                                <div class="action-item-content">
                                                                    <span class="action-item-title">Histórico / Auditoria</span>
                                                                    <span class="action-item-desc">Registro de alterações</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        @endcan

                                                        {{-- Editar Pré-venda --}}
                                                        @if($item->status == 1)
                                                            @can('pre_venda_edit')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('pre-venda.edit', $item->id) }}">
                                                                    <div class="action-item-icon icon-warning">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Editar Pré-venda</span>
                                                                        <span class="action-item-desc">Modificar itens e valores</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @endcan
                                                        @endif

                                                        {{-- Visualizar / Imprimir Venda Finalizada (NFe) --}}
                                                        @if($item->status == 0 && $item->venda_id != null && $item->tipo_finalizado == 'nfe')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('nfe.show', $item->venda_id) }}">
                                                                    <div class="action-item-icon icon-primary">
                                                                        <i class="ri-eye-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Ver NF-e</span>
                                                                        <span class="action-item-desc">Visualizar venda gerada</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.imprimir', [$item->venda_id]) }}">
                                                                    <div class="action-item-icon icon-purple">
                                                                        <i class="ri-printer-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Imprimir Pedido</span>
                                                                        <span class="action-item-desc">Espelho do pedido</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        @endif

                                                        {{-- Visualizar / Imprimir Venda Finalizada (NFCe) --}}
                                                        @if($item->status == 0 && $item->venda_id != null && $item->tipo_finalizado == 'nfce')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('nfce.show', $item->venda_id) }}">
                                                                    <div class="action-item-icon icon-primary">
                                                                        <i class="ri-eye-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Ver NFC-e</span>
                                                                        <span class="action-item-desc">Visualizar cupom gerado</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="PrintThermal.imprimir('cupom', {{$item->venda_id}}, '{{ route('frontbox.imprimir-nao-fiscal', [$item->venda_id]) }}')">
                                                                    <div class="action-item-icon icon-teal">
                                                                        <i class="ri-printer-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Imprimir Térmica</span>
                                                                        <span class="action-item-desc">Cupom não fiscal</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @if($item->nfce && $item->nfce->estado == 'aprovado')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfce.imprimir', [$item->venda_id]) }}">
                                                                    <div class="action-item-icon icon-success">
                                                                        <i class="ri-printer-fill"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Imprimir NFC-e</span>
                                                                        <span class="action-item-desc">DANFCE fiscal aprovado</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        @endif

                                                        {{-- Excluir Pré-venda --}}
                                                        @if($item->status == 1)
                                                            @can('pre_venda_delete')
                                                            <li><hr class="dropdown-divider my-1"></li>
                                                            <li>
                                                                <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                                    <div class="action-item-icon icon-danger">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title text-danger">Excluir Pré-venda</span>
                                                                        <span class="action-item-desc text-danger-emphasis">Remover registro</span>
                                                                    </div>
                                                                </button>
                                                            </li>
                                                            @endcan
                                                        @endif

                                                    </ul>
                                                </div>
                                            </form>

                                            {{-- Botão Finalizar ao lado DIREITO das Ações --}}
                                            @if($item->status == 1)
                                                @can('nfce_create')
                                                    <button type="button" class="btn-finalizar-destaque" onclick="finalizar('{{$item->id}}')" title="Finalizar Pré-venda">
                                                        <i class="ri-checkbox-circle-fill"></i>
                                                        <span>Finalizar</span>
                                                    </button>
                                                @endcan
                                            @endif
                                            </div>
                                        </td>

                                        {{-- 2ª Coluna: Cliente com Ícone e CPF/CNPJ --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block fs-13">
                                                        {{ $item->cliente_id ? $item->cliente->razao_social : 'Consumidor Final' }}
                                                    </span>
                                                    <span class="text-muted fs-11">
                                                        {{ $item->cliente ? $item->cliente->cpf_cnpj : '--' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Local (se multi-local) --}}
                                        @if(__countLocalAtivo() > 1)
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12">
                                                    {{ $item->localizacao->descricao ?? '--' }}
                                                </span>
                                            </td>
                                        @endif

                                        {{-- Data Cadastro --}}
                                        <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>

                                        {{-- Valor Total --}}
                                        <td>
                                            <strong class="text-success" style="font-size: 13.5px;">R$ {{ __moeda($item->valor_total) }}</strong>
                                        </td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            @if($item->status == 0)
                                                <span class="modulo-badge modulo-badge-success">
                                                    <i class="ri-checkbox-circle-fill"></i> Recebida
                                                </span>
                                            @else
                                                <span class="modulo-badge modulo-badge-warning">
                                                    <i class="ri-time-line"></i> Pendente
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ __countLocalAtivo() > 1 ? 6 : 5 }}">
                                            <div class="modulo-empty">
                                                <i class="ri-inbox-2-line"></i>
                                                <p>Nenhuma pré-venda encontrada.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ PAGINAÇÃO ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark fs-14">Total em Pré-vendas: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('valor_total')) }}</strong></h5>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('modals._finalizar_pre_venda', ['not_submit' => true])
@endsection

@section('js')
<script src="/js/pre_venda.js?v={{ filemtime(public_path('js/pre_venda.js')) }}"></script>
@endsection
