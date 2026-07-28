@extends('layouts.app', ['title' => 'Lista de Pré-vendas'])

@section('css')
<style>
    /* ─── Header Gradient ─── */
    .pv-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .pv-header-gradient .pv-title {
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .pv-header-gradient .pv-title i {
        background: rgba(255,255,255,0.12);
        padding: 8px;
        border-radius: 10px;
        color: #a8b5ff;
    }
    .pv-header-gradient .pv-subtitle {
        color: rgba(255,255,255,0.6) !important;
        font-weight: 400;
    }
    .pv-header-gradient .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .pv-header-gradient .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    /* ─── KPI Cards ─── */
    .pv-kpi-card {
        border: none !important;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
        position: relative;
    }
    .pv-kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }
    .pv-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
    }
    .pv-kpi-card .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .pv-kpi-card .kpi-value {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    .pv-kpi-card .kpi-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
    }

    .pv-kpi-blue::before { background: linear-gradient(90deg, #4facfe, #00f2fe); }
    .pv-kpi-green::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }
    .pv-kpi-orange::before { background: linear-gradient(90deg, #fa709a, #fee140); }
    .pv-kpi-purple::before { background: linear-gradient(90deg, #a18cd1, #fbc2eb); }

    /* ─── Glass Filters ─── */
    .pv-glass-filter {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.8) !important;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.04);
    }
    .pv-glass-filter .form-label,
    .pv-glass-filter label {
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #5a5a7a;
        margin-bottom: 2px;
    }
    .pv-glass-filter .btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s;
    }
    .pv-glass-filter .btn:hover {
        transform: translateY(-1px);
    }

    /* ─── Premium Table ─── */
    .pv-table-wrap {
        border-radius: 12px;
        border: 1px solid #eef0f5;
        overflow: hidden;
    }
    .pv-table-wrap table {
        margin-bottom: 0;
    }
    .pv-table-wrap thead th {
        background: #f8f9fc;
        color: #5a5a7a;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 14px;
        border-bottom: 2px solid #e8eaf6;
    }
    .pv-table-wrap tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.15s ease;
        font-size: 13px;
    }
    .pv-table-wrap tbody tr {
        transition: all 0.15s ease;
    }
    .pv-table-wrap tbody tr:hover {
        background: #f5f6fe;
    }
    .pv-table-wrap tbody tr:last-child td {
        border-bottom: none;
    }
    .pv-table-wrap tbody tr.clickable {
        cursor: pointer;
    }

    /* ─── Status Badges Premium ─── */
    .pv-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .pv-badge-received {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
    }
    .pv-badge-pending {
        background: linear-gradient(135deg, #fff3e0, #ffe0b2);
        color: #e65100;
    }

    /* ─── Action Buttons ─── */
    .pv-action-group {
        display: inline-flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .pv-action-group .btn {
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 13px;
        transition: all 0.15s ease;
    }
    .pv-action-group .btn:hover {
        transform: translateY(-1px);
    }
    .pv-action-group .btn-light {
        background: #f0f2f8;
        border-color: #e8eaf6;
        color: #5a5a7a;
    }
    .pv-action-group .btn-light:hover {
        background: #e8eaf6;
        color: #302b63;
    }

    /* ─── Pagination / Footer ─── */
    .pv-footer {
        padding: 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .pv-footer .pv-total-label {
        font-size: 13px;
        color: #5a5a7a;
        font-weight: 600;
    }
    .pv-footer .pv-total-value {
        font-size: 18px;
        font-weight: 800;
        color: #2e7d32;
        letter-spacing: -0.3px;
    }

    /* ─── Empty State ─── */
    .pv-empty {
        padding: 48px 20px;
        text-align: center;
    }
    .pv-empty i {
        font-size: 48px;
        color: #c5cae9;
        margin-bottom: 12px;
        display: block;
    }
    .pv-empty p {
        color: #9e9eb8;
        font-size: 14px;
        margin: 0;
    }

    @media (max-width: 768px) {
        .pv-header-gradient .pv-title { font-size: 18px; }
        .pv-kpi-card .kpi-value { font-size: 18px; }
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header pv-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 pv-title d-flex align-items-center gap-2">
                            <i class="ri-list-ordered"></i>
                            Pré-vendas
                        </h4>
                        <p class="text-muted mb-0 pv-subtitle fs-13">
                            Visualize, filtre e gerencie pré-vendas antes de finalizá-las como NFe ou NFCe.
                        </p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('pre_venda_create')
                        <a href="{{ route('pre-venda.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Pré-venda
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI Cards — Padrão Inline Sólido ═══ -->
                <div class="row g-3 mb-4">
                    <!-- Card: Total -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(59,130,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-file-list-3-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Nesta Página</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Recebidas -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(16,185,129,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-checkbox-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Recebidas (pág.)</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->where('status', 0)->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Pendentes -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(245,158,11,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-hourglass-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Pendentes (pág.)</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->where('status', 1)->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Total R$ -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(139,92,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-money-dollar-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div style="overflow:hidden;">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Total (pág.)</div>
                                <div style="font-size:18px;font-weight:800;letter-spacing:-0.5px;white-space:nowrap;">R$ {{ __moeda($data->sum('valor_total')) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="pv-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::text('codigo', 'Código')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-1 col-6">
                            {!!Form::select('status', 'Status', [
                                '' => 'Todas',
                                '1' => 'Pendentes',
                                '0' => 'Recebidas'
                            ])->attrs(['class' => 'form-select form-select-sm'])!!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-1 col-6">
                            {!!Form::select('local_id', 'Local', ['' => 'Todos'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        @endif
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Filtrar
                                </button>
                                <a class="btn btn-outline-secondary btn-sm px-3" href="{{ route('pre-venda.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="pv-table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:80px;">Código</th>
                                    <th>Cliente</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th style="width:100px;">Local</th>
                                    @endif
                                    <th style="width:120px;">Data</th>
                                    <th style="width:120px;">Valor</th>
                                    <th style="width:110px;">Status</th>
                                    <th style="width:240px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr @can('nfce_create') class="clickable" ondblclick="finalizar('{{$item->id}}')" @endcan>
                                    <td>
                                        <span class="fw-bold" style="color:#302b63;">{{ $item->codigo }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">
                                            {{ $item->cliente_id ? $item->cliente->razao_social : 'Consumidor Final' }}
                                        </span>
                                        @if($item->cliente)
                                        <span class="text-muted d-block fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                        @endif
                                    </td>
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-11">
                                            {{ $item->localizacao->descricao ?? '--' }}
                                        </span>
                                    </td>
                                    @endif
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor_total) }}</td>
                                    <td>
                                        @if($item->status == 0)
                                        <span class="pv-badge pv-badge-received">
                                            <i class="ri-check-line"></i> Recebida
                                        </span>
                                        @else
                                        <span class="pv-badge pv-badge-pending">
                                            <i class="ri-time-line"></i> Pendente
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('pre-venda.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="pv-action-group">
                                                @if($item->status == 1)
                                                    @can('pre_venda_delete')
                                                    <button type="button" class="btn btn-danger btn-delete btn-sm" title="Excluir">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                @endif

                                                @if($item->status == 0 && $item->venda_id != null && $item->tipo_finalizado == 'nfe')
                                                <a class="btn btn-light btn-sm" title="Ver NFe" href="{{ route('nfe.show', $item->venda_id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a class="btn btn-primary text-white btn-sm" title="Imprimir pedido" target="_blank" href="{{ route('nfe.imprimir', [$item->venda_id]) }}">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @endif

                                                @if($item->status == 0 && $item->venda_id != null && $item->tipo_finalizado == 'nfce')
                                                <a class="btn btn-light btn-sm" title="Ver NFCe" href="{{ route('nfce.show', $item->venda_id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a class="btn btn-success text-white btn-sm" title="Imprimir Pedido" target="_blank" href="{{ route('frontbox.imprimir-nao-fiscal', [$item->venda_id]) }}">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @if($item->nfce && $item->nfce->estado == 'aprovado')
                                                <a class="btn btn-primary text-white btn-sm" title="Imprimir NFCe" target="_blank" href="{{ route('nfce.imprimir', [$item->venda_id]) }}">
                                                    <i class="ri-printer-fill"></i>
                                                </a>
                                                @endif
                                                @endif

                                                @if($item->status == 1)
                                                    @can('nfce_create')
                                                    <button type="button" class="btn btn-dark text-white btn-sm" title="Finalizar" onclick="finalizar('{{$item->id}}')">
                                                        <i class="ri-coins-fill"></i>
                                                    </button>
                                                    @endcan
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ __countLocalAtivo() > 1 ? 7 : 6 }}">
                                        <div class="pv-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma pré-venda encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Footer ═══ -->
                <div class="pv-footer">
                    <div>
                        <span class="pv-total-label">Total em pré-vendas:</span>
                        <span class="pv-total-value">R$ {{ __moeda($data->sum('valor_total')) }}</span>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('modals._finalizar_pre_venda', ['not_submit' => true])

@endsection

@section('js')
<script src="/js/pre_venda.js?v={{ filemtime(public_path('js/pre_venda.js')) }}"></script>
@endsection
