@extends('layouts.app', ['title' => 'Histórico 360º do Cliente'])

@section('css')
<style type="text/css">
.tb-wrap {
    border-radius: 14px;
    border: 1px solid #eef0f5;
    overflow: hidden;
    background: #fff;
}
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8eaf6;
    white-space: nowrap;
}
.tb-wrap tbody td {
    padding: 13px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13.5px;
    color: #374151;
}
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

.stat-card {
    border: 0;
    border-radius: 16px;
    padding: 18px 20px;
    height: 100%;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
.stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
.stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.32); }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-user-follow-line"></i>
                            {{ $cliente->razao_social }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">{{ $cliente->cpf_cnpj }} • {{ $cliente->cidade ? $cliente->cidade->info : 'Sem cidade cadastrada' }}</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        <a href="{{ route('crm.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar ao CRM
                        </a>
                        @if($cliente->telefone)
                            <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $cliente->telefone) }}" target="_blank" class="dash-btn dash-btn-primary" style="background: #16a34a;">
                                <i class="ri-whatsapp-line"></i> Contato WhatsApp
                            </a>
                        @endif
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="dash-btn dash-btn-light">
                            <i class="ri-edit-line"></i> Editar Cadastro
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPIs do Cliente ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-sm-6">
                        <div class="stat-card stat-green">
                            <div class="text-uppercase" style="font-size: 11px; font-weight: 700; opacity: 0.9;">Total Comprado (NF-e)</div>
                            <div class="fs-2 fw-bold text-white mt-1">R$ {{ __moeda($totalComprado) }}</div>
                            <small class="text-white opacity-75">{{ $nfes->where('estado', 'aprovado')->count() }} notas fiscais emitidas</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="stat-card stat-indigo">
                            <div class="text-uppercase" style="font-size: 11px; font-weight: 700; opacity: 0.9;">Total Recebido</div>
                            <div class="fs-2 fw-bold text-white mt-1">R$ {{ __moeda($totalRecebido) }}</div>
                            <small class="text-white opacity-75">{{ $contas->where('status', 1)->count() }} títulos quitados</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="stat-card {{ $totalAberto > 0 ? 'stat-red' : 'stat-indigo' }}">
                            <div class="text-uppercase" style="font-size: 11px; font-weight: 700; opacity: 0.9;">Em Aberto / Pendente</div>
                            <div class="fs-2 fw-bold text-white mt-1">R$ {{ __moeda($totalAberto) }}</div>
                            <small class="text-white opacity-75">{{ $contas->where('status', 0)->count() }} parcelas a receber</small>
                        </div>
                    </div>
                </div>

                <!-- ═══ Tabs Histórico ═══ -->
                <ul class="nav nav-pills mb-4 gap-2" id="pills-crm-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold px-4 py-2" id="pills-nfe-tab" data-bs-toggle="pill" data-bs-target="#pills-nfe" type="button" role="tab">
                            <i class="ri-file-list-3-line me-1"></i> Vendas & NF-e ({{ $nfes->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2" id="pills-financeiro-tab" data-bs-toggle="pill" data-bs-target="#pills-financeiro" type="button" role="tab">
                            <i class="ri-money-dollar-circle-line me-1"></i> Contas a Receber ({{ $contas->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-crm-tabContent">
                    <!-- TAB NFES -->
                    <div class="tab-pane fade show active" id="pills-nfe" role="tabpanel">
                        <div class="tb-wrap table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Número</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th class="text-end">Valor dos Produtos</th>
                                        <th class="text-end">Desconto</th>
                                        <th class="text-end pe-4">Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nfes as $nfe)
                                    <tr>
                                        <td class="ps-4 fw-bold">NF-e {{ $nfe->numero }} (Série {{ $nfe->numero_serie }})</td>
                                        <td>{{ $nfe->created_at ? $nfe->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            @if($nfe->estado == 'aprovado')
                                                <span class="badge bg-success">Aprovada</span>
                                            @elseif($nfe->estado == 'cancelado')
                                                <span class="badge bg-danger">Cancelada</span>
                                            @elseif($nfe->estado == 'rejeitado')
                                                <span class="badge bg-warning text-dark">Rejeitada</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($nfe->estado) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">R$ {{ __moeda($nfe->valor_produtos) }}</td>
                                        <td class="text-end text-danger">- R$ {{ __moeda($nfe->desconto) }}</td>
                                        <td class="text-end pe-4 fw-bold text-success">R$ {{ __moeda($nfe->total) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Nenhuma venda ou NF-e registrada para este cliente.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB CONTAS A RECEBER -->
                    <div class="tab-pane fade" id="pills-financeiro" role="tabpanel">
                        <div class="tb-wrap table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Vencimento</th>
                                        <th>Status</th>
                                        <th class="text-end">Valor Integral</th>
                                        <th class="text-end">Valor Recebido</th>
                                        <th>Data Pagamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contas as $c)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ \Carbon\Carbon::parse($c->data_vencimento)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($c->status == 1)
                                                <span class="badge bg-success">Recebido</span>
                                            @elseif(\Carbon\Carbon::parse($c->data_vencimento)->isPast())
                                                <span class="badge bg-danger">Vencida</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pendente</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">R$ {{ __moeda($c->valor_integral) }}</td>
                                        <td class="text-end text-success fw-bold">R$ {{ __moeda($c->valor_recebido) }}</td>
                                        <td>{{ $c->data_recebimento ? \Carbon\Carbon::parse($c->data_recebimento)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Nenhum título financeiro registrado para este cliente.</td>
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
