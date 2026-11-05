@extends('layouts.app', ['title' => 'SPED Fiscal - Empresa Selecionada'])

@section('css')
<style type="text/css">
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    .stat-card {
        border-radius: 10px;
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
    .fade-in {
        animation: fadeIn 0.4s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .progress-sped {
        height: 8px;
        border-radius: 4px;
    }
    .file-info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px dashed #adb5bd;
    }
    .company-pill {
        background: rgba(255,255,255,0.15);
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                <div class="card-header modulo-header-gradient py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            SPED Fiscal — EFD ICMS/IPI
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Geração do arquivo de escrituração fiscal mensal para entrega ao Fisco / PVA.
                        </p>
                    </div>
                    <div>
                        <span class="company-pill">
                            <i class="ri-building-line"></i> {{ $empresa->nome }} ({{ $empresa->cpf_cnpj }})
                        </span>
                    </div>
                </div>
                
                <div class="card-body bg-white p-4">

                    {{-- Cards de Resumo Fiscal --}}
                    @if($stats)
                    <div class="row g-3 mb-4 fade-in">
                        <div class="col-md-3 col-6">
                            <div class="card stat-card border-0 shadow-sm h-100 bg-primary bg-gradient text-white">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon bg-white bg-opacity-25 text-white">
                                        <i class="ri-logout-box-r-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-white-50 d-block fw-medium">NF-e Saída</small>
                                        <span class="fs-4 fw-bold">{{ $stats['nfe_saida'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card stat-card border-0 shadow-sm h-100 bg-success bg-gradient text-white">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon bg-white bg-opacity-25 text-white">
                                        <i class="ri-store-2-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-white-50 d-block fw-medium">NFC-e (PDV)</small>
                                        <span class="fs-4 fw-bold">{{ $stats['nfce'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card stat-card border-0 shadow-sm h-100 bg-info bg-gradient text-white">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon bg-white bg-opacity-25 text-white">
                                        <i class="ri-login-box-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-white-50 d-block fw-medium">NF-e Entrada (Compras)</small>
                                        <span class="fs-4 fw-bold">{{ $stats['nfe_entrada'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card stat-card border-0 shadow-sm h-100 bg-warning bg-gradient text-dark">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon bg-white bg-opacity-25 text-dark">
                                        <i class="ri-settings-3-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-dark d-block fw-medium" style="opacity: 0.7;">Parâmetros Fiscais</small>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            <span class="badge {{ $stats['escritorio_configurado'] ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                <i class="ri-building-2-line"></i> Contábil
                                            </span>
                                            <span class="badge {{ $stats['sped_configurado'] ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                <i class="ri-settings-3-line"></i> SPED
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Card de Último SPED Gerado --}}
                    @if($spedFileInfo)
                    <div class="alert alert-success file-info-card d-flex align-items-start gap-3 fade-in rounded-3 mb-4" role="alert">
                        <i class="ri-file-text-line fs-3 text-success mt-1"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark">Último arquivo SPED gerado para esta empresa</strong>
                            <small class="d-block text-muted">
                                Gerado em {{ $spedFileInfo['data'] }} — 
                                {{ number_format($spedFileInfo['tamanho'] / 1024, 1) }} KB — 
                                {{ $spedFileInfo['linhas'] }} linhas
                            </small>
                            <a href="{{ $spedFileInfo['url'] }}" class="btn btn-sm btn-success shadow-sm mt-2 fw-medium" download>
                                <i class="ri-download-2-line me-1"></i> Baixar Arquivo Atual (.TXT)
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($stats && (!$stats['escritorio_configurado'] || !$stats['sped_configurado']))
                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 fade-in">
                        <div class="d-flex align-items-center gap-3">
                            <i class="ri-alert-fill fs-2 text-warning"></i>
                            <div>
                                <h6 class="mb-1 text-dark fw-bold">Pendências de Configuração Fiscal</h6>
                                <p class="mb-0 fs-13 text-muted">
                                    Para gerar o arquivo SPED completo, a empresa precisa ter cadastrado:
                                    @if(!$stats['escritorio_configurado']) <strong class="text-danger">Escritório Contábil</strong> @endif
                                    @if(!$stats['escritorio_configurado'] && !$stats['sped_configurado']) e @endif
                                    @if(!$stats['sped_configurado']) <strong class="text-danger">Configuração SPED</strong> @endif.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('contador-empresa.sped-store') }}" method="POST" id="form-sped">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Data Inicial</label>
                                <input type="date" name="data_inicial" class="form-control" value="{{ $firstDate ?? date('Y-m-01') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Data Final</label>
                                <input type="date" name="data_final" class="form-control" value="{{ $lastDate ?? date('Y-m-t') }}" required>
                            </div>

                            <div class="col-md-3 d-flex align-items-end mb-1">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" name="inventario" id="inventario-switch" value="1">
                                    <label class="form-check-label fs-6 mt-1 ms-1" for="inventario-switch">Incluir Bloco H (Inventário)</label>
                                </div>
                            </div>
                        </div>

                        <div id="inventario-fields" class="row g-3 mt-1" style="display: none;">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Data do Inventário</label>
                                <input type="date" name="data_inventario" class="form-control" value="{{ date('Y-m-t') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Motivo do Inventário</label>
                                <select name="motivo_inventario" class="form-select">
                                    <option value="01">01 - No final do período</option>
                                    <option value="02">02 - Mudança de forma de tributação</option>
                                    <option value="03">03 - Interrupção de atividades</option>
                                    <option value="04">04 - Alteração de perfil</option>
                                    <option value="05">05 - Determinação dos fiscos</option>
                                </select>
                            </div>
                        </div>

                        {{-- Barra de Progresso --}}
                        <div id="progress-container" class="mt-4 p-3 bg-light rounded" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-dark fs-6" id="progress-text">Gerando SPED Fiscal...</strong>
                                <strong class="text-primary fs-6" id="progress-percent">0%</strong>
                            </div>
                            <div class="progress progress-sped">
                                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                     role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <hr class="mt-4 mb-4 border-secondary border-opacity-25">

                        <div class="row">
                            <div class="col-md-6 d-flex align-items-center">
                                <span class="text-muted fs-13">
                                    <i class="ri-shield-check-line text-success me-1"></i> O arquivo gerado estará no layout do PVA SPED Fiscal (EFD ICMS/IPI).
                                </span>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('contador-empresa.fechamento') }}" class="btn btn-outline-primary px-4 me-2 shadow-sm fw-medium">
                                    <i class="ri-file-zip-line me-1"></i>
                                    Ir para Fechamento Mensal
                                </a>
                                <button type="submit" class="btn btn-success px-5 shadow-sm fw-semibold" id="btn-gerar">
                                    <i class="ri-file-download-line me-1"></i>
                                    Gerar SPED da Empresa
                                </button>
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
    $(document).ready(function() {
        var escritorioConfigurado = {{ $stats && $stats['escritorio_configurado'] ? 'true' : 'false' }};
        var spedConfigurado = {{ $stats && $stats['sped_configurado'] ? 'true' : 'false' }};

        $('#inventario-switch').on('change', function() {
            if ($(this).is(':checked')) {
                $('#inventario-fields').slideDown(300);
            } else {
                $('#inventario-fields').slideUp(300);
            }
        });

        $('#form-sped').on('submit', function(e) {
            if (!escritorioConfigurado || !spedConfigurado) {
                e.preventDefault();
                var msg = 'Atenção:\n';
                if (!escritorioConfigurado && !spedConfigurado) {
                    msg += 'Esta empresa ainda não possui Escritório Contábil e Parâmetros SPED cadastrados!';
                } else if (!escritorioConfigurado) {
                    msg += 'Esta empresa não possui Escritório Contábil cadastrado!';
                } else {
                    msg += 'Esta empresa não possui Parâmetros SPED cadastrados!';
                }
                if (typeof swal === 'function') {
                    swal('Atenção', msg, 'warning');
                } else {
                    alert(msg);
                }
                return false;
            }

            if (!confirm('Gerar o arquivo SPED da empresa selecionada para o período?')) {
                e.preventDefault();
                return false;
            }

            $('#progress-container').slideDown(300);
            var $btn = $('#btn-gerar');
            $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i> Gerando SPED...');

            var progress = 0;
            var $bar = $('#progress-bar');
            var $percent = $('#progress-percent');
            var $text = $('#progress-text');

            $bar.removeClass('bg-primary').addClass('bg-success');

            var interval = setInterval(function() {
                progress += Math.floor(Math.random() * 15) + 8;
                if (progress >= 90) {
                    progress = 90;
                    clearInterval(interval);
                    $text.text('Estruturando blocos fiscais e compilando arquivo...');

                    setTimeout(function() {
                        $bar.css('width', '100%').attr('aria-valuenow', 100);
                        $percent.text('100%');
                        $text.html('<i class="ri-checkbox-circle-fill text-success me-1"></i> SPED gerado com sucesso! O download foi iniciado.');

                        setTimeout(function() {
                            $btn.prop('disabled', false).html('<i class="ri-file-download-line me-1"></i> Gerar SPED Novamente');
                        }, 2500);
                    }, 2000);
                }
                $bar.css('width', progress + '%').attr('aria-valuenow', progress);
                $percent.text(Math.round(progress) + '%');
            }, 300);
        });
    });
</script>
@endsection

