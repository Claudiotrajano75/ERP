@extends('layouts.app', ['title' => 'Cotação #' . $item->referencia])
@section('css')
<style type="text/css">
    @page { size: auto; margin: 0mm; }
    @media print {
        .print { margin: 10px; }
        .d-print-none { display: none !important; }
    }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Badges ─── */
    .modulo-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .modulo-badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
    .modulo-badge-info    { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
    .modulo-badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .modulo-badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
</style>
@endsection
@section('content')

<div class="mt-3 text-dark print">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- Cabeçalho Principal -->
                <div class="card-header modulo-header-gradient py-3 px-4 d-print-none">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-price-tag-3-line"></i>
                                Cotação <strong class="text-white ms-1">#{{ $item->referencia }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Distribuidor: <strong>{{ $item->fornecedor->info }}</strong></p>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @if($cotacaoComCompra == null)
                                @if($item->estado != 'aprovada')
                                <a href="{{ route('cotacoes.purchase', [$item->id]) }}" class="dash-btn dash-btn-primary">
                                    <i class="ri-shopping-cart-line me-1"></i> Gerar Compra
                                </a>
                                @endif
                            @endif
                            @if($item->nfe_id)
                            <a class="dash-btn dash-btn-light" href="{{ route('nfe.show', $item->nfe_id) }}">
                                <i class="ri-file-text-line me-1"></i> Ver NFe
                            </a>
                            @endif
                            <a href="javascript:window.print()" class="dash-btn dash-btn-light">
                                <i class="ri-printer-line me-1"></i> Imprimir
                            </a>
                            <a href="{{ route('cotacoes.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- Informações da Cotação -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 col-12">
                            <div class="card card-secao-fiscal border p-3 rounded-3 bg-white h-100">
                                <h5 class="text-dark fw-bold fs-13 mb-3 border-bottom pb-2"><i class="ri-information-line text-primary me-1"></i> Dados da Cotação</h5>
                                <ul class="list-unstyled mb-0 fs-13" style="line-height: 2.2;">
                                    <li>Referência: <strong class="badge bg-indigo-subtle text-primary border border-indigo-subtle px-2 py-0.5 fs-12 fw-bold" style="background: #eef2ff;">#{{ $item->referencia }}</strong></li>
                                    <li>Responsável: <strong>{{ $item->responsavel ?: '--' }}</strong></li>
                                    <li>Estado Atual:
                                        @if($item->estado == 'aprovada')
                                        <span class="modulo-badge modulo-badge-success"><i class="ri-check-double-line"></i> Aprovada</span>
                                        @elseif($item->estado == 'rejeitada')
                                        <span class="modulo-badge modulo-badge-danger"><i class="ri-close-line"></i> Rejeitada</span>
                                        @elseif($item->estado == 'respondida')
                                        <span class="modulo-badge modulo-badge-info"><i class="ri-chat-1-line"></i> Respondida</span>
                                        @else
                                        <span class="modulo-badge modulo-badge-warning"><i class="ri-time-line"></i> Nova</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card card-secao-fiscal border p-3 rounded-3 bg-white h-100">
                                <h5 class="text-dark fw-bold fs-13 mb-3 border-bottom pb-2"><i class="ri-calendar-event-line text-primary me-1"></i> Datas & Prazos</h5>
                                <ul class="list-unstyled mb-0 fs-13" style="line-height: 2.2;">
                                    <li>Cadastro: <strong>{{ __data_pt($item->created_at, 1) }}</strong></li>
                                    <li>Resposta do Fornecedor: <strong>{{ $item->data_resposta ? __data_pt($item->data_resposta, 1) : '--' }}</strong></li>
                                    <li>Previsão de Entrega: <strong>{{ $item->previsao_entrega ? __data_pt($item->previsao_entrega, 0) : '--' }}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($cotacaoComCompra != null)
                    <div class="alert alert-warning border-0 shadow-sm p-3 mb-4 d-flex align-items-center d-print-none" style="background: #fffbeb; border-radius: 12px;">
                        <i class="ri-alert-line me-2 fs-20 text-warning"></i>
                        <span class="text-warning-emphasis fs-13">Não é possível gerar uma nova compra. <strong>{{ $cotacaoComCompra->fornecedor->info }}</strong> já foi escolhido como fornecedor definitivo desta cotação.</span>
                    </div>
                    @endif

                    <!-- Tabela de Itens -->
                    <div class="mb-4">
                        <h5 class="text-dark fw-bold fs-14 mb-3 d-flex align-items-center gap-2">
                            <i class="ri-box-3-line text-primary"></i> Itens da Cotação ({{ sizeof($item->itens) }})
                        </h5>
                        <div class="tb-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor Unitário</th>
                                            <th>Subtotal</th>
                                            <th>Observação do Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->itens as $i)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $i->produto->nome }}</td>
                                            @php
                                            $casasDecimais = 2;
                                            if($i->produto->unidade == 'UN'){
                                                $casasDecimais = 0;
                                            }
                                            @endphp
                                            <td class="fw-semibold">{{ number_format($i->quantidade, $casasDecimais) }} {{ $i->produto->unidade }}</td>
                                            <td class="fw-bold">R$ {{ __moeda($i->valor_unitario) }}</td>
                                            <td class="fw-bold text-success">R$ {{ __moeda($i->sub_total) }}</td>
                                            <td class="text-muted">{{ $i->observacao ?: '--' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if(sizeof($item->fatura) > 0)
                    <!-- Tabela de Fatura -->
                    <div class="mb-4">
                        <h5 class="text-dark fw-bold fs-14 mb-3 d-flex align-items-center gap-2">
                            <i class="ri-wallet-line text-primary"></i> Fatura / Condições de Pagamento
                        </h5>
                        <div class="tb-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Data de Vencimento</th>
                                            <th>Tipo de Pagamento</th>
                                            <th>Valor (R$)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->fatura as $i)
                                        <tr>
                                            <td>{{ __data_pt($i->data_vencimento, 0) }}</td>
                                            <td>{{ $i->getTipoPagamento() }}</td>
                                            <td class="fw-bold text-success">R$ {{ __moeda($i->valor) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Resumo Financeiro -->
                    <div class="border-top pt-4">
                        <div class="row justify-content-end">
                            <div class="col-md-5 col-12">
                                <div class="card card-secao-fiscal border p-3 rounded-3 bg-white">
                                    <div class="d-flex justify-content-between fs-13 mb-2">
                                        <span class="text-muted">Total dos Produtos:</span>
                                        <strong>R$ {{ __moeda($item->itens->sum('sub_total')) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between fs-13 mb-2">
                                        <span class="text-muted">Desconto Concedido:</span>
                                        <strong class="text-danger">- R$ {{ __moeda($item->desconto) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between fs-13 mb-2">
                                        <span class="text-muted">Valor do Frete:</span>
                                        <strong>R$ {{ __moeda($item->valor_frete) }}</strong>
                                    </div>
                                    <hr class="my-2 opacity-25">
                                    <div class="d-flex justify-content-between fw-bold fs-16">
                                        <span>Valor Total da Proposta:</span>
                                        <span class="text-success fs-18">R$ {{ __moeda($item->valor_total) }}</span>
                                    </div>
                                    @if($item->observacao)
                                    <hr class="my-2 opacity-25">
                                    <div class="fs-12 text-muted"><strong>Obs.:</strong> {{ $item->observacao }}</div>
                                    @endif
                                    @if($item->observacao_resposta)
                                    <div class="fs-12 text-muted mt-1"><strong>Obs. Resposta:</strong> {{ $item->observacao_resposta }}</div>
                                    @endif
                                    @if($item->observacao_frete)
                                    <div class="fs-12 text-muted mt-1"><strong>Obs. Frete:</strong> {{ $item->observacao_frete }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection