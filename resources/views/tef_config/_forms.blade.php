<div class="row g-3 text-dark">
    <!-- Seção 1 -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-bank-card-line text-primary me-2 align-middle fs-18"></i>
            1. Dados de Integração
        </h5>

        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('cnpj', 'CNPJ')->attrs(['class' => 'cnpj'])
    ->required()
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::text('pdv', 'PDV')->required()
                !!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::text('token', 'Token')
    ->required()
                !!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::select('usuario_id', 'Usuário', ['' => 'Selecione'] + $usuarios->pluck('name', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::select('status', 'Status', [1 => 'Ativo', 0 => 'Desativado'])
    ->attrs(['class' => 'form-select'])->required()
                !!}
            </div>
        </div>
    </div>
</div>

<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('tef-config.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>