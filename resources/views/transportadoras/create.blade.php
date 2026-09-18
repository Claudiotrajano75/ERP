@extends('layouts.app', ['title' => 'Nova Transportadora'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Nova Transportadora
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Configure o cadastro de um novo parceiro de logística com consulta automática por CNPJ.</p>
                        </div>
                        <div>
                            <a href="{{ route('transportadoras.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('transportadoras.store')
                    ->multipart()
                    !!}
                    @include('transportadoras._forms')
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
