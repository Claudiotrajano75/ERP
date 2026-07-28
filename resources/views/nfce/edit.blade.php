@extends('layouts.app', ['title' => 'Editar NFCe'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-bill-line me-2 text-warning fs-22"></i>
                            Editar NFCe
                        </h4>
                        <p class="text-muted mb-0 fs-13">Altere os dados da nota fiscal de consumidor eletrônica selecionada.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        @if(__countLocalAtivo() > 1 && isset($caixa))
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-12 align-self-center">
                            <i class="ri-map-pin-line me-1"></i> {{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}
                        </span>
                        @endif
                        <a href="{{ route('nfce.index') }}" class="btn btn-danger btn-sm px-3">
                            <i class="ri-arrow-left-double-fill align-middle me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->fill($item)
                ->put()
                ->route('nfce.update', [$item->id])
                ->multipart()
                !!}
                <div class="pl-lg-4">
                    @include('nfce._forms')
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/nfce.js"></script>
@endsection
