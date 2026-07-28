@extends('layouts.app', ['title' => 'Atendimento Garçom'])

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
    .modulo-table-wrap tfoot td { font-weight: 700; background: #f8f9fc; padding: 12px 16px; border-top: 2px solid #e8eaf6; }

    .comanda-badge { display: inline-flex; align-items: center; justify-content: center; background: #fee2e2; color: #dc2626; border-radius: 8px; padding: 4px 10px; font-size: 12px; font-weight: 700; }
    .total-highlight { background: rgba(22, 163, 74, 0.1); color: #16a34a; padding: 12px 20px; border-radius: 10px; border: 1px dashed #16a34a; display: inline-flex; align-items: center; gap: 10px; margin-top: 15px; font-size: 18px; font-weight: 700; }
    
    .modulo-empty { padding: 60px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- CABEÇALHO PREMIUM --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-user-star-line"></i>
                                Relatório Atendimento Garçom
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Consulte as vendas e comissões dos garçons.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BARRA DE FILTRO --}}
                <div class="modulo-filter-bar">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            {!!Form::select('funcionario_id', 'Garçom')
                            ->options($funcionario ? [$funcionario->id => $funcionario->nome] : [])
                            ->attrs(['class' => 'select2'])
                            !!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::date('start_date', 'Data inicial')!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::date('end_date', 'Data final')!!}
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-auto">
                            <a id="clear-filter" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('atendimento-garcom.index') }}">
                                <i class="ri-eraser-fill"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- TABELA PREMIUM --}}
                <div class="modulo-table-wrap">
                    <div class="p-3 border-bottom text-muted fs-13">
                        <i class="ri-list-check"></i> Total de registros encontrados: <strong>{{ $data->total() }}</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Garçom</th>
                                    <th>Data Registro</th>
                                    <th>Comanda</th>
                                    <th>Produto</th>
                                    <th class="text-end">Quantidade</th>
                                    <th class="text-end">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->funcionario ? $item->funcionario->nome : '--' }}</td>
                                    <td>{{ __data_pt($item->created_at) }}</td>
                                    <td>
                                        <span class="comanda-badge">{{ $item->pedido->comanda }}</span>
                                    </td>
                                    <td class="text-muted">{{ $item->produto->nome }}</td>
                                    <td class="text-end">
                                        @if($item->produto->unidade == 'UN')
                                        {{ number_format($item->quantidade, 0) }} UN
                                        @else
                                        {{ $item->quantidade }}
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        R$ {{ __moeda($item->sub_total) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-user-star-line"></i>
                                            <p>Nenhum registro de atendimento encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($data->count() > 0)
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end text-muted text-uppercase" style="font-size: 11px;">Total (Página)</td>
                                    <td class="text-end text-success fs-14">R$ {{ __moeda($data->sum('sub_total')) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="px-4 py-3 border-top bg-white d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        @if($data->total() > 0)
                            {!! $data->appends(request()->all())->links() !!}
                        @endif
                    </div>
                    <div>
                        <div class="total-highlight">
                            <i class="ri-money-dollar-circle-fill fs-24"></i>
                            <div>
                                <span class="d-block text-uppercase" style="font-size: 11px; font-weight: 600; opacity: 0.8; line-height: 1;">Total Geral Filtrado</span>
                                <span>R$ {{ __moeda($soma) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection