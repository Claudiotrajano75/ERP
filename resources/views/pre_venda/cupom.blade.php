<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovante de Pré-Venda #{{ $item->codigo }}</title>
    <style>
        @page {
            margin: 10px 10px 0px 10px !important;
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

    <!-- Cabeçalho Emitente -->
    <div class="text-center bold uppercase" style="font-size: 14px;">
        {{ $config->nome_fantasia ?: $config->nome }}
    </div>
    @if($config->nome_fantasia && $config->nome && ($config->nome_fantasia !== $config->nome))
    <div class="text-center uppercase" style="font-size: 10px;">
        {{ $config->nome }}
    </div>
    @endif
    <div class="text-center">
        @if($config->rua)
            {{ $config->rua }}, {{ $config->numero }} - {{ $config->bairro }}<br>
        @endif
        @if($config->cidade)
            {{ $config->cidade->nome }} - {{ $config->cidade->uf }}<br>
        @endif
        @if($config->celular)
            Tel: {{ $config->celular }}
        @endif
    </div>
    
    <div class="line"></div>
    
    <div>
        CNPJ: {{ $config->cpf_cnpj }} 
        @if($config->ie)
            <span style="float: right;">IE: {{ $config->ie }}</span>
        @endif
    </div>
    
    <div class="line"></div>
    
    <div>
        CLIENTE: {{ $item->cliente ? $item->cliente->razao_social : ($item->nome ?: 'Consumidor Final') }}
        @if($item->cliente && $item->cliente->cpf_cnpj)
            <br>CPF/CNPJ: {{ $item->cliente->cpf_cnpj }}
        @elseif($item->cpf)
            <br>CPF: {{ $item->cpf }}
        @endif
    </div>
    
    <div class="line"></div>
    
    <div>
        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
        <span style="float: right;">PRÉ-VENDA Nº {{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
    @if($item->codigo)
    <div style="font-size: 7.5px; color: #333;">
        CÓDIGO: #{{ $item->codigo }}
    </div>
    @endif

    <div class="line"></div>

    <!-- Tabela de Itens (Padronizada em 6 Colunas idêntica ao PDV) -->
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th class="text-left" style="width: 14%;">CÓDIGO</th>
                <th class="text-left" style="width: 36%;">DESCRIÇÃO</th>
                <th class="text-right" style="width: 11%;">QTDE</th>
                <th class="text-right" style="width: 9%;">UN</th>
                <th class="text-right" style="width: 15%;">VL UNIT</th>
                <th class="text-right" style="width: 15%;">VL TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $totalItensCount = 0; @endphp
            @foreach($item->itens as $i)
                @php
                    $totalItensCount++;
                    $unidade = $i->produto ? ($i->produto->unidade ?? 'UN') : 'UN';
                    $subTotal = $i->quantidade * $i->valor;
                @endphp
                <tr>
                    <td class="text-left" valign="top">{{ $i->produto_id }}</td>
                    <td class="text-left uppercase" valign="top">{{ $i->produto ? $i->produto->nome : ($i->descricao() ?? 'Item ' . $i->produto_id) }}</td>
                    <td class="text-right" valign="top">{{ number_format($i->quantidade, ($i->quantidade == intval($i->quantidade) ? 0 : 2), ',', '.') }}</td>
                    <td class="text-right uppercase" valign="top">{{ $unidade }}</td>
                    <td class="text-right" valign="top">{{ number_format($i->valor, 2, ',', '.') }}</td>
                    <td class="text-right bold" valign="top">{{ number_format($subTotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <!-- Totais -->
    @php
        $subtotalSoma = $item->valor_total + ($item->desconto ?? 0) - ($item->acrescimo ?? 0);
    @endphp
    <table class="totals" style="width: 100%;">
        <tr>
            <td>Qtde total de itens</td>
            <td class="text-right">{{ $totalItensCount }}</td>
        </tr>
        <tr>
            <td>Valor Total R$</td>
            <td class="text-right">{{ number_format($subtotalSoma, 2, ',', '.') }}</td>
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
        <tr>
            <td class="bold">Total da Pré-Venda R$</td>
            <td class="text-right bold">{{ number_format($item->valor_total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <br>

    <!-- Pagamentos / Previsão -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px;">
        <thead>
            <tr>
                <th class="bold text-left" style="width: 28%; padding: 1px 0;">DATA PGTO</th>
                <th class="bold text-right" style="width: 22%; padding: 1px 0;">R$ VALOR</th>
                <th class="bold text-right" style="width: 50%; padding: 1px 0;">TIPO PGTO</th>
            </tr>
        </thead>
        <tbody>
            @if(count($item->fatura) > 0)
                @foreach($item->fatura as $f)
                <tr>
                    <td class="text-left" style="padding: 1px 0; vertical-align: top;">
                        {{ \Carbon\Carbon::parse($f->vencimento ?? ($f->data_vencimento ?? $f->created_at))->format('d/m/Y') }}
                    </td>
                    <td class="text-right" style="padding: 1px 0; vertical-align: top;">
                        {{ number_format($f->valor_parcela ?? $f->valor, 2, ',', '.') }}
                    </td>
                    <td class="text-right uppercase" style="padding: 1px 0; vertical-align: top;">
                        {{ \App\Models\Nfce::getTipoPagamento($f->tipo_pagamento) }}
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-left" style="padding: 1px 0; vertical-align: top;">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-right" style="padding: 1px 0; vertical-align: top;">
                        {{ number_format($item->valor_total, 2, ',', '.') }}
                    </td>
                    <td class="text-right uppercase" style="padding: 1px 0; vertical-align: top;">
                        {{ $item->tipo_pagamento ? \App\Models\Nfce::getTipoPagamento($item->tipo_pagamento) : 'DINHEIRO' }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="line"></div>
    
    <div>
        VENDEDOR(A): {{ $item->vendedor ? $item->vendedor->nome : '--' }}
    </div>

    <div class="line"></div>

    <div class="text-center" style="margin-top: 10px; font-size: 9px;">
        Recebi a(s) mercadoria(s) acima descrita(s),<br>
        concordando plenamente com os prazos e condições de<br>
        garantia.
    </div>

    <br><br><br>
    
    <div class="text-center">
        __________________________________________<br>
        ASSINATURA DO CLIENTE
    </div>

    <br>

    <div class="text-center bold">
        * OBRIGADO E VOLTE SEMPRE *
    </div>

</body>
</html>
