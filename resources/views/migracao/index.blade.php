@extends('layouts.app', ['title' => 'Migração de Dados Legados'])

@section('css')
<style type="text/css">
    /* ─── Header Gradiente ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .modulo-header-gradient .modulo-title {
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .modulo-header-gradient .modulo-title i {
        background: rgba(255,255,255,0.12);
        padding: 8px;
        border-radius: 10px;
        color: #a8b5ff;
    }
    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255,255,255,0.6) !important;
        font-weight: 400;
    }
    .modulo-header-gradient .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .modulo-header-gradient .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    /* ─── Inputs de Arquivo Customizados ─── */
    .btn-file {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-database-2-line"></i>
                            Migração de Dados
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Migração Simplificada de Dados (SQL Server -> Sistema ERP).
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('produtos.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                {{-- ═══ INSTRUÇÕES DE MIGRAÇÃO ═══ --}}
                <div class="alert alert-info border-0 shadow-none d-flex gap-2 mb-4" role="alert">
                    <i class="ri-information-line fs-22 text-info mt-1"></i>
                    <div>
                        <h5 class="alert-heading fw-semibold mb-1">Instruções Importantes</h5>
                        <p class="mb-2 fs-13">Utilize esta ferramenta para importar o CSV gerado pelo comando SQL do sistema antigo.</p>
                        <ul class="mb-0 fs-13 ps-3">
                            <li>Cadastrará produtos novos baseados no Nome ou Código de Barras (EAN).</li>
                            <li>Preencherá automaticamente NCM com <code>00000000</code> se estiver vazio.</li>
                            <li>Definirá tributação padrão para Simples Nacional (CFOP 5102, CSOSN 102).</li>
                            <li>Produtos com mesmo nome ou código de barras já existentes serão <strong>ignorados</strong>.</li>
                        </ul>
                    </div>
                </div>

                {{-- ═══ BUSCA INTELIGENTE DE NCM (Se aplicável) ═══ --}}
                @if(Route::has('migracao.ncm_finder'))
                <div class="mb-4">
                    <a href="{{ route('migracao.ncm_finder') }}" class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center justify-content-between">
                        <div>
                            <i class="ri-search-eye-line fs-18 align-middle me-2"></i>
                            <strong>Quer atualizar os NCMs automaticamente?</strong>
                            <span class="d-block text-muted fs-12 mt-1">Clique aqui para usar a Busca Inteligente de NCMs.</span>
                        </div>
                        <i class="ri-arrow-right-s-line fs-20"></i>
                    </a>
                </div>
                @endif

                {{-- ═══ UPLOAD & CONFIGURAÇÃO ═══ --}}
                <div class="card border-0 bg-light p-4 rounded-3 mb-0">
                    <form id="form-import" method="post" action="{{ route('migracao.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="empresa_id" value="{{ request()->empresa_id }}">

                        <div class="row align-items-end g-3">
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-medium text-muted">Selecione o arquivo CSV/Excel (.csv, .txt)</label>
                                <div>
                                    <span class="btn btn-primary btn-file w-100">
                                        <i class="ri-file-search-line align-middle me-1"></i>
                                        Escolher Arquivo...
                                        <input accept=".csv, .txt" name="file" type="file" id="file" required>
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-success fw-semibold fs-13" id="filename"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-medium text-muted">Localização de Estoque Padrão</label>
                                @if(__countLocalAtivo() > 1)
                                    <select required class="form-select select2" name="local_id">
                                        @foreach(__getLocaisAtivoUsuario() as $local)
                                        <option value="{{ $local->id }}">{{ $local->descricao }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
                                    <input type="text" class="form-control" value="{{ __getLocalAtivo() ? __getLocalAtivo()->descricao : '' }}" readonly>
                                @endif
                            </div>

                            <div class="col-12 col-md-3">
                                <button type="button" class="btn btn-success btn-save w-100">
                                    <i class="ri-play-circle-line align-middle me-1"></i>
                                    Iniciar Migração
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
    $('#file').change(function() {
        var filename = $(this).val().split('\\').pop();
        $('#filename').text('Arquivo selecionado: ' + filename);
    });

    $('.btn-save').click(() => {
        if(!$('#file').val()){
            alert('Por favor, selecione um arquivo CSV para a migração.');
            return;
        }
        $('#form-import').submit();
        $("body").addClass("loading");
    });
</script>
@endsection
