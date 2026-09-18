@extends('layouts.app', ['title' => 'Op. Interestadual - DIFAL'])

@section('css')
<style>
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
.act-del  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover  { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; bottom: 0;
    width: 4px;
}
.stat-indigo::before  { background: linear-gradient(180deg, #4f46e5, #818cf8); }
.stat-emerald::before { background: linear-gradient(180deg, #059669, #34d399); }
.stat-amber::before   { background: linear-gradient(180deg, #d97706, #fbbf24); }

.stat-indigo .stat-icon  { background: #eef2ff; color: #4f46e5; }
.stat-emerald .stat-icon { background: #ecfdf5; color: #059669; }
.stat-amber .stat-icon   { background: #fffbeb; color: #d97706; }

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
}
.stat-value {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Badges ─── */
.uf-badge {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    background: #eef2ff;
    color: #4f46e5;
    border: 1px solid #c7d2fe;
    display: inline-block;
}

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
                                <i class="ri-map-2-fill"></i>
                                Op. Interestadual — DIFAL
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Cadastro e parametrização das alíquotas do Diferencial de Alíquota (DIFAL) e FCP por estado de destino.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('config.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-settings-4-line"></i> Config. Fiscais
                            </a>
                            @can('difal_view')
                            <a href="{{ route('difal.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Novo DIFAL
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-12">
                            <div class="stat-card stat-indigo">
                                <div>
                                    <div class="stat-label">Regras Cadastradas</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-map-2-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-emerald">
                                <div>
                                    <div class="stat-label">Estados (UFs) Mapeados</div>
                                    <div class="stat-value mt-1">{{ $stats['total_ufs'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-compass-3-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-amber">
                                <div>
                                    <div class="stat-label">CFOPs Configurados</div>
                                    <div class="stat-value mt-1">{{ $stats['total_cfops'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-file-code-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTRO DE BUSCA ═══ -->
                    <div class="modulo-glass-filter-premium mb-4">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Regras DIFAL
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-9 col-12">
                                <label class="form-label"><i class="ri-search-line"></i> Pesquisar por CFOP</label>
                                {!!Form::text('cfop', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o CFOP para filtrar (ex: 6102, 6108)...'])!!}
                            </div>
                            <div class="col-md-3 col-12 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('difal.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>UF Destino</th>
                                        <th>CFOP</th>
                                        <th>% ICMS UF Destino</th>
                                        <th>% ICMS Interno</th>
                                        <th>% ICMS Interestadual</th>
                                        <th>% Fundo Combate à Pobreza (FCP)</th>
                                        <th class="text-end" style="width: 100px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span class="uf-badge">{{ $item->uf }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">
                                                <i class="ri-file-code-line text-muted me-1"></i> {{ $item->cfop }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $item->pICMSUFDest }}%</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $item->pICMSInter }}%</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $item->pICMSInterPart }}%</span>
                                        </td>
                                        <td>
                                            @if($item->pFCPUFDest > 0)
                                                <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1">{{ $item->pFCPUFDest }}%</span>
                                            @else
                                                <span class="text-muted fs-12">0,00%</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('difal.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @can('difal_edit')
                                                    <a class="act-btn act-edit" href="{{ route('difal.edit', [$item->id]) }}" title="Editar DIFAL">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan
                                                    @can('difal_delete')
                                                    <button type="button" class="act-btn act-del btn-delete" title="Remover DIFAL">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="modulo-empty">
                                                <i class="ri-map-2-line"></i>
                                                <p>Nenhuma regra de DIFAL encontrada para os filtros selecionados.</p>
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
                            <span class="text-muted fs-13">Exibindo <strong>{{ $data->count() }}</strong> registro(s)</span>
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