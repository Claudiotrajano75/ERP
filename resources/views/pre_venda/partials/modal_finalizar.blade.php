<style>
    .fin-modal-header-card {
        background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
        border-radius: 8px;
        padding: 12px 16px;
        color: #fff;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(26, 35, 126, 0.15);
    }

    .fin-modal-header-card .info-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        opacity: 0.9;
    }

    .fin-modal-header-card .info-item strong {
        color: #fff;
        opacity: 1;
        font-weight: 600;
    }

    .fin-modal-header-card .info-item i {
        font-size: 14px !important;
    }

    .fin-section-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #1a237e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fin-section-title i {
        font-size: 15px;
    }

    .fin-section-title .badge-count {
        font-size: 10px;
        background: #1a237e;
        color: #fff;
        border-radius: 50px;
        padding: 1px 8px;
        margin-left: 4px;
        font-weight: 600;
    }

    .fin-card-item {
        background: #f8f9fc;
        border-radius: 6px;
        padding: 6px 10px;
        margin-bottom: 4px;
        border: 1px solid #eef0f5;
        transition: all 0.15s;
    }

    .fin-card-item:hover {
        border-color: #c5cae9;
        background: #f0f2f8;
    }

    .fin-card-item .item-img {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        object-fit: cover;
        background: #fff;
        border: 1px solid #e8eaf6;
    }

    .fin-card-item .item-name {
        font-weight: 600;
        color: #263238;
        font-size: 12px;
        line-height: 1.2;
    }

    .fin-card-item .item-meta {
        color: #78909c;
        font-size: 11px;
        margin-top: 1px;
    }

    .fin-card-item .item-value {
        font-weight: 700;
        color: #1a237e;
        font-size: 13px;
    }

    .fin-total-bar {
        background: #e8eaf6;
        border-radius: 8px;
        padding: 8px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
    }

    .fin-total-bar .total-label {
        color: #283593;
        font-weight: 600;
        font-size: 12px;
    }

    .fin-total-bar .total-value {
        color: #1a237e;
        font-weight: 800;
        font-size: 16px;
    }

    .fin-payment-row {
        background: #f8f9fc;
        border-radius: 6px;
        border: 1px solid #eef0f5;
        margin-bottom: 4px;
        transition: all 0.15s;
    }

    .fin-payment-row:hover {
        border-color: #c5cae9;
    }

    .fin-payment-row td {
        padding: 6px 8px;
        vertical-align: middle;
        border: none;
    }

    .fin-add-payment-btn {
        border: 1.5px dashed #c5cae9;
        border-radius: 8px;
        padding: 7px;
        background: transparent;
        color: #5c6bc0;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.15s;
        width: 100%;
        cursor: pointer;
    }

    .fin-add-payment-btn:hover {
        border-color: #5c6bc0;
        background: #f0f2f8;
        color: #283593;
    }

    .fin-action-btn {
        padding: 9px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .fin-action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none !important;
        transform: none !important;
    }

    .fin-action-btn.btn-nfe {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
    }

    .fin-action-btn.btn-nfe:hover:not(:disabled) {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.35);
    }

    .fin-action-btn.btn-nfce {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .fin-action-btn.btn-nfce:hover:not(:disabled) {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45);
    }

    .fin-action-btn.btn-finalizar {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
    }

    .fin-action-btn.btn-finalizar:hover:not(:disabled) {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    .fin-opcoes-card {
        background: #fff;
        border: 1px solid #eef0f5;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 10px;
    }

    .fin-opcoes-card label {
        font-weight: 600;
        color: #37474f;
        font-size: 11px;
        margin-bottom: 2px;
    }

    .fin-opcoes-card .form-control-sm,
    .fin-opcoes-card .form-select-sm {
        font-size: 12px;
        padding: 3px 8px;
        height: auto;
    }

    .fin-barcode-input {
        border: 2px solid #e8eaf6;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 12px;
        background: #fafbff;
        transition: border-color 0.15s;
    }

    .fin-barcode-input:focus {
        border-color: #5c6bc0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.12);
    }

    .fin-barcode-icon {
        background: #e8eaf6;
        border: none;
        padding: 4px 10px;
        border-radius: 6px 0 0 6px;
        color: #5c6bc0;
    }

    .fin-modal-hr {
        margin: 10px 0;
        border-color: #eef0f5;
    }

    .fin-btn-delete-row {
        border-radius: 5px;
        padding: 2px 6px;
    }

    .fin-modal-footer {
        border: none;
        padding: 10px 0 0 0;
        gap: 8px;
        display: flex;
        flex-wrap: wrap;
    }
</style>

<input type="hidden" id="confirma-itens" value="0">
<input type="hidden" id="pre_venda_id" name="pre_venda_id" value="{{ $item->id }}">

<!-- === HEADER CARD === -->
<div class="fin-modal-header-card">
    <div class="row align-items-center">
        <div class="col-md-7">
            <div class="info-item mb-1">
                <i class="ri-user-3-line"></i>
                <span>Cliente:
                    <strong>{{ $item->cliente_id ? $item->cliente->razao_social : 'Consumidor Final' }}</strong></span>
            </div>
            <div class="info-item">
                <i class="ri-calendar-line"></i>
                <span>Data: <strong>{{ __data_pt($item->created_at, 1) }}</strong></span>
            </div>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="info-item justify-content-md-end">
                <i class="ri-price-tag-3-line"></i>
                <span>Código: <strong>#{{ $item->codigo }}</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- === OPÇÕES === -->
<div class="fin-opcoes-card">
    <div class="row g-1 align-items-end">
        <div class="col-md-4">
            <label>Gerar Conta a Receber</label>
            {!! Form::select('gerar_conta_receber', '', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select form-select-sm']) !!}
        </div>
        <div class="col-md-4">
            <label>CPF/CNPJ na nota</label>
            {!! Form::text('cpf_nota', '')->attrs(['class' => 'form-control form-control-sm cpf_cnpj', 'placeholder' => 'CPF/CNPJ']) !!}
        </div>
        @if($config && $config->confirmar_itens_prevenda)
            <div class="col-md-4">
                <label>Conferir por código de barras</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text fin-barcode-icon"><i class="ri-barcode-box-line"></i></span>
                    <input type="text" id="inp-codigo_barras" class="form-control form-control-sm fin-barcode-input"
                        @if($item->status == 0) disabled @endif placeholder="Escaneie ou digite o código">
                </div>
            </div>
        @endif
    </div>
</div>

<!-- === ITENS === -->
<div class="fin-section-title">
    <i class="ri-shopping-cart-line"></i> Itens
    <span class="badge-count">{{ count($item->itens) }} {{ count($item->itens) == 1 ? 'item' : 'itens' }}</span>
</div>

<div class="row">
    @foreach ($item->itens as $i)
        <div class="col-12">
            <div class="fin-card-item">
                <div class="row align-items-center">
                    <div class="col-auto pe-1">
                        <img class="item-img" src="{{ $i->produto->img }}" alt="{{ $i->produto->nome }}">
                    </div>
                    <div class="col ps-1">
                        <div class="item-name">{{ $i->produto->nome }}</div>
                        <div class="item-meta">
                            Qtd: <strong>
                                @if($i->produto->unidade == 'UN')
                                    {{ number_format($i->quantidade, 0) }}
                                @else
                                    {{ $i->quantidade }}
                                @endif
                            </strong>
                            · Vl. un: <strong>{{ __moeda($i->valor) }}</strong>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <div class="item-value">{{ __moeda($i->quantidade * $i->valor) }}</div>
                    </div>
                </div>
                <input type="hidden" class="line_id" value="{{ $i->id }}">
                <input type="hidden" class="line_status" value="0">
                <input type="hidden" class="line_codigo_barras" value="{{ $i->produto->codigo_barras }}">
            </div>
        </div>
    @endforeach
</div>

<div class="fin-total-bar">
    <span class="total-label"><i class="ri-shopping-cart-line"></i> Total Produtos</span>
    <span class="total-value">{{ __moeda($item->valor_total) }}</span>
</div>

<!-- === FATURA / PAGAMENTOS === -->
<hr class="fin-modal-hr">

<div class="fin-section-title">
    <i class="ri-money-dollar-circle-line"></i> Pagamentos
    <span class="badge-count">{{ count($item->fatura) }} {{ count($item->fatura) == 1 ? 'parcela' : 'parcelas' }}</span>
</div>

<input type="hidden" id="modal_valor_total" value="{{ $item->valor_total }}">
<input type="hidden" id="modal_dinheiro_recebido" value="0">
<input type="hidden" id="modal_troco" value="0">

<div class="row">
    <div class="col-12">
        <div class="table-responsive" style="font-size:12px;">
            <table class="table table-dynamic" style="margin-bottom:0;">
                <thead>
                    <tr style="border-bottom: 1.5px solid #e8eaf6;">
                        <th
                            style="width:35%; border:none; color:#5c6bc0; font-size:11px; text-transform:uppercase; letter-spacing:0.3px;">
                            Pagamento</th>
                        <th
                            style="width:25%; border:none; color:#5c6bc0; font-size:11px; text-transform:uppercase; letter-spacing:0.3px;">
                            Vencimento</th>
                        <th
                            style="width:25%; border:none; color:#5c6bc0; font-size:11px; text-transform:uppercase; letter-spacing:0.3px;">
                            Valor</th>
                        <th style="width:10%; border:none;"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($item) && count($item->fatura) > 0)
                        @foreach ($item->fatura as $i)
                            <tr class="fin-payment-row dynamic-form">
                                <td>
                                    <select name="tipo_pagamento[]" class="form-select form-select-sm tipo_pagamento"
                                        style="font-size:12px; padding:2px 6px; height:auto;">
                                        <option value="">Selecione..</option>
                                        @foreach(\App\Models\Nfe::tiposPagamento() as $key => $c)
                                            <option @if($i->tipo_pagamento == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input value="{{ $i->vencimento }}" type="date" class="form-control form-control-sm"
                                        style="font-size:12px; padding:2px 6px; height:auto;" name="data_vencimento[]">
                                </td>
                                <td>
                                    <input value="{{ __moeda($i->valor_parcela) }}" type="tel"
                                        class="form-control form-control-sm moeda valor_parcela"
                                        style="font-size:12px; padding:2px 6px; height:auto;" name="valor_fatura[]">
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger fin-btn-delete-row btn-delete-row"
                                        @if($item->status == 0) disabled @endif>
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="fin-payment-row dynamic-form">
                            <td>
                                <select name="tipo_pagamento[]" class="form-select form-select-sm tipo_pagamento"
                                    style="font-size:12px; padding:2px 6px; height:auto;">
                                    <option value="">Selecione..</option>
                                    @foreach(\App\Models\Nfe::tiposPagamento() as $key => $c)
                                        <option @if($key == '01') selected @endif value="{{$key}}">{{$c}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input value="{{ date('Y-m-d') }}" type="date" class="form-control form-control-sm"
                                    style="font-size:12px; padding:2px 6px; height:auto;" name="data_vencimento[]">
                            </td>
                            <td>
                                <input value="{{ __moeda($item->valor_total) }}" type="tel"
                                    class="form-control form-control-sm moeda valor_parcela"
                                    style="font-size:12px; padding:2px 6px; height:auto;" name="valor_fatura[]">
                            </td>
                            <td class="text-center">
                                <button @if($item->status == 0) disabled @endif
                                    class="btn btn-sm btn-outline-danger fin-btn-delete-row btn-delete-row">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($item->status == 1)
    <div class="row">
        <div class="col-12 mt-1 mb-2">
            <button type="button" class="fin-add-payment-btn btn-add-tr">
                <i class="ri-add-circle-line" style="font-size:14px; vertical-align:middle;"></i>
                Adicionar forma de pagamento
            </button>
        </div>
    </div>
@endif

<!-- === PAINEL DE TOTAIS E TROCO === -->
<div class="row g-2 mt-1">
    <div class="col-6">
        <div class="fin-total-bar" style="background:#fff3e0; margin-top:0;">
            <span class="total-label" style="color:#e65100;"><i class="ri-bank-card-line"></i> Total Faturado</span>
            <span class="total-value" style="color:#bf360c;"><strong class="total_parcelas">R$ 0,00</strong></span>
        </div>
    </div>
    <div class="col-6">
        <div class="fin-total-bar" style="background:#e8f5e9; margin-top:0;">
            <span class="total-label" style="color:#2e7d32;"><i class="ri-shopping-bag-3-line"></i> Total
                Pré-venda</span>
            <span class="total-value" style="color:#1b5e20;"><strong>R$
                    {{ __moeda($item->valor_total) }}</strong></span>
        </div>
    </div>
    <div class="col-12 mt-2">
        <div id="painel-status-pagamento"
            class="p-2 rounded-3 text-center fw-bold fs-13 d-flex align-items-center justify-content-center gap-2"
            style="transition: all 0.2s;">
            <!-- Preenchido dinamicamente via JS -->
        </div>
    </div>
</div>

<!-- === FOOTER / AÇÕES === -->
@if($item->status == 1)
    <div class="fin-modal-footer">
        @if($item->cliente_id != null)
            <button type="button" class="fin-action-btn btn-nfe btn-sbm" id="gerar_nfe" data-bs-dismiss="modal">
                <i class="ri-file-list-3-line"></i> Gerar NFe
            </button>
        @endif
        <button type="button" class="fin-action-btn btn-nfce btn-sbm" id="gerar_nfce" data-bs-dismiss="modal">
            <i class="ri-receipt-line"></i> Gerar NFCe
        </button>
        <button type="button" class="fin-action-btn btn-finalizar finalizar_pre_venda btn-sbm" data-bs-dismiss="modal">
            <i class="ri-checkbox-circle-line"></i> Somente Finalizar
        </button>
    </div>
@endif