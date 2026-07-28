@extends('layouts.app', ['title' => 'Detalhes da venda PDV - NFCe'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-bill-line me-2 text-primary fs-22"></i>
                            Detalhes da NFCe
                        </h4>
                        <p class="text-muted mb-0 fs-13">Informações detalhadas da nota fiscal de consumidor eletrônica.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        <a href="{{ route('nfce.index') }}" class="btn btn-danger btn-sm px-3">
                            <i class="ri-arrow-left-double-fill align-middle me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- Informações do Cliente e Totais -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Cliente</span>
                            <span class="fw-bold text-dark fs-15">{{ $data->cliente_id ? $data->cliente->razao_social : 'Consumidor Final' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Valor Total</span>
                            <span class="fw-bold text-success fs-18">R$ {{ __moeda($data->total) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Data Cadastro</span>
                            <span class="fw-bold text-dark fs-15">{{ __data_pt($data->created_at) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Estado</span>
                            @if($data->estado == 'aprovado')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-13">Aprovado</span>
                            @elseif($data->estado == 'cancelado')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-13">Cancelado</span>
                            @elseif($data->estado == 'rejeitado')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-13">Rejeitado</span>
                            @else
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 fs-13">Novo</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(__isPlanoFiscal())
                <div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
                    <span class="text-muted fs-12">Data de emissão: <strong>{{ __data_pt($data->data_emissao) }}</strong></span>
                    @if($data->estado == 'aprovado')
                    <a href="{{ route('nfce.download-xml', [$data->id]) }}" class="btn btn-dark btn-sm">
                        <i class="ri-file-download-line me-1"></i> Download XML
                    </a>
                    <a class="btn btn-primary btn-sm text-white" title="Imprimir NFCe" target="_blank" href="{{ route('nfce.imprimir', [$data->id]) }}">
                        <i class="ri-printer-line me-1"></i> Imprimir
                    </a>
                    @endif
                </div>
                @endif

                <!-- Itens da NFCe -->
                <div class="mt-4">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-box-2-line me-2 text-primary fs-18"></i> Itens da NFCe
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Qtd</th>
                                    <th>Valor Unit.</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data->itens as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->produto->nome }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td>R$ {{ __moeda($item->valor_unitario) }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->sub_total) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Nenhum item encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Fatura -->
                <div class="mt-4">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-coins-line me-2 text-primary fs-18"></i> Fatura / Pagamentos
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead class="table-light">
                                <tr>
                                    <th>Pagamento</th>
                                    <th>Data Vencimento</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data->fatura as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->getTipoPagamento($item->tipo_pagamento) }}</td>
                                    <td>{{ __data_pt($item->data_vencimento, 0) }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->valor) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">NFCe sem informações de pagamento.</td>
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
@endsection
