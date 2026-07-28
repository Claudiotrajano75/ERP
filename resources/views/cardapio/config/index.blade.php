@extends('layouts.app', ['title' => 'Configuração do Cardápio'])

@section('css')
<style type="text/css">
    /* ─── Header Gradiente ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .modulo-header-gradient .modulo-title {
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .modulo-header-gradient .modulo-title i {
        background: rgba(255,255,255,0.12);
        padding: 8px;
        border-radius: 10px;
        color: #a8b5ff;
    }
    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255,255,255,0.6) !important;
        font-weight: 400;
    }
    .modulo-header-gradient .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .modulo-header-gradient .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-settings-5-line"></i>
                            Configuração do Cardápio
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie as informações públicas, visual do cabeçalho e integrações do cardápio digital.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->fill($item)
                ->post()
                ->route('config-cardapio.store')
                ->multipart()
                !!}
                
                @include('cardapio.config._forms')

                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
@endsection
