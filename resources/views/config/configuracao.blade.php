@section('css')
<style type="text/css">
    input[type="file"] {
        display: none;
    }

    /* ─── Navegação por Abas em Estilo Segmented Control Premium ─── */
    .modulo-tabs-container {
        background: #f1f3f9;
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 24px;
        border: 1px solid #e2e6f0;
    }

    .modulo-tabs-premium {
        gap: 6px;
        border: none !important;
    }

    .modulo-tabs-premium .nav-item {
        flex: 1;
    }

    .modulo-tabs-premium .nav-link {
        border-radius: 10px !important;
        padding: 12px 18px;
        font-weight: 600;
        font-size: 13px;
        color: #5a5a7a;
        background: transparent;
        transition: all 0.25s ease;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
    }

    .modulo-tabs-premium .nav-link i {
        font-size: 16px;
        transition: transform 0.2s ease;
    }

    .modulo-tabs-premium .nav-link:hover {
        background: rgba(255, 255, 255, 0.7);
        color: #302b63;
    }

    .modulo-tabs-premium .nav-link:hover i {
        transform: translateY(-1px);
    }

    .modulo-tabs-premium .nav-link.active {
        background: #ffffff !important;
        color: #302b63 !important;
        box-shadow: 0 4px 12px rgba(48, 43, 99, 0.12);
        border: 1px solid rgba(48, 43, 99, 0.08);
    }

    .modulo-tabs-premium .nav-link.active i {
        color: #5572f5;
    }

    /* ─── Painéis de Seção / Cards de Conteúdo ─── */
    .modulo-section-card-premium {
        background: #ffffff;
        border: 1px solid #eef0f6;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }

    .modulo-section-card-premium:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
    }

    .modulo-section-card-premium .section-header {
        background: #fcfdfe;
        border-bottom: 1px solid #edf0f6;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modulo-section-card-premium .section-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.2px;
    }

    .modulo-section-card-premium .section-title i {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    /* Cores dos Ícones dos Painéis Fiscais */
    .icon-geral { background: #e0f2fe; color: #0284c7; }
    .icon-nfe { background: #dcfce7; color: #16a34a; }
    .icon-nfce { background: #fef3c7; color: #d97706; }
    .icon-cte { background: #f3e8ff; color: #9333ea; }
    .icon-mdfe { background: #fee2e2; color: #dc2626; }
    .icon-empresa { background: #ede9fe; color: #6366f1; }
    .icon-endereco { background: #dcfce7; color: #10b981; }
    .icon-certificado { background: #fef9c3; color: #ca8a04; }

    .modulo-section-card-premium .card-body {
        padding: 20px;
    }

    /* ─── Customização dos Labels e Inputs ─── */
    .modulo-section-card-premium label {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b !important;
        margin-bottom: 6px !important;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .modulo-section-card-premium .form-control,
    .modulo-section-card-premium .form-select {
        height: 40px;
        border-radius: 8px !important;
        border: 1px solid #dcdce9 !important;
        font-size: 13px !important;
        padding: 8px 12px !important;
        color: #334155 !important;
        background-color: #fcfdfe !important;
        transition: all 0.2s ease;
    }

    .modulo-section-card-premium textarea.form-control {
        height: auto !important;
    }

    .modulo-section-card-premium .form-control:focus,
    .modulo-section-card-premium .form-select:focus {
        border-color: #5572f5 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
    }

    /* ─── Upload de Imagem / Logo ─── */
    .modulo-image-upload-box {
        background: #f8faff;
        border: 2px dashed #cbd5e1 !important;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
    }

    .modulo-image-upload-box:hover {
        border-color: #5572f5 !important;
        background: #f4f6ff;
    }

    /* ─── Cartão de Informações do Certificado ─── */
    .cert-info-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 14px;
        color: #fff;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
        overflow: hidden;
    }

    .cert-info-card::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cert-info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .cert-info-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
        font-weight: 600;
    }

    .cert-info-value {
        font-size: 13px;
        font-weight: 700;
        color: #f8fafc;
        font-family: 'SF Mono', monospace;
    }

    /* ─── Botão de Ação Salvar ─── */
    .btn-salvar-config {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
        color: #fff !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        padding: 12px 32px !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25) !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-salvar-config:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35) !important;
    }
</style>
@endsection

<div>
    <!-- ═══ Abas Segmented Pills Premium ═══ -->
    <div class="modulo-tabs-container">
        <ul class="nav nav-pills modulo-tabs-premium" id="configTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#empresa" role="tab" aria-selected="true">
                    <i class="ri-building-line"></i>
                    <span>Dados da Empresa</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#endereco" role="tab" aria-selected="false">
                    <i class="ri-map-pin-line"></i>
                    <span>Endereço & Localização</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#nota_fiscal" role="tab" aria-selected="false">
                    <i class="ri-file-text-line"></i>
                    <span>Emissão Fiscal</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#certificado" role="tab" aria-selected="false">
                    <i class="ri-shield-keyhole-line"></i>
                    <span>Certificado Digital A1</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">

        {{-- ══════════════ ABA 1: DADOS DA EMPRESA ══════════════ --}}
        <div class="tab-pane fade show active" id="empresa" role="tabpanel">

            {{-- Informações Cadastrais --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-building-2-line icon-empresa"></i>
                        Identificação e Tributação
                    </h4>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                        Dados Principais
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label><i class="ri-scales-3-line"></i> Regime Tributário <span class="text-danger">*</span></label>
                            {!!Form::select('tributacao', '', App\Models\Empresa::tiposTributacao())
                            ->attrs(['class' => 'form-select', 'id' => 'inp-tributacao'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-passport-line"></i> CPF / CNPJ <span class="text-danger">*</span></label>
                            {!!Form::tel('cpf_cnpj', '')
                            ->attrs(['class' => 'cpf_cnpj form-control', 'id' => 'inp-cpf_cnpj', 'placeholder' => '00.000.000/0000-00'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-file-list-line"></i> Inscrição Estadual</label>
                            {!!Form::tel('ie', '')
                            ->attrs(['data-mask' => '000000000000000000', 'class' => 'form-control', 'id' => 'inp-ie', 'placeholder' => 'Inscrição Estadual'])
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><i class="ri-building-line"></i> Razão Social <span class="text-danger">*</span></label>
                            {!!Form::tel('nome', '')
                            ->attrs(['class' => 'form-control', 'id' => 'inp-nome', 'placeholder' => 'Razão Social da Empresa'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><i class="ri-store-2-line"></i> Nome Fantasia <span class="text-danger">*</span></label>
                            {!!Form::tel('nome_fantasia', '')
                            ->attrs(['class' => 'form-control', 'id' => 'inp-nome_fantasia', 'placeholder' => 'Nome Fantasia da Empresa'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-mail-line"></i> E-mail de Contato</label>
                            {!!Form::tel('email', '')
                            ->attrs(['class' => 'form-control', 'id' => 'inp-email', 'placeholder' => 'contato@empresa.com.br'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-phone-line"></i> Telefone / Celular</label>
                            {!!Form::tel('celular', '')
                            ->attrs(['class' => 'fone form-control', 'id' => 'inp-telefone', 'placeholder' => '(00) 00000-0000'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-code-s-slash-line"></i> Autorizador XML (CNPJ Terceiro)</label>
                            {!!Form::tel('aut_xml', '')
                            ->attrs(['class' => 'cnpj form-control', 'placeholder' => 'CNPJ para autorizar download do XML'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logotipo da Empresa --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-image-line icon-empresa"></i>
                        Logotipo da Empresa
                    </h4>
                    <span class="text-muted fs-12">Exibido nos Danfes, comprovantes e telas do sistema</span>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="modulo-image-upload-box">
                                <div class="preview mb-3">
                                    @isset($item)
                                        <img id="file-ip-1-preview" src="{{ $item->img }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 110px; object-fit: contain;">
                                        @if($item->logo)
                                            <div class="mt-2">
                                                <a href="{{ route('config.delete-logo') }}" class="btn btn-outline-danger btn-sm py-0 px-2 rounded-2 fs-11">
                                                    <i class="ri-delete-bin-line align-middle"></i> Remover Logotipo
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <img id="file-ip-1-preview" src="/imgs/no-image.png" class="img-fluid rounded-3 opacity-50" style="max-height: 110px;">
                                    @endif
                                </div>
                                <label for="file-ip-1" class="btn btn-outline-primary btn-sm w-100 fw-semibold rounded-2">
                                    <i class="ri-upload-cloud-2-line me-1 align-middle"></i> Selecionar Imagem
                                </label>
                                <input type="file" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="ps-md-3 mt-3 mt-md-0">
                                <h6 class="fw-bold text-dark fs-13 mb-1">Dicas para a imagem do Logotipo:</h6>
                                <ul class="text-muted fs-12 mb-0 ps-3">
                                    <li>Recomendamos o uso de fundo transparente (.PNG).</li>
                                    <li>Tamanho ideal de 300x120 pixels para melhor legibilidade no DANFE.</li>
                                    <li>A imagem será redimensionada proporcionalmente para caber nos cabeçalhos de impressão.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════ ABA 2: ENDEREÇO & LOCALIZAÇÃO ══════════════ --}}
        <div class="tab-pane fade" id="endereco" role="tabpanel">
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-map-pin-2-line icon-endereco"></i>
                        Endereço da Empresa
                    </h4>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                        Localização Fiscal
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label><i class="ri-map-pin-line"></i> CEP <span class="text-danger">*</span></label>
                            {!!Form::tel('cep', '')
                            ->attrs(['class' => 'cep form-control', 'id' => 'inp-cep', 'placeholder' => '00000-000'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><i class="ri-road-map-line"></i> Logradouro / Rua <span class="text-danger">*</span></label>
                            {!!Form::tel('rua', '')
                            ->attrs(['class' => 'form-control', 'id' => 'inp-rua', 'placeholder' => 'Nome da rua ou avenida'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-home-line"></i> Número <span class="text-danger">*</span></label>
                            {!!Form::tel('numero', '')
                            ->attrs(['data-mask' => '000000', 'class' => 'form-control', 'id' => 'inp-numero', 'placeholder' => 'Nº'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-community-line"></i> Bairro <span class="text-danger">*</span></label>
                            {!!Form::tel('bairro', '')
                            ->attrs(['class' => 'form-control', 'id' => 'inp-bairro', 'placeholder' => 'Bairro'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-information-line"></i> Complemento</label>
                            {!!Form::tel('complemento', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Sala, Galpão, Bloco (Opcional)'])
                            !!}
                        </div>
                        <div class="col-md-4 cidade">
                            <label><i class="ri-building-line"></i> Cidade <span class="text-danger">*</span></label>
                            @isset($item)
                                {!!Form::select('cidade_id', '', $item != null ? [$item->cidade_id => $item->cidade->info] : [])
                                ->required()
                                ->attrs(['class' => 'form-select select2', 'id' => 'inp-cidade_id'])
                                !!}
                            @else
                                {!!Form::select('cidade_id', '', [])
                                ->required()
                                ->attrs(['class' => 'form-select select2', 'id' => 'inp-cidade_id'])
                                !!}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════ ABA 3: EMISSÃO FISCAL (PAINÉIS EMPILHADOS) ══════════════ --}}
        <div class="tab-pane fade" id="nota_fiscal" role="tabpanel">

            {{-- 1. PAINEL: CONFIGURAÇÕES GERAIS DE EMISSÃO --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-settings-4-line icon-geral"></i>
                        1. Configurações Gerais de Emissão
                    </h4>
                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">
                        Ambiente & Parâmetros Fiscais
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label><i class="ri-server-line"></i> Ambiente de Emissão <span class="text-danger">*</span></label>
                            {!!Form::select('ambiente', '', [2 => 'Homologação (Testes)', 1 => 'Produção (Oficial)'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-key-line"></i> CSC (Código de Segurança)</label>
                            {!!Form::text('csc', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Código CSC fornecido pela SEFAZ'])
                            !!}
                        </div>
                        <div class="col-md-2">
                            <label><i class="ri-hashtag"></i> CSC ID</label>
                            {!!Form::text('csc_id', '')
                            ->attrs(['data-mask' => '0000000000', 'class' => 'form-control', 'placeholder' => '000001'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-key-2-line"></i> Token de Integração API</label>
                            <div class="input-group">
                                <input readonly type="text" class="form-control" id="api_token" name="token" value="{{ isset($item) ? $item->token : '' }}" placeholder="Gerar Token">
                                <button type="button" class="btn btn-primary px-3" id="btn_token" title="Gerar Novo Token API">
                                    <i class="ri-refresh-line align-middle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label><i class="ri-percent-line"></i> Exclusão ICMS de PIS/COFINS</label>
                            {!!Form::select('exclusao_icms_pis_cofins', '', [0 => 'Não', 1 => 'Sim'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-shield-check-line"></i> Token NFSe (Serviço)</label>
                            {!!Form::text('token_nfse', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Token NFSe da Prefeitura'])
                            !!}
                        </div>
                        <div class="col-md-2">
                            <label><i class="ri-sort-number-asc"></i> Série NFSe</label>
                            {!!Form::tel('numero_serie_nfse', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '1'])
                            !!}
                        </div>
                        <div class="col-md-2">
                            <label><i class="ri-hashtag"></i> Nº Última NFSe</label>
                            {!!Form::tel('numero_ultima_nfse', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. PAINEL: NFE (NOTA FISCAL ELETRÔNICA) --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-file-list-3-line icon-nfe"></i>
                        2. NFe (Nota Fiscal Eletrônica - Modelo 55)
                    </h4>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                        Vendas & Faturamento
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label><i class="ri-sort-number-asc"></i> Série NFe <span class="text-danger">*</span></label>
                            {!!Form::tel('numero_serie_nfe', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '1'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-hashtag"></i> Última NFe (Produção)</label>
                            {!!Form::tel('numero_ultima_nfe_producao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-hashtag"></i> Última NFe (Homologação)</label>
                            {!!Form::tel('numero_ultima_nfe_homologacao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                        <div class="col-md-3 div-simples">
                            <label><i class="ri-percent-line"></i> % Aprov. Crédito ICMS</label>
                            {!!Form::tel('perc_ap_cred', '')
                            ->attrs(['class' => 'percentual form-control', 'placeholder' => '0,00%'])
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><i class="ri-chat-1-line"></i> Observação Padrão no DANFE NFe</label>
                            {!!Form::textarea('observacao_padrao_nfe', '')
                            ->attrs(['rows' => '3', 'class' => 'form-control', 'placeholder' => 'Informações complementares fixas que sairão na NFe...'])
                            !!}
                        </div>
                        <div class="col-md-6 div-simples">
                            <label><i class="ri-chat-quote-line"></i> Mensagem de Aproveitamento de Crédito</label>
                            {!!Form::textarea('mensagem_aproveitamento_credito', '')
                            ->attrs(['rows' => '3', 'class' => 'form-control', 'placeholder' => 'Ex: Permite o aproveitamento de crédito R$ correspondente ao %.'])
                            !!}
                            <small class="text-muted d-block mt-1 fs-11">Use <code>R$</code> para calcular e exibir o valor em reais na emissão.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. PAINEL: NFCE (CUPOM FISCAL) --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-receipt-line icon-nfce"></i>
                        3. NFCe (Cupom Fiscal Eletrônico - Modelo 65)
                    </h4>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                        Varejo & PDV
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label><i class="ri-sort-number-asc"></i> Série NFCe <span class="text-danger">*</span></label>
                            {!!Form::tel('numero_serie_nfce', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '1'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-hashtag"></i> Última NFCe (Produção)</label>
                            {!!Form::tel('numero_ultima_nfce_producao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-hashtag"></i> Última NFCe (Homologação)</label>
                            {!!Form::tel('numero_ultima_nfce_homologacao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            <label><i class="ri-bookmark-line"></i> Natureza de Operação para PDV <span class="text-danger">*</span></label>
                            {!!Form::select('natureza_id_pdv', '', ['' => 'Selecione uma Natureza'] + $naturezas->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'form-select'])
                            ->required()
                            ->value(isset($item) ? $item->natureza_id_pdv : null)
                            !!}
                        </div>
                        <div class="col-md-12">
                            <label><i class="ri-chat-1-line"></i> Observação Padrão no Cupom NFCe</label>
                            {!!Form::textarea('observacao_padrao_nfce', '')
                            ->attrs(['rows' => '2', 'class' => 'form-control', 'placeholder' => 'Mensagem impressa no rodapé do cupom fiscal...'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. PAINEL: CTE (CONHECIMENTO DE TRANSPORTE ELETRÔNICO) --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-truck-line icon-cte"></i>
                        4. CTe (Conhecimento de Transporte Eletrônico - Modelo 57)
                    </h4>
                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2 py-1 fs-11" style="background-color: #f3e8ff; color: #9333ea;">
                        Transporte & Cargas
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label><i class="ri-sort-number-asc"></i> Série CTe</label>
                            {!!Form::tel('numero_serie_cte', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '1'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-hashtag"></i> Última CTe (Produção)</label>
                            {!!Form::tel('numero_ultima_cte_producao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-hashtag"></i> Última CTe (Homologação)</label>
                            {!!Form::tel('numero_ultima_cte_homologacao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. PAINEL: MDFE (MANIFESTO ELETRÔNICO DE DOCUMENTOS FISCAIS) --}}
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-file-paper-2-line icon-mdfe"></i>
                        5. MDFe (Manifesto Eletrônico de Documentos Fiscais - Modelo 58)
                    </h4>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                        Manifesto de Cargas
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label><i class="ri-sort-number-asc"></i> Série MDFe</label>
                            {!!Form::tel('numero_serie_mdfe', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '1'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-hashtag"></i> Última MDFe (Produção)</label>
                            {!!Form::tel('numero_ultima_mdfe_producao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                        <div class="col-md-4">
                            <label><i class="ri-hashtag"></i> Última MDFe (Homologação)</label>
                            {!!Form::tel('numero_ultima_mdfe_homologacao', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => '0'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══════════════ ABA 4: CERTIFICADO DIGITAL A1 ══════════════ --}}
        <div class="tab-pane fade" id="certificado" role="tabpanel">
            <div class="modulo-section-card-premium">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="ri-shield-keyhole-line icon-certificado"></i>
                        Certificado Digital A1
                    </h4>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                        Assinatura Digital SEFAZ
                    </span>
                </div>
                <div class="card-body">
                    <p class="text-muted fs-13 mb-4">
                        O certificado digital modelo <strong>A1</strong> (.pfx ou .p12) é indispensável para autorização de documentos fiscais (NFe, NFCe, CTe, MDFe e NFSe) junto aos servidores da SEFAZ.
                    </p>

                    @if($dadosCertificado != null)
                        @isset($dadosCertificado['serial'])
                            <div class="cert-info-card mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary pb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-shield-check-fill text-success fs-20"></i>
                                        <span class="fw-bold fs-14 text-white">Certificado Digital Instalado</span>
                                    </div>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 fs-11 fw-semibold">
                                        ATIVO
                                    </span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <div class="cert-info-item">
                                            <span class="cert-info-label">Titular / Razão Social</span>
                                            <span class="cert-info-value">{{ $dadosCertificado['id'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="cert-info-item">
                                            <span class="cert-info-label">Número Serial</span>
                                            <span class="cert-info-value">{{ $dadosCertificado['serial'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <div class="cert-info-item">
                                            <span class="cert-info-label">Data de Início</span>
                                            <span class="cert-info-value">{{ $dadosCertificado['inicio'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <div class="cert-info-item">
                                            <span class="cert-info-label">Data de Expiração</span>
                                            <span class="cert-info-value text-warning">{{ $dadosCertificado['expiracao'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger d-flex align-items-center rounded-3 mb-4" role="alert">
                                <i class="ri-error-warning-line fs-20 me-2"></i>
                                <div>{{ $dadosCertificado['mensagem'] }}</div>
                            </div>
                        @endisset
                    @endif

                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label><i class="ri-upload-2-line"></i> Arquivo do Certificado (.pfx ou .p12)</label>
                            <div class="input-group">
                                <label for="inp-cert" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2" style="height: 40px; border-radius: 8px !important;">
                                    <i class="ri-file-shield-2-line fs-16"></i>
                                    <span id="cert-filename">Selecionar Arquivo .PFX ou .P12</span>
                                </label>
                                <input type="file" id="inp-cert" name="certificado" accept=".pfx,.p12" onchange="$('#cert-filename').text(this.files[0] ? this.files[0].name : 'Selecionar Arquivo .PFX ou .P12');" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><i class="ri-lock-password-line"></i> Senha do Certificado</label>
                            {!! Form::tel('senha', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Informe a senha do arquivo'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ═══ Rodapé Fixo com Botão de Ação Salvar ═══ -->
    <div class="d-flex align-items-center justify-content-between pt-3 mt-2 border-top">
        <span class="text-muted fs-13">
            <i class="ri-information-line align-middle me-1"></i> Certifique-se de salvar após qualquer alteração fiscal ou cadastral.
        </span>
        <button type="submit" class="btn btn-salvar-config">
            <i class="ri-save-3-fill fs-18"></i>
            Salvar Alterações
        </button>
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

    $('.btn-salvar-config').click(() => {
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
