@extends('loja.default', ['title' => 'Meu Carrinho'])

@section('content')

@include('loja.partials.checkout_steps', ['checkoutStep' => 1])

@php
    $itens = ($item && !empty($item->itens)) ? $item->itens : collect();
    $subtotal = ($item && !empty($item->valor_total)) ? $item->valor_total : 0;
    $frete = ($item && $item->valor_frete !== null) ? $item->valor_frete : 0;
    $totalFinal = $subtotal + $frete;
    $freteMinimo = $config->frete_gratis_valor ?? 0;
    $faltaFrete = ($freteMinimo > 0 && $subtotal < $freteMinimo) ? ($freteMinimo - $subtotal) : 0;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{
        cep: '{{ $item->cep ?? '' }}',
        calculandoFrete: false,
        opcoesFreteHtml: '',
        async calcularFrete() {
            const cepLimpo = this.cep.replace(/\D/g, '');
            if (cepLimpo.length !== 8) {
                alert('Por favor, informe um CEP válido com 8 dígitos.');
                return;
            }
            this.calculandoFrete = true;
            try {
                const res = await fetch(`/api/ecommerce/calcular-frete?carrinho_id={{ $item->id ?? '' }}&cep=${cepLimpo}`);
                if (res.ok) {
                    const html = await res.text();
                    this.opcoesFreteHtml = html;
                    document.getElementById('frete-options-container').innerHTML = html;
                    this.bindRadiosFrete();
                } else {
                    alert('Não foi possível calcular o frete para este CEP.');
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.calculandoFrete = false;
            }
        },
        bindRadiosFrete() {
            document.querySelectorAll('.radio-frete').forEach(r => {
                r.addEventListener('change', (e) => {
                    const v = parseFloat(e.target.dataset.valor || 0);
                    const endId = e.target.dataset.endereco || '';
                    document.getElementById('valor_frete_input').value = v;
                    document.getElementById('endereco_id_input').value = endId;
                    document.getElementById('btn-finalizar-carrinho').removeAttribute('disabled');
                    document.getElementById('btn-finalizar-carrinho').classList.remove('opacity-50', 'cursor-not-allowed');
                });
            });
        }
     }">

    @if(count($itens) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Coluna Esquerda: Itens do Carrinho -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Barra de Frete Grátis -->
                @if($freteMinimo > 0)
                    <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-between gap-3 text-xs sm:text-sm">
                        <div class="flex items-center gap-2">
                            <i class="ri-truck-fill text-indigo-600 text-lg"></i>
                            @if($faltaFrete > 0)
                                <span class="text-slate-700">
                                    Adicione mais <strong class="text-indigo-600 font-bold">R$ {{ __moeda($faltaFrete) }}</strong> para ganhar <strong>Frete Grátis!</strong>
                                </span>
                            @else
                                <span class="text-emerald-700 font-bold">
                                    Parabéns! Seu pedido atingiu o valor de Frete Grátis.
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}" class="text-indigo-600 hover:text-indigo-700 font-bold whitespace-nowrap">
                            + Produtos
                        </a>
                    </div>
                @endif

                <!-- Cartão com a Lista de Itens -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-lg font-black text-slate-900">
                            Produtos no Carrinho
                        </h2>
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                            {{ count($itens) }} {{ count($itens) == 1 ? 'item' : 'itens' }}
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100 p-4 sm:p-6">
                        @foreach($itens as $ci)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                                
                                <!-- Foto e Nome -->
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0">
                                        <img src="{{ $ci->produto->img }}" alt="{{ $ci->produto->nome }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 line-clamp-2">
                                            {{ $ci->produto->nome }}
                                        </h4>
                                        @if($ci->variacao)
                                            <span class="text-xs text-slate-500 mt-0.5 block">
                                                Opção: <strong class="text-slate-700">{{ $ci->variacao->descricao }}</strong>
                                            </span>
                                        @endif
                                        <span class="text-xs text-slate-400 mt-1 block">
                                            Unitário: R$ {{ __moeda($ci->valor_unitario) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Controles e Subtotal -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-50">
                                    <!-- Formulário de Quantidade -->
                                    <form action="{{ route('loja.atualiza-quantidade', [$ci->id]) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="link" value="{{ $config->loja_id }}">
                                        <div class="flex items-center border border-slate-200 rounded-2xl bg-white overflow-hidden">
                                            <button type="submit" name="quantidade" value="{{ max(1, $ci->quantidade - 1) }}"
                                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold transition-colors">
                                                -
                                            </button>
                                            <span class="w-9 text-center text-xs font-bold text-slate-800">
                                                {{ number_format($ci->quantidade, 0) }}
                                            </span>
                                            <button type="submit" name="quantidade" value="{{ $ci->quantidade + 1 }}"
                                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold transition-colors">
                                                +
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[90px]">
                                        <span class="text-sm sm:text-base font-black text-slate-900">
                                            R$ {{ __moeda($ci->sub_total) }}
                                        </span>
                                    </div>

                                    <!-- Botão de Remoção -->
                                    <form action="{{ route('loja.remove-item', [$ci->id]) }}?link={{ $config->loja_id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors"
                                                title="Remover produto do carrinho">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Continuar Comprando -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}"
                       class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                        <i class="ri-arrow-left-line"></i>
                        <span>Continuar Comprando</span>
                    </a>
                </div>
            </div>

            <!-- Coluna Direita: Resumo do Pedido & Cálculo de Frete -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-5 sticky top-28">
                    <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100">
                        Resumo do Pedido
                    </h3>

                    <!-- Linhas do Resumo -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal ({{ count($itens) }} {{ count($itens) == 1 ? 'item' : 'itens' }})</span>
                            <span class="font-bold text-slate-800">R$ {{ __moeda($subtotal) }}</span>
                        </div>

                        <div class="flex justify-between text-slate-600">
                            <span>Frete</span>
                            <span class="font-bold text-slate-800">
                                @if($item->valor_frete !== null)
                                    @if($item->valor_frete == 0)
                                        <span class="text-emerald-600">Grátis</span>
                                    @else
                                        R$ {{ __moeda($item->valor_frete) }}
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">Calcule abaixo</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Formulário de Frete -->
                    <form action="{{ route('loja.carrinho-setar-frete', ['link' => $config->loja_id]) }}" method="POST" id="form-frete-checkout" class="pt-3 border-t border-slate-100 space-y-3">
                        @csrf
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Calcular Entrega e Prazo
                        </label>
                        <div class="flex gap-2">
                            <input type="text"
                                   name="cep"
                                   x-model="cep"
                                   oninput="maskCep(this)"
                                   placeholder="00000-000"
                                   maxlength="9"
                                   class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                            <button type="button"
                                    @click="calcularFrete()"
                                    :disabled="calculandoFrete"
                                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1">
                                <i class="ri-truck-line text-sm" x-show="!calculandoFrete"></i>
                                <i class="ri-loader-4-line animate-spin text-sm" x-show="calculandoFrete" style="display: none;"></i>
                                <span x-text="calculandoFrete ? 'Calculando...' : 'Calcular'"></span>
                            </button>
                        </div>

                        <!-- Opções de Frete Retornadas -->
                        <div id="frete-options-container" class="space-y-2 pt-2">
                            @if(!empty($dataFrete))
                                {!! $dataFrete !!}
                            @else
                                @if($config->habilitar_retirada)
                                    <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl hover:border-indigo-400 cursor-pointer text-xs">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="tipo_frete" value="0" data-valor="0" class="radio-frete text-indigo-600 focus:ring-indigo-500" @if($item->tipo_frete == '0') checked @endif>
                                            <span class="font-medium text-slate-700">Retirada na Loja</span>
                                        </div>
                                        <span class="font-bold text-emerald-600">Grátis</span>
                                    </label>
                                @endif
                                @if($config->frete_gratis_valor > 0 && $config->frete_gratis_valor <= $item->valor_total)
                                    <label class="flex items-center justify-between p-3 border border-emerald-200 bg-emerald-50/50 rounded-xl cursor-pointer text-xs">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="tipo_frete" value="gratis" data-valor="0" class="radio-frete text-emerald-600 focus:ring-emerald-500" @if($item->tipo_frete == 'gratis') checked @endif>
                                            <span class="font-bold text-emerald-800">Frete Grátis Especial</span>
                                        </div>
                                        <span class="font-bold text-emerald-600">Grátis</span>
                                    </label>
                                @endif
                            @endif
                        </div>

                        <input type="hidden" name="valor_frete" id="valor_frete_input" value="{{ $item->valor_frete }}">
                        <input type="hidden" name="endereco_id" id="endereco_id_input" value="{{ $item->endereco_id }}">

                        <!-- Total Final -->
                        <div class="pt-4 border-t border-slate-100 flex items-baseline justify-between">
                            <span class="text-sm font-bold text-slate-700">Total a Pagar</span>
                            <div class="text-right">
                                <span class="text-2xl font-black text-slate-900 tracking-tight">
                                    R$ {{ __moeda($totalFinal) }}
                                </span>
                                <span class="text-[11px] text-slate-400 block">em até 10x no cartão</span>
                            </div>
                        </div>

                        <!-- Botão de Finalização -->
                        <button type="submit"
                                id="btn-finalizar-carrinho"
                                @if($item->valor_frete === null && $item->tipo_frete === null) disabled class="w-full py-4 rounded-2xl bg-indigo-600 text-white font-extrabold text-sm opacity-50 cursor-not-allowed transition-all shadow-md flex items-center justify-center gap-2"
                                @else class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm transition-all shadow-xl shadow-indigo-600/30 hover:scale-[1.02] flex items-center justify-center gap-2" @endif>
                            <i class="ri-lock-2-line text-lg"></i>
                            <span>Ir para o Pagamento</span>
                        </button>

                        @if($item->valor_frete === null && $item->tipo_frete === null)
                            <p class="text-[11px] text-center text-slate-400">
                                Escolha uma opção de frete para prosseguir.
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Estado Vazio Amigável -->
        <div class="max-w-lg mx-auto py-16 px-6 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-5 text-3xl">
                <i class="ri-shopping-cart-line"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">Seu carrinho está vazio</h3>
            <p class="text-xs sm:text-sm text-slate-500 mb-6">
                Você ainda não adicionou nenhum produto. Explore nossas categorias e encontre as melhores ofertas para você!
            </p>
            <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 transition-all">
                <span>Ir para a Página Inicial</span>
                <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    @endif

    <!-- Seção de Sugestões de Produtos -->
    @if(isset($sugestoes) && count($sugestoes) > 0)
        <div class="mt-20 pt-12 border-t border-slate-200">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Aproveite e leve junto</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Você também pode gostar</h3>
                </div>
            </div>

            <x-loja.product-carousel :produtos="$sugestoes" :config="$config" id="carousel-sugestoes" />
        </div>
    @endif
</div>

@endsection
