@foreach($produtos as $prod)
    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 p-1"
        onclick="addProdutos('{{ $prod->id }}')"
        style="cursor: pointer;">

        <div class="card h-100 pdv-card-produto pdv-product-card" style="margin-bottom: 0;">

            <!-- Imagem com Overlay Premium -->
            <div class="pdv-product-img-wrapper">
                <img src="{{ $prod->img }}"
                    class="pdv-product-img"
                    alt="{{ $prod->nome }}"
                    loading="lazy">

                <!-- Tag de Promoção -->
                @if(isset($prod->promocao) && $prod->promocao)
                    <span class="pdv-promo-tag">
                        <i class="ri-price-tag-3-fill me-1"></i>Promo
                    </span>
                @endif

                <!-- Overlay com ícone de adicionar -->
                <div class="pdv-product-overlay">
                    <div class="pdv-product-overlay-icon">
                        <i class="ri-add-line"></i>
                    </div>
                </div>
            </div>

            <!-- Corpo do Card -->
            <div class="pdv-product-body text-center">
                <p class="pdv-product-name">{{ $prod->nome }}</p>

                <div class="mt-auto">
                    @if($prod->valor_unitario > 0)
                        <p class="pdv-product-price">
                            <span class="pdv-currency">R$</span> {{ __moeda($prod->valor_unitario) }}
                        </p>
                    @else
                        <p class="pdv-product-price-empty">—</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endforeach

