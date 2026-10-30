@extends('loja.default', ['title' => 'Catálogo de Produtos'])

@section('content')

@php
    $breadcrumbs = [
        ['label' => 'Produtos', 'url' => null]
    ];
@endphp

<!-- Breadcrumbs -->
<x-loja.breadcrumbs :links="$breadcrumbs" :config="$config" />

<!-- Cabeçalho -->
<div class="bg-white border-b border-slate-100 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Catálogo Completo</span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
            @if($pesquisa ?? false)
                Resultados para: <span class="text-indigo-600 font-extrabold">"{{ $pesquisa }}"</span>
            @else
                Todos os Produtos
            @endif
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-2">
            {{ count($produtos) }} {{ count($produtos) == 1 ? 'produto encontrado' : 'produtos encontrados' }}
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Sidebar de Categorias -->
        <aside class="hidden lg:block lg:col-span-3 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="ri-filter-3-line text-indigo-600"></i> Filtrar por Categoria
                </h3>
                <nav class="space-y-1">
                    <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id, 'pesquisa' => $pesquisa ?? '']) }}"
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-colors {{ empty($categoria_pesquisa) ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span>Todas as Categorias</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ empty($categoria_pesquisa) ? 'bg-indigo-200 text-indigo-800' : 'bg-slate-100 text-slate-400' }}">
                            {{ collect($categorias)->sum('produtos_count') }}
                        </span>
                    </a>
                    @foreach($categorias as $c)
                        <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id, 'pesquisa' => $pesquisa ?? '', 'categoria' => $c->hash_ecommerce]) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ ($categoria_pesquisa ?? null) == $c->hash_ecommerce ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="truncate">{{ $c->nome }}</span>
                            @if(isset($c->produtos_count) && $c->produtos_count > 0)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($categoria_pesquisa ?? null) == $c->hash_ecommerce ? 'bg-indigo-200 text-indigo-800' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $c->produtos_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <!-- Grid de Resultados -->
        <div class="lg:col-span-9">
            <x-loja.product-grid :produtos="$produtos" :config="$config" cols="grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3" emptyMessage="Nenhum produto encontrado com os filtros selecionados." />
        </div>
    </div>
</div>

@endsection
