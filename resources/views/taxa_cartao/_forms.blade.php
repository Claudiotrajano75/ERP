<div class="row g-3 text-dark">
    <div class="col-md-4">
        {!!Form::select('tipo_pagamento', 'Tipo de Pagamento', App\Models\TaxaPagamento::tiposPagamento())
        ->required()
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    
    <div class="col-md-4">
        {!!Form::tel('taxa', 'Taxa (%)')
        ->attrs(['class' => 'moeda form-control'])
        ->value(isset($item) ? __moeda($item->taxa) : '')
        ->required()
        !!}
    </div>
    
    <div class="col-md-4">
        {!!Form::select('bandeira_cartao', 'Bandeira do Cartão', ['' => 'Selecione'] + App\Models\TaxaPagamento::bandeiras())
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('taxa-cartao.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Taxa
            </button>
        </div>
    </div>
</div>
