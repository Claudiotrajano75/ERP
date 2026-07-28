@extends('layouts.app', ['title' => 'Histórico do Fornecedor'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-list-3-line"></i>
                                Histórico Comercial — <strong style="color:#f8bbd0;">{{ $item->info }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Acompanhe as compras efetuadas, produtos adquiridos e faturas a pagar com este fornecedor.</p>
                        </div>
                        <div>
                            <a href="{{ route('fornecedores.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div id="basicwizard">
                        <ul class="nav nav-pills nav-justified mb-4 border p-1 rounded bg-light" role="tablist">
                            <li class="nav-item">
                                <a href="#tab-compras" data-bs-toggle="tab" class="nav-link rounded py-2 active" role="tab">
                                    <i class="ri-shopping-cart-line me-1 align-middle fs-16"></i> Compras Realizadas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-produtos" data-bs-toggle="tab" class="nav-link rounded py-2" role="tab">
                                    <i class="ri-box-3-line me-1 align-middle fs-16"></i> Produtos Comprados
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-faturas" data-bs-toggle="tab" class="nav-link rounded py-2" role="tab">
                                    <i class="ri-wallet-line me-1 align-middle fs-16"></i> Contas & Faturas
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content border p-3 rounded bg-white shadow-sm mt-3">
                            <div class="tab-pane show active" id="tab-compras" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data da Compra</th>
                                                <th>Valor Total</th>
                                                <th>Estado</th>
                                                <th>Chave Eletrônica</th>
                                                <th>Nº Documento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @forelse($data as $c)
                                            <tr>
                                                <td>{{ __data_pt($c->created_at) }}</td>
                                                <td class="fw-bold text-success">R$ {{ __moeda($c->total) }}</td>
                                                <td>
                                                    @if($c->estado == 'aprovado')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                                    @elseif($c->estado == 'cancelado')
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                                    @elseif($c->estado == 'rejeitado')
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                                    @else<span class="badge bg-light text-dark border px-2 py-1 fs-11">Novo</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted fs-12">{{ $c->estado == 'aprovado' ? $c->chave : '--' }}</td>
                                                <td class="fw-semibold">{{ $c->estado == 'aprovado' ? $c->numero : '--' }}</td>
                                            </tr>
                                            @php $total += $c->total; @endphp
                                            @empty
                                            <tr><td colspan="5" class="text-center text-muted py-3">Nenhuma compra registrada.</td></tr>
                                            @endforelse
                                        </tbody>
                                        @if(sizeof($data) > 0)
                                        <tfoot class="table-light"><tr class="fw-bold"><td>Total Acumulado</td><td class="text-success fs-15" colspan="4">R$ {{ __moeda($total) }}</td></tr></tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-produtos" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60px;">Imagem</th>
                                                <th>Descrição</th>
                                                <th>Quantidade</th>
                                                <th>Valor Unitário</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($produtos as $p)
                                            <tr>
                                                <td><img class="rounded border bg-light" src="{{ $p->produto->img }}" alt="" style="width: 38px; height: 38px; object-fit: cover;"></td>
                                                <td class="fw-semibold text-dark">{{ $p->produto->nome }}</td>
                                                <td>{{ number_format($p->quantidade, 2) }}</td>
                                                <td>R$ {{ __moeda($p->valor_unitario) }}</td>
                                                <td class="fw-bold text-success">R$ {{ __moeda($p->quantidade * $p->valor_unitario) }}</td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="5" class="text-center text-muted py-3">Nenhum produto comprado.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-faturas" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Descrição</th>
                                                <th>Lançamento</th>
                                                <th>Vencimento</th>
                                                <th>Pagamento</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($faturas as $c)
                                            <tr>
                                                <td class="fw-semibold text-dark">{{ $c->descricao }}</td>
                                                <td>{{ __data_pt($c->created_at) }}</td>
                                                <td>{{ __data_pt($c->data_vencimento, 0) }}</td>
                                                <td>{{ $c->status ? __data_pt($c->data_recebimento, 0) : '--' }}</td>
                                                <td class="fw-bold text-success">R$ {{ __moeda($c->valor_integral) }}</td>
                                                <td>
                                                    @if($c->status)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11"><i class="ri-checkbox-circle-line me-1"></i>Pago</span>
                                                    @else
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11"><i class="ri-alert-line me-1"></i>Pendente</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="6" class="text-center text-muted py-3">Nenhuma fatura lançada.</td></tr>
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

@section('js')
<script src="/assets/vendor/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script src="/assets/js/pages/demo.form-wizard.js"></script>
@endsection
