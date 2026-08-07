{{-- Barra de etapas do checkout — espera a variável $checkoutStep (1, 2 ou 3) --}}
@php
    $steps = [
        1 => ['label' => 'Carrinho', 'route' => 'loja.carrinho'],
        2 => ['label' => 'Identificação', 'route' => 'loja.cadastro'],
        3 => ['label' => 'Pagamento', 'route' => 'loja.pagamento'],
    ];
    $step = $checkoutStep ?? 1;
@endphp
<div class="checkout-steps">
    <div class="container">
        @foreach($steps as $num => $s)
            @if($num > 1)
            <div class="cs-line {{ $step >= $num ? 'filled' : '' }}"></div>
            @endif
            <a href="{{ route($s['route'], ['link='.$config->loja_id]) }}"
               class="cs-step {{ $step == $num ? 'active' : '' }} {{ $step > $num ? 'done' : '' }}">
                <span class="cs-dot">
                    @if($step > $num)<i class="ri-check-line"></i>@else{{ $num }}@endif
                </span>
                <span class="cs-label">{{ $s['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
