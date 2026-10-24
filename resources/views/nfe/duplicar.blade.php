@php
    $ehCompra = (isset($item) && ($item->tpNF == 0 || ($item->fornecedor_id && $item->fornecedor)));
    if ($ehCompra) {
        $isCompra = 1;
    } else {
        unset($isCompra);
    }
    $tipoDoc = $item->orcamento ? 'Orçamento' : ($ehCompra ? 'Compra' : 'Venda');
@endphp
@extends('layouts.app', ['title' => 'Duplicar ' . $tipoDoc])

@section('css')
<style>
/* =====================================================
   FATURAMENTO / NF-e - PADRÃO PREMIUM UBOLD (Skill ERP)
   ===================================================== */
:root {
    --skin-primary: #4f46e5;
    --skin-primary-hover: #4338ca;
    --skin-primary-soft: #eef0ff;
    --skin-border: #e9ecf3;
}

/* NAVEGAÇÃO POR ABAS PILLS */
.nav-tabs-custom {
    background: #f8fafc;
    padding: 6px;
    border-radius: 14px;
    border: 1px solid var(--skin-border, #e9ecf3);
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
    padding: 12px 18px;
    font-weight: 600;
    font-size: 13.5px;
    color: #64748b;
    border: 1px solid transparent !important;
    background: transparent;
    text-align: center;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 46px;
}
.nav-tabs-custom .nav-link i {
    font-size: 18px;
    color: #94a3b8;
    transition: all 0.18s ease;
}
.nav-tabs-custom .nav-link:hover {
    color: var(--skin-primary, #4f46e5);
    background: #ffffff;
}
.nav-tabs-custom .nav-link:hover i {
    color: var(--skin-primary, #4f46e5);
}
.nav-tabs-custom .nav-link.active {
    background: #ffffff !important;
    color: var(--skin-primary, #4f46e5) !important;
    font-weight: 700;
    border-color: #e2e8f0 !important;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.12), 0 1px 2px rgba(16, 24, 40, 0.04);
}
.nav-tabs-custom .nav-link.active i {
    color: var(--skin-primary, #4f46e5);
    transform: scale(1.1);
}

/* CARDS DE SEÇÃO FISCAL */
.card-secao-fiscal {
    border: 1px solid var(--skin-border, #e9ecf3) !important;
    border-radius: 14px !important;
    box-shadow: 0 1px 3px rgba(16, 24, 40, 0.03) !important;
    margin-bottom: 22px !important;
    background: #ffffff;
    overflow: hidden;
}
.card-secao-fiscal > .card-header {
    background: #fafbfe !important;
    border-bottom: 1px solid #eef2f6 !important;
    padding: 14px 20px !important;
}
.card-secao-fiscal > .card-header h5 {
    margin: 0;
    font-size: 13.5px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-secao-fiscal > .card-header h5 i {
    font-size: 17px;
    color: var(--skin-primary, #4f46e5);
}
.card-secao-fiscal > .card-body {
    padding: 22px 20px !important;
}

/* TIPOGRAFIA E INPUTS DO FORMULÁRIO */
.card-secao-fiscal label,
.card-secao-fiscal .form-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #64748b;
    margin-bottom: 5px;
    display: block;
}
.card-secao-fiscal .form-control,
.card-secao-fiscal .form-select {
    height: 38px;
    border-radius: 8px;
    border: 1px solid #dcdce9;
    font-size: 13px;
    color: #1f2937;
    background: #fcfdfe;
    box-sizing: border-box;
    transition: border-color .15s ease, box-shadow .15s ease;
}
.card-secao-fiscal .form-control:focus,
.card-secao-fiscal .form-select:focus {
    border-color: var(--skin-primary, #4f46e5) !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    background: #ffffff;
}
.card-secao-fiscal .select2-container--bootstrap4 .select2-selection {
    height: 38px !important;
    border-radius: 8px !important;
    border-color: #dcdce9 !important;
}
.card-secao-fiscal .select2-container--bootstrap4.select2-container--focus .select2-selection {
    border-color: var(--skin-primary, #4f46e5) !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}

/* GRADE DE PRODUTOS FISCAIS */
.table-produtos {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}
.table-produtos thead th {
    background: #f8fafc !important;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 11px 14px;
    border-bottom: 1px solid #e2e8f0;
    border-top: 1px solid #e2e8f0;
    white-space: nowrap;
    vertical-align: middle;
}
.table-produtos tbody td {
    padding: 10px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}
.table-produtos tbody tr:hover td {
    background-color: #f8faff !important;
}
.table-produtos .sticky-col {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #ffffff;
}
.table-produtos thead .sticky-col {
    z-index: 3;
    background: #f8fafc !important;
}
.table-produtos .first-col {
    box-shadow: 3px 0 6px rgba(0, 0, 0, 0.04);
}

/* BOTÕES DE ITENS E AÇÕES DA GRADE */
.btn-add-tr-nfe {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--skin-primary, #4f46e5) !important;
    color: #ffffff !important;
    border: none;
    border-radius: 9px;
    font-size: 12.5px;
    font-weight: 700;
    padding: 8px 16px;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.22);
    transition: all .15s ease;
    cursor: pointer;
}
.btn-add-tr-nfe:hover {
    background: var(--skin-primary-hover, #4338ca) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.32);
}
.btn-remove-tr {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fee2e2 !important;
    color: #dc2626 !important;
    border: 0;
    transition: all .15s ease;
    cursor: pointer;
}
.btn-remove-tr:hover {
    background: #fecaca !important;
    color: #b91c1c !important;
    transform: scale(1.05);
}

/* CARDS DE TOTAIS (RODAPÉ FISCAL) */
.total-box-card {
    border: 1px solid var(--skin-border, #e9ecf3);
    border-radius: 14px;
    background: #ffffff;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 3px rgba(16, 24, 40, 0.03);
    transition: transform .15s ease, box-shadow .15s ease;
}
.total-box-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 24, 40, 0.06);
}

/* RODAPÉ DE AÇÕES */
.modulo-actions {
    background: #fafbfe;
    border-top: 1px solid #eef2f6;
    margin: 24px -24px -24px -24px;
    padding: 16px 24px;
    border-radius: 0 0 16px 16px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}
.btn-cf-voltar {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569 !important;
    border-radius: 10px;
    padding: 9px 18px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
    transition: all .15s ease;
}
.btn-cf-voltar:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a !important;
    transform: translateY(-1px);
}
.btn-salvar-nfe {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--skin-primary, #4f46e5);
    border: none;
    color: #ffffff !important;
    font-weight: 700;
    font-size: 13.5px;
    border-radius: 10px;
    padding: 10px 24px;
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    transition: all .15s ease;
    cursor: pointer;
}
.btn-salvar-nfe:hover {
    background: var(--skin-primary-hover, #4338ca);
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
}

/* BANNER INFORMATIVO DE DUPLICAÇÃO */
.duplicar-badge-banner {
    background: #eef2ff;
    border: 1px solid #c7d2fe;
    border-radius: 12px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
}
.duplicar-badge-banner i {
    font-size: 24px;
    color: #4f46e5;
    flex-shrink: 0;
}
.duplicar-badge-banner .info-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #1e1b4b;
    margin-bottom: 2px;
}
.duplicar-badge-banner .info-sub {
    font-size: 12.5px;
    color: #4338ca;
    margin: 0;
}
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            {{-- CABEÇALHO PADRÃO PREMIUM UBOLD --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-copy-2-line"></i>
                            Duplicar {{ $tipoDoc }}
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-1">
                                Origem: #{{ $item->numero_sequencial ?? $item->numero ?? $item->id }}
                            </span>
                            @if(__countLocalAtivo() > 1 && isset($caixa) && !__escolheLocalidade())
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-12 ms-1">
                                Filial: {{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}
                            </span>
                            @endif
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Revise e ajuste os dados do {{ $ehCompra ? 'fornecedor' : 'cliente' }}, itens e condições de pagamento para gerar a nova {{ strtolower($tipoDoc) }}.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <input type="hidden" id="is_orcamento" value="{{ isset($isOrcamento) && $isOrcamento == 1 ? '1' : ($item->orcamento ? '1' : '0') }}">
                        <a href="{{ $ehCompra ? route('compras.index') : route('nfe.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {{-- BANNER DE AVISO DE DUPLICAÇÃO --}}
                <div class="duplicar-badge-banner">
                    <i class="ri-information-line"></i>
                    <div>
                        <div class="info-title">Modo de Duplicação Ativo</div>
                        <p class="info-sub">
                            Os dados da {{ strtolower($tipoDoc) }} original <strong>#{{ $item->numero_sequencial ?? $item->numero ?? $item->id }}</strong> foram pré-carregados. Ao clicar em <strong>Salvar {{ $tipoDoc }}</strong>, um novo documento será gerado com numeração própria e independente.
                        </p>
                    </div>
                </div>

                {{-- FORMULÁRIO COM ID OBRIGATÓRIO PARA O JS --}}
                {!!Form::open()->fill($item)
                ->post()
                ->id('form-nfe')
                ->route('nfe.store')
                ->attrs(['autocomplete' => 'off'])
                ->multipart()
                !!}
                <div>
                    @include('nfe._forms')
                </div>
                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>

@if($ehCompra)
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
@if($ehCompra)
<script src="/js/novo_fornecedor.js"></script>
@else
<script src="/js/novo_cliente.js"></script>
@endif
@endsection
@endsection
