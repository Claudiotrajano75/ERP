<!DOCTYPE html>
<html lang="pt-BR" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Início' }} — {{ $config->nome }}</title>
    <meta name="description" content="{{ $config->descricao_breve ?? $config->nome }}">

    <!-- Open Graph / Redes Sociais -->
    <link rel="canonical" href="{{ url()->full() }}">
    @php $ogImage = $config->logo_img ?: ($config->banner_img ?? null); @endphp
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $config->nome }}">
    <meta property="og:title" content="{{ $title ?? 'Início' }} — {{ $config->nome }}">
    <meta property="og:description" content="{{ $config->descricao_breve ?? $config->nome }}">
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="theme-color" content="#4f46e5">

    <!-- Tipografia Moderna: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN com configuração otimizada) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Swiper.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        /* Estilos de customização da paginação do Swiper */
        .swiper-pagination-bullet { background: rgba(255, 255, 255, 0.45); opacity: 1; transition: all 0.3s ease; }
        .swiper-pagination-bullet-active { background: #ffffff !important; width: 28px !important; border-radius: 9999px !important; }
        .swiper-pagination-bullet-active-main { background: #4f46e5 !important; }
        /* Scrollbar elegante */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    @yield('css')
</head>
<body class="min-h-full flex flex-col bg-slate-50/50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white"
      x-data="{
        toast: { show: false, message: '', type: 'success' },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 4000);
        }
      }"
      @show-toast.window="showToast($event.detail.message, $event.detail.type || 'success')"
      x-init="
        @if(session('flash_success'))
            showToast('{{ addslashes(session('flash_success')) }}', 'success');
        @elseif(session('flash_error'))
            showToast('{{ addslashes(session('flash_error')) }}', 'error');
        @elseif(session('flash_warning'))
            showToast('{{ addslashes(session('flash_warning')) }}', 'warning');
        @endif
      ">

    <!-- Notificação Flutuante (Toast Global) -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="fixed bottom-6 right-6 z-50 max-w-sm rounded-2xl p-4 shadow-2xl flex items-center gap-3 border text-sm font-semibold"
         :class="{
            'bg-emerald-600 text-white border-emerald-500 shadow-emerald-600/20': toast.type === 'success',
            'bg-rose-600 text-white border-rose-500 shadow-rose-600/20': toast.type === 'error',
            'bg-amber-500 text-slate-900 border-amber-400 shadow-amber-500/20': toast.type === 'warning'
         }"
         style="display: none;">
        <i :class="{
            'ri-checkbox-circle-fill text-xl': toast.type === 'success',
            'ri-error-warning-fill text-xl': toast.type === 'error',
            'ri-alert-fill text-xl': toast.type === 'warning'
        }"></i>
        <span x-text="toast.message" class="flex-1"></span>
        <button type="button" @click="toast.show = false" class="opacity-70 hover:opacity-100">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>

    <!-- 1. Topbar Promocional -->
    <x-loja.topbar :config="$config" />

    <!-- 2. Header Principal -->
    <x-loja.header :config="$config" :categorias="$categorias ?? []" :carrinho="$carrinho ?? $item ?? null" />

    <!-- 3. Conteúdo da Página -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- 4. Carrinho Lateral Deslizante (Cart Drawer) -->
    <x-loja.cart-drawer :config="$config" :carrinho="$carrinho ?? $item ?? null" />

    <!-- 5. Rodapé Institucional -->
    <x-loja.footer :config="$config" :categorias="$categorias ?? []" />

    <!-- Scripts Globais: Swiper.js & Utilitários de Máscaras -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // Inicialização global do Swiper do Hero (quando presente na página)
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.hero-swiper')) {
                new Swiper('.hero-swiper', {
                    loop: true,
                    speed: 700,
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    },
                    grabCursor: true,
                    effect: 'fade',
                    fadeEffect: { crossFade: true },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    }
                });
            }
        });

        // Máscaras de entrada limpas sem jQuery
        function maskCpfCnpj(input) {
            let v = input.value.replace(/\D/g, '');
            if (v.length <= 11) {
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            } else {
                v = v.slice(0, 14);
                v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
                v = v.replace(/(\d{4})(\d)/, '$1-$2');
            }
            input.value = v;
        }

        function maskCep(input) {
            let v = input.value.replace(/\D/g, '').slice(0, 8);
            if (v.length > 5) v = v.replace(/^(\d{5})(\d)/, '$1-$2');
            input.value = v;
        }

        function maskPhone(input) {
            let v = input.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 10) {
                v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            } else if (v.length > 5) {
                v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
            } else if (v.length > 2) {
                v = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
            }
            input.value = v;
        }
    </script>

    @stack('scripts')
    @yield('js')
</body>
</html>
