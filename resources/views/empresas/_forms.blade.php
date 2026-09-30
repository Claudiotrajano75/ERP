@section('css')
<style>
    /* ─── Seções do formulário ─── */
    .uf-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        border-bottom: 1px solid #eef0f6;
        padding-bottom: 10px;
        margin-top: 10px;
        margin-bottom: 16px;
    }
    .uf-section-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 15px;
    }
    .uf-sub-title {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-top: 8px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ─── Campos do Form ─── */
    .uf-field { margin-bottom: 14px; }
    .uf-field label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .uf-field label.required:after { content: " *"; color: #ef4444; font-weight: 700; }
    .uf-field .form-control,
    .uf-field .form-select {
        border-radius: 9px;
        border: 1px solid #d1d5db;
        padding: 9px 12px;
        font-size: 13px;
        color: #111827;
        transition: border-color .15s ease, box-shadow .15s ease;
        background-color: #fff;
    }
    .uf-field .form-control:focus,
    .uf-field .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        outline: 0;
    }

    /* ─── Input Groups (Senha / Token) ─── */
    .uf-input-group {
        display: flex;
        align-items: stretch;
    }
    .uf-input-group .form-control {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    .uf-input-group .input-group-text,
    .uf-input-group .btn {
        border: 1px solid #d1d5db;
        border-left: 0;
        border-top-right-radius: 9px !important;
        border-bottom-right-radius: 9px !important;
        background-color: #f8fafc;
        color: #475569;
        display: flex;
        align-items: center;
        padding: 0 14px;
        cursor: pointer;
        text-decoration: none;
    }
    .uf-input-group .btn-token-refresh {
        background: #4338ca;
        color: #fff;
        border-color: #4338ca;
    }
    .uf-input-group .btn-token-refresh:hover {
        background: #3730a3;
        color: #fff;
    }

    /* ─── Upload de Certificado ─── */
    .file-certificado label {
        padding: 10px 16px;
        width: 100%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        text-transform: uppercase;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 22px;
        cursor: pointer;
        border-radius: 9px;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: .3px;
        transition: all .2s ease;
        box-shadow: 0 2px 6px rgba(79,70,229,.2);
    }
    .file-certificado label:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79,70,229,.3);
    }
    .file-certificado input[type="file"] { display: none; }

    /* ─── Ações Rodapé ─── */
    .uf-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
        margin-top: 20px;
        border-top: 1px solid #eef0f6;
    }
    .uf-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 20px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        border: 0;
        cursor: pointer;
        transition: transform .1s ease, box-shadow .15s ease;
        text-decoration: none;
    }
    .uf-btn:hover { transform: translateY(-1px); }
    .uf-btn-primary { background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; box-shadow: 0 4px 10px rgba(22,163,74,.25); }
    .uf-btn-primary:hover { color: #fff; box-shadow: 0 6px 14px rgba(22,163,74,.35); }
</style>
@endsection

<div class="row g-3">
    {{-- ═══ IDENTIFICAÇÃO DA EMPRESA ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-building-line"></i> Identificação da Empresa
        </div>
    </div>

    <div class="col-md-3 uf-field">
        {!!Form::tel('cpf_cnpj', 'CPF/CNPJ')
        ->attrs(['class' => 'form-control cpf_cnpj', 'id' => 'inp-cpf_cnpj', 'placeholder' => '00.000.000/0000-00'])
        ->required()
        !!}
    </div>

    <div class="col-md-5 uf-field">
        {!!Form::text('nome', 'Razão Social')
        ->attrs(['class' => 'form-control', 'id' => 'inp-nome', 'placeholder' => 'Razão Social da Empresa'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::text('nome_fantasia', 'Nome Fantasia')
        ->attrs(['class' => 'form-control', 'id' => 'inp-nome_fantasia', 'placeholder' => 'Nome Fantasia'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::tel('ie', 'Inscrição Estadual (IE)')
        ->attrs(['class' => 'form-control', 'id' => 'inp-ie', 'data-mask' => '000000000000000000', 'placeholder' => 'Apenas números'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::select('tributacao', 'Regime Tributário', App\Models\Empresa::tiposTributacao())
        ->attrs(['class' => 'form-select', 'id' => 'inp-tributacao'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::select('simples_hibrido', 'Simples Híbrido', [0 => 'Não', 1 => 'Sim'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-simples_hibrido'])
        !!}
        <small class="text-muted d-block mt-1 fs-11">A partir de 2027: IBS/CBS fora do DAS</small>
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::select('status', 'Status da Empresa', [1 => 'Ativo', 0 => 'Desativado'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-status'])
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::select('ambiente', 'Ambiente de Emissão', [2 => 'Homologação', 1 => 'Produção'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-ambiente'])
        !!}
    </div>

    @isset($segmentos)
        <div class="col-md-4 uf-field">
            {!!Form::select('segmento_id', 'Segmento Comercial', ['' => 'Selecione'] + $segmentos->pluck('nome', 'id')->all())
            ->attrs(['class' => 'form-select', 'id' => 'inp-segmento_id'])
            ->value(isset($item) ? (sizeof($item->segmentos) > 0 ? $item->segmentos[0]->segmento_id : '') : '')
            !!}
        </div>
    @endisset

    {{-- ═══ ENDEREÇO E CONTATO ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-map-pin-line"></i> Endereço e Contato
        </div>
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('cep', 'CEP')
        ->attrs(['class' => 'form-control cep', 'id' => 'inp-cep', 'placeholder' => '00000-000'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::text('rua', 'Logradouro (Rua/Av.)')
        ->attrs(['class' => 'form-control', 'id' => 'inp-rua', 'placeholder' => 'Rua, Avenida, etc'])
        ->required()
        !!}
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('numero', 'Número')
        ->attrs(['class' => 'form-control', 'id' => 'inp-numero', 'placeholder' => '123'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::text('bairro', 'Bairro')
        ->attrs(['class' => 'form-control', 'id' => 'inp-bairro', 'placeholder' => 'Bairro'])
        ->required()
        !!}
    </div>

    <div class="col-md-4 uf-field">
        {!!Form::text('complemento', 'Complemento')
        ->attrs(['class' => 'form-control', 'id' => 'inp-complemento', 'placeholder' => 'Sala, Galpão, etc'])
        !!}
    </div>

    <div class="col-md-4 uf-field">
        @isset($item)
            {!!Form::select('cidade_id', 'Cidade')
            ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])
            ->options($item != null && $item->cidade ? [$item->cidade_id => $item->cidade->info] : [])
            ->required()
            !!}
        @else
            {!!Form::select('cidade_id', 'Cidade')
            ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])
            ->required()
            !!}
        @endisset
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::text('email', 'Email de Contato')
        ->attrs(['class' => 'form-control', 'id' => 'inp-email', 'placeholder' => 'contato@empresa.com'])
        ->value(isset($item) ? $item->email : '')
        !!}
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('celular', 'Telefone/Celular')
        ->attrs(['class' => 'form-control fone', 'id' => 'inp-celular', 'placeholder' => '(00) 00000-0000'])
        ->required()
        !!}
    </div>

    {{-- ═══ PARÂMETROS FISCAIS ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-shield-flash-line"></i> Parâmetros de Emissão Fiscal
        </div>
    </div>

    <!-- NFe -->
    <div class="col-12 mb-1">
        <div class="uf-sub-title"><i class="ri-file-text-line text-primary"></i> Nota Fiscal Eletrônica (NFe)</div>
        <div class="row g-2">
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_nfe_producao', 'Última NFe Produção')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_nfe_producao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_nfe_homologacao', 'Última NFe Homologação')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_nfe_homologacao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_serie_nfe', 'Série NFe')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_serie_nfe'])
                !!}
            </div>
        </div>
    </div>

    <!-- NFCe -->
    <div class="col-12 mb-1">
        <div class="uf-sub-title"><i class="ri-shopping-bag-3-line text-primary"></i> Nota Fiscal de Consumidor (NFCe)</div>
        <div class="row g-2">
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_nfce_producao', 'Última NFCe Produção')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_nfce_producao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_nfce_homologacao', 'Última NFCe Homologação')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_nfce_homologacao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_serie_nfce', 'Série NFCe')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_serie_nfce'])
                !!}
            </div>
        </div>
    </div>

    <!-- CTe -->
    <div class="col-12 mb-1">
        <div class="uf-sub-title"><i class="ri-truck-line text-primary"></i> Conhecimento de Transporte (CTe)</div>
        <div class="row g-2">
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_cte_producao', 'Última CTe Produção')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_cte_producao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_cte_homologacao', 'Última CTe Homologação')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_cte_homologacao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_serie_cte', 'Série CTe')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_serie_cte'])
                !!}
            </div>
        </div>
    </div>

    <!-- MDFe -->
    <div class="col-12 mb-1">
        <div class="uf-sub-title"><i class="ri-folders-line text-primary"></i> Manifesto Fiscal (MDFe)</div>
        <div class="row g-2">
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_mdfe_producao', 'Última MDFe Produção')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_mdfe_producao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_ultima_mdfe_homologacao', 'Última MDFe Homologação')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_ultima_mdfe_homologacao'])
                !!}
            </div>
            <div class="col-md-4 uf-field">
                {!!Form::tel('numero_serie_mdfe', 'Série MDFe')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero_serie_mdfe'])
                !!}
            </div>
        </div>
    </div>

    <!-- CSC e Token -->
    <div class="col-12 mb-1">
        <div class="uf-sub-title"><i class="ri-key-line text-primary"></i> Integração e CSC (NFCe)</div>
        <div class="row g-2">
            <div class="col-md-6 uf-field">
                {!!Form::text('csc', 'Código de Segurança do Contribuinte (CSC)')
                ->attrs(['class' => 'form-control', 'id' => 'inp-csc', 'placeholder' => 'Código CSC'])
                !!}
            </div>
            <div class="col-md-6 uf-field">
                {!!Form::text('csc_id', 'Identificador do CSC (CSC ID)')
                ->attrs(['class' => 'form-control', 'id' => 'inp-csc_id', 'data-mask' => '0000000000', 'placeholder' => '000001'])
                !!}
            </div>
        </div>
    </div>

    {{-- ═══ CREDENCIAIS DO GESTOR ═══ --}}
    @if(__isMaster() || __isContador())
        @if(!isset($edit))
            <div class="col-12">
                <div class="uf-section-title">
                    <i class="ri-user-settings-line"></i> Credenciais do Usuário Gestor
                </div>
            </div>

            <div class="col-md-3 uf-field">
                {!!Form::text('usuario', 'Nome do Usuário')
                ->attrs(['class' => 'form-control', 'id' => 'inp-usuario', 'placeholder' => 'Nome completo'])
                ->required()
                !!}
            </div>

            <div class="col-md-3 uf-field">
                {!!Form::text('email', 'Email de Acesso')
                ->attrs(['class' => 'form-control', 'id' => 'inp-email_acesso', 'placeholder' => 'login@empresa.com'])
                ->required()
                !!}
            </div>

            <div class="col-md-3 uf-field">
                <label class="form-label required">Senha</label>
                <div class="uf-input-group" id="show_hide_password">
                    <input required type="password" class="form-control" name="password" autocomplete="off" placeholder="••••••••"
                        @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif>
                    <a class="input-group-text"><i class='ri-eye-line'></i></a>
                </div>
            </div>

            <div class="col-md-3 uf-field">
                <label class="form-label required">Repetir Senha</label>
                <div class="uf-input-group" id="show_hide_password_r">
                    <input required type="password" class="form-control" name="password_confirmation" autocomplete="off" placeholder="••••••••">
                    <a class="input-group-text"><i class='ri-eye-line'></i></a>
                </div>
            </div>
        @endif
    @endif

    {{-- ═══ CERTIFICADO DIGITAL E SEGURANÇA ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-keyhole-line"></i> Certificado Digital e Segurança
        </div>
    </div>

    <div class="col-md-4 file-certificado uf-field">
        {!! Form::file('certificado', 'Certificado Digital (.pfx)')->value(isset($item) ? false : true) !!}
        <span class="text-danger d-block mt-1 fs-12" id="filename"></span>
    </div>

    <div class="col-md-3 uf-field">
        {!! Form::text('senha_certificado', 'Senha do Certificado')
        ->attrs(['class' => 'form-control', 'id' => 'inp-senha_certificado', 'placeholder' => 'Senha do .pfx'])
        !!}
    </div>

    <div class="col-md-5 uf-field">
        <label class="form-label">Token de API da Empresa</label>
        <div class="uf-input-group">
            <input readonly type="text" class="form-control" id="api_token" name="token"
                value="{{ isset($item) ? $item->token : '' }}" placeholder="Token gerado para integrações">
            <button type="button" class="btn btn-token-refresh" id="btn_token" title="Gerar Novo Token">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>

    {{-- ═══ BOTÃO SALVAR ═══ --}}
    <div class="col-12">
        <div class="uf-actions">
            <button type="submit" class="uf-btn uf-btn-primary px-5" id="btn-store">
                <i class="ri-save-line"></i> Salvar Empresa
            </button>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function () {
        $("#show_hide_password a").on('click', function (event) {
            event.preventDefault();
            let input = $('#show_hide_password input');
            let icon = $('#show_hide_password i');
            if (input.attr("type") == "text") {
                input.attr('type', 'password');
                icon.addClass("ri-eye-line").removeClass("ri-eye-off-line");
            } else {
                input.attr('type', 'text');
                icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
            }
        });

        $("#show_hide_password_r a").on('click', function (event) {
            event.preventDefault();
            let input = $('#show_hide_password_r input');
            let icon = $('#show_hide_password_r i');
            if (input.attr("type") == "text") {
                input.attr('type', 'password');
                icon.addClass("ri-eye-line").removeClass("ri-eye-off-line");
            } else {
                input.attr('type', 'text');
                icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
            }
        });
    });

    $('#btn_token').click(() => {
        let token = generate_token(25);
        swal({
            title: "Atenção",
            text: "Esse token é o responsável pela comunicação com a API!",
            icon: "warning",
            buttons: ["Cancelar", "Confirmar"],
            dangerMode: true
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

    $(document).on("blur", "#inp-cpf_cnpj", function () {
        let cpf_cnpj = $(this).val().replace(/[^0-9]/g, '');

        if (cpf_cnpj.length == 14) {
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
                .done((data) => {
                    if (data != null) {
                        let ie = '';
                        if (data.estabelecimento.inscricoes_estaduais && data.estabelecimento.inscricoes_estaduais.length > 0) {
                            ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual;
                        }

                        $('#inp-ie').val(ie);
                        $('#inp-nome').val(data.razao_social);
                        $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia);
                        $("#inp-rua").val(data.estabelecimento.tipo_logradouro + " " + data.estabelecimento.logradouro);
                        $('#inp-numero').val(data.estabelecimento.numero);
                        $("#inp-bairro").val(data.estabelecimento.bairro);
                        let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                        $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9));
                        $('#inp-email').val(data.estabelecimento.email);
                        $('#inp-celular').val(data.estabelecimento.telefone1);

                        if (data.estabelecimento.cidade && data.estabelecimento.cidade.ibge_id) {
                            findCidade(data.estabelecimento.cidade.ibge_id);
                        }
                    }
                })
                .fail((err) => {
                    console.log(err);
                });
        }
    });

    function findCidade(codigo_ibge) {
        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
            .done((res) => {
                var newOption = new Option(res.info, res.id, false, false);
                $('#inp-cidade_id').append(newOption).trigger('change');
            })
            .fail((err) => {
                console.log(err);
            });
    }
</script>
@endsection