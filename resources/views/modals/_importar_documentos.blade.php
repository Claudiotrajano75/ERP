<div class="modal fade" id="modal-importar_documentos" tabindex="-1" aria-labelledby="modal-importar-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            
            {{-- Header --}}
            <div class="modal-header bg-white border-bottom py-3 px-4" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold text-dark fs-16" id="modal-importar-title">Importar Documentos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                
                {{-- PASSO 1: Selecionar Arquivos --}}
                <div id="import-step-1" class="text-center">
                    {{-- Texto Introdutório --}}
                    <div class="mb-4">
                        <p class="text-secondary fs-15 mb-0">Para iniciar a emissão, você precisa importar seus documentos.</p>
                        <p class="text-secondary fs-15">Arraste seus arquivos XML para a área abaixo ou clique para selecionar.</p>
                    </div>

                    {{-- Área de Drag & Drop --}}
                    <div class="d-flex justify-content-center mb-4">
                        <div class="upload-drop-zone d-flex flex-column align-items-center justify-content-center p-3 cursor-pointer" 
                             id="dropzone-xml"
                             style="border: 2px dashed #cbd5e1; border-radius: 12px; width: 100%; max-width: 550px; height: 150px; transition: all 0.2s ease; background: #f8fafc;"
                             onclick="document.getElementById('file-input-xml').click();">
                            
                            <div class="icon-container mb-3" style="color: #64748b;">
                                <i class="ri-macbook-line" style="font-size: 48px;"></i>
                            </div>
                            
                            <h6 class="fw-bold text-dark fs-14 mb-2" style="max-width: 250px; line-height: 1.4;">
                                Arrastar e soltar o arquivo aqui ou clicar para selecionar do computador
                            </h6>
                            
                            <p class="text-muted fs-11 mt-2 mb-0">
                                Formatos aceitos:<br>
                                NFe (XML) | CTe e MDFe (XML)
                            </p>

                            {{-- Input Hidden --}}
                            <input type="file" id="file-input-xml" accept=".xml" multiple style="display: none;">
                        </div>
                    </div>

                    {{-- Divisor e Botão Manual --}}
                    <div class="mt-3">
                        <p class="fw-semibold text-dark fs-14 mb-2">Não possui XML?</p>
                        <a href="{{ route('mdfe.create') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 6px; border-color: #cbd5e1; color: #475569; font-weight: 500;">
                            Preencher manualmente
                        </a>
                    </div>

                    {{-- Checkbox --}}
                    <div class="d-flex justify-content-end mt-3">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="checkbox" id="carregamento_posterior" style="width: 18px; height: 18px;">
                            <label class="form-check-label text-dark fs-13" for="carregamento_posterior">
                                Indicar carregamento posterior
                            </label>
                        </div>
                    </div>
                </div>

                {{-- PASSO 2: Configurações de Importação --}}
                <div id="import-step-2" style="display: none;">
                    <h6 class="fw-bold text-dark mb-4 fs-15">Configurações de Importação</h6>
                    
                    <div class="row align-items-end mb-4">
                        <div class="col-md-5">
                            <div class="form-check d-flex align-items-center gap-2 mb-2 mb-md-0">
                                <input class="form-check-input mt-0" type="checkbox" id="especificar_descarregamento" style="width: 18px; height: 18px;">
                                <label class="form-check-label text-dark fs-14" for="especificar_descarregamento">
                                    Especificar Local de Descarregamento
                                </label>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fs-12 fw-semibold text-dark mb-1">Local de descarregamento</label>
                            <div class="d-flex gap-2">
                                <select class="form-select select2 bg-light border-0" id="uf_descarregamento" style="width: 100px;" disabled>
                                    <option value="">UF</option>
                                    <option value="AC">AC</option>
                                    <option value="AL">AL</option>
                                    <option value="AP">AP</option>
                                    <option value="AM">AM</option>
                                    <option value="BA">BA</option>
                                    <option value="CE">CE</option>
                                    <option value="DF">DF</option>
                                    <option value="ES">ES</option>
                                    <option value="GO">GO</option>
                                    <option value="MA">MA</option>
                                    <option value="MT">MT</option>
                                    <option value="MS">MS</option>
                                    <option value="MG">MG</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PR">PR</option>
                                    <option value="PE">PE</option>
                                    <option value="PI">PI</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RN">RN</option>
                                    <option value="RS">RS</option>
                                    <option value="RO">RO</option>
                                    <option value="RR">RR</option>
                                    <option value="SC">SC</option>
                                    <option value="SP">SP</option>
                                    <option value="SE">SE</option>
                                    <option value="TO">TO</option>
                                </select>
                                <select class="form-select select2 bg-light border-0 flex-grow-1" id="mun_descarregamento" disabled>
                                    <option value="">Município</option>
                                    @isset($cidades)
                                        @foreach($cidades as $c)
                                            <option value="{{ $c->id }}" data-uf="{{ $c->uf }}">{{ $c->info }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Lista de Arquivos --}}
                    <div class="p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; max-height: 200px; overflow-y: auto;" id="xml-files-list">
                        <!-- Arquivos injetados via JS -->
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-end" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-outline-secondary px-4 fw-medium" id="btn-cancelar-import" data-bs-dismiss="modal" style="border-color: #cbd5e1; color: #475569;">
                    Cancelar
                </button>
                <button type="button" class="btn btn-outline-secondary px-4 fw-medium d-none" id="btn-voltar-import" style="border-color: #cbd5e1; color: #475569;">
                    Voltar
                </button>
                <button type="button" class="btn text-white px-4 fw-bold d-none" id="btn-confirmar-import" style="background: #ff6b00; border: none; box-shadow: 0 4px 14px rgba(255, 107, 0, 0.25);">
                    Confirmar
                </button>
            </div>
            
        </div>
    </div>
