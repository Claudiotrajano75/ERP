<div class="row g-3">

    <!-- ═══ IDENTIFICAÇÃO DO MODELO ═══ -->
    <div class="col-12">
        <h6 class="fw-bold text-primary mb-2 border-bottom pb-2">
            <i class="ri-information-line me-1"></i> 1. Identificação do Gabarito
        </h6>
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-font-size me-1"></i> Nome do Modelo</label>
        {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Pimaco 6180 / Argox 3 Colunas'])->required()!!}
    </div>

    <div class="col-md-3 col-12">
        <label class="form-label required"><i class="ri-price-tag-3-line me-1"></i> Tipo de Etiqueta</label>
        {!!Form::select('tipo', '', ['simples' => 'Simples (Padrão)', 'gondola' => 'Gôndola (Prateleira)'])->attrs(['class' => 'form-select'])->required()!!}
    </div>

    <div class="col-md-5 col-12">
        <label class="form-label"><i class="ri-chat-1-line me-1"></i> Observações / Descrição</label>
        {!!Form::text('observacao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Papel A4 com 30 etiquetas por folha'])!!}
    </div>

    <!-- ═══ DIMENSÕES E ESPAÇAMENTOS ═══ -->
    <div class="col-12 mt-4">
        <h6 class="fw-bold text-primary mb-2 border-bottom pb-2">
            <i class="ri-ruler-line me-1"></i> 2. Dimensões e Espaçamentos (em milímetros - mm)
        </h6>
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required">Altura (mm)</label>
        {!!Form::tel('altura', '')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required">Largura (mm)</label>
        {!!Form::tel('largura', '')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required">Qtd. Etiquetas</label>
        {!!Form::tel('quantidade_etiquetas', '')->attrs(['data-mask' => '000', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required">Etiq. por Linha</label>
        {!!Form::tel('etiquestas_por_linha', '')->attrs(['data-mask' => '00', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required">Dist. Lateral (mm)</label>
        {!!Form::tel('distancia_etiquetas_lateral', '')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required">Dist. Topo (mm)</label>
        {!!Form::tel('distancia_etiquetas_topo', '')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-3 col-6">
        <label class="form-label required">Tamanho da Fonte (pt)</label>
        {!!Form::tel('tamanho_fonte', '')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
    </div>

    <div class="col-md-3 col-6">
        <label class="form-label required">Alt. Código de Barras (mm)</label>
        {!!Form::tel('tamanho_codigo_barras', '')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
    </div>

    <!-- ═══ CAMPOS A SEREM IMPRESSOS ═══ -->
    <div class="col-12 mt-4">
        <h6 class="fw-bold text-primary mb-2 border-bottom pb-2">
            <i class="ri-checkbox-line me-1"></i> 3. Campos Exibidos na Etiqueta
        </h6>
    </div>

    <div class="col-md-4 col-6">
        <div class="form-check form-switch p-2 bg-light rounded border">
            <input type="checkbox" name="nome_empresa" id="chk_nome_empresa" class="form-check-input ms-0 me-2" value="1" {{ (isset($item) && $item->nome_empresa) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark fs-13" for="chk_nome_empresa">Nome da Empresa</label>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="form-check form-switch p-2 bg-light rounded border">
            <input type="checkbox" name="nome_produto" id="chk_nome_produto" class="form-check-input ms-0 me-2" value="1" {{ (isset($item) && $item->nome_produto) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark fs-13" for="chk_nome_produto">Nome do Produto</label>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="form-check form-switch p-2 bg-light rounded border">
            <input type="checkbox" name="valor_produto" id="chk_valor_produto" class="form-check-input ms-0 me-2" value="1" {{ (isset($item) && $item->valor_produto) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark fs-13" for="chk_valor_produto">Preço de Venda</label>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="form-check form-switch p-2 bg-light rounded border">
            <input type="checkbox" name="codigo_produto" id="chk_codigo_produto" class="form-check-input ms-0 me-2" value="1" {{ (isset($item) && $item->codigo_produto) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark fs-13" for="chk_codigo_produto">Código do Produto (SKU)</label>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="form-check form-switch p-2 bg-light rounded border">
            <input type="checkbox" name="codigo_barras_numerico" id="chk_codigo_barras_numerico" class="form-check-input ms-0 me-2" value="1" {{ (isset($item) && $item->codigo_barras_numerico) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark fs-13" for="chk_codigo_barras_numerico">Barras Numérico (EAN)</label>
        </div>
    </div>

</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('padroes-etiqueta.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-3-line me-1"></i> Salvar Gabarito
        </button>
    </div>
</div>
