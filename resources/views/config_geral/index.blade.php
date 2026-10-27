@extends('layouts.app', ['title' => 'Configurações Gerais'])

@section('css')
<style>
/* ─── Navegação por Abas (Tabs) ─── */
.nav-tabs-custom {
    background: #f8fafc;
    padding: 6px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.nav-tabs-custom .nav-link {
    flex: 1;
    min-width: 160px;
    border-radius: 10px !important;
    padding: 12px 18px;
    font-weight: 600;
    font-size: 13px;
    color: #64748b;
    border: none !important;
    background: transparent;
    text-align: center;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.nav-tabs-custom .nav-link:hover {
    color: #334155;
    background: rgba(255, 255, 255, 0.7);
}

.nav-tabs-custom .nav-link.active {
    background: #ffffff !important;
    color: #4f46e5 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
}

/* ─── Painéis de Seção Interna ─── */
.card-secao-fiscal {
    border: 1px solid #eef2f6 !important;
    border-radius: 14px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
    margin-bottom: 24px !important;
    background: #ffffff;
    overflow: hidden;
}
.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 14px 20px;
}
.card-secao-fiscal .card-header h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-secao-fiscal .card-body { padding: 24px !important; }

/* ─── Checkboxes Estilizados ─── */
.check-module-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    cursor: pointer;
    margin-bottom: 0;
    transition: all .2s ease;
    font-size: 13px;
    color: #334155;
}
.check-module-label:hover {
    border-color: #4f46e5;
    background: #f5f7ff;
    color: #4f46e5;
}
.check-module-label input[type=checkbox] {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    cursor: pointer;
    accent-color: #4f46e5;
}

/* ─── Aba Backup ─── */
.backup-card {
    border: 1px solid #eef0f6;
    border-radius: 14px;
    background: #fff;
    overflow: hidden;
    margin-bottom: 20px;
}
.backup-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #eef0f6;
}
.backup-card-header h5 { margin: 0; font-size: 14px; font-weight: 700; color: #1e293b; }
.backup-card-body { padding: 22px; }
.backup-btn-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 14px;
}
.backup-info-box {
    background: #f0f4ff;
    border: 1px solid #d4dcff;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 12.5px;
    color: #3730a3;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    margin-bottom: 18px;
}
.backup-info-box i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
.backup-history-table th { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: .4px; background: #f8fafc; }
.backup-history-table td { font-size: 13px; vertical-align: middle; }
.backup-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 6px; font-size: 11.5px; font-weight: 600; }
.backup-badge-sql { background: #dcfce7; color: #15803d; }
.backup-progress { display: none; margin-top: 14px; }
.backup-spinner { width: 18px; height: 18px; border: 3px solid #e0e7ff; border-top-color: #4f46e5; border-radius: 50%; animation: bspin .7s linear infinite; display: inline-block; vertical-align: middle; margin-right: 8px; }
@keyframes bspin { to { transform: rotate(360deg); } }

/* ─── Estilos da Restauração de Backup ─── */
.backup-section-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #475569;
    margin: 22px 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.backup-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}
.backup-restore-box {
    border: 1px solid #fed7aa;
    background: #fffbeb;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 12.5px;
    color: #92400e;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    margin-bottom: 16px;
}
.backup-restore-box i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }

/* ─── Botões de Ação na Tabela de Histórico (Design Profissional) ─── */
.backup-actions-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}
.act-btn {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none !important;
    padding: 0;
    line-height: 1;
    background: #f8fafc;
}
.act-btn i {
    font-size: 17px;
    line-height: 1;
    transition: transform 0.2s ease;
}
.act-btn:hover i {
    transform: scale(1.15);
}

/* 1. Botão Restaurar (Amber) */
.act-btn.act-restore {
    background: #fffbeb;
    color: #d97706;
    border-color: #fde68a;
}
.act-btn.act-restore:hover {
    background: #fde68a;
    color: #92400e;
    border-color: #fcd34d;
    box-shadow: 0 3px 8px rgba(245, 158, 11, 0.25);
    transform: translateY(-1px);
}

/* 2. Botão Download (Indigo) */
.act-btn.act-view {
    background: #eef2ff;
    color: #4f46e5;
    border-color: #c7d2fe;
}
.act-btn.act-view:hover {
    background: #e0e7ff;
    color: #3730a3;
    border-color: #a5b4fc;
    box-shadow: 0 3px 8px rgba(79, 70, 229, 0.25);
    transform: translateY(-1px);
}

