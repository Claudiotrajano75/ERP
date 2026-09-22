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
                                <div class="card-header">
                                    <h5><i class="ri-printer-line text-primary"></i> Impressora Térmica de Rede (ESC/POS)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3 col-12">
                                            <label class="form-label fw-semibold">Impressão Direta na Rede</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="printer_status" value="1" id="printer-status-toggle"
                                                    @isset($item) @if($item->printer_status == 1) checked @endif @endif>
                                                <label class="form-check-label fw-semibold text-dark" for="printer-status-toggle" style="font-size:13px;">Habilitar envio direto via IP</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            {!!Form::text('printer_nome', 'Nome / Identificação da Impressora')
                                            ->id('printer-nome')
                                            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Térmica Caixa 01'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::text('printer_ip', 'Endereço IP na Rede')
                                            ->id('printer-ip')
                                            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 192.168.1.200'])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-6">
                                            {!!Form::tel('printer_porta', 'Porta TCP/IP')
                                            ->id('printer-porta')
                                            ->attrs(['class' => 'form-control', 'placeholder' => '9100', 'value' => isset($item) && $item->printer_porta ? $item->printer_porta : 9100])
                                            !!}
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <label class="form-label fw-semibold">Largura da Bobina</label>
                                            <select name="printer_largura" class="form-select" id="printer-largura">
                                                <option value="80" @isset($item) @if(($item->printer_largura ?? '80') == '80') selected @endif @else selected @endif>80mm (Padrão de Mercado)</option>
                                                <option value="58" @isset($item) @if(($item->printer_largura ?? '80') == '58') selected @endif @endif>58mm (Bobina Estreita / Compacta)</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mt-3 d-flex align-items-center gap-3">
                                            <button type="button" class="dash-btn dash-btn-light" id="btn-testar-impressora" onclick="testarImpressora()">
                                                <i class="ri-wifi-line me-1 text-primary"></i> Testar Conexão com a Impressora
                                            </button>
                                            <span id="printer-test-result" class="fs-13"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="alert alert-info border-0 shadow-sm py-3 px-3 mb-0" style="border-radius: 12px; background: #f0f4ff;">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="ri-information-line fs-18 text-primary mt-0.5"></i>
                                                    <div class="fs-13 text-dark">
                                                        <strong>Como funciona a Impressão Direta:</strong> Quando habilitada, o sistema envia o comprovante de venda ou NFCe instantaneamente para a impressora via protocolo ESC/POS (porta 9100), sem necessidade de abrir a caixa de diálogo de impressão do navegador. Caso esteja desabilitada, o PDF continuará sendo aberto no navegador normalmente.
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

                    </div>

                    <!-- ═══ BOTÃO SALVAR ═══ -->
                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 mt-3 border-top">
                        <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                            <i class="ri-close-line me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                            <i class="ri-save-line me-1"></i> Salvar Configurações Gerais
                        </button>
                    </div>

                    {!!Form::close()!!}
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
</script>
@endsection
