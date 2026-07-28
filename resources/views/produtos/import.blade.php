@extends('layouts.app', ['title' => 'Importar Produtos'])

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

    /* ─── Cards & Badges de Campos ─── */
    .field-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .field-card:hover {
        background: #f1f3f5;
        border-color: #dee2e6;
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
                            <i class="ri-file-upload-line"></i>
                            Importar Produtos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Utilize uma planilha Excel para fazer o cadastro em massa de produtos de forma rápida.
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
                
                {{-- ═══ INSTRUÇÕES DE IMPORTAÇÃO ═══ --}}
                <div class="alert alert-info border-0 shadow-none d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="ri-information-line fs-20"></i>
                    <div>
                        <strong>Atenção:</strong> Os campos com <span class="text-danger">*</span> são obrigatórios na estrutura do arquivo.
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 col-md-6">
                        <div class="field-card"><h5><strong class="text-primary">Nome</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo texto)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Categoria</strong> <span class="text-muted fs-12">(tipo texto)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Valor de venda</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo moeda)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Valor de compra</strong> <span class="text-muted fs-12">(tipo moeda)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">NCM</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Código de barras</strong> <span class="text-muted fs-12">(tipo texto)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CEST</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CST/CSOSN</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CST PIS</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CST COFINS</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CST IPI</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">% Red. base de cálculo</strong> <span class="text-muted fs-12">(tipo percentual)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Origem</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Código de enquadramento IPI</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="field-card"><h5><strong class="text-primary">CFOP estadual</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CFOP outro estado</strong><span class="text-danger">*</span> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Código do benefício</strong> <span class="text-muted fs-12">(tipo texto)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Unidade</strong> <span class="text-muted fs-12">(tipo texto)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Origem</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Gerenciar Estoque</strong> <span class="text-muted fs-12">(tipo binário 1 ou 0)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">%ICMS</strong> <span class="text-muted fs-12">(tipo percentual)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">%PIS</strong> <span class="text-muted fs-12">(tipo percentual)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">%COFINS</strong> <span class="text-muted fs-12">(tipo percentual)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">%IPI</strong> <span class="text-muted fs-12">(tipo percentual)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CFOP entrada estadual</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">CFOP entrada outro estado</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Estoque</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                        <div class="field-card"><h5><strong class="text-primary">Estoque mínimo</strong> <span class="text-muted fs-12">(tipo numérico)</span></h5></div>
                    </div>
                </div>

                {{-- ═══ ACTIONS & UPLOAD FORM ═══ --}}
                <div class="card border-0 bg-light p-4 mb-0 rounded rounded-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                        <h5 class="m-0 fw-semibold text-dark">Preencha o modelo e envie abaixo</h5>
                        <a href="{{ route('produtos.import-download') }}" class="btn btn-outline-primary btn-sm">
                            <i class="ri-file-download-line align-middle me-1"></i>
                            Baixar Planilha Modelo (.xlsx)
                        </a>
                    </div>

                    <form id="form-import" method="post" action="{{ route('produtos.import-store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row align-items-end g-3">
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-medium text-muted">Selecionar arquivo excel (.xls, .xlsx)</label>
                                <div>
                                    <span class="btn btn-primary btn-file w-100">
                                        <i class="ri-file-search-line align-middle me-1"></i>
                                        Procurar arquivo...
                                        <input accept=".xls, .xlsx" name="file" type="file" id="file">
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-success fw-semibold fs-13" id="filename"></span>
                                </div>
                            </div>

                            @if(__countLocalAtivo() > 1)
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-medium text-muted">Disponibilidade de Local</label>
                                <select required class="select2 form-control select2-multiple" data-toggle="select2" name="locais[]" multiple="multiple">
                                    @foreach(__getLocaisAtivoUsuario() as $local)
                                    <option @if(in_array($local->id, (isset($item) ? $item->locais->pluck('localizacao_id')->toArray() : []))) selected @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <input type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
                            @endif

                            <div class="col-12 col-md-3">
                                <button type="button" class="btn btn-success btn-save w-100">
                                    <i class="ri-upload-cloud-line align-middle me-1"></i>
                                    Importar produtos
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
            alert('Por favor, selecione um arquivo para importar.');
            return;
        }
        $('#form-import').submit();
        $("body").addClass("loading");
    });
</script>
@endsection
