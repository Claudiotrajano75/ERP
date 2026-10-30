@extends('loja.default', ['title' => $categoria->nome])

@section('content')

@php
    $breadcrumbs = [
        ['label' => 'Categorias', 'url' => route('loja.pesquisa', ['link' => $config->loja_id])],
        ['label' => $categoria->nome, 'url' => null]
    ];
@endphp

<!-- Breadcrumbs -->
<x-loja.breadcrumbs :links="$breadcrumbs" :config="$config" />

<!-- Cabeçalho da Categoria -->
<div class="bg-white border-b border-slate-100 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Categoria</span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $categoria->nome }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                {{ count($produtos) }} {{ count($produtos) == 1 ? 'produto encontrado' : 'produtos encontrados' }}
            </p>
        </div>
    </div>
</div>

<!-- Conteúdo com Barra Lateral e Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Sidebar de Categorias (Desktop) -->
        <aside class="hidden lg:block lg:col-span-3 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="ri-grid-fill text-indigo-600"></i> Departamentos
                </h3>
                <nav class="space-y-1">
                    <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                        <span>Todos os Produtos</span>
                    </a>
                    @foreach($categorias as $c)
                        <a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link' => $config->loja_id]) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ $c->id == $categoria->id ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="truncate">{{ $c->nome }}</span>
                            @if(isset($c->produtos_count) && $c->produtos_count > 0)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $c->id == $categoria->id ? 'bg-indigo-200 text-indigo-800' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $c->produtos_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <!-- Grid de Produtos da Categoria -->
        <div class="lg:col-span-9">
            <x-loja.product-grid :produtos="$produtos" :config="$config" cols="grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3" emptyMessage="Nenhum produto cadastrado nesta categoria ainda." />
        </div>
    </div>
</div>

@endsection
