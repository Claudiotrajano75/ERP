@extends('layouts.app', ['title' => 'Movimentações - ' . $item->nome])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

/* ─── Filtros Glass ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">
            
            <!-- Cabeçalho Principal -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            Extrato: {{ $item->nome }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Banco: <strong class="text-white">{{ $item->banco ?: 'Interno' }}</strong> | 
                            Agência: <strong class="text-white">{{ $item->agencia ?: '--' }}</strong> | 
                            Conta: <strong class="text-white">{{ $item->conta ?: '--' }}</strong> | 
                            Saldo Atual: <strong class="text-white font-weight-bold">R$ {{ __moeda($item->saldo) }}</strong>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('conta-pagar.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Conta a Pagar
                        </a>
                        <a href="{{ route('contas-empresa.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- Filtros do Extrato -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-3 col-12">
                            {!!Form::select('tipo', 'Tipo de Transação', ['' => 'Todos', 'entrada' => 'Entrada', 'saida' => 'Saída'])
                            ->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('contas-empresa.show', [$item->id]) }}" title="Limpar Filtro">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela de Movimentações -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Descrição da Movimentação</th>
                                    <th>Meio de Pagamento</th>
                                    <th class="text-end">Valor</th>
                                    <th class="text-end">Saldo Acumulado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $m)
                                <tr>
                                    <td class="text-muted fs-12">{{ __data_pt($m->created_at) }}</td>
                                    <td>
                                        <span class="text-dark fw-semibold">{{ $m->descricao }}</span>
                                        @if($m->caixa_id)
                                        <div class="fs-11 text-muted">
                                            <i class="ri-calculator-line align-middle me-1"></i> Fechamento de Caixa (Abertura: {{ __data_pt($m->caixa->created_at) }})
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m->tipo_pagamento)
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-12">
                                            {{ App\Models\Nfce::getTipoPagamento($m->tipo_pagamento) }}
                                        </span>
                                        @else
                                        <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold @if($m->tipo == 'entrada') text-success @else text-danger @endif">
                                        @if($m->tipo == 'entrada')+@else-@endif R$ {{ __moeda($m->valor) }}
                                    </td>
                                    <td class="text-end fw-bold @if($m->saldo_atual >= 0) text-info @else text-danger @endif">
                                        R$ {{ __moeda($m->saldo_atual) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma movimentação encontrada para o período selecionado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação -->
                <div class="mt-3 d-flex justify-content-end">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
