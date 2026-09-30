@extends('layouts.app', ['title' => 'Aplicar Tributação em Lote'])

@section('css')
<style>
    .modulo-section-card {
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
    }
    .modulo-section-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e9ecf3;
        padding: 14px 18px;
    }
    .modulo-section-card .card-header h5 {
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        margin: 0;
    }
    .modulo-section-card .card-header h5 i {
        color: #4338ca;
    }

    .prod-filter-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
    }
    .prod-check-item {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        background: #fff;
        transition: all .15s ease;
        height: 100%;
    }
    .prod-check-item:hover {
        border-color: #6366f1;
        background: #f8faff;
    }
    .prod-check-item label {
        cursor: pointer;
        font-size: 12.5px;
        color: #1e293b;
        font-weight: 500;
        line-height: 1.3;
    }
    .prod-search-input {
        border-radius: 9px;
        border: 1px solid #cbd5e1;
        padding: 7px 12px;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm modulo-form-card">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-refresh-line"></i> Aplicar Tributação de Produtos em Lote
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Aplique os parâmetros fiscais de um padrão tributário a múltiplos produtos de uma só vez.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('produtopadrao-tributacao.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar aos Padrões
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {!! Form::open()->post()->route('produtopadrao-tributacao.set-tributacao') !!}

                {{-- Bloco 1: Seleção do Padrão --}}
                <div class="card border-0 shadow-sm modulo-section-card mb-4">
                    <div class="card-header">
                        <h5><i class="ri-scales-3-line me-2"></i>1. Selecione o Padrão Tributário Modelo</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label text-uppercase fw-bold text-muted fs-11">Padrão Tributário</label>
                                <select name="padrao_id" id="inp-padrao_id" class="form-select select2" required>
                                    <option value="">Selecione o padrão tributário</option>
                                    @foreach($padroes as $p)
                                        <option value="{{ $p->id }}">{{ $p->descricao }} (ICMS: {{ $p->perc_icms }}% | CST: {{ $p->cst_csosn }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted fs-12 mt-1">
                                    <i class="ri-information-line me-1"></i> Ao selecionar o padrão, os parâmetros fiscais serão preenchidos automaticamente.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bloco 2: Formulário e Seleção de Produtos --}}
                <div class="form-trib d-none">
                    <!-- Parâmetros Carregados -->
                    <div class="card border-0 shadow-sm modulo-section-card mb-4">
                        <div class="card-header">
                            <h5><i class="ri-calculator-line me-2"></i>2. Parâmetros Fiscais Carregados</h5>
                        </div>
                        <div class="card-body p-4">
                            @include('padrao_tributacao._forms', ['not_submit' => 1])
                        </div>
                    </div>

                    <!-- Produtos a serem Atualizados -->
                    <div class="card border-0 shadow-sm modulo-section-card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h5><i class="ri-checkbox-multiple-line me-2"></i>3. Selecione os Produtos a Atualizar ({{ count($produtos) }} cadastrados)</h5>
                                    <small class="text-danger">
                                        <i class="ri-alert-line me-1"></i> Desmarque os produtos que NÃO devem receber essa tributação.
                                    </small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="text" id="filtro-produto-nome" class="form-control form-control-sm prod-search-input" placeholder="Filtrar por nome...">
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" checked class="form-check-input" id="check-all">
                                        <label class="form-check-label fw-semibold fs-12" for="check-all">Marcar Todos</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2" id="grid-produtos" style="max-height: 420px; overflow-y: auto;">
                                @forelse($produtos as $p)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 prod-col" data-nome="{{ strtolower($p->nome) }}">
                                    <div class="prod-check-item d-flex align-items-center">
                                        <div class="form-check mb-0 w-100">
                                            <input type="checkbox" checked name="produto_check[]" class="form-check-input prod-check" value="{{ $p->id }}" id="prod-{{ $p->id }}">
                                            <label class="form-check-label ms-1 d-block text-truncate" for="prod-{{ $p->id }}" title="{{ $p->nome }}">
                                                {{ $p->nome }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-4 text-muted">
                                    <i class="ri-inbox-line fs-32 d-block mb-1 text-secondary"></i>
                                    Nenhum produto cadastrado.
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer bg-light p-3 border-top">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('produtopadrao-tributacao.index') }}" class="dash-btn dash-btn-light px-4">
                                    <i class="ri-close-line me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-submit-lote">
                                    <i class="ri-check-double-line me-1"></i> Aplicar Tributação em Lote
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function(){
        $(document).on("change", "#inp-padrao_id", function () {
            let val = $(this).val();
            if(val) {
                let baseUrl = typeof path_url !== 'undefined' ? path_url : '/';
                $.get(baseUrl + "api/produtos/padrao", { padrao: val })
                .done((result) => {
                    $('.form-trib').removeClass('d-none');
                    $('#inp-ncm').val(result.ncm);
                    $('#inp-cest').val(result.cest);
                    $('#inp-perc_icms').val(result.perc_icms);
                    $('#inp-perc_pis').val(result.perc_pis);
                    $('#inp-perc_cofins').val(result.perc_cofins);
                    $('#inp-perc_ipi').val(result.perc_ipi);
                    $('#inp-cst_csosn').val(result.cst_csosn).change();
                    $('#inp-cst_pis').val(result.cst_pis).change();
                    $('#inp-cst_cofins').val(result.cst_cofins).change();
                    $('#inp-cst_ipi').val(result.cst_ipi).change();
                    $('#inp-cEnq').val(result.cEnq).change();
                    $('#inp-cfop_estadual').val(result.cfop_estadual);
                    $('#inp-cfop_outro_estado').val(result.cfop_outro_estado);
                    $('#inp-codigo_beneficio_fiscal').val(result.codigo_beneficio_fiscal);
                    $('#inp-cfop_entrada_estadual').val(result.cfop_entrada_estadual);
                    $('#inp-cfop_entrada_outro_estado').val(result.cfop_entrada_outro_estado);
                })
                .fail((err) => {
                    console.error("Erro ao carregar padrão tributário:", err);
                });
            } else {
                $('.form-trib').addClass('d-none');
            }
        });

        $(document).on("click", "#check-all", function () {
            let checked = $(this).is(':checked');
            $('.prod-check').prop('checked', checked);
        });

        $('#filtro-produto-nome').on('keyup', function(){
            let term = $(this).val().toLowerCase();
            $('.prod-col').each(function(){
                let nome = $(this).data('nome');
                if(nome.indexOf(term) > -1){
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#btn-submit-lote').on('click', function(e){
            let count = $('.prod-check:checked').length;
            if(count === 0){
                e.preventDefault();
                alert('Selecione ao menos um produto para atualizar a tributação.');
                return false;
            }
            if(!confirm('Deseja realmente atualizar as regras fiscais de ' + count + ' produtos selecionados?')){
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection
