@extends('layouts.app', ['title' => 'Configuração da Empresa'])

@section('css')
<style>
    /* ─── Navegação por Abas (Tabs) ─── */
    .nav-tabs-custom {
        background: #f8fafc;
        padding: 6px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .nav-tabs-custom .nav-link {
        flex: 1;
        min-width: 160px;
        border-radius: 10px !important;
        padding: 12px 18px;
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
        background: rgba(255, 255, 255, 0.7);
    }

    .nav-tabs-custom .nav-link.active {
        background: #ffffff !important;
        color: #4f46e5 !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    /* ─── Cards de Seção Interna ─── */
    .card-secao-fiscal {
        border: 1px solid #eef2f6 !important;
        border-radius: 14px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
        margin-bottom: 24px !important;
        background: #ffffff;
        overflow: hidden;
    }

    .card-secao-fiscal .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        padding: 14px 20px;
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
        padding: 24px !important;
    }

    /* Upload de Logo */
    .upload-logo-container {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    .upload-logo-container:hover {
        border-color: #4f46e5;
        background: #f5f7ff;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-settings-3-line"></i>
                                Configuração do Emitente & Parâmetros Fiscais
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie as informações cadastrais da empresa, parâmetros de emissão NFe/NFCe/CTe/MDFe e certificado A1.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Início
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
