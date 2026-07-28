@extends('layouts.app', ['title' => 'Clientes de Delivery'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    /* Tabela */
    .table-custom thead th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; border-top: none; padding: 14px 16px; }
    .table-custom tbody tr { transition: all 0.2s; border-bottom: 1px solid #eef0f5; }
    .table-custom tbody tr:hover { background-color: #f8fafc; }
    .table-custom tbody td { padding: 14px 16px; vertical-align: middle; color: #1e293b; font-size: 14px; }
    
    /* Filtros */
    .filter-box { background-color: #f8fafc; border: 1px solid #eef0f5; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
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
                                <i class="ri-user-smile-fill"></i>
                                Clientes de Delivery
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Consulte a lista de clientes, pedidos e endereços do delivery.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    
                    <div class="filter-box">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row align-items-end g-3">
                            <div class="col-md-5">
                                {!!Form::text('razao_social', 'Pesquisar por nome')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('telefone', 'Pesquisar por Telefone')->type('tel')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary px-3" type="submit" style="background-color: #0d2b40; border-color: #0d2b40;">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a id="clear-filter" class="btn btn-light border px-3" href="{{ route('clientes-delivery.index') }}">
                                    <i class="ri-eraser-fill me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Endereço Principal</th>
                                    <th>Total de Pedidos</th>
                                    <th>Status</th>
                                    <th>UID</th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold text-primary">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            {{ $item->razao_social }}
                                        </div>
                                    </td>
                                    <td>{{ $item->telefone }}</td>
                                    <td><span class="text-muted text-truncate d-inline-block" style="max-width: 250px;" title="{{ $item->enderecoPrincipal ? $item->enderecoPrincipal->info : '' }}">{{ $item->enderecoPrincipal ? $item->enderecoPrincipal->info : '--' }}</span></td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-13"><i class="ri-shopping-bag-3-line me-1 text-muted"></i>{{ sizeof($item->pedidos) }}</span>
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="ri-check-line"></i> Ativo</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="ri-close-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ $item->uid }}</small></td>
                                    <td>
                                        <form action="{{ route('clientes-delivery.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex gap-1 flex-wrap">
                                            @method('delete')
                                            @csrf
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('clientes-delivery.edit', [$item->id]) }}" data-bs-toggle="tooltip" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="tooltip" title="Excluir">
                                                <i class="ri-delete-bin-fill"></i>
                                            </button>
                                            <a class="btn btn-dark btn-sm" href="{{ route('clientes-delivery.show', [$item->id]) }}" data-bs-toggle="tooltip" title="Pedidos">
                                                <i class="ri-send-plane-2-line"></i>
                                            </a>
                                            <a class="btn btn-info btn-sm text-white" href="{{ route('clientes-delivery.enderecos', [$item->id]) }}" data-bs-toggle="tooltip" title="Endereços">
                                                <i class="ri-road-map-line"></i>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="ri-user-smile-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                        <h5 class="text-muted">Nenhum cliente encontrado</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
