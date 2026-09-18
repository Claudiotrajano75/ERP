@extends('layouts.app', ['title' => 'Cotações de Compras'])

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
.act-add  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.act-add:hover  { background: #dcfce7; color: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.2); }
.act-dark { background: #f8fafc; color: #334155; border-color: #cbd5e1; }
.act-dark:hover { background: #f1f5f9; color: #0f172a; box-shadow: 0 4px 12px rgba(0,0,0,.08); }
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
.stat-cyan::before    { background: linear-gradient(180deg, #0891b2, #38bdf8); }
.stat-emerald::before { background: linear-gradient(180deg, #059669, #34d399); }
.stat-amber::before   { background: linear-gradient(180deg, #d97706, #fbbf24); }

.stat-indigo .stat-icon  { background: #eef2ff; color: #4f46e5; }
.stat-cyan .stat-icon    { background: #ecfeff; color: #0891b2; }
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
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar do Fornecedor ─── */
.forn-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

/* ─── Badges de Estado ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-info    { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
.modulo-badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.modulo-badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }

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
                                <i class="ri-price-tag-3-line"></i>
                                Painel de Cotações de Compras
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Envie links de cotação para fornecedores parceiros, compare propostas e gere compras com um clique.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('compras.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-shopping-cart-line"></i> Compras
                            </a>
                            @can('cotacao_create')
                            <a href="{{ route('cotacoes.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Nova Cotação
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
                                    <div class="stat-label">Total de Cotações</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-price-tag-3-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-cyan">
                                <div>
                                    <div class="stat-label">Respondidas / Aprovadas</div>
                                    <div class="stat-value mt-1">{{ $stats['respondidas'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-chat-check-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-emerald">
                                <div>
                                    <div class="stat-label">Compras Geradas</div>
                                    <div class="stat-value mt-1">{{ $stats['compradas'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-shopping-bag-3-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-amber">
                                <div>
                                    <div class="stat-label">Valor Total Acumulado</div>
                                    <div class="stat-value mt-1">R$ {{ __moeda($stats['valor_total']) }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTRO DE BUSCA ═══ -->
                    <div class="modulo-glass-filter-premium mb-4">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Cotações de Compras
                            </h5>
                        </div>

                        <form method="get" action="{{ route('cotacoes.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-12">
                                    <label class="form-label"><i class="ri-truck-line"></i> Fornecedor</label>
                                    <select class="select2 form-select" name="fornecedor_id">
                                        @if($fornecedor)
                                            <option value="{{ $fornecedor->id }}" selected>{{ $fornecedor->info }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-hashtag"></i> Referência</label>
                                    <input type="text" name="referencia" value="{{ request('referencia') }}" class="form-control" placeholder="Código da cotação...">
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                                    <select name="estado" class="form-select">
                                        <option value="" @selected(request('estado') == '')>Todas</option>
                                        <option value="novo" @selected(request('estado') == 'novo')>Nova</option>
                                        <option value="rejeitada" @selected(request('estado') == 'rejeitada')>Rejeitada</option>
                                        <option value="respondida" @selected(request('estado') == 'respondida')>Respondida</option>
                                        <option value="aprovada" @selected(request('estado') == 'aprovada')>Aprovada</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-shopping-cart-line"></i> Gerou Compra?</label>
                                    <select name="gerado_compra" class="form-select">
                                        <option value="" @selected(request('gerado_compra') == '')>Todas</option>
                                        <option value="0" @selected(request('gerado_compra') === '0')>Não</option>
                                        <option value="1" @selected(request('gerado_compra') === '1')>Sim</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-12 d-flex gap-2">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('cotacoes.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Distribuidor / Fornecedor</th>
                                        <th>CPF / CNPJ</th>
                                        <th>Valor da Proposta</th>
                                        <th>Estado</th>
                                        <th>Gerou Compra</th>
                                        <th>Data Criação</th>
                                        <th>Data Resposta</th>
                                        <th>Referência</th>
                                        <th class="text-end" style="width: 160px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="forn-avatar">
                                                    <i class="ri-building-line"></i>
                                                </div>
                                                <span class="fw-bold text-dark fs-13">{{ $item->fornecedor ? $item->fornecedor->razao_social : "--" }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-semibold fs-12">{{ $item->fornecedor ? $item->fornecedor->cpf_cnpj : "--" }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-13">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @if($item->estado == 'aprovada')
                                                <span class="modulo-badge modulo-badge-success"><i class="ri-check-double-line"></i> Aprovada</span>
                                            @elseif($item->estado == 'rejeitada')
                                                <span class="modulo-badge modulo-badge-danger"><i class="ri-close-line"></i> Rejeitada</span>
                                            @elseif($item->estado == 'respondida')
                                                <span class="modulo-badge modulo-badge-info"><i class="ri-chat-1-line"></i> Respondida</span>
                                            @else
                                                <span class="modulo-badge modulo-badge-warning"><i class="ri-time-line"></i> Nova</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->nfe_id)
                                                <span class="modulo-badge modulo-badge-success"><i class="ri-check-line"></i> Sim</span>
                                            @else
                                                <span class="modulo-badge modulo-badge-neutral" style="background:#f1f5f9; color:#64748b;"><i class="ri-close-line"></i> Não</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ __data_pt($item->created_at) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $item->data_resposta ? __data_pt($item->data_resposta) : '--' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-indigo-subtle text-primary border border-indigo-subtle px-2 py-1 fs-12 fw-bold" style="background: #eef2ff;">
                                                #{{ $item->referencia }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('cotacoes.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @if($item->estado != 'aprovada')
                                                        @can('cotacao_edit')
                                                        <a class="act-btn act-edit" href="{{ route('cotacoes.edit', $item->id) }}" title="Editar Cotação">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        @endcan
                                                        @can('cotacao_delete')
                                                        <button type="button" class="act-btn act-del btn-delete" title="Excluir Cotação">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                        @endcan
                                                    @endif

                                                    <a title="Abrir Link de Resposta do Fornecedor" target="_blank" class="act-btn act-dark" href="{{ route('cotacoes.resposta', $item->hash_link) }}">
                                                        <i class="ri-external-link-line"></i>
                                                    </a>

                                                    @if($item->estado == 'respondida' || $item->estado == 'aprovada')
                                                        <a title="Ver e Avaliar Resposta da Cotação" class="act-btn act-add" href="{{ route('cotacoes.show', $item->id) }}">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9">
                                            <div class="modulo-empty">
                                                <i class="ri-price-tag-3-line"></i>
                                                <p>Nenhuma cotação de compra encontrada no período.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection