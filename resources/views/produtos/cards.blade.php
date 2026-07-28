@foreach($produtos as $prod)
    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 p-1" onclick="addProdutos('{{ $prod->id }}')"
        style="cursor: pointer;">
        <div class="card h-100 pdv-card-produto"
            style="border: 1px solid #e8e8e8; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s ease; margin-bottom: 0;">

            <div style="position: relative; height: 85px; background: #f8f9fa; overflow: hidden;">
                <img src="{{$prod->img}}" class="card-img-top" alt="{{$prod->nome}}"
                    style="height: 100%; width: 100%; object-fit: cover;">

                @if(isset($prod->promocao) && $prod->promocao)
                    <span
                        style="position: absolute; top: 8px; left: -4px; background: #ff4d4d; color: white; padding: 2px 10px; font-size: 9px; font-weight: 700; border-radius: 0 8px 8px 0; box-shadow: 0 1px 4px rgba(0,0,0,0.2);">
                        Promoção
                    </span>
                @endif
            </div>

            <div class="card-body text-center d-flex flex-column justify-content-between"
                style="background: #fff; padding: 6px 6px !important;">
                <p class="text-dark mb-1"
                    style="font-size: 11px; font-weight: 500; line-height: 1.25; height: 2.5em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; color: #333 !important;">
                    {{$prod->nome}}
                </p>

                <div class="mt-auto">
                    @if($prod->valor_unitario > 0)
                        <p class="mb-0" style="font-size: 14px; font-weight: 700; color: #49526B;">
                            R$ {{ __moeda($prod->valor_unitario) }}
                        </p>
                    @else
                        <p class="mb-0" style="font-size: 14px; font-weight: 700; color: #49526B;">
                            --
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endforeach
