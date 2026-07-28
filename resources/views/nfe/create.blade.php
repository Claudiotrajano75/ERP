@extends('layouts.app', ['title' => isset($isCompra) ? 'Nova Compra' : (isset($isOrcamento) && $isOrcamento == 1 ? 'Novo Orçamento' : 'Nova Venda')])

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
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Botões de Ação do Formulário ─── */
.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }
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
                                <i class="ri-shopping-cart-line"></i> Nova Compra
                            </h4>
                            @else
                            @if(isset($isOrcamento) && $isOrcamento == 1)
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-list-3-line"></i> Novo Orçamento
                            </h4>
                            @else
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-receipt-line"></i> Nova Venda — NFe
                            </h4>
                            @endif
                            @endif
                            
                            @isset($isReserva)
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Consumo da Reserva <strong class="text-white">#{{ $item->numero_sequencial }}</strong></p>
                            @else
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Preencha os dados do cliente, insira os produtos, e configure frete/fatura.</p>
                            @endisset
                            
                            @if(__countLocalAtivo() > 1 && isset($caixa) && !__escolheLocalidade())
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Local / Filial: <strong class="text-white">{{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}</strong></p>
                            @endif
                        </div>
                        <div>
                            @if(isset($isCompra))
                            <input type="hidden" id="is_orcamento" value="0">
                            <a href="{{ route('compras.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                            @else
                            @if(isset($isOrcamento))
                            <input type="hidden" id="is_orcamento" value="1">
                            @else
                            <input type="hidden" id="is_orcamento" value="0">
                            @endif
                            <a href="{{ route('nfe.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
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
<script src="/js/nfe.js"></script>
@isset($isCompra)
<script src="/js/novo_fornecedor.js"></script>
@else
<script src="/js/novo_cliente.js"></script>
@endif
@endsection
@endsection
