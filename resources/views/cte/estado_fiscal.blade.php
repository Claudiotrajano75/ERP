@extends('layouts.app', ['title' => 'Alterar Estado Fiscal CTe'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

.detail-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #8a8aaa; font-weight: 700; margin-bottom: 2px; }
.detail-value { font-size: 14px; color: #2d2d44; font-weight: 600; }

.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }

.file-certificado label { padding: 8px 12px; width: 100%; background: linear-gradient(135deg, #0f0c29, #302b63); color: #FFF; text-transform: uppercase; text-align: center; display: block; cursor: pointer; border-radius: 8px; font-size: 12px; font-weight: 600; transition: all 0.2s ease; }
.file-certificado label:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.2); }
input[type="file"] { display: none; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-arrow-up-down-line"></i>
                                Alterar Estado Fiscal CTe
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Altere o estado fiscal do Conhecimento de Transporte Eletrônico.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('cte.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
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
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
                        Dados do CTe
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Código</div>
                            <div class="detail-value text-info">#{{ $item->id }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Natureza de Operação</div>
                            <div class="detail-value text-info">{{ $item->natureza->natureza }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Data de Registro</div>
                            <div class="detail-value text-info">{{ __data_pt($item->created_at, 0) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Valor de Transporte</div>
                            <div class="detail-value text-info">R$ {{ __moeda($item->valor_transporte) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Valor a Receber</div>
                            <div class="detail-value text-info">R$ {{ __moeda($item->valor_receber) }}</div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-swap-line text-primary me-2 align-middle fs-18"></i>
                        Novo Estado
                    </h5>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            {!!Form::select('estado_emissao', 'Estado',
                            ['novo' => 'Novo', 'rejeitado' => 'Rejeitado', 'cancelado' => 'Cancelado', 'aprovado' => 'Aprovado'])
                            ->attrs(['class' => 'form-select'])->value(isset($item) ? $item->estado : '')!!}
                        </div>
                        <div class="col-md-5">
                            <div class="file-certificado">
                                {!! Form::file('file', 'Arquivo XML')->attrs(['accept' => '.xml']) !!}
                                <span class="text-danger" id="filename"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('cte.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line align-middle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-save-line align-middle me-1"></i> Salvar
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
