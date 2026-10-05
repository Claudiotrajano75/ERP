<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DANFE NFC-e - Nº {{ str_pad($nfce->numero, 9, '0', STR_PAD_LEFT) }}</title>
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

        .chave-acesso {
            font-size: {{ ($largura ?? '80') == '58' ? '7.5px' : '9.5px' }};
            letter-spacing: 0.5px;
            word-break: break-all;
            text-align: center;
            margin: 3px 0;
        }

        .qrcode-box {
            text-align: center;
            margin: 6px 0;
        }
        .qrcode-box img, .qrcode-box canvas {
            display: inline-block;
            width: {{ ($largura ?? '80') == '58' ? '120px' : '150px' }};
            height: {{ ($largura ?? '80') == '58' ? '120px' : '150px' }};
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
            🖨️ Imprimir NFC-e
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

        <!-- 2. TÍTULO DO DOCUMENTO -->
        <div class="text-center bold uppercase" style="font-size: {{ ($largura ?? '80') == '58' ? '9px' : '10.5px' }}; margin: 2px 0;">
            Documento Auxiliar da Nota Fiscal de Consumidor Eletrônica
        </div>
        <div class="text-center" style="font-size: {{ ($largura ?? '80') == '58' ? '8px' : '9.5px' }};">
            Não permite aproveitamento de crédito de ICMS
        </div>

        <div class="line"></div>

        <!-- 3. TABELA DE ITENS (6 COLUNAS PADRONIZADAS) -->
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
                @foreach($nfce->itens as $prodItem)
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

        <!-- 4. TOTAIS -->
        @php
            $valorTotalR = $nfce->total + ($nfce->desconto ?? 0) - ($nfce->acrescimo ?? 0);
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
            <tr>
                <td>Desconto R$</td>
                <td class="text-right">{{ number_format($nfce->desconto ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Frete R$</td>
                <td class="text-right">0,00</td>
            </tr>
        </table>

        <div class="line"></div>

        <table class="tbl-dados bold" style="font-size: {{ ($largura ?? '80') == '58' ? '11px' : '13px' }};">
            <tr>
                <td>Valor a Pagar R$</td>
                <td class="text-right">{{ number_format($nfce->total, 2, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- 5. FORMAS DE PAGAMENTO -->
        <table class="tbl-dados bold">
            <tr>
                <td class="uppercase">FORMA PAGAMENTO</td>
                <td class="text-right uppercase">VALOR PAGO R$</td>
            </tr>
        </table>
        <table class="tbl-dados">
            @if(isset($nfce->fatura) && count($nfce->fatura) > 0)
                @foreach($nfce->fatura as $f)
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
                    $tipoPag = \App\Models\Nfce::getTipoPagamento($nfce->tipo_pagamento) ?? 'Dinheiro';
                    $valorPag = ($nfce->dinheiro_recebido ?? 0) > 0 ? $nfce->dinheiro_recebido : $nfce->total;
                @endphp
                <tr>
                    <td class="uppercase">{{ $tipoPag }}</td>
                    <td class="text-right">{{ number_format($valorPag, 2, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td>Troco R$</td>
                <td class="text-right">{{ number_format($nfce->troco ?? 0, 2, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- 6. CONSULTA SEFAZ & CHAVE DE ACESSO -->
        <div class="text-center bold">Consulte pela Chave de Acesso em:</div>
        @php
            $uf = $empresa->cidade->uf ?? 'ce';
            $urlConsulta = $urlChave ?: ('www.sefaz.' . strtolower($uf) . '.gov.br/nfce/consulta');
            $chaveLimpa = preg_replace('/[^0-9]/', '', $nfce->chave ?? '');
            $chaveFormatada = trim(chunk_split($chaveLimpa, 4, ' '));
        @endphp
        <div class="text-center" style="font-size: {{ ($largura ?? '80') == '58' ? '8px' : '9.5px' }}; word-break: break-all;">
            {{ $urlConsulta }}
        </div>
        <div class="chave-acesso bold">
            {{ $chaveFormatada }}
        </div>

        <div class="line"></div>

        <!-- 7. CONSUMIDOR -->
        <div class="text-center bold">
            @if($nfce->cliente || $nfce->cliente_cpf_cnpj || $nfce->cliente_nome)
                @php
                    $cpfCnpj = $nfce->cliente
                        ? ($nfce->cliente->cpf_cnpj ? 'CPF/CNPJ: ' . $nfce->cliente->cpf_cnpj : '')
                        : ($nfce->cliente_cpf_cnpj ? 'CPF/CNPJ: ' . $nfce->cliente_cpf_cnpj : '');
                    $nomeConsumidor = $nfce->cliente
                        ? ($nfce->cliente->razao_social ?? '')
                        : ($nfce->cliente_nome ?? '');
                @endphp
                @if($cpfCnpj) <div>{{ $cpfCnpj }}</div> @endif
                @if($nomeConsumidor) <div class="uppercase">{{ $nomeConsumidor }}</div> @endif
            @else
                <div>CONSUMIDOR NÃO IDENTIFICADO</div>
            @endif
        </div>

        <div class="line"></div>

        <!-- 8. NÚMERO / SÉRIE / PROTOCOLO / DATA -->
        @php
            $dataEmissao = $nfce->data_emissao
                ? \Carbon\Carbon::parse($nfce->data_emissao)->format('d/m/Y H:i:s')
                : date('d/m/Y H:i:s');
        @endphp
        <div class="text-center bold" style="font-size: {{ ($largura ?? '80') == '58' ? '9px' : '10.5px' }};">
            NFCe n. {{ str_pad($nfce->numero, 9, '0', STR_PAD_LEFT) }} Série {{ str_pad($nfce->numero_serie ?: '1', 3, '0', STR_PAD_LEFT) }} {{ $dataEmissao }}
        </div>
        @if($nfce->recibo)
            <div class="text-center" style="font-size: {{ ($largura ?? '80') == '58' ? '8.5px' : '10px' }};">
                Protocolo de Autorização: {{ $nfce->recibo }}<br>
                Data de Autorização: {{ $dataEmissao }}
            </div>
        @endif

        <div class="line"></div>

        <!-- 9. QR CODE SEFAZ -->
        @if(!empty($qrCodeUrl))
            <div class="qrcode-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrCodeUrl) }}" 
                     alt="QR Code NFC-e"
                     onerror="this.onerror=null; this.src='https://quickchart.io/qr?size=180&text={{ urlencode($qrCodeUrl) }}';" />
            </div>
        @endif

        <!-- 10. TRIBUTOS / LEI 12.741 -->
        <div class="text-center" style="font-size: {{ ($largura ?? '80') == '58' ? '8px' : '9px' }}; margin-top: 4px;">
            Tributos totais Incidentes (Lei Federal 12.741/2012): R$ -----
        </div>

    </div>

    <script>
        // Dispara a impressão automaticamente ao carregar
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
