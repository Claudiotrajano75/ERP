@extends('layouts.app', ['title' => 'Caixas Abertos'])

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
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.stat-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar Operador ─── */
.operator-avatar {
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
.act-close { background: #fef3c7; color: #d97706; border-color: #fde68a; }
.act-close:hover { background: #d97706; color: #fff; box-shadow: 0 3px 8px rgba(217,119,6,0.3); }
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
                            <i class="ri-lock-unlock-line"></i>
                            Caixas Abertos no Sistema
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Visão administrativa de todos os caixas em operação na empresa neste momento.</p>
                    </div>
                    <div>
                        <a href="{{ route('caixa.list') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar ao Histórico
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                @if(isset($stats))
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-12">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Caixas Abertos Ativos</div>
                                <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                            </div>
                            <i class="ri-lock-unlock-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-card stat-indigo">
                            <div>
                                <div class="stat-label">Total em Abertura</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['valor_abertura']) }}</div>
                            </div>
                            <i class="ri-money-dollar-circle-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Abertos Hoje</div>
                                <div class="stat-value mt-1">{{ $stats['hoje'] }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
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
                                    <th>Operador (Caixa)</th>
                                    <th>Status</th>
                                    <th>Data e Hora de Abertura</th>
                                    <th>Valor Inicial de Abertura</th>
                                    <th class="text-end" style="width: 140px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="operator-avatar">
                                                <i class="ri-user-3-line"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark d-block">{{ $item->usuario ? $item->usuario->name : '--' }}</span>
                                                <span class="text-muted fs-11">Caixa #{{ $item->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-checkbox-circle-line me-1"></i> Em Aberto
                                        </span>
                                    </td>
                                    <td class="fs-12 text-dark">
                                        {{ __data_pt($item->created_at) }}
                                        <span class="d-block fs-11 text-muted">{{ $item->created_at->format('H:i:s') }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success fs-14">R$ {{ __moeda($item->valor_abertura) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('caixa.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @csrf
                                            @method('delete')
                                            <div class="act-group">
                                                {{-- Fechar Caixa (Admin) --}}
                                                <a class="act-btn act-close" href="{{ route('caixa.fechar-empresa', $item) }}" title="Encerrar / Fechar Caixa">
                                                    <i class="ri-lock-line"></i>
                                                </a>

                                                {{-- Excluir Caixa --}}
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Registro de Caixa">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-checkbox-circle-line"></i>
                                            <p>Nenhum caixa aberto no momento. Todos os caixas estão encerrados.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
