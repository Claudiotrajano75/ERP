@extends('layouts.app', ['title' => 'Nova Solicitação'])

@section('css')
<style type="text/css">
    /* Card Geral */
    .card-modulo {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        margin-bottom: 24px;
    }

    /* Cabeçalho no padrão claro moderno */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #f2f3ff 0%, #e6e9ff 100%) !important;
        border-bottom: 1px solid #e0e7ff !important;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #4338ca !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        font-size: 1.25rem !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: #4f46e5 !important;
        color: #ffffff !important;
        padding: 10px !important;
        border-radius: 12px !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25) !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: #64748b !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    .btn-voltar {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 10px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-voltar:hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Inputs e Form controls */
    .form-label, label {
        font-weight: 600 !important;
        color: #334155 !important;
        font-size: 13px !important;
        margin-bottom: 6px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        background-color: #fcfdfe !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4f46e5 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    }

    /* Container de upload moderno */
    .upload-custom-box {
        background: #f8faff;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 16px 20px;
        transition: all 0.2s ease;
    }
    .upload-custom-box:hover {
        border-color: #818cf8;
        background: #f1f4ff;
    }

    input[type=file]::file-selector-button {
        background-color: #4f46e5;
        color: #fff;
        border: 0;
        border-radius: 8px;
        padding: 8px 16px;
        margin-right: 14px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    input[type=file]::file-selector-button:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
    }

    /* Botões de Ação */
    .btn-acao {
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 10px 22px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease !important;
    }

    .btn-salvar {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
    }
    .btn-salvar:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
    }

    .btn-cancelar {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #475569 !important;
    }
    .btn-cancelar:hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
    }

    /* Banner informativo sutil */
    .info-callout {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 10px;
        padding: 12px 16px;
        color: #1e40af;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-12">
            <div class="card card-modulo">

                <!-- ═══ CABEÇALHO PADRÃO CLARO ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="modulo-title">
                                <i class="ri-add-circle-line"></i>
                                Nova Solicitação de Suporte
                            </h4>
                            <p class="modulo-subtitle">
                                Descreva seu problema ou dúvida para nossa equipe de suporte e atendimento.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('ticket.index') }}" class="btn-voltar">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="info-callout mb-4">
                        <i class="ri-information-line fs-18"></i>
                        <div>
                            Nossa equipe técnica atenderá seu chamado o mais breve possível. Se o problema envolver erros de tela ou notas fiscais, anexe imagens ou arquivos XML para agilizar o suporte.
                        </div>
                    </div>

                    {!! Form::open()->post()->route('ticket.store')->multipart() !!}
                    @include('ticket._forms')
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(function(){
        tinymce.init({ 
            selector: 'textarea.tiny', 
            language: 'pt_BR',
            height: 220,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link code',
            branding: false,
            promotion: false
        });

        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none');
        }, 500);
    });
</script>
@endsection
