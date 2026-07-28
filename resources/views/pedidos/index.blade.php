@extends('layouts.app', ['title' => 'Pedidos (Comandas)'])

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

    /* ─── Comanda Card Premium ─── */
    .comanda-link {
        text-decoration: none !important;
        color: inherit !important;
    }
    .comanda-card {
        background: #fff;
        border: 1px solid #eef0f5;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .comanda-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(48, 43, 99, 0.08);
        border-color: #d1d5db;
    }
    .comanda-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .comanda-card-number {
        font-size: 18px;
        font-weight: 800;
        color: #1f2937;
    }
    .comanda-card-body {
        padding: 20px;
        flex-grow: 1;
    }
    .comanda-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #4b5563;
        font-size: 13px;
    }
    .comanda-meta-item i {
        font-size: 16px;
        color: #9ca3af;
    }
    .comanda-total {
        font-size: 20px;
        font-weight: 800;
        color: #2e7d32;
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px dashed #e5e7eb;
        padding-top: 12px;
    }
    .comanda-card-footer {
        padding: 12px 20px;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
    }
    .comanda-card-footer .btn {
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ─── Badge Pulse Fechamento ─── */
    .badge-fechar {
        background-color: #ef4444;
        color: #fff;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        animation: pulse-danger 1.5s infinite;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    @keyframes pulse-danger {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.85; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Empty State */
    .modulo-empty { padding: 48px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-restaurant-line"></i>
                                Pedidos (Comandas)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie a abertura, consumo e fechamento de comandas do cardápio.
                            </p>
                        </div>
                        <div>
                            <button class="btn btn-light btn-sm px-3 text-dark d-flex align-items-center gap-1 fw-semibold" type="button" data-bs-toggle="modal" data-bs-target="#modal-comanda">
                                <i class="ri-add-circle-line fs-18"></i> Abrir comanda
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    {{-- Grid de Comandas --}}
                    <div class="row g-3">
                        @forelse($data as $item)
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="comanda-card">
                                <a class="comanda-link flex-grow-1" href="{{ route('pedidos-cardapio.show', [$item->id]) }}">
                                    <div class="comanda-card-header">
                                        <span class="comanda-card-number">Comanda #{{ $item->comanda }}</span>
                                        @if(!$item->em_atendimento)
                                        <span class="badge-fechar">
                                            <i class="ri-alert-line"></i> Fechar
                                        </span>
                                        @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            Em Aberto
                                        </span>
                                        @endif
                                    </div>

                                    <div class="comanda-card-body">
                                        <div class="comanda-meta-item">
                                            <i class="ri-user-line"></i>
                                            <span>Cliente: <strong>{{ $item->cliente_nome != "" ? $item->cliente_nome : 'Não identificado' }}</strong></span>
                                        </div>
                                        <div class="comanda-meta-item">
                                            <i class="ri-bookmark-3-line"></i>
                                            <span>Mesa: <strong>{{ $item->mesa ? $item->mesa : '--' }}</strong></span>
                                        </div>

                                        <div class="comanda-total">
                                            <span class="fs-12 text-muted fw-normal">Total</span>
                                            <span>R$ {{ __moeda($item->total) }}</span>
                                        </div>
                                    </div>
                                </a>

                                @if(__isAdmin() || sizeof($item->itens) == 0)
                                <div class="comanda-card-footer">
                                    <form action="{{ route('pedidos-cardapio.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-delete w-100 d-flex align-items-center justify-content-center gap-1 py-1">
                                            <i class="ri-delete-bin-line"></i> Remover comanda
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="modulo-empty">
                                <i class="ri-inbox-2-line"></i>
                                <p>Nenhuma comanda aberta no momento.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL ABERTURA DE COMANDA --}}
<div class="modal fade text-dark" id="modal-comanda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <form action="{{ route('pedidos-cardapio.store') }}" method="post" class="m-0">
                @csrf
                <div class="modal-header modulo-header-gradient py-3 px-4">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="exampleModalLabel">
                        <i class="ri-add-circle-line"></i> Abertura de Comanda
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">

                        <div class="col-md-4">
                            {!!Form::text('comanda', 'Número comanda')
                            ->required()
                            ->attrs(['data-mask' => 'AAAAAAAA', 'class' => 'form-control'])
                            !!}
                        </div>

                        <div class="col-md-4">
                            {!!Form::tel('mesa', 'Mesa')
                            ->attrs(['class' => 'form-control'])
                            !!}
                        </div>

                        <div class="col-md-4">
                            {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-control'])
                            !!}
                        </div>

                        <div class="col-md-6">
                            {!!Form::text('cliente_nome', 'Cliente nome')
                            ->attrs(['class' => 'form-control'])
                            !!}
                        </div>

                        <div class="col-md-6">
                            {!!Form::text('cliente_fone', 'Cliente telefone')
                            ->attrs(['class' => 'fone form-control'])
                            !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-white border-top d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary px-3" style="border-radius: 8px;" data-bs-dismiss="modal">
                        <i class="ri-close-line align-middle me-1"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">
                        <i class="ri-check-line align-middle me-1"></i> Abrir Comanda
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
                    placeholder: "Digite para buscar o cliente",
                    width: "100%",

                    ajax: {
                        cache: true,
                        url: path_url + "api/clientes/pesquisa",
                        dataType: "json",
                        data: function (params) {
                            console.clear();
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
        }, 10)
    })

    $('body').on('change', '#inp-cliente_id', function () {
        let id = $(this).val()
        $.get(path_url + 'api/clientes/find/'+id)
        .done((success) => {
            $('#inp-cliente_nome').val(success.razao_social)
            $('#inp-cliente_fone').val(success.telefone)
        })
        .fail((err) => {
            console.log(err)
        })
    });
</script>
@endsection


