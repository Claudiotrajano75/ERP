<div class="row g-3 text-dark">
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-bank-card-line text-primary"></i>
                Dados de Integração com o TEF
            </h5>

            <div class="row g-3">
                <div class="col-md-4 col-12">
                    {!!Form::text('cnpj', 'CNPJ do Estabelecimento')
                    ->attrs(['class' => 'form-control cnpj', 'placeholder' => '00.000.000/0000-00'])
                    ->required()
                    !!}
                </div>

                <div class="col-md-4 col-6">
                    {!!Form::text('pdv', 'Identificação do PDV')
                    ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: CAIXA01, PDV01'])
                    ->required()
                    !!}
                </div>

                <div class="col-md-4 col-6">
                    {!!Form::select('status', 'Status do Terminal', [1 => 'Ativo', 0 => 'Desativado'])
                    ->attrs(['class' => 'form-select'])
                    ->required()
                    !!}
                </div>

                <div class="col-md-6 col-12">
                    {!!Form::select('usuario_id', 'Operador / Usuário Vinculado', ['' => 'Selecione o Usuário'] + $usuarios->pluck('name', 'id')->all())
                    ->attrs(['class' => 'select2 form-select'])
                    ->required()
                    !!}
                </div>

                <div class="col-md-6 col-12">
                    {!!Form::text('token', 'Token de Autenticação TEF')
                    ->attrs(['class' => 'form-control', 'placeholder' => 'Chave Token fornecida pela operadora'])
                    ->required()
                    !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-end gap-2 pt-2">
    <a href="{{ route('tef-config.index') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line me-1"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Salvar Configuração TEF
    </button>
</div>