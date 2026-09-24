@section('css')
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        /* ── Header Compacto Pré-venda ── */
        .pdv-mesa-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 44px;
            padding: 0 14px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .pdv-mesa-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .pdv-mesa-logo-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #0f766e 0%, #06b6d4 100%);
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(6, 182, 212, 0.35);
        }

        .pdv-mesa-brand-info {
            min-width: 0;
        }

        .pdv-mesa-brand-title {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
            color: #1e293b;
        }

        .pdv-mesa-brand-title span {
            color: #06b6d4;
        }

        .pdv-mesa-subtitle {
            font-size: 11px;
            color: #64748b;
            margin: 0;
            font-weight: 600;
            white-space: nowrap;
        }

        .pdv-mesa-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .pdv-mesa-online-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .pdv-mesa-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            background: #22c55e;
            border-radius: 50%;
            animation: pdv-pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pdv-pulse-dot {
            0%, 100% {
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }
            50% {
                opacity: 0.7;
                box-shadow: 0 0 0 3px rgba(34, 197, 94, 0);
            }
        }

        .pdv-mesa-user-badge {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 4px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
        }

        /* ── Cards de Finalização ── */
        .pdv-fin-card {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            height: 100% !important;
            min-height: 68px !important;
        }

        .pdv-fin-card-body {
            padding: 6px 10px !important;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .pdv-fin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .pdv-fin-label {
            font-size: 10px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b !important;
            margin-bottom: 0;
            line-height: 1.2;
        }

        .pdv-fin-value {
            font-size: 16px !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            margin-bottom: 0 !important;
            line-height: 1.2;
        }

        .pdv-fin-icon-box {
            width: 24px !important;
            height: 24px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px !important;
            font-size: 13px !important;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .pdv-fin-total {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%) !important;
            border-color: #312e81 !important;
        }

        .pdv-fin-total .pdv-fin-label {
            color: #c7d2fe !important;
        }

        .pdv-fin-total .pdv-fin-value,
        .pdv-fin-total .total-venda {
            color: #ffffff !important;
        }

        /* ── Leitor toggle ── */
        .pdv-leitor-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .pdv-leitor-toggle.leitor-on {
            background: #ecfdf5;
            border-color: #6ee7b7;
            color: #065f46;
        }
        .pdv-leitor-toggle.leitor-off {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #7f1d1d;
        }
        .pdv-leitor-switch {
            font-size: 18px;
            line-height: 1;
        }
        .pdv-barcode-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            width: 1px;
            height: 1px;
        }

        /* ── Barra de Adicionar Produto Compacta ── */
        .pdv-add-row {
            padding: 4px 10px !important;
            margin: 0 !important;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .pdv-add-label {
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #475569 !important;
            margin-bottom: 2px !important;
            display: flex;
            align-items: center;
            gap: 4px;
            line-height: 1.2;
            height: 16px !important;
        }

        .pdv-add-input {
            height: 36px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 4px 8px !important;
        }

        .btn-add-item {
            height: 36px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .pdv-add-row .select2-container .select2-selection--single {
            height: 36px !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            display: flex;
            align-items: center;
        }

        .pdv-add-row .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px !important;
            padding-left: 10px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
        }

        .pdv-add-row .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px !important;
            right: 8px !important;
        }

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
<input type="hidden" id="alerta_sonoro" value="{{ isset($configGeral) && $configGeral ? $configGeral->alerta_sonoro : 1 }}">
<input type="hidden" id="abertura" value="{{ $abertura }}" name="">
@isset($pedido)
<input name="pedido_id" id="pedido_id" value="{{ $pedido->id }}" class="d-none">
@endif

{{-- ══════════ HEADER COMPACTO PRÉ-VENDA ══════════ --}}
<header class="pdv-mesa-header mb-1 mt-0">
    <div class="pdv-mesa-header-left">
        <div class="pdv-mesa-logo-icon">
            <i class="ri-list-ordered"></i>
        </div>
        <div class="pdv-mesa-brand-info">
            <h1 class="pdv-mesa-brand-title">
                {{ config('app.name', 'ERP') }} <span>Pré-venda</span>
            </h1>
            <p class="pdv-mesa-subtitle">
                Gestão de Pedidos / Orçamentos
            </p>
        </div>
    </div>
    <div class="pdv-mesa-header-right">
        <span class="pdv-mesa-online-badge">
            <span class="pdv-mesa-dot"></span> Online
        </span>
        <span class="pdv-mesa-user-badge">
            <i class="ri-user-line me-1"></i>{{ Auth::user()->name }}
        </span>
    </div>
</header>

