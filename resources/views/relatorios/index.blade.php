@extends('layouts.app', ['title' => 'Relatórios'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-file-chart-line me-2 text-primary fs-24"></i>
                            Central de Relatórios
                        </h4>
                        <p class="text-muted mb-0 fs-13">Gere relatórios gerenciais em PDF e Excel de produtos, estoque, financeiro, cadastros e notas fiscais.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- Links das Abas de Categorias -->
                <ul class="nav nav-tabs nav-bordered mb-4" id="relatoriosTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold fs-14" id="produtos-tab" data-bs-toggle="tab" data-bs-target="#produtos-pane" type="button" role="tab" aria-controls="produtos-pane" aria-selected="true">
                            <i class="ri-box-3-line me-1 align-middle fs-16"></i> 📦 Produtos & Estoque
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold fs-14" id="financeiro-tab" data-bs-toggle="tab" data-bs-target="#financeiro-pane" type="button" role="tab" aria-controls="financeiro-pane" aria-selected="false">
                            <i class="ri-wallet-3-line me-1 align-middle fs-16"></i> 💰 Financeiro & Vendas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold fs-14" id="cadastros-tab" data-bs-toggle="tab" data-bs-target="#cadastros-pane" type="button" role="tab" aria-controls="cadastros-pane" aria-selected="false">
                            <i class="ri-contacts-line me-1 align-middle fs-16"></i> 🤝 Cadastro & Comissões
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold fs-14" id="fiscais-tab" data-bs-toggle="tab" data-bs-target="#fiscais-pane" type="button" role="tab" aria-controls="fiscais-pane" aria-selected="false">
                            <i class="ri-file-text-line me-1 align-middle fs-16"></i> 📄 Documentos Fiscais
                        </button>
                    </li>
                </ul>

                <!-- Conteúdo das Abas -->
                <div class="tab-content text-dark" id="relatoriosTabsContent">
                    
                    <!-- ABA 1: Produtos & Estoque -->
                    <div class="tab-pane fade show active" id="produtos-pane" role="tabpanel" aria-labelledby="produtos-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Relatório de Produtos -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.produtos') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-barcode-box-line text-primary me-2 align-middle fs-18"></i>Relatório de Produtos</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('estoque', 'Estoque', ['' => 'Selecione', '1' => 'Positivo', '-1' => 'Negativo'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('tipo', 'Tipo', ['' => 'Selecione', '1' => 'Mais vendidos', '-1' => 'Menos vendidos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('marca_id', 'Marca', ['' => 'Selecione'] + $marcas->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('categoria_id', 'Categoria', ['' => 'Selecione'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Estoque -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.estoque') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-archive-line text-primary me-2 align-middle fs-18"></i>Relatório de Estoque</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('categoria_id', 'Categoria', ['' => 'Selecione'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    {!!Form::select('estoque_minimo', 'Mínimo', ['-1' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    {!!Form::select('esportar_excel', 'Excel', ['-1' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Venda de Produtos -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.venda-produtos') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-shopping-basket-line text-primary me-2 align-middle fs-18"></i>Relatório de Venda de Produtos</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('marca_id', 'Marca', ['' => 'Selecione'] + $marcas->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('categoria_id', 'Categoria', ['' => 'Selecione'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('produto_id', 'Produto')->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('ordem', 'Ordem', ['desc' => 'Mais Vendidos', 'asc' => 'Menos Vendidos', 'alfa' => 'Alfabética'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório Totalizador de Produtos -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.totaliza-produtos') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-numbers-line text-primary me-2 align-middle fs-18"></i>Totalizador de Produtos</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Dt. Inicial Cadastro')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Dt. Final Cadastro')!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ABA 2: Financeiro & Vendas -->
                    <div class="tab-pane fade" id="financeiro-pane" role="tabpanel" aria-labelledby="financeiro-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Contas a Pagar -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.conta_pagar') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-money-dollar-circle-line text-danger me-2 align-middle fs-18"></i>Relatório de Contas a Pagar</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('status', 'Estado', ['1' => 'Quitadas', '-1' => 'Pendentes', '' => 'Todas'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Contas a Receber -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.conta_receber') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-hand-coin-line text-success me-2 align-middle fs-18"></i>Relatório de Contas a Receber</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('status', 'Estado', ['1' => 'Recebidas', '-1' => 'Pendentes', '' => 'Todos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Vendas -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.vendas') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-shopping-bag-3-line text-primary me-2 align-middle fs-18"></i>Relatório de Vendas</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('estado', 'Estado', ['novo' => 'Novas', 'rejeitado' => 'Rejeitadas', 'cancelado' => 'Canceladas', 'aprovado' => 'Aprovadas', '' => 'Todos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Compras -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.compras') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-play-list-add-line text-primary me-2 align-middle fs-18"></i>Relatório de Compras</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Taxas -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.taxas') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-percent-line text-primary me-2 align-middle fs-18"></i>Relatório de Taxas</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Lucros -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.lucro') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-line-chart-line text-success me-2 align-middle fs-18"></i>Relatório de Lucros</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Despesa de Fretes -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.despesa-frete') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-truck-line text-primary me-2 align-middle fs-18"></i>Relatório de Despesa de Fretes</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('tipo_despesa_frete_id', 'Tipo de Despesa', ['' => 'Todos'] + $tiposDespesaFrete->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ABA 3: Cadastro & Comissões -->
                    <div class="tab-pane fade" id="cadastros-pane" role="tabpanel" aria-labelledby="cadastros-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Relatório de Clientes -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.clientes') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-user-shared-line text-primary me-2 align-middle fs-18"></i>Relatório de Clientes</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('tipo', 'Tipo', ['' => 'Selecione', '1' => 'Mais vendas', '-1' => 'Menos vendas'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Fornecedores -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.fornecedores') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-user-received-line text-primary me-2 align-middle fs-18"></i>Relatório de Fornecedores</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('tipo', 'Tipo', ['' => 'Selecione', '1' => 'Mais compras', '-1' => 'Menos compras'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Comissão -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.comissao') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-user-star-line text-primary me-2 align-middle fs-18"></i>Relatório de Comissão</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('funcionario_id', 'Funcionário', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ABA 4: Documentos Fiscais -->
                    <div class="tab-pane fade" id="fiscais-pane" role="tabpanel" aria-labelledby="fiscais-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Relatório de NFe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.nfe') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-file-3-line text-primary me-2 align-middle fs-18"></i>Relatório de NFe</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('tipo', 'Tipo', ['' => 'Selecione', '1' => 'Saída', '-1' => 'Entrada'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::select('cliente', 'Cliente')->attrs(['class' => 'form-select cliente_id'])!!}
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    {!!Form::select('finNFe', 'Finalidade', ['1' => 'NFe normal', '2' => 'NFe complementar', '3' => 'NFe de ajuste', '4' => 'Devolução'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    {!!Form::select('estado', 'Estado', ['novo' => 'Novas', 'rejeitado' => 'Rejeitadas', 'cancelado' => 'Canceladas', 'aprovado' => 'Aprovadas', '' => 'Todos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de NFCe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.nfce') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-file-list-3-line text-primary me-2 align-middle fs-18"></i>Relatório de NFCe</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'form-select cliente_id'])!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('estado', 'Estado', ['novo' => 'Novas', 'rejeitado' => 'Rejeitadas', 'cancelado' => 'Canceladas', 'aprovado' => 'Aprovadas', '' => 'Todos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-md-8 col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de CTe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.cte') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-truck-line text-primary me-2 align-middle fs-18"></i>Relatório de CTe</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('estado', 'Estado', ['novo' => 'Novas', 'rejeitado' => 'Rejeitadas', 'cancelado' => 'Canceladas', 'aprovado' => 'Aprovadas', '' => 'Todos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de MDFe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.mdfe') }}" target="_blank">
                                    <div class="card shadow-none border mb-0 h-100">
                                        <div class="card-header bg-transparent border-bottom py-2.5">
                                            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-map-pin-2-line text-primary me-2 align-middle fs-18"></i>Relatório de MDFe</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('estado', 'Estado', ['novo' => 'Novas', 'rejeitado' => 'Rejeitadas', 'cancelado' => 'Canceladas', 'aprovado' => 'Aprovadas', '' => 'Todos'])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @if(__countLocalAtivo() > 1)
                                                <div class="col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top">
                                            <button class="btn btn-primary btn-sm w-100"><i class="ri-printer-line me-1 align-middle"></i> Gerar Relatório</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
