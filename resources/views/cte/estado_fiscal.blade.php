@extends('layouts.app', ['title' => 'Alterar Estado Fiscal CTe'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-uppercase fw-bold text-primary d-block mb-1"
                                  style="letter-spacing: 0.5px;">Fiscal & Documentos Eletrônicos</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-arrow-up-down-line"></i>
                                Alterar Estado Fiscal: <strong class="text-primary ms-1">CTe #{{ $item->numero ?: $item->id }}</strong>
                            </h4>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('cte.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()
                    ->put()
                    ->route('cte.storeEstado', [$item->id])
                    ->multipart()
                    !!}

                    <!-- Info do CTe -->
                    <h5 class="fs-14 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="ri-information-line text-primary"></i>
                        Dados Atuais do Conhecimento de Transporte
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <span class="fs-11 text-uppercase fw-bold text-muted d-block mb-1">Identificador</span>
                            <strong class="fs-14 text-dark">#{{ $item->id }}</strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="fs-11 text-uppercase fw-bold text-muted d-block mb-1">Natureza de Operação</span>
                            <strong class="fs-14 text-dark">{{ $item->natureza ? $item->natureza->descricao : '--' }}</strong>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="fs-11 text-uppercase fw-bold text-muted d-block mb-1">Data de Emissão</span>
                            <span class="fs-14 text-dark">{{ __data_pt($item->created_at, 0) }}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="fs-11 text-uppercase fw-bold text-muted d-block mb-1">Valor Transporte</span>
                            <strong class="fs-14 text-primary">R$ {{ __moeda($item->valor_transporte) }}</strong>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="fs-11 text-uppercase fw-bold text-muted d-block mb-1">Valor a Receber</span>
                            <strong class="fs-14 text-success">R$ {{ __moeda($item->valor_receber) }}</strong>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fs-14 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="ri-swap-line text-primary"></i>
                        Atualização do Estado Fiscal
                    </h5>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            {!!Form::select('estado_emissao', 'Novo Estado Fiscal',
                            ['novo' => 'Novo / Rascunho', 'rejeitado' => 'Rejeitado', 'cancelado' => 'Cancelado', 'aprovado' => 'Aprovado / Autorizado'])
                            ->attrs(['class' => 'form-select'])->value(isset($item) ? $item->estado : '')!!}
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-12 fw-bold text-muted mb-1">Upload de Arquivo XML (Opcional)</label>
                            <input type="file" name="file" class="form-control" accept=".xml">
                            <span class="text-danger fs-12 mt-1" id="filename"></span>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('cte.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-close-line"></i> Cancelar
                        </a>
                        <button type="submit" class="dash-btn dash-btn-primary px-4">
                            <i class="ri-save-line"></i> Salvar Novo Estado
                        </button>
                    </div>

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

