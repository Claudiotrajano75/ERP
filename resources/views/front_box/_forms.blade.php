@section('css')
    <style>
        /* Estilos mínimos essenciais - maior parte está em pdv.css */
        #salvar_venda:hover {
            cursor: pointer;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection

<input type="hidden" id="abertura" value="{{ $abertura }}" name="">
<input type="hidden" id="tef_hash" value="" name="tef_hash">
<input type="hidden" id="config_tef" value="{{ isset($configTef) && $configTef != null ? 1 : 0 }}">
<input type="hidden" id="agrupar_itens" value="{{ $config ? $config->agrupar_itens : 0 }}" name="">
<input type="hidden" id="venda_id" value="{{ isset($item) ? $item->id : '' }}">
<input type="hidden" id="lista_id" value="" name="lista_id">
<input type="hidden" id="alerta_sonoro" value="{{ $config ? $config->alerta_sonoro : 0 }}">

@if($isVendaSuspensa)
    <input type="hidden" value="{{ $item->id }}" name="venda_suspensa_id">
@endif

@isset($pedido)
@isset($isDelivery)
    <input name="pedido_delivery_id" id="pedido_delivery_id" value="{{ $pedido->id }}" class="d-none">
    <input id="pedido_desconto" value="{{ $pedido->desconto ? $pedido->desconto : 0 }}" class="d-none">
    <input id="pedido_valor_entrega" value="{{ $pedido->valor_entrega }}" class="d-none">
@else
<input name="pedido_id" id="pedido_id" value="{{ $pedido->id }}" class="d-none">
@endif
@endif

@if(isset($config))
    <input type="hidden" id="inp-abrir_modal_cartao" value="{{ $config != null ? $config->abrir_modal_cartao : 0 }}">
    <input type="hidden" id="inp-senha_manipula_valor" value="{{ $config != null ? $config->senha_manipula_valor : '' }}">
@else
    <input type="hidden" id="inp-abrir_modal_cartao" value="0">
    <input type="hidden" id="inp-senha_manipula_valor" value="">
@endif

@isset($agendamento)
<input name="agendamento_id" value="{{ $agendamento->id }}" class="d-none">
@endif

<input type="hidden" id="estoque_view" value="@can('estoque_view') 1 @else 0 @endif">

