@extends('layouts.app', ['title' => 'Importar Fornecedores'])

@section('css')
<style type="text/css">
.btn-file {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: pointer;
    display: block;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-upload-line"></i>
                                Importação de Fornecedores via Planilha
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Cadastre fornecedores em lote de forma rápida e automatizada utilizando uma planilha Excel.</p>
                        </div>
                        <div>
                            <a href="{{ route('fornecedores.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO ═══ -->
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 shadow-sm p-3 mb-4" style="background: #f0f4ff; border-radius: 12px;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-information-line fs-18 text-primary mt-0.5"></i>
                            <div class="fs-13 text-dark">
                                <strong>Importante:</strong> Os campos assinalados com <strong class="text-danger">*</strong> são obrigatórios no preenchimento da planilha de importação. Utilize o modelo padrão para evitar divergências.
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-12">
                            <div class="p-3 border bg-light rounded-3 h-100 fs-13">
                                <h5 class="fs-12 text-muted text-uppercase fw-bold mb-3 border-bottom pb-2">
                                    <i class="ri-asterisk text-danger me-1"></i> Parâmetros Obrigatórios
                                </h5>
                                <ul class="list-unstyled mb-0" style="line-height: 1.9;">
                                    <li><strong class="text-dark">RAZÃO SOCIAL</strong><span class="text-danger">*</span> (Texto)</li>
                                    <li><strong class="text-dark">CPF/CNPJ</strong><span class="text-danger">*</span> (Numérico)</li>
                                    <li><strong class="text-dark">RUA</strong><span class="text-danger">*</span> (Texto)</li>
                                    <li><strong class="text-dark">NÚMERO</strong><span class="text-danger">*</span> (Texto)</li>
                                    <li><strong class="text-dark">BAIRRO</strong><span class="text-danger">*</span> (Texto)</li>
                                    <li><strong class="text-dark">CIDADE</strong><span class="text-danger">*</span> (IBGE / Nome da Cidade)</li>
                                    <li><strong class="text-dark">UF</strong><span class="text-danger">*</span> (Sigla de 2 dígitos, ex: SP)</li>
                                    <li><strong class="text-dark">CEP</strong><span class="text-danger">*</span> (Numérico)</li>
                                    <li><strong class="text-dark">TELEFONE</strong><span class="text-danger">*</span> (Numérico)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="p-3 border bg-light rounded-3 h-100 fs-13">
                                <h5 class="fs-12 text-muted text-uppercase fw-bold mb-3 border-bottom pb-2">
                                    <i class="ri-add-circle-line text-primary me-1"></i> Campos Adicionais / Opcionais
                                </h5>
                                <ul class="list-unstyled mb-0" style="line-height: 1.9;">
                                    <li><strong class="text-dark">NOME FANTASIA</strong> (Texto)</li>
                                    <li><strong class="text-dark">IE (Inscrição Estadual)</strong> (Numérico)</li>
                                    <li><strong class="text-dark">CONTRIBUINTE</strong> (0 = Não, 1 = Sim)</li>
                                    <li><strong class="text-dark">CONSUMIDOR FINAL</strong> (0 = Não, 1 = Sim)</li>
                                    <li><strong class="text-dark">COMPLEMENTO</strong> (Texto)</li>
                                    <li><strong class="text-dark">EMAIL</strong> (E-mail válido)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botão de Download do Modelo -->
                    <div class="mb-4">
                        <a href="{{ route('fornecedores.import-download') }}" class="dash-btn dash-btn-light">
                            <i class="ri-file-download-line text-primary"></i> Baixar Planilha Modelo (.XLSX)
                        </a>
                    </div>

                    <!-- Envio do arquivo -->
                    <div class="border-top pt-4">
                        <form id="form-import" method="post" action="{{ route('fornecedores.import-store') }}" enctype="multipart/form-data" class="m-0">
                            @csrf
                            <h5 class="fs-14 fw-bold text-dark mb-3">Selecione a planilha preenchida para iniciar o envio</h5>
                            
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <span class="dash-btn dash-btn-primary btn-file px-4 py-2">
                                        <i class="ri-file-search-line me-1"></i> Procurar e Enviar Planilha
                                        <input accept=".xls, .xlsx" name="file" type="file" id="file">
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="text-muted fs-12 ms-2">Formatos suportados: .XLS ou .XLSX</span>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('#file').change(function() {
        $('#form-import').submit();
        $("body").addClass("loading");
    });
</script>
@endsection
