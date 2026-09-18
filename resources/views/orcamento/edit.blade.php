@extends('layouts.app', ['title' => 'Editar Orçamento'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm modulo-form-card">
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            Editar Orçamento #{{ $item->id }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Altere itens, valores ou condições de pagamento do orçamento.</p>
                    </div>
                    <div>
                        <a href="{{ route('orcamentos.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->fill($item)
                ->put()
                ->route('nfe.update', [$item->id])
                ->multipart()
                !!}
                <div class="pl-lg-4">
                    @include('nfe._forms')
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/nfe.js?v={{ time() }}"></script>
@endsection
