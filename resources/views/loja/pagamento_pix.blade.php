@extends('loja.default', ['title' => 'Pagamento PIX'])

@section('content')

<div class="section py-5 text-dark">
    <div class="container">
        <div class="row g-4">

            <!-- ─── DETALHES DO PEDIDO (DIREITA) ─── -->
            <div class="col-lg-5 col-12 order-lg-last">
                <div class="summary-card">
                    <div class="summary-title">Pedido #{{ $item->hash_pedido }}</div>

                    <div style="max-height: 280px; overflow-y: auto; margin-bottom: 8px;">
                        @foreach($item->itens as $i)
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
                        <span>Entrega:</span>
                        <strong style="color:var(--luxe-brown)">R$ {{ __moeda($item->valor_frete) }}</strong>
                    </div>

                    <div class="summary-row-line total">
                        <span>TOTAL:</span>
                        <span class="text-gold">R$ {{ __moeda($item->valor_total) }}</span>
                    </div>

                    @if($item->endereco)
                    <div style="border-top:1px solid var(--border-light);padding-top:16px;margin-top:16px">
                        <div class="summary-section-label">Endereço de Entrega</div>
                        <div class="summary-info-box">
                            {{ $item->endereco }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ─── FORMULÁRIO PIX (ESQUERDA) ─── -->
            <div class="col-lg-7 col-12">
                <div class="content-card luxe-form">
                    <div class="card-heading">Pagamento via PIX</div>
                    <p class="card-subheading">Confirme seus dados para gerar o QR Code de pagamento.</p>

                    <form method="post" id="paymentFormPix" action="{{ route('loja.pagamento-novo-pix', ['link='.$config->loja_id]) }}" class="row g-3">
                        @csrf
                        <input type="hidden" value="{{ $item->id }}" name="pedido_id">

                        <div class="col-md-6 col-12">
                            <label class="required">Nome</label>
                            <input required value="{{ $item->nome }}" name="payerFirstName" type="text" class="form-control">
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="required">Sobrenome</label>
                            <input required value="{{ $item->sobre_nome }}" name="payerLastName" type="text" class="form-control">
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="required">E-mail</label>
                            <input required value="{{ $item->email }}" name="payerEmail" type="email" class="form-control">
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="required">CPF / CNPJ</label>
                            <input required value="{{ $item->numero_documento }}" name="docNumber" type="tel" class="form-control cpf_cnpj">
                        </div>

                        <input type="hidden" name="docType" value="CPF">

                        <div class="col-12 mt-4">
                            <button id="btn-pix" class="btn-luxe" type="submit">
                                <i class="ri-qr-code-line"></i>
                                Confirmar e Gerar Chave PIX
                            </button>
                        </div>
                    </form>
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
        window.Mercadopago.setPublishableKey('{{ $config->mercadopago_public_key }}');
    });
</script>
@endsection
