@extends('layouts.app', ['title' => 'Produtos WooCommerce'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #96588a 0%, #7f54b3 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.8) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    
    .modulo-filter-bar { background: #fff; border-bottom: 1px solid #eef0f5; padding: 16px 24px; }
    .modulo-filter-bar label { font-size: 12px; font-weight: 600; color: #5a5a7a; }
    
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 16px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .modulo-table-wrap tbody tr:hover td { background: #fafbff; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
    
    .modulo-empty { padding: 60px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
    
    .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eef0f5; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            
            <div class="card border-0 shadow-sm modulo-form-card">
                
                {{-- CABEÇALHO PREMIUM --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-line"></i>
                                Produtos do WooCommerce
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie seus produtos integrados diretamente da sua loja.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('produtos.create', ['woocommerce=1']) }}" class="btn btn-light text-dark fw-semibold px-3 shadow-sm d-flex align-items-center gap-2">
                                <i class="ri-add-circle-fill text-primary"></i> Novo Produto WooCommerce
                            </a>
                        </div>
                    </div>
                </div>

                {{-- BARRA DE FILTRO --}}
                <div class="modulo-filter-bar">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            {!!Form::text('nome', 'Pesquisar por nome do produto')!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::tel('codigo_barras', 'Código de barras (EAN)')!!}
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-auto">
                            <a id="clear-filter" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('woocommerce-produtos.index') }}">
                                <i class="ri-eraser-fill"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
                
                <form action="{{ route('produtos.destroy-select') }}" method="post" id="form-delete-select">
                    @method('delete')
                    @csrf
                
                {{-- TABELA DE PRODUTOS --}}
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    <th width="70">Img</th>
                                    <th>Produto</th>
                                    <th>Integração</th>
                                    <th class="text-end">Estoque</th>
                                    <th class="text-end">Valor Venda</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr class="{{ $item->statusWoocommerce() }}">
                                    <td>
                                        <div class="form-check m-0">
                                            <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        @if(isset($item->img_aux))
                                        <img class="img-thumb" src="{{ $item->img_aux }}" alt="Imagem" onerror="this.src='/imgs/no-image.png'">
                                        @else
                                        <img class="img-thumb" src="{{ $item->img }}" alt="Imagem" onerror="this.src='/imgs/no-image.png'">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->nome }}</div>
                                        <div class="text-muted fs-12 d-flex gap-2 mt-1">
                                            <span><i class="ri-barcode-box-line"></i> {{ $item->codigo_barras ?: '--' }}</span>
                                            <span><i class="ri-price-tag-3-line"></i> {{ $item->categoria ? $item->categoria->nome : '--' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-12">
                                            <strong>ID WOO:</strong> {{ $item->woocommerce_id ?: '--' }}
                                        </div>
                                        <div class="fs-12 text-muted mt-1">
                                            @if($item->gerenciar_estoque)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-0">Sync: Sim</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-0">Sync: Não</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light text-secondary border px-2 py-1">{{ $item->estoqueAtual() }} {{ $item->unidade }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ __moeda($item->woocommerce_valor) }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1 m-0">
                                            
                                            <a class="btn btn-warning btn-sm px-2 rounded-2 text-dark" href="{{ route('woocommerce-produtos.edit', [$item->id, 'mercadolivre=1']) }}" title="Editar no WooCommerce">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            
                                            <a class="btn btn-primary btn-sm px-2 rounded-2" href="{{ route('produtos.duplicar', [$item->id]) }}" title="Duplicar">
                                                <i class="ri-file-copy-line"></i>
                                            </a>
                                            
                                            <a class="btn btn-dark btn-sm px-2 rounded-2 text-white" href="{{ route('woocommerce-produtos.galery', [$item->id]) }}" title="Galeria de Imagens">
                                                <i class="ri-image-line"></i>
                                            </a>
                                            
                                            @if($item->woocommerce_link)
                                            <a target="_blank" class="btn btn-light border btn-sm px-2 rounded-2" href="{{ $item->woocommerce_link }}" title="Ver na Loja Oficial">
                                                <i class="ri-links-fill"></i>
                                            </a>
                                            @endif
                                            
                                            <button type="button" class="btn btn-delete btn-sm btn-danger px-2 rounded-2" onclick="confirmDelete(this, '{{ route('produtos.destroy', $item->id) }}')" title="Excluir Produto">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="modulo-empty">
                                            <i class="ri-box-3-line"></i>
                                            <p>Nenhum produto cadastrado no WooCommerce encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-3 bg-light border-top d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-outline-danger btn-sm px-3 btn-delete-all" disabled>
                        <i class="ri-close-circle-line me-1"></i> Remover Selecionados
                    </button>
                </div>
                </form>

                @if($data->hasPages())
                <div class="px-4 py-3 border-top bg-white">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

<form id="form-delete-single" method="post" action="">
    @method('delete')
    @csrf
</form>

@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
<script>
    function confirmDelete(btn, url) {
        if(confirm('Tem certeza que deseja excluir este produto?')) {
            let form = document.getElementById('form-delete-single');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
