@extends('layouts.app', ['title' => 'Produtos de Ecommerce'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
    
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
    
    .img-thumbnail-custom { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e0e3eb; background: #fff; padding: 2px; }
    .status-badge { display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; padding: 4px 8px; font-size: 11px; font-weight: 700; gap: 4px; }
    .status-badge.active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-badge.inactive { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
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
                                <i class="ri-shopping-cart-2-line"></i>
                                Produtos no E-commerce
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie o catálogo, preços e estoque dos produtos da loja virtual.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('produtos.create', ['ecommerce=1']) }}" class="btn btn-success btn-sm px-3 d-flex align-items-center gap-1">
                                <i class="ri-add-line fs-16"></i> Novo Produto
                            </a>
                        </div>
                    </div>
                </div>

                {{-- BARRA DE FILTRO --}}
                <div class="modulo-filter-bar">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            {!!Form::text('nome', 'Pesquisar por nome')!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::select('status', 'Status', ['' => 'Todos', '1' => 'Ativos', '0' => 'Desativados'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-auto">
                            <a id="clear-filter" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('produtos-ecommerce.index') }}">
                                <i class="ri-eraser-fill"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- TABELA PREMIUM --}}
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Imagem</th>
                                    <th>Nome do Produto</th>
                                    <th>Un.</th>
                                    <th>Categoria</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    <th class="text-end">Valor (Loja)</th>
                                    <th class="text-end" style="width: 180px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <img class="img-thumbnail-custom" src="{{ $item->img }}" alt="Imagem" onerror="this.src='/imgs/no-image.png'">
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        <div class="d-block text-truncate" style="max-width: 250px;" title="{{ $item->nome }}">
                                            {{ $item->nome }}
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $item->unidade }}</td>
                                    <td class="text-muted">{{ $item->categoria ? $item->categoria->nome : '--' }}</td>
                                    <td>
                                        @if($item->gerenciar_estoque)
                                        <span class="status-badge active"><i class="ri-check-line"></i> Sim</span>
                                        @else
                                        <span class="status-badge inactive"><i class="ri-close-line"></i> Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="status-badge active"><i class="ri-eye-line"></i> Ativo</span>
                                        @else
                                        <span class="status-badge inactive"><i class="ri-eye-off-line"></i> Oculto</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        R$ {{ __moeda($item->valor_ecommerce) }}
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline-flex gap-1 m-0">
                                            @method('delete')
                                            @csrf
                                            
                                            <a class="btn btn-warning btn-sm text-white px-2 rounded-2" href="{{ route('produtos.edit', [$item->id, 'ecommerce=1']) }}" title="Editar Produto">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a class="btn btn-dark btn-sm px-2 rounded-2" href="{{ route('produtos.galeria', [$item->id, 'ecommerce=1']) }}" title="Galeria de Imagens">
                                                <i class="ri-image-2-fill"></i>
                                            </a>
                                            <a class="btn btn-info btn-sm text-white px-2 rounded-2" href="{{ route('produtos.duplicar', [$item->id, 'ecommerce=1']) }}" title="Duplicar Produto">
                                                <i class="ri-file-copy-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-delete btn-sm btn-danger px-2 rounded-2" title="Excluir Produto">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="modulo-empty">
                                            <i class="ri-shopping-cart-2-line"></i>
                                            <p>Nenhum produto encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($data->hasPages())
                <div class="px-4 py-3 border-top bg-white">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
