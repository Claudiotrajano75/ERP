@extends('layouts.app', ['title' => 'Detalhes do Boleto'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-eye-line text-warning"></i>
                                Detalhes do Boleto #{{ $item->id }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Visualização completa dos dados do boleto emitido.</p>
                        </div>
                        <div>
                            <a href="{{ route('boleto.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="card bg-light-subtle shadow-none border mb-4">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Cliente / Pagador</h6>
                                    <p class="fw-semibold text-dark mb-0 fs-13">{{ $item->contaReceber && $item->contaReceber->cliente ? $item->contaReceber->cliente->info : '--' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Valor Nominal</h6>
                                    <p class="fw-bold text-success mb-0 fs-14">R$ {{ __moeda($item->valor) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Vencimento</h6>
                                    <p class="fw-semibold text-dark mb-0 fs-13">{{ __data_pt($item->vencimento, 0) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Data de Emissão / Registro</h6>
                                    <p class="fw-semibold text-dark mb-0 fs-13">{{ __data_pt($item->created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <a target="_blank" class="dash-btn dash-btn-primary" href="{{ route('boleto.print', [$item->id]) }}">
                            <i class="ri-printer-line"></i> Imprimir Boleto PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
