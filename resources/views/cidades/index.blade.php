@extends('layouts.app', ['title' => 'Cidades'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05) !important;
    }

    .card-body {
        padding: 24px !important;
    }

    /* Cabeçalho de Gradiente Premium */
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

    /* Formulários de Filtro */
    .form-control, select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .form-control:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 13px !important;
        margin-bottom: 6px !important;
    }

    /* Botões */
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

    .btn-primary {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
    }

    .btn-primary:hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
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
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
    }

    /* Tabelas */
    .table-responsive-sm {
        border-radius: 12px;
        overflow-x: auto !important;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table {
        margin-bottom: 0 !important;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        padding: 14px 20px !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-top: none !important;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table tbody td {
        padding: 14px 20px !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        color: #334155 !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
    }

    .table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Badges Modernizados */
    .badge {
        padding: 6px 12px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: none !important;
        border: 1px solid transparent;
    }

    .bg-primary-subtle {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border-color: #c7d2fe !important;
    }

    .bg-info-subtle {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border-color: #bae6fd !important;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card">
            <!-- Cabeçalho com Gradiente Premium -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-map-pin-user-line"></i> Gestão de Cidades & Municípios
                        </h4>
                        <p class="modulo-subtitle">
                            Cadastre, edite e gerencie a tabela oficial de municípios e códigos IBGE.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('cidades.create') }}" class="btn btn-success">
                            <i class="ri-add-circle-fill"></i> Nova Cidade
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <!-- ═══ KPI CARDS (RESUMO) ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Cidades</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Municípios cadastrados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-map-pin-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-primary mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Estados Atendidos</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['estados'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Unidades Federativas</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-government-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="col-lg-12 mb-3">
                    {!! Form::open()->fill(request()->all())->get() !!}
                    <div class="row align-items-end g-2">
                        <div class="col-md-5 col-12">
                            <label class="form-label"><i class="ri-search-line me-1"></i> Nome do Município</label>
                            {!! Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da cidade...']) !!}
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-map-pin-line me-1"></i> UF (Estado)</label>
                            {!! Form::text('uf', '')->attrs(['class' => 'form-control text-uppercase', 'placeholder' => 'Ex: SP, RJ, MG...', 'maxlength' => 2]) !!}
                        </div>
                        <div class="col-md-4 col-12 d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                            <a id="clear-filter" class="btn btn-danger px-3" href="{{ route('cidades.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="col-md-12 mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th>Nome da Cidade</th>
                                    <th>UF</th>
                                    <th>Código IBGE</th>
                                    <th class="text-end" style="width: 120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark fs-13">
                                            <i class="ri-map-pin-fill text-danger me-1"></i> {{ $item->nome }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle fs-12">
                                            {{ $item->uf }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle font-monospace fs-12">
                                            {{ $item->codigo }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('cidades.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('cidades.edit', [$item->id]) }}" title="Editar Cidade">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="ri-inbox-line fs-24 d-block mb-1 text-muted"></i>
                                        Nenhuma cidade encontrada.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        <span class="text-muted fs-12">Exibindo {{ $data->count() }} de {{ $data->total() }} cidades</span>
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
