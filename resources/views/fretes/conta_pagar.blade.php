@extends('layouts.app', ['title' => 'Nova Conta a Pagar - Despesa de Frete'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-uppercase fw-bold text-primary d-block mb-1"
                                  style="letter-spacing: 0.5px;">Financeiro & Fretes</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-text-line"></i>
                                Nova Conta a Pagar: <strong class="text-primary ms-1">Despesa de Frete #{{ $item->frete->numero_sequencial }}</strong>
                            </h4>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('fretes.show', [$item->frete->id]) }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar ao Frete
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('conta-pagar.store')
                    ->multipart()
                    !!}

                    <input type="hidden" name="redirect" value="{{ route('fretes.show', [$item->frete->id]) }}">
                    <input type="hidden" name="despesa_id" value="{{ $item->id }}">
                    
                    @include('conta-pagar._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@include('modals._novo_cliente')
@endsection

