@extends('layouts.app', ['title' => 'Comissão Motoboy'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    /* Tabela */
    .table-custom thead th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; border-top: none; padding: 14px 16px; }
    .table-custom tbody tr { transition: all 0.2s; border-bottom: 1px solid #eef0f5; }
    .table-custom tbody tr:hover { background-color: #f8fafc; }
    .table-custom tbody td { padding: 14px 16px; vertical-align: middle; color: #1e293b; font-size: 14px; }
    
    /* Filtros */
    .filter-box { background-color: #f8fafc; border: 1px solid #eef0f5; border-radius: 10px; padding: 16px; margin-bottom: 24px; }

    /* Cards de Totalizadores */
    .total-card { background: #f8fafc; border: 1px solid #eef0f5; border-radius: 10px; padding: 20px; border-left: 4px solid #1a4a6e; }
    .total-card h6 { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .total-card h3 { margin: 0; font-weight: 700; font-size: 24px; }
    .border-left-danger { border-left-color: #dc3545 !important; }
    .border-left-success { border-left-color: #198754 !important; }
    .border-left-primary { border-left-color: #0d6efd !important; }
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
                                <i class="ri-wallet-2-fill"></i>
                                Comissão Motoboys
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie e pague as comissões dos entregadores.
                            </p>
                        </div>
                        <a href="{{ route('motoboys.index') }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-arrow-left-double-fill me-1"></i> Voltar para Motoboys
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    
                    <div class="filter-box">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row align-items-end g-3">
                            <div class="col-md-3">
                                {!!Form::select('motoboy_id', 'Motoboy', ['' => 'Selecione'] + $motoboys->pluck('nome', 'id')->all())
                                ->attrs(['class' => 'select2 form-select'])
                                ->value($motoboy != null ? $motoboy->id : [])!!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::date('start_date', 'Data inicial')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::date('end_date', 'Data final')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2">
                                {!!Form::select('status', 'Status', ['' => 'Todos', '0' => 'Pendente', '1' => 'Pago'])->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary px-3" type="submit" style="background-color: #0d2b40; border-color: #0d2b40;">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a id="clear-filter" class="btn btn-light border px-3" href="{{ route('motoboys-comissao.index') }}">
                                    <i class="ri-eraser-fill me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="total-card border-left-danger">
                                <h6 class="text-danger">Total Pendente</h6>
                                <h3>R$ {{ __moeda($sumComissaoPendente) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="total-card border-left-success">
                                <h6 class="text-success">Total Pago</h6>
                                <h3>R$ {{ __moeda($sumComissaoPago) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="total-card border-left-primary">
                                <h6 class="text-primary">Total em Vendas</h6>
                                <h3>R$ {{ __moeda($sumPedidos) }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <form method="post" action="{{ route('motoboys-comissao.pay-multiple') }}" id="form-comissao">
                            @csrf
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-success fw-bold px-4 py-2 btn-pay shadow-sm" disabled>
                                    <i class="ri-wallet-3-fill me-1"></i>
                                    Pagar Selecionados <span class="badge bg-white text-success ms-1 fs-12 total-pay">R$ 0,00</span>
                                </button>
                            </div>

                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th width="40px">
                                            <div class="form-check">
                                                <input class="form-check-input select-all" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Motoboy</th>
                                        <th>Data</th>
                                        <th>Valor Pedido</th>
                                        <th>Valor Comissão</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            @if(!$item->status)
                                            <div class="form-check">
                                                <input class="form-check-input select-check" type="checkbox" name="check[]" value="{{ $item->id }}" id="check-{{$item->id}}">
                                            </div>
                                            @else
                                            <i class="ri-check-double-line text-success fs-5"></i>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $item->motoboy->nome }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-13"><i class="ri-calendar-event-line me-1 text-muted"></i>{{ __data_pt($item->created_at) }}</span>
                                        </td>
                                        <td>R$ {{ __moeda($item->valor_total_pedido) }}</td>
                                        <td class="fw-bold text-success">R$ {{ __moeda($item->valor) }}</td>
                                        <td>
                                            @if($item->status)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="ri-check-line"></i> Pago</span>
                                            @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="ri-time-line"></i> Pendente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="ri-wallet-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                            <h5 class="text-muted">Nenhuma comissão encontrada</h5>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light fw-bold">
                                        <td colspan="3" class="text-end text-muted">Totais da página:</td>
                                        <td class="text-primary">R$ {{ __moeda($data->sum('valor_total_pedido')) }}</td>
                                        <td class="text-success">R$ {{ __moeda($data->sum('valor')) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                            @include('modals._modal_conta_pagar')

                        </form>
                    </div>

                    <div class="mt-4">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="/js/comissao_motoboy.js"></script>
@endsection
