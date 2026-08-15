@extends('layouts.app', ['title' => 'Produtos no E-commerce'])

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

        /* ─── Badges de Status ─── */
        .modulo-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
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

        .modulo-badge-info {
            background: #e0f2fe;
            color: #0284c7;
        }

        /* ─── Imagem do Produto ─── */
        .img-thumbnail-custom {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 2px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .img-thumbnail-custom:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
            position: relative;
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
                                    <i class="ri-shopping-bag-3-fill"></i>
                                    Produtos no E-commerce
                                </h4>
                                <p class="text-muted mb-0 modulo-subtitle fs-13">
                                    Gerencie o catálogo, fotos, preços diferenciados e estoque dos produtos da sua loja virtual.
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('produtos-ecommerce.categorias') }}" class="btn btn-outline-light btn-sm px-3 text-white">
                                    <i class="ri-store-2-line align-middle me-1"></i> Categorias no E-commerce
                                </a>
                                @can('produtos_create')
                                    <a href="{{ route('produtos.create', ['ecommerce=1']) }}" class="btn btn-light btn-sm px-3 text-dark">
                                        <i class="ri-add-circle-line align-middle me-1"></i> Novo Produto
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total no E-commerce</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['total'] ?? $data->total() }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Produtos no catálogo</p>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                    <i class="ri-shopping-bag-line"></i>
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Ativos na Loja</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['ativos'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Visíveis aos clientes</p>
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Ocultos na Loja</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['ocultos'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Desativados temporariamente</p>
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
                                                <h4 class="text-uppercase fs-12 mt-0 text-white-50">Categorias Ativas</h4>
                                                <h3 class="my-2 text-white fs-18">{{ $stats['categorias_count'] ?? 0 }}</h3>
                                                <p class="mb-0 text-white-50 fs-11">Categorias no e-commerce</p>
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
                        </div>

                        <!-- ═══ Filtros de Busca Premium ═══ -->
                        <div class="modulo-glass-filter-premium">
                            <div class="filtro-premium-header">
                                <h5 class="filtro-premium-title">
                                    <i class="ri-search-line"></i> Filtrar Produtos do E-commerce
                                </h5>
                            </div>

                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-3">
                                <div class="col-md-4 col-12">
                                    <label class="form-label"><i class="ri-box-3-line"></i> Nome do Produto</label>
                                    {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do produto...'])!!}
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-folder-line"></i> Categoria</label>
                                    {!!Form::select('categoria_id', '', ['' => 'Todas as Categorias'] + ($categorias ?? []))
                                    ->attrs(['class' => 'select2 form-select'])!!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label"><i class="ri-checkbox-circle-line"></i> Status</label>
                                    {!!Form::select('status', '', [
                                        '' => 'Todos os Status',
                                        '1' => 'Ativos na Loja',
                                        '0' => 'Ocultos na Loja'
                                    ])->attrs(['class' => 'form-select'])!!}
                                </div>
                                <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                            <i class="ri-search-line"></i> Buscar
                                        </button>
                                        <a class="btn btn-limpar px-3" href="{{ route('produtos-ecommerce.index') }}" title="Limpar Filtros">
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
                                            <th style="width: 60px;">Foto</th>
                                            <th>Nome do Produto</th>
                                            <th>Un.</th>
                                            <th>Categoria</th>
                                            <th class="text-center">Gerencia Estoque</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end">Valor no E-commerce</th>
                                            <th class="text-end" style="width: 170px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>
                                                    <img class="img-thumbnail-custom" src="{{ $item->img }}" alt="{{ $item->nome }}" onerror="this.src='/imgs/no-image.png'">
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-dark fs-13 d-block text-truncate" style="max-width: 280px;" title="{{ $item->nome }}">
                                                            {{ $item->nome }}
                                                        </span>
                                                        @if($item->codigo_barras)
                                                            <span class="text-muted fs-11">
                                                                <i class="ri-barcode-line"></i> {{ $item->codigo_barras }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-0.5 fs-11 fw-semibold">
                                                        {{ $item->unidade ?? 'UN' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($item->categoria)
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                                            <i class="ri-folder-line me-0.5"></i> {{ $item->categoria->nome }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted fs-12">--</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->gerenciar_estoque)
                                                        <span class="modulo-badge modulo-badge-info">
                                                            <i class="ri-checkbox-circle-line"></i> Sim
                                                        </span>
                                                    @else
                                                        <span class="modulo-badge modulo-badge-secondary">
                                                            <i class="ri-close-line"></i> Não
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->status)
                                                        <span class="modulo-badge modulo-badge-success">
                                                            <i class="ri-eye-line"></i> Ativo
                                                        </span>
                                                    @else
                                                        <span class="modulo-badge modulo-badge-secondary">
                                                            <i class="ri-eye-off-line"></i> Oculto
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold text-success fs-14">
                                                        R$ {{ __moeda($item->valor_ecommerce) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                        @method('delete')
                                                        @csrf
                                                        <div class="modulo-action-group justify-content-end">
                                                            @can('produtos_edit')
                                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('produtos.edit', [$item->id, 'ecommerce=1']) }}" title="Editar Produto">
                                                                    <i class="ri-edit-line"></i>
                                                                </a>
                                                            @endcan
                                                            <a class="btn btn-dark btn-sm text-white" href="{{ route('produtos.galeria', [$item->id, 'ecommerce=1']) }}" title="Galeria de Fotos">
                                                                <i class="ri-image-2-fill"></i>
                                                            </a>
                                                            <a class="btn btn-info btn-sm text-white" href="{{ route('produtos.duplicar', [$item->id, 'ecommerce=1']) }}" title="Duplicar Produto">
                                                                <i class="ri-file-copy-line"></i>
                                                            </a>
                                                            @can('produtos_delete')
                                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Produto">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">
                                                    <div class="modulo-empty">
                                                        <i class="ri-shopping-bag-3-line"></i>
                                                        <p>Nenhum produto do e-commerce encontrado para os critérios selecionados.</p>
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
                                <span class="modulo-total-label">Total de Produtos: </span>
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
