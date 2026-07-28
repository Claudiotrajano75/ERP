@extends('layouts.app', ['title' => 'Categorias de Produto'])

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
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- Cabeçalho Principal Premium -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-folder-open-line"></i>
                            Categorias de Produto
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Cadastre categorias e subcategorias para organizar seu catálogo e definir canais de exibição.</p>
                    </div>
                    <div>
                        @can('categoria_produtos_create')
                        <a href="{{ route('categoria-produtos.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Categoria
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- Filtros de Busca Glass -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6 col-12">
                            {!!Form::text('nome', 'Pesquisar por nome')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('categoria-produtos.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela de Categorias Premium -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('categoria_produtos_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Nome</th>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
                                    <th>Cardápio</th>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <th>Delivery</th>
                                    <th>Tipo Pizza</th>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
                                    <th>Ecommerce</th>
                                    @endif
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                                    <th>Reserva</th>
                                    @endif
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <!-- Categoria Pai -->
                                <tr class="table-light-subtle">
                                    @can('categoria_produtos_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox" name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td class="fw-bold text-dark">{{ $item->nome }}</td>
                                    
                                    @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
                                    <td>
                                        @if($item->cardapio)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <td>
                                        @if($item->delivery)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tipo_pizza)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    
                                    @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
                                    <td>
                                        @if($item->ecommerce)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    
                                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                                    <td>
                                        @if($item->reserva)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>
                                        @endif
                                    </td>
                                    @endif

                                    <td class="text-end">
                                        <form action="{{ route('categoria-produtos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('categoria_produtos_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('categoria-produtos.edit', [$item->id]) }}" title="Editar Categoria">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('categoria_produtos_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Categoria">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Subcategorias -->
                                @if(sizeof($item->subCategorias) > 0)
                                @foreach($item->subCategorias as $sub)
                                <tr>
                                    @can('categoria_produtos_delete')
                                    <td>
                                        <!-- Espaço em branco para alinhar com o checkbox principal -->
                                    </td>
                                    @endcan
                                    <td colspan="5">
                                        <span class="text-muted ms-3 fs-13">
                                            <i class="ri-corner-down-right-line me-1 align-middle text-primary"></i> 
                                            {{ $sub->nome }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('categoria-produtos.destroy', $sub->id) }}" method="post" id="form-{{$sub->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('categoria_produtos_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('categoria-produtos.edit', [$sub->id]) }}" title="Editar Subcategoria">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('categoria_produtos_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Subcategoria">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="modulo-empty">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Nenhuma categoria cadastrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer da Tabela -->
                <div class="modulo-footer">
                    <div>
                        @can('categoria_produtos_delete')
                        <form action="{{ route('categoria-produtos.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionadas
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