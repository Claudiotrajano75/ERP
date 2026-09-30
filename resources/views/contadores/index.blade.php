@extends('layouts.app', ['title' => 'Contadores'])

@section('css')
<style>
    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(220,38,38,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
    .filter-wrap .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    .filter-wrap .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Grade de botões de ação ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-edit    { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover    { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .act-view    { background: #e0f2fe; color: #0284c7; }
    .act-view:hover    { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
    .act-money   { background: #dcfce7; color: #16a34a; }
    .act-money:hover   { box-shadow: 0 4px 12px rgba(22,163,74,.3); }
    .act-del     { background: #fee2e2; color: #dc2626; }
    .act-del:hover     { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Badges (pills) ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok  { background: #dcfce7; color: #15803d; }
    .pill-no  { background: #f1f5f9; color: #64748b; }

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
                            <i class="ri-user-star-line"></i>
                            Gestão de Contadores
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Cadastre, edite e gerencie os profissionais e escritórios contábeis do sistema.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('contadores.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        <a href="{{ route('escritorio-contabils') }}" class="dash-btn dash-btn-light"><i class="ri-building-2-line"></i> Escritórios</a>
                        <a href="{{ route('contadores.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Novo Contador</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ Cards de Estatísticas ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-4">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de Contadores</div>
                                    <div class="st-value">{{ $stats['total'] }}</div>
                                    <div class="st-sub">Profissionais e escritórios</div>
                                </div>
                                <div class="st-icon"><i class="ri-user-star-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Contadores Ativos</div>
                                    <div class="st-value">{{ $stats['ativos'] }}</div>
                                    <div class="st-sub">Com cadastro ativo</div>
                                </div>
                                <div class="st-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Inativos</div>
                                    <div class="st-value">{{ $stats['inativos'] }}</div>
                                    <div class="st-sub">Sem atividade</div>
                                </div>
                                <div class="st-icon"><i class="ri-close-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Filtros de Busca ═══ --}}
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Contadores</h5>
                    </div>
                    <div class="mt-3">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Razão Social / Nome</label>
                                {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do contador...'])!!}
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-file-text-line"></i> CPF / CNPJ</label>
                                {!!Form::tel('cpf_cnpj', '')->attrs(['class' => 'form-control cpf_cnpj', 'placeholder' => '00.000.000/0000-00'])!!}
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-light border px-3" href="{{ route('contadores.index') }}" title="Limpar Filtros" style="border-radius:10px;">
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
                                    <th>Contador</th>
                                    <th>CPF / CNPJ</th>
                                    <th>IE / RG</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 160px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="color:#1f2937;">{{ $item->nome }}</div>
                                        @if($item->nome_fantasia)
                                        <div class="fs-12" style="color:#94a3b8;">{{ $item->nome_fantasia }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $item->cpf_cnpj }}</td>
                                    <td>{{ $item->ie }}</td>
                                    <td>
                                        @if($item->status)
                                        <span class="pill pill-ok"><i class="ri-checkbox-circle-line"></i> Ativo</span>
                                        @else
                                        <span class="pill pill-no"><i class="ri-close-circle-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('contadores.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                <a class="act-btn act-edit" href="{{ route('contadores.edit', [$item->id]) }}" title="Editar Contador">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <a class="act-btn act-view" href="{{ route('contadores.show', [$item->id]) }}" title="Empresas do Contador">
                                                    <i class="ri-building-2-line"></i>
                                                </a>
                                                <a class="act-btn act-money" href="{{ route('contadores.financeiro', [$item->id]) }}" title="Financeiro do Contador">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </a>
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Contador">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum contador encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ Footer ═══ --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> contadores
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
