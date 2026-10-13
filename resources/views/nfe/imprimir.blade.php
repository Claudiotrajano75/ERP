<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        @page {
            margin: 10mm 10mm 18mm 10mm;
        }

        * {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #1f2937;
            font-size: 9.5px;
            line-height: 1.35;
        }

        /* ═══════════════ RODAPÉ FIXO (DOMPDF EXIGE NO INÍCIO DO BODY) ═══════════════ */
        footer {
            position: fixed;
            bottom: -14mm;
            left: 0;
            right: 0;
            height: 12mm;
        }

        footer table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #cbd5e1;
        }

        footer td {
            padding: 4px 0 0 0;
            font-size: 7.5px;
            color: #64748b;
        }

        footer td.right {
            text-align: right;
        }

        footer img {
            max-width: 40px;
            height: auto;
        }

        /* ═══════════════ CABEÇALHO COMPACTO ═══════════════ */
        .doc-header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #4338ca;
            margin-bottom: 8px;
        }

        .doc-header-table td {
            padding: 8px 10px;
            vertical-align: middle;
        }

        .hd-logo {
            width: 25%;
            text-align: left;
        }

        .hd-logo img {
            max-width: 52px;
            max-height: 48px;
            height: auto;
        }

        .hd-title {
            width: 50%;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #4338ca;
        }

        .hd-emissao {
            width: 25%;
            text-align: right;
            font-size: 8px;
            color: #64748b;
        }

        /* ═══════════════ BLOCOS DE DADOS (EMPRESA & CLIENTE LADO A LADO) ═══════════════ */
        .two-cols-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .two-cols-table td.col-box {
            width: 49%;
            vertical-align: top;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            background: #fafbfc;
        }

        .two-cols-table td.col-spacer {
            width: 2%;
        }

        .box-title {
            color: #4338ca;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
            padding-bottom: 2px;
            border-bottom: 1px solid #cbd5e1;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 1.5px 0;
            vertical-align: top;
            font-size: 8.5px;
        }

        .info-table td.k {
            color: #64748b;
            width: 70px;
            white-space: nowrap;
        }

        .info-table td.v {
            color: #1f2937;
            font-weight: bold;
        }

        /* ═══════════════ FAIXA DE INFORMAÇÕES DO DOCUMENTO ═══════════════ */
        .doc-info-bar {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            margin-bottom: 8px;
        }

        .doc-info-bar td {
            padding: 5px 6px;
            font-size: 8.5px;
            border-right: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .doc-info-bar td:last-child {
            border-right: none;
        }

        .doc-info-bar .lbl {
            color: #64748b;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: block;
            margin-bottom: 1px;
        }

        .doc-info-bar .val {
            color: #1f2937;
            font-weight: bold;
        }

        /* ═══════════════ TABELA DE MERCADORIAS (6 COLUNAS) ═══════════════ */
        .sec-label {
            color: #4338ca;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 6px 0 4px 0;
            padding-left: 6px;
            border-left: 3px solid #4338ca;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            margin-top: 3px;
        }

        .items-table th {
            background: #f1f5f9;
            color: #334155;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 5px 6px;
            border-bottom: 1.5px solid #cbd5e1;
            border-right: 1px solid #e2e8f0;
        }

        .items-table th:last-child {
            border-right: none;
        }

        .items-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            font-size: 8.5px;
            color: #1f2937;
            vertical-align: middle;
        }

        .items-table td:last-child {
            border-right: none;
        }

        .items-table tr.zebra {
            background: #f8fafc;
        }

        .items-table .center {
            text-align: center;
        }

        .items-table .num {
            text-align: center;
        }

        /* ═══════════════ PAINEL DE TOTAIS ═══════════════ */
        .totals-wrapper-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 8px;
        }

        .totals-wrapper-table td.empty-col {
            width: 45%;
        }

        .totals-wrapper-table td.content-col {
            width: 55%;
            vertical-align: top;
            text-align: right;
        }

        .totals-summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-summary-table td {
            padding: 2.5px 4px;
            font-size: 9px;
            text-align: right;
            border: none;
        }

        .totals-summary-table td.lbl-tot {
            color: #4b5563;
        }

        .totals-summary-table td.val-tot {
            font-weight: bold;
            color: #1f2937;
            width: 100px;
        }

        .totals-summary-table tr.tot-margem td {
            border-top: 1.5px solid #cbd5e1;
            padding-top: 4px;
            font-weight: bold;
        }

        .totals-summary-table tr.tot-margem td.lbl-tot {
            color: #1f2937;
            text-transform: uppercase;
        }

        .totals-summary-table tr.tot-margem td.val-tot {
            color: #1f2937;
            font-size: 11px;
            font-weight: bold;
        }

        /* ═══════════════ FATURA / PAGAMENTO ═══════════════ */
        .fatura-table {
            width: 45%;
            border-collapse: collapse;
            margin-top: 3px;
            border: 1px solid #cbd5e1;
        }

        .fatura-table th {
            background: #f1f5f9;
            color: #4338ca;
            font-size: 8px;
            text-transform: uppercase;
            padding: 4px 6px;
            text-align: left;
            border-bottom: 1.5px solid #cbd5e1;
        }

        .fatura-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8.5px;
        }

        /* ═══════════════ OBSERVAÇÃO & ASSINATURA ═══════════════ */
        .obs-box {
            border: 1px dashed #cbd5e1;
            padding: 6px 8px;
            margin-top: 8px;
            font-size: 8.5px;
            background: #fafbfc;
        }

        .obs-box b {
            color: #4338ca;
        }

        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 8px;
        }

        .sign-table .line {
            border-top: 1px solid #475569;
            padding-top: 3px;
            font-size: 8.5px;
            font-weight: bold;
            margin: 0 16px;
            color: #334155;
        }
    </style>
