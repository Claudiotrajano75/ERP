@extends('layouts.app', ['title' => 'Financeiro — ' . $item->nome])

@section('css')
<style>
    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
    .filter-wrap .form-control,
    .filter-wrap .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    .filter-wrap .form-control:focus,
    .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }
    .tb-wrap tfoot td { padding: 12px 16px; font-size: 13px; font-weight: 700; color: #374151; background: #f8f9fc; border-top: 2px solid #e8eaf6; }

    /* ─── Grade de botões de ação ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-del  { background: #fee2e2; color: #dc2626; }
    .act-del:hover  { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Badges ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok    { background: #dcfce7; color: #15803d; }
    .pill-amber { background: #fef3c7; color: #b45309; }

    /* ─── Estado vazio ─── */
    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-money-dollar-circle-line"></i> Financeiro do Contador
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Histórico de pagamentos e comissões de <strong>{{ $item->nome }}</strong>.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('contadores.index') }}" class="dash-btn dash-btn-light"><i class="ri-arrow-left-line"></i> Voltar</a>
                        <a href="{{ route('contadores.financeiro-create', [$item->id]) }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Novo Pagamento
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ Filtros ═══ --}}
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar por Período</h5>
                    </div>
                    <div class="mt-3">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Mês</label>
                                {!!Form::select('mes', 'Mês', ['' => 'Todos os meses'] + \App\Models\FinanceiroContador::meses())
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-calendar-2-line"></i> Ano</label>
                                <select class="form-select" name="ano">
                                    @foreach(\App\Models\FinanceiroContador::anos() as $key => $a)
                                    <option @if(request()->ano == $a) selected @elseif(!request()->ano && date('Y') == $a) selected @endif value="{{ $a }}">{{ $a }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-light border px-3" href="{{ route('contadores.financeiro', [$item->id]) }}" title="Limpar Filtros" style="border-radius:10px;">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>

                {{-- ═══ Tabela ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Mês / Ano</th>
                                    <th>Valor de Venda</th>
                                    <th>Valor da Comissão</th>
                                    <th>% Comissão</th>
                                    <th>Tipo de Pagamento</th>
                                    <th>Observação</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 70px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($financeiro as $i)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="color:#1f2937;">{{ ucfirst($i->mes) }}/{{ $i->ano }}</div>
                                    </td>
                                    <td>R$ {{ __moeda($i->total_venda) }}</td>
                                    <td>R$ {{ __moeda($i->valor_comissao) }}</td>
                                    <td>{{ $i->percentual_comissao }}%</td>
                                    <td>{{ $i->tipo_pagamento ?? '—' }}</td>
                                    <td>
                                        <span style="color:#64748b;font-size:13px;">{{ $i->observacao ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @if($i->status_pagamento)
                                        <span class="pill pill-ok"><i class="ri-checkbox-circle-line"></i> Pago</span>
                                        @else
                                        <span class="pill pill-amber"><i class="ri-time-line"></i> Pendente</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('contadores-financeiro.destroy', [$i->id]) }}" method="post" id="form-{{$i->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir registro">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="ri-money-dollar-circle-line"></i>
                                            <p>Nenhum registro financeiro encontrado para este período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($financeiro->count() > 0)
                            <tfoot>
                                <tr>
                                    <td><i class="ri-calculator-line me-1" style="color:#4f46e5;"></i> Totais</td>
                                    <td><strong>R$ {{ __moeda($financeiro->sum('total_venda')) }}</strong></td>
                                    <td><strong>R$ {{ __moeda($financeiro->sum('valor_comissao')) }}</strong></td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
