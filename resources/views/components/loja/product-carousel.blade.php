{{-- Carrossel de Produtos com Swiper.js --}}
@props(['produtos' => [], 'config', 'id' => 'prod-carousel-' . uniqid()])

@php
    $lista = collect($produtos);
@endphp

@if($lista->count() > 0)
    <div class="relative group/carousel">
        <div class="swiper {{ $id }} !pb-6 !overflow-visible sm:!overflow-hidden">
            <div class="swiper-wrapper">
                @foreach($lista as $produto)
                    <div class="swiper-slide !h-auto">
                        <x-loja.product-card :produto="$produto" :config="$config" />
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="swiper-pagination {{ $id }}-pagination !-bottom-1"></div>
        </div>

        <!-- Botões de Navegação -->
        <button type="button"
                class="{{ $id }}-prev absolute -left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white shadow-xl border border-slate-100 text-slate-700 hover:text-indigo-600 hover:scale-110 flex items-center justify-center transition-all z-20 opacity-0 group-hover/carousel:opacity-100 disabled:opacity-0 pointer-events-auto"
                aria-label="Produto anterior">
            <i class="ri-arrow-left-s-line text-2xl"></i>
        </button>
        <button type="button"
                class="{{ $id }}-next absolute -right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white shadow-xl border border-slate-100 text-slate-700 hover:text-indigo-600 hover:scale-110 flex items-center justify-center transition-all z-20 opacity-0 group-hover/carousel:opacity-100 disabled:opacity-0 pointer-events-auto"
                aria-label="Próximo produto">
            <i class="ri-arrow-right-s-line text-2xl"></i>
        </button>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.{{ $id }}', {
                slidesPerView: 1.25,
                spaceBetween: 14,
                grabCursor: true,
                navigation: {
                    nextEl: '.{{ $id }}-next',
                    prevEl: '.{{ $id }}-prev',
                },
                pagination: {
                    el: '.{{ $id }}-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                breakpoints: {
                    480: { slidesPerView: 2.1, spaceBetween: 16 },
                    768: { slidesPerView: 3.1, spaceBetween: 20 },
                    1024: { slidesPerView: 4, spaceBetween: 24 }
                }
            });
        });
    </script>
    @endpush
@endif
