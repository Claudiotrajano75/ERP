@extends('layouts.app', ['title' => 'Tributações Padrão'])

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

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 10px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 12px 10px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 12.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Ações ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-edit { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Pills ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-no { background: #f1f5f9; color: #64748b; }
    .pill-info { background: #eef0ff; color: #4f46e5; }

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
                            <i class="ri-scales-3-line"></i>
                            Padrões de Tributação
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Configure templates tributários pré-definidos para agilizar a emissão fiscal de notas e o cadastro de produtos.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @can('config_produto_fiscal_create')
                        <a href="{{ route('produtopadrao-tributacao.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Novo Padrão
                        </a>
                        @endcan
                        @can('config_produto_fiscal_edit')
                        <a href="{{ route('produtopadrao-tributacao.alterar') }}" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line"></i> Alterar Tributação
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ CARDS DE ESTATÍSTICA ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-4">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Padrões de Tributação</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">templates cadastrados</div></div>
                                <div class="st-icon"><i class="ri-scales-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Padrão Ativo</div><div class="st-value">{{ $stats['padrao'] }}</div><div class="st-sub">usado por padrão no cadastro</div></div>
                                <div class="st-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Outros</div><div class="st-value">{{ $stats['total'] - $stats['padrao'] }}</div><div class="st-sub">templates opcionais</div></div>
                                <div class="st-icon"><i class="ri-stack-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('config_produto_fiscal_delete')
                                    <th style="width: 36px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Descrição</th>
                                    <th>Padrão</th>
                                    <th>NCM</th>
                                    <th>% ICMS</th>
                                    <th>% PIS</th>
                                    <th>% COFINS</th>
                                    <th>% IPI</th>
                                    <th>CST</th>
                                    <th>CST PIS</th>
                                    <th>CST COFINS</th>
                                    <th>CST IPI</th>
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('config_produto_fiscal_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <span class="fw-semibold text-dark d-block fs-13">{{ $item->descricao }}</span>
                                    </td>
                                    <td>
                                        @if($item->padrao)
                                        <span class="pill pill-ok"><i class="ri-check-line"></i> Sim</span>
                                        @else
                                        <span class="pill pill-no">Não</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-12">{{ $item->ncm ?? '--' }}</td>
                                    <td class="fw-semibold">{{ $item->perc_icms }}%</td>
                                    <td>{{ $item->perc_pis }}%</td>
                                    <td>{{ $item->perc_cofins }}%</td>
                                    <td>{{ $item->perc_ipi }}%</td>
                                    <td><span class="pill pill-info">{{ $item->cst_csosn }}</span></td>
                                    <td><span class="pill pill-info">{{ $item->cst_pis }}</span></td>
                                    <td><span class="pill pill-info">{{ $item->cst_cofins }}</span></td>
                                    <td><span class="pill pill-info">{{ $item->cst_ipi }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('produtopadrao-tributacao.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('config_produto_fiscal_edit')
                                                <a class="act-btn act-edit" href="{{ route('produtopadrao-tributacao.edit', [$item->id]) }}" title="Editar Padrão"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('config_produto_fiscal_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Padrão"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('config_produto_fiscal_delete') ? 13 : 12) }}">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum padrão de tributação cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote + Paginação) ═══ -->
                <div class="modulo-footer">
                    <div class="d-flex gap-2 flex-wrap">
                        @can('config_produto_fiscal_delete')
                        <form action="{{ route('produtopadrao-tributacao.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="dash-btn dash-btn-light btn-delete-all" style="color:#dc2626;" disabled><i class="ri-delete-bin-2-line"></i> Remover Selecionados</button>
                        </form>
                        @endcan
                    </div>
                    <div class="fs-12" style="color:#94a3b8;">Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> padrões</div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
