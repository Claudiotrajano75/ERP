@extends('layouts.app', ['title' => 'Cotação #' . $item->referencia])
@section('css')
<style type="text/css">
    @page { size: auto; margin: 0mm; }
    @media print {
        .print { margin: 10px; }
        .d-print-none { display: none !important; }
    }

    /* ─── Header Gradiente ─── */
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

    /* ─── Form Card (Create/Edit) ─── */
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-form-card .card-body { background: #fff; }

    /* ─── Premium Table ─── */
    .modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
    .modulo-table-wrap tbody tr { transition: all 0.15s ease; }
    .modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection
@section('content')

<div class="mt-3 text-dark print">
    <div class="row">
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
                    <div class="d-inline-flex gap-1">
                        @if($cotacaoComCompra == null)
                            @if($item->estado != 'aprovada')
                            <a href="{{ route('cotacoes.purchase', [$item->id]) }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-bookmark-line align-middle me-1"></i> Gerar Compra
                            </a>
                            @endif
                        @endif
                        @if($item->nfe_id)
                        <a class="btn btn-success btn-sm px-3" href="{{ route('nfe.show', $item->nfe_id) }}">
                            <i class="ri-file-text-line align-middle me-1"></i> Ver NFe
                        </a>
                        @endif
                        <a href="javascript:window.print()" class="btn btn-primary btn-sm px-3">
                            <i class="ri-printer-line align-middle me-1"></i> Imprimir
                        </a>
                        <a href="{{ route('cotacoes.index') }}" class="btn btn-danger btn-sm px-3">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- Informações da Cotação -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-12">
                        <div class="bg-light-subtle border rounded p-3">
                            <h5 class="text-dark fw-bold fs-13 mb-3">Dados da Cotação</h5>
                            <ul class="list-unstyled mb-0 fs-13" style="line-height: 2.0;">
                                <li>Referência: <strong class="text-success">#{{ $item->referencia }}</strong></li>
                                <li>Responsável: <strong>{{ $item->responsavel }}</strong></li>
                                <li>Estado:
                                    @if($item->estado == 'aprovada')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Aprovada</span>
                                    @elseif($item->estado == 'rejeitada')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Rejeitada</span>
                                    @elseif($item->estado == 'respondida')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Respondida</span>
                                    @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Nova</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="bg-light-subtle border rounded p-3">
                            <h5 class="text-dark fw-bold fs-13 mb-3">Datas</h5>
                            <ul class="list-unstyled mb-0 fs-13" style="line-height: 2.0;">
                                <li>Cadastro: <strong>{{ __data_pt($item->created_at, 1) }}</strong></li>
                                <li>Resposta: <strong>{{ __data_pt($item->data_resposta, 1) }}</strong></li>
                                <li>Previsão de Entrega: <strong>{{ __data_pt($item->previsao_entrega, 0) }}</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if($cotacaoComCompra != null)
                <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning p-3 mb-4 d-flex align-items-center d-print-none">
                    <i class="ri-alert-line me-2 fs-18"></i>
                    <span>Não é possível gerar uma nova compra. <strong>{{ $cotacaoComCompra->fornecedor->info }}</strong> já foi escolhido como fornecedor desta cotação.</span>
                </div>
                @endif

                <!-- Tabela de Itens -->
                <div class="mb-4">
                    <h5 class="text-dark fw-bold fs-14 mb-3"><i class="ri-box-3-line me-1 text-primary align-middle"></i> Itens da Cotação</h5>
                    <div class="modulo-table-wrap">
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
                                        <td class="fw-semibold text-dark">{{ $i->produto->nome }}</td>
                                        @php
                                        $casasDecimais = 2;
                                        if($i->produto->unidade == 'UN'){
                                            $casasDecimais = 0;
                                        }
                                        @endphp
                                        <td>{{ number_format($i->quantidade, $casasDecimais) }}</td>
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
                    <h5 class="text-dark fw-bold fs-14 mb-3"><i class="ri-wallet-line me-1 text-primary align-middle"></i> Fatura / Condições de Pagamento</h5>
                    <div class="modulo-table-wrap">
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
                        <div class="col-md-4 col-12">
                            <div class="bg-light-subtle border rounded p-3">
                                <div class="d-flex justify-content-between fs-13 mb-2">
                                    <span class="text-muted">Total dos Produtos</span>
                                    <strong>R$ {{ __moeda($item->itens->sum('sub_total')) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between fs-13 mb-2">
                                    <span class="text-muted">Desconto</span>
                                    <strong class="text-danger">- R$ {{ __moeda($item->desconto) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between fs-13 mb-2">
                                    <span class="text-muted">Valor do Frete</span>
                                    <strong>R$ {{ __moeda($item->valor_frete) }}</strong>
                                </div>
                                <hr class="my-2 opacity-25">
                                <div class="d-flex justify-content-between fw-bold fs-15">
                                    <span>Valor Total</span>
                                    <span class="text-success">R$ {{ __moeda($item->valor_total) }}</span>
                                </div>
                                @if($item->observacao)
                                <hr class="my-2 opacity-25">
                                <div class="fs-12 text-muted">Obs.: {{ $item->observacao }}</div>
                                @endif
                                @if($item->observacao_resposta)
                                <div class="fs-12 text-muted">Obs. Resposta: {{ $item->observacao_resposta }}</div>
                                @endif
                                @if($item->observacao_frete)
                                <div class="fs-12 text-muted">Obs. Frete: {{ $item->observacao_frete }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection