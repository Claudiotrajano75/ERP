@extends('layouts.app', ['title' => 'Nova NFCe'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-bill-line"></i>
                            Nova NFCe
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Preencha os dados para emitir uma nova nota fiscal de consumidor eletrônica.</p>
                    </div>
                    <div class="d-inline-flex align-items-center gap-2">
                        @if(__countLocalAtivo() > 1 && isset($caixa))
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-12 d-flex align-items-center gap-1">
                            <i class="ri-map-pin-line"></i>
                            {{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}
                        </span>
                        @endif
                        <a href="{{ route('nfce.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-double-fill align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()
                ->post()
                ->id('form-nfce')
                ->route('nfce.store')
                ->multipart()
                !!}
                <div class="pl-lg-4">
                    @include('nfce._forms')
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
@include('modals._novo_cliente')
@endsection

@section('js')
<script src="/js/nfce.js"></script>
<script src="/js/novo_cliente.js"></script>
@endsection
