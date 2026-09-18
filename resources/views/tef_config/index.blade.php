@extends('layouts.app', ['title' => 'Configuração de TEF'])

@section('css')
<style>
/* ─── Botões de Ação Squircle ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end; }
.act-btn { 
    width: 34px; 
    height: 34px; 
    border-radius: 10px; 
    border: 1px solid transparent; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 15px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: all .2s ease; 
    padding: 0;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover { background: #e0e7ff; color: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,.2); }
.act-add  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.act-add:hover  { background: #dcfce7; color: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.2); }
.act-del  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover  { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; bottom: 0;
    width: 4px;
}
.stat-indigo::before  { background: linear-gradient(180deg, #4f46e5, #818cf8); }
.stat-emerald::before { background: linear-gradient(180deg, #059669, #34d399); }
.stat-rose::before    { background: linear-gradient(180deg, #e11d48, #fb7185); }

.stat-indigo .stat-icon  { background: #eef2ff; color: #4f46e5; }
.stat-emerald .stat-icon { background: #ecfdf5; color: #059669; }
.stat-rose .stat-icon    { background: #fff1f2; color: #e11d48; }

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
}
.stat-value {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar do Usuário ─── */
.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #f0f4ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

/* ─── Badges de Status ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-active { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-bank-card-line"></i>
                                Configurações de TEF
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie as credenciais de TEF (Transferência Eletrônica de Fundos) integradas aos operadores e pontos de venda.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('config-geral.create') }}" class="dash-btn dash-btn-light">
                                <i class="ri-settings-3-line"></i> Configurações Gerais
                            </a>
                            <a href="{{ route('tef-config.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Nova Configuração TEF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-12">
                            <div class="stat-card stat-indigo">
                                <div>
                                    <div class="stat-label">Terminais Configurados</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-terminal-box-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-emerald">
                                <div>
                                    <div class="stat-label">Terminais Ativos</div>
                                    <div class="stat-value mt-1">{{ $stats['ativos'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-rose">
                                <div>
                                    <div class="stat-label">Terminais Inativos</div>
                                    <div class="stat-value mt-1">{{ $stats['inativos'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-close-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Operador / Usuário</th>
                                        <th>CNPJ Vinculado</th>
                                        <th>Ponto de Venda (PDV)</th>
                                        <th>Status do Terminal</th>
                                        <th class="text-end" style="width: 140px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar">
                                                    <i class="ri-user-3-line"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block fs-13">{{ $item->usuario ? $item->usuario->name : 'Geral / Não atribuído' }}</span>
                                                    <span class="text-muted fs-11">{{ $item->usuario ? $item->usuario->email : '--' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold">{{ $item->cnpj }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">
                                                <i class="ri-store-2-line text-muted me-1"></i> {{ $item->pdv }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->status)
                                                <span class="modulo-badge modulo-badge-active">
                                                    <i class="ri-checkbox-circle-fill"></i> Ativo
                                                </span>
                                            @else
                                                <span class="modulo-badge modulo-badge-inactive">
                                                    <i class="ri-close-circle-line"></i> Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('tef-config.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @if($item->status)
                                                    <button type="button" class="act-btn act-add btn-test-tef" 
                                                            data-empresa="{{ $item->empresa_id }}" 
                                                            data-usuario="{{ $item->usuario_id }}" 
                                                            title="Testar Conexão TEF">
                                                        <i class="ri-wifi-line"></i>
                                                    </button>
                                                    @endif
                                                    <a class="act-btn act-edit" href="{{ route('tef-config.edit', [$item->id]) }}" title="Editar Configuração">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <button type="button" class="act-btn act-del btn-delete" title="Remover Configuração">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="modulo-empty">
                                                <i class="ri-bank-card-line"></i>
                                                <p>Nenhuma configuração de TEF encontrada.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).on('click', '.btn-test-tef', function () {
        let empresa_id = $(this).data('empresa');
        let usuario_id = $(this).data('usuario');
        let btn = $(this);

        btn.prop('disabled', true);
        
        $.get(path_url + "api/tef/verifica-ativo", {
            empresa_id: empresa_id,
            usuario_id: usuario_id,
        })
        .done((data) => {
            swal("Sucesso", "Terminal TEF Conectado e Ativo!", "success");
        })
        .fail((e) => {
            let msg = e.responseJSON ? (e.responseJSON.message || e.responseJSON) : "Erro na comunicação com o TEF";
            swal("Atenção", msg, "error");
        })
        .always(() => {
            btn.prop('disabled', false);
        });
    });
</script>
@endsection
