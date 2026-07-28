<div class="row g-3">
    
    <div class="col-12 mt-1 mb-2">
        <div class="form-section-title"><i class="ri-information-fill"></i> Informações Básicas</div>
    </div>

    <div class="col-md-3">
        {!!Form::text('nome', 'Nome do Marketplace')->required()->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-md-5">
        {!!Form::text('descricao', 'Descrição')->required()->attrs(['class' => 'form-control'])!!}
    </div>
    
    <div class="col-md-4">
        {!!Form::text('email', 'Email Comercial')->required()->type('email')->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-12 mt-4 mb-2">
        <div class="form-section-title"><i class="ri-map-pin-2-fill"></i> Endereço e Localização</div>
    </div>

    <div class="col-md-2">
        {!!Form::tel('cep', 'CEP')->attrs(['class' => 'cep form-control'])->required()!!}
    </div>

    <div class="col-md-4">
        {!!Form::text('rua', 'Logradouro (Rua)')->required()->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-md-2">
        {!!Form::text('numero', 'Número')->required()->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-md-4">
        {!!Form::text('bairro', 'Bairro')->required()->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-md-4">
        {!!Form::select('cidade_id', 'Cidade')->required()->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])->attrs(['class' => 'form-select'])!!}
    </div>

    <div class="col-md-4">
        {!!Form::text('latitude', 'Latitude (Maps)')->attrs(['class' => 'coordenada form-control'])!!}
    </div>

    <div class="col-md-4">
        {!!Form::text('longitude', 'Longitude (Maps)')->attrs(['class' => 'coordenada form-control'])!!}
    </div>

    <div class="col-12 mt-4 mb-2">
        <div class="form-section-title"><i class="ri-truck-fill"></i> Entregas e Pedidos</div>
    </div>

    <div class="col-md-3">
        {!!Form::text('pedido_minimo', 'Valor de Pedido Mínimo')->attrs(['class' => 'moeda form-control'])->value(isset($item) ? __moeda($item->pedido_minimo) : '')!!}
    </div>
    <div class="col-md-3">
        {!!Form::text('valor_entrega', 'Valor de Entrega Padrão')->attrs(['class' => 'moeda form-control'])->value(isset($item) ? __moeda($item->valor_entrega) : '')->required()!!}
    </div>
    <div class="col-md-3">
        {!!Form::text('valor_entrega_gratis', 'Valor para Entrega Grátis')->attrs(['class' => 'moeda form-control'])->value(isset($item) ? __moeda($item->valor_entrega_gratis) : '')!!}
    </div>
    
    <div class="col-lg-3 col-12">
        <label class="form-label fw-semibold text-secondary">Tipo de entrega</label>
        <select required class="select2 form-control select2-multiple" name="tipo_entrega[]" data-toggle="select2" multiple="multiple" id="tipo_entrega">
            <option @if(in_array('balcao', (isset($item) && is_array($item->tipo_entrega) ? $item->tipo_entrega : []))) selected @endif value="balcao">Balcão</option>
            <option @if(in_array('delivery', (isset($item) && is_array($item->tipo_entrega) ? $item->tipo_entrega : []))) selected @endif value="delivery">Delivery</option>
        </select>
    </div>

    <div class="col-12 mt-4 mb-2">
        <div class="form-section-title"><i class="ri-settings-4-fill"></i> Configurações Gerais</div>
    </div>

    <div class="col-lg-3 col-12">
        <label class="form-label fw-semibold text-secondary">Segmentos</label>
        <select class="select2 form-control select2-multiple" name="segmento[]" data-toggle="select2" multiple="multiple" id="segmento">
            <option @if(in_array('produtos', (isset($item) && is_array($item->segmento) ? $item->segmento : []))) selected @endif value="produtos">Produtos</option>
            <option @if(in_array('servicos', (isset($item) && is_array($item->segmento) ? $item->segmento : []))) selected @endif value="servicos">Serviços</option>
        </select>
    </div>

    <div class="col-md-3">
        {!!Form::select('tipo_divisao_pizza', 'Divisão de Preço para Pizza', [
        'divide' => 'Divide (Média)', 'valor_maior' => 'Valor da mais cara'])->attrs(['class' => 'form-select'])!!}
    </div>
    
    <div class="col-md-3">
        {!!Form::select('notificacao_novo_pedido', 'Notificar Novo Pedido?', [1 => 'Sim', 0 => 'Não'])->attrs(['class' => 'form-select'])!!}
    </div>
    <div class="col-md-3">
        {!!Form::select('confirmacao_pedido_cliente', 'Confirmar pedido do cliente?', [1 => 'Sim', 0 => 'Não'])->attrs(['class' => 'form-select'])!!}
    </div>
    <div class="col-md-3">
        {!!Form::select('autenticacao_sms', 'Exigir Autenticação SMS no App?', [1 => 'Sim', 0 => 'Não'])->attrs(['class' => 'form-select'])!!}
    </div>
    
    <div class="col-md-2">
        {!!Form::select('status', 'Status da Loja', [1 => 'Aberta (Ativa)', 0 => 'Fechada'])->attrs(['class' => 'form-select'])!!}
    </div>
    
    <div class="col-md-3">
        {!!Form::text('loja_id', 'ID da Loja (Link)')->attrs(['class' => 'tooltipp form-control', 'title' => 'Para utilizar o delivery modelo link'])!!}
    </div>

    <div class="col-md-12 mt-2">
        {!!Form::text('funcionamento_descricao', 'Texto descritivo de horário de funcionamento')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Seg a Sáb das 18h às 23h'])!!}
    </div>

    <div class="col-12 mt-4 mb-2">
        <div class="form-section-title"><i class="ri-money-dollar-circle-fill"></i> Pagamentos (App/Link)</div>
    </div>

    <div class="col-lg-4 col-12">
        <label class="form-label fw-semibold text-secondary">Tipos de Pagamento Aceitos</label>
        <select class="select2 form-control select2-multiple" name="tipos_pagamento[]" data-toggle="select2" multiple="multiple" id="tipos_pagamento">
            @foreach(\App\Models\MarketPlaceConfig::tiposPagamento() as $t)
            <option @if(in_array($t, (isset($item) && is_array($item->tipos_pagamento) ? $item->tipos_pagamento : []))) selected @endif value="{{ $t }}">{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        {!!Form::text('mercadopago_public_key', 'Mercado Pago - Public Key')->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-md-4">
        {!!Form::text('mercadopago_access_token', 'Mercado Pago - Access Token')->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-12 mt-4 mb-2">
        <div class="form-section-title"><i class="ri-smartphone-fill"></i> App Customização & Sociais</div>
    </div>

    <div class="col-md-4">
        {!!Form::tel('telefone', 'WhatsApp (Contato)')->attrs(['class' => 'fone form-control'])->required()!!}
    </div>
    <div class="col-md-4">
        {!!Form::text('link_whatsapp', 'Link direto do WhatsApp')->attrs(['class' => 'form-control', 'placeholder' => 'https://wa.me/...'])!!}
    </div>
    <div class="col-md-4">
        {!!Form::text('link_instagram', 'Perfil do Instagram')->attrs(['class' => 'form-control', 'placeholder' => '@sua.loja'])!!}
    </div>
    <div class="col-md-4">
        {!!Form::text('link_facebook', 'Página do Facebook')->attrs(['class' => 'form-control'])!!}
    </div>

    <div class="col-md-4">
        {!!Form::text('cor_principal', 'Cor Principal (Tema)')->attrs(['class' => 'form-control', 'type' => 'color'])!!}
    </div>
    
    <div class="col-md-4">
        <label class="form-label fw-semibold text-secondary d-flex align-items-center gap-1">Token de API
            <button type="button" class="btn btn-link btn-tooltip btn-sm p-0 m-0 text-primary" data-toggle="tooltip" data-placement="top" title="Esse Token é inserido no app antes do build, para conectar o App com este servidor"><i class="ri-information-fill fs-16"></i></button>
        </label>
        <div class="input-group">
            <input readonly type="text" class="form-control bg-light text-secondary" id="api_token" name="api_token" value="{{ isset($item) ? $item->api_token : '' }}">
            <button type="button" class="btn btn-dark d-flex align-items-center gap-1" id="btn_token"><i class="ri-refresh-line"></i> Gerar</button>
        </div>
        @if($errors->has('api_token'))
        <small class="text-danger mt-1 d-block">O Token de API é obrigatório.</small>
        @endif
    </div>

    <div class="col-md-3 mt-3">
        <div class="card border shadow-sm p-3 form-input text-center">
            <label class="fw-semibold text-secondary mb-3 d-block text-start">Logotipo do Marketplace</label>
            <div class="preview mx-auto position-relative" style="width: 120px; height: 120px; border-radius: 12px; border: 1px dashed #c5cae9; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <button type="button" id="btn-remove-imagem" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-0" style="width: 24px; height: 24px; z-index: 10;"><i class="ri-close-line"></i></button>
                @isset($item)
                <img id="file-ip-1-preview" src="{{ $item->logo_img }}" class="img-fluid" style="object-fit: contain; width: 100%; height: 100%;">
                @else
                <img id="file-ip-1-preview" src="/imgs/no-image.png" class="img-fluid" style="opacity: 0.5;">
                @endif
            </div>
            <div class="mt-3">
                <label for="file-ip-1" class="btn btn-outline-primary btn-sm w-100"><i class="ri-upload-2-line"></i> Escolher Imagem</label>
                <input type="file" id="file-ip-1" name="logo_image" accept="image/*" onchange="showPreview(event);" class="d-none">
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-5 py-2 d-flex align-items-center gap-2 fw-semibold" id="btn-store" style="background: linear-gradient(135deg, #ff4b1f 0%, #ff9068 100%); border: none;">
            <i class="ri-save-3-line"></i> Salvar Configurações
        </button>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $('#btn_token').click(() => {

        let token = generate_token(25);
        swal({
            title: "Atenção", 
            text: "Esse token é o responsavel pela comunicação com a API, tenha atenção!!",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then((confirmed) => {
            if (confirmed) {
                $('#api_token').val(token)
            }
        });
    })

    function generate_token(length) {
        var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
        var b = [];
        for (var i = 0; i < length; i++) {
            var j = (Math.random() * (a.length - 1)).toFixed(0);
            b[i] = a[j];
        }
        return b.join("");
    }

</script>
@endsection
