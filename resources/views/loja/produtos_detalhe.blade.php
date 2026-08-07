@extends('loja.default', ['title' => $produto->nome])

@section('content')

{{-- ═══════════════════════════════════════════
    BREADCRUMB
═══════════════════════════════════════════ --}}
<div style="background:var(--luxe-cream);border-bottom:1px solid var(--border-light);padding:13px 0">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:12px;margin:0">
                <li class="breadcrumb-item">
                    <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" style="color:var(--luxe-tan)">Home</a>
                </li>
                @if($produto->categoria)
                <li class="breadcrumb-item">
                    <a href="{{ route('loja.produtos-categoria', [$produto->categoria->hash_ecommerce, 'link='.$config->loja_id]) }}" style="color:var(--luxe-tan)">{{ $produto->categoria->nome }}</a>
                </li>
                @endif
                <li class="breadcrumb-item active" style="color:var(--luxe-brown);font-weight:500;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $produto->nome }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ═══════════════════════════════════════════
    PRODUTO — galeria + buy box
═══════════════════════════════════════════ --}}
<div class="container py-5">
    <div class="row g-4 g-lg-5">

        {{-- ─── GALERIA ─── --}}
        <div class="col-lg-6">
            <div class="product-detail-gallery">
                <div class="detail-main-img" id="detail-zoom">
                    <img id="main-product-image" src="{{ $produto->img }}" alt="{{ $produto->nome }}">
                    <span class="detail-zoom-hint"><i class="ri-zoom-in-line"></i> Passe o mouse para ampliar</span>
                </div>

                @php $hasGallery = sizeof($produto->galeria) > 0; @endphp
                @if($hasGallery)
                <div class="detail-thumbs">
                    <div class="detail-thumb active" onclick="changeMainImage('{{ $produto->img }}', this)">
                        <img src="{{ $produto->img }}" alt="Principal">
                    </div>
                    @foreach($produto->galeria as $g)
                    <div class="detail-thumb" onclick="changeMainImage('{{ $g->img }}', this)">
                        <img src="{{ $g->img }}" alt="Foto">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ─── BUY BOX ─── --}}
        <div class="col-lg-6">
            <div class="detail-buybox">

                @if($produto->categoria)
                <a href="{{ route('loja.produtos-categoria', [$produto->categoria->hash_ecommerce, 'link='.$config->loja_id]) }}"
                   class="detail-category text-decoration-none mb-3">
                    {{ $produto->categoria->nome }}
                </a>
                @endif

                <h1 class="detail-title mb-2">{{ $produto->nome }}</h1>
                <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
                    @php $temEstoque = !$produto->gerenciar_estoque || ($produto->estoque && $produto->estoque->quantidade > 0); @endphp
                    <span class="detail-stock {{ $temEstoque ? 'in' : 'out' }}">
                        <i class="ri-{{ $temEstoque ? 'checkbox-circle' : 'close-circle' }}-line"></i>
                        {{ $temEstoque ? 'Disponível' : 'Sem estoque' }}
                    </span>
                    <span class="detail-sku">Ref: {{ $produto->referencia ?: $produto->id }}</span>
                </div>

                {{-- Preço --}}
                @if($produto->valor_ecommerce > 0)
                <div class="detail-price-row mb-2">
                    <span class="detail-price" id="detail-current-price">R$ {{ __moeda($produto->valor_ecommerce) }}</span>
                    @if($produto->percentual_desconto > 0)
                    <span class="detail-old-price">R$ {{ __moeda($produto->valor_ecommerce + ($produto->valor_ecommerce * $produto->percentual_desconto / 100)) }}</span>
                    <span class="detail-badge">-{{ $produto->percentual_desconto }}% OFF</span>
                    @endif
                </div>
                <div class="detail-installment mb-4" id="detail-installment">
                    <i class="ri-bank-card-line"></i>
                    <span>ou <strong>3x de R$ {{ __moeda($produto->valor_ecommerce / 3) }}</strong> sem juros</span>
                </div>
                @else
                <div class="mb-4">
                    <span style="font-size:18px;color:var(--luxe-tan)">Preço sob consulta</span>
                </div>
                @endif

                {{-- Descrição curta --}}
                @if($produto->descricao_ecommerce)
                <p style="font-size:14px;color:var(--luxe-tan);line-height:1.75;margin-bottom:24px">
                    {{ $produto->descricao_ecommerce }}
                </p>
                @endif

                {{-- Form de compra --}}
                <form method="post" action="{{ route('loja.adicionar-carrinho') }}" id="form-add-to-cart">
                    @csrf
                    <input type="hidden" name="link" value="{{ $config->loja_id }}">
                    <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                    <input type="hidden" name="comprar_agora" id="comprar_agora_val" value="0">

                    @if(sizeof($produto->variacoes) > 0)
                    <div class="mb-4">
                        <label class="detail-var-label" for="variacao_id">Escolha a Opção</label>
                        <select id="variacao_id" name="variacao_id" class="detail-variacao">
                            @foreach($produto->variacoes as $v)
                            <option value="{{ $v->id }}">{{ $v->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="detail-var-label" for="qty-input">Quantidade</label>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                            <input type="number" name="quantidade" id="qty-input" class="qty-input" value="1" min="1">
                            <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-4">
                        <button type="submit" class="btn-add-cart" id="btn-add-cart" onclick="setComprarAgora(0)">
                            <span class="btn-spinner"></span>
                            <i class="ri-shopping-bag-3-line"></i>
                            <span class="btn-label">Adicionar ao Carrinho</span>
                        </button>
                        <button type="submit" class="btn-add-cart outline" id="btn-buy-now" onclick="setComprarAgora(1)">
                            <span class="btn-spinner"></span>
                            <i class="ri-flashlight-line"></i>
                            <span class="btn-label">Comprar Agora</span>
                        </button>
                    </div>
                </form>

                {{-- Benefícios --}}
                <div class="detail-benefits pt-4 d-flex flex-column gap-3">
                    <div class="detail-benefit">
                        <i class="ri-truck-line"></i>
                        <div>
                            <b>Frete para todo o Brasil</b>
                            <span>Prazo de entrega informado no carrinho.</span>
                        </div>
                    </div>
                    <div class="detail-benefit">
                        <i class="ri-shield-check-line"></i>
                        <div>
                            <b>Pagamento 100% seguro</b>
                            <span>Ambiente protegido e criptografado.</span>
                        </div>
                    </div>
                    @if($config->link_whatsapp || $config->telefone)
                    @php $waLink = $config->link_whatsapp ?: 'https://wa.me/'.preg_replace('/\D/', '', $config->telefone); @endphp
                    <div class="detail-benefit">
                        <i class="ri-whatsapp-fill" style="color:#25D366"></i>
                        <div>
                            <b>Tire suas dúvidas</b>
                            <span>Fale com a gente pelo
                                <a href="{{ $waLink }}" target="_blank" class="wa-link">WhatsApp</a>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── DESCRIÇÃO COMPLETA ─── --}}
        @if($produto->texto_ecommerce)
        <div class="col-12 mt-5">
            <div class="detail-description">
                <h3><i class="ri-file-list-3-line"></i> Informações Detalhadas</h3>
                <div class="detail-description-body">
                    {!! $produto->texto_ecommerce !!}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════
    PRODUTOS RELACIONADOS
