@extends('layouts.app', ['title' => 'Vendas / NFe'])

@section('css')
<style>
/* =====================================================
   PAINEL DE VENDAS / NF-e — PADRÃO PREMIUM ERP
   ===================================================== */
/* ─── Cards de Estatísticas da Skill ─── */
.stat-card {
    border: 0;
    border-radius: 16px;
    padding: 18px 20px;
    height: 100%;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
}
.stat-card::after {
    content: '';
    position: absolute;
    top: -44px;
    right: -44px;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
}
.stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.28); }
.stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.28); }
.stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.28); }
.stat-rose   { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(220,38,38,.28); }
.stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
.stat-card .st-value { font-size: 24px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
.stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
.stat-card .st-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; }

/* ─── Filtro ─── */
.filter-wrap {
    background: #fff;
    border: 1px solid #e9ecf3;
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(16,24,40,.04);
    padding: 18px 20px;
    margin-bottom: 20px;
}
.filter-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.filter-title i {
    color: #4f46e5;
    margin-right: 6px;
}
.filter-wrap label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #64748b;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.filter-wrap label i {
    color: #94a3b8;
    font-size: 12px;
}
.filter-wrap .form-control,
.filter-wrap .form-select {
    height: 40px;
    border-radius: 10px;
    border: 1px solid #dcdce9;
    font-size: 13px;
    background: #fcfdfe;
}
.filter-wrap .form-control:focus,
.filter-wrap .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}
.filter-wrap .select2-container .select2-selection--single {
    height: 40px !important;
    line-height: 40px !important;
    border: 1px solid #dcdce9 !important;
    border-radius: 10px !important;
    background: #fcfdfe !important;
    font-size: 13px;
}
.filter-wrap .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
    color: #1f2937;
    padding-left: 12px;
}
.filter-wrap .select2-container .select2-selection--single .select2-selection__arrow {
    height: 38px !important;
}
.filter-wrap .select2-container .select2-selection--single .select2-selection__arrow b {
    display: none;
}
.filter-wrap .select2-container .select2-selection--single .select2-selection__clear {
    line-height: 38px !important;
    font-size: 16px !important;
    color: #94a3b8 !important;
    cursor: pointer !important;
    margin-right: 6px !important;
    font-weight: bold !important;
}
.filter-wrap .select2-container .select2-selection--single .select2-selection__clear:hover {
    color: #ef4444 !important;
}

/* ─── Tabela ─── */
.tb-wrap {
    border-radius: 14px;
    border: 1px solid #eef0f5;
    overflow: hidden;
    background: #fff;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.tb-wrap table {
    margin-bottom: 0;
}
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
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13px;
    color: #374151;
}
.tb-wrap tbody tr:hover {
    background: #f8faff;
}
.tb-wrap tbody tr:last-child td {
    border-bottom: none;
}

/* ─── Avatar do Cliente ─── */
.cf-avatar-cliente {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eef2ff;
    color: #4f46e5;
    font-weight: 700;
    font-size: 11.5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    letter-spacing: -0.5px;
    flex-shrink: 0;
}

