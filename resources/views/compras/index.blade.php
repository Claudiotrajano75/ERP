@extends('layouts.app', ['title' => 'Compras'])

@section('css')
    <style>
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
            color: #2e7d32;
            letter-spacing: -0.3px;
        }

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

        .modulo-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .modulo-badge-success {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
        }

        .modulo-badge-warning {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #e65100;
        }

        .modulo-badge-danger {
            background: linear-gradient(135deg, #fbe9e7, #ffccbc);
            color: #c62828;
        }

        .modulo-badge-info {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1565c0;
        }

        @media (max-width: 768px) {
            .modulo-header-gradient .modulo-title {
                font-size: 18px;
            }
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
    .pill { display:inline-flex; align-items:center; gap:5px; border-radius:8px; padding:4px 10px; font-size:11.5px; font-weight:700; }
    .pill-ok { background:#dcfce7; color:#15803d; }
    .pill-red { background:#fee2e2; color:#b91c1c; }
    .pill-amber { background:#fef3c7; color:#b45309; }
    .pill-info { background:#e0f2fe; color:#0284c7; }
    .pill-gray { background:#f1f5f9; color:#64748b; }

    /* ─── Estilos Modais Premium Compras (Cancelamento & Carta de Correção) ─── */
    .modal-compras-header-red {
        background: #ef4444;
        padding: 16px 20px;
    }
    .modal-compras-header-red .modal-title {
        color: #fff;
        font-size: 16px;
        font-weight: 700;
    }
    .modal-compras-header-red .modal-subtitle {
        color: rgba(255,255,255,0.92);
        font-size: 11.5px;
        margin: 3px 0 0;
    }
    .modal-compras-header-amber {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        padding: 16px 20px;
    }
    .modal-compras-header-amber .modal-title {
        color: #fff;
        font-size: 16px;
        font-weight: 700;
    }
    .modal-compras-header-amber .modal-subtitle {
        color: rgba(255,255,255,0.92);
        font-size: 11.5px;
        margin: 3px 0 0;
    }
    .modal-compras-dados-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .modal-compras-dados-card .card-label {
        font-size: 10.5px;
        color: #94a3b8;
        font-weight: 500;
        margin-bottom: 2px;
    }
    .modal-compras-dados-card .card-val {
        font-size: 12.5px;
        font-weight: 700;
        color: #0f172a;
    }
    .modal-compras-dados-card .card-title {
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 12px;
        display: block;
    }
    .modal-alert-danger-soft {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .modal-alert-amber-soft {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .modal-alert-yellow {
        background: #fefce8;
        border: 1px solid #fef08a;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 8px;
    }
    .modal-alert-yellow ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .modal-alert-yellow li {
        font-size: 11.5px;
        color: #713f12;
        line-height: 1.6;
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
                                <i class="ri-logout-box-line"></i>
                                Entradas de Mercadorias (Compras)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as compras efetuadas, dê entrada nos
                                estoques importando arquivos XML de fornecedores ou manualmente.</p>
                        </div>
                        <div class="d-flex gap-2">
                            @can('compras_create')
                                <a href="{{ route('compras.create') }}" class="dash-btn dash-btn-primary">
                                    <i class="ri-add-line"></i> Nova Compra
                                </a>
                            @endcan
                            <a href="{{ route('compras.xml') }}" class="dash-btn dash-btn-light">
                                <i class="ri-file-upload-line"></i> Importar XML
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- Cards de Estatísticas --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-indigo"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Compras</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">entradas registradas</div></div><div class="st-icon"><i class="ri-logout-box-line"></i></div></div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-green"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Aprovadas</div><div class="st-value">{{ $stats['aprovadas'] }}</div><div class="st-sub">documentos autorizados</div></div><div class="st-icon"><i class="ri-checkbox-circle-line"></i></div></div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-red"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Canceladas</div><div class="st-value">{{ $stats['canceladas'] }}</div><div class="st-sub">documentos cancelados</div></div><div class="st-icon"><i class="ri-close-circle-line"></i></div></div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-amber"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Valor Total</div><div class="st-value">R$ {{ __moeda($stats['valor']) }}</div><div class="st-sub">compras aprovadas</div></div><div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div></div></div>
                        </div>
                    </div>

                                        <!-- ═══ Filtros de Busca Premium ═══ -->
                    <div class="filter-wrap">
                        <div class="filtro-premium-header">
                            <h5 class="filter-title mb-0">
                                <i class="ri-search-line"></i> Filtrar Compras
                            </h5>
                        </div>

                    <form method="get" action="{{ route('compras.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-truck-line"></i> Fornecedor</label>
                                <select class="select2 form-select" name="fornecedor_id">
                                    <option value="">Selecione</option>
                                    @foreach($fornecedores as $f)
                                    <option value="{{ $f->id }}" @selected(request('fornecedor_id') == $f->id)>{{ $f->razao_social }}</option>
                                    @endforeach
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
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                                <select name="estado" class="form-select">
                                    <option value="" @selected(request('estado') == '')>Todos</option>
                                    <option value="novo" @selected(request('estado') == 'novo')>Novas</option>
                                    <option value="rejeitado" @selected(request('estado') == 'rejeitado')>Rejeitadas</option>
                                    <option value="cancelado" @selected(request('estado') == 'cancelado')>Canceladas</option>
                                    <option value="aprovado" @selected(request('estado') == 'aprovado')>Aprovadas</option>
                                </select>
                            </div>
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
                                <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('compras.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                    </div>

                    @if($contigencia != null)
                        <div
                            class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-center">
                            <i class="ri-error-warning-line me-2 fs-20"></i>
                            <div>
                                <strong class="d-block">Contingência Ativada!</strong>
                                <span>Tipo: {{ $contigencia->tipo }} | Início em:
                                    {{ __data_pt($contigencia->created_at) }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- ═══ Tabela Premium ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive" style="min-height: 280px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-end" style="width: 80px;">Ações</th>
                                        <th>Fornecedor</th>
                                        <th>Nº Doc</th>
                                        @if(__countLocalAtivo() > 1)
                                            <th>Local / Filial</th>
                                        @endif
                                        <th>Valor Total (R$)</th>
                                        <th>Estado</th>
                                        <th>Ambiente</th>
                                        <th>Data Emissão</th>
                                        <th>Emissão</th>
                                        <th>Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            {{-- Última Coluna: Ações com Submenus em Dropdown --}}
                                            <td class="text-end">
                                                <form action="{{ route('nfe.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="dropdown dropdown-action-menu d-inline-block">
                                                        <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções da Compra">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end action-dropdown-card shadow-lg">
                                                            
                                                            {{-- Imprimir DANFE --}}
                                                            @if($item->estado == 'aprovado')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.imprimir', [$item->id]) }}">
                                                                    <div class="action-item-icon icon-primary">
                                                                        <i class="ri-printer-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Imprimir DANFE</span>
                                                                        <span class="action-item-desc">Visualizar DANFE em PDF</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @endif

                                                            {{-- Pedido de Compra --}}
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.imprimirVenda', [$item->id]) }}">
                                                                    <div class="action-item-icon icon-info">
                                                                        <i class="ri-file-text-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Pedido de Compra</span>
                                                                        <span class="action-item-desc">Imprimir espelho da compra</span>
                                                                    </div>
                                                                </a>
                                                            </li>

                                                            {{-- Transmitir ao SEFAZ --}}
                                                            @if($item->estado == 'novo' || $item->estado == 'rejeitado')
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

                                                            {{-- Editar Compra --}}
                                                            @if(($item->estado == 'novo' || $item->estado == 'rejeitado') && $item->chave_importada == '')
                                                                @can('compras_edit')
                                                                <li>
                                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfe.edit', $item->id) }}">
                                                                        <div class="action-item-icon icon-warning">
                                                                            <i class="ri-edit-line"></i>
                                                                        </div>
                                                                        <div class="action-item-content">
                                                                            <span class="action-item-title">Editar Compra</span>
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
                                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="corrigir(
                                                                        '{{$item->id}}',
                                                                        '{{$item->numero}}',
                                                                        '{{$item->serie ?? 1}}',
                                                                        '{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '--' }}',
                                                                        '{{ $item->fornecedor ? addslashes($item->fornecedor->razao_social) : '--' }}',
                                                                        '{{ $item->chave ?? '--' }}'
                                                                    )">
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
                                                                        <span class="action-item-desc">Recibo e chave de acesso</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @endif

                                                            {{-- Gerar Etiquetas --}}
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('compras.etiqueta', [$item->id]) }}">
                                                                    <div class="action-item-icon icon-teal">
                                                                        <i class="ri-barcode-box-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Gerar Etiquetas</span>
                                                                        <span class="action-item-desc">Etiquetas de preço e barras</span>
                                                                    </div>
                                                                </a>
                                                            </li>

                                                            {{-- Lotes e Vencimentos --}}
                                                            @if($item->isItemValidade())
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('compras.info-validade', $item->id) }}">
                                                                    <div class="action-item-icon icon-purple">
                                                                        <i class="ri-calendar-check-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Lotes & Validade</span>
                                                                        <span class="action-item-desc">Preencher vencimentos</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @endif

                                                            {{-- XML Temporário --}}
                                                            @if($item->estado == 'novo' || $item->estado == 'rejeitado')
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
                                                                    <button type="button" class="dropdown-item action-menu-item text-danger w-100 border-0 bg-transparent" onclick="cancelar(
                                                                        '{{$item->id}}',
                                                                        '{{$item->numero}}',
                                                                        '{{$item->serie ?? 1}}',
                                                                        '{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '--' }}',
                                                                        '{{ $item->fornecedor ? addslashes($item->fornecedor->razao_social) : '--' }}',
                                                                        '{{ $item->chave ?? '--' }}'
                                                                    )">
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

                                                            {{-- Excluir Compra --}}
                                                            @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                                @can('compras_delete')
                                                                <li><hr class="dropdown-divider my-1"></li>
                                                                <li>
                                                                    <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                                        <div class="action-item-icon icon-danger">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </div>
                                                                        <div class="action-item-content">
                                                                            <span class="action-item-title text-danger">Excluir Compra</span>
                                                                            <span class="action-item-desc text-danger-emphasis">Remover pré-compra</span>
                                                                        </div>
                                                                    </button>
                                                                </li>
                                                                @endcan
                                                            @endif

                                                        </ul>
                                                    </div>
                                                </form>
                                            </td>

                                            {{-- 1ª Coluna: Fornecedor (Identificação Principal) --}}
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong class="text-dark">{{ $item->fornecedor ? $item->fornecedor->razao_social : "Fornecedor não informado" }}</strong>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <span class="badge bg-light text-muted border fs-11">#{{ $item->numero_sequencial }}</span>
                                                        @if($item->fornecedor && $item->fornecedor->cpf_cnpj)
                                                            <span class="text-muted fs-11">{{ $item->fornecedor->cpf_cnpj }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Nº Documento --}}
                                            <td>
                                                <span class="fw-bold text-dark">{{ $item->numero ? $item->numero : '--' }}</span>
                                            </td>

                                            {{-- Local / Filial (se multi-local) --}}
                                            @if(__countLocalAtivo() > 1)
                                                <td>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                        {{ $item->localizacao ? $item->localizacao->descricao : '--' }}
                                                    </span>
                                                </td>
                                            @endif

                                            {{-- Valor Total --}}
                                            <td>
                                                <strong class="text-success" style="font-size: 13.5px;">R$ {{ number_format($item->total, 2, ',', '.') }}</strong>
                                            </td>

                                            {{-- Estado da Emissão --}}
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
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                                </span>
                                            </td>

                                            {{-- Data Emissão --}}
                                            <td class="text-muted fs-12">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>

                                            {{-- Tipo de Emissão --}}
                                            <td>
                                                @if($item->api)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle">API</span>
                                                @else
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Painel</span>
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
                                            <td colspan="12">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Nenhuma compra cadastrada no período.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- ═══ Footer ═══ -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                            <div>
                                <h5 class="m-0 text-dark">Soma das Compras: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
                            </div>
                            <div>{!! $data->appends(request()->all())->links() !!}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL CANCELAR COMPRA — DESIGN PREMIUM                       -->
        <!-- ============================================================ -->
        <div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

                    {{-- Cabeçalho Vermelho --}}
                    <div class="modal-compras-header-red d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title mb-0" id="modalCancelarLabel">Cancelar Nota de Entrada</h5>
                            <p class="modal-subtitle">O cancelamento da NF-e de compra será transmitido para a SEFAZ</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4" style="background:#fff;">

                        {{-- Alerta de Atenção Vermelho --}}
                        <div class="modal-alert-danger-soft">
                            <i class="ri-alert-line" style="color:#ef4444; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                            <div>
                                <div style="font-size:12px; font-weight:700; color:#dc2626; margin-bottom:2px;">Atenção ao cancelar esta NF-e</div>
                                <p style="font-size:11px; color:#ef4444; margin:0; line-height:1.4;">Após o cancelamento autorizado pela SEFAZ, o documento ficará sem validade fiscal e não poderá ser revertido.</p>
                            </div>
                        </div>

                        {{-- Card Dados da Compra --}}
                        <div class="modal-compras-dados-card">
                            <span class="card-title">Dados da Nota de Entrada</span>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="card-label">Número NF-e</div>
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
                                    <div class="card-label">Fornecedor</div>
                                    <div class="card-val" id="cancela-card-fornecedor">--</div>
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
                                placeholder="Descreva o motivo do cancelamento da NF-e"
                                required
                                minlength="15"
                                oninput="comprasAtualizarContadorCancela(this)"></textarea>
                            <div class="mt-1">
                                <span style="font-size:11px; color:#94a3b8;"><span id="cancela-char-count">0</span> de 255 caracteres utilizados</span>
                            </div>
                        </div>

                        {{-- Alerta Amarelo Importante --}}
                        <div class="modal-alert-yellow">
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
        <!-- MODAL CARTA DE CORREÇÃO (CC-e) — DESIGN PREMIUM AMARELO      -->
        <!-- ============================================================ -->
        <div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

                    {{-- Cabeçalho Amarelo / Âmbar --}}
                    <div class="modal-compras-header-amber d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title mb-0" id="modalCorrigirLabel">
                                <i class="ri-file-warning-line me-2"></i>Carta de Correção (CC-e)
                            </h5>
                            <p class="modal-subtitle">O evento CC-e será transmitido à SEFAZ para correção da Nota de Entrada</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4" style="background:#fff;">

                        {{-- Alerta Informativo Âmbar --}}
                        <div class="modal-alert-amber-soft">
                            <i class="ri-information-line" style="color:#d97706; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                            <div>
                                <div style="font-size:12px; font-weight:700; color:#92400e; margin-bottom:2px;">O que é a Carta de Correção?</div>
                                <p style="font-size:11px; color:#b45309; margin:0; line-height:1.4;">A CC-e permite corrigir informações acessórias da Nota Fiscal de Entrada. Ela não pode alterar valores fiscais, impostos ou dados do fornecedor/destinatário.</p>
                            </div>
                        </div>

                        {{-- Card Dados da Compra --}}
                        <div class="modal-compras-dados-card">
                            <span class="card-title">Dados da Nota de Entrada</span>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="card-label">Número NF-e</div>
                                    <div class="card-val" id="cce-card-numero">--</div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="card-label">Série</div>
                                    <div class="card-val" id="cce-card-serie">1</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card-label">Data de Emissão</div>
                                    <div class="card-val" id="cce-card-data">--</div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card-label">Fornecedor</div>
                                    <div class="card-val" id="cce-card-fornecedor">--</div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="card-label">Chave de Acesso</div>
                                    <div style="font-size:12px; font-weight:700; color:#334155; word-break:break-all;" id="cce-card-chave">--</div>
                                </div>
                            </div>
                        </div>

                        {{-- Campo Texto da Correção --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">
                                Texto da Correção <span class="text-danger">*</span>
                            </label>
                            <textarea
                                id="inp-motivo-corrigir"
                                class="form-control"
                                rows="4"
                                maxlength="1000"
                                style="border-radius:8px; border-color:#cbd5e1; font-size:13px; resize:vertical;"
                                placeholder="Descreva aqui a correção a ser considerada.&#10;&#10;Exemplo: Onde se lê &quot;CFOP 1102&quot;, leia-se &quot;CFOP 1403&quot;."
                                required
                                minlength="15"
                                oninput="comprasAtualizarContadorCce(this)"></textarea>
                            <div class="mt-1">
                                <span style="font-size:11px; color:#94a3b8;"><span id="cce-char-count">0</span> de 1000 caracteres utilizados</span>
                            </div>
                        </div>

                        {{-- Alerta Amarelo Atenção --}}
                        <div class="modal-alert-yellow">
                            <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                                <i class="ri-alert-line" style="color:#ca8a04;"></i> Atenção — A CC-e <strong>NÃO</strong> pode ser usada para:
                            </div>
                            <ul>
                                <li><span style="color:#eab308; font-weight:bold;">•</span> Alterar valores fiscais, impostos ou alíquotas;</li>
                                <li><span style="color:#eab308; font-weight:bold;">•</span> Alterar dados do fornecedor ou destinatário;</li>
                                <li><span style="color:#eab308; font-weight:bold;">•</span> Alterar data de emissão da NF-e.</li>
                            </ul>
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                        <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                            style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                        <button type="button" id="btn-corrigir" class="btn btn-sm px-4 fw-bold text-white"
                            style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 2px 6px rgba(217,119,6,0.3);">
                            <i class="ri-send-plane-fill"></i> Transmitir Correção
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
                    let text = "Motivo Rejeição: " + motivo_rejeicao + "\n\n"
                    text += "Chave Acesso: " + chave + "\n"
                    swal("Nota Rejeitada", text, "warning")
                } else {
                    let text = "Chave Acesso: " + chave + "\n"
                    text += "Número Recibo: " + recibo + "\n"
                    swal("Nota Autorizada / Homologada", text, "success")
                }
            }
        </script>
        {{-- Carregar nfe_transmitir.js PRIMEIRO para depois sobrescrever com as funções premium --}}
        <script type="text/javascript" src="/js/nfe_transmitir.js"></script>

        <script type="text/javascript">
            // ─── Sobrescreve cancelar() do nfe_transmitir.js com versão premium (card de dados de Compras) ───
            function cancelar(id, numero, serie, data, fornecedor, chave) {
                IDNFE = id;
                $('.ref-numero').text(numero || '');
                $('#cancela-card-numero').text(numero || '--');
                $('#cancela-card-serie').text(serie || '1');
                $('#cancela-card-data').text(data || '--');
                $('#cancela-card-fornecedor').text(fornecedor || '--');
                $('#cancela-card-chave').text(chave || '--');
                $('#inp-motivo-cancela').val('');
                $('#cancela-char-count').text('0');
                $('#modal-cancelar').modal('show');
            }

            // ─── Sobrescreve corrigir() do nfe_transmitir.js com versão premium (card de dados de Compras) ───
            function corrigir(id, numero, serie, data, fornecedor, chave) {
                IDNFE = id;
                $('.ref-numero').text(numero || '');
                $('#cce-card-numero').text(numero || '--');
                $('#cce-card-serie').text(serie || '1');
                $('#cce-card-data').text(data || '--');
                $('#cce-card-fornecedor').text(fornecedor || '--');
                $('#cce-card-chave').text(chave || '--');
                $('#inp-motivo-corrigir').val('');
                $('#cce-char-count').text('0');
                $('#modal-corrigir').modal('show');
            }

            function comprasAtualizarContadorCancela(el) {
                $('#cancela-char-count').text(el.value.length);
            }

            function comprasAtualizarContadorCce(el) {
                $('#cce-char-count').text(el.value.length);
            }
        </script>
    @endsection