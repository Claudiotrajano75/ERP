@extends('layouts.app', ['title' => 'Visualizando Reserva'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    .table-custom { border-collapse: separate; border-spacing: 0; }
    .table-custom thead th { background: #f8f9fa; color: #495057; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; border-bottom: 2px solid #eef0f5; padding: 12px 15px; }
    .table-custom tbody tr { transition: all 0.2s; }
    .table-custom tbody tr:hover { background-color: #f8f9fa; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .table-custom tbody td { padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #eef0f5; color: #555; }
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
                                <i class="ri-eye-2-line"></i>
                                Visualizando Reserva <span class="ms-1 fw-bold">#{{ $item->numero_sequencial }}</span>
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Detalhes completos da reserva e opções de gestão.
                            </p>
                        </div>
                        <a href="{{ route('reservas.index') }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-arrow-left-double-fill me-1"></i> Voltar
                        </a>
                    </div>
                </div>
                
                <input type="hidden" id="reserva_id" value="{{ $item->id }}">
                
                <div class="card-body bg-white p-4">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-6">
                            <span class="badge bg-{{ $item->colorStatus() }} bg-opacity-10 border border-{{ $item->colorStatus() }} border-opacity-25 text-{{ $item->colorStatus() }} px-3 py-2 fs-6 rounded-pill">
                                {{ strtoupper($item->estado) }}
                            </span>
                        </div>
                        
                        @if($item->hospedes)
                        <div class="col-md-6 text-end">
                            @if($item->estado == 'iniciado')
                            <button type="button" class="btn btn-dark btn-sm fw-semibold shadow-sm mx-1" id="btn-hospedes" data-bs-toggle="tooltip" title="Gerenciar Hóspedes">
                                <i class="ri-folder-user-fill me-1"></i> Hóspedes
                            </button>
                            @endif
            
                            <a target="_blank" href="{{ route('reservas.imprimir', [$item->id]) }}" type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm mx-1" id="btn-imprimir" data-bs-toggle="tooltip" title="Imprimir Comprovante">
                                <i class="ri-printer-fill me-1"></i> Imprimir
                            </a>
            
                            @if($item->estado == 'iniciado')
                            <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm mx-1" id="btn-fatura" data-bs-toggle="tooltip" title="Gerar Fatura">
                                <i class="ri-wallet-line me-1"></i> Fatura
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <div class="row bg-light p-3 rounded mb-4 border shadow-none">
                        <div class="col-md-6">
                            <p class="mb-2 fs-14">Cliente: <strong class="text-primary">{{ $item->cliente->info }}</strong></p>
                            <p class="mb-2 fs-14">Data de criação: <strong class="text-dark">{{ __data_pt($item->created_at) }}</strong></p>
                            <p class="mb-2 fs-14">Valor da estádia: <strong class="text-dark">{{ __moeda($item->valor_estadia) }}</strong></p>
                            <p class="mb-0 fs-14">Valor total: <strong class="text-success fs-5">{{ __moeda($item->valor_total) }}</strong></p>
                        </div>
                        <div class="col-md-6 border-start">
                            <p class="mb-2 fs-14 ms-3">Acomodação: <strong class="text-primary">{{ $item->acomodacao->info }}</strong></p>
                            <p class="mb-2 fs-14 ms-3">Total de hóspedes: <strong class="text-dark">{{ $item->total_hospedes }}</strong></p>
                            <p class="mb-2 fs-14 ms-3">Período: <strong class="text-dark">{{ __data_pt($item->data_checkin, 0) }} à {{ __data_pt($item->data_checkout, 0) }}</strong></p>
            
                            @if($item->estado == 'iniciado' || $item->estado == 'pendente')
                            <p class="mb-0 fs-14 ms-3">Link do cliente: <a target="_blank" href="{{ $item->link_externo }}" class="text-decoration-none fw-medium text-primary">{{ $item->link_externo }}</a></p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex gap-2 align-items-center">
                            @if($item->estado == 'iniciado')
                            <form action="{{ route('reservas.update-estado', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex align-items-center gap-2">
                                @method('put')
                                @csrf
                                <input type="hidden" name="estado" value="finalizado">
                                <button type="button" class="btn btn-dark btn-sm btn-update-estado shadow-sm fw-semibold" data-bs-toggle="tooltip" title="Finalizar Reserva">
                                    <i class="ri-checkbox-circle-line me-1"></i> Alterar para finalizado
                                </button>
            
                                @if($item->conferencia_frigobar == 0)
                                <a href="{{ route('reservas.conferir-frigobar', [$item->id]) }}" type="button" class="btn btn-outline-secondary btn-sm shadow-sm fw-semibold" id="btn-conferir" data-bs-toggle="tooltip" title="Conferir Frigobar">
                                    <i class="ri-door-fill me-1"></i> Conferir frigobar
                                </a>
                                @else
                                <span class="badge bg-success bg-opacity-10 border border-success border-opacity-25 text-success px-3 py-2 ms-2">
                                    <i class="ri-checkbox-circle-line me-1"></i> Frigobar conferido
                                </span>
                                @endif
                            </form>
                            @endif
                            
                            @if($item->estado == 'pendente')
                                @can('reserva_delete')
                                <form action="{{ route('reservas.destroy', $item->id) }}" method="post" id="form-{{$item->id}}">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-danger btn-sm btn-delete shadow-sm fw-semibold" data-bs-toggle="tooltip" title="Excluir Reserva permanentemente">
                                        <i class="ri-delete-bin-line me-1"></i> Remover reserva
                                    </button>
                                </form>
                                @endcan
                                
                                <div class="ms-auto d-flex gap-2">
                                    <a href="{{ route('reservas.checkin', [$item->id]) }}" class="btn btn-success btn-sm shadow-sm fw-semibold" data-bs-toggle="tooltip" title="Iniciar o Processo de Check-in">
                                        <i class="ri-check-fill me-1"></i> Iniciar checkin
                                    </a>
                                </div>
                            @endif
            
                            @if($item->estado == 'pendente' || $item->estado == 'iniciado')
                                <div class="{{ $item->estado == 'iniciado' ? 'ms-auto' : '' }}">
                                    <button type="button" class="btn btn-danger btn-sm shadow-sm fw-semibold" id="btn-cancelar" data-bs-toggle="tooltip" title="Cancelar esta Reserva">
                                        <i class="ri-close-fill me-1"></i> Cancelar reserva
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
            
                    @if($item->estado == 'iniciado')
                    <hr class="text-muted opacity-25">
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">
                                <i class="ri-shopping-cart-2-line align-middle me-1"></i> Consumo de Produtos
                            </h5>
                            
                            <form class="row g-2 align-items-end mb-4 bg-light p-3 rounded border shadow-none" method="post" action="{{ route('reservas.store-produto', [$item->id]) }}">
                                @csrf
                                <div class="col-md-3">
                                    {!!Form::select('produto_id', 'Produto')->required()->attrs(['class' => 'form-select select2'])
                                    !!}
                                </div>
            
                                <div class="col-md-1">
                                    {!!Form::tel('quantidade_produto', 'Qtd.')
                                    ->attrs(['class' => 'qtd form-control'])
                                    ->required()
                                    !!}
                                </div>
            
                                <div class="col-md-2">
                                    {!!Form::tel('valor_unitario_produto', 'Valor Unit.')
                                    ->attrs(['class' => 'moeda form-control'])
                                    ->required()
                                    !!}
                                </div>
                                <div class="col-md-2">
                                    {!!Form::tel('sub_total_produto', 'Subtotal')
                                    ->attrs(['class' => 'moeda form-control'])
                                    ->required()
                                    !!}
                                </div>
            
                                <div class="col-md-1">
                                    {!!Form::select('frigobar', 'Frigobar', ['0' => 'Não', '1' => 'Sim'])
                                    ->attrs(['class' => 'form-select'])
                                    ->required()
                                    !!}
                                </div>
            
                                <div class="col-md-2">
                                    {!!Form::text('observacao', 'Observação')
                                    ->attrs(['class' => 'form-control'])
                                    !!}
                                </div>
            
                                <div class="col-md-1">
                                    <button class="btn btn-dark w-100 fw-semibold" data-bs-toggle="tooltip" title="Adicionar ao Consumo">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="table-responsive border rounded-3 mb-3">
                                <table class="table table-custom mb-0" id="table-produtos">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Qtd.</th>
                                            <th>Valor Unitário</th>
                                            <th>Subtotal</th>
                                            <th class="text-center">Frigobar</th>
                                            <th>Observação</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->consumoProdutos as $p)
                                        <tr>
                                            <td class="fw-medium">{{ $p->produto->nome }}</td>
                                            <td>{{ $p->quantidade }}</td>
                                            <td>{{ __moeda($p->valor_unitario) }}</td>
                                            <td class="fw-bold text-success">{{ __moeda($p->sub_total) }}</td>
                                            <td class="text-center">
                                                @if($p->frigobar)
                                                <i class="ri-checkbox-circle-fill text-success fs-5" data-bs-toggle="tooltip" title="Sim"></i>
                                                @else
                                                <i class="ri-close-circle-fill text-danger fs-5" data-bs-toggle="tooltip" title="Não"></i>
                                                @endif
                                            </td>
                                            <td><span class="text-muted small">{{ $p->observacao }}</span></td>
                                            <td class="text-end">
                                                <form action="{{ route('reservas.destroy-produto', $p->id) }}" method="post" id="form-produto-{{$p->id}}" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-delete btn-sm btn-outline-danger shadow-sm border-0" data-bs-toggle="tooltip" title="Remover Produto">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="ri-shopping-bag-3-line fs-2 mb-2 d-block opacity-50"></i>
                                                Nenhum produto consumido.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    @if(sizeof($item->consumoProdutos) > 0)
                                        @if($item->nfe_id == 0)
                                        <a class="btn btn-success btn-sm shadow-sm fw-semibold d-print-none" href="{{ route('reservas.gerar-nfe', $item->id) }}">
                                            <i class="ri-file-text-line me-1"></i> Gerar NFe de consumo
                                        </a>
                                        @else
                                        <a class="btn btn-info btn-sm shadow-sm fw-semibold text-white d-print-none" href="{{ route('nfe.show', $item->nfe_id) }}">
                                            <i class="ri-file-list-3-line me-1"></i> Ver NFe
                                        </a>
                                        @endif
                                    @endif
                                </div>
                                <h5 class="mb-0 fw-bold">Total Produtos: <span class="text-success">R$ {{ __moeda($item->consumoProdutos->sum('sub_total')) }}</span></h5>
                            </div>
                        </div>
                    </div>
            
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">
                                <i class="ri-service-line align-middle me-1"></i> Consumo de Serviços
                            </h5>
                            
                            <form class="row g-2 align-items-end mb-4 bg-light p-3 rounded border shadow-none" method="post" action="{{ route('reservas.store-servico', [$item->id]) }}">
                                @csrf
                                <div class="col-md-3">
                                    {!!Form::select('servico_id', 'Serviço')->required()->attrs(['class' => 'form-select select2'])
                                    !!}
                                </div>
            
                                <div class="col-md-2">
                                    {!!Form::tel('quantidade_servico', 'Quantidade')
                                    ->attrs(['class' => 'qtd form-control'])
                                    ->required()
                                    !!}
                                </div>
            
                                <div class="col-md-2">
                                    {!!Form::tel('valor_unitario_servico', 'Valor Unitário')
                                    ->attrs(['class' => 'moeda form-control'])
                                    ->required()
                                    !!}
                                </div>
                                <div class="col-md-2">
                                    {!!Form::tel('sub_total_servico', 'Subtotal')
                                    ->attrs(['class' => 'moeda form-control'])
                                    ->required()
                                    !!}
                                </div>
            
                                <div class="col-md-2">
                                    {!!Form::text('observacao', 'Observação')
                                    ->attrs(['class' => 'form-control'])
                                    !!}
                                </div>
            
                                <div class="col-md-1">
                                    <button class="btn btn-dark w-100 fw-semibold" data-bs-toggle="tooltip" title="Adicionar Serviço">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="table-responsive border rounded-3 mb-3">
                                <table class="table table-custom mb-0" id="table-servicos">
                                    <thead>
                                        <tr>
                                            <th>Serviço</th>
                                            <th>Qtd.</th>
                                            <th>Valor Unitário</th>
                                            <th>Subtotal</th>
                                            <th>Observação</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->consumoServicos as $p)
                                        <tr>
                                            <td class="fw-medium">{{ $p->servico->nome }}</td>
                                            <td>{{ $p->quantidade }}</td>
                                            <td>{{ __moeda($p->valor_unitario) }}</td>
                                            <td class="fw-bold text-success">{{ __moeda($p->sub_total) }}</td>
                                            <td><span class="text-muted small">{{ $p->observacao }}</span></td>
                                            <td class="text-end">
                                                <form action="{{ route('reservas.destroy-servico', $p->id) }}" method="post" id="form-servico-{{$p->id}}" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-delete btn-sm btn-outline-danger shadow-sm border-0" data-bs-toggle="tooltip" title="Remover Serviço">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="ri-customer-service-2-line fs-2 mb-2 d-block opacity-50"></i>
                                                Nenhum serviço registrado.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    @if(sizeof($item->consumoServicos) > 0)
                                        @if($item->nfse_id == 0)
                                            @can('nfse_create')
                                            <a class="btn btn-success btn-sm shadow-sm fw-semibold d-print-none" href="{{ route('reservas.gerar-nfse', $item->id) }}">
                                                <i class="ri-file-text-line me-1"></i> Gerar NFSe
                                            </a>
                                            @endcan
                                        @else
                                        <a class="btn btn-info btn-sm shadow-sm fw-semibold text-white d-print-none" href="{{ route('nota-servico.show', $item->nfse_id) }}">
                                            <i class="ri-file-list-3-line me-1"></i> Ver NFSe
                                        </a>
                                        @endif
                                    @endif
                                </div>
                                <h5 class="mb-0 fw-bold">Total Serviços: <span class="text-success">R$ {{ __moeda($item->consumoServicos->sum('sub_total')) }}</span></h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">
                                <i class="ri-sticky-note-line align-middle me-1"></i> Notas da Reserva
                            </h5>
                            
                            <form class="row g-2 align-items-end mb-4 bg-light p-3 rounded border shadow-none" method="post" action="{{ route('reservas.store-nota', [$item->id]) }}">
                                @csrf
                                <div class="col-md-10">
                                    {!!Form::textarea('texto', 'Texto da Nota')->required()
                                    ->attrs(['class' => 'form-control', 'rows' => '2'])
                                    !!}
                                </div>
            
                                <div class="col-md-2">
                                    <button class="btn btn-dark w-100 fw-semibold h-100" data-bs-toggle="tooltip" title="Adicionar Nota" style="min-height: 40px;">
                                        <i class="ri-add-line me-1"></i> Adicionar
                                    </button>
                                </div>
                            </form>
                            
                            <div class="table-responsive border rounded-3">
                                <table class="table table-custom mb-0" id="table-notas">
                                    <thead>
                                        <tr>
                                            <th style="width: 90%;">Texto da Nota</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->notas as $p)
                                        <tr>
                                            <td style="width: 90%; white-space: pre-wrap;">{{ $p->texto }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('reservas.destroy-nota', $p->id) }}" method="post" id="form-nota-{{$p->id}}" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-delete btn-sm btn-outline-danger shadow-sm border-0" data-bs-toggle="tooltip" title="Remover Nota">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">
                                                <i class="ri-message-3-line fs-2 mb-2 d-block opacity-50"></i>
                                                Nenhuma nota cadastrada.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals._cancelamento_reserva')
@include('modals._hospedes')
@include('modals._fatura_reserva')

@endsection

@section('js')
<script type="text/javascript" src="/js/reserva.js"></script>
<script type="text/javascript">
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    $('#btn-cancelar').click(() => {
        $('#modal_cancelamento_reserva').modal('show')
    })
    $('#btn-fatura').click(() => {
        $('#modal_fatura').modal('show')
    })

    $('body').on('blur', '.valor_fatura', function () {
        var total = 0
        $(".valor_fatura").each(function () {
            total += convertMoedaToFloat($(this).val())
        })

        setTimeout(() => {
            $('#total_fatura').text("R$ " + convertFloatToMoeda(total))
        }, 20)
    })

    
    $(".btn-update-estado").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form").attr("id");

        swal({
            title: "Você está certo?",
            text: "Uma vez alterado, você não poderá voltar esse estado novamente!",
            icon: "warning",
            buttons: true,
            buttons: ["Cancelar", "Alterar"],
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                document.getElementById(form).submit();
            }
        });
    });
</script>
@endsection


