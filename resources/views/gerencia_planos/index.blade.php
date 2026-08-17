@extends('layouts.app', ['title' => 'Gerenciar Planos'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página de Gerenciar Planos */
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

    /* Formulários de Filtro e Modais */
    .form-control, .form-select, select, input[type="text"], input[type="tel"] {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .form-control:focus, .form-select:focus, select:focus {
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

    /* Badges / Pills */
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

    .bg-success-subtle {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }

    .bg-danger-subtle {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }

    .bg-primary-subtle {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border-color: #c7d2fe !important;
    }

    /* Modal Styling */
    .modal-content {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden;
    }

    .modal-header {
        background-color: #f8fafc !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        padding: 20px 24px !important;
    }

    .modal-title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
    }

    .modal-body {
        padding: 24px !important;
    }

    .modal-footer {
        background-color: #f8fafc !important;
        border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
        padding: 16px 24px !important;
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
                            <i class="ri-exchange-funds-line"></i> Atribuição e Gestão de Planos
                        </h4>
                        <p class="modulo-subtitle">
                            Gerencie assinaturas ativas, datas de expiração e atribua planos diretamente às empresas.
                        </p>
                    </div>
                    <div>
                        <button class="btn btn-success btn-cad" data-bs-toggle="modal" data-bs-target="#modal-cad">
                            <i class="ri-add-circle-fill"></i> Atribuir Plano
                        </button>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Atribuições</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['total'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Contratos registrados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-exchange-funds-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Planos Vigentes</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['ativas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Dentro do prazo</p>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Planos Expirados</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['expiradas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Necessitam renovação</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-calendar-close-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Valor Total</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ __moeda($stats['valor_total'] ?? 0) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Total contratado</p>
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

                <!-- Filtros -->
                <div class="col-lg-12 mb-3">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row align-items-end g-2">
                        <div class="col-md-6 col-12">
                            <label class="form-label"><i class="ri-building-line me-1"></i> Filtrar por Empresa</label>
                            {!!Form::select('empresa', '', $empresa ? [$empresa->id => $empresa->info] : [])
                            ->attrs(['class' => 'select2 form-select', 'id' => 'inp-empresa_filtro_id'])
                            !!}
                        </div>
                        <div class="col-md-6 col-12 d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                            <a id="clear-filter" class="btn btn-danger px-3" href="{{ route('gerenciar-planos.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <div class="col-md-12 mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Plano</th>
                                    <th>Valor</th>
                                    <th>Forma de Pagamento</th>
                                    <th>Data Cadastro</th>
                                    <th>Data Expiração</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark fs-13">
                                            <i class="ri-building-line text-primary me-1"></i> {{ $item->empresa ? $item->empresa->info : '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle fs-12">
                                            {{ $item->plano ? $item->plano->nome : '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ __moeda($item->valor) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-12">{{ $item->forma_pagamento }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ __data_pt($item->created_at, 1) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium fs-12">{{ __data_pt($item->data_expiracao, 0) }}</span>
                                    </td>
                                    <td>
                                        @if(strtotime($item->data_expiracao) >= strtotime(date('Y-m-d')))
                                            <span class="badge bg-success-subtle"><i class="ri-checkbox-circle-line"></i> Vigente</span>
                                        @else
                                            <span class="badge bg-danger-subtle"><i class="ri-close-circle-line"></i> Expirado</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('gerenciar-planos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir Atribuição">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="ri-inbox-line fs-24 d-block mb-1 text-muted"></i>
                                        Nenhuma atribuição de plano encontrada.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

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

<div class="modal fade" id="modal-cad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post" action="{{ route('gerenciar-planos.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="ri-exchange-funds-line text-primary"></i> Atribuir Plano à Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-building-line me-1"></i> Empresa</label>
                        {!!Form::select('empresa_atribuir', '')
                        ->required()
                        ->attrs(['class' => 'select2 empresa', 'id' => 'inp-empresa_atribuir'])
                        !!}
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-vip-diamond-line me-1"></i> Plano</label>
                        <select required id="plano" name="plano_id" class="form-select select2">
                            <option value="">Selecione o plano</option>
                            @foreach($planos as $p)
                            <option value="{{ $p->id }}" data-valor="{{ $p->valor }}">{{ $p->nome }} - R$ {{ __moeda($p->valor)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-bank-card-line me-1"></i> Forma de Pagamento</label>
                        {!!Form::select('forma_pagamento', '', \App\Models\Plano::formasPagamento())
                        ->required()
                        ->attrs(['class' => 'select2 form-select'])
                        !!}
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-money-dollar-circle-line me-1"></i> Valor</label>
                        {!!Form::tel('valor', '')
                        ->required()
                        ->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor', 'placeholder' => '0,00'])
                        !!}
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label required"><i class="ri-checkbox-circle-line me-1"></i> Status de Pagamento</label>
                        {!!Form::select('status_pagamento', '', \App\Models\FinanceiroPlano::statusDePagamentos())
                        ->required()
                        ->attrs(['class' => 'select2 form-select'])
                        ->value('recebido')
                        !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success px-4">
                    <i class="ri-save-line me-1"></i> Salvar Atribuição
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        setTimeout(() => {
            $("#modal-cad .empresa").select2({
                minimumInputLength: 2,
                language: "pt-BR",
                placeholder: "Digite para buscar a empresa",
                width: "100%",
                dropdownParent: $('#modal-cad'),
                ajax: {
                    cache: true,
                    url: path_url + "api/empresas/find-all",
                    dataType: "json",
                    data: function (params) {
                        return { pesquisa: params.term };
                    },
                    processResults: function (response) {
                        var results = [];
                        $.each(response, function (i, v) {
                            results.push({ id: v.id, text: v.info, value: v.id });
                        });
                        return { results: results };
                    },
                },
            });
        }, 200);
    });

    $(document).on("change", "#plano", function () {
        if($(this).val()){
            let empresa_id = $('#inp-empresa_atribuir').val();
            $.get(path_url + 'api/planos/find', {empresa_id: empresa_id, plano_id: $(this).val()})
            .done((res) => {
                $('#inp-valor').val(convertFloatToMoeda(res.valor));
            })
            .fail((err) => {
                console.log(err);
            });
        }else{
            $('#inp-valor').val(convertFloatToMoeda(0));
        }
    });
</script>
@endsection
