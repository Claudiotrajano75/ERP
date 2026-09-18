@extends('layouts.app', ['title' => 'NFCe'])

@section('css')
<style>
/* ─── Estilos Modais Premium NFCe ─── */
.modal-nfce-header-red {
    background: #ef4444;
    padding: 16px 20px;
}
.modal-nfce-header-red .modal-title {
    color: #fff;
    font-size: 16px;
    font-weight: 700;
}
.modal-nfce-header-red .modal-subtitle {
    color: rgba(255,255,255,0.92);
    font-size: 11.5px;
    margin: 3px 0 0;
}
.modal-nfce-dados-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
}
.modal-nfce-dados-card .card-label {
    font-size: 10.5px;
    color: #94a3b8;
    font-weight: 500;
    margin-bottom: 2px;
}
.modal-nfce-dados-card .card-val {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
}
.modal-nfce-dados-card .card-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 12px;
    display: block;
}
.modal-nfce-alert-danger {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.modal-nfce-alert-yellow {
    background: #fefce8;
    border: 1px solid #fef08a;
    border-radius: 10px;
    padding: 14px 16px;
}
.modal-nfce-alert-yellow ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.modal-nfce-alert-yellow ul li {
    font-size: 11.5px;
    color: #a16207;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
}
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
.stat-rose   { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); }

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
    min-width: 250px;
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
.action-item-icon.icon-cyan    { background: #ecfeff; color: #0891b2; }
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

/* ─── Estilos Modais Premium NFC-e ─── */
.modal-nfce-header-red {
    background: #ef4444;
    padding: 16px 20px;
}
.modal-nfce-header-red .modal-title {
    color: #fff;
    font-size: 16px;
    font-weight: 700;
}
.modal-nfce-header-red .modal-subtitle {
    color: rgba(255,255,255,0.92);
    font-size: 11.5px;
    margin: 3px 0 0;
}
.modal-nfce-dados-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
}
.modal-nfce-dados-card .card-label {
    font-size: 10.5px;
    color: #94a3b8;
    font-weight: 500;
    margin-bottom: 2px;
}
.modal-nfce-dados-card .card-val {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
}
.modal-nfce-dados-card .card-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 12px;
    display: block;
}
.modal-nfce-alert-danger {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.modal-nfce-alert-yellow {
    background: #fefce8;
    border: 1px solid #fef08a;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 8px;
}
.modal-nfce-alert-yellow ul {
    margin: 0;
    padding: 0;
    list-style: none;
}
.modal-nfce-alert-yellow li {
    font-size: 11.5px;
    color: #713f12;
    line-height: 1.6;
}

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
                            <i class="ri-bill-line"></i>
                            NFCe &mdash; Nota Fiscal de Consumidor Eletrônica
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie notas fiscais de consumidor: emissão, transmissão ao SEFAZ, cancelamento e impressão do DANFCE.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('nfce_view')
                        <a href="{{ route('nfce.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-circle-line"></i> Nova NFCe
                        </a>
                        @endcan
                        <button id="btn-consulta-sefaz" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line"></i> Status SEFAZ
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                @if($contigencia != null)
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-start" style="border-radius: 12px;">
                    <i class="ri-error-warning-line me-2 fs-20 mt-0.5"></i>
                    <div>
                        <strong>Contingência Ativada!</strong>
                        Tipo: <strong>{{ $contigencia->tipo }}</strong> &mdash; Início: <strong>{{ __data_pt($contigencia->created_at) }}</strong>
                    </div>
                </div>
                @endif

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                @if(isset($stats))
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-indigo">
                            <div>
                                <div class="stat-label">Total Emitido</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_vendas']) }}</div>
                            </div>
                            <i class="ri-bill-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Notas Aprovadas</div>
                                <div class="stat-value mt-1">{{ $stats['total_aprovadas'] }}</div>
                            </div>
                            <i class="ri-checkbox-circle-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Notas Pendentes (Novas)</div>
                                <div class="stat-value mt-1">{{ $stats['total_novas'] }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-rose">
                            <div>
                                <div class="stat-label">Canceladas / Rejeitadas</div>
                                <div class="stat-value mt-1">{{ $stats['total_canceladas'] }}</div>
                            </div>
                            <i class="ri-close-circle-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ═══ FILTRO DE BUSCA PREMIUM ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Notas Fiscais (NFC-e)
                        </h5>
                    </div>

                    <form method="get" action="{{ route('nfce.index') }}">
                        <div class="row g-3 align-items-end">
                            <!-- Cliente -->
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                                <select class="select2 form-select" name="cliente_id">
                                    @if(isset($cliente) && $cliente)
                                        <option value="{{ $cliente->id }}" selected>{{ $cliente->info }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Data Inicial -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>

                            <!-- Data Final -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>

                            <!-- Estado -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-equalizer-line"></i> Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="" @selected(request('estado') == '')>Todos</option>
                                    <option value="novo" @selected(request('estado') == 'novo')>Novas</option>
                                    <option value="rejeitado" @selected(request('estado') == 'rejeitado')>Rejeitadas</option>
                                    <option value="cancelado" @selected(request('estado') == 'cancelado')>Canceladas</option>
                                    <option value="aprovado" @selected(request('estado') == 'aprovado')>Aprovadas</option>
                                </select>
                            </div>

                            <!-- Localização -->
                            @if(__countLocalAtivo() > 1)
                                <div class="col-md-2 col-6">
                                    <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                                    <select name="local_id" class="select2 form-select">
                                        <option value="">Selecione</option>
                                        @foreach(__getLocaisAtivoUsuario() as $loc)
                                        <option value="{{ $loc->id }}" @selected(request('local_id') == $loc->id)>{{ $loc->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Botões de Ação -->
                            <div class="col-md-2 col-12 ms-auto d-flex gap-2">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('nfce.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ═══ TABELA DE NFCE ═══ -->
                <div class="tb-wrap mb-4">
                    <div class="table-responsive" style="min-height: 280px;">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    {{-- 1ª Coluna: Ações antes de Cliente --}}
                                    <th class="text-center" style="width: 60px;">Ações</th>
                                    <th>Cliente</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Nº Nota</th>
                                    <th>Valor Total</th>
                                    <th>Estado Fiscal</th>
                                    <th>Ambiente</th>
                                    <th>Data Cadastro</th>
                                    <th>Data Emissão</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    {{-- 1ª Coluna: AÇÕES (Sem o botão Transmitir inline, apenas Dropdown) --}}
                                    <td class="text-center">
                                        <form action="{{ route('nfce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                            @method('delete')
                                            @csrf
                                            <div class="dropdown dropdown-action-menu d-inline-block">
                                                <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções da NFCe">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-start action-dropdown-card shadow-lg">
                                                
                                                {{-- Imprimir NFCe (Aprovado) --}}
                                                @if($item->estado == 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfce.imprimir', [$item->id]) }}">
                                                        <div class="action-item-icon icon-primary">
                                                            <i class="ri-printer-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Imprimir NFC-e</span>
                                                            <span class="action-item-desc">DANFCE fiscal emitido</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Transmitir ao SEFAZ (No Dropdown) --}}
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('nfce_transmitir')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="transmitir('{{$item->id}}')">
                                                            <div class="action-item-icon icon-success">
                                                                <i class="ri-send-plane-fill"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Transmitir NFC-e</span>
                                                                <span class="action-item-desc">Enviar nota para autorização</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Editar NFCe --}}
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('nfce_edit')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('nfce.edit', $item->id) }}">
                                                            <div class="action-item-icon icon-warning">
                                                                <i class="ri-pencil-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Editar NFC-e</span>
                                                                <span class="action-item-desc">Alterar produtos e valores</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Consultar Protocolo SEFAZ --}}
                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                        <div class="action-item-icon icon-teal">
                                                            <i class="ri-file-search-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Consultar SEFAZ</span>
                                                            <span class="action-item-desc">Verificar status do protocolo</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Detalhes do Retorno SEFAZ --}}
                                                @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                        <div class="action-item-icon icon-cyan">
                                                            <i class="ri-information-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Detalhes do Retorno</span>
                                                            <span class="action-item-desc">Chave de acesso e recibo</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Detalhes da NFCe --}}
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfce.show', $item->id) }}">
                                                        <div class="action-item-icon icon-info">
                                                            <i class="ri-eye-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Detalhes da NFC-e</span>
                                                            <span class="action-item-desc">Ver dados completos</span>
                                                        </div>
                                                    </a>
                                                </li>

                                                {{-- Enviar por E-mail (Aprovado) --}}
                                                @if($item->estado == 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="enviarEmail('{{$item->id}}', '{{$item->numero}}')">
                                                        <div class="action-item-icon icon-primary">
                                                            <i class="ri-mail-send-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Enviar por E-mail</span>
                                                            <span class="action-item-desc">Enviar DANFCE e XML</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Download XML (Aprovado) --}}
                                                @if($item->estado == 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfce.download-xml', [$item->id]) }}">
                                                        <div class="action-item-icon icon-teal">
                                                            <i class="ri-download-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Download XML</span>
                                                            <span class="action-item-desc">Baixar XML aprovado</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Alterar Estado Fiscal --}}
                                                @can('nfce_edit')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfce.alterar-estado', $item->id) }}">
                                                        <div class="action-item-icon icon-purple">
                                                            <i class="ri-arrow-up-down-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Alterar Estado</span>
                                                            <span class="action-item-desc">Ajustar status fiscal</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endcan

                                                {{-- XML Temporário --}}
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfce.xml-temp', $item->id) }}">
                                                        <div class="action-item-icon icon-purple">
                                                            <i class="ri-file-code-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">XML Temporário</span>
                                                            <span class="action-item-desc">Visualizar XML gerado</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- DANFCE Temporária --}}
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfce.danfce-temporaria', [$item->id]) }}">
                                                        <div class="action-item-icon icon-warning">
                                                            <i class="ri-printer-fill"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">DANFCE Temporária</span>
                                                            <span class="action-item-desc">Pré-visualizar documento</span>
                                                        </div>
                                                    </a>
                                                </li>

                                                {{-- Cancelar NFCe SEFAZ --}}
                                                @if($item->estado == 'aprovado')
                                                    @can('nfce_transmitir')
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item action-menu-item text-danger w-100 border-0 bg-transparent"
                                                            onclick="cancelar(
                                                                '{{$item->id}}',
                                                                '{{$item->numero}}',
                                                                '{{$item->serie ?? 1}}',
                                                                '{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '--' }}',
                                                                '{{$item->cliente ? $item->cliente->razao_social : '--'}}',
                                                                '{{$item->chave ?? '--'}}'
                                                            )">
                                                            <div class="action-item-icon icon-danger">
                                                                <i class="ri-close-circle-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title text-danger">Cancelar NFCe</span>
                                                                <span class="action-item-desc text-danger-emphasis">Cancelar documento na SEFAZ</span>
                                                            </div>
                                                        </button>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Excluir NFCe --}}
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('nfce_delete')
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                            <div class="action-item-icon icon-danger">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title text-danger">Excluir NFCe</span>
                                                                <span class="action-item-desc text-danger-emphasis">Remover registro do sistema</span>
                                                            </div>
                                                        </button>
                                                    </li>
                                                    @endcan
                                                @endif

                                                </ul>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- 2ª Coluna: Cliente com Ícone e CPF/CNPJ --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block fs-13">
                                                    {{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome != "" ? $item->cliente_nome : "Consumidor Final") }}
                                                </span>
                                                <span class="text-muted fs-11">
                                                    {{ $item->cliente ? $item->cliente->cpf_cnpj : ($item->cliente_cpf_cnpj != "" ? $item->cliente_cpf_cnpj : '--') }}
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

                                    {{-- Nº Nota --}}
                                    <td>
                                        <span class="fw-bold text-dark">{{ $item->numero ?: '--' }}</span>
                                    </td>

                                    {{-- Valor Total --}}
                                    <td>
                                        <strong class="text-success" style="font-size: 13.5px;">R$ {{ __moeda($item->total) }}</strong>
                                    </td>

                                    {{-- Estado Fiscal --}}
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                        @endif
                                    </td>

                                    {{-- Ambiente --}}
                                    <td>
                                        <span class="badge bg-light text-dark border fs-11">
                                            {{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}
                                        </span>
                                    </td>

                                    {{-- Cadastro --}}
                                    <td class="fs-12 text-muted">{{ __data_pt($item->created_at) }}</td>

                                    {{-- Emissão --}}
                                    <td class="fs-12 text-muted">{{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : '--' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ __countLocalAtivo() > 1 ? 9 : 8 }}">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma NFCe encontrada.</p>
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
                        <h5 class="m-0 text-dark fs-14">Total das Vendas: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL CANCELAR NFCe — DESIGN PREMIUM IDÊNTICO AO MODELO     -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

            {{-- Cabeçalho Vermelho --}}
            <div class="modal-nfce-header-red d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0" id="modalCancelarLabel">Cancelar NFC-e</h5>
                    <p class="modal-subtitle">O cancelamento da NFC-e será transmitido para a SEFAZ</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">

                {{-- Alerta de Atenção Vermelho --}}
                <div class="modal-nfce-alert-danger">
                    <i class="ri-alert-line" style="color:#ef4444; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                    <div>
                        <div style="font-size:12px; font-weight:700; color:#dc2626; margin-bottom:2px;">Atenção ao cancelar esta NFC-e</div>
                        <p style="font-size:11px; color:#ef4444; margin:0; line-height:1.4;">Após o cancelamento autorizado pela SEFAZ, a nota fiscal ficará sem validade fiscal e não poderá ser utilizada novamente.</p>
                    </div>
                </div>

                {{-- Card Dados da NFC-e --}}
                <div class="modal-nfce-dados-card">
                    <span class="card-title">Dados da NFC-e</span>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card-label">Número NFC-e</div>
                            <div class="card-val" id="cancela-card-numero">--</div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="card-label">Série</div>
                            <div class="card-val" id="cancela-card-serie">1</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Data de Emissão</div>
                            <div class="card-val" id="cancela-card-data">--</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="card-label">Cliente</div>
                            <div class="card-val" id="cancela-card-cliente">--</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card-label">Chave de Acesso</div>
                            <div style="font-size:12px; font-weight:700; color:#334155; word-break:break-all;" id="cancela-card-chave">--</div>
                        </div>
                    </div>
                </div>

                {{-- Campo Motivo --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">
                        Motivo do Cancelamento <span class="text-danger">*</span>
                    </label>
                    <textarea
                        id="inp-motivo-cancela"
                        class="form-control"
                        rows="3"
                        maxlength="255"
                        style="border-radius:8px; border-color:#cbd5e1; font-size:13px; resize:vertical;"
                        placeholder="Descreva o motivo do cancelamento da NFC-e"
                        required
                        minlength="15"
                        oninput="nfceAtualizarContadorCancela(this)"></textarea>
                    <div class="mt-1">
                        <span style="font-size:11px; color:#94a3b8;"><span id="cancela-char-count">0</span> de 255 caracteres utilizados</span>
                    </div>
                </div>

                {{-- Alerta Amarelo Importante --}}
                <div class="modal-nfce-alert-yellow">
                    <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <i class="ri-information-line" style="color:#ca8a04;"></i> Importante
                    </div>
                    <ul>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> O cancelamento deve respeitar o prazo permitido pela SEFAZ;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Após autorizado, o cancelamento não poderá ser revertido;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Informe um motivo claro e objetivo.</li>
                    </ul>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm px-4 fw-bold"
                    style="background:#ef4444; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="ri-record-circle-line"></i> Transmitir Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL E-MAIL NFCe — DESIGN PREMIUM                          -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-email" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.15);">

            <div style="background:#2563eb; padding:16px 20px;" class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0" id="modalEmailLabel" style="color:#fff; font-size:16px; font-weight:700;">
                        <i class="ri-mail-send-line me-2"></i>Enviar NFC-e por E-mail
                    </h5>
                    <p style="color:rgba(255,255,255,0.9); font-size:11.5px; margin:3px 0 0;">Nota Fiscal <strong class="ref-numero"></strong></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">Endereço de E-mail do Destinatário:</label>
                    <input type="email" id="inp-email" name="email" class="form-control" placeholder="cliente@email.com"
                        style="border-radius:8px; border-color:#cbd5e1; font-size:13px;" required>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px;">
                    <p style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:10px;">Anexos:</p>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inp-danfe" name="danfe" checked>
                            <label class="form-check-label" for="inp-danfe" style="font-size:13px;">Incluir DANFCE (PDF)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inp-xml" name="xml" checked>
                            <label class="form-check-label" for="inp-xml" style="font-size:13px;">Incluir XML</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                <button type="button" id="btn-enviar-email" class="btn btn-primary btn-sm px-4 fw-bold"
                    style="background:#2563eb; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="ri-mail-send-line"></i> Enviar E-mail
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n\n"
            text += "Chave: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave: " + chave + "\n"
            text += "Recibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }

    $('#btn-consulta-sefaz').click(() => {
        $.post(path_url + 'api/nfce_painel/consulta-status-sefaz', { 
            empresa_id: $('#empresa_id').val(),
            usuario_id: $('#usuario_id').val(),
        })
        .done((res) => {
            let msg = "cStat: " + res.cStat
            msg += "\nMotivo: " + res.xMotivo
            msg += "\nAmbiente: " + (res.tpAmb == 2 ? "Homologação" : "Produção")
            msg += "\nverAplic: " + res.verAplic
            swal("Status SEFAZ", msg, "success")
        })
        .fail((err) => {
            try { swal("Erro", err.responseText, "error") }
            catch { swal("Erro", "Algo deu errado", "error") }
        })
    })
</script>

{{-- Carregar nfce_transmitir.js PRIMEIRO, depois sobrescrever cancelar() com versão premium --}}
<script type="text/javascript" src="/js/nfce_transmitir.js"></script>

<script type="text/javascript">
    // ─── Sobrescreve cancelar() com versão premium (card de dados) ───
    function cancelar(id, numero, serie, data, cliente, chave) {
        IDNFE = id;
        $('#cancela-card-numero').text(numero || '--');
        $('#cancela-card-serie').text(serie || '1');
        $('#cancela-card-data').text(data || '--');
        $('#cancela-card-cliente').text(cliente || '--');
        $('#cancela-card-chave').text(chave || '--');
        $('#inp-motivo-cancela').val('');
        $('#cancela-char-count').text('0');
        $('#modal-cancelar').modal('show');
    }

    function nfceAtualizarContadorCancela(el) {
        $('#cancela-char-count').text(el.value.length);
    }
</script>
@endsection
