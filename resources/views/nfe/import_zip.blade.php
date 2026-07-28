@extends('layouts.app', ['title' => 'Importar Arquivos XML — NFe'])

@section('css')
<style>
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

/* ─── Custom Upload Card ─── */
input[type="file"] { display: none; }
.custom-file-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed #302b63;
    border-radius: 12px;
    padding: 60px 40px;
    background: #fafbfe;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(48,43,99,0.02);
}
.custom-file-upload:hover {
    border-color: #302b63;
    background: #f5f6fe;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(48,43,99,0.06);
}
.custom-file-upload i { font-size: 54px; color: #302b63; margin-bottom: 16px; transition: transform 0.2s ease; }
.custom-file-upload:hover i { transform: scale(1.1); }
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
                                <i class="ri-upload-cloud-2-line"></i>
                                Importar Arquivo ZIP de XMLs
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Selecione um arquivo <strong>.zip</strong> contendo os XMLs das notas fiscais para importar em lote.</p>
                        </div>
                        <div>
                            <a href="{{ route('nfe.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5 text-center">
                    <form id="form-import" class="row justify-content-center" method="post" action="{{ route('nfe.import-zip-store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6 col-12">
                            <label for="file" class="custom-file-upload mb-3">
                                <i class="ri-file-zip-line"></i>
                                <span class="fw-bold text-dark fs-15">Arraste ou clique para selecionar o arquivo .zip</span>
                                <span class="text-muted fs-12 mt-1">Apenas arquivos .zip de XMLs de NFe são aceitos</span>
                            </label>
                            <input accept=".zip" name="file" type="file" id="file">
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
        $("body").addClass("loading");
        $('#form-import').submit();
    });
</script>
@endsection
