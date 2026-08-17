@extends('layouts.app', ['title' => 'Notificações do Sistema'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05) !important;
    }

    .card-body {
        padding: 24px !important;
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

    /* Formulários de Filtro */
    .form-control, select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .form-control:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 13px !important;
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

    .btn-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
    }

    .btn-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
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

    .btn-info {
        background-color: #0ea5e9 !important;
        border-color: #0ea5e9 !important;
        color: #fff !important;
    }

    .btn-info:hover {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2) !important;
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

    /* Tabelas */
    .table-responsive {
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

    /* Badges Modernizados (Pills) */
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

    .bg-success-subtle {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }

    .bg-danger-subtle {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }

    .bg-warning-subtle {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border-color: #fef3c7 !important;
    }

    .bg-info-subtle {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border-color: #bae6fd !important;
    }

    .bg-primary-subtle {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border-color: #c7d2fe !important;
    }

    .bg-secondary-subtle {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border-color: #e2e8f0 !important;
    }

    .modulo-action-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: nowrap;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">

            <!-- ═══ CABEÇALHO COM GRADIENTE PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-notification-3-line"></i> Notificações do Sistema
                        </h4>
                        <p class="modulo-subtitle">
                            Envie e gerencie comunicados, avisos e alertas para as empresas clientes do ERP.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('notificacao-super.create') }}" class="btn btn-success">
                            <i class="ri-add-circle-fill"></i> Nova Notificação
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- ═══ KPI CARDS (RESUMO) ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Notificações</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['total'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Histórico geral</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-message-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Ativas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['ativas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Em exibição nos painéis</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-primary mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Visualizadas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['visualizadas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Lidas pelos clientes</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-eye-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-danger mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Prioridade Alta</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['altas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Avisos urgentes</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-alarm-warning-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FILTRO DE PESQUISA ═══ -->
                <div class="col-lg-12 mb-3">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row align-items-end g-2">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-building-line me-1"></i> Empresa</label>
                            {!!Form::select('empresa_id', '', ['' => 'Todas as Empresas'] + $empresas)
                            ->attrs(['class' => 'form-select select2'])
                            !!}
                        </div>

                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-toggle-line me-1"></i> Status</label>
                            {!!Form::select('status', '', ['' => 'Todos', '1' => 'Ativo', '0' => 'Desativado'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-flag-line me-1"></i> Prioridade</label>
                            {!!Form::select('prioridade', '', ['' => 'Todas', 'baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                            <button class="btn btn-primary" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                            <a id="clear-filter" class="btn btn-danger" href="{{ route('notificacao-super.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA DE DADOS ═══ -->
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    <th>Empresa</th>
                                    <th>Título & Resumo</th>
                                    <th>Prioridade</th>
                                    <th>Status</th>
                                    <th>Lida?</th>
                                    <th>Data de Criação</th>
                                    <th width="12%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->empresa)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-xs flex-shrink-0">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-13 fw-bold">
                                                        {{ strtoupper(substr($item->empresa->nome ?? 'E', 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $item->empresa->nome }}</strong>
                                                    <span class="text-muted d-block fs-11">{{ $item->empresa->cpf_cnpj }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary-subtle">
                                                <i class="ri-global-line me-1"></i> Todas as Empresas
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $item->titulo }}</strong>
                                        <span class="text-muted d-block fs-11">{{ $item->descricao_curta }}</span>
                                    </td>
                                    <td>
                                        @if($item->prioridade == 'alta')
                                            <span class="badge bg-danger-subtle">
                                                <i class="ri-alarm-warning-line me-1"></i> Alta
                                            </span>
                                        @elseif($item->prioridade == 'media')
                                            <span class="badge bg-warning-subtle">
                                                <i class="ri-alert-line me-1"></i> Média
                                            </span>
                                        @else
                                            <span class="badge bg-info-subtle">
                                                <i class="ri-information-line me-1"></i> Baixa
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                            <span class="badge bg-success-subtle">
                                                <i class="ri-checkbox-circle-line me-1"></i> Ativo
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle">
                                                <i class="ri-close-circle-line me-1"></i> Desativado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->visualizada)
                                            <span class="badge bg-success-subtle">
                                                <i class="ri-eye-line me-1"></i> Sim
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle">
                                                <i class="ri-eye-off-line me-1"></i> Não
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">
                                            <i class="ri-calendar-line me-1"></i>{{ __data_pt($item->created_at, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('notificacao-super.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('notificacao-super.edit', $item->id) }}" title="Editar Notificação">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>

                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Notificação">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="ri-inbox-line fs-24 d-block mb-1 text-muted"></i>
                                        Nenhuma notificação cadastrada.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (REMOVER SELECIONADOS + PAGINAÇÃO) ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        <form action="{{ route('notificacao-super.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionados
                            </button>
                        </form>
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

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
