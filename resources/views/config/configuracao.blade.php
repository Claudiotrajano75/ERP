@section('css')
<style type="text/css">
    input[type="file"] {
        display: none;
    }

    /* ─── Wizard Tabs Premium ─── */
    .modulo-wizard .nav-link {
        border-radius: 10px !important;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 13px;
        color: #5a5a7a;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .modulo-wizard .nav-link:hover {
        background: #f0f2f8;
        color: #302b63;
    }
    .modulo-wizard .nav-link.active {
        background: #302b63 !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(48,43,99,0.25);
    }

    /* ─── Cards Internos (Secções) ─── */
    .modulo-section-card {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 16px;
    }
    .modulo-section-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0;
        padding: 10px 18px;
    }
    .modulo-section-card .card-header h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    /* Upload logo */
    .modulo-image-upload {
        background: #f8f9fa;
        border: 2px dashed #dee2e6 !important;
        transition: border-color .2s;
    }
    .modulo-image-upload:hover { border-color: #302b63 !important; }

    .card-body strong { color: #8833FF; }

    .file-certificado label {
        padding: 8px;
        width: 100%;
        background-color: #8833FF;
        color: #FFF;
        text-transform: uppercase;
        text-align: center;
        display: block;
        margin-top: 10px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>
@endsection

<div>
    <div class="row">
        <div class="card p-0">
            <div class="col-md-12 mt-3">
                <ul class="nav nav-pills nav-justified modulo-wizard form-wizard-header mb-3 m-2">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active rounded-0 py-2 px-3" data-bs-toggle="tab" href="#empresa" role="tab" aria-selected="true">
                            <i class="ri-briefcase-fill align-middle me-1"></i>
                            <span class="d-none d-sm-inline">Empresa</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link rounded-0 py-2 px-3" data-bs-toggle="tab" href="#endereco" role="tab" aria-selected="false">
                            <i class="ri-map-pin-2-line align-middle me-1"></i>
                            <span class="d-none d-sm-inline">Endereço</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link rounded-0 py-2 px-3" data-bs-toggle="tab" href="#nota_fiscal" role="tab" aria-selected="false">
                            <i class="ri-file-edit-line align-middle me-1"></i>
                            <span class="d-none d-sm-inline">Emissão</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link rounded-0 py-2 px-3" data-bs-toggle="tab" href="#certificado" role="tab" aria-selected="false">
                            <i class="ri-fingerprint-line align-middle me-1"></i>
                            <span class="d-none d-sm-inline">Certificado A1</span>
                        </a>
                    </li>
                </ul>
                <hr class="mt-0">

                <div class="tab-content py-2 px-3">

                    {{-- ══════════════ ABA EMPRESA ══════════════ --}}
                    <div class="tab-pane fade show active" id="empresa" role="tabpanel">

                        {{-- Dados Principais --}}
                        <div class="modulo-section-card">
                            <div class="card-header">
                                <h4><i class="ri-building-line me-2"></i>Dados da Empresa</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4 mb-2">
                                        {!!Form::select('tributacao', 'Tipo de tributação', App\Models\Empresa::tiposTributacao())
                                        ->attrs(['class' => 'form-select'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('cpf_cnpj', 'CPF/CNPJ')
                                        ->attrs(['class' => 'cpf_cnpj form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('ie', 'Inscrição Estadual')
                                        ->attrs(['data-mask' => '000000000000000000', 'class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        {!!Form::tel('nome', 'Razão Social')
                                        ->attrs(['class' => 'form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        {!!Form::tel('nome_fantasia', 'Nome Fantasia')
                                        ->attrs(['class' => 'form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        {!!Form::tel('email', 'Email')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('celular', 'Telefone')
                                        ->attrs(['class' => 'fone form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        {!!Form::tel('aut_xml', 'Autorizador XML')
                                        ->attrs(['class' => 'cnpj form-control'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Logo da Empresa --}}
                        <div class="modulo-section-card">
                            <div class="card-header">
                                <h4><i class="ri-image-line me-2"></i>Logo da Empresa</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="modulo-image-upload position-relative p-3 text-center rounded-3 shadow-sm border">
                                            <button type="button" id="btn-remove-imagem" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle shadow">
                                                <i class="ri-close-line"></i>
                                            </button>
                                            <div class="preview mb-2 mt-1">
                                                @isset($item)
                                                <img id="file-ip-1-preview" src="{{ $item->img }}" class="img-fluid rounded" style="max-height: 100px;">
                                                <div class="mt-1"><a href="{{ route('config.delete-logo') }}" class="text-danger small fw-medium">Remover Imagem</a></div>
                                                @else
                                                <img id="file-ip-1-preview" src="/imgs/no-image.png" class="img-fluid rounded opacity-50" style="max-height: 100px;">
                                                @endif
                                            </div>
                                            <label for="file-ip-1" class="btn btn-outline-primary btn-sm w-100 fw-medium">
                                                <i class="ri-image-add-line me-1 align-middle"></i> Selecionar Imagem
                                            </label>
                                            <input type="file" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);" class="d-none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ ABA ENDEREÇO ══════════════ --}}
                    <div class="tab-pane fade" id="endereco" role="tabpanel">
                        <div class="modulo-section-card">
                            <div class="card-header">
                                <h4><i class="ri-map-pin-line me-2"></i>Localização</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('cep', 'CEP')
                                        ->attrs(['class' => 'cep form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        {!!Form::tel('rua', 'Endereço')
                                        ->attrs(['class' => 'form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('numero', 'Número')
                                        ->attrs(['data-mask' => '000000', 'class' => 'form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        {!!Form::tel('complemento', 'Complemento')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        {!!Form::tel('bairro', 'Bairro')
                                        ->attrs(['class' => 'form-control'])
                                        ->required()
                                        !!}
                                    </div>
                                    <div class="col-md-8 mb-2 cidade">
                                        @isset($item)
                                        {!!Form::select('cidade_id', 'Cidade')
                                        ->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])
                                        ->required()
                                        ->attrs(['class' => 'form-select'])
                                        !!}
                                        @else
                                        {!!Form::select('cidade_id', 'Cidade')
                                        ->required()
                                        ->attrs(['class' => 'form-select'])
                                        !!}
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ ABA EMISSÃO ══════════════ --}}
                    <div class="tab-pane fade" id="nota_fiscal" role="tabpanel">

                        {{-- Configurações Gerais --}}
                        <div class="modulo-section-card">
                            <div class="card-header">
                                <h4><i class="ri-file-settings-line me-2"></i>Configurações Gerais de Emissão</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-3 mb-2">
                                        {!!Form::text('csc', 'CSC')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::text('csc_id', 'CSC ID')
                                        ->attrs(['data-mask' => '0000000000', 'class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label">Token API</label>
                                        <div class="input-group">
                                            <input readonly type="text" class="form-control" id="api_token" name="token" value="{{ isset($item) ? $item->token : '' }}">
                                            <button type="button" class="btn btn-primary" id="btn_token"><i class="ri-eye-line"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        {!!Form::select('ambiente', 'Ambiente', [2 => 'Homologação', 1 => 'Produção'])
                                        ->attrs(['class' => 'form-select'])
                                        !!}
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        {!!Form::select('exclusao_icms_pis_cofins', 'Exclusão ICMS de PIS/COFINS', [0 => 'Não', 1 => 'Sim'])
                                        ->attrs(['class' => 'form-select'])
                                        !!}
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        {!!Form::text('token_nfse', 'Token NFSe')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('numero_ultima_nfse', 'Nº Última NFSe')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        {!!Form::tel('numero_serie_nfse', 'Série NFSe')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- NFe e NFCe lado a lado --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="modulo-section-card h-100 mb-0">
                                    <div class="card-header">
                                        <h4><i class="ri-file-text-line me-2"></i>NFe (Nota Fiscal Eletrônica)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_serie_nfe', 'Série')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_nfe_producao', 'Última (Prod)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_nfe_homologacao', 'Última (Homol)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2 div-simples">
                                                {!!Form::tel('perc_ap_cred', '% Aprov. Crédito')
                                                ->attrs(['class' => 'percentual form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                {!!Form::textarea('observacao_padrao_nfe', 'Observação padrão')
                                                ->attrs(['rows' => '3', 'class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-12 mb-2 div-simples">
                                                {!!Form::textarea('mensagem_aproveitamento_credito', 'Aproveitamento de crédito ICMS')
                                                ->attrs(['rows' => '3', 'class' => 'form-control'])
                                                !!}
                                                <small class="text-muted d-block mt-1">Exemplo: Permite o aproveitamento de crédito R$ correspondente ao %. Use R$ para calcular o valor.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="modulo-section-card h-100 mb-0">
                                    <div class="card-header">
                                        <h4><i class="ri-receipt-line me-2"></i>NFCe (Cupom Fiscal)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_serie_nfce', 'Série')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_nfce_producao', 'Última (Prod)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_nfce_homologacao', 'Última (Homol)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                {!!Form::select('natureza_id_pdv', 'Natureza de Operação para PDV', ['' => 'Selecione'] + $naturezas->pluck('descricao', 'id')->all())
                                                ->attrs(['class' => 'form-select'])
                                                ->required()
                                                ->value(isset($item) ? $item->natureza_id_pdv : null)
                                                !!}
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                {!!Form::textarea('observacao_padrao_nfce', 'Observação padrão')
                                                ->attrs(['rows' => '3', 'class' => 'form-control'])
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CTe e MDFe lado a lado --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="modulo-section-card mb-0">
                                    <div class="card-header">
                                        <h4><i class="ri-truck-line me-2"></i>CTe</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_serie_cte', 'Série')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_cte_producao', 'Última (Prod)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_cte_homologacao', 'Última (Homol)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modulo-section-card mb-0">
                                    <div class="card-header">
                                        <h4><i class="ri-file-paper-2-line me-2"></i>MDFe</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_serie_mdfe', 'Série')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_mdfe_producao', 'Última (Prod)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                {!!Form::tel('numero_ultima_mdfe_homologacao', 'Última (Homol)')
                                                ->attrs(['class' => 'form-control'])
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ ABA CERTIFICADO ══════════════ --}}
                    <div class="tab-pane fade" id="certificado" role="tabpanel">
                        <div class="modulo-section-card">
                            <div class="card-header">
                                <h4><i class="ri-fingerprint-line me-2"></i>Certificado A1</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3" style="font-size:13px;">Selecione o arquivo do certificado A1 (Formato .pfx ou .p12) e informe a senha para configuração.</p>

                                @if($dadosCertificado != null)
                                <div class="alert alert-info border-0 shadow-sm rounded-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-information-line fs-3 me-3 text-primary"></i>
                                        <div>
                                            @isset($dadosCertificado['serial'])
                                            <h6 class="mb-1 fw-bold text-dark">Informações do Certificado Atual</h6>
                                            <ul class="list-unstyled mb-0 small text-dark">
                                                <li><strong>Serial:</strong> {{ $dadosCertificado['serial'] }}</li>
                                                <li><strong>Início:</strong> {{ $dadosCertificado['inicio'] }}</li>
                                                <li><strong>Expiração:</strong> {{ $dadosCertificado['expiracao'] }}</li>
                                                <li><strong>ID:</strong> {{ $dadosCertificado['id'] }}</li>
                                            </ul>
                                            @else
                                            <h6 class="mb-0 fw-bold text-danger">{{ $dadosCertificado['mensagem'] }}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row g-2 align-items-end">
                                    <div class="col-md-5 mb-2 file-certificado">
                                        {!! Form::file('certificado', 'Certificado Digital')->value(isset($item) ? false : true)->attrs(['class' => 'form-control', 'accept' => '.pfx,.p12']) !!}
                                        <span class="text-danger small mt-1 d-block" id="filename"></span>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        {!! Form::tel('senha', 'Senha do certificado')
                                        ->attrs(['class' => 'form-control'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end tab-content --}}
            </div>

            <hr class="mt-3">
            <div class="d-flex justify-content-end pb-3 pe-3">
                <button type="submit" class="btn btn-success px-5">
                    <i class="ri-save-line me-1"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $(function(){
        isRegimeSimples()
    })

    function isRegimeSimples(){
        let tributacao = $('#inp-tributacao').val()
        if(tributacao == 'Simples Nacional'){
            $('.div-simples').removeClass('d-none')
        }else{
            $('.div-simples').addClass('d-none')
            $('.div-simples').find('input').val('')
            $('.div-simples').find('textarea').val('')
        }
    }

    $('#btn_token').click(() => {
        let token = generate_token(25);
        swal({
            title: "Atenção"
            , text: "Esse token é o responsavel pela comunicação com a API, tenha atenção!!"
            , icon: "warning"
            , buttons: true
            , dangerMode: true
            , }).then((confirmed) => {
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

    $('.btn-success').click(() => {
        addClassRequired()
    })

    function addClassRequired() {
        let infMsg = ""
        $("body").find('input, select').each(function() {
            if ($(this).prop('required')) {
                if ($(this).val() == "") {
                    try {
                        infMsg += $(this).prev()[0].textContent + "\n"
                    } catch {}
                    $(this).addClass('is-invalid')
                } else {
                    $(this).removeClass('is-invalid')
                }
            } else {
                $(this).removeClass('is-invalid')
            }
        })
        if (infMsg != "") {
            swal("Campos pendentes", infMsg, "warning")
        }
    }

    $(document).on("change", "#inp-tributacao", function () {
        isRegimeSimples()
    })

    $(document).on("blur", "#inp-cpf_cnpj", function () {
        let cpf_cnpj = $(this).val().replace(/[^0-9]/g,'')
        if(cpf_cnpj.length == 14){
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
            .done((data) => {
                if (data!= null) {
                    let ie = ''
                    if (data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual
                    }
                    $('#inp-ie').val(ie)
                    if(ie != ""){
                        $('#inp-contribuinte').val(1).change()
                    }
                    $('#inp-nome').val(data.razao_social)
                    $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia)
                    $("#inp-rua").val(data.estabelecimento.tipo_logradouro + " " + data.estabelecimento.logradouro)
                    $('#inp-numero').val(data.estabelecimento.numero)
                    $("#inp-bairro").val(data.estabelecimento.bairro);
                    let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                    $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))
                    $('#inp-email').val(data.estabelecimento.email)
                    $('#inp-telefone').val(data.estabelecimento.telefone1)
                    findCidade(data.estabelecimento.cidade.ibge_id)
                }
            })
            .fail((err) => {
                console.log(err)
            })
        }
    })

    function findCidade(codigo_ibge){
        $('#inp-cidade_id').html('')
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
