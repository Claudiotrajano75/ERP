{{-- Header principal com busca inteligente, categorias e ações --}}
@props(['config', 'categorias' => [], 'carrinho' => null])

@php
    $cats = collect($categorias);
    $itensNoCarrinho = ($carrinho && !empty($carrinho->itens)) ? count($carrinho->itens) : 0;
@endphp

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all"
        x-data="{
            mobileMenuOpen: false,
            searchQuery: '{{ request('pesquisa') ?? '' }}',
            searchResults: [],
            searchLoading: false,
            searchOpen: false,
            cartCount: {{ $itensNoCarrinho }},
            cartTotal: '{{ ($carrinho && !empty($carrinho->valor_total)) ? __moeda($carrinho->valor_total) : '0,00' }}',
            async doSearch() {
                if (this.searchQuery.trim().length < 2) {
                    this.searchResults = [];
                    this.searchOpen = false;
                    return;
                }
                this.searchLoading = true;
                this.searchOpen = true;
                try {
                    const res = await fetch(`{{ route('loja.pesquisa-autocomplete') }}?link={{ $config->loja_id }}&pesquisa=${encodeURIComponent(this.searchQuery)}`);
                    if (res.ok) {
                        this.searchResults = await res.json();
                    }
                } catch (e) {
                    this.searchResults = [];
                } finally {
                    this.searchLoading = false;
                }
            }
        }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4 lg:gap-8">
            
            <!-- Mobile Menu Toggle Button -->
            <button type="button"
                    @click="mobileMenuOpen = true"
                    class="lg:hidden p-2 rounded-xl text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-colors focus:outline-none"
                    aria-label="Abrir menu de navegação">
                <i class="ri-menu-2-line text-2xl"></i>
            </button>

            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}" class="flex items-center gap-3 group">
                    @if(!empty($config->logo_img) && $config->logo_img !== '/imgs/no-image.png')
                        <img src="{{ $config->logo_img }}"
                             alt="{{ $config->nome }}"
                             class="h-10 md:h-12 w-auto max-w-[180px] object-contain transition-transform group-hover:scale-105"
                             loading="eager">
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-indigo-200">
                                {{ strtoupper(substr($config->nome, 0, 1)) }}
                            </div>
                            <span class="font-extrabold text-xl tracking-tight text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ $config->nome }}
                            </span>
                        </div>
                    @endif
                </a>
            </div>

            <!-- Barra de Busca Central com Autocomplete -->
            <div class="hidden md:flex flex-1 max-w-2xl relative" @click.away="searchOpen = false">
                <form action="{{ route('loja.pesquisa') }}" method="GET" class="w-full relative">
                    <input type="hidden" name="link" value="{{ $config->loja_id }}">
                    <div class="relative flex items-center">
                        <input type="text"
                               name="pesquisa"
                               x-model="searchQuery"
                               @input.debounce.300ms="doSearch()"
                               @focus="if(searchQuery.length >= 2) searchOpen = true"
                               placeholder="O que você está procurando hoje?"
                               class="w-full pl-12 pr-28 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                               autocomplete="off">
                        <span class="absolute left-4 text-slate-400 text-lg flex items-center pointer-events-none">
                            <i class="ri-search-2-line" x-show="!searchLoading"></i>
                            <i class="ri-loader-4-line animate-spin text-indigo-600" x-show="searchLoading" style="display: none;"></i>
                        </span>
                        <button type="submit"
                                class="absolute right-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1">
                            <span>Buscar</span>
                            <i class="ri-arrow-right-s-line text-sm"></i>
                        </button>
                    </div>
                </form>

                <!-- Dropdown de Resultados da Busca -->
                <div x-show="searchOpen && searchResults.length > 0"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 divide-y divide-slate-50"
                     style="display: none;">
                    <div class="p-2 text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-50/70">
                        Produtos sugeridos
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="item in searchResults" :key="item.hash_ecommerce">
                            <a :href="`{{ route('loja.produto-detalhe', ['hash' => '__HASH__']) }}?link={{ $config->loja_id }}`.replace('__HASH__', item.hash_ecommerce)"
                               class="flex items-center gap-3 p-3 hover:bg-indigo-50/50 transition-colors group">
                                <img :src="item.img" :alt="item.nome" class="w-12 h-12 rounded-lg object-cover bg-slate-100 border border-slate-100 flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate group-hover:text-indigo-600 transition-colors" x-text="item.nome"></p>
                                    <p class="text-xs font-bold text-indigo-600" x-text="'R$ ' + (item.valor ? Number(item.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2}) : '0,00')"></p>
                                </div>
                                <i class="ri-arrow-right-line text-slate-300 group-hover:text-indigo-600 text-base pr-2 transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </template>
                    </div>
                    <div class="p-3 bg-slate-50 text-center">
                        <a :href="`{{ route('loja.pesquisa') }}?link={{ $config->loja_id }}&pesquisa=${encodeURIComponent(searchQuery)}`"
                           class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center justify-center gap-1">
                            <span>Ver todos os resultados</span>
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ícones e Ações do Header -->
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Link Minha Conta -->
                <a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}"
                   class="flex items-center gap-2 p-2.5 rounded-2xl text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-all"
                   title="Acessar minha conta">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <i class="ri-user-3-line text-lg"></i>
                    </div>
                    <div class="hidden xl:flex flex-col text-left">
                        <span class="text-[11px] text-slate-400 font-medium leading-none">Olá, bem-vindo</span>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Minha Conta</span>
                    </div>
                </a>

                <!-- Favoritos (com contador via localStorage) -->
                <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}#favoritos"
                   class="relative p-2.5 rounded-2xl text-slate-700 hover:text-rose-500 hover:bg-rose-50 transition-all"
                   title="Meus Favoritos"
                   x-data="{ count: 0 }"
                   x-init="count = JSON.parse(localStorage.getItem('loja_favoritos_{{ $config->loja_id }}') || '[]').length"
                   @favoritos-updated.window="count = JSON.parse(localStorage.getItem('loja_favoritos_{{ $config->loja_id }}') || '[]').length">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-rose-50 hover:text-rose-500 transition-colors">
                        <i class="ri-heart-3-line text-lg"></i>
                    </div>
                    <span x-show="count > 0"
                          x-text="count"
                          class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center shadow-sm"
                          style="display: none;"></span>
                </a>

                <!-- Carrinho de Compras (Abre o Cart Drawer) -->
                <button type="button"
                        @click="$dispatch('open-cart-drawer')"
                        @cart-updated.window="if($event.detail && $event.detail.cart){ cartCount = $event.detail.cart.count; cartTotal = $event.detail.cart.total_formatado; }"
                        @open-cart-drawer.window="if($event.detail && $event.detail.cart){ cartCount = $event.detail.cart.count; cartTotal = $event.detail.cart.total_formatado; }"
                        class="relative flex items-center gap-2.5 p-2 sm:px-3 sm:py-2 rounded-2xl bg-indigo-50 hover:bg-indigo-100/80 text-indigo-700 transition-all border border-indigo-100"
                        title="Ver carrinho de compras">
                    <div class="relative flex items-center justify-center">
                        <i class="ri-shopping-bag-3-fill text-xl text-indigo-600"></i>
                        <span id="cart-header-badge"
                              x-text="cartCount"
                              class="absolute -top-2 -right-2.5 min-w-[20px] h-5 px-1 rounded-full bg-indigo-600 text-white text-[11px] font-bold flex items-center justify-center shadow-sm">
                            {{ $itensNoCarrinho }}
                        </span>
                    </div>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider leading-none">Carrinho</span>
                        <span id="cart-header-total"
                              x-text="'R$ ' + cartTotal"
                              class="text-xs font-extrabold text-slate-900 leading-tight">
                            R$ {{ ($carrinho && !empty($carrinho->valor_total)) ? __moeda($carrinho->valor_total) : '0,00' }}
                        </span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Barra de Busca no Mobile -->
        <div class="md:hidden pb-4">
            <form action="{{ route('loja.pesquisa') }}" method="GET" class="relative">
                <input type="hidden" name="link" value="{{ $config->loja_id }}">
                <input type="text"
                       name="pesquisa"
                       value="{{ request('pesquisa') }}"
                       placeholder="Buscar produtos na loja..."
                       class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 outline-none">
                <span class="absolute left-3.5 top-3 text-slate-400 text-base pointer-events-none">
                    <i class="ri-search-2-line"></i>
                </span>
            </form>
        </div>

        <!-- Menu de Categorias Desktop (Navegação Horizontal com Megamenu) -->
        <nav class="hidden lg:flex items-center gap-1 border-t border-slate-100 py-2.5 overflow-x-auto text-sm">
            <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}"
               class="px-3 py-1.5 rounded-xl font-semibold {{ request()->routeIs('loja.index') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50' }} transition-colors flex items-center gap-1.5">
                <i class="ri-home-4-line text-base"></i>
                <span>Início</span>
            </a>

            <!-- Dropdown 'Todas as Categorias' -->
            @if($cats->count() > 0)
                <div class="relative" x-data="{ openCat: false }" @click.away="openCat = false">
                    <button type="button"
                            @click="openCat = !openCat"
                            class="px-3 py-1.5 rounded-xl font-semibold text-slate-700 hover:text-indigo-600 hover:bg-slate-50 transition-colors flex items-center gap-1.5">
                        <i class="ri-grid-fill text-indigo-600"></i>
                        <span>Categorias</span>
                        <i class="ri-arrow-down-s-line text-slate-400 transition-transform" :class="{ 'rotate-180': openCat }"></i>
                    </button>

                    <div x-show="openCat"
                         x-transition
                         class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50 divide-y divide-slate-50"
                         style="display: none;">
                        <div class="max-h-96 overflow-y-auto space-y-1 py-1">
                            @foreach($cats as $cat)
                                <a href="{{ route('loja.produtos-categoria', [$cat->hash_ecommerce, 'link' => $config->loja_id]) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 text-sm font-medium transition-colors group">
                                    <span class="flex items-center gap-2">
                                        <i class="ri-checkbox-blank-circle-fill text-[6px] text-slate-300 group-hover:text-indigo-500"></i>
                                        <span>{{ $cat->nome }}</span>
                                    </span>
                                    @if(isset($cat->produtos_count) && $cat->produtos_count > 0)
                                        <span class="text-[11px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full group-hover:bg-indigo-100 group-hover:text-indigo-700">
                                            {{ $cat->produtos_count }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                        <div class="pt-2 px-1">
                            <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                               class="block w-full py-2 text-center text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50/60 rounded-xl hover:bg-indigo-100/70 transition-colors">
                                Ver todos os produtos
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Categorias em Destaque na Barra -->
                @foreach($cats->take(5) as $cat)
                    <a href="{{ route('loja.produtos-categoria', [$cat->hash_ecommerce, 'link' => $config->loja_id]) }}"
                       class="px-3 py-1.5 rounded-xl font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-colors whitespace-nowrap">
                        {{ $cat->nome }}
                    </a>
                @endforeach
            @endif

            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                   class="px-3 py-1.5 rounded-xl font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors flex items-center gap-1 text-xs uppercase tracking-wider">
                    <i class="ri-fire-fill text-rose-500"></i>
                    <span>Ofertas</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Mobile Drawer Menu (Gaveta Lateral no Mobile) -->
    <div x-show="mobileMenuOpen"
         class="fixed inset-0 z-50 lg:hidden"
         style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
             @click="mobileMenuOpen = false"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Painel Lateral -->
        <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-2xl p-6 overflow-y-auto flex flex-col justify-between"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            <div>
                <!-- Topo do Menu Mobile -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                    <span class="font-extrabold text-lg text-slate-900">{{ $config->nome }}</span>
                    <button type="button" @click="mobileMenuOpen = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>

                <!-- Links Rápidos -->
                <div class="py-4 space-y-1">
                    <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-slate-800 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <i class="ri-home-4-line text-lg text-indigo-600"></i>
                        <span>Página Inicial</span>
                    </a>
                    <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-slate-800 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <i class="ri-shopping-basket-line text-lg text-indigo-600"></i>
                        <span>Todos os Produtos</span>
                    </a>
                    <a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-slate-800 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <i class="ri-user-3-line text-lg text-indigo-600"></i>
                        <span>Minha Conta</span>
                    </a>
                </div>

                <!-- Lista de Categorias no Mobile -->
                <div class="pt-4 border-t border-slate-100">
                    <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Categorias</p>
                    <div class="space-y-1">
                        @foreach($cats as $cat)
                            <a href="{{ route('loja.produtos-categoria', [$cat->hash_ecommerce, 'link' => $config->loja_id]) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors">
                                <span>{{ $cat->nome }}</span>
                                @if(isset($cat->produtos_count) && $cat->produtos_count > 0)
                                    <span class="text-xs text-slate-400 font-semibold">{{ $cat->produtos_count }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Rodapé do Menu Mobile -->
            <div class="pt-6 border-t border-slate-100">
                @if(!empty($config->link_whatsapp))
                    <a href="{{ $config->link_whatsapp }}" target="_blank"
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm shadow-sm transition-all">
                        <i class="ri-whatsapp-line text-lg"></i>
                        <span>Falar no WhatsApp</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
