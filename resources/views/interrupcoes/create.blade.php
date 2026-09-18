@extends('layouts.app', ['title' => 'Nova Interrupção'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-add-circle-line"></i>
                            Cadastrar Nova Interrupção
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Configure um intervalo regular ou pausa de expediente para um colaborador.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('interrupcoes.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->post()->route('interrupcoes.store')!!}
                @include('interrupcoes._forms', ['formType' => 'create'])
                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>
@endsection

