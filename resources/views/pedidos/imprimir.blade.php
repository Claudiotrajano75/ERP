<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Comanda #{{ $item->comanda }}</title>
    <style type="text/css">
        @page {
            margin: 6px 8px 6px 8px !important;
            padding: 0 !important;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8px;
            line-height: 1.25;
            color: #111;
            background: #fff;
            width: 100%;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .line-dashed {
            border-top: 1px dashed #555;
            margin: 5px 0;
            width: 100%;
        }

        .line-solid {
            border-top: 1px solid #111;
            margin: 4px 0;
            width: 100%;
        }

        /* Box de identificação da Comanda */
        .comanda-badge {
            border: 1px solid #222;
            border-radius: 4px;
            padding: 5px 4px;
            margin: 5px 0;
            text-align: center;
            background-color: #fafafa;
        }

        .comanda-title {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #000;
        }

        .mesa-title {
            font-size: 9px;
            font-weight: bold;
            color: #222;
            margin-top: 2px;
        }

        .comanda-info {
            font-size: 7.5px;
            color: #555;
            margin-top: 3px;
        }

        /* Tabela de Produtos */
        table.itens-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }

        table.itens-table th {
            font-size: 7.5px;
            font-weight: bold;
            padding: 3px 1px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        table.itens-table td {
            font-size: 8px;
            padding: 2px 1px;
            vertical-align: top;
        }

        .item-obs {
            font-size: 7px;
            color: #444;
            padding-left: 6px;
            line-height: 1.2;
        }

        /* Totais */
        .total-box {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 0;
            margin: 4px 0;
        }

        .footer-note {
            text-align: center;
            margin-top: 8px;
            font-size: 7px;
            color: #555;
            line-height: 1.3;
        }
    </style>
</head>
<body>

    <!-- CABEÇALHO EMPRESA -->
    <div class="text-center">
        @if(!empty($config->nome_fantasia))
            <div class="bold uppercase" style="font-size: 11px; letter-spacing: 0.3px;">{{ $config->nome_fantasia }}</div>
            <div style="font-size: 8px; color: #444;">{{ $config->nome }}</div>
        @else
            <div class="bold uppercase" style="font-size: 10.5px;">{{ $config->nome }}</div>
        @endif

        @if(!empty($config->cpf_cnpj))
            <div style="font-size: 7.5px; margin-top: 1px;">
                CNPJ: {{ $config->cpf_cnpj }}
                @if(!empty($config->ie)) | IE: {{ $config->ie }} @endif
            </div>
        @endif

        @if(!empty($config->rua))
            <div style="font-size: 7.5px;">
                {{ $config->rua }}, {{ $config->numero }}
                @if(!empty($config->bairro)) - {{ $config->bairro }} @endif
            </div>
        @endif

        @if($config->cidade)
            <div style="font-size: 7.5px;">
                {{ $config->cidade->nome }} - {{ $config->cidade->uf }}
                @if(!empty($config->celular)) | Tel: {{ $config->celular }} @endif
            </div>
        @elseif(!empty($config->celular))
            <div style="font-size: 7.5px;">Tel: {{ $config->celular }}</div>
        @endif
    </div>

    <!-- IDENTIFICADOR DA COMANDA -->
    <div class="comanda-badge">
        <div class="comanda-title">COMANDA #{{ str_pad($item->comanda, 2, '0', STR_PAD_LEFT) }}</div>
        @if(!empty($item->mesa))
            <div class="mesa-title">MESA {{ $item->mesa }}</div>
        @endif
        <div class="comanda-info">
            Emissão: {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
            @if($item->funcionario)
                | Garçom: {{ $item->funcionario->nome }}
            @endif
        </div>
    </div>

    <!-- DADOS DO CLIENTE (SE HOUVER) -->
    @if(!empty($item->cliente_nome) || !empty($item->cliente_fone))
    <div style="margin: 3px 0; font-size: 8px; background: #fafafa; border: 1px dashed #ccc; padding: 3px 4px; border-radius: 3px;">
        @if(!empty($item->cliente_nome))
            <div><strong>Cliente:</strong> {{ $item->cliente_nome }}</div>
        @endif
        @if(!empty($item->cliente_fone))
            <div><strong>Telefone:</strong> {{ $item->cliente_fone }}</div>
        @endif
    </div>
    @endif

    <!-- TABELA DE ITENS -->
    <table class="itens-table">
        <thead>
            <tr>
                <th class="text-left" style="width: 50%;">ITEM / PRODUTO</th>
                <th class="text-center" style="width: 14%;">QTD</th>
                <th class="text-right" style="width: 18%;">VL.UN</th>
                <th class="text-right" style="width: 18%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($item->itens as $i)
            <tr>
                <td class="text-left bold">
                    @if(sizeof($i->pizzas) > 0)
                        Pizza {{ $i->tamanho ? $i->tamanho->nome : '' }}
                    @else
                        {{ $i->produto ? $i->produto->nome : 'Item' }}
                    @endif
                </td>
                <td class="text-center">{{ number_format($i->quantidade, ($i->quantidade == intval($i->quantidade) ? 0 : 2), ',', '.') }}</td>
                <td class="text-right">{{ number_format($i->valor_unitario, 2, ',', '.') }}</td>
                <td class="text-right bold">{{ number_format($i->sub_total, 2, ',', '.') }}</td>
            </tr>

            {{-- Sabores de Pizza --}}
            @if(sizeof($i->pizzas) > 0)
            <tr>
                <td colspan="4" class="item-obs">
                    ↳ Sabores: 
                    @foreach($i->pizzas as $s)
                        {{ $s->sabor ? $s->sabor->nome : '' }}@if(!$loop->last) / @endif
                    @endforeach
                </td>
            </tr>
            @endif

            {{-- Adicionais --}}
            @if(sizeof($i->adicionais) > 0)
            <tr>
                <td colspan="4" class="item-obs">
                    ↳ + Adicionais: {{ $i->getAdicionaisStr() }}
                </td>
            </tr>
            @endif

            {{-- Observação do Item --}}
            @if(!empty($i->observacao))
            <tr>
                <td colspan="4" class="item-obs" style="font-style: italic; color: #555;">
                    ↳ Obs: {{ $i->observacao }}
                </td>
            </tr>
            @endif

            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 10px 0; font-style: italic; color: #777; font-size: 8px;">
                    Nenhum item lançado no momento.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="line-dashed"></div>

    <!-- TOTAIS E RESUMO -->
    <table style="width: 100%; font-size: 8px;">
        <tr>
            <td class="text-left">Total de Itens:</td>
            <td class="text-right bold">{{ sizeof($item->itens) }}</td>
        </tr>
        @if(!empty($item->tipo_pagamento))
        <tr>
            <td class="text-left">Forma de Pagto:</td>
            <td class="text-right uppercase">{{ $item->tipo_pagamento }}</td>
        </tr>
        @endif
        @if(!empty($item->observacao))
        <tr>
            <td colspan="2" style="padding-top: 3px; font-size: 7.5px; color: #444;">
                <strong>Obs da Comanda:</strong> {{ $item->observacao }}
            </td>
        </tr>
        @endif
    </table>

    <!-- VALOR TOTAL EM DESTAQUE -->
    <div class="total-box">
        <table style="width: 100%;">
            <tr>
                <td class="text-left bold" style="font-size: 10.5px;">TOTAL:</td>
                <td class="text-right bold" style="font-size: 12px; letter-spacing: 0.3px;">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- RODAPÉ TÉRMICO NÃO FISCAL -->
    <div class="footer-note">
        <div class="bold" style="letter-spacing: 0.5px;">COMPROVANTE NÃO FISCAL</div>
        <div>Controle de Consumo Interno</div>
        <div style="margin-top: 2px; font-size: 6.5px; color: #777;">
            Impresso em {{ date('d/m/Y H:i:s') }}
        </div>
    </div>

</body>
</html>
