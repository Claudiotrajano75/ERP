@extends('layouts.app', ['title' => 'Controle de Estoque'])

@section('css')
<style>
    .modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
    .stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.32); }
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
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Ações ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-edit { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .act-profile { background: #dcfce7; color: #16a34a; }
    .act-profile:hover { box-shadow: 0 4px 12px rgba(22,163,74,.3); }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }

    /* ─── Modal Entrada/Saída ─── */
    #modal_estoque_ajuste .modal-content { border-radius: 16px; border: 0; }
    #modal_estoque_ajuste .modal-header { border-bottom: 1px solid #eef0f6; padding: 18px 22px; }
    #modal_estoque_ajuste .modal-title { font-size: 16px; font-weight: 700; color: #1f2937; }
    #modal_estoque_ajuste .modal-title i { color: #4f46e5; }
    #modal_estoque_ajuste .modal-body { padding: 20px 22px; }
    #modal_estoque_ajuste .modal-footer { border-top: 1px solid #eef0f6; padding: 14px 22px; }
    #modal_estoque_ajuste .form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
    #modal_estoque_ajuste .form-control, #modal_estoque_ajuste .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    #modal_estoque_ajuste .form-control:focus, #modal_estoque_ajuste .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }
    #modal_estoque_ajuste textarea.form-control { height: auto; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-store-3-line"></i>
                            Controle de Estoque
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie e fiscalize a quantidade física de produtos nos estoques de suas localizações.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('estoque_create')
                        <a href="{{ route('estoque.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Adicionar Estoque
                        </a>
                        <a href="#" class="dash-btn dash-btn-light" data-bs-toggle="modal" data-bs-target="#modal_estoque_ajuste">
                            <i class="ri-swap-line align-middle me-1"></i> Entrada/Saída
                        </a>
                        <a href="{{ route('apontamento.create') }}" class="dash-btn dash-btn-light">
                            <i class="ri-settings-3-line align-middle me-1"></i> Apontamento
                        </a>
                        @endcan
                        @can('estoque_view')
                        <a href="{{ route('estoque.movimentacoes') }}" class="dash-btn dash-btn-light">
                            <i class="ri-history-line align-middle me-1"></i> Movimentações
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Produtos</div><div class="st-value">{{ $stats['total_produtos'] }}</div><div class="st-sub">Produtos em estoque</div></div>
                                <div class="st-icon"><i class="ri-box-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Itens</div><div class="st-value">{{ number_format($stats['total_itens'], 0) }}</div><div class="st-sub">Quantidade total</div></div>
                                <div class="st-icon"><i class="ri-stack-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Valor Estimado</div><div class="st-value">R$ {{ __moeda($stats['valor_estoque']) }}</div><div class="st-sub">Valor de venda em estoque</div></div>
                                <div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Estoque Baixo</div><div class="st-value">{{ $stats['estoque_baixo'] }}</div><div class="st-sub">Abaixo do mínimo</div></div>
                                <div class="st-icon"><i class="ri-alert-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="filter-wrap">
                    <div class="filtro-premium-header">
                        <h5 class="filter-title mb-0">
                            <i class="ri-search-line"></i> Filtrar Estoque
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-8 col-12">
                            <label class="form-label"><i class="ri-box-3-line"></i> Pesquisar por Produto</label>
                            {!!Form::text('produto', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome ou código de barras...'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-primary flex-grow-1" style="border-radius:10px;" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-light border px-3" style="border-radius:10px;" href="{{ route('estoque.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Imagem</th>
                                    <th>Produto</th>
                                    <th class="text-center">Quantidade Atual</th>
                                    <th>Valor de Venda</th>
                                    <th class="text-end" style="width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <img class="rounded border" src="{{ $item->produto->img }}"
                                             style="width: 45px; height: 45px; object-fit: cover;">
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->descricao() }}</div>
                                        <span class="text-muted fs-11">Unidade: {{ $item->produto->unidade }}</span>
                                    </td>

                                    <td class="text-center">
                                        @if(__countLocalAtivo() == 1)
                                            @php
                                                $qtd = (float)$item->quantidade;
                                                $qtdFormatada = ($item->produto->unidade == 'UN' || $item->produto->unidade == 'UNID') 
                                                    ? number_format($item->quantidade, 0) 
                                                    : number_format($item->quantidade, 3, '.', '');
                                            @endphp
                                            @if($qtd <= 0)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-12 fw-bold" title="Estoque zerado ou negativo">
                                                    <i class="ri-alert-line me-1"></i>{{ $qtdFormatada }}
                                                </span>
                                            @elseif($item->produto->estoque_minimo > 0 && $qtd <= $item->produto->estoque_minimo)
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 fs-12 fw-bold" title="Estoque baixo (Mínimo: {{ $item->produto->estoque_minimo }})">
                                                    <i class="ri-error-warning-line me-1"></i>{{ $qtdFormatada }}
                                                </span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12 fw-bold" title="Estoque disponível">
                                                    <i class="ri-box-3-line me-1"></i>{{ $qtdFormatada }}
                                                </span>
                                            @endif
                                        @else
                                            <div class="fs-11 text-muted d-inline-flex flex-wrap gap-1 justify-content-center" style="min-width: 120px;">
                                                @foreach($item->produto->estoqueLocais as $e)
                                                    @if($e->local)
                                                        @php
                                                            $qtdLocal = (float)$e->quantidade;
                                                            $qtdLocalFormatada = ($item->produto->unidade == 'UN' || $item->produto->unidade == 'UNID')
                                                                ? number_format($e->quantidade, 0)
                                                                : number_format($e->quantidade, 3);
                                                        @endphp
                                                        <span class="badge {{ $qtdLocal <= 0 ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-light text-dark border' }} py-1 px-2">
                                                            {{ $e->local->descricao }}: <strong class="{{ $qtdLocal <= 0 ? 'text-danger' : 'text-success' }}">{{ $qtdLocalFormatada }}</strong>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="fw-bold text-success">R$ {{ __moeda($item->produto->valor_unitario) }}</span>
                                    </td>

                                    <td class="text-end">
                                        <form action="{{ route('estoque.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('estoque_edit')
                                                <a class="act-btn act-edit" href="{{ route('estoque.edit', [$item->id]) }}" title="Editar quantidade de estoque"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('produtos_edit')
                                                <a class="act-btn act-profile" href="{{ route('produtos.edit', [$item->produto_id]) }}" title="Editar dados cadastrais do produto"><i class="ri-box-3-line"></i></a>
                                                @endcan
                                                @can('estoque_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Remover registro de estoque"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="ri-store-3-line"></i>
                                            <p>Nenhum registro de estoque encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Footer / Paginação ═══ -->
                <div class="modulo-footer">
                    <div class="fs-12" style="color:#94a3b8;">Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> registros</div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('estoque._modal_ajuste')
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        $('#estoque_ajuste_produto_id').select2({
            minimumInputLength: 2,
            language: 'pt-BR',
            placeholder: 'Digite para buscar o produto',
            width: '100%',
            dropdownParent: $('#modal_estoque_ajuste'),
            ajax: {
                cache: true,
                url: path_url + 'api/produtos',
                dataType: 'json',
                data: function (params) {
                    return {
                        pesquisa: params.term,
                        empresa_id: $('#empresa_id').val()
                    };
                },
                processResults: function (response) {
                    var results = [];
                    $.each(response, function (i, v) {
                        var o = {};
                        o.id = v.id;
                        o.text = v.nome;
                        if (v.codigo_barras) {
                            o.text += ' [' + v.codigo_barras + ']';
                        }
                        results.push(o);
                    });
                    return { results: results };
                }
            }
        });
    });
</script>
@endsection
