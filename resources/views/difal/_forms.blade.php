<div class="row g-3 text-dark">
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-map-2-line text-primary"></i>
                Parâmetros e Alíquotas DIFAL
            </h5>
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    {!!Form::select('uf', 'UF de Destino', \App\Models\Cidade::estados())
                    ->required()
                    ->attrs(['class' => 'form-select select2'])
                    !!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('cfop', 'CFOP')
                    ->required()
                    ->attrs(['class' => 'form-control cfop', 'placeholder' => '6102'])
                    !!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('pICMSUFDest', '% ICMS UF Destino')
                    ->required()
                    ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                    !!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('pICMSInter', '% ICMS Interno')
                    ->required()
                    ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                    !!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('pICMSInterPart', '% ICMS Interestadual')
                    ->required()
                    ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                    !!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('pFCPUFDest', '% Fundo Combate à Pobreza (FCP)')
                    ->required()
                    ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                    !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-end gap-2 pt-2">
    <a href="{{ route('difal.index') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line me-1"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Salvar Regra
    </button>
</div>