@extends('layouts.app', ['title' => 'Configuração Mercado Livre'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #ffe600 0%, #f4d000 50%, #e6b800 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #333; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(0,0,0,0.05); padding: 8px; border-radius: 10px; color: #333; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(51,51,51,0.7) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.15); }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    
    /* Premium Form elements */
    .modulo-form-card .card-body h5 { color: #333; font-weight: 700; margin-top: 15px; margin-bottom: 20px; font-size: 16px; border-bottom: 2px solid #eef0f5; padding-bottom: 10px; }
    .modulo-form-card .card-body label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
    .modulo-form-card .form-control, .modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 10px 12px; }
    .modulo-form-card .form-control:focus, .modulo-form-card .form-select:focus { border-color: #f4d000; box-shadow: 0 0 0 0.2rem rgba(244, 208, 0, 0.25); }
    
    .info-card { background: #fafbff; border: 1px dashed #c5cae9; border-radius: 10px; padding: 16px; font-size: 13px; color: #5a5a7a; height: 100%; display: flex; flex-direction: column; justify-content: center; }
    .info-card strong { color: #302b63; word-break: break-all; display: block; margin-top: 4px; }
    .info-card i { font-size: 20px; color: #f4d000; margin-bottom: 8px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="card border-0 shadow-sm modulo-form-card">
        
        {{-- CABEÇALHO PREMIUM (Amarelo ML) --}}
        <div class="card-header modulo-header-gradient py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                        <i class="ri-shopping-bag-3-fill"></i>
                        Integração Mercado Livre
                    </h4>
                    <p class="text-muted mb-0 modulo-subtitle fs-13">
                        Configure as credenciais de API para sincronizar anúncios e pedidos com o Mercado Livre.
                    </p>
                </div>
                <div>
                    @if($item != null)
                    <a href="{{ route('mercado-livre.get-code') }}" class="btn btn-dark btn-sm px-3 d-flex align-items-center gap-1">
                        <i class="ri-key-2-line fs-16"></i> Solicitar Novo Token
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            {!!Form::open()->fill($item)
            ->post()
            ->route('mercado-livre-config.store')
            ->multipart()
            !!}
            
            @include('mercado_livre_config._forms')
            
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
