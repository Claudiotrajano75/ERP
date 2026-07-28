@extends('layouts.app', ['title' => 'Nova Configuração de TEF'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-wifi-line me-2 text-success fs-22"></i>
                            Nova Configuração de TEF
                        </h4>
                        <p class="text-muted mb-0 fs-13">Configure uma nova integração TEF (Transferência Eletrônica de Fundos).</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        <a href="{{ route('tef-config.index') }}" class="btn btn-danger btn-sm px-3">
                            <i class="ri-arrow-left-double-fill align-middle me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()
                ->post()
                ->route('tef-config.store')
                !!}
                <div class="pl-lg-4">
                    @include('tef_config._forms')
                </div>
                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>
@endsection
