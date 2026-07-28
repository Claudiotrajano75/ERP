@extends('layouts.app', ['title' => 'Pedidos de Ecommerce'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    
    .modulo-filter-bar { background: #fff; border-bottom: 1px solid #eef0f5; padding: 16px 24px; }
    .modulo-filter-bar label { font-size: 12px; font-weight: 600; color: #5a5a7a; }
    
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 16px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .modulo-table-wrap tbody tr:hover td { background: #fafbff; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
    
    .modulo-empty { padding: 60px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
    
    .payment-alert { border-radius: 8px; font-size: 13px; font-weight: 600; padding: 12px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .payment-alert.success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .payment-alert.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            
            @if(count($pagamentosAlterados) > 0)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3 pb-0">
                    <h5 class="text-muted fs-14 fw-bold mb-3 d-flex align-items-center gap-2"><i class="ri-notification-3-line"></i> Atualizações Automáticas de Pagamento (Mercado Pago)</h5>
                    <div class="row">
                        @foreach($pagamentosAlterados as $p)
                        <div class="col-md-4">
                            <div class="payment-alert {{ $p['status'] == 'approved' ? 'success' : 'danger' }}">
                                <i class="{{ $p['status'] == 'approved' ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-16"></i>
                                Pedido #{{ $p['hash_pedido'] }} alterado para: {{ strtoupper($p['status']) }}
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

                {{-- BARRA DE FILTRO --}}
                <div class="modulo-filter-bar">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            {!!Form::select('cliente_id', 'Pesquisar por cliente')
                            ->options($cliente != null ? [$cliente->id => ($cliente->razao_social . " - " . $cliente->telefone)] : [])
                            ->attrs(['class' => 'select2'])
                            !!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::select('estado', 'Estado', ['' => 'Selecione'] + App\Models\PedidoEcommerce::estados())
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-auto">
                            <a id="clear-filter" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('pedidos-ecommerce.index') }}">
                                <i class="ri-eraser-fill"></i> Limpar
                            </a>
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
                                        <span class="badge bg-light text-secondary border fs-12 px-2 py-1"><i class="ri-bank-card-line"></i> {{ strtoupper($item->tipo_pagamento) }}</span>
                                    </td>
                                    <td>{!! $item->_estado() !!}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-2">{{ sizeof($item->itens) }}</span>
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
                                        <form action="{{ route('pedidos-ecommerce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline-flex gap-1 m-0">
                                            @method('delete')
                                            @csrf
                                            
                                            <a title="Detalhes do Pedido" href="{{ route('pedidos-ecommerce.show', $item->id) }}" class="btn btn-dark btn-sm text-white px-2 rounded-2">
                                                <i class="ri-survey-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-delete btn-sm btn-danger px-2 rounded-2" title="Remover Pedido">
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
    $(function(){
        // js
    });
</script>
@endsection

