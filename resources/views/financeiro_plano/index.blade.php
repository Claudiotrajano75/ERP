@extends('layouts.app', ['title' => 'Financeiro Planos'])

@section('css')
<style type="text/css">
    /* ═══════════════════════════════════════════════════════════════
       ESTILOS MODERNOS - FINANCEIRO DE PLANOS
       ═══════════════════════════════════════════════════════════════ */
    .financeiro-container {
        --primary-gradient: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        --card-radius: 16px;
        --soft-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
        --hover-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.1), 0 4px 10px -2px rgba(15, 23, 42, 0.05);
    }

    .main-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        box-shadow: var(--soft-shadow) !important;
        border-radius: var(--card-radius) !important;
        overflow: hidden;
        background: #ffffff;
        margin-bottom: 24px;
    }

    /* Cabeçalho Premium */
    .modulo-header-gradient {
        background: var(--primary-gradient) !important;
        padding: 22px 28px !important;
        border-bottom: none !important;
        position: relative;
    }

    .modulo-header-gradient .modulo-title {
        color: #ffffff !important;
        font-weight: 700 !important;
        letter-spacing: -0.4px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        font-size: 1.25rem;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.12) !important;
        padding: 10px !important;
        border-radius: 12px !important;
        color: #fcd34d !important;
        font-size: 22px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(224, 231, 255, 0.8) !important;
        font-weight: 400 !important;
        font-size: 13.5px !important;
        margin-top: 6px !important;
        margin-bottom: 0 !important;
    }

    /* KPI Cards Modernos */
    .kpi-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--soft-shadow);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--hover-shadow);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .kpi-recebido::before { background: #10b981; }
    .kpi-pendente::before { background: #f59e0b; }
    .kpi-cancelado::before { background: #ef4444; }
    .kpi-total::before { background: #6366f1; }

    .kpi-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .kpi-recebido .kpi-icon-wrap { background: #ecfdf5; color: #059669; }
    .kpi-pendente .kpi-icon-wrap { background: #fffbeb; color: #d97706; }
    .kpi-cancelado .kpi-icon-wrap { background: #fef2f2; color: #dc2626; }
    .kpi-total .kpi-icon-wrap { background: #eef2ff; color: #4f46e5; }

    .kpi-title {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 4px;
    }

    .kpi-value {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 2px;
    }

    .kpi-desc {
        font-size: 11.5px;
        color: #94a3b8;
        margin: 0;
    }

    /* Área de Filtros */
    .filter-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 20px;
    }

    /* Tabela Moderna */
    .table-container-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow-x: auto;
    }

    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-modern tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern tbody td {
        padding: 16px 18px;
        vertical-align: middle;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badges e Pílulas */
    .badge-modern {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-status-recebido {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-status-pendente {
        background-color: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-status-cancelado {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-plano {
        background-color: #f5f3ff;
        color: #5b21b6;
        border: 1px solid #ddd6fe;
        font-weight: 700;
    }

    .badge-pagamento {
        background-color: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    /* Botões de Ação */
    .btn-action-edit {
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .btn-action-edit:hover {
        background: #4338ca;
        color: #ffffff;
        border-color: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(67, 56, 202, 0.25);
    }

    .btn-action-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);
    }

    .company-avatar-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .modal-banner-empresa {
        background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 financeiro-container">
    <div class="row">
        <div class="col-12">
            <div class="card main-card">
                <!-- Cabeçalho com Gradiente Moderno -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h4 class="modulo-title text-white">
                                <i class="ri-vip-crown-2-line"></i> Financeiro de Planos & Assinaturas
                            </h4>
                            <p class="modulo-subtitle">
                                Acompanhe os pagamentos de planos, receitas recebidas, faturas pendentes e cancelamentos.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('gerenciar-planos.index') }}" class="btn btn-outline-light px-3 shadow-sm" style="border-radius: 10px;">
                                <i class="ri-exchange-funds-line me-1"></i> Gerenciar Planos & Atribuições
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ KPI CARDS (RESUMO FINANCEIRO) ═══ -->
                    <div class="row g-3 mb-4">
                        <!-- Total Recebido -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-recebido">
                                <div>
                                    <div class="kpi-title">Total Recebido</div>
                                    <div class="kpi-value text-success">R$ {{ __moeda($somaRecebido ?? 0) }}</div>
                                    <p class="kpi-desc">Pagamentos confirmados</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pendente -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-pendente">
                                <div>
                                    <div class="kpi-title">Total Pendente</div>
                                    <div class="kpi-value text-warning">R$ {{ __moeda($somaPendente ?? 0) }}</div>
                                    <p class="kpi-desc">Aguardando compensação</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-hourglass-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Cancelado -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-cancelado">
                                <div>
                                    <div class="kpi-title">Total Cancelado</div>
                                    <div class="kpi-value text-danger">R$ {{ __moeda($somaCancelado ?? 0) }}</div>
                                    <p class="kpi-desc">Faturas estornadas/canceladas</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-close-circle-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Previsão Geral -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-total">
                                <div>
                                    <div class="kpi-title">Previsão Total</div>
                                    <div class="kpi-value text-primary">R$ {{ __moeda($somaTotal ?? (($somaRecebido ?? 0) + ($somaPendente ?? 0))) }}</div>
                                    <p class="kpi-desc">Recebido + Pendente</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ FILTROS DE PESQUISA ═══ -->
                    <div class="filter-box mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-3 col-md-6 col-12">
                                <label class="form-label text-muted fw-bold fs-12 mb-1">
                                    <i class="ri-building-line text-primary me-1"></i> Empresa
                                </label>
                                {!!Form::select('empresa', '', $empresa ? [$empresa->id => $empresa->info] : [])
                                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-empresa_id'])!!}
                            </div>
                            <div class="col-lg-2 col-md-3 col-6">
                                <label class="form-label text-muted fw-bold fs-12 mb-1">
                                    <i class="ri-calendar-line text-primary me-1"></i> Data Inicial
                                </label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-lg-2 col-md-3 col-6">
                                <label class="form-label text-muted fw-bold fs-12 mb-1">
                                    <i class="ri-calendar-line text-primary me-1"></i> Data Final
                                </label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-lg-2 col-md-4 col-12">
                                <label class="form-label text-muted fw-bold fs-12 mb-1">
                                    <i class="ri-filter-line text-primary me-1"></i> Status
                                </label>
                                {!!Form::select('status_pagamento', '', ['' => 'Todos os Status'] + \App\Models\FinanceiroPlano::statusDePagamentos())
                                ->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-lg-3 col-md-8 col-12 d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Filtrar
                                </button>
                                <a class="btn btn-outline-secondary px-3" href="{{ route('financeiro-plano.index') }}" title="Limpar Filtro">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA DE FATURAS MODERNIZADA ═══ -->
                    <div class="table-container-modern shadow-sm">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Plano</th>
                                    <th>Valor</th>
                                    <th>Forma Pagto</th>
                                    <th>Data Cadastro</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 130px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                @php
                                    $st = strtolower($item->status_pagamento);
                                @endphp
                                <tr>
                                    <!-- Empresa -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="company-avatar-box">
                                                <i class="ri-building-2-line"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-13 lh-1 mb-1">
                                                    {{ $item->empresa ? ($item->empresa->nome ?? $item->empresa->razao_social ?? $item->empresa->info) : '--' }}
                                                </div>
                                                <div class="text-muted fs-11">
                                                    <i class="ri-fingerprint-line me-1"></i>{{ $item->empresa ? ($item->empresa->cpf_cnpj ?? $item->empresa->cnpj ?? '') : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Plano -->
                                    <td>
                                        <span class="badge-modern badge-plano">
                                            <i class="ri-vip-diamond-line fs-12"></i>
                                            {{ $item->plano ? $item->plano->nome : 'Plano Removido' }}
                                        </span>
                                    </td>

                                    <!-- Valor -->
                                    <td>
                                        <span class="fw-bold fs-14 @if($st == 'recebido' || $st == 'pago') text-success @elseif($st == 'pendente') text-warning @else text-muted @endif">
                                            R$ {{ __moeda($item->valor) }}
                                        </span>
                                    </td>

                                    <!-- Tipo Pagamento -->
                                    <td>
                                        <span class="badge-modern badge-pagamento">
                                            @if(stripos($item->tipo_pagamento, 'pix') !== false)
                                                <i class="ri-qr-code-line text-info"></i>
                                            @elseif(stripos($item->tipo_pagamento, 'cart') !== false)
                                                <i class="ri-bank-card-line text-primary"></i>
                                            @elseif(stripos($item->tipo_pagamento, 'dinheiro') !== false)
                                                <i class="ri-money-dollar-box-line text-success"></i>
                                            @else
                                                <i class="ri-wallet-3-line text-secondary"></i>
                                            @endif
                                            {{ $item->tipo_pagamento ?? 'N/D' }}
                                        </span>
                                    </td>

                                    <!-- Data de Cadastro -->
                                    <td>
                                        <span class="text-muted fs-12 d-block">
                                            {{ __data_pt($item->created_at, 1) }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($st == 'recebido' || $st == 'pago' || $st == 'aprovado')
                                            <span class="badge-modern badge-status-recebido">
                                                <i class="ri-checkbox-circle-fill text-success fs-13"></i> RECEBIDO
                                            </span>
                                        @elseif($st == 'pendente' || $st == 'aguardando')
                                            <span class="badge-modern badge-status-pendente">
                                                <i class="ri-time-fill text-warning fs-13"></i> PENDENTE
                                            </span>
                                        @elseif($st == 'cancelado' || $st == 'rejeitado')
                                            <span class="badge-modern badge-status-cancelado">
                                                <i class="ri-close-circle-fill text-danger fs-13"></i> CANCELADO
                                            </span>
                                        @else
                                            <span class="badge-modern badge-pagamento">
                                                <i class="ri-information-line fs-13"></i> {{ strtoupper($item->status_pagamento) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Ações -->
                                    <td class="text-end">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <!-- Botão de Edição Rápida via Modal -->
                                            <button type="button" 
                                                class="btn-action-edit btn-edit-financeiro" 
                                                title="Editar Fatura / Alterar Status de Pagamento"
                                                data-id="{{ $item->id }}"
                                                data-empresa="{{ $item->empresa ? ($item->empresa->nome ?? $item->empresa->razao_social ?? $item->empresa->info) : 'Empresa não vinculada' }}"
                                                data-empresa-doc="{{ $item->empresa ? ($item->empresa->cpf_cnpj ?? $item->empresa->cnpj ?? '') : '' }}"
                                                data-plano="{{ $item->plano ? $item->plano->nome : 'Plano Removido' }}"
                                                data-valor="{{ __moeda($item->valor) }}"
                                                data-tipo-pagamento="{{ $item->tipo_pagamento }}"
                                                data-status-pagamento="{{ $item->status_pagamento }}"
                                                data-action="{{ route('financeiro-plano.update', $item->id) }}"
                                            >
                                                <i class="ri-edit-2-line"></i> Editar
                                            </button>

                                            <!-- Botão de Excluir -->
                                            <form action="{{ route('financeiro-plano.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="btn-action-delete btn-delete" title="Excluir Registro">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="ri-inbox-archive-line fs-36 text-muted mb-2 d-block opacity-50"></i>
                                            <h6 class="text-muted fw-normal">Nenhum registro financeiro de plano encontrado.</h6>
                                            <p class="text-muted fs-12 mb-0">Novas faturas aparecem automaticamente ao atribuir planos às empresas.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                        <div>
                            <span class="text-muted fs-12 fw-medium">
                                Exibindo {{ $data->count() }} de {{ $data->total() }} faturas cadastradas
                            </span>
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

<!-- ═══════════════════════════════════════════════════════════════
     MODAL DE EDIÇÃO RÁPIDA DA FATURA FINANCEIRA
     ═══════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modal-edit-financeiro" tabindex="-1" aria-labelledby="modalFinanceiroLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow-lg rounded-4" id="form-edit-financeiro" method="post" action="">
            @csrf
            @method('PUT')
            
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center">
                        <i class="ri-edit-2-line fs-18"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold text-dark fs-16 mb-0" id="modalFinanceiroLabel">
                            Editar Fatura de Plano
                        </h5>
                        <small class="text-muted fs-11">Atualize o status da fatura, valor ou forma de pagamento</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Informações da Empresa -->
                <div class="modal-banner-empresa mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="company-avatar-box">
                            <i class="ri-building-line"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-11 text-uppercase fw-bold">Empresa</span>
                            <h6 class="fw-bold text-dark mb-0 fs-14" id="fin-empresa-nome">--</h6>
                            <span class="text-muted fs-11" id="fin-empresa-doc">--</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="fs-12 text-muted">Plano: <strong id="fin-plano-nome" class="text-dark">--</strong></span>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Valor -->
                    <div class="col-12">
                        <label class="form-label required">
                            <i class="ri-money-dollar-circle-line text-primary me-1"></i> Valor da Fatura (R$)
                        </label>
                        <input type="tel" name="valor" id="fin-valor" class="form-control moeda" placeholder="0,00" required>
                    </div>

                    <!-- Status de Pagamento -->
                    <div class="col-12">
                        <label class="form-label required">
                            <i class="ri-checkbox-circle-line text-primary me-1"></i> Status de Pagamento
                        </label>
                        <select name="status_pagamento" id="fin-status-pagamento" class="form-select" required>
                            @foreach(\App\Models\FinanceiroPlano::statusDePagamentos() as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted fs-11">Mude para <strong>Recebido</strong> para confirmar a quitação.</small>
                    </div>

                    <!-- Forma de Pagamento -->
                    <div class="col-12">
                        <label class="form-label required">
                            <i class="ri-bank-card-line text-primary me-1"></i> Forma de Pagamento
                        </label>
                        <select name="tipo_pagamento" id="fin-tipo-pagamento" class="form-select" required>
                            @foreach(\App\Models\Plano::formasPagamento() as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                    <i class="ri-check-line me-1"></i> Salvar Fatura
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        // Abre o modal de edição rápida ao clicar no botão Editar
        $(document).on('click', '.btn-edit-financeiro', function() {
            let btn = $(this);
            let action = btn.data('action');
            let empresa = btn.data('empresa');
            let doc = btn.data('empresa-doc');
            let plano = btn.data('plano');
            let valor = btn.data('valor');
            let tipoPagto = btn.data('tipo-pagamento');
            let statusPagto = btn.data('status-pagamento');

            $('#form-edit-financeiro').attr('action', action);
            $('#fin-empresa-nome').text(empresa);
            $('#fin-empresa-doc').html(doc ? '<i class="ri-fingerprint-line me-1"></i>' + doc : '');
            $('#fin-plano-nome').text(plano);
            $('#fin-valor').val(valor);
            $('#fin-tipo-pagamento').val(tipoPagto);
            $('#fin-status-pagamento').val(statusPagto);

            let modalElement = document.getElementById('modal-edit-financeiro');
            let modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalElement);
            }
            modalInstance.show();
        });
    });
</script>
@endsection
