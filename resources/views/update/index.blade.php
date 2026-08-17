@extends('layouts.app', ['title' => 'Atualização SQL'])

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
        padding: 24px !important;
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

    /* Cards de Ferramentas */
    .tool-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .tool-card-header {
        padding: 16px 20px;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tool-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    .tool-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .tool-card-footer {
        padding: 16px 20px;
        background-color: #fff;
        border-top: 1px solid #f1f5f9;
    }

    /* Input de Arquivo Customizado */
    .file-drop-area {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px 20px;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
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

    /* Editor SQL Monospace */
    .sql-editor {
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
        background-color: #0f172a !important;
        color: #38bdf8 !important;
        border: 1px solid #1e293b !important;
        border-radius: 10px !important;
        padding: 14px !important;
        resize: vertical;
    }

    .sql-editor:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15) !important;
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
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">

            <!-- ═══ CABEÇALHO COM GRADIENTE PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-database-2-line"></i> Atualização & Comandos SQL
                        </h4>
                        <p class="modulo-subtitle">
                            Execute migrações estruturais, scripts DDL/DML ou correções manuais no banco de dados do sistema.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- ═══ ALERTA DE SEGURANÇA ═══ -->
                <div class="alert alert-danger d-flex align-items-center gap-3 mb-4 rounded-3 border-0 shadow-sm p-3">
                    <i class="ri-alert-fill fs-24 flex-shrink-0"></i>
                    <div>
                        <strong class="d-block">Atenção Crítica:</strong>
                        <span class="fs-13">
                            Comandos executados nesta área impactam diretamente as tabelas e registros de todas as empresas. Certifique-se de que os scripts SQL foram testados previamente.
                        </span>
                    </div>
                </div>

                <!-- ═══ GRID DE FERRAMENTAS ═══ -->
                <div class="row g-4">

                    <!-- 1. IMPORTAR ARQUIVO .SQL -->
                    <div class="col-12 col-lg-5">
                        <form method="post" action="/update-sql/sql" enctype="multipart/form-data">
                            @csrf
                            <div class="tool-card">
                                <div class="tool-card-header">
                                    <i class="ri-file-code-line fs-20 text-success"></i>
                                    <h5>Importar Arquivo .SQL</h5>
                                </div>
                                <div class="tool-card-body">
                                    <p class="text-muted fs-12 mb-3">
                                        Envie um arquivo contendo as instruções SQL separadas por ponto e vírgula (<code>;</code>).
                                    </p>

                                    <div class="file-drop-area mb-3" id="dropArea">
                                        <i class="ri-upload-cloud-2-line fs-36 text-primary mb-2"></i>
                                        <span class="fw-bold text-dark fs-13">Clique ou arraste o arquivo .SQL</span>
                                        <span class="text-muted fs-11 mt-1">Formato aceito: .sql</span>
                                        <input required accept=".sql" name="file" type="file" id="sqlFileInput" onchange="showFileName(this)">
                                    </div>

                                    <div id="fileSelectedName" class="badge bg-success-subtle text-success p-2 w-100 fs-12 d-none">
                                        <i class="ri-check-line me-1"></i> <span id="fileNameText"></span>
                                    </div>
                                </div>
                                <div class="tool-card-footer">
                                    <button class="btn btn-success w-100" type="submit">
                                        <i class="ri-play-circle-line me-1"></i> Executar Arquivo SQL
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 2. CONSOLE SQL MANUAL -->
                    <div class="col-12 col-lg-7">
                        <form method="post" action="/update-sql/run-sql">
                            @csrf
                            <div class="tool-card">
                                <div class="tool-card-header">
                                    <i class="ri-terminal-box-line fs-20 text-primary"></i>
                                    <h5>Console SQL Manual</h5>
                                </div>
                                <div class="tool-card-body">
                                    <p class="text-muted fs-12 mb-2">
                                        Digite ou cole os comandos SQL abaixo. Separe múltiplas instruções com ponto e vírgula (<code>;</code>).
                                    </p>

                                    <div class="mb-2">
                                        <textarea name="sql" rows="7" class="form-control sql-editor" placeholder="ALTER TABLE empresas ADD COLUMN observacao VARCHAR(255) NULL;&#10;CREATE INDEX idx_data ON vendas (data);"></textarea>
                                    </div>
                                    <small class="text-muted fs-11 d-flex align-items-center gap-1">
                                        <i class="ri-information-line text-info"></i> Comandos suportados: ALTER, CREATE, INSERT, UPDATE, DROP, etc.
                                    </small>
                                </div>
                                <div class="tool-card-footer">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="ri-terminal-window-line me-1"></i> Executar Comandos SQL
                                    </button>
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
    function showFileName(input) {
        if (input.files && input.files[0]) {
            var fileName = input.files[0].name;
            document.getElementById('fileNameText').innerText = fileName;
            document.getElementById('fileSelectedName').classList.remove('d-none');
        }
    }
</script>
@endsection
