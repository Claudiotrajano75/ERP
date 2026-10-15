@extends('layouts.app', ['title' => 'Importação de XML'])

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
                                Confirmar Dados do XML Importado
                            </h4>
                            @isset($dadosXml)
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Chave da Nota: <strong class="text-white">{{ $dadosXml['chave'] }}</strong></p>
                            @else
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Valide a associação de produtos e tributação da nota importada antes de salvar.</p>
                            @endif
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @if(__countLocalAtivo() > 1 && isset($caixa))
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle p-2 fs-12">
                                <i class="ri-briefcase-line align-middle me-1"></i> Local / Filial: {{ $caixa->localizacao ? $caixa->localizacao->descricao : '' }}
                            </span>
                            @endif
                            <a href="{{ route('compras.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('compras.finish-xml')
                    ->multipart()
                    ->id('form-nfe')
                    ->attrs(['novalidate' => 'novalidate'])
                    !!}
                    
                    <div class="pl-lg-2">
                        @include('compras._forms_xml')
                    </div>
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Modal de Confirmação e Lembretes da Importação de XML -->
<div class="modal fade" id="modal-confirmar-importacao" tabindex="-1" aria-labelledby="modalConfirmarImportacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white fs-16" id="modalConfirmarImportacaoLabel">
                    <i class="ri-checkbox-circle-line text-success fs-20"></i>
                    Confirmar Entrada de Nota Fiscal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 text-center">
                    <span class="badge bg-primary-subtle text-primary p-2 px-3 fs-13 rounded-pill">
                        Nota Fiscal de Compra Nº <strong>{{ $dadosXml['nNf'] ?? '' }}</strong>
                    </span>
                    <h5 class="mt-2 mb-1 fw-bold text-dark modal-fornecedor-nome">{{ $fornecedor->razao_social ?? 'Fornecedor' }}</h5>
                    <p class="text-muted fs-13 mb-0">Total dos Produtos: <strong class="text-success fs-15 modal-total-nota">R$ {{ __moeda($dadosXml['vProd'] ?? 0) }}</strong></p>
                </div>

                <div class="card bg-light border-0 p-3 mb-3" style="border-radius: 10px;">
                    <h6 class="fw-bold fs-13 text-dark mb-2"><i class="ri-list-check-2 text-primary me-1"></i> Resumo das Ações no Sistema:</h6>
                    <ul class="list-unstyled mb-0 fs-13 text-secondary">
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ri-check-line text-success fw-bold"></i>
                            <span><strong>Fornecedor:</strong> Atualiza ou cadastra o emitente da nota.</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ri-check-line text-success fw-bold"></i>
                            <span><strong>Produtos:</strong> Cadastra novos produtos e vincula os já existentes.</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <i class="ri-check-line text-success fw-bold"></i>
                            <span><strong>Estoque:</strong> Dá entrada das quantidades com o fator de conversão.</span>
                        </li>
                    </ul>
                </div>

                <div class="p-3 border rounded-3 bg-white" style="border-color: #cbd5e1 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-check-label fw-bold text-dark fs-14 d-block" for="modal_check_gerar_contas">
                                <i class="ri-money-dollar-circle-line text-warning align-middle me-1"></i> Gerar no Contas a Pagar?
                            </label>
                            <small class="text-muted d-block mt-1">Lança as faturas/duplicatas no financeiro</small>
                        </div>
                        <div class="form-check form-switch fs-18">
                            <input class="form-check-input" type="checkbox" id="modal_check_gerar_contas" checked style="cursor: pointer;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Revisar Dados</button>
                <button type="button" class="btn btn-success px-4 fw-bold" id="btn-confirmar-submit-xml">
                    <i class="ri-check-double-line me-1"></i> Confirmar e Salvar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/nfe.js"></script>
<script src="/js/import_xml.js"></script>
<script type="text/javascript">
    // Ao clicar em Salvar, valida os campos e abre o modal de confirmação e lembretes
    $('.btn-salvar-nfe').off('click').on('click', function (e) {
        e.preventDefault();
        if (addClassRequired()) {
            let $selectContaPagar = $('#inp-gerar_conta_pagar');
            let gerarConta = $selectContaPagar.length && $selectContaPagar.val() == '0' ? false : true;
            $('#modal_check_gerar_contas').prop('checked', gerarConta);
            $('#modal-confirmar-importacao').modal('show');
        }
    });

    // Confirmação final no modal
    $('#btn-confirmar-submit-xml').on('click', function () {
        let gerarContas = $('#modal_check_gerar_contas').is(':checked') ? 1 : 0;
        $('#inp-gerar_conta_pagar').val(gerarContas);

        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i> Gravando Entrada...');
        $('#form-nfe').submit();
    });
</script>
@endsection
