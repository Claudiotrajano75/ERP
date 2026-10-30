{{-- Carrossel Principal de Banners com Swiper.js --}}
@props(['banners' => collect(), 'config'])

@php
    $totalBanners = $banners->count();
@endphp

<section class="relative bg-slate-950 overflow-hidden" aria-label="Banners em Destaque">
    @if($totalBanners > 0)
        <!-- Swiper Hero Container -->
        <div class="swiper hero-swiper w-full h-[460px] sm:h-[520px] lg:h-[620px]">
            <div class="swiper-wrapper">
                @foreach($banners as $index => $banner)
                    @php
                        $slideLink = ($banner->produto && !empty($banner->produto->hash_ecommerce))
                            ? route('loja.produto-detalhe', [$banner->produto->hash_ecommerce, 'link' => $config->loja_id])
                            : route('loja.pesquisa', ['link' => $config->loja_id]);
                    @endphp
                    <div class="swiper-slide relative w-full h-full select-none overflow-hidden group">
                        <!-- Imagem de Fundo com Zoom Suave ao Carregar -->
                        <div class="absolute inset-0">
                            <img src="{{ $banner->img }}"
                                 alt="{{ $banner->descricao ?? $config->nome }}"
                                 class="w-full h-full object-cover object-center transform scale-105 group-hover:scale-110 transition-transform duration-1000 ease-out"
                                 @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                            <!-- Overlay com gradiente premium para legibilidade máxima -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent sm:bg-gradient-to-r sm:from-slate-950/90 sm:via-slate-950/60 sm:to-transparent"></div>
                        </div>

                        <!-- Conteúdo do Slide -->
                        <div class="relative max-w-7xl mx-auto h-full px-6 sm:px-8 lg:px-12 flex flex-col justify-end sm:justify-center pb-16 sm:pb-0 z-10">
                            <div class="max-w-xl text-white space-y-4">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 backdrop-blur-md text-indigo-300 border border-white/10">
                                    <i class="ri-sparkling-fill text-amber-400"></i>
                                    Destaque Especial
                                </span>

                                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight sm:leading-none text-white drop-shadow-sm">
                                    {{ $banner->descricao ?: 'Coleção Exclusiva' }}
                                </h2>

                                @if(!empty($banner->valor) && $banner->valor > 0)
                                    <div class="flex items-baseline gap-2 pt-1">
                                        <span class="text-xs sm:text-sm text-slate-300 uppercase tracking-wider">A partir de</span>
                                        <span class="text-2xl sm:text-4xl font-extrabold text-amber-400">
                                            R$ {{ __moeda($banner->valor) }}
                                        </span>
                                    </div>
                                @endif

                                <div class="pt-4 flex flex-wrap items-center gap-3">
                                    <a href="{{ $slideLink }}"
                                       class="inline-flex items-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm sm:text-base shadow-xl shadow-indigo-600/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all">
                                        <span>{{ $banner->produto ? 'Ver Detalhes' : 'Comprar Agora' }}</span>
                                        <i class="ri-arrow-right-line text-lg"></i>
                                    </a>
                                    <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                                       class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur-md border border-white/10 transition-all">
                                        <span>Ver Coleção</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botões de Navegação (Desktop) -->
            <div class="swiper-button-prev !hidden md:!flex !w-12 !h-12 !rounded-full !bg-white/10 hover:!bg-white/30 !backdrop-blur-md !text-white !border !border-white/10 after:!text-lg !transition-all"></div>
            <div class="swiper-button-next !hidden md:!flex !w-12 !h-12 !rounded-full !bg-white/10 hover:!bg-white/30 !backdrop-blur-md !text-white !border !border-white/10 after:!text-lg !transition-all"></div>

            <!-- Paginação com Bullets Modernos -->
            <div class="swiper-pagination !bottom-6"></div>
        </div>
    @else
        <!-- Fallback Sofisticado se não houver banners cadastrados -->
        <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-20 sm:py-28 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="max-w-2xl text-white space-y-6 z-10 text-center md:text-left">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <i class="ri-store-2-line"></i>
                    Bem-vindo à Loja Oficial
                </span>
                <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight">
                    Tudo o que você precisa em um só lugar.
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
                    Descubra produtos selecionados com qualidade garantida, entrega rápida para todo o Brasil e as melhores condições de pagamento.
                </p>
                <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-4">
                    <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                       class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-base shadow-xl shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                        <span>Explorar Todos os Produtos</span>
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>

            <div class="w-full md:w-1/2 flex items-center justify-center">
                @if(!empty($config->logo_img) && $config->logo_img !== '/imgs/no-image.png')
                    <img src="{{ $config->logo_img }}" alt="{{ $config->nome }}" class="max-h-64 object-contain filter drop-shadow-2xl">
                @else
                    <div class="w-64 h-64 rounded-3xl bg-gradient-to-tr from-indigo-900/50 to-violet-900/30 border border-white/10 flex items-center justify-center shadow-2xl">
                        <i class="ri-shopping-bag-3-fill text-8xl text-indigo-400/40"></i>
                    </div>
                @endif
            </div>
        </div>
    @endif
</section>
