@extends('layouts.app', ['title' => 'Contas a Receber'])

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
            background: rgba(255, 255, 255, 0.12);
            padding: 8px;
            border-radius: 10px;
            color: #a8b5ff;
        }

        .modulo-header-gradient .modulo-subtitle {
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 400;
        }

        .modulo-header-gradient .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .modulo-header-gradient .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
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

        .modulo-kpi-blue::before {
            background: linear-gradient(90deg, #4facfe, #00f2fe);
        }

        .modulo-kpi-green::before {
            background: linear-gradient(90deg, #43e97b, #38f9d7);
        }

        .modulo-kpi-orange::before {
            background: linear-gradient(90deg, #fa709a, #fee140);
        }

        .modulo-glass-filter {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
        }

        .modulo-glass-filter label {
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #5a5a7a;
            margin-bottom: 2px;
        }

        .modulo-glass-filter .form-control,
        .modulo-glass-filter .form-select {
            height: 38px;
        }

        .modulo-glass-filter .btn {
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            height: 38px;
            padding-top: 0;
            padding-bottom: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .modulo-table-wrap table {
            margin-bottom: 0;
        }

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

        .modulo-table-wrap tbody tr {
            transition: all 0.15s ease;
        }

        .modulo-table-wrap tbody tr:hover {
            background: #f5f6fe;
        }

        .modulo-table-wrap tbody tr:last-child td {
            border-bottom: none;
        }

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

        .modulo-badge-success {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
        }

        .modulo-badge-warning {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #e65100;
        }

        .modulo-badge-info {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1565c0;
        }

        .modulo-action-group {
            display: inline-flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .modulo-action-group .btn {
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 13px;
            transition: all 0.15s ease;
        }

        .modulo-action-group .btn:hover {
            transform: translateY(-1px);
        }

        .modulo-action-group .btn-light {
            background: #f0f2f8;
            border-color: #e8eaf6;
            color: #5a5a7a;
        }

        .modulo-action-group .btn-light:hover {
            background: #e8eaf6;
            color: #302b63;
        }

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
            .modulo-header-gradient .modulo-title {
                font-size: 18px;
            }

            .modulo-kpi-card .kpi-value {
                font-size: 18px;
            }
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
                                <i class="ri-hand-coin-line"></i>
                                Contas a Receber
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie as contas a receber, controle os prazos de recebimento e gerencie boletos.
                            </p>
                        </div>
                        <div>
                            @can('conta_receber_create')
                                <a href="{{ route('conta-receber.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                                    <i class="ri-add-circle-line align-middle me-1"></i> Nova Conta
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    @php
                        $totalIntegralPagina = $data->sum('valor_integral');
                        $totalRecebidoPagina = $data->sum('valor_recebido');
                        $totalPendentePagina = $totalIntegralPagina - $totalRecebidoPagina;
                    @endphp

                    <!-- ═══ KPI Cards Premium ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-6">
                            <div class="card widget-icon-box text-bg-info mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total Integral (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">R$ {{ __moeda($totalIntegralPagina) }}</h3>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="card widget-icon-box text-bg-success mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Recebido (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">R$ {{ __moeda($totalRecebidoPagina) }}</h3>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="card widget-icon-box text-bg-danger mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Pendente (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">R$ {{ __moeda($totalPendentePagina) }}</h3>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-alert-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ Filtros Glass ═══ -->
                    <div class="modulo-glass-filter p-3 mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4 col-12">
                                {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-select form-select-sm'])
        ->options($cliente != null ? [$cliente->id => $cliente->info] : [])!!}
                            </div>
                            <div class="col-md-4 col-6">
                                {!!Form::date('start_date', 'Data Inicial')->attrs(['class' => 'form-control form-control-sm'])!!}
                            </div>
                            <div class="col-md-4 col-6">
                                {!!Form::date('end_date', 'Data Final')->attrs(['class' => 'form-control form-control-sm'])!!}
                            </div>
                            <div class="col-md-4 col-6">
                                {!!Form::select('status', 'Status', ['' => 'Todas', 1 => 'Recebidas', 0 => 'Pendentes'])
        ->attrs(['class' => 'form-select form-select-sm'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                {!!Form::select('ordem', 'Ordenar', ['' => 'Cadastro', 1 => 'Vencimento'])
        ->attrs(['class' => 'form-select form-select-sm'])!!}
                            </div>
                            @if(__countLocalAtivo() > 1)
                                                <div class="col-md-2 col-12">
                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                                                </div>
                            @endif
                            <div class="col-md-3 col-12 ms-auto mt-2">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                        <i class="ri-search-line me-1"></i> Filtrar
                                    </button>
                                    <a class="btn btn-outline-secondary btn-sm px-3"
                                        href="{{ route('conta-receber.index') }}">
                                        <i class="ri-eraser-line me-1"></i> Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ Tabela Premium ═══ -->
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        @can('conta_receber_delete')
                                            <th style="width:40px;">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                                </div>
                                            </th>
                                        @endcan
                                        <th>Cliente</th>
                                        @if(__countLocalAtivo() > 1)
                                        <th>Local</th>@endif
                                        <th>Valor Integral</th>
                                        <th>Valor Recebido</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                        <th>Venda</th>
                                        <th class="text-end" style="width:180px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            @can('conta_receber_delete')
                                                <td>
                                                    @if(!$item->status)
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input check-delete" type="checkbox"
                                                                name="item_delete[]" value="{{ $item->id }}">
                                                        </div>
                                                    @endif
                                                </td>
                                            @endcan
                                            <td>
                                                <span
                                                    class="fw-semibold text-dark">{{ $item->cliente ? $item->cliente->razao_social : '--' }}</span>
                                                @if($item->cliente)
                                                    <span class="text-muted d-block fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                                @endif
                                            </td>
                                            @if(__countLocalAtivo() > 1)
                                                <td><span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle fs-11">{{ $item->localizacao->descricao }}</span>
                                                </td>
                                            @endif
                                            <td class="fw-bold text-dark">R$ {{ __moeda($item->valor_integral) }}</td>
                                            <td class="fw-semibold text-success">R$ {{ __moeda($item->valor_recebido) }}</td>
                                            <td>
                                                <span class="fw-medium">{{ __data_pt($item->data_vencimento, 0) }}</span>
                                                @if(!$item->status)
                                                    <span
                                                        class="badge bg-danger-subtle text-danger fs-10 ms-1">{{ $item->diasAtraso() }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status)
                                                    <span class="modulo-badge modulo-badge-success">
                                                        <i class="ri-check-line"></i> Recebido
                                                    </span>
                                                @else
                                                    <span class="modulo-badge modulo-badge-warning">
                                                        <i class="ri-time-line"></i> Pendente
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->nfce)
                                                    <a href="{{ route('nfce.show', [$item->nfce->id]) }}"
                                                        class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-1.5">PDV
                                                        #{{ $item->nfce->numero_sequencial }}</a>
                                                @elseif($item->nfe)
                                                    <a href="{{ route('nfe.show', [$item->nfe->id]) }}"
                                                        class="badge bg-dark-subtle text-dark border border-dark-subtle py-1 px-1.5">Pedido
                                                        #{{ $item->nfe->numero_sequencial }}</a>
                                                @else
                                                    <span class="text-muted">--</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('conta-receber.destroy', $item->id) }}" method="post"
                                                    id="form-{{$item->id}}" class="m-0">
                                                    @csrf @method('delete')
                                                    <div class="modulo-action-group">
                                                        @if(!$item->status)
                                                            @can('conta_receber_edit')
                                                                <a class="btn btn-warning btn-sm text-white"
                                                                    href="{{ route('conta-receber.edit', [$item->id]) }}"
                                                                    title="Editar">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            @endcan
                                                            @can('conta_receber_delete')
                                                                <button type="button" class="btn btn-danger btn-delete btn-sm"
                                                                    title="Excluir">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endcan
                                                            @can('conta_receber_edit')
                                                                <a href="{{ route('conta-receber.pay', $item) }}"
                                                                    class="btn btn-success btn-sm text-white" title="Receber">
                                                                    <i class="ri-money-dollar-box-line"></i>
                                                                </a>
                                                            @endcan
                                                        @endif
                                                        @if(!$item->boleto && !$item->status)
                                                            @can('boleto_create')
                                                                <a class="btn btn-secondary btn-sm text-white"
                                                                    href="{{ route('boleto.create', [$item->id]) }}"
                                                                    title="Gerar Boleto">
                                                                    <i class="ri-file-list-2-line"></i>
                                                                </a>
                                                            @endcan
                                                        @else
                                                            @can('boleto_view')
                                                                @if($item->boleto)
                                                                    <a class="btn btn-info btn-sm text-white"
                                                                        href="{{ route('boleto.show', [$item->id]) }}" title="Ver Boleto">
                                                                        <i class="ri-file-list-3-fill"></i>
                                                                    </a>
                                                                @endif
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                colspan="{{ (Auth::user()->can('conta_receber_delete') ? 1 : 0) + (__countLocalAtivo() > 1 ? 8 : 7) }}">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Nenhuma conta encontrada para os filtros aplicados.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ Ações em Lote + Footer ═══ -->
                    <div class="modulo-footer">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @can('conta_receber_delete')
                                <form action="{{ route('conta-receber.destroy-select') }}" method="post" id="form-delete-select"
                                    class="m-0">
                                    @method('delete') @csrf
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                        <i class="ri-delete-bin-line me-1 align-middle"></i> Remover selecionados
                                    </button>
                                </form>
                            @endcan
                            @can('conta_receber_edit')
                                <form action="{{ route('conta-receber.recebe-select') }}" method="post"
                                    id="form-recebe-paga-select" class="m-0">
                                    @csrf
                                    <button type="button" class="btn btn-outline-success btn-sm btn-recebe-paga-all" disabled>
                                        <i class="ri-check-line me-1 align-middle"></i> Receber selecionados
                                    </button>
                                </form>
                            @endcan
                            @can('boleto_create')
                                <form action="{{ route('boleto.create-several') }}" method="get" id="form-gerar-boletos"
                                    class="m-0">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm btn-boleto" disabled>
                                        <i class="ri-file-line me-1 align-middle"></i> Gerar boletos
                                    </button>
                                </form>
                            @endcan
                        </div>
                        <div>{!! $data->appends(request()->all())->links() !!}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/js/delete_selecionados.js"></script>
    <script src="/js/boleto.js"></script>
    <script src="/js/recebe_paga_selecionados.js"></script>
@endsection