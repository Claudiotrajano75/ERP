<div class="row g-3 text-dark">
    <div class="col-12">

        <!-- Linha 1: Fornecedor + Veículo -->
        <div class="row g-3 mb-3">
            <div class="col-md-5">
                <label class="fw-semibold fs-12 text-muted mb-1 required">Fornecedor</label>
                <div class="input-group flex-nowrap">
                    <select required id="inp-fornecedor_id" name="fornecedor_id" class="form-control">
                        @if(isset($item) && $item->fornecedor)
                        <option value="{{ $item->fornecedor_id }}">{{ $item->fornecedor->info }}</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                {!! Form::select('veiculo_id', 'Veículo', ['' => 'Selecione'] + $veiculos->pluck('info', 'id')->all())
                ->id('veiculo')
                ->attrs(['class' => 'select2'])
                ->required() !!}
            </div>
        </div>

        <!-- Abas: Dados Iniciais | Serviços | Produtos -->
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dados-iniciais" role="tab" aria-selected="true">
                    <i class="ri-settings-fill me-1"></i> Dados Iniciais
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#servicos" role="tab" aria-selected="false">
                    <i class="ri-tools-line me-1"></i> Serviços
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#produtos" role="tab" aria-selected="false">
                    <i class="ri-product-hunt-line me-1"></i> Produtos
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ═══ TAB: DADOS INICIAIS ═══ -->
            <div class="tab-pane fade show active" id="dados-iniciais" role="tabpanel">
                <div class="row g-3">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
                        1. Informações da Manutenção
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            {!!Form::tel('desconto', 'Desconto')
                            ->attrs(['class' => 'moeda'])
                            ->value(isset($item) ? __moeda($item->desconto) : '')
                            !!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::tel('acrescimo', 'Acréscimo')
                            ->attrs(['class' => 'moeda'])
                            ->value(isset($item) ? __moeda($item->acrescimo) : '')
                            !!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::select('estado', 'Estado', ['' => 'Selecione', 
                            'aguardando' => 'Aguardando', 'em_manutencao' => 'Em manutenção', 'finalizado' => 'Finalizado'])
                            ->attrs(['class' => 'form-select'])
                            ->required()
                            !!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-calendar-line text-primary me-2 align-middle fs-18"></i>
                        2. Datas
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            {!!Form::date('data_inicio', 'Data início')->required()!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::date('data_fim', 'Data fim')!!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-edit-line text-primary me-2 align-middle fs-18"></i>
                        3. Observação
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            {!!Form::text('observacao', 'Observação')!!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-money-dollar-circle-line text-primary me-2 align-middle fs-18"></i>
                        4. Valor Final
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            {!!Form::tel('total', 'Valor da manutenção')
                            ->attrs(['class' => 'moeda'])
                            ->value(isset($item) ? __moeda($item->total) : '')
                            ->readonly()
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TAB: SERVIÇOS ═══ -->
            <div class="tab-pane fade" id="servicos" role="tabpanel">
                <h5 class="text-dark border-bottom pb-2 mb-3">
                    <i class="ri-tools-line text-primary me-2 align-middle fs-18"></i>
                    Serviços Realizados
                </h5>
                <div class="table-responsive mt-3">
                    <table class="table table-dynamic table-servicos table-centered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Serviço</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>Subtotal</th>
                                <th>Observação</th>
                                <th class="text-center" style="width: 60px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($item) && sizeof($item->servicos) > 0)
                            @foreach($item->servicos as $d)
                            <tr class="dynamic-form">
                                <td style="width: 250px">
                                    <select class="servico_id form-control" name="servico_id[]">
                                        <option selected value="{{ $d->servico_id }}">
                                            {{ $d->servico->nome }}
                                        </option>
                                    </select>
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control qtd" name="quantidade_servico[]"
                                    value="{{ __moeda($d->quantidade) }}">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_servico[]" value="{{ __moeda($d->valor_unitario) }}">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_servico[]" value="{{ __moeda($d->sub_total) }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_servico[]" value="{{ $d->observacao }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="dynamic-form">
                                <td style="width: 250px">
                                    <select class="servico_id form-control" name="servico_id[]">
                                    </select>
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control qtd" name="quantidade_servico[]">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_servico[]">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_servico[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_servico[]">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="fw-bold">Total Serviços</td>
                                <td class="total-servico text-primary fw-bold" colspan="3">
                                    @isset($item)
                                    R$ {{ __moeda($item->servicos->sum('sub_total')) }}
                                    @else
                                    R$ 0,00
                                    @endisset
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-line-servico">
                        <i class="ri-add-line me-1"></i> Adicionar Serviço
                    </button>
                </div>
            </div>

            <!-- ═══ TAB: PRODUTOS ═══ -->
            <div class="tab-pane fade" id="produtos" role="tabpanel">
                <h5 class="text-dark border-bottom pb-2 mb-3">
                    <i class="ri-product-hunt-line text-primary me-2 align-middle fs-18"></i>
                    Produtos Utilizados
                </h5>
                <div class="table-responsive mt-3">
                    <table class="table table-dynamic table-produtos table-centered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>Subtotal</th>
                                <th>Observação</th>
                                <th class="text-center" style="width: 60px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($item) && sizeof($item->produtos) > 0)
                            @foreach($item->produtos as $d)
                            <tr class="dynamic-form">
                                <td style="width: 250px">
                                    <select class="produto_id form-control" name="produto_id[]">
                                        <option selected value="{{ $d->produto_id }}">
                                            {{ $d->produto->nome }}
                                        </option>
                                    </select>
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control qtd" name="quantidade_produto[]"
                                    value="{{ __moeda($d->quantidade) }}">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_produto[]" value="{{ __moeda($d->valor_unitario) }}">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_produto[]" value="{{ __moeda($d->sub_total) }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_produto[]" value="{{ $d->observacao }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="dynamic-form">
                                <td style="width: 250px">
                                    <select class="produto_id form-control" name="produto_id[]">
                                    </select>
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control qtd" name="quantidade_produto[]">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_produto[]">
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_produto[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_produto[]">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="fw-bold">Total Produtos</td>
                                <td class="total-produto text-primary fw-bold" colspan="3">
                                    @isset($item)
                                    R$ {{ __moeda($item->produtos->sum('sub_total')) }}
                                    @else
                                    R$ 0,00
                                    @endisset
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-line-produto">
                        <i class="ri-add-line me-1"></i> Adicionar Produto
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Botões de Ação -->
<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('manutencao-veiculos.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