<div class="row">            <div class="col-lg-4">
        <div class="row g-2">
            <div class="col-lg-6">
                <div class="card pdv-card-client">
                    <div class="card-body pdv-fin-card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center">
                                    <h5 class="pdv-card-label text-muted mb-0">Cliente
                                        @isset($cliente)
                                            <span class="pdv-badge-status pdv-badge-selected">✓ Selecionado</span>
                                        @else
                                            <span class="pdv-badge-status pdv-badge-pending">○ Pendente</span>
                                        @endif
                                    </h5>
                                </div>
                                @isset($cliente)
                                    <h6 class="pdv-card-value cliente_selecionado mt-1">{{ $cliente->razao_social }}</h6>
                                @else
                                    <h6 class="pdv-card-value-empty cliente_selecionado mt-1"><i class="ri-user-search-line"></i> Nenhum cliente selecionado</h6>
                                @endif
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <button type="button"
                                    class="pdv-card-icon-btn text-bg-success btn-selecionar_cliente"
                                    data-bs-toggle="modal" data-bs-target="#cliente"
                                    title="Selecionar Cliente">
                                    <i class="ri-group-line fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card pdv-card-seller">
                    <div class="card-body pdv-fin-card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center">
                                    <h5 class="pdv-card-label text-muted mb-0">Vendedor
                                        @isset($funcionario)
                                            <span class="pdv-badge-status pdv-badge-selected">✓ Selecionado</span>
                                        @else
                                            <span class="pdv-badge-status pdv-badge-pending">○ Pendente</span>
                                        @endif
                                    </h5>
                                </div>
                                @isset($funcionario)
                                    <h6 class="pdv-card-value funcionario_selecionado mt-1">{{ $funcionario->nome }}</h6>
                                @else
                                    <h6 class="pdv-card-value-empty funcionario_selecionado mt-1"><i class="ri-user-search-line"></i> Nenhum vendedor selecionado</h6>
                                @endif
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <button type="button"
                                    class="pdv-card-icon-btn text-bg-warning"
                                    data-bs-toggle="modal" data-bs-target="#funcionario"
                                    title="Selecionar Vendedor">
                                    <i class="ri-user-2-line fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="min-height: calc(100vh - 150px)">

            <div class="card pdv-categories-wrapper m-1 border-0 shadow-none">
                <div class="pdv-categories-header">
                    <h6 class="pdv-categories-title"><i class="ri-grid-fill me-1"></i>Categorias</h6>
                </div>
                <hr class="m-0 mb-1" style="border-top: 1px solid #e0e0e0;">
                <div class="pdv-categories-scroll">
                    <button type="button" class="pdv-nav-arrow" id="cat-scroll-left" onclick="document.querySelector('.pdv-categories-container').scrollBy({left: -200, behavior: 'smooth'})">
                        <i class="ri-arrow-left-s-line"></i>
                    </button>
                    <div class="pdv-categories-container" id="cat-container">
                        <button type="button" id="cat_todos" onclick="todos()" class="btn-pdv-cat btn-cat active">Todos</button>
                        @foreach ($categorias as $cat)
                            <button type="button" class="btn-pdv-cat btn-cat btn_cat_{{ $cat->id }}"
                                onclick="selectCat('{{ $cat->id }}')">{{$cat->nome}}</button>
                        @endforeach
                    </div>
                    <button type="button" class="pdv-nav-arrow" id="cat-scroll-right" onclick="document.querySelector('.pdv-categories-container').scrollBy({left: 200, behavior: 'smooth'})">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>
            </div>                <div class="card-body lista_produtos m-1" data-simplebar data-simplebar-lg
                style="max-height: calc(100vh - 320px);">
                <div class="row cards-categorias">

                </div>
            </div>
            <div class="row" style="margin-top: 0px">
                <div class="col-1 text-center">
                    <input class="mousetrap" type="" autofocus
                        style="border: none; width: 10px; height: 10px; background-color:black" id="codBarras" name="">
                </div>
                <div class="col-6 leitor_ativado text-info">
                    Leitor Ativado
                </div>
                <div class="col-6 leitor_desativado d-none">
                    Leitor Desativado
                </div>
                @if(__countLocalAtivo() > 1 && $caixa->localizacao)
                    <div class="col-5 text-end">
                        <strong class="text-danger" style="margin-right: 5px;">{{ $caixa->localizacao->descricao }}</strong>
                    </div>
                @endif

            </div>

        </div>
    </div>
    <div class="col-lg-8 produtos">
        <div class="card" style="min-height: calc(100vh - 150px)">
            <div class="row m-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inp-produto_id" class="">Produto <span class="pdv-shortcut pdv-shortcut-sm">F1</span></label>
                        <div class="input-group">
                            <select class="form-control produto_id" name="produto_id" id="inp-produto_id"></select>
                        </div>
                        <input name="variacao_id" id="inp-variacao_id" type="hidden" value="">

                    </div>
                </div>
                <div class="col-md-2">
                    {!! Form::tel('quantidade', 'Quantidade')->attrs(['data-mask' => '00000,000', 'data-mask-reverse' => "true"]) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::tel('valor_unitario', 'Valor Unitário')->attrs(['class' => 'moeda value_unit']) !!}
                </div>
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-12">
                            <br>
                            <button class="btn btn-primary btn-add-item w-100" type="button"
                                style="margin-left: 0px">Adicionar</button>
                        </div>

                    </div>
                </div>
                <div class="col-md-1">
                    {!! Form::hidden('subtotal', 'SubTotal')->attrs(['class' => 'moeda']) !!}
                    {!! Form::hidden('valor_total', 'valor Total')->attrs(['class' => 'moeda']) !!}
                </div>
            </div>
            <div class="card m-1">
                <div data-bs-target="#navbar-example2" class="scrollspy-example table-responsive"
                    style="height: calc(100vh - 355px)">
                    <table class="table table-striped dt-responsive nowrap table-itens pdv-table-items">
                        <thead>
                            <tr>
                                <th style="width:44px"></th>
                                <th>Produto <span class="pdv-cart-count badge bg-success rounded-pill ms-1">0</span></th>
                                <th style="width:130px">Quantidade</th>
                                <th style="width:100px">Valor</th>
                                <th style="width:100px">Subtotal</th>
                                <th style="width:40px">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($item))
                                @foreach ($item->itens as $key => $product)
                                    <tr class="line-product">
                                        <input readonly type="hidden" name="key" class="form-control"
                                            value="{{ $product->key }}">
                                        <input readonly type="hidden" name="produto_id[]" class="produto_row"
                                            value="{{ $product->produto->id }}">
                                        <input name="variacao_id[]" type="hidden" value="{{ $product->variacao_id }}">

                                        <td>
                                            <img src="{{ $product->produto->img }}"
                                                style="width: 30px; height: 40px; border-radius: 10px;">
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="text" name="produto_nome[]"
                                                class="form-control"
                                                value="{{ $product->produto->nome }} @if($product->produtoVariacao != null) - {{ $product->produtoVariacao->descricao }} @endif">
                                        </td>

                                        <td class="datatable-cell">
                                            <div class="form-group mb-2" style="width: 140px">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button id="btn-subtrai" class="btn btn-danger" type="button">-</button>
                                                    </div>
                                                    <input type="tel" readonly class="form-control qtd qtd_row"
                                                        name="quantidade[]"
                                                        value="{{ number_format($product->quantidade, 2, ',', '') }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" id="btn-incrementa"
                                                            type="button">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="tel" name="valor_unitario[]"
                                                class="form-control value-unit" value="{{ __moeda($product->valor_unitario) }}">
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="tel" name="subtotal_item[]"
                                                class="form-control subtotal-item"
                                                value="{{ __moeda($product->valor_unitario * $product->quantidade) }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete-row"><i
                                                    class="ri-delete-bin-line"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (isset($servicos))
                                @foreach ($servicos as $key => $servico)
                                    <tr>
                                        <input readonly type="hidden" name="servico_id[]" class="form-control"
                                            value="{{ $servico->servico->id }}">

                                        <td>
                                            <img src="{{ $servico->servico->img }}"
                                                style="width: 30px; height: 40px; border-radius: 10px;">
                                        </td>
                                        <td style="width: 100%">
                                            <input readonly type="text" name="servico_nome[]" class="form-control"
                                                value="{{ $servico->servico->nome }} [serviço]" style="color: darkred;">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button disabled id="btn-subtrai" class="btn btn-danger"
                                                        type="button">-</button>
                                                </div>
                                                <input readonly type="tel" name="quantidade_servico[]"
                                                    class="form-control qtd-item"
                                                    value="{{ number_format($servico->quantidade, 0) }}">
                                                <div class="input-group-append">
                                                    <button disabled class="btn btn-success" id="btn-incrementa"
                                                        type="button">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input readonly type="tel" name="valor_unitario_servico[]" class="form-control"
                                                value="{{ __moeda($servico->valor) }}">
                                        </td>
                                        <td>
                                            <input readonly type="tel" name="subtotal_servico[]"
                                                class="form-control subtotal-item"
                                                value="{{ __moeda($servico->valor * $servico->quantidade) }}">
                                        </td>
                                        <td>
                                            <button disabled type="button" class="btn btn-danger btn-sm btn-delete-row"><i
                                                    class="ri-delete-bin-line"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (isset($pedido) && isset($itens))
                                @foreach ($itens as $key => $product)
                                    <tr class="line-product">
                                        <input readonly type="hidden" name="key" class="form-control"
                                            value="{{ $product->key }}">
                                        <input readonly type="hidden" name="produto_id[]" class="produto_row"
                                            value="{{ $product->produto->id }}">
                                        <input name="variacao_id[]" type="hidden" value="{{ $product->variacao_id }}">

                                        <td>
                                            <img src="{{ $product->produto->img }}"
                                                class="pdv-item-img" alt="{{ $product->produto->nome }}">
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="text" name="produto_nome[]"
                                                class="pdv-item-name"
                                                value="{{ $product->produto->nome }} @if($product->produtoVariacao != null) - {{ $product->produtoVariacao->descricao }} @endif">
                                        </td>

                                        <td class="datatable-cell">
                                            <div class="pdv-qty-group">
                                                <button class="pdv-qty-btn pdv-qty-btn-minus" id="btn-subtrai" type="button">-</button>
                                                <input type="tel" readonly class="pdv-qty-input qtd qtd_row"
                                                    name="quantidade[]"
                                                    value="{{ number_format($product->quantidade, 2, ',', '') }}">
                                                <button class="pdv-qty-btn pdv-qty-btn-plus" id="btn-incrementa" type="button">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="tel" name="valor_unitario[]"
                                                class="pdv-item-value value-unit" value="{{ __moeda($product->valor_unitario) }}">
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="tel" name="subtotal_item[]"
                                                class="pdv-item-subtotal subtotal-item"
                                                value="{{ __moeda($product->valor_unitario * $product->quantidade) }}">
                                        </td>
                                        <td>
                                            <button type="button" class="pdv-btn-delete btn-delete-row"><i
                                                    class="ri-delete-bin-line"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-1">

                {{-- Finalização foi comentada no dia 26-06-2026 para da mais espaço na tela --}}
                {{-- <h5 class="text-center mb-2 mt-1 fw-bold"><i class="ri-shopping-cart-2-fill me-1"></i>Finalização</h5> --}}


                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header">
                                    <h5 class="pdv-fin-label">Desconto <span class="pdv-shortcut">F2</span></h5>
                                    <button type="button" onclick="setaDesconto()"
                                        class="pdv-fin-icon-box text-bg-primary shadow-sm">
                                        <i class="ri-checkbox-indeterminate-line"></i>
                                    </button>
                                </div>
                                <h4 class="pdv-fin-value" id="valor_desconto">R$
                                    {{ isset($item) ? __moeda($item->desconto) : '0,00' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header">
                                    <h5 class="pdv-fin-label">Acréscimo <span class="pdv-shortcut">F3</span></h5>
                                    <button type="button" onclick="setaAcrescimo()"
                                        class="pdv-fin-icon-box text-bg-warning shadow-sm"
                                        title="F3 - Abrir Acréscimo">
                                        <i class="ri-add-box-line"></i>
                                    </button>
                                </div>
                                <h4 class="pdv-fin-value" id="valor_acrescimo">R$
                                    {{ isset($item) ? __moeda($item->acrescimo) : '0,00' }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="row g-0">
                                    <div class="col-6 text-center">
                                        <h6 class="pdv-fin-label mb-1">SUPRIM.</h6>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#suprimento_caixa"
                                            class="pdv-fin-icon-box text-bg-info shadow-sm mx-auto">
                                            <i class="ri-add-box-line"></i>
                                        </button>
                                    </div>
                                    <div class="col-6 text-center">
                                        <h6 class="pdv-fin-label mb-1">SANGRIA</h6>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#sangria_caixa"
                                            class="pdv-fin-icon-box text-bg-danger shadow-sm mx-auto">
                                            <i class="ri-checkbox-indeterminate-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card pdv-fin-total">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header">
                                    <h5 class="pdv-fin-label">TOTAL</h5>
                                    <span class="pdv-fin-icon-box text-bg-light shadow-sm" style="color:#333;">
                                        <i class="ri-shopping-cart-fill"></i>
                                    </span>
                                </div>
                                <h4 class="pdv-fin-value">
                                    @isset($item)
                                        <strong class="total-venda">{{ __moeda($item->valor_total) }}</strong>
                                    @else
                                        <strong class="total-venda">0,00</strong>
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header mb-1">
                                    <h5 class="pdv-fin-label">Pagamento</h5>
                                    <span class="pdv-fin-icon-box text-bg-success shadow-sm">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                                {!! Form::select('tipo_pagamento', '', ['' => 'Selecione'] + $tiposPagamento)->attrs(['class' => 'form-select pdv-pagamento-select tp-pag'])->value(isset($item) ? $item->tipo_pagamento : '') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6 div-troco d-none">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header mb-1">
                                    <h5 class="pdv-fin-label">Recebido</h5>
                                    <span class="pdv-fin-icon-box text-bg-danger shadow-sm">
                                        <i class="ri-hand-coin-line"></i>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1" style="min-width:0">
                                        {!! Form::tel('valor_recebido', '')->attrs(['class' => 'moeda form-control form-control-sm text-end', 'placeholder' => '0,00']) !!}
                                    </div>
                                    <div class="pdv-troco-badge flex-shrink-0 text-center">
                                        <span class="pdv-troco-label">Troco</span>
                                        <strong class="pdv-troco-value" id="valor-troco">R$ 0,00</strong>
                                        <input type="hidden" name="troco" id="inp-troco">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-6 div-vencimento d-none">
                        <div class="pdv-vencimento-card h-100">
                            <h6 class="pdv-vencimento-label"><i class="ri-calendar-line me-1"></i>Data de Vencimento</h6>
                            {!! Form::date('data_vencimento', '')->attrs(['class' => 'form-control form-control-sm data_atual']) !!}
                        </div>
                    </div> <!-- end col-->
                    <div class="col">
                        <div class="col">
                            <div class="card widget-icon-box div-pagamento mb-1">
                                <div class="card-body p-2">
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <button type="button"
                                                class="btn pdv-action-btn btn-outline-info w-100 btn-pagamento-multi"
                                                data-bs-toggle="modal" data-bs-target="#pagamento_multiplo"
                                                title="F4 - Pagamento Múltiplo"><i
                                                    class="ri-list-check-3"></i> Pag. Multi <span class="pdv-shortcut pdv-shortcut-sm">F4</span></button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button"                                                    class="btn pdv-action-btn btn-outline-secondary w-100"
                                                data-bs-toggle="modal" data-bs-target="#lista_precos"
                                                title="Lista de Preços"><i
                                                    class="ri-cash-line"></i> Preços</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button"                                                    class="btn pdv-action-btn btn-outline-primary w-100"
                                                data-bs-toggle="modal" data-bs-target="#observacao_pdv"
                                                title="Observação"><i
                                                    class="ri-file-edit-fill"></i> Observ.</button>
                                        </div>
                                        <div class="col-6">
                                            @if(!isset($item))
                                                <button type="button"
                                                    class="btn pdv-action-btn btn-outline-dark w-100 btn-vendas-suspensas"
                                                    data-bs-toggle="modal" data-bs-target="#vendas_suspensas"
                                                    title="Histórico de Vendas Suspensas"><i
                                                        class="ri-time-fill"></i> Histór.</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col">
                        <div class="col">
                            <div class="card widget-icon-box div-pagamento mb-1">
                                <div class="card-body p-2">
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <a class="btn pdv-action-btn btn-outline-danger w-100" href="{{ route('frontbox.index')}}">
                                                <i class="ri-arrow-left-s-line"></i> Sair
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            @if($isVendaSuspensa == 0)
                                                <button type="button" id="btn-suspender" class="btn pdv-action-btn btn-outline-warning w-100">
                                                    <i class="ri-timer-line"></i> Susp.
                                                </button>
                                            @else
                                                <a href="{{ route('frontbox.create') }}" class="btn pdv-action-btn btn-outline-warning w-100">
                                                    <i class="ri-refresh-line"></i> Nova
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            @if(isset($item) && $isVendaSuspensa == 0)
                                                <button type="button" class="pdv-btn-finalizar mt-1" disabled
                                                    id="editar_venda"
                                                    title="F5 - Editar Venda">
                                                    <i class="ri-checkbox-line"></i> Editar <span class="pdv-shortcut pdv-shortcut-light pdv-shortcut-sm">F5</span>
                                                </button>
                                            @else
                                                <button type="button" class="pdv-btn-finalizar mt-1 pdv-animate-pulse-finalizar" disabled
                                                    id="salvar_venda"
                                                    title="F5 - Finalizar Venda">
                                                    <i class="ri-checkbox-circle-line"></i> Finalizar <span class="pdv-shortcut pdv-shortcut-light pdv-shortcut-sm">F5</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                {{-- <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        {!! Form::select('forma_pagamento', 'Forma de Pagamento')->attrs(['class' => 'form-select']) !!}
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        {!! Form::select('tipo_pagamento', 'Tipo de Pagamento')->attrs(['class' => 'form-select']) !!}
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</div>

@include('modals._pagamento_multiplo', ['not_submit' => true])
@include('modals._finalizar_venda', ['not_submit' => true])
@include('modals._funcionario', ['not_submit' => true])
@include('modals._cartao_credito', ['not_submit' => true])
@include('modals._variacao', ['not_submit' => true])
@include('modals._lista_precos')
@include('modals._vendas_suspensas')
@include('modals._tef_consulta')
@include('modals._observacao_pdv')
@include('modals._cliente', ['cashback' => 1])

@section('js')
<script src="/js/frente_caixa.js?v={{time()}}" type=""></script>
<script type="text/javascript" src="/js/mousetrap.js?v={{time()}}"></script>
<script type="text/javascript" src="/js/controla_conta_empresa.js?v={{time()}}"></script>
<script src="/js/pdv_session.js?v={{time()}}"></script>
<script src="/js/novo_cliente.js?v={{time()}}"></script>

<script type="text/javascript">

    @if(Session::has('sangria_id'))
        window.open(path_url + 'sangria-print/' + {{ Session::get('sangria_id') }}, "_blank")
    @endif
    @if(Session::has('suprimento_id'))
        window.open(path_url + 'suprimento-print/' + {{ Session::get('suprimento_id') }}, "_blank")
    @endif

    $('.btn-novo-cliente').click(() => {
        $('.modal-select-cliente .btn-close').trigger('click')
        $('#modal_novo_cliente').modal('show')

    })
</script>

@endsection
