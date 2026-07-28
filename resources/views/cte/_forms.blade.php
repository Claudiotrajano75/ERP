<div class="row g-3 text-dark">
    <input type="hidden" id="clientes" value="{{json_encode($clientes)}}" name="">

    <div class="col-md-12">
        <!-- Abas principais -->
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dados_iniciais" role="tab" aria-selected="true">
                    <i class="ri-settings-fill me-1"></i> Dados Iniciais
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#referencia_cte" role="tab" aria-selected="false">
                    <i class="ri-file-paper-fill me-1"></i> Documentos Referência
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#info_carga" role="tab" aria-selected="false">
                    <i class="ri-truck-line me-1"></i> Informações da Carga
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#info_entrega" role="tab" aria-selected="false">
                    <i class="ri-map-2-line me-1"></i> Informações de Entrega
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ═══════════ TAB 1: DADOS INICIAIS ═══════════ -->
            <div class="tab-pane fade show active" id="dados_iniciais" role="tabpanel">
                <div class="row g-3">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
                        1. Configuração da CTe
                    </h5>
                    <div class="row g-3">
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
                        <div class="col-md-3">
                            {!!Form::select('natureza_id', 'Natureza de Operação', ['' => 'Selecione'] + $naturezas->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])->required()!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::select('globalizado', 'Tipo Globalizado', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::select('cst', 'CST', App\\Models\\Cte::getCsts())->attrs(['class' => 'form-select']) !!}
                        </div>
                        <div class="col-md-1">
                            {!!Form::tel('perc_icms', '%ICMS')->attrs(['class' => 'percentual'])->required()!!}
                        </div>
                        <div class="col-md-1">
                            {!!Form::tel('cfop', 'CFOP')->attrs(['class' => 'cfop'])->required()!!}
                        </div>
                        <div class="col-md-1">
                            {!!Form::tel('numero', 'Número CTe')->required()->value(isset($item) ? $item->numero : $numeroCte)!!}
                        </div>
                        <div class="col-md-1 mb-2">
                            {!!Form::text('perc_red_bc', '%Red. BC')->attrs(['class' => 'percentual'])!!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-user-line text-primary me-2 align-middle fs-18"></i>
                        2. Participantes
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            {!! Form::select('remetente_id','Remetente', ['' => 'Selecione'] + $clientes->pluck('razao_social', 'id')->all())
                            ->attrs(['class' => 'select2'])->required()
                            ->value(isset($item) ? $item->remetente_id : null) !!}
                            <div class="card border mt-3 div-remetente d-none">
                                <div class="m-3">
                                    <h5 class="text-center text-info">REMETENTE SELECIONADO</h5>
                                    <hr>
                                    <H6>Razão Social: <strong id="razao_social_remetente"></strong></H6>
                                    <H6>CNPJ: <strong id="cnpj_remetente"></strong></H6>
                                    <H6>Cidade: <strong id="cidade_remetente"></strong></H6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::select('destinatario_id', 'Destinatário', ['' => 'Selecione'] + $clientes->pluck('razao_social', 'id')->all())->attrs(['class' => 'select2'])->required()
                            ->value(isset($item) ? $item->destinatario_id : null) !!}
                            <div class="card border mt-3 div-destinatario d-none">
                                <div class="m-3">
                                    <h5 class="text-center text-info">DESTINATÁRIO SELECIONADO</h5>
                                    <hr>
                                    <H6>Razão Social: <strong id="razao_social_destinatario"></strong></H6>
                                    <H6>CNPJ: <strong id="cnpj_destinatario"></strong></H6>
                                    <H6>Cidade: <strong id="cidade_destinatario"></strong></H6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::select('expedidor_id', 'Expedidor', ['' => 'Selecione'] + $clientes->pluck('razao_social', 'id')->all())
                            ->attrs(['class' => 'select2'])
                            ->value(isset($item) ? $item->expedidor_id : null) !!}
                            <div class="card border mt-3 div-expedidor d-none">
                                <div class="m-3">
                                    <h5 class="text-center text-info">EXPEDIDOR SELECIONADO</h5>
                                    <hr>
                                    <H6>Razão Social: <strong id="razao_social_expedidor"></strong></H6>
                                    <H6>CNPJ: <strong id="cnpj_expedidor"></strong></H6>
                                    <H6>Cidade: <strong id="cidade_expedidor"></strong></H6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::select('recebedor_id', 'Recebedor', ['' => 'Selecione'] + $clientes->pluck('razao_social', 'id')->all())
                            ->attrs(['class' => 'select2'])
                            ->value(isset($item) ? $item->recebedor_id : null) !!}
                            <div class="card border mt-3 div-recebedor d-none">
                                <div class="m-3">
                                    <h5 class="text-center text-info">RECEBEDOR SELECIONADO</h5>
                                    <hr>
                                    <H6>Razão Social: <strong id="razao_social_recebedor"></strong></H6>
                                    <H6>CNPJ: <strong id="cnpj_recebedor"></strong></H6>
                                    <H6>Cidade: <strong id="cidade_recebedor"></strong></H6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════ TAB 2: REFERÊNCIA ═══════════ -->
            <div class="tab-pane fade" id="referencia_cte" role="tabpanel">
                <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#referencia_nfe" role="tab" aria-selected="true">
                            <i class="ri-file-text-line me-1"></i> NFe
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#referencia_outros" role="tab" aria-selected="false">
                            <i class="ri-file-list-line me-1"></i> Outros
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="referencia_nfe" role="tabpanel">
                        <h5 class="text-dark border-bottom pb-2 mb-3">
                            <i class="ri-file-text-line text-primary me-2 align-middle fs-18"></i>
                            Chaves de NFe Referenciadas
                        </h5>
                        <div class="table-responsive">
                            <div class="col-11">
                                <table class="table table-dynamic table-chave table-centered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Chave</th>
                                            <th class="text-center" style="width: 60px;">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($item) && sizeof($item->chaves_nfe) > 0)
                                        @foreach ($item->chaves_nfe as $i)
                                        <tr class="dynamic-form">
                                            <td>
                                                <input type="tel" class="form-control" name="chave_nfe[]" value="{{$i->chave}}" placeholder="Chave da NFe">
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
                                                <input type="tel" class="form-control" name="chave_nfe[]" placeholder="Chave da NFe">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-primary btn-sm btn-add-tr">
                                    <i class="ri-add-line me-1"></i> Adicionar chave
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="referencia_outros" role="tabpanel">
                        <h5 class="text-dark border-bottom pb-2 mb-3">
                            <i class="ri-file-list-line text-primary me-2 align-middle fs-18"></i>
                            Outros Documentos
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                {!! Form::select('tpDoc', 'Tipo', [null => 'Selecione'] + [
                                '00' => 'Declaração',
                                '10' => 'Dutoviário',
                                '59' => 'Cf-e SAT',
                                '65' => 'NFCe',
                                '99' => 'Outros',
                                ])->attrs(['class' => 'form-select'])
                                ->value(isset($item) ? $item->tpDoc : '') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::text('descOutros', 'Descrição doc.')->value(isset($item) ? $item->descOutros : '') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::tel('nDoc', 'Número doc.')->value(isset($item) ? $item->nDoc : '') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::tel('vDocFisc', 'Valor doc.')->attrs(['class' => 'moeda'])->value(isset($item) ? __moeda($item->vDocFisc) : '') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════ TAB 3: INFO CARGA ═══════════ -->
            <div class="tab-pane fade" id="info_carga" role="tabpanel">
                <div class="row g-3">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-truck-line text-primary me-2 align-middle fs-18"></i>
                        1. Dados da Carga
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            {!! Form::select('veiculo_id', 'Veículo', ['' => 'Selecione'] + $veiculos->pluck('placa', 'id')
                            ->all())->attrs(['class' => 'form-select'])->value(isset($item) ? $item->veiculo_id : '')
                            ->required() !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::text('produto_predominante', 'Produto predominante')->required()->value(isset($item) ? $item->produto_predominante : '') !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('tomador', 'Tomador', App\\Models\\Cte::tiposTomador())->attrs(['class' => 'form-select'])->value(isset($item) ? $item->tomador : '')
                            ->required() !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::tel('valor_carga', 'Valor carga')->attrs(['class' => 'moeda'])->value(isset($item) ? __moeda($item->valor_carga) : '')
                            ->required() !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::select('modal', 'Modelo de transporte', App\\Models\\Cte::modals())->attrs(['class' => 'form-select'])->required()->value(isset($item) ? $item->modal : '') !!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-bar-chart-line text-primary me-2 align-middle fs-18"></i>
                        2. Informações de Quantidade
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-dynamic table-striped table-informacoes table-centered align-middle mb-0" id="prod">
                            <thead class="table-light">
                                <tr>
                                    <th>Unidade</th>
                                    <th>Tipo de Medida</th>
                                    <th>Quantidade</th>
                                    <th class="text-center" style="width: 60px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody id="body">
                                @if(isset($item) && sizeof($item->medidas) > 0)
                                @foreach($item->medidas as $med)
                                <tr class="dynamic-form">
                                    <td>{!! Form::select('cod_unidade[]', '', $unidadesMedida)->attrs(['class' => 'form-select'])->required()->value($med->cod_unidade) !!}</td>
                                    <td>{!! Form::select('tipo_medida[]', '', $tiposMedida)->attrs(['class' => 'form-select'])->required()->value($med->tipo_medida) !!}</td>
                                    <td>{!! Form::tel('quantidade_carga[]', '')->attrs(['class' => 'moeda'])->required()->value(__moeda($med->quantidade)) !!}</td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="dynamic-form">
                                    <td>{!! Form::select('cod_unidade[]', '', $unidadesMedida)->attrs(['class' => 'select2'])->required() !!}</td>
                                    <td>{!! Form::select('tipo_medida[]', '', $tiposMedida)->attrs(['class' => 'select2'])->required() !!}</td>
                                    <td>{!! Form::tel('quantidade_carga[]', '')->attrs(['class' => 'moeda'])->required() !!}</td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm btn-add-tr">
                            <i class="ri-add-line me-1"></i> Adicionar
                        </button>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-pie-chart-line text-primary me-2 align-middle fs-18"></i>
                        3. Componentes de Carga
                    </h5>
                    <p class="text-danger fs-13">*A soma dos valores dos componentes deve ser igual ao valor a receber!</p>
                    <div class="table-responsive">
                        <table class="table table-dynamic table-componentes table-centered align-middle mb-0" id="componentes">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome do Componente</th>
                                    <th>Valor</th>
                                    <th class="text-center" style="width: 60px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody id="body">
                                @if(isset($item) && sizeof($item->componentes) > 0)
                                @foreach($item->componentes as $cp)
                                <tr class="dynamic-form">
                                    <td>{!! Form::text('nome_componente[]', '')->required()->value($cp->nome) !!}</td>
                                    <td>{!! Form::text('valor_componente[]', '')->attrs(['class' => 'moeda'])->required()->value(__moeda($cp->valor)) !!}</td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="dynamic-form">
                                    <td>{!! Form::text('nome_componente[]', '')->required() !!}</td>
                                    <td>{!! Form::text('valor_componente[]', '')->attrs(['class' => 'moeda'])->required() !!}</td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm btn-add-tr">
                            <i class="ri-add-line me-1"></i> Adicionar
                        </button>
                    </div>
                </div>
            </div>

            <!-- ═══════════ TAB 4: INFO ENTREGA ═══════════ -->
            <div class="tab-pane fade" id="info_entrega" role="tabpanel">
                <div class="row g-3">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i>
                        1. Endereço do Tomador
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-12 d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo" id="tipo-destinatario" value="destinatario" required @if(isset($item) && $item->tomador == 3) checked @endif>
                                <label class="form-check-label" for="tipo-destinatario">Endereço do destinatário</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo" id="tipo-remetente" value="remetente" required @if(isset($item) && $item->tomador == 0) checked @endif>
                                <label class="form-check-label" for="tipo-remetente">Endereço do remetente</label>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            {!! Form::text('logradouro_tomador', 'Rua')->required()->value(isset($item) ? $item->logradouro_tomador : '') !!}
                        </div>
                        <div class="col-md-1 mt-3">
                            {!! Form::text('numero_tomador', 'Número')->required()->value(isset($item) ? $item->numero_tomador : '') !!}
                        </div>
                        <div class="col-md-2 mt-3">
                            {!! Form::text('cep_tomador', 'CEP')->attrs(['class' => 'cep'])->required()->value(isset($item) ? $item->cep_tomador : '') !!}
                        </div>
                        <div class="col-md-3 mt-3">
                            {!! Form::text('bairro_tomador', 'Bairro')->required()->value(isset($item) ? $item->bairro_tomador : '') !!}
                        </div>
                        <div class="col-md-5 mt-3">
                            {!! Form::select('municipio_tomador', 'Cidade', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2'])->required()->value(isset($item) ? $item->municipio_tomador : '') !!}
                        </div>
                        <div class="col-md-3 mt-3">
                            {!! Form::date('data_prevista_entrega', 'Data prevista de entrega')->required()->value(isset($item) ? $item->data_prevista_entrega : '') !!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-money-dollar-circle-line text-primary me-2 align-middle fs-18"></i>
                        2. Valores
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            {!! Form::tel('valor_transporte', 'Valor da prestação de serviço')->required()->attrs(['class' => 'moeda'])->value(isset($item) ? __moeda($item->valor_transporte) : '') !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::tel('valor_receber', 'Valor a receber')->attrs(['class' => 'moeda'])->required()->value(isset($item) ? __moeda($item->valor_receber) : '') !!}
                        </div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mb-3 mt-3">
                        <i class="ri-road-map-line text-primary me-2 align-middle fs-18"></i>
                        3. Rotas
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            {!! Form::select('municipio_envio', 'Município de envio', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2'])->required()->value(isset($item) ? $item->municipio_envio : '') !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::select('municipio_inicio', 'Município de início', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2'])->required()->value(isset($item) ? $item->municipio_inicio : '') !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::select('municipio_fim', 'Município final', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2'])->required()->value(isset($item) ? $item->municipio_fim : '') !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('retira', 'Retira', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'select2'])->value(isset($item) ? $item->retira : '') !!}
                        </div>
                        <div class="col-md-10">
                            {!! Form::text('detalhes_retira', 'Detalhes (opcional)')->value(isset($item) ? $item->detalhes_retira : '') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observação -->
        <div class="mt-4 alert alert-secondary p-3">
            {!! Form::text('observacao', 'Informação adicional')->value(isset($item) ? $item->observacao : '') !!}
        </div>
    </div>
</div>

<!-- Botões de Ação -->
@isset($item)
<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('cte.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
@else
<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('cte.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store" disabled>
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
@endisset
