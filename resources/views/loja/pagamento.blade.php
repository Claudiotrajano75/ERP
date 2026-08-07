@extends('loja.default', ['title' => 'Pagamento'])

@section('css')
<style type="text/css">
    .luxe-form .payment-method-card { margin-bottom: 0; }
    .body-pay { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection

@section('content')

{{-- Barra de etapas --}}
@include('loja.partials.checkout_steps', ['checkoutStep' => 3])

<div class="section py-5 text-dark">
    <div class="container">
        <div class="row g-4">

            <!-- ─── RESUMO DO PEDIDO (DIREITA NO DESKTOP) ─── -->
            <div class="col-lg-5 col-12 order-lg-last">
                <div class="summary-card">
                    <div class="summary-title">Seu Pedido</div>

                    <div style="max-height: 280px; overflow-y: auto; margin-bottom: 8px;">
                        @foreach($carrinho->itens as $i)
                        <div class="summary-item">
                            <div style="min-width:0">
                                <div class="si-name text-truncate">{{ $i->produto->nome }}</div>
                                <div class="si-meta">Qtd: {{ number_format($i->quantidade, 0) }} x R$ {{ __moeda($i->valor_unitario) }}</div>
                            </div>
                            <div class="si-price">R$ {{ __moeda($i->sub_total) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="summary-row-line">
                        <span>Entrega ({{ $carrinho->tipo_frete != 0 ? $carrinho->tipo_frete : 'Frete' }}):</span>
                        <strong style="color:var(--luxe-brown)">R$ {{ __moeda($carrinho->valor_frete) }}</strong>
                    </div>

                    <div class="summary-row-line total mb-3">
                        <span>TOTAL GERAL:</span>
                        <span class="text-gold" id="checkout-total-val">R$ {{ __moeda($carrinho->valor_total) }}</span>
                    </div>

                    @if($carrinho->endereco)
                    <div class="mb-3">
                        <div class="summary-section-label">Endereço de Entrega</div>
                        <div class="summary-info-box">
                            {{ $carrinho->endereco->info ?? '' }}
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="form-label fw-bold fs-12 mb-1" style="color:var(--luxe-brown)">Observações do Pedido</label>
                        <textarea class="form-control" id="observacao" rows="3" placeholder="Instruções de entrega, referências, etc."
                                  style="border:1.5px solid var(--border-light);border-radius:var(--radius-sm);font-size:13px;color:var(--luxe-brown)"></textarea>
                    </div>
                </div>
            </div>

            <!-- ─── OPÇÕES DE PAGAMENTO (ESQUERDA) ─── -->
            <div class="col-lg-7 col-12">
                <div class="content-card luxe-form">
                    <div class="card-heading">Forma de Pagamento</div>
                    <p class="card-subheading">Escolha a opção que preferir para finalizar sua compra com segurança.</p>

                    <!-- Seletores das formas de pagamento -->
                    <div class="payment-methods-grid">
                        @if(in_array('Pix', $tiposPagamento))
                        <div class="payment-method-card active" onclick="selectPay('pix', this)">
                            <i class="ri-qr-code-line"></i> PIX
                        </div>
                        @endif
                        @if(in_array('Boleto', $tiposPagamento))
                        <div class="payment-method-card" onclick="selectPay('boleto', this)">
                            <i class="ri-bill-line"></i> Boleto
                        </div>
                        @endif
                        @if(in_array('Cartão de credito', $tiposPagamento))
                        <div class="payment-method-card" onclick="selectPay('cartao', this)">
                            <i class="ri-bank-card-line"></i> Cartão
                        </div>
                        @endif
                        @if(in_array('Depósito bancário', $tiposPagamento))
                        <div class="payment-method-card" onclick="selectPay('deposito', this)">
                            <i class="ri-bank-line"></i> Depósito
                        </div>
                        @endif
                    </div>

                    <!-- Formulários de Pagamento -->
                    <div style="border-top:1px solid var(--border-light);padding-top:24px">

                        <!-- PIX Form -->
                        <div class="body-pay" id="pay-pix">
                            <h5 class="fw-bold mb-3" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Pagamento via PIX</h5>
                            <p class="text-muted fs-13 mb-4">O QR Code e a chave copia e cola serão gerados na próxima etapa.</p>
                            <form method="post" id="paymentFormPix" action="{{ route('loja.pagamento-pix', ['link='.$config->loja_id]) }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="observacao" class="observacao">
                                <div class="col-md-6">
                                    <label class="required">Nome</label>
                                    <input required name="payerFirstName" type="text" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">Sobrenome</label>
                                    <input required name="payerLastName" type="text" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">E-mail</label>
                                    <input required name="payerEmail" type="email" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">CPF / CNPJ</label>
                                    <input required name="docNumber" type="tel" class="form-control cpf_cnpj">
                                </div>
                                <input type="hidden" name="docType" value="CPF">
                                <div class="col-12 mt-4">
                                    <button class="btn-luxe btn-submit-checkout" type="submit">
                                        Confirmar e Pagar com PIX
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- BOLETO Form -->
                        <div class="body-pay d-none" id="pay-boleto">
                            <h5 class="fw-bold mb-3" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Pagamento via Boleto</h5>
                            <p class="text-muted fs-13 mb-4">O boleto bancário será gerado após a confirmação do pedido.</p>
                            <form method="post" id="paymentFormBoleto" action="{{ route('loja.pagamento-boleto', ['link='.$config->loja_id]) }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="observacao" class="observacao">
                                <div class="col-md-6">
                                    <label class="required">Nome</label>
                                    <input required name="payerFirstName" type="text" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">Sobrenome</label>
                                    <input required name="payerLastName" type="text" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">E-mail</label>
                                    <input required name="payerEmail" type="email" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">CPF / CNPJ</label>
                                    <input required name="docNumber" type="tel" class="form-control cpf_cnpj">
                                </div>
                                <input type="hidden" name="docType" value="CPF">
                                <div class="col-12 mt-4">
                                    <button class="btn-luxe btn-submit-checkout" type="submit">
                                        Confirmar e Gerar Boleto
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- CARTÃO Form -->
                        <div class="body-pay d-none" id="pay-cartao">
                            <h5 class="fw-bold mb-3" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Pagamento via Cartão de Crédito</h5>
                            <form method="post" id="paymentFormCartao" action="{{ route('loja.pagamento-cartao', ['link='.$config->loja_id]) }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="observacao" class="observacao">
                                <div class="col-md-8">
                                    <label class="required">Titular do Cartão</label>
                                    <input required id="cardholderName" data-checkout="cardholderName" type="text" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="required">CPF / CNPJ do Titular</label>
                                    <input required name="docNumber" id="docNumber" type="tel" class="form-control cpf_cnpj cpf-cartao">
                                </div>
                                <input type="hidden" id="docType3" value="CPF">
                                <div class="col-md-6">
                                    <label class="required">E-mail</label>
                                    <input required name="email" id="email" type="email" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="required">Número do Cartão</label>
                                    <div class="input-group">
                                        <input required data-checkout="cardNumber" id="cardNumber" type="tel" class="form-control" data-mask="0000 0000 0000 0000" placeholder="0000 0000 0000 0000">
                                        <span class="input-group-text bg-white" style="border-left: none;"><img id="band-img" style="width: 30px;"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="required">Parcelas</label>
                                    <select required name="installments" id="installments" class="form-select"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="required">Código de Segurança (CVC)</label>
                                    <input required data-checkout="securityCode" id="securityCode" type="tel" class="form-control" data-mask="000" placeholder="123">
                                </div>
                                <div class="col-md-3">
                                    <label class="required">Vencimento (Mês/Ano)</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input required placeholder="MM" data-checkout="cardExpirationMonth" id="cardExpirationMonth" type="tel" class="form-control" data-mask="00">
                                        </div>
                                        <div class="col-6">
                                            <input required placeholder="AA" data-checkout="cardExpirationYear" id="cardExpirationYear" type="tel" class="form-control" data-mask="00">
                                        </div>
                                    </div>
                                </div>
                                <div style="visibility: hidden; height:0;" class="form-group">
                                    <select class="custom-select" id="issuer" name="issuer" data-checkout="issuer"></select>
                                </div>
                                <input type="hidden" name="paymentMethodId" id="paymentMethodId"/>
                                <input type="hidden" name="transactionAmount" id="transactionAmount" value="{{$carrinho->valor_total}}" />
                                <div class="col-12 mt-4">
                                    <button id="btn-cartao" class="btn-luxe btn-submit-checkout" type="submit">
                                        Pagar com Cartão de Crédito
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- DEPÓSITO Form -->
                        <div class="body-pay d-none" id="pay-deposito">
                            <h5 class="fw-bold mb-3" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Pagamento via Depósito / Transferência</h5>
                            <div class="summary-info-box mb-4">
                                <div class="summary-section-label">Dados para Depósito:</div>
                                <div style="font-size:13px;line-height:1.8;color:var(--luxe-tan)">
                                    {!! $config->dados_deposito !!}
                                </div>
                            </div>
                            <form method="post" id="paymentFormDeposito" action="{{ route('loja.pagamento-deposito', ['link='.$config->loja_id]) }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="observacao" class="observacao">
                                <p class="text-muted fs-13">Clique abaixo para confirmar a intenção de depósito e finalizar seu pedido.</p>
                                <div class="col-12">
                                    <button class="btn-luxe btn-submit-checkout" type="submit">
                                        Confirmar e Finalizar Pedido
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script type="text/javascript">
    $(function(){
        // Configurações Mercado Pago
        window.Mercadopago.setPublishableKey('{{ $config->mercadopago_public_key }}');
    });

    function selectPay(tipo, element){
        $('.payment-method-card').removeClass('active');
        $(element).addClass('active');

        $('.body-pay').addClass('d-none');
        $('#pay-' + tipo).removeClass('d-none');
    }

    $('#cardNumber').keyup(() => {
        let cardnumber = $('#cardNumber').val().replaceAll(" ", "");
        if (cardnumber.length >= 6) {
            let bin = cardnumber.substring(0,6);

            window.Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethod);
        }
    });

    function setPaymentMethod(status, response) {
        if (status == 200) {
            let paymentMethod = response[0];
            document.getElementById('paymentMethodId').value = paymentMethod.id;

            $('#band-img').attr("src", paymentMethod.thumbnail);
            getIssuers(paymentMethod.id);
        }
    }

    function getIssuers(paymentMethodId) {
        window.Mercadopago.getIssuers(paymentMethodId, setIssuers);
    }

    function setIssuers(status, response) {
        if (status == 200) {
            let issuerSelect = document.getElementById('issuer');
            $('#issuer').html('');
            response.forEach( issuer => {
                let opt = document.createElement('option');
                opt.text = issuer.name;
                opt.value = issuer.id;
                issuerSelect.appendChild(opt);
            });

            getInstallments(
                document.getElementById('paymentMethodId').value,
                document.getElementById('transactionAmount').value,
                issuerSelect.value
            );
        }
    }

    function getInstallments(paymentMethodId, transactionAmount, issuerId){
        window.Mercadopago.getInstallments({
            "payment_method_id": paymentMethodId,
            "amount": parseFloat(transactionAmount),
            "issuer_id": parseInt(issuerId)
        }, setInstallments);
    }

    function setInstallments(status, response){
        if (status == 200) {
            document.getElementById('installments').options.length = 0;
            response[0].payer_costs.forEach( payerCost => {
                let opt = document.createElement('option');
                opt.text = payerCost.recommended_message;
                opt.value = payerCost.installments;
                document.getElementById('installments').appendChild(opt);
            });
        }
    }

    doSubmit = false;
    document.getElementById('paymentFormCartao').addEventListener('submit', getCardToken);

    function getCardToken(event){
        event.preventDefault();
        if(!doSubmit){
            let docNumber = $('.cpf-cartao').val().replace(/[^0-9]/g,'');
            $('.cpf-cartao').val(docNumber);
            setTimeout(() => {
                let $form = document.getElementById('paymentFormCartao');
                window.Mercadopago.createToken($form, setCardTokenAndPay);
                return false;
            }, 50);
        }
    }

    function setCardTokenAndPay(status, response) {
        if (status == 200 || status == 201) {
            let form = document.getElementById('paymentFormCartao');
            let card = document.createElement('input');
            card.setAttribute('name', 'token');
            card.setAttribute('type', 'hidden');
            card.setAttribute('value', response.id);
            form.appendChild(card);
            doSubmit = true;
            $('.btn-submit-checkout').attr('disabled', true);
            form.submit();
        } else {
            Swal.fire("Erro", "Por favor, verifique os dados do cartão de crédito digitados.", "error");
        }
    }

    $('#observacao').on('input', function() {
        $('.observacao').val($(this).val());
    });
</script>
@endsection
