<div class="row g-3 text-dark">
    <!-- Seção: Informações do Adicional -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
            1. Informações do Adicional
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('nome', 'Nome do Adicional')->required()
                ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Bacon extra'])
                !!}
            </div>

            @if(__isInternacionalizar(Auth::user()->empresa))
            <div class="col-md-3 col-12">
                {!!Form::text('nome_en', 'Nome (inglês)')
                ->attrs(['class' => 'form-control', 'placeholder' => 'Extra bacon'])
                !!}
            </div>
            <div class="col-md-3 col-12">
                {!!Form::text('nome_es', 'Nome (espanhol)')
                ->attrs(['class' => 'form-control', 'placeholder' => 'Tocino extra'])
                !!}
            </div>
            @endif

            <div class="col-md-2 col-6">
                {!!Form::tel('valor', 'Valor')
                ->required()
                ->value(isset($item) ? __moeda($item->valor) : '')
                ->attrs(['class' => 'moeda form-control', 'placeholder' => 'R$ 0,00'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::select('status', 'Ativo', ['1' => 'Sim', '0' => 'Não'])
                ->attrs(['class' => 'form-select'])->required()
                !!}
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="col-12">
        <div class="modulo-actions">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('adicionais.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>
