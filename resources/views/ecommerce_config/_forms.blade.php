<div class="row">
    <div class="col-md-12">
        <!-- ═══ NAVEGAÇÃO POR ABAS ═══ -->
        <ul class="nav nav-pills nav-tabs-custom mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="pill" href="#tab-geral" role="tab" aria-selected="true">
                    <i class="ri-store-2-line"></i>
                    <span>Dados da Loja</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#tab-visual" role="tab" aria-selected="false">
                    <i class="ri-image-line"></i>
                    <span>Visual & Logo</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#tab-pagamentos" role="tab" aria-selected="false">
                    <i class="ri-bank-card-line"></i>
                    <span>Pagamentos & Checkout</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#tab-operacao" role="tab" aria-selected="false">
                    <i class="ri-truck-line"></i>
                    <span>Operação & Entregas</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#tab-social" role="tab" aria-selected="false">
                    <i class="ri-share-line"></i>
                    <span>Redes Sociais</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#tab-termos" role="tab" aria-selected="false">
                    <i class="ri-file-text-line"></i>
                    <span>Políticas & Termos</span>
                </a>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <!-- ══════════════ ABA 1: DADOS DA LOJA & ENDEREÇO ══════════════ -->
            <div class="tab-pane fade show active" id="tab-geral" role="tabpanel">
                
                <!-- IDENTIFICAÇÃO BÁSICA -->
                <div class="card card-secao-ecommerce mb-4">
                    <div class="card-header">
                        <h5><i class="ri-information-line text-primary"></i> Informações Principais</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::text('nome', 'Nome da Loja')->required()->placeholder('Ex: Minha Loja Virtual')!!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('loja_id', 'ID Único da Loja (Slug)')->required()->placeholder('Ex: minhaloja')!!}
                            </div>
                            <div class="col-md-5">
                                {!!Form::text('descricao_breve', 'Descrição Curta / Slogan')->placeholder('Ex: A melhor loja de moda online')!!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ENDEREÇO DA SEDE -->
                <div class="card card-secao-ecommerce mb-4">
                    <div class="card-header">
                        <h5><i class="ri-map-pin-line text-primary"></i> Endereço & Localização</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                {!!Form::tel('cep', 'CEP')->attrs(['class' => 'cep'])->required()->placeholder('00000-000')!!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('rua', 'Logradouro / Rua')->required()->placeholder('Ex: Av. Paulista')!!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::text('numero', 'Número')->required()->placeholder('Ex: 1000')!!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('bairro', 'Bairro')->required()->placeholder('Ex: Bela Vista')!!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::select('cidade_id', 'Cidade / UF')
                                ->required()
                                ->attrs(['class' => 'select2 form-control cidade_id'])
                                ->options($item != null && $item->cidade ? [$item->cidade_id => $item->cidade->info] : [])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CANAIS DE CONTATO -->
                <div class="card card-secao-ecommerce">
                    <div class="card-header">
                        <h5><i class="ri-customer-service-2-line text-primary"></i> Contatos Oficiais da Loja</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                {!!Form::tel('telefone', 'Telefone / WhatsApp de Atendimento')->attrs(['class' => 'fone'])->required()->placeholder('(00) 00000-0000')!!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::tel('email', 'E-mail de Atendimento e Suporte')->required()->type('email')->placeholder('contato@sualoja.com.br')!!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══════════════ ABA 2: VISUAL & LOGOMARCA ══════════════ -->
            <div class="tab-pane fade" id="tab-visual" role="tabpanel">
                <div class="card card-secao-ecommerce">
                    <div class="card-header">
                        <h5><i class="ri-image-edit-line text-primary"></i> Logomarca da Loja Virtual</h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-12">
                                <div class="logo-upload-box">
                                    <div class="logo-preview-wrapper">
                                        <button type="button" id="btn-remove-imagem" class="btn-remove-logo" title="Remover Logo">
                                            <i class="ri-close-line"></i>
                                        </button>
                                        @isset($item)
                                        <img id="file-ip-1-preview" src="{{ $item->logo_img }}" alt="Logo da Loja">
                                        @else
                                        <img id="file-ip-1-preview" src="/imgs/no-image.png" alt="Sem Imagem">
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 fs-14">Logotipo Principal</h6>
                                    <p class="text-muted fs-12 mb-3">Recomendado: Formato PNG transparente ou SVG, resolução mín. 400x150px.</p>
                                    
                                    <label for="file-ip-1" class="dash-btn dash-btn-primary px-4" style="cursor: pointer;">
                                        <i class="ri-upload-2-line"></i> Selecionar Imagem
                                    </label>
                                    <input type="file" id="file-ip-1" name="logo_image" accept="image/*" onchange="showPreview(event);" style="display:none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 3: PAGAMENTOS & MERCADO PAGO ══════════════ -->
            <div class="tab-pane fade" id="tab-pagamentos" role="tabpanel">
                
                <!-- TIPOS DE PAGAMENTO -->
                <div class="card card-secao-ecommerce mb-4">
                    <div class="card-header">
                        <h5><i class="ri-bank-card-2-line text-primary"></i> Formas de Pagamento Ativas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold"><i class="ri-checkbox-multiple-line"></i> Métodos Aceitos no Checkout</label>
                                <select required class="select2 form-control select2-multiple" name="tipos_pagamento[]" data-toggle="select2" multiple="multiple" id="tipos_pagamento">
                                    @foreach(\App\Models\EcommerceConfig::tiposPagamento() as $t)
                                    <option @if($item != null) @if(in_array($t, $item->tipos_pagamento)) selected @endif @endif value="{{ $t }}">{{ $t }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted fs-12 mt-1 d-block">Selecione uma ou mais opções (Pix, Cartão de Crédito, Boleto, Depósito bancário).</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MERCADO PAGO -->
                <div class="card card-secao-ecommerce mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="ri-shield-check-line text-primary"></i> Credenciais Mercado Pago</h5>
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Gateway Integrado</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                {!!Form::text('mercadopago_public_key', 'Mercado Pago Public Key')->required()->placeholder('APP_USR-xxxxxx-xxxxxx')!!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('mercadopago_access_token', 'Mercado Pago Access Token')->required()->placeholder('APP_USR-xxxxxx-xxxxxx')!!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DEPÓSITO BANCÁRIO (DINÂMICO) -->
                <div class="card card-secao-ecommerce d-none d-deposito">
                    <div class="card-header">
                        <h5><i class="ri-bank-line text-primary"></i> Instruções para Depósito / Transferência Bancária</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                {!!Form::textarea('dados_deposito', 'Dados Bancários para o Cliente')
                                ->attrs(['rows' => '5', 'class' => 'tiny'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══════════════ ABA 4: OPERAÇÃO & ENTREGAS ══════════════ -->
            <div class="tab-pane fade" id="tab-operacao" role="tabpanel">
                <div class="card card-secao-ecommerce">
                    <div class="card-header">
                        <h5><i class="ri-settings-4-line text-primary"></i> Regras de Funcionamento & Frete</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                {!!Form::select('status', 'Status da Loja Online', [1 => 'Ativa (Aberta)', 0 => 'Desativada (Manutenção)'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('frete_gratis_valor', 'Valor Mínimo para Frete Grátis (R$)')
                                ->attrs(['class' => 'form-control moeda'])
                                ->value(isset($item) ? __moeda($item->frete_gratis_valor) : '')
                                ->placeholder('0,00')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('habilitar_retirada', 'Permitir Retirada na Loja Física', [1 => 'Sim, habilitar', 0 => 'Não, apenas envio'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('notificacao_novo_pedido', 'Notificar por E-mail a cada Pedido', [1 => 'Sim, enviar alerta', 0 => 'Não notificar'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 5: REDES SOCIAIS ══════════════ -->
            <div class="tab-pane fade" id="tab-social" role="tabpanel">
                <div class="card card-secao-ecommerce">
                    <div class="card-header">
                        <h5><i class="ri-share-forward-line text-primary"></i> Links das Redes Sociais</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="ri-instagram-line text-danger"></i> Link do Instagram</label>
                                {!!Form::text('link_instagram', '')->attrs(['class' => 'form-control', 'placeholder' => 'https://instagram.com/sualoja'])!!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="ri-facebook-circle-line text-primary"></i> Link do Facebook</label>
                                {!!Form::text('link_facebook', '')->attrs(['class' => 'form-control', 'placeholder' => 'https://facebook.com/sualoja'])!!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="ri-whatsapp-line text-success"></i> Link do WhatsApp</label>
                                {!!Form::text('link_whatsapp', '')->attrs(['class' => 'form-control', 'placeholder' => 'https://wa.me/5500000000000'])!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 6: POLÍTICAS & TERMOS ══════════════ -->
            <div class="tab-pane fade" id="tab-termos" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card card-secao-ecommerce h-100">
                            <div class="card-header">
                                <h5><i class="ri-file-shield-line text-primary"></i> Política de Privacidade (LGPD)</h5>
                            </div>
                            <div class="card-body">
                                {!!Form::textarea('politica_privacidade', '')
                                ->attrs(['rows' => '8', 'class' => 'tiny'])
                                !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-secao-ecommerce h-100">
                            <div class="card-header">
                                <h5><i class="ri-file-list-3-line text-primary"></i> Termos e Condições de Uso</h5>
                            </div>
                            <div class="card-body">
                                {!!Form::textarea('termos_condicoes', '')
                                ->attrs(['rows' => '8', 'class' => 'tiny'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
    <div class="col-12 modulo-actions d-flex align-items-center justify-content-end gap-2 mt-4">
        <button type="submit" class="dash-btn dash-btn-primary px-5 fw-bold" id="btn-store">
            <i class="ri-save-3-line"></i> Salvar Configurações da Loja
        </button>
    </div>
</div>

@section('js')
<script src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(function(){
        tinymce.init({ selector: 'textarea.tiny', language: 'pt_BR' });

        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none');
        }, 500);
        changeTipo();
    });

    $('#tipos_pagamento').change(() => {
        changeTipo();
    });

    function changeTipo(){
        let tipos = $('#tipos_pagamento').val() || [];

        if(tipos.includes("Depósito bancário")){
            $('.d-deposito').removeClass('d-none');
        }else{
            $('.d-deposito').addClass('d-none');
        }
    }

    function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            $('#btn-remove-imagem').removeClass('d-none');
        }
    }

    $('#btn-remove-imagem').click(function(){
        $('#file-ip-1-preview').attr('src', '/imgs/no-image.png');
        $('#file-ip-1').val('');
        $(this).addClass('d-none');
    });
</script>
@endsection
