<div class="modal fade modal-pdv modal-pdv-modern" id="pagamento_multiplo" tabindex="-1" role="dialog" aria-labelledby="pagamentoMultiploLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>                        <h5 class="modal-title" id="pagamentoMultiploLabel">
                        <i class="ri-wallet-3-line"></i> Pagamento Múltiplo
                    </h5>
                    <p class="modulo-header-subtitle">Divida o valor da venda em várias formas de pagamento</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="pdv-header-total">
                        <i class="ri-shopping-cart-line me-1"></i>
                        <strong class="total-venda-modal">@isset($item) {{__moeda($item->valor_total)}}@else R$ 0,00 @endisset</strong>
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">

                {{-- Resumo --}}
                <div class="row g-2 mb-3">
                    <div class="col-md-4 col-6">
                        <div class="pdv-modal-stat">
                            <span class="pdv-modal-stat-label"><i class="ri-calculator-line me-1"></i>Soma dos Pagamentos</span>
                            <strong class="pdv-modal-stat-value sum-payment">R$ 0,00</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="pdv-modal-stat">
                            <span class="pdv-modal-stat-label"><i class="ri-subtract-line me-1"></i>Diferença</span>
                            <strong class="pdv-modal-stat-value sum-restante">R$ 0,00</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="pdv-modal-stat">
                            <span class="pdv-modal-stat-label"><i class="ri-shopping-cart-line me-1"></i>Total da Venda</span>
                            <strong class="pdv-modal-stat-value">
                                @isset($item)
                                    {{__moeda($item->valor_total)}}
                                @else
                                    R$ 0,00
                                @endisset
                            </strong>
                        </div>
                    </div>
                </div>

                {{-- Adicionar pagamento --}}
                <div class="pdv-payment-add">
                    <div class="pdv-payment-add-title"><i class="ri-add-circle-line"></i> Adicionar Forma de Pagamento</div>
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            <label class="form-label">Tipo de Pagamento</label>
                            {!! Form::select('tipo_pagamento_row', '', ['' => 'Selecione'] + $tiposPagamento)->attrs(['class' => 'form-select pdv-pag-tp', 'id' => 'inp-tipo_pagamento_row']) !!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">Valor</label>
                            {!! Form::tel('valor_row', '')->attrs(['class' => 'moeda form-control', 'id' => 'inp-valor_row', 'placeholder' => '0,00']) !!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">Vencimento</label>
                            {!! Form::date('data_vencimento_row', '')->attrs(['class' => 'form-control', 'id' => 'inp-data_vencimento_row'])->value(date('Y-m-d')) !!}
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label">Observação</label>
                            {!! Form::text('observacao_row', '')->attrs(['class' => 'form-control', 'id' => 'inp-observacao_row', 'placeholder' => 'Opcional']) !!}
                        </div>
                        <div class="col-md-2 col-12">
                            <button type="button" class="btn btn-primary w-100 btn-add-payment">
                                <i class="ri-add-line me-1"></i> Adicionar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabela de pagamentos --}}
                <div class="table-responsive mt-3">
                    <table class="table table-payment pdv-modal-table mb-0">
                        <thead>
                            <tr>
                                <th>Tipo de Pagamento</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Observações</th>
                                <th class="text-center" style="width:70px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($item)
                            @if($item != null && $item->fatura && sizeof($item->fatura) > 1)
                            @foreach ($item->fatura as $fatura)
                            <tr>
                                <td>
                                    <span class="pdv-pag-badge"><i class="ri-bank-card-line"></i> {{ App\Models\Nfce::getTipoPagamento($fatura->tipo_pagamento) }}</span>
                                    <input type="hidden" name="tipo_pagamento_row[]" value="{{ $fatura->tipo_pagamento }}">
                                </td>
                                <td>
                                    <input readonly type="date" name="data_vencimento_row[]" class="form-control form-control-sm data_multiplo" value="{{ $fatura->data_vencimento }}">
                                </td>
                                <td>
                                    <input readonly type="text" name="valor_integral_row[]" class="form-control form-control-sm valor_integral text-end fw-bold" value="{{ __moeda($fatura->valor) }}">
                                </td>
                                <td>
                                    <input readonly type="text" name="obs_row[]" class="form-control form-control-sm" value="{{ $fatura->obs_row }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Remover parcela">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endisset
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-modal-multiplo" data-bs-dismiss="modal">
                    <i class="ri-check-double-line me-1"></i> Salvar Pagamentos
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
