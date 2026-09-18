@extends('layouts.app', ['title' => 'Devoluções'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

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
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Dropdown de Ações Moderno com Submenus ─── */
.btn-action-trigger {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.2s ease;
    padding: 0;
}
.btn-action-trigger:hover, 
.btn-action-trigger:focus,
.dropdown-action-menu.show .btn-action-trigger {
    background: #f1f5f9;
    color: #3b82f6;
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
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
.action-item-icon.icon-info { background: #f0f9ff; color: #0284c7; }
.action-item-icon.icon-purple { background: #faf5ff; color: #7c3aed; }
.action-item-icon.icon-teal { background: #f0fdfa; color: #0d9488; }
.action-item-icon.icon-success { background: #f0fdf4; color: #16a34a; }
.action-item-icon.icon-cyan { background: #ecfeff; color: #0891b2; }
.action-item-icon.icon-warning { background: #fffbeb; color: #d97706; }
.action-item-icon.icon-danger { background: #fef2f2; color: #dc2626; }

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

    .stat-card { border:0; border-radius:16px; padding:18px 20px; height:100%; color:#fff; position:relative; overflow:hidden; transition:transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content:''; position:absolute; top:-44px; right:-44px; width:130px; height:130px; border-radius:50%; background:rgba(255,255,255,.12); }
    .stat-indigo { background:linear-gradient(135deg,#6366f1,#4f46e5); box-shadow:0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background:linear-gradient(135deg,#24c98a,#109f61); box-shadow:0 6px 18px rgba(16,185,129,.32); }
    .stat-red    { background:linear-gradient(135deg,#fb7185,#dc2626); box-shadow:0 6px 18px rgba(239,68,68,.32); }
    .stat-amber  { background:linear-gradient(135deg,#fbbf24,#d97706); box-shadow:0 6px 18px rgba(245,158,11,.32); }
    .stat-card .st-label { font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:rgba(255,255,255,.85); }
    .stat-card .st-value { font-size:26px; font-weight:800; color:#fff; margin-top:4px; line-height:1.1; }
    .stat-card .st-sub { font-size:11.5px; color:rgba(255,255,255,.75); margin-top:4px; }
    .stat-card .st-icon { width:46px; height:46px; border-radius:13px; background:rgba(255,255,255,.22); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; }
    .filter-wrap { background:#fff; border:1px solid #e9ecf3; border-radius:14px; box-shadow:0 1px 2px rgba(16,24,40,.04); padding:18px 20px; margin-bottom:18px; }
    .filter-title { font-size:13px; font-weight:700; color:#3f3e6a; text-transform:uppercase; letter-spacing:.5px; }
    .filter-title i { color:#4f46e5; margin-right:6px; }
    .filter-wrap label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#8c8ca6; margin-bottom:6px; }
    .filter-wrap label i { color:#a8a8c0; }
    .filter-wrap .form-control, .filter-wrap .form-select { height:40px; border-radius:10px; border:1px solid #dcdce9; font-size:13.5px; color:#1f2937; background:#fcfdfe; transition:all .15s ease; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); background:#fff; }
    .tb-wrap { border-radius:14px; border:1px solid #eef0f5; overflow:hidden; background:#fff; }
    .tb-wrap table { margin-bottom:0; }
    .tb-wrap thead th { background:#f8f9fc; color:#5a5a7a; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.4px; padding:13px 14px; border-bottom:1px solid #e8eaf6; white-space:nowrap; }
    .tb-wrap tbody td { padding:13px 14px; vertical-align:middle; border-bottom:1px solid #f0f2f8; font-size:13.5px; color:#374151; }
    .tb-wrap tbody tr:hover { background:#f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom:none; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-arrow-go-back-line"></i>
                            Painel de Devoluções de Mercadorias
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as notas fiscais de devolução, importando o XML original e configurando os dados de retorno ao fornecedor.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        @can('devolucao_create')
                        <a href="{{ route('devolucao.xml') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-file-upload-line"></i> Nova Devolução
                        </a>
                        @endcan
                        @if(__isPlanoFiscal())
                        <button id="btn-consulta-sefaz" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line align-middle me-1"></i> Status SEFAZ
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                {{-- Cards de Estatísticas --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Devoluções</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">registradas</div></div><div class="st-icon"><i class="ri-arrow-go-back-line"></i></div></div></div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Aprovadas</div><div class="st-value">{{ $stats['aprovadas'] }}</div><div class="st-sub">autorizadas</div></div><div class="st-icon"><i class="ri-checkbox-circle-line"></i></div></div></div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-red"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Canceladas</div><div class="st-value">{{ $stats['canceladas'] }}</div><div class="st-sub">canceladas</div></div><div class="st-icon"><i class="ri-close-circle-line"></i></div></div></div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Valor Total</div><div class="st-value">R$ {{ __moeda($stats['valor']) }}</div><div class="st-sub">devoluções aprovadas</div></div><div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div></div></div>
                    </div>
                </div>

                                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="filter-wrap">
                    <div class="filtro-premium-header">
                        <h5 class="filter-title mb-0">
                            <i class="ri-search-line"></i> Filtrar Devoluções
                        </h5>
                    </div>

                    <form method="get" action="{{ route('devolucao.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-truck-line"></i> Fornecedor</label>
                                <select class="select2 form-select" name="fornecedor_id">
                                    @if(isset($fornecedor) && $fornecedor)
                                        <option value="{{ $fornecedor->id }}" selected>{{ $fornecedor->info }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>
                            @if(__isPlanoFiscal())
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-equalizer-line"></i> Estado NFe</label>
                                <select name="estado" class="form-select">
                                    <option value="" @selected(request('estado') == '')>Todos</option>
                                    <option value="novo" @selected(request('estado') == 'novo')>Novas</option>
                                    <option value="rejeitado" @selected(request('estado') == 'rejeitado')>Rejeitadas</option>
                                    <option value="cancelado" @selected(request('estado') == 'cancelado')>Canceladas</option>
                                    <option value="aprovado" @selected(request('estado') == 'aprovado')>Aprovadas</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-6">
                                <label class="form-label"><i class="ri-arrow-left-right-line"></i> Tipo</label>
                                <select name="tpNF" class="form-select">
                                    <option value="" @selected(request('tpNF') == '')>Todos</option>
                                    <option value="1" @selected(request('tpNF') === '1')>Saída</option>
                                    <option value="0" @selected(request('tpNF') === '0')>Entrada</option>
                                </select>
                            </div>
                            @endif
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
                            <div class="col-md-2 col-12 ms-auto d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" style="border-radius:10px;" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('devolucao.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap mb-4">
                    <div class="table-responsive" style="min-height: 280px;">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th class="text-end" style="width: 80px;">Ações</th>
                                    <th>Fornecedor / Cliente</th>
                                    <th>Nº Nota</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Valor Total (R$)</th>
                                    @if(__isPlanoFiscal())
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    @endif
                                    <th>Data</th>
                                    <th>Emissão</th>
                                    <th>CRT</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    {{-- Última Coluna: Ações com Submenus em Dropdown --}}
                                    <td class="text-end">
                                        <form action="{{ route('devolucao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                            @method('delete')
                                            @csrf
                                            <div class="dropdown dropdown-action-menu d-inline-block">
                                                <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções da Devolução">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-card shadow-lg">
                                                    
                                                    {{-- Imprimir NFe --}}
                                                    @if($item->estado == 'aprovado')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="imprimir('{{$item->id}}', '{{$item->numero}}')">
                                                            <div class="action-item-icon icon-primary">
                                                                <i class="ri-printer-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Imprimir NFe</span>
                                                                <span class="action-item-desc">Escolher modelo DANFE</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endif

                                                    {{-- Transmitir ao SEFAZ --}}
                                                    @if(($item->estado == 'novo' || $item->estado == 'rejeitado') && __isPlanoFiscal())
                                                        @can('nfe_transmitir')
                                                        <li>
                                                            <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="transmitir('{{$item->id}}')">
                                                                <div class="action-item-icon icon-success">
                                                                    <i class="ri-send-plane-fill"></i>
                                                                </div>
                                                                <div class="action-item-content">
                                                                    <span class="action-item-title">Transmitir SEFAZ</span>
                                                                    <span class="action-item-desc">Enviar nota para autorização</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        @endcan
                                                    @endif

                                                    {{-- Editar Devolução --}}
                                                    @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                        @can('devolucao_edit')
                                                        <li>
                                                            <a class="dropdown-item action-menu-item" href="{{ route('devolucao.edit', $item->id) }}">
                                                                <div class="action-item-icon icon-warning">
                                                                    <i class="ri-pencil-line"></i>
                                                                </div>
                                                                <div class="action-item-content">
                                                                    <span class="action-item-title">Editar Devolução</span>
                                                                    <span class="action-item-desc">Alterar produtos e valores</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        @endcan
                                                    @endif

                                                    {{-- Carta de Correção (CC-e) --}}
                                                    @if($item->estado == 'aprovado')
                                                        @can('nfe_transmitir')
                                                        <li>
                                                            <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="corrigir('{{$item->id}}', '{{$item->numero}}')">
                                                                <div class="action-item-icon icon-warning">
                                                                    <i class="ri-file-warning-line"></i>
                                                                </div>
                                                                <div class="action-item-content">
                                                                    <span class="action-item-title">Carta de Correção</span>
                                                                    <span class="action-item-desc">Emitir CC-e para a NFe</span>
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

                                                    {{-- Detalhes do Retorno --}}
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

                                                    {{-- Alterar Estado Fiscal --}}
                                                    @if(__isPlanoFiscal())
                                                        @can('devolucao_edit')
                                                        <li>
                                                            <a class="dropdown-item action-menu-item" href="{{ route('nfe.alterar-estado', [$item->id, 'tipo=devolucao']) }}">
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
                                                    @endif

                                                    {{-- XML Temporário --}}
                                                    @if(($item->estado == 'novo' || $item->estado == 'rejeitado') && __isPlanoFiscal())
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.xml-temp', $item->id) }}">
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

                                                    {{-- Imprimir Cancelamento --}}
                                                    @if($item->estado == 'cancelado')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.imprimir-cancela', [$item->id]) }}">
                                                            <div class="action-item-icon icon-danger">
                                                                <i class="ri-printer-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Imprimir Cancelamento</span>
                                                                <span class="action-item-desc">Comprovante de cancelamento</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endif

                                                    {{-- Cancelar NFe SEFAZ --}}
                                                    @if($item->estado == 'aprovado')
                                                        @can('nfe_transmitir')
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        <li>
                                                            <button type="button" class="dropdown-item action-menu-item text-danger w-100 border-0 bg-transparent" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                                <div class="action-item-icon icon-danger">
                                                                    <i class="ri-close-circle-line"></i>
                                                                </div>
                                                                <div class="action-item-content">
                                                                    <span class="action-item-title text-danger">Cancelar NFe</span>
                                                                    <span class="action-item-desc text-danger-emphasis">Cancelar documento na SEFAZ</span>
                                                                </div>
                                                            </button>
                                                        </li>
                                                        @endcan
                                                    @endif

                                                    {{-- Excluir Devolução --}}
                                                    @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                        @can('devolucao_delete')
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        <li>
                                                            <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                                <div class="action-item-icon icon-danger">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </div>
                                                                <div class="action-item-content">
                                                                    <span class="action-item-title text-danger">Excluir Devolução</span>
                                                                    <span class="action-item-desc text-danger-emphasis">Remover do sistema</span>
                                                                </div>
                                                            </button>
                                                        </li>
                                                        @endcan
                                                    @endif

                                                </ul>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- 1ª Coluna: Fornecedor / Cliente (Identificação Principal) --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($item->cliente)
                                                <strong class="text-dark">{{ $item->cliente->razao_social }}</strong>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light text-muted border fs-11">#{{ $item->numero_sequencial }}</span>
                                                    <span class="text-muted fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                                </div>
                                            @else
                                                <strong class="text-dark">{{ $item->fornecedor ? $item->fornecedor->razao_social : '--' }}</strong>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light text-muted border fs-11">#{{ $item->numero_sequencial }}</span>
                                                    @if($item->fornecedor && $item->fornecedor->cpf_cnpj)
                                                        <span class="text-muted fs-11">{{ $item->fornecedor->cpf_cnpj }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Nº Nota --}}
                                    <td>
                                        <span class="fw-bold text-dark">{{ $item->numero ?: '--' }}</span>
                                    </td>

                                    {{-- Local (se multi-local) --}}
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                            {{ $item->localizacao ? $item->localizacao->descricao : '--' }}
                                        </span>
                                    </td>
                                    @endif

                                    {{-- Valor Total --}}
                                    <td>
                                        <strong class="text-success" style="font-size: 13.5px;">R$ {{ __moeda($item->total) }}</strong>
                                    </td>

                                    {{-- Estado e Ambiente Fiscal --}}
                                    @if(__isPlanoFiscal())
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
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                        </span>
                                    </td>
                                    @endif

                                    {{-- Data --}}
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>

                                    {{-- Emissão --}}
                                    <td>
                                        @if($item->api)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">API</span>
                                        @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Painel</span>
                                        @endif
                                    </td>

                                    {{-- CRT --}}
                                    <td class="fs-11 text-muted">
                                        @if($item->crt == 1) Simples Nacional
                                        @elseif($item->crt == 2) Simples Exc. Sublimite
                                        @elseif($item->crt == 3) Regime Normal
                                        @endif
                                    </td>

                                    {{-- Tipo (Entrada/Saída) --}}
                                    <td>
                                        @if($item->tpNF)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Saída</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">Entrada</span>
                                        @endif
                                    </td>


                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma devolução cadastrada no período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação & Rodapé de Valores -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark">Total das Devoluções no Grid: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Imprimir NFe -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrintLabel">Imprimir Nota de Devolução <strong class="ref-numero text-primary"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-success w-100 btn-sm" onclick="gerarDanfe('danfe')">
                            <i class="ri-printer-line me-1"></i> DANFE Padrão
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-primary w-100 btn-sm" onclick="gerarDanfe('simples')">
                            <i class="ri-printer-line me-1"></i> DANFE Simples
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-dark w-100 btn-sm" onclick="gerarDanfe('etiqueta')">
                            <i class="ri-printer-line me-1"></i> DANFE Etiqueta
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar NFe -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCancelarLabel">Cancelar NFe <strong class="ref-numero text-danger"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-cancela', 'Motivo da Justificativa (Mínimo 15 caracteres)')->required()->attrs(['class' => 'form-control'])!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm">Confirmar Cancelamento</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corrigir NFe -->
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCorrigirLabel">Emitir Carta de Correção <strong class="ref-numero text-warning"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-corrigir', 'Texto de Correção (Mínimo 15 caracteres)')->required()->attrs(['class' => 'form-control'])!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="btn btn-warning btn-sm">Transmitir Correção</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo Rejeição: " + motivo_rejeicao + "\n\n"
            text += "Chave Acesso: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave Acesso: " + chave + "\n"
            text += "Número Recibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }

    $('#btn-consulta-sefaz').click(() => {
        $.post(path_url + 'api/nfe_painel/consulta-status-sefaz', {
            usuario_id: $('#usuario_id').val(),
            empresa_id: $('#empresa_id').val()
        })
        .done((res) => {
            let msg = "cStat: " + res.cStat
            msg += "\nMotivo: " + res.xMotivo
            msg += "\nAmbiente: " + (res.tpAmb == 2 ? "Homologação" : "Produção")
            msg += "\nVerAplic: " + res.verAplic
            swal("Status SEFAZ", msg, "success")
        })
        .fail((err) => {
            try { swal("Erro", err.responseText, "error") }
            catch { swal("Erro", "Algo deu errado", "error") }
        })
    })
</script>
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>
@endsection
