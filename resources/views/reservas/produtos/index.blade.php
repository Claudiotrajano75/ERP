@extends('layouts.app', ['title' => 'Produtos para Reserva'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    .table-custom { border-collapse: separate; border-spacing: 0; }
    .table-custom thead th { background: #f8f9fa; color: #495057; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; border-bottom: 2px solid #eef0f5; padding: 12px 15px; }
    .table-custom tbody tr { transition: all 0.2s; }
    .table-custom tbody tr:hover { background-color: #f8f9fa; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .table-custom tbody td { padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #eef0f5; color: #555; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-2-line"></i>
                                Produtos para Reserva
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie o catálogo de produtos disponíveis para reservas.
                            </p>
                        </div>
                        <a href="{{ route('produtos.create', ['reserva=1']) }}" class="btn btn-success fw-semibold px-4 py-2">
                            <i class="ri-add-circle-fill me-1"></i> Novo Produto
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            {!!Form::open()->fill(request()->all())->get()!!}
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    {!!Form::text('nome', 'Pesquisar por nome')
                                    ->attrs(['class' => 'form-control', 'placeholder' => 'Nome do produto...'])
                                    !!}
                                </div>
                                <div class="col-md-3">
                                    {!!Form::select('status', 'Status', ['' => 'Todos', '1' => 'Ativos', '0' => 'Desativados'])
                                    ->attrs(['class' => 'form-select'])
                                    !!}
                                </div>
                                <div class="col-md-auto">
                                    <button class="btn btn-primary shadow-sm" type="submit"> 
                                        <i class="ri-search-line me-1"></i>Pesquisar
                                    </button>
                                    <a id="clear-filter" class="btn btn-danger shadow-sm" href="{{ route('produtos-reserva.index') }}">
                                        <i class="ri-eraser-fill me-1"></i>Limpar
                                    </a>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>
                    </div>

                    <div class="table-responsive border rounded-3 mb-3">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th width="80" class="text-center">Imagem</th>
                                    <th>Nome</th>
                                    <th>Unid.</th>
                                    <th>Categoria</th>
                                    <th class="text-center">Estoque</th>
                                    <th class="text-center">Status</th>
                                    <th>Valor</th>
                                    <th class="text-center">Preparo</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="text-center">
                                        <img class="img-thumbnail" src="{{ $item->img }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td class="fw-medium text-truncate" style="max-width: 250px;" title="{{ $item->nome }}">
                                        {{ $item->nome }}
                                    </td>
                                    <td>{{ $item->unidade }}</td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ $item->categoria ? $item->categoria->nome : '--' }}</span></td>
                                    <td class="text-center">
                                        @if($item->gerenciar_estoque)
                                        <i class="ri-checkbox-circle-fill text-success fs-5" data-bs-toggle="tooltip" title="Sim"></i>
                                        @else
                                        <i class="ri-close-circle-fill text-danger fs-5" data-bs-toggle="tooltip" title="Não"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->status)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Ativo</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-success">
                                        @if($item->categoria && $item->categoria->tipo_pizza)
                                        {!! $item->valoresPizza() !!}
                                        @else
                                        {{ __moeda($item->valor_delivery) }}
                                        @endif
                                    </td>
                                    <td class="text-center text-muted">
                                        <i class="ri-time-line align-middle me-1"></i>
                                        {{ $item->tempo_preparo ? $item->tempo_preparo : '--' }}
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <form action="{{ route('produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline">
                                            @method('delete')
                                            
                                            <a class="btn btn-warning btn-sm shadow-sm" href="{{ route('produtos.edit', [$item->id, 'reserva=1']) }}" data-bs-toggle="tooltip" title="Editar">
                                                <i class="ri-edit-line"></i>
                                            </a>

                                            <a class="btn btn-info btn-sm shadow-sm text-white" href="{{ route('produtos-cardapio.show', [$item->id]) }}" data-bs-toggle="tooltip" title="Gerenciar Adicionais">
                                                <i class="ri-play-list-add-line"></i>
                                            </a>
                                            
                                            <a class="btn btn-dark btn-sm shadow-sm" href="{{ route('produtos-cardapio.ingredientes', [$item->id]) }}" data-bs-toggle="tooltip" title="Ingredientes">
                                                <i class="ri-draft-line"></i>
                                            </a>

                                            @if($item->categoria && $item->categoria->tipo_pizza)
                                            <a class="btn btn-primary btn-sm shadow-sm" href="{{ route('produtos.tamanho-pizza', [$item->id]) }}" data-bs-toggle="tooltip" title="Tamanhos de Pizza">
                                                <i class="ri-restaurant-fill"></i>
                                            </a>
                                            @endif

                                            <a class="btn btn-secondary btn-sm shadow-sm" href="{{ route('produtos.duplicar', [$item->id]) }}" data-bs-toggle="tooltip" title="Duplicar Produto">
                                                <i class="ri-file-copy-line"></i>
                                            </a>

                                            @csrf
                                            <button type="button" class="btn btn-delete btn-sm btn-danger shadow-sm border-0" data-bs-toggle="tooltip" title="Remover Produto">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="ri-shopping-cart-2-line fs-2 mb-2 d-block opacity-50"></i>
                                        Nenhum produto de reserva cadastrado.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($data->hasPages())
                <div class="card-footer bg-light p-3">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
