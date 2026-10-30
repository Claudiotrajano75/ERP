{{-- Grid Responsivo de Produtos --}}
@props([
    'produtos' => [],
    'config',
    'emptyMessage' => 'Nenhum produto encontrado nesta seção.',
    'cols' => 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4'
])

@php
    $lista = collect($produtos);
@endphp

@if($lista->count() > 0)
    <div class="grid {{ $cols }} gap-3 sm:gap-6">
        @foreach($lista as $produto)
            <x-loja.product-card :produto="$produto" :config="$config" />
        @endforeach
    </div>
@else
    <div class="py-16 px-4 text-center rounded-3xl bg-slate-50 border border-slate-100 max-w-lg mx-auto">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="ri-inbox-line"></i>
        </div>
        <h4 class="text-base font-bold text-slate-800 mb-1">Nenhum produto disponível</h4>
        <p class="text-xs sm:text-sm text-slate-500 mb-4">{{ $emptyMessage }}</p>
        <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all">
            <span>Ver Todos os Produtos</span>
            <i class="ri-arrow-right-line"></i>
        </a>
    </div>
@endif
