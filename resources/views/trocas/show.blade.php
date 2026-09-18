@extends('layouts.app', ['title' => 'Detalhes da Troca'])

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
                            <i class="ri-arrow-go-back-line"></i>
                            Detalhes da Troca #{{ $item->numero_sequencial }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Informações detalhadas dos itens e valores da troca de mercadoria.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="dash-btn dash-btn-light"
                            onclick="PrintThermal.imprimir('troca', {{$item->id}}, '{{ route('trocas.imprimir', $item->id) }}')">
                            <i class="ri-printer-line"></i> Imprimir Cupom
                        </button>
                        <a href="{{ route('trocas.index') }}" class="dash-btn dash-btn-light">
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
                            <div class="val-info fs-15">{{ $item->nfce->cliente_id ? $item->nfce->cliente->razao_social : 'Consumidor Final' }}</div>
                            <div class="fs-12 text-muted mt-1">{{ $item->nfce->cliente ? $item->nfce->cliente->cpf_cnpj : '--' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-shopping-bag-3-line" style="color:#16a34a;"></i> Venda Original</div>
                            <div class="val-info" style="color:#16a34a;">R$ {{ __moeda($item->valor_original) }}</div>
                            <div class="fs-12 text-muted mt-1">NFCe #{{ $item->nfce ? $item->nfce->numero_sequencial : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-exchange-dollar-line" style="color:#dc2626;"></i> Valor da Troca</div>
                            <div class="val-info" style="color:#dc2626;">R$ {{ __moeda($item->valor_troca) }}</div>
                            <div class="fs-12 text-muted mt-1">Código: <strong>{{ $item->codigo }}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-calendar-line" style="color:#0284c7;"></i> Data do Registro</div>
                            <div class="val-info fs-15">{{ __data_pt($item->created_at) }}</div>
                            <div class="fs-12 text-muted mt-1">{{ $item->created_at->format('H:i:s') }}</div>
                        </div>
                    </div>
                </div>

                <!-- ═══ ITENS DA VENDA ORIGINAL ═══ -->
                <div class="mt-4 mb-4">
                    <div class="section-headline">
                        <span class="sec-icon" style="background:#eff6ff;color:#2563eb;"><i class="ri-box-3-line"></i></span>
                        <span>Itens da Venda Original</span>
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
                                    @forelse($item->nfce->itens as $i)
                                    <tr>
                                        <td class="fw-semibold" style="color:#1f2937;">{{ $i->produto->nome }}</td>
                                        <td class="text-center fw-bold" style="color:#4f46e5;">{{ $i->quantidade }}</td>
                                        <td class="text-end">R$ {{ __moeda($i->valor_unitario) }}</td>
                                        <td class="text-end fw-bold" style="color:#16a34a;">R$ {{ __moeda($i->sub_total) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Nenhum item encontrado na venda original.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══ ITENS ALTERADOS (TROCA) ═══ -->
                <div class="mt-4">
                    <div class="section-headline">
                        <span class="sec-icon" style="background:#fffbeb;color:#d97706;"><i class="ri-arrow-go-back-line"></i></span>
                        <span>Itens Trocados / Devolvidos</span>
                    </div>
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center" style="width: 150px;">Quantidade Trocada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->itens as $i)
                                    <tr>
                                        <td class="fw-semibold" style="color:#1f2937;">{{ $i->produto->nome }}</td>
                                        <td class="text-center fw-bold" style="color:#dc2626;">{{ $i->quantidade }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Nenhum item alterado registrado.</td>
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
