@extends('layouts.app', ['title' => 'Importar XML NFCe'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
/* ─── Upload Zone ─── */
.upload-zone { border: 2px dashed #c5cae9; border-radius: 16px; padding: 52px 24px; text-align: center; background: #fafbff; cursor: pointer; transition: all 0.25s ease; }
.upload-zone:hover, .upload-zone.drag-over { border-color: #302b63; background: #f0f0ff; }
.upload-zone .upload-icon { font-size: 56px; color: #c5cae9; display: block; margin-bottom: 14px; transition: color 0.2s; }
.upload-zone:hover .upload-icon { color: #302b63; }
.upload-zone .upload-title { font-size: 17px; font-weight: 700; color: #302b63; margin-bottom: 6px; }
.upload-zone .upload-sub { font-size: 12px; color: #9e9eb8; margin-bottom: 20px; }
/* ─── Steps ─── */
.step-item { display: flex; align-items: flex-start; gap: 14px; padding: 14px 16px; border-radius: 10px; background: #f8f9fc; border: 1px solid #eef0f5; }
.step-num { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg,#302b63,#24243e); color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.step-item p { margin: 0; font-size: 12px; color: #5a5a7a; }
.step-item strong { font-size: 13px; color: #302b63; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-zip-line"></i>
                                Importar XML para NFCe
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Importe arquivos XML de notas fiscais de consumidor eletrônica a partir de um arquivo <strong>.zip</strong>.</p>
                        </div>
                        <div>
                            <a href="{{ route('nfce.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-double-fill align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <form id="form-import" method="post" action="{{ route('nfce.import-zip-store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Zona de Upload -->
                        <label for="file" class="w-100 mb-4">
                            <div class="upload-zone" id="upload-drop">
                                <i class="ri-file-zip-line upload-icon" id="upload-icon"></i>
                                <div class="upload-title" id="upload-title">Arraste ou clique para selecionar</div>
                                <div class="upload-sub">Formato aceito: <strong>.ZIP</strong> contendo os XMLs das NFCe</div>
                                <span class="btn btn-primary px-5" id="upload-btn">
                                    <i class="ri-folder-open-line me-1"></i> Escolher Arquivo
                                </span>
                            </div>
                            <input accept=".zip" name="file" type="file" id="file" style="opacity:0;position:absolute;width:1px;height:1px;">
                        </label>

                        <!-- Passos de Instrução -->
                        <div class="d-flex flex-column gap-2">
                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div>
                                    <strong>Prepare o arquivo ZIP</strong>
                                    <p>Certifique-se de que o arquivo <code>.zip</code> contém apenas arquivos <code>.xml</code> válidos de NFCe.</p>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div>
                                    <strong>Selecione e aguarde</strong>
                                    <p>Após selecionar o arquivo, o upload e processamento iniciarão automaticamente. Não feche a página.</p>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div>
                                    <strong>Verifique os resultados</strong>
                                    <p>Os XMLs importados estarão disponíveis na listagem de <strong>NFCe</strong> após o processamento.</p>
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
    $('#file').change(function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('#upload-icon').removeClass('ri-file-zip-line').addClass('ri-checkbox-circle-line').css('color', '#2e7d32');
            $('#upload-title').text(fileName).css('color', '#2e7d32');
            $('#upload-btn').removeClass('btn-primary').addClass('btn-success').html('<i class="ri-loader-4-line me-1"></i> Processando...');
            $('#upload-drop').css({ 'border-color': '#43e97b', 'background': '#f0fff4' });
        }
        $('#form-import').submit();
        $("body").addClass("loading");
    });

    // Drag & Drop
    var dropZone = document.getElementById('upload-drop');
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        dropZone.addEventListener('dragleave', function() {
            dropZone.classList.remove('drag-over');
        });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            var files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('file').files = files;
                $('#file').trigger('change');
            }
        });
    }
</script>
@endsection
