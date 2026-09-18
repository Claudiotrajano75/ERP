{{-- Modal Alterar Local de Carregamento --}}
<div class="modal fade" id="modal-editar-carregamento" tabindex="-1" aria-labelledby="modal-editar-carregamento-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            
            <div class="modal-header bg-white border-bottom py-3 px-4" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold text-dark fs-16" id="modal-editar-carregamento-title">Alterar local de carregamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">UF *</label>
                        <select id="edit-carregamento-uf" class="form-select" style="border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                            <option value="">UF</option>
                            @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                                <option value="{{ $uf }}" {{ $uf === 'CE' ? 'selected' : '' }}>{{ $uf }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fs-13 fw-bold text-dark mb-1">Município de Carregamento *</label>
                        <select id="edit-carregamento-municipio" class="form-select select2" style="border-radius: 6px; border-color: #cbd5e1; height: 42px;">
                            <option value="">Selecione o Município</option>
                            @isset($cidades)
                                @foreach($cidades as $c)
                                    <option value="{{ $c->id }}" data-uf="{{ $c->uf }}" data-nome="{{ $c->nome }}">{{ $c->nome }} ({{ $c->uf }})</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-end gap-2" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-outline-secondary px-4 fw-medium" data-bs-dismiss="modal" style="border-color: #cbd5e1; color: #475569; border-radius: 6px;">
                    Cancelar
                </button>
                <button type="button" class="btn text-white px-4 fw-bold" id="btn-confirmar-carregamento" style="background: #ff5722; border: none; border-radius: 6px; box-shadow: 0 4px 14px rgba(255, 87, 34, 0.25);">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal Alterar Percurso --}}
<div class="modal fade" id="modal-editar-percurso" tabindex="-1" aria-labelledby="modal-editar-percurso-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            
            <div class="modal-header bg-white border-bottom py-3 px-4" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold text-dark fs-16" id="modal-editar-percurso-title">Definir percurso da viagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                <p class="text-secondary fs-13 mb-3">Selecione os estados por onde a viagem irá transitar entre a origem e o destino:</p>

                <div class="row g-2" id="grid-percurso-ufs">
                    @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                        <div class="col-2">
                            <div class="form-check p-0 d-flex align-items-center justify-content-center">
                                <input type="checkbox" class="btn-check chk-percurso-uf" id="percurso-uf-{{ $uf }}" value="{{ $uf }}" autocomplete="off">
                                <label class="btn btn-outline-secondary w-100 py-2 fs-12 fw-bold" for="percurso-uf-{{ $uf }}" style="border-radius: 6px;">{{ $uf }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 p-3 bg-light rounded" style="border: 1px solid #e2e8f0;">
                    <span class="fs-12 fw-bold text-dark d-block mb-1">Percurso selecionado:</span>
                    <span class="fs-13 text-secondary fw-semibold" id="preview-percurso-selecionado">Nenhum estado intermediário selecionado.</span>
                </div>
            </div>

            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-end gap-2" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-outline-secondary px-4 fw-medium" data-bs-dismiss="modal" style="border-color: #cbd5e1; color: #475569; border-radius: 6px;">
                    Cancelar
                </button>
                <button type="button" class="btn text-white px-4 fw-bold" id="btn-confirmar-percurso" style="background: #ff5722; border: none; border-radius: 6px; box-shadow: 0 4px 14px rgba(255, 87, 34, 0.25);">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>
