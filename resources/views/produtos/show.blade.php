@extends('layouts.app', ['title' => 'Movimentações'])

@section('css')
<style type="text/css">
    @page { size: auto; margin: 0mm; }
    @media print {
        .print {
            margin: 10px;
        }
    }
    
    /* ─── Estilos Personalizados Premium ─── */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }
    
    .card-body {
        padding: 24px !important;
    }
    
    /* ─── Cabeçalho de Gradiente Premium ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }
    
    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }
    
    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }
    
    /* ─── KPI Cards (widget-icon-box) ─── */
    .widget-icon-box {
        border-radius: 12px !important;
        border: none !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }
    .widget-icon-box:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }
    .widget-icon-box-avatar {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ─── Tabela Premium ─── */
    .modulo-table-wrap {
        border-radius: 12px;
        border: 1px solid #eef0f5;
        overflow: hidden;
    }
    .modulo-table-wrap table {
        margin-bottom: 0;
    }
    .modulo-table-wrap thead th {
        background: #f8f9fc;
        color: #5a5a7a;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 14px;
        border-bottom: 2px solid #e8eaf6;
    }
    .modulo-table-wrap tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.15s ease;
        font-size: 13px;
    }
    .modulo-table-wrap tbody tr:hover {
        background: #f5f6fe;
    }
    .modulo-table-wrap tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* ─── Abas / Tabs Custom ─── */
    .nav-tabs.nav-bordered .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #6c757d;
        padding: 12px 20px;
        background: transparent;
    }
    .nav-tabs.nav-bordered .nav-link.active {
        border-bottom: 2px solid #302b63;
        color: #302b63 !important;
        font-weight: 600;
    }
</style>
@endsection

@section('content')