/* 3. Botão Excluir (Red) */
.act-btn.act-del {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}
.act-btn.act-del:hover {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fca5a5;
    box-shadow: 0 3px 8px rgba(220, 38, 38, 0.25);
    transform: translateY(-1px);
}
.backup-file-picker {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 22px 16px;
    text-align: center;
    background: #f8fafc;
    transition: all .2s ease;
    cursor: pointer;
    position: relative;
}
.backup-file-picker:hover {
    border-color: #f59e0b;
    background: #fffdf5;
}
.backup-file-picker input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}
.backup-file-info {
    display: none;
    align-items: center;
    justify-content: space-between;
    background: #f1f5f9;
    padding: 10px 14px;
    border-radius: 8px;
    margin-top: 12px;
    font-size: 13px;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-settings-3-fill"></i>
                                Configurações Gerais do Sistema
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Defina os parâmetros globais de funcionamento para PDV, impressoras térmicas, regras de venda e alertas.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Início
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->post()
                    ->route('config-geral.store')
                    ->multipart()
                    !!}

                    <!-- ═══ NAVEGAÇÃO POR ABAS ═══ -->
                    <ul class="nav nav-pills nav-tabs-custom mb-4" id="config-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="pill" href="#tab-pdv" role="tab">
                                <i class="ri-store-2-line"></i>
                                <span>PDV & Caixa</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab-impressora" role="tab">
                                <i class="ri-printer-line"></i>
                                <span>Impressora Térmica</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab-vendas" role="tab">
                                <i class="ri-shopping-bag-3-line"></i>
                                <span>Vendas, Produtos & Estoque</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab-alertas" role="tab">
                                <i class="ri-notification-3-line"></i>
                                <span>Alertas & Notificações</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab-backup" role="tab">
                                <i class="ri-database-2-line"></i>
                                <span>Backup & Restauração</span>
                            </a>
                        </li>
                    </ul>

                    <!-- ═══ CONTEÚDO DAS ABAS ═══ -->
                    <div class="tab-content" id="config-tabContent">

                        <!-- ══════════════ ABA 1: PDV & CAIXA ══════════════ -->
                        <div class="tab-pane fade show active" id="tab-pdv" role="tabpanel">
                            
                            {{-- Parâmetros do PDV --}}
                            <div class="card card-secao-fiscal">
                                <div class="card-header">
                                    <h5><i class="ri-store-2-line text-primary"></i> Parâmetros Operacionais do PDV</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3 col-6">
                                            {!!Form::text('balanca_digito_verificador', 'Dígitos ref. balança')
                                            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 2 ou 7'])
                                            ->value(isset($item) ? $item->balanca_digito_verificador : '')
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('balanca_valor_peso', 'Tipo leitura balança', ['valor' => 'Valor Total', 'peso' => 'Peso (KG)'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('abrir_modal_cartao', 'Modal de dados do cartão', ['1' => 'Sim (Exibir)', '0' => 'Não (Ocultar)'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::text('senha_manipula_valor', 'Senha desconto / acréscimo')
                                            ->attrs(['class' => 'form-control', 'placeholder' => 'Opcional'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('agrupar_itens', 'Agrupar itens repetidos no cupom', ['0' => 'Não', '1' => 'Sim'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('tipo_comissao', 'Cálculo de comissão', ['percentual_vendedor' => '% Sobre Valor de Venda', 'percentual_margem' => '% Sobre Margem de Lucro'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('modelo', 'Layout do PDV', ['light' => 'Light (Claro)', 'compact' => 'Compact (Compacto)'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('alerta_sonoro', 'Efeitos sonoros no leitor', ['1' => 'Sim (Ativo)', '0' => 'Não (Desativado)'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::select('cabecalho_pdv', 'Exibir cabeçalho no PDV', ['1' => 'Sim', '0' => 'Não'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Formas de Pagamento no PDV --}}
                            <div class="card card-secao-fiscal">
                                <div class="card-header">
                                    <h5><i class="ri-bank-card-line text-primary"></i> Formas de Pagamento Habilitadas no PDV</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted fs-13 mb-3">
                                        Marque as formas de pagamento que devem aparecer disponíveis na tela de finalização de venda do PDV:
                                    </p>
                                    <div class="row g-2">
                                        @foreach(\App\Models\Nfce::tiposPagamento() as $key => $t)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <label class="check-module-label">
                                                <input name="tipos_pagamento_pdv[]" value="{{$t}}" type="checkbox" class="form-check-input check-module"
                                                    @isset($item) @if(sizeof($item->tipos_pagamento_pdv) > 0 && in_array($t, $item->tipos_pagamento_pdv)) checked="true" @endif @endif>
                                                <span class="fw-semibold">{{$t}}</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- ══════════════ ABA 2: IMPRESSORA TÉRMICA ══════════════ -->
                        <div class="tab-pane fade" id="tab-impressora" role="tabpanel">
                            <div class="card card-secao-fiscal">
                                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <h5 class="mb-0"><i class="ri-printer-line text-primary me-1"></i> Configuração da Impressora Térmica (Cupom Fiscal & Não Fiscal)</h5>
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">Padronização ESC/POS</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Ativação -->
                                        <div class="col-md-3 col-12">
                                            <label class="form-label fw-semibold">Impressora Térmica</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="printer_status" value="1" id="printer-status-toggle"
                                                    @isset($item) @if($item->printer_status == 1) checked @endif @endif>
                                                <label class="form-check-label fw-semibold text-dark" for="printer-status-toggle" style="font-size:13px;">Habilitar Impressão Térmica</label>
                                            </div>
                                        </div>

                                        <!-- Tipo de Conexão: Rede ou USB -->
                                        <div class="col-md-3 col-12 secao-printer-ativa">
                                            <label class="form-label fw-semibold required">Tipo de Conexão</label>
                                            <select name="printer_tipo" class="form-select" id="printer-tipo-select">
                                                <option value="rede" @isset($item) @if(($item->printer_tipo ?? 'rede') == 'rede') selected @endif @else selected @endif>
                                                    🌐 Rede (TCP/IP - Envio Direto)
                                                </option>
                                                <option value="usb" @isset($item) @if(($item->printer_tipo ?? '') == 'usb') selected @endif @endif>
                                                    🔌 USB / Local (Driver do Windows)
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Nome da Impressora -->
                                        <div class="col-md-3 col-12 secao-printer-ativa">
                                            {!!Form::text('printer_nome', 'Nome / Identificação da Impressora')
                                            ->id('printer-nome')
                                            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Térmica Balcão'])
                                            !!}
                                        </div>

                                        <!-- Largura da Bobina -->
                                        <div class="col-md-3 col-12 secao-printer-ativa">
                                            <label class="form-label fw-semibold">Largura da Bobina</label>
                                            <select name="printer_largura" class="form-select" id="printer-largura">
                                                <option value="80" @isset($item) @if(($item->printer_largura ?? '80') == '80') selected @endif @else selected @endif>80mm (Padrão de Mercado)</option>
                                                <option value="58" @isset($item) @if(($item->printer_largura ?? '80') == '58') selected @endif @endif>58mm (Bobina Estreita / Compacta)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ═══ CAMPOS ESPECÍFICOS DE REDE (TCP/IP) ═══ -->
                                    <div id="bloco-printer-rede" class="mt-3 p-3 rounded-3 border bg-light-subtle secao-printer-ativa" style="display: none;">
                                        <h6 class="fw-bold text-dark fs-13 mb-3 d-flex align-items-center gap-1">
                                            <i class="ri-router-line text-primary"></i> Parâmetros de Rede da Impressora (TCP/IP)
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4 col-12">
                                                {!!Form::text('printer_ip', 'Endereço IP na Rede')
                                                ->id('printer-ip')
                                                ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 192.168.1.200'])
                                                !!}
                                                <small class="text-muted fs-11">Endereço IP fixado na impressora térmica na mesma rede do PDV.</small>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                {!!Form::tel('printer_porta', 'Porta TCP/IP')
                                                ->id('printer-porta')
                                                ->attrs(['class' => 'form-control', 'placeholder' => '9100', 'value' => isset($item) && $item->printer_porta ? $item->printer_porta : 9100])
                                                !!}
                                                <small class="text-muted fs-11">Padrão de fábrica: 9100</small>
                                            </div>
                                            <div class="col-md-5 col-12 d-flex align-items-end">
                                                <button type="button" class="dash-btn dash-btn-light w-100" id="btn-testar-impressora" onclick="testarImpressora()">
                                                    <i class="ri-wifi-line me-1 text-primary"></i> Testar Conexão com a Impressora
                                                </button>
                                            </div>
                                            <div class="col-12 mt-1">
                                                <span id="printer-test-result" class="fs-13"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ═══ AVISO/INSTRUÇÃO DE USB LOCAL ═══ -->
                                    <div id="bloco-printer-usb" class="mt-3 p-3 rounded-3 border bg-light-subtle secao-printer-ativa" style="display: none;">
                                        <div class="d-flex align-items-start gap-3">
                                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                                                <i class="ri-usb-line"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark fs-14 mb-1">Modo Impressora Térmica USB Ativado</h6>
                                                <p class="text-muted fs-12 mb-2">
                                                    A impressora está conectada diretamente via <strong>cabo USB</strong> no computador do caixa. Ao finalizar qualquer venda no PDV ou emitir NFC-e, o sistema abrirá o cupom térmico no <strong>modelo oficial padronizado</strong> (idêntico ao da rede) e disparará automaticamente a caixa de impressão do Windows sem quebras de página ou margens indesejadas.
                                                </p>
                                                <button type="button" class="dash-btn dash-btn-light btn-sm" onclick="window.open('{{ route('print.termico-teste') }}', '_blank', 'width=450,height=650');">
                                                    <i class="ri-printer-line me-1 text-success"></i> Imprimir Página de Teste Térmico (USB)
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="alert alert-info border-0 shadow-sm py-3 px-3 mb-0" style="border-radius: 12px; background: #f0f4ff;">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="ri-information-line fs-18 text-primary mt-0.5"></i>
                                                    <div class="fs-13 text-dark">
                                                        <strong>Como funciona a Padronização:</strong> Tanto na impressão em <strong>Rede</strong> quanto em <strong>USB</strong>, o sistema emite <strong>exatamente o mesmo modelo</strong> de cupom fiscal (NFC-e) e cupom não fiscal, com tabelas e itens alinhados. Quando a opção estiver desmarcada, o comprovante abrirá em uma nova página normalmente para impressão manual livre.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════ ABA 3: VENDAS, PRODUTOS & ESTOQUE ══════════════ -->
                        <div class="tab-pane fade" id="tab-vendas" role="tabpanel">
                            
                            {{-- Pré-Venda & Orçamento --}}
                            <div class="card card-secao-fiscal">
                                <div class="card-header">
                                    <h5><i class="ri-file-list-3-line text-primary"></i> Regras de Pré-Venda & Orçamentos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6 col-12">
                                            {!!Form::select('confirmar_itens_prevenda', 'Exigir confirmação dos itens na Pré-Venda?', ['0' => 'Não (Direto)', '1' => 'Sim (Confirmar)'] )
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                        <div class="col-md-6 col-12">
                                            {!!Form::tel('percentual_desconto_orcamento', '% Máximo de desconto permitido sobre lucro no orçamento')
                                            ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Produtos & Estoque --}}
                            <div class="card card-secao-fiscal">
                                <div class="card-header">
                                    <h5><i class="ri-box-3-line text-primary"></i> Produtos & Controle de Estoque</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4 col-12">
                                            {!!Form::tel('percentual_lucro_produto', '% Margem de lucro padrão para novos produtos')
                                            ->attrs(['class' => 'form-control percentual', 'placeholder' => 'Ex: 50,00%'])
                                            !!}
                                        </div>
                                        <div class="col-md-4 col-12">
                                            {!!Form::tel('margem_combo', 'Margem % aplicada em produtos do tipo combo')
                                            ->attrs(['class' => 'form-control percentual', 'placeholder' => 'Ex: 50,00%'])
                                            !!}
                                        </div>
                                        <div class="col-md-4 col-12">
                                            {!!Form::select('gerenciar_estoque', 'Gerenciar controle de estoque global?', ['1' => 'Sim (Controlar)', '0' => 'Não (Sem controle)'])
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- ══════════════ ABA 4: ALERTAS & NOTIFICAÇÕES ══════════════ -->
                        <div class="tab-pane fade" id="tab-alertas" role="tabpanel">
                            <div class="card card-secao-fiscal">
                                <div class="card-header">
                                    <h5><i class="ri-notification-3-line text-primary"></i> Alertas e Notificações Ativas</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted fs-13 mb-3">
                                        Selecione quais avisos e notificações devem ser gerados automaticamente pelo sistema:
                                    </p>
                                    <div class="row g-2">
                                        @foreach(App\Models\ConfigGeral::getNotificacoes() as $n)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <label class="check-module-label">
                                                <input name="notificacoes[]" value="{{$n}}" type="checkbox" class="form-check-input"
                                                    @isset($item) @if(sizeof($item->notificacoes) > 0 && in_array($n, $item->notificacoes)) checked="true" @endif @endif>
                                                <span class="fw-semibold">{{$n}}</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════ ABA 5: BACKUP E RESTAURAÇÃO DA EMPRESA ══════════════ -->
                        <div class="tab-pane fade" id="tab-backup" role="tabpanel">

                            @php
                                $empresaAtualId = request()->empresa_id ?? (Auth::check() && Auth::user()->empresa ? Auth::user()->empresa->empresa_id : ($item ? $item->empresa_id : null));
                            @endphp

                            {{-- Aviso informativo --}}
                            <div class="backup-info-box">
                                <i class="ri-shield-check-line"></i>
                                <div>
                                    <strong>Backup & Restauração com Isolamento por Empresa (Multitenant)</strong><br>
                                    As operações de backup e restauração trabalham <strong>exclusivamente com os dados desta empresa (ID: {{ $empresaAtualId }})</strong>.
                                    Nenhum dado de outras empresas é exportado, alterado ou excluído. Backups de outras empresas são bloqueados automaticamente pelo sistema.
                                </div>
                            </div>

                            {{-- TÍTULO SEÇÃO 1: EXPORTAR BACKUP --}}
                            <div class="backup-section-title">
                                <i class="ri-upload-cloud-line text-primary"></i> 1. Gerar e Exportar Backup
                            </div>

                            <div class="row g-4">

                                {{-- CARD 1: DOWNLOAD DIRETO --}}
                                <div class="col-12 col-lg-6">
                                    <div class="backup-card h-100">
                                        <div class="backup-card-header">
                                            <i class="ri-download-cloud-2-line fs-20" style="color:#4f46e5;"></i>
                                            <h5>Download Direto</h5>
                                        </div>
                                        <div class="backup-card-body d-flex flex-column justify-content-between">
                                            <p class="text-muted fs-13 mb-3">
                                                Gera e baixa imediatamente um arquivo <code>.sql</code> com todos os dados desta empresa.
                                                Salve o arquivo em local seguro no seu computador.
                                            </p>
                                            <div>
                                                <button type="button" class="dash-btn dash-btn-primary w-100" id="btn-backup-download" onclick="iniciarBackup('download')">
                                                    <i class="ri-download-2-line me-1"></i> Gerar e Baixar Backup (.sql)
                                                </button>
                                                <div class="backup-progress" id="progress-download">
                                                    <span class="backup-spinner"></span>
                                                    <span class="fs-13 text-muted">Gerando backup, aguarde...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- CARD 2: SALVAR NO SERVIDOR --}}
                                <div class="col-12 col-lg-6">
                                    <div class="backup-card h-100">
                                        <div class="backup-card-header">
                                            <i class="ri-server-line fs-20" style="color:#10b981;"></i>
                                            <h5>Salvar no Servidor</h5>
                                        </div>
                                        <div class="backup-card-body d-flex flex-column justify-content-between">
                                            <p class="text-muted fs-13 mb-3">
                                                Salva o backup na pasta isolada desta empresa no servidor (Hostinger).
                                                Permite restaurar com 1 clique a qualquer momento através do histórico abaixo.
                                            </p>
                                            <div>
                                                <button type="button" class="dash-btn w-100" style="background:#10b981;color:#fff;border:none;" id="btn-backup-servidor" onclick="iniciarBackup('servidor')">
                                                    <i class="ri-save-line me-1"></i> Salvar Backup no Servidor
                                                </button>
                                                <div class="backup-progress" id="progress-servidor">
                                                    <span class="backup-spinner"></span>
                                                    <span class="fs-13 text-muted">Salvando no servidor, aguarde...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- TÍTULO SEÇÃO 2: RESTAURAR BACKUP --}}
                            <div class="backup-section-title">
                                <i class="ri-history-line text-warning"></i> 2. Restaurar Dados da Empresa
                            </div>

                            {{-- CARD DE RESTAURAÇÃO VIA UPLOAD DE ARQUIVO --}}
                            <div class="backup-card">
                                <div class="backup-card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-device-recover-line fs-20" style="color:#d97706;"></i>
                                        <h5>Restaurar a partir de Arquivo (.sql)</h5>
                                    </div>
                                    <span class="badge bg-soft-warning text-warning fs-12 px-2 py-1">
                                        <i class="ri-shield-keyhole-line me-1"></i>Validação Estrita por Empresa
                                    </span>
                                </div>
                                <div class="backup-card-body">
                                    <div class="backup-restore-box">
                                        <i class="ri-alert-line"></i>
                                        <div>
                                            <strong>Atenção ao Restaurar:</strong>
                                            O arquivo <code>.sql</code> deve ter sido gerado para esta empresa (<strong>Empresa ID: {{ $empresaAtualId }}</strong>).
                                            Antes de aplicar qualquer alteração, o sistema verifica a identidade da empresa no arquivo e rejeita comandos não permitidos.
                                        </div>
                                    </div>

                                    <div class="row g-3 align-items-center">
                                        <div class="col-12 col-md-7">
                                            <div class="backup-file-picker" id="dropzone-backup-file">
                                                <input type="file" id="input-arquivo-backup" accept=".sql" onchange="arquivoBackupSelecionado(this)">
                                                <div id="picker-placeholder">
                                                    <i class="ri-file-upload-line fs-28 text-muted d-block mb-1"></i>
                                                    <span class="fw-semibold text-dark fs-13">Clique ou arraste o arquivo .sql aqui</span>
                                                    <span class="d-block text-muted fs-12">Tamanho máximo permitido: 100 MB</span>
                                                </div>
                                                <div class="backup-file-info" id="picker-file-info">
                                                    <div class="d-flex align-items-center gap-2 text-start">
                                                        <i class="ri-file-code-line text-primary fs-20"></i>
                                                        <div>
                                                            <div class="fw-bold text-dark fs-13" id="picker-nome-arquivo">arquivo.sql</div>
                                                            <div class="text-muted fs-11" id="picker-tamanho-arquivo">0 KB</div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="limparArquivoBackup(event)" title="Remover seleção">
                                                        <i class="ri-close-circle-line fs-18"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-5">
                                            <div class="p-3 bg-light rounded-3 border">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" id="check-limpar-antes" checked style="cursor:pointer; width:38px; height:20px;">
                                                    <label class="form-check-label ms-2 fs-13 fw-semibold text-dark" for="check-limpar-antes" style="cursor:pointer;">
                                                        Substituir dados atuais da empresa
                                                    </label>
                                                    <div class="text-muted fs-11 mt-1">
                                                        Remove os registros anteriores desta empresa antes de restaurar, mantendo o banco idêntico ao backup e prevenindo erros de chave duplicada.
                                                    </div>
                                                </div>

                                                <button type="button" class="dash-btn w-100" style="background:#f59e0b;color:#fff;border:none;font-weight:600;padding:10px 16px;" id="btn-executar-restauracao" onclick="iniciarRestauracaoUpload()">
                                                    <i class="ri-history-line me-1"></i> Iniciar Restauração do Arquivo
                                                </button>

                                                <div class="backup-progress" id="progress-restauracao-upload">
                                                    <span class="backup-spinner" style="border-top-color:#f59e0b;"></span>
                                                    <span class="fs-13 text-muted">Validando e restaurando dados, aguarde... Não saia desta tela.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TÍTULO SEÇÃO 3: HISTÓRICO NO SERVIDOR --}}
                            <div class="backup-section-title">
                                <i class="ri-folder-history-line text-secondary"></i> 3. Histórico de Backups no Servidor
                            </div>

                            {{-- HISTÓRICO DE BACKUPS NO SERVIDOR --}}
                            <div class="backup-card">
                                <div class="backup-card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-history-line fs-18" style="color:#64748b;"></i>
                                        <h5>Backups Armazenados para Esta Empresa</h5>
                                    </div>
                                    <button type="button" class="dash-btn dash-btn-light" onclick="carregarHistoricoBackup()" id="btn-refresh-backup">
                                        <i class="ri-refresh-line"></i> Atualizar
                                    </button>
                                </div>
                                <div class="backup-card-body">
                                    <div id="backup-history-container">
                                        <div class="text-center py-4 text-muted fs-13">
                                            <i class="ri-inbox-2-line fs-30 d-block mb-2"></i>
                                            Clique em "Atualizar" para ver os backups salvos no servidor.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>{{-- /config-tabContent --}}

                    <!-- ═══ BOTÃO SALVAR CONFIGURAÇÕES GERAIS ═══ -->
                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 mt-3 border-top" id="footer-config-salvar">
                        <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                            <i class="ri-close-line me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                            <i class="ri-save-line me-1"></i> Salvar Configurações Gerais
                        </button>
                    </div>

                    {!!Form::close()!!}

                    {{-- Form autônomo para ações de backup (fora do form de config-geral para não aninhar tags form) --}}
                    <form id="form-backup-executar" method="POST" action="{{ route('backup-empresa.gerar') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="acao" id="input-backup-acao" value="download">
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function testarImpressora() {
    var ip = ($('#printer-ip').length && $('#printer-ip').val() ? $('#printer-ip').val() : '') ||
             ($('input[name="printer_ip"]').length && $('input[name="printer_ip"]').val() ? $('input[name="printer_ip"]').val() : '') ||
             ($('#inp-printer_ip').length && $('#inp-printer_ip').val() ? $('#inp-printer_ip').val() : '');

    var porta = ($('#printer-porta').length && $('#printer-porta').val() ? $('#printer-porta').val() : '') ||
                ($('input[name="printer_porta"]').length && $('input[name="printer_porta"]').val() ? $('input[name="printer_porta"]').val() : '') ||
                ($('#inp-printer_porta').length && $('#inp-printer_porta').val() ? $('#inp-printer_porta').val() : '') ||
                9100;

    var result = $('#printer-test-result');
    var btn = $('#btn-testar-impressora');

    if (!ip || !$.trim(ip)) {
        result.html('<span class="text-danger fw-bold"><i class="ri-error-warning-line"></i> Informe o IP da impressora</span>');
        return;
    }
    ip = $.trim(ip);
    porta = $.trim(porta) || 9100;

    btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Testando conexão...');
    result.html('<span class="text-muted"><i class="ri-loader-4-line spin me-1"></i> Conectando em ' + ip + ':' + porta + '...</span>');

    $.ajax({
        url: '{{ route("print.testar") }}',
        type: 'POST',
        data: { ip: ip, porta: porta, _token: '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) {
                result.html('<span class="text-success fw-bold"><i class="ri-checkbox-circle-fill"></i> ' + res.message + '</span>');
                btn.prop('disabled', false).html('<i class="ri-wifi-line me-1 text-primary"></i> Testar Conexão com a Impressora');
            } else {
                // Se a Hostinger não alcança o IP da LAN, tenta pelo Agente Local (WebSocket / HTTP)
                testarViaAgenteLocal(ip, porta, btn, result, res.message);
            }
        },
        error: function(xhr) {
            testarViaAgenteLocal(ip, porta, btn, result, 'Servidor em nuvem sem acesso direto ao IP local');
        }
    });
}

function testarViaAgenteLocal(ip, porta, btn, result, erroServidor) {
    result.html('<span class="text-muted"><i class="ri-loader-4-line spin me-1"></i> Tentando via Agente Local (ws://127.0.0.1:9187)...</span>');

    var wsConcluido = false;
    var wsTimeout = null;
    var ws = null;

    try {
        ws = new WebSocket('ws://127.0.0.1:9187');
    } catch(e) {
        testarViaHttpFallback(ip, porta, btn, result, erroServidor);
        return;
    }

    wsTimeout = setTimeout(function() {
        if (!wsConcluido) {
            wsConcluido = true;
            try { ws.close(); } catch(e){}
            testarViaHttpFallback(ip, porta, btn, result, erroServidor);
        }
    }, 3500);

    ws.onopen = function() {
        ws.send(JSON.stringify({
            action: 'test',
            ip: ip,
            porta: porta
        }));
    };

    ws.onmessage = function(evt) {
        if (wsConcluido) return;
        wsConcluido = true;
        clearTimeout(wsTimeout);
        try { ws.close(); } catch(e){}

        try {
            var data = JSON.parse(evt.data);
            if (data.success) {
                result.html('<span class="text-success fw-bold"><i class="ri-checkbox-circle-fill"></i> ' + data.message + '</span>');
            } else {
                result.html('<span class="text-danger fw-bold"><i class="ri-error-warning-line"></i> ' + (data.message || 'Falha ao conectar') + '</span>');
            }
        } catch(ex) {
            result.html('<span class="text-success fw-bold"><i class="ri-checkbox-circle-fill"></i> Conexão estabelecida com o Agente Local!</span>');
        }
        btn.prop('disabled', false).html('<i class="ri-wifi-line me-1 text-primary"></i> Testar Conexão com a Impressora');
    };

    ws.onerror = function(err) {
        if (wsConcluido) return;
        wsConcluido = true;
        clearTimeout(wsTimeout);
        try { ws.close(); } catch(e){}
        testarViaHttpFallback(ip, porta, btn, result, erroServidor);
    };
}

function testarViaHttpFallback(ip, porta, btn, result, erroServidor) {
    $.ajax({
        url: 'http://127.0.0.1:9187/test',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ ip: ip, porta: porta }),
        timeout: 3000,
        success: function(agentRes) {
            if (agentRes && agentRes.success) {
                result.html('<span class="text-success fw-bold"><i class="ri-checkbox-circle-fill"></i> ' + agentRes.message + '</span>');
            } else {
                result.html('<span class="text-danger fw-bold"><i class="ri-error-warning-line"></i> ' + (agentRes.message || 'Falha ao conectar') + '</span>');
            }
        },
        error: function(err) {
            result.html('<span class="text-danger fw-bold"><i class="ri-error-warning-line"></i> ' + erroServidor + ' (Inicie o Agente de Impressão na máquina local)</span>');
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="ri-wifi-line me-1 text-primary"></i> Testar Conexão com a Impressora');
        }
    });
}

function atualizarCamposImpressora() {
    var ativo = $('#printer-status-toggle').is(':checked');
    var tipo = $('#printer-tipo-select').val();

    if (!ativo) {
        $('.secao-printer-ativa').css('opacity', '0.45');
        $('#bloco-printer-rede').slideUp(200);
        $('#bloco-printer-usb').slideUp(200);
    } else {
        $('.secao-printer-ativa').css('opacity', '1');
        if (tipo === 'usb') {
            $('#bloco-printer-rede').slideUp(200);
            $('#bloco-printer-usb').slideDown(200);
        } else {
            $('#bloco-printer-usb').slideUp(200);
            $('#bloco-printer-rede').slideDown(200);
        }
    }
}

$(document).ready(function() {
    $('#printer-status-toggle').on('change', atualizarCamposImpressora);
    $('#printer-tipo-select').on('change', atualizarCamposImpressora);
    atualizarCamposImpressora();
});

// ─── Backup: execução e controle ─────────────────────────────────────────────
function iniciarBackup(acao) {
    if (acao === 'download') {
        $('#btn-backup-download').prop('disabled', true).html('<span class="backup-spinner"></span> Gerando backup...');
        $('#progress-download').show();

        // Restaura o botão após alguns segundos para permitir nova ação
        setTimeout(function() {
            $('#btn-backup-download').prop('disabled', false).html('<i class="ri-download-2-line me-1"></i> Gerar e Baixar Backup');
            $('#progress-download').hide();
        }, 8000);
    } else {
        $('#btn-backup-servidor').prop('disabled', true).html('<span class="backup-spinner"></span> Salvando no servidor...');
        $('#progress-servidor').show();
    }

    $('#input-backup-acao').val(acao);
    $('#form-backup-executar').submit();
}

// ─── Carregar histórico de backups do servidor via AJAX ─────────────────────
function carregarHistoricoBackup() {
    var btn = $('#btn-refresh-backup');
    btn.prop('disabled', true).html('<span class="backup-spinner"></span> Atualizando...');

    $.get('{{ route("backup-empresa.listar") }}', function(res) {
        var container = $('#backup-history-container');
        if (!res.arquivos || res.arquivos.length === 0) {
            container.html('<div class="text-center py-4 text-muted fs-13"><i class="ri-inbox-2-line fs-30 d-block mb-2"></i>Nenhum backup salvo no servidor ainda.</div>');
        } else {
            var html = '<div class="table-responsive"><table class="table table-hover align-middle mb-0 backup-history-table">';
            html += '<thead><tr><th><i class="ri-file-code-line me-1"></i>Arquivo</th><th>Tamanho</th><th>Data de Criação</th><th class="text-end">Ações</th></tr></thead><tbody>';
            $.each(res.arquivos, function(i, arq) {
                html += '<tr>';
                html += '<td><span class="backup-badge backup-badge-sql"><i class="ri-database-line"></i> ' + arq.nome + '</span></td>';
                html += '<td class="text-muted fs-13">' + arq.tamanho + '</td>';
                html += '<td class="text-muted fs-13">' + arq.data + '</td>';
                html += '<td class="text-end">';
                html += '<div class="backup-actions-wrap">';
                html += '<button type="button" class="act-btn act-restore" title="Restaurar este backup nesta empresa" onclick="confirmarRestaurarServidor(\'' + arq.nome + '\')"><i class="ri-history-line"></i></button>';
                html += '<a href="{{ route("backup-empresa.download-servidor") }}?nome=' + encodeURIComponent(arq.nome) + '" class="act-btn act-view" title="Baixar este arquivo"><i class="ri-download-line"></i></a>';
                html += '<button type="button" class="act-btn act-del" title="Excluir do servidor" onclick="removerBackup(\'' + arq.nome + '\')"><i class="ri-delete-bin-line"></i></button>';
                html += '</div>';
                html += '</td></tr>';
            });
            html += '</tbody></table></div>';
            container.html(html);
        }
    }).fail(function() {
        $('#backup-history-container').html('<div class="alert alert-danger fs-13">Erro ao carregar lista de backups. Tente novamente.</div>');
    }).always(function() {
        btn.prop('disabled', false).html('<i class="ri-refresh-line"></i> Atualizar');
    });
}

function removerBackup(nome) {
    if (!confirm('Tem certeza que deseja remover o backup "' + nome + '" do servidor?')) return;

    $.ajax({
        url: '{{ route("backup-empresa.remover-servidor") }}',
        type: 'DELETE',
        data: { nome: nome, _token: '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) {
                carregarHistoricoBackup();
            } else {
                alert(res.message);
            }
        },
        error: function() {
            alert('Erro ao excluir o arquivo do servidor.');
        }
    });
}

// ─── Seleção e UI de Arquivo de Backup ──────────────────────────────────────
function arquivoBackupSelecionado(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        var sizeFormatted = sizeMb >= 1 ? sizeMb + ' MB' : (file.size / 1024).toFixed(1) + ' KB';

        $('#picker-nome-arquivo').text(file.name);
        $('#picker-tamanho-arquivo').text(sizeFormatted);
        $('#picker-placeholder').hide();
        $('#picker-file-info').css('display', 'flex');
    }
}

function limparArquivoBackup(e) {
    if (e) e.stopPropagation();
    $('#input-arquivo-backup').val('');
    $('#picker-file-info').hide();
    $('#picker-placeholder').show();
}

// ─── Executar Restauração a partir do Upload ────────────────────────────────
function iniciarRestauracaoUpload() {
    var fileInput = document.getElementById('input-arquivo-backup');
    if (!fileInput.files || !fileInput.files[0]) {
        if (typeof swal === 'function') {
            swal({
                title: "Atenção",
                text: "Por favor, selecione um arquivo .sql de backup antes de prosseguir.",
                icon: "warning",
                button: "Entendido"
            });
        } else {
            alert('Por favor, selecione um arquivo .sql de backup antes de prosseguir.');
        }
        return;
    }

    var file = fileInput.files[0];
    var limparAntes = $('#check-limpar-antes').is(':checked') ? 1 : 0;
    var avisoLimpeza = limparAntes 
        ? "Os dados atuais desta empresa serão substituídos pelos registros contidos no backup." 
        : "Os registros do backup serão inseridos/mesclados nesta empresa.";

    var mensagem = "Você confirma a restauração do arquivo \"" + file.name + "\"?\n\n" + avisoLimpeza + "\n\nApenas os dados desta empresa serão alterados.";

    var executar = function() {
        var btn = $('#btn-executar-restauracao');
        var progress = $('#progress-restauracao-upload');
        btn.prop('disabled', true).html('<span class="backup-spinner" style="border-top-color:#fff;"></span> Restaurando dados...');
        progress.show();

        var formData = new FormData();
        formData.append('arquivo_backup', file);
        formData.append('limpar_antes', limparAntes);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route("backup-empresa.restaurar-upload") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            timeout: 300000,
            success: function(res) {
                progress.hide();
                btn.prop('disabled', false).html('<i class="ri-history-line me-1"></i> Iniciar Restauração do Arquivo');
                limparArquivoBackup();

                if (res.success) {
                    if (typeof swal === 'function') {
                        swal({
                            title: "Restauração Concluída!",
                            text: res.message,
                            icon: "success",
                            button: "OK"
                        }).then(function() {
                            carregarHistoricoBackup();
                        });
                    } else {
                        alert(res.message);
                        carregarHistoricoBackup();
                    }
                } else {
                    if (typeof swal === 'function') {
                        swal("Atenção", res.message || "Não foi possível concluir a restauração.", "error");
                    } else {
                        alert(res.message);
                    }
                }
            },
            error: function(xhr) {
                progress.hide();
                btn.prop('disabled', false).html('<i class="ri-history-line me-1"></i> Iniciar Restauração do Arquivo');

                var msgErro = "Falha ao processar a restauração.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msgErro = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var json = JSON.parse(xhr.responseText);
                        if (json.message) msgErro = json.message;
                    } catch(e) {}
                }

                if (typeof swal === 'function') {
                    swal("Erro de Restauração", msgErro, "error");
                } else {
                    alert(msgErro);
                }
            }
        });
    };

    if (typeof swal === 'function') {
        swal({
            title: "Confirmar Restauração?",
            text: mensagem,
            icon: "warning",
            buttons: ["Cancelar", "Sim, Iniciar Restauração"],
            dangerMode: true,
        }).then(function(confirmou) {
            if (confirmou) {
                executar();
            }
        });
    } else {
        if (confirm(mensagem)) {
            executar();
        }
    }
}

// ─── Executar Restauração a partir do Servidor ─────────────────────────────
function confirmarRestaurarServidor(nome) {
    var mensagem = "Deseja restaurar o backup \"" + nome + "\" do servidor para a sua empresa?\n\nOs dados atuais desta empresa serão sincronizados com os dados deste backup.";

    var executar = function() {
        if (typeof swal === 'function') {
            swal({
                title: "Restaurando Backup...",
                text: "Aguarde enquanto os dados estão sendo aplicados. Não feche o navegador.",
                icon: "info",
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false
            });
        }

        $.ajax({
            url: '{{ route("backup-empresa.restaurar-servidor") }}',
            type: 'POST',
            data: {
                nome: nome,
                limpar_antes: 1,
                _token: '{{ csrf_token() }}'
            },
            timeout: 300000,
            success: function(res) {
                if (res.success) {
                    if (typeof swal === 'function') {
                        swal({
                            title: "Restauração Concluída!",
                            text: res.message,
                            icon: "success",
                            button: "OK"
                        }).then(function() {
                            carregarHistoricoBackup();
                        });
                    } else {
                        alert(res.message);
                        carregarHistoricoBackup();
                    }
                } else {
                    if (typeof swal === 'function') {
                        swal("Falha", res.message, "error");
                    } else {
                        alert(res.message);
                    }
                }
            },
            error: function(xhr) {
                var msg = "Erro ao executar restauração.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if (typeof swal === 'function') {
                    swal("Erro", msg, "error");
                } else {
                    alert(msg);
                }
            }
        });
    };

    if (typeof swal === 'function') {
        swal({
            title: "Restaurar Backup do Servidor?",
            text: mensagem,
            icon: "warning",
            buttons: ["Cancelar", "Sim, Restaurar"],
            dangerMode: true,
        }).then(function(confirmou) {
            if (confirmou) {
                executar();
            }
        });
    } else {
        if (confirm(mensagem)) {
            executar();
        }
    }
}

// ─── Controle das abas: esconde botão Salvar na aba Backup e carrega histórico
$('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
    var target = $(e.target).attr('href');
    if (target === '#tab-backup') {
        $('#footer-config-salvar').hide();
        carregarHistoricoBackup();
    } else {
        $('#footer-config-salvar').show();
    }
});
</script>
@endsection
