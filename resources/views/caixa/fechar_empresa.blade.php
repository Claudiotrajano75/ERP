@extends('layouts.app', ['title' => 'Fechar Caixa'])

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
    font-size: 20px;
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
        <div class="card border-0 shadow-sm modulo-form-card">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-lock-line"></i>
                            Fechamento de Caixa #{{ $item->id }} — {{ $item->usuario ? $item->usuario->name : '--' }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Confira o resumo das movimentações, vendas e recebimentos antes de encerrar o caixa.</p>
                    </div>
                    <div>
                        <a href="{{ route('caixa.abertos-empresa') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                @php
                $soma = 0;
                $somaDinheiro = 0;
                @endphp

                <!-- ═══ TOTAL POR TIPO DE PAGAMENTO ═══ -->
                <div class="section-headline">
                    <span class="sec-icon" style="background:#eff6ff;color:#2563eb;"><i class="ri-bank-card-line"></i></span>
                    <span>Total por Tipo de Pagamento</span>
                </div>

                <div class="row g-3 mb-4">
                    @foreach($somaTiposPagamento as $key => $tp)
                    @if($tp > 0)
                    @php
                    if($key == '01') $somaDinheiro = $tp;
                    $soma += $tp;
                    @endphp
                    <div class="col-sm-4 col-lg-3 col-6">
                        <div class="info-card-summary">
                            <div class="label-info">{{ App\Models\Nfce::getTipoPagamento($key) }}</div>
                            <div class="val-info text-success">R$ {{ __moeda($tp) }}</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-- ═══ RESUMO DE VENDAS ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-12">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-shopping-cart-line" style="color:#4f46e5;"></i> Total de Vendas</div>
                            <div class="val-info text-dark">R$ {{ __moeda($soma) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-box-3-line" style="color:#16a34a;"></i> Venda de Produtos</div>
                            <div class="val-info text-success">R$ {{ __moeda($soma - $somaServicos) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-service-line" style="color:#0284c7;"></i> Venda de Serviços</div>
                            <div class="val-info text-primary">R$ {{ __moeda($somaServicos) }}</div>
                        </div>
                    </div>
                </div>

                <!-- ═══ TABELA DE MOVIMENTAÇÕES DE VENDAS ═══ -->
                <div class="mt-4 mb-4">
                    <div class="section-headline">
                        <span class="sec-icon" style="background:#ecfdf5;color:#059669;"><i class="ri-file-list-3-line"></i></span>
                        <span>Movimentações de Vendas</span>
                    </div>
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tipo de Documento</th>
                                        <th>Data e Hora</th>
                                        <th class="text-end" style="width: 160px;">Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendas as $i)
                                    <tr>
                                        <td class="fw-semibold">{{ $i->tipo }}</td>
                                        <td class="fs-12 text-muted">{{ __data_pt($i->created_at, 0) }}</td>
                                        <td class="text-end fw-bold text-success">R$ {{ __moeda($i->total) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Nenhuma venda registrada neste caixa.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══ TABELA DE RECEBIMENTOS ═══ -->
                <div class="mt-4 mb-4">
                    <div class="section-headline">
                        <span class="sec-icon" style="background:#eff6ff;color:#2563eb;"><i class="ri-money-dollar-circle-line"></i></span>
                        <span>Movimentações de Recebimentos / Contas</span>
                    </div>
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Data</th>
                                        <th class="text-end" style="width: 160px;">Valor Integral</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contas as $i)
                                    <tr>
                                        <td class="fw-semibold">{{ $i->tipo }}</td>
                                        <td class="fs-12 text-muted">{{ __data_pt($i->created_at, 0) }}</td>
                                        <td class="text-end fw-bold text-success">R$ {{ __moeda($i->valor_integral) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Nenhum recebimento de conta registrado.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══ TOTAL RECEBIDO / TOTAL PAGO ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-12">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-arrow-down-circle-line" style="color:#16a34a;"></i> Total Recebido</div>
                            <div class="val-info text-success">R$ {{ sizeof($receber) > 0 ? __moeda($receber->sum('valor_integral')) : '0,00' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-arrow-up-circle-line" style="color:#dc2626;"></i> Total Pago</div>
                            <div class="val-info text-danger">R$ {{ sizeof($pagar) > 0 ? __moeda($pagar->sum('valor_integral')) : '0,00' }}</div>
                        </div>
                    </div>
                </div>

                @php
                $somaSuprimento = 0;
                $somaSangria = 0;
                @endphp

                <!-- ═══ SUPRIMENTOS E SANGRIAS ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-12">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-add-circle-line" style="color:#0284c7;"></i> Suprimentos</div>
                            @if(sizeof($suprimentos) > 0)
                                @foreach($suprimentos as $s)
                                    @php $somaSuprimento += $s->valor; @endphp
                                @endforeach
                                <div class="val-info text-primary">R$ {{ number_format($somaSuprimento, 2, ',', '.') }}</div>
                            @else
                                <div class="val-info text-muted">R$ 0,00</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="info-card-summary">
                            <div class="label-info"><i class="ri-indeterminate-circle-line" style="color:#d97706;"></i> Sangrias</div>
                            @if(sizeof($sangrias) > 0)
                                @foreach($sangrias as $s)
                                    @php $somaSangria += $s->valor; @endphp
                                @endforeach
                                <div class="val-info text-warning">R$ {{ number_format($somaSangria, 2, ',', '.') }}</div>
                            @else
                                <div class="val-info text-muted">R$ 0,00</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ═══ BALANÇO FINAL E BOTÃO DE FECHAMENTO ═══ -->
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <p class="mb-1 text-muted fs-13">Total de Entradas: <strong class="text-success fs-15">R$ {{ __moeda($soma + $somaSuprimento + $receber->sum('valor_integral')) }}</strong></p>
                            <p class="mb-1 text-muted fs-13">Total de Saídas: <strong class="text-danger fs-15">R$ {{ __moeda($somaSangria + $pagar->sum('valor_integral')) }}</strong></p>
                            <p class="mb-0 text-muted fs-13">Valor de Fechamento Registrado: <strong class="text-dark fs-15">R$ {{ __moeda($item->valor_fechamento) }}</strong></p>
                        </div>
                        <div class="col-md-6 col-12">
                            <p class="mb-1 text-muted fs-13">Valor em Dinheiro: <strong class="text-dark">R$ {{ __moeda($item->valor_dinheiro) }}</strong></p>
                            <p class="mb-1 text-muted fs-13">Valor em Cheque: <strong class="text-dark">R$ {{ __moeda($item->valor_cheque) }}</strong></p>
                            <p class="mb-0 text-muted fs-13">Valor em Outros: <strong class="text-dark">R$ {{ __moeda($item->valor_outros) }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Botão de Ação -->
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <a href="{{ route('caixa.abertos-empresa') }}" class="dash-btn dash-btn-light">
                        <i class="ri-close-line"></i> Cancelar
                    </a>

                    @if(sizeof($vendas) == 0 && sizeof($contas) == 0)
                        <button class="dash-btn dash-btn-primary" data-bs-toggle="modal" data-bs-target="#fechamento_caixa">
                            <i class="ri-lock-line"></i> Fechar Caixa
                        </button>
                    @else
                        @if(sizeof($contasEmpresa) == 0)
                            <button class="dash-btn dash-btn-primary" data-bs-toggle="modal" data-bs-target="#fechamento_caixa">
                                <i class="ri-lock-line"></i> Fechar Caixa
                            </button>
                        @else
                            <a class="dash-btn dash-btn-primary" href="{{ route('caixa.fechar-conta', [$item->id]) }}">
                                <i class="ri-lock-line"></i> Fechar Caixa
                            </a>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@include('modals._fechamento_caixa', ['not_submit' => true])
@endsection
