{{-- Carrinho Lateral Deslizante (Cart Drawer) com Alpine.js --}}
@props(['carrinho' => null, 'config'])

@php
    $itensCollection = ($carrinho && !empty($carrinho->itens)) ? $carrinho->itens : collect();
    $initialItens = $itensCollection->map(function($ci) {
        return [
            'id' => $ci->id,
            'nome' => $ci->produto ? $ci->produto->nome : 'Produto',
            'img' => $ci->produto ? $ci->produto->img : '/imgs/no-image.png',
            'quantidade' => (float)$ci->quantidade,
            'valor_unitario' => __moeda($ci->valor_unitario),
            'sub_total' => __moeda($ci->sub_total),
        ];
    })->values();

    $initialTotal = ($carrinho && !empty($carrinho->valor_total)) ? (float)$carrinho->valor_total : 0;
    $freteMinimo = (float)($config->frete_gratis_valor ?? 0);
@endphp

<div x-data="{
        isOpen: false,
        loading: false,
        itens: {{ json_encode($initialItens) }},
        total: {{ $initialTotal }},
        freteMinimo: {{ $freteMinimo }},
        get totalFormatado() {
            return Number(this.total).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        get faltaFrete() {
            return (this.freteMinimo > 0 && this.total < this.freteMinimo) ? (this.freteMinimo - this.total) : 0;
        },
        get faltaFreteFormatado() {
            return Number(this.faltaFrete).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        get percFrete() {
            if (this.freteMinimo <= 0) return 100;
            return Math.min(100, Math.round((this.total / this.freteMinimo) * 100));
        },
        init() {
            window.addEventListener('open-cart-drawer', (e) => {
                if (e.detail && e.detail.cart) {
                    this.atualizarDados(e.detail.cart);
                }
                this.isOpen = true;
            });
            window.addEventListener('cart-updated', (e) => {
                if (e.detail && e.detail.cart) {
                    this.atualizarDados(e.detail.cart);
                }
            });
        },
        atualizarDados(cart) {
            this.itens = cart.itens || [];
            this.total = parseFloat(cart.total) || 0;
            const badge = document.getElementById('cart-header-badge');
            if (badge) badge.innerText = this.itens.length;
            const headerTotal = document.getElementById('cart-header-total');
            if (headerTotal) headerTotal.innerText = 'R$ ' + this.totalFormatado;
        },
        async alterarQtd(item, delta) {
            const novaQtd = item.quantidade + delta;
            if (novaQtd < 1) return;
            this.loading = true;
            try {
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('quantidade', novaQtd);
                formData.append('link', '{{ $config->loja_id }}');
                formData.append('_token', document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '');

                const res = await fetch(`/loja-atualiza-quantidade/${item.id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                if (res.ok) {
                    const data = await res.json();
                    this.atualizarDados(data);
                }
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        async removerItem(item) {
            this.loading = true;
            try {
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('link', '{{ $config->loja_id }}');
                formData.append('_token', document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '');

                const res = await fetch(`/remove-item-loja/${item.id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                if (res.ok) {
                    const data = await res.json();
                    this.atualizarDados(data);
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Item removido do carrinho.', type: 'info' } }));
                }
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        close() {
            this.isOpen = false;
        }
     }"
     @keydown.window.escape="close()">
    
    <!-- Backdrop com transição suave -->
    <div x-show="isOpen"
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 transition-opacity"
         @click="close()"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"></div>

    <!-- Painel Lateral (Drawer) -->
    <div x-show="isOpen"
         class="fixed inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl z-50 flex flex-col justify-between overflow-hidden"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         style="display: none;">

        <!-- Topo do Drawer -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <i class="ri-shopping-bag-3-line text-lg"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800">
                    Meu Carrinho
                    <span class="text-xs font-semibold text-slate-400"
                          x-text="'(' + itens.length + (itens.length === 1 ? ' item)' : ' itens)')">
                        ({{ count($itensCollection) }} {{ count($itensCollection) == 1 ? 'item' : 'itens' }})
                    </span>
                </h3>
            </div>
            <button type="button"
                    @click="close()"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                    aria-label="Fechar carrinho lateral">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>

        <!-- Meta de Frete Grátis -->
        <template x-if="freteMinimo > 0">
            <div class="px-5 py-3.5 bg-indigo-50/60 border-b border-indigo-100/50">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <template x-if="faltaFrete > 0">
                        <span class="text-indigo-900 font-medium">
                            Faltam <strong class="text-indigo-600 font-bold" x-text="'R$ ' + faltaFreteFormatado"></strong> para Frete Grátis!
                        </span>
                    </template>
                    <template x-if="faltaFrete <= 0">
                        <span class="text-emerald-700 font-bold flex items-center gap-1">
                            <i class="ri-checkbox-circle-fill text-emerald-500"></i> Parabéns! Você ganhou Frete Grátis!
                        </span>
                    </template>
                    <span class="font-bold text-indigo-600" x-text="percFrete + '%'"></span>
                </div>
                <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-emerald-500 h-1.5 rounded-full transition-all duration-500"
                         :style="'width: ' + percFrete + '%'"></div>
                </div>
            </div>
        </template>

        <!-- Conteúdo do Carrinho: Lista de Itens ou Estado Vazio -->
        <div class="flex-1 overflow-y-auto p-5 divide-y divide-slate-100 relative">
            <!-- Loading overlay discreto -->
            <div x-show="loading"
                 class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-10"
                 style="display: none;">
                <i class="ri-loader-4-line animate-spin text-2xl text-indigo-600"></i>
            </div>

            <!-- Lista de Itens Reativa -->
            <template x-if="itens.length > 0">
                <div class="space-y-4">
                    <template x-for="ci in itens" :key="ci.id">
                        <div class="flex gap-4 py-3 group">
                            <!-- Foto -->
                            <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0">
                                <img :src="ci.img" :alt="ci.nome" class="w-full h-full object-cover">
                            </div>

                            <!-- Info & Quantidade -->
                            <div class="flex-1 flex flex-col justify-between min-w-0">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h5 class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-2 leading-tight" x-text="ci.nome"></h5>
                                        <!-- Botão de Remoção AJAX -->
                                        <button type="button"
                                                @click="removerItem(ci)"
                                                class="text-slate-300 hover:text-rose-500 transition-colors p-1"
                                                title="Remover item">
                                            <i class="ri-delete-bin-line text-base"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5" x-text="'Unitário: R$ ' + ci.valor_unitario"></p>
                                </div>

                                <div class="flex items-center justify-between mt-2">
                                    <!-- Atualizador de Quantidade AJAX -->
                                    <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-white">
                                        <button type="button"
                                                @click="alterarQtd(ci, -1)"
                                                class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-slate-100 text-xs font-bold">
                                            -
                                        </button>
                                        <span class="w-8 text-center text-xs font-bold text-slate-800" x-text="ci.quantidade"></span>
                                        <button type="button"
                                                @click="alterarQtd(ci, 1)"
                                                class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-slate-100 text-xs font-bold">
                                            +
                                        </button>
                                    </div>

                                    <!-- Subtotal -->
                                    <span class="text-sm font-extrabold text-indigo-600" x-text="'R$ ' + ci.sub_total"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Estado Vazio -->
            <template x-if="itens.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-center py-12">
                    <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-400 flex items-center justify-center text-3xl mb-4">
                        <i class="ri-shopping-bag-3-line"></i>
                    </div>
                    <h4 class="text-base font-extrabold text-slate-800 mb-1">Seu carrinho está vazio</h4>
                    <p class="text-xs text-slate-400 max-w-xs mb-6">
                        Adicione os produtos que você deseja e aproveite as melhores condições e entrega rápida!
                    </p>
                    <button type="button"
                            @click="close()"
                            class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                        Continuar Comprando
                    </button>
                </div>
            </template>
        </div>

        <!-- Rodapé do Drawer com Totais e Ações -->
        <template x-if="itens.length > 0">
            <div class="p-5 border-t border-slate-100 bg-slate-50/70 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Subtotal</span>
                    <span class="text-xl font-black text-slate-900" x-text="'R$ ' + totalFormatado"></span>
                </div>
                <p class="text-[11px] text-slate-400">
                    O frete e os prazos serão calculados no checkout.
                </p>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('loja.carrinho', ['link' => $config->loja_id]) }}"
                       class="py-3 px-3 rounded-2xl border border-slate-300 hover:border-slate-400 bg-white text-slate-800 font-bold text-xs text-center transition-all flex items-center justify-center gap-1.5">
                        <i class="ri-shopping-cart-line"></i>
                        <span>Ver Carrinho</span>
                    </a>
                    <a href="{{ route('loja.pagamento', ['link' => $config->loja_id]) }}"
                       class="py-3 px-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs text-center shadow-lg shadow-indigo-600/25 transition-all flex items-center justify-center gap-1.5">
                        <span>Finalizar Pedido</span>
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>
