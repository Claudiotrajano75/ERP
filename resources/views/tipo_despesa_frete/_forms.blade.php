<div class="row g-3 text-dark">
    <div class="col-md-4 col-12">
        {!!Form::text('nome', 'Nome')->required()!!}
    </div>

    <div class="col-md-3 col-6">
        {!!Form::select('status', 'Ativo', ['1' => 'Sim', '0' => 'Não'])
        ->attrs(['class' => 'form-select'])->required()!!}
    </div>
</div>

<!-- Botões de Ação -->
<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('tipo-despesa-frete.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
