<style>
    .modulo-section-header { display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; color:#1f2937 !important; border-bottom:1px solid #eef0f6 !important; padding-bottom:10px; margin-bottom:16px; }
    .modulo-section-header i { color:#4f46e5 !important; }
    .form-label, label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#64748b; }
    .modulo-form-card .form-control, .modulo-form-card .form-select, .form-control, .form-select, select { border-radius:10px; border:1px solid #dcdce9 !important; font-size:13.5px; background:#fcfdfe; }
    .form-control:focus, .form-select:focus { border-color:#4f46e5 !important; box-shadow:0 0 0 3px rgba(79,70,229,.12) !important; background:#fff; }
</style>

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
            <a href="{{ route('marcas.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Marca
            </button>
        </div>
    </div>

</div>
