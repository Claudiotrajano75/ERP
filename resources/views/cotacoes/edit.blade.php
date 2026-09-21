@extends('layouts.app', ['title' => 'Editar Cotação #' . $item->id])

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
                                <i class="ri-pencil-line"></i>
                                Editar Cotação <strong class="text-white ms-1">#{{ $item->id }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Referência: <strong>#{{ $item->referencia }}</strong> | Fornecedor: <strong>{{ $item->fornecedor ? $item->fornecedor->info : '--' }}</strong></p>
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
                    ->put()
                    ->route('cotacoes.update', $item->id)
                    ->fill($item)
                    !!}
                    
                    @include('cotacoes._forms')
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/cotacao.js?v={{ time() }}"></script>
@endsection

