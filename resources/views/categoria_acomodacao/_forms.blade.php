<div class="row g-2">
    <div class="col-md-3">
        {!!Form::text('nome', 'Nome')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Categoria
            </button>
        </div>
    </div>
</div>