@extends('layouts.app', ['title' => 'Detalhes da NFCe'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-bill-line"></i>
                                Detalhes da NFCe #{{ $data->numero_sequencial ?: $data->id }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Informações completas da nota fiscal de consumidor eletrônica.</p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @if(__isPlanoFiscal() && $data->estado == 'aprovado')
                            <a href="{{ route('nfce.download-xml', [$data->id]) }}" class="dash-btn dash-btn-light">
                                <i class="ri-file-download-line"></i> Download XML
                            </a>
                            <a class="dash-btn dash-btn-primary" target="_blank" href="{{ route('nfce.imprimir', [$data->id]) }}">
                                <i class="ri-printer-line"></i> Imprimir DANFCE
                            </a>
                            @endif
                            <a href="{{ route('nfce.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- Informações Principais da Nota -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Cliente</span>
                                <span class="fw-bold text-dark fs-14 d-block text-truncate mt-1">
                                    {{ $data->cliente_id ? $data->cliente->razao_social : ($data->cliente_nome ?: 'Consumidor Final') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Valor Total</span>
                                <span class="fw-bold text-success fs-16 d-block mt-1">R$ {{ __moeda($data->total) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Data Cadastro</span>
                                <span class="fw-bold text-dark fs-14 d-block mt-1">{{ __data_pt($data->created_at) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light-subtle border rounded p-3 text-center" style="border-radius: 12px !important;">
                                <span class="text-muted fs-11 text-uppercase fw-bold d-block">Estado Fiscal</span>
                                <div class="mt-1">
                                    @if($data->estado == 'aprovado')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-12">Aprovado</span>
                                    @elseif($data->estado == 'cancelado')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-12">Cancelado</span>
                                    @elseif($data->estado == 'rejeitado')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-12">Rejeitado</span>
                                    @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-12">Novo</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ ITENS DA NFCE ═══ -->
                    <div class="mt-4">
                        <h5 class="text-dark border-bottom pb-2 mb-3 fs-15 fw-bold">
                            <i class="ri-box-2-line me-1 text-primary align-middle"></i> Itens da NFCe
                        </h5>
                        <div class="tb-wrap mb-4">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor Unitário</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->itens as $item)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $item->produto ? $item->produto->nome : '--' }}</td>
                                            <td>{{ $item->quantidade }}</td>
                                            <td>R$ {{ __moeda($item->valor_unitario) }}</td>
                                            <td class="text-end fw-bold text-success">R$ {{ __moeda($item->sub_total) }}</td>
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
                    </div>

                    <!-- ═══ FATURA / PAGAMENTOS ═══ -->
                    <div class="mt-4">
                        <h5 class="text-dark border-bottom pb-2 mb-3 fs-15 fw-bold">
                            <i class="ri-coins-line me-1 text-primary align-middle"></i> Fatura / Pagamentos
                        </h5>
                        <div class="tb-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Forma de Pagamento</th>
                                            <th>Data de Vencimento</th>
                                            <th class="text-end">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->fatura as $item)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $item->getTipoPagamento($item->tipo_pagamento) }}</td>
                                            <td>{{ __data_pt($item->data_vencimento, 0) }}</td>
                                            <td class="text-end fw-bold text-success">R$ {{ __moeda($item->valor) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">NFCe sem informações detalhadas de pagamento.</td>
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
@endsection
