<div class="row g-3 text-dark">

    <!-- ═══ SEÇÃO: INFORMAÇÕES BÁSICAS ═══ -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
            1. Informações Básicas
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('nome', 'Nome da Categoria')
                ->placeholder('Ex: Manutenção, Consultoria, Estética')
                ->required()
                ->attrs(['class' => 'form-control'])!!}
            </div>

            @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
            <div class="col-md-3 col-6">
                {!!Form::select('marketplace', 'Visível no Marketplace', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select'])!!}
                <div class="form-text text-muted fs-11 mt-1">
                    Marque "Sim" para exibir esta categoria no Delivery/Marketplace.
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('categoria-servico.index') }}" class="btn btn-outline-secondary px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn {{ $formType === 'edit' ? 'btn-primary' : 'btn-success' }} px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar Categoria' }}
            </button>
        </div>
    </div>

</div>
