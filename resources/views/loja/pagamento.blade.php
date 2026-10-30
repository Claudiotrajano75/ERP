@extends('loja.default', ['title' => 'Pagamento'])

@section('content')

@include('loja.partials.checkout_steps', ['checkoutStep' => 3])

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{
        paymentMethod: '{{ in_array('Pix', $tiposPagamento) ? 'pix' : (in_array('Cartão de credito', $tiposPagamento) ? 'cartao' : (in_array('Boleto', $tiposPagamento) ? 'boleto' : 'deposito')) }}',
        observacao: ''
     }">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- Formulários de Pagamento (Esquerda) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm space-y-6">
                
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Passo 3 de 3</span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Forma de Pagamento</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Selecione como deseja pagar. Todas as transações são 100% criptografadas e seguras.</p>
                </div>

                <!-- Seletores de Métodos de Pagamento em Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @if(in_array('Pix', $tiposPagamento))
                        <button type="button"
                                @click="paymentMethod = 'pix'"
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all font-bold text-xs gap-2"
                                :class="paymentMethod === 'pix' ? 'border-emerald-600 bg-emerald-50/50 text-emerald-800 shadow-sm ring-2 ring-emerald-500/20' : 'border-slate-100 hover:border-slate-200 text-slate-700 bg-slate-50/50'">
                            <i class="ri-qr-code-line text-2xl text-emerald-600"></i>
                            <span>PIX Imediato</span>
                        </button>
                    @endif

                    @if(in_array('Cartão de credito', $tiposPagamento))
                        <button type="button"
                                @click="paymentMethod = 'cartao'"
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all font-bold text-xs gap-2"
                                :class="paymentMethod === 'cartao' ? 'border-indigo-600 bg-indigo-50/50 text-indigo-800 shadow-sm ring-2 ring-indigo-500/20' : 'border-slate-100 hover:border-slate-200 text-slate-700 bg-slate-50/50'">
                            <i class="ri-bank-card-line text-2xl text-indigo-600"></i>
                            <span>Cartão de Crédito</span>
                        </button>
                    @endif

                    @if(in_array('Boleto', $tiposPagamento))
                        <button type="button"
                                @click="paymentMethod = 'boleto'"
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all font-bold text-xs gap-2"
                                :class="paymentMethod === 'boleto' ? 'border-amber-600 bg-amber-50/50 text-amber-800 shadow-sm ring-2 ring-amber-500/20' : 'border-slate-100 hover:border-slate-200 text-slate-700 bg-slate-50/50'">
                            <i class="ri-bill-line text-2xl text-amber-600"></i>
                            <span>Boleto Bancário</span>
                        </button>
                    @endif

                    @if(in_array('Depósito bancário', $tiposPagamento))
                        <button type="button"
                                @click="paymentMethod = 'deposito'"
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all font-bold text-xs gap-2"
                                :class="paymentMethod === 'deposito' ? 'border-violet-600 bg-violet-50/50 text-violet-800 shadow-sm ring-2 ring-violet-500/20' : 'border-slate-100 hover:border-slate-200 text-slate-700 bg-slate-50/50'">
                            <i class="ri-bank-line text-2xl text-violet-600"></i>
                            <span>Depósito / TED</span>
                        </button>
                    @endif
                </div>

                <!-- Formulários Dinâmicos por Método -->
                <div class="pt-4 border-t border-slate-100">

                    <!-- 1. Formulário PIX -->
                    <div x-show="paymentMethod === 'pix'" class="space-y-4">
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-900 text-xs sm:text-sm flex items-start gap-3">
                            <i class="ri-flashlight-line text-lg text-emerald-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold">Aprovação Imediata:</strong>
                                <p class="mt-0.5 text-emerald-800">Ao clicar em finalizar, o QR Code e o código copia-e-cola serão gerados na tela com validade para pagamento.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('loja.pagamento-pix', ['link' => $config->loja_id]) }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="observacao" class="observacao" :value="observacao">
                            <input type="hidden" name="docType" value="CPF">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome do Titular *</label>
                                    <input type="text" name="nome" required placeholder="Primeiro nome" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sobrenome *</label>
                                    <input type="text" name="sobre_nome" required placeholder="Sobrenome" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail para Recibo *</label>
                                    <input type="email" name="email" required placeholder="nome@exemplo.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">CPF do Titular *</label>
                                    <input type="tel" name="docNumber" required oninput="maskCpfCnpj(this)" placeholder="000.000.000-00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm shadow-xl shadow-emerald-600/30 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                                <i class="ri-qr-code-line text-lg"></i>
                                <span>Gerar QR Code PIX</span>
                            </button>
                        </form>
                    </div>

                    <!-- 2. Formulário Cartão de Crédito -->
                    <div x-show="paymentMethod === 'cartao'" style="display: none;" class="space-y-4">
                        <form method="POST" id="paymentFormCartao" action="{{ route('loja.pagamento-cartao', ['link' => $config->loja_id]) }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="observacao" class="observacao" :value="observacao">
                            <input type="hidden" id="docType3" value="CPF">
                            <input type="hidden" name="paymentMethodId" id="paymentMethodId"/>
                            <input type="hidden" name="transactionAmount" id="transactionAmount" value="{{ $carrinho->valor_total }}" />

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="cardholderName" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome no Cartão *</label>
                                    <input type="text" id="cardholderName" data-checkout="cardholderName" required placeholder="Como impresso no cartão" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label for="docNumber" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">CPF do Titular *</label>
                                    <input type="tel" name="docNumber" id="docNumber" required oninput="maskCpfCnpj(this)" placeholder="000.000.000-00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail *</label>
                                <input type="email" name="email" id="email" required placeholder="seu.email@exemplo.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="cardNumber" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Número do Cartão *</label>
                                    <div class="relative">
                                        <input type="tel" id="cardNumber" data-checkout="cardNumber" required placeholder="0000 0000 0000 0000" class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                        <img id="band-img" class="absolute right-3 top-3 h-6 object-contain" alt="">
                                    </div>
                                </div>
                                <div>
                                    <label for="installments" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Parcelamento *</label>
                                    <select name="installments" id="installments" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="securityCode" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">CVC / CVV *</label>
                                    <input type="tel" id="securityCode" data-checkout="securityCode" maxlength="4" required placeholder="123" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none text-center">
                                </div>
                                <div>
                                    <label for="cardExpirationMonth" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Mês (MM) *</label>
                                    <input type="tel" id="cardExpirationMonth" data-checkout="cardExpirationMonth" maxlength="2" required placeholder="MM" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none text-center">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="cardExpirationYear" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Ano (AA) *</label>
                                    <input type="tel" id="cardExpirationYear" data-checkout="cardExpirationYear" maxlength="2" required placeholder="AA" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none text-center">
                                </div>
                            </div>

                            <div style="visibility: hidden; height:0;">
                                <select id="issuer" name="issuer" data-checkout="issuer"></select>
                            </div>

                            <button type="submit" id="btn-cartao"
                                    class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/30 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                                <i class="ri-lock-2-line text-lg"></i>
                                <span>Pagar com Cartão de Crédito</span>
                            </button>
                        </form>
                    </div>

                    <!-- 3. Formulário Boleto -->
                    <div x-show="paymentMethod === 'boleto'" style="display: none;" class="space-y-4">
                        <form method="POST" action="{{ route('loja.pagamento-boleto', ['link' => $config->loja_id]) }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="observacao" class="observacao" :value="observacao">
                            <input type="hidden" name="docType" value="CPF">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome Completo *</label>
                                    <input type="text" name="payerFirstName" required placeholder="Nome e sobrenome" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">CPF do Sacado *</label>
                                    <input type="tel" name="docNumber" required oninput="maskCpfCnpj(this)" placeholder="000.000.000-00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail para envio do boleto *</label>
                                <input type="email" name="payerEmail" required placeholder="seu.email@exemplo.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                            </div>

                            <button type="submit"
                                    class="w-full py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-xl shadow-amber-600/30 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                                <i class="ri-printer-line text-lg"></i>
                                <span>Gerar e Imprimir Boleto</span>
                            </button>
                        </form>
                    </div>

                    <!-- 4. Formulário Depósito -->
                    <div x-show="paymentMethod === 'deposito'" style="display: none;" class="space-y-4">
                        <div class="p-5 rounded-2xl bg-violet-50 border border-violet-100 text-violet-900 text-xs sm:text-sm space-y-2">
                            <h5 class="font-bold flex items-center gap-1.5 text-violet-800">
                                <i class="ri-bank-line text-base"></i> Dados da Conta para Transferência:
                            </h5>
                            <div class="prose max-w-none text-xs text-violet-800">
                                {!! $config->dados_deposito !!}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('loja.pagamento-deposito', ['link' => $config->loja_id]) }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="observacao" class="observacao" :value="observacao">
                            <p class="text-xs text-slate-500">Ao clicar em confirmar, seu pedido será registrado e você poderá anexar o comprovante na área de Meus Pedidos.</p>
                            
                            <button type="submit"
                                    class="w-full py-4 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-extrabold text-sm shadow-xl shadow-violet-600/30 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                                <i class="ri-checkbox-circle-line text-lg"></i>
                                <span>Confirmar Pedido com Depósito</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Resumo do Pedido e Endereço (Direita) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm space-y-5 sticky top-28">
                <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100">
                    Resumo do Pedido
                </h3>

                <!-- Lista de Itens -->
                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1">
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

                <!-- Endereço de Entrega Escolhido -->
                @if($carrinho->endereco)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600">
                        <span class="font-bold text-slate-900 block mb-1">
                            <i class="ri-map-pin-line text-indigo-600"></i> Entregar em:
                        </span>
                        <span>{{ $carrinho->endereco->info ?? '' }}</span>
                    </div>
                @endif

                <!-- Observação do Cliente -->
                <div>
                    <label for="obs-pedido" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Observações para o Pedido
                    </label>
                    <textarea id="obs-pedido"
                              x-model="observacao"
                              rows="2"
                              placeholder="Ex: Deixar na portaria, ponto de referência..."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 outline-none"></textarea>
                </div>

                <!-- Totais -->
                <div class="space-y-2 pt-3 border-t border-slate-100 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Produtos:</span>
                        <span class="font-bold text-slate-800">R$ {{ __moeda($carrinho->valor_total) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Frete ({{ $carrinho->tipo_frete != 0 ? $carrinho->tipo_frete : 'Padrão' }}):</span>
                        <span class="font-bold text-slate-800">
                            @if($carrinho->valor_frete == 0)
                                <span class="text-emerald-600 font-bold">Grátis</span>
                            @else
                                R$ {{ __moeda($carrinho->valor_frete) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-100">
                        <span>Total Final:</span>
                        <span class="text-xl text-indigo-600">R$ {{ __moeda($carrinho->valor_total + ($carrinho->valor_frete ?? 0)) }}</span>
                    </div>
                </div>

                <!-- Selos de Segurança -->
                <div class="pt-2 flex items-center justify-center gap-2 text-slate-400 text-xs">
                    <i class="ri-shield-check-fill text-emerald-500 text-base"></i>
                    <span>Ambiente criptografado 256 bits</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script type="text/javascript">
    $(function(){
        @if(!empty($config->mercadopago_public_key))
            window.Mercadopago.setPublishableKey('{{ $config->mercadopago_public_key }}');
        @endif
    });
</script>
@endsection
