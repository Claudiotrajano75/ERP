@extends('layouts.app', ['title' => 'Configuração da Empresa'])
@section('css')
<style>
    /* ─── Header Gradient Premium ─── */
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
    .modulo-form-card {
        border: 1px solid #eef0f5;
        border-radius: 12px;
        overflow: hidden;
    }
    .modulo-form-card .card-body {
        background: #fff;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                <!-- ═══ Cabeçalho Gradient Premium ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-settings-3-line"></i>
                                Configuração da Empresa
                            </h4>
                            <p class="text-white-50 mb-0 modulo-subtitle fs-13">
                                Configure os dados da empresa, emissão fiscal e certificado A1.
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
                        <div class="pl-lg-4">
                            @include('config.configuracao')
                        </div>
                        {!!Form::close()!!}
                    @else
                        {!!Form::open()->fill($empresa)
                        ->post()
                        ->route('config.store')
                        ->multipart()
                        !!}
                        <div class="pl-lg-4">
                            @include('empresas._forms')
                        </div>
                        {!!Form::close()!!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
