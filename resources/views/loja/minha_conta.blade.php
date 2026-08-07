@extends('loja.default', ['title' => 'Minha Conta'])

@section('css')
<link href="/assets/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
    }
    .select2-selection__rendered {
        line-height: 36px !important;
        font-size: 13px;
        color: var(--luxe-brown) !important;
    }
    .select2-selection__arrow {
        height: 36px !important;
    }
    .endereco-card {
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        background: var(--luxe-cream);
        padding: 18px;
        height: 100%;
        transition: var(--transition);
    }
    .endereco-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .endereco-card .endereco-rua {
        font-size: 14px;
        font-weight: 600;
        color: var(--luxe-brown);
    }
    .endereco-card .endereco-info {
        font-size: 12px;
        color: var(--luxe-tan);
        line-height: 1.7;
    }
</style>
@endsection

@section('content')

<div class="section py-5 text-dark">
    <div class="container">

        <!-- ─── SEÇÃO 1: DADOS PESSOAIS ─── -->
        <div class="account-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h4 class="account-title">Seus Dados Pessoais</h4>
                <a href="{{ route('loja.logoff', ['link='.$config->loja_id])}}" class="btn-luxe-outline btn-sm danger">
                    <i class="ri-logout-box-line me-1 align-middle"></i> Sair da Conta
                </a>
            </div>

            <form method="post" action="{{ route('loja.update-cliente', [$cliente->id]) }}" class="row g-3 luxe-form">
                @csrf
                @method('put')
                <input type="hidden" name="link" value="{{ $config->loja_id }}">

                <div class="col-md-4">
                    <label class="required">Nome</label>
                    <input required class="form-control" type="text" value="{{ $cliente->razao_social }}" name="nome">
                    @if($errors->has('nome'))
                    <span class="text-danger fs-11 mt-1 d-block">{{ $errors->first('nome') }}</span>
                    @endif
                </div>

                <div class="col-md-4">
                    <label class="required">E-mail</label>
                    <input required class="form-control" type="email" value="{{ $cliente->email }}" name="email">
                    @if($errors->has('email'))
                    <span class="text-danger fs-11 mt-1 d-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="col-md-4">
                    <label>Nova Senha (deixe em branco para manter)</label>
                    <input class="form-control" type="password" name="senha" placeholder="Digite uma nova senha">
                    @if($errors->has('senha'))
                    <span class="text-danger fs-11 mt-1 d-block">{{ $errors->first('senha') }}</span>
                    @endif
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn-luxe-dark btn-inline">
                        <i class="ri-save-line me-1 align-middle"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- ─── SEÇÃO 2: ENDEREÇOS ─── -->
        <div class="account-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h4 class="account-title">Endereços de Entrega</h4>
                <button class="btn-luxe-dark btn-sm" onclick="novoEndereco()">
                    <i class="ri-add-line me-1 align-middle"></i> Novo Endereço
                </button>
            </div>

            <div class="row g-3">
                @forelse($cliente->enderecosEcommerce as $e)
                <div class="col-md-6 col-12">
                    <div class="endereco-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="endereco-rua mb-0">
                                {{ $e->rua }}, {{ $e->numero }}
                            </h6>
                            <span class="badge {{ $e->padrao ? 'badge-luxe-default' : 'badge-luxe-pending' }}">
                                {{ $e->padrao ? 'Padrão' : 'Secundário' }}
                            </span>
                        </div>
                        <div class="endereco-info mb-3">
                            <div>Bairro: {{ $e->bairro }}</div>
                            <div>CEP: {{ $e->cep }}</div>
                            <div>Cidade: {{ $e->cidade->info }}</div>
                            @if($e->referencia)
                            <div>Complemento: {{ $e->referencia }}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <button class="btn-luxe-outline btn-sm" onclick="editarEndereco('{{ json_encode($e->load('cidade')) }}')">
                                <i class="ri-edit-line"></i> Editar
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state col-12">
                    <i class="ri-map-pin-line"></i>
                    <p>Nenhum endereço cadastrado. Adicione um endereço para agilizar seus pedidos.</p>
                    <button class="btn-luxe btn-inline" onclick="novoEndereco()">
                        <i class="ri-add-line"></i> Cadastrar Endereço
                    </button>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ─── SEÇÃO 3: HISTÓRICO DE PEDIDOS ─── -->
        <div class="account-card">
            <h4 class="account-title mb-4">Histórico de Pedidos</h4>

            @forelse($cliente->pedidosEcommerce as $p)
            <div class="order-history-card">
                <!-- Header do Pedido -->
                <div class="order-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-4 flex-wrap fs-13" style="color:var(--luxe-tan)">
                        <div>
                            <span>Pedido Realizado em:</span>
                            <strong class="d-block" style="color:var(--luxe-brown)">{{ __data_pt($p->created_at) }}</strong>
                        </div>
                        <div>
                            <span>Total Geral:</span>
                            <strong class="d-block text-gold">R$ {{ __moeda($p->valor_total) }}</strong>
                        </div>
                        <div>
                            <span>Pagamento:</span>
                            <strong class="d-block text-uppercase" style="color:var(--luxe-brown)">{{ $p->tipo_pagamento }}</strong>
                        </div>
                    </div>
                    <div>
                        @if($p->status_pagamento == 'approved')
                        <span class="badge-luxe badge-luxe-success"><i class="ri-checkbox-circle-line"></i> PAGO</span>
                        @else
                        <span class="badge-luxe badge-luxe-pending"><i class="ri-time-line"></i> PENDENTE</span>
                        @endif
                    </div>
                </div>

                <!-- Corpo do Pedido -->
                <div class="order-body">
                    <h6 class="fw-bold mb-3 fs-13" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Itens do Pedido</h6>

                    @foreach($p->itens as $i)
                    <div class="row order-item-row align-items-center">
                        <div class="col-md-1 col-3">
                            <img src="{{ $i->produto->img }}" alt="" class="order-item-img img-fluid">
                        </div>
                        <div class="col-md-5 col-9">
                            <h6 class="fw-bold fs-13 mb-1" style="color:var(--luxe-brown)">
                                {{ $i->produto->nome }}
                            </h6>
                            @if($i->produtoVariacao)
                            <span class="badge-luxe badge-luxe-pending fs-11">Opção: {{ $i->produtoVariacao->descricao }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 col-4 mt-2 mt-md-0">
                            <span class="text-muted fs-12">Unitário</span>
                            <strong class="d-block fs-13">R$ {{ __moeda($i->valor_unitario) }}</strong>
                        </div>
                        <div class="col-md-2 col-4 mt-2 mt-md-0">
                            <span class="text-muted fs-12">Quantidade</span>
                            <strong class="d-block fs-13">{{ number_format($i->quantidade, 0) }}</strong>
                        </div>
                        <div class="col-md-2 col-4 mt-2 mt-md-0 text-end">
                            <span class="text-muted fs-12">Subtotal</span>
                            <strong class="d-block fs-13 text-gold">R$ {{ __moeda($i->sub_total) }}</strong>
                        </div>
                    </div>
                    @endforeach

                    <!-- Ações extras do Pedido -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-3" style="border-top:1px solid var(--border-light)">
                        <div class="fs-13" style="color:var(--luxe-tan)">
                            @if($p->observacao)
                            <span>Obs: <strong>{{ $p->observacao }}</strong></span>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            @if($p->tipo_pagamento == 'pix' && $p->status_pagamento != 'approved')
                            <a href="{{ route('loja.nova-chavepix', ['link='.$config->loja_id.'&hash='.$p->hash_pedido]) }}" class="btn-luxe btn-sm">
                                <i class="ri-qr-code-line align-middle me-1"></i> Pagar com PIX
                            </a>
                            @endif

                            @if($p->tipo_pagamento == 'boleto')
                            <a target="_blank" href="{{ $p->link_boleto }}" class="btn-luxe-outline btn-sm">
                                <i class="ri-file-text-line align-middle me-1"></i> Imprimir Boleto
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="ri-inbox-line"></i>
                <h4>Nenhum pedido ainda</h4>
                <p>Quando você finalizar uma compra, o histórico aparecerá aqui.</p>
                <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="btn-luxe btn-inline">
                    Começar a Comprar
                </a>
            </div>
            @endforelse
        </div>

    </div>
</div>

<!-- Modal Endereço -->
<div class="modal fade" id="modal-endereco" tabindex="-1" aria-labelledby="enderecoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form method="post" action="{{ route('loja.store-endereco', ['link='.$config->loja_id]) }}" class="luxe-form">
            @csrf
            <input type="hidden" name="endereco_id" value="" id="endereco_id">

            <div class="modal-content" style="border-radius:var(--radius-lg);border:none;overflow:hidden">
                <div class="modal-header" style="border-bottom:1px solid var(--border-light)">
                    <h5 class="modal-title fw-bold" id="enderecoModalLabel" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Novo Endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-4 col-12">
                        <label class="required">CEP</label>
                        <input required class="form-control cep" data-mask="00000-000" type="text" name="cep" placeholder="00000-000" id="cep">
                    </div>

                    <div class="col-md-8 col-12">
                        <label class="required">Rua</label>
                        <input required class="form-control" type="text" name="rua" id="rua" placeholder="Rua, Av, etc.">
                    </div>

                    <div class="col-md-4 col-12">
                        <label class="required">Número</label>
                        <input required class="form-control" type="text" name="numero" id="numero" placeholder="Ex: 123">
                    </div>

                    <div class="col-md-8 col-12">
                        <label class="required">Cidade</label>
                        <select required class="form-select" id="inp-cidade_id" name="cidade_id" style="width: 100% !important;">
                        </select>
                        <input type="hidden" id="cidade_old_id">
                    </div>

                    <div class="col-md-8 col-12">
                        <label class="required">Bairro</label>
                        <input required class="form-control" type="text" name="bairro" id="bairro" placeholder="Bairro">
                    </div>

                    <div class="col-md-4 col-12 d-flex align-items-end mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="padrao" id="padrao" value="1" style="accent-color:var(--luxe-gold)">
                            <label class="form-check-label fs-13" for="padrao">Padrão</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label>Complemento</label>
                        <input class="form-control" type="text" name="referencia" placeholder="Apartamento, bloco, ponto de referência..." id="complemento">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border-light)">
                    <button type="button" class="btn-luxe-outline btn-sm" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn-luxe-dark btn-sm" style="width:auto;padding:8px 18px">Salvar Endereço</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script src="/assets/vendor/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(function(){
        $("#inp-cidade_id").select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar a cidade",
            dropdownParent: $("#modal-endereco"),
            ajax: {
                cache: true,
                url: path_url + "api/buscaCidades",
                dataType: "json",
                data: function (params) {
                    return { pesquisa: params.term };
                },
                processResults: function (response) {
                    var results = [];
                    $.each(response, function (i, v) {
                        results.push({
                            id: v.id,
                            text: v.info,
                            value: v.id
                        });
                    });
                    return { results: results };
                }
            }
        });
    });

    $(document).on("blur", ".cep", function () {
        let cep = $(this).val().replace(/[^0-9]/g,'');
        if(cep.length == 8){
            $.get('https://viacep.com.br/ws/'+cep+'/json')
            .done((res) => {
                findCidade(res.ibge);
                $('#rua').val(res.logradouro);
                $('#bairro').val(res.bairro);
                $('#complemento').val(res.complemento);
            });
        }
    });

    function findCidade(codigo_ibge){
        $('#inp-cidade_id').html('');
        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        });
    }

    function abrirModalEndereco(){
        var modalEl = document.getElementById('modal-endereco');
        if (window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof $(modalEl).modal === 'function') {
            $(modalEl).modal('show');
        }
    }

    function novoEndereco(){
        $('.modal-title').text('Novo Endereço');
        abrirModalEndereco();

        $('#endereco_id').val('');
        $('#rua').val('');
        $('#numero').val('');
        $('#bairro').val('');
        $('#cep').val('');
        $('#complemento').val('');
        $('#inp-cidade_id').html('');
        $('#padrao').prop('checked', false);
    }

    function editarEndereco(enderecoStr){
        let endereco = JSON.parse(enderecoStr);
        $('.modal-title').text('Editar Endereço');
        abrirModalEndereco();

        $('#endereco_id').val(endereco.id);
        $('#rua').val(endereco.rua);
        $('#numero').val(endereco.numero);
        $('#bairro').val(endereco.bairro);
        $('#cep').val(endereco.cep);
        $('#complemento').val(endereco.referencia);
        $('#padrao').prop('checked', endereco.padrao == 1);

        findCidade(endereco.cidade.codigo);
    }
</script>
@endsection
