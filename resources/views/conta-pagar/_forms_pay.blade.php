<div class="row g-3">
    <div class="col-md-3 col-6">
        {!!Form::tel('valor_pago', 'Valor a Pagar')
        ->attrs(['class' => 'moeda form-control'])
        ->required()
        ->value(__moeda($item->valor_integral - $item->valor_pago))
        !!}
    </div>

    <div class="col-md-3 col-6">
        {!!Form::date('data_pagamento', 'Data do Pagamento')
        ->attrs(['class' => 'form-control'])
        ->required()
        ->value(date('Y-m-d'))
        !!}
    </div>

    <div class="col-md-3 col-6">
        {!!Form::select('tipo_pagamento', 'Tipo de Pagamento', ['' => 'Selecione'] + App\Models\ContaReceber::tiposPagamento())
        ->attrs(['class' => 'form-select'])
        ->required()
        ->value($item->tipo_pagamento)
        !!}
    </div>

    <div class="col-md-3 col-6 div-conta-empresa">
        {!!Form::select('conta_empresa_id', 'Conta Empresa')
        ->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>

    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('conta-pagar.index') }}" class="dash-btn dash-btn-light">Cancelar</a>
            <button type="submit" class="dash-btn dash-btn-primary">
                <i class="ri-check-line"></i> Confirmar Pagamento
            </button>
        </div>
    </div>
</div>
