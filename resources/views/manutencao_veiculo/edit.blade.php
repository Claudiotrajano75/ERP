@extends('layouts.app', ['title' => 'Editar Manutenção de Veículo'])

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
                                <i class="ri-pencil-line"></i>
                                Editar Manutenção <strong class="text-primary ms-1">#{{ $item->numero_sequencial }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize o status, veículo, fornecedor, peças, serviços e observações da manutenção.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('manutencao-veiculos.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('manutencao-veiculos.update', [$item->id])
                    ->multipart()
                    !!}

                    @include('manutencao_veiculo._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/manutencao_veiculo.js"></script>
@endsection

