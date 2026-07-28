@extends('layouts.app', ['title' => 'Pedidos'])

@section('css')
<style type="text/css">
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }

    /* Card de Pedido */
    .pedido-card { border: 1px solid #e2e8f0; border-radius: 12px; transition: all 0.2s; text-decoration: none; display: block; overflow: hidden; }
    .pedido-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #cbd5e1; }
    .pedido-card-header { background-color: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; }
    .pedido-card-body { padding: 16px; background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-send-plane-2-fill"></i>
                                Pedidos do Cliente
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Histórico de pedidos realizados por <strong>{{ $cliente->razao_social }}</strong>.
                            </p>
                        </div>
                        <a href="{{ route('clientes-delivery.index') }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-arrow-left-double-fill me-1"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    
                    <div class="row g-4">
                        @forelse($cliente->pedidos as $item)
                        <div class="col-12 col-md-6 col-lg-4">
                            <a class="pedido-card text-dark h-100" href="{{ route('pedidos-delivery.show', [$item->id]) }}">
                                <div class="pedido-card-header d-flex justify-content-between align-items-center">
                                    <h5 class="m-0 fw-bold text-primary"><i class="ri-hashtag text-muted me-1 fs-15"></i>{{ $item->id }}</h5>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fs-12">
                                        R$ {{ __moeda($item->valor_total) }}
                                    </span>
                                </div>
                                <div class="pedido-card-body">
                                    <div class="mb-2">
                                        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Itens</small>
                                        <div class="fs-15 fw-medium"><i class="ri-shopping-basket-line me-2 text-primary"></i>{{ sizeof($item->itens) }} itens no pedido</div>
                                    </div>
                                    <hr class="my-2 border-dashed">
                                    <div>
                                        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Entrega</small>
                                        @if($item->endereco)
                                        <div class="fs-14 fw-medium text-truncate" title="{{ $item->endereco->info }}"><i class="ri-map-pin-line me-2 text-danger"></i>{{ $item->endereco->info }}</div>
                                        @else
                                        <div class="fs-14 fw-medium"><i class="ri-store-2-line me-2 text-info"></i>Retirada no balcão</div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="ri-send-plane-2-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <h5 class="text-muted">Nenhum pedido para este cliente</h5>
                        </div>
                        @endforelse
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
