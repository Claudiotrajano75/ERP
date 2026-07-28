@extends('layouts.app', ['title' => 'Visualizando arquivos'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-file-search-line me-2 text-primary fs-22"></i>
                            Visualizar Importação NFCe
                        </h4>
                        <p class="text-muted mb-0 fs-13">Revise os dados extraídos do arquivo antes de salvar.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        <a href="{{ route('nfce.import-zip') }}" class="btn btn-danger btn-sm px-3">
                            <i class="ri-arrow-left-double-fill align-middle me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="form-import" method="post" action="{{ route('nfce.import-zip-store-files') }}" enctype="multipart/form-data">
                    @csrf

                    @if(__countLocalAtivo() > 1)
                    <div class="row mb-4">
                        <div class="col-md-3 col-12">
                            <label class="form-label fw-semibold">Local</label>
                            <select id="inp-local_id" required class="select2 form-select" name="local_id">
                                <option value="">Selecione</option>
                                @foreach(__getLocaisAtivoUsuario() as $local)
                                <option @isset($item) @if($item->local_id == $local->id) selected @endif @endisset value="{{ $local->id }}">{{ $local->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
                    @endif

                    @foreach($data as $key => $d)
                    <div class="card border mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <input value="{{ $d['chave'] }}" checked class="form-check-input me-2" type="checkbox" name="file_id[]" id="file_{{ $key }}">
                                    <label class="fw-bold text-danger" for="file_{{ $key }}">{{ $d['chave'] }}</label>
                                </div>
                                <span class="text-muted fs-13">{{ __data_pt($d['data']) }}</span>
                            </div>

                            <input type="hidden" value="{{ json_encode($d) }}" name="data[]">

                            <div class="row g-2 mb-3">
                                <div class="col-md-4 col-12">
                                    <span class="text-muted fs-12">Valor Total:</span>
                                    <strong class="text-primary d-block">R$ {{ __moeda($d['valor_total']) }}</strong>
                                </div>
                                <div class="col-md-4 col-12">
                                    <span class="text-muted fs-12">Desconto:</span>
                                    <strong class="text-primary d-block">R$ {{ __moeda($d['desconto']) }}</strong>
                                </div>
                                <div class="col-md-4 col-12">
                                    <span class="text-muted fs-12">Número NFCe:</span>
                                    <strong class="text-primary d-block">{{ $d['numero_nfe'] }}</strong>
                                </div>
                            </div>

                            <!-- Produtos -->
                            <h5 class="text-dark border-bottom pb-2 mb-3 fs-14">
                                <i class="ri-box-2-line me-2 text-primary fs-16"></i> Produtos
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-centered table-hover align-middle mb-0 text-dark">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>CFOP</th>
                                            <th>Un.</th>
                                            <th>Qtd</th>
                                            <th>Valor Unit.</th>
                                            <th>Subtotal</th>
                                            <th>NCM</th>
                                            <th>Cód. Barras</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($d['produtos'] as $p)
                                        <tr>
                                            <td class="text-muted">{{ $p['codigo'] }}</td>
                                            <td class="fw-semibold">{{ $p['nome'] }}</td>
                                            <td>{{ $p['cfop_estadual'] }}/{{ $p['cfop_outro_estado'] }}</td>
                                            <td>{{ $p['unidade'] }}</td>
                                            <td>{{ $p['quantidade'] }}</td>
                                            <td>R$ {{ __moeda((float)$p['valor_unitario']) }}</td>
                                            <td class="fw-bold text-success">R$ {{ __moeda((float)$p['sub_total']) }}</td>
                                            <td>{{ $p['ncm'] }}</td>
                                            <td>{{ $p['codigo_barras'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Fatura -->
                            @if(count($d['fatura']) > 0)
                            <h5 class="text-dark border-bottom pb-2 mb-3 mt-4 fs-14">
                                <i class="ri-coins-line me-2 text-primary fs-16"></i> Fatura
                            </h5>
                            <div class="row g-2">
                                @foreach($d['fatura'] as $f)
                                <div class="col-md-4 col-12">
                                    <div class="bg-light-subtle border rounded p-2">
                                        <span class="text-muted fs-11 d-block">Vencimento</span>
                                        <strong class="text-dark">{{ __data_pt($f['vencimento'], 0) }}</strong>
                                        <span class="ms-3 text-muted fs-11 d-block d-sm-inline">Valor</span>
                                        <strong class="text-success">R$ {{ __moeda((float)$f['valor_parcela']) }}</strong>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success px-5" id="btn-store">
                            <i class="ri-save-line me-1"></i> Salvar
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
