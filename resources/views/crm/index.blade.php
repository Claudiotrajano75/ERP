@extends('layouts.app', ['title' => 'CRM & Inteligência de Clientes'])

@section('css')
<style type="text/css">
/* ─── Cards RFM ─── */
.crm-card {
    border-radius: 14px;
    padding: 16px 20px;
    border: 1px solid #eef0f6;
    background: #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.02);
    transition: all .2s ease;
    cursor: pointer;
    text-decoration: none !important;
    display: block;
}
.crm-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}
.crm-card.active-segment {
    border-color: #4f46e5 !important;
    background: #f8fafc;
    box-shadow: 0 0 0 2px #4f46e5;
}
.crm-card-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
}
.crm-card-value {
    font-size: 26px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

/* ─── Ranking ─── */
.rank-badge {
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 800;
    font-size: 12px;
}
.rank-1 { background: #fef08a; color: #854d0e; }
.rank-2 { background: #e2e8f0; color: #475569; }
.rank-3 { background: #fed7aa; color: #9a3412; }
.rank-other { background: #f1f5f9; color: #64748b; }

.tb-wrap {
    border-radius: 14px;
    border: 1px solid #eef0f5;
    overflow: hidden;
    background: #fff;
}
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8eaf6;
    white-space: nowrap;
}
.tb-wrap tbody td {
    padding: 13px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13.5px;
    color: #374151;
}
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-user-star-line"></i>
                            CRM & Segmentação de Clientes (RFM)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Inteligência de base de clientes, recência de compra e identificação de clientes em risco.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        <a href="{{ route('clientes.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-user-add-line"></i> Novo Cliente
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ Cards RFM / Segmentos ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4 col-xl-2">
                        <a href="{{ route('crm.index', ['segmento' => 'campeoes']) }}" class="crm-card {{ $segmentoFiltro == 'campeoes' ? 'active-segment' : '' }}">
                            <div class="crm-card-title text-success">★ Campeões (VIP)</div>
                            <div class="crm-card-value text-success">{{ $contagemSegmentos['campeoes'] }}</div>
                            <small class="text-muted">Compraram < 30d</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <a href="{{ route('crm.index', ['segmento' => 'fieis']) }}" class="crm-card {{ $segmentoFiltro == 'fieis' ? 'active-segment' : '' }}">
                            <div class="crm-card-title text-primary">Fiéis / Recorrentes</div>
                            <div class="crm-card-value text-primary">{{ $contagemSegmentos['fieis'] }}</div>
                            <small class="text-muted">3+ compras frequentes</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <a href="{{ route('crm.index', ['segmento' => 'novos']) }}" class="crm-card {{ $segmentoFiltro == 'novos' ? 'active-segment' : '' }}">
                            <div class="crm-card-title text-info">Novos Promissores</div>
                            <div class="crm-card-value text-info">{{ $contagemSegmentos['novos'] }}</div>
                            <small class="text-muted">1ª compra recente</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <a href="{{ route('crm.index', ['segmento' => 'em_risco']) }}" class="crm-card {{ $segmentoFiltro == 'em_risco' ? 'active-segment' : '' }}">
                            <div class="crm-card-title text-warning">⚠️ Em Risco (60d+)</div>
                            <div class="crm-card-value text-warning">{{ $contagemSegmentos['em_risco'] }}</div>
                            <small class="text-muted">Sem comprar há 60-120d</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <a href="{{ route('crm.index', ['segmento' => 'inativos']) }}" class="crm-card {{ $segmentoFiltro == 'inativos' ? 'active-segment' : '' }}">
                            <div class="crm-card-title text-danger">⛔ Inativos (+120d)</div>
                            <div class="crm-card-value text-danger">{{ $contagemSegmentos['inativos'] }}</div>
                            <small class="text-muted">Sem compra há +120d</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <a href="{{ route('crm.index') }}" class="crm-card {{ !$segmentoFiltro ? 'active-segment' : '' }}">
                            <div class="crm-card-title text-dark">Todos os Clientes</div>
                            <div class="crm-card-value">{{ $totalClientes }}</div>
                            <small class="text-muted">R$ {{ __moeda($totalFaturamentoGeral) }}</small>
                        </a>
                    </div>
                </div>

                <div class="row g-4 mb-2">
                    <!-- ═══ TOP 10 CLIENTES (RANKING) ═══ -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0 text-dark">
                                    <i class="ri-trophy-line text-warning me-1"></i> Top 10 Clientes
                                </h5>
                            </div>
                            <div class="card-body p-3">
                                <div class="list-group list-group-flush">
                                    @forelse($topClientes as $index => $tc)
                                    @if($tc->valor_total > 0)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-3 py-2 border-0 rounded-3 mb-1 bg-white shadow-sm">
                                        <div class="d-flex align-items-center gap-2 text-truncate">
                                            <span class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other')) }}">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="text-truncate">
                                                <a href="{{ route('crm.show', $tc->id) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate">
                                                    {{ $tc->razao_social }}
                                                </a>
                                                <small class="text-muted">{{ $tc->total_compras }} compras • Última: {{ $tc->ultima_compra ? $tc->ultima_compra->format('d/m/y') : '-' }}</small>
                                            </div>
                                        </div>
                                        <span class="fw-bold text-success text-nowrap ms-2">
                                            R$ {{ __moeda($tc->valor_total) }}
                                        </span>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="text-center py-4 text-muted">Nenhum dado de compras encontrado.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ LISTA COMPLETA COM FILTROS ═══ -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-3 px-4 pb-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h5 class="fw-bold mb-0 text-dark">
                                        <i class="ri-contacts-line text-primary me-1"></i> Base de Clientes 
                                        @if($segmentoFiltro)
                                            <span class="badge bg-indigo text-white ms-1">Filtro: {{ ucfirst($segmentoFiltro) }}</span>
                                        @endif
                                    </h5>
                                    <form method="GET" action="{{ route('crm.index') }}" class="d-flex gap-2">
                                        @if($segmentoFiltro)
                                            <input type="hidden" name="segmento" value="{{ $segmentoFiltro }}">
                                        @endif
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" name="pesquisa" class="form-control" placeholder="Buscar por nome, CPF/CNPJ..." value="{{ $pesquisa }}">
                                            <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                        </div>
                                        @if($pesquisa || $segmentoFiltro)
                                            <a href="{{ route('crm.index') }}" class="btn btn-sm btn-light" title="Limpar"><i class="ri-close-line"></i></a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tb-wrap table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Cliente</th>
                                                <th>Status / Segmento</th>
                                                <th class="text-center">Compras</th>
                                                <th class="text-end">Total Comprado</th>
                                                <th class="text-center">Última Compra</th>
                                                <th class="text-end pe-4">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clientesLista as $cli)
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="{{ route('crm.show', $cli->id) }}" class="fw-bold text-dark text-decoration-none">
                                                        {{ $cli->razao_social }}
                                                    </a>
                                                    <div class="text-muted small">
                                                        {{ $cli->cpf_cnpj }} 
                                                        @if($cli->telefone)
                                                            • <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $cli->telefone) }}" target="_blank" class="text-success text-decoration-none"><i class="ri-whatsapp-line"></i> {{ $cli->telefone }}</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $cli->badge['class'] }} px-2 py-1">
                                                        {{ $cli->badge['label'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold">{{ $cli->total_compras }}</td>
                                                <td class="text-end fw-bold text-success">
                                                    R$ {{ __moeda($cli->valor_total) }}
                                                </td>
                                                <td class="text-center">
                                                    @if($cli->ultima_compra)
                                                        <span class="d-block small fw-bold">{{ $cli->ultima_compra->format('d/m/Y') }}</span>
                                                        <small class="text-muted">{{ $cli->dias_sem_comprar }} dias atrás</small>
                                                    @else
                                                        <span class="text-muted small">Nunca comprou</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('crm.show', $cli->id) }}" class="dash-btn dash-btn-light" title="Ver Histórico Completo">
                                                        <i class="ri-eye-line"></i> Histórico
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Nenhum cliente encontrado para os filtros selecionados.</td>
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
        </div>
    </div>
</div>
@endsection
