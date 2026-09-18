@extends('layouts.app', ['title' => 'Lista de Preços'])

@section('css')
<style>
    .modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; margin-bottom: 6px; }
    .filter-wrap label i { color: #a8a8c0; }
    .filter-wrap .form-control, .filter-wrap .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 14px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Ações ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-edit { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .act-view { background: #e0f2fe; color: #0284c7; }
    .act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Pills ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-info { background: #eef0ff; color: #4f46e5; }
    .pill-up { background: #dcfce7; color: #15803d; }
    .pill-down { background: #fee2e2; color: #b91c1c; }
    .pill-blue { background: #e0f2fe; color: #0284c7; }

    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-price-tag-3-line"></i>
                            Lista de Preços
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Defina tabelas de preços alternativas por meios de pagamento, clientes, usuários ou datas promocionais.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="d-none d-sm-inline fs-12" style="background:#fff;border:1px solid #e9ecf3;border-radius:10px;padding:6px 12px;">
                            <i class="ri-store-2-line me-1" style="color:#4f46e5;"></i>
                            Total: <strong>{{ $totalDeProdutos }}</strong> produtos
                        </span>
                        <a href="{{ route('lista-preco.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('lista_preco_create')
                        <a href="{{ route('lista-preco.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Nova Lista
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ CARDS DE ESTATÍSTICA ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Listas de Preço</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">tabelas ativas</div></div>
                                <div class="st-icon"><i class="ri-price-tag-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Produtos</div><div class="st-value">{{ $stats['produtos'] }}</div><div class="st-sub">disponíveis no catálogo</div></div>
                                <div class="st-icon"><i class="ri-box-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Com Restrição</div><div class="st-value">{{ $stats['com_pagamento'] }}</div><div class="st-sub">por meio de pagamento</div></div>
                                <div class="st-icon"><i class="ri-bank-card-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Sem Restrição</div><div class="st-value">{{ $stats['total'] - $stats['com_pagamento'] }}</div><div class="st-sub">qualquer pagamento</div></div>
                                <div class="st-icon"><i class="ri-checkbox-blank-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="filter-wrap">
                    <div class="filtro-premium-header">
                        <h5 class="filter-title mb-0">
                            <i class="ri-search-line"></i> Filtrar Listas de Preços
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label class="form-label"><i class="ri-price-tag-3-line"></i> Nome da Lista</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da lista...'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-bank-card-line"></i> Meio de Pagamento</label>
                            {!!Form::select('tipo_pagamento', '', ['' => 'Todos os tipos'] + App\Models\ListaPreco::tiposPagamento())->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-user-follow-line"></i> Funcionário</label>
                            {!!Form::select('funcionario_id', '', ['' => 'Todos os funcionários'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-primary flex-grow-1" style="border-radius:10px;" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('lista-preco.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('lista_preco_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Nome</th>
                                    <th>Ajuste Sobre</th>
                                    <th>Tipo</th>
                                    <th>% Alteração</th>
                                    <th>Cadastro</th>
                                    <th>Meio de Pagamento</th>
                                    <th>Funcionário</th>
                                    <th class="text-end" style="width: 140px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('lista_preco_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->nome }}</span>
                                    </td>
                                    <td>
                                        <span class="pill pill-info">{{ $item->ajuste_sobre == 'valor_venda' ? 'Valor de venda' : 'Valor de compra' }}</span>
                                    </td>
                                    <td>
                                        @if($item->tipo == 'incremento')
                                        <span class="pill pill-up"><i class="ri-arrow-up-line"></i> Incremento</span>
                                        @else
                                        <span class="pill pill-down"><i class="ri-arrow-down-line"></i> Redução</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold fs-14">{{ $item->percentual_alteracao }}%</td>
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td>
                                        @if($item->tipo_pagamento)
                                        <span class="pill pill-blue"><i class="ri-bank-card-line"></i> {{ $item->getTipoPagamento() }}</span>
                                        @else
                                        <span class="text-muted fs-12">Qualquer</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-12">{{ $item->funcionario ? $item->funcionario->nome : '--' }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('lista-preco.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('lista_preco_edit')
                                                <a class="act-btn act-edit" href="{{ route('lista-preco.edit', [$item->id]) }}" title="Editar Lista"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                <a class="act-btn act-view" href="{{ route('lista-preco.show', [$item->id]) }}" title="Ver produtos vinculados"><i class="ri-file-list-2-line"></i></a>
                                                @can('lista_preco_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Lista"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('lista_preco_delete') ? 9 : 8) }}">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma lista de preços cadastrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote) ═══ -->
                <div class="modulo-footer">
                    <div class="d-flex gap-2 flex-wrap">
                        @can('lista_preco_delete')
                        <form action="{{ route('lista-preco.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="dash-btn dash-btn-light btn-delete-all" style="color:#dc2626;" disabled><i class="ri-delete-bin-2-line"></i> Remover Selecionadas</button>
                        </form>
                        @endcan
                    </div>
                    <div class="fs-12" style="color:#94a3b8;">Exibindo <strong>{{ $data->count() }}</strong> listas</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
