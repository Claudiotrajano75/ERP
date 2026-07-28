@extends('layouts.app', ['title' => 'Comanda ' . $item->comanda])

@section('css')
<style type="text/css">
    /* ─── Header Gradiente ─── */
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

    /* ─── Form Card ─── */
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-form-card .card-body { background: #fff; }
    .modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
    .modulo-form-card .form-control,
    .modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
    .modulo-form-card .form-control:focus,
    .modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

    /* ─── Premium Table ─── */
    .modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; }
    
    /* ─── Badges de Estado das Legendas ─── */
    .badge-legenda {
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div id="print"></div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card mb-4">

                {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-restaurant-line"></i>
                                Comanda #{{ $item->comanda }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Cliente: <strong>{{ $item->cliente_nome != "" ? $item->cliente_nome : 'Não identificado' }}</strong> | Mesa: <strong>{{ $item->mesa ? $item->mesa : '--' }}</strong>
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            <button class="btn btn-light btn-sm px-3 text-dark d-flex align-items-center gap-1" onclick="print('{{ $item->id }}')">
                                <i class="ri-printer-line fs-16"></i> Imprimir
                            </button>
                            <a href="{{ route('pedidos-cardapio.index') }}" class="btn btn-light btn-sm px-3 text-dark d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line fs-16"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 bg-light">
                    <div class="row g-3">

                        {{-- Painel da Esquerda: Lançamento de Itens --}}
                        <div class="col-12 col-lg-4">
                            <div class="card border-0 shadow-sm modulo-form-card h-100">
                                <div class="card-header bg-white border-bottom py-3 px-3">
                                    <h5 class="m-0 text-dark fw-bold d-flex align-items-center gap-1">
                                        <i class="ri-add-circle-line text-success"></i> Adicionar Item
                                    </h5>
                                </div>
                                <div class="card-body p-3">
                                    <form class="row g-3" method="post" action="{{ route('pedidos-cardapio.store-item', [$item->id]) }}">
                                        @csrf

                                        <input type="hidden" id="tipo_divisao_pizza" value="{{ $config != null ? $config->valor_pizza : 'divide' }}">
                                        
                                        <div class="col-md-12">
                                            {!!Form::select('produto_cardapio', 'Produto')->required()
                                            ->attrs(['class' => 'produto_cardapio select2'])
                                            !!}
                                        </div>

                                        <div class="col-md-6 col-12">
                                            {!!Form::tel('quantidade', 'Quantidade')
                                            ->required()
                                            ->attrs(['class' => 'moeda form-control'])
                                            !!}
                                        </div>

                                        <div class="col-md-6 col-12">
                                            {!!Form::tel('sub_total', 'Subtotal')
                                            ->required()
                                            ->readonly()
                                            ->attrs(['class' => 'moeda form-control'])
                                            !!}
                                        </div>

                                        <div class="col-md-6 col-12 d-none">
                                            {!!Form::tel('valor_unitario', 'Valor unitário')
                                            ->required()
                                            ->attrs(['class' => 'moeda form-control'])
                                            !!}
                                        </div>

                                        <div class="col-md-12">
                                            <button type="button" class="btn w-100 btn-dark d-flex align-items-center justify-content-center gap-1" id="btn-adicionais">
                                                <i class="ri-shopping-basket-fill"></i>
                                                Definir adicionais
                                            </button>
                                        </div>

                                        <div class="col-md-12 adicionaisescolhidos">
                                        </div>

                                        <div class="col-md-12">
                                            {!!Form::text('observacao', 'Observação')
                                            ->attrs(['class' => 'form-control'])
                                            !!}
                                        </div>

                                        <div class="col-12 div-tp-carne d-none">
                                            {!!Form::select('ponto_carne', 'Ponto da carne', ['' => 'Selecione'] +  App\Models\Produto::pontosDaCarne())
                                            ->attrs(['class' => 'form-select'])
                                            !!}
                                        </div>

                                        <div class="col-md-12">
                                            {!!Form::select('estado', 'Estado', 
                                            [
                                            'novo' => 'Novo', 
                                            'pendente' => 'Pendente', 
                                            'preparando' => 'Preparando', 
                                            'finalizado' => 'Finalizado'
                                            ])
                                            ->attrs(['class' => 'form-select'])
                                            ->required()
                                            !!}
                                        </div>

                                        <input type="hidden" id="adicionais-hidden" name="adicionais">
                                        <input type="hidden" id="pizzas-hidden" name="pizzas">
                                        <input type="hidden" id="tamanho_id-hidden" name="tamanho_id">

                                        <div class="col-md-12 col-12 mt-4">
                                            <button type="submit" class="btn w-100 btn-success py-2 fw-semibold d-flex align-items-center justify-content-center gap-1">
                                                <i class="ri-checkbox-circle-line fs-18"></i>
                                                Adicionar Item
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Painel da Direita: Lista de Itens na Comanda --}}
                        <div class="col-12 col-lg-8">
                            <div class="card border-0 shadow-sm modulo-form-card h-100">
                                <div class="card-header bg-white border-bottom py-3 px-3">
                                    <h5 class="m-0 text-dark fw-bold d-flex align-items-center gap-1">
                                        <i class="ri-list-check text-primary"></i> Itens da Comanda
                                    </h5>
                                </div>
                                <div class="card-body p-3 d-flex flex-column h-100">
                                    
                                    {{-- Tabela de Itens --}}
                                    <div class="modulo-table-wrap flex-grow-1 mb-3" style="min-height: 250px;">
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Produto</th>
                                                        <th class="text-center">Qtd.</th>
                                                        <th class="text-end">Unitário</th>
                                                        <th class="text-end">Subtotal</th>
                                                        <th class="text-center">Obs.</th>
                                                        <th class="text-end" style="width: 50px;">Excluir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($item->itens as $i)
                                                    <tr class="bg-{{ $i->estado }}">
                                                        <td>
                                                            <span class="fw-semibold">{{ $i->produto->nome }}</span>
                                                            @if($i->funcionario)
                                                            <br> <span class="text-danger" style="font-size: 11px"><i class="ri-user-star-line"></i> Garçom: {{ $i->funcionario->nome }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">{{ __moeda($i->quantidade) }}</td>
                                                        <td class="text-end">R$ {{ __moeda($i->valor_unitario) }}</td>
                                                        <td class="text-end fw-semibold">R$ {{ __moeda($i->sub_total) }}</td>
                                                        <td class="text-center">
                                                            @if($i->observacao == '')
                                                            <button class="btn btn-sm btn-link text-muted p-0" disabled>
                                                                <i class="ri-sticky-note-line fs-16"></i>
                                                            </button>
                                                            @else
                                                            <button class="btn btn-sm btn-link text-dark p-0" onclick="noteSwal('{{ $i->observacao }}')">
                                                                <i class="ri-chat-check-line fs-18"></i>
                                                            </button>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <form action="{{ route('pedidos-cardapio.destroy-item', $i->id) }}" method="post" id="form-{{$i->id}}" class="m-0">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="button" title="Deletar" class="btn btn-outline-danger btn-delete btn-sm border-0"><i class="ri-delete-bin-line"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    
                                                    @if(sizeof($i->adicionais) > 0)
                                                    <tr class="bg-light">
                                                        <td></td>
                                                        <td colspan="5" class="fs-12 text-muted">
                                                            <span class="fw-bold">Adicionais:</span> {{ $i->getAdicionaisStr() }}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if($i->ponto_carne)
                                                    <tr class="bg-light">
                                                        <td></td>
                                                        <td colspan="5" class="fs-12 text-muted">
                                                            <span class="fw-bold">Ponto da carne:</span> <strong class="text-success">{{ $i->ponto_carne }}</strong>
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if(sizeof($i->pizzas) > 0)
                                                    <tr class="bg-light">
                                                        <td></td>
                                                        <td colspan="5" class="fs-12 text-muted">
                                                            <span class="fw-bold">Sabores:</span> 
                                                            <strong class="text-success">
                                                                @foreach($i->pizzas as $s)
                                                                1/{{ sizeof($i->pizzas) }} {{ $s->sabor->nome }}
                                                                @if(!$loop->last) | @endif
                                                                @endforeach
                                                            </strong>
                                                            <span> - Tamanho: <strong class="text-info">{{ $i->tamanho ? $i->tamanho->nome : '--' }}</strong></span>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">
                                                            Nenhum item lançado para esta comanda.
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Estados dos itens --}}
                                    <div class="row g-2 mb-3 bg-light p-2 rounded-3 border">
                                        <div class="col-12"><span class="fs-11 fw-bold text-uppercase text-muted">Legendas de Estado:</span></div>
                                        <div class="col-lg-3 col-6 d-flex align-items-center gap-1">
                                            <span class="badge-legenda text-novo" style="background:#e0f2fe; color:#0284c7;"><i class="ri-flag-2-fill"></i> Novo</span>
                                        </div>
                                        <div class="col-lg-3 col-6 d-flex align-items-center gap-1">
                                            <span class="badge-legenda text-pendente" style="background:#fee2e2; color:#dc2626;"><i class="ri-flag-2-fill"></i> Pendente</span>
                                        </div>
                                        <div class="col-lg-3 col-6 d-flex align-items-center gap-1">
                                            <span class="badge-legenda text-preparando" style="background:#fef9c3; color:#ca8a04;"><i class="ri-flag-2-fill"></i> Preparando</span>
                                        </div>
                                        <div class="col-lg-3 col-6 d-flex align-items-center gap-1">
                                            <span class="badge-legenda text-finalizado" style="background:#dcfce7; color:#16a34a;"><i class="ri-flag-2-fill"></i> Finalizado</span>
                                        </div>
                                    </div>

                                    {{-- Botão de Finalizar --}}
                                    <div class="mt-auto">
                                        <a class="btn btn-lg btn-success w-100 py-3 fw-bold d-flex align-items-center justify-content-between px-4 @if(!$item->status) disabled @endif" style="border-radius: 12px;" href="{{ route('pedidos-cardapio.finish', [$item->id])}}">
                                            <span class="d-flex align-items-center gap-1">
                                                <i class="ri-shopping-cart-2-line fs-22"></i>
                                                Finalizar Comanda
                                            </span>
                                            <span class="fs-22">R$ {{ __moeda($item->total) }}</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL ADICIONAIS --}}
