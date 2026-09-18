@extends('layouts.app', ['title' => 'Extrato - ' . $item->nome])

@section('css')
<style>
/* ─── Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
    padding: 20px !important;
    margin-bottom: 24px;
}
.filtro-premium-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f3f9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.filtro-premium-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.filtro-premium-title i {
    color: #4f46e5;
    margin-right: 6px;
}
.modulo-glass-filter-premium label {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 5px;
}
.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 40px !important;
    border-radius: 9px !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #334155 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}
.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #4f46e5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}
.modulo-glass-filter-premium .btn-limpar {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e2e8f0 !important;
    color: #334155 !important;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">
            
            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            Extrato da Conta: {{ $item->nome }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Banco: <strong class="text-white">{{ $item->banco ?: 'Interno / Caixa' }}</strong> | 
                            Agência: <strong class="text-white">{{ $item->agencia ?: '--' }}</strong> | 
                            Conta: <strong class="text-white">{{ $item->conta ?: '--' }}</strong> | 
                            Saldo Atual: <strong class="text-white">R$ {{ __moeda($item->saldo) }}</strong>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('conta-pagar.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-circle-line"></i> Nova Despesa
                        </a>
                        <a href="{{ route('contas-empresa.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- ═══ FILTRO DO EXTRATO ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Lançamentos
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-swap-line"></i> Tipo de Transação</label>
                            {!!Form::select('tipo', '', ['' => 'Todas as Transações', 'entrada' => 'Entradas (+)', 'saida' => 'Saídas (-)'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Filtrar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('contas-empresa.show', [$item->id]) }}" title="Limpar Filtro">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA DE MOVIMENTAÇÕES ═══ -->
                <div class="tb-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Data e Hora</th>
                                    <th>Descrição do Lançamento</th>
                                    <th>Meio de Pagamento</th>
                                    <th class="text-end">Valor</th>
                                    <th class="text-end">Saldo Acumulado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $m)
                                <tr>
                                    <td class="text-muted fs-12">{{ __data_pt($m->created_at) }}</td>
                                    <td>
                                        <span class="text-dark fw-semibold d-block">{{ $m->descricao }}</span>
                                        @if($m->caixa_id)
                                        <span class="fs-11 text-muted">
                                            <i class="ri-wallet-3-line align-middle me-1"></i> Fechamento de Caixa #{{ $m->caixa_id }} (Abertura: {{ __data_pt($m->caixa->created_at) }})
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m->tipo_pagamento)
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-11">
                                            {{ App\Models\Nfce::getTipoPagamento($m->tipo_pagamento) }}
                                        </span>
                                        @else
                                        <span class="text-muted fs-12">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold @if($m->tipo == 'entrada') text-success @else text-danger @endif fs-13">
                                        @if($m->tipo == 'entrada')+@else-@endif R$ {{ __moeda($m->valor) }}
                                    </td>
                                    <td class="text-end fw-bold @if($m->saldo_atual >= 0) text-primary @else text-danger @endif fs-13">
                                        R$ {{ __moeda($m->saldo_atual) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-file-list-3-line"></i>
                                            <p>Nenhuma movimentação encontrada para o período selecionado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ PAGINAÇÃO ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        <h6 class="m-0 text-muted fs-13">
                            Exibindo <strong>{{ $data->count() }}</strong> lançamentos
                        </h6>
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
