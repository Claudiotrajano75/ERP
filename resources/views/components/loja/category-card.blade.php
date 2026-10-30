{{-- Card de Categoria em Destaque --}}
@props(['categoria', 'config'])

@php
    $link = route('loja.produtos-categoria', [$categoria->hash_ecommerce, 'link' => $config->loja_id]);
    $temImg = !empty($categoria->img) && $categoria->img !== '/ecommerce/img/placeholder-cat.png';
@endphp

<a href="{{ $link }}"
   class="group relative flex flex-col items-center p-4 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 hover:-translate-y-1 text-center min-w-[140px] sm:min-w-0">
    <!-- Imagem da Categoria ou Ícone de Fallback -->
    <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-slate-50 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform duration-300">
        @if($temImg)
            <img src="{{ $categoria->img }}"
                 alt="{{ $categoria->nome }}"
                 class="w-full h-full object-cover"
                 loading="lazy">
        @else
            <div class="w-full h-full bg-gradient-to-tr from-indigo-50 to-violet-50 flex items-center justify-center text-indigo-500 group-hover:from-indigo-100 group-hover:to-violet-100 transition-colors">
                <i class="ri-price-tag-3-line text-3xl group-hover:rotate-6 transition-transform"></i>
            </div>
        @endif
    </div>

    <!-- Título e Contagem -->
    <h3 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1">
        {{ $categoria->nome }}
    </h3>

    @if(isset($categoria->produtos_count) && $categoria->produtos_count > 0)
        <span class="text-xs text-slate-400 font-medium mt-0.5">
            {{ $categoria->produtos_count }} {{ $categoria->produtos_count == 1 ? 'item' : 'itens' }}
        </span>
    @else
        <span class="text-xs text-indigo-500 font-semibold mt-0.5 group-hover:underline">
            Explorar
        </span>
    @endif
</a>
