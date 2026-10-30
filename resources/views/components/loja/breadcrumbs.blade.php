{{-- Trilha de Navegação (Breadcrumbs) --}}
@props(['links' => [], 'config'])

<nav aria-label="Navegação estruturada" class="py-3 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <ol class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">
        <li>
            <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                <i class="ri-home-4-line text-sm"></i>
                <span>Início</span>
            </a>
        </li>

        @foreach($links as $item)
            <li class="flex items-center gap-1.5">
                <i class="ri-arrow-right-s-line text-slate-300 text-sm"></i>
                @if(!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-indigo-600 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-slate-800 font-semibold truncate max-w-xs sm:max-w-md">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
