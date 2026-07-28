@extends('layouts.app', ['title' => 'Movimentações'])
@section('css')
<style type="text/css">
    @page { size: auto; margin: 0mm; }
    @media print {
        .print {
            margin: 10px;
        }
    }
</style>
@endsection
@section('content')

<div class="mt-3 print text-dark">
    <div class="row">
        <div class="col-lg-12">
            
            <!-- Detalhes do Produto (Card Superior) -->
            <div class="card border-0 shadow-sm text-dark mb-4">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <img class="img-60 rounded border me-3" src="{{ $item->img }}" style="width: 55px; height: 55px; object-fit: cover;">
                        <div>
                            <h4 class="mb-1 text-dark">{{ $item->nome }}</h4>
                            <p class="text-muted mb-0 fs-13">
                                Categoria: <strong class="text-primary">{{ $item->categoria ? $item->categoria->nome : '--' }}</strong> | 
                                Marca: <strong class="text-primary">{{ $item->marca ? $item->marca->nome : '--' }}</strong> | 
                                Cód: <strong class="text-dark">{{ $item->codigo_barras ?? '--' }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('produtos.download-zip', [$item->id]) }}" class="btn btn-success btn-sm px-3 d-print-none" title="Baixar todas as imagens">
                            <i class="ri-download-2-line align-middle me-1"></i> Imagens (ZIP)
                        </a>
                        <a href="{{ route('produtos.index') }}" class="btn btn-danger btn-sm px-3 d-print-none">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-2.5 bg-white shadow-sm text-center">
                                <span class="fs-11 text-muted text-uppercase fw-semibold d-block mb-1">Preço de Venda</span>
                                <span class="fs-18 fw-bold text-success">R$ {{ __moeda($item->valor_unitario) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-2.5 bg-white shadow-sm text-center">
                                <span class="fs-11 text-muted text-uppercase fw-semibold d-block mb-1">Preço de Compra</span>
                                <span class="fs-18 fw-bold text-dark">R$ {{ __moeda($item->valor_compra) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-2.5 bg-white shadow-sm text-center">
                                <span class="fs-11 text-muted text-uppercase fw-semibold d-block mb-1">Movimentações</span>
                                <span class="fs-18 fw-bold text-primary">{{ sizeof($data) }} registros</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-2.5 bg-white shadow-sm text-center">
                                <span class="fs-11 text-muted text-uppercase fw-semibold d-block mb-1">Data de Cadastro</span>
                                <span class="fs-16 fw-semibold text-dark">{{ __data_pt($item->created_at, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abas com Movimentações e Fornecedores -->
            <div class="card border-0 shadow-sm text-dark">
                <div class="card-header bg-transparent border-bottom pt-3 pb-0">
                    <ul class="nav nav-tabs nav-bordered border-0 mb-0" id="productShowTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold fs-14" id="movimentacao-tab" data-bs-toggle="tab" data-bs-target="#movimentacao-pane" type="button" role="tab" aria-controls="movimentacao-pane" aria-selected="true">
                                <i class="ri-history-line me-1 align-middle fs-16"></i> Histórico de Movimentações
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold fs-14" id="fornecedores-tab" data-bs-toggle="tab" data-bs-target="#fornecedores-pane" type="button" role="tab" aria-controls="fornecedores-pane" aria-selected="false">
                                <i class="ri-user-shared-line me-1 align-middle fs-16"></i> Fornecedores Vinculados
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="tab-content p-4 text-dark" id="productShowTabContent">
                    
                    <!-- Aba 1: Movimentações -->
                    <div class="tab-pane fade show active" id="movimentacao-pane" role="tabpanel" aria-labelledby="movimentacao-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead class="table-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Quantidade</th>
                                        <th>Estoque Atual</th>
                                        <th>Transação</th>
                                        <th>Operador</th>
                                        <th>Data/Hora</th>
                                        <th>Tipo</th>
                                        <th>Variação</th>
                                        <th class="text-end d-print-none" style="width: 100px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $i)
                                    <tr>
                                        <td><span class="text-muted">#{{ $i->id }}</span></td>
                                        <td class="fw-bold">{{ number_format($i->quantidade, 2) }}</td>
                                        <td>{{ $i->estoque_atual ? number_format($i->estoque_atual, 2) : '--' }}</td>
                                        <td>{{ $i->tipoTransacao() }}</td>
                                        <td class="text-muted">{{ $i->user ? $i->user->name : '' }}</td>
                                        <td>{{ __data_pt($i->created_at) }}</td>
                                        <td>
                                            @if($i->tipo == 'incremento')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Incremento</span>
                                            @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Redução</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $i->produtoVariacao ? $i->produtoVariacao->descricao : '--' }}</span></td>
                                        <td class="text-end d-print-none">
                                            <a class="btn btn-dark btn-sm" href="{{ route('produtos.movimentacao', [$i->id]) }}">
                                                Visualizar
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Nenhuma movimentação registrada para este produto.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($data->count() > 0)
                        <div class="d-flex align-items-center justify-content-end mt-3 bg-light p-2.5 rounded">
                            <span class="fw-semibold">Soma de Quantidades Movimentadas: <strong class="text-primary ms-1 fs-15">{{ number_format($data->sum('quantidade'), 2) }}</strong></span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Aba 2: Fornecedores -->
                    <div class="tab-pane fade" id="fornecedores-pane" role="tabpanel" aria-labelledby="fornecedores-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead class="table-light">
                                    <tr>
                                        <th>Razão Social</th>
                                        <th>CNPJ / CPF</th>
                                        <th>Logradouro</th>
                                        <th>Número</th>
                                        <th>Bairro</th>
                                        <th>Cidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->fornecedores as $i)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $i->fornecedor->razao_social }}</td>
                                        <td class="text-muted">{{ $i->fornecedor->cpf_cnpj }}</td>
                                        <td>{{ $i->fornecedor->rua }}</td>
                                        <td>{{ $i->fornecedor->numero }}</td>
                                        <td>{{ $i->fornecedor->bairro }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $i->fornecedor->cidade->info }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Nenhum fornecedor vinculado a este produto.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Rodapé de Ações do Card -->
                <div class="card-footer bg-transparent border-top p-3 d-print-none">
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="javascript:window.print()" class="btn btn-primary px-4">
                            <i class="ri-printer-line me-1 align-middle"></i> Imprimir Extrato
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection