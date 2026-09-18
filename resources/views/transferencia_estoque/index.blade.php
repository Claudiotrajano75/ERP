@extends('layouts.app', ['title' => 'Transferências de Estoque'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* --- Novo Filtro de Pesquisa Premium --- */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    padding: 20px !important;
    margin-bottom: 24px;
}

/* Título e Header do Filtro */
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
    color: #5572f5;
    margin-right: 6px;
}

/* Customização dos Inputs dentro do Filtro */
.modulo-glass-filter-premium label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c8ca6 !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 4px;
}
.modulo-glass-filter-premium label i {
    font-size: 12px;
    color: #a8a8c0;
}

.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 38px !important;
    border-radius: 8px !important;
    border: 1px solid #dcdce9 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #374151 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}

.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #5572f5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
}

/* Botões do Filtro */
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
}

.modulo-glass-filter-premium .btn-limpar {
    background: #f1f3f9 !important;
    border: 1px solid #e2e5ec !important;
    color: #5a5a7a !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e8ebf3 !important;
    color: #302b63 !important;
}
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-table-wrap tbody tr.clickable { cursor: pointer; }

/* ─── Transação Badge ─── */
.transacao-badge { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #eef2ff, #e0e7ff); color: #4338ca; font-weight: 700; font-family: 'Courier New', monospace; font-size: 12px; padding: 4px 12px; border-radius: 8px; border: 1px solid rgba(67,56,202,0.15); letter-spacing: 0.3px; }

/* ─── Local Badge ─── */
.local-badge { font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; }
.local-badge i { font-size: 15px; opacity: 0.7; }
.local-saida { color: #d32f2f; }
.local-entrada { color: #2e7d32; }

/* ─── Action Buttons — SEMPRE lado a lado (flex-wrap: nowrap é obrigatório) ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── KPI Cards Premium ─── */
.modulo-kpi-card { border: none !important; border-radius: 12px; overflow: hidden; transition: all 0.25s ease; position: relative; }
.modulo-kpi-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
.modulo-kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important; }
.modulo-kpi-card .kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.modulo-kpi-card .kpi-value { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; }
.modulo-kpi-card .kpi-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; }
.modulo-kpi-blue::before  { background: linear-gradient(90deg, #4facfe, #00f2fe); }
.modulo-kpi-green::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }
.modulo-kpi-orange::before { background: linear-gradient(90deg, #fa709a, #fee140); }
.modulo-kpi-purple::before { background: linear-gradient(90deg, #a18cd1, #fbc2eb); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer da Tabela ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }

/* ─── Operador Badge ─── */
.operador-badge { display: inline-flex; align-items: center; gap: 5px; background: #f0f2f5; color: #3a3a5a; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; border: 1px solid #e0e3eb; }
.operador-badge i { font-size: 13px; color: #7c7caa; }

/* ─── Observação Truncada ─── */
.obs-text { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; color: #7c7caa; font-size: 12px; }

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
    .modulo-kpi-card .kpi-value { font-size: 18px; }
}

    /* ═══ Premium overlay (padrão ERP) ═══ */
    .stat-card { border:0; border-radius:16px; padding:18px 20px; height:100%; color:#fff; position:relative; overflow:hidden; transition:transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content:''; position:absolute; top:-44px; right:-44px; width:130px; height:130px; border-radius:50%; background:rgba(255,255,255,.12); }
    .stat-indigo { background:linear-gradient(135deg,#6366f1,#4f46e5); box-shadow:0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background:linear-gradient(135deg,#24c98a,#109f61); box-shadow:0 6px 18px rgba(16,185,129,.32); }
    .stat-blue   { background:linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow:0 6px 18px rgba(37,99,235,.32); }
    .stat-red    { background:linear-gradient(135deg,#fb7185,#dc2626); box-shadow:0 6px 18px rgba(239,68,68,.32); }
    .stat-card .st-label { font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:rgba(255,255,255,.85); }
    .stat-card .st-value { font-size:26px; font-weight:800; color:#fff; margin-top:4px; line-height:1.1; }
    .stat-card .st-sub { font-size:11.5px; color:rgba(255,255,255,.75); margin-top:4px; }
    .stat-card .st-icon { width:46px; height:46px; border-radius:13px; background:rgba(255,255,255,.22); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; }

    .filter-wrap { background:#fff; border:1px solid #e9ecf3; border-radius:14px; box-shadow:0 1px 2px rgba(16,24,40,.04); padding:18px 20px; margin-bottom:18px; }
    .filter-title { font-size:13px; font-weight:700; color:#3f3e6a; text-transform:uppercase; letter-spacing:.5px; }
    .filter-title i { color:#4f46e5; margin-right:6px; }
    .filter-wrap label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#8c8ca6; margin-bottom:6px; }
    .filter-wrap label i { color:#a8a8c0; }
    .filter-wrap .form-control, .filter-wrap .form-select { height:40px; border-radius:10px; border:1px solid #dcdce9; font-size:13.5px; color:#1f2937; background:#fcfdfe; transition:all .15s ease; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); background:#fff; }

    .tb-wrap { border-radius:14px; border:1px solid #eef0f5; overflow:hidden; background:#fff; }
    .tb-wrap table { margin-bottom:0; }
    .tb-wrap thead th { background:#f8f9fc; color:#5a5a7a; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.4px; padding:13px 14px; border-bottom:1px solid #e8eaf6; white-space:nowrap; }
    .tb-wrap tbody td { padding:13px 14px; vertical-align:middle; border-bottom:1px solid #f0f2f8; font-size:13.5px; color:#374151; }
    .tb-wrap tbody tr:hover { background:#f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom:none; }

    .act-group { display:inline-flex; gap:6px; align-items:center; }
    .act-btn { width:34px; height:34px; border-radius:10px; border:0; display:inline-flex; align-items:center; justify-content:center; font-size:15px; text-decoration:none; cursor:pointer; transition:transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform:translateY(-2px); text-decoration:none; }
    .act-edit { background:#eef0ff; color:#4f46e5; }
    .act-edit:hover { box-shadow:0 4px 12px rgba(79,70,229,.3); }
    .act-view { background:#e0f2fe; color:#0284c7; }
    .act-view:hover { box-shadow:0 4px 12px rgba(2,132,199,.3); }
    .act-del { background:#fee2e2; color:#dc2626; }
    .act-del:hover { box-shadow:0 4px 12px rgba(220,38,38,.3); }

    .pill { display:inline-flex; align-items:center; gap:5px; border-radius:8px; padding:4px 10px; font-size:11.5px; font-weight:700; }
    .pill-ok { background:#dcfce7; color:#15803d; }
    .pill-no { background:#f1f5f9; color:#64748b; }
    .pill-info { background:#eef0ff; color:#4f46e5; }
    .pill-blue { background:#e0f2fe; color:#0284c7; }
    .pill-up { background:#dcfce7; color:#15803d; }
    .pill-down { background:#fee2e2; color:#b91c1c; }

    .empty-state { padding:52px 20px; text-align:center; }
    .empty-state i { font-size:52px; color:#c5cae9; display:block; margin-bottom:12px; }
    .empty-state p { color:#9e9eb8; font-size:14px; margin:0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-arrow-left-right-line"></i>
                            Transferências de Estoque
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Registre e acompanhe a movimentação física de produtos entre as diferentes localizações da sua empresa.
                        </p>
                    </div>
                    <div>
                        @can('transferencia_estoque_create')
                        <a href="{{ route('transferencia-estoque.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Transferência
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

{{-- Cards de Estatísticas --}}
                <div class="row g-3 mb-3">
<div class="col-6 col-xl-3">
        <div class="stat-card stat-indigo">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="st-label">Transferências</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">movimentações registradas</div></div>
                <div class="st-icon"><i class="ri-arrow-left-right-line"></i></div>
            </div>
        </div>
    </div><div class="col-6 col-xl-3">
        <div class="stat-card stat-green">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="st-label">Itens Movimentados</div><div class="st-value">{{ $stats['itens'] }}</div><div class="st-sub">quantidade de itens</div></div>
                <div class="st-icon"><i class="ri-stack-line"></i></div>
            </div>
        </div>
    </div>
                </div>
                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="filter-wrap">
                    <div class="filtro-premium-header">
                        <h5 class="filter-title mb-0">
                            <i class="ri-search-line"></i> Filtrar Transferências
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label class="form-label"><i class="ri-box-3-line"></i> Pesquisar por Produto</label>
                            {!!Form::text('produto', '')->attrs(['class' => 'form-control', 'placeholder' => 'Nome do produto...'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-primary flex-grow-1" style="border-radius:10px;" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('transferencia-estoque.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Origem</th>
                                    <th>Destino</th>
                                    <th>Data</th>
                                    <th>Operador</th>
                                    <th>Observação</th>
                                    <th class="text-end" style="width: 120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="transacao-badge">
                                            <i class="ri-hashtag"></i>
                                            {{ $item->codigo_transacao }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="local-badge local-saida">
                                            <i class="ri-logout-circle-r-line"></i>
                                            {{ $item->local_saida->descricao }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="local-badge local-entrada">
                                            <i class="ri-login-circle-r-line"></i>
                                            {{ $item->local_entrada->descricao }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ __data_pt($item->created_at) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="operador-badge">
                                            <i class="ri-user-smile-line"></i>
                                            {{ $item->usuario->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->observacao)
                                        <span class="obs-text" title="{{ $item->observacao }}">
                                            <i class="ri-chat-1-line me-1 text-muted"></i>
                                            {{ $item->observacao }}
                                        </span>
                                        @else
                                        <span class="text-muted fs-12">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('transferencia-estoque.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                <a class="act-btn act-view" target="_blank" href="{{ route('transferencia-estoque.imprimir', [$item->id]) }}" title="Imprimir Comprovante de Transferência">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @can('transferencia_estoque_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Registro de Transferência">
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
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma transferência de estoque encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER ═══ -->
                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de transferências:</span>
                        <span class="modulo-total-value">{{ $data->total() }}</span>
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
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
