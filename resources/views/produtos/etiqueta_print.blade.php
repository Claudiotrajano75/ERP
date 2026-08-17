<div class="no-print" style="padding: 10px; background: #f4f4f4; border-bottom: 1px solid #ccc; margin-bottom: 15px;">
    <button class="btn btn-success" onclick="window.print()" style="padding: 8px 20px; font-weight: bold; cursor: pointer; border-radius: 4px; border: none; background: #28a745; color: white;">
        Imprimir Etiquetas
    </button>
</div>

<div id="preview_body">
    @for($i=0; $i<$quantidade; $i++)
        <div class="sticker-border" style="height: {{$altura}}mm; width: {{$largura}}mm;">
            
            @if($data['tipo'] == 'gondola')
                {{-- ═══ LAYOUT GÔNDOLA (PREÇO GRANDE À DIREITA E CÓDIGO À ESQUERDA) ═══ --}}
                <div class="label-container layout-gondola">
                    {{-- 1. Nome do Produto no Topo --}}
                    @if($data['nome_produto'])
                    <div class="product-name gondola-title" style="font-size: {{max(10, $tamanho_fonte+1)}}px">
                        {{$data['nome']}}
                    </div>
                    @endif

                    <div class="gondola-row">
                        {{-- 2. Código de Barras à Esquerda --}}
                        <div class="gondola-barcode">
                            <img src="/barcode/{{$rand}}.png" style="height: {{$tamanho_codigo}}mm; max-width: 100%;">
                            @if($data['codigo_barras_numerico'])
                            <div class="barcode-num" style="font-size: {{max(8, $tamanho_fonte-2)}}px">
                                {{$codigo}}
                            </div>
                            @endif
                            @if($data['cod_produto'])
                            <div class="secondary-info" style="font-size: {{max(7, $tamanho_fonte-5)}}px">
                                ID: {{$data['codigo']}}
                            </div>
                            @endif
                        </div>

                        {{-- 3. Preço Gigante à Direita --}}
                        @if($data['valor_produto'])
                        <div class="gondola-price">
                            <span class="currency" style="font-size: {{max(12, $tamanho_fonte+4)}}px">R$</span>
                            <span class="val-gondola" style="font-size: {{max(22, $tamanho_fonte+22)}}px">{{number_format($data['valor'], 2, ',', '.')}}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- ═══ LAYOUT PADRÃO (SIMPLES / MODERNO) ═══ --}}
                <div class="label-container layout-{{$data['tipo']}}">
                    
                    {{-- 1. Barcode --}}
                    <div class="barcode-section">
                        <img src="/barcode/{{$rand}}.png" style="height: {{$tamanho_codigo}}mm; width: 100%;">
                        @if($data['codigo_barras_numerico'])
                        <div class="barcode-num" style="font-size: {{max(9, $tamanho_fonte+1)}}px">
                            {{$codigo}}
                        </div>
                        @endif
                    </div>

                    {{-- 2. Nome --}}
                    @if($data['nome_produto'])
                    <div class="product-name" style="font-size: {{max(9, $tamanho_fonte)}}px">
                        {{$data['nome']}}
                    </div>
                    @endif

                    {{-- 3. Info Secundária --}}
                    @if($data['cod_produto'])
                    <div class="secondary-info" style="font-size: {{max(7, $tamanho_fonte-5)}}px">
                        ID: {{$data['codigo']}}
                    </div>
                    @endif

                    {{-- 4. Preço --}}
                    @if($data['valor_produto'])
                    <div class="price-box {{ $data['tipo'] == 'moderno' ? 'price-dark' : '' }}">
                        <span class="val" style="font-size: {{$data['tipo'] == 'moderno' ? ($tamanho_fonte+18) : ($tamanho_fonte+16)}}px">
                            <small style="font-size: 0.5em">R$</small>{{number_format($data['valor'], 2, ',', '.')}}
                        </span>
                    </div>
                    @endif

                </div>
            @endif

        </div>
    @endfor
</div>

<style type="text/css">
    * {
        box-sizing: border-box;
        -webkit-print-color-adjust: exact;
    }

    body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: Arial, sans-serif;
    }

    #preview_body {
        display: grid;
        width: 210mm; 
        grid-template-columns: repeat({{$quantidade_por_linhas}}, {{$largura}}mm);
        column-gap: {{$distancia_lateral}}mm;
        row-gap: {{$distancia_topo}}mm;
        padding-left: 3mm;
    }

    .sticker-border {
        border: 0.15mm dashed #bbb;
        overflow: hidden;
        background: #fff;
        border-radius: 1.5mm;
    }

    .label-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        padding: 0.5mm 1.5mm;
        box-sizing: border-box;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #000;
        gap: 0.1mm;
    }

    /* Estilos Específicos para Gôndola */
    .layout-gondola {
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        padding: 1.5mm 3mm !important;
        height: 100% !important;
        width: 100% !important;
        text-align: left !important;
    }

    .gondola-title {
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: -0.3px !important;
        line-height: 1.05 !important;
        width: 100% !important;
        text-align: left !important;
        margin-bottom: 0.5mm !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gondola-row {
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-end !important;
        justify-content: space-between !important;
        width: 100% !important;
        gap: 2mm !important;
    }

    .gondola-barcode {
        flex: 1;
        text-align: left;
    }

    .gondola-barcode img {
        margin: 0 !important;
        max-width: 100% !important;
        object-fit: contain;
    }

    .gondola-price {
        display: flex !important;
        align-items: baseline !important;
        justify-content: flex-end !important;
        font-weight: 900 !important;
        line-height: 1 !important;
        white-space: nowrap;
    }

    .gondola-price .currency {
        font-weight: 900 !important;
        margin-right: 2px !important;
    }

    .gondola-price .val-gondola {
        font-weight: 900 !important;
        letter-spacing: -1.5px !important;
    }

    .barcode-section {
        width: 100%;
        margin-bottom: 0.1mm;
    }

    .barcode-section img {
        display: block;
        margin: 0 auto;
        max-width: 90%;
        object-fit: contain;
    }

    .barcode-num {
        font-weight: normal;
        line-height: 1;
        margin-top: 0.2mm;
        margin-bottom: 0.3mm;
    }

    .product-name {
        font-weight: bold;
        line-height: 0.95;
        text-transform: uppercase;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        width: 100%;
    }

    .secondary-info {
        opacity: 0.6;
        line-height: 1;
        margin-top: 0.2mm;
    }

    .price-box {
        width: 100%;
        line-height: 1;
        margin-top: 0.5mm;
    }

    .price-box .val {
        font-weight: 900;
        letter-spacing: -0.5px;
    }

    .price-dark {
        background: #000 !important;
        color: #fff !important;
        padding: 0.8mm 0;
        border-radius: 0.8mm;
    }

    @media print {
        .no-print { display: none !important; }
        .sticker-border { border: none !important; border-radius: 0; }
        body { background: none !important; }
        @page { 
            margin: 0 !important;
            size: auto;
        }
    }
</style>