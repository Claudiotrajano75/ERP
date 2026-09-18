@extends('layouts.app', ['title' => 'Categorias de Produto'])

@section('css')
<style>
    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
    .filter-wrap .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    .filter-wrap .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }
    .row-parent td { background: #fbfbff; }
    .row-parent td.cat-name { font-weight: 700; color: #1f2937; }

    /* ─── Ações ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-edit { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Badges ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-no { background: #f1f5f9; color: #64748b; }
    .pill-sub { background: #e0f2fe; color: #0284c7; }

    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-folder-open-line"></i>
                            Categorias de Produto
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Cadastre categorias e subcategorias para organizar seu catálogo e definir canais de exibição.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('categoria-produtos.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('categoria_produtos_create')
                        <a href="{{ route('categoria-produtos.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Nova Categoria</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA ═══ -->
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Categorias</div>
                                    <div class="st-value">{{ $stats['total'] }}</div>
                                    <div class="st-sub">categorias principais</div>
                                </div>
                                <div class="st-icon"><i class="ri-folder-2-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Subcategorias</div>
                                    <div class="st-value">{{ $stats['subcategorias'] }}</div>
                                    <div class="st-sub">vinculadas a uma categoria</div>
                                </div>
                                <div class="st-icon"><i class="ri-split-cells-vertical"></i></div>
                            </div>
                        </div>
                    </div>
                    @if(isset($stats['cardapio']))
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Cardápio</div>
                                    <div class="st-value">{{ $stats['cardapio'] }}</div>
                                    <div class="st-sub">categorias no cardápio</div>
                                </div>
                                <div class="st-icon"><i class="ri-restaurant-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(isset($stats['delivery']))
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Delivery</div>
                                    <div class="st-value">{{ $stats['delivery'] }}</div>
                                    <div class="st-sub">categorias no delivery</div>
                                </div>
                                <div class="st-icon"><i class="ri-shopping-bag-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(isset($stats['ecommerce']))
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">E-commerce</div>
                                    <div class="st-value">{{ $stats['ecommerce'] }}</div>
                                    <div class="st-sub">categorias na loja</div>
                                </div>
                                <div class="st-icon"><i class="ri-shopping-cart-2-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(isset($stats['reserva']))
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Reserva</div>
                                    <div class="st-value">{{ $stats['reserva'] }}</div>
                                    <div class="st-sub">categorias em reservas</div>
                                </div>
                                <div class="st-icon"><i class="ri-hotel-bed-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- ═══ FILTROS ═══ -->
                <div class="filter-wrap">
                    <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Categorias</h5>
                    <div class="mt-3">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8 col-12">
                                <label class="form-label"><i class="ri-folder-line"></i> Pesquisar por Nome</label>
                                {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da categoria...'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-light border px-3" href="{{ route('categoria-produtos.index') }}" title="Limpar Filtros" style="border-radius:10px;">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    @can('categoria_produtos_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Nome</th>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
                                    <th>Cardápio</th>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <th>Delivery</th>
                                    <th>Tipo Pizza</th>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
                                    <th>Ecommerce</th>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                                    <th>Reserva</th>
                                    @endif
                                    <th class="text-end" style="width: 110px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <!-- Categoria Pai -->
                                <tr class="row-parent">
                                    @can('categoria_produtos_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td class="cat-name">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-folder-2-fill" style="color:#9aa5ff;font-size:18px;"></i>
                                            {{ $item->nome }}
                                        </div>
                                    </td>

                                    @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
                                    <td>@include('categoria_produtos._pill', ['v' => $item->cardapio])</td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <td>@include('categoria_produtos._pill', ['v' => $item->delivery])</td>
                                    <td>@include('categoria_produtos._pill', ['v' => $item->tipo_pizza])</td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
                                    <td>@include('categoria_produtos._pill', ['v' => $item->ecommerce])</td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                                    <td>@include('categoria_produtos._pill', ['v' => $item->reserva])</td>
                                    @endif

                                    <td class="text-end">
                                        <form action="{{ route('categoria-produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('categoria_produtos_edit')
                                                <a class="act-btn act-edit" href="{{ route('categoria-produtos.edit', [$item->id]) }}" title="Editar Categoria"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('categoria_produtos_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Categoria"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Subcategorias -->
                                @if($item->subCategorias && sizeof($item->subCategorias) > 0)
                                @foreach($item->subCategorias as $sub)
                                <tr>
                                    @can('categoria_produtos_delete')
                                    <td></td>
                                    @endcan
                                    <td colspan="99">
                                        <span class="pill pill-sub ms-3">
                                            <i class="ri-corner-down-right-line"></i> {{ $sub->nome }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('categoria-produtos.destroy', $sub->id) }}" method="post" id="form-{{$sub->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('categoria_produtos_edit')
                                                <a class="act-btn act-edit" href="{{ route('categoria-produtos.edit', [$sub->id]) }}" title="Editar Subcategoria"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('categoria_produtos_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Subcategoria"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @empty
                                <tr>
                                    <td colspan="99">
                                        <div class="empty-state">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Nenhuma categoria cadastrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        @can('categoria_produtos_delete')
                        <form action="{{ route('categoria-produtos.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="dash-btn dash-btn-light btn-delete-all" style="color:#dc2626;" disabled>
                                <i class="ri-delete-bin-2-line"></i> Remover Selecionadas
                            </button>
                        </form>
                        @endcan
                    </div>
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> categorias
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
