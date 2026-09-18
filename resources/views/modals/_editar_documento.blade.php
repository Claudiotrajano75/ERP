<div class="modal fade" id="modal-editar-documento" tabindex="-1" aria-labelledby="modal-editar-documento-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 650px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            
            {{-- Header --}}
            <div class="modal-header bg-white border-bottom py-3 px-4" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold text-dark fs-16" id="modal-editar-documento-title">Editar documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4 bg-white">
                <input type="hidden" id="edit-doc-index" value="">

                <div class="row g-3">
                    {{-- Tipo de Documento --}}
                    <div class="col-md-4">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">Tipo do documento *</label>
                        <select id="edit-doc-tipo" class="form-select" style="border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                            <option value="NFE">NFE</option>
                            <option value="CTE">CTE</option>
                        </select>
                    </div>

                    {{-- Local de Descarregamento (UF + Município) --}}
                    <div class="col-md-8">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">Local de descarregamento *</label>
                        <div class="d-flex gap-2">
                            <select id="edit-doc-uf" class="form-select" style="width: 90px; border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                                <option value="">UF</option>
                                @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                                    <option value="{{ $uf }}">{{ $uf }}</option>
                                @endforeach
                            </select>
                            <select id="edit-doc-municipio" class="form-select select2" style="border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                                <option value="">Selecione o Município</option>
                                @isset($cidades)
                                    @foreach($cidades as $c)
                                        <option value="{{ $c->id }}" data-uf="{{ $c->uf }}" data-nome="{{ $c->nome }}">{{ $c->nome }} ({{ $c->uf }})</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    {{-- Valor Total --}}
                    <div class="col-md-6">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">Valor total *</label>
                        <input type="text" id="edit-doc-valor" class="form-control moeda" placeholder="R$ 0,00" style="border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                    </div>

                    {{-- Peso (KG) --}}
                    <div class="col-md-6">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">Peso (KG) *</label>
                        <input type="text" id="edit-doc-peso" class="form-control peso" placeholder="0,00" style="border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                    </div>

                    {{-- Chave de Acesso --}}
                    <div class="col-12">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">Chave acesso NFe *</label>
                        <input type="text" id="edit-doc-chave" class="form-control" maxlength="44" placeholder="44 dígitos da chave de acesso" style="border-radius: 6px; border-color: #cbd5e1; height: 42px; font-size: 13.5px;">
                    </div>

                    {{-- Mais Informações --}}
                    <div class="col-12 mt-2">
                        <a href="javascript:void(0)" class="text-dark fw-semibold fs-13 text-decoration-none d-inline-flex align-items-center gap-1" id="btn-toggle-mais-info">
                            <i class="ri-add-line fs-16" id="icon-mais-info"></i> Mais informações
                        </a>

                        <div id="container-mais-info" class="mt-3 p-3 bg-light rounded" style="display: none; border: 1px solid #e2e8f0;">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label fs-12 fw-bold text-dark mb-1">Tipo Unidade Transporte</label>
                                    <select id="edit-doc-tp-und" class="form-select form-select-sm">
                                        <option value="1">1 - Rodoviário Tração</option>
                                        <option value="2">2 - Rodoviário Reboque</option>
                                        <option value="3">3 - Navio</option>
                                        <option value="4">4 - Balsa</option>
                                        <option value="5">5 - Aeronave</option>
                                        <option value="6">6 - Vagão</option>
                                        <option value="7">7 - Outros</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-12 fw-bold text-dark mb-1">Identificação Unid. Transporte</label>
                                    <input type="text" id="edit-doc-id-und" class="form-control form-select-sm" placeholder="Ex: Placa / Identificador">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-end gap-2" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-outline-secondary px-4 fw-medium" data-bs-dismiss="modal" style="border-color: #cbd5e1; color: #475569; border-radius: 6px;">
                    Cancelar
                </button>
                <button type="button" class="btn text-white px-4 fw-bold" id="btn-confirmar-edicao-doc" style="background: #ff5722; border: none; border-radius: 6px; box-shadow: 0 4px 14px rgba(255, 87, 34, 0.25);">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>
