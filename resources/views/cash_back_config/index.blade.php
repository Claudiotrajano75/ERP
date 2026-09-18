@extends('layouts.app', ['title' => 'Configuração de CashBack'])

@section('css')
<style>
/* ─── Painéis de Seção Interna ─── */
.card-secao-fiscal {
    border: 1px solid #eef2f6 !important;
    border-radius: 14px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
    margin-bottom: 24px !important;
    background: #ffffff;
    overflow: hidden;
}
.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 14px 20px;
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
.card-secao-fiscal .card-body { padding: 24px !important; }

/* ─── Tags de Variáveis ─── */
.var-badge {
    background: #eef2ff;
    color: #4f46e5;
    border: 1px solid #c7d2fe;
    padding: 4px 8px;
    border-radius: 6px;
    font-family: monospace;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-coin-line"></i>
                                Programa de Fidelidade — CashBack
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Defina as regras de retorno de crédito, validade, valor mínimo de venda e mensagem automática do WhatsApp.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Início
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)->post()->route('cash-back-config.store')->multipart()!!}

                    @include('cash_back_config._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
