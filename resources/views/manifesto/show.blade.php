@extends('layouts.app', ['title' => 'Detalhes da NFe Importada'])

@section('css')
<style>
/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
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
                            <a href="{{ route('manifesto.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
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
                    <div class="card card-secao-fiscal border p-3 rounded-3 mb-4 bg-white">
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

                        <div class="tb-wrap">
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
                                                <button type="button" class="dash-btn dash-btn-primary btn-sm btn-cad-{{$i['codigo']}}" id="th_acao1_{{$i['codigo']}}" onclick="cadProd('{{$i['codigo']}}','{{$i['xProd']}}','{{$i['codBarras']}}','{{$i['NCM']}}','{{$i['CFOP']}}','{{$i['uCom']}}','{{$i['vUnCom']}}','{{$i['qCom']}}', '{{$i['CFOP']}}','{{$i['CEST']}}')" title="Cadastrar Produto" style="padding: 4px 8px; font-size: 12px;">
                                                    <i class="ri-add-line"></i> Cadastrar
                                                </button>
                                                @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Vinculado</span>
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
                            <button type="submit" disabled id="btn-salvar" class="dash-btn dash-btn-primary px-4">
                                <i class="ri-save-line me-1"></i> Importar e Salvar como Compra
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
                        
                        <div class="tb-wrap mb-3">
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
                        <button type="submit" class="dash-btn dash-btn-primary px-4">
                            <i class="ri-checkbox-circle-line me-1"></i> Registrar Fatura no Contas a Pagar
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
