@extends('layouts.app', ['title' => 'Produtos de Cardápio'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.img-60 { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #eef0f5; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Premium -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-restaurant-2-line"></i>
                            Produtos de Cardápio
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie os produtos exibidos no cardápio digital.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('produtos.create', ['cardapio=1']) }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Produto
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- KPI Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Produtos no cardápio</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-file-list-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Ativos</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['ativos'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Produtos disponíveis</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-danger mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Inativos</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['inativos'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Produtos desativados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-close-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros Glass -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5 col-12">
                            {!!Form::text('nome', 'Pesquisar por nome')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('status', 'Status', ['' => 'Todos', '1' => 'Ativos', '0' => 'Desativados'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('produtos-cardapio.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela Premium -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('produtos_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th style="width: 56px;"></th>
                                    <th>Nome</th>
                                    <th>Categoria</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Estoque</th>
                                    <th class="text-end">Valor</th>
                                    <th class="text-end" style="width: 180px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('produtos_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <img class="img-60" src="{{ $item->img }}" alt="{{ $item->nome }}">
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->nome }}</span>
                                        <span class="text-muted fs-11">{{ $item->unidade }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">
                                            {{ $item->categoria ? $item->categoria->nome : '--' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-line me-1"></i>Ativo
                                        </span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-close-line me-1"></i>Inativo
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->gerenciar_estoque)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        @if($item->categoria && $item->categoria->tipo_pizza)
                                        {!! $item->valoresPizza() !!}
                                        @else
                                        {{ __moeda($item->valor_cardapio) }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('produtos.edit', [$item->id, 'cardapio=1']) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <a class="btn btn-info btn-sm text-white" href="{{ route('produtos-cardapio.show', [$item->id]) }}" title="Adicionais">
                                                    <i class="ri-play-list-add-line"></i>
                                                </a>
                                                <a class="btn btn-dark btn-sm" href="{{ route('produtos-cardapio.ingredientes', [$item->id]) }}" title="Ingredientes">
                                                    <i class="ri-draft-line"></i>
                                                </a>
                                                @if($item->categoria && $item->categoria->tipo_pizza)
                                                <a class="btn btn-primary btn-sm text-white" href="{{ route('produtos.tamanho-pizza', [$item->id]) }}" title="Tamanhos de pizza">
                                                    <i class="ri-restaurant-fill"></i>
                                                </a>
                                                @endif
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum produto de cardápio encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modulo-footer">
                    <div>
                        @can('produtos_delete')
                        <form action="{{ route('produtos.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionados
                            </button>
                        </form>
                        @endcan
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
