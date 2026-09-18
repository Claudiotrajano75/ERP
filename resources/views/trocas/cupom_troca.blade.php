<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovante de Troca</title>
    <style>
        @page {
            margin: 15px 10px 0px 15px !important;
            padding: 0px !important;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8px; 
            margin: 0px;
            padding: 0px;
            width: 100%;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .line {
            border-bottom: 1px dashed #000;
            margin: 4px 0;
            width: 100%;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        td, th {
            padding: 1px;
        }

        .items-header {
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .totals {
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho -->
    <div class="text-center bold uppercase" style="font-size: 14px;">
        {{ $config->nome_fantasia }}
    </div>
    <div class="text-center uppercase" style="font-size: 10px;">
        {{ $config->nome }}
    </div>
    <div class="text-center">
        {{ $config->rua }}, {{ $config->numero }} - {{ $config->bairro }}<br>
        {{ $config->cidade->nome }} - {{ $config->cidade->uf }}<br>
        Tel: {{ $config->celular }}
    </div>
    
    <div class="line"></div>
    
    <div>
        CNPJ: {{ $config->cpf_cnpj }} <span style="float: right;">IE: {{ $config->ie }}</span>
    </div>
    
    <div class="line"></div>
    
    <div>
        CLIENTE: {{ $item->nfce && $item->nfce->cliente ? $item->nfce->cliente->razao_social : 'Cliente padrão' }}
    </div>
    
    <div class="line"></div>
    
    <div class="text-center bold" style="font-size: 11px;">
        COMPROVANTE DE TROCA
    </div>

    <div class="line"></div>

    <div>
        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
        <span style="float: right;">Cód. Troca: {{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="line"></div>

    <!-- Tabela de Itens (Venda Original) -->
    @if($item->nfce && count($item->nfce->itens) > 0)
    <div style="font-weight: bold; text-align: center; margin-bottom: 5px;">VENDA ORIGINAL (Cód. {{ $item->nfce->id }})</div>
    <table style="width: 100%;">
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th class="text-left" style="width: 10%;">CÓD</th>
                <th class="text-left" style="width: 65%;">DESC<br>QTD x UN</th>
                <th class="text-right" style="width: 25%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->nfce->itens as $i)
            <tr>
                <td class="text-left" valign="top">{{ $i->produto->id }}</td>
                <td class="text-left">
                    {{ $i->produto->nome }}<br>
                    {{ number_format($i->quantidade, 2, ',', '.') }} x {{ number_format($i->valor_unitario, 2, ',', '.') }}
                </td>
                <td class="text-right" valign="bottom">
                    {{ number_format($i->sub_total, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>
    @endif

    <!-- Tabela de Itens (Troca) -->
    @if(count($item->itens) > 0)
    <div style="font-weight: bold; text-align: center; margin-bottom: 5px;">ITENS ADICIONADOS/ALTERADOS</div>
    <table style="width: 100%;">
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th class="text-left" style="width: 10%;">CÓD</th>
                <th class="text-left" style="width: 65%;">DESC<br>QTD x UN</th>
                <th class="text-right" style="width: 25%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->itens as $i)
            <tr>
                <td class="text-left" valign="top">{{ $i->produto->id }}</td>
                <td class="text-left">
                    {{ $i->produto->nome }}<br>
                    {{ number_format($i->quantidade, 2, ',', '.') }} x {{ number_format($i->produto->valor_unitario, 2, ',', '.') }}
                </td>
                <td class="text-right" valign="bottom">
                    {{ number_format($i->quantidade * $i->produto->valor_unitario, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>
    @endif

    <!-- Totais -->
    <table class="totals">
        <tr>
            <td class="bold">Total Venda Original R$</td>
            <td class="text-right bold">{{ number_format($item->valor_original, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Total Nova Venda R$</td>
            <td class="text-right bold">{{ number_format($item->valor_troca, 2, ',', '.') }}</td>
        </tr>
        @if($item->valor_troca > $item->valor_original)
        <tr>
            <td class="bold">Diferença a Pagar R$</td>
            <td class="text-right bold">{{ number_format($item->valor_troca - $item->valor_original, 2, ',', '.') }}</td>
        </tr>
        @elseif($item->valor_original > $item->valor_troca)
        <tr>
            <td class="bold">Crédito Gerado R$</td>
            <td class="text-right bold">{{ number_format($item->valor_original - $item->valor_troca, 2, ',', '.') }}</td>
        </tr>
        @else
        <tr>
            <td class="bold">Diferença R$</td>
            <td class="text-right bold">0,00</td>
        </tr>
        @endif
    </table>

    <br>

    <!-- Pagamentos -->
    @if($item->valor_troca > $item->valor_original)
        <table style="width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px;">
            <thead>
                <tr>
                    <th class="bold text-left" style="width: 28%; padding: 1px 0;">DATA PGTO</th>
                    <th class="bold text-right" style="width: 22%; padding: 1px 0;">R$ VALOR</th>
                    <th class="bold text-right" style="width: 50%; padding: 1px 0;">TIPO PGTO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left" style="padding: 1px 0; vertical-align: top;">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-right" style="padding: 1px 0; vertical-align: top;">
                        {{ number_format($item->valor_troca - $item->valor_original, 2, ',', '.') }}
                    </td>
                    <td class="text-right uppercase" style="padding: 1px 0; vertical-align: top;">
                        {{ \App\Models\Nfce::getTipoPagamento($item->tipo_pagamento) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="line"></div>
    @endif
    
    <div>
        VENDEDOR(A): {{ $item->nfce ? $item->nfce->vendedor() : '--' }}
    </div>

    <div class="line"></div>

    <div class="text-center" style="margin-top: 10px; font-size: 9px;">
        Recebi a(s) mercadoria(s) acima descrita(s),<br>
        concordando plenamente com os prazos e condições de<br>
        garantia da troca.
    </div>

    <br><br><br>
    
    <div class="text-center">
        __________________________________________<br>
        ASSINATURA DO CLIENTE
    </div>

    <br>

</body>
</html>
