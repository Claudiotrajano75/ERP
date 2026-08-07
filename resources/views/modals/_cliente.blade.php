<div class="modal fade modal-pdv modal-pdv-modern modal-select-cliente" id="cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="clienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="clienteLabel">
                        <i class="ri-group-line"></i> Selecionar Cliente
                    </h5>
                    <p class="modulo-header-subtitle">Busque e selecione o cliente da venda</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label"><i class="ri-user-search-line me-1"></i>Cliente</label>
                        <div class="input-group flex-nowrap">
                            <select id="inp-cliente_id" name="cliente_id" class="cliente_id form-select">
                                @if(isset($item) && $item->cliente)
                                <option value="{{ $item->cliente_id }}">{{ $item->cliente->razao_social }}</option>
                                @endif
                            </select>
                            @can('clientes_create')
                            <button class="btn btn-dark btn-novo-cliente" type="button" title="Novo Cliente">
                                <i class="ri-add-circle-fill"></i>
                            </button>
                            @endcan
                        </div>
                    </div>

                    @if($cashback == 1)
                    <div class="cashback-div d-none col-12">
                        <div class="pdv-payment-add">
                            <div class="pdv-payment-add-title"><i class="ri-vip-crown-line"></i> Cashback disponível</div>
                            <p class="info_cash_back text-success mb-2"></p>
                            <div class="row g-2 align-items-end">
                                <div class="col-12">
                                    <p class="mb-1 fs-13">Valor de cashback disponível para uso: <strong class="text-success valor-cashback-disponivel">R$ 0,00</strong></p>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label"><i class="ri-vip-crown-line me-1"></i>Valor de cashback</label>
                                    {!! Form::text('valor_cashback', '')
                                    ->attrs(['class' => 'moeda form-control']) !!}
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label"><i class="ri-shield-check-line me-1"></i>Permitir crédito</label>
                                    {!! Form::select('permitir_credito', '', ['1' => 'Sim', '0' => 'Não'])
                                    ->attrs(['class' => 'form-select']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success cliente-venda" data-bs-dismiss="modal">
                    <i class="ri-check-double-line me-1"></i> Selecionar
                </button>
            </div>
        </div>
    </div>
</div>
