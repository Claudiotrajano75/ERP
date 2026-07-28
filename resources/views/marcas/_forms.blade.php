<div class="row g-3 text-dark">

    <!-- ═══ Campo Único ═══ -->
    <div class="col-12">
        <h5 class="modulo-section-header" style="font-weight: 700; font-size: 14px; color: #2c2c44; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
            Informações da Marca
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('nome', 'Nome da Marca')->placeholder('Ex: Coca-cola, Nike, Nestlé')->required()->attrs(['class' => 'form-control'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Marca
            </button>
        </div>
    </div>

</div>
