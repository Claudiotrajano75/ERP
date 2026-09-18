@extends('layouts.app', ['title' => 'Produtos no E-commerce'])

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

/* ─── Avatar do Produto ─── */
.product-avatar {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.product-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ─── Badges de Status ─── */
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
.modulo-badge-secondary { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

/* ─── Botões de Ação Squircle ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end; }
.act-btn { 
    width: 34px; 
    height: 34px; 
    border-radius: 10px; 
    border: 1px solid transparent; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 15px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: all .2s ease; 
    padding: 0;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover { background: #e0e7ff; color: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,.2); }
.act-add { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.act-add:hover { background: #dcfce7; color: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.2); }
.act-del { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-bag-3-line"></i>
                                Produtos no E-commerce
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualize e controle todos os produtos sincronizados com a sua loja virtual.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('produtos-ecommerce.categorias') }}" class="dash-btn dash-btn-light">
                                <i class="ri-store-2-line"></i> Categorias do E-commerce
                            </a>
                            @can('produtos_create')
                            <a href="{{ route('produtos.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Novo Produto
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
                                    <div class="stat-label">Produtos na Loja</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <i class="ri-shopping-bag-3-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-green">
                                <div>
                                    <div class="stat-label">Produtos Ativos</div>
                                    <div class="stat-value mt-1">{{ $stats['ativos'] }}</div>
                                </div>
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-amber">
                                <div>
                                    <div class="stat-label">Produtos Ocultos</div>
                                    <div class="stat-value mt-1">{{ $stats['ocultos'] }}</div>
                                </div>
                                <i class="ri-eye-off-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-purple">
                                <div>
                                    <div class="stat-label">Categorias Ativas</div>
                                    <div class="stat-value mt-1">{{ $stats['categorias_count'] }}</div>
                                </div>
                                <i class="ri-folder-3-line stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
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
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-folder-line"></i> Categoria</label>
                                {!!Form::select('categoria_id', '', ['' => 'Todas as Categorias'] + $categorias)->attrs(['class' => 'select2 form-select'])!!}
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-store-line"></i> Status do Produto</label>
                                {!!Form::select('status', '', [
                                    '' => 'Todos os Status',
                                    '1' => 'Ativos no E-commerce',
                                    '0' => 'Ocultos no E-commerce'
                                ])->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
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
                    <div class="tb-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">#</th>
                                        <th>Produto</th>
                                        <th>Categoria</th>
                                        <th>Valor de Venda</th>
                                        <th class="text-center" style="width: 140px;">Status</th>
                                        <th class="text-end" style="width: 100px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            <td class="fw-bold text-muted">#{{ $item->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="product-avatar">
                                                        <img src="{{ $item->img }}" alt="{{ $item->nome }}">
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark d-block fs-13">{{ $item->nome }}</span>
                                                        <span class="text-muted fs-11">
                                                            Cód: <strong>{{ $item->codigo_barras ?: $item->id }}</strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge bg-light text-dark border px-2 py-1 fs-12">
                                                    <i class="ri-folder-line text-muted me-1"></i> {{ $item->categoria ? $item->categoria->nome : 'Sem Categoria' }}
                                                </span>
                                            </td>

                                            <td>
                                                <strong class="text-success" style="font-size: 13.5px;">R$ {{ __moeda($item->valor_unitario) }}</strong>
                                            </td>

                                            <td class="text-center">
                                                @if($item->status)
                                                    <span class="modulo-badge modulo-badge-success">
                                                        <i class="ri-checkbox-circle-fill"></i> Ativo
                                                    </span>
                                                @else
                                                    <span class="modulo-badge modulo-badge-secondary">
                                                        <i class="ri-eye-off-line"></i> Oculto
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                <div class="act-group">
                                                    @can('produtos_edit')
                                                    <a class="act-btn act-edit" href="{{ route('produtos.edit', [$item->id]) }}" title="Editar Produto">
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
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Nenhum produto encontrado para o e-commerce.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ FOOTER & PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                        <div>
                            <h5 class="m-0 text-dark fs-14">Total de Produtos: <strong class="text-primary fs-16">{{ $data->total() }}</strong></h5>
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
