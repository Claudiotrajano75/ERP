@extends('layouts.app', ['title' => 'Nova Taxa'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Nova Taxa de Cartão
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Configure uma nova taxa de pagamento informando o tipo e a bandeira do cartão.</p>
                        </div>
                        <div>
                            <a href="{{ route('taxa-cartao.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('taxa-cartao.store')
                    !!}
                    
                    @include('taxa_cartao._forms')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
