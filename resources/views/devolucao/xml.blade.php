@extends('layouts.app', ['title' => 'Importar XML Devolução'])

@section('css')
<style type="text/css">
    input[type="file"] {
        display: none;
    }
    .custom-file-upload {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 40px;
        background: #fdfdfd;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .custom-file-upload:hover {
        border-color: #dc3545;
        background: #fff5f5;
    }
    .custom-file-upload i {
        font-size: 48px;
        color: #dc3545;
        margin-bottom: 10px;
    }

    /* ─── Header Gradiente ─── */
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

    /* ─── Form Card (Create/Edit) ─── */
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-form-card .card-body { background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-upload-line"></i>
                                Importar XML de Devolução
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Selecione o arquivo XML original da nota fiscal para gerar a nota de devolução automaticamente.</p>
                        </div>
                        <div>
                            <a href="{{ route('devolucao.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-5 text-center">
                    {!!Form::open()
                    ->post()
                    ->route('devolucao.store-xml')
                    ->multipart()
                    ->id('form-xml')
                    !!}
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-12">
                            <label for="inp-file" class="custom-file-upload mb-3">
                                <i class="ri-file-code-line"></i>
                                <span class="fw-bold text-dark fs-15">Arraste ou clique para selecionar o XML</span>
                                <span class="text-muted fs-12 mt-1">Formato aceito: arquivo .xml da nota original</span>
                            </label>
                            
                            {!! Form::file('file', 'XML')->attrs(['accept' => '.xml', 'id' => 'inp-file']) !!}
                            
                            <div class="mt-2">
                                <span class="text-danger fw-semibold" id="filename"></span>
                            </div>
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
<script type="text/javascript">
    $('#inp-file').change(function() {
        let filename = $(this).val().split('\\').pop();
        if(filename){
            $('#filename').text('Arquivo selecionado: ' + filename);
            setTimeout(() => {
                $('#form-xml').submit();
                $("body").addClass("loading");
            }, 500);
        }
    });
</script>
@endsection
