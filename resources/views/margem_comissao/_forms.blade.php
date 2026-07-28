<div class="row g-3 text-dark">
    
    <div class="col-md-3 col-6">
        {!!Form::text('margem', 'Margem de Lucro Referência (%)')->attrs(['class' => 'form-control percentual'])->placeholder('Ex: 20.00')->required()!!}
    </div>
    
    <div class="col-md-3 col-6">
        {!!Form::text('percentual', 'Percentual de Comissão (%)')->attrs(['class' => 'form-control percentual'])->placeholder('Ex: 2.50')->required()!!}
    </div>
    
    <!-- Rodapé de Envio -->
    <div class="col-12">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('comissao-margem.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Margem
                </button>
            </div>
        </div>
    </div>

</div>
