<div class="row">
    <div class="col-12">
        <!-- ═══ NAVEGAÇÃO POR ABAS ═══ -->
        <ul class="nav nav-pills nav-tabs-custom mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="pill" href="#pills-empresa" role="tab" aria-selected="true">
                    <i class="ri-building-line"></i>
                    <span>Dados da Empresa</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#pills-endereco" role="tab" aria-selected="false">
                    <i class="ri-map-pin-line"></i>
                    <span>Endereço & Localização</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#pills-emissao" role="tab" aria-selected="false">
                    <i class="ri-file-text-line"></i>
                    <span>Emissão Fiscal</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#pills-certificado" role="tab" aria-selected="false">
                    <i class="ri-shield-keyhole-line"></i>
                    <span>Certificado Digital A1</span>
                </a>
            </li>
        </ul>

        <!-- ═══ CONTEÚDO DAS ABAS ═══ -->
        <div class="tab-content" id="pills-tabContent">

            <!-- ══════════════ ABA 1: DADOS DA EMPRESA ══════════════ -->
            <div class="tab-pane fade show active" id="pills-empresa" role="tabpanel">
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-building-2-line text-primary"></i> Identificação da Empresa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::select('tributacao', 'Regime Tributário', App\Models\Empresa::tiposTributacao())
                                ->attrs(['class' => 'form-select', 'id' => 'inp-tributacao'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('cpf_cnpj', 'CPF / CNPJ')
                                ->attrs(['class' => 'form-control cpf_cnpj', 'id' => 'inp-cpf_cnpj'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('ie', 'Inscrição Estadual')
                                ->attrs(['class' => 'form-control', 'data-mask' => '000000000000000000', 'id' => 'inp-ie'])
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('nome', 'Razão Social')
                                ->attrs(['class' => 'form-control', 'id' => 'inp-nome'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('nome_fantasia', 'Nome Fantasia')
                                ->attrs(['class' => 'form-control', 'id' => 'inp-nome_fantasia'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('email', 'E-mail de Contato')
                                ->attrs(['class' => 'form-control', 'id' => 'inp-email'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('celular', 'Telefone / Celular')
                                ->attrs(['class' => 'form-control fone', 'id' => 'inp-telefone'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('aut_xml', 'Autorizador XML (CNPJ Terceiro)')
                                ->attrs(['class' => 'form-control cnpj'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logotipo da Empresa -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-image-line text-primary"></i> Logotipo da Empresa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="upload-logo-container">
                                    <div class="mb-3">
                                        @isset($item)
                                            <img id="file-ip-1-preview" src="{{ $item->img }}" class="img-fluid rounded" style="max-height: 100px; object-fit: contain;">
                                            @if($item->logo)
                                                <div class="mt-2">
                                                    <a href="{{ route('config.delete-logo') }}" class="btn btn-outline-danger btn-sm py-0 px-2 fs-11">
                                                        <i class="ri-delete-bin-line"></i> Remover Logotipo
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <img id="file-ip-1-preview" src="/imgs/no-image.png" class="img-fluid rounded opacity-50" style="max-height: 100px;">
                                        @endif
                                    </div>
                                    <label for="file-ip-1" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="ri-upload-cloud-line me-1"></i> Selecionar Imagem
                                    </label>
                                    <input type="file" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);" style="display: none;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="ps-md-2 mt-3 mt-md-0">
                                    <h6 class="fw-bold text-dark fs-13 mb-1">Informações sobre o Logotipo:</h6>
                                    <p class="text-muted fs-12 mb-0">
                                        O logotipo será exibido na barra superior do sistema, no DANFE da NFe/NFCe e nos comprovantes de venda. Recomenda-se formato .PNG com fundo transparente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 2: ENDEREÇO & LOCALIZAÇÃO ══════════════ -->
            <div class="tab-pane fade" id="pills-endereco" role="tabpanel">
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-map-pin-2-line text-primary"></i> Localização da Empresa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                {!!Form::tel('cep', 'CEP')
                                ->attrs(['class' => 'form-control cep', 'id' => 'inp-cep'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('rua', 'Logradouro / Rua')
                                ->attrs(['class' => 'form-control', 'id' => 'inp-rua'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('numero', 'Número')
                                ->attrs(['class' => 'form-control', 'data-mask' => '000000', 'id' => 'inp-numero'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('bairro', 'Bairro')
                                ->attrs(['class' => 'form-control', 'id' => 'inp-bairro'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('complemento', 'Complemento')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4 cidade">
                                @isset($item)
                                    {!!Form::select('cidade_id', 'Cidade', $item != null && $item->cidade ? [$item->cidade_id => $item->cidade->info] : [])
                                    ->attrs(['class' => 'form-select select2', 'id' => 'inp-cidade_id'])
                                    ->required()
                                    !!}
                                @else
                                    {!!Form::select('cidade_id', 'Cidade', [])
                                    ->attrs(['class' => 'form-select select2', 'id' => 'inp-cidade_id'])
                                    ->required()
                                    !!}
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 3: EMISSÃO FISCAL (PAINÉIS EMPILHADOS) ══════════════ -->
            <div class="tab-pane fade" id="pills-emissao" role="tabpanel">

                <!-- 1. PAINEL: CONFIGURAÇÕES GERAIS DE EMISSÃO -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-settings-4-line text-primary"></i> 1. Configurações Gerais de Emissão</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                {!!Form::select('ambiente', 'Ambiente de Emissão', [2 => 'Homologação (Testes)', 1 => 'Produção (Oficial)'])
                                ->attrs(['class' => 'form-select'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('csc', 'CSC (Código de Segurança)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::text('csc_id', 'CSC ID')
                                ->attrs(['class' => 'form-control', 'data-mask' => '0000000000'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Token API de Integração</label>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control" id="api_token" name="token" value="{{ isset($item) ? $item->token : '' }}">
                                    <button type="button" class="btn btn-primary" id="btn_token" title="Gerar Token"><i class="ri-refresh-line"></i></button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                {!!Form::select('exclusao_icms_pis_cofins', 'Exclusão ICMS de PIS/COFINS', [0 => 'Não', 1 => 'Sim'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('token_nfse', 'Token NFSe (Serviço)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('numero_serie_nfse', 'Série NFSe')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('numero_ultima_nfse', 'Nº Última NFSe')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. PAINEL: NFE (NOTA FISCAL ELETRÔNICA) -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-file-list-3-line text-success"></i> 2. NFe (Nota Fiscal Eletrônica - Modelo 55)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::tel('numero_serie_nfe', 'Série NFe')
                                ->attrs(['class' => 'form-control'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_nfe_producao', 'Última NFe (Produção)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_nfe_homologacao', 'Última NFe (Homologação)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4 div-simples">
                                {!!Form::tel('perc_ap_cred', '% Aprov. Crédito ICMS')
                                ->attrs(['class' => 'percentual form-control'])
                                !!}
                            </div>
                            <div class="col-md-8">
                                {!!Form::textarea('observacao_padrao_nfe', 'Observação Padrão no DANFE NFe')
                                ->attrs(['rows' => '3', 'class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-12 div-simples">
                                {!!Form::textarea('mensagem_aproveitamento_credito', 'Mensagem de Aproveitamento de Crédito')
                                ->attrs(['rows' => '3', 'class' => 'form-control'])
                                !!}
                                <small class="text-muted d-block mt-1 fs-12">Exemplo: Permite o aproveitamento de crédito R$ correspondente ao %. Use R$ para calcular o valor.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. PAINEL: NFCE (CUPOM FISCAL) -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-receipt-line text-warning"></i> 3. NFCe (Cupom Fiscal Eletrônico - Modelo 65)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::tel('numero_serie_nfce', 'Série NFCe')
                                ->attrs(['class' => 'form-control'])
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_nfce_producao', 'Última NFCe (Produção)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_nfce_homologacao', 'Última NFCe (Homologação)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-12">
                                {!!Form::select('natureza_id_pdv', 'Natureza de Operação para PDV', ['' => 'Selecione uma Natureza'] + $naturezas->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'form-select'])
                                ->required()
                                ->value(isset($item) ? $item->natureza_id_pdv : null)
                                !!}
                            </div>
                            <div class="col-md-12">
                                {!!Form::textarea('observacao_padrao_nfce', 'Observação Padrão no Cupom NFCe')
                                ->attrs(['rows' => '2', 'class' => 'form-control'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. PAINEL: CTE (CONHECIMENTO DE TRANSPORTE) -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-truck-line text-info"></i> 4. CTe (Conhecimento de Transporte Eletrônico - Modelo 57)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::tel('numero_serie_cte', 'Série CTe')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_cte_producao', 'Última CTe (Produção)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_cte_homologacao', 'Última CTe (Homologação)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. PAINEL: MDFE (MANIFESTO ELETRÔNICO) -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-file-paper-2-line text-danger"></i> 5. MDFe (Manifesto de Documentos Fiscais - Modelo 58)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::tel('numero_serie_mdfe', 'Série MDFe')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_mdfe_producao', 'Última MDFe (Produção)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('numero_ultima_mdfe_homologacao', 'Última MDFe (Homologação)')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══════════════ ABA 4: CERTIFICADO DIGITAL A1 ══════════════ -->
            <div class="tab-pane fade" id="pills-certificado" role="tabpanel">
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-shield-keyhole-line text-warning"></i> Certificado Digital A1</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted fs-13 mb-3">
                            Selecione o arquivo do certificado digital A1 (.pfx ou .p12) e informe a senha para autorizar a emissão fiscal junto à SEFAZ.
                        </p>

                        @if($dadosCertificado != null)
                            @isset($dadosCertificado['serial'])
                                <div class="alert alert-info border-0 shadow-sm rounded-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-information-line fs-3 me-3 text-primary"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">Informações do Certificado Instalado</h6>
                                            <ul class="list-unstyled mb-0 small text-dark">
                                                <li><strong>Titular:</strong> {{ $dadosCertificado['id'] }}</li>
                                                <li><strong>Número Serial:</strong> {{ $dadosCertificado['serial'] }}</li>
                                                <li><strong>Válido de:</strong> {{ $dadosCertificado['inicio'] }} <strong>até:</strong> {{ $dadosCertificado['expiracao'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger mb-3">
                                    <strong>Atenção:</strong> {{ $dadosCertificado['mensagem'] }}
                                </div>
                            @endisset
                        @endif

                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Arquivo do Certificado (.pfx / .p12)</label>
                                <div class="input-group">
                                    <label for="inp-cert" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="ri-file-shield-line"></i>
                                        <span id="cert-filename">Selecionar Arquivo .PFX ou .P12</span>
                                    </label>
                                    <input type="file" id="inp-cert" name="certificado" accept=".pfx,.p12" onchange="$('#cert-filename').text(this.files[0] ? this.files[0].name : 'Selecionar Arquivo .PFX ou .P12');" style="display: none;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                {!! Form::tel('senha', 'Senha do Certificado')
                                ->attrs(['class' => 'form-control', 'placeholder' => 'Senha do certificado'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ═══ BOTÃO SALVAR (PADRÃO SKILL ERP) ═══ -->
        <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="ri-close-line me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4" id="btn-store">
                <i class="ri-save-line me-1"></i> Salvar Alterações
            </button>
        </div>

    </div>
</div>

@section('js')
<script type="text/javascript">
    $(function(){
        isRegimeSimples();
    });

    function isRegimeSimples(){
        let tributacao = $('#inp-tributacao').val();
        if(tributacao == 'Simples Nacional'){
            $('.div-simples').removeClass('d-none');
        }else{
            $('.div-simples').addClass('d-none');
            $('.div-simples').find('input').val('');
            $('.div-simples').find('textarea').val('');
        }
    }

    $('#btn_token').click(() => {
        let token = generate_token(25);
        swal({
            title: "Atenção",
            text: "Esse token é o responsável pela comunicação com a API, tenha atenção!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
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

    $('#btn-store').click(() => {
        addClassRequired();
    });

    function addClassRequired() {
        let infMsg = "";
        $("body").find('input, select').each(function() {
            if ($(this).prop('required')) {
                if ($(this).val() == "") {
                    try {
                        let labelText = $(this).closest('div').find('label').text().trim();
                        if (labelText) {
                            infMsg += "• " + labelText + "\n";
                        }
                    } catch {}
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if (infMsg != "") {
            swal("Campos obrigatórios pendentes", infMsg, "warning");
        }
    }

    $(document).on("change", "#inp-tributacao", function () {
        isRegimeSimples();
    });

    $(document).on("blur", "#inp-cpf_cnpj", function () {
        let cpf_cnpj = $(this).val().replace(/[^0-9]/g,'');
        if(cpf_cnpj.length == 14){
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
            .done((data) => {
                if (data != null) {
                    let ie = '';
                    if (data.estabelecimento && data.estabelecimento.inscricoes_estaduais && data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual;
                    }
                    $('#inp-ie').val(ie);
                    if(ie != ""){
                        $('#inp-contribuinte').val(1).change();
                    }
                    if(data.razao_social) $('#inp-nome').val(data.razao_social);
                    if(data.estabelecimento && data.estabelecimento.nome_fantasia) {
                        $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia);
                    } else if(data.razao_social) {
                        $('#inp-nome_fantasia').val(data.razao_social);
                    }
                    if(data.estabelecimento) {
                        if(data.estabelecimento.logradouro) {
                            $("#inp-rua").val((data.estabelecimento.tipo_logradouro ? data.estabelecimento.tipo_logradouro + " " : "") + data.estabelecimento.logradouro);
                        }
                        if(data.estabelecimento.numero) $('#inp-numero').val(data.estabelecimento.numero);
                        if(data.estabelecimento.bairro) $("#inp-bairro").val(data.estabelecimento.bairro);
                        if(data.estabelecimento.cep) {
                            let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                            $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9));
                        }
                        if(data.estabelecimento.email) $('#inp-email').val(data.estabelecimento.email);
                        if(data.estabelecimento.telefone1) $('#inp-telefone').val(data.estabelecimento.telefone1);
                        if(data.estabelecimento.cidade && data.estabelecimento.cidade.ibge_id) {
                            findCidade(data.estabelecimento.cidade.ibge_id);
                        }
                    }
                }
            })
            .fail((err) => {
                console.log(err);
            });
        }
    });

    function findCidade(codigo_ibge){
        $('#inp-cidade_id').html('');
        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        })
        .fail((err) => {
            console.log(err);
        });
    }

    function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.classList.remove("opacity-50");
        }
    }
</script>
@endsection
