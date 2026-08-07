@extends('loja.default', ['title' => $categoria->nome])

@section('content')

{{-- Page Hero --}}
<section class="page-hero">
    <div class="page-hero-content container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $categoria->nome }}</li>
            </ol>
        </nav>
        <h1>{{ $categoria->nome }}</h1>
        <p class="badge-count-info">{{ sizeof($produtos) }} {{ sizeof($produtos) == 1 ? 'produto encontrado' : 'produtos encontrados' }}</p>
    </div>
</section>

{{-- Conteúdo --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- Sidebar Categorias --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-sidebar">
                <div class="filter-group">
                    <div class="filter-title"><i class="ri-price-tag-3-line"></i> Categorias</div>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="filter-link">
                            Todos os produtos
                            <span class="filter-count">{{ collect($categorias)->sum('produtos_count') }}</span>
                        </a>
                        @foreach($categorias as $c)
                        <a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link='.$config->loja_id]) }}"
                           class="filter-link {{ $c->id == $categoria->id ? 'active' : '' }}">
                            {{ $c->nome }}
                            <span class="filter-count">{{ $c->produtos_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="filter-group">
                    <div class="filter-title"><i class="ri-home-3-line"></i> Navegação</div>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('loja.index', ['link='.$config->loja_id]) }}" class="filter-link">
                            Início
                        </a>
                        <a href="{{ route('loja.pesquisa', ['link='.$config->loja_id]) }}" class="filter-link">
                            Ver todos os produtos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid de Produtos --}}
        <div class="col-lg-9">
            {{-- Sort Bar --}}
            <div class="sort-bar mb-4">
                <span>Mostrando <strong>{{ sizeof($produtos) }}</strong> em <strong>{{ $categoria->nome }}</strong></span>
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
            <div class="row g-3 reveal" id="cat-produtos-grid">
                @foreach($produtos as $p)
                <div class="col-6 col-md-4 col-lg-4 produto-item"
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
                            <span class="product-cat">{{ $categoria->nome }}</span>
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
                <h4>Nenhum produto nesta categoria</h4>
                <p>Esta categoria ainda não possui produtos cadastrados.</p>
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
    var grid = document.getElementById('cat-produtos-grid');
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
