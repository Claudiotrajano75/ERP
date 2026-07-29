<div class="row g-3 text-dark">
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-map-2-line text-primary me-2 align-middle fs-18"></i>
            Dados do DIFAL
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-6">
                {!!Form::select('uf', 'UF', \App\Models\Cidade::estados())->required()
    ->attrs(['class' => 'form-select select2'])
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('cfop', 'CFOP')->required()
    ->attrs(['class' => 'cfop'])
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('pICMSUFDest', '% ICMS UF Destino')->required()
    ->attrs(['class' => 'percentual'])
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('pICMSInter', '% ICMS Interno')->required()
    ->attrs(['class' => 'percentual'])
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('pICMSInterPart', '% ICMS Interestadual UF')->required()
    ->attrs(['class' => 'percentual'])
                !!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('pFCPUFDest', '% Fundo Combate à Pobreza')->required()
    ->attrs(['class' => 'percentual'])
                !!}
            </div>
        </div>
    </div>
</div>

<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('difal.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>