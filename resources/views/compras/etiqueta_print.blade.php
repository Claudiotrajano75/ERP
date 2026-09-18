<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Etiquetas</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
    }

    /* ── Barra de controle (some no print) ── */
    .print-toolbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 9999;
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 60%, #24243e 100%);
        padding: 10px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0,0,0,0.35);
        gap: 12px;
    }
    .print-toolbar .tb-title {
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .print-toolbar .tb-title span {
        background: rgba(255,255,255,0.12);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #c8d0ff;
    }
    .print-toolbar .tb-actions { display: flex; gap: 10px; align-items: center; }
    .btn-print {
        background: #4f6ef7;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-print:hover { background: #3b57e0; transform: translateY(-1px); }
    .btn-back {
        background: rgba(255,255,255,0.12);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }
    .tb-count {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        color: #a8b5ff;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 12px;
    }

    /* ── Área de preview ── */
    #preview-area {
        margin-top: 68px;
        padding: 20px;
    }

    /* ── Etiqueta individual ── */
    .sticker-wrap {
        display: inline-block;
        vertical-align: top;
    }

    .sticker {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px dashed #aaa;
        overflow: hidden;
        box-sizing: border-box;
        padding: 2px 3px;
        text-align: center;
        background: #fff;
        page-break-inside: avoid;
    }

    .sticker .s-empresa {
        font-weight: 700;
        text-transform: uppercase;
        line-height: 1.1;
        word-break: break-word;
        overflow-wrap: break-word;
        width: 100%;
    }
    .sticker .s-nome {
        line-height: 1.1;
        word-break: break-word;
        overflow-wrap: break-word;
        width: 100%;
        margin-top: 1px;
    }
    .sticker .s-cod {
        color: #555;
        line-height: 1.1;
        width: 100%;
        margin-top: 1px;
    }
    .sticker .s-barcode {
        max-width: 96%;
        display: block;
        margin: 2px auto 0;
    }
    .sticker .s-barnum {
        letter-spacing: 0.5px;
        width: 100%;
        margin-top: 1px;
    }
    .sticker .s-valor {
        font-weight: 700;
        width: 100%;
        margin-top: 2px;
        padding-top: 1px;
        border-top: 0.3px solid #ddd;
    }

    /* ── PRINT ── */
    @media print {
        .print-toolbar { display: none !important; }
        #preview-area { margin-top: 0; padding: 0; background: #fff; }
        body { background: #fff; }
        .sticker { border: none !important; }
        @page {
            margin: 0;
        }
    }
</style>
</head>
<body>

{{-- BARRA DE CONTROLE --}}
<div class="print-toolbar">
    <div class="tb-title">
        🏷️ Pré-visualização de Etiquetas
        <span>{{ count($data) }} etiqueta(s) no total</span>
    </div>
    <div class="tb-actions">
        <div class="tb-count">
            Layout: {{ $largura }}mm × {{ $altura }}mm &nbsp;|&nbsp; {{ $quantidade_por_linhas }} por linha
        </div>
        <button class="btn-print" onclick="window.print()">
            🖨️ Imprimir
        </button>
        <a class="btn-back" href="javascript:history.back()">
            ← Voltar
        </a>
    </div>
</div>

{{-- ÁREA DE ETIQUETAS --}}
<div id="preview-area">
@php $contLinha = 0; @endphp
@foreach($data as $item)
    @php
        $marginLeft = ($quantidade_por_linhas > 1 && $contLinha > 0) ? $distancia_lateral : 0;
        $fs = (float)$tamanho_fonte;
    @endphp

    <div class="sticker-wrap" style="margin-top:{{ $distancia_topo }}mm; margin-left:{{ $marginLeft }}mm;">
        <div class="sticker" style="width:{{ $largura }}mm; height:{{ $altura }}mm;">

            @if($item['nome_empresa'])
            <div class="s-empresa" style="font-size:{{ $fs }}px;">{{ $item['empresa'] }}</div>
            @endif

            @if($item['nome_produto'])
            <div class="s-nome" style="font-size:{{ $fs }}px;">{{ Str::limit($item['nome'], 40) }}</div>
            @endif

            @if($item['cod_produto'])
            <div class="s-cod" style="font-size:{{ max($fs - 1, 5) }}px;">Cód: {{ $item['codigo'] }}</div>
            @endif

            <img class="s-barcode" src="/barcode/{{ $item['rand'] }}.png"
                style="height:{{ $tamanho_codigo }}mm; max-width:95%;">

            @if($item['codigo_barras_numerico'])
            <div class="s-barnum" style="font-size:{{ max($fs - 1, 5) }}px;">{{ $item['codigo_barras'] }}</div>
            @endif

            @if($item['valor_produto'])
            <div class="s-valor" style="font-size:{{ $fs + 1 }}px;">
                R$ {{ number_format($item['valor'], 2, ',', '.') }}
            </div>
            @endif

        </div>
    </div>

@php
    $contLinha++;
    if($contLinha == $quantidade_por_linhas){
        echo '<br>';
        $contLinha = 0;
    }
@endphp
@endforeach
</div>

</body>
</html>