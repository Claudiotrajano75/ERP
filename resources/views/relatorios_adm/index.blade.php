@extends('layouts.app', ['title' => 'Relatórios Administrativos'])

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

    /* Cards de Relatório Individuais */
    .report-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .report-card-header {
        padding: 16px 20px;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    .report-card-header i {
        font-size: 20px;
    }

    .report-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .report-card-footer {
        padding: 16px 20px;
        background-color: #fff;
        border-top: 1px solid #f1f5f9;
    }

    /* Formulários de Filtro */
    .form-control, select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
    }

    .form-control:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 12px !important;
        margin-bottom: 6px !important;
    }

    /* Botões */
    .btn {
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
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
                            <i class="ri-file-chart-line"></i> Relatórios Administrativos
                        </h4>
                        <p class="modulo-subtitle">
                            Emissão de relatórios gerenciais, cadastrais e de segurança do Super Admin em formato PDF.
                        </p>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Empresas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['total_empresas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cadastradas na base</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-building-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Empresas Ativas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['empresas_ativas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Com acesso regular</p>
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
                        <div class="card widget-icon-box text-bg-warning mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Planos / Assinaturas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['total_planos'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Contratos vigentes</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-bank-card-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Logs de Acesso</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ number_format($stats['total_acessos'] ?? 0, 0, ',', '.') }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Autenticações no sistema</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-shield-check-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ GRID DOS RELATÓRIOS ═══ -->
                <div class="row g-4">

                    <!-- 1. RELATÓRIO DE EMPRESAS -->
                    <div class="col-12 col-lg-6">
                        <form method="get" action="{{ route('relatorios-adm.empresas') }}" target="_blank">
                            <div class="report-card">
                                <div class="report-card-header">
                                    <i class="ri-building-2-line text-primary"></i>
                                    <h5>Relatório de Empresas Cadastradas</h5>
                                </div>
                                <div class="report-card-body">
                                    <p class="text-muted fs-12 mb-3">
                                        Relação completa de clientes, status cadastral, datas de criação e tributações.
                                    </p>
                                    <div class="row g-2">
                                        <div class="col-md-6 col-12">
                                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Cadastro Inicial</label>
                                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Cadastro Final</label>
                                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <label class="form-label"><i class="ri-toggle-line me-1"></i> Status</label>
                                            {!!Form::select('status', '', [
                                                '' => 'Todas as Empresas',
                                                '1' => 'Ativa',
                                                '-1' => 'Desativada',
                                            ])->attrs(['class' => 'form-select'])!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="report-card-footer">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="ri-printer-line me-1"></i> Gerar Relatório de Empresas PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 2. RELATÓRIO DE HISTÓRICO DE ACESSOS -->
                    <div class="col-12 col-lg-6">
                        <form method="get" action="{{ route('relatorios-adm.historico-acesso') }}" target="_blank">
                            <div class="report-card">
                                <div class="report-card-header">
                                    <i class="ri-login-box-line text-info"></i>
                                    <h5>Relatório de Histórico de Acessos</h5>
                                </div>
                                <div class="report-card-body">
                                    <p class="text-muted fs-12 mb-3">
                                        Histórico de logins, IPs, dispositivos e usuários que autenticaram no ERP.
                                    </p>
                                    <div class="row g-2">
                                        <div class="col-md-6 col-12">
                                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Data Final</label>
                                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <label class="form-label"><i class="ri-building-line me-1"></i> Empresa (Opcional)</label>
                                            {!!Form::select('empresa', '', ['' => 'Todas as Empresas'] + ($empresas ?? []))
                                            ->attrs(['class' => 'form-select select2'])
                                            !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="report-card-footer">
                                    <button class="btn btn-info w-100 text-white" type="submit">
                                        <i class="ri-printer-line me-1"></i> Gerar Histórico de Acessos PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 3. RELATÓRIO DE PLANOS -->
                    <div class="col-12 col-lg-6">
                        <form method="get" action="{{ route('relatorios-adm.planos') }}" target="_blank">
                            <div class="report-card">
                                <div class="report-card-header">
                                    <i class="ri-bank-card-line text-warning"></i>
                                    <h5>Relatório de Planos & Assinaturas</h5>
                                </div>
                                <div class="report-card-body">
                                    <p class="text-muted fs-12 mb-3">
                                        Acompanhamento de planos ativos, valores e vencimento das mensalidades.
                                    </p>
                                    <div class="row g-2">
                                        <div class="col-md-6 col-12">
                                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Dt. Inicial Expiração</label>
                                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Dt. Final Expiração</label>
                                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="report-card-footer">
                                    <button class="btn btn-warning w-100 text-white" type="submit">
                                        <i class="ri-printer-line me-1"></i> Gerar Relatório de Planos PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 4. RELATÓRIO DE CERTIFICADOS DIGITAIS -->
                    <div class="col-12 col-lg-6">
                        <form method="get" action="{{ route('relatorios-adm.certificados') }}" target="_blank">
                            <div class="report-card">
                                <div class="report-card-header">
                                    <i class="ri-shield-keyhole-line text-danger"></i>
                                    <h5>Relatório de Certificados Digitais à Vencer</h5>
                                </div>
                                <div class="report-card-body">
                                    <p class="text-muted fs-12 mb-3">
                                        Previsão de expiração dos Certificados Digitais A1 para antecipar renovações fiscais.
                                    </p>
                                    <div class="row g-2">
                                        <div class="col-md-6 col-12">
                                            <label class="form-label required"><i class="ri-calendar-line me-1"></i> Vencimento Inicial</label>
                                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])->required()!!}
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label required"><i class="ri-calendar-line me-1"></i> Vencimento Final</label>
                                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])->required()!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="report-card-footer">
                                    <button class="btn btn-danger w-100" type="submit">
                                        <i class="ri-printer-line me-1"></i> Gerar Relatório de Certificados PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection