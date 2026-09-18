@extends('layouts.app', ['title' => 'Nova Conta a Pagar - Manutenção de Veículo'])

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
                                <i class="ri-file-text-line"></i>
                                Nova Conta a Pagar: <strong class="text-primary ms-1">Manutenção #{{ $item->numero_sequencial }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gere o lançamento no contas a pagar com base no valor total da ordem de manutenção.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('manutencao-veiculos.show', [$item->id]) }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar à Manutenção
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

                    <input type="hidden" name="redirect" value="{{ route('manutencao-veiculos.show', [$item->id]) }}">
                    <input type="hidden" name="manutencao_id" value="{{ $item->id }}">
                    
                    @include('conta-pagar._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@include('modals._novo_cliente')
@endsection