</div>

<style>
/* Efeito de Hover no Dropzone */
.upload-drop-zone:hover {
    border-color: #3b82f6 !important;
    background: #eff6ff !important;
}
.upload-drop-zone:hover .icon-container {
    color: #3b82f6 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone-xml');
    const fileInput = document.getElementById('file-input-xml');
    
    // Passos e Botões
    const step1 = document.getElementById('import-step-1');
    const step2 = document.getElementById('import-step-2');
    const btnCancelar = document.getElementById('btn-cancelar-import');
    const btnVoltar = document.getElementById('btn-voltar-import');
    const btnConfirmar = document.getElementById('btn-confirmar-import');
    const filesListContainer = document.getElementById('xml-files-list');
    
    // Checkbox e Selects
    const checkEspecificar = document.getElementById('especificar_descarregamento');
    const ufSelect = document.getElementById('uf_descarregamento');
    const munSelect = document.getElementById('mun_descarregamento');

    let currentFiles = [];

    // Inicializar Select2
    $('#uf_descarregamento, #mun_descarregamento').select2({
        dropdownParent: $('#modal-importar_documentos'),
        width: '100%',
        language: "pt-BR"
    });

    // Habilitar/Desabilitar Selects
    checkEspecificar.addEventListener('change', function() {
        const isChecked = this.checked;
        $('#uf_descarregamento').prop('disabled', !isChecked).trigger('change');
        $('#mun_descarregamento').prop('disabled', !isChecked).trigger('change');
    });

    // Ação Voltar
    btnVoltar.addEventListener('click', function() {
        step2.style.display = 'none';
        step1.style.display = 'block';
        
        btnVoltar.classList.add('d-none');
        btnConfirmar.classList.add('d-none');
        btnCancelar.classList.remove('d-none');
        
        // Limpar arquivos
        fileInput.value = '';
        currentFiles = [];
    });

    // Ação Confirmar (Fase 3: Upload AJAX e redirecionamento)
    btnConfirmar.addEventListener('click', function() {
        if(currentFiles.length === 0) return;
        
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i> Processando XML...');
        
        let formData = new FormData();
        for (let i = 0; i < currentFiles.length; i++) {
            formData.append('xml[]', currentFiles[i]);
        }

        // Se marcou descarregamento específico
        let ufCustom = $('#uf_descarregamento').val();
        let munCustom = $('#mun_descarregamento').val();
        let munCustomText = $('#mun_descarregamento option:selected').text();
        let especificar = $('#especificar_descarregamento').is(':checked');

        $.ajax({
            url: '/mdfe/importar-xml',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .done(function(res) {
            // Se o usuário customizou o descarregamento na modal, atualizamos na resposta
            if (especificar && ufCustom) {
                res.descarregamento_customizado = {
                    uf: ufCustom,
                    cidade_id: munCustom,
                    cidade_nome: munCustomText
                };
            }
            
            // Salva os dados no sessionStorage para carregar na tela create
            sessionStorage.setItem('mdfe_imported_data', JSON.stringify(res));
            
            // Redireciona para a tela de criação
            window.location.href = "{{ route('mdfe.create') }}";
        })
        .fail(function(err) {
            btn.prop('disabled', false).html(originalText);
            let msg = 'Não foi possível ler o(s) arquivo(s) XML enviados.';
            if (err.responseJSON && err.responseJSON.erro) {
                msg = err.responseJSON.erro;
            } else if (err.responseJSON && err.responseJSON.message) {
                msg = err.responseJSON.message;
            }
            
            if (typeof swal === 'function') {
                swal('Atenção', msg, 'warning');
            } else {
                alert(msg);
            }
        });
    });

    // Previne comportamento padrão de drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropzone.style.borderColor = '#3b82f6';
        dropzone.style.background = '#eff6ff';
    }

    function unhighlight(e) {
        dropzone.style.borderColor = '#cbd5e1';
        dropzone.style.background = '#f8fafc';
    }

    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        let dt = e.dataTransfer;
        let files = dt.files;
        handleFiles(files);
    }

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    // Filtrar municípios ao mudar UF
    $('#uf_descarregamento').on('change', function() {
        const selectedUf = $(this).val();
        if (!selectedUf) {
            $('#mun_descarregamento option').show();
            return;
        }
        $('#mun_descarregamento option').each(function() {
            const optUf = $(this).data('uf');
            if (!optUf || optUf === selectedUf) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
        $('#mun_descarregamento').select2({
            dropdownParent: $('#modal-importar_documentos'),
            width: '100%',
            language: "pt-BR"
        });
    });

    function formatBytes(bytes, decimals = 1) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['B', 'K', 'M', 'G', 'T'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + sizes[i];
    }

    function handleFiles(files) {
        if (files.length > 0) {
            currentFiles = Array.from(files).filter(f => f.name.toLowerCase().endsWith('.xml'));
            
            if(currentFiles.length === 0) {
                alert("Por favor, selecione apenas arquivos XML.");
                return;
            }

            // Resetar campos de descarregamento personalizado
            $('#especificar_descarregamento').prop('checked', false);
            $('#uf_descarregamento').val('').prop('disabled', true).trigger('change');
            $('#mun_descarregamento').val('').prop('disabled', true).trigger('change');

            // Esconder Passo 1, Mostrar Passo 2
            step1.style.display = 'none';
            step2.style.display = 'block';
            
            // Trocar Botões
            btnCancelar.classList.add('d-none');
            btnVoltar.classList.remove('d-none');
            btnConfirmar.classList.remove('d-none');

            // Renderizar Lista de Arquivos
            filesListContainer.innerHTML = '';
            currentFiles.forEach(file => {
                const fileHtml = `
                    <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-white" style="border: 1px solid #cbd5e1; border-radius: 24px;">
                        <div class="d-flex align-items-center gap-2 px-2 text-truncate">
                            <i class="ri-attachment-2" style="color: #94a3b8; transform: rotate(-45deg);"></i>
                            <span class="fs-13 fw-medium text-dark text-truncate">${file.name}</span>
                        </div>
                        <span class="fs-12 text-muted px-3">${formatBytes(file.size)}</span>
                    </div>
                `;
                filesListContainer.insertAdjacentHTML('beforeend', fileHtml);
            });
        }
    }
});
</script>
