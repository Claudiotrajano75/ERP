@extends('layouts.app', ['title' => 'Nova Cotação'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Criar Nova Cotação de Preços
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Gere uma rodada de cotação para um ou múltiplos distribuidores cadastrados.</p>
                        </div>
                        <div>
                            <a href="{{ route('cotacoes.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('cotacoes.store')
                    !!}
                    
                    @include('cotacoes._forms')
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
