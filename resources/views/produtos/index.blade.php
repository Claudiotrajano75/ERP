@extends('layouts.app', ['title' => 'Produtos'])

@section('css')
<style type="text/css">
    .div-overflow { width: 180px; overflow-x: auto; white-space: nowrap; }
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
    .modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
    .modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
    .modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .modulo-glass-filter .btn:hover { transform: translateY(-1px); }

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

    /* ─── Action Buttons Elegantes (estilo pré-venda) ─── */
    .modulo-action-group {
        display: inline-flex;
        gap: 4px;
        flex-wrap: nowrap;
        align-items: center;
    }
    .modulo-action-group .btn {
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 13px;
        transition: all 0.15s ease;
        line-height: 1.4;
    }
    .modulo-action-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .modulo-action-group .btn-warning {
        background: #ffb74d;
        border-color: #ffa726;
        color: #fff !important;
    }
    .modulo-action-group .btn-warning:hover {
        background: #ffa726;
        border-color: #ff9800;
    }
    .modulo-action-group .btn-danger {
        background: #ef5350;
        border-color: #e53935;
    }
    .modulo-action-group .btn-danger:hover {
        background: #e53935;
    }
    .modulo-action-group .btn-info {
        background: #5c6bc0;
        border-color: #3f51b5;
        color: #fff !important;
    }
    .modulo-action-group .btn-info:hover {
        background: #3f51b5;
    }
    .modulo-action-group .btn-dark {
        background: #455a64;
        border-color: #37474f;
    }
    .modulo-action-group .btn-dark:hover {
        background: #37474f;
    }
    .modulo-action-group .btn-primary {
        background: #5c6bc0;
        border-color: #3f51b5;
    }
    .modulo-action-group .btn-primary:hover {
        background: #3f51b5;
    }
    .modulo-action-group .btn-light {
        background: #f0f2f8;
        border-color: #e8eaf6;
        color: #5a5a7a;
    }
    .modulo-action-group .btn-light:hover {
        background: #e8eaf6;
        color: #302b63;
    }

    /* ─── Footer Premium ─── */
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

    @media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-box-3-line"></i>
                            Produtos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie o cadastro de produtos, controle de estoque, tributação e integração com canais de venda.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @can('produtos_create')
                        <a href="{{ route('produtos.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Produto
                        </a>
                        @endcan
                        <a href="{{ route('produtos.import') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-file-upload-line align-middle me-1"></i> Upload
                        </a>
                        @can('produtos_edit')
                        <a href="{{ route('produtos.reajuste') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-file-edit-fill align-middle me-1"></i> Reajuste
                        </a>
                        @endcan
                        <a href="{{ route('migracao.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-database-2-fill align-middle me-1"></i> Migração
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS: Estatísticas de Produtos ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Produtos</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cadastrados no sistema</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-box-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-danger mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Sem Estoque</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['sem_estoque'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Quantidade zerada/negativa</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-error-warning-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Categorias Ativas</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['categorias_count'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Grupos cadastrados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-folders-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Estoque Estimado</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($stats['valor_estoque']) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Valor de venda em estoque</p>
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
                            <i class="ri-search-line"></i> Filtrar Catálogo de Produtos
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <!-- Linha 1: Identificação do Produto -->
                        <div class="col-md-5 col-12">
                            <label class="form-label"><i class="ri-box-3-line"></i> Nome do Produto</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do produto...'])!!}
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="form-label"><i class="ri-barcode-line"></i> Cód. Barras</label>
                            {!!Form::tel('codigo_barras', '')->attrs(['class' => 'form-control', 'placeholder' => 'Código de barras...'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-apps-2-line"></i> Tipo de Produto</label>
                            {!!Form::select('tipo', '', ['' => 'Todos', 'composto' => 'Composto', 'variavel' => 'Variável', 'combo' => 'Combo'])->attrs(['class' => 'form-select'])!!}
                        </div>

                        <!-- Linha 2: Categoria, Datas, Local e Ações -->
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-folders-line"></i> Categoria</label>
                            {!!Form::select('categoria_id', '', ['' => 'Todas as categorias'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Dt. Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Dt. Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                            {!!Form::select('local_id', '', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif

                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('produtos.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>


                <!-- ═══ Tabela Premium ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @can('produtos_delete')
                                    <th style="width: 40px;"><div class="form-check mb-0"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                                    @endcan
                                    <th>Ações</th>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Valor Venda</th>
                                    <th>Valor Compra</th>
                                    @if(__countLocalAtivo() > 1)<th>Disponibilidade</th>@endif
                                    <th>Categoria</th>
                                    <th>Cód. Barras</th>
                                    <th>NCM</th>
                                    <th>Un.</th>
                                    <th>Cadastro</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))<th>Cardápio</th>@endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))<th>Delivery</th>@endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))<th>Ecommerce</th>@endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))<th>Reserva</th>@endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('produtos_delete')
                                    <td><div class="form-check mb-0"><input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}"></div></td>
                                    @endcan
                                    <td>
                                        <form action="{{ route('produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete') @csrf
                                            <div class="modulo-action-group">
                                                @can('produtos_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('produtos.edit', [$item->id]) }}" title="Editar"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('produtos_delete')
                                                <button type="button" class="btn btn-danger btn-delete btn-sm" title="Excluir"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                                @if($item->composto)
                                                <a class="btn btn-info btn-sm text-white" href="{{ route('produto-composto.show', [$item->id]) }}" title="Composição"><i class="ri-mind-map"></i></a>
                                                @endif
                                                @if($item->alerta_validade != '')
                                                <button type="button" class="btn btn-light btn-sm" onclick="infoVencimento('{{$item->id}}')" data-bs-toggle="modal" data-bs-target="#info_vencimento" title="Lote/Validade"><i class="ri-calendar-event-line"></i></button>
                                                @endif
                                                <a title="Movimentações" href="{{ route('produtos.show', [$item->id]) }}" class="btn btn-dark btn-sm text-white"><i class="ri-draft-line"></i></a>
                                                <a class="btn btn-primary btn-sm text-white" href="{{ route('produtos.duplicar', [$item->id]) }}" title="Duplicar"><i class="ri-file-copy-line"></i></a>
                                                <a class="btn btn-light btn-sm" href="{{ route('produtos.etiqueta', [$item->id]) }}" title="Etiqueta"><i class="ri-barcode-box-line"></i></a>
                                                <a class="btn btn-success btn-sm text-white" href="{{ route('produtos.download-zip', [$item->id]) }}" title="Baixar imagens (ZIP)"><i class="ri-download-2-line"></i></a>
                                            </div>
                                        </form>
                                    </td>
                                    <td><img class="rounded border" src="{{ $item->img }}" style="width: 45px; height: 45px; object-fit: cover;"></td>
                                    <td class="fw-semibold text-dark">{{ $item->nome }}</td>
                                    @if($item->variacao_modelo_id)
                                    <td><div class="div-overflow text-muted fs-12">{{ $item->valoresVariacao() }}</div></td>
                                    @else
                                    <td class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor_unitario) }}</td>
                                    @endif
                                    <td class="text-muted">R$ {{ __moeda($item->valor_compra) }}</td>
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        <span class="fs-12 text-muted">
                                            @foreach($item->locais as $l)
                                                @if($l->localizacao)
                                                    {{ $l->localizacao->descricao }}@if(!$loop->last) | @endif
                                                @endif
                                            @endforeach
                                        </span>
                                    </td>
                                    @endif
                                    <td><span class="badge bg-light text-dark border">{{ $item->categoria ? $item->categoria->nome : '--' }}</span></td>
                                    <td class="text-muted fs-12">{{ $item->codigo_barras ?? '--' }}</td>
                                    <td class="fs-12">{{ $item->ncm }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $item->unidade }}</span></td>
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at, 0) }}</td>
                                    <td>
                                        @if($item->gerenciar_estoque)
                                            @can('estoque_view')
                                                @if(__countLocalAtivo() == 1)
                                                <strong class="text-success">{{ $item->estoqueAtual() }}</strong>
                                                @else
                                                <div class="fs-11 text-muted" style="min-width: 120px;">
                                                    @foreach($item->estoqueLocais as $e)
                                                        @if($e->local)
                                                            {{ $e->local->descricao }}: <strong class="text-success">{{ number_format($e->quantidade, ($item->unidade == 'UN' || $item->unidade == 'UNID' ? 0 : 3)) }}</strong>@if(!$loop->last) | @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                                @endif
                                            @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Sim</span>
                                            @endcan
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Ativo</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inativo</span>
                                        @endif
                                    </td>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
                                    <td>
                                        @if($item->cardapio)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <td>
                                        @if($item->delivery)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
                                    <td>
                                        @if($item->ecommerce)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                                    <td>
                                        @if($item->reserva)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr><td colspan="22" class="text-center text-muted py-4">Nenhum produto cadastrado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Ações em Lote + Footer ═══ -->
                <div class="modulo-footer">
                    <div>
                        <form action="{{ route('produtos.download-selecionados') }}" method="post" id="form-download-select" class="m-0 d-inline">
                            @csrf
                            <div class="download-ids-wrap"></div>
                            <button type="button" class="btn btn-outline-success btn-sm btn-download-selecionados" disabled><i class="ri-download-2-line align-middle me-1"></i> Baixar Imagens Selecionados</button>
                        </form>
                        @can('produtos_delete')
                        <form action="{{ route('produtos.destroy-select') }}" method="post" id="form-delete-select" class="m-0 d-inline">
                            @method('delete') @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled><i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionados</button>
                        </form>
                        @endcan
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('modals._info_vencimento', ['not_submit' => true])

