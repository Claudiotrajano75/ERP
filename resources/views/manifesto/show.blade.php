@extends('layouts.app', ['title' => 'Detalhes da NFe Importada'])

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
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-code-line"></i>
                                Detalhes da Nota Fiscal Importada
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Nota Fiscal: <strong class="text-white">{{ $nNf }}</strong> | Chave: <strong class="text-white">{{ $chave }}</strong></p>
                        </div>
                        <div>
                            <a href="{{ route('manifesto.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO PAINEL -->
                <div class="card-body p-4">
                    
                    <!-- Formulário de Compra -->
                    {!!Form::open()
                    ->post()
                    ->route('dfe.storeCompra')
                    ->multipart()!!}
                    
                    <input type="hidden" name="fornecedor_id" id="idFornecedor" value="{{$forn->id}}">
                    <input type="hidden" name="valor_total" value="{{$dfe->valor}}">
                    <input type="hidden" name="chave" id="chave" value="{{$chave}}">
                    <input type="hidden" name="nNf" id="nNf" value="{{$nNf}}">
                    <input type="hidden" name="dfe_id" id="" value="{{$dfe->id}}">

                    <!-- Dados do Emitente / Fornecedor -->
                    <div class="border rounded p-3 mb-4 bg-light">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                            <h5 class="m-0 fs-14 fw-bold text-dark"><i class="ri-truck-line me-1 text-primary"></i> Informações do Fornecedor / Distribuidor</h5>
                            @if(count($fornecedor) > 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fs-11">Dados Atualizados</span>
                            @endif
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <ul class="list-unstyled mb-0 fs-13" style="line-height: 1.8;">
                                    <li>Fornecedor: <strong class="text-dark">{{ $forn->razao_social }}</strong></li>
                                    <li>Nome Fantasia: <strong class="text-dark">{{ $forn->nome_fantasia }}</strong></li>
                                    <li>CNPJ / CPF: <strong class="text-dark">{{ $forn->cpf_cnpj }}</strong></li>
                                    <li>Inscrição Estadual: <strong class="text-dark">{{ $forn->ie_rg }}</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-12">
                                <ul class="list-unstyled mb-0 fs-13" style="line-height: 1.8;">
                                    <li>Logradouro: <strong class="text-dark">{{ $forn->rua }}, Nº {{ $forn->numero }}</strong></li>
                                    <li>Bairro: <strong class="text-dark">{{ $forn->bairro }}</strong></li>
                                    <li>Cidade / CEP: <strong class="text-dark">{{ $forn->cep }}</strong></li>
                                    <li>Telefone: <strong class="text-dark">{{ $forn->fone }}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Produtos / Itens da Nota -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                            <h5 class="m-0 fs-14 fw-bold text-dark"><i class="ri-box-3-line me-1 text-primary"></i> Itens da Nota Fiscal ({{ sizeof($itens) }})</h5>
                            <span class="text-danger fs-12">* Produtos destacados em vermelho não estão catalogados no sistema.</span>
                        </div>

                        <div class="modulo-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>Nome / Descrição NFe</th>
                                            <th>NCM</th>
                                            <th>CFOP Orig</th>
                                            <th>CEST</th>
                                            <th>Cód. Barras</th>
                                            <th>Unidade</th>
                                            <th>Preço Un.</th>
                                            <th>Qtd</th>
                                            <th style="width: 100px;">CFOP Ent.</th>
                                            <th>Subtotal</th>
                                            <th class="text-end" style="width: 80px;">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($itens as $i)
                                        <tr id="tr_{{$i['codigo']}}">
                                            <input type="hidden" class="inp-novo-{{$i['codigo']}} inp-check" value="{{$i['produtoNovo']}}">
                                            <input type="hidden" class="produto_id_{{$i['codigo']}}" name="produto_id[]" value="{{$i['produto_id']}}">
                                            <input type="hidden" name="quantidade[]" value="{{$i['qCom']}}">
                                            <input type="hidden" name="valor_unitario[]" value="{{$i['vUnCom']}}">
                                            <input type="hidden" name="unidade_compra[]" value="{{$i['uCom']}}">
                                            <input type="hidden" name="cfop[]" value="{{$i['CFOP']}}">
                                            <input type="hidden" name="cest[]" value="{{$i['CEST']}}">

                                            <td>{{$i['codigo']}}</td>
                                            <td>
                                                <span id="n_{{$i['codigo']}}" class="{{$i['produtoNovo'] ? 'text-danger fw-bold' : 'text-dark fw-semibold'}}">{{$i['xProd']}}</span>
                                            </td>
                                            <td>{{$i['NCM']}}</td>
                                            <td>{{$i['CFOP']}}</td>
                                            <td>{{$i['CEST'] ?? '--'}}</td>
                                            <td>{{$i['codBarras'] ?? '--'}}</td>
                                            <td>{{$i['uCom']}}</td>
                                            <td class="fw-semibold">R$ {{__moeda((float)$i['vUnCom'])}}</td>
                                            <td id="qtd_aux_{{$i['codigo']}}" class="fw-semibold">{{$i['qCom']}}</td>
                                            <td>
                                                <input id="cfop_entrada_input" class="cfop form-control form-control-sm text-center" style="width: 70px;" type="text" value="{{$i['CFOP']}}">
                                            </td>
                                            <td class="fw-bold text-success">R$ {{__moeda((float) $i['qCom'] * (float) $i['vUnCom'])}}</td>
                                            <td class="text-end">
                                                @if($i['produtoNovo'])
                                                <button type="button" class="btn btn-success btn-sm btn-cad-{{$i['codigo']}}" id="th_acao1_{{$i['codigo']}}" onclick="cadProd('{{$i['codigo']}}','{{$i['xProd']}}','{{$i['codBarras']}}','{{$i['NCM']}}','{{$i['CFOP']}}','{{$i['uCom']}}','{{$i['vUnCom']}}','{{$i['qCom']}}', '{{$i['CFOP']}}','{{$i['CEST']}}')" title="Cadastrar Produto">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                @else
                                                <span class="badge bg-light text-muted border">Ok</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="total" id="total" class="moeda" value="{{$infos['vProd']}}">

                    <!-- Rodapé da Compra -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-5 border-bottom pb-4">
                        <div>
                            <h4 class="m-0 text-dark">Valor Líquido da NFe: <strong id="valorDaNF" class="text-success fs-18">R$ {{ __moeda((float)$infos['vProd']) }}</strong></h4>
                        </div>
                        <div>
                            @if($dfe->compra_id == 0)
                            <button type="submit" disabled id="btn-salvar" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600;">
                                <i class="ri-save-line align-middle me-1"></i> Importar e Salvar como Compra
                            </button>
                            @else
                            <span class="badge bg-success border border-success p-2 fs-12" style="border-radius: 8px;"><i class="ri-checkbox-circle-line me-1"></i> Compra já Criada</span>
                            @endif
                        </div>
                    </div>

                    {!!Form::close()!!}

                    <!-- Formulário de Fatura / Financeiro -->
                    <div class="border-top pt-4">
                        {!!Form::open()
                        ->post()
                        ->route('dfe.storeFatura')
                        !!}
                        
                        <input type="hidden" name="fornecedor_id" id="" value="{{$forn->id}}">
                        <input type="hidden" name="dfe_id" id="" value="{{$dfe->id}}">
                        <input type="hidden" id="fatura" value="{{json_encode($fatura)}}">

                        <h5 class="fs-14 fw-bold text-dark mb-3"><i class="ri-wallet-line me-1 text-primary"></i> Programação de Faturamento / Parcelas</h5>
                        
                        <div class="modulo-table-wrap mb-3">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 align-middle table-dynamic">
                                    <thead>
                                        <tr>
                                            <th style="width: 250px;">Data de Vencimento</th>
                                            <th>Valor da Parcela (R$)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body" class="datatable-body">
                                        @isset($fatura)
                                        @foreach ($fatura as $f)
                                        <tr class="dynamic-form">
                                            <td>
                                                <input type="text" class="form-control" name="vencimento[]" value="{{$f['vencimento']}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control moeda" name="valor_parcela[]" value="{{$f['valor_parcela']}}">
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="vencimento[]" value="{{$d['data_emissao'] ?? ''}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control moeda" name="valor_parcela[]" value="{{$d['valor'] ?? ''}}">
                                            </td>
                                        </tr>
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($dfe->fatura_salva == 0 && sizeof($fatura) > 0)
                        <button type="submit" class="btn btn-primary btn-sm px-4" style="border-radius: 8px; font-weight: 600;">
                            <i class="ri-checkbox-circle-line align-middle me-1"></i> Registrar Fatura no Contas a Pagar
                        </button>
                        @else
                        <span class="badge bg-success border border-success p-2 fs-12" style="border-radius: 8px;"><i class="ri-checkbox-circle-line me-1"></i> Fatura Registrada / Indisponível</span>
                        @endif

                        {!!Form::close()!!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
