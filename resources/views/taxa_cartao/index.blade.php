@extends('layouts.app', ['title' => 'Taxas de Cartão'])

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
.stat-blue   { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar Tipo ─── */
.type-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

/* ─── Grade de Ações ─── */
.act-group { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
.act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 1px solid transparent;
    transition: all .15s ease;
    cursor: pointer;
    text-decoration: none !important;
}
.act-btn:hover { transform: translateY(-1px); }
.act-edit  { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover  { background: #4f46e5; color: #fff; box-shadow: 0 3px 8px rgba(79,70,229,0.3); }
.act-del   { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
.act-del:hover   { background: #dc2626; color: #fff; box-shadow: 0 3px 8px rgba(220,38,38,0.3); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-percent-line"></i> 
                            Taxas de Cartão
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Configure as taxas de pagamento de cartão e suas bandeiras para o cálculo correto dos faturamentos.</p>
                    </div>
                    <div>
                        @can('taxa_pagamento_create')
                        <a href="{{ route('taxa-cartao.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-circle-line"></i> Nova Taxa
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
                                <div class="stat-label">Total de Regras</div>
                                <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                            </div>
                            <i class="ri-percent-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Taxa Média</div>
                                <div class="stat-value mt-1">{{ number_format($stats['taxa_media'], 2, ',', '.') }}%</div>
                            </div>
                            <i class="ri-calculator-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-blue">
                            <div>
                                <div class="stat-label">Regras Crédito</div>
                                <div class="stat-value mt-1">{{ $stats['credito'] }}</div>
                            </div>
                            <i class="ri-bank-card-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Regras Débito</div>
                                <div class="stat-value mt-1">{{ $stats['debito'] }}</div>
                            </div>
                            <i class="ri-bank-card-2-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Tipo de Pagamento</th>
                                    <th>Bandeira do Cartão</th>
                                    <th>Taxa (%)</th>
                                    <th class="text-end" style="width:120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="type-avatar">
                                                <i class="ri-bank-card-line"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark d-block">{{ $item->getTipo() }}</span>
                                                <span class="text-muted fs-11">Código: {{ $item->tipo_pagamento }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->bandeira_cartao)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12">
                                            <i class="ri-vip-diamond-line me-1"></i> {{ $item->getBandeira() }}
                                        </span>
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">
                                            Todas as Bandeiras
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-danger fs-14">{{ __moeda($item->taxa) }}%</strong>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('taxa-cartao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @csrf
                                            @method('delete')
                                            <div class="act-group">
                                                @can('taxa_pagamento_edit')
                                                <a class="act-btn act-edit" href="{{ route('taxa-cartao.edit', [$item->id]) }}" title="Editar Taxa">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('taxa_pagamento_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Taxa">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="modulo-empty">
                                            <i class="ri-percent-line"></i>
                                            <p>Nenhuma taxa de cartão configurada até o momento.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ PAGINAÇÃO ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        <h6 class="m-0 text-muted fs-13">
                            Exibindo <strong>{{ $data->count() }}</strong> taxas cadastradas
                        </h6>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
