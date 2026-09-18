<div class="modal fade modal-busca-imagens-lote" id="modal_busca_imagens_lote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalBuscaImagensLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <!-- Header Premium -->
            <div class="modal-header modulo-header-gradient p-3 text-white">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 10px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-image-search-fill fs-20 text-info"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-white" id="modalBuscaImagensLabel">
                            Busca Inteligente de Imagens em Lote
                        </h5>
                        <small class="text-white-50 fs-12">Localiza e baixa fotos oficiais de e-commerces e catálogos para seus produtos</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btn-close-modal-lote"></button>
            </div>

            <div class="modal-body p-4" style="background: #f8f9fc;">
                
                <!-- ═══ ETAPA 1: CONFIGURAÇÃO DO LOTE ═══ -->
                <div id="lote-step-config">
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="ri-settings-3-line text-primary"></i> Selecione o Escopo da Busca:
                            </h6>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded-3 bg-light h-100 cursor-pointer" style="border-color: #e2e8f0 !important;">
                                        <input class="form-check-input" type="radio" name="escopo_busca_lote" id="escopo_sem_imagem" value="sem_imagem" checked>
                                        <label class="form-check-label ms-2" for="escopo_sem_imagem">
                                            <strong class="d-block text-dark">Apenas Sem Imagem <span class="badge bg-success ms-1">Recomendado</span></strong>
                                            <span class="text-muted fs-12">Busca fotos somente para os produtos que ainda não possuem foto cadastrada.</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded-3 bg-light h-100 cursor-pointer" style="border-color: #e2e8f0 !important;">
                                        <input class="form-check-input" type="radio" name="escopo_busca_lote" id="escopo_todos" value="todos">
                                        <label class="form-check-label ms-2" for="escopo_todos">
                                            <strong class="d-block text-dark">Todos os Produtos</strong>
                                            <span class="text-muted fs-12">Varre todo o catálogo da empresa e tenta buscar imagem para todos.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-2 border-top">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="chk_substituir_existentes" value="1">
                                    <label class="form-check-label text-dark fs-13" for="chk_substituir_existentes">
                                        <strong>Substituir imagens existentes</strong> (caso o produto já possua foto, substitui pela nova imagem encontrada na web)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm py-2 px-3 fs-13 mb-0" style="border-radius: 10px;">
                        <i class="ri-information-line align-middle me-1"></i>
                        A busca é realizada através do nome comercial e código de barras do produto em tempo real, sem travar seu servidor e salvando diretamente nas fotos do ERP.
                    </div>
                </div>

                <!-- ═══ ETAPA 2: PROGRESSO EM TEMPO REAL ═══ -->
                <div id="lote-step-progress" style="display: none;">
                    
                    <!-- KPI Cards de Status -->
                    <div class="row g-2 mb-3">
                        <div class="col-3">
                            <div class="bg-white p-2 border rounded text-center shadow-sm">
                                <small class="text-muted text-uppercase fs-10 fw-bold d-block">Total</small>
                                <span class="fs-18 fw-bold text-dark" id="kpi-total">0</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-white p-2 border rounded text-center shadow-sm">
                                <small class="text-muted text-uppercase fs-10 fw-bold d-block">Processados</small>
                                <span class="fs-18 fw-bold text-primary" id="kpi-processados">0</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-white p-2 border rounded text-center shadow-sm">
                                <small class="text-muted text-uppercase fs-10 fw-bold d-block">Encontrados</small>
                                <span class="fs-18 fw-bold text-success" id="kpi-sucesso">0</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-white p-2 border rounded text-center shadow-sm">
                                <small class="text-muted text-uppercase fs-10 fw-bold d-block">Não Achados</small>
                                <span class="fs-18 fw-bold text-danger" id="kpi-falha">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de Progresso Animada -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fs-12 fw-bold text-dark" id="lbl-status-lote">Processando fila de produtos...</span>
                            <span class="fs-13 fw-bold text-primary" id="lbl-percent-lote">0%</span>
                        </div>
                        <div class="progress" style="height: 14px; border-radius: 8px; background: #e2e8f0;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient" 
                                 id="bar-progresso-lote" 
                                 role="progressbar" 
                                 style="width: 0%; background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%);">
                            </div>
                        </div>
                    </div>

                    <!-- Item Atual Sendo Processado -->
                    <div class="card border-0 shadow-sm mb-3 bg-white" style="border-radius: 10px;">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <div class="spinner-border spinner-border-sm text-primary flex-shrink-0" role="status" id="spinner-lote"></div>
                                <span class="fs-12 text-muted flex-shrink-0">Item atual:</span>
                                <strong class="fs-13 text-dark text-truncate" id="lbl-item-atual">-</strong>
                            </div>
                            <span class="badge bg-light text-dark border fs-11" id="lbl-contador-pos">0 / 0</span>
                        </div>
                    </div>

                    <!-- Galeria de Imagens Encontradas em Tempo Real -->
                    <div class="card border-0 shadow-sm bg-white" style="border-radius: 10px;">
                        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                            <span class="fs-12 fw-bold text-uppercase text-muted"><i class="ri-gallery-line me-1"></i> Imagens Baixadas ao Vivo:</span>
                            <span class="badge bg-success-subtle text-success fs-11 fw-bold" id="badge-live-count">0 novas fotos</span>
                        </div>
                        <div class="card-body p-2" style="max-height: 220px; overflow-y: auto;">
                            <div class="row g-2" id="grid-fotos-encontradas">
                                <div class="col-12 text-center text-muted py-4 fs-12" id="empty-fotos-msg">
                                    <i class="ri-image-line fs-24 d-block mb-1 text-black-50"></i>
                                    As fotos baixadas aparecerão aqui em tempo real...
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Footer com Ações -->
            <div class="modal-footer p-3 bg-white border-top d-flex justify-content-between">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal" id="btn-cancelar-lote">
                    <i class="ri-close-line me-1"></i> Fechar
                </button>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning btn-sm px-3" id="btn-pausar-lote" style="display: none;">
                        <i class="ri-pause-circle-line me-1"></i> Pausar
                    </button>
                    <button type="button" class="btn btn-success btn-sm px-3" id="btn-retomar-lote" style="display: none;">
                        <i class="ri-play-circle-line me-1"></i> Retomar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" id="btn-iniciar-lote">
                        <i class="ri-play-fill me-1"></i> Iniciar Busca Automática
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
