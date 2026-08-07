@extends('loja.default', ['title' => 'Meu Carrinho'])

@section('content')

{{-- Barra de etapas --}}
@include('loja.partials.checkout_steps', ['checkoutStep' => 1])

{{-- Breadcrumb --}}
<div style="background:var(--luxe-cream);border-bottom:1px solid var(--border-light);padding:12px 0">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:12px">
                <li class="breadcrumb-item"><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" style="color:var(--luxe-tan)">Home</a></li>
                <li class="breadcrumb-item active" style="color:var(--luxe-brown)">Meu Carrinho</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5" id="cart-container-wrapper">
    <div class="row g-4" id="cart-container">
        <input type="hidden" id="carrinho_id" value="{{ $item->id ?? '' }}">

        {{-- ─── LISTA DE ITENS ─── --}}
        <div class="col-lg-8">

            <div style="background:var(--luxe-white);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid var(--border-light)">
                {{-- Header --}}
                <div style="padding:20px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px">
                    <i class="ri-shopping-bag-3-line" style="font-size:18px;color:var(--luxe-gold)"></i>
                    <span class="fw-bold" style="font-size:16px;color:var(--luxe-brown)">Meu Carrinho</span>
                    <span style="font-size:12px;background:var(--luxe-gold-light);color:var(--luxe-gold);padding:3px 10px;border-radius:50px;font-weight:600;margin-left:4px">
                        {{ $item != [] ? sizeof($item->itens) : 0 }} {{ ($item != [] && sizeof($item->itens) == 1) ? 'item' : 'itens' }}
                    </span>
                </div>

                {{-- Itens --}}
                <div>
                    @forelse($item->itens as $i)
                    <div class="cart-item-row">
                        {{-- Imagem --}}
                        <div class="cart-item-img">
                            <img src="{{ $i->produto->img }}" alt="{{ $i->produto->nome }}">
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow-1" style="min-width:0">
                            <div class="cart-item-name">
                                {{ $i->produto->nome }} {{ $i->variacao ? '— '.$i->variacao->descricao : '' }}
                            </div>
                            <div class="cart-item-cat">Unitário: R$ {{ __moeda($i->valor_unitario) }}</div>
                        </div>

                        {{-- Quantidade --}}
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('loja.atualiza-quantidade', [$i->id, 'link='.$config->loja_id]) }}" method="post" class="form-update-qty">
                                @csrf
                                @method('put')
                                <input type="hidden" name="link" value="{{ $config->loja_id }}">
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" onclick="changeCartQty('{{ $i->id }}', -1)">−</button>
                                    <input type="number" class="qty-input" id="qty-{{ $i->id }}" name="quantidade" value="{{ number_format($i->quantidade, 0) }}" min="1" readonly>
                                    <button type="button" class="qty-btn" onclick="changeCartQty('{{ $i->id }}', 1)">+</button>
                                </div>
                            </form>
                        </div>

                        {{-- Preço --}}
                        <div class="cart-item-price" style="white-space:nowrap">
                            R$ {{ __moeda($i->sub_total) }}
                        </div>

                        {{-- Remover --}}
                        <div>
                            <form action="{{ route('loja.remove-item', [$i->id, 'link='.$config->loja_id]) }}" method="post" class="form-remove-item">
                                @csrf
                                @method('delete')
                                <button type="button"
                                        class="btn-remove-item-ajax"
                                        style="width:30px;height:30px;border-radius:50%;border:1px solid rgba(220,38,38,0.2);background:rgba(220,38,38,0.05);color:#dc2626;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;transition:all 0.2s"
                                        onmouseover="this.style.background='rgba(220,38,38,0.12)'"
                                        onmouseout="this.style.background='rgba(220,38,38,0.05)'"
                                        title="Remover">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state py-5">
                        <i class="ri-shopping-bag-3-line"></i>
                        <h4>Seu carrinho está vazio</h4>
                        <p>Adicione produtos para continuar suas compras.</p>
                        <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="btn-buy d-inline-block" style="padding:10px 28px;border-radius:8px">Ir para a Loja</a>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Continuar comprando --}}
            @if($item != [] && sizeof($item->itens) > 0)
            <div class="mt-3">
                <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="d-inline-flex align-items-center gap-1" style="font-size:13px;color:var(--luxe-tan)">
                    <i class="ri-arrow-left-line"></i> Continuar Comprando
                </a>
            </div>
            @endif
        </div>

        {{-- ─── RESUMO DO PEDIDO ─── --}}
        @if($item != [] && sizeof($item->itens) > 0)
        <div class="col-lg-4">
            <div class="cart-summary-box">
                <div class="cart-summary-title">Resumo do Pedido</div>

                <div class="summary-row">
                    <span>Subtotal ({{ sizeof($item->itens) }} itens)</span>
                    <span>R$ {{ __moeda($item->valor_total) }}</span>
                </div>

                @if($item->valor_frete !== null)
                <div class="summary-row">
                    <span>Frete</span>
                    <span>@if($item->valor_frete == 0) <span style="color:#17a497;font-weight:600">Grátis</span>
                    @else R$ {{ __moeda($item->valor_frete) }} @endif</span>
                </div>
                @endif

                {{-- Cálculo de Frete --}}
                <form method="post" action="{{ route('loja.carrinho-setar-frete', ['link='.$config->loja_id]) }}" id="form-checkout-frete">
                    @csrf

                    <div style="border-top:1px solid var(--border-light);padding-top:16px;margin-top:12px">
                        <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:var(--luxe-tan);margin-bottom:10px">
                            Calcular Frete
                        </div>
                        <div class="d-flex gap-2">
                            <input data-mask="00000-000" class="search-input flex-grow-1" name="cep" id="cep" type="tel"
                                   placeholder="CEP: 00000-000" value="{{ $item->cep ?? '' }}"
                                   style="padding:8px 14px;border-radius:var(--radius-sm)">
                            <button type="button" class="btn-frete-ajax"
                                    style="padding:0 14px;background:var(--luxe-brown);color:white;border:none;border-radius:var(--radius-sm);cursor:pointer;font-size:14px;transition:background 0.2s"
                                    onmouseover="this.style.background='var(--luxe-tan)'"
                                    onmouseout="this.style.background='var(--luxe-brown)'"
                                    title="Calcular">
                                <i class="ri-truck-line"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Opções Frete --}}
                    <div class="mt-3" id="frete-options-container">
                        @if($dataFrete != null)
                            {!! $dataFrete !!}
                        @else
                            @if($config->habilitar_retirada)
                            <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid var(--border-light);border-radius:8px;cursor:pointer;margin-bottom:8px;font-size:13px">
                                <input class="radio-frete" type="radio" name="tipo_frete" id="retirada" value="0" data-valor="0" @if($item->tipo_frete == '0') checked @endif>
                                <span style="flex:1">Retirar na Loja</span>
                                <strong style="color:#17a497">Grátis</strong>
                            </label>
                            @endif
                            @if($config->frete_gratis_valor > 0 && $config->frete_gratis_valor <= $item->valor_total)
                            <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid var(--border-light);border-radius:8px;cursor:pointer;margin-bottom:8px;font-size:13px">
                                <input class="radio-frete" type="radio" name="tipo_frete" id="gratis" value="gratis" data-valor="0" @if($item->tipo_frete == 'gratis') checked @endif>
                                <span style="flex:1">Frete Grátis 🎉</span>
                                <strong style="color:#17a497">Grátis</strong>
                            </label>
                            @endif
                        @endif
                    </div>

                    <input type="hidden" name="valor_frete" id="valor_frete" value="{{ $item->valor_frete }}">
                    <input type="hidden" name="endereco_id" id="endereco_id" value="{{ $item->endereco_id }}">

                    @php $totalFinal = $item->valor_total + ($item->valor_frete ?? 0); @endphp
                    <div class="summary-row total" style="margin-top:12px">
                        <span>Total</span>
                        <span class="text-gold">R$ {{ __moeda($totalFinal) }}</span>
                    </div>

                    <button type="submit"
                            class="btn-checkout"
                            @if($item->valor_frete === null && $item->tipo_frete === null) disabled style="opacity:0.5;cursor:not-allowed" @endif>
                        <i class="ri-lock-2-line me-1"></i> Finalizar Compra
                    </button>
                    @if($item->valor_frete === null && $item->tipo_frete === null)
                    <p style="font-size:11px;text-align:center;color:var(--luxe-tan);margin-top:8px">
                        Calcule o frete para continuar
                    </p>
                    @endif
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- ─── SUGESTÕES: Você também vai gostar ─── --}}
    @if(isset($sugestoes) && sizeof($sugestoes) > 0)
    <section class="mt-5 pt-4" style="border-top:1px solid var(--border-light)">
        <div class="section-head d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <span class="section-kicker">Complemente seu pedido</span>
                <h2>Você também vai gostar</h2>
            </div>
            <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="btn-link">
                Ver mais produtos <i class="ri-arrow-right-line"></i>
            </a>
        </div>

        <div class="suggest-scroll">
            @foreach($sugestoes as $sp)
            <div class="product-card">
                <div class="product-img-wrapper">
                    <a href="{{ route('loja.produto-detalhe', [$sp->hash_ecommerce, 'link='.$config->loja_id]) }}">
                        <img src="{{ $sp->img }}" alt="{{ $sp->nome }}" class="img-primary" loading="lazy">
                    </a>
                    <div class="product-labels">
                        @if($sp->percentual_desconto > 0)
                        <span class="badge-discount">-{{ $sp->percentual_desconto }}%</span>
                        @endif
                    </div>
                </div>
                <div class="product-details">
                    <span class="product-cat">{{ $sp->categoria ? $sp->categoria->nome : 'Geral' }}</span>
                    <h3 class="product-title">
                        <a href="{{ route('loja.produto-detalhe', [$sp->hash_ecommerce, 'link='.$config->loja_id]) }}">{{ $sp->nome }}</a>
                    </h3>
                    @if($sp->valor_ecommerce > 0)
                    <div class="product-price-row">
                        <span class="current-price">R$ {{ __moeda($sp->valor_ecommerce) }}</span>
                        @if($sp->percentual_desconto > 0)
                        <span class="old-price">R$ {{ __moeda($sp->valor_ecommerce + ($sp->valor_ecommerce * $sp->percentual_desconto / 100)) }}</span>
                        @endif
                    </div>
                    @else
                    <div class="product-price-row">
                        <span style="font-size:12px;color:var(--luxe-tan)">Sob consulta</span>
                    </div>
                    @endif
                </div>
                <div class="product-actions">
                    <a href="{{ route('loja.produto-detalhe', [$sp->hash_ecommerce, 'link='.$config->loja_id]) }}" class="btn-buy">
                        Ver Detalhes
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@endsection

