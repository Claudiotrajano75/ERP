@extends('layouts.app', ['title' => 'Comanda #' . $item->comanda])

@section('css')
<style type="text/css">
    /* ─── Cards de Resumo da Comanda ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 16px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,.12); pointer-events: none; }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 4px 14px rgba(79,70,229,.25); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 4px 14px rgba(16,185,129,.25); }
    .stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 4px 14px rgba(37,99,235,.25); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 4px 14px rgba(245,158,11,.25); }
    .stat-card .st-label { font-size: 10.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 22px; font-weight: 800; color: #fff; margin-top: 3px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11px; color: rgba(255,255,255,.75); margin-top: 2px; }
    .stat-card .st-icon { width: 42px; height: 42px; border-radius: 12px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Cards e Seções de Formulário ─── */
    .modulo-panel { background: #fff; border: 1px solid #e9ecf3; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(16, 24, 40, 0.04); }
    .uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 12px; margin-bottom: 16px; }
    .uf-section-title .uf-ico { width: 32px; height: 32px; border-radius: 10px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; }
    
    .uf-field label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    .uf-field label i { color: #a8a8c0; font-size: 12px; }
    .uf-field .form-control, .uf-field .form-select { height: 42px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela Premium de Itens ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 14px; border-bottom: 1px solid #e8eaf6; }
    .tb-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f8fafc; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Linhas de Itens por Estado ─── */
    tr.bg-novo { background-color: #f0f9ff !important; }
    tr.bg-pendente { background-color: #fff1f2 !important; }
    tr.bg-preparando { background-color: #fefce8 !important; }
    tr.bg-finalizado { background-color: #f0fdf4 !important; }

    /* ─── Pills / Badges ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-blue { background: #e0f2fe; color: #0369a1; }
    .pill-amber { background: #fef3c7; color: #b45309; }
    .pill-red { background: #fee2e2; color: #b91c1c; }

    /* ─── Botão de Excluir Item ─── */
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Modal Modernizada ─── */
    .modal-content-modern { border-radius: 16px; border: 1px solid #e9ecf3; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.16); overflow: hidden; }
    .modal-header-modern {
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-title-modern {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-title-modern .modal-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #eef0ff;
        color: #4f46e5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Select2 Single Height */
    .select2-container .select2-selection--single {
        height: 42px !important;
        line-height: 42px !important;
        border: 1px solid #dcdce9 !important;
        border-radius: 10px !important;
        background: #fcfdfe !important;
        font-size: 13.5px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        color: #1f2937;
        padding-left: 12px;
    }
    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }

    /* Adicionais Card Grid */
    .adicional-item-card {
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        cursor: pointer;
        transition: all .2s ease;
        text-align: center;
    }
    .adicional-item-card:hover {
        border-color: #4f46e5;
        transform: translateY(-2px);
    }
    .adicional-item-card.selected {
        background: #eef0ff;
        border-color: #4f46e5;
        color: #312e81;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div id="print"></div>

    <div class="row">
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO PADRÃO DO ERP ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-restaurant-2-line"></i>
                            Comanda #{{ $item->comanda }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Cliente: <strong>{{ $item->cliente_nome != "" ? $item->cliente_nome : 'Não identificado' }}</strong>
                            <span class="mx-1">•</span> Mesa: <strong>{{ $item->mesa ? $item->mesa : '--' }}</strong>
                            @if($item->created_at)
                            <span class="mx-1">•</span> Aberta às <strong>{{ $item->created_at->format('H:i') }}</strong>
                            @endif
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="dash-btn dash-btn-light" type="button" onclick="print('{{ $item->id }}')">
                            <i class="ri-printer-line"></i> Imprimir
                        </button>
                        <a href="{{ route('pedidos-cardapio.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ RESUMO RÁPIDO (CARDS KPI) ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total Consumido</div>
                                    <div class="st-value">R$ {{ __moeda($item->total) }}</div>
                                    <div class="st-sub">{{ count($item->itens) }} itens lançados</div>
                                </div>
                                <div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Mesa</div>
                                    <div class="st-value">{{ $item->mesa ?: '--' }}</div>
                                    <div class="st-sub">Local de atendimento</div>
                                </div>
                                <div class="st-icon"><i class="ri-bookmark-3-line"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Itens no Pedido</div>
                                    <div class="st-value">{{ count($item->itens) }}</div>
                                    <div class="st-sub">Lançamentos registrados</div>
                                </div>
                                <div class="st-icon"><i class="ri-shopping-basket-line"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="stat-card @if(!$item->em_atendimento) stat-amber @else stat-indigo @endif">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Status da Comanda</div>
                                    <div class="st-value fs-18 mt-1">{{ !$item->em_atendimento ? 'Pediu Conta' : 'Em Aberto' }}</div>
                                    <div class="st-sub">Estado do atendimento</div>
                                </div>
                                <div class="st-icon"><i class="ri-time-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO PRINCIPAL: LANÇAMENTO + LISTA DE ITENS ═══ --}}
                <div class="row g-4">

                    {{-- ─── COLUNA ESQUERDA: ADICIONAR ITEM ─── --}}
                    <div class="col-12 col-lg-4">
                        <div class="modulo-panel p-4 h-100">
                            <div class="uf-section-title">
                                <span class="uf-ico"><i class="ri-add-circle-fill"></i></span>
                                Lançar Novo Item
                            </div>

                            <form class="row g-3" method="post" action="{{ route('pedidos-cardapio.store-item', [$item->id]) }}">
                                @csrf

                                <input type="hidden" id="tipo_divisao_pizza" value="{{ $config != null ? $config->valor_pizza : 'divide' }}">

                                <div class="col-12 uf-field">
                                    <label><i class="ri-search-line"></i> Selecionar Produto <span class="text-danger">*</span></label>
                                    {!!Form::select('produto_cardapio', '')
                                    ->required()
                                    ->attrs(['class' => 'produto_cardapio select2 form-control', 'id' => 'inp-produto_cardapio'])
                                    !!}
                                </div>

                                <div class="col-6 uf-field">
                                    <label><i class="ri-hashtag"></i> Quantidade <span class="text-danger">*</span></label>
                                    {!!Form::tel('quantidade', '')
                                    ->required()
                                    ->attrs(['class' => 'moeda form-control', 'id' => 'inp-quantidade', 'value' => '1,00'])
                                    !!}
                                </div>

                                <div class="col-6 uf-field">
                                    <label><i class="ri-money-dollar-circle-line"></i> Subtotal <span class="text-danger">*</span></label>
                                    {!!Form::tel('sub_total', '')
                                    ->required()
                                    ->readonly()
                                    ->attrs(['class' => 'moeda form-control', 'id' => 'inp-sub_total'])
                                    !!}
                                </div>

                                <div class="col-12 d-none">
                                    {!!Form::tel('valor_unitario', 'Valor unitário')
                                    ->required()
                                    ->attrs(['class' => 'moeda form-control', 'id' => 'inp-valor_unitario'])
                                    !!}
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2" id="btn-adicionais" style="border-radius: 10px;">
                                        <i class="ri-shopping-basket-fill fs-16"></i>
                                        <span class="fw-semibold">Definir Adicionais</span>
                                    </button>
                                </div>

                                <div class="col-12 adicionaisescolhidos">
                                </div>

                                <div class="col-12 uf-field">
                                    <label><i class="ri-chat-1-line"></i> Observação do Item</label>
                                    {!!Form::text('observacao', '')
                                    ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Sem cebola, bem passado...'])
                                    !!}
                                </div>

                                <div class="col-12 div-tp-carne d-none uf-field">
                                    <label><i class="ri-restaurant-line"></i> Ponto da Carne</label>
                                    {!!Form::select('ponto_carne', '', ['' => 'Selecione'] + App\Models\Produto::pontosDaCarne())
                                    ->attrs(['class' => 'form-select'])
                                    !!}
                                </div>

                                <div class="col-12 uf-field">
                                    <label><i class="ri-flag-2-line"></i> Estado do Item <span class="text-danger">*</span></label>
                                    {!!Form::select('estado', '', 
                                    [
                                    'novo' => 'Novo', 
                                    'pendente' => 'Pendente', 
                                    'preparando' => 'Preparando', 
                                    'finalizado' => 'Finalizado'
                                    ])
                                    ->attrs(['class' => 'form-select', 'id' => 'inp-estado'])
                                    ->required()
                                    !!}
                                </div>

                                <input type="hidden" id="adicionais-hidden" name="adicionais">
                                <input type="hidden" id="pizzas-hidden" name="pizzas">
                                <input type="hidden" id="tamanho_id-hidden" name="tamanho_id">

                                <div class="col-12 pt-2">
                                    <button type="submit" class="dash-btn dash-btn-primary w-100 py-2 fs-14 fw-bold justify-content-center">
                                        <i class="ri-add-line fs-18"></i> Adicionar Item
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ─── COLUNA DIREITA: LISTA DE ITENS NA COMANDA ─── --}}
                    <div class="col-12 col-lg-8">
                        <div class="modulo-panel p-4 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="uf-section-title">
                                    <span class="uf-ico"><i class="ri-list-check"></i></span>
                                    Itens Lançados na Comanda
                                    <span class="ms-auto text-muted fs-12 fw-semibold">
                                        {{ count($item->itens) }} {{ count($item->itens) == 1 ? 'item' : 'itens' }}
                                    </span>
                                </div>

                                {{-- Tabela de Itens --}}
                                <div class="tb-wrap mb-3">
                                    <div class="table-responsive">
                                        <table class="table table-centered align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Produto</th>
                                                    <th class="text-center">Qtd.</th>
                                                    <th class="text-end">Unitário</th>
                                                    <th class="text-end">Subtotal</th>
                                                    <th class="text-center">Obs.</th>
                                                    <th class="text-end" style="width: 60px;">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($item->itens as $i)
                                                <tr class="bg-{{ $i->estado }}">
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $i->produto ? $i->produto->nome : 'Produto' }}</div>
                                                        @if($i->funcionario)
                                                        <div class="fs-11 text-muted d-flex align-items-center gap-1 mt-0.5">
                                                            <i class="ri-user-star-line text-indigo"></i> Garçom: {{ $i->funcionario->nome }}
                                                        </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center fw-semibold">{{ __moeda($i->quantidade) }}</td>
                                                    <td class="text-end">R$ {{ __moeda($i->valor_unitario) }}</td>
                                                    <td class="text-end fw-bold text-dark">R$ {{ __moeda($i->sub_total) }}</td>
                                                    <td class="text-center">
                                                        @if(empty($i->observacao))
                                                        <span class="text-muted fs-13">--</span>
                                                        @else
                                                        <button type="button" class="btn btn-sm btn-light border p-1 rounded-circle" onclick="noteSwal('{{ $i->observacao }}')" title="Ver Observação">
                                                            <i class="ri-chat-check-line text-primary fs-15"></i>
                                                        </button>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <form action="{{ route('pedidos-cardapio.destroy-item', $i->id) }}" method="post" id="form-{{$i->id}}" class="m-0">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="button" title="Deletar Item" class="act-btn act-del btn-delete">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                {{-- Detalhes extras do item (adicionais, ponto, pizzas) --}}
                                                @if(sizeof($i->adicionais) > 0)
                                                <tr class="bg-light">
                                                    <td></td>
                                                    <td colspan="5" class="fs-12 text-muted py-1.5">
                                                        <span class="fw-bold text-dark"><i class="ri-add-line text-success"></i> Adicionais:</span> {{ $i->getAdicionaisStr() }}
                                                    </td>
                                                </tr>
                                                @endif

                                                @if($i->ponto_carne)
                                                <tr class="bg-light">
                                                    <td></td>
                                                    <td colspan="5" class="fs-12 text-muted py-1.5">
                                                        <span class="fw-bold text-dark"><i class="ri-restaurant-line text-warning"></i> Ponto da carne:</span> <strong class="text-dark">{{ $i->ponto_carne }}</strong>
                                                    </td>
                                                </tr>
                                                @endif

                                                @if(sizeof($i->pizzas) > 0)
                                                <tr class="bg-light">
                                                    <td></td>
                                                    <td colspan="5" class="fs-12 text-muted py-1.5">
                                                        <span class="fw-bold text-dark"><i class="ri-pie-chart-line text-primary"></i> Sabores:</span> 
                                                        <strong class="text-dark">
                                                            @foreach($i->pizzas as $s)
                                                            1/{{ sizeof($i->pizzas) }} {{ $s->sabor ? $s->sabor->nome : '' }}
                                                            @if(!$loop->last) | @endif
                                                            @endforeach
                                                        </strong>
                                                        <span class="ms-1">- Tamanho: <strong class="text-primary">{{ $i->tamanho ? $i->tamanho->nome : '--' }}</strong></span>
                                                    </td>
                                                </tr>
                                                @endif

                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        <i class="ri-shopping-basket-line fs-32 text-muted opacity-50 d-block mb-1"></i>
                                                        Nenhum item lançado para esta comanda ainda.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Legendas de Estados --}}
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-4 p-2.5 rounded-3 bg-light border">
                                    <span class="fs-11 fw-bold text-uppercase text-muted me-1">Legendas:</span>
                                    <span class="pill pill-blue"><i class="ri-flag-2-fill"></i> Novo</span>
                                    <span class="pill pill-red"><i class="ri-flag-2-fill"></i> Pendente</span>
                                    <span class="pill pill-amber"><i class="ri-flag-2-fill"></i> Preparando</span>
                                    <span class="pill pill-ok"><i class="ri-flag-2-fill"></i> Finalizado</span>
                                </div>
                            </div>

                            {{-- Botão de Finalização / Fechamento da Comanda --}}
                            <div class="pt-3 border-top">
                                <a class="btn btn-lg btn-success w-100 py-3 fw-bold d-flex align-items-center justify-content-between px-4 shadow-sm @if(!$item->status) disabled @endif" style="border-radius: 14px;" href="{{ route('pedidos-cardapio.finish', [$item->id])}}">
                                    <span class="d-flex align-items-center gap-2 fs-15">
                                        <i class="ri-shopping-cart-2-line fs-22"></i>
                                        <span>Fechar Comanda</span>
                                    </span>
                                    <span class="fs-22 font-extrabold tracking-tight">R$ {{ __moeda($item->total) }}</span>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL ADICIONAIS PREMIUM ═══ --}}
<div class="modal fade text-dark" id="modal-adicionais" tabindex="-1" aria-labelledby="modalAdicionaisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern shadow-lg">
            <div class="modal-header-modern">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-ico">
                        <i class="ri-shopping-basket-line"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-modern" id="modalAdicionaisLabel">Escolher Adicionais</h5>
                        <p class="text-muted fs-12 mb-0">Selecione os adicionais desejados para este produto.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            
            <div class="modal-body p-4 bg-white">
                <div class="row g-2 adicionais">
                </div>
                <div class="mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-bold fs-13 text-uppercase">Subtotal com adicionais:</span>
                    <h4 class="m-0 text-success fw-extrabold subtotal_modal"></h4>
                </div>
            </div>

            <div class="modal-footer p-3 bg-light border-top d-flex gap-2 justify-content-end">
                <button type="button" class="dash-btn dash-btn-light px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Fechar
                </button>
                <button id="btn-save-modal" type="button" class="dash-btn dash-btn-primary px-4" data-bs-dismiss="modal">
                    <i class="ri-check-line"></i> Salvar Adicionais
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL SABORES PIZZA PREMIUM ═══ --}}
<div class="modal fade text-dark" id="modal-pizza" tabindex="-1" aria-labelledby="modalPizzaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern shadow-lg">
            <div class="modal-header-modern">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-ico">
                        <i class="ri-pie-chart-line"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-modern" id="modalPizzaLabel">Sabores da Pizza</h5>
                        <p class="text-muted fs-12 mb-0">Selecione o tamanho e os sabores desejados.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-6 col-12 uf-field">
                        <label><i class="ri-expand-up-down-line"></i> Tamanho da Pizza <span class="text-danger">*</span></label>
                        {!!Form::select('tamanho_id', '', ['' => 'Selecione'] + 
                        $tamanhosPizza->pluck('info', 'id')->all())
                        ->attrs(['class' => 'form-select', 'id' => 'inp-tamanho_id'])
                        !!}
                    </div>
                </div>
                
                <div class="row pizzas g-2 mb-3">
                </div>

                <div class="col-md-5 col-12 mt-3 pt-3 border-top uf-field">
                    <label><i class="ri-money-dollar-circle-line"></i> Subtotal da Pizza</label>
                    {!!Form::tel('subtotal_modal', '')
                    ->required()
                    ->attrs(['class' => 'moeda form-control', 'id' => 'inp-subtotal_modal'])
                    !!}
                </div>
            </div>

            <div class="modal-footer p-3 bg-light border-top d-flex gap-2 justify-content-end">
                <button type="button" class="dash-btn dash-btn-light px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Fechar
                </button>
                <button id="btn-save-sabores" type="button" class="dash-btn dash-btn-primary px-4">
                    <i class="ri-check-line"></i> Salvar Sabores
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="/js/pedido.js"></script>
@endsection
