<div class="row g-3 text-dark">

    <!-- ═══ Seção 1: Rota de Movimentação ═══ -->
    <div class="col-12">
        <h5 class="section-header">
            <i class="ri-map-pin-line"></i>
            1. Rota de Movimentação
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Local de Saída (Origem)</label>
                <select id="inp-local_saida_id" required class="select2 form-control" data-toggle="select2" name="local_saida_id" style="width: 100%;">
                    <option value="">Selecione</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Local de Entrada (Destino)</label>
                <select id="inp-local_entrada_id" required class="select2 form-control" data-toggle="select2" name="local_entrada_id" style="width: 100%;">
                    <option value="">Selecione</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-12">
                {!!Form::text('observacao', 'Observação Geral')->placeholder('Ex: Transferência de estoque para reposição de loja')->attrs(['class' => 'form-control'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ Seção 2: Produtos a Transferir ═══ -->
    <div class="col-12 mt-4">
        <h5 class="section-header">
            <i class="ri-shopping-basket-line"></i>
            2. Produtos a Transferir
        </h5>

        <div class="modulo-table-wrap border shadow-sm">
            <div class="card-body p-3" style="background: #fafbfe;">
                <!-- Wrapper que armazena a tabela dinâmica. Deve ser o irmão anterior direto da row do botão -->
                <div class="table-responsive">
                    <table class="table table-dynamic table-centered align-middle mb-0" style="background: #fff; border: 1px solid #eef0f5; border-radius: 8px;">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th style="width: 160px;">Quantidade</th>
                                <th>Observação do Item</th>
                                <th class="text-end" style="width: 80px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="dynamic-form">
                                <td>
                                    <!-- A classe produto_id e o ID inp-produto_id são necessários para o select2 via AJAX no script JS -->
                                    <select required class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id" style="width: 100%;">
                                    </select>
                                </td>
                                <td>
                                    <input type="tel" class="form-control quantidade text-end fw-bold" name="quantidade[]" required placeholder="0,00" style="color: #302b63;">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_item[]" placeholder="Opcional">
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-danger btn-sm btn-remove-tr" type="button" title="Remover Produto">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Row que abriga o botão de inclusão. Deve ser o irmão seguinte direto de .table-responsive -->
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-dark btn-sm btn-add-tr-prod px-3">
                            <i class="ri-add-line align-middle me-1"></i> Adicionar Produto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('transferencia-estoque.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Registrar Transferência
                </button>
            </div>
        </div>
    </div>

</div>
