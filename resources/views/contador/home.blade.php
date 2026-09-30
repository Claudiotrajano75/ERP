@section('css')
<style>
    /* ─── Cards de Estatística ─── */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #e9ecf3;
        box-shadow: 0 1px 3px rgba(16,24,40,.04);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16,24,40,.08);
    }
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
    .stat-icon-indigo { background: #eef2ff; color: #4338ca; }
    .stat-icon-green  { background: #ecfdf5; color: #047857; }
    .stat-icon-amber  { background: #fffbeb; color: #b45309; }
    .stat-icon-blue   { background: #eff6ff; color: #1d4ed8; }
    .stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        margin-bottom: 2px;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    /* ─── Tabela ─── */
    .tb-wrap {
        border-radius: 14px;
        border: 1px solid #eef0f5;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 3px rgba(16,24,40,.04);
    }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 1px solid #eef0f5;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .tb-wrap tbody td {
        padding: 13px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .tb-wrap tbody tr:last-child td { border-bottom: 0; }
    .tb-wrap tbody tr:hover td { background-color: #fafbfd; }

    /* Linha da empresa ativa selecionada */
    .tr-active-empresa {
        background-color: #f5f8ff !important;
    }

    /* ─── Badges (Pills) ─── */
    .pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .pill-green  { background: #ecfdf5; color: #047857; }
    .pill-red    { background: #fef2f2; color: #b91c1c; }
    .pill-indigo { background: #eef2ff; color: #4338ca; }
    .pill-amber  { background: #fffbeb; color: #b45309; }
    .pill-muted  { background: #f1f5f9; color: #475569; }

    .company-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 13.5px;
    }

    /* ─── Ações em Linha ─── */
    .act-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none !important;
    }
    .act-btn-select {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }
    .act-btn-select:hover {
        background: #d1fae5;
        color: #065f46;
        transform: translateY(-1px);
    }
    .act-btn-assigned {
        background: #eef2ff;
        color: #4338ca;
        border-color: #c7d2fe;
        cursor: default;
    }
    .act-btn-assign {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .act-btn-assign:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* ─── Botões do Header ─── */
    .dash-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        cursor: pointer;
        border: 0;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .dash-btn:hover { transform: translateY(-1px); }
    .dash-btn-primary {
        background: #4f46e5 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(79,70,229,.35) !important;
    }
    .dash-btn-primary:hover {
        background: #4338ca !important;
        color: #fff !important;
        box-shadow: 0 6px 16px rgba(79,70,229,.45) !important;
    }

    /* ─── Empty State ─── */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
    }
    .empty-state-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 12px;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm modulo-form-card">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-briefcase-4-line"></i> Painel do Contador
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Gerencie as empresas vinculadas, planos contratados e selecione a empresa para visualizar movimentações fiscais.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(sizeof(__empresasDoContador()) < (Auth::user()->empresa->empresa->limite_cadastro_empresas ?? 999))
                        <a href="{{ route('contador.empresa-create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Nova Empresa
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS (ESTATÍSTICAS) ═══ --}}
                @php
                    $empresas = __empresasDoContador();
                    $totalEmpresas = sizeof($empresas);
                    $limiteEmpresas = Auth::user()->empresa->empresa->limite_cadastro_empresas ?? 0;
                    $empresaSelecionadaId = Auth::user()->empresa->empresa->empresa_selecionada ?? null;
                    $empresasAtivas = 0;
                    $empresasComPlano = 0;
                    foreach($empresas as $itemEmp) {
                        if($itemEmp->empresa && $itemEmp->empresa->status) $empresasAtivas++;
                        if($itemEmp->empresa && $itemEmp->empresa->plano) $empresasComPlano++;
                    }
                @endphp

                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-indigo">
                                <i class="ri-building-4-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Empresas Cadastradas</div>
                                <div class="stat-value">{{ $totalEmpresas }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-amber">
                                <i class="ri-pie-chart-2-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Limite Permitido</div>
                                <div class="stat-value">{{ $limiteEmpresas }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-green">
                                <i class="ri-checkbox-circle-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Empresas Ativas</div>
                                <div class="stat-value">{{ $empresasAtivas }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="ri-vip-diamond-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Com Plano Atribuído</div>
                                <div class="stat-value">{{ $empresasComPlano }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ TABELA DE EMPRESAS ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Razão Social</th>
                                    <th>CPF / CNPJ</th>
                                    <th>Status</th>
                                    <th>Data de Cadastro</th>
                                    <th>Plano Atual</th>
                                    <th class="text-end" width="160">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empresas as $e)
                                @php
                                    $isSelected = ($empresaSelecionadaId == $e->empresa->id);
                                @endphp
                                <tr class="{{ $isSelected ? 'tr-active-empresa' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="company-title">{{ $e->empresa->nome }}</span>
                                            @if($isSelected)
                                            <span class="pill pill-indigo"><i class="ri-check-double-line"></i> Em Acesso</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="pill pill-muted"><i class="ri-file-text-line"></i> {{ $e->empresa->cpf_cnpj }}</span>
                                    </td>
                                    <td>
                                        @if($e->empresa->status)
                                        <span class="pill pill-green"><i class="ri-checkbox-circle-line"></i> Ativa</span>
                                        @else
                                        <span class="pill pill-red"><i class="ri-close-circle-line"></i> Inativa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ __data_pt($e->empresa->created_at) }}</span>
                                    </td>
                                    <td>
                                        @if($e->empresa->plano)
                                        <span class="pill pill-indigo"><i class="ri-vip-diamond-line"></i> {{ $e->empresa->plano->plano->nome }}</span>
                                        @else
                                            @if(!$e->__planoPendente())
                                            <a class="act-btn act-btn-assign" href="{{ route('contador-empresa.plano', [$e->empresa->id]) }}" title="Atribuir Plano">
                                                <i class="ri-add-line"></i> Atribuir Plano
                                            </a>
                                            @else
                                            <span class="pill pill-amber"><i class="ri-time-line"></i> Aguardando Liberação</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(!$isSelected)
                                        <a class="act-btn act-btn-select" href="{{ route('contador.set-empresa', [$e->empresa->id]) }}" title="Acessar dados desta empresa">
                                            <i class="ri-arrow-right-circle-line"></i> Acessar
                                        </a>
                                        @else
                                        <span class="act-btn act-btn-assigned">
                                            <i class="ri-checkbox-circle-fill text-primary"></i> Selecionada
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="ri-building-line"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">Nenhuma empresa vinculada ao contador</h6>
                                            <p class="text-muted fs-13 mb-0">Cadastre novas empresas para começar a gerenciar.</p>
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