@section('js')
<script>
    // ── Alterar quantidade via AJAX ──
    function changeCartQty(itemId, amount) {
        var input = $('#qty-' + itemId);
        var val = parseInt(input.val()) || 1;
        val += amount; if (val < 1) val = 1;
        input.val(val);

        var form = input.closest('form');
        $.ajax({ url: form.attr('action'), type: 'POST', data: form.serialize(),
            success: function(html) { updateCartHTML(html); toastr.success('Quantidade atualizada!'); },
            error:   function()     { toastr.error('Erro ao atualizar.'); }
        });
    }

    // ── Remover item via AJAX ──
    $(document).on('click', '.btn-remove-item-ajax', function() {
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Remover item?',
            text: 'Deseja remover este produto do carrinho?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#17a497',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sim, remover',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.ajax({ url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function(html) { updateCartHTML(html); toastr.success('Item removido!'); },
                    error:   function()     { toastr.error('Erro ao remover.'); }
                });
            }
        });
    });

    // ── Calcular Frete via AJAX ──
    $(document).on('click', '.btn-frete-ajax', function() {
        var cep = $('#cep').val();
        if (cep.length !== 9) { Swal.fire('Atenção', 'Digite um CEP válido (Ex: 00000-000)', 'warning'); return; }
        $.get(path_url + 'api/ecommerce/calcular-frete', { carrinho_id: $('#carrinho_id').val(), cep: cep })
        .done(function(res) { $('#frete-options-container').html(res); })
        .fail(function()    { Swal.fire('Erro', 'CEP não encontrado.', 'error'); });
    });

    // ── Selecionar Frete ──
    $(document).on('click', '.radio-frete', function() {
        $('#valor_frete').val($(this).data('valor'));
        $('#endereco_id').val($(this).data('endereco-id') || '');
        $('.btn-checkout, .btn-pagamento-submit').removeAttr('disabled').css({ opacity: 1, cursor: 'pointer' });
    });

    // ── Atualizar HTML do carrinho ──
    function updateCartHTML(html) {
        var newCart = $(html).find('#cart-container').html();
        $('#cart-container').html(newCart);
        var newCount = $(html).find('#header-cart-count, .badge-count');
        if (newCount.length) $('#header-cart-count, .badge-count').each(function(i, el) {
            var n = $(html).find(el.tagName === 'SPAN' ? '.badge-count' : '#header-cart-count').first().html();
            if (n) $(el).html(n);
        });
        $('#cep').mask('00000-000');
    }

    // ── Máscara CEP ──
    $(function() { $('#cep').mask('00000-000'); });
</script>
@endsection