<div class="row align-items-stretch">
    {{-- ═══ COLUNA ESQUERDA: Categorias + Produtos ═══ --}}
    <div class="col-lg-4">
        <div class="row g-2">
            {{-- Card Cliente --}}
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

            {{-- Card Vendedor --}}
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

        {{-- Card com Categorias + Lista de Produtos --}}
        <div class="card mb-0" style="height: calc(100vh - 155px); display: flex; flex-direction: column;">

            <div class="card pdv-categories-wrapper mt-0 mb-0 border-0 shadow-none">
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

            <div class="card-body lista_produtos m-1" style="flex: 1 1 auto; min-height: 0; overflow-y: auto;">
                <div class="row cards-categorias"></div>
            </div>

            {{-- Leitor de código de barras --}}
            <div class="row align-items-center px-2 pb-2 g-2">
                <div class="col-12">
                    <button type="button" id="btn-leitor-toggle" class="btn pdv-leitor-toggle leitor-on w-100"
                        title="Clique para desativar o leitor de código de barras">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="ri-barcode-line fs-5"></i>
                            <span class="pdv-leitor-label">Leitor Ativado</span>
                        </span>
                        <span class="pdv-leitor-switch"><i class="ri-toggle-fill"></i></span>
                    </button>
                    {{-- Input invisível que recebe o código do leitor USB (funciona como teclado) --}}
                    <input class="mousetrap pdv-barcode-input" autofocus type="text"
                        id="codBarras" name="" autocomplete="off">
                </div>
            </div>

        </div>
    </div>

    {{-- ═══ COLUNA DIREITA: Busca + Itens + Finalização ═══ --}}
    <div class="col-lg-8 produtos">
        <div class="card mb-0" style="height: calc(100vh - 74px); display: flex; flex-direction: column;">

            {{-- ═══ LINHA: BUSCAR / ADICIONAR PRODUTO ═══ --}}
            <div class="row align-items-end pdv-add-row g-2 flex-shrink-0">

                {{-- Campo: Produto --}}
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <label for="inp-produto_id" class="pdv-add-label">
                            <i class="ri-search-line me-1"></i>PRODUTO <span class="pdv-shortcut pdv-shortcut-sm">F1</span>
                        </label>
                        <div class="input-group">
                            <select class="form-control produto_id" name="produto_id" id="inp-produto_id"></select>
                        </div>
                        <input name="variacao_id" id="inp-variacao_id" type="hidden" value="">
                    </div>
                </div>

                {{-- Campo: Quantidade --}}
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="pdv-add-label" for="inp-quantidade">
                            <i class="ri-numbers-line me-1"></i>QTD.
                        </label>
                        <input type="tel" name="quantidade" id="inp-quantidade"
                            class="qtd form-control text-center pdv-add-input" placeholder="1" value="1">
                    </div>
                </div>

                {{-- Campo: Valor Unitário --}}
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="pdv-add-label" for="inp-valor_unitario">
                            <i class="ri-price-tag-2-line me-1"></i>VALOR UNIT.
                        </label>
                        <input type="tel" name="valor_unitario" id="inp-valor_unitario"
                            class="moeda value_unit form-control text-end pdv-add-input" placeholder="0,00">
                    </div>
                </div>

                {{-- Botão: Adicionar --}}
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="pdv-add-label opacity-0 d-none d-md-flex">&nbsp;</label>
                        <button class="btn btn-primary btn-add-item w-100" type="button">
                            <i class="ri-add-circle-line me-1"></i>Adicionar
                        </button>
                    </div>
                </div>

                {{-- Campos hidden --}}
                <div class="d-none">
                    <input type="hidden" name="subtotal" id="inp-subtotal" class="moeda" value="">
                    <input type="hidden" name="valor_total" id="inp-valor_total" class="moeda" value="">
                </div>

            </div>

            {{-- ═══ TABELA DE ITENS ═══ --}}
            <div class="card m-1 flex-grow-1" style="min-height: 0; display: flex; flex-direction: column;">
                <div data-bs-target="#navbar-example2" class="scrollspy-example table-responsive"
                    style="flex: 1 1 auto; min-height: 0; overflow-y: auto;">
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

            {{-- ═══ ÁREA DE FINALIZAÇÃO ═══ --}}
            <div class="mt-auto px-3 pb-2 flex-shrink-0">

                {{-- Linha 1: Desconto | Acréscimo | Info Pré-venda | Total --}}
                <div class="row g-2 mt-0 align-items-stretch">
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card h-100 mb-0">
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
                        <div class="card pdv-fin-card h-100 mb-0">
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
                        <div class="card pdv-fin-card h-100 mb-0">
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
                        <div class="card pdv-fin-card pdv-fin-total h-100 mb-0">
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

                {{-- Linha 2: Pagamento | Botões de Ação --}}
                <div class="row g-2 mt-0 align-items-stretch">
                    {{-- Pagamento --}}
                    <div class="col-lg-3 col-6">
                        <div class="card pdv-fin-card h-100 mb-0">
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

                    {{-- Data vencimento (oculta por padrão) --}}
                    <div class="col-lg-2 col-6 div-vencimento d-none">
                        <div class="pdv-vencimento-card h-100">
                            <h6 class="pdv-vencimento-label"><i class="ri-calendar-line me-1"></i>Data de Vencimento
                            </h6>
                            {!! Form::date('data_vencimento', '')->attrs(['class' => 'form-control form-control-sm data_atual']) !!}
                        </div>
                    </div>

                    {{-- Botões Ação: Pag. Multi | Observ. --}}
                    <div class="col">
                        <div class="card widget-icon-box div-pagamento mb-0 h-100">
                            <div class="card-body p-2 h-100 d-flex align-items-center">
                                <div class="row g-1 w-100">
                                    <div class="col-6">
                                        <button type="button" class="btn pdv-action-btn btn-info w-100"
                                            data-bs-toggle="modal" data-bs-target="#pagamento_multiplo">
                                            <i class="ri-list-check-3"></i> Pag. Multi <span
                                                class="pdv-shortcut pdv-shortcut-sm">F4</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn pdv-action-btn btn-primary w-100"
                                            data-bs-toggle="modal" data-bs-target="#observacao_pdv"
                                            title="Observação"><i class="ri-file-edit-fill"></i> Observ.
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botões Ação: Sair | Nova Prevenda | Finalizar --}}
                    <div class="col">
                        <div class="card widget-icon-box div-pagamento mb-0 h-100">
                            <div class="card-body p-2 h-100 d-flex align-items-center">
                                <div class="row g-1 w-100">
                                    <div class="col-6">
                                        <a class="btn pdv-action-btn btn-danger w-100"
                                            href="{{ route('pre-venda.index') }}">
                                            <i class="ri-arrow-left-s-line"></i> Sair
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('pre-venda.create') }}" class="btn pdv-action-btn btn-secondary w-100">
                                            <i class="ri-refresh-line me-1"></i> Nova Prev.
                                        </a>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" disabled class="pdv-btn-finalizar mt-1" id="salvar_pre_venda">
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
        // Leitor toggle (idêntico ao PDV)
        (function () {
            var btn = document.getElementById('btn-leitor-toggle');
            var input = document.getElementById('codBarras');
            var label = btn ? btn.querySelector('.pdv-leitor-label') : null;
            var icon = btn ? btn.querySelector('.pdv-leitor-switch i') : null;
            var isOn = true;

            function setLeitor(on) {
                isOn = on;
                if (on) {
                    btn.classList.remove('leitor-off');
                    btn.classList.add('leitor-on');
                    if (label) label.textContent = 'Leitor Ativado';
                    if (icon) { icon.classList.remove('ri-toggle-line'); icon.classList.add('ri-toggle-fill'); }
                    if (input) { input.disabled = false; input.focus(); }
                } else {
                    btn.classList.remove('leitor-on');
                    btn.classList.add('leitor-off');
                    if (label) label.textContent = 'Leitor Desativado';
                    if (icon) { icon.classList.remove('ri-toggle-fill'); icon.classList.add('ri-toggle-line'); }
                    if (input) input.disabled = true;
                }
            }

            if (btn) {
                btn.addEventListener('click', function () { setLeitor(!isOn); });
            }
        })();

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
        <style>
            .swal-modal {
                border-radius: 20px !important;
                padding: 28px 24px !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                background: #ffffff !important;
            }
            .swal-icon--success {
                border-color: #10b981 !important;
            }
            .swal-icon--success__line {
                background-color: #10b981 !important;
            }
            .swal-icon--success__ring {
                border: 4px solid rgba(16, 185, 129, 0.2) !important;
            }
            .swal-title {
                font-size: 22px !important;
                font-weight: 700 !important;
                color: #0f172a !important;
                margin-top: 15px !important;
                padding: 0 !important;
            }
            .swal-text {
                font-size: 15px !important;
                color: #64748b !important;
                text-align: center !important;
                margin-top: 8px !important;
            }
            .swal-footer {
                text-align: center !important;
                margin-top: 24px !important;
                padding: 0 !important;
            }
            .swal-button {
                border-radius: 10px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                padding: 10px 24px !important;
                transition: all 0.2s ease !important;
            }
            .swal-button--cancel {
                background-color: #f1f5f9 !important;
                color: #64748b !important;
            }
            .swal-button--cancel:hover {
                background-color: #e2e8f0 !important;
                color: #334155 !important;
            }
            .swal-button--confirm {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                color: #fff !important;
                box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35) !important;
            }
            .swal-button--confirm:hover {
                box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45) !important;
                transform: translateY(-1px);
            }
        </style>
        <script type="text/javascript">
            $(function () {
                var codigo = @json(session('codigo'));
                var imprimirUrl = path_url + 'pre-venda/imprimir/' + codigo;

                if (typeof swal !== 'undefined') {
                    swal({
                        title: "Pré-venda Finalizada!",
                        text: "Deseja imprimir o comprovante da pré-venda?",
                        icon: "success",
                        buttons: {
                            cancel: {
                                text: "Não",
                                value: false,
                                visible: true,
                                closeModal: true
                            },
                            confirm: {
                                text: "Sim, Imprimir",
                                value: true,
                                visible: true,
                                closeModal: true
                            }
                        },
                        dangerMode: false
                    }).then((isConfirm) => {
                        if (isConfirm) {
                            var win = window.open(imprimirUrl, '_blank');
                            if (!win) {
                                window.location.href = imprimirUrl;
                            }
                        }
                    });
                }
            });
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