<div class="row g-3 text-dark">
    
    <!-- Seção 1: Fornecedores -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-truck-line text-primary"></i> 1. Distribuição de Fornecedores
            </h5>
            <div class="row g-3">
                @if(!isset($item))
                <div class="col-12">
                    <label class="form-label required fw-semibold">Fornecedores para Envio</label>
                    <select required class="select2 form-control select2-multiple form-select" data-toggle="select2" name="fornecedor_id[]" multiple="multiple">
                        @foreach($fornecedores as $f)
                        <option value="{{ $f->id }}">{{ $f->info }}</option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted fs-11 mt-1">Você pode selecionar múltiplos fornecedores para disparar esta cotação em lote.</div>
                </div>
                @else
                <div class="col-12">
                    <span class="fs-11 text-muted text-uppercase fw-bold d-block">Distribuidor Associado</span>
                    <h4 class="text-dark fw-bold mb-0 mt-1 fs-15">{{ $item->fornecedor->info }}</h4>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Seção 2: Itens da Cotação -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3 flex-wrap gap-2">
                <h5 class="text-dark mb-0 d-flex align-items-center gap-2 fs-14 fw-bold">
                    <i class="ri-box-3-line text-primary"></i> 2. Grade de Produtos Solicitados
                </h5>
                <button type="button" class="dash-btn dash-btn-primary btn-add-tr-item" style="font-size: 12px; padding: 6px 14px;">
                    <i class="ri-add-line me-1"></i> Adicionar Produto
                </button>
            </div>
            
            <div class="tb-wrap">
                <div class="table-responsive">
                    <table class="table table-centered table-dynamic table-produtos mb-0 align-middle table-hover text-dark">
                        <thead>
                            <tr>
                                <th>Produto / Item do Catálogo</th>
                                <th style="width: 250px;">Quantidade Requerida</th>
                                <th style="width: 60px;" class="text-center">Ação</th>
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
                                    <input required class="form-control qtd text-center" type="tel" value="{{ __moeda($l->quantidade) }}" name="quantidade[]" id="inp-quantidade">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="act-btn act-del btn-remove-tr" title="Remover Produto">
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
                                <td class="text-center">
                                    <button type="button" class="act-btn act-del btn-remove-tr" title="Remover Produto">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-3">
                <button type="button" class="dash-btn dash-btn-light btn-add-tr-item">
                    <i class="ri-add-line me-1"></i> Adicionar Mais um Item
                </button>
            </div>
        </div>
    </div>

    <!-- Seção 3: Parâmetros e Status -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-settings-line text-primary"></i> 3. Parametrização & Observações
            </h5>
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label fw-semibold">Observação Interna</label>
                    {!!Form::text('observacao', '')->placeholder('Observações adicionais para este registro...')->attrs(['class' => 'form-control'])!!}
                </div>
                
                <div class="col-md-3 col-6">
                    <label class="form-label required fw-semibold">Estado / Status Cotação</label>
                    {!!Form::select('estado', '', [
                        'nova' => 'Nova',
                        'rejeitada' => 'Rejeitada',
                        'respondida' => 'Respondida',
                        'aprovada' => 'Aprovada'
                    ])->attrs(['class' => 'form-select'])->required()!!}
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label required fw-semibold">Registro Ativo</label>
                    {!!Form::select('status', '', ['1' => 'Sim', '0' => 'Não'])->attrs(['class' => 'form-select'])!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('cotacoes.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary px-5 btn-salvar" id="btn-save-cotacao">
            <i class="ri-save-line me-1"></i> {{ isset($item) ? 'Salvar Alterações' : 'Criar Cotação' }}
        </button>
    </div>

</div>

@section('js')
<script type="text/javascript" src="/js/cotacao.js"></script>
@endsection