<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — {{ $config->nome }}</title>
    <meta name="description" content="{{ $config->descricao_breve ?? $config->nome }}">

    <!-- Aplica o tema salvo antes do CSS (evita flash) -->
    <script>
        (function(){
            try {
                var t = localStorage.getItem('ecommerce-theme');
                if (t === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Google Fonts: Roboto (fonte padrão do ERP) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- noUISlider -->
    <link type="text/css" rel="stylesheet" href="/ecommerce/css/nouislider.min.css"/>

    <!-- E-commerce Design System (ToolCraft Style) -->
    <link type="text/css" rel="stylesheet" href="/ecommerce/css/ecommerce_modern.css?v={{ time() }}"/>
    <link rel="stylesheet" type="text/css" href="/assets/css/toastr.min.css">

    @yield('css')

    <style>
        body.loading { overflow: hidden; }
        body.loading .modal-loading { display: flex; }
        .modal-loading {
            display: none;
            position: fixed; z-index: 10000; inset: 0;
            background: rgba(243,243,248,0.82);
            align-items: center; justify-content: center;
        }
        .modal-loading::after {
            content: '';
            width: 36px; height: 36px;
            border: 3px solid rgba(99,121,195,0.25);
            border-top-color: #6379c3;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<!-- ═══ ANNOUNCEMENT BAR ═══ -->
<div class="announcement-bar" id="announcementBar">
    🎉 Frete Grátis para compras acima de R$ 199 — Aproveite!
    <button class="ann-close" onclick="document.getElementById('announcementBar').style.display='none'" aria-label="Fechar">✕</button>
</div>

<!-- ═══ TOP HEADER ═══ -->
<div class="top-header">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            @if($config->telefone)
            <a href="tel:{{ $config->telefone }}" class="d-flex align-items-center gap-1">
                <i class="ri-phone-line"></i> {{ $config->telefone }}
            </a>
            @endif
            @if($config->email)
            <a href="mailto:{{ $config->email }}" class="d-flex align-items-center gap-1">
                <i class="ri-mail-line"></i> {{ $config->email }}
            </a>
            @endif
            @if($config->endereco)
            <span class="d-flex align-items-center gap-1" style="color:var(--luxe-tan)0.55)">
                <i class="ri-map-pin-line"></i> {{ $config->endereco }}
            </span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('loja.minha-conta', ['link='.$config->loja_id]) }}" class="d-flex align-items-center gap-1 fw-medium" style="color:var(--luxe-tan)">
                <i class="ri-user-3-line"></i> Minha Conta
            </a>
        </div>
    </div>
</div>

<!-- ═══ MAIN HEADER ═══ -->
<header class="main-header" id="mainHeader">
    <div class="container">
        <div class="d-flex align-items-center gap-3 gap-md-4">

            <!-- LOGO -->
            <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="text-decoration-none flex-shrink-0">
                @if($config->logo_img)
                    <img src="{{ $config->logo_img }}" alt="{{ $config->nome }}" class="logo-img" style="max-height:42px;object-fit:contain;">
                @else
                    <div class="logo-brand">
                        <div class="logo-icon">{{ strtoupper(substr($config->nome, 0, 1)) }}</div>
                        <span>{{ strtoupper($config->nome) }}</span>
                    </div>
                @endif
            </a>

            <!-- NAV LINKS (desktop) -->
            <nav class="d-none d-lg-block">
                @php $cats = collect($categorias); @endphp
                <ul class="main-nav">
                    <li><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="{{ request()->routeIs('loja.index') ? 'active' : '' }}">Home</a></li>
                    @foreach($cats->take(3) as $c)
                    <li><a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link='.$config->loja_id]) }}">{{ $c->nome }}</a></li>
                    @endforeach
                    @if($cats->count() > 0)
                    <li class="has-megamenu">
                        <a href="#" class="mm-trigger">Categorias <i class="ri-arrow-down-s-line"></i></a>
                        <div class="megamenu" id="megamenu">
                            @foreach($cats as $c)
                            <a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link='.$config->loja_id]) }}" class="mm-item">
                                @if($c->img ?? false)
                                <img src="{{ $c->img }}" alt="{{ $c->nome }}">
                                @else
                                <img src="/ecommerce/img/placeholder-cat.png" alt="{{ $c->nome }}" onerror="this.style.display='none'">
                                @endif
                                <span>
                                    <span class="mm-name d-block">{{ $c->nome }}</span>
                                    @if(isset($c->produtos_count))
                                    <span class="mm-count">{{ $c->produtos_count }} produtos</span>
                                    @endif
                                </span>
                            </a>
                            @endforeach
                            <div class="mm-all">
                                <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="btn-link">
                                    Ver todos os produtos <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- SEARCH BAR -->
            <form method="get" action="{{ route('loja.pesquisa') }}" class="search-wrapper flex-grow-1 d-none d-md-block">
                <input type="hidden" value="{{ $config->loja_id }}" name="link" id="ecommerce-loja-id">
                <input
                    id="search-input"
                    autocomplete="off"
                    class="search-input"
                    placeholder="Buscar produtos..."
                    name="pesquisa"
                    @isset($pesquisa) value="{{ $pesquisa }}" @endif
                >
                <button class="search-btn" type="submit" aria-label="Buscar">
                    <i class="ri-search-line"></i>
                </button>
                <div class="search-autocomplete" id="autocomplete-box"></div>
            </form>

            <!-- HEADER ACTIONS -->
            <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-auto ms-md-0">
                <!-- Tema claro/escuro -->
                <button class="header-icon" id="themeToggle" type="button" title="Alternar tema claro/escuro" aria-label="Alternar tema">
                    <i class="ri-moon-line" id="themeIcon" style="font-size:18px"></i>
                </button>

                <!-- Conta -->
                <a href="{{ route('loja.minha-conta', ['link='.$config->loja_id]) }}" class="header-icon d-none d-md-flex" title="Minha Conta">
                    <i class="ri-user-3-line" style="font-size:18px"></i>
                </a>

                <!-- Carrinho -->
                @isset($carrinho)
                <div class="dropdown">
                    <button class="header-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Carrinho" type="button">
                        <i class="ri-shopping-bag-3-line" style="font-size:18px"></i>
                        @php $cartTotal = $carrinho != [] ? sizeof($carrinho->itens) : 0; @endphp
                        @if($cartTotal > 0)
                        <span class="badge-count">{{ $cartTotal }}</span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg" style="width:320px;border-radius:14px;overflow:hidden" id="header-cart-dropdown">
                        <div style="padding:16px 18px 12px;border-bottom:1px solid var(--border-light)">
                            <span class="fw-playfair fw-bold" style="font-size:15px;color:var(--luxe-brown)">Meu Carrinho</span>
                            <span class="text-gold fw-bold ms-2" style="font-size:12px">{{ $cartTotal }} {{ $cartTotal == 1 ? 'item' : 'itens' }}</span>
                        </div>

                        @if($carrinho != [] && sizeof($carrinho->itens) > 0)
                        <div style="max-height:240px;overflow-y:auto;padding:12px 18px">
                            @foreach($carrinho->itens as $ci)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--border-light)">
                                <div style="width:52px;height:52px;flex-shrink:0;border-radius:8px;overflow:hidden;background:var(--luxe-cream)">
                                    <img src="{{ $ci->produto->img }}" style="width:100%;height:100%;object-fit:cover">
                                </div>
                                <div class="flex-grow-1" style="min-width:0">
                                    <div class="text-truncate" style="font-size:12px;font-weight:500;color:var(--luxe-brown)">{{ $ci->produto->nome }}</div>
                                    <div style="font-size:11px;color:var(--luxe-tan)0.5)">{{ number_format($ci->quantidade, 0) }}x R${{ __moeda($ci->valor_unitario) }}</div>
                                </div>
                                <span class="text-gold fw-bold" style="font-size:13px;flex-shrink:0">R${{ __moeda($ci->sub_total) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div style="padding:12px 18px;border-top:1px solid var(--border-light)">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="font-size:13px;color:var(--luxe-tan)">Total:</span>
                                <strong style="font-size:16px;color:var(--luxe-brown)">R$ {{ __moeda($carrinho->valor_total) }}</strong>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('loja.carrinho', ['link='.$config->loja_id]) }}" class="btn-buy outline text-center" style="border-radius:8px;padding:8px">Ver Carrinho</a>
                                <a href="{{ route('loja.pagamento', ['link='.$config->loja_id]) }}" class="btn-buy text-center" style="border-radius:8px;padding:8px">Finalizar Compra</a>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-5" style="color:var(--luxe-tan)0.45)">
                            <i class="ri-shopping-bag-3-line" style="font-size:36px;display:block;margin-bottom:10px;color:var(--luxe-gold);opacity:0.5"></i>
                            <span style="font-size:13px">Seu carrinho está vazio.</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Mobile menu -->
                <button class="header-icon d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-label="Menu">
                    <i class="ri-menu-line" style="font-size:20px"></i>
                </button>
            </div>
        </div>

        <!-- MOBILE SEARCH + NAV -->
        <div class="collapse d-md-none mt-3" id="mobileSearchCollapse">
            <form method="get" action="{{ route('loja.pesquisa') }}" class="search-wrapper w-100">
                <input type="hidden" value="{{ $config->loja_id }}" name="link">
                <input class="search-input" placeholder="Buscar produtos..." name="pesquisa">
                <button class="search-btn" type="submit"><i class="ri-search-line"></i></button>
            </form>
        </div>
        <div class="collapse d-lg-none mt-2" id="mobileNav">
            <nav class="py-2">
                <ul class="list-unstyled m-0">
                    <li><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="d-block py-2 px-3 rounded" style="color:var(--luxe-brown);font-size:14px;font-weight:500">Home</a></li>
                    @foreach($categorias as $c)
                    <li><a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link='.$config->loja_id]) }}" class="d-block py-2 px-3 rounded" style="color:var(--luxe-tan);font-size:14px">{{ $c->nome }}</a></li>
                    @endforeach
                    <li><a href="{{ route('loja.minha-conta', ['link='.$config->loja_id]) }}" class="d-block py-2 px-3 rounded" style="color:var(--luxe-tan);font-size:14px"><i class="ri-user-3-line me-1"></i>Minha Conta</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<!-- ═══ CONTENT ═══ -->
<main>
    @yield('content')
</main>

<!-- ═══ FOOTER ═══ -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <!-- Col 1: Brand -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-brand">
                    @if($config->logo_img)
                        <img src="{{ $config->logo_img }}" alt="{{ $config->nome }}" style="max-height:32px;object-fit:contain;filter:brightness(10)">
                    @else
                        <div class="logo-icon">{{ strtoupper(substr($config->nome, 0, 1)) }}</div>
                        <span>{{ strtoupper($config->nome) }}</span>
                    @endif
                </div>
                @if($config->descricao_breve)
                <p class="footer-desc">{{ $config->descricao_breve }}</p>
                @else
                <p class="footer-desc">Produtos selecionados com qualidade e preços justos para você.</p>
                @endif
                <div class="footer-social">
                    @if($config->link_facebook)
                    <a href="{{ $config->link_facebook }}" target="_blank" class="social-icon" title="Facebook"><i class="ri-facebook-fill"></i></a>
                    @endif
                    @if($config->link_instagram)
                    <a href="{{ $config->link_instagram }}" target="_blank" class="social-icon" title="Instagram"><i class="ri-instagram-fill"></i></a>
                    @endif
                    @if($config->link_whatsapp)
                    <a href="{{ $config->link_whatsapp }}" target="_blank" class="social-icon" title="WhatsApp"><i class="ri-whatsapp-fill"></i></a>
                    @endif
                </div>
            </div>

            <!-- Col 2: Links Rápidos -->
            <div class="col-lg-2 col-md-6">
                @php $cats = collect($categorias); @endphp
                <div class="footer-col-title">Links Rápidos</div>
                <ul class="footer-links">
                    <li><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}">Home</a></li>
                    @foreach($cats->take(5) as $c)
                    <li><a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link='.$config->loja_id]) }}">{{ $c->nome }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Col 3: Atendimento -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-col-title">Atendimento</div>
                <ul class="footer-links">
                    <li><a href="{{ route('loja.minha-conta', ['link='.$config->loja_id]) }}">Minha Conta</a></li>
                    @if($config->politica_privacidade)
                    <li><a href="{{ route('loja.politica-privacidade', ['link='.$config->loja_id]) }}">Política de Privacidade</a></li>
                    @endif
                    @if($config->link_whatsapp)
                    <li><a href="{{ $config->link_whatsapp }}" target="_blank">Fale pelo WhatsApp</a></li>
                    @endif
                </ul>
                @if($config->endereco)
                <div class="footer-contact-item"><i class="ri-map-pin-2-line"></i> {{ $config->endereco }}</div>
                @endif
                @if($config->telefone)
                <div class="footer-contact-item"><i class="ri-phone-line"></i> {{ $config->telefone }}</div>
                @endif
                @if($config->email)
                <div class="footer-contact-item"><i class="ri-mail-line"></i> {{ $config->email }}</div>
                @endif
            </div>

            <!-- Col 4: Newsletter -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-col-title">Newsletter</div>
                <p style="font-size:13px;color:rgba(255,255,255,0.45);line-height:1.6;margin-bottom:14px">
                    Cadastre-se e receba novidades, promoções exclusivas e ofertas especiais diretamente no seu e-mail.
                </p>
                <div class="footer-newsletter-input-wrap">
                    <input type="email" name="email" class="footer-newsletter-input" placeholder="Seu e-mail">
                    <button class="footer-newsletter-btn" type="button" title="Inscrever"><i class="ri-arrow-right-line"></i></button>
                </div>
                <div style="margin-top:20px;display:flex;gap:10px;align-items:center;opacity:0.4">
                    <i class="ri-visa-fill" style="font-size:24px;color:#fff"></i>
                    <i class="ri-mastercard-fill" style="font-size:24px;color:#fff"></i>
                    <i class="ri-secure-payment-line" style="font-size:20px;color:#fff"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            &copy; {{ date('Y') }} {{ $config->nome }}. Todos os direitos reservados.
        </div>
    </div>
