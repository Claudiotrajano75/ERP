<div class="modal fade" id="modal_estoque_ajuste" tabindex="-1" aria-labelledby="modal_estoque_ajuste_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('estoque.ajuste') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_estoque_ajuste_label">
                        <i class="ri-swap-line me-1"></i> Entrada / Saída de Estoque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label mb-1">Tipo <span class="text-danger">*</span></label>
                            <select name="tipo" class="form-select" required>
                                <option value="entrada">Entrada (adicionar ao estoque)</option>
                                <option value="saida">Saída (remover do estoque)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label mb-1">Produto <span class="text-danger">*</span></label>
                            <select name="produto_id" id="estoque_ajuste_produto_id" class="form-select" required></select>
                        </div>

                        <div class="col-12">
                            <label class="form-label mb-1">Quantidade <span class="text-danger">*</span></label>
                            <input type="text" name="quantidade" class="form-control text-end fw-bold" required autocomplete="off" placeholder="0,000">
                        </div>

                        @if(__countLocalAtivo() > 1)
                        <div class="col-12">
                            <label class="form-label mb-1">Localização</label>
                            <select name="local_id" class="form-select">
                                <option value="">Selecione</option>
                                @foreach(__getLocaisAtivoUsuario() as $local)
                                <option value="{{ $local->id }}">{{ $local->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label mb-1">Observação</label>
                            <textarea name="observacao" class="form-control" rows="2" maxlength="255" placeholder="Ex.: perda, consumo interno, ajuste de inventário..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Lançar Movimentação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
