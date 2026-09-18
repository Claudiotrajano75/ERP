@extends('layouts.app', ['title' => 'Vendas / NFe'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* --- Novo Filtro de Pesquisa Premium --- */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    padding: 20px !important;
    margin-bottom: 24px;
}

/* Título e Header do Filtro */
.filtro-premium-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f3f9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.filtro-premium-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.filtro-premium-title i {
    color: #5572f5;
    margin-right: 6px;
}

/* Customização dos Inputs dentro do Filtro */
.modulo-glass-filter-premium label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c8ca6 !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 4px;
}
.modulo-glass-filter-premium label i {
    font-size: 12px;
    color: #a8a8c0;
}

.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 38px !important;
    border-radius: 8px !important;
    border: 1px solid #dcdce9 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #374151 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}

.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #5572f5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
}

/* Botões do Filtro */
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
}

.modulo-glass-filter-premium .btn-limpar {
    background: #f1f3f9 !important;
    border: 1px solid #e2e5ec !important;
    color: #5a5a7a !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e8ebf3 !important;
    color: #302b63 !important;
}

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

        /* ─── Botão Transmitir em Destaque Compacto ─── */
        .btn-transmitir-destaque {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 6px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            padding: 4px 10px !important;
            height: 30px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25) !important;
            transition: all 0.2s ease !important;
            letter-spacing: 0.1px;
            white-space: nowrap;
            text-decoration: none !important;
        }
        .btn-transmitir-destaque:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35) !important;
            background: linear-gradient(135deg, #047857 0%, #065f46 100%) !important;
            color: #ffffff !important;
        }
        .btn-transmitir-destaque:active {
            transform: translateY(0);
        }
        .btn-transmitir-destaque i {
            font-size: 12px;
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
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-receipt-line"></i>
                            Painel de Vendas (NFe)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie notas fiscais de saída: emissão, transmissão ao SEFAZ, cancelamento e impressão do DANFE.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        @can('nfe_create')
                        <a href="{{ route('nfe.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Venda
                        </a>
                        @endcan
                        @if(__isPlanoFiscal())
                        <button id="btn-consulta-sefaz" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-refresh-line align-middle me-1"></i> Status SEFAZ
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                @if($contigencia != null)
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-start">
                    <i class="ri-error-warning-line me-2 fs-20 mt-0.5"></i>
                    <div>
                        <strong>Contingência Ativada!</strong>
                        Tipo: <strong>{{ $contigencia->tipo }}</strong> &mdash; Início: <strong>{{ __data_pt($contigencia->created_at) }}</strong>
                    </div>
                </div>
                @endif

                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Notas Fiscais (NF-e)
                        </h5>
                    </div>

                    <form method="get" action="{{ route('nfe.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Cliente / Fornecedor</label>
                                <select class="select2 form-select" name="cliente_id">
                                    @if(isset($cliente) && $cliente)
                                        <option value="{{ $cliente->id }}" selected>{{ $cliente->info }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Data Inicial -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>

                            <!-- Data Final -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>

                            @if(__isPlanoFiscal())
                            <!-- Estado NFe -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-equalizer-line"></i> Estado NFe</label>
                                <select name="estado" class="form-select">
                                    <option value="" @selected(request('estado') == '')>Todos</option>
                                    <option value="novo" @selected(request('estado') == 'novo')>Novas</option>
                                    <option value="rejeitado" @selected(request('estado') == 'rejeitado')>Rejeitadas</option>
                                    <option value="cancelado" @selected(request('estado') == 'cancelado')>Canceladas</option>
                                    <option value="aprovado" @selected(request('estado') == 'aprovado')>Aprovadas</option>
                                </select>
                            </div>

                            <!-- Tipo (Saída / Entrada) -->
                            <div class="col-md-1 col-6">
                                <label class="form-label"><i class="ri-arrow-left-right-line"></i> Tipo</label>
                                <select name="tpNF" class="form-select">
                                    <option value="-" @selected(request('tpNF') == '-' || request('tpNF') == '')>Todos</option>
                                    <option value="1" @selected(request('tpNF') === '1')>Saída</option>
                                    <option value="0" @selected(request('tpNF') === '0')>Entrada</option>
                                </select>
                            </div>
                            @endif

                            @if(__countLocalAtivo() > 1)
                            <!-- Localização -->
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                                <select name="local_id" class="select2 form-select">
                                    <option value="">Selecione</option>
                                    @foreach(__getLocaisAtivoUsuario() as $loc)
                                    <option value="{{ $loc->id }}" @selected(request('local_id') == $loc->id)>{{ $loc->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Botões de Ação -->
                            <div class="col-md-2 col-12 ms-auto d-flex gap-2">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('nfe.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap mb-4">
                    <div class="table-responsive" style="min-height: 280px;">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Ações</th>
                                    <th>Cliente / Fornecedor</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Usuário</th>
                                    <th>Nº Nota</th>
                                    <th>Valor (R$)</th>
                                    @if(__isPlanoFiscal())
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    @endif
                                    <th>Cadastro</th>
                                    <th>Emissão</th>
                                    <th>Origem</th>
                                    <th>Tipo</th>
                                    <th>Ref.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    {{-- 1ª Coluna: Ações com Menu Dropdown --}}
                                    <td style="white-space: nowrap; width: 70px;">
                                        <form action="{{ route('nfe.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0 d-inline">
                                            @method('delete')
                                            @csrf
                                            <div class="dropdown dropdown-action-menu d-inline-block">
                                                <button type="button" class="btn btn-action-trigger" data-bs-toggle="dropdown" aria-expanded="false" title="Opções da Venda">
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
                                        </form>
                                    </td>

                                    {{-- 2ª Coluna: Cliente / Fornecedor (Identificação Principal) --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($item->cliente)
                                                <strong class="text-dark">{{ $item->cliente->razao_social }}</strong>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light text-muted border fs-11">#{{ $item->numero_sequencial }}</span>
                                                    <span class="text-muted fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                                </div>
                                            @else
                                                <strong class="text-dark">{{ $item->fornecedor ? $item->fornecedor->razao_social : '--' }}</strong>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light text-muted border fs-11">#{{ $item->numero_sequencial }}</span>
                                                    @if($item->fornecedor && $item->fornecedor->cpf_cnpj)
                                                        <span class="text-muted fs-11">{{ $item->fornecedor->cpf_cnpj }}</span>
                                                    @endif
                                                </div>
                                            @endif
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

                                    {{-- Nº Nota --}}
                                    <td>
                                        <span class="fw-bold text-dark">{{ $item->numero ?: '--' }}</span>
                                    </td>

                                    {{-- Valor Total --}}
                                    <td>
                                        <strong class="text-success" style="font-size: 13.5px;">R$ {{ __moeda($item->total) }}</strong>
                                    </td>

                                    {{-- Estado e Ambiente Fiscal --}}
                                    @if(__isPlanoFiscal())
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-11">
                                            {{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}
                                        </span>
                                    </td>
                                    @endif

                                    {{-- Cadastro --}}
                                    <td class="fs-12 text-muted">{{ __data_pt($item->created_at) }}</td>

                                    {{-- Emissão --}}
                                    <td class="fs-12 text-muted">{{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : '--' }}</td>

                                    {{-- Origem --}}
                                    <td>
                                        @if($item->api)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fs-11">API</span>
                                        @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-11">Painel</span>
                                        @endif
                                    </td>

                                    {{-- Tipo (Saída / Entrada) --}}
                                    <td>
                                        @if($item->tpNF)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fs-11">Saída</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle fs-11">Entrada</span>
                                        @endif
                                    </td>

                                    {{-- Referência do Módulo Externo --}}
                                    <td>
                                        @if($item->pedidoEcommerce)
                                        <a title="Pedido E-commerce" class="badge bg-danger text-white text-decoration-none" href="{{ route('pedidos-ecommerce.show', [$item->pedidoEcommerce->id]) }}">EC</a>
                                        @elseif($item->ordemServico)
                                        <a title="Ordem de Serviço" class="badge bg-primary text-white text-decoration-none" href="{{ route('ordem-servico.show', [$item->ordemServico->id]) }}">OS</a>
                                        @elseif($item->pedidoMercadoLivre)
                                        <a title="Pedido Mercado Livre" class="badge bg-warning text-dark text-decoration-none" href="{{ route('mercado-livre-pedidos.show', [$item->pedidoMercadoLivre->id]) }}">ML</a>
                                        @elseif($item->pedidoNuvemShop)
                                        <a title="Pedido Nuvem Shop" class="badge bg-dark text-white text-decoration-none" href="{{ route('nuvem-shop-pedidos.show', [$item->pedidoNuvemShop->pedido_id]) }}">NS</a>
                                        @elseif($item->reserva)
                                        <a title="Reserva" class="badge bg-secondary text-white text-decoration-none" href="{{ route('reservas.show', [$item->reserva->id]) }}">RS</a>
                                        @elseif($item->pedidoWoocomerce)
                                        <a title="Pedido WooCommerce" class="badge bg-info text-dark text-decoration-none" href="{{ route('woocommerce-pedidos.show', [$item->pedidoWoocomerce->id]) }}">WO</a>
                                        @else
                                        <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma venda encontrada no período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação & Soma -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark">Total das Vendas no Grid: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

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
</script>
@endsection
