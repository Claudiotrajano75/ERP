<div class="row g-3 text-dark">
    
    <!-- Seção 1: Fornecedores -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-truck-line text-primary me-2 align-middle fs-18"></i> 1. Distribuição de Fornecedores</h5>
        <div class="row g-3">
            @if(!isset($item))
            <div class="col-12">
                <label class="form-label fw-semibold text-dark">Fornecedores para Envio</label>
                <select required class="select2 form-control select2-multiple form-select" data-toggle="select2" name="fornecedor_id[]" multiple="multiple">
                    @foreach($fornecedores as $f)
                    <option value="{{ $f->id }}">{{ $f->info }}</option>
                    @endforeach
                </select>
                <div class="form-text text-danger fs-11 mt-1">* Você pode selecionar múltiplos distribuidores para esta cotação em lote.</div>
            </div>
            @else
            <div class="col-12">
                <span class="fs-12 text-muted text-uppercase fw-semibold d-block">Distribuidor Associado</span>
                <h4 class="text-dark fw-bold mb-0">{{ $item->fornecedor->info }}</h4>
            </div>
            @endif
        </div>
    </div>

    <!-- Seção 2: Itens da Cotação -->
    <div class="col-12 mt-4">
        <div class="card border rounded shadow-sm">
            <div class="card-header bg-light border-bottom py-2">
                <h5 class="card-title text-dark mb-0 fs-13"><i class="ri-box-3-line me-1 align-middle"></i> Grade de Produtos Solicitados</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table table-centered table-dynamic table-produtos mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produto / Item do Catálogo</th>
                            <th style="width: 250px;">Quantidade Requerida</th>
                            <th style="width: 60px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="dynamic-form">
                            @isset($item)
                            @foreach($item->itens as $l)
                            <td>
                                <select required class="form-control select2 produto_id form-select" name="produto_id[]" id="inp-produto_id">
                                    <option value="{{ $l->produto_id }}">{{ $l->produto->nome }}</option>
                                </select>
                            </td>
                            <td>
                                <input required class="form-control qtd" type="tel" value="{{ __moeda($l->quantidade) }}" name="quantidade[]" id="inp-quantidade">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-remove-tr" title="Remover Produto">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                            @endforeach
                            @else
                            <td>
                                <select required class="form-control select2 produto_id form-select" name="produto_id[]" id="inp-produto_id">
                                </select>
                            </td>
                            <td>
                                <input required class="form-control qtd text-center" type="tel" name="quantidade[]" id="inp-quantidade" placeholder="0,00">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-remove-tr" title="Remover Produto">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light border-top py-2">
                <button type="button" class="btn btn-sm btn-dark btn-add-tr-item">
                    <i class="ri-add-line align-middle me-1"></i> Adicionar Produto
                </button>
            </div>
        </div>
    </div>

    <!-- Seção 3: Parâmetros e Status -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-settings-line text-primary me-2 align-middle fs-18"></i> 3. Parametrização & Observações</h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('observacao', 'Observação Interna')->attrs(['class' => 'form-control'])!!}
            </div>
            
            <div class="col-md-3 col-6">
                {!!Form::select('estado', 'Estado / Status Cotação', [
                    'nova' => 'Nova',
                    'rejeitada' => 'Rejeitada',
                    'respondida' => 'Respondida',
                    'aprovada' => 'Aprovada'
                ])->attrs(['class' => 'form-select'])->required()!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::select('status', 'Registro Ativo', ['1' => 'Sim', '0' => 'Não'])->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('cotacoes.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4 btn-salvar" id="btn-save-cotacao">
                <i class="ri-save-line align-middle me-1"></i> Salvar Cotação
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript" src="/js/cotacao.js"></script>
@endsection