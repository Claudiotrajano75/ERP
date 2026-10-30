@extends('loja.default', ['title' => $produto->nome])

@section('content')

@php
    $preco = $produto->valor_ecommerce ?? $produto->valor_unitario ?? 0;
    $desconto = $produto->percentual_desconto ?? 0;
    $temDesconto = $desconto > 0;
    $precoAntigo = $temDesconto ? ($preco + ($preco * $desconto / 100)) : 0;
    $parcelas = 10;
    $valorParcela = $preco > 0 ? ($preco / $parcelas) : 0;
    $temEstoque = !$produto->gerenciar_estoque || ($produto->estoque && $produto->estoque->quantidade > 0);
    $galeria = $produto->galeria ?? collect();

    $breadcrumbs = [
        ['label' => $produto->categoria ? $produto->categoria->nome : 'Produtos', 'url' => $produto->categoria ? route('loja.produtos-categoria', [$produto->categoria->hash_ecommerce, 'link' => $config->loja_id]) : route('loja.pesquisa', ['link' => $config->loja_id])],
        ['label' => $produto->nome, 'url' => null]
    ];
@endphp

<!-- Breadcrumbs -->
<x-loja.breadcrumbs :links="$breadcrumbs" :config="$config" />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10"
     x-data="{
        imagemPrincipal: '{{ $produto->img }}',
        quantidade: 1,
        variacaoId: '{{ count($produto->variacoes) > 0 ? $produto->variacoes[0]->id : '' }}',
        precoAtual: {{ (float)$preco }},
        precoParcela: {{ (float)$valorParcela }},
        adicionando: false,
        activeTab: 'descricao',
        async updateVariacao(e) {
            const vId = e.target.value;
            this.variacaoId = vId;
            if (!vId) return;
            try {
                const res = await fetch(`/api/ecommerce/variacao/?variacao_id=${vId}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data.valor) {
                        this.precoAtual = parseFloat(data.valor);
                        this.precoParcela = this.precoAtual / {{ $parcelas }};
                    }
                }
            } catch (err) {
                console.error(err);
            }
        },
        async adicionarAoCarrinhoAjax() {
            if (this.adicionando) return;
            this.adicionando = true;
            try {
                const form = document.getElementById('form-comprar-produto');
                const formData = new FormData(form);
                formData.set('comprar_agora', '0');

                const res = await fetch('{{ route('loja.adicionar-carrinho') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    window.dispatchEvent(new CustomEvent('open-cart-drawer', { detail: { cart: data } }));
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cart: data } }));
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: data.message || 'Produto adicionado ao carrinho!', type: 'success' } }));
                } else {
                    alert(data.message || 'Não foi possível adicionar o produto ao carrinho.');
                }
            } catch(e) {
                console.error(e);
                alert('Erro ao adicionar produto ao carrinho. Verifique sua conexão.');
            } finally {
                this.adicionando = false;
            }
        }
     }">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- Coluna Esquerda: Galeria de Imagens com Miniaturas e Zoom -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Imagem Principal com Zoom Suave -->
            <div class="relative w-full aspect-square sm:aspect-[4/3] rounded-3xl overflow-hidden bg-white border border-slate-100 shadow-sm group">
                <img :src="imagemPrincipal"
                     alt="{{ $produto->nome }}"
                     class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500"
                     loading="eager">

                @if($temDesconto)
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-xl text-xs font-black bg-rose-500 text-white shadow-md">
                        -{{ number_format($desconto, 0) }}% OFF
                    </span>
                @endif

                <span class="absolute bottom-4 right-4 px-3 py-1.5 rounded-xl bg-slate-900/60 backdrop-blur-md text-white text-xs font-medium flex items-center gap-1.5 pointer-events-none">
                    <i class="ri-zoom-in-line text-sm"></i> Imagem Ilustrativa
                </span>
            </div>

            <!-- Miniaturas da Galeria -->
            @if(count($galeria) > 0)
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
                    <button type="button"
                            @click="imagemPrincipal = '{{ $produto->img }}'"
                            class="w-20 h-20 rounded-2xl border-2 p-1 bg-white overflow-hidden flex-shrink-0 transition-all"
                            :class="imagemPrincipal === '{{ $produto->img }}' ? 'border-indigo-600 ring-2 ring-indigo-600/20' : 'border-slate-100 hover:border-slate-300'">
                        <img src="{{ $produto->img }}" alt="{{ $produto->nome }}" class="w-full h-full object-cover rounded-xl">
                    </button>
                    @foreach($galeria as $foto)
                        <button type="button"
                                @click="imagemPrincipal = '{{ $foto->img }}'"
                                class="w-20 h-20 rounded-2xl border-2 p-1 bg-white overflow-hidden flex-shrink-0 transition-all"
                                :class="imagemPrincipal === '{{ $foto->img }}' ? 'border-indigo-600 ring-2 ring-indigo-600/20' : 'border-slate-100 hover:border-slate-300'">
                            <img src="{{ $foto->img }}" alt="" class="w-full h-full object-cover rounded-xl">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Coluna Direita: Buy Box / Informações de Compra -->
        <div class="lg:col-span-5 flex flex-col justify-between">
            <div class="space-y-6">
                <!-- Categoria e Estoque -->
                <div class="flex items-center justify-between flex-wrap gap-2">
                    @if($produto->categoria)
                        <a href="{{ route('loja.produtos-categoria', [$produto->categoria->hash_ecommerce, 'link' => $config->loja_id]) }}"
                           class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full hover:bg-indigo-100 transition-colors">
                            {{ $produto->categoria->nome }}
                        </a>
                    @endif

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full {{ $temEstoque ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            <span class="w-2 h-2 rounded-full {{ $temEstoque ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $temEstoque ? 'Pronta Entrega' : 'Esgotado' }}
                        </span>
                        <span class="text-xs text-slate-400">Ref: {{ $produto->referencia ?: $produto->id }}</span>
                    </div>
                </div>

                <!-- Título -->
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $produto->nome }}
                </h1>

                <!-- Bloco de Preço -->
                @if($preco > 0)
                    <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100 space-y-2">
                        @if($temDesconto)
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-slate-400 line-through">R$ {{ __moeda($precoAntigo) }}</span>
                                <span class="text-xs font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">Economize R$ {{ __moeda($precoAntigo - $preco) }}</span>
                            </div>
                        @endif

                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight"
                                  x-text="'R$ ' + Number(precoAtual).toLocaleString('pt-BR', {minimumFractionDigits: 2})">
                                R$ {{ __moeda($preco) }}
                            </span>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-lg">à vista</span>
                        </div>

                        <p class="text-xs sm:text-sm text-slate-600 font-medium">
                            ou até <strong class="text-slate-900 font-bold">{{ $parcelas }}x</strong> de <strong class="text-slate-900 font-bold" x-text="'R$ ' + Number(precoParcela).toLocaleString('pt-BR', {minimumFractionDigits: 2})">R$ {{ __moeda($valorParcela) }}</strong> sem juros
                        </p>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-slate-100 text-slate-700 font-semibold text-sm">
                        Preço sob consulta
                    </div>
                @endif

                <!-- Descrição Breve -->
                @if($produto->descricao_ecommerce)
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $produto->descricao_ecommerce }}
                    </p>
                @endif

                <!-- Formulário de Compra -->
                <form id="form-comprar-produto" action="{{ route('loja.adicionar-carrinho') }}" method="POST" class="space-y-5 pt-2">
                    @csrf
                    <input type="hidden" name="link" value="{{ $config->loja_id }}">
                    <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                    <input type="hidden" name="comprar_agora" id="comprar_agora" value="0">

                    <!-- Variações de Modelo / Tamanho / Cor (se existirem) -->
                    @if(count($produto->variacoes) > 0)
                        <div class="space-y-2">
                            <label for="variacao_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Escolha uma Opção:
                            </label>
                            <select id="variacao_id"
                                    name="variacao_id"
                                    @change="updateVariacao($event)"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                                @foreach($produto->variacoes as $v)
                                    <option value="{{ $v->id }}">{{ $v->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Seletor de Quantidade -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Quantidade:
                        </label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center border border-slate-200 rounded-2xl bg-white overflow-hidden">
                                <button type="button"
                                        @click="if(quantidade > 1) quantidade--"
                                        class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold transition-colors">
                                    -
                                </button>
                                <input type="number"
                                       name="quantidade"
                                       x-model="quantidade"
                                       min="1"
                                       class="w-12 h-10 text-center font-bold text-sm text-slate-900 border-none outline-none">
                                <button type="button"
                                        @click="quantidade++"
                                        class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold transition-colors">
                                    +
                                </button>
                            </div>
                            <span class="text-xs text-slate-400">Unidades</span>
                        </div>
                    </div>

                    <!-- Botões Principais de Conversão -->
                    @if($temEstoque && $preco > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <!-- Adicionar ao Carrinho via AJAX (Abre Cart Drawer Lateral) -->
                            <button type="button"
                                    @click="adicionarAoCarrinhoAjax()"
                                    :disabled="adicionando"
                                    class="py-4 px-6 rounded-2xl border-2 border-indigo-600 bg-indigo-50 hover:bg-indigo-100/70 text-indigo-700 font-extrabold text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
                                <i x-show="!adicionando" class="ri-shopping-cart-2-line text-lg"></i>
                                <i x-show="adicionando" class="ri-loader-4-line animate-spin text-lg" style="display: none;"></i>
                                <span x-text="adicionando ? 'Adicionando...' : 'Adicionar ao Carrinho'">Adicionar ao Carrinho</span>
                            </button>

                            <!-- Comprar Agora -->
                            <button type="submit"
                                    onclick="document.getElementById('comprar_agora').value='1'"
                                    class="py-4 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm flex items-center justify-center gap-2 shadow-xl shadow-indigo-600/30 transition-all hover:scale-[1.02]">
                                <i class="ri-flashlight-fill text-lg text-amber-300"></i>
                                <span>Comprar Agora</span>
                            </button>
                        </div>
                    @else
                        <button type="button" disabled
                                class="w-full py-4 px-6 rounded-2xl bg-slate-200 text-slate-400 font-bold text-sm cursor-not-allowed">
                            Produto Indisponível no Momento
                        </button>
                    @endif
                </form>

                <!-- Benefícios no Buy Box -->
                <div class="pt-6 border-t border-slate-100 space-y-3">
                    <div class="flex items-center gap-3 text-xs text-slate-600">
                        <i class="ri-truck-line text-lg text-indigo-600"></i>
                        <span><strong>Frete e prazo:</strong> Calculado na etapa do carrinho.</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-600">
                        <i class="ri-shield-check-line text-lg text-emerald-600"></i>
                        <span><strong>Garantia de Entrega:</strong> Produto com garantia e nota fiscal.</span>
                    </div>
                    @if(!empty($config->link_whatsapp))
                        <div class="flex items-center gap-3 text-xs text-slate-600">
                            <i class="ri-whatsapp-line text-lg text-emerald-500"></i>
                            <span>Dúvidas sobre este produto? <a href="{{ $config->link_whatsapp }}" target="_blank" class="text-emerald-600 font-bold hover:underline">Fale conosco no WhatsApp</a></span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Abas de Informações: Descrição Longa e Especificações -->
    <div class="mt-16 pt-10 border-t border-slate-200">
        <div class="flex border-b border-slate-200 gap-8">
            <button type="button"
                    @click="activeTab = 'descricao'"
                    class="pb-4 text-sm font-extrabold tracking-tight transition-colors border-b-2"
                    :class="activeTab === 'descricao' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                Descrição Completa
            </button>
            <button type="button"
                    @click="activeTab = 'garantia'"
                    class="pb-4 text-sm font-extrabold tracking-tight transition-colors border-b-2"
                    :class="activeTab === 'garantia' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                Formas de Pagamento & Envio
            </button>
        </div>

        <div class="py-6">
            <!-- Descrição -->
            <div x-show="activeTab === 'descricao'" class="prose max-w-none text-slate-700 leading-relaxed text-sm sm:text-base">
                @if($produto->texto_ecommerce)
                    {!! $produto->texto_ecommerce !!}
                @elseif($produto->descricao_ecommerce)
                    <p>{{ $produto->descricao_ecommerce }}</p>
                @else
                    <p class="text-slate-400 italic">Nenhuma descrição detalhada disponível para este produto.</p>
                @endif
            </div>

            <!-- Informações de Pagamento e Envio -->
            <div x-show="activeTab === 'garantia'" style="display: none;" class="space-y-4 text-sm text-slate-600">
                <p>Nossos pedidos são despachados com seguro e código de rastreamento enviado diretamente para seu e-mail ou WhatsApp.</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <h6 class="font-bold text-slate-900 mb-1">PIX</h6>
                        <p class="text-xs text-slate-500">Aprovação imediata e envio mais rápido do seu pacote.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <h6 class="font-bold text-slate-900 mb-1">Cartão de Crédito</h6>
                        <p class="text-xs text-slate-500">Parcele em até 10x com segurança via Mercado Pago.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <h6 class="font-bold text-slate-900 mb-1">Boleto Bancário</h6>
                        <p class="text-xs text-slate-500">Pagamento facilitado em qualquer banco ou aplicativo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Produtos Relacionados -->
    @if(isset($produtosRelacionados) && count($produtosRelacionados) > 0)
        <div class="mt-16 pt-10 border-t border-slate-200">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Quem comprou este produto também comprou</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Produtos Relacionados</h3>
                </div>
            </div>

            <x-loja.product-carousel :produtos="$produtosRelacionados" :config="$config" id="carousel-relacionados" />
        </div>
    @endif
</div>

<!-- Schema.org JSON-LD para SEO do Google -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": {!! json_encode($produto->nome) !!},
    "image": {!! json_encode((string) $produto->img) !!},
    "sku": {!! json_encode((string) ($produto->referencia ?: $produto->id)) !!},
    @if($produto->descricao_ecommerce)
    "description": {!! json_encode((string) $produto->descricao_ecommerce) !!},
    @endif
    @if($produto->categoria && $produto->categoria->nome)
    "category": {!! json_encode((string) $produto->categoria->nome) !!},
    @endif
    "url": {!! json_encode(url()->current() . '?link=' . $config->loja_id) !!}
    @if($preco > 0)
    ,"offers": {
        "@type": "Offer",
        "priceCurrency": "BRL",
        "price": {!! json_encode(number_format((float) $preco, 2, '.', '')) !!},
        "availability": "https://schema.org/{{ $temEstoque ? 'InStock' : 'OutOfStock' }}",
        "url": {!! json_encode(url()->current() . '?link=' . $config->loja_id) !!}
    }
    @endif
}
</script>

@endsection
