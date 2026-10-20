<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Compras</title>
    <style>
        @page {
            margin: 6mm 14mm 8mm 6mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #1f2937;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* ─── Cabeçalho Empresa e Emissão ─── */
        .header-table {
            width: 100%;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            margin-bottom: 8px;
            table-layout: fixed;
        }

        .header-logo {
            max-width: 90px;
            max-height: 48px;
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
            font-size: 8.5px;
            color: #4b5563;
            margin: 0;
            line-height: 1.25;
        }

        .report-meta {
            text-align: right;
            font-size: 8.5px;
            color: #4b5563;
            vertical-align: top;
            padding-right: 12px;
        }

        .report-meta strong {
            color: #111827;
        }

        /* ─── Título do Relatório (Básico e Limpo) ─── */
        .title-bar {
            text-align: center;
            padding: 4px 0;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .title-bar h1 {
            font-size: 12px;
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
            padding: 5px 8px;
            margin-bottom: 8px;
            font-size: 8.5px;
            color: #374151;
        }

        .filter-label {
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
        }

        .filter-val {
            color: #1f2937;
            margin-right: 12px;
        }

        /* ─── Tabela de Resumo / Cards Básicos (Sem cores fortes) ─── */
        .summary-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .summary-cell {
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

        .summary-title {
            font-size: 8px;
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }

        .summary-val {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }

        .summary-sub {
            font-size: 8px;
            color: #6b7280;
            margin-top: 1px;
        }

        /* ─── Tabela de Dados (Grid Limpo) ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed;
            font-size: 8.5px;
        }

        .data-table thead th {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        .data-table tbody td {
            padding: 4px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #fbfbfb;
        }

        /* ─── Status e Ambientes em Texto Simples ─── */
        .status-txt {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }
        .status-aprovado { color: #15803d; }
        .status-cancelado { color: #b91c1c; }
        .status-rejeitado { color: #b45309; }
        .status-novo { color: #2563eb; }

        /* ─── Barra de Totais ─── */
        .total-table {
            width: 100%;
            border: 1px solid #cbd5e1;
            background-color: #f3f4f6;
            margin-top: 4px;
        }

        .total-table td {
            padding: 6px 8px;
            font-size: 9.5px;
            font-weight: bold;
            color: #111827;
        }

        /* ─── Rodapé de Página ─── */
        .footer {
            position: fixed;
            bottom: -0.6cm;
            left: 0;
            right: 0;
            height: 0.5cm;
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
        .text-muted { color: #6b7280; font-size: 8px; }
    </style>
</head>
<body>

    <!-- ═══ Cabeçalho da Empresa (Logo e Dados Juntos e Alinhados) ═══ -->
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
                <div><strong>Módulo:</strong> Compras / Entradas</div>
            </td>
        </tr>
    </table>

    <!-- ═══ Título Básico do Relatório ═══ -->
    <div class="title-bar">
        <h1>Relatório de Entradas de Mercadorias (Compras)</h1>
    </div>

    <!-- ═══ Linha de Filtros Aplicados ═══ -->
    <div class="filter-row">
        <span class="filter-label">Período:</span>
        <span class="filter-val">
            @if($start_date && $end_date)
                {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
            @elseif($start_date)
                A partir de {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
            @elseif($end_date)
                Até {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
            @else
                Geral (Todas as datas)
            @endif
        </span>

        <span class="filter-label">Fornecedor:</span>
        <span class="filter-val">{{ $fornecedor_selecionado ? $fornecedor_selecionado->razao_social : 'Todos' }}</span>

        <span class="filter-label">Status:</span>
        <span class="filter-val">
            @if($estado == 'aprovado') Aprovadas
            @elseif($estado == 'cancelado') Canceladas
            @elseif($estado == 'rejeitado') Rejeitadas
            @elseif($estado == 'novo') Novas
            @else Todos
            @endif
        </span>

        @if($local_selecionado)
        <span class="filter-label">Filial:</span>
        <span class="filter-val">{{ $local_selecionado->descricao }}</span>
        @endif

        <span class="filter-label">Registros:</span>
        <span class="filter-val" style="margin-right: 0;">{{ $data->count() }}</span>
    </div>

    <!-- ═══ Resumo Básico (Sem cores fortes) ═══ -->
    <table class="summary-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="summary-cell" style="width: 25%;">
                <div class="summary-title">Total de Compras</div>
                <div class="summary-val">{{ $stats['total'] }}</div>
                <div class="summary-sub">entradas registradas</div>
            </td>
            <td class="summary-cell" style="width: 25%;">
                <div class="summary-title">Compras Aprovadas</div>
                <div class="summary-val">{{ $stats['aprovadas'] }}</div>
                <div class="summary-sub">R$ {{ __moeda($stats['valor_aprovadas']) }}</div>
            </td>
            <td class="summary-cell" style="width: 25%;">
                <div class="summary-title">Compras Canceladas</div>
                <div class="summary-val">{{ $stats['canceladas'] }}</div>
                <div class="summary-sub">documentos cancelados</div>
            </td>
            <td class="summary-cell" style="width: 25%;">
                <div class="summary-title">Valor Total Listado</div>
                <div class="summary-val">R$ {{ __moeda($stats['valor_total']) }}</div>
                <div class="summary-sub">soma das compras listadas</div>
            </td>
        </tr>
    </table>

    <!-- ═══ Tabela de Dados (Grid Limpo e Compacto) ═══ -->
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">#</th>
                <th style="width: 12%;">Data Emissão</th>
                <th style="width: 8%;" class="text-center">Nº Doc</th>
                <th style="width: {{ ($countLocais ?? 0) > 1 ? '24%' : '30%' }};">Fornecedor</th>
                @if(($countLocais ?? 0) > 1)
                <th style="width: 9%;">Filial</th>
                @endif
                <th style="width: 10%;" class="text-center">Ambiente</th>
                <th style="width: 9%;" class="text-center">Status</th>
                <th style="width: 14%; padding-right: 12px;" class="text-right">Valor Total (R$)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center bold" style="color: #6b7280;">
                    {{ $index + 1 }}
                </td>
                <td>
                    <span class="bold">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                    <span class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
                </td>
                <td class="text-center bold">
                    {{ $item->numero ?? $item->id }}
                    @if($item->numero_serie)
                        <span class="text-muted">/{{ $item->numero_serie }}</span>
                    @endif
                </td>
                <td>
                    <div class="bold" style="color: #111827;">
                        {{ $item->fornecedor ? $item->fornecedor->razao_social : ($item->emissor_nome ?: '--') }}
                    </div>
                    <div class="text-muted">
                        @if($item->fornecedor && $item->fornecedor->cpf_cnpj)
                            CNPJ: {{ $item->fornecedor->cpf_cnpj }}
                        @elseif($item->emissor_cpf_cnpj)
                            CNPJ: {{ $item->emissor_cpf_cnpj }}
                        @endif
                        @if($item->chave)
                            | Chave: {{ substr($item->chave, 0, 4) }}...{{ substr($item->chave, -6) }}
                        @endif
                    </div>
                </td>
                @if(($countLocais ?? 0) > 1)
                <td>
                    {{ $item->localizacao ? $item->localizacao->descricao : '--' }}
                </td>
                @endif
                <td class="text-center">
                    {{ $item->ambiente == 1 ? 'Produção' : 'Homologação' }}
                </td>
                <td class="text-center">
                    @if($item->estado == 'aprovado')
                        <span class="status-txt status-aprovado">Aprovado</span>
                    @elseif($item->estado == 'cancelado')
                        <span class="status-txt status-cancelado">Cancelado</span>
                    @elseif($item->estado == 'rejeitado')
                        <span class="status-txt status-rejeitado">Rejeitado</span>
                    @else
                        <span class="status-txt status-novo">Novo</span>
                    @endif
                </td>
                <td class="text-right bold" style="font-size: 9.5px; padding-right: 12px;">
                    R$ {{ __moeda($item->total) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ ($countLocais ?? 0) > 1 ? 8 : 7 }}" class="text-center" style="padding: 16px; color: #6b7280;">
                    Nenhuma entrada de mercadoria encontrada para os filtros selecionados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ═══ Totalizador Final ═══ -->
    <table class="total-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 50%;">
                TOTAL DE ENTRADAS: {{ $data->count() }} documento(s)
            </td>
            <td style="width: 50%; padding-right: 12px;" class="text-right">
                VALOR TOTAL CONSOLIDADO: R$ {{ __moeda($stats['valor_total']) }}
            </td>
        </tr>
    </table>

    <!-- ═══ Rodapé Fixo ═══ -->
    <div class="footer">
        <div class="footer-left">
            {{ $empresa->nome }} • Sistema ERP
        </div>
        <div class="footer-right">
            Relatório Gerencial de Compras
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
