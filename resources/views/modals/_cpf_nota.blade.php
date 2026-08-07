<div class="modal fade modal-pdv modal-pdv-modern" id="cpf_nota" tabindex="-1" role="dialog" aria-labelledby="cpfNotaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="cpfNotaLabel">
                        <i class="ri-id-card-line"></i> CPF na Nota?
                    </h5>
                    <p class="modulo-header-subtitle">Informe os dados para emissão da NFCe</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-id-card-line me-1"></i>CPF/CNPJ</label>
                        {!! Form::tel('cliente_cpf_cnpj', '')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-user-3-line me-1"></i>Nome</label>
                        {!! Form::text('cliente_nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Opcional']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" id="btn_fiscal" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="ri-check-double-line me-1"></i> Emitir
                </button>
            </div>
        </div>
    </div>
</div>
