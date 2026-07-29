<div class="row g-2">
    <div class="col-md-3">
        {!!Form::text('nome', 'Nome')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('numero', 'Número')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('capacidade', 'Capacidade')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('valor_diaria', 'Valor da diária')->required()
        ->attrs(['class' => 'moeda form-control'])
        ->value(isset($item) ? __moeda($item->valor_diaria) : '')
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::text('estacionamento', 'Estacionamento')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('categoria_id', 'Categoria', ['' => 'Selecione uma categoria'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('status', 'Ativo', ['1' => 'Sim', '0' => 'Não'])
        ->attrs(['class' => 'form-select'])->required()
        !!}
    </div>

    <div class="col-md-12">
        {!!Form::textarea('descricao', 'Descrição')
        ->attrs(['rows' => '6', 'class' => 'form-control'])->required()
        !!}
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Acomodação
            </button>
        </div>
    </div>
</div>