@extends('layouts.app', ['title' => 'Controle de Estoque'])

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

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-store-3-line"></i>
                            Controle de Estoque
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie e fiscalize a quantidade física de produtos nos estoques de suas localizações.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('estoque_create')
                        <a href="{{ route('estoque.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Adicionar Estoque
                        </a>
                        <a href="{{ route('apontamento.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-settings-3-line align-middle me-1"></i> Apontamento
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Produtos</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total_produtos'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Produtos em estoque</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-box-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Itens</h4>
                                        <h3 class="my-2 text-white fs-18">{{ number_format($stats['total_itens'], 0) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Quantidade total</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-stack-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-warning mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Valor Estimado</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($stats['valor_estoque']) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Valor de venda em estoque</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-danger mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Estoque Baixo</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['estoque_baixo'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Abaixo do mínimo</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-alert-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6 col-12">
                            {!!Form::text('produto', 'Pesquisar por produto')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('estoque.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Imagem</th>
                                    <th>Produto</th>
                                    <th>Quantidade Atual</th>
                                    <th>Valor de Venda</th>
                                    <th class="text-end" style="width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <img class="rounded border" src="{{ $item->produto->img }}"
                                             style="width: 45px; height: 45px; object-fit: cover;">
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->descricao() }}</div>
                                        <span class="text-muted fs-11">Unidade: {{ $item->produto->unidade }}</span>
                                    </td>

                                    <td>
                                        @if(__countLocalAtivo() == 1)
                                            <strong class="text-primary fs-14">
                                                @if($item->produto->unidade == 'UN' || $item->produto->unidade == 'UNID')
                                                {{ number_format($item->quantidade, 0) }}
                                                @else
                                                {{ number_format($item->quantidade, 3, '.', '') }}
                                                @endif
                                            </strong>
                                        @else
                                            <div class="fs-12 text-muted">
                                                @foreach($item->produto->estoqueLocais as $e)
                                                @if($e->local)
                                                {{ $e->local->descricao }}:
                                                <strong class="text-success">
                                                    @if($item->produto->unidade == 'UN' || $item->produto->unidade == 'UNID')
                                                    {{ number_format($e->quantidade, 0) }}
                                                    @else
                                                    {{ number_format($e->quantidade, 3) }}
                                                    @endif
                                                </strong>
                                                @endif
                                                @if(!$loop->last) | @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="fw-bold text-success">R$ {{ __moeda($item->produto->valor_unitario) }}</span>
                                    </td>

                                    <td class="text-end">
                                        <form action="{{ route('estoque.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('estoque_edit')
                                                <a class="btn btn-dark btn-sm text-white"
                                                   href="{{ route('estoque.edit', [$item->id]) }}"
                                                   title="Editar quantidade de estoque">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('produtos_edit')
                                                <a class="btn btn-warning btn-sm text-white"
                                                   href="{{ route('produtos.edit', [$item->produto_id]) }}"
                                                   title="Editar dados cadastrais do produto">
                                                    <i class="ri-box-3-line"></i>
                                                </a>
                                                @endcan
                                                @can('estoque_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                        title="Remover registro de estoque">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-store-3-line"></i>
                                            <p>Nenhum registro de estoque encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Footer / Paginação ═══ -->
                <div class="modulo-footer">
                    <div></div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
