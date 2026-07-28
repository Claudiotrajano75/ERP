@extends('layouts.app', ['title' => 'Importar Retorno Bancário'])

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
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Upload Zone ─── */
.upload-zone { border: 2px dashed #c5cae9; border-radius: 16px; padding: 48px 24px; text-align: center; background: #fafbff; cursor: pointer; transition: all 0.25s ease; }
.upload-zone:hover { border-color: #302b63; background: #f0f0ff; }
.upload-zone i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; transition: color 0.2s; }
.upload-zone:hover i { color: #302b63; }
.upload-zone .upload-title { font-size: 16px; font-weight: 700; color: #302b63; margin-bottom: 6px; }
.upload-zone .upload-sub { font-size: 12px; color: #9e9eb8; }
.upload-zone input[type=file] { opacity: 0; position: absolute; width: 1px; height: 1px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-upload-line"></i>
                                Importar Retorno Bancário
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Importe arquivos de retorno (.ret) gerados pelo seu banco para liquidar boletos pagos automaticamente.</p>
                        </div>
                        <div>
                            <a href="{{ route('remessa-boleto.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->id('form-import')
                    ->post()
                    ->route('remessa-boleto.import-store')
                    ->multipart()
                    !!}

                    <!-- Zona de Upload -->
                    <label for="file" class="w-100">
                        <div class="upload-zone position-relative" id="upload-drop">
                            <i class="ri-file-upload-line" id="upload-icon"></i>
                            <div class="upload-title" id="upload-title">Clique para selecionar o arquivo</div>
                            <div class="upload-sub">Formatos aceitos: <strong>.RET</strong> ou <strong>.ret</strong></div>
                            <div class="mt-3">
                                <span class="btn btn-primary btn-sm px-4" id="upload-btn">
                                    <i class="ri-folder-open-line me-1"></i> Escolher Arquivo
                                </span>
                            </div>
                        </div>
                        <input accept=".ret, .RET" name="file" type="file" id="file" class="form-control" style="opacity:0;position:absolute;width:1px;height:1px;">
                    </label>

                    <div class="mt-3 p-3 bg-light-subtle border border-dashed rounded fs-12 text-muted d-flex align-items-start gap-2">
                        <i class="ri-information-line fs-16 text-info flex-shrink-0 mt-1"></i>
                        <span>O arquivo será processado automaticamente assim que selecionado. Os boletos identificados como pagos serão baixados nas contas a receber correspondentes.</span>
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
    $('#file').change(function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('#upload-icon').removeClass('ri-file-upload-line').addClass('ri-file-check-line').css('color', '#2e7d32');
            $('#upload-title').text(fileName).css('color', '#2e7d32');
            $('#upload-btn').removeClass('btn-primary').addClass('btn-success').html('<i class="ri-loader-4-line me-1"></i> Processando...');
            $('#upload-drop').css({'border-color': '#43e97b', 'background': '#f0fff4'});
        }
        $('#form-import').submit();
        $("body").addClass("loading");
    });
</script>
@endsection