</head>

<body>

    <!-- ═══════════════ RODAPÉ FIXO ═══════════════ -->
    <footer id="footer_imagem">
        <table>
            <tbody>
                <tr>
                    <td>
                        {{env('SITE_SUPORTE')}}
                    </td>
                    <td class="right">
                        @if(file_exists(public_path('logo.png')))
                        <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo.png')))}}" alt="Logo">
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </footer>

    <!-- ═══════════════ CABEÇALHO COMPACTO ═══════════════ -->
    <table class="doc-header-table">
        <tr>
            <td class="hd-logo">
                @if($config->logo != null && file_exists(public_path('/uploads/logos/'. $config->logo)))
                <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/uploads/logos/'. $config->logo)))}}" alt="Logo">
                @elseif(file_exists(public_path('logo.png')))
                <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo.png')))}}" alt="Logo">
                @endif
            </td>
            <td class="hd-title">
                @if($item->tpNF == 1)
                ORÇAMENTO DE VENDA
                @else
                ORÇAMENTO DE COMPRA
                @endif
            </td>
            <td class="hd-emissao">
                Emissão: {{ date('d/m/Y - H:i') }}
            </td>
        </tr>
    </table>

    <!-- ═══════════════ DADOS DA EMPRESA E CLIENTE LADO A LADO ═══════════════ -->
    <table class="two-cols-table">
        <tr>
            <!-- Coluna 1: Empresa -->
            <td class="col-box">
                <div class="box-title">Dados da Empresa</div>
                <table class="info-table">
                    <tr>
                        <td class="k">Razão Social:</td>
                        <td class="v">{{$config->nome}}</td>
                    </tr>
                    <tr>
                        <td class="k">Documento:</td>
                        <td class="v">{{ __setMask($config->cpf_cnpj) }}</td>
                    </tr>
                    <tr>
                        <td class="k">Endereço:</td>
                        <td class="v">{{$config->rua}}, {{$config->numero}} - {{$config->bairro}} - {{$config->cidade->nome}} ({{$config->cidade->uf}})</td>
                    </tr>
                    <tr>
                        <td class="k">CEP / Fone:</td>
                        <td class="v">{{$config->cep}} | {{$config->celular ?: $config->telefone ?: '--'}}</td>
                    </tr>
                    @if($config->email)
                    <tr>
                        <td class="k">Email:</td>
                        <td class="v">{{$config->email}}</td>
                    </tr>
                    @endif
                </table>
            </td>

            <td class="col-spacer"></td>

            <!-- Coluna 2: Fornecedor / Cliente -->
            <td class="col-box">
                @if($item->tpNF == 0)
                <div class="box-title">Dados do Fornecedor</div>
                <table class="info-table">
                    <tr>
                        <td class="k">Nome:</td>
                        <td class="v">{{$item->fornecedor ? $item->fornecedor->razao_social : 'Consumidor Final'}}</td>
                    </tr>
                    <tr>
                        <td class="k">CPF/CNPJ:</td>
                        <td class="v">{{$item->fornecedor ? $item->fornecedor->cpf_cnpj : '--'}}</td>
                    </tr>
                    <tr>
                        <td class="k">Endereço:</td>
                        <td class="v">
                            @if($item->fornecedor)
                            {{$item->fornecedor->rua}}, {{$item->fornecedor->numero}} - {{$item->fornecedor->bairro}} - {{$item->fornecedor->cidade->nome}} ({{$item->fornecedor->cidade->uf}})
                            @else
                            --
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="k">CEP / Fone:</td>
                        <td class="v">
                            {{$item->fornecedor ? $item->fornecedor->cep : '--'}} | {{$item->fornecedor ? ($item->fornecedor->telefone ?: $item->fornecedor->celular ?: '--') : '--'}}
                        </td>
                    </tr>
                    @if($item->fornecedor && $item->fornecedor->email)
                    <tr>
                        <td class="k">Email:</td>
                        <td class="v">{{$item->fornecedor->email}}</td>
                    </tr>
                    @endif
                </table>
                @else
                <div class="box-title">Dados do Cliente</div>
                <table class="info-table">
                    <tr>
                        <td class="k">Nome:</td>
                        <td class="v">{{$item->cliente ? $item->cliente->razao_social : 'Consumidor Final'}}</td>
                    </tr>
                    <tr>
                        <td class="k">CPF/CNPJ:</td>
                        <td class="v">{{$item->cliente ? $item->cliente->cpf_cnpj : '--'}}</td>
                    </tr>
                    <tr>
                        <td class="k">Endereço:</td>
                        <td class="v">
                            @if($item->cliente)
                            {{$item->cliente->rua}}, {{$item->cliente->numero}} - {{$item->cliente->bairro}} - {{$item->cliente->cidade->nome}} ({{$item->cliente->cidade->uf}})
                            @else
                            --
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="k">CEP / Fone:</td>
                        <td class="v">
                            {{$item->cliente ? $item->cliente->cep : '--'}} | {{$item->cliente ? ($item->cliente->telefone ?: $item->cliente->celular ?: '--') : '--'}}
                        </td>
                    </tr>
                    @if($item->cliente && $item->cliente->email)
                    <tr>
                        <td class="k">Email:</td>
                        <td class="v">{{$item->cliente->email}}</td>
                    </tr>
                    @endif
                </table>
                @endif
            </td>
        </tr>
    </table>

    <!-- ═══════════════ INFORMAÇÕES DO DOCUMENTO COMPACTAS ═══════════════ -->
    <table class="doc-info-bar">
        <tr>
            <td style="width: 16%;">
                <span class="lbl">Nº Documento</span>
                <span class="val">{{ $item->numero_sequencial ?: ($item->numero ?: $item->id) }}</span>
            </td>
            <td style="width: 22%;">
                <span class="lbl">Data da Venda</span>
                <span class="val">{{ __data_pt($item->created_at) }}</span>
            </td>
            <td style="width: 26%;">
                <span class="lbl">Forma de Pagamento</span>
                <span class="val">
                    @if(isset($item->fatura) && sizeof($item->fatura) > 0)
                        @foreach ($item->fatura as $f)
                            {{ $f->getTipo($f->tipo_pagamento) }}@if(!$loop->last), @endif
                        @endforeach
                    @else
                        Não Informado
                    @endif
                </span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Frete</span>
                <span class="val">
                    @if($item->frete)
                        @if($item->frete->tipo == 0) Emitente
                        @elseif($item->frete->tipo == 1) Destinatário
                        @elseif($item->frete->tipo == 2) Terceiros
                        @else Outros @endif
                    @else
                        sem frete
                    @endif
                </span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Vendedor</span>
                <span class="val">
                    @if($item->vendedor_id && $item->vendedor_setado)
                        {{ $item->vendedor_setado->funcionario ? $item->vendedor_setado->funcionario->nome : '--' }}
                    @elseif($item->user)
                        {{ $item->user->name }}
                    @else
                        --
                    @endif
                </span>
            </td>
        </tr>
    </table>

    <!-- ═══════════════ MERCADORIAS (GRID COM 6 COLUNAS) ═══════════════ -->
    <div class="sec-label">Mercadorias</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 38%; text-align: left;">Produto</th>
                <th class="center" style="width: 10%;">Quantidade</th>
                <th class="center" style="width: 8%;">Unidade</th>
                <th class="center" style="width: 14%;">Valor Venda</th>
                <th class="center" style="width: 14%;">Valor Compra</th>
                <th class="center" style="width: 16%;">Total C/ Desconto</th>
            </tr>
        </thead>
        @php
            $somaTotalVenda = 0;
            $somaTotalCompra = 0;
            $tipoDimensao = false;
            $tipoReceita = false;
        @endphp
        <tbody>
            @foreach($item->itens as $i)
            @php
                $qtd = (float) $i->quantidade;
                $vendaUnit = (float) $i->valor_unitario;
                $unidadeMedida = $i->produto->unidade ?? 'UN';
                $vlrCompra = (float) ($i->produto->valor_compra ?? 0);

                $totItemVenda = $qtd * $vendaUnit;
                $totItemCompra = $qtd * $vlrCompra;

                // Total C/ Desconto = (Valor Venda - Valor Compra) * Quantidade
                $totCDesc = ($vendaUnit - $vlrCompra) * $qtd;

                $somaTotalVenda += $totItemVenda;
                $somaTotalCompra += $totItemCompra;

                if($i->altura > 0 || $i->esquerda > 0){
                    $tipoDimensao = true;
                }
                if($i->produto && $i->produto->receita){
                    $tipoReceita = true;
                }
            @endphp
            <tr class="{{ $loop->index % 2 == 0 ? '' : 'zebra' }}">
                <td style="text-align: left;">
                    <div style="font-weight: bold; color: #1f2937;">{{ $i->descricao() }}</div>
                    @if($i->produto && $i->produto->codigo_barras)
                    <div style="font-size: 7.5px; color: #64748b;">Cód: {{ $i->produto->codigo_barras }}</div>
                    @elseif($i->produto && $i->produto->referencia)
                    <div style="font-size: 7.5px; color: #64748b;">Ref: {{ $i->produto->referencia }}</div>
                    @endif
                </td>
                <td class="center" style="font-weight: bold;">
                    {{ number_format($qtd, 2, ',', '.') }}
                </td>
                <td class="center">
                    {{ strtoupper($unidadeMedida) }}
                </td>
                <td class="num" style="font-weight: bold;">
                    R$ {{ __moeda($vendaUnit) }}
                </td>
                <td class="num" style="font-weight: bold;">
                    {{ number_format($vlrCompra, 2, ',', '.') }}
                </td>
                <td class="num" style="font-weight: bold;">
                    R$ {{ __moeda($totCDesc) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $somaMargemLucro = $somaTotalVenda - $somaTotalCompra;
    @endphp

    <!-- ═══════════════ PAINEL DE TOTAIS (FECHAMENTO À DIREITA) ═══════════════ -->
    <table class="totals-wrapper-table">
        <tr>
            <td class="empty-col"></td>
            <td class="content-col">
                <table class="totals-summary-table">
                    <tr>
                        <td class="lbl-tot">Total do Subtotal (Sem Desconto):</td>
                        <td class="val-tot">R$ {{ __moeda($somaTotalVenda) }}</td>
                    </tr>
                    <tr>
                        <td class="lbl-tot">Total do Subtotal (Com Desconto):</td>
                        <td class="val-tot">R$ {{ __moeda($somaTotalCompra) }}</td>
                    </tr>
                    <tr class="tot-margem">
                        <td class="lbl-tot">TOTAL (Margem lucro):</td>
                        <td class="val-tot">R$ {{ __moeda($somaMargemLucro) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ═══════════════ IDENTIFICAÇÃO DOS PRODUTOS ═══════════════ -->
    @if(sizeof($item->produtoUnicos) > 0)
    <div class="sec-label">Identificação dos Produtos</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 210px; text-align: left;">Produto</th>
                <th style="width: 130px; text-align: left;">Código</th>
                <th style="text-align: left;">Observação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->produtoUnicos as $p)
            <tr>
                <td>{{ $p->produto->nome }}</td>
                <td>{{ $p->codigo }}</td>
                <td>{{ $p->observacao }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- ═══════════════ FATURA ═══════════════ -->
    @if(sizeof($item->fatura) > 0)
    <div class="sec-label">Fatura</div>
    <table class="fatura-table">
        <thead>
            <tr>
                <th>Vencimento</th>
                <th style="text-align: right;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->fatura as $key => $d)
            <tr>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($d->data_vencimento)->format('d/m/Y')}}</strong>
                </td>
                <td style="text-align: right;">
                    <strong>R$ {{ __moeda($d->valor)}}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- ═══════════════ OBSERVAÇÃO ═══════════════ -->
    @if($item->observacao != "" || (isset($config->campo_obs_pedido) && $config->campo_obs_pedido != ""))
    <div class="obs-box">
        <b>Observação:</b>
        {{$config->campo_obs_pedido ?? ''}} {{$item->observacao}}
    </div>
    @endif

    <!-- ═══════════════ ASSINATURAS ═══════════════ -->
    <table class="sign-table">
        <tr>
            <td>
                <div class="line">{{$config->nome}}</div>
            </td>
            <td>
                <div class="line">
                    @if($item->tpNF == 1)
                    {{$item->cliente ? $item->cliente->razao_social : 'Consumidor Final'}}
                    @else
                    {{$item->fornecedor ? $item->fornecedor->razao_social : 'Consumidor Final'}}
                    @endif
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
