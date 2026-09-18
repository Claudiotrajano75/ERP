@extends('layouts.app', ['title' => 'Manutenção de Veículos'])

@section('css')
    <style>
        /* ─── Cards de Estatísticas ─── */
        .stat-card {
            border-radius: 14px;
            padding: 18px 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .07);
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            opacity: .22;
        }

        .stat-card.c-blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-card.c-amber {
            background: linear-gradient(135deg, #f59e0b, #b45309);
        }

        .stat-card.c-green {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        .stat-card.c-purple {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        .stat-card .stat-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            opacity: .85;
            margin-bottom: 4px;
        }

        .stat-card .stat-val {
            font-size: 22px;
            font-weight: 800;
            line-height: 1;
        }

        /* ─── Tabela ─── */
        .tb-wrap {
            border-radius: 14px;
            border: 1px solid #eef0f5;
            overflow: hidden;
            background: #fff;
        }

        .tb-wrap table {
            margin-bottom: 0;
        }

        .tb-wrap thead th {
            background: #f8f9fc;
            color: #5a5a7a;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 12px 14px;
            border-bottom: 1px solid #e8eaf6;
        }

        .tb-wrap tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f8;
            font-size: 13px;
            color: #374151;
        }

        .tb-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        .tb-wrap tbody tr:hover {
            background: #fbfbfe;
        }

        .tb-wrap tfoot td {
            background: #f8f9fc;
            font-weight: 700;
            font-size: 13.5px;
            padding: 12px 14px;
            border-top: 2px solid #e8eaf6;
        }

        /* ─── Badges (Pills) ─── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .pill-ok {
            background: #dcfce7;
            color: #15803d;
        }

        .pill-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .pill-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        /* ─── Ações ─── */
        .act-group {
            display: inline-flex;
            gap: 4px;
            align-items: center;
            justify-content: flex-end;
        }

        .act-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border: 1px solid transparent;
            transition: all .15s ease;
            text-decoration: none;
        }

        .act-btn:hover {
            transform: translateY(-1px);
        }

        .act-btn-info {
            background: #f0f9ff;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .act-btn-info:hover {
            background: #0284c7;
            color: #fff;
        }

        .act-btn-warn {
            background: #fffbeb;
            color: #b45309;
            border-color: #fde68a;
        }

        .act-btn-warn:hover {
            background: #f59e0b;
            color: #fff;
        }

        .act-btn-del {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .act-btn-del:hover {
            background: #dc2626;
            color: #fff;
        }

        /* ─── Input Group com Select2 ─── */
        .input-group.flex-nowrap {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: stretch;
        }

        .input-group.flex-nowrap>div {
            flex: 1 1 auto;
            min-width: 0;
        }

        .input-group.flex-nowrap .select2-container {
            width: 100% !important;
        }
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
                                    <i class="ri-tools-fill"></i>
                                    Manutenção de Veículos
                                </h4>
                                <p class="text-muted mb-0 modulo-subtitle fs-13">
                                    Controle ordens de serviço, manutenções preventivas, corretivas e despesas da frota.
                                </p>
                            </div>
                            <div class="d-inline-flex align-items-center gap-2">
                                @can('manutencao_veiculo_create')
                                    <a href="{{ route('manutencao-veiculos.create') }}" class="dash-btn dash-btn-primary">
                                        <i class="ri-add-circle-line"></i> Nova Manutenção
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        <!-- ═══ CARDS DE ESTATÍSTICAS (KPIS) ═══ -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="stat-card c-blue">
                                    <i class="ri-money-dollar-circle-line stat-icon"></i>
                                    <div class="stat-title">Valor Total</div>
                                    <div class="stat-val">R$ {{ __moeda($stats['valor_total'] ?? $data->sum('total')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card c-amber">
                                    <i class="ri-discount-percent-line stat-icon"></i>
                                    <div class="stat-title">Desconto Total</div>
                                    <div class="stat-val">R$
                                        {{ __moeda($stats['total_desconto'] ?? $data->sum('desconto')) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card c-purple">
                                    <i class="ri-time-line stat-icon"></i>
                                    <div class="stat-title">Em Andamento / Aguardando</div>
                                    <div class="stat-val">{{ ($stats['aguardando'] ?? 0) + ($stats['em_manutencao'] ?? 0) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card c-green">
                                    <i class="ri-checkbox-circle-line stat-icon"></i>
                                    <div class="stat-title">Total de Manutenções</div>
                                    <div class="stat-val">{{ $stats['total_manutencoes'] ?? $data->total() }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- ═══ FILTROS GLASS PREMIUM ═══ -->
                        <div class="modulo-glass-filter-premium p-3 mb-4">
                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3 col-12">
                                    <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Fornecedor /
                                        Oficina</label>
                                    {!!Form::select('fornecedor_id', '')
        ->attrs(['class' => 'select2'])
                                    !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Data
                                        Início</label>
                                    {!!Form::date('start_date', '')!!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Data Fim</label>
                                    {!!Form::date('end_date', '')!!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Status</label>
                                    {!!Form::select(
        'estado',
        '',
        [
            '' => 'Todos os Status',
            'aguardando' => 'Aguardando',
            'em_manutencao' => 'Em manutenção',
            'finalizado' => 'Finalizado',
        ]
    )
        ->attrs(['class' => 'form-select'])
                                    !!}
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Veículo</label>
                                    {!!Form::select(
        'veiculo_id',
        '',
        ['' => 'Todos os Veículos'] + ($veiculo != null ? [$veiculo->id => $veiculo->info] : [])
    )
        ->attrs(['class' => 'select2'])
                                    !!}
                                </div>

                                @if(__countLocalAtivo() > 1)
                                                        <div class="col-md-3 col-6">
                                                            <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Local /
                                                                Filial</label>
                                                            {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                    ->attrs(['class' => 'select2'])
                                                            !!}
                                                        </div>
                                @endif

                                <div class="col-md-auto ms-auto d-flex gap-2">
                                    <button class="dash-btn dash-btn-primary" type="submit">
                                        <i class="ri-search-line"></i> Filtrar Manutenção
                                    </button>
                                    <a class="dash-btn dash-btn-light" href="{{ route('manutencao-veiculos.index') }}">
                                        <i class="ri-eraser-line"></i> Limpar
                                    </a>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>

                        <!-- ═══ TABELA DE MANUTENÇÕES ═══ -->
                        <div class="tb-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px;">#</th>
                                            <th>Fornecedor / Oficina</th>
                                            <th>Veículo</th>
                                            <th>Valor Total</th>
                                            <th>Período</th>
                                            <th>Data Cadastro</th>
                                            <th>Status</th>
                                            <th class="text-end" style="width: 140px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>
                                                    <strong class="text-primary fs-12">#{{ $item->numero_sequencial }}</strong>
                                                </td>
                                                <td>
                                                    <strong class="text-dark d-block">{{ $item->fornecedor->info }}</strong>
                                                    @if($item->fornecedor->cpf_cnpj)
                                                        <span class="text-muted fs-11">{{ $item->fornecedor->cpf_cnpj }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fw-semibold text-dark"><i
                                                            class="ri-truck-line text-muted me-1"></i>{{ $item->veiculo->info }}</span>
                                                </td>
                                                <td>
                                                    <strong class="text-dark fs-13">R$ {{ __moeda($item->total) }}</strong>
                                                    @if($item->desconto > 0)
                                                        <small class="text-success d-block fs-11">Desc: R$
                                                            {{ __moeda($item->desconto) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fs-12 d-block text-muted">
                                                        <i
                                                            class="ri-calendar-line me-1"></i>{{ __data_pt($item->data_inicio, 0) }}
                                                        @if($item->data_fim)
                                                            até {{ __data_pt($item->data_fim, 0) }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="fs-12 text-muted">{{ __data_pt($item->created_at) }}</td>
                                                <td>
                                                    @if($item->estado == 'aguardando')
                                                        <span class="pill pill-amber">
                                                            <i class="ri-time-line"></i> Aguardando
                                                        </span>
                                                    @elseif($item->estado == 'em_manutencao')
                                                        <span class="pill pill-info">
                                                            <i class="ri-tools-line"></i> Em manutenção
                                                        </span>
                                                    @else
                                                        <span class="pill pill-ok">
                                                            <i class="ri-checkbox-circle-fill"></i> Finalizado
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('manutencao-veiculos.destroy', $item->id) }}"
                                                        method="post" id="form-{{$item->id}}" class="m-0">
                                                        @method('delete')
                                                        @csrf
                                                        <div class="act-group">
                                                            @can('manutencao_veiculo_view')
                                                                <a class="act-btn act-btn-info"
                                                                    href="{{ route('manutencao-veiculos.show', $item->id) }}"
                                                                    title="Visualizar Detalhes">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            @endcan
                                                            @can('manutencao_veiculo_edit')
                                                                <a class="act-btn act-btn-warn"
                                                                    href="{{ route('manutencao-veiculos.edit', $item->id) }}"
                                                                    title="Editar">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            @endcan
                                                            @can('manutencao_veiculo_delete')
                                                                <button type="button" class="act-btn act-btn-del btn-delete"
                                                                    title="Excluir">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="ri-tools-line fs-36 text-secondary d-block mb-2"></i>
                                                        <p class="mb-0 fs-13">Nenhuma manutenção de veículo encontrada para os
                                                            filtros selecionados.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-uppercase fs-12 fw-bold text-muted">Soma na Página
                                            </td>
                                            <td colspan="5" class="text-primary fw-bold fs-14">R$
                                                {{ __moeda($data->sum('total')) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- ═══ PAGINAÇÃO ═══ -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 pt-2">
                            <small class="text-muted fs-12">
                                Mostrando {{ $data->firstItem() ?? 0 }} a {{ $data->lastItem() ?? 0 }} de
                                {{ $data->total() }} registros
                            </small>
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