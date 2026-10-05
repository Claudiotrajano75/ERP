<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupom Não Fiscal - {{ str_pad($item->numero ?? $item->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @page {
            size: {{ ($largura ?? '80') == '58' ? '58mm' : '80mm' }} auto;
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ ($largura ?? '80') == '58' ? '9px' : '11px' }};
            line-height: 1.25;
            font-weight: 600;
        }
        .cupom-container {
            width: {{ ($largura ?? '80') == '58' ? '54mm' : '74mm' }};
            margin: 0 auto;
            padding: 8px 4px 20px 4px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: 900; }
        .uppercase { text-transform: uppercase; }

        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
            width: 100%;
        }

        .header-loja {
            text-align: center;
            margin-bottom: 4px;
        }
        .header-loja .fantasia {
            font-size: {{ ($largura ?? '80') == '58' ? '12px' : '14px' }};
            font-weight: 900;
            line-height: 1.2;
        }
        .header-loja .razao {
            font-size: {{ ($largura ?? '80') == '58' ? '9px' : '10px' }};
        }

        table.tbl-itens {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 2px 0;
        }
        table.tbl-itens th {
            font-weight: 900;
            padding: 2px 1px;
            font-size: {{ ($largura ?? '80') == '58' ? '8px' : '10px' }};
        }
        table.tbl-itens td {
            padding: 2px 1px;
            word-wrap: break-word;
            font-size: {{ ($largura ?? '80') == '58' ? '8px' : '10px' }};
            vertical-align: top;
        }

        .tbl-dados {
            width: 100%;
            border-collapse: collapse;
        }
        .tbl-dados td {
            padding: 1px 0;
            font-size: {{ ($largura ?? '80') == '58' ? '9px' : '11px' }};
        }

        .barra-acoes {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.85);
            padding: 8px 12px;
            border-radius: 8px;
            z-index: 99999;
            display: flex;
            gap: 8px;
        }
        .barra-acoes button {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .barra-acoes button:hover {
            background: #1d4ed8;
        }
        .barra-acoes button.btn-fechar {
            background: #4b5563;
        }
        .barra-acoes button.btn-fechar:hover {
            background: #374151;
        }

        @media print {
            .barra-acoes {
                display: none !important;
            }
            .cupom-container {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="barra-acoes no-print">
        <button onclick="window.print()">
            🖨️ Imprimir Cupom
        </button>
        <button class="btn-fechar" onclick="window.close()">
            ✕ Fechar
        </button>
    </div>

    <div class="cupom-container">

        <!-- 1. CABEÇALHO DO EMITENTE -->
        <div class="header-loja">
            <div class="fantasia uppercase">{{ $empresa->nome_fantasia ?: $empresa->nome }}</div>
            @if($empresa->nome_fantasia && $empresa->nome && ($empresa->nome_fantasia !== $empresa->nome))
                <div class="razao uppercase">{{ $empresa->nome }}</div>
            @endif
            <div>
                CNPJ: {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', preg_replace('/\D/', '', $empresa->cpf_cnpj ?? '')) }}
                @if($empresa->ie)
                    &nbsp;IE: {{ $empresa->ie }}
                @endif
            </div>
            <div>
                {{ $empresa->rua }}, {{ $empresa->numero }}
                @if($empresa->bairro) - {{ $empresa->bairro }} @endif
            </div>
            @if($empresa->cidade)
                <div>{{ $empresa->cidade->nome }}-{{ $empresa->cidade->uf }}</div>
            @endif
            @if($empresa->celular || $empresa->telefone)
                <div>Fone: {{ $empresa->celular ?: $empresa->telefone }}</div>
            @endif
        </div>

        <div class="line"></div>

        <!-- 2. TÍTULO -->
        <div class="text-center bold uppercase" style="font-size: {{ ($largura ?? '80') == '58' ? '11px' : '13px' }}; margin: 2px 0;">
            CUPOM NÃO FISCAL
        </div>

        <div class="line"></div>

        <!-- 3. CLIENTE & DADOS DA VENDA -->
        <div class="uppercase">
            <strong>CLIENTE:</strong> {{ $item->cliente ? ($item->cliente->razao_social ?: $item->cliente->nome) : ($item->cliente_nome ?: 'CLIENTE PADRÃO') }}
        </div>
        <table class="tbl-dados">
            <tr>
                <td class="text-left">
                    {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : date('d/m/Y H:i') }}
                </td>
                <td class="text-right bold">
                    Nº {{ str_pad($item->numero ?? $item->id, 6, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- 4. TABELA DE ITENS (PADRONIZADA EM 6 COLUNAS) -->
        <table class="tbl-itens">
            <thead>
                <tr style="border-bottom: 1px dashed #000;">
                    <th class="text-left" style="width: 14%;">Código</th>
                    <th class="text-left" style="width: 36%;">Descrição</th>
                    <th class="text-right" style="width: 11%;">Qtde</th>
                    <th class="text-right" style="width: 9%;">UN</th>
                    <th class="text-right" style="width: 15%;">VlUnit</th>
                    <th class="text-right" style="width: 15%;">VlTotal</th>
                </tr>
            </thead>
            <tbody>
                @php $totalItensCount = 0; @endphp
                @foreach($item->itens as $prodItem)
                    @php
                        $totalItensCount++;
                        $prodNome = $prodItem->produto ? $prodItem->produto->nome : ($prodItem->descricao ?? 'Item ' . $prodItem->produto_id);
                        $unidade = $prodItem->produto ? ($prodItem->produto->unidade ?? 'UN') : 'UN';
                    @endphp
                    <tr>
                        <td class="text-left">{{ $prodItem->produto_id }}</td>
                        <td class="text-left uppercase">{{ $prodNome }}</td>
                        <td class="text-right">{{ number_format($prodItem->quantidade, ($prodItem->quantidade == intval($prodItem->quantidade) ? 0 : 2), ',', '.') }}</td>
                        <td class="text-right uppercase">{{ $unidade }}</td>
                        <td class="text-right">{{ number_format($prodItem->valor_unitario, 2, ',', '.') }}</td>
                        <td class="text-right bold">{{ number_format($prodItem->sub_total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="line"></div>

        <!-- 5. TOTAIS -->
        @php
            $valorTotalR = $item->total + ($item->desconto ?? 0) - ($item->acrescimo ?? 0);
            $recebido = ($item->dinheiro_recebido ?? 0) > 0 ? $item->dinheiro_recebido : $item->total;
        @endphp
        <table class="tbl-dados">
            <tr>
                <td>Qtde total de itens</td>
                <td class="text-right">{{ $totalItensCount }}</td>
            </tr>
            <tr>
                <td>Valor Total R$</td>
                <td class="text-right">{{ number_format($valorTotalR, 2, ',', '.') }}</td>
            </tr>
            @if(($item->desconto ?? 0) > 0)
                <tr>
                    <td>Desconto R$</td>
                    <td class="text-right">-{{ number_format($item->desconto, 2, ',', '.') }}</td>
                </tr>
            @endif
            @if(($item->acrescimo ?? 0) > 0)
                <tr>
                    <td>Acréscimo R$</td>
                    <td class="text-right">+{{ number_format($item->acrescimo, 2, ',', '.') }}</td>
                </tr>
            @endif
        </table>

        <div class="line"></div>

        <table class="tbl-dados bold" style="font-size: {{ ($largura ?? '80') == '58' ? '11px' : '13px' }};">
            <tr>
                <td>Total da Nota R$</td>
                <td class="text-right">{{ number_format($item->total, 2, ',', '.') }}</td>
            </tr>
        </table>
        <table class="tbl-dados">
            <tr>
                <td>Valor Recebido R$</td>
                <td class="text-right">{{ number_format($recebido, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Troco R$</td>
                <td class="text-right">{{ number_format($item->troco ?? 0, 2, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- 6. FORMAS DE PAGAMENTO -->
        <table class="tbl-dados bold">
            <tr>
                <td class="uppercase">FORMA PAGAMENTO</td>
                <td class="text-right uppercase">VALOR PAGO R$</td>
            </tr>
        </table>
        <table class="tbl-dados">
            @if(isset($item->fatura) && count($item->fatura) > 0)
                @foreach($item->fatura as $f)
                    @php
                        $tipoPag = \App\Models\Nfce::getTipoPagamento($f->tipo_pagamento) ?? $f->tipo_pagamento;
                    @endphp
                    <tr>
                        <td class="uppercase">{{ $tipoPag }}</td>
                        <td class="text-right">{{ number_format($f->valor, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                @php
                    $tipoPag = \App\Models\Nfce::getTipoPagamento($item->tipo_pagamento) ?? 'Dinheiro';
                @endphp
                <tr>
                    <td class="uppercase">{{ $tipoPag }}</td>
                    <td class="text-right">{{ number_format($recebido, 2, ',', '.') }}</td>
                </tr>
            @endif
        </table>

        <div class="line"></div>

        <!-- 7. VENDEDOR -->
        @php $vendedor = $item->vendedor(); @endphp
        @if($vendedor)
            <div class="uppercase">
                <strong>VENDEDOR(A):</strong> {{ $vendedor }}
            </div>
            <div class="line"></div>
        @endif

        <!-- 8. RODAPÉ / ASSINATURA -->
        <div class="text-center" style="margin-top: 6px; font-size: {{ ($largura ?? '80') == '58' ? '8px' : '9px' }};">
            Recebi a(s) mercadoria(s) acima descrita(s),<br>
            concordando plenamente com os prazos e condições de<br>
            garantia.
        </div>

        <div class="text-center" style="margin-top: 25px;">
            __________________________________________<br>
            <span style="font-size: {{ ($largura ?? '80') == '58' ? '8px' : '9px' }};">ASSINATURA DO CLIENTE</span>
        </div>

        <div class="text-center bold" style="margin-top: 10px; font-size: {{ ($largura ?? '80') == '58' ? '10px' : '11px' }};">
            * OBRIGADO E VOLTE SEMPRE *
        </div>

    </div>

    <script>
        // Dispara a impressão automaticamente ao carregar
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
