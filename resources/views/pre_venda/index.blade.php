@extends('layouts.app', ['title' => 'Lista de Pré-vendas'])

@section('css')
    <style>
        /* ─── Header Gradient ─── */
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

        /* ─── Premium Table ─── */
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

        .modulo-table-wrap tbody tr.clickable {
            cursor: pointer;
        }

        /* ─── Status Badges ─── */
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

        /* ─── Action Buttons ─── */
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

        /* ─── Footer ─── */
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
            color: #2e7d32;
            letter-spacing: -0.3px;
        }

        /* ─── Empty State ─── */
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
        }
    </style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ Cabeçalho Premium ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-2-line"></i>
                                Pré-vendas
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualize, filtre e gerencie pré-vendas antes de finalizá-las como NFe ou NFCe.
                            </p>
                        </div>
                        <div>
                            @can('pre_venda_create')
                                <a href="{{ route('pre-venda.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                                    <i class="ri-add-circle-line align-middle me-1"></i> Nova Pré-venda
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    @php
                        $totalPagina = $data->total();
                        $totalRecebidasPagina = $data->where('status', 0)->count();
                        $totalPendentesPagina = $data->where('status', 1)->count();
                        $totalValorPagina = $data->sum('valor_total');
                    @endphp

                    <!-- ═══ KPI Cards Premium ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-info mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $totalPagina }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Pré-vendas na página</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-file-list-3-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-success mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Recebidas (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $totalRecebidasPagina }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Já finalizadas</p>
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
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-warning mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Pendentes (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $totalPendentesPagina }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Aguardando fechamento</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-time-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-dark mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Faturamento (pág.)</h4>
                                            <h3 class="my-2 text-white fs-18">R$ {{ __moeda($totalValorPagina) }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Valor em pré-vendas</p>
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
                    </div>

                    <!-- ═══ Filtros de Busca Premium ═══ -->
                    <div class="modulo-glass-filter-premium">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Pré-vendas
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                                {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-hashtag"></i> Código</label>
                                {!!Form::text('codigo', '')->attrs(['class' => 'form-control', 'placeholder' => 'Nº do código'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-checkbox-circle-line"></i> Status</label>
                                {!!Form::select('status', '', [
                                    '' => 'Todas as Pré-vendas',
                                    '1' => 'Pendentes',
                                    '0' => 'Recebidas'
                                ])->attrs(['class' => 'form-select'])!!}
                            </div>
                            @if(__countLocalAtivo() > 1)
                                <div class="col-md-3 col-12">
                                    <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                                    {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                    ->attrs(['class' => 'select2 form-select'])!!}
                                </div>
                            @endif
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-limpar px-3" href="{{ route('pre-venda.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
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
                                        <th style="width:80px;">Código</th>
                                        <th>Cliente</th>
                                        @if(__countLocalAtivo() > 1)
                                            <th style="width:100px;">Local</th>
                                        @endif
                                        <th style="width:120px;">Data</th>
                                        <th style="width:120px; text-align: center;">Valor</th>
                                        <th style="width:110px; text-align: center;">Status</th>
                                        <th class="text-end" style="width:240px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr @can('nfce_create') class="clickable" ondblclick="finalizar('{{$item->id}}')" @endcan>
                                            <td>
                                                <span class="fw-bold" style="color:#302b63;">{{ $item->codigo }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-dark">
                                                    {{ $item->cliente_id ? $item->cliente->razao_social : 'Consumidor Final' }}
                                                </span>
                                                @if($item->cliente)
                                                    <span class="text-muted d-block fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                                @endif
                                            </td>
                                            @if(__countLocalAtivo() > 1)
                                                <td>
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle fs-11">
                                                        {{ $item->localizacao->descricao ?? '--' }}
                                                    </span>
                                                </td>
                                            @endif
                                            <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>
                                            <td style="color:#2e7d32; text-align: center;">R$ {{ __moeda($item->valor_total) }}</td>
                                            <td style="text-align: center;">
                                                @if($item->status == 0)
                                                    <span class="modulo-badge modulo-badge-success">
                                                        <i class="ri-check-line"></i> Recebida
                                                    </span>
                                                @else
                                                    <span class="modulo-badge modulo-badge-warning">
                                                        <i class="ri-time-line"></i> Pendente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('pre-venda.destroy', $item->id) }}" method="post"
                                                    id="form-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modulo-action-group">
                                                        @can('pre_venda_view')
                                                            <a class="btn btn-light btn-sm" title="Histórico de alterações"
                                                                href="{{ route('pre-venda.auditoria', $item->id) }}">
                                                                <i class="ri-history-line"></i>
                                                            </a>
                                                        @endcan
                                                        @if($item->status == 1)
                                                            @can('pre_venda_edit')
                                                                <a class="btn btn-warning btn-sm text-white" title="Editar pré-venda"
                                                                    href="{{ route('pre-venda.edit', $item->id) }}">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            @endcan
                                                            @can('pre_venda_delete')
                                                                <button type="button" class="btn btn-danger btn-delete btn-sm"
                                                                    title="Excluir">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endcan
                                                        @endif

                                                        @if($item->status == 0 && $item->venda_id != null && $item->tipo_finalizado == 'nfe')
                                                            <a class="btn btn-light btn-sm" title="Ver NFe"
                                                                href="{{ route('nfe.show', $item->venda_id) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                            <a class="btn btn-primary text-white btn-sm" title="Imprimir pedido"
                                                                target="_blank" href="{{ route('nfe.imprimir', [$item->venda_id]) }}">
                                                                <i class="ri-printer-line"></i>
                                                            </a>
                                                        @endif

                                                        @if($item->status == 0 && $item->venda_id != null && $item->tipo_finalizado == 'nfce')
                                                            <a class="btn btn-light btn-sm" title="Ver NFCe"
                                                                href="{{ route('nfce.show', $item->venda_id) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                            <a class="btn btn-success text-white btn-sm" title="Imprimir Pedido"
                                                                target="_blank"
                                                                href="{{ route('frontbox.imprimir-nao-fiscal', [$item->venda_id]) }}">
                                                                <i class="ri-printer-line"></i>
                                                            </a>
                                                            @if($item->nfce && $item->nfce->estado == 'aprovado')
                                                                <a class="btn btn-primary text-white btn-sm" title="Imprimir NFCe"
                                                                    target="_blank"
                                                                    href="{{ route('nfce.imprimir', [$item->venda_id]) }}">
                                                                    <i class="ri-printer-fill"></i>
                                                                </a>
                                                            @endif
                                                        @endif

                                                        @if($item->status == 1)
                                                            @can('nfce_create')
                                                                <button type="button" class="btn btn-dark text-white btn-sm"
                                                                    title="Finalizar" onclick="finalizar('{{$item->id}}')">
                                                                    <i class="ri-coins-fill"></i>
                                                                </button>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ __countLocalAtivo() > 1 ? 7 : 6 }}">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Nenhuma pré-venda encontrada.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ Footer ═══ -->
                    <div class="modulo-footer">
                        <div>
                            <span class="modulo-total-label">Total em pré-vendas:</span>
                            <span class="modulo-total-value">R$ {{ __moeda($data->sum('valor_total')) }}</span>
                        </div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('modals._finalizar_pre_venda', ['not_submit' => true])
@endsection

@section('js')
    <script src="/js/pre_venda.js?v={{ filemtime(public_path('js/pre_venda.js')) }}"></script>
@endsection
