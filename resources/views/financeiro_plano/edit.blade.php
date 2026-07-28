@extends('layouts.app', ['title' => 'Editar Pagamento'])
@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-edit-box-line me-2 text-warning fs-22"></i>
                            Editar Pagamento
                        </h4>
                        <p class="text-muted mb-0 fs-13">Atualize os dados do pagamento do plano.</p>
                    </div>
                    <div>
                        <a href="{{ route('financeiro-plano.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="ri-arrow-left-line me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                {!!Form::open()->fill($item)->put()->route('financeiro-plano.update', [$item->id])!!}
                @include('financeiro_plano._forms')
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
@endsection
