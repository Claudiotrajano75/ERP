<div class="row g-3">
    <div class="col-md-3">
        {!!Form::text('nome', 'Nome')
        ->required()
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('loja_id', 'ID Loja')
        ->required()
        !!}
    </div>

    <div class="col-md-7">
        {!!Form::text('descricao_breve', 'Descrição curta')
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('rua', 'Rua')->required()
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('numero', 'Número')->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::text('bairro', 'Bairro')->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('cidade_id', 'Cidade')
        ->required()
        ->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('cep', 'CEP')
        ->attrs(['class' => 'cep'])
        ->required()
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('telefone', 'Telefone')
        ->attrs(['class' => 'fone'])
        ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::tel('email', 'Email')
        ->required()
        ->type('email')
        !!}
    </div>

    <div class="col-12 mt-4">
        <h5 class="d-flex align-items-center gap-2"><i class="ri-share-line"></i> Redes Sociais</h5>
    </div>
    <div class="col-md-4">
        {!!Form::text('link_instagram', 'Link do instagram')
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('link_facebook', 'Link do facebook')
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('link_whatsapp', 'Link do whatsApp')
        !!}
    </div>

    <div class="col-12 mt-4">
        <h5 class="d-flex align-items-center gap-2"><i class="ri-truck-line"></i> Dados de Entrega e Loja</h5>
    </div>

  <!--   <div class="col-md-2">
        {!!Form::text('desconto_padrao_boleto', '%Desconto para boleto')
        ->attrs(['class' => 'percentual'])
        ->value(isset($item) ? $item->desconto_padrao_boleto : '')
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::text('desconto_padrao_pix', '%Desconto para pix')
        ->attrs(['class' => 'percentual'])
        ->value(isset($item) ? $item->desconto_padrao_pix : '')
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::text('desconto_padrao_cartao', '%Desconto para cartão')
        ->attrs(['class' => 'percentual'])
        ->value(isset($item) ? $item->desconto_padrao_cartao : '')
        !!}
    </div> -->

    <div class="col-md-3">
        {!!Form::text('frete_gratis_valor', 'Valor para frete grátis')
        ->attrs(['class' => 'moeda'])
        ->value(isset($item) ? __moeda($item->frete_gratis_valor) : '')
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('status', 'Status da loja', [1 => 'Ativa', 0 => 'Desativada'])
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('habilitar_retirada', 'Habilitar retirada', [1 => 'Sim', 0 => 'Não'])
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('notificacao_novo_pedido', 'Notificação de novo pedido', [1 => 'Sim', 0 => 'Não'])
        ->attrs(['class' => 'form-select'])
        !!}
    </div>

    <div class="col-12 mt-4">
        <h5 class="d-flex align-items-center gap-2"><i class="ri-money-dollar-circle-line"></i> Pagamentos</h5>
    </div>

    <div class="col-lg-4 col-12">
        <label for="">Tipos de pagamento</label>
        <select required class="select2 form-control select2-multiple" name="tipos_pagamento[]" data-toggle="select2" multiple="multiple" id="tipos_pagamento">
            @foreach(\App\Models\EcommerceConfig::tiposPagamento() as $t)
            <option @if($item != null) @if(in_array($t, $item->tipos_pagamento)) selected @endif @endif value="{{ $t }}">{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        {!!Form::text('mercadopago_public_key', 'Mercado Pago Public Key')->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('mercadopago_access_token', 'Mercado Pago Access Token')->required()
        !!}
    </div>

    <div class="col-12 mt-4">
        <h5 class="d-flex align-items-center gap-2"><i class="ri-file-list-3-line"></i> Textos e Termos</h5>
    </div>
    <div class="col-md-4">
        <div class="card form-input border shadow-none" style="background-color:#fafbff; border-radius:10px;">
            <div class="card-body text-center p-3">
                <div class="preview mb-3 position-relative">
                    <button type="button" id="btn-remove-imagem" class="btn btn-danger btn-sm position-absolute" style="top:-10px; right:-10px; border-radius:50%; width:30px; height:30px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"><i class="ri-close-line"></i></button>
                    @isset($item)
                    <img id="file-ip-1-preview" src="{{ $item->logo_img }}" style="max-height:120px; object-fit:contain; border-radius:8px;">
                    @else
                    <img id="file-ip-1-preview" src="/imgs/no-image.png" style="max-height:120px; object-fit:contain; border-radius:8px;">
                    @endif
                </div>
                <label for="file-ip-1" class="btn btn-outline-primary btn-sm w-100"><i class="ri-upload-2-line"></i> Selecionar Logo</label>
                <input type="file" id="file-ip-1" name="logo_image" accept="image/*" onchange="showPreview(event);" style="display:none;">
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-md-12">
                {!!Form::textarea('politica_privacidade', 'Política de Privacidade')
                ->attrs(['rows' => '5', 'class' => 'tiny'])
                !!}
            </div>
            <div class="col-md-12">
                {!!Form::textarea('termos_condicoes', 'Termos e Condições')
                ->attrs(['rows' => '5', 'class' => 'tiny'])
                !!}
            </div>
        </div>
    </div>

    <div class="col-md-12 d-none d-deposito mt-3">
        {!!Form::textarea('dados_deposito', 'Dados para depósito bancário')
        ->attrs(['rows' => '5', 'class' => 'tiny'])
        !!}
    </div>

    <div class="col-12 mt-4 text-end">
        <button type="submit" class="btn btn-success px-5 fw-bold" id="btn-store"><i class="ri-save-3-line"></i> Salvar Configurações</button>
    </div>
</div>

@section('js')
<script src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(function(){
        tinymce.init({ selector: 'textarea.tiny', language: 'pt_BR'})

        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none')
        }, 500)
        changeTipo()
    })

    $('#tipos_pagamento').change(() => {
        changeTipo()
    })

    function changeTipo(){
        let tipos = $('#tipos_pagamento').val()

        if(tipos.includes("Depósito bancário")){
            $('.d-deposito').removeClass('d-none')
        }else{
            $('.d-deposito').addClass('d-none')
        }
    }
</script>
@endsection
