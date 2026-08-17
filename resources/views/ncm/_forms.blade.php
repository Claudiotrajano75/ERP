<div class="row g-3">
    <div class="col-md-3 col-12">
        <label class="form-label required"><i class="ri-barcode-line me-1"></i> Código NCM</label>
        {!!Form::text('codigo', '')
        ->required()
        ->attrs(['class' => 'form-control', 'data-mask' => '0000.00.00', 'placeholder' => '0000.00.00'])
        !!}
    </div>
    <div class="col-md-9 col-12">
        <label class="form-label required"><i class="ri-file-text-line me-1"></i> Descrição do NCM</label>
        {!!Form::text('descricao', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Digite a descrição detalhada do NCM...'])
        !!}
    </div>
</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('ncm.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line me-1"></i> Salvar NCM
        </button>
    </div>
</div>