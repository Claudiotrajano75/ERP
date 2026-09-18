@extends('layouts.app', ['title' => 'Editar Frete'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-line"></i>
                                Editar Frete: <strong class="text-primary ms-1">#{{ $item->numero_sequencial }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize os dados do frete, veículo, motorista, despesas operacionais e itinerário.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('fretes.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('fretes.update', [$item->id])
                    ->multipart()
                    !!}

                    @include('fretes._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@include('modals._novo_cliente')
@endsection

@section('js')
<script src="/js/frete.js"></script>
<script src="/js/novo_cliente.js"></script>
@endsection

