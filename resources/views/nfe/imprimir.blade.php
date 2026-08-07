<html>

<head>
    <meta charset="utf-8">

    <style type="text/css">
        /* ═══════════════ PÁGINA ═══════════════ */
        @page {
            margin: 13mm 11mm 20mm 11mm;
        }

        * {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #212529;
            font-size: 10.5px;
            line-height: 1.5;
        }

        /* ═══════════════ CABEÇALHO DO DOCUMENTO (gradiente índigo do ERP) ═══════════════ */
        .doc-header {
            background: #ffffff;
            border: 2px solid #4254ba;
            color: #4254ba;
            border-radius: 10px;
            padding: 14px 18px 12px 18px;
            margin-bottom: 12px;
        }

        .hd-top {
            width: 100%;
            border-collapse: collapse;
        }

        .hd-top td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .hd-logo {
            text-align: left;
            width: 55%;
        }

        .hd-logo img {
            max-width: 62px;
            max-height: 62px;
            height: auto;
        }

        .hd-emissao {
            text-align: right;
            width: 45%;
            font-size: 8.5px;
            color: #5f6673;
        }

        .hd-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #c3cadd;
        }

        /* ═══════════════ SEÇÕES ═══════════════ */
        .sec-label {
            color: #4254ba;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 12px 0 6px 0;
            padding-left: 8px;
            border-left: 3px solid #4254ba;
        }

        .info-box {
            border: 1px solid #e4e8f0;
            border-radius: 8px;
            padding: 9px 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 2.5px 6px;
            vertical-align: top;
            font-size: 10px;
        }

        .info-table td.k {
            color: #5f6673;
            width: 140px;
            white-space: nowrap;
        }

        .info-table td.v {
            color: #212529;
            font-weight: 600;
        }

        /* ═══════════════ TABELA DE ITENS ═══════════════ */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .items-table th {
            background: #ffffff;
            color: #4254ba;
            border-bottom: 2px solid #4254ba;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 7px 8px;
            text-align: left;
        }

        .items-table th.num,
        .items-table td.num {
            text-align: right;
        }

        .items-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e4e8f0;
            font-size: 10px;
            color: #212529;
            vertical-align: middle;
        }

        .items-table tr.total td {
            background: #ffffff;
            color: #17a497;
            border-top: 2px solid #17a497;
            border-bottom: 2px solid #17a497;
            font-weight: 700;
            font-size: 10.5px;
            padding: 8px;
        }

        .items-table td.obs-cel {
            padding: 3px 8px;
        }

        /* ═══════════════ RESUMO DE TOTAIS ═══════════════ */
        .totals-box {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .totals-box td {
            border: 1px solid #e4e8f0;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
        }

        .totals-box td b {
            display: block;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #5f6673;
            margin-bottom: 3px;
        }

        .totals-box td span {
            font-size: 12px;
            font-weight: 700;
            color: #212529;
        }

        .totals-box td.total-final {
            border: 2px solid #17a497;
        }

        .totals-box td.total-final b {
            color: #5f6673;
        }

        .totals-box td.total-final span {
            color: #17a497;
        }

        /* ═══════════════ FATURA / FORMA DE PAGAMENTO ═══════════════ */
        .fatura-table {
            width: 45%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .fatura-table th {
            color: #4254ba;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 6px 8px;
            text-align: left;
            border-bottom: 2px solid #4254ba;
        }

        .fatura-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e4e8f0;
            font-size: 10px;
        }

        .tag-pagamento {
            color: #4254ba;
            font-weight: 700;
        }

        /* ═══════════════ OBSERVAÇÃO ═══════════════ */
        .obs-box {
            border: 1px dashed #c3cadd;
            border-radius: 8px;
            padding: 9px 12px;
            margin-top: 10px;
            font-size: 10px;
        }

        .obs-box b {
            color: #4254ba;
        }

        /* ═══════════════ ASSINATURAS ═══════════════ */
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 26px;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 10px;
        }

        .sign-table .line {
            border-top: 1px solid #212529;
            padding-top: 5px;
            font-size: 10px;
            font-weight: 600;
            margin: 0 8px;
        }

        /* ═══════════════ RODAPÉ FIXO (repete em todas as páginas) ═══════════════ */
        footer {
            position: fixed;
            bottom: -16mm;
            left: 11mm;
            right: 11mm;
        }

        footer table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #c3cadd;
        }

        footer td {
            padding: 5px 2px 0 2px;
            font-size: 8px;
            color: #5f6673;
        }

        footer td.right {
            text-align: right;
        }

        footer img {
            max-width: 46px;
            height: auto;
        }

        .page_break {
            page-break-after: always;
        }

        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>

