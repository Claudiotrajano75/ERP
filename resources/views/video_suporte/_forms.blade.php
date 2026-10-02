<div class="row g-3">
    <!-- ═══ TÍTULO ═══ -->
    <div class="col-md-8 col-12">
        <label class="form-label fw-semibold text-dark required">
            <i class="ri-text me-1 text-primary"></i> Título do Tutorial
        </label>
        <input type="text"
               name="titulo"
               id="inp-titulo"
               maxlength="150"
               required
               class="form-control @error('titulo') is-invalid @enderror"
               placeholder="Ex.: Como cadastrar um novo produto"
               value="{{ old('titulo', $item->titulo ?? '') }}">
        <div class="d-flex justify-content-between mt-1">
            <small class="text-muted fs-11">Título claro e objetivo que o usuário verá na busca.</small>
            <small class="text-muted fs-11"><span id="counter-titulo">0</span>/150</small>
        </div>
        @error('titulo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- ═══ CATEGORIA ═══ -->
    <div class="col-md-4 col-12">
        <label class="form-label fw-semibold text-dark">
            <i class="ri-folder-line me-1 text-primary"></i> Categoria
        </label>
        <select name="categoria_id" id="inp-categoria_id" class="form-select @error('categoria_id') is-invalid @enderror">
            <option value="">Selecione uma Categoria...</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}"
                    {{ (old('categoria_id', $item->categoria_id ?? '') == $cat->id || old('categoria', $item->categoria ?? '') == $cat->nome) ? 'selected' : '' }}>
                    {{ $cat->nome }}
                </option>
            @endforeach
        </select>
        <small class="text-muted fs-11">Módulo ao qual o vídeo tutorial pertence.</small>
        @error('categoria_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- ═══ DESCRIÇÃO ═══ -->
    <div class="col-12">
        <label class="form-label fw-semibold text-dark required">
            <i class="ri-file-text-line me-1 text-primary"></i> Descrição do Vídeo
        </label>
        <textarea name="descricao"
                  id="inp-descricao"
                  rows="3"
                  maxlength="1000"
                  required
                  class="form-control @error('descricao') is-invalid @enderror"
                  placeholder="Explique brevemente o que o usuário aprenderá neste vídeo...">{{ old('descricao', $item->descricao ?? '') }}</textarea>
        <div class="d-flex justify-content-between mt-1">
            <small class="text-muted fs-11">Resumo das rotinas abordadas no tutorial (exibido na Central de Ajuda).</small>
            <small class="text-muted fs-11"><span id="counter-descricao">0</span>/1000 caracteres</small>
        </div>
        @error('descricao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- ═══ UPLOAD DRAG & DROP MP4 ═══ -->
    <div class="col-12">
        <label class="form-label fw-semibold text-dark {{ !isset($item) ? 'required' : '' }}">
            <i class="ri-video-upload-line me-1 text-primary"></i> Arquivo de Vídeo (Formato MP4)
        </label>

        <div class="upload-dropzone p-4 text-center border-dashed rounded-3 bg-light position-relative" id="dropzoneVideo" style="border: 2px dashed #a5b4fc; transition: all 0.25s ease;">
            <input type="file"
                   name="video_file"
                   id="inp-video_file"
                   accept="video/mp4"
                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
                   style="z-index: 5; cursor: pointer;">

            <div id="dropzonePrompt">
                <div class="mb-2">
                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 26px;">
                        <i class="ri-upload-cloud-2-line"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1 text-dark">Arraste o vídeo aqui ou clique para selecionar um arquivo MP4</h6>
                <p class="text-muted fs-12 mb-0">
                    Apenas arquivos <strong>.mp4</strong> (MIME: video/mp4) até <strong>{{ $maxSizeMb ?? 500 }} MB</strong>
                </p>
            </div>

            <!-- Detalhes do arquivo selecionado -->
            <div id="fileSelectedBox" class="d-none mt-2">
                <div class="p-2 bg-white rounded border d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-file-video-fill text-danger fs-22"></i>
                    <div class="text-start">
                        <div class="fw-bold fs-12 text-truncate" id="fileName" style="max-width: 320px;"></div>
                        <small class="text-muted fs-11" id="fileSize"></small>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm p-1 rounded-circle ms-2" id="btnRemoveFile" title="Remover seleção" style="z-index: 10;">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>

            <!-- Barra de Progresso Real do Upload -->
            <div id="uploadProgressContainer" class="d-none mt-3">
                <div class="d-flex justify-content-between fs-12 mb-1">
                    <span class="fw-semibold text-primary"><i class="ri-loader-4-line ri-spin me-1"></i> Enviando vídeo para o servidor...</span>
                    <span class="fw-bold text-dark" id="uploadPercentage">0%</span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 6px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                         id="uploadProgressBar"
                         role="progressbar"
                         style="width: 0%;"></div>
                </div>
                <small class="text-muted fs-11 mt-1 d-block">Por favor, não feche esta página enquanto o upload estiver em andamento.</small>
            </div>
        </div>

        @if(isset($item) && $item->arquivo_path)
            <div class="mt-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-success fs-18"></i>
                    <div>
                        <small class="text-muted d-block">Vídeo atual armazenado:</small>
                        <strong class="fs-12 font-monospace">{{ $item->arquivo_original ?: 'video.mp4' }}</strong>
                        <span class="badge bg-secondary-subtle text-secondary ms-1 fs-11">{{ $item->tamanho_formatado }}</span>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleCurrentPreview()">
                    <i class="ri-eye-line me-1"></i> Pré-visualizar atual
                </button>
            </div>
        @endif

        <!-- Player de Preview HTML5 -->
        <div id="previewContainer" class="mt-3 {{ (isset($item) && $item->arquivo_path) ? 'd-none' : 'd-none' }}">
            <label class="form-label fs-12 fw-semibold text-muted">
                <i class="ri-play-circle-line me-1"></i> Pré-visualização do Vídeo
            </label>
            <div class="ratio ratio-16x9 bg-black rounded overflow-hidden shadow-sm border" style="max-height: 380px;">
                <video id="videoPreviewPlayer" controls style="width: 100%; height: 100%;">
                    @if(isset($item) && $item->arquivo_path)
                        <source src="{{ $item->stream_url }}" type="video/mp4" id="videoPreviewSource">
                    @else
                        <source src="" type="video/mp4" id="videoPreviewSource">
                    @endif
                    Seu navegador não suporta a tag de vídeo.
                </video>
            </div>
        </div>
    </div>

    <!-- ═══ TAGS ═══ -->
    <div class="col-md-6 col-12">
        <label class="form-label fw-semibold text-dark">
            <i class="ri-price-tag-3-line me-1 text-primary"></i> Tags / Palavras-chave
        </label>
        <input type="text"
               name="tags"
               id="inp-tags"
               class="form-control"
               placeholder="Ex.: produto, cadastro, nfe, nfce, caixa, estoque, tributação"
               value="{{ old('tags', $item->tags ?? '') }}">
        <small class="text-muted fs-11">Separe as palavras-chave por vírgula para facilitar a busca do usuário.</small>
    </div>

    <!-- ═══ ORDEM DE EXIBIÇÃO ═══ -->
    <div class="col-md-3 col-6">
        <label class="form-label fw-semibold text-dark">
            <i class="ri-sort-asc me-1 text-primary"></i> Ordem de Exibição
        </label>
        <input type="number"
               name="ordem_exibicao"
               id="inp-ordem"
               class="form-control"
               min="0"
               step="1"
               placeholder="0"
               value="{{ old('ordem_exibicao', $item->ordem_exibicao ?? 0) }}">
        <small class="text-muted fs-11">Quanto menor o número, antes o vídeo será exibido.</small>
    </div>

    <!-- ═══ STATUS ═══ -->
    <div class="col-md-3 col-6">
        <label class="form-label fw-semibold text-dark">
            <i class="ri-toggle-line me-1 text-primary"></i> Status de Publicação
        </label>
        <select name="status" id="inp-status" class="form-select">
            <option value="ATIVO" {{ old('status', $item->status ?? 'ATIVO') === 'ATIVO' ? 'selected' : '' }}>Ativo (Publicado)</option>
            <option value="INATIVO" {{ old('status', $item->status ?? '') === 'INATIVO' ? 'selected' : '' }}>Inativo (Rascunho)</option>
        </select>
        <small class="text-muted fs-11">Apenas vídeos Ativos aparecem para os clientes.</small>
    </div>

    <!-- ═══ INFORMAÇÕES ADICIONAIS NA EDIÇÃO ═══ -->
    @if(isset($item))
        <div class="col-12 mt-2">
            <div class="p-2 bg-light rounded text-muted fs-11 d-flex flex-wrap gap-4 border">
                <div><i class="ri-calendar-line me-1"></i> <strong>Criado em:</strong> {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '—' }}</div>
                <div><i class="ri-time-line me-1"></i> <strong>Última alteração:</strong> {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '—' }}</div>
                <div><i class="ri-global-line me-1"></i> <strong>Tipo de Publicação:</strong> {{ $item->tipo_publicacao }}</div>
            </div>
        </div>
    @endif