<div class="modal fade text-dark" id="modal-adicionais" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header modulo-header-gradient py-3 px-4">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="exampleModalLabel">
                    <i class="ri-shopping-basket-line"></i> Adicionais
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-2 adicionais">
                </div>
                <div class="mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
                    <h4 class="m-0 text-dark font-semibold">Subtotal: <strong class="subtotal_modal text-success"></strong></h4>
                </div>
            </div>
            <div class="modal-footer p-3 bg-white border-top">
                <button id="btn-save-modal" type="button" class="btn btn-success px-4" style="border-radius: 8px;" data-bs-dismiss="modal">
                    <i class="ri-check-line align-middle me-1"></i> Salvar Adicionais
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SABORES PIZZA --}}
<div class="modal fade text-dark" id="modal-pizza" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header modulo-header-gradient py-3 px-4">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="exampleModalLabel">
                    <i class="ri-search-line"></i> Selecione os Sabores da Pizza
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="text-danger fs-12 mb-3">* Selecione o tamanho para buscar os sabores correspondentes</p>
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-6 col-12">
                        {!!Form::select('tamanho_id', 'Tamanho da Pizza', ['' => 'Selecione'] + 
                        $tamanhosPizza->pluck('info', 'id')->all())
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>
                </div>
                
                <div class="row pizzas g-2 mb-3">
                </div>

                <div class="col-md-4 col-12 mt-3 pt-3 border-top">
                    {!!Form::tel('subtotal_modal', 'Subtotal da Pizza')
                    ->required()
                    ->attrs(['class' => 'moeda form-control'])
                    !!}
                </div>
            </div>
            <div class="modal-footer p-3 bg-white border-top">
                <button id="btn-save-sabores" type="button" class="btn btn-success px-4" style="border-radius: 8px;">
                    <i class="ri-check-line align-middle me-1"></i> Salvar Sabores
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="/js/pedido.js"></script>
@endsection


@endsection

@section('js')
<script type="text/javascript" src="/js/pedido.js"></script>
@endsection
