@extends('layouts.app', ['title' => 'Importação de Retorno'])
@section('content')

<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark">
                <!-- Cabeçalho -->
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-file-search-line me-2 text-info fs-22"></i>
                                Conciliação do Arquivo de Retorno
                            </h4>
                            <p class="text-muted mb-0 fs-13">Banco Processado: <strong class="text-primary">{{ $banco }}</strong>. Revise os lançamentos identificados e confirme a liquidação das contas.</p>
                        </div>
                    </div>
                </div>
                
                <form class="card-body p-4" method="post" action="{{ route('remessa-boleto.import-save') }}">
                    @csrf
                    
                    <div class="row g-4">
                        @foreach($data as $key => $item)
                        <div class="col-12">
                            <div class="card shadow-none border mb-0 overflow-hidden">
                                <!-- Título do Bloco de Lançamento -->
                                <div class="card-header bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2 py-2.5">
                                    <h5 class="card-title mb-0 fs-14 text-dark">
                                        <i class="ri-user-line me-1 align-middle fs-16 text-primary"></i> Pagador: <strong>{{ $item->pagador }}</strong>
                                    </h5>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->conta_id)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-link-m align-middle me-1"></i> Conta Vinculada
                                        </span>
                                        @endif
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                            Tarifa: R$ {{ __moeda($item->valor_tarifa) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Metadados Rápidos do Boleto -->
                                    <div class="row g-2 mb-3 bg-light-subtle border rounded p-2.5 fs-13 text-dark">
                                        <div class="col-sm-3 col-6">
                                            <span class="text-muted">Documento:</span> <strong class="text-dark">{{ $item->documento }}</strong>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <span class="text-muted">Carteira:</span> <strong class="text-dark">{{ $item->carteira }}</strong>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <span class="text-muted">Vencimento:</span> <strong class="text-dark">{{ $item->vencimento }}</strong>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <span class="text-muted">Ocorrência:</span> <strong class="text-dark">{{ $item->ocorrencia }}</strong>
                                        </div>
                                        <div class="col-sm-3 col-6 mt-1">
                                            <span class="text-muted">Valor Integral:</span> <strong class="text-dark">R$ {{ __moeda($item->valor_integral) }}</strong>
                                        </div>
                                        <div class="col-sm-3 col-6 mt-1">
                                            <span class="text-muted">Valor Recebido:</span> <strong class="text-success">R$ {{ __moeda($item->valor_recebido) }}</strong>
                                        </div>
                                    </div>

                                    <!-- Inputs de Confirmação/Conciliação -->
                                    <div class="row g-3">
                                        <div class="col-md-7 col-12">
                                            {!! Form::select('conta_id[]', 'Associar à Conta a Receber Padrão', ['' => 'Selecione'] + $contasPendentes->pluck('info', 'id')->all())
                                            ->required()
                                            ->id('conta_'.$key)
                                            ->value($item->conta_id)
                                            ->attrs(['class' => 'select2 form-select']) !!}
                                        </div>

                                        <div class="col-md-2 col-6">
                                            {!! Form::tel('valor_recebido[]', 'Valor Recebido')
                                            ->required()
                                            ->value(__moeda($item->valor_recebido))
                                            ->attrs(['class' => 'moeda form-control']) !!}
                                        </div>
                                        
                                        <div class="col-md-3 col-6">
                                            {!! Form::date('data_recebimento[]', 'Data do Recebimento')
                                            ->required()
                                            ->value(date('Y-m-d')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Rodapé de Submissão -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('remessa-boleto.index') }}" class="btn btn-light px-4">Voltar</a>
                                <button type="submit" class="btn btn-success px-4" id="btn-store">
                                    <i class="ri-save-line align-middle me-1"></i> Salvar Conciliações
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
