@extends('layouts.app', ['title' => 'Editar NFCe'])

@section('css')
<style>
/* ─── Navegação por Abas (Tabs) ─── */
.nav-tabs-custom {
    background: #f8fafc;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    gap: 4px;
}

.nav-tabs-custom .nav-link {
    flex: 1;
    border-radius: 8px !important;
    padding: 10px 16px;
    font-weight: 600;
    font-size: 13px;
    color: #64748b;
    border: none !important;
    background: transparent;
    text-align: center;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.nav-tabs-custom .nav-link:hover {
    color: #334155;
    background: rgba(255, 255, 255, 0.6);
}

.nav-tabs-custom .nav-link.active {
    background: #ffffff !important;
    color: #4f46e5 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

/* ─── Cards de Seção Interna (Painéis Fiscais) ─── */
.card-secao-fiscal {
    border: 1px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
    margin-bottom: 20px !important;
    background: #ffffff;
}

.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 12px 20px;
    border-radius: 12px 12px 0 0 !important;
}

.card-secao-fiscal .card-header h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-secao-fiscal .card-body {
    padding: 20px !important;
}

/* ─── Botões de Ação Footer ─── */
.modulo-actions {
    background: #f8fafc;
    border-top: 1px solid #eef0f5;
    margin: 24px -24px -24px -24px;
    padding: 20px 24px;
    border-radius: 0 0 12px 12px;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-box-line text-warning"></i>
                                Editar NFCe #{{ $item->numero_sequencial ?: $item->id }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Altere os dados da nota fiscal de consumidor eletrônica selecionada.</p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @if(__countLocalAtivo() > 1 && isset($caixa))
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-12 d-flex align-items-center gap-1">
                                <i class="ri-map-pin-line"></i>
                                {{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}
                            </span>
                            @endif
                            <a href="{{ route('nfce.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->id('form-nfce')
                    ->route('nfce.update', [$item->id])
                    ->multipart()
                    !!}
                    <div class="pl-lg-2">
                        @include('nfce._forms')
                    </div>
                    {!!Form::close()!!}
                </div>
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
