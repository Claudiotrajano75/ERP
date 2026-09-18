@extends('layouts.app', ['title' => 'Usuários'])

@section('css')
<style>
    /* --- Cards de Estatísticas (reuso do padrão do dashboard) --- */
    .stat-card {
        border: 0; border-radius: 16px; padding: 18px 20px; height: 100%;
        color: #fff; position: relative; overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after {
        content: ''; position: absolute; top: -44px; right: -44px;
        width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12);
    }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* --- Filtro --- */
    .filter-wrap {
        background: #fff; border: 1px solid #e9ecf3; border-radius: 14px;
        box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px;
    }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
    .filter-wrap .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    .filter-wrap .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* --- Tabela --- */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* --- Grade de botões de ação --- */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn {
        width: 34px; height: 34px; border-radius: 10px; border: 0;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 15px; text-decoration: none; cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-edit { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .act-view { background: #e0f2fe; color: #0284c7; }
    .act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
    .act-profile { background: #dcfce7; color: #16a34a; }
    .act-profile:hover { box-shadow: 0 4px 12px rgba(22,163,74,.3); }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }

    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-no { background: #f1f5f9; color: #64748b; }
    .pill-role { background: #eef0ff; color: #4f46e5; }
    .pill-local { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
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
                            <i class="ri-user-settings-line"></i>
                            Controle de Usuários
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie os colaboradores, atribua permissões e defina locais de acesso.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('usuarios.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('usuarios_create')
                        <a href="{{ route('usuarios.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Novo Usuário</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ Cards de Estatísticas ═══ -->
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de usuários</div>
                                    <div class="st-value">{{ $stats['total'] }}</div>
                                    <div class="st-sub">vinculados à empresa</div>
                                </div>
                                <div class="st-icon"><i class="ri-user-settings-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Administradores</div>
                                    <div class="st-value">{{ $stats['admins'] }}</div>
                                    <div class="st-sub">acesso total</div>
                                </div>
                                <div class="st-icon"><i class="ri-shield-user-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Com permissão</div>
                                    <div class="st-value">{{ $stats['comPermissao'] }}</div>
                                    <div class="st-sub">atribuição de roles</div>
                                </div>
                                <div class="st-icon"><i class="ri-key-2-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Sem local</div>
                                    <div class="st-value">{{ $stats['semLocal'] }}</div>
                                    <div class="st-sub">locais de acesso</div>
                                </div>
                                <div class="st-icon"><i class="ri-map-pin-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros de Busca ═══ -->
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Usuários</h5>
                    </div>
                    <div class="mt-3">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Pesquisar por Nome</label>
                                {!!Form::text('name', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do usuário...'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-light border px-3" href="{{ route('usuarios.index') }}" title="Limpar Filtros" style="border-radius:10px;">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>

                <!-- ═══ Tabela ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>E-mail</th>
                                    <th>Administrador</th>
                                    <th>Nível de Acesso</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Locais Autorizados</th>
                                    @endif
                                    <th class="text-end" style="width: 170px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="rounded-circle border bg-light me-2 shadow-sm" src="{{ $item->img }}"
                                                 alt="{{ $item->name }}" style="width: 36px; height: 36px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold" style="color:#1f2937;">{{ $item->name }}</div>
                                                <div class="fs-12" style="color:#94a3b8;">ID #{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        @if($item->admin)
                                        <span class="pill pill-ok"><i class="ri-checkbox-circle-line"></i> Sim</span>
                                        @else
                                        <span class="pill pill-no"><i class="ri-close-circle-line"></i> Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="pill pill-role">
                                            {{ sizeof($item->roles) > 0 ? $item->roles->first()->description : 'Nenhuma' }}
                                        </span>
                                    </td>
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        @forelse($item->locais as $local)
                                        <span class="pill pill-local my-1">{{ $local->localizacao->descricao }}</span>
                                        @empty
                                        <span style="color:#94a3b8;font-size:12px;">Nenhum local</span>
                                        @endforelse
                                    </td>
                                    @endif
                                    <td class="text-end">
                                        @if(__isAdmin())
                                        <form action="{{ route('usuarios.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('usuarios_edit')
                                                <a class="act-btn act-edit" href="{{ route('usuarios.edit', [$item->id]) }}" title="Editar Usuário">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                <a class="act-btn act-view" href="{{ route('usuarios.show', [$item->id]) }}" title="Logs de acesso">
                                                    <i class="ri-key-2-line"></i>
                                                </a>
                                                <a class="act-btn act-profile" href="{{ route('usuarios.profile', $item->id) }}" title="Ver perfil">
                                                    <i class="ri-user-3-line"></i>
                                                </a>
                                                @can('usuarios_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir Usuário">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    @php $colspan = 5 + (__countLocalAtivo() > 1 ? 1 : 0); @endphp
                                    <td colspan="{{ $colspan }}">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum usuário cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> usuários
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
