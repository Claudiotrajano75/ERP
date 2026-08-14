@extends('layouts.app', ['title' => 'Pedidos de Ecommerce'])

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

        .modulo-form-card {
            border: 1px solid #eef0f5;
            border-radius: 12px;
            overflow: hidden;
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
            padding: 12px 16px;
            border-bottom: 2px solid #e8eaf6;
        }

        .modulo-table-wrap tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f8;
            font-size: 13px;
            color: #374151;
        }

        .modulo-table-wrap tbody tr:hover td {
            background: #fafbff;
        }

        .modulo-table-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        .modulo-empty {
            padding: 60px 20px;
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

        .payment-alert {
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .payment-alert.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .payment-alert.danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .payment-alert.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .badge-status.novo { background: #e0f2fe; color: #0284c7; }
        .badge-status.pago { background: #dcfce7; color: #16a34a; }
        .badge-status.preparando { background: #fef3c7; color: #d97706; }
        .badge-status.enviado { background: #f3e8ff; color: #9333ea; }
        .badge-status.entregue { background: #d1fae5; color: #059669; }
        .badge-status.cancelado { background: #fee2e2; color: #dc2626; }
        .badge-status.recusado { background: #f1f5f9; color: #475569; }
    </style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="col-12">

                @if(sizeof($paymentMethods) > 0)
                    <div class="card border-0 shadow-sm modulo-form-card mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h5 class="mb-0 fs-14 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ri-bank-card-line text-primary"></i> Status das Integrações de Pagamento
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                @foreach($paymentMethods as $p)
                                    <div class="col-md-6 col-12">
                                        <div class="payment-alert {{ $p['status'] == 'success' ? 'success' : 'warning' }} mb-0">
                                            <i class="ri-{{ $p['status'] == 'success' ? 'checkbox-circle-fill' : 'error-warning-fill' }} fs-18"></i>
                                            <span>{{ $p['message'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm modulo-form-card">

                    {{-- CABEÇALHO PREMIUM --}}
                    <div class="card-header modulo-header-gradient py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                    <i class="ri-inbox-archive-line"></i>
                                    Pedidos do E-commerce
                                </h4>
                                <p class="text-muted mb-0 modulo-subtitle fs-13">
                                    Acompanhe os pedidos gerados pela loja virtual.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- ═══ Filtros de Busca Premium ═══ --}}
                        <div class="modulo-glass-filter-premium">
                            <div class="filtro-premium-header">
                                <h5 class="filtro-premium-title">
                                    <i class="ri-search-line"></i> Filtrar Pedidos
                                </h5>
                            </div>

                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-3">
                                <div class="col-md-5 col-12">
                                    <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                                    {!!Form::select('cliente_id', '')
                                        ->options($cliente != null ? [$cliente->id => ($cliente->razao_social . " - " . $cliente->telefone)] : [])
                                        ->attrs(['class' => 'select2 form-select'])
                                    !!}
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label"><i class="ri-checkbox-circle-line"></i> Estado do Pedido</label>
                                    {!!Form::select('estado', '', ['' => 'Todos os Estados'] + App\Models\PedidoEcommerce::estados())
                                        ->attrs(['class' => 'form-select'])
                                    !!}
                                </div>
                                <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                            <i class="ri-search-line"></i> Buscar
                                        </button>
                                        <a id="clear-filter" class="btn btn-limpar px-3" href="{{ route('pedidos-ecommerce.index') }}" title="Limpar Filtros">
                                            <i class="ri-eraser-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>

                    {{-- TABELA PREMIUM --}}
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th># Pedido</th>
                                        <th>Data</th>
                                        <th>Cliente</th>
                                        <th>Pagamento</th>
                                        <th>Estado</th>
                                        <th class="text-center">Itens</th>
                                        <th class="text-end">Frete</th>
                                        <th class="text-end">Desconto</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            <td class="fw-bold text-dark">#{{ $item->hash_pedido }}</td>
                                            <td class="text-muted">{{ __data_pt($item->created_at) }}</td>
                                            <td class="fw-semibold">{{ $item->cliente->info }}</td>
                                            <td>
                                                <span class="badge bg-light text-secondary border fs-12 px-2 py-1"><i
                                                        class="ri-bank-card-line"></i>
                                                    {{ strtoupper($item->tipo_pagamento) }}</span>
                                            </td>
                                            <td>{!! $item->_estado() !!}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-2">{{ sizeof($item->itens) }}</span>
                                            </td>
                                            <td class="text-end text-muted">
                                                {{ $item->valor_frete > 0 ? 'R$ ' . __moeda($item->valor_frete) : 'Grátis' }}
                                            </td>
                                            <td class="text-end text-danger">
                                                {{ $item->desconto > 0 ? '- R$ ' . __moeda($item->desconto) : '--' }}
                                            </td>
                                            <td class="text-end fw-bold text-success fs-14">
                                                R$ {{ __moeda($item->valor_total) }}
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('pedidos-ecommerce.destroy', $item->id) }}" method="post"
                                                    id="form-{{$item->id}}" class="d-inline-flex gap-1 m-0">
                                                    @method('delete')
                                                    @csrf

                                                    <a title="Detalhes do Pedido"
                                                        href="{{ route('pedidos-ecommerce.show', $item->id) }}"
                                                        class="btn btn-dark btn-sm text-white px-2 rounded-2">
                                                        <i class="ri-survey-line"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-delete btn-sm btn-danger px-2 rounded-2"
                                                        title="Remover Pedido">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-archive-line"></i>
                                                    <p>Nenhum pedido de e-commerce encontrado.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($data->hasPages())
                        <div class="px-4 py-3 border-top bg-white">
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            // js
        });
    </script>
@endsection