<div class="mt-3 print text-dark">
    <div class="row">
        <div class="col-lg-12">
            
            <!-- Detalhes do Produto (Card Superior) -->
            <div class="card border-0 shadow-sm text-dark mb-4">
                <div class="card-header modulo-header-gradient py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <img class="img-60 rounded border border-light-subtle me-3 bg-white" src="{{ $item->img }}" style="width: 55px; height: 55px; object-fit: cover;">
                        <div>
                            <h4 class="mb-1 text-white fw-bold">{{ $item->nome }}</h4>
                            <p class="text-white-50 mb-0 fs-13">
                                Categoria: <strong class="text-white">{{ $item->categoria ? $item->categoria->nome : '--' }}</strong> | 
                                Marca: <strong class="text-white">{{ $item->marca ? $item->marca->nome : '--' }}</strong> | 
                                Cód: <strong class="text-white">{{ $item->codigo_barras ?? '--' }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('produtos.download-zip', [$item->id]) }}" class="btn btn-light btn-sm px-3 d-print-none text-dark" title="Baixar todas as imagens">
                            <i class="ri-download-2-line align-middle me-1"></i> Imagens (ZIP)
                        </a>
                        <a href="{{ route('produtos.index') }}" class="btn btn-danger btn-sm px-3 d-print-none">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-success mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Preço de Venda</h4>
                                            <h3 class="my-2 text-white fs-18">R$ {{ __moeda($item->valor_unitario) }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Valor unitário cobrado</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-primary mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Preço de Compra</h4>
                                            <h3 class="my-2 text-white fs-18">R$ {{ __moeda($item->valor_compra) }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Custo de aquisição</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-shopping-bag-3-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-info mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Movimentações</h4>
                                            <h3 class="my-2 text-white fs-18">{{ sizeof($data) }} registros</h3>
                                            <p class="mb-0 text-white-50 fs-11">Total histórico</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-history-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-dark mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Data de Cadastro</h4>
                                            <h3 class="my-2 text-white fs-18">{{ __data_pt($item->created_at, 0) }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Inclusão no sistema</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-calendar-event-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abas com Movimentações e Fornecedores -->
            <div class="card border-0 shadow-sm text-dark">
                <div class="card-header bg-transparent border-bottom pt-3 pb-0 px-4">
                    <ul class="nav nav-tabs nav-bordered border-0 mb-0" id="productShowTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold fs-14" id="movimentacao-tab" data-bs-toggle="tab" data-bs-target="#movimentacao-pane" type="button" role="tab" aria-controls="movimentacao-pane" aria-selected="true">
                                <i class="ri-history-line me-1 align-middle fs-16"></i> Histórico de Movimentações
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold fs-14" id="fornecedores-tab" data-bs-toggle="tab" data-bs-target="#fornecedores-pane" type="button" role="tab" aria-controls="fornecedores-pane" aria-selected="false">
                                <i class="ri-user-shared-line me-1 align-middle fs-16"></i> Fornecedores Vinculados
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="tab-content p-4 text-dark" id="productShowTabContent">
                    
                    <!-- Aba 1: Movimentações -->
                    <div class="tab-pane fade show active" id="movimentacao-pane" role="tabpanel" aria-labelledby="movimentacao-tab" tabindex="0">
                        <div class="modulo-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th># ID</th>
                                            <th>Quantidade</th>
                                            <th>Estoque Atual</th>
                                            <th>Transação</th>
                                            <th>Operador</th>
                                            <th>Data/Hora</th>
                                            <th>Tipo</th>
                                            <th>Variação</th>
                                            <th class="text-end d-print-none" style="width: 100px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $i)
                                        <tr>
                                            <td><span class="text-muted">#{{ $i->id }}</span></td>
                                            <td class="fw-bold">{{ number_format($i->quantidade, 2) }}</td>
                                            <td>{{ $i->estoque_atual ? number_format($i->estoque_atual, 2) : '--' }}</td>
                                            <td>{{ $i->tipoTransacao() }}</td>
                                            <td class="text-muted">{{ $i->user ? $i->user->name : '' }}</td>
                                            <td>{{ __data_pt($i->created_at) }}</td>
                                            <td>
                                                @if($i->tipo == 'incremento')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                    <i class="ri-add-line me-1"></i>Incremento
                                                </span>
                                                @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                                    <i class="ri-subtract-line me-1"></i>Redução
                                                </span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $i->produtoVariacao ? $i->produtoVariacao->descricao : '--' }}</span></td>
                                            <td class="text-end d-print-none">
                                                <a class="btn btn-outline-secondary btn-sm" href="{{ route('produtos.movimentacao', [$i->id]) }}">
                                                    <i class="ri-eye-line align-middle me-1"></i> Visualizar
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Nenhuma movimentação registrada para este produto.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($data->count() > 0)
                        <div class="d-flex align-items-center justify-content-end mt-3 bg-light p-2.5 rounded border border-dashed">
                            <span class="fw-semibold text-dark">Soma de Quantidades Movimentadas: <strong class="text-primary ms-1 fs-15">{{ number_format($data->sum('quantidade'), 2) }}</strong></span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Aba 2: Fornecedores -->
                    <div class="tab-pane fade" id="fornecedores-pane" role="tabpanel" aria-labelledby="fornecedores-tab" tabindex="0">
                        <div class="modulo-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Razão Social</th>
                                            <th>CNPJ / CPF</th>
                                            <th>Logradouro</th>
                                            <th>Número</th>
                                            <th>Bairro</th>
                                            <th>Cidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->fornecedores as $i)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $i->fornecedor->razao_social }}</td>
                                            <td class="text-muted">{{ $i->fornecedor->cpf_cnpj }}</td>
                                            <td>{{ $i->fornecedor->rua }}</td>
                                            <td>{{ $i->fornecedor->numero }}</td>
                                            <td>{{ $i->fornecedor->bairro }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $i->fornecedor->cidade->info }}</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Nenhum fornecedor vinculado a este produto.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Rodapé de Ações do Card -->
                <div class="card-footer bg-transparent border-top p-3 d-print-none px-4">
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="javascript:window.print()" class="btn btn-primary px-4">
                            <i class="ri-printer-line me-1 align-middle"></i> Imprimir Extrato
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection