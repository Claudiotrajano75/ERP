<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Caixa</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 12mm 15mm 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
            padding: 15mm 12mm;
        }

        /* ─── Cabeçalho ─── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 12px;
            border-bottom: 3px solid #4f46e5;
            margin-bottom: 16px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-logo {
            max-height: 60px;
            max-width: 80px;
            object-fit: contain;
        }

        .header-info h1 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .header-info .company-name {
            font-size: 12px;
            color: #475569;
            font-weight: 500;
            margin-top: 2px;
        }

        .header-right {
            text-align: right;
        }

        .header-right .doc-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .header-right .doc-date {
            font-size: 11px;
            color: #334155;
            font-weight: 600;
        }

        /* ─── Seções ─── */
        .section {
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 6px 0;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-title .icon {
            font-size: 13px;
        }

        /* ─── Grid Info ─── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 6px 20px;
        }

        .info-grid.two-cols {
            grid-template-columns: 1fr 1fr;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }

        .info-value {
            font-size: 11px;
            color: #1e293b;
            font-weight: 600;
        }

        .info-value.highlight {
            color: #059669;
            font-size: 13px;
        }

        /* ─── Tabelas ─── */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background: #f1f5f9;
            color: #475569;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 6px 8px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        table thead th:last-child,
        table tbody td:last-child {
            text-align: right;
        }

        table tbody td {
            padding: 5px 8px;
            font-size: 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        table tbody tr:hover {
            background: #f8fafc;
        }

        /* ─── Totais ─── */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 6px;
            margin-top: 6px;
        }

        .total-row .total-label {
            color: #e2e8f0;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .total-row .total-value {
            color: #34d399;
            font-size: 16px;
            font-weight: 700;
        }

        /* ─── Resumo Financeiro ─── */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
        }

        .summary-card {
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .summary-card .card-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }

        .summary-card .card-value {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }

        .summary-card.green {
            border-left: 3px solid #059669;
        }

        .summary-card.red {
            border-left: 3px solid #dc2626;
        }

        .summary-card.blue {
            border-left: 3px solid #4f46e5;
        }

        .summary-card.orange {
            border-left: 3px solid #ea580c;
        }

        /* ─── Lista compacta (suprimentos/sangrias) ─── */
        .compact-list {
            margin-top: 4px;
        }

        .compact-list .list-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dotted #e2e8f0;
            font-size: 10px;
        }

        .compact-list .list-item .item-date {
            color: #64748b;
        }

        .compact-list .list-item .item-desc {
            color: #334155;
            flex: 1;
            margin: 0 8px;
        }

        .compact-list .list-item .item-value {
            font-weight: 600;
            color: #1e293b;
        }

        .empty-state {
            font-size: 10px;
            color: #94a3b8;
            font-style: italic;
            padding: 4px 0;
        }

        /* ─── Rodapé ─── */
        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-signature {
            text-align: center;
        }

        .footer-signature .signature-line {
            width: 220px;
            border-top: 1px solid #94a3b8;
            margin-top: 40px;
            padding-top: 4px;
        }

        .footer-signature .signature-name {
            font-size: 11px;
            color: #1e293b;
            font-weight: 600;
        }

        .footer-signature .signature-info {
            font-size: 9px;
            color: #94a3b8;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CABEÇALHO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="header">
        <div class="header-left">
            @if(file_exists(public_path('logo.png')))
                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo.png'))) }}" alt="Logo" class="header-logo">
            @endif
            <div class="header-info">
                <h1>Relatório de Caixa</h1>
                <div class="company-name">{{ $config->nome_fantasia }}</div>
            </div>
        </div>
        <div class="header-right">
            <div class="doc-label">Emissão</div>
            <div class="doc-date">{{ date('d/m/Y') }} às {{ date('H:i') }}</div>
            <div class="doc-label" style="margin-top: 6px;">Documento</div>
            <div class="doc-date" style="font-size: 10px;">{{ str_replace(" ", "", $config->cpf_cnpj) }}</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- DADOS DO CAIXA --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section">
        <div class="section-title">
            <span class="icon">📋</span> Dados do Caixa
        </div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Data de Abertura</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Data de Fechamento</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Valor de Fechamento</span>
                <span class="info-value highlight">R$ {{ number_format($item->valor_fechamento, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- TOTAL POR TIPO DE PAGAMENTO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section">
        <div class="section-title">
            <span class="icon">💳</span> Total por Tipo de Pagamento
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 60%;">Tipo de Pagamento</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($somaTiposPagamento as $key => $tp)
                    @if($tp > 0)
                    <tr>
                        <td>{{ App\Models\Nfce::getTipoPagamento($key) }}</td>
                        <td><strong>R$ {{ number_format($tp, 2, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- VENDAS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @php $soma = 0; @endphp
    <div class="section">
        <div class="section-title">
            <span class="icon">🛒</span> Vendas ({{ count($vendas) }})
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Cliente</th>
                    <th style="width: 16%;">Data</th>
                    <th style="width: 15%;">Pagamento</th>
                    <th style="width: 8%;">Estado</th>
                    <th style="width: 10%;">NF/NFCe</th>
                    <th style="width: 8%;">Tipo</th>
                    <th style="width: 12%;">Valor</th>
                    <th style="width: 11%;">Desconto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendas as $v)
                <tr>
                    <td>{{ $v->cliente->razao_social ?? 'NÃO IDENTIFICADO' }}</td>
                    <td>{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($v->tipo_pagamento == '99')
                            Outros
                        @else
                            {{ $v->tipo_pagamento ? $v->getTipoPagamento($v->tipo_pagamento) : 'Pag Múltiplo' }}
                        @endif
                    </td>
                    <td>
                        @if($v->tipo != 'OS')
                            @if($v->estado == 'aprovado')
                                <span style="color: #059669;">{{ ucfirst($v->estado) }}</span>
                            @else
                                <span style="color: #dc2626;">{{ ucfirst($v->estado) }}</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($v->estado == 'aprovado' && $v->numero > 0)
                            {{ $v->numero }}
                        @else
                            --
                        @endif
                    </td>
                    <td>{{ $v->tipo }}</td>
                    <td>
                        @if($v->tipo != 'OS')
                            <strong>R$ {{ __moeda($v->total) }}</strong>
                        @else
                            <strong>R$ {{ __moeda($v->valor) }}</strong>
                        @endif
                    </td>
                    <td>R$ {{ __moeda($v->desconto) }}</td>
                </tr>
                @php
                    $soma += ($v->tipo != 'OS') ? $v->total : $v->valor;
                @endphp
                @endforeach
            </tbody>
        </table>

        <div class="total-row">
            <span class="total-label">Total Geral das Vendas</span>
            <span class="total-value">R$ {{ number_format($soma, 2, ',', '.') }}</span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SUPRIMENTOS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @php $somaSuprimento = 0; @endphp
    <div class="section">
        <div class="section-title">
            <span class="icon">📥</span> Suprimentos
        </div>
        <div class="compact-list">
            @if(sizeof($suprimentos) > 0)
                @foreach($suprimentos as $s)
                    @php $somaSuprimento += $s->valor; @endphp
                    <div class="list-item">
                        <span class="item-date">{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y H:i') }}</span>
                        <span class="item-desc">{{ $s->observacao }}</span>
                        <span class="item-value" style="color: #059669;">R$ {{ number_format($s->valor, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            @else
                <div class="empty-state">Nenhum suprimento registrado.</div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SANGRIAS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @php $somaSangria = 0; @endphp
    <div class="section">
        <div class="section-title">
            <span class="icon">📤</span> Sangrias
        </div>
        <div class="compact-list">
            @if(sizeof($sangrias) > 0)
                @foreach($sangrias as $s)
                    @php $somaSangria += $s->valor; @endphp
                    <div class="list-item">
                        <span class="item-date">{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y H:i') }}</span>
                        <span class="item-desc">{{ $s->observacao }}</span>
                        <span class="item-value" style="color: #dc2626;">R$ {{ number_format($s->valor, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            @else
                <div class="empty-state">Nenhuma sangria registrada.</div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- RESUMO FINANCEIRO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section">
        <div class="section-title">
            <span class="icon">📊</span> Resumo Financeiro
        </div>
        <div class="summary-grid">
            <div class="summary-card green">
                <div class="card-label">Vendas</div>
                <div class="card-value">R$ {{ number_format($soma, 2, ',', '.') }}</div>
            </div>
            <div class="summary-card red">
                <div class="card-label">Sangrias</div>
                <div class="card-value">R$ {{ number_format($somaSangria, 2, ',', '.') }}</div>
            </div>
            <div class="summary-card blue">
                <div class="card-label">Suprimentos</div>
                <div class="card-value">R$ {{ number_format($somaSuprimento, 2, ',', '.') }}</div>
            </div>
            <div class="summary-card orange">
                <div class="card-label">Valor em Caixa</div>
                <div class="card-value">R$ {{ number_format($somaSuprimento + $soma - $somaSangria, 2, ',', '.') }}</div>
            </div>
            <div class="summary-card blue">
                <div class="card-label">Contagem da Gaveta</div>
                <div class="card-value">R$ {{ number_format($item->valor_dinheiro, 2, ',', '.') }}</div>
            </div>
            <div class="summary-card green">
                <div class="card-label">Serviços</div>
                <div class="card-value">R$ {{ number_format($somaServicos, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PRODUTOS VENDIDOS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="section page-break">
        <div class="section-title">
            <span class="icon">📦</span> Produtos Vendidos
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 45%;">Produto</th>
                    <th style="width: 15%;">Quantidade</th>
                    <th style="width: 20%;">Valor de Venda</th>
                    <th style="width: 20%;">Valor de Compra</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $p)
                <tr>
                    <td>{{ $p['nome'] }}</td>
                    <td>{{ $p['quantidade'] }}</td>
                    <td>R$ {{ __moeda($p['valor_venda']) }}</td>
                    <td>R$ {{ __moeda($p['valor_compra']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- ASSINATURA --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="footer">
        <div class="footer-signature">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $usuario->name }}</div>
            <div class="signature-info">Operador(a) — {{ date('d/m/Y H:i') }}</div>
        </div>
    </div>

</body>
</html>
