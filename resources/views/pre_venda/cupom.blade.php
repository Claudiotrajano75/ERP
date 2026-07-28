<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pré-Venda</title>
    <style>
        @page {
            margin: 15px 10px 0px 15px !important; /* Topo aumentado para 15px */
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
            table-layout: fixed; /* Força respeito às larguras */
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
        
        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .barcode {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    @if (session()->has('flash_success'))
        <div style="text-align:center; margin-bottom:6px; padding:4px 6px; background:#d4edda; border-radius:3px; font-size:9px; font-weight:bold; color:#155724;">
            {{ session()->get('flash_success') }}
        </div>
    @endif


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
        CLIENTE: {{ $item->cliente ? $item->cliente->razao_social : 'Cliente padrão' }}
    </div>
    
    <div class="line"></div>
    
    <div>
        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
        <span style="float: right;">PRÉ-VENDA Nº {{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="line"></div>

    <!-- Tabela de Itens -->
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
                    {{ number_format($i->quantidade, 2, ',', '.') }} x {{ number_format($i->valor, 2, ',', '.') }}
                </td>
                <td class="text-right" valign="bottom">
                    {{ number_format($i->quantidade * $i->valor, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <!-- Totais -->
    <table class="totals">
        <tr>
            <td class="bold">Total da Pré-Venda R$</td>
            <td class="text-right bold">{{ number_format($item->valor_total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <br>

    <!-- Pagamentos -->
    <div style="margin-bottom: 5px;">
        <span class="bold">DATA PGTO</span> 
        <span class="bold" style="margin-left: 20px;">R$ VALOR</span>
        <span class="bold" style="float: right;">TIPO PGTO</span>
    </div>
    
    @if(count($item->fatura) > 0)
        @foreach($item->fatura as $f)
        <div>
            {{ \Carbon\Carbon::parse($f->created_at)->format('d/m/Y') }}
            <span style="margin-left: 30px;">{{ number_format($f->valor_parcela, 2, ',', '.') }}</span>
            <span style="float: right;" class="uppercase">
                {{ \App\Models\Nfce::getTipoPagamento($f->tipo_pagamento) }}
            </span>
        </div>
        @endforeach
    @else
        <div>
            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
            <span style="margin-left: 30px;">{{ number_format($item->valor_total, 2, ',', '.') }}</span>
            <span style="float: right;" class="uppercase">
                {{ $item->tipo_pagamento ? \App\Models\Nfce::getTipoPagamento($item->tipo_pagamento) : 'DINHEIRO' }}
            </span>
        </div>
    @endif

    <div class="line"></div>
    
    <div>
        VENDEDOR(A): {{ $item->vendedor ? $item->vendedor->nome : '--' }}
    </div>

    <div class="line"></div>
    
</body>
</html>
