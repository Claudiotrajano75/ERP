@extends('layouts.app', ['title' => 'Home'])
@if(!__isContador())
    @section('css')
        <style>
            /* ─── Header Gradiente Premium ─── */
            .modulo-header-gradient {
                background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
                border-radius: 12px 12px 0 0 !important;
                border-bottom: none !important;
            }

            .modulo-header-gradient .modulo-title {
                color: #fff;
                font-weight: 700;
                letter-spacing: -0.3px;
            }

            .modulo-header-gradient .modulo-title i {
                background: rgba(255, 255, 255, 0.12);
                padding: 8px;
                border-radius: 10px;
                color: #a8b5ff;
            }

            .modulo-header-gradient .modulo-subtitle {
                color: rgba(255, 255, 255, 0.6) !important;
                font-weight: 400;
            }

            .modulo-header-gradient .modulo-subtitle strong {
                color: #fff;
            }

            /* ─── Cards de Ação da Home do Caixa ─── */
            .home-action-card {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 22px 18px;
                border-radius: 14px;
                color: #fff;
                height: 100%;
                position: relative;
                overflow: hidden;
                text-decoration: none;
                transition: all 0.25s ease;
            }

            .home-action-card::after {
                content: '';
                position: absolute;
                top: -45px;
                right: -45px;
                width: 130px;
                height: 130px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.08);
                transition: transform 0.3s ease;
            }

            .home-action-card:hover {
                transform: translateY(-4px);
                text-decoration: none;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2) !important;
            }

            .home-action-card:hover::after {
                transform: scale(1.5);
            }

            .home-action-card:hover .home-action-arrow {
                transform: translateX(4px);
                opacity: 1;
            }

            .home-action-icon {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 26px;
            }

            .home-action-card h5 {
                margin: 0;
                font-weight: 700;
                font-size: 16px;
                color: #fff;
                letter-spacing: -0.2px;
            }

            .home-action-card p {
                margin: 4px 0 0;
                font-size: 12px;
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.4;
            }

            .home-action-arrow {
                font-size: 26px;
                opacity: 0.6;
                transition: all 0.25s ease;
                flex-shrink: 0;
            }

            .home-action-blue {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
            }

            .home-action-green {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            }

            .home-action-orange {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
            }

            .home-action-purple {
                background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
                box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
            }

            @media (max-width: 768px) {
                .modulo-header-gradient .modulo-title {
                    font-size: 18px;
                }
            }
        </style>
    @endsection

    @section('content')
        <div class="mt-3">
            <div class="row">

                @if(__isAdmin())
                    <div class="card">
                        <div class="card-body">
                            <h3>Painel</h3>
                            <div class="row">
                                <div class="col-md-4 col-lg-2 col-12 mb-2">
                                    {!!Form::select('periodo', 'Período', [
                        '1' => 'Hoje',
                        '7' => 'Semana',
                        '30' => 'Mês',
                        '365' => 'Ano'
                    ])->value(7)
                        ->attrs(['class' => 'form-select'])
                                                !!}
                                </div>

                                @if(__countLocalAtivo() > 1)
                                            <div class="col-md-2">
                                                {!!Form::select('local_id', 'Local', [
                                        '' => 'Todos'
                                    ] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                        ->attrs(['class' => 'form-select'])
                                                                        !!}
                                            </div>
                                @else
                                    <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}"
                                        name="local_id">
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3 col-xl-2">
                                    <div class="card widget-icon-box text-bg-success">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h4 class="text-uppercase fs-13 mt-0" title="Average Revenue">Contas a Receber
                                                    </h4>
                                                    <h3 class="my-3 total-receber" style="font-size: 16px;">R$ 0,00</h3>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                        <i class="ri-money-dollar-circle-line"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-xl-2">
                                    <div class="card widget-icon-box text-bg-danger">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h4 class="text-uppercase fs-13 mt-0" title="Average Revenue">Contas a Pagar
                                                    </h4>
                                                    <h3 class="my-3 total-pagar" style="font-size: 16px;">R$ 0,00</h3>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                        <i class="ri-money-dollar-circle-line"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-xl-2">
                                    <div class="card widget-icon-box text-bg-info">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h4 class="text-uppercase fs-13 mt-0" title="Average Revenue">Produtos</h4>
                                                    <h3 class="my-3 total-produtos" style="font-size: 16px;">0</h3>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                        <i class="ri-box-3-line"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-xl-2">
                                    <div class="card widget-icon-box text-bg-dark">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h4 class="text-uppercase fs-13 mt-0" title="Average Revenue">Clientes</h4>
                                                    <h3 class="my-3 total-clientes" style="font-size: 16px;">0</h3>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                        <i class="ri-account-box-fill"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-xl-2">
                                    <div class="card widget-icon-box text-bg-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h4 class="text-uppercase fs-13 mt-0" title="Average Revenue">Total de Vendas
                                                    </h4>
                                                    <h3 class="my-3 total-vendas" style="font-size: 16px;">R$ 0,00</h3>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                        <i class="ri-shopping-cart-fill"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-xl-2">
                                    <div class="card widget-icon-box text-bg-warning">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h4 class="text-uppercase fs-13 mt-0" title="Average Revenue">Total de Compras
                                                    </h4>
                                                    <h3 class="my-3 total-compras" style="font-size: 16px;">R$ 0,00</h3>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                        <i class="ri-shopping-bag-2-fill"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Vendas</h5>
                                        </div>
                                        <div class="card-body">
                                            <h5>Total de vendas {{ $mes }} R$ <strong>{{ __moeda($totalVendasMes)}}</strong></h5>
                                            <p>Vendas meses anteriores.</p>
                                            @foreach($somaVendasMesesAnteriores as $key => $s)
                                                <h6>{{ $key }}: <strong class="text-success">R$ {{ __moeda($s) }}</strong></h6>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de vendas mensal (valores por dia)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-vendas-mes"></canvas>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Compras</h5>
                                        </div>
                                        <div class="card-body">
                                            <h5>Total de compras {{ $mes }} R$ <strong>{{ __moeda($totalComprasMes)}}</strong></h5>
                                            <p>Vendas meses anteriores.</p>
                                            @foreach($somaComprasMesesAnteriores as $key => $s)
                                                <h6>{{ $key }}: <strong class="text-success">R$ {{ __moeda($s) }}</strong></h6>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de compras mensal (valores por dia)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-compras-mes"></canvas>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                @if($msgPlano != "")
                                    <div class="col-lg-12 mb-2">
                                        <p class="text-danger">{{ $msgPlano }}</p>
                                        <a href="{{ route('payment.index') }}" class="btn btn-success btn-lg pulse-success">Contratar
                                            Plano</a>
                                    </div>
                                @endif

                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Volume</h5>
                                        </div>
                                        <div class="card-body">
                                            <h4>R$ <strong>{{ __moeda($totalEmitidoMes)}}</strong></h4>
                                            <p>Notas emitidas neste mês.</p>
                                            <h6>Emissões de NFe: <strong class="text-success">{{ $totalNfeCount }}</strong></h6>
                                            <h6>Emissões de NFCe: <strong class="text-success">{{ $totalNfceCount }}</strong></h6>
                                            <h6>Emissões de CTe: <strong class="text-success">{{ $totalCteCount }}</strong></h6>
                                            <h6>Emissões de MDFe: <strong class="text-success">{{ $totalMdfeCount }}</strong></h6>

                                        </div>
                                    </div>

                                    @if($empresa->plano)
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <h5>Plano</h5>
                                            </div>
                                            <div class="card-body">
                                                <h4>{{ $empresa->plano->plano->nome }}</h4>
                                                <h6>Total de emissões NFe: <strong
                                                        class="text-danger">{{ $empresa->plano->plano->maximo_nfes }}</strong></h6>
                                                <h6>Total de emissões NFCe: <strong
                                                        class="text-danger">{{ $empresa->plano->plano->maximo_nfces }}</strong></h6>
                                                <h6>Total de emissões CTe: <strong
                                                        class="text-danger">{{ $empresa->plano->plano->maximo_ctes }}</strong></h6>
                                                <h6>Total de emissões MDFe: <strong
                                                        class="text-danger">{{ $empresa->plano->plano->maximo_mdfes }}</strong></h6>

                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de emissão mensal (valores por dia)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-emissao-mes"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de emissão mensal (quantidade emitida)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-emissao-mes-contador"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de emissão últimos meses (valor mensal acumulado)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-emissao-ult-meses"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title mb-4">Contas a receber</h4>
                                            <div dir="ltr">

                                                <canvas id="conta-receber" style="width: 100%"
                                                    data-colors="#4A4AFD, #B6D7A8, #B6D7A8"></canvas>

                                            </div>
                                        </div> <!-- end card body-->
                                    </div> <!-- end card -->
                                </div><!-- end col-->
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title mb-4">Contas a pagar</h4>
                                            <div dir="ltr">

                                                <canvas id="conta-pagar" data-colors="#4A4AFD, #B6D7A8, #B6D7A8"></canvas>

                                            </div>
                                        </div> <!-- end card body-->
                                    </div> <!-- end card -->
                                </div><!-- end col-->
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de emissão mensal CTe (quantidade emitida)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-emissao-mes-cte"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Grafico de emissão mensal MDFe (quantidade emitida)</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico-emissao-mes-mdfe"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- ═══ Painel Inicial — Home do Caixa ═══ -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header modulo-header-gradient py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                        <i class="ri-home-5-line"></i>
                                        Painel Inicial
                                    </h4>
                                    <p class="text-muted mb-0 modulo-subtitle fs-13">
                                        Olá, <strong>{{ get_name_user() }}</strong>! Selecione uma ação para começar.
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 fs-12">
                                        <i class="ri-calendar-line me-1"></i> {{ __data_pt(date('Y-m-d'), 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('nfe.create') }}" class="home-action-card home-action-blue">
                                        <div class="home-action-icon"><i class="ri-shopping-bag-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Nova Venda</h5>
                                            <p>Emita uma NFe de venda para o cliente</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('pre-venda.create') }}" class="home-action-card home-action-green">
                                        <div class="home-action-icon"><i class="ri-file-list-3-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Nova Pré-Venda</h5>
                                            <p>Crie uma pré-venda para finalizar depois</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('produtos.create') }}" class="home-action-card home-action-orange">
                                        <div class="home-action-icon"><i class="ri-price-tag-3-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Novo Produto</h5>
                                            <p>Cadastre um novo produto no estoque</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('clientes.create') }}" class="home-action-card home-action-purple">
                                        <div class="home-action-icon"><i class="ri-user-add-line"></i></div>
                                        <div class="flex-grow-1">
                                            <h5>Novo Cliente</h5>
                                            <p>Cadastre um novo cliente</p>
                                        </div>
                                        <i class="ri-arrow-right-s-line home-action-arrow"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @endsection

    @if(__isAdmin())
        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script type="text/javascript">
                $(function () {
                    setTimeout(() => {
                        buscaDadosGraficoMes()
                        buscaDadosGraficoMesContador()
                        buscaDadosUlitmosMeses()
                        contaReceber()
                        contaPagar()
                        buscaDadosGraficoMesCte()
                        buscaDadosGraficoMesMdfe()

                        dadosCards()
                        buscaDadosGraficoVendasMes()
                        buscaDadosGraficoComprasMes()
                    }, 10)
                })

                $(document).on("change", "#inp-periodo", function () {
                    dadosCards()
                })

                $(document).on("change", "#inp-local_id", function () {
                    dadosCards()
                })

                function dadosCards() {
                    let periodo = $("#inp-periodo").val()
                    let local_id = $('#inp-local_id').val()
                    let empresa_id = $('#empresa_id').val()
                    let usuario_id = $('#usuario_id').val()

                    $.get(path_url + "api/graficos/dados-cards", {
                        empresa_id: empresa_id,
                        usuario_id: usuario_id,
                        periodo: periodo,
                        local_id: local_id
                    })
                        .done((success) => {

                            $('.total-clientes').text(success['clientes'])
                            $('.total-produtos').text(success['produtos'])
                            $('.total-vendas').text("R$ " + convertFloatToMoeda(success['vendas']))
                            $('.total-compras').text("R$ " + convertFloatToMoeda(success['compras']))
                            $('.total-receber').text("R$ " + convertFloatToMoeda(success['contas_receber']))
                            $('.total-pagar').text("R$ " + convertFloatToMoeda(success['contas_pagar']))
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosGraficoVendasMes() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-vendas-mes", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoVendasMes(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosGraficoComprasMes() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-compras-mes", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoComprasMes(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosGraficoMes() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-mes", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoMes(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosGraficoMesContador() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-mes-contador", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoMesContador(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosGraficoMesCte() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-mes-cte", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoMesCte(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosGraficoMesMdfe() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-mes-mdfe", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoMesMdfe(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function buscaDadosUlitmosMeses() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-ult-meses", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            iniciaGraficoUltMeses(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function contaReceber() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-conta-receber", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            contaReceberTotal(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function contaPagar() {
                    let empresa_id = $('#empresa_id').val()

                    $.get(path_url + "api/graficos/grafico-conta-pagar", {
                        empresa_id: empresa_id
                    })
                        .done((success) => {
                            contaPagarTotal(success)
                        })
                        .fail((err) => {
                            console.log(err)
                        })
                }

                function iniciaGraficoVendasMes(data) {
                    const ctx = document.getElementById('grafico-vendas-mes');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'total',
                                data: montaValues(data),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function iniciaGraficoComprasMes(data) {
                    const ctx = document.getElementById('grafico-compras-mes');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'total',
                                data: montaValues(data),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function iniciaGraficoMes(data) {
                    const ctx = document.getElementById('grafico-emissao-mes');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'emissão',
                                data: montaValues(data),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function iniciaGraficoMesContador(data) {
                    const ctx = document.getElementById('grafico-emissao-mes-contador');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'emissão',
                                data: montaValues(data),
                                borderWidth: 1,
                                borderColor: '#19AC65',
                                backgroundColor: '#19AC65'
                            }],

                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function iniciaGraficoMesCte(data) {
                    const ctx = document.getElementById('grafico-emissao-mes-cte');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'emissão',
                                data: montaValues(data),
                                borderWidth: 1,
                                borderColor: '#19AC65',
                                backgroundColor: '#19AC65'
                            }],

                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function iniciaGraficoMesMdfe(data) {
                    const ctx = document.getElementById('grafico-emissao-mes-mdfe');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'emissão',
                                data: montaValues(data),
                                borderWidth: 1,
                                borderColor: '#19AC65',
                                backgroundColor: '#19AC65'
                            }],

                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
                function iniciaGraficoUltMeses(data) {
                    const ctx = document.getElementById('grafico-emissao-ult-meses');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'emissão',
                                data: montaValues(data),
                                borderWidth: 1,
                                borderColor: '#FF6384',
                                backgroundColor: '#FF6384'
                            }],

                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function montaLabels(data) {
                    let temp = []
                    data.map((x) => {
                        temp.push(x.dia)
                    })
                    return temp
                }

                function montaValues(data) {
                    let temp = []
                    data.map((x) => {
                        temp.push(x.valor)
                    })
                    return temp
                }

                function montaValuesPendente(data) {
                    let temp = []
                    data.map((x) => {
                        temp.push(x.valorPendente)
                    })
                    return temp
                }

                function montaValuesQuitado(data) {
                    let temp = []
                    data.map((x) => {
                        temp.push(x.valorQuitado)
                    })
                    return temp
                }

                function contaReceberTotal(data) {
                    var chartElement = document.getElementById('conta-receber');
                    var dataColors = chartElement.getAttribute('data-colors');
                    var colors = dataColors ? dataColors.split(",") : this.defaultColors
                    var ctx = chartElement.getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'Valor a Receber',
                                data: montaValuesPendente(data),
                                fill: '-1',
                                backgroundColor: '#000000',
                            },
                            {
                                label: 'Valor Recebido',
                                data: montaValuesQuitado(data),
                                fill: '-1',
                                backgroundColor: '#6AA84F',
                            },
                            {
                                label: 'Total',
                                data: montaValues(data),
                                fill: '0',
                                backgroundColor: '#1261A9',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                filler: {
                                    propagate: true
                                },
                            },
                            interaction: {
                                intersect: true,
                            }
                        }
                    });
                }

                function contaPagarTotal(data) {
                    var chartElement = document.getElementById('conta-pagar');
                    var dataColors = chartElement.getAttribute('data-colors');
                    var colors = dataColors ? dataColors.split(",") : this.defaultColors
                    var ctx = chartElement.getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: montaLabels(data),
                            datasets: [{
                                label: 'Valor a Pagar',
                                data: montaValuesPendente(data),
                                fill: '-1',
                                backgroundColor: '#000000',
                            },
                            {
                                label: 'Valor Pago',
                                data: montaValuesQuitado(data),
                                fill: '-1',
                                backgroundColor: '#6AA84F',
                            },
                            {
                                label: 'Total',
                                data: montaValues(data),
                                fill: '0',
                                backgroundColor: '#1261A9',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                filler: {
                                    propagate: true
                                },
                            },
                            interaction: {
                                intersect: true,
                            }

                        },
                    });
                }
            </script>
        @endsection
    @endif
@else

    @include('contador.home')
@endif