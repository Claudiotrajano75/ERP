@extends('layouts.app', ['title' => 'Configurações Globais do Super Admin'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card-body {
        padding: 28px !important;
    }

    /* Cabeçalho de Gradiente Premium */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    /* Sessões e Grupos */
    .config-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f1f5f9;
    }

    .config-section-title i {
        font-size: 18px;
    }

    .config-card-group {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    /* Inputs e Selects */
    .form-control, select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        background-color: #fff;
    }

    .form-control:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 12px !important;
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
        background-color: #0ea5e9 !important;
        border-color: #0ea5e9 !important;
        color: #fff !important;
    }

    .btn-info:hover {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
    }

    .modulo-actions {
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO COM GRADIENTE PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="modulo-title text-white">
                                <i class="ri-settings-5-line"></i> Configurações Globais do Super Admin
                            </h4>
                            <p class="modulo-subtitle">
                                Parâmetros centrais do sistema, responsável técnico da Software House, gateways de pagamento e integrações de API.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {!!Form::open()->fill($item)
                    ->post()
                    ->route('configuracao-super.store')
                    ->multipart()
                    !!}

                    <!-- ═══ SEÇÃO 1: RESPONSÁVEL TÉCNICO ═══ -->
                    <div class="config-card-group">
                        <div class="config-section-title text-primary">
                            <i class="ri-shield-user-line"></i> 1. Dados do Responsável Técnico (Software House)
                        </div>
                        <p class="text-muted fs-12 mb-3">
                            Informações fiscais da empresa desenvolvedora do software, exigidas no grupo de Responsável Técnico dos documentos fiscais eletrônicos (NF-e, NFC-e, CT-e, MDF-e).
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label required"><i class="ri-building-line me-1"></i> Razão Social / Nome</label>
                                {!!Form::text('name', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Nome da Software House'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label required"><i class="ri-fingerprint-line me-1"></i> CNPJ</label>
                                {!!Form::tel('cpf_cnpj', '')->required()->attrs(['class' => 'form-control cpf_cnpj', 'placeholder' => '00.000.000/0000-00'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label required"><i class="ri-phone-line me-1"></i> Telefone / Contato</label>
                                {!!Form::tel('telefone', '')->required()->attrs(['class' => 'form-control fone', 'placeholder' => '(00) 00000-0000'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label required"><i class="ri-mail-line me-1"></i> E-mail Técnico</label>
                                {!!Form::text('email', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'suporte@softwarehouse.com'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label required"><i class="ri-toggle-line me-1"></i> Incluir nas Notas</label>
                                {!!Form::select('usar_resp_tecnico', '', [0 => 'Não', 1 => 'Sim'])->required()->attrs(['class' => 'form-select'])!!}
                            </div>
                        </div>
                    </div>

                    <!-- ═══ SEÇÃO 2: GATEWAY DE PAGAMENTOS ═══ -->
                    <div class="config-card-group">
                        <div class="config-section-title text-info">
                            <i class="ri-bank-card-line"></i> 2. Gateway de Pagamento dos Planos (Mercado Pago)
                        </div>
                        <p class="text-muted fs-12 mb-3">
                            Credenciais de integração para cobrança automática e liberação dos planos de assinatura das empresas via Pix e Cartão.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label"><i class="ri-key-2-line me-1"></i> Mercado Pago Public Key</label>
                                {!!Form::text('mercadopago_public_key', '')->attrs(['class' => 'form-control', 'placeholder' => 'APP_USR-...'])!!}
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label"><i class="ri-lock-password-line me-1"></i> Mercado Pago Access Token</label>
                                {!!Form::text('mercadopago_access_token', '')->attrs(['class' => 'form-control', 'placeholder' => 'APP_USR-...'])!!}
                            </div>
                        </div>
                    </div>

                    <!-- ═══ SEÇÃO 3: COMUNICAÇÕES & NOTIFICAÇÕES ═══ -->
                    <div class="config-card-group">
                        <div class="config-section-title text-success">
                            <i class="ri-message-3-line"></i> 3. Comunicações e Mensageria (WhatsApp & SMS)
                        </div>
                        <p class="text-muted fs-12 mb-3">
                            Integrações de disparo de avisos, ativação de contas e notificações de pedidos de delivery.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label"><i class="ri-message-2-line me-1"></i> SMS Key Comtele (Ativação de Conta)</label>
                                {!!Form::text('sms_key', '')->attrs(['class' => 'form-control', 'placeholder' => 'Chave de API Comtele'])!!}
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label"><i class="ri-whatsapp-line me-1"></i> Token WhatsApp (CriarWhats / Delivery)</label>
                                {!!Form::text('token_whatsapp', '')->attrs(['class' => 'form-control', 'placeholder' => 'Token da API CriarWhats'])!!}
                            </div>
                        </div>
                    </div>

                    <!-- ═══ SEÇÃO 4: CORREIOS & NFS-E ═══ -->
                    <div class="config-card-group">
                        <div class="config-section-title text-warning">
                            <i class="ri-truck-line"></i> 4. Logística dos Correios & Emissão de NFS-e
                        </div>
                        <p class="text-muted fs-12 mb-3">
                            Credenciais para cálculo de frete com contrato dos Correios e token para emissão de Nota Fiscal de Serviço (NFS-e).
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-user-settings-line me-1"></i> Usuário Correios</label>
                                {!!Form::text('usuario_correios', '')->attrs(['class' => 'form-control', 'placeholder' => 'Usuário Sigep / Cws'])!!}
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-key-line me-1"></i> Código de Acesso Correios</label>
                                {!!Form::text('codigo_acesso_correios', '')->attrs(['class' => 'form-control', 'placeholder' => 'Chave ou senha de acesso'])!!}
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-file-list-2-line me-1"></i> Cartão de Postagem Correios</label>
                                {!!Form::text('cartao_postagem_correios', '')->attrs(['class' => 'form-control', 'placeholder' => '0000000000'])!!}
                            </div>
                            <div class="col-12 mt-2">
                                <label class="form-label"><i class="ri-file-shield-2-line me-1"></i> Token Integra Notas (NFS-e Nacional)</label>
                                {!!Form::text('token_auth_nfse', '')->attrs(['class' => 'form-control', 'placeholder' => 'Token de autenticação do gateway NFS-e'])!!}
                            </div>
                        </div>
                    </div>

                    <!-- ═══ SEÇÃO 5: TEMPOS DE RESPOSTA FISCAIS (TIMEOUTS) ═══ -->
                    <div class="config-card-group">
                        <div class="config-section-title text-secondary">
                            <i class="ri-timer-line"></i> 5. Tempo de Espera dos Documentos Fiscais (Timeouts da SEFAZ)
                        </div>
                        <p class="text-muted fs-12 mb-3">
                            Tempo máximo (em segundos) que o sistema aguardará a resposta dos servidores da SEFAZ antes de cancelar a tentativa ou reenviar em contingência. Padrão recomendado: <strong>8 a 15 segundos</strong>.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-file-text-line me-1"></i> Timeout NF-e (segundos)</label>
                                {!!Form::tel('timeout_nfe', '')->attrs(['data-mask' => '00', 'class' => 'form-control text-center fw-bold'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-coupon-2-line me-1"></i> Timeout NFC-e (segundos)</label>
                                {!!Form::tel('timeout_nfce', '')->attrs(['data-mask' => '00', 'class' => 'form-control text-center fw-bold'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-truck-line me-1"></i> Timeout CT-e (segundos)</label>
                                {!!Form::tel('timeout_cte', '')->attrs(['data-mask' => '00', 'class' => 'form-control text-center fw-bold'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-route-line me-1"></i> Timeout MDF-e (segundos)</label>
                                {!!Form::tel('timeout_mdfe', '')->attrs(['data-mask' => '00', 'class' => 'form-control text-center fw-bold'])!!}
                            </div>
                        </div>
                    </div>

                    <!-- ═══ SEÇÃO 6: API REST DO ERP ═══ -->
                    <div class="config-card-group">
                        <div class="config-section-title text-dark">
                            <i class="ri-code-s-slash-line"></i> 6. Autenticação da API REST Global
                        </div>
                        <p class="text-muted fs-12 mb-3">
                            Token mestre utilizado para integrar sistemas externos, aplicativos mobile e robôs automatizados ao ERP.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label required"><i class="ri-shield-keyhole-line me-1"></i> Token de Acesso da API</label>
                                <div class="input-group">
                                    <input readonly required type="text" class="form-control font-monospace bg-light" id="api_token" name="token_api" value="{{ isset($item) ? $item->token_api : '' }}" placeholder="Clique no botão ao lado para gerar um token...">
                                    <button type="button" class="btn btn-info text-white" id="btn_token" title="Gerar Novo Token">
                                        <i class="ri-refresh-line me-1"></i> Gerar Token
                                    </button>
                                </div>
                                <small class="text-muted fs-11 mt-1 d-block">Ao alterar o token, certifique-se de atualizar todas as integrações ativas.</small>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BOTÃO DE SALVAR ═══ -->
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <button type="submit" class="btn btn-success px-5" id="btn-store">
                                <i class="ri-save-3-line me-1"></i> Salvar Todas as Configurações
                            </button>
                        </div>
                    </div>

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('#btn_token').click(() => {
        let token = generate_token(32);
        swal({
            title: "Gerar Novo Token da API?", 
            text: "Atenção: A alteração deste token interromperá a comunicação de integrações e aplicativos conectados até que o novo token seja atualizado neles!", 
            icon: "warning", 
            buttons: ["Cancelar", "Sim, Gerar Token"],
            dangerMode: true
        }).then((confirmed) => {
            if (confirmed) {
                $('#api_token').val(token);
                swal("Novo Token Gerado!", "Não se esqueça de clicar em 'Salvar Todas as Configurações' no final da página.", "success");
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
</script>
@endsection
