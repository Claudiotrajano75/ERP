@extends('layouts.app', ['title' => 'Configuração Geral do ERP'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Preview da Logo ─── */
.logo-preview-container {
    background: #f8f9fc;
    border: 2px dashed #d0d5dd;
    border-radius: 12px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
    transition: all 0.2s ease;
}
.logo-preview-container:hover {
    border-color: #302b63;
    background: #f1f0f9;
}
.logo-preview-img {
    max-height: 120px;
    max-width: 100%;
    object-fit: contain;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
    padding: 10px;
}
.logo-preview-placeholder {
    color: #888;
    text-align: center;
}
.logo-preview-placeholder i {
    font-size: 40px;
    color: #b5bbcb;
}

/* ─── Botões de Ação do Formulário ─── */
.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-settings-4-line"></i>
                            Configuração Geral do ERP
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie os dados e a identidade visual global de todo o sistema.
                        </p>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->post()->route('config-geral-admin.update-logo')->multipart()!!}
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="text-dark border-bottom pb-2 mb-3">
                                <i class="ri-palette-line text-primary me-2 align-middle fs-18"></i>
                                Identidade Visual do Painel
                            </h5>
                            
                            <p class="text-muted fs-13 mb-4">
                                Envie a logo oficial que será exibida no cabeçalho do ERP para todas as empresas. Recomendamos arquivos com fundo transparente (PNG ou SVG) de boa resolução.
                            </p>

                            <div class="row align-items-center g-4">
                                <div class="col-md-6 col-12">
                                    <div class="logo-preview-container">
                                        @if(isset($item) && $item->logo)
                                            <img id="preview" class="logo-preview-img" src="{{ $item->logo_url }}" alt="Logo ERP">
                                        @else
                                            <div id="placeholder" class="logo-preview-placeholder">
                                                <i class="ri-image-line d-block mb-2"></i>
                                                <span class="fs-12 text-muted">Nenhuma logo personalizada cadastrada.</span>
                                            </div>
                                            <img id="preview" class="logo-preview-img d-none" src="#" alt="Nova Logo">
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        <label for="logo_input" class="form-label required">Selecionar Imagem</label>
                                        <input type="file" required name="logo" id="logo_input" class="form-control" accept="image/*">
                                        <div class="form-text text-muted fs-11 mt-1">
                                            Formatos permitidos: PNG, JPG, JPEG, SVG ou WEBP. Tamanho máximo: 2MB.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ FOOTER AÇÕES ═══ -->
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="ri-save-line align-middle me-1"></i> Salvar Identidade
                            </button>
                        </div>
                    </div>

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.getElementById('logo_input').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
    }
</script>
@endsection
