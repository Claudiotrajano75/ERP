<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Cotação #{{ $cotacao->referencia }} - Resposta do Fornecedor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Nunito', sans-serif; }
        .navbar-brand img { height: 40px; object-fit: contain; }
        .section-title { font-size: 13px; text-transform: uppercase; font-weight: 700; color: #6c757d; letter-spacing: .5px; margin-bottom: 12px; }
        .table th { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
        .total-badge { font-size: 20px; font-weight: 800; color: #0ab39c; }
    </style>
</head>
<body>
    <!-- Barra de Navegação do Emitente -->
    <nav class="navbar navbar-expand-md bg-white shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand py-2">
                @if($empresa->logo != '')
                <img src="{{ $empresa->img }}" alt="{{ $empresa->nome }}">
                @else
                <strong class="fs-18 text-dark">{{ $empresa->nome }}</strong>
                @endif
            </a>
            <div class="ms-auto">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12">
                    <i class="ri-price-tag-3-line align-middle me-1"></i> Cotação #{{ $cotacao->referencia }}
                </span>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container-fluid px-4">
            <form class="col-lg-12" method="post" action="{{ route('cotacoes.resposta-store') }}">
                @csrf
                <input type="hidden" name="cotacao_id" value="{{ $cotacao->id }}">

                <!-- Título e Cabeçalho -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">Proposta de Preços</h2>
                    <p class="text-muted fs-14 mb-0">Preencha os preços unitários abaixo e envie sua proposta para <strong>{{ $empresa->nome }}</strong></p>
                </div>

                <!-- Dados do Solicitante e Fornecedor -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="section-title">Solicitante</div>
                                <h5 class="fw-bold text-dark mb-1">{{ $empresa->nome }}</h5>
                                <p class="mb-0 text-muted fs-13"><i class="ri-phone-line me-1"></i> {{ $empresa->celular }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="section-title">Fornecedor / Cotante</div>
                                <h5 class="fw-bold text-dark mb-1">{{ strtoupper($cotacao->fornecedor->razao_social) }}</h5>
                                <p class="mb-0 text-muted fs-13">
                                    <i class="ri-map-pin-line me-1"></i> {{ $cotacao->fornecedor->cidade->info ?? '' }} &nbsp;|&nbsp;
                                    <i class="ri-id-card-line me-1"></i> {{ $cotacao->fornecedor->cpf_cnpj }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($cotacao->observacao)
                <div class="alert alert-info border-info-subtle bg-info-subtle text-info p-3 mb-4 d-flex align-items-start">
                    <i class="ri-information-line me-2 fs-18 mt-0.5"></i>
                    <div><strong>Observações do Comprador:</strong> {{ $cotacao->observacao }}</div>
                </div>
                @endif

                <!-- Grade de Itens -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom py-2">
                        <h5 class="fw-bold text-dark mb-0 fs-13"><i class="ri-box-3-line me-1 align-middle"></i> Itens Solicitados</h5>
                        <p class="text-danger mb-0 fs-11 mt-1">* Campos com asterisco são obrigatórios.</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th style="width:120px;">Quantidade</th>
                                    <th style="width:150px;">Valor Unitário <span class="text-danger">*</span></th>
                                    <th style="width:150px;">Subtotal <span class="text-danger">*</span></th>
                                    <th>Observação do Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cotacao->itens as $linha => $p)
                                <tr>
                                    <input type="hidden" name="item_id[]" value="{{ $p->id }}">
                                    <td>
                                        <input style="width:280px;" disabled type="text" class="form-control form-control-sm bg-light" value="{{ $p->produto->nome }}">
                                    </td>
                                    <td>
                                        @php $casasDecimais = 2; @endphp
                                        <input style="width:100px;" readonly type="tel" class="form-control form-control-sm moeda text-center" value="{{ number_format($p->quantidade, $casasDecimais) }}" name="quantidade[]">
                                    </td>
                                    <td>
                                        <input style="width:130px;" required type="tel" class="form-control form-control-sm moeda value" id="value" name="valor_unitario[]" placeholder="0,00">
                                    </td>
                                    <td>
                                        <input style="width:130px;" readonly type="text" name="subtotal[]" class="form-control form-control-sm subtotal text-success fw-bold" placeholder="0,00">
                                    </td>
                                    <td>
                                        <input style="width:280px;" type="text" name="observacao_item[]" class="form-control form-control-sm" placeholder="Opcional...">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light border-top py-2">
                        <div class="d-flex align-items-center justify-content-end">
                            <span class="text-dark fs-14 fw-semibold">Total dos Produtos: <span class="total text-success fw-bold">R$ 0,00</span></span>
                        </div>
                    </div>
                </div>

                <!-- Extras: Descontos, Frete, Observações, Previsão -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom py-2">
                        <h5 class="fw-bold text-dark mb-0 fs-13"><i class="ri-truck-line me-1 align-middle"></i> Condições de Fornecimento</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold text-dark fs-13">Desconto (R$)</label>
                                <input type="tel" id="desconto" name="desconto" class="form-control moeda" placeholder="0,00">
                            </div>
                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold text-dark fs-13">Valor do Frete</label>
                                <input type="tel" id="valor_frete" name="valor_frete" class="form-control moeda" placeholder="0,00">
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="form-label fw-semibold text-dark fs-13">Observação do Frete</label>
                                <input type="text" name="observacao_frete" class="form-control" placeholder="Ex: CIF, FOB, Transportadora...">
                            </div>
                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold text-dark fs-13">Previsão de Entrega <span class="text-danger">*</span></label>
                                <input required type="date" id="previsao_entrega" name="previsao_entrega" class="form-control">
                            </div>
                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold text-dark fs-13">Responsável <span class="text-danger">*</span></label>
                                <input required type="text" name="responsavel" class="form-control" placeholder="Nome completo">
                            </div>
                            <div class="col-lg-12 col-12">
                                <label class="form-label fw-semibold text-dark fs-13">Observação Geral</label>
                                <input type="text" name="observacao" class="form-control" placeholder="Informações adicionais sobre sua proposta...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fatura Parcelas -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom py-2">
                        <h5 class="fw-bold text-dark mb-0 fs-13"><i class="ri-wallet-line me-1 align-middle"></i> Fatura (Programação de Pagamento - Opcional)</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered align-middle mb-0 table-dynamic">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 200px;">Data de Vencimento</th>
                                    <th style="width: 200px;">Valor da Parcela</th>
                                    <th>Tipo de Pagamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="dynamic-form">
                                    <td>
                                        <input type="date" name="data_vencimento[]" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="tel" name="valor_parcela[]" class="form-control form-control-sm moeda valor_parcela" placeholder="0,00">
                                    </td>
                                    <td>
                                        <select class="form-control form-select form-select-sm" name="tipo_pagamento[]">
                                            @foreach(App\Models\FaturaCotacao::tiposPagamento() as $key => $tp)
                                            <option value="{{ $key }}">{{ $tp }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light border-top py-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="button" class="btn btn-sm btn-dark btn-add-tr">
                                <i class="ri-add-line align-middle me-1"></i> Adicionar Parcela
                            </button>
                            <span class="text-dark fs-14 fw-semibold">Total da Fatura: <span class="total-fatura text-success fw-bold">R$ 0,00</span></span>
                        </div>
                    </div>
                </div>

                <!-- Total Geral e Botão de Envio -->
                <div class="card border-0 shadow-sm mb-4 border-success">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <span class="text-muted fs-14">Valor Total da Proposta</span>
                            <div class="total-badge mt-1"><span class="total-cotacao">R$ 0,00</span></div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="ri-send-plane-line align-middle me-2"></i> Enviar Proposta de Cotação
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        $('.moeda').mask('000.000.000.000.000,00', {reverse: true});
    </script>
    <script src="/js/cotacao_response.js"></script>
</body>
</html>