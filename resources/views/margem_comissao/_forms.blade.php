<div class="row g-3 text-dark">
    
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-percent-line text-primary"></i> Parâmetros da Faixa de Rentabilidade
            </h5>
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label required fw-semibold">Margem de Lucro Mínima da Venda (%)</label>
                    {!!Form::text('margem', '')->attrs(['class' => 'form-control percentual fs-14 fw-bold'])->placeholder('Ex: 20,00')->required()!!}
                    <div class="form-text text-muted fs-11 mt-1">Percentual mínimo de lucro sobre a venda para atingir esta faixa.</div>
                </div>
                
                <div class="col-md-6 col-12">
                    <label class="form-label required fw-semibold">Percentual de Comissão Ganho (%)</label>
                    {!!Form::text('percentual', '')->attrs(['class' => 'form-control percentual fs-14 fw-bold text-success'])->placeholder('Ex: 2,50')->required()!!}
                    <div class="form-text text-muted fs-11 mt-1">Percentual de comissão que o vendedor receberá ao atingir a margem.</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('comissao-margem.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
            <i class="ri-save-line me-1"></i> {{ isset($item) ? 'Salvar Alterações' : 'Cadastrar Faixa de Comissão' }}
        </button>
    </div>

</div>
