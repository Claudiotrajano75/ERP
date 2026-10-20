<div class="modal fade" id="modal-importar-mdfe" tabindex="-1" aria-labelledby="modal-importar-mdfe-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 820px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            {{-- Header --}}
            <div class="modal-header bg-white border-bottom py-3 px-4" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold text-dark fs-16" id="modal-importar-mdfe-title">Importar MDFe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4 bg-white">
                <form id="form-importar-mdfe" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ request()->empresa_id ?? '' }}">

                    {{-- Área de Upload com borda tracejada conforme o design --}}
                    <div class="d-flex justify-content-center">
                        <div class="upload-dropzone-mdfe w-100 d-flex flex-column align-items-center justify-content-center py-5 px-3"
                             id="dropzone-mdfe"
                             style="border: 1px dashed #cbd5e1; border-radius: 8px; cursor: pointer; transition: all .2s ease; background: #ffffff;">

                            {{-- Ícone de documento com código </> --}}
                            <div class="mb-3">
                                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#1e293b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <polyline points="9.5 13 8 15 9.5 17"></polyline>
                                    <polyline points="14.5 13 16 15 14.5 17"></polyline>
                                </svg>
                            </div>

                            {{-- Texto de instrução --}}
                            <h5 class="fw-bold mb-3 text-center" style="font-size: 15px; color: #1e293b;">
                                Arraste e solte o arquivo ou selecione a opção
                            </h5>

                            {{-- Botão Laranja "Selecionar arquivo" --}}
                            <button type="button" class="btn text-white fw-bold px-4 py-2" id="btn-selecionar-mdfe"
                                    style="background-color: #f05a28; border-color: #f05a28; border-radius: 6px; font-size: 13.5px; box-shadow: 0 2px 6px rgba(240,90,40,0.25);">
                                Selecionar arquivo
                            </button>

                            {{-- Legenda dos formatos aceitos --}}
                            <div class="text-muted mt-2" style="font-size: 12px; color: #94a3b8 !important;">
                                Arquivo deve ter a extensão .XML ou .ZIP
                            </div>

                            {{-- Card de feedback de arquivo selecionado --}}
                            <div id="mdfe-file-info" class="mt-3 d-none align-items-center gap-2 p-2 px-3 rounded-2"
                                 style="background: #f8fafc; border: 1px solid #e2e8f0; max-width: 90%;">
                                <i class="ri-file-text-line fs-18 text-primary"></i>
                                <span id="mdfe-file-name" class="fs-13 fw-semibold text-dark text-truncate" style="max-width: 380px;"></span>
                                <span id="mdfe-file-size" class="fs-12 text-muted"></span>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" id="btn-remover-arquivo-mdfe" title="Remover">
                                    <i class="ri-close-line fs-16"></i>
                                </button>
                            </div>

                            {{-- Spinner de carregamento durante envio --}}
                            <div id="mdfe-upload-loading" class="mt-3 d-none text-center">
                                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                    <span class="visually-hidden">Processando...</span>
                                </div>
                                <div class="fs-13 fw-semibold text-primary mt-2">Processando e importando MDF-e...</div>
                            </div>

                            {{-- Input de arquivo oculto --}}
                            <input type="file" id="input-arquivo-mdfe" name="arquivo" accept=".xml,.zip" style="display: none;">
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-end" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal"
                        style="border-radius: 6px; font-size: 13.5px; font-weight: 500; border-color: #cbd5e1; color: #475569;">
                    Fechar
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('dropzone-mdfe');
    const fileInput = document.getElementById('input-arquivo-mdfe');
    const btnSelecionar = document.getElementById('btn-selecionar-mdfe');
    const fileInfo = document.getElementById('mdfe-file-info');
    const fileName = document.getElementById('mdfe-file-name');
    const fileSize = document.getElementById('mdfe-file-size');
    const btnRemover = document.getElementById('btn-remover-arquivo-mdfe');
    const loading = document.getElementById('mdfe-upload-loading');

    if (!dropzone || !fileInput) return;

    // Dispara seletor de arquivos ao clicar no botão laranja ou na área
    btnSelecionar.addEventListener('click', function (e) {
        e.stopPropagation();
        fileInput.click();
    });

    dropzone.addEventListener('click', function (e) {
        if (e.target.closest('#btn-remover-arquivo-mdfe')) return;
        fileInput.click();
    });

    // Efeitos de Drag & Drop
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.borderColor = '#f05a28';
            dropzone.style.backgroundColor = '#fff9f6';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.borderColor = '#cbd5e1';
            dropzone.style.backgroundColor = '#ffffff';
        }, false);
    });

    dropzone.addEventListener('drop', function (e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            processarArquivo(files[0]);
        }
    });

    fileInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            processarArquivo(this.files[0]);
        }
    });

    btnRemover.addEventListener('click', function (e) {
        e.stopPropagation();
        resetarInput();
    });

    function resetarInput() {
        fileInput.value = '';
        fileInfo.classList.add('d-none');
        fileInfo.classList.remove('d-flex');
        loading.classList.add('d-none');
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function processarArquivo(file) {
        const extensao = file.name.split('.').pop().toLowerCase();
        if (extensao !== 'xml' && extensao !== 'zip') {
            if (typeof swal === 'function') {
                swal("Formato Inválido", "Selecione um arquivo com extensão .XML ou .ZIP.", "warning");
            } else {
                alert("Selecione um arquivo com extensão .XML ou .ZIP.");
            }
            resetarInput();
            return;
        }

        // Exibe dados do arquivo selecionado
        fileName.textContent = file.name;
        fileSize.textContent = '(' + formatBytes(file.size) + ')';
        fileInfo.classList.remove('d-none');
        fileInfo.classList.add('d-flex');

        // Envia automaticamente para importação
        enviarArquivo(file);
    }

    function enviarArquivo(file) {
        const formData = new FormData();
        formData.append('arquivo', file);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('empresa_id', '{{ request()->empresa_id ?? "" }}');

        loading.classList.remove('d-none');

        $.ajax({
            url: "{{ route('mdfe.importar-manifesto') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                loading.classList.add('d-none');
                $('#modal-importar-mdfe').modal('hide');

                const msg = res.mensagem || 'MDF-e importado com sucesso!';
                if (typeof swal === 'function') {
                    swal("Sucesso!", msg, "success").then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(msg);
                    window.location.reload();
                }
            },
            error: function (xhr) {
                loading.classList.add('d-none');
                resetarInput();

                let erroMsg = 'Ocorreu um erro ao importar o arquivo.';
                if (xhr.responseJSON && xhr.responseJSON.erro) {
                    erroMsg = xhr.responseJSON.erro;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    erroMsg = xhr.responseJSON.message;
                }

                if (typeof swal === 'function') {
                    swal("Erro na Importação", erroMsg, "error");
                } else {
                    alert("Erro: " + erroMsg);
                }
            }
        });
    }
});
</script>
