@extends('layouts.app', ['title' => 'Movimentações de Estoque'])

@section('css')
<style>
    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.32); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; margin-bottom: 6px; display: inline-flex; align-items: center; gap: 5px; }
    .filter-wrap label i { color: #a8a8c0; }
    .filter-wrap .form-control, .filter-wrap .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Pills ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-red { background: #fee2e2; color: #b91c1c; }
    .pill-role { background: #eef0ff; color: #4f46e5; }
    .pill-no { background: #f1f5f9; color: #64748b; }

    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ Cabeçalho ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-history-line"></i>
                            Movimentações de Estoque
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Extrato de entradas e saídas, com origem e saldo resultante de cada movimentação.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        <a href="{{ route('estoque.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar ao Estoque
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Movimentações</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">Registros no filtro</div></div>
                                <div class="st-icon"><i class="ri-file-list-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Entradas</div><div class="st-value">{{ number_format($stats['entradas'], 0, ',', '.') }}</div><div class="st-sub">Soma das quantidades</div></div>
                                <div class="st-icon"><i class="ri-arrow-down-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Saídas</div><div class="st-value">{{ number_format($stats['saidas'], 0, ',', '.') }}</div><div class="st-sub">Soma das quantidades</div></div>
                                <div class="st-icon"><i class="ri-arrow-up-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Ajustes Manuais</div><div class="st-value">{{ $stats['ajustes'] }}</div><div class="st-sub">Alteração de estoque</div></div>
                                <div class="st-icon"><i class="ri-equalizer-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros ═══ -->
                <div class="filter-wrap">
                    <h5 class="filter-title mb-3">
                        <i class="ri-search-line"></i> Filtrar Movimentações
                    </h5>

                    <form method="get" action="{{ route('estoque.movimentacoes') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-12">
                                <label><i class="ri-box-3-line"></i> Produto</label>
                                <input type="text" name="produto" value="{{ request('produto') }}" class="form-control" placeholder="Digite o nome do produto...">
                            </div>
                            <div class="col-md-2 col-6">
                                <label><i class="ri-swap-line"></i> Tipo</label>
                                <select name="tipo" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="incremento" @selected(request('tipo') === 'incremento')>Entrada</option>
                                    <option value="reducao" @selected(request('tipo') === 'reducao')>Saída</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <label><i class="ri-exchange-line"></i> Origem</label>
                                <select name="tipo_transacao" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="venda_nfe" @selected(request('tipo_transacao') === 'venda_nfe')>Venda NFe</option>
                                    <option value="venda_nfce" @selected(request('tipo_transacao') === 'venda_nfce')>Venda NFCe</option>
                                    <option value="compra" @selected(request('tipo_transacao') === 'compra')>Compra</option>
                                    <option value="alteracao_estoque" @selected(request('tipo_transacao') === 'alteracao_estoque')>Alteração de estoque</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <label><i class="ri-calendar-line"></i> Data Inicial</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2 col-6">
                                <label><i class="ri-calendar-line"></i> Data Final</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>

                            <div class="col-md-3 col-12 ms-auto d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3" href="{{ route('estoque.movimentacoes') }}" title="Limpar Filtros" style="border-radius:10px;">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ═══ Tabela ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Tipo</th>
                                    <th>Origem</th>
                                    <th>Observação</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-center">Saldo Após</th>
                                    <th>Usuário</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->produto ? $item->produto->nome : '--' }}</div>
                                        @if($item->produtoVariacao)
                                        <span class="text-muted fs-11">Variação: {{ $item->produtoVariacao->descricao }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->tipo == 'incremento')
                                        <span class="pill pill-ok"><i class="ri-arrow-down-line"></i> Entrada</span>
                                        @else
                                        <span class="pill pill-red"><i class="ri-arrow-up-line"></i> Saída</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="pill {{ $item->tipo_transacao == 'compra' ? 'pill-ok' : ($item->tipo_transacao == 'alteracao_estoque' ? 'pill-no' : 'pill-role') }}">
                                            {{ $item->tipoTransacao() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ $item->observacao ? \Illuminate\Support\Str::limit($item->observacao, 40) : '--' }}</span>
                                    </td>
                                    <td class="text-center fw-bold {{ $item->tipo == 'incremento' ? 'text-success' : 'text-danger' }}">
                                        {{ $item->tipo == 'incremento' ? '+' : '-' }}{{ number_format($item->quantidade, 3, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-bold">{{ number_format($item->estoque_atual, 3, ',', '.') }}</span>
                                    </td>
                                    <td><span class="text-muted fs-13">{{ $item->user ? $item->user->name : '--' }}</span></td>
                                    <td><span class="text-muted fs-13">{{ __data_pt($item->created_at) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="ri-history-line"></i>
                                            <p>Nenhuma movimentação encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Footer / Paginação ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> registros</div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
