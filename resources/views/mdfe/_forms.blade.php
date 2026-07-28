<style>
/* Alinhamento de tabelas dinâmicas de lacres */
.table-lacres-align td {
    vertical-align: middle !important;
}
.table-lacres-align input.form-control {
    margin-top: 0 !important;
}
.table-lacres-align .btn-remove-tr {
    margin-top: 0 !important;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>

<div class="row g-3">
    {{-- ═══ SEÇÃO: INFORMAÇÕES BÁSICAS DO MANIFESTO ═══ --}}
    <div class="col-12">
        <div class="card border border-light-subtle shadow-none mb-3">
            <div class="card-header bg-light-subtle py-2">
                <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">1. Identificação e Dados Gerais</h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    @if(__countLocalAtivo() > 1)
                    <div class="col-md-3 col-12">
                        <label class="form-label fw-semibold fs-12 mb-1">Local</label>
                        <select id="inp-local_id" required class="select2 class-required" data-toggle="select2" name="local_id">
                            <option value="">Selecione</option>
                            @foreach(__getLocaisAtivoUsuario() as $local)
                            <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        {!!Form::tel('mdfe_numero', 'Número MDFe')
                        ->required()
                        ->value(isset($item) ? $item->numero : $numeroMDFe)
                        !!}
                    </div>
                    @else
                    <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
                    <div class="col-md-3 col-6">
                        {!!Form::tel('mdfe_numero', 'Número MDFe')
                        ->required()
                        ->value(isset($item) ? $item->numero : $numeroMDFe)
                        !!}
                    </div>
                    @endif

                    <div class="col-md-2 col-6">
                        {!! Form::select('uf_inicio', 'UF inicial', ['' => 'Selecione...'] + App\Models\Cidade::estados())
                        ->attrs(['class' => 'form-select select2'])->required() !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::select('uf_fim', 'UF final', ['' => 'Selecione...'] + App\Models\Cidade::estados())->attrs([
                        'class' => 'form-select select2'])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::date('data_inicio_viagem', 'Data início da viagem')->value(date('Y-m-d'))->required() !!}
                    </div>

                    {{-- Segunda Linha completa (Soma 12 colunas) --}}
                    <div class="col-md-2 col-6">
                        {!! Form::select('carga_posterior', 'Carga posterior', [0 => 'Não', 1 => 'Sim'])->attrs(
                        ['class' => 'form-select'],
                        )->required() !!}
                    </div>
                    <div class="col-md-4 col-12">
                        {!! Form::select(
                        'tp_emit',
                        'Tipo do emitente',
                        ['' => 'Selecione...'] + [
                        1 => '1 - Prestador de serviço de transporte',
                        2 => '2 - Transportador de carga própria',
                        ],
                        )->attrs(['class' => 'form-select class-required'])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::select(
                        'tp_transp',
                        'Tipo do transportador',
                        ['' => 'Selecione...'] + [1 => '1 - ETC', 2 => '2 - TAC', 3 => '3 - CTC'],
                        )->attrs(['class' => 'form-select'])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::select('tipo_modal', 'Tipo modal', ['' => 'Selecione...'] + App\Models\Mdfe::tiposModal())->attrs([
                        'class' => 'form-select select2'])->required() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══ SEÇÃO: VEÍCULOS E DETALHES DE CARGA ═══ --}}
    <div class="col-12">
        <div class="card border border-light-subtle shadow-none mb-3">
            <div class="card-header bg-light-subtle py-2">
                <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">2. Veículos e Valores da Carga</h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-3 col-6">
                        {!! Form::select(
                        'veiculo_tracao_id',
                        'Veículo de tração',
                        ['' => 'Selecione...'] + $veiculos->pluck('placa', 'id')->all(),
                        )->attrs([
                        'class' => 'form-select class-required',
                        ])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::select(
                        'veiculo_reboque_id',
                        'Veículo de reboque 1 (opcional)',
                        ['' => 'Selecione...'] + $veiculos->pluck('placa', 'id')->all(),
                        )->attrs(['class' => 'form-select']) !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::select(
                        'veiculo_reboque2_id',
                        'Veículo de reboque 2 (opcional)',
                        ['' => 'Selecione...'] + $veiculos->pluck('placa', 'id')->all(),
                        )->attrs(['class' => 'form-select']) !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::select(
                        'veiculo_reboque3_id',
                        'Veículo de reboque 3 (opcional)',
                        ['' => 'Selecione...'] + $veiculos->pluck('placa', 'id')->all(),
                        )->attrs(['class' => 'form-select']) !!}
                    </div>

                    <div class="col-md-3 col-6">
                        {!! Form::text('lac_rodo', 'Lacre rodoviário')->attrs(['data-mask' => '00000000'])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::tel('cnpj_contratante', 'CNPJ do contratante')->attrs(['class' => 'cpf_cnpj'])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::tel('quantidade_carga', 'Quantidade da carga')->attrs(['class' => 'qtd_carga', 'data-mask' => '00000.000', 'data-mask-reverse' => 'true'])->required() !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::tel('valor_carga', 'Valor da carga')->attrs(['class' => 'moeda'])->required() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ SEÇÃO: PRODUTO PREDOMINANTE ═══ --}}
    <div class="col-12">
        <div class="card border border-light-subtle shadow-none mb-3">
            <div class="card-header bg-light-subtle py-2">
                <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">3. Produto Predominante (Opcional)</h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-4 col-12">
                        {!! Form::text('produto_pred_nome', 'Nome')->attrs(['class' => '']) !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::tel('produto_pred_ncm', 'NCM')->attrs(['class' => 'ncm']) !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::tel('produto_pred_cod_barras', 'Código de barras')->attrs(['class' => '']) !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!! Form::select('tp_carga', 'Tipo de carga', ['' => 'Selecione...'] + App\Models\Mdfe::tiposCarga())->attrs([
                        'class' => 'form-select',
                        ]) !!}
                    </div>

                    <div class="col-md-2 col-6">
                        {!! Form::tel('cep_carrega', 'Cep carrega')->attrs(['data-mask' => '00000000']) !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::tel('latitude_carregamento', 'Latitude carrega')->attrs(['class' => '']) !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::tel('longitude_carregamento', 'Longitude carrega')->attrs(['class' => '']) !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::tel('cep_descarrega', 'Cep descarrega')->attrs(['data-mask' => '00000000']) !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::tel('latitude_descarregamento', 'Latitude descarrega')->attrs(['class' => '']) !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!! Form::tel('longitude_descarregamento', 'Longitude descarrega')->attrs(['class' => '']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ NAVEGAÇÃO DE ABAS ═══ --}}
    <div class="col-12 mt-3">
        <div class="row g-2 mb-3">
            <div class="col-md-4 col-12">
                <button type="button" class="btn btn-outline-primary btn-gerais active w-100 py-2 fw-bold" onclick="selectDiv2('gerais')">
                    <i class="ri-information-line me-1 align-middle fs-15"></i> 1. INFORMAÇÕES GERAIS
                </button>
            </div>
            <div class="col-md-4 col-12">
                <button type="button" class="btn btn-outline-primary btn-transporte w-100 py-2 fw-bold" onclick="selectDiv2('transporte')">
                    <i class="ri-truck-line me-1 align-middle fs-15"></i> 2. INFORMAÇÕES TRANSPORTE
                </button>
            </div>
            <div class="col-md-4 col-12">
                <button type="button" class="btn btn-outline-primary btn-descarregamento w-100 py-2 fw-bold" onclick="selectDiv2('descarregamento')">
                    <i class="ri-map-pin-check-line me-1 align-middle fs-15"></i> 3. INFORMAÇÕES DESCARREGAMENTO
                </button>
            </div>
        </div>
    </div>


    {{-- ═══ DIV-GERAIS ═══ --}}
    <div class="div-gerais row g-3">
        <div class="col-12">
            <div class="card border border-light-subtle shadow-none">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Seguradora (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            {!! Form::text('seguradora_nome', 'Nome da seguradora')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::tel('seguradora_cnpj', 'CNPJ da seguradora')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::tel('numero_apolice', 'Número da apólice')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::tel('numero_averbacao', 'Número da averbação')->attrs(['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-12">
            <div class="card border border-light-subtle shadow-none h-100">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Município(s) de Carregamento</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 table-striped table-dynamic align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Cidade</th>
                                    <th width="80" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="body" class="datatable-body">
                                @if (isset($item) && sizeof($item->municipiosCarregamento) > 0)
                                @foreach($item->municipiosCarregamento as $mun)
                                <tr class="dynamic-form">
                                    <td>
                                        {!! Form::select('municipiosCarregamento[]', '', [null => 'Selecione...'] + $cidades->pluck('info', 'id')->all())
                                        ->attrs(['class' => 'select2'])->required()->value($mun->cidade_id) !!}
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="dynamic-form">
                                    <td>
                                        {!! Form::select('municipiosCarregamento[]', '', [null => 'Selecione...'] + $cidades->pluck('info', 'id')->all())
                                        ->attrs(['class' => 'select2 class-municipio class-required'])->required() !!}
                                    </td>
                                    <td class="text-end">
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
                        <button type="button" class="btn btn-dark btn-sm btn-add-tr">
                            <i class="ri-add-line align-middle me-1"></i> Adicionar Município
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="card border border-light-subtle shadow-none h-100">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Percurso</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 table-striped table-dynamic align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>UF</th>
                                    <th width="80" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="datatable-body" id="tbody">
                                @if (isset($item) && sizeof($item->percurso) > 0)
                                @foreach($item->percurso as $p)
                                <tr class="dynamic-form">
                                    <td>
                                        {!! Form::select('uf[]', '', ['' => 'Selecione...'] + App\Models\Cidade::estados())
                                        ->attrs(['class' => 'select2'])
                                        ->value($p->uf)
                                        !!}
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="dynamic-form">
                                    <td>
                                        {!! Form::select('uf[]', '', ['' => 'Selecione...'] + App\Models\Cidade::estados())
                                        ->attrs(['class' => 'select2']) !!}
                                    </td>
                                    <td class="text-end">
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
                        <button type="button" class="btn btn-dark btn-sm btn-add-tr">
                            <i class="ri-add-line align-middle me-1"></i> Adicionar Percurso
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ DIV-TRANSPORTE ═══ --}}
    <div class="div-transporte d-none row g-3">
        <div class="col-12">
            <div class="card border border-light-subtle shadow-none">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">CIOT (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 table-striped table-dynamic align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Código CIOT</th>
                                    <th>CPF/CNPJ</th>
                                    <th width="80" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($item) && sizeof($item->ciots) > 0)
                                @foreach($item->ciots as $ciot)
                                <tr class="dynamic-form">
                                    <td>
                                        <input type="tel" class="form-control codigo_ciot" name="codigo_ciot[]" value="{{$ciot->codigo}}">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control cpf_cnpj" name="cpf_cnpj[]" value="{{$ciot->cpf_cnpj}}">
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="dynamic-form">
                                    <td>
                                        <input type="tel" class="form-control codigo_ciot" name="codigo_ciot[]">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control cpf_cnpj" name="cpf_cnpj[]">
                                    </td>
                                    <td class="text-end">
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
                        <button type="button" class="btn btn-dark btn-sm btn-add-tr">
                            <i class="ri-add-line align-middle me-1"></i> Adicionar CIOT
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border border-light-subtle shadow-none">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Vale Pedágio (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 table-striped table-dynamic align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>CNPJ</th>
                                    <th>CPF/CNPJ Pagador</th>
                                    <th>Número da Compra</th>
                                    <th>Valor</th>
                                    <th width="80" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($item) && sizeof($item->valesPedagio) > 0)
                                @foreach($item->valesPedagio as $vale)
                                <tr class="dynamic-form">
                                    <td>
                                        <input type="tel" class="form-control cnpj_fornecedor cpf_cnpj" name="cnpj_fornecedor[]" value="{{$vale->cnpj_fornecedor}}">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control cnpj_fornecedor_pagador" name="cnpj_fornecedor_pagador[]" value="{{$vale->cnpj_fornecedor_pagador}}">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control numero_compra" name="numero_compra[]" value="{{$vale->numero_compra}}">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control valor" name="valor_pedagio[]" value="{{ __moeda($vale->valor)}}">
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-tr">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="dynamic-form">
                                    <td>
                                        <input type="tel" class="form-control cnpj_fornecedor cpf_cnpj" name="cnpj_fornecedor[]">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control cnpj_fornecedor_pagador cpf_cnpj" name="cnpj_fornecedor_pagador[]">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control numero_compra" name="numero_compra[]">
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control valor moeda" name="valor_pedagio[]">
                                    </td>
                                    <td class="text-end">
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
                        <button type="button" class="btn btn-dark btn-sm btn-add-tr">
                            <i class="ri-add-line align-middle me-1"></i> Adicionar Vale Pedágio
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border border-light-subtle shadow-none">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Condutor</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6 col-12">
                            {!! Form::text('condutor_nome', 'Nome')->attrs(['class' => 'form-control class-condutor class-required'])->required() !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::tel('condutor_cpf', 'CPF')->attrs(['class' => 'form-control cpf class-condutor class-required'])->required() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ DIV-DESCARREGAMENTO ═══ --}}
    <div class="div-descarregamento d-none row g-3">
        <div class="col-12 form-descarregamento">
            <div class="card border border-light-subtle shadow-none mb-3">
                <div class="card-header bg-light-subtle py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Unidade de Transporte e Carga</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-3 col-12">
                            {!! Form::select(
                            'tp_unid_transp',
                            'Tipo unidade de transporte', ['' => 'Selecione...'] +
                            App\Models\Mdfe::tiposUnidadeTransporte(),
                            )->attrs(['class' => 'form-select']) !!}
                        </div>
                        <div class="col-md-3 col-6">
                            {!! Form::tel('id_unid_transp', 'ID da Unidade de transporte (placa)')->attrs(['class' => 'form-control placa']) !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!! Form::tel('quantidade_rateio', 'Qtd rateio (transporte)')->attrs(['class' => 'form-control', 'data-mask' => '000,00']) !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!! Form::tel('id_unidade_carga', 'ID unidade da carga')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!! Form::tel('quantidade_rateio_carga', 'Qtd rateio (unidade carga)')->attrs(['class' => 'form-control', 'data-mask' => '000,00']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6 col-12">
                    <div class="card border border-light-subtle shadow-none h-100">
                        <div class="card-header bg-light-subtle py-2">
                            <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">NFe Referência</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    {!! Form::tel('chave_nfe', 'Chave NFe referência')->attrs(['class' => 'form-control ignore chave_nfe']) !!}
                                </div>
                                <div class="col-12">
                                    {!! Form::tel('seg_cod_nfe', 'Segundo código de barra NFe (contingência)')->attrs(['class' => 'form-control ignore']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card border border-light-subtle shadow-none h-100">
                        <div class="card-header bg-light-subtle py-2">
                            <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">CTe Referência</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    {!! Form::tel('chave_cte', 'Chave CTe referência')->attrs(['class' => 'form-control ignore chave_nfe']) !!}
                                </div>
                                <div class="col-12">
                                    {!! Form::tel('seg_cod_cte', 'Segundo código de barra CTe (contingência)')->attrs(['class' => 'form-control ignore']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Blocos de Lacres e Município lado a lado (3 colunas iguais) --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4 col-12">
                    <div class="card border border-light-subtle shadow-none h-100">
                        <div class="card-header bg-light-subtle py-2">
                            <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Lacres de Transporte</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-striped table-dynamic table-lacres table-lacres-align align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Número Lacre</th>
                                            <th width="60" class="text-end">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body" class="datatable-body">
                                        <tr class="dynamic-form">
                                            <td>
                                                {!! Form::tel('numero_transporte[]', '')->attrs(['class' => 'form-control numero_transporte input_lacres']) !!}
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-danger btn-sm btn-remove-tr">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-dark btn-sm btn-numero_transporte btn-add-tr">
                                    <i class="ri-add-line align-middle me-1"></i> Adicionar Lacre
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card border border-light-subtle shadow-none h-100">
                        <div class="card-header bg-light-subtle py-2">
                            <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Lacres da Unidade da Carga</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-striped table-dynamic table-lacres-carga table-lacres-align align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Número Lacre</th>
                                            <th width="60" class="text-end">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body" class="datatable-body">
                                        <tr class="dynamic-form">
                                            <td>
                                                {!! Form::tel('numero_carga[]', '')->attrs(['class' => 'form-control numero_carga input_lacres']) !!}
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-danger btn-sm btn-remove-tr">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-dark btn-sm btn-add-tr">
                                    <i class="ri-add-line align-middle me-1"></i> Adicionar Lacre
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card border border-light-subtle shadow-none h-100">
                        <div class="card-header bg-light-subtle py-2">
                            <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Município de Descarregamento</h5>
                        </div>
                        <div class="card-body p-3">
                            <label class="form-label fw-semibold fs-12 mb-1">Selecione o Município</label>
                            {!! Form::select('municipio_descarregamento', '', ['' => 'Selecione...'] + $cidades->pluck('info', 'id')->all())->attrs(['class' => 'select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12">
            <div class="card border border-light-subtle shadow-none">
                <div class="card-header bg-light-subtle py-2 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Dados do Descarregamento</h5>
                    <button type="button" class="btn btn-info btn-sm btn_info_desc">
                        <i class="ri-add-circle-line align-middle me-1"></i> Adicionar Informações
                    </button>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive class-descarregamento">
                        <table class="table table-centered table-descarregamento mb-0 align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Tipo Transporte</th>
                                    <th>Id Unid Transp</th>
                                    <th>Qtd Rateio</th>
                                    <th>Qtd Rateio Carga</th>
                                    <th>NFe Ref</th>
                                    <th>CTe Ref</th>
                                    <th>Mun Descarrega</th>
                                    <th>Lacres Transp</th>
                                    <th>Lacres Carga</th>
                                    <th width="80" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($item)
                                @foreach ($item->infoDescarga as $i)
                                <tr>
                                    <td>
                                        <input readonly type="text" name="tp_und_transp_row[]" class="form-control form-control-sm" value="{{ $i->tp_unid_transp }}">
                                    </td>
                                    <td>
                                        <input readonly type="text" name="id_und_transp_row[]" class="form-control form-control-sm" value="{{ $i->id_unid_transp }}">
                                    </td>
                                    <td>
                                        <input readonly type="tel" name="quantidade_rateio_row[]" class="form-control form-control-sm" value="{{ $i->quantidade_rateio }}">
                                    </td>
                                    <td>
                                        <input readonly type="tel" name="quantidade_rateio_carga_row[]" class="form-control form-control-sm" value="{{ $i->unidadeCarga->quantidade_rateio }}">
                                    </td>
                                    <td>
                                        <input readonly type="tel" name="chave_nfe_row[]" class="form-control form-control-sm" value="{{ isset($i->nfe->chave) ? $i->nfe->chave : '' }}">
                                    </td>
                                    <td>
                                        <input readonly type="tel" name="chave_cte_row[]" class="form-control form-control-sm" value="{{ isset($i->cte->chave) ? $i->cte->chave : '' }}">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control form-control-sm" value="{{ $i->cidade->nome }}">
                                        <input readonly type="hidden" name="municipio_descarregamento_row[]" value="{{ $i->cidade->id }}">
                                    </td>
                                    <td>
                                        <input readonly type="text" name="lacres_transporte_row[]" class="form-control form-control-sm" value="{{ json_encode($i->lacresTransp->pluck('numero')->toArray()) }}">
                                    </td>
                                    <td>
                                        <input readonly type="text" name="lacres_unidade_row[]" class="form-control form-control-sm" value="{{ json_encode($i->lacresUnidCarga->pluck('numero')->toArray()) }}">
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-danger btn-delete-row">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rodapé / Infos Adicionais --}}
    <div class="col-12 mt-3">
        <div class="row g-2 rodape">
            <div class="col-md-6 col-12">
                {!! Form::text('info_complementar', 'Informação complementar (opcional)') !!}
            </div>
            <div class="col-md-6 col-12">
                {!! Form::text('info_adicional_fisco', 'Informação fiscal (opcional)') !!}
            </div>
            <div class="col-12 alerts mt-2"></div>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-end pt-3">
        <button type="submit" disabled class="btn btn-success btn-salvar-modulo btn-salvarMdfe">
            <i class="ri-save-line align-middle me-1"></i> Salvar MDF-e
        </button>
    </div>
</div>

@section('js')
<script src="/js/mdfe.js"></script>
@endsection

