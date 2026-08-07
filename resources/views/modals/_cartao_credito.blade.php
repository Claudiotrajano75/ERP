<div class="modal fade modal-pdv modal-pdv-modern" id="cartao_credito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cartaoCreditoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="cartaoCreditoLabel">
                        <i class="ri-bank-card-line"></i> Dados do Cartão
                    </h5>
                    <p class="modulo-header-subtitle">Informações para a transação com cartão</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-credit-card-2-line me-1"></i>Bandeira</label>
                        {!! Form::select('bandeira_cartao', '', ["" => "Selecione"] + App\Models\Nfce::bandeiras())
                        ->attrs(['class' => 'form-select']) !!}
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="ri-key-2-line me-1"></i>Código de autorização</label>
                        {!! Form::tel('cAut_cartao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Opcional']) !!}
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="ri-building-2-line me-1"></i>CNPJ</label>
                        {!! Form::tel('cnpj_cartao', '')->attrs(['class' => 'form-control cnpj', 'placeholder' => 'Opcional']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary cliente-venda" data-bs-dismiss="modal">
                    <i class="ri-check-double-line me-1"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>
