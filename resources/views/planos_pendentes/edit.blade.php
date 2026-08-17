@extends('layouts.app', ['title' => 'Aprovar e Atribuir Plano'])

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

    .card-body {
        padding: 28px !important;
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

    /* Inputs e Selects */
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

    .form-control:read-only {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-weight: 500;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 13px !important;
        margin-bottom: 6px !important;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #4f46e5;
        font-size: 16px;
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
        padding: 6px 14px !important;
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

    .modulo-actions {
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
        margin-top: 24px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-0 modulo-title">
                                <i class="ri-check-double-line"></i>
                                Aprovar e Atribuir Plano
                            </h4>
                            <p class="modulo-subtitle">
                                Confirme as informações e os dados de pagamento para liberar o acesso da empresa ao plano.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('planos-pendentes.index') }}" class="btn btn-light btn-sm text-dark">
                                <i class="ri-arrow-left-line me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ FORMULÁRIO ═══ -->
                <div class="card-body">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('planos-pendentes.update', [$item->id])
                    ->multipart()
                    !!}

                    <!-- 1. Dados da Solicitação -->
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="ri-file-list-3-line"></i> 1. Dados da Solicitação
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-building-line me-1"></i> Empresa</label>
                                {!!Form::text('empresa', '')
                                ->required()
                                ->value($item->empresa->nome ?? 'N/A')
                                ->readonly()
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-user-star-line me-1"></i> Contador Responsável</label>
                                {!!Form::text('contador', '')
                                ->required()
                                ->value($item->contador->nome ?? 'Sem Contador (Direto)')
                                ->readonly()
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-vip-crown-2-line me-1"></i> Plano Solicitado</label>
                                {!!Form::text('plano', '')
                                ->required()
                                ->value($item->plano->nome ?? 'N/A')
                                ->readonly()
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-money-dollar-circle-line me-1"></i> Valor (R$)</label>
                                {!!Form::tel('valor', '')
                                ->required()
                                ->value(__moeda($item->valor))
                                ->attrs(['class' => 'form-control moeda'])
                                !!}
                            </div>
                        </div>
                    </div>

                    <!-- 2. Pagamento e Liberação -->
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="ri-bank-card-line"></i> 2. Forma e Status de Pagamento
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label required"><i class="ri-exchange-dollar-line me-1"></i> Forma de Pagamento</label>
                                {!!Form::select('forma_pagamento', '', \App\Models\Plano::formasPagamento())
                                ->required()
                                ->attrs(['class' => 'form-select select2'])
                                !!}
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label required"><i class="ri-shield-check-line me-1"></i> Status do Pagamento</label>
                                {!!Form::select('status_pagamento', '', \App\Models\FinanceiroPlano::statusDePagamentos())
                                ->required()
                                ->attrs(['class' => 'form-select select2'])
                                ->value('recebido')
                                !!}
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BOTÕES DE AÇÃO ═══ -->
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <a href="{{ route('planos-pendentes.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success px-4" id="btn-store">
                                <i class="ri-check-double-line me-1"></i> Liberar e Ativar Plano
                            </button>
                        </div>
                    </div>

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/mdfe.js"></script>
@endsection
