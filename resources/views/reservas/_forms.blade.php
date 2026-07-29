<div class="row g-2 align-items-end">
    <div class="col-md-3">
        {!!Form::date('data_checkin', 'Data Check-in')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::date('data_checkout', 'Data Check-out')->required()
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('qtd_hospedes', 'Qtd. Hóspedes')
        ->attrs(['class' => 'form-control'])
        ->required()
        !!}
    </div>

    <div class="col-md-4">
        <button type="button" class="btn btn-dark w-100 fw-semibold btn-procura-acomodacoes" style="height: 40px;">
            <i class="ri-search-line me-1"></i> Procurar Acomodações
        </button>
    </div>

    <div class="col-12 mt-3">
        <div class="row acomodacoes-view">
            
        </div>
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Confirmar Reserva
            </button>
        </div>
    </div>
</div>
