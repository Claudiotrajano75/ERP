@extends('layouts.app', ['title' => 'Configuração de Email'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }

    /* ─── Cards Internos (Secções) ─── */
    .modulo-section-card {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .modulo-section-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0;
        padding: 12px 20px;
    }
    .modulo-section-card .card-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #343a40;
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="card modulo-form-card shadow-sm">
        <div class="card-header modulo-header-gradient py-3 px-4">
            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                <i class="ri-mail-send-line"></i>
                Configuração de Email (SMTP)
            </h4>
            <p class="mb-0 modulo-subtitle fs-13">
                Configure os dados do servidor SMTP para envio de emails.
            </p>
        </div>
        <div class="card-body p-4">
            {!!Form::open()->fill($item)
            ->post()
            ->route('email-config.store')
            !!}
            <div class="pl-lg-4">
                @include('email_config._forms')
            </div>
            {!!Form::close()!!}

            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <div>
                    @if(isset($item) && $item->status)
                    <p class="mb-0 text-muted"><i class="ri-mail-check-line text-success me-1"></i> Utilizando o email configurado: <strong class="text-dark">{{ $item->email }}</strong></p>
                    @else
                    <p class="mb-0 text-muted"><i class="ri-error-warning-line text-warning me-1"></i> Utilizando o email administrador padrão: <strong class="text-dark">{{ env("MAIL_USERNAME") }}</strong></p>
                    @endif
                </div>
                <div>
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('teste-email') }}">
                        <i class="ri-send-plane-fill me-1"></i> Enviar Email de Teste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
