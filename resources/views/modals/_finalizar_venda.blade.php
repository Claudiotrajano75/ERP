<div class="modal fade modal-pdv modal-pdv-modern" id="finalizar_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="finalizarVendaLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 420px;">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="finalizarVendaLabel">
                        <i class="ri-checkbox-circle-line"></i> Finalizar Venda
                    </h5>
                    <p class="modulo-header-subtitle">Escolha como deseja concluir</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                <span class="pdv-finish-option-desc">Emitir NFCe para o cliente</span>
                            </span>
                            <i class="ri-arrow-right-s-line pdv-finish-option-arrow"></i>
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <span style="font-size: 11px; color: #adb5bd;">
                    <i class="ri-keyboard-line me-1"></i> Atalho: <strong>F5</strong> para finalizar
                </span>
            </div>
        </div>
    </div>
</div>
@include('modals._cpf_nota', ['not_submit' => true])
