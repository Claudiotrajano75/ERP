<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DANFE NFC-e - Impressão Térmica</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 4px 0;
            width: 100%;
        }
        .divider-solid {
            border-bottom: 1px solid #000;
            margin: 4px 0;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td, th {
            padding: 1px 0;
        }
    </style>
</head>
<body>

    {{-- ═══ 1. CABEÇALHO DO EMITENTE ═══ --}}
    <div class="text-center bold uppercase" style="font-size: 13px;">
        {{ $empresa->nome_fantasia ?: $empresa->nome }}
    </div>
    <div class="text-center uppercase" style="font-size: 10px;">
        {{ $empresa->nome }}
    </div>
    <div class="text-center">
        CNPJ: {{ $empresa->cpf_cnpj }} &nbsp; IE: {{ $empresa->ie }}
    </div>
    <div class="text-center">
        {{ $empresa->rua }}, {{ $empresa->numero }} {{ $empresa->bairro ? '- ' . $empresa->bairro : '' }}
    </div>
    <div class="text-center">
        {{ $empresa->cidade ? $empresa->cidade->nome . ' - ' . $empresa->cidade->uf : '' }} {{ $empresa->cep ? 'CEP: ' . $empresa->cep : '' }}
    </div>
    @if($empresa->celular || $empresa->telefone)
    <div class="text-center">
        Fone: {{ $empresa->celular ?: $empresa->telefone }}
    </div>
    @endif

    <div class="divider"></div>

    {{-- ═══ 2. TÍTULO DO DOCUMENTO FISCAL ═══ --}}
    <div class="text-center bold" style="font-size: 11px;">
        DANFE NFC-e - Documento Auxiliar
    </div>
    <div class="text-center bold" style="font-size: 10px;">
        da Nota Fiscal de Consumidor Eletrônica
    </div>
    <div class="text-center fs-8">
        Não permite aproveitamento de crédito de ICMS
    </div>

    <div class="divider"></div>

    {{-- ═══ 3. TABELA DE ITENS ═══ --}}
    <table>
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th class="text-left" style="width: 10%;">CÓD</th>
                <th class="text-left" style="width: 58%;">DESCRIÇÃO<br>QTD x VL.UNIT</th>
                <th class="text-right" style="width: 32%;">VALOR R$</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $itensCount = 0; 
                $totalQtd = 0;
            @endphp
            @foreach($nfce->itens as $item)
            @php 
                $itensCount++; 
                $totalQtd += $item->quantidade;
            @endphp
            <tr>
                <td class="text-left" valign="top">{{ $item->produto_id }}</td>
                <td class="text-left">
                    {{ $item->produto ? $item->produto->nome : ($item->descricao ?? 'Item ' . $item->produto_id) }}<br>
                    {{ number_format($item->quantidade, 3, ',', '.') }} {{ $item->produto ? $item->produto->unidade : 'UN' }} x {{ number_format($item->valor_unitario, 2, ',', '.') }}
                </td>
                <td class="text-right" valign="bottom">
                    {{ number_format($item->sub_total, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    {{-- ═══ 4. TOTAIS E VALORES ═══ --}}
    <table>
        <tr>
            <td>QTD. TOTAL DE ITENS</td>
            <td class="text-right bold">{{ $itensCount }}</td>
        </tr>
        <tr>
            <td>VALOR TOTAL PRODUTOS R$</td>
            <td class="text-right">{{ number_format($nfce->total + ($nfce->desconto ?? 0) - ($nfce->acrescimo ?? 0), 2, ',', '.') }}</td>
        </tr>
        @if(($nfce->desconto ?? 0) > 0)
        <tr>
            <td>DESCONTO R$</td>
            <td class="text-right text-danger">-{{ number_format($nfce->desconto, 2, ',', '.') }}</td>
        </tr>
        @endif
        @if(($nfce->acrescimo ?? 0) > 0)
        <tr>
            <td>ACRÉSCIMO R$</td>
            <td class="text-right">+{{ number_format($nfce->acrescimo, 2, ',', '.') }}</td>
        </tr>
        @endif
        <tr style="font-size: 11px;">
            <td class="bold">VALOR A PAGAR R$</td>
            <td class="text-right bold" style="font-size: 12px;">{{ number_format($nfce->total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- ═══ 5. FORMAS DE PAGAMENTO ═══ --}}
    <div class="bold mb-1">FORMA DE PAGAMENTO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VALOR PAGO R$</div>
    <table>
        @if(sizeof($nfce->fatura) > 0)
            @foreach($nfce->fatura as $f)
            <tr>
                <td class="uppercase">{{ App\Models\Nfce::getTipoPagamento($f->tipo_pagamento) ?? $f->tipo_pagamento }}</td>
                <td class="text-right">{{ number_format($f->valor, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td class="uppercase">{{ App\Models\Nfce::getTipoPagamento($nfce->tipo_pagamento) ?? 'Dinheiro' }}</td>
                <td class="text-right">{{ number_format($nfce->dinheiro_recebido > 0 ? $nfce->dinheiro_recebido : $nfce->total, 2, ',', '.') }}</td>
            </tr>
        @endif
        @if(($nfce->troco ?? 0) > 0)
        <tr>
            <td>TROCO R$</td>
            <td class="text-right">{{ number_format($nfce->troco, 2, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    {{-- ═══ 6. DADOS DA EMISSÃO E SEFAZ ═══ --}}
    <div class="text-center bold">
        NFC-e Nº {{ str_pad($nfce->numero, 9, '0', STR_PAD_LEFT) }} &nbsp; Série {{ str_pad($nfce->numero_serie ?: '1', 3, '0', STR_PAD_LEFT) }}
    </div>
    <div class="text-center">
        Emissão: {{ $nfce->data_emissao ? \Carbon\Carbon::parse($nfce->data_emissao)->format('d/m/Y H:i:s') : date('d/m/Y H:i:s') }}
    </div>
    @if($nfce->recibo)
    <div class="text-center">
        Protocolo de Autorização: {{ $nfce->recibo }}
    </div>
    @endif
    <div class="text-center fs-8">
        Ambiente: {{ ($nfce->ambiente == 2) ? 'HOMOLOGAÇÃO - SEM VALOR FISCAL' : 'PRODUÇÃO' }}
    </div>

    <div class="divider"></div>

    {{-- ═══ 7. CHAVE DE ACESSO FORMATADA ═══ --}}
    <div class="text-center bold">
        CHAVE DE ACESSO
    </div>
    <div class="text-center bold" style="letter-spacing: 1px; font-size: 10px;">
        @php
            $chaveLimpa = preg_replace('/[^0-9]/', '', $nfce->chave ?? '');
            $chaveFormatada = trim(chunk_split($chaveLimpa, 4, ' '));
        @endphp
        {{ $chaveFormatada ?: ($nfce->chave ?? 'EMISSÃO EM CONTINGÊNCIA') }}
    </div>

    <div class="divider"></div>

    {{-- ═══ 8. CONSUMIDOR ═══ --}}
    <div class="text-center bold">
        CONSUMIDOR
    </div>
    @if($nfce->cliente || $nfce->cliente_cpf_cnpj || $nfce->cliente_nome)
    <div class="text-center">
        {{ $nfce->cliente ? ($nfce->cliente->cpf_cnpj ? 'CPF/CNPJ: ' . $nfce->cliente->cpf_cnpj : '') : ($nfce->cliente_cpf_cnpj ? 'CPF/CNPJ: ' . $nfce->cliente_cpf_cnpj : '') }}
    </div>
    <div class="text-center uppercase">
        {{ $nfce->cliente ? $nfce->cliente->razao_social : ($nfce->cliente_nome ?: '') }}
    </div>
    @else
    <div class="text-center">
        CONSUMIDOR NÃO IDENTIFICADO
    </div>
    @endif

    <div class="divider"></div>

    {{-- ═══ 9. CONSULTA VIA QR CODE / SEFAZ ═══ --}}
    <div class="text-center bold">
        Consulte pela Chave de Acesso em:
    </div>
    <div class="text-center" style="font-size: 8px;">
        {{ $urlChave ?? 'http://www.sefaz.' . strtolower($empresa->cidade->uf ?? 'ce') . '.gov.br/nfce/consulta' }}
    </div>

    @if(isset($qrCodeUrl) && !empty($qrCodeUrl))
    <div class="text-center mt-2">
        <div class="bold">Consulta via leitor de QR Code</div>
        <div style="font-size: 7px; word-break: break-all;">
            {{ $qrCodeUrl }}
        </div>
    </div>
    @endif

    <div class="divider"></div>

    <div class="text-center bold" style="font-size: 10px;">
        OBRIGADO PELA PREFERÊNCIA!
    </div>

</body>
</html>
