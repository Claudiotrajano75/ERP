@extends('layouts.app', ['title' => 'Categorias no E-commerce'])

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
            padding: 12px 16px;
            border-bottom: 2px solid #e8eaf6;
        }

        .modulo-table-wrap tbody td {
            padding: 12px 16px;
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

        /* ─── Badges de Status ─── */
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

        .modulo-badge-secondary {
            background: #f1f3f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        /* ─── Botões de Ação ─── */
        .modulo-action-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .modulo-action-group .btn {
            border-radius: 8px;
            padding: 5px 10px;
            font-size: 12px;
            transition: all 0.15s ease;
        }

        .modulo-action-group .btn:hover {
            transform: translateY(-1px);
        }

        /* ─── Switch Toggle Premium ─── */
        .switch-container-premium {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            padding: 4px 10px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .switch-container-premium:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .switch-premium {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
            vertical-align: middle;
            margin-left: 8px;
            margin-bottom: 0;
        }

        .switch-premium input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-premium .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 34px;
        }

        .switch-premium .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }

        .switch-premium input:checked + .slider {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35);
        }

        .switch-premium input:checked + .slider:before {
            transform: translateX(18px);
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
            color: #5572f5;
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
                <div class="card border-0 shadow-sm modulo-form-card">

                    <!-- ═══ Cabeçalho Premium ═══ -->
                    <div class="card-header modulo-header-gradient py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                    <i class="ri-store-2-line"></i>
                                    Categorias no E-commerce
                                </h4>
                                <p class="text-muted mb-0 modulo-subtitle fs-13">
                                    Gerencie e controle a visibilidade das categorias de produtos exibidas na sua loja virtual.
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('produtos-ecommerce.index') }}" class="btn btn-outline-light btn-sm px-3 text-white">
                                    <i class="ri-shopping-bag-3-line align-middle me-1"></i> Produtos do E-commerce
                                </a>
                                @can('categoria_produtos_create')
                                    <a href="{{ route('categoria-produtos.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                                        <i class="ri-add-circle-line align-middle me-1"></i> Nova Categoria
                                    </a>
                                @endcan
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['total'] ?? $data->total() }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Categorias cadastradas</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-folder-3-line"></i>
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Ativas na Loja</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['ativas'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Visíveis no e-commerce</p>
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Ocultas na Loja</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['inativas'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Desativadas no e-commerce</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-eye-off-line"></i>
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Produtos na Loja</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['total_produtos'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Produtos ativos no e-commerce</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-shopping-bag-3-line"></i>
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
                                    <i class="ri-search-line"></i> Filtrar Categorias
                                </h5>
                            </div>

                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-3">
                                <div class="col-md-5 col-12">
                                    <label class="form-label"><i class="ri-folder-line"></i> Nome da Categoria</label>
                                    {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da categoria...'])!!}
                                </div>
                                <div class="col-md-4 col-12">
                                    <label class="form-label"><i class="ri-store-line"></i> Status no E-commerce</label>
                                    {!!Form::select('ecommerce', '', [
                                        '' => 'Todas as Categorias',
                                        '1' => 'Ativas no E-commerce',
                                        '0' => 'Ocultas no E-commerce'
                                    ])->attrs(['class' => 'form-select'])!!}
                                </div>
                                <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                            <i class="ri-search-line"></i> Buscar
                                        </button>
                                        <a class="btn btn-limpar px-3" href="{{ route('produtos-ecommerce.categorias') }}" title="Limpar Filtros">
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
                                            <th style="width: 70px;">#</th>
                                            <th>Nome da Categoria</th>
                                            <th class="text-center">Produtos Vinculados</th>
                                            <th>Status no E-commerce</th>
                                            <th class="text-center" style="width: 180px;">Visibilidade na Loja</th>
                                            <th class="text-end" style="width: 100px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td class="fw-bold text-muted">#{{ $item->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-xs flex-shrink-0">
                                                            <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-14">
                                                                <i class="ri-folder-3-fill"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span class="fw-semibold text-dark d-block fs-13">{{ $item->nome }}</span>
                                                            @if($item->categoria)
                                                                <span class="text-muted fs-11">
                                                                    <i class="ri-corner-down-right-line"></i> Subcategoria de: <strong>{{ $item->categoria->nome }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">
                                                        <i class="ri-box-3-line text-muted me-1"></i> {{ $item->produtos_count ?? 0 }} produto(s)
                                                    </span>
                                                </td>

                                                <td>
                                                    @if($item->ecommerce)
                                                        <span class="modulo-badge modulo-badge-success status-badge-{{ $item->id }}">
                                                            <i class="ri-checkbox-circle-fill"></i> Ativo no E-commerce
                                                        </span>
                                                    @else
                                                        <span class="modulo-badge modulo-badge-secondary status-badge-{{ $item->id }}">
                                                            <i class="ri-eye-off-line"></i> Oculto na Loja
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <div class="switch-container-premium">
                                                        <span class="fs-11 text-muted fw-bold switch-text-{{ $item->id }}">
                                                            {{ $item->ecommerce ? 'Visível' : 'Oculto' }}
                                                        </span>
                                                        <label class="switch-premium">
                                                            <input type="checkbox" value="{{ $item->id }}" class="switch-check" @if($item->ecommerce) checked @endif>
                                                            <span class="slider"></span>
                                                        </label>
                                                    </div>
                                                </td>

                                                <td class="text-end">
                                                    <div class="modulo-action-group">
                                                        @can('categoria_produtos_edit')
                                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('categoria-produtos.edit', [$item->id]) }}" title="Editar Categoria">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <div class="modulo-empty">
                                                        <i class="ri-folder-warning-line"></i>
                                                        <p>Nenhuma categoria encontrada para os critérios selecionados.</p>
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
                                <span class="modulo-total-label">Total de Categorias: </span>
                                <span class="modulo-total-value">{{ $data->total() }}</span>
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

@section('js')
    <script type="text/javascript">
        $(document).on("change", ".switch-check", function () {
            let id = $(this).val();
            let isChecked = $(this).is(':checked');
            let $statusBadge = $('.status-badge-' + id);
            let $switchText = $('.switch-text-' + id);

            $.get(path_url + "api/produtos-ecommerce/switch-categoria", { id: id })
                .done(function (res) {
                    if (isChecked) {
                        $statusBadge.removeClass('modulo-badge-secondary')
                            .addClass('modulo-badge-success')
                            .html('<i class="ri-checkbox-circle-fill"></i> Ativo no E-commerce');
                        $switchText.text('Visível');
                        toastr.success("Categoria ativada no E-commerce com sucesso!");
                    } else {
                        $statusBadge.removeClass('modulo-badge-success')
                            .addClass('modulo-badge-secondary')
                            .html('<i class="ri-eye-off-line"></i> Oculto na Loja');
                        $switchText.text('Oculto');
                        toastr.info("Categoria ocultada do E-commerce.");
                    }
                })
                .fail(function (err) {
                    toastr.error("Erro ao alterar o status da categoria!");
                });
        });
    </script>
@endsection