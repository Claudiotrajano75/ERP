@extends('layouts.app', ['title' => 'Novo Plano'])

@section('content')
<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-vip-diamond-line"></i>
                                Novo Plano
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Cadastre um novo plano de assinatura, defina seus valores, limites e módulos inclusos.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('planos.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('planos.store')
                    ->multipart()
                    !!}

                    @include('planos._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
