@extends('layouts.app', ['title' => 'Importar Retorno Bancário'])

@section('css')
<style>
/* ─── Upload Zone ─── */
.upload-zone {
    border: 2px dashed #c7d2fe;
    border-radius: 16px;
    padding: 48px 24px;
    text-align: center;
    background: #f8faff;
    cursor: pointer;
    transition: all 0.25s ease;
}
.upload-zone:hover {
    border-color: #4f46e5;
    background: #eef2ff;
}
.upload-zone i {
    font-size: 52px;
    color: #818cf8;
    display: block;
    margin-bottom: 12px;
    transition: color 0.2s;
}
.upload-zone:hover i {
    color: #4f46e5;
}
.upload-zone .upload-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e1b4b;
    margin-bottom: 6px;
}
.upload-zone .upload-sub {
    font-size: 12px;
    color: #64748b;
}
.upload-zone input[type=file] {
    opacity: 0;
    position: absolute;
    width: 1px;
    height: 1px;
}
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
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Importe arquivos de retorno (.ret) do banco para liquidar boletos pagos automaticamente.</p>
                        </div>
                        <div>
                            <a href="{{ route('remessa-boleto.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
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
                    <label for="file" class="w-100 mb-0">
                        <div class="upload-zone position-relative" id="upload-drop">
                            <i class="ri-file-upload-line" id="upload-icon"></i>
                            <div class="upload-title" id="upload-title">Clique para selecionar o arquivo</div>
                            <div class="upload-sub">Formatos aceitos: <strong>.RET</strong> ou <strong>.ret</strong></div>
                            <div class="mt-3">
                                <span class="dash-btn dash-btn-primary" id="upload-btn">
                                    <i class="ri-folder-open-line"></i> Escolher Arquivo
                                </span>
                            </div>
                        </div>
                        <input accept=".ret, .RET" name="file" type="file" id="file" class="form-control" style="opacity:0;position:absolute;width:1px;height:1px;">
                    </label>

                    <div class="mt-3 p-3 bg-light-subtle border border-dashed rounded fs-12 text-muted d-flex align-items-start gap-2">
                        <i class="ri-information-line fs-16 text-info flex-shrink-0 mt-1"></i>
                        <span>O arquivo será processado automaticamente assim que selecionado. Os boletos identificados como pagos serão exibidos para conferência antes da baixa nas contas a receber.</span>
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
            $('#upload-icon').removeClass('ri-file-upload-line').addClass('ri-file-check-line').css('color', '#059669');
            $('#upload-title').text(fileName).css('color', '#059669');
            $('#upload-btn').html('<i class="ri-loader-4-line me-1"></i> Processando arquivo...');
            $('#upload-drop').css({'border-color': '#10b981', 'background': '#f0fdf4'});
        }
        $('#form-import').submit();
        $("body").addClass("loading");
    });
</script>
@endsection
