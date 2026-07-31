<div class="row g-3">
    <div class="col-md-4">
        {!!Form::text('valor', 'Valor')->attrs(['class' => 'moeda form-control'])->required()
        ->value(__moeda($item->valor))!!}
    </div>
    <div class="col-md-4">
        {!!Form::select('status_pagamento', 'Status de pagamento', \App\Models\FinanceiroPlano::statusDePagamentos())
        ->required()
        ->attrs(['class' => 'select2 form-select'])
        ->value($item->status_pagamento)!!}
    </div>
    <div class="col-md-4">
        {!!Form::select('tipo_pagamento', 'Tipo de pagamento', \App\Models\Plano::formasPagamento())
        ->required()
        ->attrs(['class' => 'select2 form-select'])!!}
    </div>
    <div class="col-12 text-end mt-4">
        <hr class="mb-3">
        <button type="submit" class="btn btn-success" id="btn-store">
            <i class="ri-save-line"></i> Salvar
        </button>
    </div>
</div>
