<div class="row g-3 text-dark">

    <!-- ═══ Informações da Unidade ═══ -->
    <div class="col-12">
        <h5 class="section-header">
            <i class="ri-information-line"></i>
            1. Informações da Unidade
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('nome', 'Nome da Unidade')->placeholder('Ex: Kilograma, Unidade, Metro')->required()->attrs(['class' => 'form-control'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('status', 'Status de Ativação', ['1' => 'Sim (Ativo)', '0' => 'Não (Inativo)'])->required()->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('unidades-medida.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Unidade
                </button>
            </div>
        </div>
    </div>

</div>
