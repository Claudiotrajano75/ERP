@extends('layouts.app', ['title' => 'SPED Fiscal - EFD ICMS/IPI'])

@section('css')
<style type="text/css">
    .btn { margin-top: 3px; }
    .card-sped { border-left: 4px solid #6c757d; }
    .stat-card {
        border-radius: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-dot.ok { background-color: #28a745; }
    .status-dot.warn { background-color: #ffc107; }
    .status-dot.err { background-color: #dc3545; }
    .fade-in {
        animation: fadeIn 0.4s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .progress-sped {
        height: 6px;
        border-radius: 3px;
    }
    .file-info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px dashed #adb5bd;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card card-sped">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0">
                    <i class="ri-file-list-3-line"></i>
                    SPED Fiscal — EFD ICMS/IPI
                </h4>
                <small class="text-muted">
                    <i class="ri-information-line"></i>
                    Geração do arquivo mensal para entrega ao fisco
                </small>
            </div>
            <div class="card-body">

                {{-- Stats Cards --}}
                @if($stats)
                <div class="row g-3 mb-4 fade-in">
                    <div class="col-md-3 col-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="ri-logout-box-r-line"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">NF-e Saída</small>
                                    <span class="fs-4 fw-bold">{{ $stats['nfe_saida'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <i class="ri-store-2-line"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">NFC-e (PDV)</small>
                                    <span class="fs-4 fw-bold">{{ $stats['nfce'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-info bg-opacity-10 text-info">
                                    <i class="ri-login-box-r-line"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">NF-e Entrada</small>
                                    <span class="fs-4 fw-bold">{{ $stats['nfe_entrada'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="ri-settings-3-line"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Configurações</small>
                                    <div class="d-flex gap-2 mt-1">
                                        <span class="badge {{ $stats['escritorio_configurado'] ? 'bg-success' : 'bg-danger' }}">
                                            <i class="ri-building-2-line"></i> Contábil
                                        </span>
                                        <span class="badge {{ $stats['sped_configurado'] ? 'bg-success' : 'bg-danger' }}">
                                            <i class="ri-settings-3-line"></i> SPED
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- SPED File Info --}}
                @if($spedFileInfo)
                <div class="alert alert-success file-info-card d-flex align-items-start gap-3 fade-in" role="alert">
                    <i class="ri-file-text-line fs-3 text-success mt-1"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block">Último SPED gerado</strong>
                        <small class="d-block text-muted">
                            Gerado em {{ $spedFileInfo['data'] }} — 
                            {{ number_format($spedFileInfo['tamanho'] / 1024, 1) }} KB — 
                            {{ $spedFileInfo['linhas'] }} linhas
                        </small>
                        <a href="{{ $spedFileInfo['url'] }}" class="btn btn-sm btn-success mt-2" download>
                            <i class="ri-download-2-line"></i> Baixar arquivo
                        </a>
                    </div>
                </div>
                @endif

                {!!Form::open()
                ->post()
                ->route('sped.store')
                ->attrs(['id' => 'form-sped'])
                !!}

                <div class="row g-3">
                    <div class="col-md-3">
                        {!!Form::date('data_inicial', 'Data inicial')
                        ->value($firstDate ?? date('Y-m-01'))
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::date('data_final', 'Data final')
                        ->value($lastDate ?? date('Y-m-t'))
                        ->required()
                        !!}
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="form-check form-switch mt-2">
                            {!!Form::checkbox('inventario', 'Incluir inventário')
                            ->attrs(['class' => 'form-check-input', 'id' => 'inventario-switch'])
                            !!}
                        </div>
                    </div>

                    <div id="inventario-fields" class="row g-3" style="display: none;">
                        <div class="col-md-3">
                            {!!Form::date('data_inventario', 'Data do inventário')
                            ->value(date('Y-m-t'))
                            !!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::select('motivo_inventario', 'Motivo', App\Models\Sped::motivosInventario())
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                    </div>
                </div>

                {{-- Progress bar (hidden by default) --}}
                <div id="progress-container" class="mt-4" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted" id="progress-text">Gerando SPED Fiscal...</small>
                        <small class="text-muted" id="progress-percent">0%</small>
                    </div>
                    <div class="progress progress-sped">
                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                             role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <hr class="mt-3">

                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="ri-information-line me-2 fs-5"></i>
                            <div>
                                O arquivo será gerado com base nas <strong>NF-e (saídas e entradas)</strong> e <strong>NFC-e</strong> 
                                do período selecionado. Certifique-se de que o <strong>Escritório Contábil</strong> 
                                e a <strong>Configuração do SPED</strong> estão preenchidos.
                                @if($stats && $stats['nfe_saida'] + $stats['nfce'] + $stats['nfe_entrada'] == 0)
                                <span class="d-block mt-1 text-warning">
                                    <i class="ri-alert-line"></i>
                                    Nenhuma nota aprovada encontrada no período. O SPED será gerado apenas com cabeçalho e rodapé.
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12" style="text-align: right;">
                        <a href="{{ route('sped-config.index') }}" class="btn btn-secondary px-4 me-2">
                            <i class="ri-settings-3-line"></i>
                            Configurar SPED
                        </a>
                        <a href="{{ route('escritorio-contabil.index') }}" class="btn btn-info px-4 me-2">
                            <i class="ri-building-2-line"></i>
                            Escritório Contábil
                        </a>
                        <button type="submit" class="btn btn-success px-5" id="btn-gerar">
                            <i class="ri-file-download-line"></i>
                            Gerar SPED
                        </button>
                    </div>
                </div>

                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        // Toggle inventário fields
        $('#inventario-switch').on('change', function() {
            if ($(this).is(':checked')) {
                $('#inventario-fields').slideDown(300);
            } else {
                $('#inventario-fields').slideUp(300);
            }
        });

        // Confirm and progress before generating
        $('#btn-gerar').on('click', function(e) {
            if (!confirm('Gerar o arquivo SPED para o período selecionado? Isso pode levar alguns segundos.')) {
                e.preventDefault();
                return false;
            }

            // Show progress bar
            $('#progress-container').slideDown(300);
            $(this).prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i> Gerando...');

            // Animate progress (simulated)
            var progress = 0;
            var $bar = $('#progress-bar');
            var $percent = $('#progress-percent');
            var $text = $('#progress-text');

            var interval = setInterval(function() {
                progress += Math.random() * 15;
                if (progress > 90) {
                    progress = 90;
                    clearInterval(interval);
                    $text.text('Finalizando arquivo SPED...');
                }
                $bar.css('width', progress + '%').attr('aria-valuenow', progress);
                $percent.text(Math.round(progress) + '%');
            }, 400);

            // Store interval so we can clear it on form submit
            $('#form-sped').data('progress-interval', interval);
        });

        // Clear progress interval on form submit (page will reload/redirect)
        $('#form-sped').on('submit', function() {
            var interval = $(this).data('progress-interval');
            if (interval) {
                clearInterval(interval);
            }
        });
    });
</script>
@endsection
