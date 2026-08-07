@extends('loja.default', ['title' => 'Início'])

@section('content')

{{-- ═══════════════════════════════════════════
    HERO — estático, imersivo, minimalista
═══════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="hero-bg">
        @php $heroImg = $config->banner_img ?? null; @endphp
        @if($heroImg)
            <img src="{{ $heroImg }}" alt="{{ $config->nome }}" loading="eager">
        @else
            <div style="width:100%;height:100%;background:var(--luxe-gradient)"></div>
        @endif
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content container">
        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <span class="hero-tag">✦ {{ $config->nome }}</span>
                <h1 class="hero-title">
                    Bem-vindo à<br>
                    <span class="accent">{{ $config->nome }}</span>
                </h1>
                <p class="hero-subtitle">
                    {{ $config->descricao_breve ?? 'Descubra uma curadoria especial de produtos com preços justos e atendimento que você pode confiar.' }}
                </p>
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <a href="#produtos-destaque" class="btn-hero">
                        Ver Produtos <i class="ri-arrow-right-line"></i>
                    </a>
                    @if(count($categorias) > 0)
                    <a href="#categorias" class="btn-link light">
                        Explorar categorias <i class="ri-arrow-right-line"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
    TRUST BAR — benefícios minimalistas
═══════════════════════════════════════════ --}}
<div class="trust-bar">
    <div class="container">
        <div class="row g-0">
            <div class="col-6 col-md-3">
                <div class="trust-item">
                    <i class="ri-truck-line"></i>
                    <div>
                        <div class="ti-title">Frete Facilitado</div>
                        <div class="ti-sub">Entrega para todo o Brasil</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item">
                    <i class="ri-arrow-left-right-line"></i>
                    <div>
                        <div class="ti-title">Trocas Fáceis</div>
                        <div class="ti-sub">Política simplificada</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item">
                    <i class="ri-shield-check-line"></i>
                    <div>
                        <div class="ti-title">Pagamento Seguro</div>
                        <div class="ti-sub">Ambiente 100% protegido</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item">
                    <i class="ri-customer-service-2-line"></i>
                    <div>
                        <div class="ti-title">Atendimento</div>
                        <div class="ti-sub">Suporte pelo WhatsApp</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
    CATEGORIAS — grid clean com fotos grandes
═══════════════════════════════════════════ --}}
@if(count($categorias) > 0)
<section class="section-pad" id="categorias">
    <div class="container">
        <div class="section-head d-flex align-items-end justify-content-between flex-wrap gap-3">
            <div>
                <span class="section-kicker">Categorias</span>
                <h2>Navegue por categoria</h2>
                <p>Encontre exatamente o que você procura.</p>
            </div>
            <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="btn-link">
                Ver tudo <i class="ri-arrow-right-line"></i>
            </a>
        </div>

        <div class="category-grid grid-4 reveal">
            @foreach($categorias as $cat)
            <a href="{{ route('loja.produtos-categoria', [$cat->hash_ecommerce, 'link='.$config->loja_id]) }}" class="category-card">
                @if($cat->img ?? false)
                    <img src="{{ $cat->img }}" alt="{{ $cat->nome }}" class="cc-img" loading="lazy">
                @else
                    <div class="cc-img d-flex align-items-center justify-content-center" style="background:var(--luxe-cream)">
                        <i class="ri-price-tag-3-line" style="font-size:40px;color:var(--luxe-gold);opacity:0.45"></i>
                    </div>
                @endif
                <div class="cc-overlay"></div>
                <span class="cc-arrow"><i class="ri-arrow-right-line"></i></span>
                <div class="cc-info">
                    <div class="cc-name">{{ $cat->nome }}</div>
                    <div class="cc-count">Conferir coleção</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
    PRODUTOS EM DESTAQUE — grid clean
═══════════════════════════════════════════ --}}
<section class="section-pad-sm" id="produtos-destaque" style="background:var(--luxe-cream)">
    <div class="container">
        <div class="section-head d-flex align-items-end justify-content-between flex-wrap gap-3">
            <div>
                <span class="section-kicker">Em Destaque</span>
                <h2>Produtos em destaque</h2>
                <p>Os mais escolhidos pelos nossos clientes.</p>
            </div>
            <span style="font-size:12px;color:var(--luxe-tan)">{{ sizeof($produtosEmDestaque) }} produtos</span>
        </div>

        @if(sizeof($produtosEmDestaque) > 0)
        <div class="row g-4 reveal">
            @foreach(collect($produtosEmDestaque) as $p)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}">
                            <img src="{{ $p->img }}" alt="{{ $p->nome }}" class="img-primary" loading="lazy">
                        </a>
                        <div class="product-labels">
                            @if($p->percentual_desconto > 0)
                                <span class="badge-discount">-{{ $p->percentual_desconto }}%</span>
                            @endif
                        </div>
                        <div class="product-actions-hover">
                            <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}" class="action-icon" title="Ver Produto">
                                <i class="ri-eye-line"></i>
                            </a>
                        </div>
                    </div>
                    <div class="product-details">
                        <span class="product-cat">{{ $p->categoria ? $p->categoria->nome : 'Geral' }}</span>
                        <h3 class="product-title">
                            <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}">{{ $p->nome }}</a>
                        </h3>
                        @if($p->valor_ecommerce > 0)
                        <div class="product-price-row">
                            <span class="current-price">R$ {{ __moeda($p->valor_ecommerce) }}</span>
                            @if($p->percentual_desconto > 0)
                            <span class="old-price">R$ {{ __moeda($p->valor_ecommerce + ($p->valor_ecommerce * $p->percentual_desconto / 100)) }}</span>
                            @endif
                        </div>
                        @else
                        <div class="product-price-row">
                            <span style="font-size:12px;color:var(--luxe-tan)">Sob consulta</span>
                        </div>
                        @endif
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}" class="btn-buy">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="ri-box-3-line"></i>
            <h4>Nenhum produto em destaque</h4>
            <p>Adicione produtos em destaque para aparecerem aqui.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════
    BANNER PROMOCIONAL — clean
