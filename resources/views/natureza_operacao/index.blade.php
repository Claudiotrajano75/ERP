@extends('layouts.app', ['title' => 'Naturezas de Operação'])

@section('css')
<style>
    /* ─── Padrão Oficial ERP Layout Modernization ─── */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card-body {
        padding: 24px !important;
    }

    /* ─── Cabeçalho de Gradiente Premium ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    /* ─── Filtro de Pesquisa Premium ─── */
    .modulo-glass-filter-premium {
        background: #ffffff;
        border: 1px solid #eef0f6 !important;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
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
        color: #5572f5;
        margin-right: 6px;
    }

    .modulo-glass-filter-premium label {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8c8ca6 !important;
        margin-bottom: 6px !important;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .modulo-glass-filter-premium .form-control,
    .modulo-glass-filter-premium .form-select {
        height: 38px !important;
        border-radius: 8px !important;
        border: 1px solid #dcdce9 !important;
        font-size: 13px !important;
        padding: 6px 12px !important;
        color: #374151 !important;
        background-color: #fcfdfe !important;
        transition: all 0.2s ease;
    }

    .modulo-glass-filter-premium .form-control:focus,
    .modulo-glass-filter-premium .form-select:focus {
        border-color: #5572f5 !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
    }

    .modulo-glass-filter-premium .btn-pesquisar {
        background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
        border: none !important;
        color: #fff !important;
        font-weight: 600 !important;
        height: 38px;
        border-radius: 8px !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .modulo-glass-filter-premium .btn-pesquisar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
    }

    .modulo-glass-filter-premium .btn-limpar {
        background: #f1f3f9 !important;
        border: 1px solid #e2e5ec !important;
        color: #5a5a7a !important;
        font-weight: 600 !important;
        height: 38px;
        border-radius: 8px !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .modulo-glass-filter-premium .btn-limpar:hover {
        background: #e8ebf3 !important;
        color: #302b63 !important;
    }

    /* ─── Tabela Premium ─── */
    .modulo-table-wrap {
        border-radius: 12px;
        border: 1px solid #eef0f5;
        overflow: hidden;
    }

    .modulo-table-wrap table {
        margin-bottom: 0;
    }

    .modulo-table-wrap thead th {
        background: #f8f9fc;
        color: #5a5a7a;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 16px;
        border-bottom: 2px solid #e8eaf6;
    }

    .modulo-table-wrap tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f8;
        font-size: 13px;
        color: #374151;
    }

    .modulo-table-wrap tbody tr:hover {
        background: #fafbff;
    }

    .modulo-table-wrap tbody tr:last-child td {
        border-bottom: none;
    }

    .modulo-action-group {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: nowrap;
    }

    .modulo-action-group .btn {
        border-radius: 8px;
        padding: 5px 9px;
        font-size: 12px;
        transition: all 0.15s ease;
    }

    .modulo-action-group .btn:hover {
        transform: translateY(-1px);
    }

    /* ─── Botões ─── */
    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-sm {
        padding: 6px 12px !important;
        font-size: 12px !important;
        border-radius: 8px !important;
    }

    .btn-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
    }

    .btn-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
    }

    .btn-warning {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
        color: #fff !important;
    }

    .btn-warning:hover {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
        transform: translateY(-1px);
    }

    .btn-danger {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #fff !important;
    }

    .btn-danger:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        transform: translateY(-1px);
    }

    /* ─── Empty State ─── */
    .modulo-empty {
        padding: 48px 20px;
        text-align: center;
    }

    .modulo-empty i {
        font-size: 48px;
        color: #c5cae9;
        margin-bottom: 12px;
        display: block;
    }

    .modulo-empty p {
        color: #9e9eb8;
        font-size: 14px;
        margin: 0;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-settings-4-line"></i>
                                Naturezas de Operação
                            </h4>
                            <p class="text-white-50 mb-0 modulo-subtitle fs-13">
                                Cadastre e gerencie as regras tributárias e fiscais para emissão de notas.
                            </p>
                        </div>
                        <div>
                            @can('natureza_operacao_create')
                            <a href="{{ route('natureza-operacao.create') }}" class="btn btn-success btn-sm px-3 shadow-sm">
                                <i class="ri-add-circle-line align-middle me-1"></i> Nova Natureza
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ KPI CARDS ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-6">
                            <div class="card widget-icon-box text-bg-info mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Naturezas</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $stats['total'] ?? $data->total() }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Cadastradas no sistema</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-file-list-3-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="card widget-icon-box text-bg-success mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Padrão do Sistema</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $stats['padrao'] ?? 0 }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Utilizada como padrão</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card widget-icon-box text-bg-warning mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h4 class="text-uppercase fs-12 mt-0 text-white-50">Sobrescreve CFOP</h4>
                                            <h3 class="my-2 text-white fs-18">{{ $stats['sobrescreve'] ?? 0 }}</h3>
                                            <p class="mb-0 text-white-50 fs-11">Sobrescreve dados do produto</p>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                                <i class="ri-swap-box-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ FILTROS DE BUSCA PREMIUM ═══ -->
                    <div class="modulo-glass-filter-premium">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Naturezas de Operação
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label"><i class="ri-file-text-line"></i> Descrição</label>
                                {!!Form::text('descricao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite a descrição da operação...'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-star-line"></i> Natureza Padrão</label>
                                {!!Form::select('padrao', '', ['' => 'Todos', '1' => 'Sim (Padrão)', '0' => 'Não'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-limpar px-3" href="{{ route('natureza-operacao.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line me-1"></i> Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA PREMIUM ═══ -->
                    <div class="modulo-table-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Descrição da Operação</th>
                                        <th>CFOPs Padrão</th>
                                        <th>CST / CSOSN</th>
                                        <th class="text-center">Padrão</th>
                                        <th class="text-center">Sobrescreve CFOP</th>
                                        <th class="text-end" style="width: 120px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13 d-block">
                                                {{ $item->descricao }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($item->cfop_estadual)
                                                    <span class="badge bg-light text-dark border px-2 py-0.5 fs-11" title="Estadual">
                                                        Est: {{ $item->cfop_estadual }}
                                                    </span>
                                                @endif
                                                @if($item->cfop_outro_estado)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 fs-11" title="Interestadual">
                                                        Inter: {{ $item->cfop_outro_estado }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">
                                                {{ $item->cst_csosn ? $item->cst_csosn : '--' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($item->padrao)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Sim
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                                    <i class="ri-close-line me-1"></i> Não
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->sobrescrever_cfop)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Sim
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                                    <i class="ri-close-line me-1"></i> Não
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('natureza-operacao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="modulo-action-group justify-content-end">
                                                    @can('natureza_operacao_edit')
                                                    <a class="btn btn-warning btn-sm text-white" href="{{ route('natureza-operacao.edit', [$item->id]) }}" title="Editar Natureza">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan

                                                    @can('natureza_operacao_delete')
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Natureza">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="modulo-empty">
                                                <i class="ri-file-settings-line"></i>
                                                <p>Nenhuma natureza de operação encontrada.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span class="text-muted fs-13">
                            Total de Registros: <strong>{{ $data->total() }}</strong>
                        </span>
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