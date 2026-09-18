@extends('layouts.app', ['title' => 'Histórico do Fornecedor'])

@section('css')
<style>
/* ─── Navegação por Abas (Tabs) ─── */
.nav-tabs-custom {
    background: #f8fafc;
    padding: 6px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.nav-tabs-custom .nav-link {
    flex: 1;
    min-width: 160px;
    border-radius: 10px !important;
    padding: 12px 18px;
    font-weight: 600;
    font-size: 13px;
    color: #64748b;
    border: none !important;
    background: transparent;
    text-align: center;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.nav-tabs-custom .nav-link:hover {
    color: #334155;
    background: rgba(255, 255, 255, 0.7);
}

.nav-tabs-custom .nav-link.active {
    background: #ffffff !important;
    color: #4f46e5 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Badges de Status ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.modulo-badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
.modulo-badge-info    { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-list-3-line"></i>
                                Histórico Comercial — <span style="color:#a8b5ff;">{{ $item->info }}</span>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Acompanhe as compras efetuadas, produtos adquiridos e faturas a pagar com este fornecedor.</p>
                        </div>
                        <div>
                            <a href="{{ route('fornecedores.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <ul class="nav nav-pills nav-tabs-custom mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#tab-compras" data-bs-toggle="tab" class="nav-link active" role="tab">
                                <i class="ri-shopping-cart-line"></i>
                                <span>Compras Realizadas</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-produtos" data-bs-toggle="tab" class="nav-link" role="tab">
                                <i class="ri-box-3-line"></i>
                                <span>Produtos Comprados</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-faturas" data-bs-toggle="tab" class="nav-link" role="tab">
                                <i class="ri-wallet-line"></i>
                                <span>Contas & Faturas a Pagar</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- ═══ ABA 1: COMPRAS ═══ -->
                        <div class="tab-pane fade show active" id="tab-compras" role="tabpanel">
                            <div class="tb-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 text-dark">
                                        <thead>
                                            <tr>
                                                <th>Data da Compra</th>
                                                <th>Valor Total</th>
                                                <th>Status</th>
                                                <th>Chave Eletrônica</th>
                                                <th>Nº Documento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @forelse($data as $c)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ __data_pt($c->created_at) }}</span>
                                                </td>
                                                <td class="fw-bold text-success fs-14">R$ {{ __moeda($c->total) }}</td>
                                                <td>
                                                    @if($c->estado == 'aprovado')
                                                        <span class="modulo-badge modulo-badge-success">Aprovado</span>
                                                    @elseif($c->estado == 'cancelado')
                                                        <span class="modulo-badge modulo-badge-danger">Cancelado</span>
                                                    @elseif($c->estado == 'rejeitado')
                                                        <span class="modulo-badge modulo-badge-warning">Rejeitado</span>
                                                    @else
                                                        <span class="modulo-badge modulo-badge-info">Novo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted fs-12 font-monospace">{{ $c->estado == 'aprovado' ? $c->chave : '--' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">{{ $c->estado == 'aprovado' ? $c->numero : '--' }}</span>
                                                </td>
                                            </tr>
                                            @php $total += $c->total; @endphp
                                            @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="modulo-empty">
                                                        <i class="ri-shopping-cart-2-line"></i>
                                                        <p>Nenhuma compra registrada para este fornecedor.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @if(sizeof($data) > 0)
                                        <tfoot>
                                            <tr class="fw-bold bg-light">
                                                <td class="text-dark">Total Acumulado em Compras:</td>
                                                <td class="text-success fs-15" colspan="4">R$ {{ __moeda($total) }}</td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ═══ ABA 2: PRODUTOS ═══ -->
                        <div class="tab-pane fade" id="tab-produtos" role="tabpanel">
                            <div class="tb-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 text-dark">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Código de Barras</th>
                                                <th>Unidade</th>
                                                <th>Quantidade Total</th>
                                                <th>Valor Unitário Médio</th>
                                                <th>Subtotal Comprado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalProdutos = 0; @endphp
                                            @forelse($item->itensNfe ?? [] as $i)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ $i->produto ? $i->produto->nome : ($i->xProd ?? '--') }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fs-12 font-monospace">{{ $i->produto ? ($i->produto->codigo_barras ?: '--') : '--' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $i->uCom ?? 'UN' }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">{{ $i->quantidade ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark">R$ {{ __moeda($i->valor_unitario ?? 0) }}</span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">R$ {{ __moeda(($i->quantidade ?? 0) * ($i->valor_unitario ?? 0)) }}</strong>
                                                </td>
                                            </tr>
                                            @php $totalProdutos += (($i->quantidade ?? 0) * ($i->valor_unitario ?? 0)); @endphp
                                            @empty
                                            <tr>
                                                <td colspan="6">
                                                    <div class="modulo-empty">
                                                        <i class="ri-box-3-line"></i>
                                                        <p>Nenhum item ou produto vinculado registrado.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ═══ ABA 3: FATURAS & CONTAS A PAGAR ═══ -->
                        <div class="tab-pane fade" id="tab-faturas" role="tabpanel">
                            <div class="tb-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 text-dark">
                                        <thead>
                                            <tr>
                                                <th>Vencimento</th>
                                                <th>Valor da Parcela</th>
                                                <th>Status da Fatura</th>
                                                <th>Data de Pagamento</th>
                                                <th>Valor Pago</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($item->contasPagar ?? [] as $f)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ __data_pt($f->data_vencimento) }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark fs-14">R$ {{ __moeda($f->valor_integral) }}</span>
                                                </td>
                                                <td>
                                                    @if($f->status)
                                                        <span class="modulo-badge modulo-badge-success">Quitada / Paga</span>
                                                    @else
                                                        <span class="modulo-badge modulo-badge-warning">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted fs-13">{{ $f->data_pagamento ? __data_pt($f->data_pagamento) : '--' }}</span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">{{ $f->valor_pago ? 'R$ ' . __moeda($f->valor_pago) : '--' }}</strong>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="modulo-empty">
                                                        <i class="ri-wallet-line"></i>
                                                        <p>Nenhuma conta a pagar vinculada a este fornecedor.</p>
                                                    </div>
                                                </td>
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
@endsection
