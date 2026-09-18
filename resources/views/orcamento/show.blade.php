@extends('layouts.app', ['title' => 'Detalhes do Orçamento'])

@section('css')
<style>
/* ─── Cards de Resumo ─── */
.info-card-summary {
    background: #ffffff;
    border: 1px solid #eef0f6;
    border-radius: 14px;
    padding: 18px 20px;
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    transition: transform .15s ease;
}
.info-card-summary:hover {
    transform: translateY(-2px);
}
.info-card-summary .label-info {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.info-card-summary .val-info {
    font-size: 18px;
    font-weight: 800;
    color: #1f2937;
    line-height: 1.2;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Seções ─── */
.section-headline {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
    border-bottom: 1px solid #eef0f6;
    padding-bottom: 10px;
    margin-bottom: 16px;
}
.section-headline .sec-icon {
    width: 30px;
    height: 30px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            Orçamento #{{ $data->id }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Detalhes completos dos itens, valores e parcelas deste orçamento.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a target="_blank" href="{{ route('orcamentos.imprimir', [$data->id]) }}" class="dash-btn dash-btn-light">
                            <i class="ri-printer-line"></i> Imprimir PDF
                        </a>
                        <a href="{{ route('orcamentos.gerar-venda', [$data->id]) }}" class="dash-btn dash-btn-primary">
                            <i class="ri-checkbox-circle-line"></i> Transformar em Venda
                        </a>
                        <a href="{{ route('orcamentos.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ RESUMO EM CARDS ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-user-line" style="color:#4f46e5;"></i> Cliente</div>
                            <div class="val-info fs-15">{{ $data->cliente_id ? $data->cliente->razao_social : 'Consumidor Final' }}</div>
                            <div class="fs-12 text-muted mt-1">{{ $data->cliente ? $data->cliente->cpf_cnpj : '--' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-money-dollar-circle-line" style="color:#16a34a;"></i> Valor Total</div>
                            <div class="val-info" style="color:#16a34a;">R$ {{ __moeda($data->total) }}</div>
                            <div class="fs-12 text-muted mt-1">{{ count($data->itens) }} itens cadastrados</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-calendar-line" style="color:#0284c7;"></i> Data do Orçamento</div>
                            <div class="val-info fs-15">{{ __data_pt($data->created_at) }}</div>
                            <div class="fs-12 text-muted mt-1">{{ $data->created_at->format('H:i:s') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-flag-line" style="color:#d97706;"></i> Status Atual</div>
                            <div class="val-info fs-15">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-12">Orçamento Aberto</span>
                            </div>
                            <div class="fs-12 text-muted mt-1">Aguardando aprovação</div>
                        </div>
                    </div>
                </div>

                <!-- ═══ ITENS DO ORÇAMENTO ═══ -->
                <div class="mt-4 mb-4">
                    <div class="section-headline">
                        <span class="sec-icon" style="background:#eff6ff;color:#2563eb;"><i class="ri-box-3-line"></i></span>
                        <span>Itens do Orçamento</span>
                    </div>
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center" style="width: 130px;">Quantidade</th>
                                        <th class="text-end" style="width: 150px;">Valor Unitário</th>
                                        <th class="text-end" style="width: 150px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data->itens as $item)
                                    <tr>
                                        <td class="fw-semibold" style="color:#1f2937;">{{ $item->descricao() }}</td>
                                        <td class="text-center fw-bold" style="color:#4f46e5;">{{ $item->quantidade }}</td>
                                        <td class="text-end">R$ {{ __moeda($item->valor_unitario) }}</td>
                                        <td class="text-end fw-bold" style="color:#16a34a;">R$ {{ __moeda($item->sub_total) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Nenhum item encontrado no orçamento.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══ FATURA / FORMAS DE PAGAMENTO ═══ -->
                <div class="mt-4">
                    <div class="section-headline">
                        <span class="sec-icon" style="background:#ecfdf5;color:#059669;"><i class="ri-bank-card-line"></i></span>
                        <span>Fatura / Condições de Pagamento</span>
                    </div>
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Forma de Pagamento</th>
                                        <th class="text-center" style="width: 160px;">Data de Vencimento</th>
                                        <th class="text-end" style="width: 160px;">Valor da Parcela</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data->fatura as $item)
                                    <tr>
                                        <td class="fw-semibold" style="color:#1f2937;">{{ $item->getTipoPagamento($item->tipo_pagamento) }}</td>
                                        <td class="text-center">{{ __data_pt($item->data_vencimento, 0) }}</td>
                                        <td class="text-end fw-bold" style="color:#16a34a;">R$ {{ __moeda($item->valor) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Nenhuma informação de fatura cadastrada.</td>
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
@endsection
