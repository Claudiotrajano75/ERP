@extends('layouts.app', ['title' => 'Detalhes do Boleto'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card (Create/Edit) ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-eye-line text-warning"></i>
                                Detalhes do Boleto
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Visualização completa dos dados do boleto emitido.</p>
                        </div>
                        <div>
                            <a href="{{ route('boleto.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="card bg-light-subtle shadow-none border mb-4">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Cliente</h6>
                                    <p class="fw-semibold text-dark mb-0 fs-13">{{ $item->contaReceber->cliente->info }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-muted fs-11 text-uppercase fw-bold">Valor do Boleto</h6>
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
                        <a target="_blank" class="btn btn-dark px-4" href="{{ route('boleto.print', [$item->id]) }}">
                            <i class="ri-printer-line align-middle me-1"></i> Imprimir Boleto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
