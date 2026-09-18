@extends('layouts.app', ['title' => 'Editar Dias de Atendimento'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-edit-circle-line"></i>
                            Editar Dias de Atendimento
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Altere a escala de atendimento semanal do colaborador selecionado.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('atendimentos.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->fill($item)
                ->put()
                ->route('atendimentos.update', [$item->id])
                !!}
                @include('atendimentos._forms', ['formType' => 'edit'])
                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>
@endsection

