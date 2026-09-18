@extends('layouts.app', ['title' => 'Pagar Conta'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-hand-coin-line"></i>
                                Registrar Pagamento de Conta #{{ $item->id }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Confira as informações da despesa e informe a conta de saída financeira.</p>
                        </div>
                        <div>
                            <a href="{{ route('conta-pagar.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <div class="card bg-light-subtle shadow-none border mb-4">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-sm-3 col-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Fornecedor</h6>
                                    <p class="fw-semibold text-dark mb-0 fs-13">{{ $item->fornecedor ? $item->fornecedor->razao_social : '--' }}</p>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Vencimento</h6>
                                    <p class="fw-semibold text-dark mb-0 fs-13">{{ __data_pt($item->data_vencimento, false) }}</p>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Valor Integral</h6>
                                    <p class="fw-bold text-danger mb-0 fs-14">R$ {{ __moeda($item->valor_integral) }}</p>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Saldo Restante</h6>
                                    <p class="fw-bold text-dark mb-0 fs-14">R$ {{ __moeda($item->valor_integral - $item->valor_pago) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!!Form::open()
                    ->put()
                    ->route('conta-pagar.pay-put', [$item->id])
                    !!}
                    
                    @include('conta-pagar._forms_pay')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/controla_conta_empresa.js"></script>
@endsection