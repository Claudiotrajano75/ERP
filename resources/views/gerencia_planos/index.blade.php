@extends('layouts.app', ['title' => 'Gerenciar Planos'])

@section('css')
<style>
    /* Estilos Personalizados para a Página de Gerenciar Planos */
    .page-title-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b, #475569);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .page-title i {
        color: #4f46e5;
    }

    .page-title-box-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Cards e Layout */
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

    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 20px 0 !important;
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
                            <i class="ri-exchange-funds-line"></i> Atribuição de Planos
                        </h4>
                        <p class="modulo-subtitle">
                            Gerencie e atribua planos diretamente às empresas do sistema.
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
                <div class="col-lg-12">
                    {!!Form::open()->fill(request()->all())
                    ->get()
                    !!}

                    <div class="row mt-3">
                        <div class="col-md-3">
                            {!!Form::select('empresa', 'Pesquisar por empresa')
                            ->options($empresa ? [$empresa->id => $empresa->info] : [])
                            !!}
                        </div>
                        <div class="col-md-3 text-left">
                            <br>
                            <button class="btn btn-primary" type="submit"> <i class="ri-search-line"></i>Pesquisar</button>
                            <a id="clear-filter" class="btn btn-danger" href="{{ route('gerenciar-planos.index') }}"><i class="ri-eraser-fill"></i>Limpar</a>
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
                                    <th>Forma de pagamento</th>
                                    <th>Data de cadastro</th>
                                    <th>Data de expiração</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>

                                    <td>{{ $item->empresa->info }}</td>
                                    <td>{{ $item->plano->nome }}</td>
                                    <td>{{ __moeda($item->valor) }}</td>
                                    <td>
                                        <span class="badge badge-light text-dark">{{ $item->forma_pagamento }}</span>
                                    </td>

                                    <td>{{ __data_pt($item->created_at, 1) }}</td>
                                    <td>{{ __data_pt($item->data_expiracao, 0) }}</td>
                                    <td>

                                        <form action="{{ route('gerenciar-planos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex align-items-center gap-1" style="width: auto;">
                                            @method('delete')
                                            @csrf
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {!! $data->appends(request()->all())->links() !!}

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-cad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post" action="{{ route('gerenciar-planos.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="ri-exchange-funds-line text-primary"></i> Atribuir plano</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        {!!Form::select('empresa_atribuir', 'Empresa')
                        ->required()
                        ->attrs(['class' => 'select2 empresa'])
                        !!}
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Plano</label>
                        <select required id="plano" name="plano_id" class="form-select select2">
                            <option value="">Selecione</option>
                            @foreach($planos as $p)
                            <option value="{{ $p->id }}" data-valor="{{ $p->valor }}">{{ $p->nome }} R$ {{ __moeda($p->valor)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        {!!Form::select('forma_pagamento', 'Tipo de pagamento', \App\Models\Plano::formasPagamento())
                        ->required()
                        ->attrs(['class' => 'select2'])
                        !!}
                    </div>

                    <div class="col-md-6">
                        {!!Form::tel('valor', 'Valor')
                        ->required()
                        ->attrs(['class' => 'moeda'])
                        !!}
                    </div>

                    <div class="col-md-6">
                        {!!Form::select('status_pagamento', 'Status de pagamento', \App\Models\FinanceiroPlano::statusDePagamentos())
                        ->required()
                        ->attrs(['class' => 'select2'])
                        ->value('recebido')
                        !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success"><i class="ri-save-line"></i> Salvar</button>
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
                theme: "bootstrap4",
                dropdownParent: $('#modal-cad'),
                ajax: {
                    cache: true,
                    url: path_url + "api/empresas/find-all",
                    dataType: "json",
                    data: function (params) {

                        var query = {
                            pesquisa: params.term,
                        };
                        return query;
                    },
                    processResults: function (response) {
                        var results = [];

                        $.each(response, function (i, v) {
                            var o = {};
                            o.id = v.id;

                            o.text = v.info;
                            o.value = v.id;
                            results.push(o);
                        });
                        return {
                            results: results,
                        };
                    },
                },
            });
        }, 200)

    });

    $(document).on("change", "#plano", function () {
        if($(this).val()){
            let empresa_id = $('#inp-empresa_atribuir').val()

            $.get(path_url + 'api/planos/find', {empresa_id: empresa_id, plano_id: $(this).val()})
            .done((res) => {
                console.log(res)
                $('#inp-valor').val(convertFloatToMoeda(res.valor))
            })
            .fail((err) => {
                console.log(err)
                swal("Erro", "Algo deu errado", "error")
            })
        }else{
            $('#inp-valor').val(convertFloatToMoeda(0))
        }
    });

</script>
@endsection
