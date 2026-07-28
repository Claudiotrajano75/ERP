@extends('layouts.app', ['title' => 'Comissão de Vendas'])

@section('css')
<style>
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
}
.modulo-header-gradient .modulo-title {
    color: #fff;
    font-weight: 700;
    letter-spacing: -0.3px;
}
.modulo-header-gradient .modulo-title i {
    background: rgba(255,255,255,0.12);
    padding: 8px;
    border-radius: 10px;
    color: #a8b5ff;
}
.modulo-header-gradient .modulo-subtitle {
    color: rgba(255,255,255,0.6) !important;
    font-weight: 400;
}
.modulo-header-gradient .btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.modulo-header-gradient .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}

.modulo-kpi-card {
    border: none !important;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.25s ease;
    position: relative;
}
.modulo-kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}
.modulo-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}
.modulo-kpi-card .kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.modulo-kpi-card .kpi-value {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.5px;
    line-height: 1.2;
}
.modulo-kpi-card .kpi-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.7;
}
.modulo-kpi-green::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }
.modulo-kpi-orange::before { background: linear-gradient(90deg, #fa709a, #fee140); }
.modulo-kpi-purple::before { background: linear-gradient(90deg, #a18cd1, #fbc2eb); }

.modulo-glass-filter {
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.8) !important;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.04);
}
.modulo-glass-filter label {
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #5a5a7a;
    margin-bottom: 2px;
}
.modulo-glass-filter .btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s;
}
.modulo-glass-filter .btn:hover {
    transform: translateY(-1px);
}

.modulo-table-wrap {
    border-radius: 12px;
    border: 1px solid #eef0f5;
    overflow: hidden;
}
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 12px 14px;
    border-bottom: 2px solid #e8eaf6;
}
.modulo-table-wrap tbody td {
    padding: 12px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    transition: background 0.15s ease;
    font-size: 13px;
}
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.modulo-badge-warning { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }
.modulo-badge-danger  { background: linear-gradient(135deg, #fbe9e7, #ffccbc); color: #c62828; }

.modulo-footer {
    padding: 16px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.modulo-footer .modulo-total-label {
    font-size: 13px;
    color: #5a5a7a;
    font-weight: 600;
}
.modulo-footer .modulo-total-value {
    font-size: 18px;
    font-weight: 800;
    letter-spacing: -0.3px;
}

.modulo-empty {
    padding: 48px 20px;
    text-align: center;
}
.modulo-empty i {
    font-size: 48px;
    color: #c5cae9;
    margin-bottom: 12px;
    display: block;
}
.modulo-empty p {
    color: #9e9eb8;
    font-size: 14px;
    margin: 0;
}

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
    .modulo-kpi-card .kpi-value { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-currency-line"></i>
                            Comissão de Vendas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie as comissões dos vendedores, acompanhe pendências e efetue pagamentos em lote.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI Cards Premium ═══ -->
                @php
                $sumComissaoPendente = $sumComissaoPendente ?? 0;
                $sumComissaoPago = $sumComissaoPago ?? 0;
                $sumVendas = $sumVendas ?? 0;
                @endphp
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-green shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);color:#2e7d32;">
                                    <i class="ri-wallet-3-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-success">R$ {{ __moeda($sumComissaoPago) }}</div>
                                    <div class="kpi-label text-muted">Comissões Pagas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-orange shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#fff3e0,#ffe0b2);color:#e65100;">
                                    <i class="ri-time-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value" style="color:#e65100;">R$ {{ __moeda($sumComissaoPendente) }}</div>
                                    <div class="kpi-label text-muted">Pendentes</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-purple shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#f3e5f5,#e1bee7);color:#7b1fa2;">
                                    <i class="ri-shopping-bag-3-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value" style="color:#7b1fa2;">R$ {{ __moeda($sumVendas) }}</div>
                                    <div class="kpi-label text-muted">Total Vendas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('funcionario_id', 'Funcionário')
                            ->options($funcionario != null ? [$funcionario->id => $funcionario->nome] : [])
                            ->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('status', 'Status', ['' => 'Todos', '0' => 'Pendente', '1' => 'Pago'])
                            ->attrs(['class' => 'form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Filtrar
                                </button>
                                <a class="btn btn-outline-secondary btn-sm px-3" href="{{ route('comissao.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Botão Pagar Selecionados + Tabela ═══ -->
                <form method="post" action="{{ route('comissao.pay-multiple') }}" id="form-comissao">
                    @csrf
                    <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                        <button type="button" class="btn btn-success btn-sm px-3 btn-pay" disabled>
                            <i class="ri-wallet-fill me-1"></i>
                            Pagar <strong class="total-pay">R$ 0,00</strong>
                        </button>
                    </div>

                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width:40px;"><input type="checkbox" class="select-all form-check-input"></th>
                                        <th>Funcionário</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Valor Venda</th>
                                        <th>Valor Comissão</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            @if(!$item->status)
                                            <input type="checkbox" name="check[]" value="{{ $item->id }}" class="select-check form-check-input">
                                            @endif
                                        </td>
                                        <td><span class="fw-semibold text-dark">{{ $item->funcionario->nome }}</span></td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                {{ $item->tabela == 'nfce' ? 'PDV' : 'Pedido' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->status)
                                            <span class="modulo-badge modulo-badge-success">
                                                <i class="ri-check-line"></i> Pago
                                            </span>
                                            @else
                                            <span class="modulo-badge modulo-badge-warning">
                                                <i class="ri-time-line"></i> Pendente
                                            </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor_venda) }}</td>
                                        <td class="fw-bold" style="color:#302b63;">R$ {{ __moeda($item->valor) }}</td>
                                        <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="modulo-empty">
                                                <i class="ri-inbox-2-line"></i>
                                                <p>Nenhuma comissão encontrada.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if(count($data) > 0)
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="4"></td>
                                        <td class="text-success">R$ {{ __moeda($data->sum('valor_venda')) }}</td>
                                        <td style="color:#302b63;">R$ {{ __moeda($data->sum('valor')) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    @include('modals._modal_conta_pagar')
                </form>

                <!-- ═══ Footer ═══ -->
                <div class="modulo-footer">
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <span class="modulo-total-label text-success">Pagas:</span>
                            <span class="modulo-total-value text-success">R$ {{ __moeda($sumComissaoPago) }}</span>
                        </div>
                        <div>
                            <span class="modulo-total-label" style="color:#e65100;">Pendentes:</span>
                            <span class="modulo-total-value" style="color:#e65100;">R$ {{ __moeda($sumComissaoPendente) }}</span>
                        </div>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/comissao.js"></script>
@endsection
