@extends('layouts.app', ['title' => 'Gerar Conta a Pagar'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-money-dollar-box-line"></i>
                                Lançar Salário em Contas a Pagar
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Vincule a apuração mensal de salários diretamente às saídas de fluxo de caixa da empresa.</p>
                        </div>
                        <div>
                            <a href="{{ route('apuracao-mensal.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->route('apuracao-mensal.set-conta', [$item->id])
                    ->put()
                    !!}
                    
                    <div class="row g-3 text-dark">
                        <div class="col-12">
                            <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
                                <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                                    <i class="ri-money-dollar-circle-line text-primary"></i> Dados da Despesa Financeira
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label required fw-semibold">Descrição da Conta / Finalidade</label>
                                        {!!Form::text('descricao', '')->value('Pagamento Salário - '. $item->funcionario->nome . ' ('.$item->mes.'/'.$item->ano.')')->required()->attrs(['class' => 'form-control'])!!}
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <label class="form-label required fw-semibold">Valor Integral (R$)</label>
                                        {!!Form::text('valor_integral', '')->attrs(['class' => 'form-control moeda fs-14 fw-bold text-success'])->value(__moedaInput($item->valor_final))->required()!!}
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <label class="form-label required fw-semibold">Data de Vencimento</label>
                                        {!!Form::date('data_vencimento', '')->required()->attrs(['class' => 'form-control', 'value' => date('Y-m-d')])!!}
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <label class="form-label required fw-semibold">Conta Já Liquidada?</label>
                                        {!!Form::select('status', '', ['0' => 'Não (Pendente)', '1' => 'Sim (Já Pago)'])->attrs(['class' => 'form-select'])->required()!!}
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <label class="form-label required fw-semibold">Meio / Canal de Pagamento</label>
                                        {!!Form::select('tipo_pagamento', '', App\Models\ContaReceber::tiposPagamento())->attrs(['class' => 'form-select'])->required()!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rodapé de Envio -->
                        <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
                            <a href="{{ route('apuracao-mensal.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-close-line me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                                <i class="ri-save-line me-1"></i> Confirmar Lançamento no Contas a Pagar
                            </button>
                        </div>
                    </div>
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection