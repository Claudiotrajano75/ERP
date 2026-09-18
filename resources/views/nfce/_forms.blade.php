<div class="row">
    <div class="col-md-12">
        <!-- ═══ NAVEGAÇÃO POR ABAS ═══ -->
        <ul class="nav nav-pills nav-tabs-custom mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="pill" href="#cliente" role="tab" aria-selected="true">
                    <i class="ri-file-user-fill"></i>
                    <span>Cliente</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#produtos" role="tab" aria-selected="false">
                    <i class="ri-box-2-line"></i>
                    <span>Produtos</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#fatura" role="tab" aria-selected="false">
                    <i class="ri-coins-line"></i>
                    <span>Fatura</span>
                </a>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <!-- ══════════════ ABA 1: CLIENTE ══════════════ -->
            <div class="tab-pane fade show active" id="cliente" role="tabpanel">
                
                <!-- IDENTIFICAÇÃO RÁPIDA / CPF NA NOTA -->
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-qr-code-line text-primary"></i> Identificação na Nota (CPF / Nome no Cupom)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                {!!Form::text('cliente_nome', 'Nome no Cupom')->attrs(['class' => 'form-control', 'placeholder' => 'Nome do cliente consumidor']) !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::tel('cliente_cpf_cnpj', 'CPF / CNPJ na Nota')->attrs(['class' => 'form-control cpf_cnpj', 'placeholder' => '000.000.000-00']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DADOS CADASTRAIS COMPLETOS DO CLIENTE -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-file-user-line text-primary"></i> Dados Cadastrais & Endereço Completo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Cliente Cadastrado</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <select id="inp-cliente_id" name="cliente_id" class="form-select cliente_id">
                                            @if(isset($item) && $item->cliente)
                                            <option value="{{ $item->cliente_id }}">{{ $item->cliente->razao_social }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    @can('clientes_create')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_novo_cliente" type="button" style="height: 42px;">
                                        <i class="ri-add-fill fs-18"></i>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3 d-cliente">
                            <div class="col-md-4">
                                {!!Form::text('nome', 'Razão Social')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->razao_social : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('nome_fantasia', 'Nome Fantasia')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->nome_fantasia : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('cpf_cnpj', 'CPF / CNPJ')->attrs(['class' => 'form-control cpf_cnpj'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->cpf_cnpj : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('ie', 'Inscrição Estadual')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->ie : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('telefone', 'Telefone / WhatsApp')->attrs(['class' => 'form-control fone'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->telefone : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('contribuinte', 'Contribuinte ICMS', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->contribuinte : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('consumidor_final', 'Consumidor Final', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->consumidor_final : 1)
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('email', 'E-mail do Cliente')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->email : '')
                                !!}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="inp-cidade_cliente">Cidade / UF</label>
                                <select class="form-control select2 cidade_id" name="cliente_cidade" id="inp-cidade_cliente">
                                    <option value="">Selecione..</option>
                                    @foreach ($cidades as $c)
                                    <option @isset($item) && @isset($item->cliente) @if($item->cliente->cidade_id == $c->id) selected @endif @endisset @endisset value="{{$c->id}}">{{$c->nome}} - {{$c->uf}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('cliente_rua', 'Logradouro / Rua')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->rua : '')
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::text('cliente_numero', 'Número')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->numero : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('cep', 'CEP')->attrs(['class' => 'form-control cep'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->cep : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('cliente_bairro', 'Bairro')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->bairro : '')
                                !!}
                            </div>
                            <div class="col-md-12">
                                {!!Form::text('complemento', 'Complemento')->attrs(['class' => 'form-control'])
                                ->value(isset($item) && $item->cliente ? $item->cliente->complemento : '')
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 2: PRODUTOS ══════════════ -->
            <div class="tab-pane fade" id="produtos" role="tabpanel">
                <div class="card card-secao-fiscal">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="ri-shopping-cart-2-line text-primary"></i> Grade de Produtos da NFCe</h5>
                        <button type="button" class="dash-btn dash-btn-primary btn-sm btn-add-tr-nfce">
                            <i class="ri-add-line"></i> Adicionar Produto
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-custom table-dynamic table-produtos" style="width: 2800px">
                                <thead>
                                    <tr>
                                        <th class="sticky-col first-col">Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unit.</th>
                                        <th>Subtotal</th>
                                        <th>%ICMS</th>
                                        <th>%PIS</th>
                                        <th>%COFINS</th>
                                        <th>%IPI</th>
                                        <th>%IBS</th>
                                        <th>%CBS</th>
                                        <th>%RED BC</th>
                                        <th>CFOP</th>
                                        <th>NCM</th>
                                        <th>Código benefício</th>
                                        <th>CST CSOSN</th>
                                        <th>CST PIS</th>
                                        <th>CST COFINS</th>
                                        <th>CST IPI</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($item)
                                    @foreach ($item->itens as $prod)
                                    <tr class="dynamic-form">
                                        <td width="250">
                                            <select class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id">
                                                <option value="{{ $prod->produto_id }}">{{ $prod->produto->nome }}</option>
                                            </select>
                                            @if($prod->variacao_id)
                                            <span>variação: <strong>{{ $prod->produtoVariacao->descricao }}</strong></span>
                                            @endif
                                            <input name="variacao_id[]" type="hidden" value="{{ $prod->variacao_id }}">
                                        </td>
                                        <td width="80">
                                            <input value="{{ __moeda($prod->quantidade) }}" class="form-control qtd" type="tel" name="quantidade[]" id="inp-quantidade">
                                        </td>
                                        <td width="100">
                                            <input value="{{ __moeda($prod->valor_unitario) }}" class="form-control moeda valor_unit" type="tel" name="valor_unitario[]" id="inp-valor_unitario">
                                        </td>
                                        <td width="150">
                                            <input value="{{ __moeda($prod->sub_total) }}" class="form-control moeda sub_total" type="tel" name="sub_total[]" id="inp-subtotal">
                                        </td>
                                        <td width="80">
                                            <input value="{{ $prod->perc_icms }}" class="form-control" type="tel" name="perc_icms[]" id="inp-perc_icms">
                                        </td>
                                        <td width="100">
                                            <input value="{{ $prod->perc_pis }}" class="form-control" type="tel" name="perc_pis[]" id="inp-perc_pis">
                                        </td>
                                        <td width="100">
                                            <input value="{{ $prod->perc_cofins }}" class="form-control" type="tel" name="perc_cofins[]" id="inp-perc_cofins">
                                        </td>
                                        <td width="100">
                                            <input style="width: 100px" value="{{ $prod->perc_ipi }}" class="form-control" type="tel" name="perc_ipi[]" id="inp-perc_ipi">
                                        </td>
                                        <td width="100">
                                            <input style="width: 100px" value="{{ $prod->perc_ibs }}" class="form-control" type="tel" name="perc_ibs[]" id="inp-perc_ibs">
                                        </td>
                                        <td width="100">
                                            <input style="width: 100px" value="{{ $prod->perc_cbs }}" class="form-control" type="tel" name="perc_cbs[]" id="inp-perc_cbs">
                                        </td>
                                        <td width="100">
                                            <input style="width: 100px" value="{{ $prod->perc_red_bc }}" class="form-control percentual ignore" type="tel" name="perc_red_bc[]" id="inp-perc_red_bc">
                                        </td>
                                        <td width="120">
                                            <input value="{{ $prod->cfop }}" class="form-control ignore" type="tel" name="cfop[]" id="inp-cfop_estadual">
                                        </td>
                                        <td width="150">
                                            <input value="{{ $prod->ncm }}" class="form-control ignore" type="tel" name="ncm[]" id="inp-ncm2">
                                        </td>
                                        <td width="120">
                                            <input value="{{ $prod->codigo_beneficio_fiscal }}" class="form-control codigo_beneficio_fiscal ignore" type="text" name="codigo_beneficio_fiscal[]">
                                        </td>
                                        <td width="250">
                                            <select name="cst_csosn[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCSTCSOSN() as $key => $c)
                                                <option @if($prod->cst_csosn == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="250">
                                            <select name="cst_pis[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option @if($prod->cst_pis == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="250">
                                            <select name="cst_cofins[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option @if($prod->cst_cofins == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="250">
                                            <select name="cst_ipi[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_IPI() as $key => $c)
                                                <option @if($prod->cst_ipi == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="30"> 
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="dynamic-form">
                                        <td width="250">
                                            <select required class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id">
                                            </select>
                                            <input name="variacao_id[]" type="hidden" value="">
                                        </td>
                                        <td width="80">
                                            <input class="form-control qtd" type="tel" name="quantidade[]" id="inp-quantidade">
                                        </td>
                                        <td width="120">
                                            <input class="form-control moeda valor_unit" type="tel" name="valor_unitario[]" id="inp-valor_unitario">
                                        </td>
                                        <td width="150">
                                            <input class="form-control moeda sub_total" type="tel" name="sub_total[]" id="inp-subtotal">
                                        </td>
                                        <td width="120">
                                            <input class="form-control" type="tel" name="perc_icms[]" id="inp-perc_icms">
                                        </td>
                                        <td width="120">
                                            <input class="form-control" type="tel" name="perc_pis[]" id="inp-perc_pis">
                                        </td>
                                        <td width="120">
                                            <input class="form-control" type="tel" name="perc_cofins[]" id="inp-perc_cofins">
                                        </td>
                                        <td width="120">
                                            <input class="form-control" type="tel" name="perc_ipi[]" id="inp-perc_ipi">
                                        </td>
                                        <td width="120">
                                            <input class="form-control" type="tel" name="perc_ibs[]" id="inp-perc_ibs">
                                        </td>
                                        <td width="120">
                                            <input class="form-control" type="tel" name="perc_cbs[]" id="inp-perc_cbs">
                                        </td>
                                        <td width="120">
                                            <input class="form-control percentual ignore" type="tel" name="perc_red_bc[]" id="inp-perc_red_bc">
                                        </td>
                                        <td width="120">
                                            <input class="form-control ignore" type="tel" name="cfop[]" id="inp-cfop_estadual">
                                        </td>
                                        <td width="150">
                                            <input class="form-control ignore" type="tel" name="ncm[]" id="inp-cfop_outro_estado">
                                        </td>
                                        <td width="120">
                                            <input class="form-control codigo_beneficio_fiscal ignore" type="text" name="codigo_beneficio_fiscal[]">
                                        </td>
                                        <td width="250">
                                            <select name="cst_csosn[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCSTCSOSN() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="250">
                                            <select name="cst_pis[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="250">
                                            <select name="cst_cofins[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="250">
                                            <select name="cst_ipi[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_IPI() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="30"> 
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endisset
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 p-3 bg-light rounded-3">
                            <button type="button" class="btn btn-primary btn-add-tr-nfce">
                                <i class="ri-add-line"></i> Adicionar Produto
                            </button>
                            <h5 class="mb-0 fs-16 text-dark">
                                Total de Produtos: <strong class="total_prod text-primary">R$ 0,00</strong>
                            </h5>
                        </div>

                        <input type="hidden" class="total_prod" name="valor_total" value="">
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA 3: FATURA & PAGAMENTOS ══════════════ -->
            <div class="tab-pane fade" id="fatura" role="tabpanel">
                
                <!-- PARÂMETROS FISCAIS -->
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-file-settings-line text-primary"></i> Detalhes da Emissão NFCe</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::select('natureza_id', 'Natureza de Operação', ['' => 'Selecione'] + $naturezas->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'form-select'])
                                ->value(isset($item) ? $item->natureza_id : (isset($naturezaPadrao) && $naturezaPadrao != null ? $naturezaPadrao->id : '') )
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('acrescimo', 'Acréscimo (R$)')
                                ->attrs(['class' => 'form-control moeda acrescimo'])
                                ->value(isset($item) ? __moeda($item->acrescimo) : '')
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('desconto', 'Desconto (R$)')
                                ->attrs(['class' => 'form-control moeda desconto'])
                                ->value(isset($item) ? __moeda($item->desconto) : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('observacao', 'Observação da NFCe')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('numero_nfce', 'Número NFCe')
                                ->attrs(['class' => 'form-control'])
                                ->required()
                                ->value(isset($item) ? $item->numero : $numeroNfce)
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('gerar_conta_receber', 'Gerar Conta a Receber', [
                                0 => 'Não',
                                1 => 'Sim'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FATURA E FORMAS DE PAGAMENTO -->
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="ri-coins-line text-primary"></i> Formas de Pagamento</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-custom table-dynamic table-fatura mb-3">
                                <thead>
                                    <tr>
                                        <th>Tipo de Pagamento</th>
                                        <th>Data de Vencimento</th>
                                        <th>Valor da Parcela</th>
                                        <th width="60">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="body-pagamento" class="datatable-body">
                                    @if(isset($item) && sizeof($item->fatura) > 0)
                                    @foreach ($item->fatura as $f)
                                    <tr class="dynamic-form">
                                        <td>
                                            <select required name="tipo_pagamento[]" class="form-select select2">
                                                <option value="">Selecione..</option>
                                                @foreach(App\Models\Nfce::tiposPagamento() as $key => $c)
                                                <option @if($f->tipo_pagamento == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input required value="{{ $f->data_vencimento }}" type="date" class="form-control date_atual" name="data_vencimento[]">
                                        </td>
                                        <td>
                                            <input required value="{{ __moeda($f->valor) }}" type="tel" class="form-control moeda valor_fatura" name="valor_fatura[]" id="valor">
                                        </td>
                                        <td class="text-center"> 
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="dynamic-form">
                                        <td>
                                            <select required name="tipo_pagamento[]" class="form-select select2">
                                                <option value="">Selecione..</option>
                                                @foreach(App\Models\Nfce::tiposPagamento() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input required type="date" class="form-control date_atual" name="data_vencimento[]">
                                        </td>
                                        <td>
                                            <input required type="tel" class="form-control moeda valor_fatura" name="valor_fatura[]" id="valor">
                                        </td>
                                        <td class="text-center"> 
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endisset
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="dash-btn dash-btn-light btn-sm btn-add-tr px-3">
                                    <i class="ri-add-line"></i> Adicionar Pagamento
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARDS DE TOTAIS -->
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total da Fatura</span>
                            <h4 class="mb-0 mt-1 fw-bold text-dark total_fatura">R$ 0,00</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total de Produtos</span>
                            <h4 class="mb-0 mt-1 fw-bold text-dark total_prod">R$ 0,00</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-primary-subtle rounded-3 border border-primary-subtle">
                            <span class="text-primary fs-12 fw-bold text-uppercase">Total da NFCe</span>
                            <h4 class="mb-0 mt-1 fw-bold text-primary total_nfe">R$ 0,00</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- RODAPÉ DE AÇÕES -->
    <div class="col-12 modulo-actions d-flex align-items-center justify-content-end gap-2 mt-4">
        <a href="{{ route('nfce.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary btn-salvar-nfe px-4" id="btn-store">
            <i class="ri-save-line"></i> Salvar NFCe
        </button>
    </div>
</div>

@include('modals._variacao')
