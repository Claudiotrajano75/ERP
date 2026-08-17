@extends('layouts.app', ['title' => 'Atualização por Arquivo ZIP'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05) !important;
    }

    .card-body {
        padding: 28px !important;
    }

    /* Cabeçalho de Gradiente Premium */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    /* Dropzone Customizado */
    .file-drop-area {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .file-drop-area:hover {
        border-color: #4f46e5;
        background: #f5f6fe;
    }

    .file-drop-area input[type=file] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Versão Box */
    .version-badge-box {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Botões */
    .btn {
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-sm {
        padding: 6px 14px !important;
        font-size: 12px !important;
        border-radius: 8px !important;
    }

    .btn-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
    }

    .btn-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
    }

    .btn-primary {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
    }

    .btn-primary:hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
    }

    .btn-warning {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
        color: #fff !important;
    }

    .btn-warning:hover {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2) !important;
    }

    .btn-danger {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #fff !important;
    }

    .btn-danger:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO COM GRADIENTE PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="modulo-title text-white">
                                <i class="ri-upload-cloud-line"></i> Atualização por Pacote ZIP
                            </h4>
                            <p class="modulo-subtitle">
                                Instalação e aplicação de patches de atualização do código-fonte e migrações do ERP.
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('update-file.log') }}" class="btn btn-light btn-sm text-dark">
                                <i class="ri-history-line me-1"></i> Histórico de Versões
                            </a>
                            <a href="{{ route('clear') }}" class="btn btn-warning btn-sm text-white" title="Limpa todo o cache de views, rotas e config">
                                <i class="ri-refresh-line me-1"></i> Limpar Cache Servidor
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- ═══ CARD DE VERSÃO ATUAL ═══ -->
                    <div class="version-badge-box mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-3 fs-3">
                                    <i class="ri-git-branch-line"></i>
                                </span>
                            </div>
                            <div>
                                <span class="text-muted fs-12 d-block">Versão Atual do Sistema</span>
                                <h4 class="mb-0 fw-bold text-dark font-monospace">
                                    {{ $update ? $update->versao : '1.0.0 (Base)' }}
                                </h4>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-success-subtle text-success border px-3 py-2 fs-12">
                                <i class="ri-checkbox-circle-fill me-1"></i> Sistema Operacional
                            </span>
                        </div>
                    </div>

                    <!-- ═══ ALERTA DE BACKUP ═══ -->
                    <div class="alert alert-danger d-flex align-items-center gap-3 mb-4 rounded-3 border-0 shadow-sm p-3">
                        <i class="ri-shield-alert-fill fs-24 flex-shrink-0"></i>
                        <div>
                            <strong class="d-block">Recomendação Importante:</strong>
                            <span class="fs-13">
                                Certifique-se de ter realizado um backup dos arquivos e do banco de dados antes de executar uma nova atualização de diretórios.
                            </span>
                        </div>
                    </div>

                    <!-- ═══ FORMULÁRIO DE ENVIO ═══ -->
                    <form method="post" action="{{ route('update-file.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8">
                                <div class="file-drop-area mb-3" id="dropArea">
                                    <i class="ri-file-zip-line fs-48 text-primary mb-2"></i>
                                    <h5 class="fw-bold text-dark mb-1">Selecione ou arraste o pacote (.ZIP) de atualização</h5>
                                    <p class="text-muted fs-12 mb-0">Formatos suportados: Arquivo compactado contendo pastas estruturadas do ERP</p>
                                    <input required accept=".zip" name="file" type="file" id="zipFileInput" onchange="showFileName(this)">
                                </div>

                                <div id="fileSelectedName" class="badge bg-success-subtle text-success p-2 w-100 fs-13 d-none mb-4">
                                    <i class="ri-check-double-line me-1"></i> Arquivo selecionado: <strong id="fileNameText"></strong>
                                </div>

                                <div class="text-center mt-3">
                                    <button class="btn btn-success px-5 py-2 btn-execute" type="submit">
                                        <i class="ri-play-circle-line me-1 fs-16"></i> Executar Atualização do Sistema
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function showFileName(input) {
        if (input.files && input.files[0]) {
            var fileName = input.files[0].name;
            document.getElementById('fileNameText').innerText = fileName;
            document.getElementById('fileSelectedName').classList.remove('d-none');
        }
    }

    $('.btn-execute').click(() => {
        if (document.getElementById('zipFileInput').files.length > 0) {
            let $body = $("body");
            $body.addClass("loading");
        }
    });
</script>
@endsection