═══════════════════════════════════════════ --}}
@if(isset($produtosRelacionados) && sizeof($produtosRelacionados) > 0)
<section class="related-section py-5">
    <div class="container">
        <div class="section-head d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <span class="section-kicker">Você também pode gostar</span>
                <h2>Produtos relacionados</h2>
            </div>
            @if($produto->categoria)
            <a href="{{ route('loja.produtos-categoria', [$produto->categoria->hash_ecommerce, 'link='.$config->loja_id]) }}" class="btn-link">
                Ver categoria <i class="ri-arrow-right-line"></i>
            </a>
            @endif
        </div>

        <div class="related-scroll">
            @foreach($produtosRelacionados as $rp)
            <div class="product-card">
                <div class="product-img-wrapper">
                    <a href="{{ route('loja.produto-detalhe', [$rp->hash_ecommerce, 'link='.$config->loja_id]) }}">
                        <img src="{{ $rp->img }}" alt="{{ $rp->nome }}" class="img-primary" loading="lazy">
                    </a>
                    <div class="product-labels">
                        @if($rp->percentual_desconto > 0)
                        <span class="badge-discount">-{{ $rp->percentual_desconto }}%</span>
                        @endif
                    </div>
                </div>
                <div class="product-details">
                    <span class="product-cat">{{ $rp->categoria ? $rp->categoria->nome : 'Geral' }}</span>
                    <h3 class="product-title">
                        <a href="{{ route('loja.produto-detalhe', [$rp->hash_ecommerce, 'link='.$config->loja_id]) }}">{{ $rp->nome }}</a>
                    </h3>
                    @if($rp->valor_ecommerce > 0)
                    <div class="product-price-row">
                        <span class="current-price">R$ {{ __moeda($rp->valor_ecommerce) }}</span>
                        @if($rp->percentual_desconto > 0)
                        <span class="old-price">R$ {{ __moeda($rp->valor_ecommerce + ($rp->valor_ecommerce * $rp->percentual_desconto / 100)) }}</span>
                        @endif
                    </div>
                    @else
                    <div class="product-price-row">
                        <span style="font-size:12px;color:var(--luxe-tan)">Sob consulta</span>
                    </div>
                    @endif
                </div>
                <div class="product-actions">
                    <a href="{{ route('loja.produto-detalhe', [$rp->hash_ecommerce, 'link='.$config->loja_id]) }}" class="btn-buy">
                        Ver Detalhes
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@section('js')
<script>
    $(function() {
        getVariacao();
        initZoom();

        // Micro-interação: spinner → check → submete o form
        $('#form-add-to-cart').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var val = $('#comprar_agora_val').val();
            var btn = val == 1 ? $('#btn-buy-now') : $('#btn-add-cart');
            btn.addClass('loading').prop('disabled', true);
            setTimeout(function() {
                btn.removeClass('loading').addClass('btn-added');
                setTimeout(function() { form.submit(); }, 450);
            }, 550);
        });
    });
    $(document).on("change", "#variacao_id", getVariacao);

    function getVariacao() {
        var v = $('#variacao_id').val();
        if (v) {
            $.get(path_url + 'api/ecommerce/variacao/', { variacao_id: v })
            .done(function(s) {
                if (s.valor) {
                    $('#detail-current-price').html('R$ ' + convertFloatToMoeda(s.valor));
                    $('#detail-installment span').html('ou <strong>3x de R$ ' + convertFloatToMoeda(s.valor / 3) + '</strong> sem juros');
                }
            });
        }
    }

    function setComprarAgora(val) {
        $('#comprar_agora_val').val(val);
    }

    // ── Zoom (lupa) na imagem principal ──
    function initZoom() {
        var wrap = $('#detail-zoom');
        if (!wrap.length) return;
        var img = wrap.find('img');
        wrap.on('mousemove', function(e) {
            var r = wrap[0].getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width * 100;
            var y = (e.clientY - r.top) / r.height * 100;
            img.css({ 'transform-origin': x + '% ' + y + '%' });
        });
        wrap.on('mouseenter', function() { wrap.addClass('zooming'); });
        wrap.on('mouseleave', function() {
            wrap.removeClass('zooming');
            img.css('transform-origin', 'center center');
        });
    }

    function changeMainImage(url, el) {
        $('#main-product-image').attr('src', url);
        $('.detail-thumb').removeClass('active');
        $(el).addClass('active');
    }

    function changeQty(n) {
        var inp = $('#qty-input');
        var v = parseInt(inp.val()) || 1;
        v += n; if (v < 1) v = 1;
        inp.val(v);
    }
</script>
@endsection
