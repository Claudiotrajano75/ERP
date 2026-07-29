<div class="row g-2">
    <div class="col-md-3">
        {!!Form::text('modelo', 'Modelo')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-4">
        <!-- Manter sem form-select porque usa select2 -->
        {!!Form::select('acomodacao_id', 'Acomodação', ['' => 'Selecione'] + $acomodacoes->pluck('info', 'id')->all())
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Frigobar
            </button>
        </div>
    </div>
</div>