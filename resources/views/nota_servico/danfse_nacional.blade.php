<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>DANFSE - Documento Auxiliar da NFS-e Nacional</title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
            size: A4 portrait;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #111;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }

        .tarja-homologacao {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px dashed #dc2626;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            padding: 6px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .box {
            border: 1px solid #333;
            margin-bottom: 6px;
        }

        .box-header {
            background-color: #f1f5f9;
            border-bottom: 1px solid #333;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 3px 6px;
        }

        .box-body {
            padding: 4px 6px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .chave-acesso {
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: center;
            padding: 4px;
            background-color: #f8fafc;
            border: 1px solid #94a3b8;
            margin: 4px 0;
        }

        .table-valores td,
        .table-valores th {
            border: 1px solid #cbd5e1;
            padding: 3px 4px;
            font-size: 8.5px;
        }

        .table-valores th {
            background-color: #f8fafc;
            text-align: center;
            font-weight: bold;
        }

        .lbl {
            font-size: 7.5px;
            color: #475569;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }

        .val {
            font-size: 9px;
            font-weight: bold;
            color: #0f172a;
        }
    </style>
</head>

<body>

    @if($item->ambiente == 2)
        <div class="tarja-homologacao">
            NFS-E EMITIDA EM AMBIENTE DE HOMOLOGAÇÃO - SEM VALOR FISCAL (PRODUÇÃO RESTRITA)
        </div>
    @endif

    <!-- CABEÇALHO OFICIAL DO DANFSE -->
    <div class="box">
        <table style="width: 100%; margin: 0;">
            <tr>
                <td
                    style="width: 25%; text-align: center; vertical-align: middle; padding: 6px; border-right: 1px solid #333;">
                    <div style="font-size: 10px; font-weight: bold; text-transform: uppercase;">
                        REPÚBLICA FEDERATIVA DO BRASIL
                    </div>
                    <div style="font-size: 8.5px; color: #475569;">
                        SECRETARIA ESPECIAL DA RECEITA FEDERAL DO BRASIL
                    </div>
                    <div style="font-size: 8.5px; font-weight: bold; margin-top: 4px;">
                        SISTEMA NACIONAL DA NFS-e
                    </div>
                </td>
                <td
                    style="width: 50%; text-align: center; vertical-align: middle; padding: 6px; border-right: 1px solid #333;">
                    <div style="font-size: 12px; font-weight: bold;">
                        DANFSE - Documento Auxiliar da NFS-e
                    </div>
                    <div style="font-size: 9.5px; font-weight: bold; color: #334155; margin-top: 2px;">
                        Nota Fiscal de Serviços Eletrônica Padrão Nacional
                    </div>
                    <div class="chave-acesso">
                        CHAVE DE ACESSO DA NFS-e<br>
                        {{ chunk_split($item->chave ?: '00000000000000000000000000000000000000000000000000', 4, ' ') }}
                    </div>
                </td>
                <td style="width: 25%; padding: 6px; vertical-align: middle;">
                    <table style="margin: 0;">
                        <tr>
                            <td><span class="lbl">Número da NFS-e:</span></td>
                            <td class="text-end"><span class="val">{{ $item->numero_nfse ?: '2' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="lbl">Número da DPS:</span></td>
                            <td class="text-end"><span class="val">{{ $item->numero_dps ?: '2' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="lbl">Série da DPS:</span></td>
                            <td class="text-end"><span class="val">{{ $item->serie_dps ?: '1' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="lbl">Data Emissão:</span></td>
                            <td class="text-end"><span
                                    class="val">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="lbl">Competência:</span></td>
                            <td class="text-end"><span
                                    class="val">{{ $item->created_at ? $item->created_at->format('d/m/Y') : date('d/m/Y') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- PRESTADOR DE SERVIÇOS -->
    <div class="box">
        <div class="box-header">Prestador de Serviços (Emitente)</div>
        <div class="box-body">
            <table style="margin: 0;">
                <tr>
                    <td style="width: 65%;">
                        <span class="lbl">Razão Social:</span>
                        <span class="val">{{ $empresa->nome }}</span>
                        @if(!empty($empresa->nome_fantasia))
                            <span style="font-size: 8.5px; color: #475569;">({{ $empresa->nome_fantasia }})</span>
                        @endif
                    </td>
                    <td style="width: 35%;">
                        <span class="lbl">CNPJ:</span>
                        <span class="val">{{ $empresa->cpf_cnpj }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 4px;">
                        <span class="lbl">Endereço:</span>
                        <span class="val">
                            {{ $empresa->rua }}, {{ $empresa->numero }}
                            {{ $empresa->complemento ? ' - ' . $empresa->complemento : '' }} - {{ $empresa->bairro }} -
                            CEP: {{ $empresa->cep }} -
                            {{ $empresa->cidade ? $empresa->cidade->nome . '/' . $empresa->cidade->uf : '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 4px;">
                        <span class="lbl">Inscrição Municipal:</span>
                        <span class="val">{{ $empresa->im ?: 'ISENTO / NÃO INFORMADA' }}</span>
                    </td>
                    <td style="padding-top: 4px;">
                        <span class="lbl">Regime Tributário:</span>
                        <span class="val">{{ $empresa->tributacao ?: 'Simples Nacional' }} (Optante ME/EPP)</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- TOMADOR DE SERVIÇOS -->
    <div class="box">
        <div class="box-header">Tomador de Serviços (Cliente)</div>
        <div class="box-body">
            <table style="margin: 0;">
                <tr>
                    <td style="width: 65%;">
                        <span class="lbl">Nome / Razão Social:</span>
                        <span
                            class="val">{{ $item->razao_social ?: ($item->cliente ? $item->cliente->razao_social : 'CONSUMIDOR') }}</span>
                    </td>
                    <td style="width: 35%;">
                        <span class="lbl">CPF / CNPJ:</span>
                        <span
                            class="val">{{ $item->documento ?: ($item->cliente ? $item->cliente->cpf_cnpj : '') }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 4px;">
                        <span class="lbl">Endereço:</span>
                        <span class="val">
                            {{ $item->rua ?: ($item->cliente ? $item->cliente->rua : '') }},
                            {{ $item->numero ?: ($item->cliente ? $item->cliente->numero : 'S/N') }}
                            {{ $item->bairro ? ' - ' . $item->bairro : '' }} - CEP:
                            {{ $item->cep ?: ($item->cliente ? $item->cliente->cep : '') }} -
                            {{ $item->cidade ? $item->cidade->nome . '/' . $item->cidade->uf : ($item->cliente && $item->cliente->cidade ? $item->cliente->cidade->nome . '/' . $item->cliente->cidade->uf : '') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 4px;">
                        <span class="lbl">E-mail:</span>
                        <span
                            class="val">{{ $item->email ?: ($item->cliente ? $item->cliente->email : 'Não informado') }}</span>
                    </td>
                    <td style="padding-top: 4px;">
                        <span class="lbl">Telefone:</span>
                        <span
                            class="val">{{ $item->telefone ?: ($item->cliente ? $item->cliente->telefone : 'Não informado') }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- DISCRIMINAÇÃO DOS SERVIÇOS -->
    <div class="box">
        <div class="box-header">Discriminação dos Serviços Prestados</div>
        <div class="box-body" style="min-height: 80px;">
            <div style="margin-bottom: 6px;">
                <span class="lbl">Código de Tributação Nacional (LC 116 / NBS):</span>
                <span class="val">
                    {{ $item->servico && $item->servico->codigo_tributacao_nacional ? $item->servico->codigo_tributacao_nacional : '140101' }}
                    - Lubrificação, limpeza, revisão, manutenção e conservação de máquinas e equipamentos.
                </span>
            </div>
            <div>
                <span class="lbl">Descrição Detalhada:</span>
                <div style="font-size: 9px; color: #1e293b; white-space: pre-line; margin-top: 2px;">
                    {{ $item->servico ? $item->servico->discriminacao : 'Prestação de serviços técnicos especializados.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- DETALHAMENTO DE VALORES E TRIBUTAÇÃO -->
    <div class="box">
        <div class="box-header">Detalhamento de Valores e Tributação do ISSQN</div>
        <div class="box-body" style="padding: 0;">
            <table class="table-valores" style="margin: 0;">
                <thead>
                    <tr>
                        <th>Valor dos Serviços (R$)</th>
                        <th>Desconto Incond. (R$)</th>
                        <th>Deduções Legais (R$)</th>
                        <th>Base de Cálculo (R$)</th>
                        <th>Alíquota ISS (%)</th>
                        <th>ISS Apurado (R$)</th>
                        <th>ISS Retido</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="text-bold">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                        <td>{{ $item->servico && $item->servico->aliquota_iss > 0 ? number_format($item->servico->aliquota_iss, 2, ',', '.') . '%' : 'DAS (SN)' }}
                        </td>
                        <td>{{ $item->servico && $item->servico->iss_retido == 1 ? 'R$ ' . number_format($item->valor_total * ($item->servico->aliquota_iss / 100), 2, ',', '.') : 'Recolhido no DAS' }}
                        </td>
                        <td>{{ $item->servico && $item->servico->iss_retido == 1 ? 'SIM' : 'NÃO' }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table-valores" style="margin: 0; border-top: 1px solid #333;">
                <thead>
                    <tr>
                        <th>PIS (R$)</th>
                        <th>COFINS (R$)</th>
                        <th>INSS (R$)</th>
                        <th>IRRF (R$)</th>
                        <th>CSLL (R$)</th>
                        <th>Outras Retenções (R$)</th>
                        <th style="background-color: #e2e8f0;">VALOR LÍQUIDO DA NFS-e</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td class="text-bold" style="background-color: #f1f5f9; font-size: 10px; color: #0f172a;">
                            R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- INFORMAÇÕES COMPLEMENTARES -->
    <div class="box">
        <div class="box-header">Informações Complementares / Observações</div>
        <div class="box-body" style="font-size: 8px; color: #334155;">
            <div>• Documento emitido por ME ou EPP optante pelo Simples Nacional nos termos da LC nº 123/2006.</div>
            <div>• Os tributos municipais e federais são apurados e recolhidos unificadamente no regime do Simples
                Nacional (PGDAS-D).</div>
            <div>• Identificador Nacional da DPS:
                <strong>{{ $item->chave_dps ?: 'DPS231290824155666300017400001000000000000002' }}</strong></div>
            <div>• Município de Incidência do ISS: <strong>Sobral/CE (IBGE: 2312908)</strong></div>
            <div>• Protocolo / Ambiente de Processamento: <strong>SEFIN Nacional da Receita Federal (Versão do
                    Aplicativo 1.6.0)</strong></div>
        </div>
    </div>

</body>

</html>