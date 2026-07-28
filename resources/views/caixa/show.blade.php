@extends('layouts.app', ['title' => 'Detalhes do Caixa'])
@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">
            
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

            <div class="card-body p-4">
                
                <!-- 1. Cabeçalho de Status e Informações do Caixa -->
                <div class="card shadow-none border bg-light-subtle rounded mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h4 class="mb-1 d-flex align-items-center text-dark">
                                    Detalhes do Caixa #{{ $item->id }}
                                    @if($item->status == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-2 fs-12">ABERTO</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-2 fs-12">FECHADO</span>
                                    @endif
                                </h4>
                                <p class="text-muted mb-0 fs-13">
                                    Histórico de movimentações e fechamento do caixa selecionado.
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('caixa.list') }}" class="btn btn-danger btn-sm px-3">
                                    <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                                </a>
                            </div>
                        </div>
                        
                        <hr class="my-3 text-muted opacity-25">
                        
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded p-2 me-2 text-center" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-user-line fs-20 text-muted"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Operador</small>
                                        <span class="fw-medium text-dark">{{ $item->usuario->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded p-2 me-2 text-center" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-calendar-line fs-20 text-muted"></i>
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
                                    <div class="avatar-sm bg-light rounded p-2 me-2 text-center" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-calendar-check-line fs-20 text-muted"></i>
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
                                    <div class="avatar-sm bg-light rounded p-2 me-2 text-center" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-wallet-3-line fs-20 text-muted"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Valor de Abertura</small>
                                        <span class="fw-medium text-success">{{ __moeda($item->valor_abertura) }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($item->contaEmpresa)
                            <div class="col-sm-6 col-md-4 col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded p-2 me-2 text-center" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-bank-card-line fs-20 text-muted"></i>
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

                <!-- 2. Cards de KPIs (Resumo Financeiro) -->
                <div class="row g-3 mb-4">
                    <!-- Saldo Calculado -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card shadow-none border border-start border-3 border-success h-100 mb-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 text-uppercase fs-12 fw-bold">Saldo do Caixa</p>
                                        <h3 class="mb-0 {{ $saldoCaixa >= 0 ? 'text-success' : 'text-danger' }}">R$ {{ __moeda($saldoCaixa) }}</h3>
                                    </div>
                                    <div class="avatar bg-success-subtle text-success p-2 rounded text-center" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-scales-line fs-22"></i>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Abertura + Entradas - Saídas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Entradas -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card shadow-none border border-start border-3 border-info h-100 mb-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 text-uppercase fs-12 fw-bold">Total de Entradas</p>
                                        <h3 class="text-info mb-0">R$ {{ __moeda($totalEntrada) }}</h3>
                                    </div>
                                    <div class="avatar bg-info-subtle text-info p-2 rounded text-center" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-arrow-up-circle-line fs-22"></i>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Vendas + Suprimentos + Rec.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Saídas -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card shadow-none border border-start border-3 border-danger h-100 mb-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 text-uppercase fs-12 fw-bold">Total de Saídas</p>
                                        <h3 class="text-danger mb-0">R$ {{ __moeda($totalSaida) }}</h3>
                                    </div>
                                    <div class="avatar bg-danger-subtle text-danger p-2 rounded text-center" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-arrow-down-circle-line fs-22"></i>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Sangrias + Contas Pagas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Faturamento Vendas -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card shadow-none border border-start border-3 border-secondary h-100 mb-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 text-uppercase fs-12 fw-bold">Total de Vendas</p>
                                        <h3 class="text-secondary mb-0">R$ {{ __moeda($soma) }}</h3>
                                    </div>
                                    <div class="avatar bg-secondary-subtle text-secondary p-2 rounded text-center" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-shopping-bag-3-line fs-22"></i>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="text-success fw-medium fs-12">Prod: R$ {{ __moeda($soma - $somaServicos) }}</span>
                                    <span class="text-muted mx-1">|</span>
                                    <span class="text-info fw-medium fs-12">Serv: R$ {{ __moeda($somaServicos) }}</span>
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
                                <ul class="nav nav-tabs nav-bordered mb-3" id="caixaTabs" role="tablist">
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
                                        <div class="table-responsive">
                                            <table class="table table-centered table-hover table-nowrap align-middle mb-0">
                                                <thead class="table-light">
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
                                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">NFe</span>
                                                            @elseif($i->tipo == 'PDV')
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">PDV</span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">OS</span>
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
                                                        <td colspan="3" class="text-center text-muted py-4">Nenhuma venda registrada neste caixa.</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                                @if(count($vendas) > 0)
                                                <tfoot>
                                                    <tr class="table-light fw-bold">
                                                        <td colspan="2" class="text-end">Total das Vendas:</td>
                                                        <td class="text-end text-success fw-bold">R$ {{ __moeda($somaLista) }}</td>
                                                    </tr>
                                                </tfoot>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <!-- ABA 2: Contas -->
                                    <div class="tab-pane fade text-dark" id="contas-pane" role="tabpanel" aria-labelledby="contas-tab" tabindex="0">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="text-muted fs-13 d-block mb-1">Total Recebido</span>
                                                        <h4 class="text-info mb-0">R$ {{ __moeda($receber->sum('valor_integral')) }}</h4>
                                                    </div>
                                                    <div class="avatar bg-info-subtle text-info p-2 rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="ri-checkbox-circle-line fs-20"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="text-muted fs-13 d-block mb-1">Total Pago</span>
                                                        <h4 class="text-danger mb-0">R$ {{ __moeda($pagar->sum('valor_integral')) }}</h4>
                                                    </div>
                                                    <div class="avatar bg-danger-subtle text-danger p-2 rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="ri-indeterminate-circle-line fs-20"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h5 class="card-title mb-3 fs-15">Histórico de Contas no Caixa</h5>
                                        <div class="table-responsive">
                                            <table class="table table-centered table-hover table-nowrap align-middle mb-0">
                                                <thead class="table-light">
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
                                                        <td colspan="3" class="text-center text-muted py-4">Nenhum pagamento ou recebimento registrado.</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
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
                                                        <span class="badge bg-info text-white">R$ {{ __moeda($somaSuprimento) }}</span>
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-centered table-hover align-middle mb-0 fs-12">
                                                                <thead>
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
                                                        <span class="badge bg-danger text-white">R$ {{ __moeda($somaSangria) }}</span>
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-centered table-hover align-middle mb-0 fs-12">
                                                                <thead>
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
                        <div class="card shadow-none border mb-3">
                            <div class="card-header bg-danger-subtle py-2.5 border-bottom border-danger-subtle">
                                <h5 class="card-title mb-0 d-flex align-items-center fs-14 text-danger fw-bold">
                                    <i class="ri-close-circle-line me-2 fs-16"></i>
                                    Resumo do Fechamento
                                </h5>
                            </div>
                            <div class="card-body py-2">
                                <ul class="list-group list-group-flush mb-0">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="fs-13 fw-semibold text-danger">Valor Informado:</span>
                                        <span class="fw-bold text-danger fs-14">R$ {{ __moeda($item->valor_fechamento) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="fs-13 text-muted">Valor em Dinheiro:</span>
                                        <span class="fw-medium text-dark fs-13">R$ {{ __moeda($item->valor_dinheiro) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="fs-13 text-muted">Valor em Cheque:</span>
                                        <span class="fw-medium text-dark fs-13">R$ {{ __moeda($item->valor_cheque) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="fs-13 text-muted">Valor Outros:</span>
                                        <span class="fw-medium text-dark fs-13">R$ {{ __moeda($item->valor_outros) }}</span>
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
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2.5">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs rounded-circle bg-light text-success p-1 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="ri-money-dollar-circle-line fs-16"></i>
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
