@extends('layouts.app', ['title' => 'Configuração do Marketplace'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #ff4b1f 0%, #ff9068 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    
    .form-section-title { font-size: 15px; font-weight: 700; color: #374151; border-bottom: 2px solid #eef0f5; padding-bottom: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    .form-section-title i { color: #ff4b1f; }
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
                                <i class="ri-store-2-fill"></i>
                                Configuração do Marketplace
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Defina as configurações principais do seu próprio marketplace, dados de contato e entregas.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body bg-white p-4">
                    {!!Form::open()->fill($item ?? [])->post()->route('config-marketplace.store')->multipart()!!}
                    <div class="px-2">
                        @include('marketplace_config._forms')
                    </div>
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
