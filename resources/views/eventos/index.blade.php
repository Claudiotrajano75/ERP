@extends('layouts.app', ['title' => 'Eventos de Folha'])

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

/* ─── Avatar do Evento ─── */
.event-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.event-avatar-soma { background: #dcfce7; color: #16a34a; }
.event-avatar-diminui { background: #fee2e2; color: #dc2626; }

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
.modulo-badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

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
                                <i class="ri-calendar-event-line"></i>
                                Tabela de Eventos de Folha
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Cadastre proventos (soma) e descontos (diminui) mensais para controle e apuração da folha de pagamento.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('funcionarios.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-team-line"></i> Funcionários
                            </a>
                            <a href="{{ route('evento-funcionarios.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Novo Evento
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
                                    <div class="stat-label">Total de Eventos Cadastrados</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-calendar-event-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-emerald">
                                <div>
                                    <div class="stat-label">Proventos (Soma no Salário)</div>
                                    <div class="stat-value mt-1">{{ $stats['proventos'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-add-circle-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-rose">
                                <div>
                                    <div class="stat-label">Descontos (Diminui no Salário)</div>
                                    <div class="stat-value mt-1">{{ $stats['descontos'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-indeterminate-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTRO DE BUSCA ═══ -->
                    <div class="modulo-glass-filter-premium mb-4">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Eventos de Folha
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-8 col-12">
                                <label class="form-label"><i class="ri-calendar-event-line"></i> Pesquisar por Nome do Evento</label>
                                {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do evento (Ex: Insalubridade, Vale Transporte)...'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('evento-funcionarios.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                            </div>
                                        </th>
                                        <th>Nome do Evento</th>
                                        <th>Tipo Recorrência</th>
                                        <th>Método Entrada</th>
                                        <th>Operação / Efeito</th>
                                        <th>Tipo de Valor</th>
                                        <th>Status</th>
                                        <th class="text-end" style="width: 120px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="event-avatar {{ $item->condicao == 'soma' ? 'event-avatar-soma' : 'event-avatar-diminui' }}">
                                                    <i class="{{ $item->condicao == 'soma' ? 'ri-add-line' : 'ri-subtract-line' }}"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block fs-13">{{ $item->nome }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-11 fw-semibold">
                                                {{ strtoupper($item->tipo) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-11">
                                                {{ strtoupper($item->metodo) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->condicao == 'soma')
                                                <span class="modulo-badge modulo-badge-success">
                                                    <i class="ri-add-circle-line"></i> Soma (Provento)
                                                </span>
                                            @else
                                                <span class="modulo-badge modulo-badge-danger">
                                                    <i class="ri-indeterminate-circle-line"></i> Diminui (Desconto)
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ strtoupper($item->tipo_valor) }}</span>
                                        </td>
                                        <td>
                                            @if($item->ativo)
                                                <span class="modulo-badge modulo-badge-success">
                                                    <i class="ri-check-line"></i> Ativo
                                                </span>
                                            @else
                                                <span class="modulo-badge modulo-badge-danger">
                                                    <i class="ri-close-line"></i> Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('evento-funcionarios.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    <a class="act-btn act-edit" href="{{ route('evento-funcionarios.edit', [$item->id]) }}" title="Editar Evento">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <button type="button" class="act-btn act-del btn-delete" title="Excluir Evento">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="modulo-empty">
                                                <i class="ri-calendar-event-line"></i>
                                                <p>Nenhum evento de folha cadastrado até o momento.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ FOOTER & PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                        <div>
                            <form action="{{ route('evento-funcionarios.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                                @method('delete')
                                @csrf
                                <button type="button" class="dash-btn dash-btn-danger btn-delete-all" disabled>
                                    <i class="ri-delete-bin-line me-1"></i> Remover Selecionados
                                </button>
                            </form>
                        </div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection