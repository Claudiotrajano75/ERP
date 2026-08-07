@extends('loja.default', ['title' => 'Resultado de Busca'])

@section('content')

{{-- Page Hero --}}
<section class="page-hero">
    <div class="page-hero-content container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}">Home</a></li>
                <li class="breadcrumb-item active">Busca</li>
            </ol>
        </nav>
        <h1>@if($pesquisa ?? false)Resultados para "{{ $pesquisa }}"@else Todos os Produtos @endif</h1>
        <p class="badge-count-info">{{ sizeof($produtos) }} {{ sizeof($produtos) == 1 ? 'produto encontrado' : 'produtos encontrados' }}</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">

        {{-- Sidebar Categorias --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-sidebar">
                <div class="filter-group">
                    <div class="filter-title"><i class="ri-price-tag-3-line"></i> Categorias</div>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id, 'pesquisa' => $pesquisa ?? '']) }}"
                           class="filter-link {{ !($categoria_pesquisa ?? false) ? 'active' : '' }}">
                            Todas as categorias
                            <span class="filter-count">{{ collect($categorias)->sum('produtos_count') }}</span>
                        </a>
                        @foreach($categorias as $c)
                        <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id, 'pesquisa' => $pesquisa ?? '', 'categoria' => $c->hash_ecommerce]) }}"
                           class="filter-link {{ ($categoria_pesquisa ?? null) == $c->hash_ecommerce ? 'active' : '' }}">
                            {{ $c->nome }}
                            <span class="filter-count">{{ $c->produtos_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Resultados --}}
        <div class="col-lg-9">
            <div class="sort-bar mb-4">
                <span>
                    @if($pesquisa ?? false)
                        Resultados para: <strong>"{{ $pesquisa }}"</strong>
                    @else
                        Todos os produtos
                    @endif
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;color:var(--luxe-tan)">Ordenar:</span>
                    <select class="sort-select" id="sort-select" onchange="sortProducts(this.value)">
                        <option value="default">Relevância</option>
                        <option value="price-asc">Menor Preço</option>
                        <option value="price-desc">Maior Preço</option>
                        <option value="name">Nome A-Z</option>
                    </select>
                </div>
            </div>

            @if(sizeof($produtos) > 0)
            <div class="row g-3 reveal" id="search-produtos-grid">
                @foreach($produtos as $p)
                <div class="col-6 col-md-4 col-lg-3 produto-item"
                     data-price="{{ $p->valor_ecommerce }}"
                     data-name="{{ $p->nome }}">
                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}">
                                <img src="{{ $p->img }}" alt="{{ $p->nome }}" class="img-primary" loading="lazy">
                            </a>
                            <div class="product-labels">
                                @if($p->percentual_desconto > 0)
                                    <span class="badge-discount">-{{ $p->percentual_desconto }}% OFF</span>
                                @endif
                            </div>
                            <div class="product-actions-hover">
                                <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}" class="action-icon" title="Ver Produto">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-details">
                            <span class="product-cat">{{ $p->categoria ? $p->categoria->nome : 'Geral' }}</span>
                            <h3 class="product-title">
                                <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}">{{ $p->nome }}</a>
                            </h3>
                            @if($p->valor_ecommerce > 0)
                            <div class="product-price-row">
                                <span class="current-price">R$ {{ __moeda($p->valor_ecommerce) }}</span>
                                @if($p->percentual_desconto > 0)
                                <span class="old-price">R$ {{ __moeda($p->valor_ecommerce + ($p->valor_ecommerce * $p->percentual_desconto / 100)) }}</span>
                                @endif
                            </div>
                            @else
                            <div class="product-price-row">
                                <span style="font-size:12px;color:var(--luxe-tan)">Sob consulta</span>
                            </div>
                            @endif
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('loja.produto-detalhe', [$p->hash_ecommerce, 'link='.$config->loja_id]) }}" class="btn-buy">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state py-5">
                <i class="ri-search-line"></i>
                <h4>Nenhum produto encontrado</h4>
                <p>
                    Não encontramos resultados para "{{ $pesquisa ?? '' }}". Tente outras palavras-chave.
                </p>
                <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="btn-outline-luxe mt-2">Voltar para o Início</a>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
function sortProducts(val) {
    var grid = document.getElementById('search-produtos-grid');
    var items = Array.from(grid.querySelectorAll('.produto-item'));

    items.sort(function(a, b) {
        if (val === 'price-asc') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
        if (val === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
        if (val === 'name') return a.dataset.name.localeCompare(b.dataset.name);
        return 0;
    });

    items.forEach(function(item) { grid.appendChild(item); });
}
</script>
@endsection
