@extends('layouts.app', ['title' => 'Alterar Estado Fiscal — CTe OS'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-arrow-up-down-line"></i>
                                Alterar Estado Fiscal CTe OS: <strong class="text-primary ms-1">#{{ $item->numero ?: $item->id }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Ajuste manualmente o status de autorização na SEFAZ e anexe o XML correspondente caso necessário.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('cte-os.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Resumo dos Dados do CTe OS -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                        <div class="col-md-3 col-6">
                            <div class="text-muted fs-11 text-uppercase fw-bold">Código CTe OS</div>
                            <div class="fs-15 fw-bold text-dark">#{{ $item->id }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-muted fs-11 text-uppercase fw-bold">Natureza da Operação</div>
                            <div class="fs-14 fw-bold text-dark">{{ $item->natureza->descricao ?? '--' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-muted fs-11 text-uppercase fw-bold">Valor da Prestação</div>
                            <div class="fs-15 fw-bold text-success">R$ {{ __moeda($item->valor_transporte) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-muted fs-11 text-uppercase fw-bold">Data de Emissão</div>
                            <div class="fs-14 text-dark">{{ __data_pt($item->created_at, 1) }}</div>
                        </div>
                    </div>

                    {!!Form::open()->put()->route('cte-os.storeEstado', [$item->id])->multipart()!!}
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold fs-13"><i class="ri-toggle-line me-1"></i> Novo Estado Fiscal</label>
                            {!!Form::select('estado_emissao', '',
                            ['novo' => 'Novo / Pendente', 'rejeitado' => 'Rejeitado', 'cancelado' => 'Cancelado', 'aprovado' => 'Aprovado / Autorizado'])
                            ->attrs(['class' => 'form-select'])->value(isset($item) ? $item->estado_emissao : '')!!}
                        </div>
                        <div class="col-md-8 col-12">
                            <label class="form-label fw-bold fs-13"><i class="ri-file-code-line me-1"></i> Anexar Arquivo XML da SEFAZ (Opcional)</label>
                            {!! Form::file('file', '')->attrs(['class' => 'form-control', 'accept' => '.xml']) !!}
                        </div>
                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('cte-os.index') }}" class="dash-btn dash-btn-light px-4">
                                <i class="ri-close-line"></i> Cancelar
                            </a>
                            <button type="submit" class="dash-btn dash-btn-primary px-5">
                                <i class="ri-save-line"></i> Salvar Estado Fiscal
                            </button>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
