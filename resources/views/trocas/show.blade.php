@extends('layouts.app', ['title' => 'Detalhes da Troca'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card (Create/Edit/Show) ─── */
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
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-arrow-go-back-fill"></i>
                            Detalhes da Troca
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Informações detalhadas da troca de mercadorias.</p>
                    </div>
                    <div>
                        <a href="{{ route('trocas.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- Informações da Troca -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Cliente</span>
                            <span class="fw-bold text-dark fs-15">{{ $item->nfce->cliente_id ? $item->nfce->cliente->razao_social : 'Consumidor Final' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Valor Venda</span>
                            <span class="fw-bold text-success fs-18">R$ {{ __moeda($item->valor_original) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Valor Troca</span>
                            <span class="fw-bold text-danger fs-18">R$ {{ __moeda($item->valor_troca) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-light-subtle border rounded p-3 text-center">
                            <span class="text-muted fs-12 text-uppercase fw-semibold d-block">Data</span>
                            <span class="fw-bold text-dark fs-15">{{ __data_pt($item->created_at) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Itens da Venda Original -->
                <div class="mt-4">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-box-2-line me-2 text-primary fs-18"></i> Itens da Venda Original
                    </h5>
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unit.</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->nfce->itens as $i)
                                    <tr>
                                        <td class="fw-semibold">{{ $i->produto->nome }}</td>
                                        <td>{{ $i->quantidade }}</td>
                                        <td>R$ {{ __moeda($i->valor_unitario) }}</td>
                                        <td class="fw-bold text-success">R$ {{ __moeda($i->sub_total) }}</td>
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

                <!-- Itens Alterados (Troca) -->
                <div class="mt-4">
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-arrow-go-back-line me-2 text-warning fs-18"></i> Itens Alterados (Troca)
                    </h5>
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->itens as $i)
                                    <tr>
                                        <td class="fw-semibold">{{ $i->produto->nome }}</td>
                                        <td>{{ $i->quantidade }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Nenhum item alterado.</td>
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
