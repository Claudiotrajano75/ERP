@extends('layouts.app', ['title' => 'Serviços'])

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

/* ─── Action Buttons — SEMPRE lado a lado ─── */
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

/* ─── Responsivo ─── */
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

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-briefcase-line"></i>
                            Serviços Cadastrados
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie a tabela de serviços prestados, valores de cobrança e comissões.
                        </p>
                    </div>
                    <div>
                        @can('servico_create')
                        <a href="{{ route('servicos.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Serviço
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI CARDS ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-blue shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);color:#1565c0;">
                                    <i class="ri-briefcase-4-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-dark">{{ $data->total() }}</div>
                                    <div class="kpi-label text-muted">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-green shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);color:#2e7d32;">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-dark">{{ $totalAtivos }}</div>
                                    <div class="kpi-label text-muted">Ativos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-orange shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#fff3e0,#ffe0b2);color:#e65100;">
                                    <i class="ri-close-circle-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-dark">{{ $totalInativos }}</div>
                                    <div class="kpi-label text-muted">Inativos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card modulo-kpi-card modulo-kpi-purple shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kpi-icon" style="background:linear-gradient(135deg,#f3e5f5,#e1bee7);color:#6a1b9a;">
                                    <i class="ri-store-line"></i>
                                </div>
                                <div>
                                    <div class="kpi-value text-dark">{{ $totalMarketplace }}</div>
                                    <div class="kpi-label text-muted">Marketplace</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Serviços
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label"><i class="ri-briefcase-line"></i> Pesquisar por Nome</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do serviço...'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-checkbox-circle-line"></i> Status</label>
                            {!!Form::select('status', '', ['' => 'Todos', '1' => 'Ativos', '0' => 'Inativos'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('servicos.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('servico_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th style="width: 60px;">Img</th>
                                    <th style="width: 80px;">#</th>
                                    <th>Serviço</th>
                                    <th>Valor</th>
                                    <th>Duração</th>
                                    <th>Status</th>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))<th>Reserva</th>@endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))<th>Marketplace</th>@endif
                                    <th class="text-end" style="width: 120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('servico_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <img class="rounded-circle border bg-light" src="{{ $item->img }}"
                                             style="width:40px;height:40px;object-fit:cover;">
                                    </td>
                                    <td>
                                        <span class="fw-bold" style="color:#302b63;">#{{ $item->numero_sequencial }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->nome }}</span>
                                        @if($item->categoria)
                                        <span class="text-muted fs-11">{{ $item->categoria->nome }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-11">
                                            {{ $item->tempo_servico }} {{ $item->unidade_cobranca }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-line me-1"></i>Ativo
                                        </span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-close-line me-1"></i>Inativo
                                        </span>
                                        @endif
                                    </td>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                                    <td>
                                        @if($item->reserva)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-line me-1"></i>Sim
                                        </span>
                                        @else
                                        <span class="text-muted fs-11">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <td>
                                        @if($item->marketplace)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                            <i class="ri-store-line me-1"></i>Marketplace
                                        </span>
                                        @else
                                        <span class="text-muted fs-11">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td class="text-end">
                                        <form action="{{ route('servicos.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('servico_edit')
                                                <a class="btn btn-warning btn-sm text-white"
                                                   href="{{ route('servicos.edit', [$item->id]) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('servico_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    @php
                                        $colspan = 7;
                                        if (Auth::user()->can('servico_delete')) $colspan++;
                                        if (__isActivePlan(Auth::user()->empresa, 'Reservas')) $colspan++;
                                        if (__isActivePlan(Auth::user()->empresa, 'Delivery')) $colspan++;
                                    @endphp
                                    <td colspan="{{ $colspan }}">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum serviço cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote + Paginação) ═══ -->
                <div class="modulo-footer">
                    <div>
                        @can('servico_delete')
                        <form action="{{ route('servicos.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionados
                            </button>
                        </form>
                        @endcan
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
