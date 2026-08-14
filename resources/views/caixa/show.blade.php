@extends('layouts.app', ['title' => 'Detalhes do Caixa'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-table-wrap tfoot td { background: #f8f9fc; font-weight: 700; border-top: 2px solid #e8eaf6; }

/* ─── Badges Premium ─── */
.badge { padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
.bg-success-subtle { background-color: #ecfdf5 !important; color: #047857 !important; border-color: #a7f3d0 !important; }
.bg-danger-subtle { background-color: #fef2f2 !important; color: #b91c1c !important; border-color: #fecaca !important; }
.bg-info-subtle { background-color: #ecfeff !important; color: #0e7490 !important; border-color: #a5f3fc !important; }
.bg-warning-subtle { background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important; }
.bg-primary-subtle { background-color: #eef2ff !important; color: #4338ca !important; border-color: #c7d2fe !important; }
.bg-light-subtle { background-color: #f8f9fc !important; color: #475569 !important; border-color: #e2e8f0 !important; }

/* ─── Detalhes do Caixa ─── */
.caixa-info-card { background: linear-gradient(135deg, #f8f9fc 0%, #eef1f8 100%); border: 1px solid #e8ecf4; border-radius: 14px; }
.caixa-info-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }

/* ─── Abas ─── */
.nav-tabs.nav-caixa { border-bottom: 2px solid #eef0f5; gap: 2px; }
.nav-tabs.nav-caixa .nav-link { border: none; border-radius: 10px 10px 0 0; color: #64748b; font-weight: 600; font-size: 13px; padding: 10px 16px; transition: all 0.2s ease; }
.nav-tabs.nav-caixa .nav-link:hover { color: #4f46e5; background: #f5f6fe; }
.nav-tabs.nav-caixa .nav-link.active { color: #4f46e5; background: #fff; box-shadow: inset 0 -2px 0 #4f46e5; }

/* ─── Meios de pagamento (lateral) ─── */
.meio-pag-item { transition: all 0.2s ease; border-radius: 10px; }
.meio-pag-item:hover { background: #f5f6fe; }
.meio-pag-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; }

/* ─── Vazio ─── */
.modulo-empty { padding: 40px 16px; text-align: center; }
.modulo-empty i { font-size: 40px; color: #c5cae9; display: block; margin-bottom: 10px; }
.modulo-empty p { color: #9e9eb8; font-size: 13px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            @php
            // Pré-cálculos para simplificação do layout
            $soma = 0;
            $somaDinheiro = 0;
            foreach($somaTiposPagamento as $key => $tp) {
                if($tp > 0) {
                    $soma += $tp;
                    if($key == '01') {
                        $somaDinheiro = $tp;
                    }
                }
            }
            $somaSuprimento = 0;
            foreach($suprimentos as $s) {
                $somaSuprimento += $s->valor;
            }
            $somaSangria = 0;
            foreach($sangrias as $s) {
                $somaSangria += $s->valor;
            }
            $totalEntrada = $soma + $somaSuprimento + $receber->sum('valor_integral');
            $totalSaida = $somaSangria + $pagar->sum('valor_integral');
            $saldoCaixa = $soma + $somaSuprimento + $item->valor_abertura + $receber->sum('valor_integral') - $somaSangria - $pagar->sum('valor_integral');
            @endphp

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-wallet-3-line"></i>
                            Detalhes do Caixa #{{ $item->id }}
                            @if($item->status == 1)
                            <span class="badge bg-success text-white border border-success ms-2 fs-12">ABERTO</span>
                            @else
                            <span class="badge bg-danger text-white border border-danger ms-2 fs-12">FECHADO</span>
                            @endif
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Histórico de movimentações e fechamento do caixa selecionado.</p>
                    </div>
                    <div>
                        <a href="{{ route('caixa.list') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- 1. Detalhes do Caixa -->
                <div class="caixa-info-card mb-4">
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="caixa-info-icon me-2 text-primary">
                                        <i class="ri-user-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Operador</small>
                                        <span class="fw-medium text-dark">{{ $item->usuario->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="caixa-info-icon me-2 text-info">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Abertura</small>
                                        <span class="fw-medium text-dark">{{ __data_pt($item->created_at, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($item->status == 0 && $item->data_fechamento)
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="caixa-info-icon me-2 text-danger">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Fechamento</small>
                                        <span class="fw-medium text-dark">{{ __data_pt($item->data_fechamento, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="caixa-info-icon me-2 text-success">
                                        <i class="ri-wallet-3-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Valor de Abertura</small>
                                        <span class="fw-medium text-success">R$ {{ __moeda($item->valor_abertura) }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($item->contaEmpresa)
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="caixa-info-icon me-2 text-warning">
                                        <i class="ri-bank-card-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Conta Vínculo</small>
                                        <span class="fw-medium text-dark">{{ $item->contaEmpresa->nome }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($item->observacao)
                        <div class="alert alert-info mt-3 mb-0 py-2 border-0 bg-info-subtle text-info">
                            <i class="ri-information-line me-1 align-middle"></i> <strong>Observação:</strong> {{ $item->observacao }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Cards de KPIs Coloridos (Resumo Financeiro) -->
                <div class="row g-3 mb-4">
                    <!-- Saldo Calculado -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card widget-icon-box {{ $saldoCaixa >= 0 ? 'text-bg-success' : 'text-bg-danger' }} mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Saldo do Caixa</h4>
                                        <h3 class="my-2 text-white fs-22">R$ {{ __moeda($saldoCaixa) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Abertura + Entradas - Saídas</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-scales-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Entradas -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card widget-icon-box text-bg-info mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Entradas</h4>
                                        <h3 class="my-2 text-white fs-22">R$ {{ __moeda($totalEntrada) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Vendas + Suprimentos + Rec.</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-arrow-up-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Saídas -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card widget-icon-box text-bg-danger mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Saídas</h4>
                                        <h3 class="my-2 text-white fs-22">R$ {{ __moeda($totalSaida) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Sangrias + Contas Pagas</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-arrow-down-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Faturamento Vendas -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card widget-icon-box text-bg-warning mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Vendas</h4>
                                        <h3 class="my-2 text-white fs-22">R$ {{ __moeda($soma) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">
                                            <span class="text-white">Prod: R$ {{ __moeda($soma - $somaServicos) }}</span>
                                            <span class="mx-1 opacity-75">|</span>
                                            <span class="text-white">Serv: R$ {{ __moeda($somaServicos) }}</span>
                                        </p>
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
                </div>

                <!-- 3. Tabelas Detalhadas em Abas e Coluna Lateral -->
                <div class="row">
                    <!-- Coluna das Abas (Tabelas Detalhadas) -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-none border h-100 mb-0">
                            <div class="card-body">

                                <!-- Links das Abas -->
                                <ul class="nav nav-tabs nav-caixa mb-3" id="caixaTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active fw-medium" id="vendas-tab" data-bs-toggle="tab" data-bs-target="#vendas-pane" type="button" role="tab" aria-controls="vendas-pane" aria-selected="true">
                                            <i class="ri-shopping-bag-3-line me-1 align-middle"></i> Vendas ({{ count($vendas) }})
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-medium" id="contas-tab" data-bs-toggle="tab" data-bs-target="#contas-pane" type="button" role="tab" aria-controls="contas-pane" aria-selected="false">
                                            <i class="ri-hand-coin-line me-1 align-middle"></i> Contas ({{ count($contas) }})
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-medium" id="suprimentos-tab" data-bs-toggle="tab" data-bs-target="#suprimentos-pane" type="button" role="tab" aria-controls="suprimentos-pane" aria-selected="false">
                                            <i class="ri-exchange-funds-line me-1 align-middle"></i> Suprimentos/Sangrias ({{ count($suprimentos) + count($sangrias) }})
                                        </button>
                                    </li>
                                </ul>

                                <!-- Conteúdo das Abas -->
                                <div class="tab-content text-muted" id="caixaTabsContent">

                                    <!-- ABA 1: Vendas -->
                                    <div class="tab-pane fade show active text-dark" id="vendas-pane" role="tabpanel" aria-labelledby="vendas-tab" tabindex="0">
                                        <h5 class="card-title mb-3 fs-15">Movimentações de Vendas (NFe, NFCe/PDV, OS)</h5>
                                        <div class="modulo-table-wrap">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-hover table-nowrap align-middle mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>Data / Hora</th>
                                                            <th class="text-end">Valor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $somaLista = 0; @endphp
                                                        @forelse ($vendas as $i)
                                                        <tr>
                                                            <td>
                                                                @if($i->tipo == 'NFe')
                                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="ri-file-list-3-line me-1 align-middle"></i>NFe</span>
                                                                @elseif($i->tipo == 'PDV')
                                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="ri-shopping-cart-line me-1 align-middle"></i>PDV</span>
                                                                @else
                                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="ri-tools-line me-1 align-middle"></i>OS</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ __data_pt($i->created_at, 1) }}</td>
                                                            @if($i->tipo != 'OS')
                                                            <td class="text-end fw-bold text-dark">R$ {{ __moeda($i->total) }}</td>
                                                            @else
                                                            <td class="text-end fw-bold text-dark">R$ {{ __moeda($i->valor) }}</td>
                                                            @endif
                                                        </tr>
                                                        @php
                                                        if($i->tipo != 'OS'){
                                                            $somaLista += $i->total;
                                                        }else{
                                                            $somaLista += $i->valor;
                                                        }
                                                        @endphp
                                                        @empty
                                                        <tr>
                                                            <td colspan="3">
                                                                <div class="modulo-empty">
                                                                    <i class="ri-shopping-bag-3-line"></i>
                                                                    <p>Nenhuma venda registrada neste caixa.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                    @if(count($vendas) > 0)
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" class="text-end">Total das Vendas:</td>
                                                            <td class="text-end text-success">R$ {{ __moeda($somaLista) }}</td>
                                                        </tr>
                                                    </tfoot>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ABA 2: Contas -->
                                    <div class="tab-pane fade text-dark" id="contas-pane" role="tabpanel" aria-labelledby="contas-tab" tabindex="0">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <div class="card widget-icon-box text-bg-info mb-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h4 class="text-uppercase fs-11 mt-0 text-white-50 mb-1">Total Recebido</h4>
                                                                <h4 class="text-white mb-0 fs-18">R$ {{ __moeda($receber->sum('valor_integral')) }}</h4>
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                                    <i class="ri-checkbox-circle-line"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card widget-icon-box text-bg-danger mb-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h4 class="text-uppercase fs-11 mt-0 text-white-50 mb-1">Total Pago</h4>
                                                                <h4 class="text-white mb-0 fs-18">R$ {{ __moeda($pagar->sum('valor_integral')) }}</h4>
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                                    <i class="ri-indeterminate-circle-line"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="card-title mb-3 fs-15">Histórico de Contas no Caixa</h5>
                                        <div class="modulo-table-wrap">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-hover table-nowrap align-middle mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>Data</th>
                                                            <th class="text-end">Valor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($contas as $i)
                                                        <tr>
                                                            <td>
                                                                @if($i->tipo == 'Conta Recebida')
                                                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1"><i class="ri-arrow-left-down-line align-middle me-1"></i>Recebida</span>
                                                                @else
                                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="ri-arrow-right-up-line align-middle me-1"></i>Paga</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ __data_pt($i->created_at, 0) }}</td>
                                                            <td class="text-end fw-bold {{ $i->tipo == 'Conta Recebida' ? 'text-info' : 'text-danger' }}">
                                                                {{ $i->tipo == 'Conta Recebida' ? '+' : '-' }} R$ {{ __moeda($i->valor_integral) }}
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3">
                                                                <div class="modulo-empty">
                                                                    <i class="ri-hand-coin-line"></i>
                                                                    <p>Nenhum pagamento ou recebimento registrado.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ABA 3: Suprimentos/Sangrias -->
                                    <div class="tab-pane fade text-dark" id="suprimentos-pane" role="tabpanel" aria-labelledby="suprimentos-tab" tabindex="0">
                                        <div class="row g-3">
                                            <!-- Suprimentos -->
                                            <div class="col-md-6">
                                                <div class="card border border-light-subtle shadow-none mb-0">
                                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center border-bottom">
                                                        <h5 class="card-title mb-0 fs-13 text-info"><i class="ri-add-circle-line me-1 align-middle"></i>Suprimentos</h5>
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle">R$ {{ __moeda($somaSuprimento) }}</span>
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-centered table-hover align-middle mb-0 fs-12">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Data</th>
                                                                        <th>Valor</th>
                                                                        <th>Obs/Conta</th>
                                                                        <th class="text-end">Ação</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($suprimentos as $s)
                                                                    <tr>
                                                                        <td>{{ __data_pt($s->created_at) }}</td>
                                                                        <td class="fw-bold text-info">R$ {{ __moeda($s->valor) }}</td>
                                                                        <td class="text-truncate" style="max-width: 120px;" title="{{ $s->observacao }}">
                                                                            {{ $s->contaEmpresa ? $s->contaEmpresa->nome : '' }}
                                                                            @if($s->observacao)
                                                                                <small class="text-muted d-block">{{ $s->observacao }}</small>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <a target="_blank" href="{{ route('suprimento.print', [$s->id]) }}" class="btn btn-light btn-xs px-1.5 py-0.5" title="Imprimir Suprimento">
                                                                                <i class="ri-printer-line text-dark"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="4" class="text-center text-muted py-3">Nenhum suprimento.</td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sangrias -->
                                            <div class="col-md-6">
                                                <div class="card border border-light-subtle shadow-none mb-0">
                                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center border-bottom">
                                                        <h5 class="card-title mb-0 fs-13 text-danger"><i class="ri-subtract-line me-1 align-middle"></i>Sangrias</h5>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">R$ {{ __moeda($somaSangria) }}</span>
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-centered table-hover align-middle mb-0 fs-12">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Data</th>
                                                                        <th>Valor</th>
                                                                        <th>Obs/Conta</th>
                                                                        <th class="text-end">Ação</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($sangrias as $s)
                                                                    <tr>
                                                                        <td>{{ __data_pt($s->created_at) }}</td>
                                                                        <td class="fw-bold text-danger">R$ {{ __moeda($s->valor) }}</td>
                                                                        <td class="text-truncate" style="max-width: 120px;" title="{{ $s->observacao }}">
                                                                            {{ $s->contaEmpresa ? $s->contaEmpresa->nome : '' }}
                                                                            @if($s->observacao)
                                                                                <small class="text-muted d-block">{{ $s->observacao }}</small>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <a target="_blank" href="{{ route('sangria.print', [$s->id]) }}" class="btn btn-light btn-xs px-1.5 py-0.5" title="Imprimir Sangria">
                                                                                <i class="ri-printer-line text-dark"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="4" class="text-center text-muted py-3">Nenhuma sangria.</td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
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

                    <!-- Coluna lateral: Meios de Pagamento & Resumo de Fechamento -->
                    <div class="col-lg-4 mb-4 text-dark">
                        <!-- Card 1: Resumo de Fechamento (Se aplicável) -->
                        @if($item->status == 0)
                        <div class="card widget-icon-box text-bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3 d-flex align-items-center fs-14 fw-bold text-white">
                                    <i class="ri-close-circle-line me-2 fs-16"></i>
                                    Resumo do Fechamento
                                </h5>
                                <ul class="list-group list-group-flush mb-0">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 bg-transparent text-white border-0">
                                        <span class="fs-13 fw-semibold">Valor Informado:</span>
                                        <span class="fw-bold fs-14">R$ {{ __moeda($item->valor_fechamento) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 bg-transparent text-white border-0">
                                        <span class="fs-13 text-white-50">Valor em Dinheiro:</span>
                                        <span class="fw-medium fs-13">R$ {{ __moeda($item->valor_dinheiro) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 bg-transparent text-white border-0">
                                        <span class="fs-13 text-white-50">Valor em Cheque:</span>
                                        <span class="fw-medium fs-13">R$ {{ __moeda($item->valor_cheque) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 bg-transparent text-white border-0">
                                        <span class="fs-13 text-white-50">Valor Outros:</span>
                                        <span class="fw-medium fs-13">R$ {{ __moeda($item->valor_outros) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif

                        <!-- Card 2: Faturamento por Meio de Pagamento -->
                        <div class="card shadow-none border mb-0">
                            <div class="card-header bg-transparent py-3 border-bottom">
                                <h5 class="card-title mb-0 d-flex align-items-center fs-15 text-dark">
                                    <i class="ri-wallet-3-line text-primary me-2 fs-18"></i>
                                    Vendas por Meio de Pagamento
                                </h5>
                            </div>
                            <div class="card-body pt-0">
                                <ul class="list-group list-group-flush mb-0">
                                    @php $hasMeios = false; @endphp
                                    @foreach($somaTiposPagamento as $key => $tp)
                                    @if($tp > 0)
                                    @php $hasMeios = true; @endphp
                                    <li class="list-group-item meio-pag-item d-flex justify-content-between align-items-center px-2 py-2 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="meio-pag-icon bg-success-subtle text-success me-2">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </div>
                                            <span class="fs-13 fw-medium">{{ App\Models\Nfce::getTipoPagamento($key) }}</span>
                                        </div>
                                        <span class="fw-bold text-dark fs-13">R$ {{ __moeda($tp) }}</span>
                                    </li>
                                    @endif
                                    @endforeach

                                    @if(!$hasMeios)
                                    <li class="list-group-item text-center text-muted px-0 py-4">
                                        Nenhuma venda faturada neste caixa.
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
