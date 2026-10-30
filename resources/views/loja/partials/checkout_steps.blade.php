{{-- Barra de Etapas do Checkout Moderna com Tailwind CSS --}}
@php
    $steps = [
        1 => ['label' => 'Carrinho', 'icon' => 'ri-shopping-cart-2-line', 'route' => 'loja.carrinho'],
        2 => ['label' => 'Identificação', 'icon' => 'ri-user-line', 'route' => 'loja.cadastro'],
        3 => ['label' => 'Pagamento', 'icon' => 'ri-bank-card-line', 'route' => 'loja.pagamento'],
    ];
    $currentStep = $checkoutStep ?? 1;
@endphp

<div class="bg-white border-b border-slate-100 py-4 sm:py-6">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between relative">
            <!-- Linha Conectora de Fundo -->
            <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-1 bg-slate-100 -z-0"></div>
            
            @foreach($steps as $num => $s)
                @php
                    $isDone = $currentStep > $num;
                    $isActive = $currentStep === $num;
                @endphp
                <div class="relative z-10 flex flex-col items-center group">
                    <a href="{{ route($s['route'], ['link' => $config->loja_id]) }}"
                       class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-2xl font-bold text-xs sm:text-sm transition-all shadow-sm
                              {{ $isActive ? 'bg-indigo-600 text-white shadow-indigo-500/30 ring-4 ring-indigo-100 scale-105' : ($isDone ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400') }}">
                        @if($isDone)
                            <i class="ri-check-line text-base font-black"></i>
                        @else
                            <i class="{{ $s['icon'] }} text-base sm:text-lg"></i>
                        @endif
                    </a>
                    <span class="mt-2 text-[11px] sm:text-xs font-bold whitespace-nowrap {{ $isActive ? 'text-indigo-600' : ($isDone ? 'text-slate-800' : 'text-slate-400') }}">
                        {{ $s['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
