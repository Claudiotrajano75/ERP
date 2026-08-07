<div class="modal fade modal-pdv modal-pdv-modern" id="observacao_pdv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="observacaoPdvLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="observacaoPdvLabel">
                        <i class="ri-message-3-line"></i> Observação para Venda
                    </h5>
                    <p class="modulo-header-subtitle">Texto exibido no comprovante e na NFCe</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-file-edit-line me-1"></i>Observação</label>
                        {!!Form::textarea('observacao', '')->attrs(['class' => 'form-control pdv-obs-textarea', 'rows' => 5, 'placeholder' => 'Digite aqui a observação da venda...'])!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="ri-check-line me-1"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>
