@extends('layouts.app', ['title' => isset($isCompra) ? 'Nova Compra' : (isset($isOrcamento) && $isOrcamento == 1 ? 'Novo Orçamento' : 'Nova Venda')])

@section('css')
<style>
/* =====================================================
   NOVA NF-e / VENDA / COMPRA - PADRÃO CENTRAL
   ===================================================== */
.cf-wrap {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #1e293b;
}

/* CABEÇALHO */
.cf-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
.cf-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.cf-header-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
}
.cf-header-icon.icon-compra {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);
}
.cf-header-icon.icon-orcamento {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    box-shadow: 0 4px 10px rgba(217, 119, 6, 0.25);
}
.cf-page-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cf-page-subtitle {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}
.btn-cf-voltar {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569 !important;
    font-weight: 600;
    font-size: 12.5px;
    border-radius: 8px;
    padding: 8px 16px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    text-decoration: none;
    cursor: pointer;
}
.btn-cf-voltar:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a !important;
    transform: translateY(-1px);
}

/* CARDS DE SEÇÃO FISCAL */
.card-secao-fiscal {
    border: 1px solid #f1f5f9 !important;
    border-radius: 12px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
    margin-bottom: 20px !important;
    background: #ffffff;
}
.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
    padding: 14px 20px;
    border-radius: 12px 12px 0 0 !important;
}
.card-secao-fiscal .card-header h5 {
    margin: 0;
    font-size: 13.5px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-secao-fiscal .card-body {
    padding: 20px !important;
}

/* NAVEGAÇÃO POR ABAS PILLS */
.nav-tabs-custom {
    background: #f1f5f9;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    gap: 8px;
    width: 100%;
}
.nav-tabs-custom .nav-item {
    flex: 1 1 0;
    display: flex;
}
.nav-tabs-custom .nav-link {
    width: 100%;
    border-radius: 10px !important;
    padding: 13px 20px;
    font-weight: 700;
    font-size: 14px;
    color: #475569;
    border: none !important;
    background: transparent;
    text-align: center;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 48px;
}
.nav-tabs-custom .nav-link i {
    font-size: 18px;
    transition: transform 0.2s ease;
}
.nav-tabs-custom .nav-link:hover {
    color: #1e293b;
    background: rgba(255, 255, 255, 0.6);
}
.nav-tabs-custom .nav-link.active {
    background: #ffffff !important;
    color: #2563eb !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
}
.nav-tabs-custom .nav-link.active i {
    transform: scale(1.1);
}

/* BOTÕES DE AÇÃO RODAPÉ */
.modulo-actions {
    background: #f8fafc;
    border-top: 1px solid #eef0f5;
    margin: 20px -20px -20px -20px;
    padding: 16px 20px;
    border-radius: 0 0 12px 12px;
}
.btn-salvar-nfe {
    background: #2563eb;
    border: none;
    color: #ffffff !important;
    font-weight: 700;
    font-size: 13px;
    border-radius: 8px;
    padding: 10px 24px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    cursor: pointer;
}
.btn-salvar-nfe:hover {
    background: #1d4ed8;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    transform: translateY(-1px);
}
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            @isset($isCompra)
                            <i class="ri-shopping-cart-line"></i> Nova Compra — Entrada Manual
                            @else
                                @if(isset($isOrcamento) && $isOrcamento == 1)
                                <i class="ri-file-list-3-line"></i> Novo Orçamento
                                @else
                                <i class="ri-file-text-line"></i> Nova Venda — Emissão de NF-e
                                @endif
                            @endif
                            @isset($isReserva)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-12 ms-2">
                                Reserva #{{ $item->numero_sequencial }}
                            </span>
                            @endisset
                            @if(__countLocalAtivo() > 1 && isset($caixa) && !__escolheLocalidade())
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2">
                                Filial: {{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}
                            </span>
                            @endif
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            @isset($isCompra)
                            Registre a compra de mercadorias, alimentando o estoque e o contas a pagar.
                            @else
                                @if(isset($isOrcamento) && $isOrcamento == 1)
                                Preencha os dados do cliente, produtos e gere a proposta comercial.
                                @else
                                Preencha os dados do cliente, produtos, configure frete e condição de pagamento.
                                @endif
                            @endif
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        @if(isset($isCompra))
                        <input type="hidden" id="is_orcamento" value="0">
                        <a href="{{ route('compras.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                        @else
                            @if(isset($isOrcamento))
                            <input type="hidden" id="is_orcamento" value="1">
                            @else
                            <input type="hidden" id="is_orcamento" value="0">
                            @endif
                            <a href="{{ route('nfe.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
            {!!Form::open()
            ->post()
            ->id('form-nfe')
            ->route('nfe.store')
            ->attrs(['autocomplete' => 'off'])
            !!}
            <div>
                @include('nfe._forms')
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>
</div>

@isset($isCompra)
@include('modals._novo_fornecedor')
@else
@include('modals._novo_cliente')
@endif

@section('js')
<script type="text/javascript">
    $(".tipo_pagamento").change(() => {
        let tipo = $(".tipo_pagamento").val();
        if (tipo == "03" || tipo == "04") {
            $('#cartao_credito').modal('show')
        }
    })
</script>
<script src="/js/nfe.js?v={{ time() }}"></script>
@isset($isCompra)
<script src="/js/novo_fornecedor.js"></script>
@else
<script src="/js/novo_cliente.js"></script>
@endif
@endsection
@endsection
