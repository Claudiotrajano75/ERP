<div class="row g-3 text-dark">
    <!-- Seção 1: Configuração do Layout -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-palette-line text-primary me-2 align-middle fs-18"></i>
            1. Dimensões e Layout da Etiqueta
        </h5>
        
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::select('modelo_id', 'Modelo', ['' => 'Selecione'] + $modelos->pluck('nome', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()!!}
            </div>
            
            <div class="col-md-3 col-12">
                {!!Form::select('tipo', 'Tipo', ['simples' => 'Simples', 'gondola' => 'Gôndola', 'moderno' => 'Moderno'])->attrs(['class' => 'form-select'])->required()!!}
            </div>
            
            <div class="col-md-2 col-6">
                {!!Form::tel('altura', 'Altura (mm)')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
            </div>
            
            <div class="col-md-2 col-6">
                {!!Form::tel('largura', 'Largura (mm)')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
            </div>
            
            <div class="col-md-2 col-6">
                {!!Form::tel('quantidade_etiquetas', 'Quantidade de Etiquetas')->attrs(['data-mask' => '000', 'class' => 'form-control'])->required()!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('etiquestas_por_linha', 'Etiquetas por Linha')->attrs(['data-mask' => '00', 'class' => 'form-control'])->required()!!}
            </div>
            
            <div class="col-md-2 col-6">
                {!!Form::tel('distancia_etiquetas_lateral', 'Distância Lateral (mm)')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
            </div>
            
            <div class="col-md-2 col-6">
                {!!Form::tel('distancia_etiquetas_topo', 'Distância Topo (mm)')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
            </div>
            
            <div class="col-md-2 col-6">
                {!!Form::tel('tamanho_fonte', 'Tamanho Fonte (pt)')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
            </div>
            
            <div class="col-md-4 col-12">
                {!!Form::tel('tamanho_codigo_barras', 'Tamanho do Código de Barras (mm)')->attrs(['data-mask' => '000.00', 'data-mask-reverse' => 'true', 'class' => 'form-control'])->required()!!}
            </div>
        </div>
    </div>

    <!-- Seção 2: Informações da Etiqueta -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-checkbox-multiple-line text-primary me-2 align-middle fs-18"></i>
            2. Informações que Devem Constar na Etiqueta
        </h5>
        
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::checkbox('nome_empresa', 'Nome da Empresa')->attrs(['class' => 'form-check-input'])->value(1)!!}
            </div>
            
            <div class="col-md-3 col-6">
                {!!Form::checkbox('nome_produto', 'Nome do Produto')->attrs(['class' => 'form-check-input'])->value(1)!!}
            </div>
            
            <div class="col-md-3 col-6">
                {!!Form::checkbox('valor_produto', 'Valor do Produto')->attrs(['class' => 'form-check-input'])->value(1)!!}
            </div>
            
            <div class="col-md-3 col-6">
                {!!Form::checkbox('codigo_produto', 'Código do Produto')->attrs(['class' => 'form-check-input'])->value(1)!!}
            </div>
            
            <div class="col-md-3 col-6">
                {!!Form::checkbox('codigo_barras_numerico', 'Código de Barras Numérico')->attrs(['class' => 'form-check-input'])->value(1)!!}
            </div>
        </div>
    </div>
</div>

<!-- Rodapé de Ações -->
<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-barcode-line align-middle me-1"></i> Gerar Etiquetas
        </button>
    </div>
</div>