/* ─── Badges (pills da skill) ─── */
.pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #f1f5f9; color: #64748b; }
.pill-role { background: #eef0ff; color: #4f46e5; }
.pill-amber { background: #fef3c7; color: #b45309; }
.pill-red { background: #fee2e2; color: #b91c1c; }

/* ─── Botão Dropdown Trigger Centralizado ─── */
.dropdown-action-menu {
    position: relative;
}
.btn-action-trigger {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    padding: 0;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn-action-trigger:hover,
.dropdown-action-menu.show .btn-action-trigger {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
}

.action-dropdown-card {
    min-width: 250px;
    border-radius: 12px !important;
    border: 1px solid rgba(0,0,0,0.06) !important;
    padding: 6px !important;
    background: #ffffff;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12), 0 4px 10px rgba(15, 23, 42, 0.05) !important;
    z-index: 1060;
}
.action-menu-item {
    display: flex !important;
    align-items: center;
    gap: 10px;
    padding: 7px 10px !important;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.15s ease;
    background: transparent;
    cursor: pointer;
}
.action-menu-item:hover {
    background-color: #f8fafc !important;
    transform: translateX(2px);
}
.action-item-icon {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.action-item-icon.icon-primary { background: #eff6ff; color: #2563eb; }
.action-item-icon.icon-info    { background: #f0f9ff; color: #0284c7; }
.action-item-icon.icon-purple  { background: #faf5ff; color: #7c3aed; }
.action-item-icon.icon-teal    { background: #f0fdfa; color: #0d9488; }
.action-item-icon.icon-success { background: #f0fdf4; color: #16a34a; }
.action-item-icon.icon-cyan    { background: #ecfeff; color: #0891b2; }
.action-item-icon.icon-warning { background: #fffbeb; color: #d97706; }
.action-item-icon.icon-danger  { background: #fef2f2; color: #dc2626; }
.action-item-content {
    display: flex;
    flex-direction: column;
    text-align: left;
    line-height: 1.2;
}
.action-item-title {
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
}
.action-item-desc {
    font-size: 10.5px;
    color: #94a3b8;
}

/* EMPTY STATE */
.modulo-empty {
    padding: 48px 20px;
    text-align: center;
}
.modulo-empty i {
    font-size: 44px;
    color: #cbd5e1;
    margin-bottom: 10px;
    display: block;
}
.modulo-empty p {
    color: #64748b;
    font-size: 13.5px;
    margin: 0;
}

        /* ─── Estilos Modais Premium NF-e ─── */
        .modal-nfe-header-red {
            background: #ef4444;
            padding: 16px 20px;
        }
        .modal-nfe-header-red .modal-title {
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }
        .modal-nfe-header-red .modal-subtitle {
            color: rgba(255,255,255,0.92);
            font-size: 11.5px;
            margin: 3px 0 0;
        }
        .modal-nfe-header-teal {
            background: #0d9488;
            padding: 16px 20px;
        }
        .modal-nfe-header-teal .modal-title {
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }
        .modal-nfe-header-teal .modal-subtitle {
            color: rgba(255,255,255,0.92);
            font-size: 11.5px;
            margin: 3px 0 0;
        }
        .modal-nfe-dados-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .modal-nfe-dados-card .card-label {
            font-size: 10.5px;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .modal-nfe-dados-card .card-val {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
        }
        .modal-nfe-dados-card .card-title {
            font-size: 11.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 12px;
            display: block;
        }
        .modal-alert-danger-soft {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .modal-alert-info-soft {
            background: #eef2ff;
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .modal-alert-yellow {
            background: #fefce8;
            border: 1px solid #fef08a;
            border-radius: 10px;
            padding: 14px 16px;
        }
        .modal-alert-yellow ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .modal-alert-yellow ul li {
            font-size: 11.5px;
            color: #a16207;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ─── Dropdown de Ações Moderno com Submenus ─── */
        .btn-action-trigger {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.2s ease;
    padding: 0;
}
.btn-action-trigger:hover, 
.btn-action-trigger:focus,
.dropdown-action-menu.show .btn-action-trigger {
    background: #f1f5f9;
    color: #3b82f6;
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.action-dropdown-card {
    min-width: 250px;
    border-radius: 14px !important;
    border: 1px solid rgba(0,0,0,0.06) !important;
    padding: 8px !important;
    background: #ffffff;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 12px rgba(15, 23, 42, 0.06) !important;
    z-index: 1060;
}

.action-menu-item {
    display: flex !important;
    align-items: center;
    gap: 12px;
    padding: 8px 10px !important;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.18s ease;
    background: transparent;
    cursor: pointer;
}
.action-menu-item:hover {
    background-color: #f8fafc !important;
    transform: translateX(2px);
}
.action-menu-item:active {
    background-color: #f1f5f9 !important;
}

.action-item-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    transition: all 0.18s ease;
}

.action-item-icon.icon-primary { background: #eff6ff; color: #2563eb; }
.action-item-icon.icon-info { background: #f0f9ff; color: #0284c7; }
.action-item-icon.icon-purple { background: #faf5ff; color: #7c3aed; }
.action-item-icon.icon-teal { background: #f0fdfa; color: #0d9488; }
.action-item-icon.icon-success { background: #f0fdf4; color: #16a34a; }
.action-item-icon.icon-cyan { background: #ecfeff; color: #0891b2; }
.action-item-icon.icon-warning { background: #fffbeb; color: #d97706; }
.action-item-icon.icon-danger { background: #fef2f2; color: #dc2626; }

.action-menu-item:hover .action-item-icon {
    transform: scale(1.08);
}

.action-item-content {
    display: flex;
    flex-direction: column;
    text-align: left;
    line-height: 1.2;
}
.action-item-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: -0.1px;
}
.action-item-desc {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
    font-weight: 400;
}

.action-menu-item:hover .action-item-title {
    color: #0f172a;
}

.action-menu-item.text-danger:hover {
    background-color: #fef2f2 !important;
}
.action-menu-item.text-danger:hover .action-item-title {
    color: #b91c1c !important;
}

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
.modal-header { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border: none; padding: 16px 20px; }
.modal-header .modal-title { color: #fff; font-weight: 700; font-size: 15px; letter-spacing: -0.2px; }
.modal-header .modal-title i { color: #a8b5ff; }
.modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: 0.8; }
.modal-body { padding: 24px 20px; background: #fafbfe; }
.modal-body label { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 6px; }
.modal-body .form-control { border-radius: 8px; border: 1px solid #e0e3eb; font-size: 13px; padding: 10px 14px; background: #fff; transition: all 0.15s ease; }
.modal-body .form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }
.modal-footer { background: #fff; border-top: 1px solid #f0f2f8; padding: 14px 20px; }
.modal-footer .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 18px; transition: all 0.2s ease; }
.modal-footer .btn-light { background: #f0f2f8; border-color: #f0f2f8; color: #5a5a7a; }
.modal-footer .btn-light:hover { background: #e4e7f0; border-color: #e4e7f0; color: #43435c; }

/* ─── Processing Overlay (SEFAZ Loading) ─── */
.pdv-processing-overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(15, 23, 42, 0.85) !important;
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
    z-index: 99999 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
    animation: pdvOverlayFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.pdv-processing-overlay.d-none {
    display: none !important;
}

@keyframes pdvOverlayFadeIn {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}

.pdv-processing-card {
    background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 40px rgba(59, 130, 246, 0.25);
    border-radius: 24px;
    padding: 40px 32px 32px 32px;
    width: 100%;
    max-width: 440px;
    text-align: center;
    color: #ffffff;
    position: relative;
    overflow: hidden;
}

.pdv-processing-icon-wrapper {
    position: relative;
    width: 90px;
    height: 90px;
    margin: 0 auto 24px auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pdv-processing-spinner-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 4px solid rgba(59, 130, 246, 0.18);
    border-top-color: #3b82f6;
    border-right-color: #60a5fa;
    animation: pdvSpinRing 0.85s linear infinite;
}

@keyframes pdvSpinRing {
    to { transform: rotate(360deg); }
}

.pdv-processing-icon-box {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    color: #ffffff;
    box-shadow: 0 0 25px rgba(37, 99, 235, 0.6);
    transition: all 0.3s ease;
}

.pdv-processing-icon-box.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 0 25px rgba(16, 185, 129, 0.6);
}

.pdv-processing-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.pdv-processing-msg {
    font-size: 0.925rem;
    color: #94a3b8;
    margin-bottom: 24px;
    line-height: 1.5;
}

.pdv-processing-progress {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
}

.pdv-processing-progress-bar {
    height: 100%;
    width: 40%;
    background: linear-gradient(90deg, #3b82f6, #60a5fa, #3b82f6);
    border-radius: 10px;
    animation: pdvProgressIndefinite 1.5s infinite ease-in-out;
}

@keyframes pdvProgressIndefinite {
    0% { transform: translateX(-100%); width: 30%; }
    50% { transform: translateX(100%); width: 60%; }
    100% { transform: translateX(300%); width: 30%; }
}

.pdv-processing-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.775rem;
    color: #cbd5e1;
    font-weight: 500;
}
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO PADRÃO DO ERP ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-text-line"></i>
                            Vendas & NF-e
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie vendas, orçamentos e emissões fiscais autorizadas na SEFAZ.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ url('/faturamento-nfe') }}" class="dash-btn dash-btn-light" title="Abrir Central Operacional de Faturamento"><i class="ri-dashboard-line"></i> Central</a>
                        @if(__isPlanoFiscal())
                        <button id="btn-consulta-sefaz" class="dash-btn dash-btn-light" type="button" title="Consultar status de serviço da SEFAZ"><i class="ri-pulse-line"></i> Status SEFAZ</button>
                        @endif
                        <a href="{{ route('nfe.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('nfe_create')
                        <a href="{{ route('nfe.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Nova Venda</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIs DA SKILL) ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de Vendas</div>
                                    <div class="st-value">{{ $data->total() }}</div>
                                    <div class="st-sub">registros no sistema</div>
                                </div>
                                <div class="st-icon"><i class="ri-file-text-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Valor na Página</div>
                                    <div class="st-value">R$ {{ __moeda($data->sum('total')) }}</div>
                                    <div class="st-sub">soma das notas listadas</div>
                                </div>
                                <div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Notas no Filtro</div>
                                    <div class="st-value">{{ $data->count() }}</div>
                                    <div class="st-sub">nesta página atual</div>
                                </div>
                                <div class="st-icon"><i class="ri-filter-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-rose">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Ambiente SEFAZ</div>
                                    <div class="st-value" style="font-size: 19px; line-height: 1.3; padding-top: 4px;">
                                        {{ (isset($data[0]) && $data[0]->ambiente == 2) ? 'Homologação' : 'Produção' }}
                                    </div>
                                    <div class="st-sub">emissor fiscal ativo</div>
                                </div>
                                <div class="st-icon"><i class="ri-shield-check-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($contigencia != null)
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-3 d-flex align-items-start rounded-3">
                    <i class="ri-error-warning-line me-2 fs-20 mt-0.5"></i>
                    <div>
                        <strong>Contingência Ativada!</strong>
                        Tipo: <strong>{{ $contigencia->tipo }}</strong> &mdash; Início: <strong>{{ __data_pt($contigencia->created_at) }}</strong>
                    </div>
                </div>
                @endif

                <!-- ═══ PAINEL DE FILTROS ═══ -->
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="filter-title mb-0">
                            <i class="ri-search-line"></i> Filtrar Vendas & NF-e
                        </h5>
                        <span class="text-muted fs-11">Refine por cliente, período ou estado fiscal</span>
                    </div>

                    <form method="get" action="{{ route('nfe.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-3 col-md-6 col-12">
                                <label><i class="ri-user-line"></i> Cliente / Fornecedor</label>
                                <select class="form-select" id="inp-cliente_id" name="cliente_id">
                                    <option value=""></option>
                                    @if(isset($cliente) && $cliente)
                                        <option value="{{ $cliente->id }}" selected>{{ $cliente->razao_social }} ({{ $cliente->cpf_cnpj }})</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Data Inicial -->
                            <div class="col-lg-2 col-md-3 col-6">
                                <label><i class="ri-calendar-line"></i> Data Inicial</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>

                            <!-- Data Final -->
                            <div class="col-lg-2 col-md-3 col-6">
                                <label><i class="ri-calendar-line"></i> Data Final</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>

                            @if(__isPlanoFiscal())
                            <!-- Estado NFe -->
                            <div class="col-lg-2 col-md-4 col-6">
                                <label><i class="ri-equalizer-line"></i> Estado NF-e</label>
                                <select name="estado" class="form-select">
                                    <option value="" @selected(request('estado') == '')>Todos os Estados</option>
                                    <option value="novo" @selected(request('estado') == 'novo')>Novas (Digitação)</option>
                                    <option value="rejeitado" @selected(request('estado') == 'rejeitado')>Rejeitadas</option>
                                    <option value="cancelado" @selected(request('estado') == 'cancelado')>Canceladas</option>
                                    <option value="aprovado" @selected(request('estado') == 'aprovado')>Aprovadas</option>
                                </select>
                            </div>

                            <!-- Tipo (Saída / Entrada) -->
                            <div class="col-lg-1 col-md-2 col-6">
                                <label><i class="ri-arrow-left-right-line"></i> Tipo</label>
                                <select name="tpNF" class="form-select">
                                    <option value="-" @selected(request('tpNF') == '-' || request('tpNF') == '')>Todos</option>
                                    <option value="1" @selected(request('tpNF') === '1')>Saída</option>
                                    <option value="0" @selected(request('tpNF') === '0')>Entrada</option>
                                </select>
                            </div>
                            @endif

                            @if(__countLocalAtivo() > 1)
                            <!-- Localização -->
                            <div class="col-lg-2 col-md-3 col-6">
                                <label><i class="ri-store-2-line"></i> Local</label>
                                <select name="local_id" class="select2 form-select">
                                    <option value="">Selecione</option>
                                    @foreach(__getLocaisAtivoUsuario() as $loc)
                                    <option value="{{ $loc->id }}" @selected(request('local_id') == $loc->id)>{{ $loc->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Botões de Ação -->
                            <div class="col-lg-2 col-md-3 col-12 ms-auto d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px; height:40px;">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3 d-flex align-items-center justify-content-center" href="{{ route('nfe.index') }}" title="Limpar Filtros" style="border-radius:10px; height:40px;">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ═══ TABELA PADRÃO PREMIUM ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive" style="min-height: 280px;">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">Ações</th>
                                    <th>Cliente / Fornecedor</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Usuário</th>
                                    <th>Nº Nota</th>
                                    <th>Valor Total</th>
                                    @if(__isPlanoFiscal())
                                    <th>Estado SEFAZ</th>
                                    <th>Ambiente</th>
                                    @endif
                                    <th>Cadastro</th>
                                    <th>Emissão</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    {{-- 1ª Coluna: Ações com Menu Dropdown Centralizado (50px) --}}
                                    <td class="text-center" style="width: 50px;">
                                        <form action="{{ route('nfe.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                            @method('delete')
                                            @csrf
                                            <div class="dropdown dropdown-action-menu d-inline-block">
                                                <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Mais Opções">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-start action-dropdown-card shadow-lg">
                                                
                                                {{-- Imprimir NFe (DANFE Modal) --}}
                                                @if($item->estado == 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="imprimir('{{$item->id}}', '{{$item->numero}}')">
                                                        <div class="action-item-icon icon-primary">
                                                            <i class="ri-printer-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Imprimir NFe</span>
                                                            <span class="action-item-desc">Escolher modelo DANFE</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Imprimir Pedido de Venda --}}
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.imprimirVenda', [$item->id]) }}">
                                                        <div class="action-item-icon icon-info">
                                                            <i class="ri-file-text-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Imprimir Pedido</span>
                                                            <span class="action-item-desc">Espelho do pedido de venda</span>
                                                        </div>
                                                    </a>
                                                </li>

                                                {{-- Transmitir ao SEFAZ --}}
                                                @if(($item->estado == 'novo' || $item->estado == 'rejeitado') && __isPlanoFiscal())
                                                    @can('nfe_transmitir')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="transmitir('{{$item->id}}')">
                                                            <div class="action-item-icon icon-success">
                                                                <i class="ri-send-plane-fill"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Transmitir SEFAZ</span>
                                                                <span class="action-item-desc">Enviar nota para autorização</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Editar Venda --}}
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('nfe_edit')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('nfe.edit', $item->id) }}">
                                                            <div class="action-item-icon icon-warning">
                                                                <i class="ri-pencil-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Editar Venda</span>
                                                                <span class="action-item-desc">Alterar produtos e valores</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Carta de Correção (CC-e) --}}
                                                @if($item->estado == 'aprovado')
                                                    @can('nfe_transmitir')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="javascript:void(0)"
                                                            onclick="corrigir(
                                                                '{{$item->id}}',
                                                                '{{$item->numero}}',
                                                                '{{$item->serie ?? 1}}',
                                                                '{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '--' }}',
                                                                '{{$item->cliente ? $item->cliente->razao_social : ($item->fornecedor ? $item->fornecedor->razao_social : '--')}}',
                                                                '{{$item->chave ?? '--'}}'
                                                            )">
                                                            <div class="action-item-icon icon-warning">
                                                                <i class="ri-file-warning-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Carta de Correção</span>
                                                                <span class="action-item-desc">Emitir CC-e para a NFe</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Consultar Protocolo SEFAZ --}}
                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado' || $item->estado == 'rejeitado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                        <div class="action-item-icon icon-teal">
                                                            <i class="ri-file-search-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Consultar SEFAZ</span>
                                                            <span class="action-item-desc">Verificar status do protocolo</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Detalhes do Retorno SEFAZ --}}
                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado' || $item->estado == 'rejeitado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                        <div class="action-item-icon icon-cyan">
                                                            <i class="ri-information-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Detalhes do Retorno</span>
                                                            <span class="action-item-desc">Chave de acesso e recibo</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Detalhes da Venda --}}
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfe.show', $item->id) }}">
                                                        <div class="action-item-icon icon-info">
                                                            <i class="ri-eye-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Detalhes da Venda</span>
                                                            <span class="action-item-desc">Ver dados completos</span>
                                                        </div>
                                                    </a>
                                                </li>

                                                {{-- Duplicar Venda --}}
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfe.duplicar', [$item->id]) }}">
                                                        <div class="action-item-icon icon-purple">
                                                            <i class="ri-file-copy-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Duplicar Venda</span>
                                                            <span class="action-item-desc">Criar cópia desta venda</span>
                                                        </div>
                                                    </a>
                                                </li>

                                                {{-- Enviar por E-mail (Aprovado) --}}
                                                @if($item->estado == 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="javascript:void(0)" onclick="enviarEmail('{{$item->id}}', '{{$item->numero}}')">
                                                        <div class="action-item-icon icon-primary">
                                                            <i class="ri-mail-send-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Enviar por E-mail</span>
                                                            <span class="action-item-desc">Enviar DANFE e XML</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Download XML (Aprovado) --}}
                                                @if($item->estado == 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="{{ route('nfe.download-xml', [$item->id]) }}">
                                                        <div class="action-item-icon icon-teal">
                                                            <i class="ri-download-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Download XML</span>
                                                            <span class="action-item-desc">Baixar XML aprovado</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Alterar Estado Fiscal --}}
                                                @if(__isPlanoFiscal())
                                                    @can('nfe_edit')
                                                    <li>
                                                        <a class="dropdown-item action-menu-item" href="{{ route('nfe.alterar-estado', $item->id) }}">
                                                            <div class="action-item-icon icon-purple">
                                                                <i class="ri-arrow-up-down-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title">Alterar Estado</span>
                                                                <span class="action-item-desc">Ajustar status fiscal</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- XML Temporário --}}
                                                @if(($item->estado == 'novo' || $item->estado == 'rejeitado') && __isPlanoFiscal())
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.xml-temp', $item->id) }}">
                                                        <div class="action-item-icon icon-purple">
                                                            <i class="ri-file-code-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">XML Temporário</span>
                                                            <span class="action-item-desc">Visualizar XML gerado</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- DANFE Temporária --}}
                                                @if($item->estado != 'aprovado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.danfe-temporaria', [$item->id]) }}">
                                                        <div class="action-item-icon icon-warning">
                                                            <i class="ri-printer-fill"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">DANFE Temporária</span>
                                                            <span class="action-item-desc">Pré-visualizar DANFE</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Imprimir Cancelamento --}}
                                                @if($item->estado == 'cancelado')
                                                <li>
                                                    <a class="dropdown-item action-menu-item" target="_blank" href="{{ route('nfe.imprimir-cancela', [$item->id]) }}">
                                                        <div class="action-item-icon icon-danger">
                                                            <i class="ri-printer-line"></i>
                                                        </div>
                                                        <div class="action-item-content">
                                                            <span class="action-item-title">Imprimir Cancelamento</span>
                                                            <span class="action-item-desc">Comprovante de cancelamento</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif

                                                {{-- Cancelar NFe SEFAZ --}}
                                                @if($item->estado == 'aprovado')
                                                    @can('nfe_transmitir')
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item action-menu-item text-danger w-100 border-0 bg-transparent"
                                                            onclick="cancelar(
                                                                '{{$item->id}}',
                                                                '{{$item->numero}}',
                                                                '{{$item->serie ?? 1}}',
                                                                '{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '--' }}',
                                                                '{{$item->cliente ? $item->cliente->razao_social : ($item->fornecedor ? $item->fornecedor->razao_social : '--')}}',
                                                                '{{$item->chave ?? '--'}}'
                                                            )">
                                                            <div class="action-item-icon icon-danger">
                                                                <i class="ri-close-circle-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title text-danger">Cancelar NFe</span>
                                                                <span class="action-item-desc text-danger-emphasis">Cancelar documento na SEFAZ</span>
                                                            </div>
                                                        </button>
                                                    </li>
                                                    @endcan
                                                @endif

                                                {{-- Excluir Venda --}}
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('nfe_delete')
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item action-menu-item btn-delete text-danger w-100 border-0 bg-transparent">
                                                            <div class="action-item-icon icon-danger">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </div>
                                                            <div class="action-item-content">
                                                                <span class="action-item-title text-danger">Excluir Venda</span>
                                                                <span class="action-item-desc text-danger-emphasis">Remover registro de venda</span>
                                                            </div>
                                                        </button>
                                                    </li>
                                                    @endcan
                                                @endif

                                                </ul>
                                                </div>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- 2ª Coluna: Cliente / Fornecedor (Identificação com Avatar) --}}
                                    <td>
                                        @php
                                            $cliNome = $item->cliente ? $item->cliente->razao_social : ($item->fornecedor ? $item->fornecedor->razao_social : '--');
                                            $cliDoc = $item->cliente ? $item->cliente->cpf_cnpj : ($item->fornecedor ? $item->fornecedor->cpf_cnpj : '');
                                            $iniciais = mb_strtoupper(mb_substr($cliNome, 0, 2));
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="cf-avatar-cliente">{{ $iniciais }}</div>
                                            <div class="d-flex flex-column" style="min-width: 140px;">
                                                <strong class="text-dark">{{ $cliNome }}</strong>
                                                <div class="d-flex align-items-center gap-1 mt-0.5">
                                                    <span class="badge bg-light text-muted border fs-10">#{{ $item->numero_sequencial }}</span>
                                                    @if($cliDoc)
                                                    <span class="text-muted fs-11">{{ $cliDoc }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Local (se multi-local) --}}
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12">
                                            {{ $item->localizacao->descricao ?? '--' }}
                                        </span>
                                    </td>
                                    @endif

                                    {{-- Usuário --}}
                                    <td class="fs-12 text-dark">{{ $item->user ? $item->user->name : '--' }}</td>

                                    {{-- Nº Nota + Série + Ref Externa --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $item->numero ?: '--' }}</span>
                                            <div class="d-flex align-items-center gap-1 mt-0.5">
                                                <span class="fs-11 text-muted">Série {{ $item->serie ?? 1 }}</span>
                                                @if($item->pedidoEcommerce)
                                                <a title="Pedido E-commerce #{{ $item->pedidoEcommerce->id }}" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none fs-10 px-1 py-0" href="{{ route('pedidos-ecommerce.show', [$item->pedidoEcommerce->id]) }}">EC #{{ $item->pedidoEcommerce->id }}</a>
                                                @elseif($item->ordemServico)
                                                <a title="Ordem de Serviço #{{ $item->ordemServico->id }}" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none fs-10 px-1 py-0" href="{{ route('ordem-servico.show', [$item->ordemServico->id]) }}">OS #{{ $item->ordemServico->id }}</a>
                                                @elseif($item->pedidoMercadoLivre)
                                                <a title="Pedido Mercado Livre" class="badge bg-warning-subtle text-warning border border-warning-subtle text-decoration-none fs-10 px-1 py-0" href="{{ route('mercado-livre-pedidos.show', [$item->pedidoMercadoLivre->id]) }}">ML</a>
                                                @elseif($item->pedidoNuvemShop)
                                                <a title="Pedido Nuvem Shop" class="badge bg-dark-subtle text-dark border border-dark-subtle text-decoration-none fs-10 px-1 py-0" href="{{ route('nuvem-shop-pedidos.show', [$item->pedidoNuvemShop->pedido_id]) }}">NS</a>
                                                @elseif($item->reserva)
                                                <a title="Reserva" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle text-decoration-none fs-10 px-1 py-0" href="{{ route('reservas.show', [$item->reserva->id]) }}">RS</a>
                                                @elseif($item->pedidoWoocomerce)
                                                <a title="Pedido WooCommerce" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none fs-10 px-1 py-0" href="{{ route('woocommerce-pedidos.show', [$item->pedidoWoocomerce->id]) }}">WO</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Valor Total --}}
                                    <td>
                                        <strong class="text-success fw-bold" style="font-size: 13.5px;">R$ {{ __moeda($item->total) }}</strong>
                                    </td>

                                    {{-- Estado e Ambiente Fiscal --}}
                                    @if(__isPlanoFiscal())
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="pill pill-red"><i class="ri-close-circle-fill"></i> Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="pill pill-amber"><i class="ri-error-warning-fill"></i> Rejeitado</span>
                                        @else
                                        <span class="pill pill-no"><i class="ri-time-fill"></i> Novo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="pill {{ $item->ambiente == 2 ? 'pill-amber' : 'pill-ok' }}" style="font-size: 11px; padding: 3px 8px;">
                                            {{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}
                                        </span>
                                    </td>
                                    @endif

                                    {{-- Cadastro --}}
                                    <td class="fs-12 text-muted">{{ __data_pt($item->created_at) }}</td>

                                    {{-- Emissão --}}
                                    <td class="fs-12 text-muted">{{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : '--' }}</td>

                                    {{-- Tipo (Saída / Entrada + Tag API se houver) --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            @if($item->tpNF)
                                            <span class="pill pill-ok" style="font-size: 11px; padding: 3px 8px;">Saída</span>
                                            @else
                                            <span class="pill pill-role" style="font-size: 11px; padding: 3px 8px;">Entrada</span>
                                            @endif
                                            @if($item->api)
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-10" title="Gerado via API">API</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="99">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma venda encontrada no período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação & Soma -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 border-top bg-light-subtle">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="p-2 px-3 rounded-2 bg-white border">
                                <span class="text-muted fs-11 text-uppercase fw-bold">Total nesta página:</span>
                                <strong class="text-success fs-14 ms-1">R$ {{ __moeda($data->sum('total')) }}</strong>
                            </div>
                            <span class="text-muted fs-12">
                                Exibindo <strong>{{ $data->firstItem() ?? 0 }}</strong> a <strong>{{ $data->lastItem() ?? 0 }}</strong> de <strong>{{ $data->total() }}</strong> notas
                            </span>
                        </div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>
                </div>

</div>

<!-- Modal Imprimir NFe -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalPrintLabel">
                    <i class="ri-printer-line"></i> Imprimir NFe <strong class="ref-numero text-white"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-success w-100 btn-sm py-2" onclick="gerarDanfe('danfe')">
                            <i class="ri-printer-line me-1"></i> DANFE Padrão
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-primary w-100 btn-sm py-2" onclick="gerarDanfe('simples')">
                            <i class="ri-printer-line me-1"></i> DANFE Simples
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-dark w-100 btn-sm py-2" onclick="gerarDanfe('etiqueta')">
                            <i class="ri-printer-line me-1"></i> DANFE Etiqueta
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL CANCELAR NFe — DESIGN PREMIUM IDÊNTICO AO MODELO      -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

            {{-- Cabeçalho Vermelho --}}
            <div class="modal-nfe-header-red d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0" id="modalCancelarLabel">Cancelar NF-e</h5>
                    <p class="modal-subtitle">O cancelamento da NF-e será transmitido para a SEFAZ</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">

                {{-- Alerta de Atenção Vermelho --}}
                <div class="modal-alert-danger-soft">
                    <i class="ri-alert-line" style="color:#ef4444; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                    <div>
                        <div style="font-size:12px; font-weight:700; color:#dc2626; margin-bottom:2px;">Atenção ao cancelar esta NF-e</div>
                        <p style="font-size:11px; color:#ef4444; margin:0; line-height:1.4;">Após o cancelamento autorizado pela SEFAZ, a nota fiscal ficará sem validade fiscal e não poderá ser utilizada novamente.</p>
                    </div>
                </div>

                {{-- Card Dados da NF-e --}}
                <div class="modal-nfe-dados-card">
                    <span class="card-title">Dados da NF-e</span>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card-label">Número NF-e</div>
                            <div class="card-val" id="cancela-card-numero">--</div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="card-label">Série</div>
                            <div class="card-val" id="cancela-card-serie">1</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Data de Emissão</div>
                            <div class="card-val" id="cancela-card-data">--</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="card-label">Cliente</div>
                            <div class="card-val" id="cancela-card-cliente">--</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card-label">Chave de Acesso</div>
                            <div style="font-size:12px; font-weight:700; color:#334155; word-break:break-all;" id="cancela-card-chave">--</div>
                        </div>
                    </div>
                </div>

                {{-- Campo Motivo --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">
                        Motivo do Cancelamento <span class="text-danger">*</span>
                    </label>
                    <textarea
                        id="inp-motivo-cancela"
                        class="form-control"
                        rows="3"
                        maxlength="255"
                        style="border-radius:8px; border-color:#cbd5e1; font-size:13px; resize:vertical;"
                        placeholder="Descreva o motivo do cancelamento da NF-e"
                        required
                        minlength="15"
                        oninput="nfeAtualizarContadorCancela(this)"></textarea>
                    <div class="mt-1">
                        <span style="font-size:11px; color:#94a3b8;"><span id="cancela-char-count">0</span> de 255 caracteres utilizados</span>
                    </div>
                </div>

                {{-- Alerta Amarelo Importante --}}
                <div class="modal-alert-yellow">
                    <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <i class="ri-information-line" style="color:#ca8a04;"></i> Importante
                    </div>
                    <ul>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> O cancelamento deve respeitar o prazo permitido pela SEFAZ;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Após autorizado, o cancelamento não poderá ser revertido;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Informe um motivo claro e objetivo.</li>
                    </ul>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm px-4 fw-bold"
                    style="background:#ef4444; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="ri-record-circle-line"></i> Transmitir Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL E-MAIL NFe — DESIGN PREMIUM                           -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-email" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.15);">

            <div style="background:#2563eb; padding:16px 20px;" class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0" id="modalEmailLabel" style="color:#fff; font-size:16px; font-weight:700;">
                        <i class="ri-mail-send-line me-2"></i>Enviar NF-e por E-mail
                    </h5>
                    <p style="color:rgba(255,255,255,0.9); font-size:11.5px; margin:3px 0 0;">Nota Fiscal <strong class="ref-numero"></strong></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">Endereço de E-mail do Destinatário:</label>
                    <input type="email" id="inp-email" class="form-control" placeholder="cliente@email.com"
                        style="border-radius:8px; border-color:#cbd5e1; font-size:13px;" required>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px;">
                    <p style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:10px;">Anexos:</p>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inp-danfe" checked>
                            <label class="form-check-label" for="inp-danfe" style="font-size:13px;">Incluir DANFE (PDF)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inp-xml" checked>
                            <label class="form-check-label" for="inp-xml" style="font-size:13px;">Incluir XML</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151; font-weight:500;">Fechar</button>
                <button type="button" id="btn-enviar-email" class="btn btn-primary btn-sm px-4 fw-bold"
                    style="border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="ri-send-plane-fill"></i> Enviar E-mail
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL CARTA DE CORREÇÃO (CC-e) — DESIGN PREMIUM             -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

            {{-- Cabeçalho Teal --}}
            <div class="modal-nfe-header-teal d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0" id="modalCorrigirLabel">Carta de Correção (CC-e)</h5>
                    <p class="modal-subtitle">O evento CC-e será transmitido à SEFAZ para correção da NF-e</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">

                {{-- Alerta Informativo Azul --}}
                <div class="modal-alert-info-soft">
                    <i class="ri-information-line" style="color:#4f46e5; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                    <div>
                        <div style="font-size:12px; font-weight:700; color:#312e81; margin-bottom:2px;">O que é a Carta de Correção?</div>
                        <p style="font-size:11px; color:#6366f1; margin:0; line-height:1.4;">A CC-e permite corrigir informações acessórias da NF-e. Ela não pode alterar valores fiscais, impostos ou dados do destinatário.</p>
                    </div>
                </div>

                {{-- Card Dados da NF-e --}}
                <div class="modal-nfe-dados-card">
                    <span class="card-title">Dados da NF-e</span>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card-label">Número NF-e</div>
                            <div class="card-val" id="cce-card-numero">--</div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="card-label">Série</div>
                            <div class="card-val" id="cce-card-serie">1</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Data de Emissão</div>
                            <div class="card-val" id="cce-card-data">--</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="card-label">Cliente</div>
                            <div class="card-val" id="cce-card-cliente">--</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card-label">Chave de Acesso</div>
                            <div style="font-size:12px; font-weight:700; color:#334155; word-break:break-all;" id="cce-card-chave">--</div>
                        </div>
                    </div>
                </div>

                {{-- Campo Texto da Correção --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">
                        Texto da Correção <span class="text-danger">*</span>
                    </label>
                    <textarea
                        id="inp-motivo-corrigir"
                        class="form-control"
                        rows="4"
                        maxlength="1000"
                        style="border-radius:8px; border-color:#cbd5e1; font-size:13px; resize:vertical;"
                        placeholder="Descreva aqui a correção a ser considerada.&#10;&#10;Exemplo: Onde se lê &quot;CFOP 5102&quot;, leia-se &quot;CFOP 5405&quot;."
                        required
                        minlength="15"
                        oninput="nfeAtualizarContadorCce(this)"></textarea>
                    <div class="mt-1">
                        <span style="font-size:11px; color:#94a3b8;"><span id="cce-char-count">0</span> de 1000 caracteres utilizados</span>
                    </div>
                </div>

                {{-- Alerta Amarelo Atenção --}}
                <div class="modal-alert-yellow">
                    <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <i class="ri-alert-line" style="color:#ca8a04;"></i> Atenção — A CC-e <strong>NÃO</strong> pode ser usada para:
                    </div>
                    <ul>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Alterar valores fiscais, impostos ou alíquotas;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Alterar dados do destinatário/remetente;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Alterar data de emissão da NF-e.</li>
                    </ul>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                <button type="button" id="btn-corrigir" class="btn btn-sm px-4 fw-bold text-white"
                    style="background:#0d9488; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="ri-send-plane-fill"></i> Transmitir CC-e
                </button>
            </div>
        </div>
    </div>
</div>

@include('modals._processing_overlay')

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n\n"
            text += "Chave: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave: " + chave + "\n"
            text += "Recibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }

    $('#btn-consulta-sefaz').click(() => {
        nfeMostrarProcessingOverlay('Consultando SEFAZ', 'Verificando disponibilidade dos serviços da SEFAZ...', 'ri-pulse-line');
        $.post(path_url + 'api/nfe_painel/consulta-status-sefaz', {
            empresa_id: $('#empresa_id').val(),
            usuario_id: $('#usuario_id').val(),
        })
        .done((res) => {
            nfeEsconderProcessingOverlay();
            let msg = "cStat: " + res.cStat
            msg += "\nMotivo: " + res.xMotivo
            msg += "\nAmbiente: " + (res.tpAmb == 2 ? "Homologação" : "Produção")
            msg += "\nVerAplic: " + res.verAplic
            swal("Status SEFAZ", msg, "success")
        })
        .fail((err) => {
            nfeEsconderProcessingOverlay();
            try { swal("Erro", err.responseText, "error") }
            catch { swal("Erro", "Algo deu errado", "error") }
        })
    })
</script>

{{-- Carregar nfe_transmitir.js PRIMEIRO para depois sobrescrever com as funções premium --}}
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>

<script type="text/javascript">
    // ─── Sobrescreve cancelar() do nfe_transmitir.js com versão premium (card de dados) ───
    function cancelar(id, numero, serie, data, cliente, chave) {
        IDNFE = id;
        $('.ref-numero').text(numero || '');
        $('#cancela-card-numero').text(numero || '--');
        $('#cancela-card-serie').text(serie || '1');
        $('#cancela-card-data').text(data || '--');
        $('#cancela-card-cliente').text(cliente || '--');
        $('#cancela-card-chave').text(chave || '--');
        $('#inp-motivo-cancela').val('');
        $('#cancela-char-count').text('0');
        $('#modal-cancelar').modal('show');
    }

    // ─── Sobrescreve corrigir() do nfe_transmitir.js com versão premium (card de dados) ───
    function corrigir(id, numero, serie, data, cliente, chave) {
        IDNFE = id;
        $('.ref-numero').text(numero || '');
        $('#cce-card-numero').text(numero || '--');
        $('#cce-card-serie').text(serie || '1');
        $('#cce-card-data').text(data || '--');
        $('#cce-card-cliente').text(cliente || '--');
        $('#cce-card-chave').text(chave || '--');
        $('#inp-motivo-corrigir').val('');
        $('#cce-char-count').text('0');
        $('#modal-corrigir').modal('show');
    }

    function nfeAtualizarContadorCancela(el) {
        $('#cancela-char-count').text(el.value.length);
    }

    function nfeAtualizarContadorCce(el) {
        $('#cce-char-count').text(el.value.length);
    }

    // ─── Inicialização do Select2 com Busca AJAX de Cliente / Fornecedor ───
    $(document).ready(function() {
        $('#inp-cliente_id').select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar o cliente...",
            width: "100%",
            allowClear: true,
            ajax: {
                cache: true,
                url: path_url + "api/clientes/pesquisa",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        pesquisa: params.term,
                        empresa_id: $("#empresa_id").val(),
                    };
                },
                processResults: function (response) {
                    var results = [];
                    $.each(response, function (i, v) {
                        results.push({
                            id: v.id,
                            text: v.razao_social + (v.cpf_cnpj ? " (" + v.cpf_cnpj + ")" : "")
                        });
                    });
                    return {
                        results: results,
                    };
                },
            },
        });

        // Ao clicar no [x], limpa e esvazia as opções para permitir nova busca ou buscar sem cliente
        $('#inp-cliente_id').on('select2:clear select2:unselecting', function (e) {
            var $el = $(this);
            setTimeout(function() {
                $el.empty().append('<option value="" selected></option>');
                $el.val('').trigger('change');
            }, 10);
        });
    });
</script>
@endsection
