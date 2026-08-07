@extends('loja.default', ['title' => $pedido->tipo_pagamento == 'cartao' ? 'Pagamento Finalizado' : 'Finalizando Pedido'])

@section('content')

<div class="section py-5 text-dark">
    <div class="container">
        <div class="row justify-content-center">

            <input type="hidden" value="{{$pedido->transacao_id}}" id="transacao_id">
            <input type="hidden" value="{{$pedido->status_pagamento}}" id="status">
            <input type="hidden" value="{{$pedido->tipo_pagamento}}" id="tipo_pagamento">

            <div class="col-lg-6 col-md-8 col-12">
                <div class="content-card text-center">

                    <div class="success-icon-wrap">
                        <i class="ri-checkbox-circle-fill"></i>
                    </div>

                    <h3 class="fw-bold mb-1" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Pedido Finalizado!</h3>
                    <p class="text-muted fs-13 mb-4">Número do Pedido: <strong>#{{ $pedido->hash_pedido }}</strong></p>

                    <div class="summary-info-box mb-4">
                        <span class="d-block mb-1" style="font-size:12px;color:var(--luxe-tan)0.6)">Valor Total do Pedido:</span>
                        <strong style="font-size:24px;color:#17a497">R$ {{ __moeda($pedido->valor_total) }}</strong>
                    </div>

                    <!-- ─── SEÇÃO PIX ─── -->
                    @if($pedido->tipo_pagamento == 'pix')
                    <div class="div-pix">
                        <p class="text-muted fs-13 mb-3">Escaneie o QR Code abaixo com o aplicativo do seu banco para pagar:</p>
                        <div class="mb-4 text-center">
                            <img src="data:image/jpeg;base64,{{$pedido->qr_code_base64}}"
                                 style="max-width:280px;width:100%;height:auto;border-radius:var(--radius-md);border:1px solid var(--border-light);padding:12px;background:var(--luxe-white)"/>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-12 d-block text-start mb-2" style="color:var(--luxe-brown)">Código Copia e Cola:</label>
                            <div class="input-group luxe-form">
                                <input type="text" readonly class="form-control" value="{{$pedido->qr_code}}" id="qrcode_input" style="font-size:12px;background:var(--luxe-cream)">
                                <button class="btn-luxe-dark" style="padding:10px 16px;border-radius:0 var(--radius-sm) var(--radius-sm) 0;font-size:12px" onclick="copyPixCode()" type="button">
                                    <i class="ri-file-copy-line align-middle me-1"></i> Copiar
                                </button>
                            </div>
                        </div>

                        <div class="text-center py-2">
                            <span class="spinner-border spinner-border-sm me-2 align-middle" style="color:var(--luxe-gold)" role="status"></span>
                            <span class="text-muted fs-13 align-middle">Aguardando confirmação do pagamento...</span>
                        </div>
                    </div>

                    <!-- ─── SEÇÃO BOLETO ─── -->
                    @elseif($pedido->tipo_pagamento == 'boleto')
                    <div class="py-3">
                        <p class="text-muted fs-13 mb-4">Seu boleto foi gerado com sucesso. Clique abaixo para abrir e imprimir.</p>
                        <a target="_blank" href="{{$pedido->link_boleto}}" class="btn-luxe-dark fs-14">
                            <i class="ri-printer-line align-middle me-2"></i> Imprimir Boleto Bancário
                        </a>
                        <input type="hidden" value="{{$pedido->link_boleto}}" id="link_boleto">
                    </div>

                    <!-- ─── SEÇÃO DEPÓSITO ─── -->
                    @elseif($pedido->tipo_pagamento == 'deposito')
                    <div class="text-start">
                        <div class="summary-info-box mb-4">
                            <div class="summary-section-label">Dados para Depósito:</div>
                            <div style="font-size:13px;line-height:1.8;color:var(--luxe-tan)">
                                {!! $config->dados_deposito !!}
                            </div>
                        </div>

                        <form method="post" action="{{ route('loja.enviar-comprovante') }}" enctype="multipart/form-data" class="mt-4 luxe-form">
                            @csrf
                            <input type="hidden" name="link" value="{{ $config->loja_id }}">
                            <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

                            <div class="mb-3">
                                <label for="file-upload" class="file-upload-luxe">
                                    <i class="ri-upload-cloud-2-line"></i>
                                    <span class="fu-title">Selecionar Comprovante</span>
                                    <span class="fu-hint">Arquivos suportados: Imagens e PDF</span>
                                </label>
                                <input required id="file-upload" name="file" type="file" accept="image/*, .pdf" style="display:none" />
                            </div>

                            <div class="mb-3 text-center">
                                <span class="fw-bold fs-13" id="filename" style="color:#17a497"></span>
                            </div>

                            <button class="btn-luxe" type="submit">
                                <i class="ri-check-double-line"></i>
                                Enviar Comprovante
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Pagamento Aprovado Mensagem (Suporte Real-time) -->
                    <div class="status-approved" style="display: none;">
                        <div class="my-4 text-center">
                            <div class="status-approved-box">
                                <i class="ri-checkbox-circle-fill"></i>
                                <h4>PAGAMENTO APROVADO!</h4>
                                <p class="text-muted fs-13 mb-0">Obrigado! Seu pagamento foi processado com sucesso.</p>
                            </div>
                        </div>
                        <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="btn-luxe-dark">
                            Voltar para a Página Inicial
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    var intervalVar = null;

    function copyPixCode(){
        const inputTest = document.querySelector("#qrcode_input");
        inputTest.select();
        document.execCommand('copy');

        Swal.fire({
            title: "Copiado!",
            text: "Código PIX copiado com sucesso.",
            icon: "success",
            confirmButtonColor: "#4254ba"
        });
    }

    if($('#status').val() !== "approved" && $('#tipo_pagamento').val() === "pix"){
        intervalVar = setInterval(() => {
            let transacao_id = $('#transacao_id').val();
            $.get(path_url+'api/ecommerce/consulta-pix/', {transacao_id: transacao_id})
            .done((success) => {
                if(success === "approved"){
                    clearInterval(intervalVar);
                    $('.div-pix').addClass('d-none');
                    $('.status-approved').removeClass('d-none');
                }
            })
            .fail((err) => {
                console.error(err);
            });
        }, 2000);
    }

    $(function(){
        setTimeout(() => {
            let linkBoleto = $('#link_boleto').val();
            if (linkBoleto) {
                window.open(linkBoleto);
            }
        }, 200);
    });

    $('#file-upload').change(function() {
        var filename = $(this).val().replace(/.*(\/|\\)/, '');
        $('#filename').html('<i class="ri-file-check-line me-1"></i> ' + filename);
    });
</script>
@endsection