@endsection

@section('js')
<script src="/js/delete_selecionados.js"></script>
<script>
    function infoVencimento(id) {
        $.get(path_url + 'api/produtos/info-vencimento/' + id)
        .done((res) => { $('.table-infoValidade tbody').html(res); })
        .fail((e) => { console.log(e); });
    }

    // ─── Download em Lote (Imagens) ───
    $("#select-all-checkbox").on("click", function (e) {
        validaButtonDownload();
    });

    $(".check-delete").on("click", function (e) {
        validaButtonDownload();
    });

    function validaButtonDownload(){
        $checked = $('.check-delete:checked');
        if($checked.length > 0){
            $('.btn-download-selecionados').removeAttr('disabled');
            $('#form-download-select .download-ids-wrap').html('');
            $checked.each(function(){
                let v = $(this).val();
                $('#form-download-select .download-ids-wrap').append(
                    "<input type='hidden' name='ids[]' value='"+v+"'>"
                );
            });
        }else{
            $('.btn-download-selecionados').attr('disabled', 1);
            $('#form-download-select .download-ids-wrap').html('');
        }
    }

    $(".btn-download-selecionados").on("click", function (e) {
        e.preventDefault();
        if($('.check-delete:checked').length === 0){
            swal("", "Selecione pelo menos um produto!", "warning");
            return;
        }
        document.getElementById('form-download-select').submit();
    });

    $(function(){ validaButtonDownload(); });
</script>
@endsection
