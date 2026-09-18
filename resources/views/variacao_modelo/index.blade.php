@extends('layouts.app', ['title' => 'Modelos de Variação'])

@section('css')
<style>
    .modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.32); }
    .stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

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
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Pills ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-no { background: #fee2e2; color: #b91c1c; }
    .tag-item { background: #eef0ff; color: #4f46e5; border-radius: 8px; padding: 3px 10px; font-size: 12px; font-weight: 600; display: inline-block; margin: 2px; }
    .modulo-tag-list { display: flex; flex-wrap: wrap; gap: 4px; }

    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-shapes-line"></i>
                            Modelos de Variação
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Configure atributos dinâmicos para seus produtos, como Grade de Tamanhos, Cores ou Voltagens.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('variacoes.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('variacao_create')
                        <a href="{{ route('variacoes.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Nova Variação</a>
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
                                <div><div class="st-label">Total</div><div class="st-value">{{ $stats['total'] }}</div><div class="st-sub">modelos cadastrados</div></div>
                                <div class="st-icon"><i class="ri-shapes-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Ativas</div><div class="st-value">{{ $stats['ativas'] }}</div><div class="st-sub">em uso</div></div>
                                <div class="st-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Inativas</div><div class="st-value">{{ $stats['inativas'] }}</div><div class="st-sub">desativadas</div></div>
                                <div class="st-icon"><i class="ri-close-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><div class="st-label">Valores</div><div class="st-value">{{ $stats['valores'] }}</div><div class="st-sub">opções configuradas</div></div>
                                <div class="st-icon"><i class="ri-list-check-2"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ TABELA ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    @can('variacao_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Descrição (Atributo)</th>
                                    <th>Valores Configurados</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 110px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('variacao_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <span class="fw-semibold d-block" style="color:#1f2937;">{{ $item->descricao }}</span>
                                    </td>
                                    <td>
                                        <div class="modulo-tag-list">
                                            @php
                                                $valores = explode(',', $item->valores());
                                            @endphp
                                            @foreach(array_slice($valores, 0, 5) as $v)
                                                <span class="tag-item">{{ trim($v) }}</span>
                                            @endforeach
                                            @if(count($valores) > 5)
                                                <span class="tag-item">+{{ count($valores) - 5 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="pill pill-ok"><i class="ri-check-line"></i> Ativa</span>
                                        @else
                                        <span class="pill pill-no"><i class="ri-close-line"></i> Inativa</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('variacoes.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('variacao_edit')
                                                <a class="act-btn act-edit" href="{{ route('variacoes.edit', [$item->id]) }}" title="Editar Variação"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('variacao_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Variação"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('variacao_delete') ? 5 : 4) }}">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum modelo de variação cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ FOOTER ═══ --}}
                <div class="modulo-footer">
                    <div class="d-flex gap-2 flex-wrap">
                        @can('variacao_delete')
                        <form action="{{ route('variacoes.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="dash-btn dash-btn-light btn-delete-all" style="color:#dc2626;" disabled>
                                <i class="ri-delete-bin-2-line"></i> Remover Selecionados
                            </button>
                        </form>
                        @endcan
                    </div>
                    <div class="fs-12" style="color:#94a3b8;">Exibindo <strong>{{ $data->count() }}</strong> modelos</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
