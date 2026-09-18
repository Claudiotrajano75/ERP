@extends('layouts.app', ['title' => 'Boletos'])

@section('css')
<style>
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    min-height: 105px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.stat-card .stat-icon {
    position: absolute;
    right: 14px;
    bottom: 8px;
    font-size: 52px;
    opacity: .18;
    line-height: 1;
    pointer-events: none;
}
.stat-card .stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    opacity: .88;
}
.stat-card .stat-value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.1;
}
.stat-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
.stat-blue   { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }

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

/* ─── Avatar Banco / Boleto ─── */
.bank-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

/* ─── Grade de Ações ─── */
.act-group { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
.act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 1px solid transparent;
    transition: all .15s ease;
    cursor: pointer;
    text-decoration: none !important;
}
.act-btn:hover { transform: translateY(-1px); }
.act-print { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
.act-print:hover { background: #1e293b; color: #fff; box-shadow: 0 3px 8px rgba(30,41,59,0.3); }
.act-pay   { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
.act-pay:hover   { background: #059669; color: #fff; box-shadow: 0 3px 8px rgba(5,150,105,0.3); }
.act-del   { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
.act-del:hover   { background: #dc2626; color: #fff; box-shadow: 0 3px 8px rgba(220,38,38,0.3); }

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
                            Boletos Gerados
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Acompanhe todos os boletos emitidos, impressões e gerenciamento de arquivos de remessa bancária.</p>
                    </div>
                    <div>
                        <a href="{{ route('remessa-boleto.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-file-list-2-line"></i> Remessas CNAB
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                @if(isset($stats))
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-indigo">
                            <div>
                                <div class="stat-label">Total Emitido</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_valor']) }}</div>
                            </div>
                            <i class="ri-barcode-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-blue">
                            <div>
                                <div class="stat-label">Total de Boletos</div>
                                <div class="stat-value mt-1">{{ $stats['total_boletos'] }}</div>
                            </div>
                            <i class="ri-file-text-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Boletos Recebidos</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_recebido']) }}</div>
                            </div>
                            <i class="ri-checkbox-circle-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Boletos Pendentes</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_pendente']) }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Boletos
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                            {!!Form::select('cliente_id', '', ['' => 'Todos os Clientes'] + ($cliente != null ? [$cliente->id => $cliente->info] : []))->attrs(['class' => 'form-control select2'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Vencimento Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Vencimento Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label"><i class="ri-bank-line"></i> Banco / Carteira</label>
                            {!!Form::select('banco', '', ['' => 'Todos os Bancos'] + $contasBoleto->pluck('info', 'id')->all())->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('boleto.index') }}" title="Limpar Filtro">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Banco Emissor</th>
                                    <th>Cliente / Pagador</th>
                                    <th>Número / Documento</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Valor Nominal</th>
                                    <th class="text-end" style="width:130px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bank-avatar">
                                                <i class="ri-barcode-line"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark d-block">{{ $item->contaBoleto ? $item->contaBoleto->banco : '--' }}</span>
                                                <span class="text-muted fs-11">Boleto #{{ $item->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->contaReceber && $item->contaReceber->cliente ? $item->contaReceber->cliente->info : '--' }}</span>
                                    </td>
                                    <td class="fs-12 text-muted">
                                        Nº: <strong class="text-dark">{{ $item->numero }}</strong> | Doc: <strong class="text-dark">{{ $item->numero_documento }}</strong>
                                    </td>
                                    <td class="fs-13 text-dark">{{ __data_pt($item->vencimento, 0) }}</td>
                                    <td>
                                        @if($item->contaReceber && $item->contaReceber->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-checkbox-circle-line me-1"></i> Recebido
                                        </span>
                                        @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                            <i class="ri-time-line me-1"></i> Pendente
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success fs-14">
                                            R$ {{ __moeda($item->valor) }}
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('boleto.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                {{-- Imprimir PDF --}}
                                                <a target="_blank" class="act-btn act-print" href="{{ route('boleto.print', [$item->id]) }}" title="Imprimir Boleto PDF">
                                                    <i class="ri-printer-line"></i>
                                                </a>

                                                {{-- Receber Conta --}}
                                                @if($item->contaReceber && !$item->contaReceber->status)
                                                @can('conta_receber_edit')
                                                <a title="Dar Baixa / Receber Conta" href="{{ route('conta-receber.pay', $item->contaReceber->id) }}" class="act-btn act-pay">
                                                    <i class="ri-money-dollar-box-line"></i>
                                                </a>
                                                @endcan
                                                @endif

                                                {{-- Excluir --}}
                                                @can('boleto_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Boleto">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="modulo-empty">
                                            <i class="ri-file-list-3-line"></i>
                                            <p>Nenhum boleto gerado ou encontrado.</p>
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
                            Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> boletos
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
