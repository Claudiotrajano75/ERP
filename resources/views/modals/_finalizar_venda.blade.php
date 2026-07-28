<div class="modal fade modal-pdv" id="finalizar_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 420px;">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-bottom: none; padding: 18px 20px;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: #fff; font-size: 15px; font-weight: 700;">
                    <i class="ri-checkbox-circle-line me-2" style="color: #28a745;"></i>Finalizar Venda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: rgba(255,255,255,0.15) url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e&quot;) center/1em auto no-repeat;"></button>
            </div> 
            <div class="modal-body" style="padding: 24px 20px;">
                <div class="row g-3">
                    <div class="col-12">
                        <button type="button" class="pdv-finish-option" id="btn_nao_fiscal" style="width: 100%;">
                            <span class="pdv-finish-option-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="ri-file-text-line"></i>
                            </span>
                            <span class="pdv-finish-option-content">
                                <span class="pdv-finish-option-title">Cupom Não Fiscal</span>
                                <span class="pdv-finish-option-desc">Finalizar venda sem emissão de NFCe</span>
                            </span>
                            <i class="ri-arrow-right-s-line pdv-finish-option-arrow"></i>
                        </button>
                    </div>
                    @can('nfce_create')
                    <div class="col-12">
                        <button type="button" class="pdv-finish-option" data-bs-toggle="modal" data-bs-target="#cpf_nota" style="width: 100%;">
                            <span class="pdv-finish-option-icon" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                                <i class="ri-file-list-3-line"></i>
                            </span>
                            <span class="pdv-finish-option-content">
                                <span class="pdv-finish-option-title">Cupom Fiscal (NFCe)</span>
                                <span class="pdv-finish-option-desc">Emitir NF-e para o cliente</span>
                            </span>
                            <i class="ri-arrow-right-s-line pdv-finish-option-arrow"></i>
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fc; padding: 12px 20px; justify-content: center;">
                <span style="font-size: 11px; color: #adb5bd;">
                    <i class="ri-keyboard-line me-1"></i> Atalho: <strong>F5</strong> para finalizar
                </span>
            </div>
        </div> 
    </div> 
</div> 
@include('modals._cpf_nota', ['not_submit' => true])
