@extends('loja.default', ['title' => 'Início'])

@section('content')

@php
    // Banners do Hero
    $banners = \App\Models\DestaqueMarketPlace::where('empresa_id', $config->empresa_id)
        ->where('status', 1)
        ->with('produto')
        ->orderByDesc('id')
        ->limit(5)
        ->get();

    // Coleções de Produtos
    $destaques = collect($produtosEmDestaque);

    // Produtos em Promoção (com percentual de desconto cadastrado)
    $promocoes = \App\Models\Produto::where('empresa_id', $config->empresa_id)
        ->where('status', 1)
        ->where('ecommerce', 1)
        ->where('percentual_desconto', '>', 0)
        ->limit(8)
        ->get();

    // Novidades (produtos adicionados recentemente)
    $novidades = \App\Models\Produto::where('empresa_id', $config->empresa_id)
        ->where('status', 1)
        ->where('ecommerce', 1)
        ->orderByDesc('id')
        ->limit(8)
        ->get();
@endphp

<!-- 1. Carrossel Principal de Banners (Hero com Swiper.js) -->
<x-loja.hero-carousel :banners="$banners" :config="$config" />

<!-- 2. Barra de Benefícios Rápidos (Mobile & Desktop) -->
<section class="border-b border-slate-100 bg-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <div class="flex items-center gap-3 p-2">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="ri-truck-line"></i>
                </div>
                <div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Envio Rápido</h5>
                    <p class="text-[11px] text-slate-400">Para todo o Brasil</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-2">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="ri-shield-check-line"></i>
                </div>
                <div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Pagamento Seguro</h5>
                    <p class="text-[11px] text-slate-400">Criptografia de ponta</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-2">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="ri-exchange-dollar-line"></i>
                </div>
                <div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Até 10x no Cartão</h5>
                    <p class="text-[11px] text-slate-400">Ou desconto no PIX</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-2">
                <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="ri-customer-service-2-line"></i>
                </div>
                <div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Suporte Dedicado</h5>
                    <p class="text-[11px] text-slate-400">Atendimento humano</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Vitrine de Categorias em Destaque -->
@if(count($categorias) > 0)
<section class="py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Navegue por departamentos</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Compre por Categoria</h2>
            </div>
            <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
               class="text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 group">
                <span>Ver todas</span>
                <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <!-- Rolagem horizontal suave no mobile e grid no desktop -->
        <div class="flex sm:grid sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 overflow-x-auto pb-4 sm:pb-0 scrollbar-none">
            @foreach(collect($categorias)->take(6) as $cat)
                <div class="flex-shrink-0 w-36 sm:w-auto">
                    <x-loja.category-card :categoria="$cat" :config="$config" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 4. Vitrine de Produtos em Destaque -->
<section class="py-12 sm:py-16 bg-white border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-500 flex items-center gap-1.5 mb-1">
                    <i class="ri-star-fill"></i> Seleção Especial
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Produtos em Destaque</h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Os itens mais procurados e amados pelos nossos clientes.</p>
            </div>
            <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all self-start sm:self-auto">
                <span>Ver Todos os Produtos</span>
                <i class="ri-arrow-right-line"></i>
            </a>
        </div>

        <x-loja.product-grid :produtos="$destaques" :config="$config" emptyMessage="Novos produtos em destaque estarão disponíveis em breve." />
    </div>
</section>

<!-- 5. Banner Promocional de Meio de Página (Alta Conversão) -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-950 text-white p-8 sm:p-14 shadow-xl">
            <div class="relative z-10 max-w-xl space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30">
                    <i class="ri-fire-fill text-rose-400"></i> Oferta por Tempo Limitado
                </span>
                <h3 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                    Qualidade superior e condições imperdíveis.
                </h3>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                    Aproveite nossos descontos especiais com parcelamento facilitado em até 10x sem juros e entrega segura até o seu endereço.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
                       class="px-7 py-3.5 rounded-2xl bg-white hover:bg-slate-100 text-indigo-900 font-extrabold text-sm shadow-md transition-all hover:scale-105">
                        Aproveitar Ofertas Agora
                    </a>
                </div>
            </div>

            <!-- Detalhe decorativo no fundo -->
            <div class="absolute right-0 bottom-0 top-0 w-1/2 opacity-10 pointer-events-none flex items-center justify-center">
                <i class="ri-shopping-bag-3-fill text-[300px]"></i>
            </div>
        </div>
    </div>
</section>

<!-- 6. Seção de Promoções (Ofertas com Desconto %) -->
@if($promocoes->count() > 0)
<section class="py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-500 flex items-center gap-1.5 mb-1">
                    <i class="ri-percent-fill"></i> Economize Mais
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Super Promoções</h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Preços reduzidos por tempo limitado.</p>
            </div>
        </div>

        <x-loja.product-carousel :produtos="$promocoes" :config="$config" id="carousel-promocoes" />
    </div>
</section>
@endif

<!-- 7. Seção de Novidades e Lançamentos Recentes -->
@if($novidades->count() > 0)
<section class="py-12 sm:py-16 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 flex items-center gap-1.5 mb-1">
                    <i class="ri-sparkling-2-line"></i> Recém-Chegados
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Novidades na Loja</h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Fique por dentro dos últimos lançamentos adicionados ao nosso catálogo.</p>
            </div>
            <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}"
               class="text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                <span>Ver mais</span>
                <i class="ri-arrow-right-line"></i>
            </a>
        </div>

        <x-loja.product-carousel :produtos="$novidades" :config="$config" id="carousel-novidades" />
    </div>
</section>
@endif

<!-- 8. Newsletter -->
<div class="px-4 sm:px-6 lg:px-8">
    <x-loja.newsletter />
</div>

@endsection
