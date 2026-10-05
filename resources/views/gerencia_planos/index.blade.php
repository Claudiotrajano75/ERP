@extends('layouts.app', ['title' => 'Gerenciar Planos'])

@section('css')
<style type="text/css">
    /* ═══════════════════════════════════════════════════════════════
       ESTILOS MODERNOS - GESTÃO E ATRIBUIÇÃO DE PLANOS
       ═══════════════════════════════════════════════════════════════ */
    .planos-container {
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
        color: #c7d2fe !important;
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

    .kpi-total::before { background: #3b82f6; }
    .kpi-vigentes::before { background: #10b981; }
    .kpi-expirados::before { background: #ef4444; }
    .kpi-valor::before { background: #6366f1; }

    .kpi-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .kpi-total .kpi-icon-wrap { background: #eff6ff; color: #2563eb; }
    .kpi-vigentes .kpi-icon-wrap { background: #ecfdf5; color: #059669; }
    .kpi-expirados .kpi-icon-wrap { background: #fef2f2; color: #dc2626; }
    .kpi-valor .kpi-icon-wrap { background: #eef2ff; color: #4f46e5; }

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
        color: #0f172a;
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
        padding: 16px 20px;
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

    .badge-vigente {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-expirado {
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

    .badge-alerta-tempo {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 4px;
        font-weight: 500;
    }

    .alerta-venceu {
        background: #fee2e2;
        color: #b91c1c;
    }

    .alerta-hoje {
        background: #ffedd5;
        color: #c2410c;
        animation: pulseWarning 1.8s infinite;
    }

    .alerta-breve {
        background: #fef3c7;
        color: #92400e;
    }

    .alerta-ok {
        background: #f0fdf4;
        color: #15803d;
    }

    @keyframes pulseWarning {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
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

    /* Modal de Edição & Atalhos */
    .modal-banner-empresa {
        background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
    }

    .btn-atalho-prazo {
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 12px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }

    .btn-atalho-urgente {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #6ee7b7;
    }

    .btn-atalho-urgente:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
        transform: translateY(-1px);
    }

    .btn-atalho-secundario {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-atalho-secundario:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-1px);
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
</style>
@endsection

@section('content')
<div class="mt-3 planos-container">
    <div class="row">
        <div class="col-12">
            <div class="card main-card">
                <!-- Cabeçalho com Gradiente Moderno -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h4 class="modulo-title text-white">
                                <i class="ri-exchange-funds-line"></i> Atribuição e Gestão de Planos
                            </h4>
                            <p class="modulo-subtitle">
                                Controle de assinaturas ativas, prazos manuais de expiração e liberação rápida para empresas.
                            </p>
                        </div>
                        <div>
                            <button class="btn btn-success px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-cad">
                                <i class="ri-add-circle-fill fs-15 me-1"></i> Atribuir Novo Plano
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ KPI CARDS MODERNOS ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-total">
                                <div>
                                    <div class="kpi-title">Total de Atribuições</div>
                                    <div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
                                    <p class="kpi-desc">Contratos registrados</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-article-line"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-vigentes">
                                <div>
                                    <div class="kpi-title">Planos Vigentes</div>
                                    <div class="kpi-value text-success">{{ $stats['ativas'] ?? 0 }}</div>
                                    <p class="kpi-desc">Dentro do prazo de acesso</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-expirados">
                                <div>
                                    <div class="kpi-title">Planos Expirados</div>
                                    <div class="kpi-value text-danger">{{ $stats['expiradas'] ?? 0 }}</div>
                                    <p class="kpi-desc">Bloqueados / Renovação</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-alarm-warning-line"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="kpi-card kpi-valor">
                                <div>
                                    <div class="kpi-title">Valor Total Contratado</div>
                                    <div class="kpi-value text-primary">R$ {{ __moeda($stats['valor_total'] ?? 0) }}</div>
                                    <p class="kpi-desc">Volume total ativo/gerenciado</p>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ FILTRO DE PESQUISA ═══ -->
                    <div class="filter-box mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row align-items-end g-3">
                            <div class="col-lg-8 col-md-7 col-12">
                                <label class="form-label text-muted fw-bold fs-12 mb-1">
                                    <i class="ri-building-line text-primary me-1"></i> Filtrar por Empresa
                                </label>
                                {!!Form::select('empresa', '', $empresa ? [$empresa->id => $empresa->info] : [])
                                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-empresa_filtro_id'])
                                !!}
                            </div>
                            <div class="col-lg-4 col-md-5 col-12 d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Filtrar
                                </button>
                                <a id="clear-filter" class="btn btn-outline-secondary px-3" href="{{ route('gerenciar-planos.index') }}" title="Limpar Filtro">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA DE ATRIBUIÇÕES MODERNIZADA ═══ -->
                    <div class="table-container-modern shadow-sm">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Plano</th>
                                    <th>Valor</th>
                                    <th>Forma Pagto</th>
                                    <th>Data Cadastro</th>
                                    <th>Data Expiração</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 130px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                @php
                                    $isVigente = strtotime($item->data_expiracao) >= strtotime(date('Y-m-d'));
                                    $hoje = \Carbon\Carbon::today();
                                    $dataExp = \Carbon\Carbon::parse($item->data_expiracao)->startOfDay();
                                    $diasRestantes = $hoje->diffInDays($dataExp, false);
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
                                        <span class="fw-bold text-dark fs-13">
                                            R$ {{ __moeda($item->valor) }}
                                        </span>
                                    </td>

                                    <!-- Forma de Pagamento -->
                                    <td>
                                        <span class="badge-modern badge-pagamento">
                                            @if(stripos($item->forma_pagamento, 'pix') !== false)
                                                <i class="ri-qr-code-line text-info"></i>
                                            @elseif(stripos($item->forma_pagamento, 'cart') !== false)
                                                <i class="ri-bank-card-line text-primary"></i>
                                            @elseif(stripos($item->forma_pagamento, 'dinheiro') !== false)
                                                <i class="ri-money-dollar-box-line text-success"></i>
                                            @else
                                                <i class="ri-wallet-3-line text-secondary"></i>
                                            @endif
                                            {{ $item->forma_pagamento ?? 'N/D' }}
                                        </span>
                                    </td>

                                    <!-- Data de Cadastro -->
                                    <td>
                                        <span class="text-muted fs-12 d-block">
                                            {{ __data_pt($item->created_at, 1) }}
                                        </span>
                                    </td>

                                    <!-- Data de Expiração com Indicador Inteligente -->
                                    <td>
                                        <div class="fw-semibold text-dark fs-13">
                                            <i class="ri-calendar-line text-muted me-1"></i>
                                            {{ __data_pt($item->data_expiracao, 0) }}
                                        </div>
                                        <div>
                                            @if($diasRestantes < 0)
                                                <span class="badge-alerta-tempo alerta-venceu">
                                                    <i class="ri-alert-line me-1"></i>Venceu há {{ abs($diasRestantes) }} {{ abs($diasRestantes) == 1 ? 'dia' : 'dias' }}
                                                </span>
                                            @elseif($diasRestantes == 0)
                                                <span class="badge-alerta-tempo alerta-hoje">
                                                    <i class="ri-timer-flash-line me-1"></i>Vence hoje!
                                                </span>
                                            @elseif($diasRestantes <= 5)
                                                <span class="badge-alerta-tempo alerta-breve">
                                                    <i class="ri-time-line me-1"></i>Expira em {{ $diasRestantes }} {{ $diasRestantes == 1 ? 'dia' : 'dias' }}
                                                </span>
                                            @else
                                                <span class="badge-alerta-tempo alerta-ok">
                                                    <i class="ri-check-line me-1"></i>{{ $diasRestantes }} dias restantes
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($isVigente)
                                            <span class="badge-modern badge-vigente">
                                                <i class="ri-checkbox-circle-fill text-success fs-13"></i> Vigente
                                            </span>
                                        @else
                                            <span class="badge-modern badge-expirado">
                                                <i class="ri-close-circle-fill text-danger fs-13"></i> Expirado
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Ações (Editar & Excluir) -->
                                    <td class="text-end">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <!-- Botão de Editar e Acesso Total -->
                                            <button type="button" 
                                                class="btn-action-edit btn-edit-plano" 
                                                title="Acesso Total: Editar Plano e Data de Expiração"
                                                data-id="{{ $item->id }}"
                                                data-empresa-nome="{{ $item->empresa ? ($item->empresa->nome ?? $item->empresa->razao_social ?? $item->empresa->info) : 'Empresa não vinculada' }}"
                                                data-empresa-doc="{{ $item->empresa ? ($item->empresa->cpf_cnpj ?? $item->empresa->cnpj ?? '') : '' }}"
                                                data-plano-id="{{ $item->plano_id }}"
                                                data-plano-nome="{{ $item->plano ? $item->plano->nome : '' }}"
                                                data-valor="{{ __moeda($item->valor) }}"
                                                data-forma-pagamento="{{ $item->forma_pagamento }}"
                                                data-data-expiracao="{{ \Carbon\Carbon::parse($item->data_expiracao)->format('Y-m-d') }}"
                                                data-data-expiracao-pt="{{ \Carbon\Carbon::parse($item->data_expiracao)->format('d/m/Y') }}"
                                                data-status-pagamento="{{ $item->financeiro ? $item->financeiro->status_pagamento : 'recebido' }}"
                                                data-status="{{ $isVigente ? 'vigente' : 'expirado' }}"
                                                data-action="{{ route('gerenciar-planos.update', $item->id) }}"
                                            >
                                                <i class="ri-edit-2-line"></i> Editar
                                            </button>

                                            <!-- Botão de Excluir -->
                                            <form action="{{ route('gerenciar-planos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="btn-action-delete btn-delete" title="Excluir Atribuição">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="ri-inbox-archive-line fs-36 text-muted mb-2 d-block opacity-50"></i>
                                            <h6 class="text-muted fw-normal">Nenhuma atribuição de plano localizada.</h6>
                                            <p class="text-muted fs-12 mb-0">Utilize o botão "Atribuir Novo Plano" acima para conceder plano a uma empresa.</p>
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
                                Exibindo {{ $data->count() }} de {{ $data->total() }} registros cadastrados
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
     MODAL DE CADASTRO / ATRIBUIÇÃO DE PLANO
     ═══════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modal-cad" tabindex="-1" aria-labelledby="modalCadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content border-0 shadow-lg rounded-4" method="post" action="{{ route('gerenciar-planos.store') }}">
            @csrf
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center">
                        <i class="ri-add-circle-line fs-18"></i>
                    </span>
                    <h5 class="modal-title fw-bold text-dark fs-16 mb-0" id="modalCadLabel">
                        Atribuir Plano à Empresa
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-building-line text-primary me-1"></i> Empresa</label>
                        {!!Form::select('empresa_atribuir', '')
                        ->required()
                        ->attrs(['class' => 'select2 empresa', 'id' => 'inp-empresa_atribuir'])
                        !!}
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-vip-diamond-line text-primary me-1"></i> Plano</label>
                        <select required id="plano" name="plano_id" class="form-select select2">
                            <option value="">Selecione o plano</option>
                            @foreach($planos as $p)
                            <option value="{{ $p->id }}" data-valor="{{ $p->valor }}">{{ $p->nome }} - R$ {{ __moeda($p->valor)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-bank-card-line text-primary me-1"></i> Forma de Pagamento</label>
                        {!!Form::select('forma_pagamento', '', \App\Models\Plano::formasPagamento())
                        ->required()
                        ->attrs(['class' => 'select2 form-select'])
                        !!}
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-money-dollar-circle-line text-primary me-1"></i> Valor</label>
                        {!!Form::tel('valor', '')
                        ->required()
                        ->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor', 'placeholder' => '0,00'])
                        !!}
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-checkbox-circle-line text-primary me-1"></i> Status de Pagamento</label>
                        {!!Form::select('status_pagamento', '', \App\Models\FinanceiroPlano::statusDePagamentos())
                        ->required()
                        ->attrs(['class' => 'select2 form-select'])
                        ->value('recebido')
                        !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success px-4 fw-semibold">
                    <i class="ri-save-line me-1"></i> Salvar Atribuição
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL DE EDIÇÃO - ACESSO TOTAL & GESTÃO MANUAL DE EXPIRAÇÃO
     ═══════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content border-0 shadow-lg rounded-4" id="form-edit-plano" method="post" action="">
            @csrf
            @method('PUT')
            
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center">
                        <i class="ri-edit-2-line fs-18"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold text-dark fs-16 mb-0" id="modalEditLabel">
                            Acesso Total: Editar Plano e Expiração
                        </h5>
                        <small class="text-muted fs-11">Altere manualmente o prazo de vigência ou valores sem obrigatoriedade de novo pagamento</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Informações da Empresa Atual -->
                <div class="modal-banner-empresa mb-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="company-avatar-box">
                                <i class="ri-building-line"></i>
                            </div>
                            <div>
                                <span class="text-muted fs-11 text-uppercase fw-bold">Empresa Selecionada</span>
                                <h6 class="fw-bold text-dark mb-0 fs-14" id="edit-empresa-info">--</h6>
                                <span class="text-muted fs-11" id="edit-empresa-doc">--</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted fs-11 d-block">Status de Acesso</span>
                            <div id="edit-badge-status">--</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Seletor de Plano -->
                    <div class="col-md-6 col-12">
                        <label class="form-label required">
                            <i class="ri-vip-diamond-line text-primary me-1"></i> Plano Contratado
                        </label>
                        <select required id="edit-plano-id" name="plano_id" class="form-select">
                            <option value="">Selecione o plano</option>
                            @foreach($planos as $p)
                            <option value="{{ $p->id }}" data-valor="{{ $p->valor }}">{{ $p->nome }} - R$ {{ __moeda($p->valor)}}</option>
                            @endforeach
                        </select>
                        <small class="text-muted fs-11">Altere o plano se desejar fazer upgrade ou downgrade imediato.</small>
                    </div>

                    <!-- Valor -->
                    <div class="col-md-6 col-12">
                        <label class="form-label required">
                            <i class="ri-money-dollar-circle-line text-primary me-1"></i> Valor Cobrado (R$)
                        </label>
                        <input type="tel" name="valor" id="edit-valor" class="form-control moeda" placeholder="0,00" required>
                        <small class="text-muted fs-11">Valor registrado para a empresa neste contrato.</small>
                    </div>

                    <!-- DATA DE EXPIRAÇÃO & ATALHOS RÁPIDOS (DESTAQUE MÁXIMO) -->
                    <div class="col-12">
                        <div class="p-3 border rounded-3 bg-light-subtle">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <label class="form-label fw-bold text-dark mb-0 required">
                                    <i class="ri-calendar-check-line text-success me-1"></i> Nova Data de Expiração (Manual)
                                </label>
                                <span class="fs-12 text-muted">
                                    Data cadastrada: <strong id="edit-data-atual-label" class="text-dark">--</strong>
                                </span>
                            </div>

                            <div class="row g-2 align-items-center">
                                <div class="col-md-5 col-12">
                                    <input type="date" name="data_expiracao" id="edit-data-expiracao" class="form-control fw-bold fs-14" required>
                                </div>
                                <div class="col-md-7 col-12">
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <span class="fs-11 fw-bold text-muted me-1">Liberar prazo rápido:</span>
                                        <!-- Botão +3 dias (Requisitado expressamente pelo cliente!) -->
                                        <button type="button" class="btn-atalho-prazo btn-atalho-urgente btn-add-dias" data-dias="3" title="Dar mais 3 dias de acesso imediato para o cliente">
                                            <i class="ri-flashlight-line"></i> +3 Dias (Imediato)
                                        </button>
                                        <button type="button" class="btn-atalho-prazo btn-atalho-secundario btn-add-dias" data-dias="7" title="Adicionar 7 dias">
                                            +7 Dias
                                        </button>
                                        <button type="button" class="btn-atalho-prazo btn-atalho-secundario btn-add-dias" data-dias="15" title="Adicionar 15 dias">
                                            +15 Dias
                                        </button>
                                        <button type="button" class="btn-atalho-prazo btn-atalho-secundario btn-add-dias" data-dias="30" title="Adicionar 30 dias (1 Mês)">
                                            +30 Dias
                                        </button>
                                        <button type="button" class="btn-atalho-prazo btn-atalho-secundario btn-add-dias" data-dias="365" title="Adicionar 1 ano">
                                            +1 Ano
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="edit-aviso-dias" class="mt-2 fs-12 fw-medium" style="display: none;"></div>
                            <div class="text-muted fs-11 mt-1">
                                <i class="ri-information-line me-1 text-primary"></i>Você pode digitar qualquer data manualmente ou clicar nos botões de atalho rápido para prorrogar o acesso instantaneamente.
                            </div>
                        </div>
                    </div>

                    <!-- Forma de Pagamento -->
                    <div class="col-md-6 col-12">
                        <label class="form-label required">
                            <i class="ri-bank-card-line text-primary me-1"></i> Forma de Pagamento
                        </label>
                        <select name="forma_pagamento" id="edit-forma-pagamento" class="form-select" required>
                            @foreach(\App\Models\Plano::formasPagamento() as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status de Pagamento -->
                    <div class="col-md-6 col-12">
                        <label class="form-label required">
                            <i class="ri-checkbox-circle-line text-primary me-1"></i> Status do Pagamento
                        </label>
                        <select name="status_pagamento" id="edit-status-pagamento" class="form-select" required>
                            @foreach(\App\Models\FinanceiroPlano::statusDePagamentos() as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted fs-11">Controle se a fatura vinculada foi marcada como recebida ou pendente.</small>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                    <i class="ri-check-line me-1"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        // Inicialização do Select2 de Empresa no modal de cadastro
        setTimeout(() => {
            $("#modal-cad .empresa").select2({
                minimumInputLength: 2,
                language: "pt-BR",
                placeholder: "Digite para buscar a empresa",
                width: "100%",
                dropdownParent: $('#modal-cad'),
                ajax: {
                    cache: true,
                    url: path_url + "api/empresas/find-all",
                    dataType: "json",
                    data: function (params) {
                        return { pesquisa: params.term };
                    },
                    processResults: function (response) {
                        var results = [];
                        $.each(response, function (i, v) {
                            results.push({ id: v.id, text: v.info, value: v.id });
                        });
                        return { results: results };
                    },
                },
            });
        }, 200);

        // Atualização de valor ao mudar plano no modal de cadastro
        $(document).on("change", "#plano", function () {
            if($(this).val()){
                let empresa_id = $('#inp-empresa_atribuir').val();
                $.get(path_url + 'api/planos/find', {empresa_id: empresa_id, plano_id: $(this).val()})
                .done((res) => {
                    $('#inp-valor').val(convertFloatToMoeda(res.valor));
                })
                .fail((err) => {
                    console.log(err);
                });
            }else{
                $('#inp-valor').val(convertFloatToMoeda(0));
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // CONTROLE DO MODAL DE EDIÇÃO (ACESSO TOTAL DO ADMIN)
        // ═══════════════════════════════════════════════════════════════
        $(document).on('click', '.btn-edit-plano', function() {
            let btn = $(this);
            let action = btn.data('action');
            let empresaNome = btn.data('empresa-nome');
            let empresaDoc = btn.data('empresa-doc');
            let planoId = btn.data('plano-id');
            let valor = btn.data('valor');
            let formaPagamento = btn.data('forma-pagamento');
            let dataExp = btn.data('data-expiracao');
            let dataExpPt = btn.data('data-expiracao-pt');
            let statusPagamento = btn.data('status-pagamento');
            let status = btn.data('status');

            $('#form-edit-plano').attr('action', action);
            $('#edit-empresa-info').text(empresaNome);
            $('#edit-empresa-doc').html(empresaDoc ? '<i class="ri-fingerprint-line me-1"></i>' + empresaDoc : '');
            $('#edit-plano-id').val(planoId);
            $('#edit-valor').val(valor);
            $('#edit-forma-pagamento').val(formaPagamento);
            $('#edit-status-pagamento').val(statusPagamento || 'recebido');
            $('#edit-data-expiracao').val(dataExp);
            $('#edit-data-atual-label').text(dataExpPt);

            if (status === 'vigente') {
                $('#edit-badge-status').html('<span class="badge-modern badge-vigente"><i class="ri-checkbox-circle-fill text-success fs-12"></i> Vigente</span>');
            } else {
                $('#edit-badge-status').html('<span class="badge-modern badge-expirado"><i class="ri-close-circle-fill text-danger fs-12"></i> Expirado</span>');
            }

            $('#edit-aviso-dias').hide().empty();

            let modalElement = document.getElementById('modal-edit');
            let modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalElement);
            }
            modalInstance.show();
        });

        // Atualização de valor ao mudar plano no modal de edição (sugestão de valor)
        $(document).on("change", "#edit-plano-id", function () {
            let selected = $(this).find('option:selected');
            let valorSugerido = selected.data('valor');
            if (valorSugerido !== undefined && valorSugerido !== null) {
                $('#edit-valor').val(convertFloatToMoeda(valorSugerido));
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // ATALHOS RÁPIDOS DE DIAS (+3 DIAS, +7 DIAS, ETC.)
        // ═══════════════════════════════════════════════════════════════
        $(document).on('click', '.btn-add-dias', function() {
            let dias = parseInt($(this).data('dias'));
            let hoje = new Date();
            let dataBase = new Date();

            let valAtual = $('#edit-data-expiracao').val();
            if (valAtual) {
                let partes = valAtual.split('-');
                if (partes.length === 3) {
                    let dataExp = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
                    // Se a data de expiração ainda for futura, adiciona a partir dela; se já expirou, adiciona a partir de hoje
                    if (dataExp > hoje) {
                        dataBase = dataExp;
                    }
                }
            }

            dataBase.setDate(dataBase.getDate() + dias);

            let ano = dataBase.getFullYear();
            let mes = String(dataBase.getMonth() + 1).padStart(2, '0');
            let dia = String(dataBase.getDate()).padStart(2, '0');
            let novaDataYmd = `${ano}-${mes}-${dia}`;
            let novaDataPt = `${dia}/${mes}/${ano}`;

            $('#edit-data-expiracao').val(novaDataYmd).focus();

            $('#edit-aviso-dias').html(`
                <span class="text-success fw-bold d-inline-flex align-items-center gap-1">
                    <i class="ri-checkbox-circle-fill"></i> +${dias} dias aplicados com sucesso! Nova data de expiração: ${novaDataPt}
                </span>
            `).fadeIn();
        });
    });
</script>
@endsection
