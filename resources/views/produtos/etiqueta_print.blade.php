<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Etiquetas - {{ $data['nome'] ?? 'Produtos' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style type="text/css">
        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            color: #0f172a;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── BARRA DE FERRAMENTAS SUPERIOR (NÃO IMPRESSA) ─── */
        .no-print-bar {
            background: #1e293b;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 9999;
            margin-bottom: 20px;
        }

        .no-print-bar .bar-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .no-print-bar .badge-info {
            background: #334155;
            color: #94a3b8;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .no-print-bar .bar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print {
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 9px 20px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s ease, transform 0.1s ease;
        }

        .btn-print:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-back {
            background: #475569;
            color: #ffffff;
            border: none;
            padding: 9px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: #64748b;
            color: #ffffff;
        }

        .print-tip {
            font-size: 11px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ─── GRID DE ETIQUETAS ─── */
        #preview_wrapper {
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        #preview_body {
            display: grid;
            width: 210mm;
            grid-template-columns: repeat({{$quantidade_por_linhas}}, {{$largura}}mm);
            column-gap: {{$distancia_lateral}}mm;
            row-gap: {{$distancia_topo}}mm;
            padding: 0;
            margin: 0 auto;
        }

        .sticker-border {
            border: 0.2mm dashed #cbd5e1;
            overflow: hidden;
            background: #ffffff;
            border-radius: 1mm;
            position: relative;
            box-sizing: border-box;
        }

        /* ─── CONTAINER BASE DA ETIQUETA ─── */
        .label-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            padding: 1.2mm 2mm;
            box-sizing: border-box;
            color: #000000;
            position: relative;
            overflow: hidden;
        }

        .company-header {
            font-size: {{ max(7, round($tamanho_fonte * 0.75)) }}px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            color: #475569;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.6mm;
        }

        /* ═════════════════════════════════════════════════════
           1. LAYOUT GÔNDOLA (SUPERMERCADO / VAREJO)
           ═════════════════════════════════════════════════════ */
        .layout-gondola {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            width: 100%;
            padding: 1.2mm 1.8mm;
        }

        .gondola-top {
            width: 100%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .gondola-title {
            font-size: {{ max(9, round($tamanho_fonte * 0.95)) }}px;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.15;
            letter-spacing: -0.2px;
            color: #000;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 0.8mm;
        }

        .gondola-bottom {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            gap: 1.5mm;
            flex: 1;
        }

        .gondola-barcode-col {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-start;
        }

        .gondola-barcode-col img {
            display: block;
            height: {{ max(4, $tamanho_codigo) }}mm;
            max-width: 100%;
            width: 100%;
            object-fit: fill;
            image-rendering: pixelated;
        }

        .gondola-ean {
            font-size: {{ max(7, round($tamanho_fonte * 0.75)) }}px;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.3px;
            line-height: 1;
            margin-top: 0.4mm;
            white-space: nowrap;
        }

        .gondola-meta {
            font-size: {{ max(6.5, round($tamanho_fonte * 0.68)) }}px;
            font-weight: 600;
            color: #64748b;
            line-height: 1.1;
            margin-top: 0.4mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .gondola-price-col {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-end;
            text-align: right;
            padding-left: 1mm;
        }

        .gondola-price-badge {
            display: inline-flex;
            align-items: baseline;
            justify-content: flex-end;
            white-space: nowrap;
            line-height: 0.9;
        }

        .gondola-price-badge .curr {
            font-size: {{ max(9, round($tamanho_fonte * 0.9)) }}px;
            font-weight: 800;
            margin-right: 1px;
            color: #000;
        }

        .gondola-price-badge .main-val {
            font-size: {{ max(16, min(28, round($tamanho_fonte * 1.8))) }}px;
            font-weight: 900;
            letter-spacing: -0.8px;
            color: #000;
        }

        .gondola-price-badge .cents-val {
            font-size: {{ max(10, min(18, round($tamanho_fonte * 1.15))) }}px;
            font-weight: 900;
            letter-spacing: -0.4px;
            color: #000;
        }

        .gondola-unit {
            font-size: {{ max(6, round($tamanho_fonte * 0.65)) }}px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 0.3mm;
            line-height: 1;
        }

        /* ═════════════════════════════════════════════════════
           2. LAYOUT SIMPLES (PADRÃO VERTICAL EQUILIBRADO)
           ═════════════════════════════════════════════════════ */
        .layout-simples {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            height: 100%;
            padding: 1.2mm 1.5mm;
        }

        .simples-header {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3mm;
        }

        .simples-title {
            font-size: {{ max(8.5, round($tamanho_fonte * 0.9)) }}px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1.15;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            width: 100%;
        }

        .simples-barcode-box {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0.5mm 0;
        }

        .simples-barcode-box img {
            display: block;
            height: {{ max(4, $tamanho_codigo) }}mm;
            max-width: 95%;
            width: 95%;
            object-fit: fill;
            image-rendering: pixelated;
        }

        .simples-ean {
            font-size: {{ max(7, round($tamanho_fonte * 0.75)) }}px;
            font-weight: 600;
            letter-spacing: 0.5px;
            line-height: 1;
            margin-top: 0.4mm;
        }

        .simples-footer {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1mm;
            margin-top: auto;
        }

        .simples-meta {
            font-size: {{ max(6.5, round($tamanho_fonte * 0.68)) }}px;
            font-weight: 600;
            color: #64748b;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .simples-price {
            font-size: {{ max(12, min(22, round($tamanho_fonte * 1.4))) }}px;
            font-weight: 900;
            letter-spacing: -0.4px;
            white-space: nowrap;
            text-align: right;
            margin-left: auto;
        }

        .simples-price small {
            font-size: 0.65em;
            font-weight: 700;
            margin-right: 1px;
        }

        /* ═════════════════════════════════════════════════════
           3. LAYOUT MODERNO (DARK HIGHLIGHT)
           ═════════════════════════════════════════════════════ */
        .layout-moderno {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 1.2mm 1.5mm;
        }

        .moderno-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .moderno-price-block {
            background: #0f172a;
            color: #ffffff;
            border-radius: 0.8mm;
            padding: 0.8mm 1.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            line-height: 1;
            margin-top: 0.6mm;
        }

        .moderno-price-block .val {
            font-size: {{ max(13, min(24, round($tamanho_fonte * 1.5))) }}px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        /* ─── AJUSTES DE IMPRESSÃO ─── */
        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            #preview_wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }

            .sticker-border {
                border: 0.1mm solid transparent !important;
                border-radius: 0 !important;
            }

            @page {
                margin: 0 !important;
                size: auto;
            }
        }
    </style>
</head>
<body>

    <!-- BARRA SUPERIOR DE AÇÕES -->
    <div class="no-print-bar">
        <div class="bar-title">
            <span>🏷️ Visualização de Impressão</span>
            <span class="badge-info">{{ $quantidade }} etiquetas</span>
            <span class="badge-info">{{ $quantidade_por_linhas }} por linha</span>
            <span class="badge-info">{{ $largura }}mm × {{ $altura }}mm</span>
            <span class="badge-info">Modelo: {{ ucfirst($data['tipo'] ?? 'padrão') }}</span>
        </div>

        <div class="print-tip">
            💡 Dica: Nas opções da impressora, defina <strong>Margens: Nenhuma</strong>.
        </div>

        <div class="bar-actions">
            <button class="btn-print" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Imprimir Etiquetas
            </button>
            <a href="javascript:history.back()" class="btn-back">
                ✕ Fechar / Voltar
            </a>
        </div>
    </div>

    @php
        $valorFloat = (float) ($data['valor'] ?? 0);
        $valorFormatado = number_format($valorFloat, 2, ',', '.');
        $partesValor = explode(',', $valorFormatado);
        $inteiro = $partesValor[0];
        $centavos = $partesValor[1] ?? '00';
    @endphp

    <!-- CORPO DE IMPRESSÃO DAS ETIQUETAS -->
    <div id="preview_wrapper">
        <div id="preview_body">
            @for($i=0; $i<$quantidade; $i++)
                <div class="sticker-border" style="height: {{$altura}}mm; width: {{$largura}}mm;">
                    
                    @if(($data['tipo'] ?? 'simples') == 'gondola')
                        {{-- ══════════ LAYOUT GÔNDOLA PROFISSIONAL (VAREJO / SUPERMERCADO) ══════════ --}}
                        <div class="label-container layout-gondola">
                            
                            {{-- Topo: Nome da Empresa e Nome do Produto --}}
                            <div class="gondola-top">
                                @if(!empty($data['nome_empresa']) && !empty($data['empresa']))
                                    <div class="company-header">{{ $data['empresa'] }}</div>
                                @endif

                                @if(!empty($data['nome_produto']))
                                    <div class="gondola-title" title="{{ $data['nome'] }}">
                                        {{ $data['nome'] }}
                                    </div>
                                @endif
                            </div>

                            {{-- Rodapé: Código de Barras à Esquerda e Preço à Direita --}}
                            <div class="gondola-bottom">
                                
                                {{-- Bloco de Código de Barras e IDs --}}
                                <div class="gondola-barcode-col">
                                    <img src="/barcode/{{$rand}}.png" alt="Barcode">
                                    
                                    @if(!empty($data['codigo_barras_numerico']))
                                        <div class="gondola-ean">{{ $codigo }}</div>
                                    @endif

                                    @if(!empty($data['cod_produto']))
                                        <div class="gondola-meta">
                                            COD: {{ $data['codigo'] }}
                                            @if(!empty($data['referencia_formatada']))
                                                | {{ $data['referencia_formatada'] }}
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Bloco de Preço em Destaque --}}
                                @if(!empty($data['valor_produto']))
                                    <div class="gondola-price-col">
                                        <div class="gondola-price-badge">
                                            <span class="curr">R$</span>
                                            <span class="main-val">{{ $inteiro }}</span>
                                            <span class="cents-val">,{{ $centavos }}</span>
                                        </div>
                                        @if(!empty($data['unidade']))
                                            <div class="gondola-unit">Un: {{ $data['unidade'] }}</div>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>

                    @elseif(($data['tipo'] ?? 'simples') == 'moderno')
                        {{-- ══════════ LAYOUT MODERNO (CONTRASTE ELEGANTE) ══════════ --}}
                        <div class="label-container layout-moderno">
                            
                            @if(!empty($data['nome_empresa']) && !empty($data['empresa']))
                                <div class="company-header" style="text-align: center;">{{ $data['empresa'] }}</div>
                            @endif

                            @if(!empty($data['nome_produto']))
                                <div class="simples-title" style="text-align: center;">
                                    {{ $data['nome'] }}
                                </div>
                            @endif

                            <div class="moderno-body">
                                <div class="simples-barcode-box">
                                    <img src="/barcode/{{$rand}}.png" alt="Barcode">
                                    @if(!empty($data['codigo_barras_numerico']))
                                        <div class="simples-ean">{{ $codigo }}</div>
                                    @endif
                                </div>

                                @if(!empty($data['cod_produto']))
                                    <div class="simples-meta" style="text-align: center; margin-top: 0.3mm;">
                                        COD: {{ $data['codigo'] }}
                                        @if(!empty($data['referencia_formatada']))
                                            | {{ $data['referencia_formatada'] }}
                                        @endif
                                    </div>
                                @endif
                            </div>

                            @if(!empty($data['valor_produto']))
                                <div class="moderno-price-block">
                                    <span class="val"><small style="font-size: 0.65em; margin-right: 1px;">R$</small>{{ $valorFormatado }}</span>
                                </div>
                            @endif

                        </div>

                    @else
                        {{-- ══════════ LAYOUT SIMPLES (PADRÃO VERTICAL EQUILIBRADO) ══════════ --}}
                        <div class="label-container layout-simples">
                            
                            <div class="simples-header">
                                @if(!empty($data['nome_empresa']) && !empty($data['empresa']))
                                    <div class="company-header">{{ $data['empresa'] }}</div>
                                @endif

                                @if(!empty($data['nome_produto']))
                                    <div class="simples-title">
                                        {{ $data['nome'] }}
                                    </div>
                                @endif
                            </div>

                            <div class="simples-barcode-box">
                                <img src="/barcode/{{$rand}}.png" alt="Barcode">
                                @if(!empty($data['codigo_barras_numerico']))
                                    <div class="simples-ean">{{ $codigo }}</div>
                                @endif
                            </div>

                            <div class="simples-footer">
                                @if(!empty($data['cod_produto']))
                                    <div class="simples-meta">
                                        COD: {{ $data['codigo'] }}
                                        @if(!empty($data['referencia_formatada']))
                                            <br>{{ $data['referencia_formatada'] }}
                                        @endif
                                    </div>
                                @endif

                                @if(!empty($data['valor_produto']))
                                    <div class="simples-price">
                                        <small>R$</small>{{ $valorFormatado }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endif

                </div>
            @endfor
        </div>
    </div>

</body>
</html>