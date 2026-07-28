@extends('layouts.app', ['title' => 'Configuração WooCommerce'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #96588a 0%, #7f54b3 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.8) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    /* Inputs customizados */
    .modulo-form-card .form-control { border-radius: 8px; border: 1px solid #e2e5ec; padding: 10px 14px; font-size: 14px; transition: all 0.2s; box-shadow: none; }
    .modulo-form-card .form-control:focus { border-color: #7f54b3; box-shadow: 0 0 0 3px rgba(127, 84, 179, 0.1); }
    .modulo-form-card label { font-weight: 600; color: #495057; margin-bottom: 6px; font-size: 13px; }
    
    .info-card { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 16px; margin-bottom: 20px; word-break: break-all; }
    .info-card .info-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; font-weight: 700; margin-bottom: 4px; }
    .info-card .info-value { font-family: monospace; font-size: 14px; color: #212529; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            
            <div class="card border-0 shadow-sm modulo-form-card">
                
                {{-- CABEÇALHO PREMIUM --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-line"></i>
                                Integração WooCommerce
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Configure as credenciais da API REST da sua loja WooCommerce.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- FORMULÁRIO --}}
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)->post()->route('woocommerce-config.store')->multipart()!!}
                        
                        @include('woocommerce_config._forms')
                        
                    {!!Form::close()!!}
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
