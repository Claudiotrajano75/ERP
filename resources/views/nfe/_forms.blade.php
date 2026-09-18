@if(__countLocalAtivo() > 1 && __escolheLocalidade())
<div class="row mb-2">
    <div class="col-md-3">
        <label for="">Local</label>
        <select id="inp-local_id" required class="select2 class-required" data-toggle="select2" name="local_id">
            <option value="">Selecione</option>
            @foreach(__getLocaisAtivoUsuario() as $local)
            <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        @isset($item)
        @if($item->fornecedor)
        @php
        $isCompra = 1;
        @endphp
        @endif
        @endif
        @isset($isCompra)
        <input type="hidden" id="is_compra" name="is_compra" value="1">
        @endif

        @isset($isOrdemServico)
        <input type="hidden" name="ordem_servico_id" value="{{$item->id}}">
        @endif

        @isset($isPedidoEcommerce)
        <input type="hidden" name="pedido_ecommerce_id" value="{{$item->id}}">
        @endif

        @isset($isPedidoMercadoLivre)
        <input type="hidden" name="pedido_mercado_livre_id" value="{{$item->id}}">
        @endif

        @isset($isPedidoNuvemShop)
        <input type="hidden" name="pedido_nuvem_shop_id" value="{{$item->id}}">
        @endif

        @isset($cotacao)
        <input type="hidden" name="cotacao_id" value="{{$cotacao->id}}">
        @endif

        @isset($isReserva)
        <input type="hidden" name="reserva_id" value="{{$item->id}}">
        @endif

        @isset($isPedidoWoocommerce)
        <input type="hidden" name="pedido_woocommerce_id" value="{{$item->id}}">
        @endif

        <ul class="nav nav-pills nav-tabs-custom mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="pill" href="#cliente" role="tab" aria-selected="true">
                    <i class="ri-file-user-fill"></i>
                    <span>@isset($isCompra) Fornecedor @else Cliente @endif</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#produtos" role="tab" aria-selected="false">
                    <i class="ri-box-2-line"></i>
                    <span>Produtos</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#transportadora" role="tab" aria-selected="false">
                    <i class="ri-truck-line"></i>
                    <span>Frete</span>
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
            <!-- ══════════════ ABA: CLIENTE / FORNECEDOR ══════════════ -->
            <div class="tab-pane fade show active" id="cliente" role="tabpanel">
                @if(!isset($isCompra))
                <!-- SELEÇÃO DE CLIENTE -->
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-search-line text-primary"></i> Seleção do Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Cliente</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <select required id="inp-cliente_id" name="cliente_id" class="cliente_id">
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
                    </div>
                </div>

                <!-- DADOS DO CLIENTE -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-file-user-line text-primary"></i> Dados Cadastrais & Endereço</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 d-cliente">
                            <div class="col-md-4">
                                {!!Form::text('cliente_nome', 'Razão Social')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->cliente->razao_social : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('nome_fantasia', 'Nome Fantasia')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->cliente->nome_fantasia : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('cliente_cpf_cnpj', 'CPF / CNPJ')->attrs(['class' => 'form-control cpf_cnpj'])->required()
                                ->value(isset($item) ? $item->cliente->cpf_cnpj : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('ie', 'Inscrição Estadual')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->cliente->ie : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('telefone', 'Telefone / WhatsApp')->attrs(['class' => 'form-control fone'])
                                ->value(isset($item) ? $item->cliente->telefone : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('contribuinte', 'Contribuinte ICMS', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])
                                ->value(isset($item) ? $item->cliente->contribuinte : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('consumidor_final', 'Consumidor Final', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()
                                ->value(isset($item) ? $item->cliente->consumidor_final : '')
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('email', 'E-mail do Cliente')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->cliente->email : '')
                                !!}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required" for="inp-cidade_cliente">Cidade / UF</label>
                                <select required class="form-control select2 cidade_id" name="cliente_cidade" id="inp-cidade_cliente">
                                    <option value="">Selecione..</option>
                                    @foreach ($cidades as $c)
                                    <option @isset($item) @if($item->cliente->cidade_id == $c->id) selected @endif @endisset value="{{$c->id}}">{{$c->nome}} - {{$c->uf}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('cliente_rua', 'Logradouro / Rua')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->cliente->rua : '')
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::text('cliente_numero', 'Número')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->cliente->numero : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('cep', 'CEP')->attrs(['class' => 'form-control cep'])->required()
                                ->value(isset($item) ? $item->cliente->cep : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('cliente_bairro', 'Bairro')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->cliente->bairro : '')
                                !!}
                            </div>
                            <div class="col-md-12">
                                {!!Form::text('complemento', 'Complemento')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->cliente->complemento : '')
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                @else

                <!-- SELEÇÃO DE FORNECEDOR -->
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-search-line text-primary"></i> Seleção do Fornecedor</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Fornecedor</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <select id="inp-fornecedor_id" name="fornecedor_id" class="fornecedor_id">
                                            @isset($cotacao)
                                            <option value="{{ $cotacao->fornecedor_id }}">{{ $cotacao->fornecedor->razao_social }}</option>
                                            @else
                                            @isset($item)
                                            <option value="{{ $item->fornecedor_id }}">{{ $item->fornecedor->razao_social }}</option>
                                            @endif
                                            @endif
                                        </select>
                                    </div>
                                    @can('fornecedores_create')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_novo_fornecedor" type="button" style="height: 42px;">
                                        <i class="ri-add-fill fs-18"></i>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DADOS DO FORNECEDOR -->
                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-building-line text-primary"></i> Dados Cadastrais do Fornecedor</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 d-cliente">
                            <div class="col-md-4">
                                {!!Form::text('fornecedor_nome', 'Razão Social')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->fornecedor->razao_social : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('nome_fantasia', 'Nome Fantasia')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->fornecedor->nome_fantasia : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('fornecedor_cpf_cnpj', 'CPF / CNPJ')->attrs(['class' => 'form-control cpf_cnpj'])->required()
                                ->value(isset($item) ? $item->fornecedor->cpf_cnpj : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('ie', 'Inscrição Estadual')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->fornecedor->ie : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('telefone', 'Telefone / WhatsApp')->attrs(['class' => 'form-control fone'])
                                ->value(isset($item) ? $item->fornecedor->telefone : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('contribuinte', 'Contribuinte ICMS', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])
                                ->value(isset($item) ? $item->fornecedor->contribuinte : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('consumidor_final', 'Consumidor Final', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()
                                ->value(isset($item) ? $item->fornecedor->consumidor_final : '')
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('email', 'E-mail do Fornecedor')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->fornecedor->email : '')
                                !!}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required" for="inp-fornecedor_cidade">Cidade / UF</label>
                                <select required class="form-control select2 cidade_id" name="fornecedor_cidade" id="inp-fornecedor_cidade">
                                    <option value="">Selecione..</option>
                                    @foreach ($cidades as $c)
                                    <option @isset($item) @if($item->fornecedor->cidade_id == $c->id) selected @endif @endisset value="{{$c->id}}">{{$c->nome}} - {{$c->uf}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('fornecedor_rua', 'Logradouro / Rua')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->fornecedor->rua : '')
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::text('fornecedor_numero', 'Número')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->fornecedor->numero : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('cep', 'CEP')->attrs(['class' => 'form-control cep'])->required()
                                ->value(isset($item) ? $item->fornecedor->cep : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('fornecedor_bairro', 'Bairro')->attrs(['class' => 'form-control'])->required()
                                ->value(isset($item) ? $item->fornecedor->bairro : '')
                                !!}
                            </div>
                            <div class="col-md-12">
{!!Form::text('complemento', 'Complemento')->attrs(['class' => 'form-control'])
                                ->value(isset($item) ? $item->fornecedor->complemento : '')
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- ══════════════ ABA: PRODUTOS ══════════════ -->
            <div class="tab-pane fade" id="produtos" role="tabpanel">
                <div class="card card-secao-fiscal">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5><i class="ri-shopping-cart-2-line text-primary"></i> Grade de Itens da {{ isset($isCompra) ? 'Compra' : 'Venda' }}</h5>
                        <button type="button" class="dash-btn dash-btn-primary btn-add-tr-nfe" style="font-size: 12px; padding: 6px 14px;">
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
                                        <th>Nº do pedido</th>
                                        <th>Nº item do pedido</th>
                                        <th>Informação adicional do item</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($item))

                                    @foreach ($item->itens as $prod)

                                    @isset($isOrdemServico)
                                    @include('ordem_servico.partials.itens', ['prod' => $prod])
                                    @elseif(isset($isPedidoEcommerce))
                                    @include('pedido_ecommerce.partials.itens', ['prod' => $prod, 'cfop_estadual' => $item->cliente->cidade->uf])
                                    @elseif(isset($isPedidoMercadoLivre))
                                    @include('mercado_livre_pedidos.partials.itens', ['prod' => $prod, 'cfop_estadual' => $item->cliente->cidade->uf])

                                    @elseif(isset($isReserva))
                                    @include('mercado_livre_pedidos.partials.itens', ['prod' => $prod, 'cfop_estadual' => $item->cliente->cidade->uf])
                                    @elseif(isset($isPedidoWoocommerce))
                                    @include('woocommerce_pedidos.partials.itens', ['prod' => $prod, 'cfop_estadual' => $item->cliente->cidade->uf])

                                    @else
                                    <tr class="dynamic-form">
                                        <td width="250">
                                            <select style="width: 300px" class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id">
                                                <option value="{{ $prod->produto_id }}">{{ $prod->produto->nome }}</option>
                                            </select>
                                            @if($prod->variacao_id)
                                            <span>variação: <strong>{{ $prod->produtoVariacao->descricao }}</strong></span>
                                            @endif
                                            <input name="variacao_id[]" type="hidden" value="{{ $prod->variacao_id }}">
                                            <div style="width: 400px;"></div>
                                        </td>
                                        <td width="80">
                                            <input style="width: 150px" value="{{ __moeda($prod->quantidade) }}" class="form-control qtd next" type="tel" name="quantidade[]" id="inp-quantidade">
                                        </td>
                                        <td width="100">
                                            <input style="width: 150px" value="{{ __moeda($prod->valor_unitario) }}" class="form-control moeda valor_unit next" type="tel" name="valor_unitario[]" id="inp-valor_unitario">
                                        </td>
                                        <td width="150">
                                            <input style="width: 150px" value="{{ __moeda($prod->sub_total) }}" class="form-control moeda sub_total next" type="tel" name="sub_total[]" id="inp-subtotal">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_icms }}" class="form-control percentual" type="tel" name="perc_icms[]" id="inp-perc_icms">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_pis }}" class="form-control percentual" type="tel" name="perc_pis[]" id="inp-perc_pis">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_cofins }}" class="form-control percentual" type="tel" name="perc_cofins[]" id="inp-perc_cofins">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_ipi }}" class="form-control percentual" type="tel" name="perc_ipi[]" id="inp-perc_ipi">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_ibs }}" class="form-control percentual" type="tel" name="perc_ibs[]" id="inp-perc_ibs">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_cbs }}" class="form-control percentual" type="tel" name="perc_cbs[]" id="inp-perc_cbs">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->perc_red_bc }}" class="form-control percentual ignore" type="tel" name="perc_red_bc[]" id="inp-perc_red_bc">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" required value="{{ $prod->cfop }}" class="form-control cfop" type="tel" name="cfop[]" id="inp-cfop_estadual">
                                        </td>

                                        <td width="150">
                                            <input style="width: 120px" required value="{{ $prod->ncm }}" class="form-control ncm" type="tel" name="ncm[]" id="inp-ncm2">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" value="{{ $prod->codigo_beneficio_fiscal }}" class="form-control ignore codigo_beneficio_fiscal" type="text" name="codigo_beneficio_fiscal[]">
                                        </td>

                                        <td>
                                            <select name="cst_csosn[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCSTCSOSN() as $key => $c)
                                                <option @if($prod->cst_csosn == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <select name="cst_pis[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option @if($prod->cst_pis == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <select name="cst_cofins[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option @if($prod->cst_cofins == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <select name="cst_ipi[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_IPI() as $key => $c)
                                                <option @if($prod->cst_ipi == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <input value="{{ $prod->xPed }}" class="form-control ignore" maxlength="15" type="text" name="xPed[]">
                                            <div style="width: 200px"></div>
                                        </td>
                                        <td>
                                            <input value="{{ $prod->nItemPed }}" class="form-control ignore" maxlength="6" type="text" name="nItemPed[]">
                                            <div style="width: 200px"></div>
                                        </td>
                                        <td>
                                            <input value="{{ $prod->infAdProd }}" class="form-control ignore" maxlength="200" type="text" name="infAdProd[]">
                                            <div style="width: 300px"></div>
                                        </td>
                                        <td width="30">
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endisset
                                    @endforeach

                                    @else

                                    <tr class="dynamic-form">
                                        <td width="250">
                                            <select style="width: 300px" class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id">
                                            </select>
                                            <input name="variacao_id[]" type="hidden" value="">
                                            <div style="width: 400px;"></div>
                                        </td>
                                        <td width="80">
                                            <input style="width: 150px" class="form-control qtd next" type="tel" name="quantidade[]" id="inp-quantidade">
                                        </td>
                                        <td width="100">
                                            <input style="width: 150px" class="form-control moeda valor_unit next" type="tel" name="valor_unitario[]" id="inp-valor_unitario">
                                        </td>
                                        <td width="150">
                                            <input style="width: 150px" class="form-control moeda sub_total next" type="tel" name="sub_total[]" id="inp-subtotal">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual" type="tel" name="perc_icms[]" id="inp-perc_icms">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual" type="tel" name="perc_pis[]" id="inp-perc_pis">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual" type="tel" name="perc_cofins[]" id="inp-perc_cofins">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual" type="tel" name="perc_ipi[]" id="inp-perc_ipi">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual" type="tel" name="perc_ibs[]" id="inp-perc_ibs">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual" type="tel" name="perc_cbs[]" id="inp-perc_cbs">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control percentual ignore" type="tel" name="perc_red_bc[]" id="inp-perc_red_bc">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" required class="form-control cfop" type="tel" name="cfop[]" id="inp-cfop_estadual">
                                        </td>

                                        <td width="150">
                                            <input style="width: 120px" required class="form-control ncm" type="tel" name="ncm[]" id="inp-ncm2">
                                        </td>
                                        <td width="120">
                                            <input style="width: 120px" class="form-control ignore codigo_beneficio_fiscal" type="text" name="codigo_beneficio_fiscal[]">
                                        </td>

                                        <td>
                                            <select name="cst_csosn[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCSTCSOSN() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <select name="cst_pis[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <select name="cst_cofins[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <select name="cst_ipi[]" class="form-control select2">
                                                @foreach(App\Models\Produto::listaCST_IPI() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                            <div style="width: 400px"></div>
                                        </td>
                                        <td>
                                            <input class="form-control ignore" maxlength="15" type="text" name="xPed[]">
                                            <div style="width: 200px"></div>
                                        </td>
                                        <td>
                                            <input class="form-control ignore" maxlength="6" type="text" name="nItemPed[]">
                                            <div style="width: 200px"></div>
                                        </td>
                                        <td>
                                            <input class="form-control ignore" maxlength="200" type="text" name="infAdProd[]">
                                            <div style="width: 300px"></div>
                                        </td>
                                        <td width="30">
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 p-3 bg-light rounded-3 border">
                            <button type="button" class="dash-btn dash-btn-primary btn-add-tr-nfe">
                                <i class="ri-add-line"></i> Adicionar Mais um Produto
                            </button>
                            <h5 class="mb-0 fs-15 text-dark">
                                Total de Produtos: <strong class="total_prod text-primary fs-16">R$ 0,00</strong>
                            </h5>
                        </div>
                        <input type="hidden" class="total_prod" name="valor_produtos" id="" value="">
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA: FRETE / TRANSPORTE ══════════════ -->
            <div class="tab-pane fade" id="transportadora" role="tabpanel">
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-truck-line text-primary"></i> Transportadora</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                {!!Form::select('transportadora_id', 'Transportadora', ['' => 'Selecione..'] + $transportadoras->pluck('razao_social', 'id')->all())
                                ->attrs(['class' => 'form-select select2 transportadora_id'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-building-line text-primary"></i> Dados da Transportadora</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::text('razao_social_transp', 'Razão Social')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->razao_social : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('nome_fantasia_transp', 'Nome Fantasia')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->nome : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('cpf_cnpj_transp', 'CNPJ')->attrs(['class' => 'form-control cpf_cnpj'])
                                ->value(isset($item->transportadora) ? $item->transportadora->cpf_cnpj : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('ie_transp', 'Inscrição Estadual')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->ie : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('antt', 'ANTT')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->antt : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('telefone_transp', 'Telefone')->attrs(['class' => 'form-control fone'])
                                ->value(isset($item->transportadora) ? $item->transportadora->telefone : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('email_transp', 'E-mail')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->email : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::tel('rua_transp', 'Logradouro / Rua')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->rua : '')
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('numero_transp', 'Número')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->numero : '')
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('cidade_transp', 'Cidade')
                                ->attrs(['class' => 'form-select select2 cidade_select2'])
                                ->options(isset($item->transportadora) && isset($item->transportadora->cidade) ? [$item->transportadora->cidade_id => $item->transportadora->cidade->nome] : [])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('cep_transp', 'CEP')->attrs(['class' => 'form-control cep'])
                                ->value(isset($item->transportadora) ? $item->transportadora->cep : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('bairro_transp', 'Bairro')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->bairro : '')
                                !!}
                            </div>
                            <div class="col-md-8">
                                {!!Form::text('complemento_transp', 'Complemento')->attrs(['class' => 'form-control'])
                                ->value(isset($item->transportadora) ? $item->transportadora->complemento : '')
                                !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-secao-fiscal">
                    <div class="card-header">
                        <h5><i class="ri-box-3-line text-primary"></i> Informações do Frete & Volumes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                {!!Form::select('tipo', 'Modalidade de Frete', ['' => 'Selecione..'] + App\Models\Nfe::tiposFrete())
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('valor_frete', 'Valor do Frete')
                                ->attrs(['class' => 'form-control moeda valor_frete'])
                                ->value(isset($item) ? __moeda($item->valor_frete) : (isset($cotacao) ? __moeda($cotacao->valor_frete) : ''))
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('qtd_volumes', 'Qtd de Volumes')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('numeracao_volumes', 'Número de Volumes')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('peso_bruto', 'Peso Bruto (Kg)')
                                ->attrs(['class' => 'form-control peso'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::tel('peso_liquido', 'Peso Líquido (Kg)')
                                ->attrs(['class' => 'form-control peso'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('especie', 'Espécie dos Volumes')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::text('placa', 'Placa do Veículo')
                                ->attrs(['class' => 'form-control placa'])
                                !!}
                            </div>
                            <div class="col-md-1">
                                {!!Form::select('uf', 'UF', App\Models\Cidade::estados())
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════ ABA: FATURA & PARÂMETROS FISCAIS ══════════════ -->
            <div class="tab-pane fade" id="fatura" role="tabpanel">
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header">
                        <h5><i class="ri-file-settings-line text-primary"></i> Parâmetros de Emissão & Venda</h5>
                    </div>
                    <div class="card-body">
                        @php
                        // Natureza padrão por tipo de operação: compras usam natureza de compra, vendas/orçamentos usam natureza de venda
                        $defaultNaturezaId = isset($item) && $item->natureza_id ? $item->natureza_id : '';
                        if ($defaultNaturezaId == '' && isset($naturezas)) {
                            $ehCompra = isset($isCompra) && $isCompra == 1;
                            $naturezaTipo = $naturezas->first(function ($n) use ($ehCompra) {
                                $desc = mb_strtolower((string)$n->descricao);
                                $ehDeCompra = mb_strpos($desc, 'compra') !== false || mb_strpos($desc, 'entrada') !== false;
                                return $ehDeCompra == $ehCompra;
                            });
                            if ($naturezaTipo != null) {
                                $defaultNaturezaId = $naturezaTipo->id;
                            } elseif (isset($naturezaPadrao)) {
                                $defaultNaturezaId = $naturezaPadrao->id;
                            }
                        }
                        @endphp
                        <div class="row g-3">
                            <div class="col-md-4">
                                {!!Form::select('natureza_id', 'Natureza de Operação', ['' => 'Selecione'] + $naturezas->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'form-select'])
                                ->value($defaultNaturezaId)
                                ->required()
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('acrescimo', 'Acréscimo (R$)')
                                ->attrs(['class' =>'form-control acrescimo moeda'])
                                ->value(isset($item) ? __moeda($item->acrescimo) : '')
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::tel('desconto', 'Desconto (R$)')
                                ->attrs(['class' => 'form-control desconto moeda'])
                                ->value(isset($item) ? __moeda($item->desconto) : '')
                                !!}
                            </div>
                            <div class="col-md-4">
                                {!!Form::text('observacao', 'Observação da Nota')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>

                            @if(__isPlanoFiscal())
                            @if(isset($isOrdemServico) || isset($isPedidoEcommerce) || isset($isPedidoMercadoLivre) || isset($isReserva))
                            <div class="col-md-2">
                                {!!Form::tel('numero_nfe', 'Número NFe')
                                ->attrs(['class' => 'form-control'])
                                ->required()
                                ->value($numeroNfe)
                                !!}
                            </div>
                            @else
                            <div class="col-md-2">
                                {!!Form::tel('numero_nfe', 'Número NFe')
                                ->attrs(['class' => 'form-control'])
                                ->required()
                                ->value(isset($item) ? $item->numero : $numeroNfe)
                                !!}
                            </div>
                            @endif

                            <div class="col-md-4">
                                {!!Form::text('referencia', 'Referência NFe')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>
                            
                            <div class="col-md-2">
                                {!!Form::date('data_emissao_saida', 'Data Emissão Saída')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>

                            <div class="col-md-2">
                                {!!Form::date('data_emissao_retroativa', 'Data Emissão Retroativa')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>

                            <div class="col-md-2">
                                {!!Form::date('data_entrega', 'Data de Entrega')
                                ->attrs(['class' => 'form-control'])
                                !!}
                            </div>

                            @if(!isset($isCompra))
                            <div class="col-md-2">
                                {!!Form::select('tpNF', 'Tipo NFe', ['1' => 'Saída', '0' => 'Entrada'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @else
                            <div class="col-md-2">
                                {!!Form::select('tpNF', 'Tipo NFe', ['0' => 'Entrada'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @endif

                            <div class="col-md-2">
                                {!!Form::select('finNFe', 'Finalidade NFe', [
                                '1' => 'NFe normal',
                                '2' => 'NFe complementar',
                                '3' => 'NFe de ajuste',
                                '4' => 'Devolução de mercadoria'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @endif
                            
                            @if(!isset($item))
                            <div class="col-md-2 div-conta-receber">
                                {!!Form::select('gerar_conta_receber', 'Gerar Conta a Receber', [
                                0 => 'Não',
                                1 => 'Sim'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @else
                            @if(isset($item) && $item->orcamento == 0)
                            @can('conta_receber_create')
                            <div class="col-md-2 div-conta-receber">
                                {!!Form::select('gerar_conta_receber', 'Gerar Conta a Receber', [
                                0 => 'Não',
                                1 => 'Sim'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @endcan
                            @endif
                            @endif

                            @can('conta_pagar_create')
                            <div class="col-md-2 div-conta-pagar d-none">
                                {!!Form::select('gerar_conta_pagar', 'Gerar Conta a Pagar', [
                                0 => 'Não',
                                1 => 'Sim'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @endcan

                            @if(isset($isOrcamento) && $isOrcamento == 1)
                            <input type="hidden" value="1" name="orcamento">
                            @else
                            @if(!isset($isCompra))
                            @if(!isset($item))
                            @can('orcamento_create')
                            <div class="col-md-2">
                                {!!Form::select('orcamento', 'Salvar como Orçamento', [
                                0 => 'Não',
                                1 => 'Sim'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @endcan
                            @endif
                            @endif
                            @endif

                            @if(!isset($isCompra))
                            <div class="col-md-3">
                                {!! Form::select('funcionario_id', 'Vendedor')
                                ->attrs(['class' => 'form-select'])
                                ->options(isset($item) && $item->funcionario ? [$item->funcionario->id => $item->funcionario->nome] : [])
                                !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- FATURA / PARCELAS -->
                <div class="card card-secao-fiscal mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="ri-money-dollar-circle-line text-primary"></i> Parcelamento & Fatura</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-gerar-fatura" data-bs-toggle="modal" data-bs-target="#modal_fatura_venda">
                            <i class="ri-list-indefinite"></i> Gerar Fatura Automática
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-custom table-dynamic table-fatura mb-3">
                                <thead>
                                    <tr>
                                        <th>Forma de Pagamento</th>
                                        <th>Data de Vencimento</th>
                                        <th>Valor da Parcela</th>
                                        <th width="60">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="body-pagamento" class="datatable-body">
                                    @isset($cotacao)
                                    @if(sizeof($cotacao->fatura) > 0)
                                    @foreach ($cotacao->fatura as $f)
                                    <tr class="dynamic-form">
                                        <td>
                                            <select name="tipo_pagamento[]" class="form-select tipo_pagamento select2">
                                                <option value="">Selecione..</option>
                                                @foreach(App\Models\Nfe::tiposPagamento() as $key => $c)
                                                <option @if($f->tipo_pagamento == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input value="{{ $f->data_vencimento }}" type="date" class="form-control" name="data_vencimento[]">
                                        </td>
                                        <td>
                                            <input value="{{ __moeda($f->valor) }}" type="tel" class="form-control moeda valor_fatura" name="valor_fatura[]">
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
                                            <select name="tipo_pagamento[]" class="form-select tipo_pagamento select2">
                                                <option value="">Selecione..</option>
                                                @foreach(App\Models\Nfe::tiposPagamento() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control date_atual" name="data_vencimento[]">
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control moeda valor_fatura" name="valor_fatura[]">
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @else
                                    @if(isset($item) && isset($item->fatura) && sizeof($item->fatura) > 0 && !isset($isOrdemServico))
                                    @foreach ($item->fatura as $f)
                                    <tr class="dynamic-form">
                                        <td>
                                            <select name="tipo_pagamento[]" class="form-select tipo_pagamento select2">
                                                <option value="">Selecione..</option>
                                                @foreach(App\Models\Nfe::tiposPagamento() as $key => $c)
                                                <option @if($f->tipo_pagamento == $key) selected @endif value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input value="{{ $f->data_vencimento }}" type="date" class="form-control" name="data_vencimento[]">
                                        </td>
                                        <td>
                                            <input value="{{ __moeda($f->valor) }}" type="tel" class="form-control moeda valor_fatura" name="valor_fatura[]">
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
                                            <select name="tipo_pagamento[]" class="form-select tipo_pagamento select2">
                                                <option value="">Selecione..</option>
                                                @foreach(App\Models\Nfe::tiposPagamento() as $key => $c)
                                                <option value="{{$key}}">{{$c}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control date_atual" name="data_vencimento[]">
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control moeda valor_fatura" name="valor_fatura[]">
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm btn-remove-tr">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary btn-sm btn-add-tr btn-add-tr-fatura px-3">
                                    <i class="ri-add-line"></i> Adicionar Parcela Manual
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARDS DE TOTAIS -->
                <div class="row g-3 mb-2">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Fatura</span>
                            <h4 class="mb-0 mt-1 fw-bold text-dark total_fatura">R$ 0,00</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Produtos</span>
                            <h4 class="mb-0 mt-1 fw-bold text-dark total_prod">R$ 0,00</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Frete</span>
                            <h4 class="mb-0 mt-1 fw-bold text-dark total_frete">R$ 0,00</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-primary-subtle rounded-3 border border-primary-subtle">
                            <span class="text-primary fs-12 fw-bold text-uppercase">Total da Nota Fiscal</span>
                            <h4 class="mb-0 mt-1 fw-bold text-primary total_nfe">R$ 0,00</h4>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="valor_total" name="valor_total" id="" value="">
            </div>
        </div>
    </div>

    <!-- RODAPÉ DE AÇÕES -->
    <div class="col-12 modulo-actions d-flex align-items-center justify-content-end gap-2 mt-4">
        <a href="{{ isset($isCompra) ? route('compras.index') : route('nfe.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary btn-salvar-nfe px-5">
            <i class="ri-save-line me-1"></i> {{ isset($isCompra) ? 'Salvar Compra' : (isset($isOrcamento) && $isOrcamento == 1 ? 'Salvar Orçamento' : 'Salvar Venda') }}
        </button>
    </div>
</div>

@include('modals._cartao_credito', ['not_submit' => true])
@include('modals._variacao')
@include('modals._fatura_venda')


