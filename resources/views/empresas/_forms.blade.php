@section('css')
    <style type="text/css">
        /* Títulos de Seção */
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #4f46e5 !important;
            margin-top: 24px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            font-size: 18px;
        }

        /* Formulários de Filtro e Cadastro */
        .form-control,
        .form-select,
        select,
        input[type="text"],
        input[type="tel"],
        input[type="password"] {
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
            font-size: 13px !important;
            color: #334155 !important;
            transition: all 0.2s ease !important;
            box-shadow: none !important;
            background-color: #ffffff !important;
        }

        .form-control:focus,
        .form-select:focus,
        select:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        }

        .form-label,
        label {
            font-weight: 600 !important;
            color: #475569 !important;
            font-size: 13px !important;
            margin-bottom: 6px !important;
        }

        /* Botões */
        .btn {
            border-radius: 10px !important;
            font-weight: 500 !important;
            font-size: 13px !important;
            padding: 10px 20px !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-success {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            color: #fff !important;
        }

        .btn-success:hover {
            background-color: #059669 !important;
            border-color: #059669 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
        }

        .btn-info {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #fff !important;
            border-radius: 10px !important;
        }

        .btn-info:hover {
            background-color: #4338ca !important;
            border-color: #4338ca !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
        }

        .btn-danger {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #fff !important;
        }

        .btn-danger:hover {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
        }

        .btn-sm {
            padding: 6px 12px !important;
            font-size: 12px !important;
            border-radius: 8px !important;
        }

        /* Input Groups (Senha) */
        .input-group .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group-text {
            border: 1px solid #e2e8f0 !important;
            border-left: none !important;
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
            background-color: #ffffff !important;
            color: #475569 !important;
            display: flex;
            align-items: center;
            padding: 0 14px !important;
        }

        /* Estilização para o Upload de Certificado */
        .file-certificado label {
            padding: 10px 16px;
            width: 100%;
            background-color: #8833ff;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .file-certificado label:hover {
            background-color: #7026db;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(136, 51, 255, 0.2);
        }

        .file-certificado input[type="file"] {
            display: none;
        }

        hr {
            border-color: rgba(0, 0, 0, 0.06) !important;
            opacity: 1 !important;
            margin: 24px 0 !important;
        }
    </style>
@endsection

<div class="row g-3">
    <!-- SEÇÃO: DADOS DA EMPRESA -->
    <div class="col-12 mt-2">
        <h5 class="section-title"><i class="ri-building-line"></i> Identificação da Empresa</h5>
    </div>

    <div class="col-md-3">
        {!!Form::tel('cpf_cnpj', 'CPF/CNPJ')
    ->attrs(['class' => 'form-control cpf_cnpj'])
    ->required()
        !!}
    </div>

    <div class="col-md-5">
        {!!Form::text('nome', 'Razão Social')
    ->attrs(['class' => 'form-control'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('nome_fantasia', 'Nome Fantasia')
    ->attrs(['class' => 'form-control'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::tel('ie', 'Inscrição Estadual (IE)')
    ->attrs(['class' => 'form-control', 'data-mask' => '000000000000000000'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::select('tributacao', 'Regime Tributário', App\Models\Empresa::tiposTributacao())
    ->attrs(['class' => 'form-select'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::select('simples_hibrido', 'Simples Híbrido', [0 => 'Não', 1 => 'Sim'])
    ->attrs(['class' => 'form-select'])
        !!}
        <small class="text-muted d-block mt-1">A partir de 2027: IBS/CBS fora do DAS</small>
    </div>

    <div class="col-md-4">
        {!!Form::select('status', 'Status da Empresa', [1 => 'Ativo', 0 => 'Desativado'])
    ->attrs(['class' => 'form-select'])
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::select('ambiente', 'Ambiente de Emissão', [2 => 'Homologação', 1 => 'Produção'])
    ->attrs(['class' => 'form-select'])
        !!}
    </div>

    @isset($segmentos)
        <div class="col-md-4">
            {!!Form::select('segmento_id', 'Segmento Comercial', ['' => 'Selecione'] + $segmentos->pluck('nome', 'id')->all())
            ->attrs(['class' => 'form-select'])
            ->value(isset($item) ? (sizeof($item->segmentos) > 0 ? $item->segmentos[0]->segmento_id : '') : '')
            !!}
        </div>
    @endisset

    <!-- SEÇÃO: LOCALIZAÇÃO E CONTATO -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-map-pin-line"></i> Endereço e Contato</h5>
    </div>

    <div class="col-md-2">
        {!!Form::tel('cep', 'CEP')
    ->attrs(['class' => 'form-control cep'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('rua', 'Logradouro (Rua/Av.)')
    ->attrs(['class' => 'form-control'])
    ->required()
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('numero', 'Número')
    ->attrs(['class' => 'form-control'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('bairro', 'Bairro')
    ->attrs(['class' => 'form-control'])
    ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('complemento', 'Complemento')
    ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-4">
        @isset($item)
            {!!Form::select('cidade_id', 'Cidade')
            ->attrs(['class' => 'select2'])
            ->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])
            ->required()
            !!}
        @else
            {!!Form::select('cidade_id', 'Cidade')
            ->attrs(['class' => 'select2'])
            ->required()
            !!}
        @endisset
    </div>

    <div class="col-md-2">
        {!!Form::text('email', 'Email de Contato')
    ->attrs(['class' => 'form-control'])
    ->value(isset($item) ? $item->email : '')
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('celular', 'Telefone/Celular')
    ->attrs(['class' => 'form-control fone'])
    ->required()
        !!}
    </div>

    <!-- SEÇÃO: PARÂMETROS FISCAIS -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-shield-flash-line"></i> Parâmetros de Emissão Fiscal</h5>
    </div>

    <!-- NFe -->
    <div class="col-12 mb-2">
        <h6 class="text-dark fw-bold mb-2"><i class="ri-file-text-line text-primary me-1"></i> Nota Fiscal Eletrônica
            (NFe)</h6>
        <div class="row g-2">
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_nfe_producao', 'Última NFe Produção')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_nfe_homologacao', 'Última NFe Homologação')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_serie_nfe', 'Série NFe')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
        </div>
    </div>

    <!-- NFCe -->
    <div class="col-12 mb-2 mt-3">
        <h6 class="text-dark fw-bold mb-2"><i class="ri-shopping-bag-3-line text-primary me-1"></i> Nota Fiscal de
            Consumidor (NFCe)</h6>
        <div class="row g-2">
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_nfce_producao', 'Última NFCe Produção')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_nfce_homologacao', 'Última NFCe Homologação')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_serie_nfce', 'Série NFCe')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
        </div>
    </div>

    <!-- CTe -->
    <div class="col-12 mb-2 mt-3">
        <h6 class="text-dark fw-bold mb-2"><i class="ri-truck-line text-primary me-1"></i> Conhecimento de Transporte
            (CTe)</h6>
        <div class="row g-2">
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_cte_producao', 'Última CTe Produção')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_cte_homologacao', 'Última CTe Homologação')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_serie_cte', 'Série CTe')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
        </div>
    </div>

    <!-- MDFe -->
    <div class="col-12 mb-2 mt-3">
        <h6 class="text-dark fw-bold mb-2"><i class="ri-folders-line text-primary me-1"></i> Manifesto Fiscal (MDFe)
        </h6>
        <div class="row g-2">
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_mdfe_producao', 'Última MDFe Produção')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_ultima_mdfe_homologacao', 'Última MDFe Homologação')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::tel('numero_serie_mdfe', 'Série MDFe')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
        </div>
    </div>

    <!-- CSC e Token -->
    <div class="col-12 mt-3">
        <h6 class="text-dark fw-bold mb-2"><i class="ri-key-line text-primary me-1"></i> Integração e CSC (NFCe)</h6>
        <div class="row g-2">
            <div class="col-md-6">
                {!!Form::text('csc', 'Código de Segurança do Contribuinte (CSC)')
    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-6">
                {!!Form::text('csc_id', 'Identificador do CSC (CSC ID)')
    ->attrs(['class' => 'form-control', 'data-mask' => '0000000000'])
                !!}
            </div>
        </div>
    </div>

    @if(__isMaster() || __isContador())
        @if(!isset($edit))
            <!-- SEÇÃO: DADOS DO USUÁRIO GESTOR -->
            <div class="col-12 mt-4">
                <h5 class="section-title"><i class="ri-user-settings-line"></i> Credenciais do Usuário Gestor</h5>
            </div>

            <div class="col-md-3">
                {!!Form::text('usuario', 'Nome do Usuário')
                    ->attrs(['class' => 'form-control'])
                    ->required()
                !!}
            </div>

            <div class="col-md-3">
                {!!Form::text('email', 'Email de Acesso')
                    ->attrs(['class' => 'form-control'])
                    ->required()
                !!}
            </div>

            <div class="col-md-3">
                <div class="col-md-12">
                    <label class="form-label required" for="">Senha</label>
                    <div class="input-group" id="show_hide_password">
                        <input required type="password" class="form-control" name="password" autocomplete="off"
                            @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif>
                        <a class="input-group-text"><i class='ri-eye-line'></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="col-md-12">
                    <label class="form-label required" for="">Repetir Senha</label>
                    <div class="input-group" id="show_hide_password_r">
                        <input required type="password" class="form-control" name="password_confirmation" autocomplete="off">
                        <a class="input-group-text"><i class='ri-eye-line'></i></a>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- SEÇÃO: CERTIFICADO E SEGURANÇA -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-keyhole-line"></i> Certificado Digital e Segurança</h5>
    </div>

    <div class="col-md-4 file-certificado">
        {!! Form::file('certificado', 'Certificado Digital (.pfx)')->value(isset($item) ? false : true) !!}
        <span class="text-danger d-block mt-1" id="filename"></span>
    </div>

    <div class="col-md-3">
        {!! Form::text('senha_certificado', 'Senha do Certificado')
    ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-5">
        <label class="form-label">Token de API da Empresa</label>
        <div class="input-group">
            <input readonly type="text" class="form-control" id="api_token" name="token"
                value="{{ isset($item) ? $item->token : '' }}">
            <button type="button" class="btn btn-info" id="btn_token"><i
                    class="ri-refresh-line text-white"></i></button>
        </div>
    </div>

    <hr class="mt-4">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-5" id="btn-store">
            <i class="ri-save-line"></i> Salvar Empresa
        </button>
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
                text: "Esse token é o responsavel pela comunicação com a API!!",
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

        $(document).on("blur", "#inp-cpf_cnpj", function () {

            let cpf_cnpj = $(this).val().replace(/[^0-9]/g, '')

            if (cpf_cnpj.length == 14) {
                $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
                    .done((data) => {
                        if (data != null) {
                            let ie = ''
                            if (data.estabelecimento.inscricoes_estaduais.length > 0) {
                                ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual
                            }

                            $('#inp-ie').val(ie)
                            $('#inp-nome').val(data.razao_social)
                            $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia)
                            $("#inp-rua").val(data.estabelecimento.tipo_logradouro + " " + data.estabelecimento.logradouro)
                            $('#inp-numero').val(data.estabelecimento.numero)
                            $("#inp-bairro").val(data.estabelecimento.bairro);
                            let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                            $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))
                            $('#inp-email').val(data.estabelecimento.email)
                            $('#inp-celular').val(data.estabelecimento.telefone1)

                            findCidade(data.estabelecimento.cidade.ibge_id)

                        }
                    })
                    .fail((err) => {
                        console.log(err)
                        // swal("Algo errado", err.responseJSON['detalhes'], "warning")
                    })
            }
        })

        function findCidade(codigo_ibge) {

            $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
                .done((res) => {
                    var newOption = new Option(res.info, res.id, false, false);
                    $('#inp-cidade_id').append(newOption).trigger('change');
                })
                .fail((err) => {
                    console.log(err)
                })

        }

    </script>
@endsection