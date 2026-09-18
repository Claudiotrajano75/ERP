<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Caixa</title>
    <style>
        @page {
            margin: 10px 15px 0px 10px !important;
            padding: 0px !important;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100%;
            font-family: 'Courier New', 'Consolas', monospace;
            font-size: 10px;
            color: #000;
            line-height: 1.3;
            padding: 0px;
        }

        /* ─── Cabeçalho ─── */
        .header {
            text-align: center;
            padding-bottom: 6px;
            border-bottom: 2px dashed #000;
            margin-bottom: 8px;
        }

        .header .company {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header .cnpj {
            font-size: 9px;
            margin-top: 2px;
        }

        .header .title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .date {
            font-size: 8px;
            margin-top: 4px;
            color: #555;
        }

        /* ─── Separadores ─── */
        .separator {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .separator-double {
            border: none;
            border-top: 2px solid #000;
            margin: 6px 0;
        }

        /* ─── Seções ─── */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
            margin-bottom: 4px;
        }

        /* ─── Info rows ─── */
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin: 2px 0;
        }

        .info-row .label {
            color: #555;
        }

        .info-row .value {
            font-weight: bold;
        }

        .info-row .value.green {
            /* monospace - cor não será visível em impressora monocromática */
        }

        /* ─── Tabelas ─── */
        .data-table {
            width: 100%;
            margin: 4px 0;
        }

        .data-table th {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 0;
            border-bottom: 1px solid #000;
            text-align: left;
        }

        .data-table th:last-child,
        .data-table td:last-child {
            text-align: right;
        }

        .data-table td {
            font-size: 9px;
            padding: 2px 0;
            border-bottom: 1px dotted #ccc;
        }

        /* ─── Itens compactos ─── */
        .item-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            padding: 2px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-row .item-left {
            flex: 1;
        }

        .item-row .item-right {
            font-weight: bold;
            white-space: nowrap;
        }

        .item-row .item-date {
            font-size: 8px;
            color: #555;
        }

        .item-row .item-desc {
            font-size: 8px;
            color: #333;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ─── Totais ─── */
        .total-box {
            border: 1px solid #000;
            padding: 6px 8px;
            margin: 6px 0;
        }

        .total-box .total-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            text-align: center;
        }

        .total-box .total-amount {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        /* ─── Resumo ─── */
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            padding: 2px 0;
        }

        .summary-row .summary-label {
            color: #555;
        }

        .summary-row .summary-value {
            font-weight: bold;
        }

        /* ─── Assinatura ─── */
        .signature {
            margin-top: 20px;
            text-align: center;
        }

        .signature .line {
            width: 180px;
            border-top: 1px solid #000;
            margin: 0 auto;
            margin-top: 30px;
            padding-top: 4px;
        }

        .signature .name {
            font-size: 10px;
            font-weight: bold;
        }

        .signature .info {
            font-size: 8px;
            color: #555;
        }

        .empty-text {
            font-size: 9px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CABEÇALHO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="header">
        <div class="company">{{ $config->nome_fantasia }}</div>
        <div class="cnpj">{{ __setMask($config->cpf_cnpj) }}</div>
        <div class="title">Relatório de Caixa</div>
        <div class="date">{{ date('d/m/Y H:i') }}</div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- DADOS DO CAIXA --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="info-row">
        <span class="label">Total de vendas:</span>
        <span class="value">R$ {{ number_format($item->valor_fechamento, 2, ',', '.') }}</span>
    </div>
    <div class="info-row">
        <span class="label">Abertura:</span>
        <span class="value">{{ __data_pt($item->created_at) }}</span>
    </div>
    <div class="info-row">
        <span class="label">Fechamento:</span>
        <span class="value">{{ __data_pt($item->updated_at) }}</span>
    </div>

    <hr class="separator-double">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- TOTAL POR TIPO DE PAGAMENTO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section-title">Tipo de Pagamento</div>
    <div class="data-table">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; font-size:8px; border-bottom:1px solid #000; padding:2px 0;">Tipo</th>
                    <th style="text-align:right; font-size:8px; border-bottom:1px solid #000; padding:2px 0;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($somaTiposPagamento as $key => $tp)
                    @if($tp > 0)
                    <tr>
                        <td style="font-size:9px; padding:2px 0; border-bottom:1px dotted #ccc;">{{ App\Models\Nfce::getTipoPagamento($key) }}</td>
                        <td style="font-size:9px; padding:2px 0; border-bottom:1px dotted #ccc; text-align:right; font-weight:bold;">R$ {{ __moeda($tp) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <hr class="separator-double">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- VENDAS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @php $soma = 0; @endphp
    <div class="section-title">Vendas ({{ count($vendas) }})</div>

    @foreach($vendas as $v)
    <div class="item-row">
        <div class="item-left">
            <div style="font-size:9px; font-weight:bold;">{{ $v->cliente->razao_social ?? 'NÃO IDENTIFICADO' }}</div>
            <div class="item-date">{{ __data_pt($v->created_at) }}</div>
            <div style="font-size:8px; color:#555;">
                @if($v->tipo_pagamento == '99')
                    Outros
                @else
                    {{ $v->tipo_pagamento ? $v->getTipoPagamento($v->tipo_pagamento) : 'Pag Múltiplo' }}
                @endif
                · {{ $v->tipo }}
                @if($v->estado == 'aprovado' && $v->numero > 0)
                    · #{{ $v->numero }}
                @endif
            </div>
        </div>
        <div class="item-right">
            @if($v->tipo != 'OS')
                R$ {{ __moeda($v->total) }}
            @else
                R$ {{ __moeda($v->valor) }}
            @endif
        </div>
    </div>
    @php
        $soma += ($v->tipo != 'OS') ? $v->total : $v->valor;
    @endphp
    @endforeach

    <div class="total-box">
        <div class="total-title">Total Geral</div>
        <div class="total-amount">R$ {{ number_format($soma, 2, ',', '.') }}</div>
    </div>

    <hr class="separator-double">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SUPRIMENTOS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @php $somaSuprimento = 0; @endphp
    <div class="section-title">Suprimentos</div>

    @if(sizeof($suprimentos) > 0)
        @foreach($suprimentos as $s)
            @php $somaSuprimento += $s->valor; @endphp
            <div class="item-row">
                <div class="item-left">
                    <div class="item-date">{{ __data_pt($s->created_at) }}</div>
                    <div class="item-desc">{{ $s->observacao }}</div>
                </div>
                <div class="item-right">R$ {{ __moeda($s->valor) }}</div>
            </div>
        @endforeach
    @else
        <div class="empty-text">Nenhum suprimento registrado.</div>
    @endif

    <hr class="separator">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SANGRIAS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @php $somaSangria = 0; @endphp
    <div class="section-title">Sangrias</div>

    @if(sizeof($sangrias) > 0)
        @foreach($sangrias as $s)
            @php $somaSangria += $s->valor; @endphp
            <div class="item-row">
                <div class="item-left">
                    <div class="item-date">{{ __data_pt($s->created_at) }}</div>
                    <div class="item-desc">{{ $s->observacao }}</div>
                </div>
                <div class="item-right">R$ {{ __moeda($s->valor) }}</div>
            </div>
        @endforeach
    @else
        <div class="empty-text">Nenhuma sangria registrada.</div>
    @endif

    <hr class="separator-double">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- RESUMO FINANCEIRO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section-title">Resumo Financeiro</div>

    <div class="summary-row">
        <span class="summary-label">Vendas:</span>
        <span class="summary-value">R$ {{ __moeda($soma) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Sangrias:</span>
        <span class="summary-value">R$ {{ __moeda($somaSangria) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Suprimentos:</span>
        <span class="summary-value">R$ {{ __moeda($somaSuprimento) }}</span>
    </div>
    <div class="summary-row" style="border-top: 1px solid #000; padding-top: 4px; margin-top: 4px;">
        <span class="summary-label" style="font-weight:bold;">Valor em Caixa:</span>
        <span class="summary-value" style="font-size: 11px;">R$ {{ __moeda($somaSuprimento + $soma - $somaSangria) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Contagem Gaveta:</span>
        <span class="summary-value">R$ {{ __moeda($item->valor_dinheiro) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Serviços:</span>
        <span class="summary-value">R$ {{ __moeda($somaServicos) }}</span>
    </div>

    <hr class="separator-double">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PRODUTOS VENDIDOS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section-title">Produtos Vendidos</div>

    @foreach($produtos as $p)
    <div class="item-row">
        <div class="item-left">
            <div style="font-size:9px;">{{ $p['nome'] }}</div>
            <div style="font-size:8px; color:#555;">Qtd: {{ $p['quantidade'] }}</div>
        </div>
        <div class="item-right" style="text-align:right;">
            <div style="font-size:9px;">Venda: R$ {{ __moeda($p['valor_venda']) }}</div>
            <div style="font-size:8px; color:#555;">Compra: R$ {{ __moeda($p['valor_compra']) }}</div>
        </div>
    </div>
    @endforeach

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- ASSINATURA --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="signature">
        <div class="line"></div>
        <div class="name">{{ $usuario->name }}</div>
        <div class="info">Operador(a) — {{ date('d/m/Y H:i') }}</div>
    </div>

</body>
</html>