═══════════════════════════════════════════ --}}
<section class="promo-banner reveal" style="border-radius:0">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-lg-5 promo-img-col d-none d-lg-block">
                @if($config->banner_img ?? false)
                    <img src="{{ $config->banner_img }}" alt="Promoção" style="height:100%;width:100%;object-fit:cover">
                @else
                    <div style="height:100%;min-height:380px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center">
                        <i class="ri-star-fill" style="font-size:80px;color:rgba(255,255,255,0.18)"></i>
                    </div>
                @endif
            </div>
            <div class="col-lg-7">
                <div class="promo-content">
                    <span class="section-kicker promo-kicker">Oferta Especial</span>
                    <h2 style="color:#fff;font-size:clamp(26px,3.5vw,42px);font-weight:800;letter-spacing:-0.02em;margin-bottom:14px">
                        Qualidade que você pode confiar
                    </h2>
                    <p style="font-size:14px;color:rgba(255,255,255,0.65);margin:0 0 20px;max-width:440px;line-height:1.7">
                        Explore nossa coleção completa com as melhores condições de pagamento e atendimento personalizado.
                    </p>
                    <ul class="promo-checklist">
                        <li>
                            <span class="promo-check-icon"><i class="ri-check-line"></i></span>
                            Produtos com garantia de qualidade
                        </li>
                        <li>
                            <span class="promo-check-icon"><i class="ri-check-line"></i></span>
                            Preços competitivos e condições especiais
                        </li>
                        <li>
                            <span class="promo-check-icon"><i class="ri-check-line"></i></span>
                            Entrega rápida e atendimento ágil
                        </li>
                    </ul>
                    <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="btn-hero d-inline-flex mt-3">
                        Ver Todos os Produtos <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
    MAIS PRODUTOS — novidades
═══════════════════════════════════════════ --}}
@php
    $novidades = collect($produtosEmDestaque)->take(8)->skip(4);
@endphp
@if($novidades->count() > 0)
<section class="section-pad">
    <div class="container">
        <div class="section-head center reveal">
            <span class="section-kicker">Novidades</span>
            <h2>Mais produtos</h2>
            <p>Confira outros produtos da nossa loja.</p>
        </div>
        <div class="row g-4 reveal">
            @foreach($novidades as $p)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}" class="d-block text-decoration-none" style="color:inherit">
                    <div style="position:relative;margin-bottom:12px;border-radius:12px;overflow:hidden;background:var(--luxe-cream);aspect-ratio:1">
                        <img src="{{ $p->img }}" alt="{{ $p->nome }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s">
                        <div style="position:absolute;inset:0;background:rgba(66,84,186,0.45);opacity:0;transition:opacity 0.3s;display:flex;align-items:center;justify-content:center" class="quick-view-overlay">
                            <span style="background:#fff;color:var(--luxe-brown);font-size:12px;font-weight:600;padding:8px 18px;border-radius:8px">Ver Produto</span>
                        </div>
                    </div>
                    <h4 style="font-size:13px;font-weight:500;color:var(--luxe-brown);margin-bottom:4px;transition:color 0.2s" class="novidade-title">{{ $p->nome }}</h4>
                    @if($p->valor_ecommerce > 0)
                    <p style="font-weight:700;color:var(--luxe-brown);font-size:14px;margin:0">R$ {{ __moeda($p->valor_ecommerce) }}</p>
                    @endif
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5 reveal">
            <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="btn-outline-luxe">
                Ver Todos os Produtos <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
    CTA FINAL
═══════════════════════════════════════════ --}}
<section class="cta-banner reveal">
    <div class="cta-bg">
        @if($config->banner_img ?? false)
        <img src="{{ $config->banner_img }}" alt="">
        @endif
    </div>
    <div class="cta-content container">
        <span class="section-kicker cta-kicker">Coleção Completa</span>
        <h2>
            Conheça toda a nossa<br><span class="accent">seleção de produtos</span>
        </h2>
        <p>De produtos essenciais a itens exclusivos, temos tudo que você precisa com qualidade garantida.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="btn-hero">
                Comprar Agora <i class="ri-arrow-right-line"></i>
            </a>
            @if($config->link_whatsapp || $config->telefone)
            @php $waCta = $config->link_whatsapp ?: 'https://wa.me/'.preg_replace('/\D/', '', $config->telefone); @endphp
            <a href="{{ $waCta }}" target="_blank" class="btn-hero btn-wa-light">
                <i class="ri-whatsapp-fill"></i> Fale Conosco
            </a>
            @endif
        </div>
    </div>
</section>

@endsection

@section('js')
<script>
// ── Quick view hover ──
document.querySelectorAll('[href]').forEach(function(a) {
    var ov = a.querySelector('.quick-view-overlay');
    var img = a.querySelector('img');
    if (!ov) return;
    a.addEventListener('mouseenter', function() { ov.style.opacity = '1'; if(img) img.style.transform = 'scale(1.06)'; });
    a.addEventListener('mouseleave', function() { ov.style.opacity = '0'; if(img) img.style.transform = ''; });
});
</script>
@endsection
