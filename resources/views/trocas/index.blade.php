@extends('layouts.app', ['title' => 'Trocas'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
.stat-card:hover { transform: translateY(-3px); }
.stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
.stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
.stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
.stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
.stat-amber  { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(220,38,38,.32); }
.stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
.stat-card .st-value { font-size: 24px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
.stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
.stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

/* ─── Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
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
    color: #5572f5;
    margin-right: 6px;
}

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

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de botões de ação ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; }
.act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-view { background: #e0f2fe; color: #0284c7; }
.act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
.act-print { background: #f1f5f9; color: #334155; }
.act-print:hover { background: #e2e8f0; color: #0f172a; box-shadow: 0 4px 12px rgba(51,65,85,.2); }
.act-del { background: #fee2e2; color: #dc2626; }
.act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

/* ─── Badges (pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }

/* ─── Empty State ─── */
.empty-state { padding: 52px 20px; text-align: center; }
.empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
.empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 16px 40px rgba(0,0,0,0.18); }
.modal-header { background: #f8fafc; border-bottom: 1px solid #eef0f6; padding: 18px 24px; }
.modal-header .modal-title { color: #1e293b; font-weight: 700; font-size: 16px; }
.modal-header .modal-title i { color: #4f46e5; }
.modal-body { padding: 24px; background: #ffffff; }
.modal-body label { font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; color: #64748b; margin-bottom: 6px; }
.modal-body .form-control { border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; padding: 10px 14px; background: #fcfdfe; transition: all 0.15s ease; }
.modal-body .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }
.modal-divider-ou { display: flex; align-items: center; justify-content: center; margin: 16px 0; color: #94a3b8; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
.modal-divider-ou::before,
.modal-divider-ou::after { content: ""; flex: 1; border-bottom: 1px solid #e2e8f0; margin: 0 12px; }
.modal-footer { background: #f8fafc; border-top: 1px solid #eef0f6; padding: 16px 24px; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-arrow-go-back-line"></i>
                            Trocas de Mercadorias
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as trocas de mercadorias realizadas nas vendas PDV.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('trocas.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('troca_create')
                        <button type="button" class="dash-btn dash-btn-primary" data-bs-toggle="modal" data-bs-target="#modal-nova-troca">
                            <i class="ri-add-circle-line"></i> Nova Troca
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de Trocas</div>
                                    <div class="st-value">{{ $stats['total'] ?? 0 }}</div>
                                    <div class="st-sub">registros efetuados</div>
                                </div>
                                <div class="st-icon"><i class="ri-arrow-go-back-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total Trocado</div>
                                    <div class="st-value">R$ {{ __moeda($stats['valor_trocas'] ?? 0) }}</div>
                                    <div class="st-sub">em mercadorias trocadas</div>
                                </div>
                                <div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Vendas Originais</div>
                                    <div class="st-value">R$ {{ __moeda($stats['valor_vendas'] ?? 0) }}</div>
                                    <div class="st-sub">valor original das vendas</div>
                                </div>
                                <div class="st-icon"><i class="ri-shopping-bag-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Trocas Hoje</div>
                                    <div class="st-value">{{ $stats['hoje'] ?? 0 }}</div>
                                    <div class="st-sub">realizadas no dia</div>
                                </div>
                                <div class="st-icon"><i class="ri-calendar-check-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Trocas
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-5 col-12">
                            <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                            {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select'])!!}
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
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('trocas.index') }}" title="Limpar Filtros">
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
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Cliente</th>
                                    <th>Código Troca</th>
                                    <th>Valor Troca</th>
                                    <th>Valor Venda</th>
                                    <th>Data Troca</th>
                                    <th>Venda #</th>
                                    <th class="text-end" style="width: 140px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold" style="color:#64748b;">#{{ $item->numero_sequencial }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle border bg-light me-2 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px; font-size: 14px;">
                                                <i class="ri-user-3-line"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="color:#1f2937;">{{ $item->nfce->cliente ? $item->nfce->cliente->razao_social : 'Consumidor Final' }}</div>
                                                <div class="fs-12" style="color:#94a3b8;">{{ $item->nfce->cliente ? $item->nfce->cliente->cpf_cnpj : '--' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-bold">{{ $item->codigo }}</span></td>
                                    <td><span class="fw-bold" style="color:#dc2626;">R$ {{ __moeda($item->valor_troca) }}</span></td>
                                    <td><span class="fw-bold" style="color:#16a34a;">R$ {{ __moeda($item->valor_original) }}</span></td>
                                    <td><span class="fs-12" style="color:#64748b;">{{ __data_pt($item->created_at) }}</span></td>
                                    <td><span class="fw-semibold" style="color:#4f46e5;">#{{ $item->nfce ? $item->nfce->numero_sequencial : '' }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('trocas.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                <a class="act-btn act-view" title="Detalhes" href="{{ route('trocas.show', $item->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <button type="button" class="act-btn act-print" title="Imprimir Cupom Térmico"
                                                    onclick="PrintThermal.imprimir('troca', {{$item->id}}, '{{ route('trocas.imprimir', $item->id) }}')">
                                                    <i class="ri-printer-line"></i>
                                                </button>
                                                @can('troca_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma troca encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> trocas
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL NOVA TROCA ═══ -->
<div class="modal fade" id="modal-nova-troca" tabindex="-1" aria-labelledby="modalNovaTrocaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="get" action="{{ route('trocas.create') }}" class="w-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="modalNovaTrocaLabel">
                        <i class="ri-arrow-go-back-line"></i> Iniciar Nova Troca
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label"><i class="ri-hashtag"></i> Código da Venda / Sequencial</label>
                            {!!Form::text('codigo', '')->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 12345'])->placeholder('Digite o código sequencial da venda')!!}
                        </div>
                        <div class="col-12 text-center p-0">
                            <div class="modal-divider-ou">OU</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="ri-file-text-line"></i> Número da NFCe</label>
                            {!!Form::text('numero_nfce', '')->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 987654'])->placeholder('Digite o número fiscal da NFCe')!!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="dash-btn dash-btn-primary px-4">
                        <i class="ri-search-line"></i> Localizar Venda
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
