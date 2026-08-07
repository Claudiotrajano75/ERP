@extends('loja.default', ['title' => 'Cadastro'])

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
</style>
@endsection

@section('content')

{{-- Barra de etapas (fluxo de compra) --}}
@if($carrinho != [])
@include('loja.partials.checkout_steps', ['checkoutStep' => 2])
@endif

<div class="section py-5 text-dark">
    <div class="container">
        
        <form method="post" action="{{ route('loja.cadastro') }}" class="row g-4">
            @csrf
            <input type="hidden" name="link" value="{{ $config->loja_id }}">
            <input type="hidden" id="empresa_id" value="{{ $config->empresa_id }}">

            <!-- ─── FORMULÁRIO DE CADASTRO (ESQUERDA) ─── -->
            <div class="@if($carrinho == []) col-lg-8 col-12 mx-auto @else col-lg-7 col-12 @endif">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                    <div class="mb-4">
                        <h3 class="fw-bold text-dark mb-1">Crie sua Conta</h3>
                        <p class="text-muted fs-13">Preencha as informações abaixo para realizar o seu cadastro.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Nome Completo</label>
                            <input required class="form-control" type="text" value="{{ old('nome')}}" name="nome" placeholder="Seu nome completo">
                            @if($errors->has('nome'))
                            <span class="text-danger fs-11 mt-1 d-block">{{ $errors->first('nome') }}</span>
                            @endif
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">E-mail</label>
                            <input required class="form-control" type="email" id="email" name="email" placeholder="nome@exemplo.com" value="{{ old('email')}}">
                            @if($errors->has('email'))
                            <span class="text-danger fs-11 mt-1 d-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Senha</label>
                            <input required class="form-control" type="password" name="senha" placeholder="Crie uma senha" value="{{ old('senha')}}">
                            @if($errors->has('senha'))
                            <span class="text-danger fs-11 mt-1 d-block">{{ $errors->first('senha') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Confirme a Senha</label>
                            <input required class="form-control" type="password" name="repita_senha" placeholder="Repita a senha" value="{{ old('repita_senha')}}">
                        </div>

                        <hr class="my-4 text-muted">
                        <h6 class="fw-bold text-dark mb-2 mt-0">Endereço de Entrega</h6>

                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">CEP</label>
                            <input required class="form-control cep" data-mask="00000-000" type="text" name="cep" placeholder="00000-000" value="{{ $carrinho ? $carrinho->cep : ''}}">
                        </div>
                        <div class="col-md-8 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Rua / Logradouro</label>
                            <input required class="form-control" type="text" name="rua" id="rua" placeholder="Rua, Avenida, etc." value="{{ old('rua')}}">
                        </div>

                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Número</label>
                            <input required class="form-control" type="text" name="numero" id="numero" placeholder="Ex: 123" value="{{ old('numero')}}">
                        </div>

                        <div class="col-md-8 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Cidade</label>
                            <select required class="form-select w-100" id="inp-cidade_id" name="cidade_id" style="width: 100% !important;">
                            </select>
                            <input type="hidden" value="{{ old('cidade_id') }}" id="cidade_old_id">
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Bairro</label>
                            <input required class="form-control" type="text" name="bairro" id="bairro" placeholder="Bairro" value="{{ old('bairro')}}">
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold text-dark fs-12 required">Celular / Telefone</label>
                            <input required data-mask="(00) 00000-0000" class="form-control" type="tel" name="telefone" placeholder="(00) 90000-0000" value="{{ old('telefone') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark fs-12">Complemento / Referência</label>
                            <input class="form-control" type="text" name="referencia" placeholder="Apartamento, bloco, ponto de referência..." id="complemento" value="{{ old('referencia')}}">
                        </div>

                        @if($carrinho == [])
                        <div class="col-12 mt-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="termos" value="1" name="termos">
                                <label class="form-check-label fs-13" for="termos">
                                    Eu li e aceito os <a href="#!" data-bs-toggle="modal" data-bs-target="#modal-termos-condicoes">Termos e Condições</a>
                                </label>
                                @if($errors->has('termos'))
                                <span class="text-danger fs-11 d-block mt-1">{{ $errors->first('termos') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn-luxe-dark w-100">
                                Confirmar Cadastro
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- ─── RESUMO DO PEDIDO E CONFIRMAÇÃO (DIREITA) ─── -->
            @if($carrinho != [])
            <div class="col-lg-5 col-12">
                <div class="summary-card">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-4">Seu Pedido</h5>
                    
                    <div class="mb-4">
                        @foreach($carrinho->itens as $i)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fw-semibold text-dark fs-14">
                                    {{ $i->produto->nome }}
                                </span>
                                <span class="text-muted d-block fs-12">
                                    Qtd: {{ number_format($i->quantidade, 0) }} x R$ {{ __moeda($i->valor_unitario) }}
                                </span>
                            </div>
                            <strong class="text-dark fs-14">R$ {{ __moeda($i->sub_total) }}</strong>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-3 fs-14 pb-2 border-bottom">
                        <span class="text-muted">Entrega:</span>
                        <strong class="text-dark">R$ {{ __moeda($carrinho->valor_frete) }}</strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-16 text-dark">TOTAL:</span>
                        <strong class="text-success fs-18">R$ {{ __moeda($carrinho->valor_total) }}</strong>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="termos-checkout" value="1" name="termos">
                        <label class="form-check-label fs-13" for="termos-checkout">
                            Eu li e aceito os <a href="#!" data-bs-toggle="modal" data-bs-target="#modal-termos-condicoes">Termos e Condições</a>
                        </label>
                        @if($errors->has('termos'))
                        <span class="text-danger fs-11 d-block mt-1">{{ $errors->first('termos') }}</span>
                        @endif
                    </div>

                    <button type="submit" class="btn-luxe-dark w-100 mb-3">
                        Confirmar Cadastro & Continuar
                    </button>

                    <div class="text-center">
                        <span class="text-muted fs-13">
                            Já tem cadastro? 
                            <a class="fw-bold" href="{{ route('loja.login', ['link='.$config->loja_id])}}">
                                Fazer Login
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            @endif

        </form>
    </div>
</div>

<!-- Modal Termos -->
<div class="modal fade" id="modal-termos-condicoes" tabindex="-1" aria-labelledby="termosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="termosModalLabel">Termos e Condições</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-14 lh-lg text-secondary">
                {!! $config->termos_condicoes !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-luxe-outline btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="/assets/vendor/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(function(){
        let cidade_old_id = $('#cidade_old_id').val();
        if(cidade_old_id){
            findCidadeId(cidade_old_id);
        }
        
        $("#inp-cidade_id").select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite a sua cidade",
            dropdownParent: $('.card'),
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

        setTimeout(() => {
            let cep = $(".cep").val().replace(/[^0-9]/g,'');
            if(cep.length > 7){
                buscaCep();
            }
        }, 10);
    });

    $(document).on("blur", "#email", function () {
        let email = $(this).val();
        let empresa_id = $('#empresa_id').val();
        if (email) {
            $.get(path_url + "api/ecommerce/valida-email", {
                email: email,
                empresa_id: empresa_id
            })
            .fail((err) => {
                if(err.status == 402){
                    Swal.fire("Erro", "Este e-mail já está cadastrado no sistema.", "error");
                    $('#email').val('');
                }
            });
        }
    });

    $(document).on("blur", ".cep", function () {
        buscaCep();
    });

    function buscaCep(){
        let cep = $(".cep").val().replace(/[^0-9]/g,'');
        if(cep.length == 8){
            $.get('https://viacep.com.br/ws/'+cep+'/json')
            .done((res) => {
                findCidade(res.ibge);
                $('#rua').val(res.logradouro);
                $('#bairro').val(res.bairro);
                $('#complemento').val(res.complemento);
            });
        }
    }

    function findCidade(codigo_ibge){
        $('#inp-cidade_id').html('');
        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        });
    }

    function findCidadeId(id){
        $('#inp-cidade_id').html('');
        $.get(path_url + "api/cidadePorId/" + id)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        });
    }
</script>
@endsection
