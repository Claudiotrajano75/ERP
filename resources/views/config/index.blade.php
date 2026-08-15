@extends('layouts.app', ['title' => 'Configuração da Empresa'])

@section('css')
<style>
    /* ─── Padrão Oficial ERP Layout Modernization ─── */
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

    /* ─── Cabeçalho de Gradiente Premium ─── */
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

    /* ─── Títulos de Seção ─── */
    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #302b63 !important;
        margin-top: 10px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        font-size: 18px;
        color: #5572f5;
    }

    /* ─── Formulários e Inputs ─── */
    .form-control,
    .form-select,
    select,
    input[type="text"],
    input[type="tel"],
    input[type="password"] {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
        background-color: #ffffff !important;
    }

    .form-control:focus,
    .form-select:focus,
    select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label,
    label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 13px !important;
        margin-bottom: 6px !important;
    }

    /* ─── Botões Padrão ─── */
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

    /* ─── Navegação por Abas (Tabs) ─── */
    .nav-tabs-custom {
        background: #f8fafc;
        padding: 6px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        display: flex;
        gap: 4px;
    }

    .nav-tabs-custom .nav-link {
        flex: 1;
        border-radius: 8px !important;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 13px;
        color: #64748b;
        border: none !important;
        background: transparent;
        text-align: center;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #334155;
        background: rgba(255, 255, 255, 0.6);
    }

    .nav-tabs-custom .nav-link.active {
        background: #ffffff !important;
        color: #4f46e5 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    /* ─── Cards de Seção Interna (Painéis Fiscais) ─── */
    .card-secao-fiscal {
        border: 1px solid #eef2f6 !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
        margin-bottom: 20px !important;
        background: #ffffff;
    }

    .card-secao-fiscal .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        padding: 12px 20px;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-secao-fiscal .card-header h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-secao-fiscal .card-body {
        padding: 20px !important;
    }

    /* Upload de Logo */
    .upload-logo-container {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        background: #f8fafc;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO GRADIENT PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-settings-3-line"></i>
                                Configuração do Emitente
                            </h4>
                            <p class="text-white-50 mb-0 modulo-subtitle fs-13">
                                Gerencie as informações cadastrais da empresa, parâmetros fiscais de emissão e certificado A1.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="btn btn-light btn-sm px-3 text-dark shadow-sm">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(isset($empresa->id))
                        {!!Form::open()->fill($item)
                        ->put()
                        ->route('config.update', [$item->id])
                        ->multipart()
                        !!}
                        @include('config.configuracao')
                        {!!Form::close()!!}
                    @else
                        {!!Form::open()->fill($empresa)
                        ->post()
                        ->route('config.store')
                        ->multipart()
                        !!}
                        @include('empresas._forms')
                        {!!Form::close()!!}
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
