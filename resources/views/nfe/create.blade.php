@extends('layouts.app', ['title' => isset($isCompra) ? 'Nova Compra' : (isset($isOrcamento) && $isOrcamento == 1 ? 'Novo Orçamento' : 'Nova Venda')])

@section('css')
<style>
/* ─── Padrão Oficial ERP Layout Modernization ─── */
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
    padding: 14px 20px;
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
    padding: 22px !important;
}

/* ─── Navegação por Abas (Tabs) ─── */
.nav-tabs-custom {
    background: #f8fafc;
    padding: 6px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    gap: 6px;
}

.nav-tabs-custom .nav-link {
    flex: 1;
    border-radius: 10px !important;
    padding: 11px 18px;
    font-weight: 600;
    font-size: 13.5px;
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
    background: rgba(255, 255, 255, 0.7);
}

.nav-tabs-custom .nav-link.active {
    background: #ffffff !important;
    color: #4f46e5 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

/* ─── Botões de Ação Squircle ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end; }
.act-btn { 
    width: 34px; 
    height: 34px; 
    border-radius: 10px; 
    border: 1px solid transparent; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 15px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: all .2s ease; 
    padding: 0;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover { background: #e0e7ff; color: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,.2); }
.act-del  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover  { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

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
                            @isset($isCompra)
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-shopping-cart-line"></i> Nova Compra — Entrada Manual
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Registre a compra de mercadorias, alimentando o estoque e o contas a pagar da empresa.</p>
                            @else
                            @if(isset($isOrcamento) && $isOrcamento == 1)
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-list-3-line"></i> Novo Orçamento
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Preencha os dados do cliente, produtos e gere a proposta comercial.</p>
                            @else
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-receipt-line"></i> Nova Venda — Emissão de NFe
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Preencha os dados do cliente, insira os produtos, configure frete e condição de pagamento.</p>
                            @endif
                            @endif
                            
                            @isset($isReserva)
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Consumo da Reserva <strong class="text-white">#{{ $item->numero_sequencial }}</strong></p>
                            @endisset
                            
                            @if(__countLocalAtivo() > 1 && isset($caixa) && !__escolheLocalidade())
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Local / Filial: <strong class="text-white">{{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}</strong></p>
                            @endif
                        </div>
                        <div>
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

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->id('form-nfe')
                    ->route('nfe.store')
                    ->attrs(['autocomplete' => 'off'])
                    !!}
                    <div class="pl-lg-2">
                        @include('nfe._forms')
                    </div>
                    {!!Form::close()!!}
                </div>

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
