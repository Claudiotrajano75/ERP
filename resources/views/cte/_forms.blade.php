<style>
/* Abas de Navegação */
.cte-tabs .nav-link {
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    color: #5a5a7a;
    padding: 10px 18px;
    font-size: 13px;
    border: 1px solid transparent;
}
.cte-tabs .nav-link.active {
    color: #4338ca !important;
    background: #fff;
    border-color: #e2e8f0 #e2e8f0 #fff !important;
    font-weight: 700;
}
.cte-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #374151;
    border-bottom: 1px solid #eef0f6;
    padding-bottom: 8px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cte-section-title i {
    color: #4f46e5;
    font-size: 17px;
}
.cte-participant-card {
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 14px 16px;
    margin-top: 10px;
}
.cte-participant-card h6 {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
}
.cte-participant-card strong {
    color: #1e293b;
}
</style>

<div class="row g-3">
    <input type="hidden" id="clientes" value="{{json_encode($clientes)}}" name="">

    <div class="col-12">
        <!-- Abas principais -->
        <ul class="nav nav-tabs cte-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dados_iniciais" role="tab" aria-selected="true">
                    <i class="ri-settings-4-line me-1"></i> 1. Dados Iniciais & Participantes
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#referencia_cte" role="tab" aria-selected="false">
                    <i class="ri-file-copy-2-line me-1"></i> 2. Documentos Referenciados
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#info_carga" role="tab" aria-selected="false">
                    <i class="ri-truck-line me-1"></i> 3. Carga, Medidas & Componentes
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#info_entrega" role="tab" aria-selected="false">
                    <i class="ri-map-pin-2-line me-1"></i> 4. Rotas, Entrega & Valores
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ═══════════ TAB 1: DADOS INICIAIS ═══════════ -->
            <div class="tab-pane fade show active" id="dados_iniciais" role="tabpanel">
                <div class="cte-section-title">
                    <i class="ri-information-line"></i>
                    Configuração do CTe & Dados Fiscais
                </div>
                <div class="row g-3 mb-4">
                    @if(__countLocalAtivo() > 1)
                    <div class="col-md-3">
                        <label class="fw-semibold fs-12 text-muted mb-1">Local / Filial</label>
                        <select id="inp-local_id" required class="select2 form-select" data-toggle="select2" name="local_id">
                            <option value="">Selecione</option>
                            @foreach(__getLocaisAtivoUsuario() as $local)
                            <option @isset($item) @if($item->local_id == $local->id) selected @endif @endisset value="{{ $local->id }}">{{ $local->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
                    @endif
                    <div class="col-md-4">
                        {!!Form::select('natureza_id', 'Natureza de Operação', ['' => 'Selecione'] + $naturezas->pluck('descricao', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()!!}
                    </div>
                    <div class="col-md-2">
                        {!!Form::select('globalizado', 'Tipo Globalizado', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('cst', 'CST', App\Models\Cte::getCsts())->attrs(['class' => 'select2 form-select']) !!}
                    </div>
                    <div class="col-md-2">
                        {!!Form::tel('perc_icms', '% ICMS')->attrs(['class' => 'percentual'])->required()!!}
                    </div>
                    <div class="col-md-2">
                        {!!Form::tel('cfop', 'CFOP')->attrs(['class' => 'cfop'])->required()!!}
                    </div>
                    <div class="col-md-2">
                        {!!Form::tel('numero', 'Número CTe')->required()->value(isset($item) ? $item->numero : $numeroCte)!!}
                    </div>
                    <div class="col-md-2">
                        {!!Form::text('perc_red_bc', '% Red. BC')->attrs(['class' => 'percentual'])!!}
                    </div>
                </div>

                <div class="cte-section-title mt-4">
                    <i class="ri-user-shared-line"></i>
                    Participantes do Transporte
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        {!! Form::select('remetente_id','Remetente', ['' => 'Selecione um Remetente'] + $clientes->pluck('razao_social', 'id')->all())
                        ->attrs(['class' => 'select2 form-select'])->required()
                        ->value(isset($item) ? $item->remetente_id : null) !!}
                        <div class="cte-participant-card div-remetente d-none">
                            <span class="badge bg-primary-subtle text-primary fw-bold mb-2">REMETENTE SELECIONADO</span>
                            <h6 class="mb-1">Razão Social: <strong id="razao_social_remetente"></strong></h6>
                            <h6 class="mb-1">CNPJ/CPF: <strong id="cnpj_remetente"></strong></h6>
                            <h6 class="mb-0">Cidade/UF: <strong id="cidade_remetente"></strong></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::select('destinatario_id', 'Destinatário', ['' => 'Selecione um Destinatário'] + $clientes->pluck('razao_social', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()
                        ->value(isset($item) ? $item->destinatario_id : null) !!}
                        <div class="cte-participant-card div-destinatario d-none">
                            <span class="badge bg-success-subtle text-success fw-bold mb-2">DESTINATÁRIO SELECIONADO</span>
                            <h6 class="mb-1">Razão Social: <strong id="razao_social_destinatario"></strong></h6>
                            <h6 class="mb-1">CNPJ/CPF: <strong id="cnpj_destinatario"></strong></h6>
                            <h6 class="mb-0">Cidade/UF: <strong id="cidade_destinatario"></strong></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::select('expedidor_id', 'Expedidor (Opcional)', ['' => 'Selecione se houver'] + $clientes->pluck('razao_social', 'id')->all())
                        ->attrs(['class' => 'select2 form-select'])
                        ->value(isset($item) ? $item->expedidor_id : null) !!}
                        <div class="cte-participant-card div-expedidor d-none">
                            <span class="badge bg-info-subtle text-info fw-bold mb-2">EXPEDIDOR SELECIONADO</span>
                            <h6 class="mb-1">Razão Social: <strong id="razao_social_expedidor"></strong></h6>
                            <h6 class="mb-1">CNPJ/CPF: <strong id="cnpj_expedidor"></strong></h6>
                            <h6 class="mb-0">Cidade/UF: <strong id="cidade_expedidor"></strong></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::select('recebedor_id', 'Recebedor (Opcional)', ['' => 'Selecione se houver'] + $clientes->pluck('razao_social', 'id')->all())
                        ->attrs(['class' => 'select2 form-select'])
                        ->value(isset($item) ? $item->recebedor_id : null) !!}
                        <div class="cte-participant-card div-recebedor d-none">
                            <span class="badge bg-warning-subtle text-warning fw-bold mb-2">RECEBEDOR SELECIONADO</span>
                            <h6 class="mb-1">Razão Social: <strong id="razao_social_recebedor"></strong></h6>
                            <h6 class="mb-1">CNPJ/CPF: <strong id="cnpj_recebedor"></strong></h6>
                            <h6 class="mb-0">Cidade/UF: <strong id="cidade_recebedor"></strong></h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════ TAB 2: REFERÊNCIA ═══════════ -->
            <div class="tab-pane fade" id="referencia_cte" role="tabpanel">
                <ul class="nav nav-pills nav-pills-sm mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="pill" href="#referencia_nfe" role="tab" aria-selected="true">
                            <i class="ri-file-text-line me-1"></i> NF-e Referenciadas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="pill" href="#referencia_outros" role="tab" aria-selected="false">
                            <i class="ri-file-list-line me-1"></i> Outros Documentos
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-2">
                    <div class="tab-pane fade show active" id="referencia_nfe" role="tabpanel">
                        <div class="cte-section-title">
                            <i class="ri-file-text-line"></i>
                            Chaves de Acesso de NF-e
                        </div>
                        <div class="table-responsive">
                            <div class="col-md-10 col-12">
                                <table class="table table-dynamic table-chave align-middle mb-2">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Chave de Acesso (44 dígitos)</th>
                                            <th class="text-center" style="width: 70px;">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($item) && sizeof($item->chaves_nfe) > 0)
                                        @foreach ($item->chaves_nfe as $i)
                                        <tr class="dynamic-form">
                                            <td>
                                                <input type="tel" class="form-control" name="chave_nfe[]" value="{{$i->chave}}" placeholder="Digite a chave da NFe de 44 dígitos">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-tr">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="dynamic-form">
                                            <td>
                                                <input type="tel" class="form-control" name="chave_nfe[]" placeholder="Digite a chave da NFe de 44 dígitos">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-tr">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-add-tr">
                                    <i class="ri-add-line me-1"></i> Adicionar Outra Chave
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="referencia_outros" role="tabpanel">
                        <div class="cte-section-title">
                            <i class="ri-file-list-line"></i>
                            Outros Documentos Originários
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                {!! Form::select('tpDoc', 'Tipo de Documento', [null => 'Selecione'] + [
                                '00' => 'Declaração',
                                '10' => 'Dutoviário',
                                '59' => 'Cf-e SAT',
                                '65' => 'NFCe',
                                '99' => 'Outros',
                                ])->attrs(['class' => 'form-select'])
                                ->value(isset($item) ? $item->tpDoc : '') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::text('descOutros', 'Descrição do Documento')->value(isset($item) ? $item->descOutros : '') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::tel('nDoc', 'Número do Documento')->value(isset($item) ? $item->nDoc : '') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::tel('vDocFisc', 'Valor Fiscal')->attrs(['class' => 'moeda'])->value(isset($item) ? __moeda($item->vDocFisc) : '') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════ TAB 3: INFO CARGA ═══════════ -->
            <div class="tab-pane fade" id="info_carga" role="tabpanel">
                <div class="cte-section-title">
                    <i class="ri-truck-line"></i>
                    1. Dados do Veículo & Carga
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        {!! Form::select('veiculo_id', 'Veículo da Frota', ['' => 'Selecione um Veículo'] + $veiculos->pluck('placa', 'id')
                        ->all())->attrs(['class' => 'select2 form-select'])->value(isset($item) ? $item->veiculo_id : '')
                        ->required() !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::text('produto_predominante', 'Produto Predominante')->required()->value(isset($item) ? $item->produto_predominante : '') !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::select('tomador', 'Tomador do Serviço', App\Models\Cte::tiposTomador())->attrs(['class' => 'form-select'])->value(isset($item) ? $item->tomador : '')
                        ->required() !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::tel('valor_carga', 'Valor Total da Carga')->attrs(['class' => 'moeda'])->value(isset($item) ? __moeda($item->valor_carga) : '')
                        ->required() !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::select('modal', 'Modal de Transporte', App\Models\Cte::modals())->attrs(['class' => 'form-select'])->required()->value(isset($item) ? $item->modal : '') !!}
                    </div>
                </div>

                <div class="cte-section-title mt-4">
                    <i class="ri-scales-line"></i>
                    2. Unidades e Medidas de Carga
                </div>
                <div class="table-responsive mb-2">
                    <table class="table table-dynamic table-informacoes align-middle mb-2" id="prod">
                        <thead class="table-light">
                            <tr>
                                <th>Código da Unidade</th>
                                <th>Tipo de Medida</th>
                                <th>Quantidade Medida</th>
                                <th class="text-center" style="width: 70px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody id="body">
                            @if(isset($item) && sizeof($item->medidas) > 0)
                            @foreach($item->medidas as $med)
                            <tr class="dynamic-form">
                                <td>{!! Form::select('cod_unidade[]', '', $unidadesMedida)->attrs(['class' => 'form-select'])->required()->value($med->cod_unidade) !!}</td>
                                <td>{!! Form::select('tipo_medida[]', '', $tiposMedida)->attrs(['class' => 'form-select'])->required()->value($med->tipo_medida) !!}</td>
                                <td>{!! Form::tel('quantidade_carga[]', '')->attrs(['class' => 'moeda form-control'])->required()->value(__moeda($med->quantidade)) !!}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger btn-remove-tr" type="button">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="dynamic-form">
                                <td>{!! Form::select('cod_unidade[]', '', $unidadesMedida)->attrs(['class' => 'form-select'])->required() !!}</td>
                                <td>{!! Form::select('tipo_medida[]', '', $tiposMedida)->attrs(['class' => 'form-select'])->required() !!}</td>
                                <td>{!! Form::tel('quantidade_carga[]', '')->attrs(['class' => 'moeda form-control'])->required() !!}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger btn-remove-tr" type="button">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mb-4">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-tr">
                        <i class="ri-add-line me-1"></i> Adicionar Outra Medida
                    </button>
                </div>

                <div class="cte-section-title mt-4">
                    <i class="ri-pie-chart-line"></i>
                    3. Componentes da Prestação do Serviço / Frete
                </div>
                <p class="text-muted fs-12 mb-2"><i class="ri-alert-line text-warning me-1"></i> A soma dos valores dos componentes deve corresponder ao valor a receber.</p>
                <div class="table-responsive mb-2">
                    <table class="table table-dynamic table-componentes align-middle mb-2" id="componentes">
                        <thead class="table-light">
                            <tr>
                                <th>Nome do Componente</th>
                                <th>Valor do Componente (R$)</th>
                                <th class="text-center" style="width: 70px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody id="body">
                            @if(isset($item) && sizeof($item->componentes) > 0)
                            @foreach($item->componentes as $cp)
                            <tr class="dynamic-form">
                                <td>{!! Form::text('nome_componente[]', '')->attrs(['class' => 'form-control'])->required()->value($cp->nome) !!}</td>
                                <td>{!! Form::text('valor_componente[]', '')->attrs(['class' => 'moeda form-control'])->required()->value(__moeda($cp->valor)) !!}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger btn-remove-tr" type="button">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="dynamic-form">
                                <td>{!! Form::text('nome_componente[]', '')->attrs(['class' => 'form-control'])->required() !!}</td>
                                <td>{!! Form::text('valor_componente[]', '')->attrs(['class' => 'moeda form-control'])->required() !!}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger btn-remove-tr" type="button">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-tr">
                        <i class="ri-add-line me-1"></i> Adicionar Componente
                    </button>
                </div>
            </div>

            <!-- ═══════════ TAB 4: INFO ENTREGA ═══════════ -->
            <div class="tab-pane fade" id="info_entrega" role="tabpanel">
                <div class="cte-section-title">
                    <i class="ri-map-pin-line"></i>
                    1. Endereço do Tomador do Serviço
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12 d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo-destinatario" value="destinatario" required @if(isset($item) && $item->tomador == 3) checked @endif>
                            <label class="form-check-label fw-semibold" for="tipo-destinatario">Usar Endereço do Destinatário</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo-remetente" value="remetente" required @if(isset($item) && $item->tomador == 0) checked @endif>
                            <label class="form-check-label fw-semibold" for="tipo-remetente">Usar Endereço do Remetente</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        {!! Form::text('logradouro_tomador', 'Logradouro / Rua')->required()->value(isset($item) ? $item->logradouro_tomador : '') !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::text('numero_tomador', 'Número')->required()->value(isset($item) ? $item->numero_tomador : '') !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::text('cep_tomador', 'CEP')->attrs(['class' => 'cep'])->required()->value(isset($item) ? $item->cep_tomador : '') !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::text('bairro_tomador', 'Bairro')->required()->value(isset($item) ? $item->bairro_tomador : '') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::select('municipio_tomador', 'Cidade do Tomador', ['' => 'Selecione a Cidade'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()->value(isset($item) ? $item->municipio_tomador : '') !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::date('data_prevista_entrega', 'Data Prevista de Entrega')->required()->value(isset($item) ? $item->data_prevista_entrega : '') !!}
                    </div>
                </div>

                <div class="cte-section-title mt-4">
                    <i class="ri-money-dollar-circle-line"></i>
                    2. Valores da Prestação de Serviço
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        {!! Form::tel('valor_transporte', 'Valor do Transporte')->required()->attrs(['class' => 'moeda'])->value(isset($item) ? __moeda($item->valor_transporte) : '') !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::tel('valor_receber', 'Valor Total a Receber')->attrs(['class' => 'moeda'])->required()->value(isset($item) ? __moeda($item->valor_receber) : '') !!}
                    </div>
                </div>

                <div class="cte-section-title mt-4">
                    <i class="ri-route-line"></i>
                    3. Rotas de Transporte
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        {!! Form::select('municipio_envio', 'Município de Envio', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()->value(isset($item) ? $item->municipio_envio : '') !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('municipio_inicio', 'Município de Início da Prestação', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()->value(isset($item) ? $item->municipio_inicio : '') !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('municipio_fim', 'Município Final (Destino)', ['' => 'Selecione'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2 form-select'])->required()->value(isset($item) ? $item->municipio_fim : '') !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('retira', 'Recebedor Retira?', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->value(isset($item) ? $item->retira : 0) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('detalhes_retira', 'Detalhes da Retirada (Opcional)')->value(isset($item) ? $item->detalhes_retira : '') !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Observações Finais -->
        <div class="mt-4 p-3 bg-light rounded border">
            {!! Form::text('observacao', 'Informações Complementares / Observações')->value(isset($item) ? $item->observacao : '') !!}
        </div>
    </div>
</div>

<!-- Barra de Ações -->
<div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
    <a href="{{ route('cte.index') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
        <i class="ri-save-line"></i> Salvar CTe
    </button>
</div>
 me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store" disabled>
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
@endisset
