@extends('layouts.app', ['title' => 'Naturezas de Operação'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card modulo-form-card shadow-sm">
                <!-- Cabeçalho Gradient Premium -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-settings-4-line"></i>
                                Naturezas de Operação
                            </h4>
                            <p class="text-white-50 mb-0 modulo-subtitle fs-13">
                                Gerencie as naturezas de operação para emissão de notas.
                            </p>
                        </div>
                        <div>
                            @can('natureza_operacao_create')
                            <a href="{{ route('natureza-operacao.create') }}" class="btn btn-success btn-sm px-3 shadow-sm">
                                <i class="ri-add-circle-fill align-middle me-1"></i> Nova Natureza
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->fill(request()->all())
                    ->get()
                    !!}
                    <div class="row g-2 mb-4">
                        <div class="col-md-4">
                            {!!Form::text('descricao', 'Pesquisar por nome')->attrs(['class' => 'form-control', 'placeholder' => 'Buscar por nome...'])!!}
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary me-2" type="submit"> <i class="ri-search-line me-1"></i>Pesquisar</button>
                            <a id="clear-filter" class="btn btn-danger" href="{{ route('natureza-operacao.index') }}"><i class="ri-eraser-fill me-1"></i>Limpar</a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                    
                    <div class="table-responsive-sm">
                        <table class="table table-centered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Padrão</th>
                                    <th>Sobrescrerver CFOP</th>
                                    <th width="20%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td>{{ $item->descricao }}</td>
                                    <td>
                                        @if($item->padrao)
                                        <i class="ri-checkbox-circle-fill text-success"></i>
                                        @else
                                        <i class="ri-close-circle-fill text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->sobrescrever_cfop)
                                        <i class="ri-checkbox-circle-fill text-success"></i>
                                        @else
                                        <i class="ri-close-circle-fill text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('natureza-operacao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}">
                                            @method('delete')

                                            @can('natureza_operacao_edit')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('natureza-operacao.edit', [$item->id]) }}">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            @endcan
                                            
                                            @csrf
                                            @can('natureza_operacao_delete')
                                            <button type="button" class="btn btn-delete btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {!! $data->appends(request()->all())->links() !!}

            </div>
        </div>
    </div>
</div>
@endsection