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
<input type="hidden" id="valor_total_old" value="{{ $item->total }}">
<input type="hidden" id="venda_id" value="{{ isset($item) ? $item->id : '' }}">
<input type="hidden" id="lista_id" value="" name="lista_id">
@if($isVendaSuspensa)
<input type="hidden" value="{{ $item->id }}" name="venda_suspensa_id">
@endif

@isset($pedido)
@isset($isDelivery)
<input name="pedido_delivery_id" id="pedido_delivery_id" value="{{ $pedido->id }}" class="d-none">
<input id="pedido_desconto" value="{{ $pedido->desconto }}" class="d-none">
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

<div class="row align-items-stretch">
    <!-- COLUNA DA ESQUERDA (Categorias e Leitores) -->
    <div class="col-lg-4">
        <div class="row g-2">
            <!-- Cliente da Venda -->
            <div class="col-lg-6">
                <div class="card pdv-card-client">
                    <div class="card-body pdv-fin-card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center">
                                    <h5 class="pdv-card-label text-muted mb-0">Cliente Original
                                        @isset($cliente)
                                            <span class="pdv-badge-status pdv-badge-selected pdv-badge-cliente">✓</span>
                                        @else
                                            <span class="pdv-badge-status pdv-badge-pending pdv-badge-cliente">Pendente</span>
                                        @endif
                                    </h5>
                                </div>
                                @isset($cliente)
                                    <h6 class="pdv-card-value cliente_selecionado mt-1">{{ $cliente->razao_social }}</h6>
                                @else
                                    <h6 class="pdv-card-value-empty cliente_selecionado mt-1"><i class="ri-user-search-line"></i> Consumidor Final</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Vendedor Original -->
            <div class="col-lg-6">
                <div class="card pdv-card-seller">
                    <div class="card-body pdv-fin-card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center">
                                    <h5 class="pdv-card-label text-muted mb-0">Vendedor Original
                                        @isset($funcionario)
                                            <span class="pdv-badge-status pdv-badge-selected pdv-badge-vendedor">✓</span>
                                        @else
                                            <span class="pdv-badge-status pdv-badge-pending pdv-badge-vendedor">Pendente</span>
                                        @endif
                                    </h5>
                                </div>
                                @isset($funcionario)
                                    <h6 class="pdv-card-value funcionario_selecionado mt-1">{{ $funcionario->nome }}</h6>
                                @else
                                    <h6 class="pdv-card-value-empty funcionario_selecionado mt-1"><i class="ri-user-search-line"></i> Sem Vendedor</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="min-height: calc(100vh - 190px); display: flex; flex-direction: column;">
            <div class="card pdv-categories-wrapper m-1 border-0 shadow-none">
                <div class="pdv-categories-header">
                    <h6 class="pdv-categories-title"><i class="ri-grid-fill me-1"></i>Categorias</h6>
                </div>
                <hr class="m-0 mb-1" style="border-top: 1px solid #e0e0e0;">
                <div class="pdv-categories-scroll">
                    <button type="button" class="pdv-nav-arrow" id="cat-scroll-left"
                        onclick="document.querySelector('.pdv-categories-container').scrollBy({left: -200, behavior: 'smooth'})">
                        <i class="ri-arrow-left-s-line"></i>
                    </button>
                    <div class="pdv-categories-container" id="cat-container">
                        <button type="button" id="cat_todos" onclick="todos()"
                            class="btn-pdv-cat btn-cat active">Todos</button>
                        @foreach ($categorias as $cat)
                            <button type="button" class="btn-pdv-cat btn-cat btn_cat_{{ $cat->id }}"
                                onclick="selectCat('{{ $cat->id }}')">{{$cat->nome}}</button>
                        @endforeach
                    </div>
                    <button type="button" class="pdv-nav-arrow" id="cat-scroll-right"
                        onclick="document.querySelector('.pdv-categories-container').scrollBy({left: 200, behavior: 'smooth'})">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>
            </div>
            <div class="card-body lista_produtos m-1" data-simplebar data-simplebar-lg
                style="flex: 1 1 auto; min-height: 0; height: calc(100vh - 340px); overflow-y: auto;">
                <div class="row cards-categorias"></div>
            </div>
            
            <div class="row align-items-center px-2 pb-2 g-2" style="margin-top: 0px">
                <div class="col">
                    <button type="button" id="btn-leitor-toggle" class="btn pdv-leitor-toggle leitor-on w-100"
                        title="Clique para desativar o leitor de código de barras">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="ri-barcode-line fs-5"></i>
                            <span class="pdv-leitor-label leitor_ativado">Leitor Ativado</span>
                            <span class="pdv-leitor-label leitor_desativado d-none">Leitor Desativado</span>
                        </span>
                        <span class="pdv-leitor-switch"><i class="ri-toggle-fill"></i></span>
                    </button>
                    <input type="text" class="mousetrap pdv-barcode-input" autofocus id="codBarras" name=""
                        autocomplete="off">
                </div>
                @if(__countLocalAtivo() > 1 && $caixa->localizacao)
                    <div class="col-auto ms-auto text-end">
                        <strong class="text-danger">{{ $caixa->localizacao->descricao }}</strong>
                    </div>
                @endif
            </div>

        </div>
    </div>
    
    <!-- COLUNA DA DIREITA (Carrinho e Finalização) -->
    <div class="col-lg-8 produtos">
        <div class="card" style="min-height: calc(100vh - 190px);">
            <!-- Adicionar Item Row -->
            <div class="row m-2 align-items-end pdv-add-row g-2">
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <label for="inp-produto_id" class="pdv-add-label">
                            <i class="ri-search-line me-1"></i>Produto <span class="pdv-shortcut pdv-shortcut-sm">F1</span>
                        </label>
                        <div class="input-group">
                            <select class="form-control produto_id" name="produto_id" id="inp-produto_id"></select>
                        </div>
                        <input name="variacao_id" id="inp-variacao_id" type="hidden" value="">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="pdv-add-label"><i class="ri-numbers-line me-1"></i>Qtd.</label>
                        {!! Form::tel('quantidade', false)->attrs(['data-mask' => '00000,000', 'data-mask-reverse' => "true", 'class' => 'form-control text-center pdv-add-input']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="pdv-add-label"><i class="ri-price-tag-2-line me-1"></i>Valor Unit.</label>
                        {!! Form::tel('valor_unitario', false)->attrs(['class' => 'moeda value_unit form-control text-end pdv-add-input']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-add-item w-100" type="button">
                        <i class="ri-add-circle-line me-1"></i>Adicionar
                    </button>
                </div>
                <div class="col-md-1 d-none">
                    {!! Form::hidden('subtotal', 'SubTotal')->attrs(['class' => 'moeda']) !!}
                    {!! Form::hidden('valor_total', 'valor Total')->attrs(['class' => 'moeda']) !!}
                    {!! Form::hidden('valor_pagar', '')->attrs(['class' => '']) !!}
                    {!! Form::hidden('valor_credito', '')->attrs(['class' => '']) !!}
                </div>
            </div>

            <div class="card m-1">
                <div data-bs-target="#navbar-example2" class="scrollspy-example table-responsive"
                    style="height: calc(100vh - 395px)">
                    <table class="table table-striped dt-responsive nowrap table-itens pdv-table-items">
                        <thead>
                            <tr>
                                <th style="width:44px"></th>
                                <th>Produto</th>
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
                                            <img src="{{ $product->produto->img }}" class="pdv-item-img"
                                                alt="{{ $product->produto->nome }}">
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="text" name="produto_nome[]"
                                                class="pdv-item-name"
                                                value="{{ $product->produto->nome }} @if($product->produtoVariacao != null) - {{ $product->produtoVariacao->descricao }} @endif">
                                        </td>

                                        <td class="datatable-cell">
                                            <div class="pdv-qty-group">
                                                <button class="pdv-qty-btn pdv-qty-btn-minus btn-qtd" id="btn-subtrai"
                                                    type="button">-</button>
                                                <input type="tel" readonly class="pdv-qty-input qtd qtd_row" name="quantidade[]"
                                                    value="{{ number_format($product->quantidade, 2, ',', '') }}">
                                                <button class="pdv-qty-btn pdv-qty-btn-plus btn-qtd" id="btn-incrementa"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="tel" name="valor_unitario[]"
                                                class="pdv-item-value value-unit"
                                                value="{{ __moeda($product->valor_unitario) }}">
                                        </td>
                                        <td>
                                            <input style="width: 100%" readonly type="tel" name="subtotal_item[]"
                                                class="pdv-item-subtotal subtotal-item"
                                                value="{{ __moeda($product->valor_unitario * $product->quantidade) }}">
                                        </td>
                                        <td>
                                            <button type="button" class="pdv-btn-delete btn-delete-row">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
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
                                            <img src="{{ $servico->servico->img }}" class="pdv-item-img"
                                                alt="{{ $servico->servico->nome }}">
                                        </td>
                                        <td style="width: 100%">
                                            <input readonly type="text" name="servico_nome[]" class="pdv-item-name text-danger"
                                                value="{{ $servico->servico->nome }} [serviço]">
                                        </td>
                                        <td>
                                            <div class="pdv-qty-group opacity-75">
                                                <button disabled id="btn-subtrai" class="pdv-qty-btn pdv-qty-btn-minus"
                                                    type="button">-</button>
                                                <input readonly type="tel" name="quantidade_servico[]"
                                                    class="pdv-qty-input qtd-item"
                                                    value="{{ number_format($servico->quantidade, 0) }}">
                                                <button disabled id="btn-incrementa" class="pdv-qty-btn pdv-qty-btn-plus"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <input readonly type="tel" name="valor_unitario_servico[]" class="pdv-item-value"
                                                value="{{ __moeda($servico->valor) }}">
                                        </td>
                                        <td>
                                            <input readonly type="tel" name="subtotal_servico[]"
                                                class="pdv-item-subtotal subtotal-item"
                                                value="{{ __moeda($servico->valor * $servico->quantidade) }}">
                                        </td>
                                        <td>
                                            <button disabled type="button" class="pdv-btn-delete btn-delete-row">
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

            <!-- Finalização -->
            <div class="mt-1 px-3 pb-2">
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
                                <h4 class="pdv-fin-value" id="valor_desconto">R$ 0,00</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header">
                                    <h5 class="pdv-fin-label">Acréscimo <span class="pdv-shortcut">F3</span></h5>
                                    <button type="button" onclick="setaAcrescimo()"
                                        class="pdv-fin-icon-box text-bg-warning shadow-sm" title="F3 - Abrir Acréscimo">
                                        <i class="ri-add-box-line"></i>
                                    </button>
                                </div>
                                <h4 class="pdv-fin-value" id="valor_acrescimo">R$ 0,00</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="row g-0">
                                    <div class="col-6 text-center">
                                        <h6 class="pdv-fin-label mb-1">SUPRIM.</h6>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#suprimento_caixa"
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
                                    <h5 class="pdv-fin-label">NOVA VENDA <span class="badge bg-primary rounded-pill pdv-cart-count" style="font-size: 0.75rem; margin-left: 5px; vertical-align: middle;">0</span></h5>
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
                    </div>
                </div>

                <div class="row">
                    <!-- Valores Fixos de Referência da Troca -->
                    <div class="col-lg-6">
                        <div class="card pdv-fin-card border-0 mb-0">
                            <div class="card-body pdv-fin-card-body p-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="pdv-fin-label text-muted mb-0"><i class="ri-history-line"></i> Valor Original</h6>
                                    <h5 class="mb-0 text-primary">R$ {{ __moeda($item->total) }}</h5>
                                    <p class="text-danger mt-1 mb-0" style="font-size: 0.85rem;">Data da venda: <strong>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</strong></p>
                                </div>
                                
                                <div class="text-end">
                                    <h6 class="pdv-fin-label text-muted mb-0 h-valor_pagar">Diferença a Pagar</h6>
                                    <h6 class="pdv-fin-label text-muted mb-0 h-valor_restante d-none">Crédito Gerado</h6>
                                    
                                    <h5 class="mb-0 text-success valor_pagar">R$ {{ __moeda(0) }}</h5>
                                    <h5 class="mb-0 text-warning valor_restante d-none">R$ {{ __moeda(0) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="card widget-icon-box div-pagamento mb-0 h-100">
                            <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                <a class="btn pdv-action-btn btn-outline-danger w-100"
                                    href="{{ route('frontbox.index')}}">
                                    <i class="ri-arrow-left-s-line"></i> Sair do PDV
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="card widget-icon-box div-pagamento mb-0 h-100">
                            <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                <button type="button"
                                    class="pdv-btn-finalizar w-100 pdv-animate-pulse-finalizar"
                                    id="salvar_venda" data-bs-toggle="modal" data-bs-target="#finalizar_troca">
                                    <i class="ri-checkbox-circle-line"></i> Finalizar Troca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals._pagamento_multiplo', ['not_submit' => true])
@include('modals._finalizar_troca', ['not_submit' => true])
@include('modals._funcionario', ['not_submit' => true])
@include('modals._cartao_credito', ['not_submit' => true])
@include('modals._variacao', ['not_submit' => true])
@include('modals._lista_precos')
@include('modals._vendas_suspensas')
@include('modals._cliente', ['cashback' => 1])

@section('js')

@if($msgTroca != "")
<script type="text/javascript">
        toastr.warning('{{ $msgTroca }}');
</script>
@endif
<script src="/js/frente_caixa.js" type=""></script>
<script type="text/javascript" src="/js/mousetrap.js"></script>
<script type="text/javascript" src="/js/controla_conta_empresa.js"></script>
<script type="text/javascript" src="/js/controla_troca.js"></script>
<script src="/js/novo_cliente.js"></script>

<script type="text/javascript">
    // Na tela de troca o botão Finalizar Troca nunca deve ser bloqueado pela validação do frente_caixa.js, 
    // pois a lógica de pagamento ou geração de crédito/cobrança só ocorre após o modal
    function validateButtonSave() {
        $('#salvar_venda').removeAttr('disabled');
    }

    @if(Session::has('sangria_id'))
    PrintThermal.imprimir('sangria', {{ Session::get('sangria_id') }}, path_url + 'sangria-print/' + {{ Session::get('sangria_id') }})
    @endif
    @if(Session::has('suprimento_id'))
    PrintThermal.imprimir('suprimento', {{ Session::get('suprimento_id') }}, path_url + 'suprimento-print/' + {{ Session::get('suprimento_id') }})
    @endif

    $('.btn-novo-cliente').click(() => {
        $('.modal-select-cliente .btn-close').trigger('click')
        $('#modal_novo_cliente').modal('show')

    })
</script>

@endsection
