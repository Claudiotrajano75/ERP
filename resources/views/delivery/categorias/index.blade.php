@extends('layouts.app', ['title' => 'Categorias de Delivery'])

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
                                <i class="ri-list-check"></i>
                                Categorias de Delivery
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie as categorias dos seus produtos no delivery.
                            </p>
                        </div>
                        @can('categoria_produtos_create')
                        <a href="{{ route('categoria-produtos.create', ['delivery=1']) }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-add-circle-fill me-1"></i> Nova Categoria
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    
                    <div class="filter-box">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row align-items-end g-3">
                            <div class="col-md-6">
                                {!!Form::text('nome', 'Pesquisar por nome')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da categoria'])!!}
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary px-3" type="submit" style="background-color: #0d2b40; border-color: #0d2b40;">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a id="clear-filter" class="btn btn-light border px-3" href="{{ route('produtos-delivery.categorias') }}">
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
                                    <th width="20%">Visível no Delivery</th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-medium">{{ $item->nome }}</td>
                                    
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input switch-check shadow-none" type="checkbox" role="switch" value="{{ $item->id }}" style="cursor: pointer;" @if($item->delivery) checked @endif>
                                            <label class="form-check-label ms-2 text-muted" style="font-size: 13px;">@if($item->delivery) Ativado @else Desativado @endif</label>
                                        </div>
                                    </td>

                                    <td>
                                        <a class="btn btn-warning btn-sm text-white" href="{{ route('categoria-produtos.edit', [$item->id]) }}" data-bs-toggle="tooltip" title="Editar">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <i class="ri-inbox-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                        <h5 class="text-muted">Nenhuma categoria encontrada</h5>
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
<script type="text/javascript">
    $('.switch-check').on("change", function () {
        let id = $(this).val();
        let label = $(this).next('label');
        let isChecked = $(this).is(':checked');
        
        if (isChecked) {
            label.text('Ativado');
        } else {
            label.text('Desativado');
        }

        $.get(path_url + "api/produtos-delivery/switch-categoria", {id: id})
        .done((success) => {
            // Sucesso silently
        })
        .fail((err) => {
            console.log(err)
        })
    })
</script>
@endsection