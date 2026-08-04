@extends('layouts.app', ['title' => 'Faturamento Avulso (NFe)'])

@section('css')
<style>
/* ─── Header Premium ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card label { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Wizard Steps ─── */
.wizard-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    position: relative;
}
.wizard-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #e2e8f0;
    z-index: 1;
}
.wizard-step {
    position: relative;
    z-index: 2;
    text-align: center;
    background: #f8f9fa;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    line-height: 40px;
    border: 3px solid #e2e8f0;
    color: #94a3b8;
    font-weight: 700;
    transition: all 0.3s ease;
    cursor: pointer;
}
.wizard-step.active {
    background: #302b63;
    border-color: #302b63;
    color: #fff;
    box-shadow: 0 0 0 4px rgba(48,43,99,0.15);
}
.wizard-step.completed {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
}
.wizard-label {
    position: absolute;
    top: 50px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    transition: all 0.3s ease;
}
.wizard-step.active .wizard-label {
    color: #302b63;
    font-weight: 700;
}

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #334155; }

/* ─── Actions Wrapper ─── */
.step-actions {
    border-top: 1px solid #f0f2f8;
    padding-top: 20px;
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
}
.step-actions .btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 24px;
    transition: all 0.2s ease;
}
.step-actions .btn:hover {
    transform: translateY(-1px);
}

