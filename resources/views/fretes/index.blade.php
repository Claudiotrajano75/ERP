@extends('layouts.app', ['title' => 'Fretes'])

@section('css')
    <style>
        /* ─── Cards de Estatísticas ─── */
        .stat-card {
            border-radius: 14px;
            padding: 18px 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .07);
            transition: transform .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 42px;
            opacity: .22;
        }

        .stat-card.c-blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-card.c-teal {
            background: linear-gradient(135deg, #06b6d4, #0e7490);
        }

        .stat-card.c-amber {
            background: linear-gradient(135deg, #f59e0b, #b45309);
        }

        .stat-card.c-green {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        .stat-card.c-purple {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        .stat-card .stat-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            opacity: .85;
            margin-bottom: 4px;
        }

        .stat-card .stat-val {
            font-size: 22px;
            font-weight: 800;
            line-height: 1;
        }

        /* ─── Filtro Padronizado ─── */
        .modulo-glass-filter-premium {
            background: #ffffff;
            border: 1px solid #e8ecf4;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 22px;
        }

        .modulo-glass-filter-premium label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .modulo-glass-filter-premium .form-control,
        .modulo-glass-filter-premium .form-select {
            height: 40px;
            border-radius: 10px;
            border: 1px solid #dcdce9;
            font-size: 13.5px;
            color: #1f2937;
            background: #fcfdfe;
        }

        .modulo-glass-filter-premium .form-control:focus,
        .modulo-glass-filter-premium .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
            background: #fff;
        }

        /* ─── Tabela ─── */
        .tb-wrap {
            border-radius: 14px;
            border: 1px solid #eef0f5;
            overflow: hidden;
            background: #fff;
        }

        .tb-wrap table {
            margin-bottom: 0;
        }

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

        .tb-wrap tbody tr:hover {
            background: #f5f6fe;
        }

        .tb-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        .tb-wrap tfoot td {
            background: #f8f9fc;
            font-weight: 700;
            font-size: 13.5px;
            padding: 13px 16px;
            border-top: 2px solid #e8eaf6;
        }

        /* ─── Grade de Ações ─── */
        .act-group {
            display: inline-flex;
            gap: 6px;
            align-items: center;
        }

        .act-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .act-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
        }

        .act-view {
            background: #e0f2fe;
            color: #0284c7;
        }

        .act-view:hover {
            box-shadow: 0 4px 12px rgba(2, 132, 199, .3);
        }

        .act-edit {
            background: #eef0ff;
            color: #4f46e5;
        }

        .act-edit:hover {
            box-shadow: 0 4px 12px rgba(79, 70, 229, .3);
        }

        .act-del {
            background: #fee2e2;
            color: #dc2626;
        }

        .act-del:hover {
            box-shadow: 0 4px 12px rgba(220, 38, 38, .3);
        }

        /* ─── Badges (Pills) ─── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .pill-ok {
            background: #dcfce7;
            color: #15803d;
        }

        .pill-no {
            background: #fee2e2;
            color: #b91c1c;
        }

        .pill-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .pill-amber {
            background: #fef3c7;
            color: #b45309;
        }
    </style>
@endsection

@section('content')
    <div class="mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">

                    <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                    <div class="card-header modulo-header-gradient py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                    <i class="ri-road-map-line"></i>
                                    Gestão de Fretes e Viagens
                                </h4>
                                <p class="text-muted mb-0 modulo-subtitle fs-13">
                                    Gerencie fretes, controle de cargas, veículos, despesas e lucros operacionais da sua frota.
                                </p>
                            </div>
                            <div class="d-inline-flex align-items-center gap-2">
                                @can('frete_create')
                                    <a href="{{ route('fretes.create') }}" class="dash-btn dash-btn-primary">
                                        <i class="ri-add-circle-line"></i> Novo Frete
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                        @if(isset($stats))
                            <div class="row g-3 mb-4">
                                <div class="col-md-3 col-6">
                                    <div class="stat-card c-blue">
                                        <i class="ri-money-dollar-circle-line stat-icon"></i>
                                        <div class="stat-title">Valor Total Fretes</div>
                                        <div class="stat-val">R$ {{ __moeda($stats['total_valor']) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-card c-amber">
                                        <i class="ri-coins-line stat-icon"></i>
                                        <div class="stat-title">Total Despesas</div>
                                        <div class="stat-val">R$ {{ __moeda($stats['total_despesas']) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-card c-green">
                                        <i class="ri-line-chart-line stat-icon"></i>
                                        <div class="stat-title">Lucro Operacional</div>
                                        <div class="stat-val">R$ {{ __moeda($stats['lucro']) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-card c-purple">
                                        <i class="ri-truck-line stat-icon"></i>
                                        <div class="stat-title">Total de Fretes</div>
                                        <div class="stat-val">{{ $stats['total_registros'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- ═══ FILTROS PADRONIZADOS ═══ -->
                        <div class="modulo-glass-filter-premium">
                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-12">
                                    <label for="cliente_id"><i class="ri-user-line me-1"></i> Cliente</label>
                                    {!!Form::select('cliente_id', '', ['' => 'Todos os Clientes'])
                                        ->attrs(['class' => 'select2 form-select'])
                                        ->options($cliente != null ? [$cliente->id => $cliente->info] : [])
                                    !!}
                                </div>
                                <div class="col-md-4 col-6">
                                    <label for="veiculo_id"><i class="ri-truck-line me-1"></i> Veículo</label>
                                    {!!Form::select('veiculo_id', '', ['' => 'Todos os Veículos'])
                                        ->attrs(['class' => 'select2 form-select'])
                                        ->options($veiculo != null ? [$veiculo->id => $veiculo->info] : [])
                                    !!}
                                </div>
                                <div class="col-md-4 col-6">
                                    <label for="estado"><i class="ri-flag-line me-1"></i> Status da Viagem</label>
                                    {!!Form::select('estado', '', [
                                        '' => 'Todos os Status',
                                        'em_carregamento' => 'Em carregamento',
                                        'em_viagem' => 'Em viagem',
                                        'finalizado' => 'Finalizado',
                                    ])->attrs(['class' => 'form-select'])!!}
                                </div>
                                <div class="col-md-3 col-6">
                                    <label for="start_date"><i class="ri-calendar-line me-1"></i> Data Início</label>
                                    {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                                </div>
                                <div class="col-md-3 col-6">
                                    <label for="end_date"><i class="ri-calendar-line me-1"></i> Data Fim</label>
                                    {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                                </div>
                                @if(__countLocalAtivo() > 1)
                                <div class="col-md-3 col-6">
                                    <label for="local_id"><i class="ri-map-pin-line me-1"></i> Local</label>
                                    {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                        ->attrs(['class' => 'select2 form-select'])
                                    !!}
                                </div>
                                @endif
                                <div class="col-md-auto ms-auto col-12 d-flex gap-2">
                                    <button class="dash-btn dash-btn-primary" type="submit" title="Buscar Fretes">
                                        <i class="ri-search-line"></i> Filtrar Fretes
                                    </button>
                                    <a class="dash-btn dash-btn-light" href="{{ route('fretes.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i> Limpar
                                    </a>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>

                        <!-- ═══ TABELA PREMIUM ═══ -->
                        <div class="tb-wrap mb-3">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px;"># Seq</th>
                                            <th>Cliente</th>
                                            <th>Veículo</th>
                                            <th>Valor Frete</th>
                                            <th>Despesas</th>
                                            @if(__countLocalAtivo() > 1)
                                                <th>Local</th>
                                            @endif
                                            <th>Início Viagem</th>
                                            <th>Fim Viagem</th>
                                            <th>Status</th>
                                            <th class="text-end" style="width: 140px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light text-dark fw-bold border fs-12 px-2 py-1">
                                                        #{{ $item->numero_sequencial }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-semibold text-dark fs-14 d-block">{{ $item->cliente->info }}</span>
                                                    @if($item->cliente->cpf_cnpj)
                                                        <small class="text-muted fs-11">{{ $item->cliente->cpf_cnpj }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark fw-medium border fs-12">
                                                        <i
                                                            class="ri-truck-line me-1 text-primary"></i>{{ $item->veiculo->info }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-primary fs-14">R$ {{ __moeda($item->total) }}</strong>
                                                </td>
                                                <td>
                                                    <span class="text-danger fw-semibold fs-13">R$
                                                        {{ __moeda($item->total_despesa) }}</span>
                                                </td>
                                                @if(__countLocalAtivo() > 1)
                                                    <td>
                                                        <span
                                                            class="badge bg-light text-secondary border fs-12">{{ $item->local->descricao ?? '-' }}</span>
                                                    </td>
                                                @endif
                                                <td class="fs-12 text-muted">
                                                    <i class="ri-calendar-line me-1"></i>{{ __data_pt($item->data_inicio, 0) }}
                                                </td>
                                                <td class="fs-12 text-muted">
                                                    <i
                                                        class="ri-calendar-check-line me-1"></i>{{ __data_pt($item->data_fim, 0) }}
                                                </td>
                                                <td>
                                                    @if($item->estado == 'em_carregamento')
                                                        <span class="pill pill-amber">
                                                            <i class="ri-loader-4-line"></i> Em carregamento
                                                        </span>
                                                    @elseif($item->estado == 'em_viagem')
                                                        <span class="pill pill-info">
                                                            <i class="ri-truck-line"></i> Em viagem
                                                        </span>
                                                    @else
                                                        <span class="pill pill-ok">
                                                            <i class="ri-checkbox-circle-fill"></i> Finalizado
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('fretes.destroy', $item->id) }}" method="post"
                                                        id="form-{{$item->id}}" class="d-inline m-0">
                                                        @method('delete')
                                                        @csrf
                                                        <div class="act-group">
                                                            @can('frete_view')
                                                                <a class="act-btn act-view"
                                                                    href="{{ route('fretes.show', $item->id) }}"
                                                                    title="Visualizar Frete">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            @endcan

                                                            @can('frete_edit')
                                                                <a class="act-btn act-edit"
                                                                    href="{{ route('fretes.edit', $item->id) }}"
                                                                    title="Editar Frete">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            @endcan

                                                            @can('frete_delete')
                                                                <button type="button" class="act-btn act-del btn-delete"
                                                                    title="Excluir Frete">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ __countLocalAtivo() > 1 ? 10 : 9 }}"
                                                    class="text-center text-muted py-4">
                                                    <i class="ri-inbox-2-line fs-24 d-block mb-1"></i>
                                                    Nenhum frete encontrado com os filtros aplicados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($data->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-uppercase fs-12 fw-bold text-muted">Totais da Página
                                                </td>
                                                <td class="text-primary fw-bold fs-14">R$ {{ __moeda($data->sum('total')) }}
                                                </td>
                                                <td class="text-danger fw-bold fs-14">R$
                                                    {{ __moeda($data->sum('total_despesa')) }}
                                                </td>
                                                <td colspan="{{ __countLocalAtivo() > 1 ? 5 : 4 }}"></td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- ═══ FOOTER (Paginação) ═══ -->
                        <div class="d-flex justify-content-end">
                            {!! $data->appends(request()->all())->links() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection