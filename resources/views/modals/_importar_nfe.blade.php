<div class="modal fade" id="modal-importar_nfe" aria-modal="true" role="dialog" style="overflow:scroll;" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            
            {{-- Header Modernizado --}}
            <div class="modal-header bg-dark text-white py-3" style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 100%) !important;">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                    <i class="ri-file-search-line"></i>
                    Selecionar Documentos NFe para Importação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Filtro Glass dentro do Modal --}}
                <div class="modulo-glass-filter p-3 mb-4" style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.08) !important; border-radius: 12px;">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            {!! Form::date('start_date', 'Data Inicial')->attrs(['class' => 'form-control ignore']) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::date('end_date', 'Data Final')->attrs(['class' => 'form-control ignore']) !!}
                        </div>
                        <div class="col-md-4 col-12">
                            <button class="btn btn-primary btn-filtro w-100 fw-bold" style="height: 38px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="ri-search-line me-1"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabela Premium --}}
                <div class="modulo-table-wrap" style="border: 1px solid #eef0f5; border-radius: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark tbl-vendas">
                            <thead class="table-light">
                                <tr>
                                    <th width="40"></th>
                                    <th>Data</th>
                                    <th>Razão Social</th>
                                    <th>Valor Total</th>
                                    <th>Chave</th>
                                    <th>Nº NFe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center text-muted py-4" colspan="6">
                                        <div class="d-flex flex-column align-items-center py-2">
                                            <i class="ri-filter-line fs-24 text-muted mb-2"></i>
                                            <span>Utilize o filtro de datas acima para carregar os documentos.</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Modernizado --}}
            <div class="modal-footer bg-light border-top-0 py-3">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Fechar</button>
                <button id="btn-importar" type="button" class="btn btn-success px-5 fw-bold btn-salvar-modulo" style="box-shadow: 0 4px 14px rgba(40,167,69,0.25);">
                    <i class="ri-file-download-line align-middle me-1"></i> Importar Selecionadas
                </button>
            </div>
        </div>
    </div>
</div>
