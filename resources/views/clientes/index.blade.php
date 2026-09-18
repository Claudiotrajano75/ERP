@extends('layouts.app', ['title' => 'Clientes'])

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

        /* ─── Dropdown de Ações Moderno com Submenus ─── */
        .btn-action-trigger {
            color: #4f46e5;
            background: #eef0ff;
            border-color: #e3e4ff;
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
            color: #4f46e5;
            background: #eef0ff;
            border-color: #e3e4ff;
            background: #f1f5f9;
            color: #3b82f6;
            border-color: #cbd5e1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
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
        .action-item-icon.icon-info { background: #f0f9ff; color: #0284c7; }
        .action-item-icon.icon-purple { background: #faf5ff; color: #7c3aed; }
        .action-item-icon.icon-teal { background: #f0fdfa; color: #0d9488; }
        .action-item-icon.icon-success { background: #f0fdf4; color: #16a34a; }
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

        .modulo-footer {
            padding: 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
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
    .stat-blue   { background:linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow:0 6px 18px rgba(37,99,235,.32); }
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
    .pill-no { background:#fee2e2; color:#b91c1c; }
    .empty-state { padding:52px 20px; text-align:center; }
    .empty-state i { font-size:52px; color:#c5cae9; display:block; margin-bottom:12px; }
    .empty-state p { color:#9e9eb8; font-size:14px; margin:0; }
</style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="card border-0 shadow-sm">

                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2"><i
                                    class="ri-user-follow-line"></i> Gestão de Clientes</h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Cadastre clientes, acompanhe históricos de
                                vendas e saldos de cashback.</p>
                        </div>
                        <div class="d-flex gap-2">
                            @can('clientes_create')
                                <a href="{{ route('clientes.create') }}" class="dash-btn dash-btn-primary"><i
                                        class="ri-add-line"></i> Novo Cliente</a>
                                <a href="{{ route('clientes.import') }}" class="dash-btn dash-btn-light"><i
                                        class="ri-file-upload-line"></i> Importar</a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- Cards de Estatísticas --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-indigo"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Total de Clientes</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">cadastrados no sistema</div></div><div class="st-icon"><i class="ri-user-follow-line"></i></div></div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-green"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Ativos</div><div class="st-value">{{ $stats['ativos'] }}</div><div class="st-sub">clientes ativos</div></div><div class="st-icon"><i class="ri-checkbox-circle-line"></i></div></div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-red"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Inativos</div><div class="st-value">{{ $stats['inativos'] }}</div><div class="st-sub">clientes inativos</div></div><div class="st-icon"><i class="ri-close-circle-line"></i></div></div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card stat-blue"><div class="d-flex justify-content-between align-items-start"><div><div class="st-label">Nesta Página</div><div class="st-value">{{ $data->count() }}</div><div class="st-sub">registros exibidos</div></div><div class="st-icon"><i class="ri-list-check"></i></div></div></div>
                        </div>
                    </div>

                                        <!-- ═══ Filtros de Busca Premium ═══ -->
                    <div class="filter-wrap">
                        <div class="filtro-premium-header">
                            <h5 class="filter-title mb-0">
                                <i class="ri-search-line"></i> Filtrar Clientes
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Nome / Razão Social</label>
                                {!!Form::text('razao_social', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome ou razão...'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-fingerprint-line"></i> CPF / CNPJ</label>
                                {!!Form::text('cpf_cnpj', '')->attrs(['class' => 'cpf_cnpj form-control', 'placeholder' => 'CPF ou CNPJ...', 'type' => 'tel'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Dt. Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Dt. Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-primary flex-grow-1" style="border-radius:10px;" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('clientes.index') }}"
                                        title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        @can('clientes_delete')
                                            <th style="width:40px;">
                                                <div class="form-check mb-0"><input class="form-check-input" type="checkbox"
                                                        id="select-all-checkbox"></div>
                                            </th>
                                        @endcan
                                        <th>Nome</th>
                                        <th>CPF/CNPJ</th>
                                        <th>Cidade</th>
                                        <th>Endereço</th>
                                        <th>CEP</th>
                                        <th>Status</th>
                                        <th>Cadastro</th>
                                        <th class="text-end" style="width:80px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            @can('clientes_delete')
                                                <td>
                                                    <div class="form-check mb-0"><input class="form-check-input check-delete"
                                                            type="checkbox" name="item_delete[]" value="{{ $item->id }}"></div>
                                                </td>
                                            @endcan
                                            <td class="fw-semibold text-dark">{{ $item->razao_social }}</td>
                                            <td class="fw-bold text-muted fs-12">{{ $item->cpf_cnpj }}</td>
                                            <td class="text-muted fs-12">{{ $item->cidade ? $item->cidade->info : '--' }}</td>
                                            <td class="text-muted fs-12">{{ $item->endereco ?: '--' }}</td>
                                            <td class="text-muted fs-12">{{ $item->cep ?: '--' }}</td>
                                            <td>
                                                @if($item->status)
                                                                                            <span class="pill pill-ok"><i class="ri-check-line"></i> Ativo</span>
                                                @else
                                                                                            <span class="pill pill-no"><i class="ri-close-line"></i> Inativo</span>
                                                @endif
                                            </td>
                                            <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('clientes.destroy', $item->id) }}" method="post"
                                                    id="form-{{$item->id}}" class="m-0 d-inline">
                                                    @method('delete') @csrf
                                                    <div class="dropdown dropdown-action-menu d-inline-block">
                                                        <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções do Cliente">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end action-dropdown-card shadow-lg">
                                                            @can('clientes_edit')
                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('clientes.edit', [$item->id]) }}">
                                                                    <div class="action-item-icon icon-primary">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Editar Cliente</span>
                                                                        <span class="action-item-desc">Alterar dados e cadastro</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            @endcan

                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('clientes.cash-back', [$item->id]) }}">
                                                                    <div class="action-item-icon icon-purple">
                                                                        <i class="ri-coins-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Cashback</span>
                                                                        <span class="action-item-desc">Consultar saldo e extrato</span>
                                                                    </div>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a class="dropdown-item action-menu-item" href="{{ route('clientes.historico', [$item->id]) }}">
                                                                    <div class="action-item-icon icon-info">
                                                                        <i class="ri-file-list-3-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title">Histórico</span>
                                                                        <span class="action-item-desc">Ver histórico de compras</span>
                                                                    </div>
                                                                </a>
                                                            </li>

                                                            @can('clientes_delete')
                                                            <li><hr class="dropdown-divider my-1"></li>
                                                            <li>
                                                                <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                                    <div class="action-item-icon icon-danger">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </div>
                                                                    <div class="action-item-content">
                                                                        <span class="action-item-title text-danger">Excluir Cliente</span>
                                                                        <span class="action-item-desc text-danger-emphasis">Remover registro</span>
                                                                    </div>
                                                                </button>
                                                            </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ (Auth::user()->can('clientes_delete') ? 1 : 0) + 8 }}">
                                                <div class="empty-state"><i class="ri-inbox-2-line"></i>
                                                    <p>Nenhum cliente cadastrado.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modulo-footer">
                        <div class="d-flex gap-2 flex-wrap">
                            @can('clientes_delete')
                                <form action="{{ route('clientes.destroy-select') }}" method="post" id="form-delete-select"
                                    class="m-0">
                                    @method('delete') @csrf
                                    <button type="button" class="dash-btn dash-btn-light btn-delete-all" style="color:#dc2626;" disabled><i
                                            class="ri-delete-bin-2-line"></i> Remover Selecionados</button>
                                </form>
                            @endcan
                        </div>
                        <div>{!! $data->appends(request()->all())->links() !!}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/js/delete_selecionados.js"></script>
@endsection