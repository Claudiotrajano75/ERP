@extends('layouts.app', ['title' => 'Pedidos (Comandas)'])

@section('css')
<style type="text/css">
    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); pointer-events: none; }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
    .stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 20px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; margin-bottom: 6px; }
    .filter-wrap .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; transition: all .15s ease; }
    .filter-wrap .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Comanda Card Premium ─── */
    .comanda-link { text-decoration: none !important; color: inherit !important; display: flex; flex-direction: column; flex-grow: 1; }
    .comanda-card {
        background: #fff;
        border: 1px solid #e9ecf3;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(16, 24, 40, 0.04);
        transition: all 0.22s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }
    .comanda-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(79, 70, 229, 0.08);
        border-color: #c7d2fe;
    }
    .comanda-card-header {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fbfcfe;
    }
    .comanda-card-number {
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .comanda-card-body {
        padding: 18px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .comanda-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #475569;
        font-size: 12.5px;
    }
    .comanda-meta-item i {
        font-size: 15px;
        color: #94a3b8;
    }
    .comanda-total-box {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
    }
    .comanda-total-val {
        font-size: 20px;
        font-weight: 800;
        color: #059669;
        letter-spacing: -0.5px;
    }
    .comanda-card-footer {
        padding: 10px 18px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }

    /* ─── Badges de Status (Pills) ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
    .pill-ok { background: #dcfce7; color: #15803d; }
    .pill-no { background: #f1f5f9; color: #64748b; }
    .pill-amber { background: #fef3c7; color: #b45309; }
    .pill-red { background: #fee2e2; color: #b91c1c; }
    .badge-fechar {
        background-color: #fee2e2;
        color: #dc2626;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        animation: pulse-danger 1.6s infinite;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    @keyframes pulse-danger {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.04); opacity: 0.85; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* ─── Modal de Abertura de Comanda ─── */
    .modal-dialog-modern { max-width: 650px; }
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
    .uf-field label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    .uf-field label i { color: #a8a8c0; font-size: 12px; }
    .uf-field .form-control, .uf-field .form-select { height: 42px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* Alinhamento Select2 */
    .modal .select2-container .select2-selection--single {
        height: 42px !important;
        line-height: 42px !important;
        border: 1px solid #dcdce9 !important;
        border-radius: 10px !important;
        background: #fcfdfe !important;
        font-size: 13.5px;
    }
    .modal .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        color: #1f2937;
        padding-left: 12px;
    }
    .modal .select2-container .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }

    /* ─── Estado Vazio ─── */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state i { font-size: 54px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state h5 { color: #334155; font-weight: 700; margin-bottom: 4px; }
    .empty-state p { color: #94a3b8; font-size: 13.5px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO PADRÃO DO ERP ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-restaurant-2-line"></i>
                            Pedidos (Comandas)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie a abertura, consumo em tempo real e fechamento de comandas do salão.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('pedidos-cardapio.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line"></i> Atualizar
                        </a>
                        <button class="dash-btn dash-btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-comanda">
                            <i class="ri-add-circle-line"></i> Abrir Comanda
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ CARDS DE ESTATÍSTICA (KPI) ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Comandas Abertas</div>
                                    <div class="st-value">{{ $stats['total'] ?? count($data) }}</div>
                                    <div class="st-sub">Em atendimento no momento</div>
                                </div>
                                <div class="st-icon"><i class="ri-file-list-3-line"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Em Consumo</div>
                                    <div class="st-value">{{ $stats['em_atendimento'] ?? 0 }}</div>
                                    <div class="st-sub">Consumindo itens</div>
                                </div>
                                <div class="st-icon"><i class="ri-time-line"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Solicitou Fechamento</div>
                                    <div class="st-value">{{ $stats['para_fechar'] ?? 0 }}</div>
                                    <div class="st-sub">Aguardando encerramento</div>
                                </div>
                                <div class="st-icon"><i class="ri-alarm-warning-line"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total em Consumo</div>
                                    <div class="st-value">R$ {{ __moeda($stats['total_valor'] ?? 0) }}</div>
                                    <div class="st-sub">Soma das comandas ativas</div>
                                </div>
                                <div class="st-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ FILTRO DE BUSCA RÁPIDA ═══ --}}
                <div class="filter-wrap">
                    <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Comandas</h5>
                    <div class="mt-3">
                        <form method="GET" action="{{ route('pedidos-cardapio.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3 col-12">
                                    <label><i class="ri-qr-code-line"></i> Número da Comanda</label>
                                    <input type="text" name="comanda" value="{{ request('comanda') }}" class="form-control" placeholder="Ex: 01, 15...">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label><i class="ri-bookmark-3-line"></i> Mesa</label>
                                    <input type="text" name="mesa" value="{{ request('mesa') }}" class="form-control" placeholder="Ex: 01, 04...">
                                </div>
                                <div class="col-md-4 col-12">
                                    <label><i class="ri-user-line"></i> Nome do Cliente</label>
                                    <input type="text" name="cliente" value="{{ request('cliente') }}" class="form-control" placeholder="Nome do cliente...">
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="d-flex gap-2 w-100">
                                        <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px; height: 40px;">
                                            <i class="ri-search-line"></i> Buscar
                                        </button>
                                        <a class="btn btn-light border px-3 d-flex align-items-center justify-content-center" href="{{ route('pedidos-cardapio.index') }}" title="Limpar Filtros" style="border-radius:10px; height: 40px;">
                                            <i class="ri-eraser-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ═══ GRID DE COMANDAS ═══ --}}
                <div class="row g-3">
                    @forelse($data as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                        <div class="comanda-card">
                            <a class="comanda-link" href="{{ route('pedidos-cardapio.show', [$item->id]) }}">
                                <div class="comanda-card-header">
                                    <span class="comanda-card-number">
                                        <i class="ri-coupon-3-line text-primary"></i> Comanda #{{ $item->comanda }}
                                    </span>
                                    @if(!$item->em_atendimento)
                                        <span class="badge-fechar">
                                            <i class="ri-alert-line"></i> Fechar
                                        </span>
                                    @else
                                        <span class="pill pill-ok">
                                            <i class="ri-checkbox-circle-fill"></i> Em Aberto
                                        </span>
                                    @endif
                                </div>

                                <div class="comanda-card-body">
                                    <div>
                                        <div class="comanda-meta-item">
                                            <i class="ri-user-3-line"></i>
                                            <span class="text-truncate">Cliente: <strong class="text-dark">{{ $item->cliente_nome != "" ? $item->cliente_nome : 'Não identificado' }}</strong></span>
                                        </div>
                                        <div class="comanda-meta-item">
                                            <i class="ri-bookmark-3-line"></i>
                                            <span>Mesa: <strong class="text-dark">{{ $item->mesa ? $item->mesa : '--' }}</strong></span>
                                        </div>
                                        @if($item->created_at)
                                        <div class="comanda-meta-item">
                                            <i class="ri-time-line"></i>
                                            <span class="text-muted fs-11">Aberta às {{ $item->created_at->format('H:i') }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="comanda-total-box">
                                        <span class="fs-12 text-muted fw-bold text-uppercase">Total</span>
                                        <span class="comanda-total-val">R$ {{ __moeda($item->total) }}</span>
                                    </div>
                                </div>
                            </a>

                            @if(__isAdmin() || sizeof($item->itens) == 0)
                            <div class="comanda-card-footer">
                                <form action="{{ route('pedidos-cardapio.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-outline-danger btn-delete btn-sm w-100 d-flex align-items-center justify-content-center gap-1 py-1" style="border-radius: 8px;">
                                        <i class="ri-delete-bin-line"></i> Remover comanda
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state bg-white rounded-3 border">
                            <i class="ri-inbox-2-line"></i>
                            <h5>Nenhuma comanda aberta no momento</h5>
                            <p>Clique no botão <strong>"Abrir Comanda"</strong> acima para registrar um novo atendimento.</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- ═══ RODAPÉ COM CONTAGEM ═══ --}}
                @if(count($data) > 0)
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 pt-2 border-top">
                    <div class="fs-12 text-muted">
                        Exibindo <strong>{{ count($data) }}</strong> comandas abertas
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL ABERTURA DE COMANDA PREMIUM ═══ --}}
<div class="modal fade text-dark" id="modal-comanda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-modern">
        <div class="modal-content modal-content-modern shadow-lg">
            <form action="{{ route('pedidos-cardapio.store') }}" method="post" class="m-0">
                @csrf
                
                {{-- Cabeçalho da Modal Modernizado --}}
                <div class="modal-header-modern">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-ico">
                            <i class="ri-add-circle-fill"></i>
                        </div>
                        <div>
                            <h5 class="modal-title-modern">Abertura de Comanda</h5>
                            <p class="text-muted fs-12 mb-0">Preencha os dados da comanda para iniciar o atendimento.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6 col-12 uf-field">
                            <label><i class="ri-qr-code-line"></i> Número da Comanda <span class="text-danger">*</span></label>
                            {!!Form::text('comanda', '')
                            ->required()
                            ->attrs(['data-mask' => 'AAAAAAAA', 'class' => 'form-control', 'placeholder' => 'Ex: 01, 10...'])
                            !!}
                        </div>

                        <div class="col-md-6 col-12 uf-field">
                            <label><i class="ri-bookmark-3-line"></i> Mesa</label>
                            {!!Form::tel('mesa', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 01, 05...'])
                            !!}
                        </div>

                        <div class="col-12 uf-field">
                            <label><i class="ri-user-search-line"></i> Buscar Cliente Cadastrado</label>
                            {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-control'])
                            !!}
                            <span class="fs-11 text-muted mt-1 d-block">Opcional. Caso selecione, os dados abaixo serão preenchidos automaticamente.</span>
                        </div>

                        <div class="col-md-6 col-12 uf-field">
                            <label><i class="ri-user-line"></i> Nome do Cliente</label>
                            {!!Form::text('cliente_nome', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Identificação do cliente'])
                            !!}
                        </div>

                        <div class="col-md-6 col-12 uf-field">
                            <label><i class="ri-phone-line"></i> Telefone / WhatsApp</label>
                            {!!Form::text('cliente_fone', '')
                            ->attrs(['class' => 'fone form-control', 'placeholder' => '(00) 00000-0000'])
                            !!}
                        </div>
                    </div>
                </div>

                <div class="modal-footer p-3 bg-light border-top d-flex gap-2 justify-content-end">
                    <button type="button" class="dash-btn dash-btn-light px-3" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="submit" class="dash-btn dash-btn-primary px-4">
                        <i class="ri-check-line"></i> Abrir Comanda
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        setTimeout(() => {
            $('.modal .select2').each(function () {
                $(this).select2({
                    minimumInputLength: 2,
                    dropdownParent: $(this).parent(),
                    language: "pt-BR",
                    placeholder: "Digite para buscar o cliente...",
                    width: "100%",
                    ajax: {
                        cache: true,
                        url: path_url + "api/clientes/pesquisa",
                        dataType: "json",
                        data: function (params) {
                            var query = {
                                pesquisa: params.term,
                                empresa_id: $("#empresa_id").val(),
                            };
                            return query;
                        },
                        processResults: function (response) {
                            var results = [];
                            $.each(response, function (i, v) {
                                var o = {};
                                o.id = v.id;
                                o.text = v.razao_social + " - " + v.cpf_cnpj;
                                o.value = v.id;
                                results.push(o);
                            });
                            return {
                                results: results,
                            };
                        },
                    },
                });
            });
        }, 100);
    });

    $('body').on('change', '#inp-cliente_id', function () {
        let id = $(this).val();
        if(id) {
            $.get(path_url + 'api/clientes/find/' + id)
            .done((success) => {
                $('#inp-cliente_nome').val(success.razao_social);
                $('#inp-cliente_fone').val(success.telefone);
            })
            .fail((err) => {
                console.log(err);
            });
        }
    });
</script>
@endsection
