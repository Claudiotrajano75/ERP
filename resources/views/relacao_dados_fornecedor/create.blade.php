@extends('layouts.app', ['title' => 'Nova Relação de Dados'])
@section('content')

<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark">
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-add-circle-line me-2 text-success fs-22"></i>
                                Nova Relação de Dados do Fornecedor
                            </h4>
                            <p class="text-muted mb-0 fs-13">Cadastre um novo mapeamento de CST/CSOSN e CFOP de entrada e saída.</p>
                        </div>
                        <div>
                            <a href="{{ route('relacao-dados-fornecedor.index') }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('relacao-dados-fornecedor.store')
                    !!}
                    
                    @include('relacao_dados_fornecedor._forms')

                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
