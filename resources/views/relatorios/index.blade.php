@extends('layouts.app', ['title' => 'Central de Relatórios'])

@section('css')
<style>
    /* ═══ Padrão Visual Central de Relatórios (Skill ERP Layout Modernization) ═══ */
    .rep-nav-tabs {
        border-bottom: 2px solid #e9ecf3;
        gap: 6px;
        margin-bottom: 22px;
    }
    .rep-nav-tabs .nav-link {
        border: 1px solid transparent;
        border-radius: 10px 10px 0 0;
        padding: 10px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        transition: all .15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: -2px;
    }
    .rep-nav-tabs .nav-link:hover {
        color: #4f46e5;
        background: #f8faff;
    }
    .rep-nav-tabs .nav-link.active {
        color: #4f46e5;
        background: #ffffff;
        border-color: #e9ecf3 #e9ecf3 #ffffff;
        font-weight: 700;
        border-top: 3px solid #4f46e5;
    }

    /* Cards de Relatório Individuais */
    .rep-card {
        background: #ffffff;
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 1px 3px rgba(16,24,40,.03);
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .rep-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(16,24,40,.06);
    }
    .rep-card-header {
        padding: 12px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rep-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rep-card-title i {
        font-size: 18px;
        color: #4f46e5;
    }
    .rep-badge {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .3px;
        padding: 2px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
    }
    .rep-badge-excel {
        background: #ecfdf5;
        color: #047857;
    }
    .rep-card-body {
        padding: 16px 18px;
        flex: 1 1 auto;
    }
    .rep-card-body label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }
    .rep-card-body .form-control,
    .rep-card-body .form-select {
        height: 38px;
        border-radius: 8px;
        border: 1px solid #dcdce9;
        font-size: 13px;
        color: #1f2937;
        background: #fcfdfe;
        box-sizing: border-box;
    }
    .rep-card-body .form-control:focus,
    .rep-card-body .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        background: #fff;
    }
    .rep-card-footer {
        padding: 12px 18px;
        background: #fafbfe;
        border-top: 1px solid #eef2f6;
        margin-top: auto;
    }
    .rep-card-footer .dash-btn {
        width: 100%;
        box-sizing: border-box;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">
            
            <!-- Cabeçalho Principal Padrão Ubold Premium -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-chart-line text-primary"></i>
                            Central de Relatórios
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Emissão de relatórios gerenciais e fiscais consolidados do seu negócio em PDF e Excel.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('relatorios.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- Links das Abas de Categorias -->
                <ul class="nav rep-nav-tabs" id="relatoriosTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="produtos-tab" data-bs-toggle="tab" data-bs-target="#produtos-pane" type="button" role="tab" aria-controls="produtos-pane" aria-selected="true">
                            <i class="ri-box-3-line fs-16"></i> 📦 Produtos & Estoque
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="financeiro-tab" data-bs-toggle="tab" data-bs-target="#financeiro-pane" type="button" role="tab" aria-controls="financeiro-pane" aria-selected="false">
                            <i class="ri-wallet-3-line fs-16"></i> 💰 Financeiro & Vendas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cadastros-tab" data-bs-toggle="tab" data-bs-target="#cadastros-pane" type="button" role="tab" aria-controls="cadastros-pane" aria-selected="false">
                            <i class="ri-contacts-line fs-16"></i> 🤝 Cadastro & Comissões
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fiscais-tab" data-bs-toggle="tab" data-bs-target="#fiscais-pane" type="button" role="tab" aria-controls="fiscais-pane" aria-selected="false">
                            <i class="ri-file-text-line fs-16"></i> 📄 Documentos Fiscais
                        </button>
                    </li>
                </ul>

                <!-- Conteúdo das Abas -->
                <div class="tab-content" id="relatoriosTabsContent">
                    
                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <!-- ABA 1: Produtos & Estoque -->
                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <div class="tab-pane fade show active" id="produtos-pane" role="tabpanel" aria-labelledby="produtos-tab" tabindex="0">
                        <div class="row g-4">
                            
                            <!-- Relatório de Produtos -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.produtos') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-barcode-box-line"></i>
                                                Relatório de Produtos
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Estoque -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.estoque') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-archive-line"></i>
                                                Relatório de Estoque
                                            </h5>
                                            <span class="rep-badge rep-badge-excel">PDF / Excel</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Venda de Produtos -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.venda-produtos') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-shopping-basket-line"></i>
                                                Relatório de Venda de Produtos
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                                    {!!Form::select('produto_id', 'Produto', ['' => 'Todos os Produtos'] + (isset($produtos) ? $produtos->pluck('nome', 'id')->all() : []))->attrs(['class' => 'form-select'])!!}
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório Totalizador de Produtos -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.totaliza-produtos') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-numbers-line"></i>
                                                Totalizador de Produtos
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <!-- ABA 2: Financeiro & Vendas -->
                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <div class="tab-pane fade" id="financeiro-pane" role="tabpanel" aria-labelledby="financeiro-tab" tabindex="0">
                        <div class="row g-4">
                            
                            <!-- Contas a Pagar -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.conta_pagar') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-money-dollar-circle-line text-danger"></i>
                                                Relatório de Contas a Pagar
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Contas a Receber -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.conta_receber') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-hand-coin-line text-success"></i>
                                                Relatório de Contas a Receber
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Vendas -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.vendas') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-shopping-bag-3-line"></i>
                                                Relatório de Vendas
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Compras -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.compras') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-play-list-add-line"></i>
                                                Relatório de Compras
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Taxas -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.taxas') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-percent-line"></i>
                                                Relatório de Taxas
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Lucros -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.lucro') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-line-chart-line text-success"></i>
                                                Relatório de Lucros
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Despesa de Fretes -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.despesa-frete') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-truck-line"></i>
                                                Relatório de Despesa de Fretes
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <!-- ABA 3: Cadastro & Comissões -->
                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <div class="tab-pane fade" id="cadastros-pane" role="tabpanel" aria-labelledby="cadastros-tab" tabindex="0">
                        <div class="row g-4">
                            
                            <!-- Relatório de Clientes -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.clientes') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-user-shared-line"></i>
                                                Relatório de Clientes
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Fornecedores -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.fornecedores') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-user-received-line"></i>
                                                Relatório de Fornecedores
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de Comissão -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.comissao') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-user-star-line"></i>
                                                Relatório de Comissão
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <!-- ABA 4: Documentos Fiscais -->
                    <!-- ══════════════════════════════════════════════════════════════ -->
                    <div class="tab-pane fade" id="fiscais-pane" role="tabpanel" aria-labelledby="fiscais-tab" tabindex="0">
                        <div class="row g-4">
                            
                            <!-- Relatório de NFe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.nfe') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-file-3-line"></i>
                                                Relatório de NFe
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                                    {!!Form::select('cliente', 'Cliente', ['' => 'Todos os Clientes'] + (isset($clientes) ? $clientes->pluck('razao_social', 'id')->all() : []))->attrs(['class' => 'form-select cliente_id'])!!}
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de NFCe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.nfce') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-file-list-3-line"></i>
                                                Relatório de NFCe
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
                                            <div class="row g-2">
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('start_date', 'Data Inicial')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::date('end_date', 'Data Final')!!}
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    {!!Form::select('cliente_id', 'Cliente', ['' => 'Todos os Clientes'] + (isset($clientes) ? $clientes->pluck('razao_social', 'id')->all() : []))->attrs(['class' => 'form-select cliente_id'])!!}
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de CTe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.cte') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-truck-line"></i>
                                                Relatório de CTe
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Relatório de MDFe -->
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.mdfe') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-map-pin-2-line"></i>
                                                Relatório de MDFe
                                            </h5>
                                            <span class="rep-badge">PDF</span>
                                        </div>
                                        <div class="rep-card-body">
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
                                        <div class="rep-card-footer">
                                            <button type="submit" class="dash-btn dash-btn-primary">
                                                <i class="ri-printer-line"></i> Gerar Relatório
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Conferência por Padrão Fiscal -->
                            @if (__isPlanoFiscal())
                            @can('config_produto_fiscal_view')
                            <div class="col-12 col-md-6">
                                <form method="get" action="{{ route('relatorios.conferencia-padrao-fiscal') }}" target="_blank">
                                    <div class="rep-card">
                                        <div class="rep-card-header">
                                            <h5 class="rep-card-title">
                                                <i class="ri-scales-3-line text-primary"></i>
                                                Conferência por Padrão Fiscal
                                            </h5>
                                            <span class="rep-badge rep-badge-excel">Tela / PDF / Excel</span>
                                        </div>
                                        <div class="rep-card-body">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    {!!Form::select('padrao_id', 'Padrão de Tributação', ['' => 'Todos os Padrões'] + (isset($padroesTributacao) ? $padroesTributacao->pluck('descricao', 'id')->all() : []))->attrs(['class' => 'form-select'])!!}
                                                </div>
                                                <div class="col-12">
                                                    {!!Form::select('filtro_divergencia', 'Filtro de Divergências', [
                                                        '' => 'Todos os Produtos Cadastrados',
                                                        'divergentes' => 'Somente Produtos com Divergências Fiscais',
                                                        'iguais' => 'Somente Produtos em Conformidade'
                                                    ])->attrs(['class' => 'form-select'])!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rep-card-footer">
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="submit" class="dash-btn dash-btn-primary flex-grow-1">
                                                    <i class="ri-eye-line"></i> Ver na Tela
                                                </button>
                                                <button type="submit" formaction="{{ route('produtopadrao-tributacao.conferencia-pdf') }}" class="dash-btn dash-btn-light" title="Gerar PDF em A4 Paisagem">
                                                    <i class="ri-file-pdf-line text-danger"></i> PDF
                                                </button>
                                                <button type="submit" formaction="{{ route('produtopadrao-tributacao.conferencia-excel') }}" class="dash-btn dash-btn-light" title="Exportar para Excel (.xlsx)">
                                                    <i class="ri-file-excel-line text-success"></i> Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endcan
                            @endif

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
