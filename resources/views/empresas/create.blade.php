@extends('layouts.app', ['title' => 'Nova Empresa'])

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
                                <i class="ri-building-add-line"></i>
                                Nova Empresa
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Cadastre uma nova empresa e configure seus parâmetros fiscais e de acesso.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('empresas.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('empresas.store')
                    ->multipart()
                    !!}

                    @include('empresas._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