</div>

<!-- ═══ BARRA DE AÇÕES ═══ -->
<div class="modulo-actions mt-4 pt-3 border-top">
    <div class="d-flex gap-2 justify-content-end align-items-center flex-wrap">
        <a href="{{ route('video-suporte.index') }}" class="btn btn-outline-secondary" id="btn-cancelar">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>

        <button type="button" class="btn btn-outline-warning" id="btn-salvar-rascunho">
            <i class="ri-draft-line me-1"></i> Salvar como Rascunho
        </button>

        <button type="button" class="btn btn-success px-4" id="btn-salvar-publicar">
            <i class="ri-checkbox-circle-line me-1"></i> Salvar e Publicar
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inpTitulo = document.getElementById('inp-titulo');
        const counterTitulo = document.getElementById('counter-titulo');
        const inpDescricao = document.getElementById('inp-descricao');
        const counterDescricao = document.getElementById('counter-descricao');
        const inpVideo = document.getElementById('inp-video_file');
        const dropzone = document.getElementById('dropzoneVideo');
        const dropzonePrompt = document.getElementById('dropzonePrompt');
        const fileSelectedBox = document.getElementById('fileSelectedBox');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const btnRemove = document.getElementById('btnRemoveFile');
        const previewContainer = document.getElementById('previewContainer');
        const videoPlayer = document.getElementById('videoPreviewPlayer');
        const videoSource = document.getElementById('videoPreviewSource');
        const progressContainer = document.getElementById('uploadProgressContainer');
        const progressBar = document.getElementById('uploadProgressBar');
        const progressPercent = document.getElementById('uploadPercentage');
        const btnSalvarPublicar = document.getElementById('btn-salvar-publicar');
        const btnSalvarRascunho = document.getElementById('btn-salvar-rascunho');
        const inpStatus = document.getElementById('inp-status');
        const form = document.getElementById('form-video');

        const maxMb = {{ $maxSizeMb ?? 500 }};
        const maxBytes = maxMb * 1024 * 1024;
        let isUploading = false;

        // Contadores
        function updateCounters() {
            if (inpTitulo && counterTitulo) counterTitulo.textContent = inpTitulo.value.length;
            if (inpDescricao && counterDescricao) counterDescricao.textContent = inpDescricao.value.length;
        }
        inpTitulo.addEventListener('input', updateCounters);
        inpDescricao.addEventListener('input', updateCounters);
        updateCounters();

        // Drag & Drop visual feedback
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.style.borderColor = '#4f46e5';
                dropzone.style.background = '#eef2ff';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.style.borderColor = '#a5b4fc';
                dropzone.style.background = '#f8fafc';
            }, false);
        });

        // Manipulação do arquivo selecionado
        function handleFile(file) {
            if (!file) return;

            // Validação de extensão/tipo
            if (!file.name.toLowerCase().endsWith('.mp4') && file.type !== 'video/mp4') {
                toastr.error('Formato inválido! Por favor, selecione exclusivamente um arquivo MP4.');
                inpVideo.value = '';
                return;
            }

            // Validação de tamanho
            if (file.size > maxBytes) {
                toastr.error(`O arquivo excede o limite máximo permitido de ${maxMb} MB.`);
                inpVideo.value = '';
                return;
            }

            // Formatação do tamanho
            let sz = file.size;
            let szFormatted = (sz / (1024 * 1024)).toFixed(1) + ' MB';

            fileName.textContent = file.name;
            fileSize.textContent = szFormatted;
            fileSelectedBox.classList.remove('d-none');
            dropzonePrompt.classList.add('d-none');

            // Preview local instantâneo via ObjectURL
            try {
                const objectUrl = URL.createObjectURL(file);
                videoSource.src = objectUrl;
                videoPlayer.load();
                previewContainer.classList.remove('d-none');
            } catch (err) {
                console.error('Erro ao gerar preview do vídeo:', err);
            }
        }

        inpVideo.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleFile(this.files[0]);
            }
        });

        btnRemove.addEventListener('click', function (e) {
            e.stopPropagation();
            inpVideo.value = '';
            fileSelectedBox.classList.add('d-none');
            dropzonePrompt.classList.remove('d-none');
            videoPlayer.pause();
            videoSource.src = '';
            previewContainer.classList.add('d-none');
        });

        // Envio do formulário com barra de progresso real
        function submeterFormulario(statusEscolhido) {
            if (isUploading) return;

            // Validação básica frontend
            if (!inpTitulo.value.trim()) {
                toastr.warning('Por favor, informe o título do tutorial.');
                inpTitulo.focus();
                return;
            }
            if (!inpDescricao.value.trim()) {
                toastr.warning('Por favor, informe a descrição do tutorial.');
                inpDescricao.focus();
                return;
            }

            @if(!isset($item))
                if (!inpVideo.files || !inpVideo.files[0]) {
                    toastr.warning('Por favor, selecione o arquivo de vídeo MP4 do tutorial.');
                    return;
                }
            @endif

            inpStatus.value = statusEscolhido;

            // Preparar FormData
            const formData = new FormData(form);

            // Se não houver arquivo para envio (ex: edição sem troca de vídeo), submete comum
            if (!inpVideo.files || !inpVideo.files[0]) {
                form.submit();
                return;
            }

            // Envio via XMLHttpRequest com feedback de barra de progresso
            isUploading = true;
            btnSalvarPublicar.disabled = true;
            btnSalvarRascunho.disabled = true;
            document.getElementById('btn-cancelar').classList.add('disabled');
            progressContainer.classList.remove('d-none');

            const xhr = new XMLHttpRequest();
            xhr.open(form.method, form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressPercent.textContent = percentComplete + '%';
                }
            };

            xhr.onload = function () {
                isUploading = false;
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        toastr.success(response.message || 'Tutorial salvo com sucesso!');
                        setTimeout(() => {
                            window.location.href = response.redirect || "{{ route('video-suporte.index') }}";
                        }, 800);
                    } catch (e) {
                        window.location.href = "{{ route('video-suporte.index') }}";
                    }
                } else {
                    btnSalvarPublicar.disabled = false;
                    btnSalvarRascunho.disabled = false;
                    document.getElementById('btn-cancelar').classList.remove('disabled');
                    progressContainer.classList.add('d-none');
                    try {
                        const err = JSON.parse(xhr.responseText);
                        toastr.error(err.message || 'Erro ao enviar vídeo.');
                    } catch (e) {
                        toastr.error('Erro de comunicação com o servidor.');
                    }
                }
            };

            xhr.onerror = function () {
                isUploading = false;
                btnSalvarPublicar.disabled = false;
                btnSalvarRascunho.disabled = false;
                progressContainer.classList.add('d-none');
                toastr.error('Erro durante o upload. Verifique sua conexão e tente novamente.');
            };

            xhr.send(formData);
        }

        btnSalvarPublicar.addEventListener('click', function () {
            submeterFormulario('ATIVO');
        });

        btnSalvarRascunho.addEventListener('click', function () {
            submeterFormulario('INATIVO');
        });
    });

    function toggleCurrentPreview() {
        let container = document.getElementById('previewContainer');
        if (container.classList.contains('d-none')) {
            container.classList.remove('d-none');
            document.getElementById('videoPreviewPlayer').play().catch(e => {});
        } else {
            container.classList.add('d-none');
            document.getElementById('videoPreviewPlayer').pause();
        }
    }
</script>