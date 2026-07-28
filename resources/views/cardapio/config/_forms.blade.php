<style type="text/css">
    .card-config-section {
        background: #fdfdfd;
        border: 1px solid #eef0f5;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .card-config-section:hover {
        border-color: #dbe0ee;
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
    }
    .section-title-premium {
        font-size: 14px;
        font-weight: 700;
        color: #24243e;
        border-bottom: 2px solid #5c6bc0;
        padding-bottom: 6px;
        display: inline-block;
    }
    .form-label-premium {
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #5a5a7a;
        margin-bottom: 4px;
    }
    .card-config-section .form-control,
    .card-config-section .form-select {
        border-radius: 8px;
        border-color: #e0e3eb;
        font-size: 13px;
        padding: 8px 12px;
        transition: all 0.15s ease;
    }
    .card-config-section .form-control:focus,
    .card-config-section .form-select:focus {
        border-color: #302b63;
        box-shadow: 0 0 0 3px rgba(48,43,99,0.08);
    }
    /* Logo Upload Style */
    .logo-upload-wrapper {
        border: 2px dashed #cfd8dc;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #fafafa;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .logo-upload-wrapper:hover {
        border-color: #5c6bc0;
        background: #f5f6fe;
    }
    .logo-preview-img {
        max-width: 140px;
        max-height: 140px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        background: #fff;
    }
    .btn-remove-logo {
        position: absolute;
        top: 8px;
        right: 8px;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        padding: 0;
        line-height: 22px;
        font-weight: bold;
    }
</style>

