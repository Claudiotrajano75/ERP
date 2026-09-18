@extends('layouts.app', ['title' => 'Produtos'])

@section('css')
<style type="text/css">
    .div-overflow { width: 180px; overflow-x: auto; white-space: nowrap; }
    .modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; margin-bottom: 6px; }
    .filter-wrap label i { color: #a8a8c0; }
    .filter-wrap .form-control, .filter-wrap .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 14px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }
    .tb-wrap .badge { border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; border: 0; }
    .tb-wrap .badge.bg-success-subtle { background: #dcfce7 !important; color: #15803d !important; }
    .tb-wrap .badge.bg-danger-subtle  { background: #fee2e2 !important; color: #b91c1c !important; }
    .tb-wrap .badge.bg-secondary-subtle { background: #f1f5f9 !important; color: #64748b !important; }
    .tb-wrap .badge.bg-light { background: #eef0ff !important; color: #4f46e5 !important; }

    /* ─── Dropdown de Ações (mantido) ─── */
    .btn-action-trigger { width: 34px; height: 34px; border-radius: 9px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; transition: all .2s ease; padding: 0; }
    .btn-action-trigger:hover, .dropdown-action-menu.show .btn-action-trigger { background: #f1f5f9; color: #4f46e5; border-color: #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,.06); }
    .action-dropdown-card { min-width: 250px; border-radius: 14px !important; border: 1px solid rgba(0,0,0,.06) !important; padding: 8px !important; background: #fff; box-shadow: 0 12px 32px rgba(15,23,42,.12) !important; z-index: 1060; }
    .action-menu-item { display: flex !important; align-items: center; gap: 12px; padding: 8px 10px !important; border-radius: 10px; text-decoration: none; transition: all .18s ease; background: transparent; cursor: pointer; }
    .action-menu-item:hover { background-color: #f8fafc !important; transform: translateX(2px); }
    .action-item-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; transition: all .18s ease; }
    .action-item-icon.icon-primary { background: #eef0ff; color: #4f46e5; }
    .action-item-icon.icon-info { background: #e0f2fe; color: #0284c7; }
    .action-item-icon.icon-purple { background: #f3e8ff; color: #7c3aed; }
    .action-item-icon.icon-teal { background: #ccfbf1; color: #0d9488; }
    .action-item-icon.icon-success { background: #dcfce7; color: #16a34a; }
    .action-item-icon.icon-cyan { background: #cffafe; color: #0891b2; }
    .action-item-icon.icon-warning { background: #fef3c7; color: #d97706; }
    .action-item-icon.icon-danger { background: #fee2e2; color: #dc2626; }
    .action-menu-item:hover .action-item-icon { transform: scale(1.08); }
    .action-item-content { display: flex; flex-direction: column; text-align: left; line-height: 1.2; }
    .action-item-title { font-size: 12.5px; font-weight: 700; color: #1e293b; }
    .action-item-desc { font-size: 11px; color: #64748b; margin-top: 2px; font-weight: 400; }
    .action-menu-item.text-danger:hover { background-color: #fef2f2 !important; }
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
                        <a href="{{ route('produtos.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Novo Produto
                        </a>
                        @endcan
                        @can('produtos_edit')
                        <button type="button" class="dash-btn dash-btn-light" data-bs-toggle="modal" data-bs-target="#modal_busca_imagens_lote" id="btn-open-modal-lote">
                            <i class="ri-image-search-line align-middle me-1"></i> Buscar Imagens
                        </button>
                        <a href="{{ route('produtos.reajuste') }}" class="dash-btn dash-btn-light">
                            <i class="ri-file-edit-fill align-middle me-1"></i> Reajuste
                        </a>
                        @endcan
                        <a href="{{ route('produtos.import') }}" class="dash-btn dash-btn-light">
                            <i class="ri-file-upload-line align-middle me-1"></i> Upload
                        </a>
                        <a href="{{ route('migracao.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-database-2-fill align-middle me-1"></i> Migração
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS: Estatísticas de Produtos ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Total de Produtos</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">Cadastrados no sistema</div></div>
                                <div class="st-icon"><i class="ri-box-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Sem Estoque</div><div class="st-value">{{ $stats['sem_estoque'] }}</div><div class="st-sub">Quantidade zerada/negativa</div></div>
                                <div class="st-icon"><i class="ri-error-warning-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Categorias Ativas</div><div class="st-value">{{ $stats['categorias_count'] }}</div><div class="st-sub">Grupos cadastrados</div></div>
                                <div class="st-icon"><i class="ri-folders-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Estoque Estimado</div><div class="st-value">R$ {{ __moeda($stats['valor_estoque']) }}</div><div class="st-sub">Valor de venda em estoque</div></div>
                                <div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="filter-wrap">
                    <div class="filtro-premium-header">
                        <h5 class="filter-title mb-0">
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
                                <button class="btn btn-primary flex-grow-1" style="border-radius:10px;" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('produtos.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>


                <!-- ═══ Tabela Premium ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive" style="min-height: 280px;">
                        <table class="table">
                            <thead>
                                <tr>
                                    @can('produtos_delete')
                                    <th style="width: 40px;"><div class="form-check mb-0"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                                    @endcan
                                    <th class="text-end" style="width: 80px;">Ações</th>
                                    <th>Nome</th>
                                    <th>Imagem</th>
                                    <th>Valor Venda</th>
                                    <th>Valor Compra</th>
                                    @if(__countLocalAtivo() > 1)<th>Disponibilidade</th>@endif
                                    <th>Categoria</th>
                                    <th>Cód. Barras</th>
                                    <th>NCM</th>
                                    <th>Un.</th>
                                    <th>Cadastro</th>
                                    <th class="text-center">Estoque</th>
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
                                    
                                    {{-- Última Coluna: Ações com Submenus em Dropdown --}}
                                    <td class="text-end">
                                        <form action="{{ route('produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                            @method('delete') @csrf
                                            <div class="dropdown dropdown-action-menu d-inline-block">
                                                <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções do Produto">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-card shadow-lg">
                                                    @can('produtos_edit')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('produtos.edit', [$item->id]) }}">
                                                            <div class="action-item-icon icon-primary">
                                                                <i class="ri-pencil-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Editar Produto</span>
                                                                <span class="action-item-desc">Alterar dados, preços e estoque</span>
                              								</div>
                                                        </a>
                                                    </li>
                                                    @endcan

                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('produtos.show', [$item->id]) }}">
                                                            <div class="action-item-icon icon-info">
                                                                <i class="ri-draft-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Movimentações</span>
                                                                <span class="action-item-desc">Ver histórico de entradas/saídas</span>
                                                            </div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('produtos.duplicar', [$item->id]) }}">
                                                            <div class="action-item-icon icon-purple">
                                                                <i class="ri-file-copy-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Duplicar</span>
                                                                <span class="action-item-desc">Criar cópia deste item</span>
                                                            </div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('produtos.etiqueta', [$item->id]) }}">
                                                            <div class="action-item-icon icon-teal">
                                                                <i class="ri-barcode-box-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Imprimir Etiqueta</span>
                                                                <span class="action-item-desc">Gerar código de barras</span>
                                                            </div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('produtos.download-zip', [$item->id]) }}">
                                                            <div class="action-item-icon icon-success">
                                                                <i class="ri-download-2-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Baixar Imagens</span>
                                                                <span class="action-item-desc">Download das fotos em ZIP</span>
                                                            </div>
                                                        </a>
                                                    </li>

                                                    @if($item->composto)
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('produto-composto.show', [$item->id]) }}">
                                                            <div class="action-item-icon icon-cyan">
                                                                <i class="ri-mind-map"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Composição</span>
                                                                <span class="action-item-desc">Fórmula e insumos</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endif

                                                    @if($item->alerta_validade != '')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="infoVencimento('{{$item->id}}')" data-bs-toggle="modal" data-bs-target="#info_vencimento">
                                                            <div class="action-item-icon icon-warning">
                                                                <i class="ri-calendar-event-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Lote & Validade</span>
                                                                <span class="action-item-desc">Consultar vencimentos</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endif

                                                    @can('produtos_delete')
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                            <div class="action-item-icon icon-danger">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title text-danger">Excluir Produto</span>
                                                                <span class="action-item-desc text-danger-emphasis">Remover do catálogo</span>
                                                            </div>
                                                        </button>
                                                    </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
{{-- 1ª Coluna de Dados: Nome do Produto --}}
                                    <td>
                                        @can('produtos_edit')
                                        <a href="{{ route('produtos.edit', [$item->id]) }}" class="fw-bold text-dark text-decoration-none" title="Editar Produto">
                                            {{ $item->nome }}
                                        </a>
                                        @else
                                        <span class="fw-bold text-dark">{{ $item->nome }}</span>
                                        @endcan
                                    </td>

                                    {{-- 2ª Coluna de Dados: Imagem --}}
                                    <td>
                                        <img class="rounded border shadow-sm" src="{{ $item->img }}" alt="{{ $item->nome }}" style="width: 44px; height: 44px; object-fit: cover;">
                                    </td>

                                    {{-- Valor Venda --}}
                                    @if($item->variacao_modelo_id)
                                    <td><div class="div-overflow text-muted fs-12">{{ $item->valoresVariacao() }}</div></td>
                                    @else
                                    <td class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor_unitario) }}</td>
                                    @endif

                                    {{-- Valor Compra --}}
                                    <td class="text-muted">R$ {{ __moeda($item->valor_compra) }}</td>

                                    {{-- Disponibilidade (Locais) --}}
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

                                    {{-- Categoria --}}
                                    <td><span class="badge bg-light text-dark border">{{ $item->categoria ? $item->categoria->nome : '--' }}</span></td>

                                    {{-- Cód. Barras --}}
                                    <td class="text-muted fs-12">{{ $item->codigo_barras ?? '--' }}</td>

                                    {{-- NCM --}}
                                    <td class="fs-12">{{ $item->ncm }}</td>

                                    {{-- Unidade --}}
                                    <td><span class="badge bg-light text-dark border">{{ $item->unidade }}</span></td>

                                    {{-- Data de Cadastro --}}
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at, 0) }}</td>

                                    {{-- Estoque --}}
                                    <td class="text-center">
                                        @if($item->gerenciar_estoque)
                                            @can('estoque_view')
                                                @if(__countLocalAtivo() == 1)
                                                    @php
                                                        $qtdEstoque = $item->estoque ? (float)$item->estoque->quantidade : 0;
                                                    @endphp
                                                    @if($qtdEstoque <= 0)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-12 fw-bold" title="Estoque zerado ou negativo">
                                                            <i class="ri-alert-line me-1"></i>{{ $item->estoqueAtual() }}
                                                        </span>
                                                    @elseif($item->estoque_minimo > 0 && $qtdEstoque <= $item->estoque_minimo)
                                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 fs-12 fw-bold" title="Estoque baixo (Mínimo: {{ $item->estoque_minimo }})">
                                                            <i class="ri-error-warning-line me-1"></i>{{ $item->estoqueAtual() }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12 fw-bold" title="Estoque disponível">
                                                            <i class="ri-box-3-line me-1"></i>{{ $item->estoqueAtual() }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <div class="fs-11 text-muted d-inline-flex flex-wrap gap-1 justify-content-center" style="min-width: 120px;">
                                                        @foreach($item->estoqueLocais as $e)
                                                            @if($e->local)
                                                                @php
                                                                    $qtdLocal = (float)$e->quantidade;
                                                                    $qtdFormatada = number_format($e->quantidade, ($item->unidade == 'UN' || $item->unidade == 'UNID' ? 0 : 3));
                                                                @endphp
                                                                <span class="badge {{ $qtdLocal <= 0 ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-light text-dark border' }} py-1 px-2">
                                                                    {{ $e->local->descricao }}: <strong class="{{ $qtdLocal <= 0 ? 'text-danger' : 'text-success' }}">{{ $qtdFormatada }}</strong>
                                                                </span>
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

                                    {{-- Status --}}
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Ativo</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inativo</span>
                                        @endif
                                    </td>

                                    {{-- Módulos Opcionais --}}
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
                    <div class="d-flex gap-2 flex-wrap">
                        <form action="{{ route('produtos.download-selecionados') }}" method="post" id="form-download-select" class="m-0 d-inline">
                            @csrf
                            <div class="download-ids-wrap"></div>
                            <button type="button" class="dash-btn dash-btn-light btn-download-selecionados" style="color:#16a34a;" disabled><i class="ri-download-2-line"></i> Baixar Imagens Selecionados</button>
                        </form>
                        @can('produtos_delete')
                        <form action="{{ route('produtos.destroy-select') }}" method="post" id="form-delete-select" class="m-0 d-inline">
                            @method('delete') @csrf
                            <button type="button" class="dash-btn dash-btn-light btn-delete-all" style="color:#dc2626;" disabled><i class="ri-delete-bin-2-line"></i> Remover Selecionados</button>
                        </form>
                        @endcan
                    </div>
                    <div class="fs-12" style="color:#94a3b8;">Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> produtos</div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

                            </div>
        </div>
    </div>
</div>

@include('modals._info_vencimento', ['not_submit' => true])
@include('modals._busca_imagens_lote', ['not_submit' => true])

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

    // ═══════════════════════════════════════════════════════════════
    // ─── BUSCA INTELIGENTE DE IMAGENS EM LOTE (AUTOMÁTICA) ───
    // ═══════════════════════════════════════════════════════════════
    let loteFila = [];
    let loteIndex = 0;
    let loteTotal = 0;
    let loteSucesso = 0;
    let loteFalha = 0;
    let lotePausado = false;
    let loteCancelado = false;
    let loteSubstituir = false;

    $('#btn-iniciar-lote').on('click', function () {
        let modo = $('input[name="escopo_busca_lote"]:checked').val();
        loteSubstituir = $('#chk_substituir_existentes').is(':checked');

        let btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Carregando lista...');

        $.get(path_url + 'produtos-busca-imagem-lote-lista', { modo: modo })
            .done(function (res) {
                btn.prop('disabled', false).html('<i class="ri-play-fill me-1"></i> Iniciar Busca Automática');

                if (!res.success || !res.produtos || res.produtos.length === 0) {
                    swal('Aviso', 'Nenhum produto encontrado para o escopo selecionado.', 'info');
                    return;
                }

                loteFila = res.produtos;
                loteTotal = loteFila.length;
                loteIndex = 0;
                loteSucesso = 0;
                loteFalha = 0;
                lotePausado = false;
                loteCancelado = false;

                // Transição de telas dentro da modal
                $('#lote-step-config').slideUp(200);
                $('#lote-step-progress').slideDown(200);

                $('#kpi-total').text(loteTotal);
                $('#kpi-processados').text(0);
                $('#kpi-sucesso').text(0);
                $('#kpi-falha').text(0);
                $('#bar-progresso-lote').css('width', '0%');
                $('#lbl-percent-lote').text('0%');
                $('#lbl-status-lote').text('Iniciando processamento...');
                $('#badge-live-count').text('0 novas fotos');
                $('#grid-fotos-encontradas').html('');

                $('#btn-iniciar-lote').hide();
                $('#btn-pausar-lote').show();
                $('#btn-cancelar-lote').text('Cancelar Processo');

                processarProximoItemLote();
            })
            .fail(function () {
                btn.prop('disabled', false).html('<i class="ri-play-fill me-1"></i> Iniciar Busca Automática');
                swal('Erro', 'Não foi possível carregar a lista de produtos.', 'error');
            });
    });

    $('#btn-pausar-lote').on('click', function () {
        lotePausado = true;
        $(this).hide();
        $('#btn-retomar-lote').show();
        $('#lbl-status-lote').text('Processo pausado pelo usuário.');
        $('#spinner-lote').hide();
    });

    $('#btn-retomar-lote').on('click', function () {
        lotePausado = false;
        $(this).hide();
        $('#btn-pausar-lote').show();
        $('#lbl-status-lote').text('Retomando busca...');
        $('#spinner-lote').show();
        processarProximoItemLote();
    });

    $('#btn-cancelar-lote').on('click', function () {
        if ($('#lote-step-progress').is(':visible') && loteIndex < loteTotal) {
            loteCancelado = true;
            lotePausado = true;
        }
    });

    function processarProximoItemLote() {
        if (lotePausado || loteCancelado) return;

        if (loteIndex >= loteTotal) {
            // Conclusão do Lote
            $('#spinner-lote').hide();
            $('#lbl-status-lote').text('Busca em lote concluída com sucesso!');
            $('#lbl-percent-lote').text('100%');
            $('#bar-progresso-lote').css('width', '100%').removeClass('progress-bar-animated');
            $('#btn-pausar-lote').hide();
            $('#btn-retomar-lote').hide();
            $('#btn-cancelar-lote').text('Concluir e Atualizar').removeClass('btn-light').addClass('btn-success');

            swal({
                title: 'Concluído!',
                text: 'Processo finalizado! ' + loteSucesso + ' imagens foram encontradas e associadas com sucesso.',
                type: 'success',
                confirmButtonText: 'Atualizar Tela'
            }, function () {
                window.location.reload();
            });
            return;
        }

        let item = loteFila[loteIndex];
        let posAtual = loteIndex + 1;
        $('#lbl-item-atual').text(item.nome + (item.codigo_barras ? ' [' + item.codigo_barras + ']' : ''));
        $('#lbl-contador-pos').text(posAtual + ' / ' + loteTotal);
        $('#lbl-status-lote').text('Buscando imagem para o item ' + posAtual + ' de ' + loteTotal + '...');

        $.ajax({
            url: path_url + 'produtos-busca-imagem-lote-processar',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                produto_id: item.id,
                substituir: loteSubstituir ? 1 : 0
            },
            timeout: 25000
        })
        .done(function (res) {
            if (res.success && res.status === 'encontrado') {
                loteSucesso++;
                $('#kpi-sucesso').text(loteSucesso);
                $('#badge-live-count').text(loteSucesso + ' novas fotos');

                // Adiciona miniatura no grid ao vivo
                let imgCard = `
                    <div class="col-md-3 col-4">
                        <div class="card p-1 border shadow-none text-center bg-light" style="border-radius: 8px;">
                            <img src="${res.imagem}" class="img-fluid rounded" style="height: 65px; object-fit: contain;" alt="${res.nome}">
                            <small class="text-truncate d-block mt-1 fs-11 text-dark fw-semibold" title="${res.nome}">${res.nome}</small>
                        </div>
                    </div>
                `;
                $('#grid-fotos-encontradas').prepend(imgCard);
            } else if (res.success && res.status === 'mantido') {
                // Já possuía foto
            } else {
                loteFalha++;
                $('#kpi-falha').text(loteFalha);
            }
        })
        .fail(function () {
            loteFalha++;
            $('#kpi-falha').text(loteFalha);
        })
        .always(function () {
            loteIndex++;
            $('#kpi-processados').text(loteIndex);

            let percent = Math.round((loteIndex / loteTotal) * 100);
            $('#bar-progresso-lote').css('width', percent + '%');
            $('#lbl-percent-lote').text(percent + '%');

            // Pequeno delay de 250ms entre requisições para estabilidade do servidor
            setTimeout(function () {
                processarProximoItemLote();
            }, 250);
        });
    }

    // Reset da modal ao fechar
    $('#modal_busca_imagens_lote').on('hidden.bs.modal', function () {
        lotePausado = true;
        loteCancelado = true;
        $('#lote-step-config').show();
        $('#lote-step-progress').hide();
        $('#btn-iniciar-lote').show().prop('disabled', false);
        $('#btn-pausar-lote').hide();
        $('#btn-retomar-lote').hide();
        $('#btn-cancelar-lote').text('Fechar').removeClass('btn-success').addClass('btn-light');
    });
</script>
@endsection
