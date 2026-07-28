@extends('layouts.app', ['title' => 'Relação de Dados do Fornecedor'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-links-line me-2 text-primary fs-22"></i>
                            Relação de Dados do Fornecedor (CST/CFOP)
                        </h4>
                        <p class="text-muted mb-0 fs-13">Mapeie de/para CST, CSOSN e CFOP de entrada e saída para automatizar a classificação fiscal nas importações de XML.</p>
                    </div>
                    <div>
                        @can('relacao_dados_fornecedor_create')
                        <a href="{{ route('relacao-dados-fornecedor.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Relação
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- Filtros de Busca -->
                <div class="bg-light-subtle border rounded p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-6">
                            {!!Form::text('cst_csosn_entrada', 'CST/CSOSN Entrada')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::text('cfop_entrada', 'CFOP Entrada')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            {!!Form::text('cst_csosn_saida', 'CST/CSOSN Saída')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::text('cfop_saida', 'CFOP Saída')!!}
                        </div>
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('relacao-dados-fornecedor.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela de Relações -->
                <div class="table-responsive">
                    <table class="table table-centered table-hover align-middle mb-0 text-dark">
                        <thead class="table-light">
                            <tr>
                                <th>CST / CSOSN Entrada</th>
                                <th>CFOP Entrada</th>
                                <th>CST / CSOSN Saída</th>
                                <th>CFOP Saída</th>
                                <th class="text-end" style="width: 100px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">{{ $item->cst_csosn_entrada }}</span></td>
                                <td class="fw-bold">{{ $item->cfop_entrada }}</td>
                                <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">{{ $item->cst_csosn_saida }}</span></td>
                                <td class="fw-bold">{{ $item->cfop_saida }}</td>
                                <td class="text-end">
                                    <form action="{{ route('relacao-dados-fornecedor.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <div class="d-inline-flex gap-1">
                                            @can('relacao_dados_fornecedor_edit')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('relacao-dados-fornecedor.edit', [$item->id]) }}" title="Editar">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @endcan
                                            @can('relacao_dados_fornecedor_delete')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhuma relação de dados cadastrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-end mt-3">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
