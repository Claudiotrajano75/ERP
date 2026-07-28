<div class="row g-3">

    <div class="col-md-3 col-12">
        {!!Form::text('cnpj', 'CNPJ')->attrs(['class' => 'cnpj'])
        ->required()
        !!}
    </div>

    <div class="col-md-2 col-6">
        {!!Form::text('pdv', 'PDV')->required()
        !!}
    </div>
    <div class="col-md-4 col-12">
        {!!Form::text('token', 'Token')
        ->required()
        !!}
    </div>

    <div class="col-md-3 col-12">
        {!!Form::select('usuario_id', 'Usuário', ['' => 'Selecione'] + $usuarios->pluck('name', 'id')->all())->attrs(['class' => 'select2'])->required()
        !!}
    </div>

    <div class="col-md-2 col-6">
        {!!Form::select('status', 'Status', [1 => 'Ativo', 0 => 'Desativado'])
        ->attrs(['class' => 'form-select'])->required()
        !!}
    </div>

    <hr class="mt-4">
    <div class="col-12" style="text-align: right;">
        <button type="submit" class="btn btn-success px-5" id="btn-store">
            <i class="ri-save-line me-1"></i> Salvar
        </button>
    </div>
</div>