</footer>

<!-- ═══ WHATSAPP FLOATING ═══ -->
@if($config->link_whatsapp || $config->telefone)
    @php
        $waLink = $config->link_whatsapp ?: 'https://wa.me/' . preg_replace('/\D/', '', $config->telefone);
    @endphp
    <a href="{{ $waLink }}" target="_blank" class="whatsapp-float" title="Fale conosco no WhatsApp">
        <i class="ri-whatsapp-fill"></i>
    </a>
@endif

<div class="modal-loading"></div>

<!-- ═══ BOTTOM NAVIGATION (MOBILE) ═══ -->
@php
    $bnCart = (isset($carrinho) && $carrinho != []) ? sizeof($carrinho->itens) : 0;
    $bnRoute = request()->route() ? request()->route()->getName() : '';
@endphp
<nav class="bottom-nav">
    <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="bn-item {{ $bnRoute == 'loja.index' ? 'active' : '' }}">
        <i class="ri-home-4-line"></i> Início
    </a>
    <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="bn-item {{ $bnRoute == 'loja.pesquisa' ? 'active' : '' }}">
        <i class="ri-search-line"></i> Buscar
    </a>
    <a href="{{ route('loja.carrinho', ['link='.$config->loja_id]) }}" class="bn-item {{ $bnRoute == 'loja.carrinho' ? 'active' : '' }}">
        <i class="ri-shopping-bag-3-line"></i> Carrinho
        @if($bnCart > 0)
        <span class="bn-badge">{{ $bnCart }}</span>
        @endif
    </a>
    <a href="{{ route('loja.minha-conta', ['link='.$config->loja_id]) }}" class="bn-item {{ $bnRoute == 'loja.minha-conta' ? 'active' : '' }}">
        <i class="ri-user-3-line"></i> Conta
    </a>
