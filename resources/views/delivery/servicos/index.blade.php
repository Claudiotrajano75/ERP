@extends('layouts.app', ['title' => 'Serviços do Marketplace'])

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
    
    /* Imagem do Serviço */
    .img-servico { width: 45px; height: 45px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }

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
                                <i class="ri-customer-service-2-fill"></i>
                                Serviços do Marketplace
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie os serviços oferecidos no marketplace.
                            </p>
                        </div>
                        @can('servico_create')
                        <a href="{{ route('servicos.create', ['marketplace=1']) }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-add-circle-fill me-1"></i> Novo Serviço
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    
                    <div class="filter-box">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row align-items-end g-3">
                            <div class="col-md-4">
                                {!!Form::text('nome', 'Pesquisar por nome')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do serviço'])!!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('status', 'Status', ['' => 'Todos', '1' => 'Ativos', '0' => 'Desativados'])->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-5">
                                <button class="btn btn-primary px-3" type="submit" style="background-color: #0d2b40; border-color: #0d2b40;">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a id="clear-filter" class="btn btn-light border px-3" href="{{ route('servicos-marketplace.index') }}">
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
                                    <th>Img</th>
                                    <th>Descrição</th>
                                    <th>Duração</th>
                                    <th>Status</th>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <th>MarketPlace</th>
                                    @endif
                                    <th>Valor</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td><img class="img-servico shadow-sm" src="{{ $item->img }}"></td>
                                    <td class="fw-medium">{{ $item->nome }}</td>
                                    <td><span class="text-muted">{{ $item->tempo_servico }} - {{ $item->unidade_cobranca }}</span></td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="ri-check-line"></i> Ativo</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="ri-close-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                                    <td>
                                        @if($item->marketplace)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="ri-check-line"></i> Sim</span>
                                        @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="ri-close-line"></i> Não</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td class="fw-bold text-success">
                                        R$ {{ __moeda($item->valor) }}
                                    </td>
                                    <td>
                                        <form action="{{ route('servicos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex gap-1 flex-wrap">
                                            @method('delete')
                                            
                                            @can('servico_edit')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('servicos.edit', [$item->id, 'marketplace=1']) }}" data-bs-toggle="tooltip" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            @endcan

                                            @csrf
                                            @can('servico_delete')
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="tooltip" title="Excluir">
                                                <i class="ri-delete-bin-fill"></i>
                                            </button>
                                            @endcan
                                        </form>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="ri-inbox-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                        <h5 class="text-muted">Nenhum serviço encontrado</h5>
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
@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
