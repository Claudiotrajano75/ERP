@extends('layouts.app', ['title' => 'Alterar Tributação em Lote'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient,
.modulo-section-card .card-header.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Glass Filter (usado no card de seleção) ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control,
.modulo-glass-filter .form-select { height: 38px; }
.modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Section Card ─── */
.modulo-section-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-section-card .card-header { background: #f8f9fc; border-bottom: 1px solid #eef0f5; padding: 14px 16px; }
.modulo-section-card .card-header h5 { font-weight: 700; font-size: 14px; color: #2c2c44; margin: 0; }
.modulo-section-card .card-header h5 i { color: #302b63; }
.modulo-section-card .card-body { background: #fff; }
.modulo-section-card .card-footer { border-radius: 0 0 12px 12px; }

/* ─── Form Card (para campos de formulário) ─── */
.modulo-form-card label { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Product Checkbox Grid ─── */
.modulo-prod-grid .prod-check-item { border: 1px solid #eef0f5; border-radius: 10px; padding: 10px 12px; background: #fff; transition: all 0.15s ease; }
.modulo-prod-grid .prod-check-item:hover { border-color: #c5cae9; background: #fafbff; }
.modulo-prod-grid .prod-check-item label { cursor: pointer; font-size: 13px; color: #2c2c44; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ─── Section Header (para títulos de seção dentro dos cards) ─── */
.modulo-section-header { font-weight: 700; font-size: 14px; color: #2c2c44; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 20px; }

/* ─── Empty / Footer ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            {!!Form::open()
            ->post()
            ->route('produtopadrao-tributacao.set-tributacao')
            !!}

            <!-- ═══ Card Principal ═══ -->
            <div class="card border-0 shadow-sm modulo-section-card mb-4">

                <!-- Cabeçalho Premium -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-refresh-line"></i>
                                Alterar Tributação em Lote
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Aplique as configurações de um padrão de tributação a múltiplos produtos de uma só vez.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('produtopadrao-tributacao.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ Bloco 1: Seleção de Origem (Padrão) ═══ -->
                    <div class="modulo-glass-filter p-3 mb-4">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5 col-12">
                                {!!Form::select('padrao_id', 'Selecione o Padrão Tributário Destino', ['' => 'Selecione'] + $padroes->pluck('descricao', 'id')->all())
                                ->required()
                                ->attrs(['class' => 'form-select select2'])
                                !!}
                                <div class="form-text text-muted fs-11 mt-1">
                                    <i class="ri-information-line me-1 align-middle"></i>
                                    Ao selecionar um padrão, os parâmetros de impostos correspondentes serão carregados abaixo.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ Bloco 2: Parâmetros + Checklist (Revelado via JS) ═══ -->
                    <div class="form-trib d-none">

                        <!-- Card de Parâmetros Carregados -->
                        <div class="card border-0 shadow-sm modulo-section-card mb-4">
                            <div class="card-header">
                                <h5><i class="ri-calculator-line me-2"></i>Parâmetros Fiscais Carregados</h5>
                            </div>
                            <div class="card-body p-4">
                                @include('padrao_tributacao._forms', ['not_submit' => 1])
                            </div>
                        </div>

                        <!-- Card de Checklist de Produtos -->
                        <div class="card border-0 shadow-sm modulo-section-card mb-4">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <h5><i class="ri-checkbox-multiple-line me-2"></i>Produtos a Serem Atualizados</h5>
                                        <p class="text-danger mb-0 fs-12 mt-1">
                                            <i class="ri-alert-line align-middle me-1"></i>
                                            Desmarque os produtos que não deseja atualizar nesta operação.
                                        </p>
                                    </div>
                                    <div>
                                        <div class="form-check form-switch card-header-checkbox">
                                            <input type="checkbox" checked class="form-check-input" id="check-all">
                                            <label class="form-check-label fw-semibold" for="check-all">Selecionar Todos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4 modulo-glass-filter">
                                <div class="row g-3 modulo-prod-grid">
                                    @forelse($produtos as $p)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 produtos-check">
                                        <div class="prod-check-item d-flex align-items-center">
                                            <div class="form-check mb-0">
                                                <input type="checkbox" checked name="produto_check[]" class="form-check-input prod-check" value="{{ $p->id }}" id="prod-{{ $p->id }}">
                                                <label class="form-check-label fw-medium ms-1" for="prod-{{ $p->id }}" title="{{ $p->nome }}">
                                                    {{ $p->nome }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum produto cadastrado.</p>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Rodapé de Envio -->
                            <div class="card-footer bg-transparent border-top p-3">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('produtopadrao-tributacao.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-close-line align-middle me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success px-5" id="btn-store">
                                        <i class="ri-save-line align-middle me-1"></i> Aplicar Tributação em Lote
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            {!!Form::close()!!}

        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function() {
        // Mudança no select de Padrão Tributário
        $(document).on("change", "#inp-padrao_id", function () {
            let val = $(this).val();
            if(val) {
                $.get(path_url + "api/produtos/padrao", { padrao: val })
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
                    console.log(err);
                });
            } else {
                $('.form-trib').addClass('d-none');
            }
        });

        // Selecionar / Deselecionar todos
        $(document).on("click", "#check-all", function () {
            if($(this).is(':checked')){
                $('.prod-check').prop('checked', true);
            } else {
                $('.prod-check').prop('checked', false);
            }
        });

        // CFOPs Derivados do Blur
        $(document).on("blur", "#inp-cfop_estadual", function () {
            let val = $(this).val();
            if(val && val.length >= 4) {
                let suffix = val.substring(1, 4);
                $("input[name='cfop_outro_estado']").val('6' + suffix);
                $("input[name='cfop_entrada_estadual']").val('1' + suffix);
                $("input[name='cfop_entrada_outro_estado']").val('2' + suffix);
            }
        });
    });
</script>
@endsection