<!-- ═══════════════ CABEÇALHO ═══════════════ -->
<header>
    <div class="doc-header">
        <table class="hd-top">
            <tr>
                <td class="hd-logo">
                    @if($config->logo != null && file_exists(public_path('/uploads/logos/'. $config->logo)))
                    <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/uploads/logos/'. $config->logo)))}}" alt="Logo">
                    @elseif(file_exists(public_path('logo.png')))
                    <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo.png')))}}" alt="Logo">
                    @endif
                </td>
                <td class="hd-emissao">
                    Emissão: {{ date('d/m/Y - H:i') }}
                </td>
            </tr>
        </table>
        <div class="hd-title">
            @if($item->tpNF == 1)
            Pedido de Venda
            @else
            Pedido de Compra
            @endif
        </div>
    </div>
</header>

<body>

    <!-- ═══════════════ DADOS DA EMPRESA ═══════════════ -->
    <div class="sec-label">Dados da Empresa</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="k">Razão Social:</td>
                <td class="v">{{$config->nome}}</td>
                <td class="k">Documento:</td>
                <td class="v">{{ __setMask($config->cpf_cnpj) }}</td>
            </tr>
            <tr>
                <td class="k">Endereço:</td>
                <td class="v">{{$config->rua}}, {{$config->numero}} - {{$config->bairro}} - {{$config->cidade->nome}} ({{$config->cidade->uf}})</td>
                <td class="k">Complemento:</td>
                <td class="v">{{$config->complemento}}</td>
            </tr>
            <tr>
                <td class="k">CEP:</td>
                <td class="v">{{$config->cep}}</td>
                <td class="k">Telefone:</td>
                <td class="v">{{$config->celular}}</td>
            </tr>
            <tr>
                <td class="k">Email:</td>
                <td class="v" colspan="3">{{$config->email}}</td>
            </tr>
        </table>
    </div>

    <!-- ═══════════════ DADOS DO FORNECEDOR / CLIENTE ═══════════════ -->
    @if($item->tpNF == 0)
    <div class="sec-label">Dados do Fornecedor</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="k">Nome:</td>
                <td class="v">{{$item->fornecedor->razao_social}}</td>
                <td class="k">CPF/CNPJ:</td>
                <td class="v">{{$item->fornecedor->cpf_cnpj}}</td>
            </tr>
            <tr>
                <td class="k">Endereço:</td>
                <td class="v">{{$item->fornecedor->rua}}, {{$item->fornecedor->numero}} - {{$item->fornecedor->bairro}} - {{$item->fornecedor->cidade->nome}} ({{$item->fornecedor->cidade->uf}})</td>
                <td class="k">CEP:</td>
                <td class="v">{{$item->fornecedor->cep}}</td>
            </tr>
            <tr>
                <td class="k">Complemento:</td>
                <td class="v">{{$item->fornecedor->complemento }}</td>
                <td class="k">Telefone:</td>
                <td class="v">{{$item->fornecedor->telefone}}</td>
            </tr>
            <tr>
                <td class="k">Email:</td>
                <td class="v" colspan="3">{{$item->fornecedor->email}}</td>
            </tr>
        </table>
    </div>
    @else
    <div class="sec-label">Dados do Cliente</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="k">Nome:</td>
                <td class="v">{{$item->cliente->razao_social}}</td>
                <td class="k">CPF/CNPJ:</td>
                <td class="v">{{$item->cliente->cpf_cnpj}}</td>
            </tr>
            <tr>
                <td class="k">Endereço:</td>
                <td class="v">{{$item->cliente->rua}}, {{$item->cliente->numero}} - {{$item->cliente->bairro}} - {{$item->cliente->cidade->nome}} ({{$item->cliente->cidade->uf}})</td>
                <td class="k">CEP:</td>
                <td class="v">{{$item->cliente->cep}}</td>
            </tr>
            <tr>
                <td class="k">Complemento:</td>
                <td class="v">{{$item->cliente->complemento }}</td>
                <td class="k">Telefone:</td>
                <td class="v">{{$item->cliente->telefone}}</td>
            </tr>
            <tr>
                <td class="k">Email:</td>
                <td class="v" colspan="3">{{$item->cliente->email}}</td>
            </tr>
        </table>
    </div>
    @endif

    <!-- ═══════════════ INFORMAÇÕES DO DOCUMENTO ═══════════════ -->
    <div class="sec-label">Informações do Documento</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="k">Nº Doc:</td>
                <td class="v">{{ $item->numero_sequencial }}</td>
                <td class="k">Forma de Pagamento:</td>
                <td class="v">
                    @if(isset($item->fatura) && sizeof($item->fatura) > 0)
                    @foreach ($item->fatura as $f)
                    <span class="tag-pagamento">{{ $f->getTipo($f->tipo_pagamento) }}</span>
                    @endforeach
                    @else
                    <span>Não Informado</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="k">Frete por Conta:</td>
                <td class="v">
                    @if($item->frete)
                    @if($item->frete->tipo == 0)
                    Emitente
                    @elseif($item->frete->tipo == 1)
                    Destinatário
                    @elseif($item->frete->tipo == 2)
                    Terceiros
                    @else
                    Outros
                    @endif
                    @else
                    sem frete
                    @endif
                </td>
                <td class="k">Data da Venda:</td>
                <td class="v">{{ __data_pt($item->created_at) }}</td>
            </tr>
            <tr>
                @if($item->vendedor_id)
                <td class="k">Vendedor:</td>
                <td class="v">{{ $item->vendedor_setado->funcionario->nome }}</td>
                @endif
                <td class="k">Data de Entrega:</td>
                <td class="v">
                    @if($item->data_entrega != null)
                    {{ __data_pt($item->data_entrega, 0) }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- ═══════════════ MERCADORIAS ═══════════════ -->
    <div class="sec-label">Mercadorias</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 90px;">Cod/Ref</th>
                <th>Descrição</th>
                <th class="num" style="width: 75px;">Qtd.</th>
                <th class="num" style="width: 85px;">Vl Uni</th>
                <th class="num" style="width: 85px;">Vl Liq.</th>
            </tr>
        </thead>
        @php
        $somaItens = 0;
        $somaTotalItens = 0;
        $tipoDimensao = false;
        $tipoReceita = false;
        $casasDecimais = $config->casas_decimais;
        $casasDecimaisQtd = 2;
        @endphp
        <tbody>
            @foreach($item->itens as $i)
            <tr class="{{ $loop->index % 2 == 0 ? '' : 'zebra' }}">
                <td>
                    {{$i->produto->id}} {{$i->produto->referencia != "" ? "/ " . $i->produto->referencia : "" }}
                </td>
                <td>
                    {{$i->descricao()}}
                </td>
                <td class="num">
                    {{__moeda($i->quantidade)}}
                    @if($i->largura > 0 || $i->esquerda > 0)
                    <span style="font-size: 8px;">x{{__moeda($i->quantidade_dimensao)}}</span>
                    @endif
                </td>
                <td class="num">{{__moeda($i->valor_unitario)}}</td>
                <td class="num">{{__moeda($i->quantidade * $i->valor_unitario)}}</td>
            </tr>
            @php
            $somaItens += $i->quantidade;
            $somaTotalItens += $i->quantidade * $i->valor_unitario;
            if($i->altura > 0 || $i->esquerda > 0){
                $tipoDimensao = true;
            }

            if($i->produto->receita){
                $tipoReceita = true;
            }
            @endphp
            @endforeach
            <tr class="total">
                <td colspan="3">Quantidade Total: {{ $somaItens }}</td>
                <td colspan="2" style="text-align: right;">Valor Total dos Itens: {{ __moeda($somaTotalItens) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ═══════════════ TOTAIS ═══════════════ -->
    <table class="totals-box">
        <tr>
            <td style="width: 25%;">
                <b>Desconto (-)</b>
                <span>{{__moeda($item->desconto)}}</span>
            </td>
            <td style="width: 25%;">
                <b>Acréscimo (+)</b>
                <span>{{__moeda($item->acrescimo)}}</span>
            </td>
            <td style="width: 25%;">
                <b>Frete (+)</b>
                <span>
                    @if($item->frete)
                    {{__moeda($item->valor_frete)}}
                    @else
                    0,00
                    @endif
                </span>
            </td>
            <td style="width: 25%;" class="total-final">
                <b>Valor Líquido</b>
                <span>{{__moeda($item->valor_produtos - $item->desconto + $item->acrescimo)}}</span>
            </td>
        </tr>
    </table>

    <!-- ═══════════════ IDENTIFICAÇÃO DOS PRODUTOS ═══════════════ -->
    @if(sizeof($item->produtoUnicos) > 0)
    <div class="sec-label">Identificação dos Produtos</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 210px;">Produto</th>
                <th style="width: 130px;">Código</th>
                <th>Observação</th>
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
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->fatura as $key => $d)
            <tr>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($d->data_vencimento)->format('d/m/Y')}}</strong>
                </td>
                <td>
                    <strong>{{ __moeda($d->valor)}}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- ═══════════════ OBSERVAÇÃO ═══════════════ -->
    @if($item->observacao != "" || $config->campo_obs_pedido != "")
    <div class="obs-box">
        <b>Observação:</b>
        {{$config->campo_obs_pedido}} {{$item->observacao}}
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
                    {{$item->cliente->razao_social}}
                    @else
                    {{$item->fornecedor->razao_social}}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- ═══════════════ SEÇÃO DE DIMENSÃO (quando houver) ═══════════════ -->
    @if($tipoDimensao)
    <div class="page_break"></div>
    <div class="sec-label">Dados do Cliente</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="k">Nome:</td>
                <td class="v">{{$item->cliente->razao_social}}</td>
                <td class="k">CPF/CNPJ:</td>
                <td class="v">{{$item->cliente->cpf_cnpj}}</td>
            </tr>
            <tr>
                <td class="k">Endereço:</td>
                <td class="v">{{$item->cliente->rua}}, {{$item->cliente->numero}} - {{$item->cliente->bairro}} - {{$item->cliente->cidade->nome}} ({{$item->cliente->cidade->uf}})</td>
                <td class="k">Telefone:</td>
                <td class="v">{{$item->cliente->telefone}}</td>
            </tr>
        </table>
    </div>

    <div class="sec-label">Mercadorias</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 70px;">Cod</th>
                <th>Descrição</th>
                <th class="num" style="width: 70px;">Qtd. Dim.</th>
                <th class="num" style="width: 70px;">Qtd.</th>
            </tr>
        </thead>
        @php
        $somaItens = 0;
        $somaTotalItens = 0;
        $tipoDimensao = false;
        @endphp
        <tbody>
            @foreach($item->itens as $i)
            <tr class="{{ $loop->index % 2 == 0 ? '' : 'zebra' }}">
                <td>{{$i->produto->id}}</td>
                <td>
                    {{$i->produto->nome}}
                    {{$i->produto->grade ? " (" . $i->produto->str_grade . ")" : ""}}
                    @if($i->produto->lote != "")
                    | Lote: {{$i->produto->lote}},
                    Vencimento: {{$i->produto->vencimento}}
                    @endif
                    @if($i->produto->tipo_dimensao != '')
                    @if($i->produto->tipo_dimensao == 'area')
                    [Altura: {{$i->altura}}, Largura: {{$i->largura}}, Profundidade: {{$i->profundidade}}]
                    @else
                    [Superior: {{$i->superior}}, Infeior: {{$i->inferior}}, Esquerda: {{$i->esquerda}}, Direita: {{$i->direita}}]
                    @endif
                    @endif
                </td>
                <td class="num">{{number_format($i->quantidade, 2, ',', '.')}}</td>
                <td class="num">{{number_format($i->quantidade_dimensao, 2, ',', '.')}}</td>
            </tr>
            @php
            $somaItens += $i->quantidade;
            $somaTotalItens += $i->quantidade * $i->valor;
            if($i->altura > 0 || $i->esquerda > 0){
                $tipoDimensao = true;
            }
            @endphp
            @endforeach
        </tbody>
    </table>

    <div class="info-box" style="margin-top: 8px;">
        <table class="info-table">
            <tr>
                <td class="k">Vendedor:</td>
                <td class="v">{{$item->usuario->nome}}</td>
                <td class="k">Data da Venda:</td>
                <td class="v">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i')}}</td>
                <td class="k">Data da Entrega:</td>
                <td class="v">{{ \Carbon\Carbon::parse($item->data_entrega)->format('d/m/Y')}}</td>
            </tr>
        </table>
    </div>

    @if($item->observacao != "")
    <div class="obs-box">
        <b>Observação:</b>
        {{$item->observacao}}
    </div>
    @endif
    @endif

    <!-- ═══════════════ SEÇÃO DE RECEITA (quando houver) ═══════════════ -->
    @if($tipoReceita)
    <div class="page_break"></div>
    <div class="sec-label">Dados do Cliente</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="k">Nome:</td>
                <td class="v">{{$item->cliente->razao_social}}</td>
                <td class="k">CPF/CNPJ:</td>
                <td class="v">{{$item->cliente->cpf_cnpj}}</td>
            </tr>
            <tr>
                <td class="k">Endereço:</td>
                <td class="v">{{$item->cliente->rua}}, {{$item->cliente->numero}} - {{$item->cliente->bairro}} - {{$item->cliente->cidade->nome}} ({{$item->cliente->cidade->uf}})</td>
                <td class="k">Telefone:</td>
                <td class="v">{{$item->cliente->telefone}}</td>
            </tr>
        </table>
    </div>

    <div class="sec-label">Produtos</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 70px;">Cod</th>
                <th>Descrição</th>
                <th class="num" style="width: 70px;">Qtd.</th>
            </tr>
        </thead>
        @php
        $somaItens = 0;
        $somaTotalItens = 0;
        $tipoDimensao = false;
        @endphp
        <tbody>
            @foreach($item->itens as $i)
            <tr class="{{ $loop->index % 2 == 0 ? '' : 'zebra' }}">
                <td>{{$i->produto->id}}</td>
                <td>
                    {{$i->produto->nome}}
                    {{$i->produto->grade ? " (" . $i->produto->str_grade . ")" : ""}}
                    @if($i->produto->lote != "")
                    | Lote: {{$i->produto->lote}},
                    Vencimento: {{$i->produto->vencimento}}
                    @endif
                </td>
                <td class="num">{{number_format($i->quantidade, 2, ',', '.')}}</td>
            </tr>
            @php
            $somaItens += $i->quantidade;
            $somaTotalItens += $i->quantidade * $i->valor;
            @endphp

            <tr class="{{ $loop->index % 2 == 0 ? '' : 'zebra' }}">
                <td colspan="3" style="font-size: 9px; color: #4254ba; font-weight: 600;">Composição do item:</td>
            </tr>
            @foreach($i->produto->receita->itens as $ir)
            <tr>
                <td style="text-align: left;" colspan="2">{{$ir->produto->nome}}</td>
                <td class="num">{{$ir->quantidade}} {{$ir->medida}}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <h4 style="color: #4254ba; margin-top: 14px;">Informação técnica do(s) produto(s):</h4>
    @foreach($item->itens as $i)
    @if($i->produto->info_tecnica_composto != "")
    <p style="font-size: 10px;"><strong>{{$i->produto->nome}}: {!! $i->produto->info_tecnica_composto !!}</p>
    @endif
    @endforeach
    @endif

</body>

<!-- ═══════════════ RODAPÉ ═══════════════ -->
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

</html>
