<table>
    <thead>
        <tr>
            <th colspan="23" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #4f46e5; color: #ffffff;">
                RELATÓRIO DE CONFERÊNCIA FISCAL DE PRODUTOS POR PADRÃO DE TRIBUTAÇÃO
            </th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center; color: #555555;">
                Empresa: {{ $empresa ? ($empresa->nome_fantasia ?: $empresa->nome) : 'Empresa' }} | Gerado em: {{ now()->format('d/m/Y H:i:s') }}
            </th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center; font-size: 11px; background-color: #f3f4f6;">
                Total de Produtos: {{ $stats['total'] }} | Conformes: {{ $stats['conformes'] }} | Divergentes: {{ $stats['divergentes'] }} | Sem Padrão: {{ $stats['sem_padrao'] }}
            </th>
        </tr>
        <tr><th colspan="23"></th></tr>
    </thead>
    <tbody>
        @foreach($gruposPadroes as $grupo)
            {{-- Linha de Separação do Padrão --}}
            <tr>
                <td colspan="23" style="background-color: #1e1b4b; color: #ffffff; font-weight: bold; font-size: 13px;">
                    @if($grupo['is_sem_padrao'])
                        [SEM PADRÃO VINCULADO] {{ $grupo['descricao'] }} ({{ $grupo['total_produtos'] }} produtos)
                    @else
                        [PADRÃO] {{ $grupo['descricao'] }} | NCM: {{ $grupo['padrao']->ncm ?: '--' }} | CEST: {{ $grupo['padrao']->cest ?: '--' }} | CFOP: {{ $grupo['padrao']->cfop_estadual }}/{{ $grupo['padrao']->cfop_outro_estado }} | CST: {{ $grupo['padrao']->cst_csosn }} | PIS/COF: {{ $grupo['padrao']->cst_pis }}/{{ $grupo['padrao']->cst_cofins }} | IPI: {{ $grupo['padrao']->cst_ipi ?: '--' }} (cEnq {{ $grupo['padrao']->cEnq ?: '999' }}) | ICMS: {{ number_format((float)$grupo['padrao']->perc_icms, 2) }}% ({{ $grupo['total_produtos'] }} produtos: {{ $grupo['total_conformes'] }} conformes, {{ $grupo['total_divergentes'] }} divergentes)
                    @endif
                </td>
            </tr>

            {{-- Cabeçalho da Tabela de Produtos --}}
            <tr style="background-color: #e0e7ff; font-weight: bold; color: #1e293b;">
                <th>ID</th>
                <th>Código Barras</th>
                <th>Referência</th>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Unid.</th>
                <th>Status</th>
                <th>NCM Produto</th>
                <th>CEST Produto</th>
                <th>CFOP Est.</th>
                <th>CFOP Inter.</th>
                <th>CST/CSOSN</th>
                <th>CST PIS</th>
                <th>CST COFINS</th>
                <th>CST IPI</th>
                <th>cEnq</th>
                <th>% ICMS</th>
                <th>% PIS</th>
                <th>% COFINS</th>
                <th>% IPI</th>
                <th>Situação Fiscal</th>
                <th>Divergências Identificadas</th>
            </tr>

            @forelse($grupo['produtos'] as $prod)
                @php
                    $isDivergente = $prod->analise_status === 'divergente';
                    $isSemPadrao  = $prod->analise_status === 'sem_padrao';
                    $bgRow = $isDivergente ? '#fffbeb' : ($isSemPadrao ? '#fef2f2' : '#ffffff');
                @endphp
                <tr style="background-color: {{ $bgRow }};">
                    <td>{{ $prod->produto_id }}</td>
                    <td>{{ $prod->codigo_barras ?: '--' }}</td>
                    <td>{{ $prod->referencia ?: '--' }}</td>
                    <td>{{ $prod->produto_nome }}</td>
                    <td>{{ $prod->categoria_nome ?: '--' }}</td>
                    <td>{{ $prod->marca_nome ?: '--' }}</td>
                    <td>{{ $prod->unidade ?: 'UN' }}</td>
                    <td>{{ $prod->produto_status ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ $prod->prod_ncm ?: '--' }}</td>
                    <td>{{ $prod->prod_cest ?: '--' }}</td>
                    <td>{{ $prod->prod_cfop_estadual }}</td>
                    <td>{{ $prod->prod_cfop_outro_estado }}</td>
                    <td>{{ $prod->prod_cst_csosn }}</td>
                    <td>{{ $prod->prod_cst_pis }}</td>
                    <td>{{ $prod->prod_cst_cofins }}</td>
                    <td>{{ $prod->prod_cst_ipi ?: '--' }}</td>
                    <td>{{ $prod->prod_cEnq ?: '--' }}</td>
                    <td>{{ number_format((float)$prod->prod_perc_icms, 2, ',', '.') }}%</td>
                    <td>{{ number_format((float)$prod->prod_perc_pis, 2, ',', '.') }}%</td>
                    <td>{{ number_format((float)$prod->prod_perc_cofins, 2, ',', '.') }}%</td>
                    <td>{{ number_format((float)$prod->prod_perc_ipi, 2, ',', '.') }}%</td>
                    <td style="font-weight: bold; color: {{ $isDivergente ? '#b45309' : ($isSemPadrao ? '#b91c1c' : '#15803d') }};">
                        @if($isDivergente)
                            DIVERGENTE ({{ $prod->total_divergencias }})
                        @elseif($isSemPadrao)
                            SEM PADRÃO
                        @else
                            CONFORME
                        @endif
                    </td>
                    <td>
                        @if(!empty($prod->analise_divergencias))
                            {{ implode(' | ', $prod->analise_divergencias) }}
                        @else
                            --
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="23" style="text-align: center; color: #94a3b8; font-style: italic;">
                        Nenhum produto encontrado neste padrão com os filtros aplicados.
                    </td>
                </tr>
            @endforelse

            <tr><td colspan="23"></td></tr>
        @endforeach
    </tbody>
</table>
