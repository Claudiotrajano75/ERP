@extends('layouts.app', ['title' => 'NFC-e — Todas as Empresas'])

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
        padding: 6px 10px !important;
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
        padding: 14px 16px !important;
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
        padding: 12px 16px !important;
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
        padding: 5px 10px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: none !important;
        border: 1px solid transparent;
    }

    .bg-success-subtle {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
        border-color: #a7f3d0 !important;
    }

    .bg-danger-subtle {
        background-color: #fef2f2 !important;
        color: #991b1b !important;
        border-color: #fecaca !important;
    }

    .bg-warning-subtle {
        background-color: #fffbeb !important;
        color: #92400e !important;
        border-color: #fde68a !important;
    }

    .bg-info-subtle {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border-color: #bae6fd !important;
    }

    .bg-light-subtle {
        background-color: #f8fafc !important;
        color: #475569 !important;
        border-color: #e2e8f0 !important;
    }

    .modulo-action-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
        flex-wrap: nowrap;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">

            <!-- ═══ CABEÇALHO COM GRADIENTE PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-shopping-bag-3-line"></i> NFC-e — Todas as Empresas
                        </h4>
                        <p class="modulo-subtitle">
                            Visão global e gerenciamento de todas as Notas Fiscais de Consumidor Eletrônicas do sistema.
                        </p>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de NFC-e</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cupons gerados</p>
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

                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Autorizadas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ number_format($stats['aprovadas'] ?? 0, 0, ',', '.') }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cupons aprovados</p>
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

                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-danger mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Canceladas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ number_format($stats['canceladas'] ?? 0, 0, ',', '.') }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cupons cancelados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-close-circle-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total Emitido</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">R$ {{ number_format($stats['valor_total'] ?? 0, 2, ',', '.') }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Soma dos cupons aprovados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FILTROS AVANÇADOS ═══ -->
                <div class="col-lg-12 mb-3">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-building-line me-1"></i> Empresa</label>
                            {!!Form::select('empresa', '', ['' => 'Todas as Empresas'] + ($empresas ?? []))
                            ->attrs(['class' => 'select2 form-select'])
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line me-1"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-toggle-line me-1"></i> Status</label>
                            {!!Form::select('estado', '', [
                                '' => 'Todos os Estados',
                                'aprovado' => 'Autorizadas / Aprovadas',
                                'cancelado' => 'Canceladas',
                                'rejeitado' => 'Rejeitadas',
                                'novo' => 'Novas / Pendentes'
                            ])->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12 d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                            <a id="clear-filter" class="btn btn-danger px-3" href="{{ route('nfce-all') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA DE DADOS ═══ -->
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th>Empresa Emitente</th>
                                    <th>Cliente / Consumidor</th>
                                    <th>Nº NFC-e</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                    <th>Ambiente</th>
                                    <th>Emissão</th>
                                    <th class="text-end" style="min-width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $item->empresa->nome ?? 'Empresa #' . $item->empresa_id }}</span>
                                            <span class="text-muted fs-11 font-monospace">{{ $item->empresa->cpf_cnpj ?? '--' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark">{{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome ?: 'Consumidor Final') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-subtle text-dark font-monospace fs-12">
                                            #{{ $item->numero ?: '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            R$ {{ number_format($item->total, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle">
                                            <i class="ri-checkbox-circle-fill"></i> Aprovado
                                        </span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge bg-danger-subtle">
                                            <i class="ri-close-circle-fill"></i> Cancelado
                                        </span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle">
                                            <i class="ri-error-warning-fill"></i> Rejeitado
                                        </span>
                                        @else
                                        <span class="badge bg-info-subtle">
                                            <i class="ri-time-fill"></i> Novo
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->ambiente == 2 ? 'bg-warning-subtle' : 'bg-success-subtle' }}">
                                            {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">
                                            {{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('nfce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @if($item->estado == 'aprovado')
                                                <a class="btn btn-primary btn-sm text-white" title="Imprimir Cupom NFC-e" target="_blank" href="{{ route('nfce.imprimir', [$item->id]) }}">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                <button title="Cancelar NFC-e" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                @endif

                                                @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                <button title="Detalhes do Retorno SEFAZ" type="button" class="btn btn-dark btn-sm text-white" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                    <i class="ri-information-line"></i>
                                                </button>
                                                @endif

                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                <a title="Visualizar XML Temporário" class="btn btn-light btn-sm text-dark" href="{{ route('nfce.xml-temp', $item->id) }}">
                                                    <i class="ri-file-code-line"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="ri-inbox-line fs-24 d-block mb-1 text-muted"></i>
                                        Nenhum cupom NFC-e encontrado para os filtros selecionados.
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
                        <span class="text-muted fs-12">Exibindo {{ $data->count() }} de {{ $data->total() }} cupons NFC-e</span>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar NFCe -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCancelarLabel">Cancelar NFC-e <strong class="ref-numero text-danger"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label required">Motivo da Justificativa (mín. 15 caracteres)</label>
                        {!!Form::text('motivo-cancela', '')->attrs(['class' => 'form-control', 'placeholder' => 'Descreva a justificativa do cancelamento...'])->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm">Confirmar Cancelamento</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n\nChave: " + chave + "\n";
            swal("Nota Rejeitada", text, "warning");
        } else {
            let text = "Chave: " + chave + "\nRecibo: " + recibo + "\n";
            swal("Nota Autorizada", text, "success");
        }
    }
</script>
<script type="text/javascript" src="/js/nfce_transmitir.js"></script>
@endsection