/* ─── Switches Estilizados ─── */
.form-switch-lg .form-check-input {
    width: 44px;
    height: 22px;
    cursor: pointer;
}
.form-switch-lg .form-check-label {
    font-size: 13px;
    font-weight: 600;
    padding-left: 8px;
    padding-top: 2px;
    cursor: pointer;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-article-line"></i>
                                Faturamento Avulso (NFe)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Emita notas fiscais de saída diretamente, com preenchimento simplificado e tributação detalhada.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('nfe.index') }}" class="btn btn-light btn-sm px-3 text-dark fw-bold">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Listar Notas
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ WIZARD STEPS INDICATORS ═══ -->
                <div class="card-body p-4 pb-0">
                    <div class="wizard-steps px-md-5">
                        <div class="wizard-step active" data-step="1" id="step-btn-1">
                            1
                            <div class="wizard-label">Destinatário & Emissor</div>
                        </div>
                        <div class="wizard-step" data-step="2" id="step-btn-2">
                            2
                            <div class="wizard-label">Produtos & Tributos</div>
                        </div>
                        <div class="wizard-step" data-step="3" id="step-btn-3">
                            3
                            <div class="wizard-label">Frete & Transporte</div>
                        </div>
                        <div class="wizard-step" data-step="4" id="step-btn-4">
                            4
                            <div class="wizard-label">Opções & Financeiro</div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FORMULÁRIO PRINCIPAL ═══ -->
                <div class="card-body p-4 pt-3">
                    {!!Form::open()->post()->route('faturamento-avulso.store')->id('form-faturamento-avulso')!!}
                    
                    <input type="hidden" name="empresa_id" id="empresa_id" value="{{ $empresa->id }}">
                    <input type="hidden" name="local_id" value="{{ $caixa ? $caixa->local_id : '' }}">
                    
                    <!-- ═══ ABA 1: DESTINATÁRIO & EMISSOR ═══ -->
                    <div class="wizard-content" id="step-content-1">
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                                    <i class="ri-shield-user-line me-1"></i> Identificação e Destinatário
                                </h5>
                            </div>
                            
                            <!-- Local Emissor se houver mais de um -->
                            @if(__countLocalAtivo() > 1 && __escolheLocalidade())
                            <div class="col-md-4 col-12">
                                <label class="required">Local / Filial Emissora</label>
                                <select required id="inp-local_id" class="select2 form-select" name="local_id">
                                    @foreach(__getLocaisAtivoUsuario() as $local)
                                        <option @if($caixa && $caixa->local_id == $local->id) selected @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="col-md-3 col-6">
                                <label class="required">Finalidade de Emissão</label>
                                <select required name="finNFe" id="inp-finNFe" class="form-select select2">
                                    <option value="1">1 - NFe Normal</option>
                                    <option value="2">2 - NFe Complementar</option>
                                    <option value="3">3 - NFe de Ajuste</option>
                                    <option value="4">4 - Devolução de Mercadoria</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label class="required">Número da NFe</label>
                                <input type="number" name="numero" class="form-control" required value="{{ $numeroNfe }}">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="required">Natureza de Operação (CFOP Padrão)</label>
                                <select required name="natureza_id" id="inp-natureza_id" class="form-select select2">
                                    <option value="">Selecione...</option>
                                    @foreach($naturezas as $nat)
                                        <option @if($naturezaPadrao && $naturezaPadrao->id == $nat->id) selected @endif value="{{ $nat->id }}">{{ $nat->descricao }} (CFOP: {{ $nat->so_cfop }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Cliente -->
                            <div class="col-md-6 col-12">
                                <label class="required">Cliente (Destinatário)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <select required id="inp-cliente_id" name="cliente_id" class="form-select cliente_id">
                                            <option value="">Buscar cliente...</option>
                                        </select>
                                    </div>
                                    @can('clientes_create')
                                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal_novo_cliente" type="button" style="height: 38px;">
                                        <i class="ri-add-fill fs-18"></i>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                            
                            <!-- Dados do Cliente selecionado (Preenchidos dinamicamente) -->
                            <div class="col-12 row g-3 d-cliente-info" style="display: none;">
                                <div class="col-md-4">
                                    <label>Razão Social / Nome</label>
                                    <input type="text" id="cli-razao_social" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label>CPF / CNPJ</label>
                                    <input type="text" id="cli-cpf_cnpj" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label>Inscrição Estadual</label>
                                    <input type="text" id="cli-ie" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Cidade / UF</label>
                                    <input type="text" id="cli-cidade" class="form-control bg-light" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-actions">
                            <div></div>
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                Avançar <i class="ri-arrow-right-line ms-1 align-middle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ═══ ABA 2: PRODUTOS & TRIBUTAÇÃO ═══ -->
                    <div class="wizard-content" id="step-content-2" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="ri-box-3-line me-1"></i> Produtos Lançados
                            </h5>
                            <button type="button" class="btn btn-dark btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modal_lancamento_produto">
                                <i class="ri-add-line align-middle me-1"></i> Lançar Novo Produto
                            </button>
                        </div>
                        
                        <!-- Tabela de Produtos Adicionados -->
                        <div class="modulo-table-wrap">
                            <table class="table align-middle" id="table-produtos-avulso">
                                <thead>
                                    <tr>
                                        <th>Cód. Barras</th>
                                        <th>Produto</th>
                                        <th class="text-end">Qtd</th>
                                        <th class="text-end">Preço Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                        <th>CFOP</th>
                                        <th>NCM</th>
                                        <th>CST/CSOSN</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tr-sem-produtos">
                                        <td colspan="9" class="text-center text-muted py-4">Nenhum produto lançado ainda.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Totais Acumulados -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-4 text-end">
                                <div class="bg-light p-3 rounded border">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total de Produtos:</span>
                                        <strong id="label-total-produtos">R$ 0,00</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Desconto (-):</span>
                                        <input type="text" name="desconto" id="inp-desconto" class="form-control form-control-sm text-end money w-50 d-inline-block p-1 h-auto" value="0,00">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Acréscimo (+):</span>
                                        <input type="text" name="acrescimo" id="inp-acrescimo" class="form-control form-control-sm text-end money w-50 d-inline-block p-1 h-auto" value="0,00">
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-2">
                                        <h6 class="fw-bold mb-0">Total Geral da Nota:</h6>
                                        <h6 class="fw-bold mb-0 text-success" id="label-total-geral">R$ 0,00</h6>
                                    </div>
                                    <input type="hidden" name="valor_produtos" id="inp-valor_produtos" value="0.00">
                                    <input type="hidden" name="valor_total" id="inp-valor_total" value="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="step-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                <i class="ri-arrow-left-line me-1 align-middle"></i> Voltar
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                Avançar <i class="ri-arrow-right-line ms-1 align-middle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ═══ ABA 3: FRETE & TRANSPORTE ═══ -->
                    <div class="wizard-content" id="step-content-3" style="display: none;">
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                                    <i class="ri-truck-line me-1"></i> Configurações de Frete
                                </h5>
                            </div>
                            
                            <div class="col-md-6 col-12">
                                <label>Transportadora</label>
                                <select name="transportadora_id" class="form-select select2">
                                    <option value="">Selecione...</option>
                                    @foreach($transportadoras as $transp)
                                        <option value="{{ $transp->id }}">{{ $transp->razao_social }} ({{ $transp->cnpj_cpf }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 col-6">
                                <label>Placa do Veículo</label>
                                <input type="text" name="placa" class="form-control" placeholder="Ex: ABC1D23">
                            </div>
                            
                            <div class="col-md-3 col-6">
                                <label>UF Veículo</label>
                                <select name="uf" class="form-select select2">
                                    <option value="">Selecione...</option>
                                    @foreach($cidades->pluck('uf')->unique() as $uf)
                                        <option value="{{ $uf }}">{{ $uf }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 col-6">
                                <label>Valor do Frete</label>
                                <input type="text" name="valor_frete" id="inp-valor_frete" class="form-control money" value="0,00">
                            </div>

                            <div class="col-md-3 col-6">
                                <label>Quantidade de Volumes</label>
                                <input type="text" name="qtd_volumes" class="form-control" placeholder="Ex: 5">
                            </div>
                            
                            <div class="col-md-2 col-4">
                                <label>Espécie</label>
                                <input type="text" name="especie" class="form-control" placeholder="Ex: CAIXA">
                            </div>
                            
                            <div class="col-md-2 col-4">
                                <label>Peso Líquido (kg)</label>
                                <input type="text" name="peso_liquido" class="form-control money" value="0,00">
                            </div>

                            <div class="col-md-2 col-4">
                                <label>Peso Bruto (kg)</label>
                                <input type="text" name="peso_bruto" class="form-control money" value="0,00">
                            </div>
                        </div>

                        <div class="step-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                <i class="ri-arrow-left-line me-1 align-middle"></i> Voltar
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                                Avançar <i class="ri-arrow-right-line ms-1 align-middle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ═══ ABA 4: OPÇÕES & FINANCEIRO ═══ -->
                    <div class="wizard-content" id="step-content-4" style="display: none;">
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                                    <i class="ri-settings-4-line me-1"></i> Opções de Processamento
                                </h5>
                            </div>
                            
                            <!-- Switches Integrativos -->
                            <div class="col-md-6 col-12 d-flex flex-column gap-3">
                                <div class="form-check form-switch form-switch-lg border p-3 rounded-3 bg-light bg-opacity-50">
                                    <input class="form-check-input ms-0" type="checkbox" name="baixar_estoque" id="switch-baixar-estoque" value="1" checked>
                                    <label class="form-check-label" for="switch-baixar-estoque">
                                        Deduzir Estoque Automaticamente
                                        <span class="d-block text-muted fw-normal fs-11 mt-1">Se marcado, o sistema abaterá as quantidades lançadas do saldo atual do estoque físico de cada item.</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12 d-flex flex-column gap-3">
                                <div class="form-check form-switch form-switch-lg border p-3 rounded-3 bg-light bg-opacity-50">
                                    <input class="form-check-input ms-0" type="checkbox" name="gerar_financeiro" id="switch-gerar-financeiro" value="1">
                                    <label class="form-check-label" for="switch-gerar-financeiro">
                                        Gerar Contas a Receber (Financeiro)
                                        <span class="d-block text-muted fw-normal fs-11 mt-1">Se marcado, habilitará a geração de parcelas e faturas que entrarão no seu fluxo de caixa de Contas a Receber.</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Painel do Financeiro (Oculto/Exibido dinamicamente) -->
                            <div class="col-12 mt-4" id="panel-financeiro" style="display: none;">
                                <div class="bg-white p-3 border rounded-3">
                                    <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">
                                        <i class="ri-bank-card-line me-1"></i> Geração de Contas e Faturas
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-3 col-6">
                                            <label>Qtd. de Parcelas</label>
                                            <input type="number" id="fin-parcelas" class="form-control" value="1" min="1">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label>Intervalo (Dias)</label>
                                            <input type="number" id="fin-intervalo" class="form-control" value="30" min="1">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label>Primeiro Vencimento</label>
                                            <input type="date" id="fin-primeiro_vencimento" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-3 col-12 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary w-100" id="btn-gerar-faturas-avulso">
                                                <i class="ri-refresh-line me-1"></i> Gerar Parcelas
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="modulo-table-wrap mt-3">
                                        <table class="table align-middle" id="table-faturas-avulso">
                                            <thead>
                                                <tr>
                                                    <th>Número da Parcela</th>
                                                    <th>Tipo de Pagamento</th>
                                                    <th>Vencimento</th>
                                                    <th class="text-end">Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Gerado dinamicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dados adicionais/Observação -->
                            <div class="col-12 mt-4">
                                <label>Observações / Informações Adicionais ao Fisco</label>
                                <textarea name="observacao" class="form-control" rows="3" placeholder="Mensagens personalizadas fiscais e corporativas..."></textarea>
                            </div>
                        </div>

                        <div class="step-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                <i class="ri-arrow-left-line me-1 align-middle"></i> Voltar
                            </button>
                            <button type="submit" class="btn btn-success" id="btn-submit-faturamento">
                                <i class="ri-check-double-line me-1 align-middle"></i> Confirmar e Salvar NFe
                            </button>
                        </div>
                    </div>
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Lançar Produto -->
@include('faturamento_avulso._modal_lancamento_produto')

<!-- Modal Novo Cliente -->
@include('modals._novo_cliente')

@endsection

@section('js')
<script src="/js/novo_cliente.js"></script>
<script src="/js/faturamento_avulso.js"></script>
@endsection
