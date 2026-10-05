<div class="row g-3">
    <!-- Banner com Informações da Empresa e Plano -->
    <div class="col-12 mb-2">
        <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="ri-building-line"></i>
                </div>
                <div>
                    <span class="text-muted fs-11 text-uppercase fw-bold">Empresa</span>
                    <h6 class="fw-bold text-dark mb-0 fs-14">
                        {{ $item->empresa ? ($item->empresa->nome ?? $item->empresa->razao_social ?? $item->empresa->info) : '--' }}
                    </h6>
                    <span class="text-muted fs-11">{{ $item->empresa ? ($item->empresa->cpf_cnpj ?? $item->empresa->cnpj ?? '') : '' }}</span>
                </div>
            </div>
            <div>
                <span class="text-muted fs-11 d-block text-end">Plano Vinculado</span>
                <span class="badge bg-primary-subtle text-primary fw-bold fs-12">
                    <i class="ri-vip-diamond-line me-1"></i>{{ $item->plano ? $item->plano->nome : 'Plano Removido' }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-money-dollar-circle-line text-primary me-1"></i> Valor</label>
        {!!Form::text('valor', '')->attrs(['class' => 'moeda form-control', 'placeholder' => '0,00'])->required()
        ->value(__moeda($item->valor))!!}
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-checkbox-circle-line text-primary me-1"></i> Status de Pagamento</label>
        {!!Form::select('status_pagamento', '', \App\Models\FinanceiroPlano::statusDePagamentos())
        ->required()
        ->attrs(['class' => 'select2 form-select'])
        ->value($item->status_pagamento)!!}
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-bank-card-line text-primary me-1"></i> Forma de Pagamento</label>
        {!!Form::select('tipo_pagamento', '', \App\Models\Plano::formasPagamento())
        ->required()
        ->attrs(['class' => 'select2 form-select'])
        ->value($item->tipo_pagamento)!!}
    </div>

    <div class="col-12 text-end mt-4">
        <hr class="mb-3 opacity-25">
        <a href="{{ route('financeiro-plano.index') }}" class="btn btn-outline-secondary me-2">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btn-store">
            <i class="ri-save-line me-1"></i> Salvar Alterações
        </button>
    </div>
</div>
