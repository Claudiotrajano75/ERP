<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque</title>
    <style>
        @page {
            margin: 8mm 10mm 12mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5px;
            color: #1f2937;
            line-height: 1.25;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* ─── Cabeçalho Empresa e Emissão ─── */
        .header-table {
            width: 100%;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 6px;
            table-layout: fixed;
        }

        .header-logo {
            max-width: 90px;
            max-height: 46px;
            vertical-align: middle;
        }

        .empresa-title {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }

        .empresa-info {
            font-size: 8px;
            color: #4b5563;
            margin: 0;
            line-height: 1.25;
        }

        .report-meta {
            text-align: right;
            font-size: 8px;
            color: #4b5563;
            vertical-align: top;
            padding-right: 8px;
        }

        .report-meta strong {
            color: #111827;
        }

        /* ─── Título do Relatório ─── */
        .title-bar {
            text-align: center;
            padding: 3px 0;
            margin-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        .title-bar h1 {
            font-size: 11.5px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ─── Linha de Filtros ─── */
        .filter-row {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 4px 8px;
            margin-bottom: 6px;
            font-size: 8px;
            color: #374151;
        }

        .filter-label {
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
        }

        .filter-val {
            color: #1f2937;
            margin-right: 14px;
        }

        /* ─── Cards de Resumo ─── */
        .summary-table {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .summary-cell {
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
        }

        .summary-title {
            font-size: 7.5px;
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }

        .summary-val {
            font-size: 12px;
            font-weight: bold;
            color: #111827;
        }

        .summary-sub {
            font-size: 7.5px;
            color: #6b7280;
            margin-top: 1px;
        }

        /* ─── Tabela de Dados ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            table-layout: fixed;
            font-size: 8px;
            page-break-inside: auto;
        }

        .data-table thead {
            display: table-row-group;
        }

        .data-table thead th {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            padding: 5px 5px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        .data-table tr {
            page-break-inside: avoid;
        }

        .data-table tbody td {
            padding: 3.5px 5px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #fbfbfb;
        }

        /* ─── Destaques de Estoque ─── */
        .qtd-alerta {
            color: #dc2626;
            font-weight: bold;
        }

        .qtd-aviso {
            color: #d97706;
            font-weight: bold;
        }

        .qtd-normal {
            color: #111827;
            font-weight: bold;
        }

        /* ─── Barra de Totais ─── */
        .total-table {
            width: 100%;
            border: 1px solid #cbd5e1;
            background-color: #f3f4f6;
            margin-top: 4px;
        }

        .total-table td {
            padding: 5px 8px;
            font-size: 8.5px;
            font-weight: bold;
            color: #111827;
        }

        /* ─── Rodapé Fixo ─── */
        .footer {
            position: fixed;
            bottom: -8mm;
            left: 0;
            right: 0;
            height: 6mm;
            border-top: 1px solid #d1d5db;
            padding-top: 2px;
            font-size: 7.5px;
            color: #6b7280;
        }

        .footer-left { float: left; }
        .footer-right { float: right; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .text-muted { color: #6b7280; font-size: 7.5px; }
    </style>
</head>
<body>

    <!-- ═══ Cabeçalho da Empresa ═══ -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 72%; vertical-align: top;">
                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        @if(!empty($logoBase64))
                        <td style="width: 95px; vertical-align: middle; padding-right: 8px;">
                            <img src="{{ $logoBase64 }}" class="header-logo" alt="Logo">
                        </td>
                        @endif
                        <td style="vertical-align: middle;">
                            <div class="empresa-title">{{ $empresa->nome_fantasia ?: $empresa->nome }}</div>
                            <div class="empresa-info">
                                <strong>Razão Social:</strong> {{ $empresa->nome }}
                                <br>
                                <strong>CNPJ:</strong> {{ $empresa->cpf_cnpj }}
                                @if($empresa->ie) | <strong>IE:</strong> {{ $empresa->ie }} @endif
                                @if($empresa->rua)
                                    | {{ $empresa->rua }}, {{ $empresa->numero }} - {{ $empresa->bairro }}
                                    @if($empresa->cidade) ({{ $empresa->cidade->info ?? '' }}) @endif
                                @endif
                                @if($empresa->celular || $empresa->email)
                                    <br><strong>Contato:</strong> {{ $empresa->celular }} {{ $empresa->email ? '| ' . $empresa->email : '' }}
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 28%; vertical-align: top;" class="report-meta">
                <div><strong>Emissão:</strong> {{ date('d/m/Y H:i') }}</div>
                <div><strong>Usuário:</strong> {{ auth()->user()->name ?? 'Sistema' }}</div>
                <div><strong>Módulo:</strong> Controle de Estoque</div>
            </td>
        </tr>
    </table>

    <!-- ═══ Título do Relatório ═══ -->
    <div class="title-bar">
        <h1>Relatório de Posição e Saldo de Estoque</h1>
    </div>

    <!-- ═══ Linha de Filtros Aplicados ═══ -->
    <div class="filter-row">
        <span class="filter-label">Filtro de Busca:</span>
        <span class="filter-val">{{ $pesquisaProduto ? $pesquisaProduto : 'Geral (Todos os produtos em estoque)' }}</span>

        <span class="filter-label">Ordenação:</span>
        <span class="filter-val">Nome do Produto (A-Z)</span>

        <span class="filter-label">Registros Listados:</span>
        <span class="filter-val" style="margin-right: 0;">{{ $data->count() }}</span>
    </div>

    <!-- ═══ Resumo Executivo (Cards Limpos) ═══ -->
    <table class="summary-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="summary-cell" style="width: 20%;">
                <div class="summary-title">Produtos Cadastrados</div>
                <div class="summary-val">{{ $stats['total_produtos'] }}</div>
                <div class="summary-sub">itens com estoque ativo</div>
            </td>
            <td class="summary-cell" style="width: 20%;">
                <div class="summary-title">Quantidade Total Física</div>
                <div class="summary-val">{{ number_format($stats['total_itens'], 0, ',', '.') }}</div>
                <div class="summary-sub">soma das unidades/peças</div>
            </td>
            <td class="summary-cell" style="width: 20%;">
                <div class="summary-title">Valor em Venda</div>
                <div class="summary-val" style="color: #15803d;">R$ {{ __moeda($stats['valor_estoque']) }}</div>
                <div class="summary-sub">preço de venda projetado</div>
            </td>
            <td class="summary-cell" style="width: 20%;">
                <div class="summary-title">Valor em Custo</div>
                <div class="summary-val" style="color: #1e40af;">R$ {{ __moeda($stats['valor_custo']) }}</div>
                <div class="summary-sub">custo contábil de compra</div>
            </td>
            <td class="summary-cell" style="width: 20%;">
                <div class="summary-title">Estoque Baixo / Crítico</div>
                <div class="summary-val" style="color: {{ $stats['estoque_baixo'] > 0 ? '#b91c1c' : '#15803d' }};">
                    {{ $stats['estoque_baixo'] }}
                </div>
                <div class="summary-sub">abaixo do mínimo ou zerado</div>
            </td>
        </tr>
    </table>

    <!-- ═══ Tabela de Dados ═══ -->
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 3%;" class="text-center">#</th>
                <th style="width: 10%;">Cód. / Barras</th>
                <th style="width: {{ ($countLocais ?? 0) > 1 ? '31%' : '37%' }};">Descrição do Produto</th>
                <th style="width: 4%;" class="text-center">UN</th>
                @if(($countLocais ?? 0) > 1)
                <th style="width: 10%;">Localização</th>
                @endif
                <th style="width: 7%;" class="text-center">Mínimo</th>
                <th style="width: 8%;" class="text-center">Qtd. Atual</th>
                <th style="width: 9%;" class="text-right">Custo (R$)</th>
                <th style="width: 9%;" class="text-right">Venda (R$)</th>
                <th style="width: 9%; padding-right: 6px;" class="text-right">Subtotal Venda (R$)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $somaSubtotal = 0;
            @endphp
            @forelse($data as $index => $item)
            @php
                $produto = $item->produto;
                $qtd = (float)$item->quantidade;
                $unidade = $produto->unidade ?: 'UN';
                $qtdFormatada = ($unidade == 'UN' || $unidade == 'UNID') 
                    ? number_format($qtd, 0, ',', '.') 
                    : number_format($qtd, 3, ',', '.');
                $minimoFormatado = ($unidade == 'UN' || $unidade == 'UNID')
                    ? number_format((float)$produto->estoque_minimo, 0, ',', '.')
                    : number_format((float)$produto->estoque_minimo, 3, ',', '.');
                $subtotalLinha = $qtd * (float)$produto->valor_unitario;
                $somaSubtotal += $subtotalLinha;

                $alertaClasse = 'qtd-normal';
                if ($qtd <= 0) {
                    $alertaClasse = 'qtd-alerta';
                } elseif ($produto->gerenciar_estoque && $produto->estoque_minimo > 0 && $qtd <= $produto->estoque_minimo) {
                    $alertaClasse = 'qtd-aviso';
                }
            @endphp
            <tr>
                <td class="text-center bold" style="color: #6b7280;">
                    {{ $index + 1 }}
                </td>
                <td>
                    <span class="bold">{{ $produto->codigo_barras ?: ($produto->referencia ?: $produto->id) }}</span>
                    @if($produto->referencia && $produto->codigo_barras)
                        <br><span class="text-muted">Ref: {{ $produto->referencia }}</span>
                    @endif
                </td>
                <td>
                    <strong style="color: #111827;">{{ $item->descricao() }}</strong>
                    @if($produto->categoria || $produto->marca)
                        <br><span class="text-muted">
                            @if($produto->categoria) Categoria: {{ $produto->categoria->nome }} @endif
                            @if($produto->marca) | Marca: {{ $produto->marca->nome }} @endif
                        </span>
                    @endif
                </td>
                <td class="text-center bold">
                    {{ $unidade }}
                </td>
                @if(($countLocais ?? 0) > 1)
                <td>
                    {{ $item->local ? $item->local->descricao : '--' }}
                </td>
                @endif
                <td class="text-center">
                    {{ $produto->gerenciar_estoque ? $minimoFormatado : '--' }}
                </td>
                <td class="text-center {{ $alertaClasse }}">
                    {{ $qtdFormatada }}
                    @if($qtd <= 0)
                        <br><span style="font-size: 7px; color: #dc2626; font-weight: bold;">ZERADO</span>
                    @elseif($produto->gerenciar_estoque && $produto->estoque_minimo > 0 && $qtd <= $produto->estoque_minimo)
                        <br><span style="font-size: 7px; color: #d97706; font-weight: bold;">BAIXO</span>
                    @endif
                </td>
                <td class="text-right">
                    R$ {{ __moeda($produto->valor_compra) }}
                </td>
                <td class="text-right bold">
                    R$ {{ __moeda($produto->valor_unitario) }}
                </td>
                <td class="text-right bold" style="padding-right: 6px; color: #15803d;">
                    R$ {{ __moeda($subtotalLinha) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ ($countLocais ?? 0) > 1 ? 10 : 9 }}" class="text-center" style="padding: 16px; color: #6b7280;">
                    Nenhum registro de estoque encontrado para o filtro informado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ═══ Totalizador Consolidado ═══ -->
    <table class="total-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 50%;">
                TOTAL DE ITENS LISTADOS: {{ $data->count() }} registro(s) | QUANTIDADE TOTAL: {{ number_format($stats['total_itens'], 0, ',', '.') }}
            </td>
            <td style="width: 50%; padding-right: 8px;" class="text-right">
                VALOR TOTAL CONSOLIDADO (VENDA): R$ {{ __moeda($somaSubtotal) }}
            </td>
        </tr>
    </table>

    <!-- ═══ Rodapé Fixo ═══ -->
    <div class="footer">
        <div class="footer-left">
            {{ $empresa->nome }} • Sistema ERP
        </div>
        <div class="footer-right">
            Relatório Gerencial de Estoque
        </div>
    </div>

    <!-- Script de Paginação Nativo do DomPDF -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 7.5;
            $font = $fontMetrics->getFont("Helvetica", "normal");
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.4, 0.4, 0.4));
        }
    </script>

</body>
</html>
