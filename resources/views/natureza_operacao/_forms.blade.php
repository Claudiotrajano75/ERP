<div class="row g-3">
    <!-- ═══ SEÇÃO 1: INFORMAÇÕES BÁSICAS ═══ -->
    <div class="col-12">
        <div class="card border shadow-sm mb-3">
            <div class="card-header bg-light py-2 px-3">
                <h5 class="section-title mb-0 fs-14">
                    <i class="ri-information-line text-primary"></i> 1. Informações Básicas
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        {!!Form::text('descricao', 'Descrição da Natureza de Operação')
                        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Venda de Mercadorias, Remessa, etc.'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::select('padrao', 'Natureza Padrão?', [0 => 'Não', 1 => 'Sim'])
                        ->attrs(['class' => 'form-select'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::select('sobrescrever_cfop', 'Sobrescrever CFOP do Produto?', [0 => 'Não', 1 => 'Sim'])
                        ->attrs(['class' => 'form-select'])
                        ->required()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: DADOS FISCAIS E TRIBUTÁRIOS ═══ -->
    <div class="col-12">
        <div class="card border shadow-sm mb-3">
            <div class="card-header bg-light py-2 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="section-title mb-0 fs-14">
                        <i class="ri-file-list-3-line text-success"></i> 2. Parâmetros Fiscais e Tributários (Opcionais)
                    </h5>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5 fs-11">
                        Sobrescreve dados do cadastro de produto no XML
                    </span>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        {!!Form::select('cst_csosn', 'CST / CSOSN Padrão', ['' => 'Selecione (Opcional)'] + $listaCTSCSOSN)
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>
                    <div class="col-md-6">
                        {!!Form::select('cst_pis', 'CST PIS', ['' => 'Selecione (Opcional)'] + App\Models\Produto::listaCST_PIS_COFINS())
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>
                    <div class="col-md-6">
                        {!!Form::select('cst_cofins', 'CST COFINS', ['' => 'Selecione (Opcional)'] + App\Models\Produto::listaCST_PIS_COFINS())
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>
                    <div class="col-md-6">
                        {!!Form::select('cst_ipi', 'CST IPI', ['' => 'Selecione (Opcional)'] + App\Models\Produto::listaCST_IPI())
                        ->attrs(['class' => 'form-select'])
                        !!}
                    </div>

                    <!-- Alíquotas -->
                    <div class="col-md-3">
                        {!!Form::tel('perc_icms', '% ICMS')
                        ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('perc_pis', '% PIS')
                        ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('perc_cofins', '% COFINS')
                        ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('perc_ipi', '% IPI')
                        ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                        !!}
                    </div>

                    @if(__isPlanoFiscal())
                    <div class="col-md-3">
                        {!!Form::tel('perc_ibs', '% IBS')
                        ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('perc_cbs', '% CBS')
                        ->attrs(['class' => 'form-control percentual', 'placeholder' => '0,00%'])
                        !!}
                    </div>
                    @endif

                    <!-- CFOPs -->
                    <div class="col-md-3">
                        {!!Form::tel('cfop_estadual', 'CFOP Saída Estadual')
                        ->attrs(['class' => 'form-control cfop', 'id' => 'inp-cfop_estadual', 'placeholder' => '5102'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('cfop_outro_estado', 'CFOP Saída Interestadual')
                        ->attrs(['class' => 'form-control cfop', 'id' => 'inp-cfop_outro_estado', 'placeholder' => '6102'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('cfop_entrada_estadual', 'CFOP Entrada Estadual')
                        ->attrs(['class' => 'form-control cfop', 'id' => 'inp-cfop_entrada_estadual', 'placeholder' => '1102'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::tel('cfop_entrada_outro_estado', 'CFOP Entrada Interestadual')
                        ->attrs(['class' => 'form-control cfop', 'id' => 'inp-cfop_entrada_outro_estado', 'placeholder' => '2102'])
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ BOTÕES DE AÇÃO ═══ -->
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('natureza-operacao.index') }}" class="btn btn-outline-secondary">
                <i class="ri-close-line me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line me-1"></i> Salvar Natureza
            </button>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $(document).on("blur", "#inp-cfop_estadual", function () {
        let v = $(this).val().substring(1,4);
        if(v.length === 3) {
            if(!$("#inp-cfop_outro_estado").val()) $("#inp-cfop_outro_estado").val('6' + v);
            if(!$("#inp-cfop_entrada_estadual").val()) $("#inp-cfop_entrada_estadual").val('1' + v);
            if(!$("#inp-cfop_entrada_outro_estado").val()) $("#inp-cfop_entrada_outro_estado").val('2' + v);
        }
    });
</script>
@endsection