@extends('layouts.app', ['title' => $data->tpNF == 0 ? 'Detalhes da Compra' : 'Detalhes da Venda'])

@section('css')
<style>
/* ─── Painel de Informações da Venda ─── */
.sale-info-panel {
    background: #f8fafc;
    border: 1px solid #eef0f6;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.info-avatar-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #eef0ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.info-kpi-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #8c8ca6;
    margin-bottom: 2px;
}
.info-kpi-value {
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
}
.info-kpi-subvalue {
    font-size: 13.5px;
    font-weight: 600;
    color: #374151;
}

/* ─── Seções & Título ─── */
.section-headline {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}
.section-headline .sec-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #eef0ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}

/* ─── Tabela ─── */
.tb-wrap { 
    border-radius: 14px; 
    border: 1px solid #eef0f5; 
    overflow: hidden; 
    background: #fff; 
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { 
    background: #f8f9fc; 
    color: #5a5a7a; 
    font-weight: 700; 
    font-size: 11px; 
    text-transform: uppercase; 
    letter-spacing: .4px; 
    padding: 12px 14px; 
    border-bottom: 1px solid #e8eaf6; 
    white-space: nowrap; 
}
.tb-wrap tbody td { 
    padding: 11px 14px; 
    vertical-align: middle; 
    border-bottom: 1px solid #f0f2f8; 
    font-size: 13px; 
    color: #374151; 
}
.tb-wrap tbody tr:hover { background: #f8f9ff; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
.tb-wrap tfoot td {
    padding: 13px 14px;
    vertical-align: middle;
    border-top: 2px solid #e2e8f0;
    font-size: 13.5px;
}

/* ─── Edição de Valor de Compra ─── */
.purchase-input-wrap {
    position: relative;
    max-width: 145px;
}
.purchase-price-input {
    height: 32px;
    font-size: 12.5px;
    font-weight: 600;
    border-radius: 8px;
    border: 1px solid #dcdce9;
    background: #fff;
    padding-right: 30px;
    transition: all .2s ease;
}
.purchase-price-input:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}
.btn-save-purchase {
    position: absolute;
    right: 2px;
    top: 2px;
    height: 28px;
    width: 26px;
    padding: 0;
    display: none;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}

/* ─── Bloco Total Geral ─── */
.grand-total-panel {
    background: #ffffff;
    border: 1px solid #e9ecf3;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

/* ─── Badges / Pills ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #f1f5f9; color: #64748b; }
.pill-role { background: #eef0ff; color: #4f46e5; }
.pill-amber { background: #fef3c7; color: #b45309; }
.pill-red { background: #fee2e2; color: #b91c1c; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO PADRÃO DO SISTEMA ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            {{ $data->tpNF == 0 ? 'Detalhes da Compra' : 'Detalhes da Venda' }}
                            @if($data->numero)
                                <span class="fw-bold" style="color: #4f46e5;">#{{ $data->numero }}</span>
                            @else
                                <span class="fw-bold" style="color: #4f46e5;">#{{ $data->id }}</span>
                            @endif
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Visualização detalhada dos itens comercializados, valores e condições financeiras.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="dash-btn dash-btn-light" title="Imprimir Pedido" target="_blank" href="{{ route('nfe.imprimirVenda', [$data->id]) }}">
                            <i class="ri-printer-line"></i> Imprimir Pedido
                        </a>

                        @if(__isPlanoFiscal() && $data->estado == 'aprovado')
                        <a href="{{ route('nfe.download-xml', [$data->id]) }}" class="dash-btn dash-btn-light">
                            <i class="ri-file-download-line"></i> Download XML
                        </a>

                        <button type="button" onclick="imprimir('{{$data->id}}', '{{$data->numero}}')" class="dash-btn dash-btn-light" title="Imprimir NFe">
                            <i class="ri-printer-fill"></i> DANFE
                        </button>
                        @endif

                        @if($data->tpNF == 0)
                        <a href="{{ route('compras.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                        @else
                        <a href="{{ route('nfe.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ PAINEL DE DADOS PRINCIPAIS (SEM CARDS SOLTOS) ═══ -->
                <div class="sale-info-panel mb-4 p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3">
                                <div class="info-avatar-icon">
                                    <i class="ri-user-3-line"></i>
                                </div>
                                <div>
                                    <span class="info-kpi-label">{{ $data->tpNF == 0 ? 'FORNECEDOR' : 'CLIENTE' }}</span>
                                    <div class="info-kpi-value text-truncate" style="max-width: 290px;" title="{{ $data->tpNF == 0 ? ($data->fornecedor ? $data->fornecedor->razao_social : 'Consumidor Final') : ($data->cliente ? $data->cliente->razao_social : 'Consumidor Final') }}">
                                        {{ $data->tpNF == 0 ? ($data->fornecedor ? $data->fornecedor->razao_social : 'Consumidor Final') : ($data->cliente ? $data->cliente->razao_social : 'Consumidor Final') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $data->tpNF == 0 ? ($data->fornecedor ? ($data->fornecedor->cpf_cnpj ?? '') : '') : ($data->cliente ? ($data->cliente->cpf_cnpj ?? '') : '') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <span class="info-kpi-label">CADASTRO / EMISSÃO</span>
                            <div class="info-kpi-subvalue">
                                <i class="ri-calendar-line me-1 text-muted"></i>{{ __data_pt($data->created_at) }}
                            </div>
                            @if($data->data_emissao)
                            <small class="text-muted d-block mt-1">
                                Emissão: {{ __data_pt($data->data_emissao) }}
                            </small>
                            @endif
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <span class="info-kpi-label">SITUAÇÃO</span>
                            <div>
                                @if($data->estado == 'aprovado')
                                    <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                @elseif($data->estado == 'cancelado')
                                    <span class="pill pill-red"><i class="ri-close-circle-fill"></i> Cancelado</span>
                                @elseif($data->estado == 'rejeitado')
                                    <span class="pill pill-amber"><i class="ri-alert-fill"></i> Rejeitado</span>
                                @else
                                    <span class="pill pill-role"><i class="ri-time-line"></i> {{ ucfirst($data->estado ?? 'Novo') }}</span>
                                @endif
                            </div>
                            @if($data->user)
                            <small class="text-muted d-block mt-1" title="Usuário responsável">
                                <i class="ri-user-follow-line me-1"></i>{{ $data->user->name }}
                            </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-12 col-12">
                            @if($data->chave)
                            <span class="info-kpi-label">DOCUMENTO FISCAL (NFe)</span>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-light text-dark border px-2 py-1">Nº {{ $data->numero }}</span>
                                <span class="font-monospace fs-12 text-muted text-truncate" style="max-width: 220px;" title="{{ $data->chave }}">
                                    {{ $data->chave }}
                                </span>
                                <button type="button" class="btn btn-sm btn-light border py-0 px-2" onclick="navigator.clipboard.writeText('{{ $data->chave }}'); swal('Copiado!', 'Chave da NFe copiada para a área de transferência', 'success');" title="Copiar Chave">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                            @else
                            <span class="info-kpi-label">DOCUMENTO AUXILIAR</span>
                            <div class="info-kpi-subvalue text-muted">
                                Pedido interno registrado sob ID #{{ $data->id }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ═══ GRID DE ITENS (PRODUTOS) ═══ -->
                <div class="section-headline">
                    <span class="sec-icon"><i class="ri-shopping-bag-3-line"></i></span>
                    Itens da Venda
                </div>

                @php
                    $somaTotalVenda = 0;
                    $somaTotalCompra = 0;
                @endphp

                <div class="tb-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0" id="table-itens-venda">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center" style="width: 120px;">Quantidade</th>
                                    <th class="text-center" style="width: 100px;">Unidade</th>
                                    <th class="text-center" style="width: 150px;">Valor Venda</th>
                                    <th class="text-center" style="width: 160px;">Valor Compra</th>
                                    <th class="text-center" style="width: 170px;">Total C/ Desconto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data->itens as $item)
                                @php
                                    $qtd = (float) $item->quantidade;
                                    $vendaUnit = (float) $item->valor_unitario;
                                    $unidadeMedida = $item->produto->unidade ?? 'UN';
                                    $vlrCompra = (float) ($item->produto->valor_compra ?? 0);

                                    $totItemVenda = $qtd * $vendaUnit;
                                    $totItemCompra = $qtd * $vlrCompra;
                                    
                                    // Total C/ Desconto = (Valor Venda - Valor Compra) * Quantidade
                                    $totCDesc = ($vendaUnit - $vlrCompra) * $qtd;

                                    $somaTotalVenda += $totItemVenda;
                                    $somaTotalCompra += $totItemCompra;
                                @endphp
                                <tr class="row-item-venda" data-item-id="{{ $item->id }}" data-qtd="{{ $qtd }}" data-venda="{{ $vendaUnit }}">
                                    <td>
                                        <div class="fw-semibold" style="color: #1f2937;">
                                            {{ $item->descricao() }}
                                        </div>
                                        @if($item->produto && $item->produto->codigo_barras)
                                        <small class="text-muted fs-11">
                                            Cód: {{ $item->produto->codigo_barras }}
                                        </small>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold fs-14" style="color: #1f2937;">
                                        {{ number_format($qtd, 2, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border px-2 py-1 fs-11">
                                            {{ strtoupper($unidadeMedida) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold fs-14" style="color: #1f2937;">
                                        R$ {{ __moeda($vendaUnit) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="purchase-input-wrap mx-auto">
                                            <input type="text" 
                                                   class="form-control form-control-sm purchase-price-input moeda text-center" 
                                                   data-produto-id="{{ $item->produto_id }}" 
                                                   data-item-id="{{ $item->id }}" 
                                                   value="{{ number_format($vlrCompra, 2, ',', '.') }}" 
                                                   title="Valor de compra do produto (editável)">
                                            <button type="button" class="btn btn-success btn-sm btn-save-purchase" title="Salvar novo valor de compra">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold fs-14 col-total-c-desconto" style="color: #1f2937;" data-valor="{{ $totCDesc }}">
                                        R$ {{ __moeda($totCDesc) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Nenhum item encontrado nesta venda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @php
                    $somaMargemLucro = $somaTotalVenda - $somaTotalCompra;
                @endphp

                <!-- ═══ PAINEL DE FECHAMENTO (ABAIXO DA GRID, À DIREITA) ═══ -->
                <div class="row justify-content-end mt-4 mb-4">
                    <div class="col-lg-5 col-md-7 col-12">
                        <div class="text-end pe-2">
                            <div class="d-flex justify-content-end align-items-center gap-2 mb-2">
                                <span class="text-dark fs-14 fw-normal">Total do Subtotal (Sem Desconto):</span>
                                <span class="fw-bold text-dark fs-15" id="resumo-subtotal-sem-desconto">R$ {{ __moeda($somaTotalVenda) }}</span>
                            </div>

                            <div class="d-flex justify-content-end align-items-center gap-2 mb-2">
                                <span class="text-dark fs-14 fw-normal">Total do Subtotal (Com Desconto):</span>
                                <span class="fw-bold text-dark fs-15" id="resumo-subtotal-com-desconto">R$ {{ __moeda($somaTotalCompra) }}</span>
                            </div>

                            <div class="d-flex justify-content-end align-items-center gap-2 pt-2">
                                <span class="text-dark fs-15 fw-bold text-uppercase">TOTAL (Margem lucro):</span>
                                <span class="fw-bolder fs-18 text-dark" id="resumo-total-margem">R$ {{ __moeda($somaMargemLucro) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FATURA / PAGAMENTOS ═══ -->
                @if(sizeof($data->fatura) > 0)
                <div class="mt-4">
                    <div class="section-headline">
                        <span class="sec-icon"><i class="ri-bank-card-line"></i></span>
                        Fatura & Formas de Pagamento
                    </div>
                    <div class="tb-wrap col-lg-8 col-12 p-0">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Forma de Pagamento</th>
                                        <th class="text-center" style="width: 180px;">Data de Vencimento</th>
                                        <th class="text-end" style="width: 160px;">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data->fatura as $itemFatura)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-money-dollar-circle-line text-primary fs-16"></i>
                                                <span class="fw-semibold">{{ $itemFatura->getTipoPagamento($itemFatura->tipo_pagamento) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ __data_pt($itemFatura->data_vencimento, 0) }}
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            R$ {{ __moeda($itemFatura->valor) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Modal de Impressão de DANFE -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <div class="modal-header">
                <h5 class="modal-title fs-15 fw-bold" id="exampleModalLabel">
                    <i class="ri-printer-line me-1 text-primary"></i> Imprimir NFe <strong class="ref-numero text-primary"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-2">
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-success w-100 py-2" onclick="gerarDanfe('danfe')">
                            <i class="ri-file-text-line me-1"></i> DANFE
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-primary w-100 py-2" onclick="gerarDanfe('simples')">
                            <i class="ri-file-paper-line me-1"></i> DANFE Simples
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-dark w-100 py-2" onclick="gerarDanfe('etiqueta')">
                            <i class="ri-price-tag-3-line me-1"></i> DANFE Etiqueta
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var IDNFE = null;

    function imprimir(id, numero) {
        IDNFE = id;
        $('.ref-numero').text(numero ? '#' + numero : '');
        $('#modal-print').modal('show');
    }

    function gerarDanfe(tipo) {
        if (tipo == 'danfe') {
            window.open('/nfe/imprimir/' + IDNFE);
        } else if (tipo == 'simples') {
            window.open('/nfe/danfe-simples/' + IDNFE);
        } else {
            window.open('/nfe/danfe-etiqueta/' + IDNFE);
        }
        $('#modal-print').modal('hide');
    }

    function parseMoeda(str) {
        if (!str) return 0;
        str = str.toString().replace('R$', '').trim();
        str = str.replace(/\./g, '').replace(',', '.');
        return parseFloat(str) || 0;
    }

    function formatMoeda(num) {
        return 'R$ ' + Number(num).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcularLinha($input) {
        var $row = $input.closest('.row-item-venda');
        var qtd = parseFloat($row.data('qtd')) || 1;
        var valorVenda = parseFloat($row.data('venda')) || 0;
        var valorCompra = parseMoeda($input.val());

        // Total C/ Desconto = (Valor Venda - Valor Compra) * Qtd
        var totCDesc = (valorVenda - valorCompra) * qtd;

        var $totalCell = $row.find('.col-total-c-desconto');
        $totalCell.data('valor', totCDesc).text(formatMoeda(totCDesc));

        recalcularTotais();
    }

    function recalcularTotais() {
        var somaVenda = 0;
        var somaCompra = 0;

        $('.row-item-venda').each(function() {
            var qtd = parseFloat($(this).data('qtd')) || 1;
            var valorVenda = parseFloat($(this).data('venda')) || 0;
            var valorCompra = parseMoeda($(this).find('.purchase-price-input').val());

            somaVenda += (qtd * valorVenda);
            somaCompra += (qtd * valorCompra);
        });

        var margemLucro = somaVenda - somaCompra;

        $('#resumo-subtotal-sem-desconto').text(formatMoeda(somaVenda));
        $('#resumo-subtotal-com-desconto').text(formatMoeda(somaCompra));
        $('#resumo-total-margem').text(formatMoeda(margemLucro));
    }

    $(document).ready(function() {
        // Exibe botão de salvar e recalcula subtotal em tempo real ao digitar
        $(document).on('input keyup', '.purchase-price-input', function() {
            var $wrap = $(this).closest('.purchase-input-wrap');
            $wrap.find('.btn-save-purchase').css('display', 'inline-flex');
            recalcularLinha($(this));
        });

        // Salvar ao pressionar Enter ou ao perder foco se modificado
        $(document).on('keypress', '.purchase-price-input', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                salvarValorCompra($(this));
            }
        });

        $(document).on('click', '.btn-save-purchase', function() {
            var $input = $(this).siblings('.purchase-price-input');
            salvarValorCompra($input);
        });

        function salvarValorCompra($input) {
            var produtoId = $input.data('produto-id');
            var itemId = $input.data('item-id');
            var novoValor = $input.val();
            var $btn = $input.siblings('.btn-save-purchase');

            if (!produtoId) return;

            // Garante o recálculo na tela
            recalcularLinha($input);

            $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i>');

            $.ajax({
                url: '{{ route("nfe.atualizar-valor-compra") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    produto_id: produtoId,
                    item_id: itemId,
                    valor_compra: novoValor
                },
                success: function(res) {
                    $btn.html('<i class="ri-check-line"></i>');
                    setTimeout(function() {
                        $btn.hide().prop('disabled', false);
                    }, 1200);

                    if (typeof toastr !== 'undefined') {
                        toastr.success('Valor de compra atualizado!');
                    } else if (typeof swal !== 'undefined') {
                        // feedback discreto
                    }
                },
                error: function(err) {
                    $btn.prop('disabled', false).html('<i class="ri-close-line text-danger"></i>');
                    var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Erro ao atualizar valor!';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            });
        }
    });
</script>
@endsection
