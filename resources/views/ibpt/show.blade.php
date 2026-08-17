@extends('layouts.app', ['title' => 'Tabela IBPT - ' . $item->uf])

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
    .table-responsive {
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
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-percent-line"></i>
                            Tabela IBPT — Estado: {{ $item->uf }}
                        </h4>
                        <p class="modulo-subtitle">
                            Versão: <strong>{{ $item->versao }}</strong> &nbsp;|&nbsp; Total de {{ number_format($item->itens_count ?? 0, 0, ',', '.') }} alíquotas cadastradas.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('ibpt.index') }}" class="btn btn-light btn-sm text-dark">
                            <i class="ri-arrow-left-line me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- ═══ FILTRO DE PESQUISA ═══ -->
                <div class="col-lg-12 mb-3">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row align-items-end g-2">
                        <div class="col-md-6 col-12">
                            <label class="form-label"><i class="ri-search-line me-1"></i> Pesquisar por Código NCM ou Descrição</label>
                            {!!Form::text('descricao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o NCM ou termo da descrição...'])!!}
                        </div>
                        <div class="col-md-6 col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                            <a id="clear-filter" class="btn btn-danger" href="{{ route('ibpt.show', $item->id) }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA DE ALÍQUOTAS ═══ -->
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Código NCM</th>
                                    <th>Descrição da Mercadoria</th>
                                    <th>Nacional / Fed.</th>
                                    <th>Importado / Fed.</th>
                                    <th>Estadual</th>
                                    <th>Municipal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $i)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle fs-12 font-monospace">
                                            <i class="ri-barcode-line me-1"></i>{{ $i->codigo }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-medium">{{ $i->descricao }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $i->nacional_federal }}%</span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $i->importado_federal }}%</span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $i->estadual }}%</span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $i->municipal }}%</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="ri-inbox-line fs-24 d-block mb-1 text-muted"></i>
                                        Nenhuma alíquota encontrada para esta pesquisa.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ PAGINAÇÃO ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        <span class="text-muted fs-12">Exibindo {{ $data->count() }} de {{ $data->total() }} registros</span>
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
