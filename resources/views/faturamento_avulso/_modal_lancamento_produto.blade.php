<div class="modal fade" id="modal_lancamento_produto" tabindex="-1" aria-labelledby="modalLancamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <!-- Cabeçalho do Modal -->
            <div class="modal-header bg-dark py-3 px-4 text-white d-flex align-items-center justify-content-between">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="modalLancamentoLabel">
                    <i class="ri-add-box-line text-info fs-20"></i>
                    Lançar Produto / Mercadoria na Nota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Corpo do Modal -->
            <div class="modal-body p-4 text-dark" style="max-height: 80vh; overflow-y: auto; background-color: #f8f9fa;">
                <div class="row g-3">
                    
                    <!-- Bloco 1: Dados Gerais do Produto -->
                    <div class="col-12 bg-white p-3 rounded-3 border mb-2">
                        <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary">
                            <i class="ri-information-line me-1"></i> Dados Básicos
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label required">Buscar Produto</label>
                                <select id="modal-produto_id" class="form-select select2-modal"></select>
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label required">CFOP</label>
                                <input type="tel" id="modal-cfop" class="form-control cfop" required placeholder="Ex: 5102">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label required">NCM</label>
                                <input type="tel" id="modal-ncm" class="form-control ncm" required placeholder="Ex: 12345678">
                            </div>
                            
                            <div class="col-md-3 col-6">
                                <label class="form-label required">Quantidade</label>
                                <input type="text" id="modal-quantidade" class="form-control money" required value="1,00">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label required">Preço Unitário</label>
                                <input type="text" id="modal-valor_unitario" class="form-control money" required value="0,00">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label">Subtotal</label>
                                <input type="text" id="modal-sub_total" class="form-control bg-light" readonly value="0,00">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label">Cód. Benefício Fiscal</label>
                                <input type="text" id="modal-codigo_beneficio_fiscal" class="form-control" placeholder="Ex: PR800001">
                            </div>
                        </div>
                    </div>

                    <!-- Bloco 2: Detalhamento de Tributos (Abas Internas no Modal para Organização) -->
                    <div class="col-12 bg-white p-3 rounded-3 border">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                            <h6 class="fw-bold mb-0 text-secondary">
                                <i class="ri-percent-line me-1"></i> Detalhamento Fiscal (Impostos)
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-carregar-tributos-padrao">
                                <i class="ri-refresh-line align-middle"></i> Restaurar Alíquotas do Produto
                            </button>
                        </div>
                        
                        <!-- Nav tabs impostos -->
                        <ul class="nav nav-pills nav-pills-custom mb-3 gap-2" id="taxTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active btn-sm" id="icms-tab" data-bs-toggle="tab" data-bs-target="#tab-icms" type="button" role="tab">ICMS / CSOSN</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-sm" id="pis-tab" data-bs-toggle="tab" data-bs-target="#tab-pis" type="button" role="tab">PIS / COFINS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-sm" id="ipi-tab" data-bs-toggle="tab" data-bs-target="#tab-ipi" type="button" role="tab">IPI / Enquadramento</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content pt-2" id="taxTabContent">
                            <!-- Aba ICMS -->
                            <div class="tab-pane fade show active" id="tab-icms" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label">CST/CSOSN</label>
                                        <select id="modal-cst_csosn" class="form-select select2-modal">
                                            @foreach(App\Models\Produto::listaCSTCSOSN() as $key => $c)
                                                <option value="{{ $key }}">{{ $key }} - {{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Alíquota ICMS (%)</label>
                                        <input type="text" id="modal-perc_icms" class="form-control money" value="0,00">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Redução Base Cálculo (%)</label>
                                        <input type="text" id="modal-perc_red_bc" class="form-control money" value="0,00">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Aba PIS / COFINS -->
                            <div class="tab-pane fade" id="tab-pis" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label">CST PIS</label>
                                        <select id="modal-cst_pis" class="form-select select2-modal">
                                            @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option value="{{ $key }}">{{ $key }} - {{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label">CST COFINS</label>
                                        <select id="modal-cst_cofins" class="form-select select2-modal">
                                            @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option value="{{ $key }}">{{ $key }} - {{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Alíquota PIS (%)</label>
                                        <input type="text" id="modal-perc_pis" class="form-control money" value="0,00">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Alíquota COFINS (%)</label>
                                        <input type="text" id="modal-perc_cofins" class="form-control money" value="0,00">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Alíquota IBS (%)</label>
                                        <input type="text" id="modal-perc_ibs" class="form-control money" value="0,00">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Alíquota CBS (%)</label>
                                        <input type="text" id="modal-perc_cbs" class="form-control money" value="0,00">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Aba IPI -->
                            <div class="tab-pane fade" id="tab-ipi" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label">CST IPI</label>
                                        <select id="modal-cst_ipi" class="form-select select2-modal">
                                            @foreach(App\Models\Produto::listaCST_IPI() as $key => $c)
                                                <option value="{{ $key }}">{{ $key }} - {{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Alíquota IPI (%)</label>
                                        <input type="text" id="modal-perc_ipi" class="form-control money" value="0,00">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label">Cód. Enquadramento IPI</label>
                                        <input type="text" id="modal-cEnq" class="form-control" placeholder="Ex: 999">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações Adicionais do Item -->
                    <div class="col-12 bg-white p-3 rounded-3 border mt-3">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Informações Adicionais do Produto (Impresso no DANFE)</label>
                                <textarea id="modal-infAdProd" class="form-control" rows="2" placeholder="Ex: Detalhamento de lote, série, etc."></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Rodapé do Modal -->
            <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line align-middle"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success px-4" id="btn-gravar-produto-modal">
                    <i class="ri-check-line align-middle"></i> Confirmar Lançamento
                </button>
            </div>
            
        </div>
    </div>
</div>

<style>
/* Pills customizados para o modal de impostos */
.nav-pills-custom .nav-link {
    background-color: #f1f3f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 16px;
    transition: all 0.2s ease;
}
.nav-pills-custom .nav-link.active,
.nav-pills-custom .nav-link:hover {
    background-color: #302b63 !important;
    color: #ffffff !important;
    border-color: #302b63;
}
</style>
