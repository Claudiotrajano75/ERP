@section('css')
    <style>
        @media (max-width: 1200px) {
            .card {
                height: auto !important;
                min-height: auto !important;
            }

            .scrollspy-example {
                height: auto !important;
                max-height: 400px;
            }
        }
    </style>
@endsection

<input type="hidden" id="lista_id" value="" name="lista_id">

<input type="hidden" id="abertura" value="{{ $abertura }}" name="">
@isset($pedido)
<input name="pedido_id" id="pedido_id" value="{{ $pedido->id }}" class="d-none">
@endif
<div class="row">
    <div class="col-lg-4">
        <div class="row g-2">
            <div class="col-lg-6">
                <div class="card pdv-card-client">
                    <div class="card-body pdv-fin-card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center">
                                    <h5 class="pdv-card-label text-muted mb-0">Cliente
                                        @isset($cliente)
                                            <span class="pdv-badge-status pdv-badge-selected pdv-badge-cliente">✓
                                                Selecionado</span>
                                        @else
                                            <span class="pdv-badge-status pdv-badge-pending pdv-badge-cliente">○
                                                Pendente</span>
                                        @endisset
                                    </h5>
                                </div>
                                @isset($cliente)
                                    <h6 class="pdv-card-value cliente_selecionado mt-1">{{ $cliente->razao_social }}</h6>
                                @else
                                    <h6 class="pdv-card-value-empty cliente_selecionado mt-1"><i
                                            class="ri-user-search-line"></i> Nenhum</h6>
                                @endisset
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <button type="button" class="pdv-card-icon-btn text-bg-success btn-selecionar_cliente"
                                    data-bs-toggle="modal" data-bs-target="#cliente" title="Selecionar Cliente">
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
                                            <span class="pdv-badge-status pdv-badge-selected pdv-badge-vendedor">✓
                                                Selecionado</span>
                                        @else
                                            <span class="pdv-badge-status pdv-badge-pending pdv-badge-vendedor">○
                                                Pendente</span>
                                        @endisset
                                    </h5>
                                </div>
                                @isset($funcionario)
                                    <h6 class="pdv-card-value vendedor_selecionado funcionario_selecionado mt-1">
                                        {{ $funcionario->nome }}
                                    </h6>
                                @else
                                    <h6 class="pdv-card-value-empty vendedor_selecionado funcionario_selecionado mt-1"><i
                                            class="ri-user-search-line"></i> Nenhum</h6>
                                @endisset
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <button type="button" class="pdv-card-icon-btn text-bg-warning" data-bs-toggle="modal"
                                    data-bs-target="#funcionario" title="Selecionar Vendedor">
                                    <i class="ri-user-2-line fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="min-height: calc(100vh - 150px)">
            <div class="col-11" style="margin-left: 24px">
                {{-- {!!Form::select('produto_id', '')->attrs(['class' => 'select2'])
                !!} --}}
            </div>
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
                                onclick="selectCat('{{ $cat->id }}')">{{ $cat->nome }}</button>
                        @endforeach
                    </div>
                    <button type="button" class="pdv-nav-arrow" id="cat-scroll-right"
                        onclick="document.querySelector('.pdv-categories-container').scrollBy({left: 200, behavior: 'smooth'})">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>
            </div>
            <div class="card-body lista_produtos m-1" data-simplebar data-simplebar-lg
                style="max-height: calc(100vh - 320px);">
                <div class="row cards-categorias"></div>
            </div>
            <div class="row">
                <div class="col-1 text-center">
                    <input class="mousetrap" type="" autofocus
                        style="border: none; width: 10px; height: 10px; background-color:black" id="codBarras" name="">
                </div>
                <div class="col-5 leitor_ativado text-info">
                    Leitor Ativado
                </div>
                <div class="col-5 leitor_desativado d-none">
                    Leitor Desativado
                </div>
                <div class="col-6 text-end mx-3">
                    <a href="{{ route('pre-venda.create') }}" class="btn pdv-action-btn btn-outline-primary btn-sm">
                        <i class="ri-refresh-line"></i> Nova Prevenda
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-8 produtos">
        <div class="card" style="min-height: calc(100vh - 150px)">
            <div class="row m-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inp-produto_id" class="">Produto <span
                                class="pdv-shortcut pdv-shortcut-sm">F1</span></label>
                        <div class="input-group">
                            <select class="form-control produto_id" name="produto_id" id="inp-produto_id"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    {!! Form::tel('quantidade', 'Quantidade')->attrs(['class' => 'qtd']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::tel('valor_unitario', 'Valor Unitário')->attrs(['class' => 'moeda value_unit']) !!}
                </div>
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-11 ">
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
                                <th>Produto</th>
                                <th style="width:130px">Quantidade</th>
                                <th style="width:100px">Valor</th>
                                <th style="width:100px">Subtotal</th>
                                <th style="width:40px">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($itens))
                                @foreach ($itens as $key => $product)
                                    <tr class="line-product">
                                        <input readonly type="hidden" name="key" class="form-control"
                                            value="{{ $product->key }}">
                                        <input readonly type="hidden" name="produto_id[]" class="produto_row"
                                            value="{{ $product->produto->id }}">
                                        <td>
                                            <img src="{{ $product->produto->img }}" class="pdv-item-img"
                                                alt="{{ $product->produto->nome }}">
                                        </td>
                                        <td>
                                            <input readonly type="text" name="produto_nome[]" class="pdv-item-name"
                                                value="{{ $product->produto->nome }}">
                                        </td>
                                        <td>
                                            <div class="pdv-qty-group">
                                                <button class="pdv-qty-btn pdv-qty-btn-minus" id="btn-subtrai"
                                                    type="button">-</button>
                                                <input readonly type="tel" name="quantidade[]" class="pdv-qty-input qtd-item"
                                                    value="{{ number_format($product->quantidade, 0) }}">
                                                <button class="pdv-qty-btn pdv-qty-btn-plus" id="btn-incrementa"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <input readonly type="tel" name="valor_unitario[]" class="pdv-item-value"
                                                value="{{ __moeda($product->valor_unitario) }}">
                                        </td>
                                        <td>
                                            <input readonly type="tel" name="subtotal_item[]"
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
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-1">
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
                                <input type="hidden" name="desconto" id="inp-valor_desconto">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header">
                                    <h5 class="pdv-fin-label">Acréscimo <span class="pdv-shortcut">F3</span></h5>
                                    <button type="button" onclick="setaAcrescimo()"
                                        class="pdv-fin-icon-box text-bg-warning shadow-sm">
                                        <i class="ri-add-box-line"></i>
                                    </button>
                                </div>
                                <h4 class="pdv-fin-value" id="valor_acrescimo">R$ 0,00</h4>
                                <input type="hidden" name="acrescimo" id="inp-valor_acrescimo">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card">
                            <div class="card-body pdv-fin-card-body">
                                <div class="pdv-fin-header">
                                    <h5 class="pdv-fin-label">SUPRIM.</h5>
                                    <span class="pdv-fin-icon-box text-bg-info shadow-sm">
                                        <i class="ri-add-box-line"></i>
                                    </span>
                                </div>
                                <h4 class="pdv-fin-value" style="color: #6c757d; font-size: 13px;">Pré-venda</h4>
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
                    </div>
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
                                {!! Form::select('tipo_pagamento', '', ['' => 'Selecione'] + App\Models\Nfce::tiposPagamento())->attrs(['class' => 'form-select pdv-pagamento-select', 'id' => 'inp-tipo_pagamento'])->value(isset($item) ? $item->tipo_pagamento : '') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-6 div-vencimento d-none">
                        <div class="pdv-vencimento-card h-100">
                            <h6 class="pdv-vencimento-label"><i class="ri-calendar-line me-1"></i>Data de Vencimento
                            </h6>
                            {!! Form::date('data_vencimento', '')->attrs(['class' => 'form-control form-control-sm data_atual']) !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="card widget-icon-box div-pagamento mb-1">
                            <div class="card-body p-2">
                                <div class="row g-1">
                                    <div class="col-4">
                                        <button type="button" class="btn pdv-action-btn btn-outline-info w-100"
                                            data-bs-toggle="modal" data-bs-target="#pagamento_multiplo">
                                            <i class="ri-list-check-3"></i> Pag. Multi <span
                                                class="pdv-shortcut pdv-shortcut-sm">F4</span>
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn pdv-action-btn btn-outline-primary w-100"
                                            data-bs-toggle="modal" data-bs-target="#observacao_pdv"
                                            title="Observação"><i class="ri-file-edit-fill"></i> Observ.
                                        </button>
                                    </div>
                                    <br>
                                    <div class="col-4">
                                        <a class="btn pdv-action-btn btn-outline-danger w-100"
                                            href="{{ route('pre-venda.index') }}">
                                            <i class="ri-arrow-left-s-line"></i> Sair
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" disabled class="pdv-btn-finalizar" id="salvar_pre_venda">
                                            <i class="ri-checkbox-circle-line"></i> Finalizar <span
                                                class="pdv-shortcut pdv-shortcut-light pdv-shortcut-sm">F5</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script src="/js/pre_venda.js?v={{ filemtime(public_path('js/pre_venda.js')) }}"></script>
    <script type="text/javascript" src="/js/mousetrap.js"></script>

    <script type="text/javascript">
        // Atalhos de teclado
        $(document).on('keydown', function (e) {
            if (e.key === 'F1' || e.key === 'F2' || e.key === 'F3' ||
                e.key === 'F4' || e.key === 'F5') {
                e.preventDefault();
            }
        });

        if (typeof Mousetrap !== 'undefined') {
            Mousetrap.bind('f1', function () {
                $('#inp-produto_id').select2('open');
                return false;
            });
            Mousetrap.bind('f2', function () {
                setaDesconto();
                return false;
            });
            Mousetrap.bind('f3', function () {
                setaAcrescimo();
                return false;
            });
            Mousetrap.bind('f4', function () {
                if ($('#pagamento_multiplo').length) {
                    $('#pagamento_multiplo').modal('show');
                }
                return false;
            });
            Mousetrap.bind('f5', function () {
                let btn = $('#salvar_pre_venda');
                if (btn.length && !btn.prop('disabled')) {
                    btn.trigger('click');
                }
                return false;
            });
        }

    </script>

    @if(session()->has('codigo'))
        <!-- Notificação de impressão -->
        <div id="print-notification" style="
                                position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999;
                                background: #155724; color: #fff;
                                padding: 16px 24px;
                                display: flex; align-items: center; justify-content: center; gap: 20px;
                                font-size: 16px; font-family: inherit;
                                box-shadow: 0 -4px 20px rgba(0,0,0,0.25);
                                animation: slideUpPrint 0.4s ease;
                            ">
            <span>✅ &nbsp;Pré-venda finalizada! Deseja imprimir o comprovante?</span>
            <div style="display: flex; gap: 10px;">
                <button onclick="imprimirComprovante()" style="
                                        background: #fff; color: #155724; border: none;
                                        padding: 8px 24px; border-radius: 6px;
                                        font-weight: bold; cursor: pointer;
                                        font-size: 15px;
                                    ">Sim, Imprimir</button>
                <button onclick="fecharNotificacao()" style="
                                        background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.5);
                                        padding: 8px 20px; border-radius: 6px;
                                        cursor: pointer; font-size: 15px;
                                    ">Não</button>
            </div>
        </div>
        <style>
            @keyframes slideUpPrint {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>
        <script type="text/javascript">
            var codigo = @json(session('codigo'));
            var imprimirUrl = path_url + 'pre-venda/imprimir/' + codigo;

            function imprimirComprovante() {
                var win = window.open('', '_blank');
                if (win) {
                    win.location.href = imprimirUrl;
                } else {
                    window.location.href = imprimirUrl;
                    return;
                }
                fecharNotificacao();
            }

            function fecharNotificacao() {
                var el = document.getElementById('print-notification');
                if (el) {
                    el.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                    el.style.transform = 'translateY(100%)';
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 300);
                }
            }
        </script>
    @endif
@endsection
@include('modals._lista_precos')

@include('modals._pagamento_multiplo', ['not_submit' => true])
@include('modals._funcionario', ['not_submit' => true])
@include('modals._variacao', ['not_submit' => true])

@include('modals._cartao_credito', ['not_submit' => true])
@include('modals._cliente', ['cashback' => 0])
@include('modals._observacao_pdv')