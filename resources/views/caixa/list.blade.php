@extends('layouts.app', ['title' => 'Lista de Caixas'])

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
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
.stat-blue   { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }

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

/* ─── Avatar Operador ─── */
.operator-avatar {
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
.act-print { background: #e0f2fe; color: #0284c7; border-color: #bae6fd; }
.act-print:hover { background: #0284c7; color: #fff; box-shadow: 0 3px 8px rgba(2,132,199,0.3); }
.act-view  { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-view:hover  { background: #4f46e5; color: #fff; box-shadow: 0 3px 8px rgba(79,70,229,0.3); }

/* ─── Cards do Modal de Impressão ─── */
.print-option-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px 16px;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}
.print-option-card:hover {
    transform: translateY(-3px);
    border-color: #4f46e5;
    box-shadow: 0 8px 20px rgba(79,70,229,0.12);
}
.print-option-card .print-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 12px;
}

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
                            <i class="ri-wallet-3-line"></i>
                            Histórico de Caixas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Acompanhe todos os caixas abertos, fechados e relatórios de fechamento por operador.</p>
                    </div>
                    <div>
                        @if(__isAdmin())
                        <a href="{{ route('caixa.abertos-empresa') }}" class="dash-btn dash-btn-light">
                            <i class="ri-list-indefinite"></i> Caixas Abertos
                        </a>
                        @endif
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
                                <div class="stat-label">Total de Caixas</div>
                                <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                            </div>
                            <i class="ri-wallet-3-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Caixas Abertos</div>
                                <div class="stat-value mt-1">{{ $stats['abertos'] }}</div>
                            </div>
                            <i class="ri-lock-unlock-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-blue">
                            <div>
                                <div class="stat-label">Caixas Fechados</div>
                                <div class="stat-value mt-1">{{ $stats['fechados'] }}</div>
                            </div>
                            <i class="ri-lock-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Abertos Hoje</div>
                                <div class="stat-value mt-1">{{ $stats['hoje'] }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ═══ FILTRO DE PESQUISA ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-filter-3-line"></i> Filtrar Caixas
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
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-flag-line"></i> Status</label>
                            {!!Form::select('status', '', ['' => 'Todos os Status', '1' => 'Aberto', '0' => 'Fechado'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-6 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('caixa.list') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap mb-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Operador (Caixa)</th>
                                    <th>Status</th>
                                    <th>Data de Abertura</th>
                                    <th>Data de Fechamento</th>
                                    <th>Valor Abertura</th>
                                    <th>Valor Fechamento</th>
                                    <th class="text-end" style="width: 120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="operator-avatar">
                                                <i class="ri-user-3-line"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark d-block">{{ $item->usuario ? $item->usuario->name : '--' }}</span>
                                                <span class="text-muted fs-11">Caixa #{{ $item->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->status == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-checkbox-circle-line me-1"></i> Aberto
                                        </span>
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">
                                            <i class="ri-lock-line me-1"></i> Fechado
                                        </span>
                                        @endif
                                    </td>
                                    <td class="fs-12 text-dark">
                                        {{ __data_pt($item->created_at) }}
                                        <span class="d-block fs-11 text-muted">{{ $item->created_at->format('H:i:s') }}</span>
                                    </td>
                                    <td class="fs-12 text-muted">
                                        @if($item->data_fechamento)
                                            {{ __data_pt($item->data_fechamento) }}
                                            <span class="d-block fs-11 text-muted">{{ \Carbon\Carbon::parse($item->data_fechamento)->format('H:i:s') }}</span>
                                        @else
                                            <span class="badge bg-light text-muted border fs-11">Em Aberto</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success fs-13">R$ {{ __moeda($item->valor_abertura) }}</strong>
                                    </td>
                                    <td>
                                        @if($item->data_fechamento)
                                            <strong class="text-danger fs-13">R$ {{ __moeda($item->valor_fechamento) }}</strong>
                                        @else
                                            <span class="text-muted fs-12">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="act-group">
                                            @if($item->status == 0)
                                            <button type="button" onclick="imprimir('{{$item->id}}')" class="act-btn act-print" title="Imprimir Relatório de Fechamento">
                                                <i class="ri-printer-line"></i>
                                            </button>
                                            @endif
                                            <a class="act-btn act-view" href="{{ route('caixa.show', $item) }}" title="Visualizar Detalhes do Caixa">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="modulo-empty">
                                            <i class="ri-wallet-3-line"></i>
                                            <p>Nenhum registro de caixa encontrado no período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ PAGINAÇÃO ═══ -->
                @if(method_exists($data, 'links'))
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        <h6 class="m-0 text-muted fs-13">
                            Exibindo <strong>{{ $data->count() }}</strong> registros de caixa
                        </h6>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL IMPRIMIR RELATÓRIO ═══ -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalPrintLabel">
                    <i class="ri-printer-line" style="color: #4f46e5;"></i>
                    <span>Imprimir Relatório de Fechamento</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted text-center mb-4 fs-13">Selecione o formato desejado para o comprovante de fechamento do caixa:</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="print-option-card" onclick="print('a4')">
                            <div class="print-icon" style="background: #ecfdf5; color: #059669;">
                                <i class="ri-file-text-line"></i>
                            </div>
                            <h6 class="fw-bold mb-1 fs-14 text-dark">Modelo A4</h6>
                            <span class="text-muted fs-12">Folha Comum (PDF)</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="print-option-card" onclick="print('80')">
                            <div class="print-icon" style="background: #eef2ff; color: #4f46e5;">
                                <i class="ri-printer-line"></i>
                            </div>
                            <h6 class="fw-bold mb-1 fs-14 text-dark">Bobina 80mm</h6>
                            <span class="text-muted fs-12">Impressora Térmica</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-3 px-4">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var ID = 0
    function imprimir(id){
        ID = id
        $('#modal-print').modal('show')
    }

    function print(tipo){
        if(tipo == 'a4'){
            window.open('/caixa/imprimir/'+ID)
        }else{
            window.open('/caixa/imprimir80/'+ID)
        }
        $('#modal-print').modal('hide')
    }
</script>
@endsection
