@extends('layouts.app', ['title' => 'Importar Fornecedores'])
@section('css')
<style type="text/css">
    .btn-file {
        position: relative;
        overflow: hidden;
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
        cursor: inherit;
        display: block;
    }
</style>
@endsection
@section('content')

<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark">
                <!-- Cabeçalho -->
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-file-upload-line me-2 text-primary fs-22"></i>
                                Importação de Fornecedores via Planilha Excel
                            </h4>
                            <p class="text-muted mb-0 fs-13">Cadastre fornecedores em lote carregando arquivos de planilha no formato compatível.</p>
                        </div>
                        <div>
                            <a href="{{ route('fornecedores.index') }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Corpo de Regras e Download -->
                <div class="card-body p-4">
                    <div class="alert alert-info border-info-subtle bg-info-subtle text-info p-3 mb-4">
                        <i class="ri-information-line me-1 fs-15 align-middle"></i>
                        Atenção: Os campos assinalados com <strong class="text-danger">*</strong> são obrigatórios no preenchimento da planilha de importação.
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-12">
                            <div class="p-3 border bg-light rounded h-100 fs-13">
                                <h5 class="fs-12 text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2">Parâmetros Obrigatórios</h5>
                                <ul class="list-unstyled mb-0" style="line-height: 1.8;">
                                    <li><strong class="text-primary">RAZÃO SOCIAL</strong><span class="text-danger">*</span> (Tipo texto)</li>
                                    <li><strong class="text-primary">CPF/CNPJ</strong><span class="text-danger">*</span> (Tipo numérico limpo)</li>
                                    <li><strong class="text-primary">RUA</strong><span class="text-danger">*</span> (Tipo texto)</li>
                                    <li><strong class="text-primary">NÚMERO</strong><span class="text-danger">*</span> (Tipo texto)</li>
                                    <li><strong class="text-primary">BAIRRO</strong><span class="text-danger">*</span> (Tipo texto)</li>
                                    <li><strong class="text-primary">CIDADE</strong><span class="text-danger">*</span> (Tipo texto - IBGE/Nome)</li>
                                    <li><strong class="text-primary">UF</strong><span class="text-danger">*</span> (Tipo texto - Ex: SP)</li>
                                    <li><strong class="text-primary">CEP</strong><span class="text-danger">*</span> (Tipo numérico limpo)</li>
                                    <li><strong class="text-primary">TELEFONE</strong><span class="text-danger">*</span> (Tipo numérico limpo)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="p-3 border bg-light rounded h-100 fs-13">
                                <h5 class="fs-12 text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2">Campos Adicionais / Opcionais</h5>
                                <ul class="list-unstyled mb-0" style="line-height: 1.8;">
                                    <li><strong class="text-primary">NOME FANTASIA</strong> (Tipo texto)</li>
                                    <li><strong class="text-primary">IE (Inscrição Estadual)</strong> (Tipo numérico)</li>
                                    <li><strong class="text-primary">CONTRIBUINTE</strong> (Tipo binário: 0 para Não, 1 para Sim)</li>
                                    <li><strong class="text-primary">CONSUMIDOR FINAL</strong> (Tipo binário: 0 para Não, 1 para Sim)</li>
                                    <li><strong class="text-primary">COMPLEMENTO</strong> (Tipo texto)</li>
                                    <li><strong class="text-primary">EMAIL</strong> (Tipo texto)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botão de Download do Modelo -->
                    <div class="mb-4">
                        <a href="{{ route('fornecedores.import-download') }}" class="btn btn-primary px-4">
                            <i class="ri-file-download-line align-middle me-1"></i> Baixar Planilha de Exemplo (Modelo)
                        </a>
                    </div>

                    <!-- Envio do arquivo -->
                    <div class="border-top pt-4">
                        <form id="form-import" method="post" action="{{ route('fornecedores.import-store') }}" enctype="multipart/form-data" class="m-0">
                            @csrf
                            <h5 class="fs-14 fw-bold text-dark mb-3">Selecione o arquivo preenchido para envio</h5>
                            
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <span class="btn btn-success btn-file px-4 py-2">
                                        <i class="ri-file-search-line align-middle me-1"></i> Procurar Planilha
                                        <input accept=".xls, .xlsx" name="file" type="file" id="file">
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="text-muted fs-12 ms-2">Formatos aceitos: .xls / .xlsx</span>
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
