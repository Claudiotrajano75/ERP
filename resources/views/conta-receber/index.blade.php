@extends('layouts.app', ['title' => 'Contas a Receber'])

@section('css')
<style>
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    min-height: 105px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.stat-card .stat-icon {
    position: absolute;
    right: 14px;
    bottom: 8px;
    font-size: 52px;
    opacity: .18;
    line-height: 1;
    pointer-events: none;
}
.stat-card .stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    opacity: .88;
}
.stat-card .stat-value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.1;
}
.stat-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
.stat-red    { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); }

/* ─── Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
    padding: 20px !important;
    margin-bottom: 24px;
}
.filtro-premium-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f3f9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.filtro-premium-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.filtro-premium-title i {
    color: #4f46e5;
    margin-right: 6px;
}
.modulo-glass-filter-premium label {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 5px;
}
.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 40px !important;
    border-radius: 9px !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #334155 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}
.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #4f46e5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}
.modulo-glass-filter-premium .btn-limpar {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e2e8f0 !important;
    color: #334155 !important;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar Cliente ─── */
.client-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

/* ─── Grade de Ações ─── */
.act-group { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
.act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 1px solid transparent;
    transition: all .15s ease;
    cursor: pointer;
    text-decoration: none !important;
}
.act-btn:hover { transform: translateY(-1px); }
.act-pay   { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
.act-pay:hover   { background: #059669; color: #fff; box-shadow: 0 3px 8px rgba(5,150,105,0.3); }
.act-edit  { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover  { background: #4f46e5; color: #fff; box-shadow: 0 3px 8px rgba(79,70,229,0.3); }
.act-del   { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
.act-del:hover   { background: #dc2626; color: #fff; box-shadow: 0 3px 8px rgba(220,38,38,0.3); }
.act-boleto{ background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
.act-boleto:hover{ background: #15803d; color: #fff; box-shadow: 0 3px 8px rgba(21,128,61,0.3); }
.act-view-boleto{ background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
.act-view-boleto:hover{ background: #0369a1; color: #fff; box-shadow: 0 3px 8px rgba(3,105,161,0.3); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-hand-coin-line"></i>
                            Contas a Receber
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as contas a receber, controle os prazos de vencimento e emita boletos de cobrança.</p>
                    </div>
                    <div>
                        @can('conta_receber_create')
                        <a href="{{ route('conta-receber.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-circle-line"></i> Nova Conta
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                @if(isset($stats))
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-indigo">
                            <div>
                                <div class="stat-label">Total a Receber</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_integral']) }}</div>
                            </div>
                            <i class="ri-hand-coin-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Total Recebido</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_recebido']) }}</div>
                            </div>
                            <i class="ri-checkbox-circle-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Total Pendente</div>
                                <div class="stat-value mt-1">R$ {{ __moeda($stats['total_pendente']) }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-red">
                            <div>
                                <div class="stat-label">Contas Atrasadas</div>
                                <div class="stat-value mt-1">{{ $stats['total_atrasadas'] }}</div>
                            </div>
                            <i class="ri-alert-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ═══ FILTRO DE PESQUISA ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Contas a Receber
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-user-line"></i> Cliente</label>
                            {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select'])
                            ->options((isset($cliente) && $cliente != null) ? [$cliente->id => $cliente->info] : [])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-flag-line"></i> Status</label>
                            {!!Form::select('status', '', ['' => 'Todas as Contas', '1' => 'Recebidas', '0' => 'Pendentes'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-1 col-6">
                            <label class="form-label"><i class="ri-sort-asc"></i> Ordenar</label>
                            {!!Form::select('ordem', '', ['' => 'Cadastro', '1' => 'Vencimento'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                            {!!Form::select('local_id', '')->options(['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif
                        <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('conta-receber.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap mb-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('conta_receber_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Cliente</th>
                                    <th>Descrição / Origem</th>
                                    @if(__countLocalAtivo() > 1)<th>Local</th>@endif
                                    <th>Valor Integral</th>
                                    <th>Valor Recebido</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Venda</th>
                                    <th class="text-end" style="width: 160px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('conta_receber_delete')
                                    <td>
                                        @if(!$item->status)
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                        @endif
                                    </td>
                                    @endcan
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="client-avatar">
                                                <i class="ri-user-3-line"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark d-block">{{ $item->cliente ? $item->cliente->razao_social : '--' }}</span>
                                                @if($item->cliente)
                                                <span class="text-muted fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $item->descricao }}</span>
                                        @if($item->arquivo)
                                        <a href="{{ route('conta-receber.download-file', [$item->id]) }}" class="badge bg-light text-primary border ms-1" title="Baixar Arquivo Anexo">
                                            <i class="ri-attachment-line"></i> Anexo
                                        </a>
                                        @endif
                                    </td>
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-11">
                                            {{ $item->localizacao ? $item->localizacao->descricao : '--' }}
                                        </span>
                                    </td>
                                    @endif
                                    <td>
                                        <strong class="text-dark fs-14">R$ {{ __moeda($item->valor_integral) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-success fs-13">R$ {{ __moeda($item->valor_recebido) }}</strong>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark">{{ __data_pt($item->data_vencimento, 0) }}</span>
                                        @if(!$item->status && $item->diasAtraso())
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-10 ms-1">
                                            {{ $item->diasAtraso() }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-checkbox-circle-line me-1"></i> Recebido
                                        </span>
                                        @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                            <i class="ri-time-line me-1"></i> Pendente
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->nfce)
                                        <a href="{{ route('nfce.show', [$item->nfce->id]) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                            PDV #{{ $item->nfce->numero_sequencial }}
                                        </a>
                                        @elseif($item->nfe)
                                        <a href="{{ route('nfe.show', [$item->nfe->id]) }}" class="badge bg-dark-subtle text-dark border border-dark-subtle px-2 py-1 fs-11">
                                            Pedido #{{ $item->nfe->numero_sequencial }}
                                        </a>
                                        @else
                                        <span class="text-muted fs-12">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('conta-receber.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @csrf
                                            @method('delete')
                                            <div class="act-group">
                                                @if(!$item->status)
                                                    @can('conta_receber_edit')
                                                    <a href="{{ route('conta-receber.pay', $item) }}" class="act-btn act-pay" title="Receber Conta">
                                                        <i class="ri-hand-coin-line"></i>
                                                    </a>
                                                    <a class="act-btn act-edit" href="{{ route('conta-receber.edit', [$item->id]) }}" title="Editar Conta">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan
                                                    @can('conta_receber_delete')
                                                    <button type="button" class="act-btn act-del btn-delete" title="Excluir Conta">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                @else
                                                    @can('conta_receber_delete')
                                                    <button type="button" class="act-btn act-del btn-delete" title="Excluir Registro">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                @endif

                                                {{-- Botões de Boleto --}}
                                                @if(!$item->boleto && !$item->status)
                                                    @can('boleto_create')
                                                    <a class="act-btn act-boleto" href="{{ route('boleto.create', [$item->id]) }}" title="Gerar Boleto">
                                                        <i class="ri-file-list-2-line"></i>
                                                    </a>
                                                    @endcan
                                                @else
                                                    @can('boleto_view')
                                                        @if($item->boleto)
                                                        <a class="act-btn act-view-boleto" href="{{ route('boleto.show', [$item->id]) }}" title="Visualizar Boleto">
                                                            <i class="ri-file-list-3-fill"></i>
                                                        </a>
                                                        @endif
                                                    @endcan
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('conta_receber_delete') ? 1 : 0) + (__countLocalAtivo() > 1 ? 8 : 7) }}">
                                        <div class="modulo-empty">
                                            <i class="ri-hand-coin-line"></i>
                                            <p>Nenhuma conta a receber encontrada para os filtros aplicados.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ AÇÕES EM LOTE + PAGINAÇÃO ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @can('conta_receber_delete')
                        <form action="{{ route('conta-receber.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="dash-btn dash-btn-danger btn-delete-all" disabled>
                                <i class="ri-delete-bin-line"></i> Remover Selecionados
                            </button>
                        </form>
                        @endcan

                        @can('conta_receber_edit')
                        <form action="{{ route('conta-receber.recebe-select') }}" method="post" id="form-recebe-paga-select" class="m-0">
                            @csrf
                            <button type="button" class="dash-btn dash-btn-primary btn-recebe-paga-all" disabled>
                                <i class="ri-checkbox-circle-line"></i> Receber Selecionados
                            </button>
                        </form>
                        @endcan

                        @can('boleto_create')
                        <form action="{{ route('boleto.create-several') }}" method="get" id="form-gerar-boletos" class="m-0">
                            <button type="submit" class="dash-btn dash-btn-light btn-boleto" disabled>
                                <i class="ri-file-line"></i> Gerar Boletos
                            </button>
                        </form>
                        @endcan
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/delete_selecionados.js"></script>
<script src="/js/boleto.js"></script>
<script src="/js/recebe_paga_selecionados.js"></script>
@endsection