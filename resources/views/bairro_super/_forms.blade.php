<div class="row g-3">
    <div class="col-md-6 col-12">
        <label class="form-label required"><i class="ri-map-pin-line me-1"></i> Nome do Bairro</label>
        {!!Form::text('nome', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Centro, Vila Nova, Jardim das Flores...'])
        !!}
        <small class="text-muted fs-11">Informe o nome oficial do bairro.</small>
    </div>
    
    <div class="col-md-6 col-12">
        <label class="form-label required"><i class="ri-building-line me-1"></i> Cidade / Município</label>
        {!!Form::select('cidade_id', '', isset($item) && $item->cidade ? [$item->cidade_id => $item->cidade->info] : [])
        ->required()
        ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])
        !!}
        <small class="text-muted fs-11">Digite as primeiras letras para buscar a cidade.</small>
    </div>
</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('bairros-super.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-3-line me-1"></i> Salvar Bairro
        </button>
    </div>
</div>