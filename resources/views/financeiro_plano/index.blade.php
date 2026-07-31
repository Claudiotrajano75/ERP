@extends('layouts.app', ['title' => 'Financeiro Planos'])

@section('css')
<style>
    /* Estilos Personalizados para o Módulo Financeiro Planos */
    .page-title-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b, #475569);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .page-title i {
        color: #4f46e5;
    }

    /* Cards e Layout */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card-body {
        padding: 24px !important;
    }

    /* KPI Cards Premium */
    .modulo-kpi-card {
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
        position: relative;
        background: #fff;
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
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05) !important;
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
        font-size: 20px;
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
    
    .modulo-kpi-green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .modulo-kpi-orange::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .modulo-kpi-red::before { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Formulários de Filtro */
    .form-control, .form-select, select, input[type="text"], input[type="date"] {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .form-control:focus, .form-select:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 6px !important;
    }

    /* Botões */
    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-sm {
        padding: 6px 12px !important;
        font-size: 12px !important;
        border-radius: 8px !important;
    }

    .btn-primary {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
    }

    .btn-primary:hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
    }

    .btn-danger {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #fff !important;
    }

    .btn-danger:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
    }

    .btn-warning {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
        color: #fff !important;
    }

    .btn-warning:hover {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2) !important;
    }

    /* Tabelas */
    .table-responsive-sm {
        border-radius: 12px;
        overflow-x: auto !important;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table {
        margin-bottom: 0 !important;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        padding: 14px 20px !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-top: none !important;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table tbody td {
        padding: 14px 20px !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        color: #334155 !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
    }

    .table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Badges / Pills */
    .badge {
        padding: 6px 12px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: none !important;
        border: 1px solid transparent;
    }

    .badge-success {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }

    .badge-warning {
        background-color: #fff3e0 !important;
        color: #e65100 !important;
        border-color: #ffe0b2 !important;
    }

    .badge-danger {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }

    .badge-light {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border-color: #e2e8f0 !important;
    }

    /* Glass Filters */
    .modulo-glass-filter {
        background-color: #f8fafc !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 12px;
    }

    /* Cabeçalho de Gradiente Premium */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 20px 0 !important;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">
            <!-- Cabeçalho com Gradiente Premium -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-vip-crown-2-line"></i>
                            Financeiro Planos
                        </h4>
                        <p class="modulo-subtitle">
                            Acompanhe os pagamentos de planos, recebimentos, pendências e cancelamentos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body">
 
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
                <div class="table-responsive-sm">
                    <table class="table table-centered">
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
                                <td class="fw-bold text-success">R$ {{ __moeda($item->valor) }}</td>
                                <td><span class="badge badge-light">{{ $item->tipo_pagamento }}</span></td>
                                <td class="text-muted fs-12">{{ __data_pt($item->created_at, 1) }}</td>
                                <td>
                                    @php
                                    $status = strtolower($item->status_pagamento);
                                    @endphp
                                    @if($status == 'recebido' || $status == 'pago' || $status == 'aprovado')
                                    <span class="badge badge-success">
                                        <i class="ri-check-line"></i> {{ strtoupper($item->status_pagamento) }}
                                    </span>
                                    @elseif($status == 'pendente' || $status == 'aguardando')
                                    <span class="badge badge-warning">
                                        <i class="ri-time-line"></i> {{ strtoupper($item->status_pagamento) }}
                                    </span>
                                    @elseif($status == 'cancelado' || $status == 'rejeitado')
                                    <span class="badge badge-danger">
                                        <i class="ri-close-line"></i> {{ strtoupper($item->status_pagamento) }}
                                    </span>
                                    @else
                                    <span class="badge badge-info">
                                        <i class="ri-information-line"></i> {{ strtoupper($item->status_pagamento) }}
                                    </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('financeiro-plano.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-flex justify-content-end gap-1">
                                        @method('delete')
                                        @csrf
                                        <a class="btn btn-warning btn-sm text-white" href="{{ route('financeiro-plano.edit', [$item->id]) }}" title="Editar">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-delete btn-sm" title="Excluir">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-4 text-muted">
                                        <i class="ri-inbox-2-line fs-24 d-block mb-2 text-muted" style="opacity: 0.5;"></i>
                                        <p class="m-0 fs-13">Nenhum registro financeiro de plano encontrado.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
