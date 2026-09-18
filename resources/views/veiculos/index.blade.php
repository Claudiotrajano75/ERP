@extends('layouts.app', ['title' => 'Veículos'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card { border-radius: 14px; padding: 18px 20px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.07); transition: transform .2s ease; }
.stat-card:hover { transform: translateY(-2px); }
.stat-card .stat-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 42px; opacity: .22; }
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-teal   { background: linear-gradient(135deg, #06b6d4, #0e7490); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.stat-card .stat-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; opacity: .85; margin-bottom: 4px; }
.stat-card .stat-val   { font-size: 22px; font-weight: 800; line-height: 1; }

/* ─── Filtro Padronizado ─── */
.modulo-glass-filter-premium { background: #ffffff; border: 1px solid #e8ecf4; border-radius: 14px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); margin-bottom: 22px; }
.modulo-glass-filter-premium label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 6px; }
.modulo-glass-filter-premium .form-control, .modulo-glass-filter-premium .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; }
.modulo-glass-filter-premium .form-control:focus, .modulo-glass-filter-premium .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de Ações ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; }
.act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef0ff; color: #4f46e5; }
.act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
.act-del { background: #fee2e2; color: #dc2626; }
.act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }

.badge-plate { font-family: monospace; font-weight: 800; font-size: 12px; letter-spacing: 0.5px; background: #eef0ff; color: #3730a3; border: 1px solid #c7d2fe; border-radius: 6px; padding: 3px 8px; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                
                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-car-line"></i>
                                Veículos & Frotas
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie os veículos da frota, placas, dados de ANTT/RNTRC, motoristas e características dos veículos.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @can('veiculos_create')
                            <a href="{{ route('veiculos.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Novo Veículo
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-12">
                            <div class="stat-card c-blue">
                                <i class="ri-car-line stat-icon"></i>
                                <div class="stat-title">Total na Frota</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Veículos Ativos</div>
                                <div class="stat-val">{{ $stats['ativos'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card c-amber">
                                <i class="ri-close-circle-line stat-icon"></i>
                                <div class="stat-title">Veículos Inativos</div>
                                <div class="stat-val">{{ $stats['inativos'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS PADRONIZADOS ═══ -->
                    <div class="modulo-glass-filter-premium">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6 col-12">
                                <label for="placa"><i class="ri-search-line me-1"></i> Pesquisar por Placa, Modelo ou Proprietário</label>
                                {!!Form::text('placa', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite a placa, modelo ou proprietário...'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="status"><i class="ri-toggle-line me-1"></i> Status</label>
                                {!!Form::select('status', '', ['' => 'Todos os Status', '1' => 'Ativos', '0' => 'Inativos'])->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-3 col-6 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('veiculos.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i> Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA PREMIUM ═══ -->
                    <div class="tb-wrap mb-3">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Placa / UF</th>
                                        <th>Marca / Modelo</th>
                                        <th>Tipo / Rodado</th>
                                        <th>Renavam</th>
                                        <th>Motorista / Resp.</th>
                                        <th>Proprietário</th>
                                        <th>Status</th>
                                        <th class="text-end" style="width: 110px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span class="badge-plate">{{ $item->placa }}</span>
                                            <span class="badge bg-light text-secondary ms-1">{{ $item->uf ?: '--' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $item->modelo ?: '--' }}</div>
                                            <small class="text-muted">{{ $item->marca ?: '--' }} @if($item->cor) • {{ $item->cor }} @endif</small>
                                        </td>
                                        <td>
                                            <div class="fs-12 fw-medium text-dark">{{ \App\Models\Veiculo::getTipo($item->tipo) ?: '--' }}</div>
                                            <small class="text-muted">{{ \App\Models\Veiculo::getTipoRodado($item->tipo_rodado) ?: '' }}</small>
                                        </td>
                                        <td class="text-muted fs-12">{{ $item->renavam ?: '--' }}</td>
                                        <td>
                                            @if($item->funcionario)
                                            <span class="text-dark fw-medium"><i class="ri-user-line me-1 text-primary"></i>{{ $item->funcionario->nome }}</span>
                                            @else
                                            <span class="text-muted fs-12">Não informado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-dark fs-13">{{ $item->proprietario_nome ?: '--' }}</div>
                                            <small class="text-muted">{{ $item->proprietario_documento ?: '' }}</small>
                                        </td>
                                        <td>
                                            @if($item->status)
                                            <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Ativo</span>
                                            @else
                                            <span class="pill pill-no"><i class="ri-close-circle-fill"></i> Inativo</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('veiculos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @can('veiculos_edit')
                                                    <a class="act-btn act-edit" title="Editar Veículo" href="{{ route('veiculos.edit', [$item->id]) }}">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan
                                                    
                                                    @can('veiculos_delete')
                                                    <button type="button" title="Excluir Veículo" class="act-btn act-del btn-delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="ri-car-line fs-24 d-block mb-1"></i>
                                            Nenhum veículo encontrado.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