</nav>

<!-- ═══ SCRIPTS ═══ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/ecommerce/js/jquery.min.js"></script>
<script src="/ecommerce/js/nouislider.min.js"></script>
<script src="/assets/js/toastr.min.js"></script>
<script src="/assets/vendor/jquery-mask-plugin/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ── Toggle tema claro/escuro ──
    (function(){
        var root = document.documentElement;
        var icon = document.getElementById('themeIcon');

        function apply(theme){
            try {
                if (theme === 'dark') {
                    root.setAttribute('data-theme', 'dark');
                    root.setAttribute('data-bs-theme', 'dark');
                    if (icon) icon.className = 'ri-sun-line';
                } else {
                    root.removeAttribute('data-theme');
                    root.removeAttribute('data-bs-theme');
                    if (icon) icon.className = 'ri-moon-line';
                }
                localStorage.setItem('ecommerce-theme', theme);
            } catch (e) {}
        }

        var btn = document.getElementById('themeToggle');
        if (btn) {
            btn.addEventListener('click', function(){
                apply(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
            });
        }

        // Sincroniza o ícone com o tema já aplicado
        if (root.getAttribute('data-theme') === 'dark' && icon) icon.className = 'ri-sun-line';
    })();

    // ── Megamenu (Categorias) ──
    (function(){
        var trigger = document.querySelector('.mm-trigger');
        var menu = document.getElementById('megamenu');
        var item = document.querySelector('.has-megamenu');
        if (!trigger || !menu || !item) return;

        function open(){ menu.classList.add('open'); }
        function close(){ menu.classList.remove('open'); }

        item.addEventListener('mouseenter', open);
        item.addEventListener('mouseleave', close);
        trigger.addEventListener('click', function(e){
            e.preventDefault();
            menu.classList.toggle('open');
        });
        document.addEventListener('click', function(e){
            if (!item.contains(e.target)) close();
        });
    })();

    // ── Header scroll effect ──
    window.addEventListener('scroll', function() {
        const h = document.getElementById('mainHeader');
        if (h) h.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });

    // ── Flash messages ──
    @if(session()->has('flash_success'))
        toastr.success('{{ session()->get('flash_success') }}');
    @endif
    @if(session()->has('flash_error'))
        toastr.error('{{ session()->get('flash_error') }}');
    @endif

    const path_url = window.location.protocol + "//" + window.location.host + "/";

    // ── AJAX loader ──
    $(document).on({
        ajaxStart: () => $("body").addClass("loading"),
        ajaxStop:  () => $("body").removeClass("loading")
    });

    // ── Helpers de moeda ──
    function convertMoedaToFloat(v) {
        if (!v) return 0;
        return parseFloat(v.replaceAll(".", "").replaceAll(",", ".").replace(/[^0-9\.]+/g, ""));
    }
    function convertFloatToMoeda(v) {
        return parseFloat(v).toLocaleString("pt-BR", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // ── Auto-complete Search ──
    $(function() {
        var timer = null;
        var lojaId = $("#ecommerce-loja-id").val();

        $("#search-input").on("input", function() {
            var q = $(this).val().trim();
            clearTimeout(timer);
            if (q.length < 3) { $("#autocomplete-box").removeClass("active").html(""); return; }

            timer = setTimeout(function() {
                $.get(path_url + "loja-pesquisa-autocomplete", { pesquisa: q, loja_id: lojaId })
                .done(function(data) {
                    var html = "";
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            html += `<a class="autocomplete-item" href="/produto-ecommerce-detalhe/${item.hash_ecommerce}?link=${lojaId}">
                                <img src="${item.img}" alt="${item.nome}">
                                <div>
                                    <div class="ac-name">${item.nome}</div>
                                    <div class="ac-price">R$ ${convertFloatToMoeda(item.valor)}</div>
                                </div>
                            </a>`;
                        });
                    } else {
                        html = '<div style="padding:14px;text-align:center;font-size:13px;color:var(--luxe-tan)0.5)">Nenhum produto encontrado.</div>';
                    }
                    $("#autocomplete-box").html(html).addClass("active");
                });
            }, 280);
        });

        $(document).on("click", function(e) {
            if (!$(e.target).closest(".search-wrapper").length) {
                $("#autocomplete-box").removeClass("active");
            }
        });
    });

    // ── Scroll Reveal ──
    (function() {
        const els = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
        }, { threshold: 0.1 });
        els.forEach(el => io.observe(el));
    })();

    // ── Masks ──
    var cpfMask = function(v) { return v.replace(/\D/g,"").length > 11 ? "00.000.000/0000-00" : "000.000.000-009"; };
    $(document).on("focus", ".cpf_cnpj", function() {
        $(this).mask(cpfMask, { onKeyPress: function(v,e,f,o){ f.mask(cpfMask.apply({},arguments),o); } });
    });
</script>

@yield('js')

</body>
</html>
