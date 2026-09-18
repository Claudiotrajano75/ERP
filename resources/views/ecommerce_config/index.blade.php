@extends('layouts.app', ['title' => 'Configuração Ecommerce'])

@section('css')
<style>
/* ─── Navegação por Abas (Tabs) ─── */
.nav-tabs-custom {
    background: #f8fafc;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.nav-tabs-custom .nav-link {
    flex: 1;
    min-width: 140px;
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

/* ─── Cards de Seção Interna ─── */
.card-secao-ecommerce {
    border: 1px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
    margin-bottom: 20px !important;
    background: #ffffff;
}

.card-secao-ecommerce .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 14px 20px;
    border-radius: 12px 12px 0 0 !important;
}

.card-secao-ecommerce .card-header h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-secao-ecommerce .card-body {
    padding: 20px !important;
}

/* ─── Logo Preview Box ─── */
.logo-upload-box {
    background: #fcfdfe;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    transition: all 0.2s ease;
}
.logo-upload-box:hover {
    border-color: #4f46e5;
    background: #f8fafc;
}
.logo-preview-wrapper {
    width: 100%;
    max-width: 260px;
    height: 140px;
    margin: 0 auto 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    position: relative;
    padding: 10px;
}
.logo-preview-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.btn-remove-logo {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #ef4444;
    color: #fff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-remove-logo:hover {
    transform: scale(1.1);
    background: #dc2626;
}

/* ─── Botões de Ação Footer ─── */
.modulo-actions {
    background: #f8fafc;
    border-top: 1px solid #eef0f5;
    margin: 24px -24px -24px -24px;
    padding: 20px 24px;
    border-radius: 0 0 12px 12px;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="card border-0 shadow-sm modulo-form-card">
        
        <!-- ═══ CABEÇALHO ═══ -->
        <div class="card-header modulo-header-gradient py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                        <i class="ri-store-2-line"></i>
                        Configuração da Loja Virtual (E-commerce)
                    </h4>
                    <p class="text-muted mb-0 modulo-subtitle fs-13">
                        Gerencie a identidade visual, dados cadastrais, formas de pagamento, políticas e canais de atendimento da sua loja.
                    </p>
                </div>
                <div>
                    @if($item)
                        <a href="{{ route('config-ecommerce.site') }}" target="_blank" class="dash-btn dash-btn-light">
                            <i class="ri-external-link-line"></i> Acessar Loja Virtual
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            {!!Form::open()->fill($item)
            ->post()
            ->route('config-ecommerce.store')
            ->multipart()
            !!}
            <div>
                @include('ecommerce_config._forms')
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
