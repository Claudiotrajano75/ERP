@extends('layouts.app', ['title' => 'Financeiro Planos'])

@section('css')
<style>
/* ─── Header Gradient ─── */
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
}
.modulo-header-gradient .modulo-title {
    color: #fff;
    font-weight: 700;
    letter-spacing: -0.3px;
}
.modulo-header-gradient .modulo-title i {
    background: rgba(255,255,255,0.12);
    padding: 8px;
    border-radius: 10px;
    color: #a8b5ff;
}
.modulo-header-gradient .modulo-subtitle {
    color: rgba(255,255,255,0.6) !important;
    font-weight: 400;
}
.modulo-header-gradient .btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.modulo-header-gradient .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}

/* ─── KPI Cards Premium ─── */
.modulo-kpi-card {
    border: none !important;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.25s ease;
    position: relative;
}
.modulo-kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}
.modulo-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}
.modulo-kpi-card .kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.modulo-kpi-card .kpi-value {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.5px;
    line-height: 1.2;
}
.modulo-kpi-card .kpi-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.7;
}
.modulo-kpi-green::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }
.modulo-kpi-orange::before { background: linear-gradient(90deg, #fa709a, #fee140); }
.modulo-kpi-red::before { background: linear-gradient(90deg, #f093fb, #f5576c); }

/* ─── Glass Filters ─── */
.modulo-glass-filter {
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.8) !important;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.04);
}
.modulo-glass-filter label {
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #5a5a7a;
    margin-bottom: 2px;
}
.modulo-glass-filter .btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s;
}
.modulo-glass-filter .btn:hover {
    transform: translateY(-1px);
}

/* ─── Premium Table ─── */
.modulo-table-wrap {
    border-radius: 12px;
    border: 1px solid #eef0f5;
    overflow: hidden;
}
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 12px 14px;
    border-bottom: 2px solid #e8eaf6;
}
.modulo-table-wrap tbody td {
    padding: 12px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    transition: background 0.15s ease;
    font-size: 13px;
}
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Status Badges Premium ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.modulo-badge-warning { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }
.modulo-badge-danger  { background: linear-gradient(135deg, #fbe9e7, #ffccbc); color: #c62828; }
.modulo-badge-info    { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #1565c0; }

/* ─── Action Buttons ─── */
.modulo-action-group {
    display: inline-flex;
    gap: 4px;
    flex-wrap: wrap;
}
.modulo-action-group .btn {
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 13px;
    transition: all 0.15s ease;
}
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Footer ─── */
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
    letter-spacing: -0.3px;
}

/* ─── Empty State ─── */
.modulo-empty {
    padding: 48px 20px;
    text-align: center;
}
.modulo-empty i {
    font-size: 48px;
    color: #c5cae9;
    margin-bottom: 12px;
    display: block;
}
.modulo-empty p {
    color: #9e9eb8;
    font-size: 14px;
    margin: 0;
}

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
    .modulo-kpi-card .kpi-value { font-size: 18px; }
}
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
                            <i class="ri-vip-crown-2-line"></i>
                            Financeiro Planos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Acompanhe os pagamentos de planos, recebimentos, pendências e cancelamentos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI Cards Premium ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-green shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);color:#2e7d32;">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-success">R$ {{ __moeda($somaRecebido) }}</div>
                                    <div class="kpi-label text-muted">Recebido</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-orange shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#fff3e0,#ffe0b2);color:#e65100;">
                                    <i class="ri-hourglass-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value" style="color:#e65100;">R$ {{ __moeda($somaPendente) }}</div>
                                    <div class="kpi-label text-muted">Pendente</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-red shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#fbe9e7,#ffccbc);color:#c62828;">
                                    <i class="ri-close-circle-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-danger">R$ {{ __moeda($somaCancelado) }}</div>
                                    <div class="kpi-label text-muted">Cancelado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('empresa', 'Empresa')
                            ->options($empresa ? [$empresa->id => $empresa->info] : [])
                            ->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-3 col-12">
                            {!!Form::select('status_pagamento', 'Status', ['' => 'Todos'] + \App\Models\FinanceiroPlano::statusDePagamentos())
                            ->attrs(['class' => 'form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Filtrar
                                </button>
                                <a class="btn btn-outline-secondary btn-sm px-3" href="{{ route('financeiro-plano.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
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
                                    <th>Empresa</th>
                                    <th>Plano</th>
                                    <th>Valor</th>
                                    <th>Tipo Pagamento</th>
                                    <th>Data Cadastro</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td><span class="fw-semibold text-dark">{{ $item->empresa->info }}</span></td>
                                    <td>{{ $item->plano->nome }}</td>
                                    <td class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor) }}</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $item->tipo_pagamento }}</span></td>
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at, 1) }}</td>
                                    <td>
                                        @php
                                        $status = strtolower($item->status_pagamento);
                                        @endphp
                                        @if($status == 'recebido' || $status == 'pago' || $status == 'aprovado')
                                        <span class="modulo-badge modulo-badge-success">
                                            <i class="ri-check-line"></i> {{ strtoupper($item->status_pagamento) }}
                                        </span>
                                        @elseif($status == 'pendente' || $status == 'aguardando')
                                        <span class="modulo-badge modulo-badge-warning">
                                            <i class="ri-time-line"></i> {{ strtoupper($item->status_pagamento) }}
                                        </span>
                                        @elseif($status == 'cancelado' || $status == 'rejeitado')
                                        <span class="modulo-badge modulo-badge-danger">
                                            <i class="ri-close-line"></i> {{ strtoupper($item->status_pagamento) }}
                                        </span>
                                        @else
                                        <span class="modulo-badge modulo-badge-info">
                                            <i class="ri-information-line"></i> {{ strtoupper($item->status_pagamento) }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('financeiro-plano.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('financeiro-plano.edit', [$item->id]) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-delete btn-sm" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum registro financeiro de plano encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Footer ═══ -->
                <div class="modulo-footer">
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <span class="modulo-total-label text-success">Recebido:</span>
                            <span class="modulo-total-value text-success">R$ {{ __moeda($somaRecebido) }}</span>
                        </div>
                        <div>
                            <span class="modulo-total-label" style="color:#e65100;">Pendente:</span>
                            <span class="modulo-total-value" style="color:#e65100;">R$ {{ __moeda($somaPendente) }}</span>
                        </div>
                        <div>
                            <span class="modulo-total-label text-danger">Cancelado:</span>
                            <span class="modulo-total-value text-danger">R$ {{ __moeda($somaCancelado) }}</span>
                        </div>
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
