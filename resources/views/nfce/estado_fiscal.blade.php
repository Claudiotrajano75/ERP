@extends('layouts.app', ['title' => 'Alterar Estado Fiscal NFCe'])
@section('css')
<style type="text/css">
    input[type="file"] {
        display: none;
    }
    .file-certificado label {
        padding: 8px 8px;
        width: 100%;
        background-color: #8833FF;
        color: #FFF;
        text-transform: uppercase;
        text-align: center;
        display: block;
        margin-top: 20px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>
@endsection
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-arrow-up-down-line me-2 text-warning fs-22"></i>
                            Alterar Estado Fiscal NFCe
                        </h4>
                        <p class="text-muted mb-0 fs-13">Altere manualmente o estado fiscal da NFCe (aprovado, cancelado, rejeitado ou novo).</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        <a href="{{ route('nfce.index') }}" class="btn btn-danger btn-sm px-3">
                            <i class="ri-arrow-left-double-fill align-middle me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- Informações da NFCe -->
                <div class="bg-light-subtle border rounded p-3 mb-4">
                    <div class="row g-2">
                        <div class="col-md-3 col-6">
                            <span class="text-muted fs-12 fw-semibold d-block">Cliente</span>
                            <span class="fw-bold text-dark">{{ $item->cliente ? $item->cliente->razao_social : 'Consumidor Final' }}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="text-muted fs-12 fw-semibold d-block">CNPJ/CPF</span>
                            <span class="fw-bold text-success">{{ $item->cliente ? $item->cliente->cpf_cnpj : '--' }}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="text-muted fs-12 fw-semibold d-block">Data</span>
                            <span class="fw-bold text-success">{{ __data_pt($item->data_registro, 0) }}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="text-muted fs-12 fw-semibold d-block">Valor Total</span>
                            <span class="fw-bold text-success">R$ {{ __moeda($item->total) }}</span>
                        </div>
                        @if($item->cliente)
                        <div class="col-md-3 col-6">
                            <span class="text-muted fs-12 fw-semibold d-block">Cidade</span>
                            <span class="fw-bold text-success">{{ $item->cliente->cidade->nome }} ({{ $item->cliente->cidade->uf }})</span>
                        </div>
                        @endif
                        <div class="col-md-4 col-12">
                            <span class="text-muted fs-12 fw-semibold d-block">Chave NFCe</span>
                            <span class="fw-bold text-success">{{ $item->chave != "" ? $item->chave : '--' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Formulário -->
                {!!Form::open()
                ->put()
                ->route('nfce.storeEstado', [$item->id])
                ->multipart()
                !!}
                <div class="row g-3">
                    <div class="col-md-3 col-12">
                        {!!Form::select('estado_emissao', 'Novo Estado', [
                            'novo' => 'Novo',
                            'rejeitado' => 'Rejeitado',
                            'cancelado' => 'Cancelado',
                            'aprovado' => 'Aprovado'
                        ])->attrs(['class' => 'form-select'])->value(isset($item) ? $item->estado : '')!!}
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="file-certificado">
                            {!! Form::file('file', 'Arquivo XML')->attrs(['accept' => '.xml']) !!}
                            <span class="text-danger mt-1 d-block" id="filename"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="ri-save-line me-1"></i> Salvar
                    </button>
                </div>
                {!!Form::close()!!}

            </div>
        </div>
    </div>
</div>
@endsection
