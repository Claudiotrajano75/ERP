@extends('layouts.app', ['title' => 'Detalhes do Pedido - E-commerce'])

@section('css')
<style type="text/css">
    @page { size: auto; margin: 0mm; }
    @media print {
        .print-container { margin: 10px; }
        .d-print-none { display: none !important; }
    }
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark print-container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4 d-print-none">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-2-line"></i>
                                Pedido de E-commerce #{{ $item->hash_pedido }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Detalhes completos do pedido de venda online.</p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('pedidos-ecommerce.alterar-estado', $item->id) }}" class="dash-btn dash-btn-light">
                                <i class="ri-refresh-line"></i> Alterar Estado
                            </a>
                            <a class="dash-btn dash-btn-light" href="javascript:window.print()">
                                <i class="ri-printer-line"></i> Imprimir
                            </a>
                            @if($item->nfe_id == 0)
                            <a class="dash-btn dash-btn-primary" href="{{ route('pedidos-ecommerce.gerar-nfe', $item->id) }}">
                                <i class="ri-file-text-line"></i> Gerar NFe
                            </a>
                            @else
                            <a class="dash-btn dash-btn-primary" href="{{ route('nfe.show', $item->nfe_id) }}">
                                <i class="ri-file-text-line"></i> Ver NFe #{{ $item->nfe_id }}
                            </a>
                            @endif
                            <a href="{{ route('pedidos-ecommerce.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- Resumo do Pedido -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Código / Hash</span>
                                <span class="fw-bold text-dark fs-14 d-block mt-1">#{{ $item->hash_pedido }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Valor Total</span>
                                <span class="fw-bold text-success fs-16 d-block mt-1">R$ {{ __moeda($item->valor_total) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Data do Pedido</span>
                                <span class="fw-bold text-dark fs-14 d-block mt-1">{{ __data_pt($item->created_at) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Status Pagamento</span>
                                <div class="mt-1">
                                    @if($item->status_pagamento == 'approved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-12">Aprovado</span>
                                    @elseif($item->status_pagamento == 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-12">Pendente</span>
                                    @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-12">Pendente Depósito</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ ITENS DO PEDIDO ═══ -->
                    <div class="mt-4">
                        <h5 class="text-dark border-bottom pb-2 mb-3 fs-15 fw-bold">
                            <i class="ri-box-2-line me-1 text-primary align-middle"></i> Itens do Pedido
                        </h5>
                        <div class="tb-wrap mb-4">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th class="text-center">Quantidade</th>
                                            <th class="text-end">Valor Unitário</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->itens as $i)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $i->descricao() }}</td>
                                            <td class="text-center">{{ number_format($i->quantidade, 0) }}</td>
                                            <td class="text-end">R$ {{ __moeda($i->valor_unitario) }}</td>
                                            <td class="text-end fw-bold text-success">R$ {{ __moeda($i->sub_total) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ CLIENTE E ENDEREÇO DE ENTREGA ═══ -->
                    <div class="row g-4 mt-2">
                        <div class="col-md-6 col-12">
                            <div class="card border p-3 h-100" style="border-radius: 12px; background: #fafbff;">
                                <h6 class="fw-bold text-dark mb-3"><i class="ri-user-line text-primary me-1"></i> Informações do Cliente</h6>
                                <p class="mb-1 fs-13"><strong>Nome:</strong> {{ $item->cliente ? $item->cliente->info : ($item->nome . ' ' . $item->sobre_nome) }}</p>
                                <p class="mb-1 fs-13"><strong>E-mail:</strong> {{ $item->cliente ? $item->cliente->email : '--' }}</p>
                                <p class="mb-1 fs-13"><strong>Telefone:</strong> {{ $item->cliente ? $item->cliente->telefone : '--' }}</p>
                                <p class="mb-1 fs-13"><strong>Frete Selecionado:</strong> {{ $item->tipo_frete ?: 'Padrão' }}</p>
                                @if($item->codigo_rastreamento)
                                <p class="mb-0 fs-13"><strong>Rastreamento:</strong> <span class="badge bg-primary">{{ $item->codigo_rastreamento }}</span></p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card border p-3 h-100" style="border-radius: 12px; background: #fafbff;">
                                <h6 class="fw-bold text-dark mb-3"><i class="ri-map-pin-line text-primary me-1"></i> Endereço de Entrega</h6>
                                <p class="mb-1 fs-13"><strong>Logradouro:</strong> {{ $item->rua_entrega ?: '--' }}, Nº {{ $item->numero_entrega ?: 'S/N' }}</p>
                                <p class="mb-1 fs-13"><strong>Bairro:</strong> {{ $item->bairro_entrega ?: '--' }}</p>
                                <p class="mb-1 fs-13"><strong>CEP:</strong> {{ $item->cep_entrega ?: '--' }}</p>
                                <p class="mb-0 fs-13"><strong>Cidade:</strong> {{ $item->cidade_entrega ?: '--' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
