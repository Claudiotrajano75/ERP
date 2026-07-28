@extends('layouts.app', ['title' => 'Duplicar Venda'])
@section('content')

<div class="mt-3 text-dark">
    <div class="card border-0 shadow-sm text-dark">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 text-dark d-flex align-items-center">
                        <i class="ri-file-copy-line me-2 text-primary fs-22"></i>
                        Duplicar Venda <strong class="text-primary ms-2">#{{ $item->numero_sequencial }}</strong>
                    </h4>
                    @if(__countLocalAtivo() > 1 && isset($caixa))
                    <p class="text-muted mb-0 fs-13">Local / Filial: <strong class="text-danger">{{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}</strong></p>
                    @else
                    <p class="text-muted mb-0 fs-13">Revise os dados antes de salvar a nova cópia da venda original.</p>
                    @endif
                </div>
                <div>
                    <a href="{{ route('nfe.index') }}" class="btn btn-danger btn-sm px-3">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            {!!Form::open()->fill($item)
            ->post()
            ->route('nfe.store', [$item->id])
            ->multipart()
            !!}
            <div class="pl-lg-2">
                @include('nfe._forms')
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>

@section('js')
<script src="/js/nfe.js"></script>
@endsection
@endsection
