<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Conferência Fiscal de Produtos por Padrão de Tributação</title>
    <style>
        @page {
            margin: 18px 20px 25px 20px;
            size: a4 landscape;
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e1b4b;
            text-transform: uppercase;
        }
        .header-sub {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }
        .kpi-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .kpi-cell {
            padding: 6px 10px;
            text-align: center;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .kpi-total      { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .kpi-conforme   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .kpi-divergente { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .kpi-sem-padrao { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .padrao-header {
            background: #1e1b4b;
            color: #ffffff;
            padding: 6px 8px;
            font-size: 9.5px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 0;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .padrao-rules {
            background: #f1f5f9;
            color: #334155;
            padding: 4px 8px;
            font-size: 8px;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 4px;
        }

        .tb-produtos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .tb-produtos th {
            background: #f8fafc;
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 5px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        .tb-produtos td {
            padding: 4px 5px;
            border: 1px solid #e2e8f0;
            font-size: 8px;
            vertical-align: middle;
        }
        .row-divergente { background-color: #fffbeb; }
        .row-sem-padrao { background-color: #fef2f2; }
        .row-conforme   { background-color: #ffffff; }

        .cell-diff {
            color: #b45309;
            font-weight: bold;
            background: #fef3c7;
        }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            font-size: 7.5px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-conforme   { background: #dcfce7; color: #166534; }
        .badge-divergente { background: #fef3c7; color: #92400e; }
        .badge-sem-padrao { background: #fee2e2; color: #991b1b; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: right;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    {{-- CABEÇALHO DO RELATÓRIO --}}
    <table class="header">
        <tr>
            <td style="vertical-align: middle;">
                <div class="header-title">Conferência Fiscal de Produtos por Padrão de Tributação</div>
                <div class="header-sub">
                    Empresa: <strong>{{ $empresa ? ($empresa->nome_fantasia ?: $empresa->nome) : 'Empresa' }}</strong> 
                    @if($empresa && $empresa->cpf_cnpj) | CNPJ: {{ $empresa->cpf_cnpj }} @endif
                    | Data de Emissão: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <span style="font-size: 9px; color: #64748b;">Sistema ERP</span>
            </td>
        </tr>
    </table>

    {{-- KPIS DE RESUMO --}}
    <table class="kpi-table">
        <tr>
            <td style="width: 25%; padding: 0 4px 0 0;">
                <div class="kpi-cell kpi-total">TOTAL PRODUTOS: {{ $stats['total'] }}</div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-cell kpi-conforme">CONFORMES: {{ $stats['conformes'] }}</div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-cell kpi-divergente">COM DIVERGÊNCIA: {{ $stats['divergentes'] }}</div>
            </td>
            <td style="width: 25%; padding: 0 0 0 4px;">
                <div class="kpi-cell kpi-sem-padrao">SEM PADRÃO VINCULADO: {{ $stats['sem_padrao'] }}</div>
            </td>
        </tr>
    </table>

    {{-- GRUPOS DE PADRÕES --}}
    @foreach($gruposPadroes as $grupo)
        <div class="padrao-header">
            @if($grupo['is_sem_padrao'])
                ⚠️ {{ $grupo['descricao'] }} ({{ $grupo['total_produtos'] }} produtos)
            @else
                ⚖️ PADRÃO: {{ $grupo['descricao'] }} 
                ({{ $grupo['total_produtos'] }} produtos | {{ $grupo['total_conformes'] }} conformes | {{ $grupo['total_divergentes'] }} divergentes)
            @endif
        </div>

        @if(!$grupo['is_sem_padrao'])
            <div class="padrao-rules">
                <strong>Regras Oficiais do Padrão:</strong>
                NCM: <strong>{{ $grupo['padrao']->ncm ?: '--' }}</strong> |
                CEST: <strong>{{ $grupo['padrao']->cest ?: '--' }}</strong> |
                CFOP Est./Inter.: <strong>{{ $grupo['padrao']->cfop_estadual }} / {{ $grupo['padrao']->cfop_outro_estado }}</strong> |
                CST/CSOSN: <strong>{{ $grupo['padrao']->cst_csosn }}</strong> |
                PIS/COF: <strong>{{ $grupo['padrao']->cst_pis }} / {{ $grupo['padrao']->cst_cofins }}</strong> |
                IPI: <strong>{{ $grupo['padrao']->cst_ipi ?: '--' }}</strong> (cEnq {{ $grupo['padrao']->cEnq ?: '999' }}) |
                Alíquotas: ICMS {{ number_format((float)$grupo['padrao']->perc_icms, 1) }}%, PIS {{ number_format((float)$grupo['padrao']->perc_pis, 1) }}%, COF {{ number_format((float)$grupo['padrao']->perc_cofins, 1) }}%, IPI {{ number_format((float)$grupo['padrao']->perc_ipi, 1) }}%
            </div>
        @endif

        <table class="tb-produtos">
            <thead>
                <tr>
                    <th style="width: 4%;">ID</th>
                    <th style="width: 22%;">Produto</th>
                    <th style="width: 8%;">NCM</th>
                    <th style="width: 6%;">CEST</th>
                    <th style="width: 9%;">CFOP (Est/Int)</th>
                    <th style="width: 6%;">CST/CSOSN</th>
                    <th style="width: 8%;">PIS / COF</th>
                    <th style="width: 6%;">CST IPI</th>
                    <th style="width: 13%;">Alíquotas (ICMS/PIS/COF/IPI)</th>
                    <th style="width: 8%;">Situação</th>
                    <th style="width: 10%;">Divergências</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grupo['produtos'] as $prod)
                    @php
                        $isDiv = $prod->analise_status === 'divergente';
                        $isSem = $prod->analise_status === 'sem_padrao';
                        $rowClass = $isDiv ? 'row-divergente' : ($isSem ? 'row-sem-padrao' : 'row-conforme');
                        $campos = $prod->analise_campos ?? [];
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $prod->produto_id }}</td>
                        <td>
                            <strong>{{ $prod->produto_nome }}</strong>
                            @if($prod->categoria_nome) <span style="color:#64748b;">({{ $prod->categoria_nome }})</span> @endif
                        </td>
                        <td class="{{ isset($campos['ncm']) ? 'cell-diff' : '' }}">
                            {{ $prod->prod_ncm ?: '--' }}
                        </td>
                        <td class="{{ isset($campos['cest']) ? 'cell-diff' : '' }}">
                            {{ $prod->prod_cest ?: '--' }}
                        </td>
                        <td class="{{ (isset($campos['cfop_estadual']) || isset($campos['cfop_outro_estado'])) ? 'cell-diff' : '' }}">
                            {{ $prod->prod_cfop_estadual }} / {{ $prod->prod_cfop_outro_estado }}
                        </td>
                        <td class="{{ isset($campos['cst_csosn']) ? 'cell-diff' : '' }}">
                            {{ $prod->prod_cst_csosn }}
                        </td>
                        <td class="{{ (isset($campos['cst_pis']) || isset($campos['cst_cofins'])) ? 'cell-diff' : '' }}">
                            {{ $prod->prod_cst_pis }} / {{ $prod->prod_cst_cofins }}
                        </td>
                        <td class="{{ isset($campos['cst_ipi']) ? 'cell-diff' : '' }}">
                            {{ $prod->prod_cst_ipi ?: '--' }}
                        </td>
                        <td>
                            {{ number_format((float)$prod->prod_perc_icms, 1) }}% / 
                            {{ number_format((float)$prod->prod_perc_pis, 1) }}% / 
                            {{ number_format((float)$prod->prod_perc_cofins, 1) }}% / 
                            {{ number_format((float)$prod->prod_perc_ipi, 1) }}%
                        </td>
                        <td>
                            @if($isDiv)
                                <span class="badge badge-divergente">Divergente</span>
                            @elseif($isSem)
                                <span class="badge badge-sem-padrao">Sem Padrão</span>
                            @else
                                <span class="badge badge-conforme">Conforme</span>
                            @endif
                        </td>
                        <td style="font-size: 7.5px;">
                            @if(!empty($prod->analise_divergencias))
                                {{ implode(', ', $prod->analise_divergencias) }}
                            @else
                                --
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; color: #94a3b8; font-style: italic;">
                            Nenhum produto encontrado neste padrão com os filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        Conferência Fiscal de Produtos • Impresso em {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>
