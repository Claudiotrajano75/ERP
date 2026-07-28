@extends('layouts.app', ['title' => 'Pedidos de Delivery'])

@section('css')
    <style>
        .modulo-header-gradient {
            background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%);
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none !important;
        }

        .modulo-header-gradient .modulo-title {
            color: #fff;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .modulo-header-gradient .modulo-title i {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px;
            border-radius: 10px;
            color: #fff;
        }

        .modulo-header-gradient .modulo-subtitle {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 400;
        }

        .modulo-form-card {
            border: 1px solid #eef0f5;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        /* Pedido Card */
        .pedido-card {
            border-radius: 12px;
            border: 1px solid #eef0f5;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none !important;
            color: inherit;
        }

        .pedido-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .pedido-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #eef0f5;
            padding: 12px 16px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pedido-id {
            font-weight: 700;
            color: #0d2b40;
            font-size: 15px;
        }

        .pedido-data {
            font-size: 12px;
            color: #64748b;
        }

        .pedido-body {
            padding: 16px;
            flex-grow: 1;
        }

        .pedido-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .pedido-info-label {
            color: #64748b;
            font-weight: 500;
        }

        .pedido-info-value {
            color: #1e293b;
            font-weight: 600;
            text-align: right;
        }

        .pedido-total {
            font-size: 18px;
            font-weight: 800;
            color: #10b981;
        }

        .pedido-footer {
            padding: 12px 16px;
            border-top: 1px dashed #e2e8f0;
            text-align: center;
        }

        /* Filtros */
        .filter-box {
            background-color: #f8fafc;
            border: 1px solid #eef0f5;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }
    </style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm modulo-form-card">

                    <div class="card-header modulo-header-gradient py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                    <i class="ri-motorbike-fill"></i>
                                    Pedidos de Delivery
                                </h4>
                                <p class="mb-0 modulo-subtitle fs-13">
                                    Gerencie e acompanhe todos os pedidos realizados no delivery.
                                </p>
                            </div>
                            <button class="btn btn-light text-dark fw-semibold px-4 py-2" type="button"
                                data-bs-toggle="modal" data-bs-target="#modal-comanda">
                                <i class="ri-add-circle-fill me-1"></i> Novo Pedido
                            </button>
                        </div>
                    </div>

                    <div class="card-body bg-white p-4">

                        <div class="filter-box">
                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row align-items-end g-3">
                                <div class="col-md-3">
                                    {!!Form::select('cliente_delivery_id', 'Pesquisar por cliente')
        ->options($cliente != null ? [$cliente->id => ($cliente->razao_social . " - " . $cliente->telefone)] : [])
                                    !!}
                                </div>
                                <div class="col-md-2">
                                    {!!Form::select('estado', 'Status', ['' => 'Todos'] + App\Models\PedidoDelivery::estados())->attrs(['class' => 'form-select'])!!}
                                </div>
                                @if(__isProdutoServicoDelivery(request()->empresa_id))
                                    <div class="col-md-3">
                                        {!!Form::select('tipo', 'Tipo', ['' => 'Todos', 'delivery' => 'Somente Delivery', 'agendamento' => 'Somente Agendamento'])->attrs(['class' => 'form-select'])!!}
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <button class="btn btn-primary px-3" type="submit"
                                        style="background-color: #0d2b40; border-color: #0d2b40;">
                                        <i class="ri-search-line me-1"></i> Pesquisar
                                    </button>
                                    <a id="clear-filter" class="btn btn-light border px-3"
                                        href="{{ route('pedidos-delivery.index') }}">
                                        <i class="ri-eraser-fill me-1"></i> Limpar
                                    </a>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>

                        <div class="row g-4 mt-1">
                            @forelse($data as $item)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="{{ route('pedidos-delivery.show', [$item->id]) }}" class="pedido-card">
                                        <div class="pedido-header">
                                            <span class="pedido-id">Pedido
                                                #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            <span class="pedido-data"><i class="ri-calendar-line"></i>
                                                {{ __data_pt($item->created_at) }}</span>
                                        </div>
                                        <div class="pedido-body">
                                            <div class="pedido-info-row">
                                                <span class="pedido-info-label">Cliente</span>
                                                <span
                                                    class="pedido-info-value">{{ \Illuminate\Support\Str::limit($item->cliente->razao_social, 20) }}</span>
                                            </div>
                                            <div class="pedido-info-row">
                                                <span class="pedido-info-label">Itens</span>
                                                <span class="pedido-info-value">{{ sizeof($item->itens) }}
                                                    {!! sizeof($item->itens) == 1 ? 'item' : 'itens' !!}</span>
                                            </div>
                                            <div class="pedido-info-row">
                                                <span class="pedido-info-label">Entrega</span>
                                                <span class="pedido-info-value">
                                                    @if($item->endereco)
                                                        {{ \Illuminate\Support\Str::limit($item->endereco->rua, 15) }}
                                                    @else
                                                        <span class="badge bg-light text-dark border">Retirada</span>
                                                    @endif
                                                </span>
                                            </div>

                                            @if($item->inicio_agendamento)
                                                <div class="mt-2 text-end">
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i
                                                            class="ri-time-line"></i> Agendamento</span>
                                                </div>
                                            @endif

                                            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                                <span class="text-secondary fw-semibold">Total</span>
                                                <span class="pedido-total">R$ {{ __moeda($item->valor_total) }}</span>
                                            </div>
                                        </div>
                                        <div class="pedido-footer">
                                            {!! $item->_estado() !!}
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="ri-inbox-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                    <h5 class="text-muted">Nenhum pedido de delivery encontrado</h5>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-4">
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL NOVO PEDIDO --}}
    <div class="modal fade" id="modal-comanda" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('pedidos-delivery.store') }}" method="post">
                    @csrf
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title text-dark fw-bold"><i class="ri-add-circle-fill text-primary me-1"></i>
                            Abertura de Pedido Delivery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2'])!!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::text('cliente_nome', 'Nome do Cliente')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Nome do consumidor'])!!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::tel('cliente_fone', 'Telefone')->required()->attrs(['class' => 'fone form-control', 'placeholder' => '(00) 00000-0000'])!!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4"><i class="ri-check-line me-1"></i> Criar
                            Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            setTimeout(() => {
                $('.modal .select2').each(function () {
                    $(this).select2({
                        minimumInputLength: 2,
                        dropdownParent: $(this).parent(),
                        language: "pt-BR",
                        placeholder: "Digite para buscar o cliente",
                        theme: "bootstrap4",
                        ajax: {
                            cache: true,
                            url: path_url + "api/clientes/pesquisa",
                            dataType: "json",
                            data: function (params) {
                                return {
                                    pesquisa: params.term,
                                    empresa_id: $("#empresa_id").val(),
                                };
                            },
                            processResults: function (response) {
                                var results = [];
                                $.each(response, function (i, v) {
                                    results.push({
                                        id: v.id,
                                        text: v.razao_social + " - " + v.cpf_cnpj,
                                        value: v.id
                                    });
                                });
                                return { results: results };
                            },
                        },
                    });
                });
            }, 10)
        })

        $('body').on('change', '#inp-cliente_id', function () {
            let id = $(this).val()
            $.get(path_url + 'api/clientes/find/' + id)
                .done((success) => {
                    $('#inp-cliente_nome').val(success.razao_social)
                    $('#inp-cliente_fone').val(success.telefone)
                })
        });
    </script>
@endsection