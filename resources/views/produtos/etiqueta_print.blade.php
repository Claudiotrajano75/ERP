<div class="no-print" style="padding: 10px; background: #f4f4f4; border-bottom: 1px solid #ccc; margin-bottom: 15px;">
    <button class="btn btn-success" onclick="window.print()" style="padding: 8px 20px; font-weight: bold; cursor: pointer; border-radius: 4px; border: none; background: #28a745; color: white;">
        Imprimir Etiquetas
    </button>
</div>

<div id="preview_body">
    @for($i=0; $i<$quantidade; $i++)
        <div class="sticker-border" style="height: {{$altura}}mm; width: {{$largura}}mm;">
            
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
        padding-left: 3mm; /* Margem de segurança para o começo da folha */
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
        padding: 0.5mm 1.5mm; /* Aumentado o padding lateral para não cortar */
        box-sizing: border-box;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #000;
        gap: 0.1mm;
    }

    .barcode-section {
        width: 100%;
        margin-bottom: 0.1mm;
    }

    .barcode-section img {
        display: block;
        margin: 0 auto;
        max-width: 90%; /* Reduzido para não encostar nas bordas */
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