@extends('layouts.app', ['title' => 'Consultar Novos Documentos (DF-e)'])

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
                                <i class="ri-refresh-line"></i>
                                Consultar Novos Documentos (DF-e)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Execute uma varredura em tempo real no webservice do SEFAZ nacional para buscar novas emissões contra o seu CNPJ.</p>
                        </div>
                        <div>
                            <a href="{{ route('manifesto.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO PAINEL -->
                <div class="card-body p-4 text-center">
                    
                    <div class="py-5" id="aguarde">
                        <div class="spinner-border text-primary fs-20 mb-3" role="status" style="width: 3.5rem; height: 3.5rem;">
                            <span class="visually-hidden">Consultando SEFAZ...</span>
                        </div>
                        <h4 class="text-primary fw-bold">Consultando novos documentos no SEFAZ, por favor aguarde...</h4>
                        <p class="text-muted fs-13 mb-0">Essa consulta pode demorar alguns segundos conforme o volume de notas e a velocidade do webservice da SEFAZ.</p>
                    </div>

                    <div id="sem-resultado" class="py-5" style="display: none;">
                        <i class="ri-checkbox-circle-line text-success fs-48 mb-2 d-block" style="font-size: 48px;"></i>
                        <h4 class="text-success fw-bold">Nenhum novo documento pendente</h4>
                        <p class="text-muted fs-13 mb-3">Todas as notas fiscais emitidas recentemente contra o seu CNPJ já foram importadas e indexadas no painel.</p>
                        <a href="{{ route('manifesto.index') }}" class="dash-btn dash-btn-primary px-4">
                            <i class="ri-arrow-left-line"></i> Ir para Lista de Documentos
                        </a>
                    </div>

                    <!-- Tabela de Resultados Dinâmicos -->
                    <div class="col-xl-12 text-start" id="table" style="display: none;">
                        <div class="alert alert-success border-0 shadow-sm p-3 mb-4 d-flex align-items-center" style="background: #ecfdf5; border-radius: 12px;">
                            <i class="ri-checkbox-circle-line me-2 fs-20 text-success"></i>
                            <span class="text-success fw-semibold fs-13">Novos documentos fiscais localizados com sucesso! Confira abaixo.</span>
                        </div>
                        
                        <div class="tb-wrap mb-4">
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

                        <div class="text-end">
                            <a href="{{ route('manifesto.index') }}" class="dash-btn dash-btn-primary px-4">
                                <i class="ri-arrow-right-line me-1"></i> Ir para o Painel de Manifesto
                            </a>
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
