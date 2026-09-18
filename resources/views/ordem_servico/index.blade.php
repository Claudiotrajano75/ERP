@extends('layouts.app', ['title' => 'Ordem de Serviço'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
.stat-card:hover { transform: translateY(-3px); }
.stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
.stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
.stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
.stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
.stat-purple { background: linear-gradient(135deg,#a855f7,#7c3aed); box-shadow: 0 6px 18px rgba(168,85,247,.32); }
.stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
.stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
.stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
.stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

/* ─── Novo Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium { background: #ffffff; border: 1px solid #eef0f6 !important; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); padding: 20px !important; margin-bottom: 24px; }
.filtro-premium-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f3f9; padding-bottom: 12px; margin-bottom: 16px; }
.filtro-premium-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0; }
.filtro-premium-title i { color: #5572f5; margin-right: 6px; }
.modulo-glass-filter-premium label, .form-label, label:not(.form-check-label):not(.btn) { font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; padding-bottom: 0 !important; display: inline-flex !important; align-items: center !important; gap: 5px !important; }
.modulo-glass-filter-premium label i { font-size: 13px; color: #64748b; }
.modulo-glass-filter-premium .form-control, .modulo-glass-filter-premium .form-select { height: 38px !important; border-radius: 8px !important; border: 1px solid #dcdce9 !important; font-size: 13px !important; padding: 6px 12px !important; color: #374151 !important; background-color: #fcfdfe !important; transition: all 0.2s ease; }
.modulo-glass-filter-premium .form-control:focus, .modulo-glass-filter-premium .form-select:focus { border-color: #5572f5 !important; background-color: #fff !important; box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de Ações ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; }
.act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-view { background: #e0f2fe; color: #0284c7; }
.act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
.act-edit { background: #eef0ff; color: #4f46e5; }
.act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
.act-del { background: #fee2e2; color: #dc2626; }
.act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }

/* ─── Estado Vazio ─── */
.empty-state { padding: 52px 20px; text-align: center; }
.empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
.empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-survey-line"></i>
                            Ordens de Serviço (OS)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Acompanhe orçamentos, manutenções, controle prazos e emita notas fiscais de serviço.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('ordem-servico.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line"></i> Atualizar
                        </a>
                        @can('ordem_servico_create')
                        <a href="{{ route('ordem-servico.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Nova OS
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de OS</div>
                                    <div class="st-value">{{ $stats['total'] ?? $data->total() }}</div>
                                    <div class="st-sub">registros no sistema</div>
                                </div>
                                <div class="st-icon"><i class="ri-survey-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">OS Pendentes</div>
                                    <div class="st-value">{{ $stats['pendentes'] ?? 0 }}</div>
                                    <div class="st-sub">aguardando aprovação/execução</div>
                                </div>
                                <div class="st-icon"><i class="ri-time-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">OS Finalizadas</div>
                                    <div class="st-value">{{ $stats['finalizadas'] ?? 0 }}</div>
                                    <div class="st-sub">serviços concluídos</div>
                                </div>
                                <div class="st-icon"><i class="ri-check-double-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card stat-purple">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">OS Reprovadas</div>
                                    <div class="st-value">{{ $stats['reprovadas'] ?? 0 }}</div>
                                    <div class="st-sub">recusadas / canceladas</div>
                                </div>
                                <div class="st-icon"><i class="ri-close-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Ordens de Serviço
                        </h5>
                    </div>

                    <form method="get" action="{{ route('ordem-servico.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                                <select name="cliente_id" class="select2 form-select">
                                    @if(isset($cliente) && $cliente != null)
                                        <option value="{{ $cliente->id }}" selected>{{ $cliente->razao_social }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Início</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-hashtag"></i> Código OS</label>
                                <input type="tel" name="codigo" value="{{ request('codigo') }}" class="form-control" placeholder="Ex: 15">
                            </div>
                            <div class="col-md-3 col-12 d-flex gap-2">
                                <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="dash-btn dash-btn-light px-3" href="{{ route('ordem-servico.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    @can('ordem_servico_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th style="width: 80px;">Código</th>
                                    <th>Cliente</th>
                                    <th>Data Início</th>
                                    <th>Previsão Entrega</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 130px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('ordem_servico_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <span class="fw-bold text-muted">#{{ $item->codigo_sequencial }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold" style="color:#1f2937;">{{ $item->cliente->razao_social }}</div>
                                        @if($item->funcionario)
                                        <div class="fs-12" style="color:#94a3b8;"><i class="ri-user-settings-line me-1"></i>{{ $item->funcionario->nome }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12"><i class="ri-calendar-line me-1"></i>{{ __data_pt($item->data_inicio, 1) }}</span>
                                    </td>
                                    <td>
                                        @if(\Carbon\Carbon::parse($item->data_entrega)->isPast() && $item->estado == 'pd')
                                        <span class="text-danger fw-semibold fs-12">
                                            <i class="ri-error-warning-line me-1"></i>{{ __data_pt($item->data_entrega, 1) }}
                                        </span>
                                        @else
                                        <span class="text-muted fs-12"><i class="ri-time-line me-1"></i>{{ __data_pt($item->data_entrega, 1) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-14" style="color:#15803d;">R$ {{ __moeda($item->valor) }}</span>
                                    </td>
                                    <td>
                                        @if($item->estado == 'pd')
                                        <span class="pill pill-amber">
                                            <i class="ri-time-line"></i> Pendente
                                        </span>
                                        @elseif($item->estado == 'ap')
                                        <span class="pill pill-ok">
                                            <i class="ri-check-line"></i> Aprovada
                                        </span>
                                        @elseif($item->estado == 'rp')
                                        <span class="pill pill-no">
                                            <i class="ri-close-line"></i> Reprovada
                                        </span>
                                        @elseif($item->estado == 'fz')
                                        <span class="pill pill-info">
                                            <i class="ri-check-double-line"></i> Finalizada
                                        </span>
                                        @else
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->estado }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('ordem-servico.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                <a class="act-btn act-view" href="{{ route('ordem-servico.show', $item->id) }}" title="Painel / Detalhes">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @can('ordem_servico_edit')
                                                <a class="act-btn act-edit" href="{{ route('ordem-servico.edit', [$item->id]) }}" title="Editar OS">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('ordem_servico_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir OS">
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
                                        if (Auth::user()->can('ordem_servico_delete')) $colspan++;
                                    @endphp
                                    <td colspan="{{ $colspan }}">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma OS cadastrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote + Paginação) ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        @can('ordem_servico_delete')
                        <form action="{{ route('ordem-servico.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="dash-btn dash-btn-danger btn-delete-all" disabled>
                                <i class="ri-delete-bin-line"></i> Remover Selecionadas
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
<script src="/js/delete_selecionados.js"></script>
@endsection

