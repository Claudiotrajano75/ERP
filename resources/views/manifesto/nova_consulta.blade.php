@extends('layouts.app', ['title' => 'Nova Consulta'])

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

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
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
                                <i class="ri-refresh-line"></i>
                                Consultar Novos Documentos (DF-e)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Execute uma varredura em tempo real no banco do SEFAZ nacional para buscar novas emissões contra sua empresa.</p>
                        </div>
                        <div>
                            <a href="{{ route('manifesto.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO PAINEL -->
                <div class="card-body p-4 text-center">
                    
                    <div class="py-5" id="aguarde">
                        <div class="spinner-border text-primary fs-20 mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Consultando SEFAZ...</span>
                        </div>
                        <h5 class="text-primary fw-bold">Consultando novos documentos no SEFAZ, por favor aguarde...</h5>
                        <p class="text-muted fs-12 mb-0">Essa consulta pode demorar alguns segundos conforme o volume de notas e a velocidade do webservice.</p>
                    </div>

                    <div id="sem-resultado" class="py-5" style="display: none;">
                        <i class="ri-error-warning-line text-danger fs-48 mb-2"></i>
                        <h4 class="text-danger fw-bold">Nenhum novo documento localizado</h4>
                        <p class="text-muted fs-13 mb-0">Todas as notas emitidas recentemente contra o seu CNPJ já foram importadas e indexadas no painel.</p>
                    </div>

                    <!-- Tabela de Resultados Dinâmicos -->
                    <div class="col-xl-12 text-start" id="table" style="display: none;">
                        <div class="alert alert-success border-success-subtle bg-success-subtle text-success p-3 mb-4 d-flex align-items-center">
                            <i class="ri-checkbox-circle-line me-2 fs-20"></i>
                            <span>Novos documentos fiscais localizados com sucesso! Confira abaixo.</span>
                        </div>
                        
                        <div class="modulo-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>Razão Social Emitente</th>
                                            <th>CNPJ / CPF</th>
                                            <th style="width: 200px;">Valor Total NFe</th>
                                            <th>Chave de Acesso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Preenchido via DFe.js -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/dfe.js"></script>
@endsection
