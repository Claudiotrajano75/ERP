@extends('layouts.app', ['title' => 'Alterar Estado Fiscal'])
@section('content')

<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm text-dark">
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-arrow-up-down-line me-2 text-warning fs-22"></i>
                                @if($tipo == 'devolucao')
                                Alterar Estado Fiscal — Devolução
                                @else
                                Alterar Estado Fiscal — NFe
                                @endif
                            </h4>
                            <p class="text-muted mb-0 fs-13">Corrija manualmente o estado da nota fiscal no sistema. Use com cautela.</p>
                        </div>
                        <div>
                            <a href="{{ route('nfe.index') }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">

                    {!!Form::open()
                    ->put()
                    ->route('nfe.storeEstado', [$item->id])
                    ->multipart()
                    !!}
                    <input type="hidden" name="tipo" value="{{ $tipo }}">

                    <!-- Resumo da Nota -->
                    <div class="bg-light-subtle border rounded p-3 mb-4">
                        <h5 class="text-dark fw-bold fs-13 mb-3">
                            <i class="ri-information-line me-1 text-primary align-middle"></i>
                            Dados da Nota Fiscal
                        </h5>
                        @if($item->cliente)
                        <ul class="list-unstyled mb-0 fs-13" style="line-height: 2.0;">
                            <li>Cliente: <strong class="text-primary">{{ $item->cliente->razao_social }}</strong></li>
                            <li>CNPJ/CPF: <strong>{{ $item->cliente->cpf_cnpj }}</strong></li>
                            <li>Cidade: <strong>{{ $item->cliente->cidade->nome }} ({{ $item->cliente->cidade->uf }})</strong></li>
                            <li>Data: <strong>{{ __data_pt($item->data_registro, 0) }}</strong></li>
                            <li>Valor Total: <strong class="text-success">R$ {{ __moeda($item->total) }}</strong></li>
                            <li>Chave NFe: <strong class="font-monospace fs-11">{{ $item->chave ?: '--' }}</strong></li>
                        </ul>
                        @else
                        <ul class="list-unstyled mb-0 fs-13" style="line-height: 2.0;">
                            <li>Fornecedor: <strong class="text-primary">{{ $item->fornecedor->razao_social }}</strong></li>
                            <li>CNPJ/CPF: <strong>{{ $item->fornecedor->cpf_cnpj }}</strong></li>
                            <li>Cidade: <strong>{{ $item->fornecedor->cidade->info }}</strong></li>
                            <li>Data: <strong>{{ __data_pt($item->data_registro, 0) }}</strong></li>
                            <li>Valor Total: <strong class="text-success">R$ {{ __moeda($item->total) }}</strong></li>
                            <li>Chave NFe: <strong class="font-monospace fs-11">{{ $item->chave ?: '--' }}</strong></li>
                        </ul>
                        @endif
                    </div>

                    <!-- Formulário de Alteração -->
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            {!!Form::select('estado_emissao', 'Novo Estado da Nota', [
                                'novo' => 'Novo',
                                'rejeitado' => 'Rejeitado',
                                'cancelado' => 'Cancelado',
                                'aprovado' => 'Aprovado'
                            ])->attrs(['class' => 'form-select'])->value(isset($item) ? $item->estado : '')!!}
                        </div>
                        <div class="col-md-5 col-12">
                            <label class="form-label fw-semibold text-dark fs-13">Arquivo XML (opcional)</label>
                            <input type="file" name="file" id="inp-file-estado" accept=".xml" class="form-control">
                            <span class="text-danger fs-12 mt-1 d-block" id="filename"></span>
                        </div>
                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('nfe.index') }}" class="btn btn-light px-4">Cancelar</a>
                                <button type="submit" class="btn btn-warning px-4 fw-bold">
                                    <i class="ri-save-line me-1 align-middle"></i> Salvar Alteração
                                </button>
                            </div>
                        </div>
                    </div>

                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
