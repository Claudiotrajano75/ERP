@extends('layouts.app', ['title' => 'Naturezas de Operação'])

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

/* ─── Avatar da Natureza ─── */
.natureza-avatar {
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

/* ─── Botões de Ação Squircle (Edit e Delete) ─── */
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
                                <i class="ri-settings-4-line"></i>
                                Naturezas de Operação
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Cadastre e gerencie as regras tributárias e fiscais para emissão de notas.
                            </p>
                        </div>
                        <div>
                            @can('natureza_operacao_create')
                            <a href="{{ route('natureza-operacao.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Nova Natureza
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ KPI CARDS ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-indigo">
                                <div>
                                    <div class="stat-label">Total de Naturezas</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <i class="ri-file-list-3-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-green">
                                <div>
                                    <div class="stat-label">Padrão do Sistema</div>
                                    <div class="stat-value mt-1">{{ $stats['padrao'] }}</div>
                                </div>
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="stat-card stat-amber">
                                <div>
                                    <div class="stat-label">Sobrescreve CFOP</div>
                                    <div class="stat-value mt-1">{{ $stats['sobrescreve'] }}</div>
                                </div>
                                <i class="ri-swap-box-line stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
                    <div class="modulo-glass-filter-premium">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Naturezas de Operação
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label"><i class="ri-file-text-line"></i> Descrição</label>
                                {!!Form::text('descricao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite a descrição da operação...'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-star-line"></i> Natureza Padrão</label>
                                {!!Form::select('padrao', '', ['' => 'Todos', '1' => 'Sim (Padrão)', '0' => 'Não'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-limpar px-3" href="{{ route('natureza-operacao.index') }}" title="Limpar Filtros">
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
                                        <th>Descrição da Operação</th>
                                        <th>CFOPs Padrão</th>
                                        <th>CST / CSOSN</th>
                                        <th class="text-center">Padrão</th>
                                        <th class="text-center">Sobrescreve CFOP</th>
                                        <th class="text-end" style="width: 120px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="natureza-avatar">
                                                    <i class="ri-file-settings-line"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark fs-13 d-block">
                                                        {{ $item->descricao }}
                                                    </span>
                                                    <span class="text-muted fs-11">#{{ $item->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($item->cfop_estadual)
                                                    <span class="badge bg-light text-dark border px-2 py-0.5 fs-11" title="Estadual">
                                                        Est: {{ $item->cfop_estadual }}
                                                    </span>
                                                @endif
                                                @if($item->cfop_outro_estado)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 fs-11" title="Interestadual">
                                                        Inter: {{ $item->cfop_outro_estado }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">
                                                {{ $item->cst_csosn ? $item->cst_csosn : '--' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($item->padrao)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Sim
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                                    <i class="ri-close-line me-1"></i> Não
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->sobrescrever_cfop)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Sim
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                                    <i class="ri-close-line me-1"></i> Não
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('natureza-operacao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @can('natureza_operacao_edit')
                                                    <a class="act-btn act-edit" href="{{ route('natureza-operacao.edit', [$item->id]) }}" title="Editar Natureza">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan

                                                    @can('natureza_operacao_delete')
                                                    <button type="button" class="act-btn act-del btn-delete" title="Excluir Natureza">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="modulo-empty">
                                                <i class="ri-file-settings-line"></i>
                                                <p>Nenhuma natureza de operação encontrada.</p>
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
                        <h5 class="m-0 text-dark fs-14">Total de Registros: <strong class="text-primary fs-16">{{ $data->total() }}</strong></h5>
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