<div class="row g-4">
    
    {{-- ═══ COLUNA DA ESQUERDA: FORMULÁRIOS ═══ --}}
    <div class="col-lg-9 col-12">
        
        {{-- SECTION 1: DADOS DO RESTAURANTE --}}
        <div class="card card-config-section p-4 mb-4">
            <h5 class="mb-4"><span class="section-title-premium"><i class="ri-store-2-line me-1"></i> Dados do Restaurante</span></h5>
            
            <div class="row g-3">
                <div class="col-md-5 col-12">
                    <label class="form-label-premium required">Nome do Restaurante</label>
                    {!!Form::text('nome_restaurante', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Cantina Bella Italia'])!!}
                </div>

                <div class="col-md-4 col-12">
                    <label class="form-label-premium required">Telefone de Contato</label>
                    {!!Form::tel('telefone', '')->attrs(['class' => 'form-control fone', 'placeholder' => '(00) 00000-0000'])->required()!!}
                </div>

                <div class="col-md-3 col-12">
                    <label class="form-label-premium">Internacionalização</label>
                    {!!Form::select('intercionalizar', '', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])!!}
                </div>

                <div class="col-12">
                    <label class="form-label-premium required">Descrição Geral do Restaurante</label>
                    {!!Form::text('descricao_restaurante_pt', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Breve descrição de boas-vindas do seu estabelecimento'])!!}
                </div>

                @if(__isInternacionalizar(Auth::user()->empresa))
                <div class="col-md-6 col-12">
                    <label class="form-label-premium">Descrição em Inglês (EN)</label>
                    {!!Form::text('descricao_restaurante_en', '')->attrs(['class' => 'form-control', 'placeholder' => 'Establishment description in English'])!!}
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label-premium">Descrição em Espanhol (ES)</label>
                    {!!Form::text('descricao_restaurante_es', '')->attrs(['class' => 'form-control', 'placeholder' => 'Descripción del establecimiento en Español'])!!}
                </div>
                @endif
            </div>
        </div>

        {{-- SECTION 2: ENDEREÇO & LOCALIZAÇÃO --}}
        <div class="card card-config-section p-4 mb-4">
            <h5 class="mb-4"><span class="section-title-premium"><i class="ri-map-pin-line me-1"></i> Endereço e Localização</span></h5>
            
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label-premium required">Rua / Logradouro</label>
                    {!!Form::text('rua', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Av. Paulista'])!!}
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label-premium required">Número</label>
                    {!!Form::text('numero', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 1500'])!!}
                </div>

                <div class="col-md-4 col-6">
                    <label class="form-label-premium required">Bairro</label>
                    {!!Form::text('bairro', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Centro'])!!}
                </div>

                <div class="col-12">
                    <label class="form-label-premium required">Cidade</label>
                    {!!Form::select('cidade_id', '')->required()->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])->attrs(['class' => 'form-control'])!!}
                </div>
            </div>
        </div>

        {{-- SECTION 3: REDES SOCIAIS & TÉCNICO --}}
        <div class="card card-config-section p-4">
            <h5 class="mb-4"><span class="section-title-premium"><i class="ri-link-m me-1"></i> Redes Sociais & Integrações</span></h5>
            
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label-premium">Instagram (URL ou Usuário)</label>
                    {!!Form::text('link_instagran', '')->attrs(['class' => 'form-control', 'placeholder' => '@restaurante'])!!}
                </div>

                <div class="col-md-4 col-12">
                    <label class="form-label-premium">Facebook (URL)</label>
                    {!!Form::text('link_facebook', '')->attrs(['class' => 'form-control', 'placeholder' => 'facebook.com/restaurante'])!!}
                </div>

                <div class="col-md-4 col-12">
                    <label class="form-label-premium">WhatsApp (Link Direto ou Número)</label>
                    {!!Form::text('link_whatsapp', '')->attrs(['class' => 'form-control', 'placeholder' => '5511999999999'])!!}
                </div>

                <div class="col-md-4 col-12">
                    <label class="form-label-premium">Cálculo de Pizza Fracionada</label>
                    {!!Form::select('valor_pizza', '', [
                        'divide' => 'Média dos valores (Divide)', 
                        'valor_maior' => 'Valor da maior fatia'
                    ])->attrs(['class' => 'form-select'])!!}
                </div>

                <div class="col-md-8 col-12">
                    <label class="form-label-premium required d-flex align-items-center justify-content-between">
                        <span>Token de Integração do App</span>
                        <button type="button" class="btn btn-link btn-tooltip p-0 text-muted" data-toggle="tooltip" data-placement="top" title="Este Token estabelece a comunicação exclusiva entre o Aplicativo Móvel do Cardápio e este Servidor.">
                            <i class="ri-question-line fs-14"></i>
                        </button>
                    </label>
                    <div class="input-group">
                        <input readonly type="text" class="form-control" id="api_token" name="api_token" value="{{ isset($item) ? $item->api_token : '' }}">
                        <button type="button" class="btn btn-primary" id="btn_token" title="Gerar Novo Token">
                            <i class="ri-refresh-line align-middle"></i>
                        </button>
                    </div>
                    @if($errors->has('api_token'))
                    <label class="text-danger fs-11 mt-1">Campo Obrigatório</label>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ═══ COLUNA DA DIREITA: IMAGENS & LOGO ═══ --}}
    <div class="col-lg-3 col-12">
        <div class="card card-config-section p-4 text-center">
            <h5 class="mb-4 text-start"><span class="section-title-premium"><i class="ri-image-line me-1"></i> Logo Cardápio</span></h5>
            
            <div class="logo-upload-wrapper">
                <button type="button" id="btn-remove-imagem" class="btn btn-danger btn-sm btn-remove-logo d-none" title="Remover Imagem">×</button>
                <div class="mb-3">
                    @isset($item)
                    <img id="file-ip-1-preview" class="logo-preview-img" src="{{ $item->logo_img }}">
                    @else
                    <img id="file-ip-1-preview" class="logo-preview-img" src="/imgs/no-image.png">
                    @endif
                </div>
                <label for="file-ip-1" class="btn btn-sm btn-outline-primary w-100">
                    <i class="ri-upload-line align-middle me-1"></i> Escolher Logotipo
                </label>
                <input type="file" id="file-ip-1" name="logo_image" accept="image/*" class="d-none" onchange="showPreview(event);">
            </div>
            <p class="text-muted fs-11 mt-3 mb-0">Recomendado formato PNG transparente ou quadrado (512x512px).</p>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success btn-lg w-100 shadow" id="btn-store">
                <i class="ri-checkbox-circle-line align-middle me-1"></i> Salvar Configuração
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $('#btn_token').click(() => {
        let token = generate_token(25);
        swal({
            title: "Deseja gerar um novo token?"
            , text: "Atenção: Ao alterar o token, a comunicação com o aplicativo móvel atual será interrompida até que o novo token seja reconfigurado no app!"
            , icon: "warning"
            , buttons: ["Cancelar", "Confirmar e Alterar"]
            , dangerMode: true
        }).then((confirmed) => {
            if (confirmed) {
                $('#api_token').val(token);
            }
        });
    });

    function generate_token(length) {
        var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
        var b = [];
        for (var i = 0; i < length; i++) {
            var j = (Math.random() * (a.length - 1)).toFixed(0);
            b[i] = a[j];
        }
        return b.join("");
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
