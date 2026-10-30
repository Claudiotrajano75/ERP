@extends('loja.default', ['title' => 'Criar Conta'])

@section('css')
<link href="/assets/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        background-color: #f8fafc !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-selection__rendered {
        line-height: 44px !important;
        font-size: 0.875rem !important;
        color: #1e293b !important;
        padding-left: 1rem !important;
    }
    .select2-selection__arrow {
        height: 44px !important;
        right: 12px !important;
    }
</style>
@endsection

@section('content')

@if($carrinho != [])
    @include('loja.partials.checkout_steps', ['checkoutStep' => 2])
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <form method="POST" action="{{ route('loja.cadastro') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        @csrf
        <input type="hidden" name="link" value="{{ $config->loja_id }}">
        <input type="hidden" id="empresa_id" value="{{ $config->empresa_id }}">

        <!-- Formulário de Cadastro (Esquerda) -->
        <div class="{{ $carrinho == [] ? 'lg:col-span-8 lg:col-start-3' : 'lg:col-span-7' }} space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm space-y-6">
                
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Passo 2 de 3</span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Identificação e Endereço</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Preencha os dados abaixo para emissão da nota e envio seguro do seu pedido.</p>
                </div>

                <!-- Dados Pessoais -->
                <div class="space-y-4 pt-2">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">1. Dados de Acesso e Pessoais</h4>

                    <div>
                        <label for="cad-nome" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nome Completo *
                        </label>
                        <input type="text"
                               name="nome"
                               id="cad-nome"
                               required
                               value="{{ old('nome') }}"
                               placeholder="Ex: João da Silva"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        @if($errors->has('nome'))
                            <span class="text-rose-500 text-xs mt-1 block">{{ $errors->first('nome') }}</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                E-mail *
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   required
                                   value="{{ old('email') }}"
                                   placeholder="seu.email@exemplo.com"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                            @if($errors->has('email'))
                                <span class="text-rose-500 text-xs mt-1 block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div>
                            <label for="telefone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Telefone / WhatsApp *
                            </label>
                            <input type="tel"
                                   name="telefone"
                                   id="telefone"
                                   required
                                   oninput="maskPhone(this)"
                                   value="{{ old('telefone') }}"
                                   placeholder="(00) 00000-0000"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="cpf_cnpj" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                CPF / CNPJ *
                            </label>
                            <input type="text"
                                   name="cpf_cnpj"
                                   id="cpf_cnpj"
                                   required
                                   oninput="maskCpfCnpj(this)"
                                   value="{{ old('cpf_cnpj') }}"
                                   placeholder="000.000.000-00"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label for="cad-senha" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Criar Senha *
                            </label>
                            <input type="password"
                                   name="senha"
                                   id="cad-senha"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Endereço de Entrega com Busca de CEP -->
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">2. Endereço de Envio</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="cad-cep" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                CEP *
                            </label>
                            <input type="text"
                                   name="cep"
                                   id="cad-cep"
                                   required
                                   oninput="maskCep(this)"
                                   value="{{ $carrinho ? $carrinho->cep : old('cep') }}"
                                   placeholder="00000-000"
                                   class="cep w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="rua" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Rua / Logradouro *
                            </label>
                            <input type="text"
                                   name="rua"
                                   id="rua"
                                   required
                                   value="{{ old('rua') }}"
                                   placeholder="Ex: Rua das Flores"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="numero" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Número *
                            </label>
                            <input type="text"
                                   name="numero"
                                   id="numero"
                                   required
                                   value="{{ old('numero') }}"
                                   placeholder="123"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label for="bairro" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Bairro *
                            </label>
                            <input type="text"
                                   name="bairro"
                                   id="bairro"
                                   required
                                   value="{{ old('bairro') }}"
                                   placeholder="Centro"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label for="complemento" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Complemento
                            </label>
                            <input type="text"
                                   name="complemento"
                                   id="complemento"
                                   value="{{ old('complemento') }}"
                                   placeholder="Apto 101"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="inp-cidade_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Cidade / UF *
                        </label>
                        <select required id="inp-cidade_id" name="cidade_id" class="w-full">
                        </select>
                        <input type="hidden" value="{{ old('cidade_id') }}" id="cidade_old_id">
                    </div>
                </div>

                <!-- Aceite dos Termos -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                        <input type="checkbox" name="termos" value="1" required class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        <span>Li e concordo com os termos de compra e políticas de privacidade.</span>
                    </label>
                </div>

                @if($carrinho == [])
                    <button type="submit"
                            class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/25 transition-all">
                        Concluir Cadastro
                    </button>
                @endif
            </div>
        </div>

        <!-- Resumo Lateral do Pedido (Direita) -->
        @if($carrinho != [])
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm space-y-5 sticky top-28">
                    <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100">
                        Resumo da Compra
                    </h3>

                    <!-- Itens -->
                    <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 pr-1">
                        @foreach($carrinho->itens as $i)
                            <div class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                                <div class="min-w-0">
                                    <h5 class="text-xs font-bold text-slate-800 truncate">{{ $i->produto->nome }}</h5>
                                    <span class="text-[11px] text-slate-400">Qtd: {{ number_format($i->quantidade, 0) }}x R$ {{ __moeda($i->valor_unitario) }}</span>
                                </div>
                                <span class="text-xs font-black text-slate-900">R$ {{ __moeda($i->sub_total) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Linhas de Valores -->
                    <div class="space-y-2 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span class="font-bold text-slate-800">R$ {{ __moeda($carrinho->valor_total) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Frete:</span>
                            <span class="font-bold text-slate-800">
                                @if($carrinho->valor_frete == 0)
                                    <span class="text-emerald-600 font-bold">Grátis</span>
                                @else
                                    R$ {{ __moeda($carrinho->valor_frete) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-100">
                            <span>Total Geral:</span>
                            <span class="text-lg text-indigo-600">R$ {{ __moeda($carrinho->valor_total + ($carrinho->valor_frete ?? 0)) }}</span>
                        </div>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit"
                            class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/30 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                        <span>Confirmar e Ir para Pagamento</span>
                        <i class="ri-arrow-right-line"></i>
                    </button>

                    <div class="text-center pt-2">
                        <span class="text-xs text-slate-400">
                            Já tem conta?
                            <a href="{{ route('loja.login', ['link' => $config->loja_id]) }}" class="font-bold text-indigo-600 hover:underline">
                                Faça login aqui
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
            placeholder: "Digite o nome da sua cidade",
            ajax: {
                cache: true,
                url: "/api/buscaCidades",
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

        // Busca automática de CEP
        $(document).on('blur', '#cad-cep', function(){
            var cep = $(this).val().replace(/\D/g, '');
            if(cep.length === 8){
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(r => r.json())
                .then(data => {
                    if(!data.erro){
                        $('#rua').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        if(data.ibge){
                            findCidadeIbge(data.ibge);
                        }
                    }
                }).catch(e => console.error(e));
            }
        });
    });

    function findCidadeIbge(ibge){
        $.get('/api/buscaCidadeCodigoIbge/' + ibge)
        .done(function(data){
            if(data){
                var option = new Option(data.info, data.id, true, true);
                $('#inp-cidade_id').append(option).trigger('change');
            }
        });
    }

    function findCidadeId(id){
        $.get('/api/cidadePorId/' + id)
        .done(function(data){
            if(data){
                var option = new Option(data.info, data.id, true, true);
                $('#inp-cidade_id').append(option).trigger('change');
            }
        });
    }
</script>
@endsection
