@extends('layouts.app', ['title' => 'Lista de Vendas PDV'])

@section('css')
    <style>
        /* ─── Header Gradiente ─── */
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

        /* ─── Botões de Ação do Formulário / Grid ─── */
        .modulo-action-group {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
            flex-wrap: nowrap !important;
        }

        .modulo-action-group .btn {
            padding: 5px 8px;
            font-size: 12px;
            border-radius: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-fill"></i>
                                Vendas PDV
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as vendas realizadas no PDV (Ponto de
                                Venda), acompanhe o status de cada transação e emita NFCe.</p>
                        </div>
                        <div class="d-inline-flex gap-1">
                            @can('pdv_create')
                                <a href="{{ route('frontbox.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                                    <i class="ri-add-circle-line align-middle me-1"></i> PDV
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    @if($contigencia != null)
                        <div
                            class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-start">
                            <i class="ri-error-warning-line me-2 fs-20 mt-0.5"></i>
                            <div>
                                <strong>Contingência Ativada!</strong>
                                Tipo: <strong>{{ $contigencia->tipo }}</strong> &mdash; Início:
                                <strong>{{ __data_pt($contigencia->created_at) }}</strong>
                            </div>
                        </div>
                    @endif

                    {{-- ═══ KPI CARDS ═══ --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-info mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Vendas</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $stats['total_vendas'] }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Total no período</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-shopping-cart-line"></i>
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
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Aprovadas</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $stats['aprovadas'] }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Vendas concluídas</p>
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
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Faturamento</h4>
                                            <h3 class="my-2 text-white fs-18">R$
                                                {{ __moeda($stats['total_dinheiro'] ?? $stats['valor_total'] ?? 0) }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Recebido</p>
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
                        <div class="col-md-3 col-6">
                            <div class="card widget-icon-box text-bg-danger mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Canceladas</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $stats['canceladas'] }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Vendas canceladas</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-close-circle-line"></i>
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
                                <i class="ri-search-line"></i> Filtrar Vendas do PDV
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
                                    <a class="btn btn-limpar px-3" href="{{ route('frontbox.index') }}"
                                        title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- TABELA PREMIUM -->
                    <div class="modulo-table-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Valor (R$)</th>
                                        <th>Estado</th>
                                        <th>Ambiente</th>
                                        <th>NFCe</th>
                                        <th>Data</th>
                                        <th>Lista Preço</th>
                                        <th>Usuário</th>
                                        <th class="text-end" style="min-width: 220px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            <td class="fw-bold text-muted">{{ $item->numero_sequencial }}</td>
                                            <td>
                                                <span
                                                    class="fw-semibold text-dark d-block">{{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome != "" ? $item->cliente_nome : "Consumidor Final") }}</span>
                                                <span
                                                    class="text-muted fs-11">{{ $item->cliente ? $item->cliente->cpf_cnpj : ($item->cliente_cpf_cnpj != "" ? $item->cliente_cpf_cnpj : '--') }}</span>
                                            </td>
                                            <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                            <td>
                                                @if($item->estado == 'aprovado')
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                                @elseif($item->estado == 'cancelado')
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                                @elseif($item->estado == 'rejeitado')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                                @else
                                                    <span
                                                        class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fs-11">
                                                    {{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->estado == 'aprovado' ? $item->numero : '--' }}</td>
                                            <td class="fs-12">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $item->lista ? $item->listaPreco->nome : '--' }}</td>
                                            <td class="fs-12">{{ $item->user ? $item->user->name : '--' }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('frontbox.destroy', $item->id) }}" method="post"
                                                    id="form-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modulo-action-group">
                                                        <a title="Imprimir não fiscal" class="btn btn-primary btn-sm text-white"
                                                            target="_blank"
                                                            href="{{ route('frontbox.imprimir-nao-fiscal', [$item->id]) }}">
                                                            <i class="ri-printer-line"></i>
                                                        </a>

                                                        @can('pdv_delete')
                                                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                                title="Excluir">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        @endcan

                                                        @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                            <button title="Transmitir NFCe" type="button"
                                                                class="btn btn-success btn-sm text-white"
                                                                onclick="transmitir('{{$item->id}}')">
                                                                <i class="ri-send-plane-fill"></i>
                                                            </button>
                                                            @can('pdv_edit')
                                                                <a class="btn btn-warning btn-sm text-white" title="Editar venda"
                                                                    href="{{ route('frontbox.edit', $item->id) }}">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            @endcan
                                                        @endif

                                                        @if($item->estado != 'aprovado')
                                                            <a class="btn btn-light btn-sm" title="Detalhes"
                                                                href="{{ route('frontbox.show', $item->id) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                            <a title="XML temporário" class="btn btn-dark btn-sm text-white"
                                                                href="{{ route('nfce.xml-temp', $item->id) }}">
                                                                <i class="ri-file-code-line"></i>
                                                            </a>
                                                        @endif

                                                        @if($item->estado == 'aprovado')
                                                            <a class="btn btn-success btn-sm text-white" title="Imprimir NFCe"
                                                                target="_blank" href="{{ route('nfce.imprimir', [$item->id]) }}">
                                                                <i class="ri-printer-line"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Nenhuma venda PDV encontrada.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                        <div>
                            <h5 class="m-0 text-dark">Total das Vendas: <strong class="text-success fs-16">R$
                                    {{ __moeda($data->sum('total')) }}</strong></h5>
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
    <script type="text/javascript" src="/js/nfce_transmitir.js"></script>
@endsection