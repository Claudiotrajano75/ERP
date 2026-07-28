<div class="row g-3 text-dark">

    <!-- ═══ SEÇÃO 1: DADOS DA ATRIBUIÇÃO ═══ -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
            1. Dados da Atribuição
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('name', 'Nome')
                ->required()
                ->attrs(['maxlength' => 15, 'class' => 'form-control'])
                !!}
                <div class="form-text text-muted fs-11 mt-1">Identificador único do papel (máx. 15 caracteres).</div>
            </div>
            <div class="col-md-5 col-12">
                {!!Form::text('description', 'Descrição')
                ->required()
                ->attrs(['maxlength' => 40, 'class' => 'form-control'])
                !!}
                <div class="form-text text-muted fs-11 mt-1">Nome legível para exibição (máx. 40 caracteres).</div>
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: PERMISSÕES ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-lock-line text-primary me-2 align-middle fs-18"></i>
            2. Permissões do Papel
        </h5>
        <div class="row g-3">
            <div class="col-12">
                {!!Form::select('permissions[]', 'Permissões', $permissions->pluck('description', 'id')->all())
                ->attrs(['class' => 'multi-select form-control'])
                ->multiple()
                ->value(isset($item) ? $item->permissions->pluck('id')->all() : [])
                ->required()!!}
                <div class="form-text text-muted fs-11 mt-1">Selecione as permissões que este papel terá no sistema.</div>
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn {{ $formType === 'edit' ? 'btn-primary' : 'btn-success' }} px-5" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i>
                {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar' }}
            </button>
        </div>
    </div>

</div>
