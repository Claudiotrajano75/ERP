<div class="row g-3 text-dark">
    <div class="col-12">

        <!-- Linha 1: Cliente + Veículo + Local -->
        <div class="row g-3 mb-3">
            <div class="col-md-5">
                <label class="fw-semibold fs-12 text-muted mb-1 required">Cliente</label>
                <div class="input-group flex-nowrap">
                    <select required id="inp-cliente_id" name="cliente_id" class="cliente_id form-control">
                        @if(isset($item) && $item->cliente)
                        <option value="{{ $item->cliente_id }}">{{ $item->cliente->razao_social }}</option>
                        @endif
                    </select>
                    @can('clientes_create')
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal_novo_cliente" type="button">
                        <i class="ri-add-circle-fill"></i>
                    </button>
                    @endcan
                </div>
            </div>
            <div class="col-md-3">
                {!! Form::select('veiculo_id', 'Veículo', ['' => 'Selecione'] + $veiculos->pluck('info', 'id')->all())
                ->id('veiculo')
                ->attrs(['class' => 'select2'])
                ->required() !!}
            </div>
            @if(__countLocalAtivo() > 1)
            <div class="col-md-2">
                <label class="fw-semibold fs-12 text-muted mb-1">Local</label>
                <select id="inp-local_id" required class="select2" data-toggle="select2" name="local_id">
                    <option value="">Selecione</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option @isset($item) @if($item->local_id == $local->id) selected @endif @endisset value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
            @endif
        </div>

        <!-- Abas: Dados Iniciais | Despesas -->
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dados-iniciais" role="tab" aria-selected="true">
                    <i class="ri-settings-fill me-1"></i>
                    Dados Iniciais
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#despesas" role="tab" aria-selected="false">
                    <i class="ri-coins-line me-1"></i>
                    Despesas
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ═══ TAB: DADOS INICIAIS ═══ -->
            <div class="tab-pane fade show active" id="dados-iniciais" role="tabpanel">
                <div class="row g-3">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
                        1. Informações Financeiras
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            {!!Form::tel('total', 'Valor total do frete')->required()
                            ->attrs(['class' => 'moeda'])
                            ->value(isset($item) ? __moeda($item->total) : '')
                            !!}
                        </div>
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
                            'em_carregamento' => 'Em carregamento', 'em_viagem' => 'Em viagem', 'finalizado' => 'Finalizado'])
                            ->attrs(['class' => 'form-select'])
                            ->required()
                            !!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-calendar-line text-primary me-2 align-middle fs-18"></i>
                        2. Datas e Horários
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            {!!Form::date('data_inicio', 'Dt. início viagem')->required()!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::date('data_fim', 'Dt. final viagem')->required()!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::time('horario_inicio', 'Horário início')!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::time('horario_fim', 'Horário fim')!!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i>
                        3. Rota e Observação
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            {!!Form::select('cidade_carregamento', 'Cidade carregamento', 
                            ['' => 'Selecione a cidade'] + $cidades->pluck('info', 'id')->all())
                            ->attrs(['class' => 'select2'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::select('cidade_descarregamento', 'Cidade descarregamento', 
                            ['' => 'Selecione a cidade'] + $cidades->pluck('info', 'id')->all())
                            ->attrs(['class' => 'select2'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::tel('distancia_km', 'Distância KM')->required()!!}
                        </div>
                        <div class="col-md-12">
                            {!!Form::text('observacao', 'Observação')!!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TAB: DESPESAS ═══ -->
            <div class="tab-pane fade" id="despesas" role="tabpanel">
                <h5 class="text-dark border-bottom pb-2 mb-3">
                    <i class="ri-coins-line text-primary me-2 align-middle fs-18"></i>
                    Despesas do Frete
                </h5>
                <div class="table-responsive mt-3">
                    <table class="table table-dynamic table-centered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo de Despesa</th>
                                <th>Fornecedor</th>
                                <th>Valor</th>
                                <th>Observação</th>
                                <th class="text-center" style="width: 60px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($item) && sizeof($item->despesas) > 0)
                            @foreach($item->despesas as $d)
                            <tr class="dynamic-form">
                                <td>
                                    <select class="select2 form-control" name="tipo_despesa_id[]">
                                        <option value="">Selecione</option>
                                        @foreach($tiposDespesas as $t)
                                        <option @if($t->id == $d->tipo_despesa_id) selected @endif value="{{ $t->id }}">{{ $t->nome }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="max-width: 400px">
                                    <select class="fornecedor_id ignore form-control" name="fornecedor_id[]">
                                        @if($d->fornecedor)
                                        <option value="{{ $d->fornecedor_id }}">
                                            {{ $d->fornecedor->info }}
                                        </option>
                                        @endif
                                    </select>
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control valor moeda" name="valor_despesa[]" value="{{ __moeda($d->valor) }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_despesa[]" value="{{ $d->observacao }}">
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
                                <td>
                                    <select class="select2 form-control" name="tipo_despesa_id[]">
                                        <option value="">Selecione</option>
                                        @foreach($tiposDespesas as $t)
                                        <option value="{{ $t->id }}">{{ $t->nome }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="max-width: 400px">
                                    <select class="fornecedor_id ignore form-control" name="fornecedor_id[]">
                                    </select>
                                </td>
                                <td style="width: 130px">
                                    <input type="tel" class="form-control valor moeda" name="valor_despesa[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control ignore" name="observacao_despesa[]">
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
                                <td colspan="2" class="fw-bold">Total Despesas</td>
                                <td class="total-despesa text-primary fw-bold">
                                    @isset($item)
                                    R$ {{ __moeda($item->total_despesa) }}
                                    @else
                                    R$ 0,00
                                    @endisset
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-line">
                        <i class="ri-add-line me-1"></i>
                        Adicionar Despesa
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Botões de Ação -->
<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('fretes.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
