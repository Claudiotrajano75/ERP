<div class="row g-3">
    <!-- Bloco Geral -->
    <div class="col-md-12">
        <div class="modulo-section-card">
            <div class="card-header">
                <h4><i class="ri-file-list-3-line me-2"></i>Geral / Identificação</h4>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6 mb-2">
                        {!!Form::text('codigo_conta_analitica', 'Código conta analítica')
                        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 12345'])
                        !!}
                    </div>

                    <div class="col-md-6 mb-2">
                        {!!Form::text('codigo_receita', 'Código da receita')
                        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 6789'])
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bloco K -->
    <div class="col-md-12 mt-3">
        <div class="modulo-section-card">
            <div class="card-header">
                <h4><i class="ri-box-3-line me-2"></i>Bloco K (Produção e Estoque)</h4>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3 mb-2">
                        {!!Form::select('gerar_bloco_k', 'Gerar bloco K', [0 => 'Não', 1 => 'Sim'])
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>

                    <div class="col-md-9 mb-2">
                        {!!Form::select('layout_bloco_k', 'Layout bloco K', [0 => 'Leiaute simplificado', 1 => 'Leiaute completo', 2 => 'Leiaute restrito aos saldos de estoque'])
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Obrigações (E116) -->
    <div class="col-md-12 mt-3">
        <div class="modulo-section-card">
            <div class="card-header">
                <h4><i class="ri-bill-line me-2"></i>Obrigação E116</h4>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-9 mb-2">
                        {!!Form::select('codigo_obrigacao', 'Código de obrigação', App\Models\SpedConfig::codigosDeObrigacao())
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>

                    <div class="col-md-3 mb-2">
                        {!!Form::tel('data_vencimento', 'Dia de vencimento')
                        ->attrs(['class' => 'form-control', 'data-mask' => '00', 'placeholder' => 'Dia'])
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 text-end mt-4">
        <hr class="mt-0 mb-3 border-secondary border-opacity-25">
        <button type="submit" class="btn btn-success px-5 fw-semibold shadow-sm" id="btn-store">
            <i class="ri-save-3-line me-1"></i> Salvar Configurações
        </button>
    </div>
</div>
