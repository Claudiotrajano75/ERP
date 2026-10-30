{{-- Card de Produto Moderno de Alta Conversão --}}
@props(['produto', 'config'])

@php
    $link = route('loja.produto-detalhe', [$produto->hash_ecommerce, 'link' => $config->loja_id]);
    $preco = $produto->valor_ecommerce ?? $produto->valor_unitario ?? 0;
    $desconto = $produto->percentual_desconto ?? 0;
    $temDesconto = $desconto > 0;
    $precoAntigo = $temDesconto ? ($preco + ($preco * $desconto / 100)) : 0;
    
    // Parcelamento em até 10x ou 12x
    $parcelasMax = 10;
    $valorParcela = $preco > 0 ? ($preco / $parcelasMax) : 0;

    // Selo de frete grátis se o produto ultrapassar o valor mínimo
    $freteGratis = (!empty($config->frete_gratis_valor) && $config->frete_gratis_valor > 0 && $preco >= $config->frete_gratis_valor);
@endphp

<div class="group relative flex flex-col bg-white rounded-3xl border border-slate-100 p-3 sm:p-4 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 hover:-translate-y-1"
     x-data="{
        favorito: false,
        adicionando: false,
        init() {
            const favs = JSON.parse(localStorage.getItem('loja_favoritos_{{ $config->loja_id }}') || '[]');
            this.favorito = favs.includes('{{ $produto->hash_ecommerce }}');
        },
        toggleFavorito() {
            let favs = JSON.parse(localStorage.getItem('loja_favoritos_{{ $config->loja_id }}') || '[]');
            if (this.favorito) {
                favs = favs.filter(id => id !== '{{ $produto->hash_ecommerce }}');
                this.favorito = false;
            } else {
                favs.push('{{ $produto->hash_ecommerce }}');
                this.favorito = true;
            }
            localStorage.setItem('loja_favoritos_{{ $config->loja_id }}', JSON.stringify(favs));
            window.dispatchEvent(new CustomEvent('favoritos-updated'));
        },
        async adicionarAoCarrinho() {
            if (this.adicionando) return;
            this.adicionando = true;
            try {
                const formData = new FormData();
                formData.append('link', '{{ $config->loja_id }}');
                formData.append('produto_id', '{{ $produto->id }}');
                formData.append('quantidade', '1');
                formData.append('comprar_agora', '0');
                formData.append('_token', document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '{{ csrf_token() }}');

                const res = await fetch('{{ route('loja.adicionar-carrinho') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    window.dispatchEvent(new CustomEvent('open-cart-drawer', { detail: { cart: data } }));
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cart: data } }));
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: data.message || 'Produto adicionado ao carrinho!', type: 'success' } }));
                } else {
                    alert(data.message || 'Não foi possível adicionar o produto ao carrinho.');
                }
            } catch(e) {
                console.error(e);
                alert('Erro ao adicionar produto ao carrinho. Verifique sua conexão.');
            } finally {
                this.adicionando = false;
            }
        }
     }">
    
    <!-- Imagem e Badges -->
    <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-slate-50 mb-3 sm:mb-4">
        <a href="{{ $link }}" class="block w-full h-full">
            <img src="{{ $produto->img }}"
                 alt="{{ $produto->nome }}"
                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out"
                 loading="lazy">
        </a>

        <!-- Badges Superior Esquerdo -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10">
            @if($temDesconto)
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-extrabold bg-rose-500 text-white shadow-sm">
                    -{{ number_format($desconto, 0) }}% OFF
                </span>
            @endif
            @if($freteGratis)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-500 text-white shadow-sm">
                    <i class="ri-truck-fill text-xs"></i> Frete Grátis
                </span>
            @endif
        </div>

        <!-- Botão de Favorito Superior Direito -->
        <button type="button"
                @click="toggleFavorito()"
                class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-slate-400 hover:text-rose-500 hover:scale-110 shadow-sm transition-all z-10"
                :class="{ 'text-rose-500 !bg-rose-50': favorito }"
                :aria-label="favorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos'">
            <i :class="favorito ? 'ri-heart-3-fill text-rose-500' : 'ri-heart-3-line'" class="text-base"></i>
        </button>
    </div>

    <!-- Informações do Produto -->
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <!-- Categoria -->
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1 block line-clamp-1">
                {{ $produto->categoria ? $produto->categoria->nome : 'Geral' }}
            </span>

            <!-- Nome / Título -->
            <h4 class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug mb-2" title="{{ $produto->nome }}">
                <a href="{{ $link }}">{{ $produto->nome }}</a>
            </h4>
        </div>

        <!-- Bloco de Preços -->
        <div class="pt-2 border-t border-slate-50">
            @if($preco > 0)
                <div class="space-y-0.5 mb-3">
                    @if($temDesconto)
                        <span class="text-[11px] text-slate-400 line-through block">
                            R$ {{ __moeda($precoAntigo) }}
                        </span>
                    @endif
                    <div class="flex items-baseline gap-1">
                        <span class="text-base sm:text-xl font-black text-slate-900 tracking-tight">
                            R$ {{ __moeda($preco) }}
                        </span>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">à vista</span>
                    </div>
                    <span class="text-[11px] text-slate-500 font-medium block">
                        ou {{ $parcelasMax }}x de <strong class="text-slate-700">R$ {{ __moeda($valorParcela) }}</strong>
                    </span>
                </div>
            @else
                <div class="mb-3 py-1">
                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                        Sob consulta
                    </span>
                </div>
            @endif

            <!-- Botões de Ação -->
            <div class="flex items-center gap-2 pt-1">
                @if($preco > 0)
                    @if(isset($produto->variacoes) && count($produto->variacoes) > 0)
                        <!-- Produto com Variações: Redireciona para escolher opções -->
                        <a href="{{ $link }}"
                           class="h-10 w-11 rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 flex items-center justify-center flex-shrink-0 transition-all shadow-sm group/btn"
                           title="Escolher opções e adicionar ao carrinho">
                            <i class="ri-shopping-cart-2-line text-lg group-hover/btn:scale-110 transition-transform"></i>
                        </a>
                    @else
                        <!-- Adicionar Direto ao Carrinho via AJAX (Abre Cart Drawer Lateral) -->
                        <button type="button"
                                @click="adicionarAoCarrinho()"
                                :disabled="adicionando"
                                class="h-10 w-11 rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 flex items-center justify-center flex-shrink-0 transition-all shadow-sm group/btn disabled:opacity-50 disabled:cursor-not-allowed"
                                title="Adicionar ao carrinho">
                            <i x-show="!adicionando" class="ri-shopping-cart-2-line text-lg group-hover/btn:scale-110 transition-transform"></i>
                            <i x-show="adicionando" class="ri-loader-4-line animate-spin text-lg" style="display: none;"></i>
                        </button>
                    @endif

                    <!-- Botão Comprar / Ver Detalhes -->
                    <a href="{{ $link }}"
                       class="flex-1 h-10 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm hover:shadow transition-all text-center whitespace-nowrap">
                        <span>Comprar</span>
                        <i class="ri-arrow-right-s-line text-sm"></i>
                    </a>
                @else
                    <a href="{{ $link }}"
                       class="w-full h-10 px-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs flex items-center justify-center gap-1 transition-all text-center">
                        <span>Ver Detalhes</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
