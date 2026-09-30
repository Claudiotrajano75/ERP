<style>
    .modulo-section-header { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #1f2937 !important; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 18px; }
    .modulo-section-header i { color: #4338ca; font-size: 18px; }
    .form-label, label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748b; margin-bottom: 4px; }
    .form-control, .form-select, select { border-radius: 9px; border: 1px solid #d1d5db !important; font-size: 13.5px; background: #fff; box-shadow: none; }
    .form-control:focus, .form-select:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.12) !important; background: #fff; }
</style>

<div class="row g-3 text-dark">

    <!-- ═══ Seção 1: Identificação do Padrão ═══ -->
    @if(!isset($not_submit))
    <div class="col-12">
        <div class="alert alert-info border-0 d-flex align-items-center mb-2" role="alert" style="border-radius: 10px; background: #eef2ff; color: #4338ca;">
            <i class="ri-information-line me-2 fs-18"></i>
            <div>
                Este padrão tributário será associado aos produtos da empresa selecionada, agilizando o preenchimento fiscal e a emissão de notas.
            </div>
        </div>
    </div>
    <div class="col-12 mt-2">
        <h5 class="modulo-section-header">
            <i class="ri-information-line"></i>
            1. Identificação do Padrão
        </h5>
        <div class="row g-3">
            <div class="col-md-7 col-12">
                {!! Form::text('descricao', 'Descrição do Padrão')
                    ->placeholder('Ex: Venda Geral Simples Nacional, Substituição Tributária ST, Revenda')
                    ->required()
                    ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-5 col-12">
                {!! Form::select('padrao', 'Definir como Padrão Principal da Empresa', [0 => 'Não', 1 => 'Sim (Principal)'])
                    ->required()
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
        </div>
    </div>
    @else
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!! Form::select('padrao', 'Padrão Principal', [0 => 'Não', 1 => 'Sim'])->required()->attrs(['class' => 'form-select']) !!}
            </div>
        </div>
    </div>
    @endif

    <!-- ═══ Seção 2: Classificação & Alíquotas ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header">
            <i class="ri-percent-line"></i>
            2. Classificação & Alíquotas
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!! Form::select('ncm', 'NCM')
                    ->options(isset($item) && $item->ncm ? [$item->ncm => ($item->_ncm ? $item->_ncm->descricao : $item->ncm)] : [])
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('cest', 'CEST')
                    ->attrs(['class' => 'form-control cest'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('perc_red_bc', '% Redução BC')
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!! Form::tel('perc_icms', '% ICMS')
                    ->attrs(['class' => 'form-control percentual text-end fw-semibold text-primary'])
                    ->required()
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('perc_pis', '% PIS')
                    ->required()
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('perc_cofins', '% COFINS')
                    ->required()
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('perc_ipi', '% IPI')
                    ->required()
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>

            @if(__isPlanoFiscal())
            <div class="col-md-3 col-6">
                {!! Form::tel('perc_ibs', '% IBS')
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('perc_cbs', '% CBS')
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ Seção 3: Códigos de Situação Tributária (CST / CSOSN) ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header">
            <i class="ri-key-line"></i>
            3. Códigos de Situação Tributária (CST / CSOSN)
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!! Form::select('cst_csosn', 'CST/CSOSN ICMS', ['' => 'Selecione'] + App\Models\Produto::listaCSTCSOSN())
                    ->required()
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!! Form::select('cEnq', 'Código de Enquadramento do IPI', ['' => 'Selecione'] + App\Models\Produto::listaCenqIPI())
                    ->required()
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>

            <div class="col-md-4 col-12">
                {!! Form::select('cst_pis', 'CST PIS', ['' => 'Selecione'] + App\Models\Produto::listaCST_PIS_COFINS())
                    ->required()
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-4 col-12">
                {!! Form::select('cst_cofins', 'CST COFINS', ['' => 'Selecione'] + App\Models\Produto::listaCST_PIS_COFINS())
                    ->required()
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-4 col-12">
                {!! Form::select('cst_ipi', 'CST IPI', ['' => 'Selecione'] + App\Models\Produto::listaCST_IPI())
                    ->required()
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
        </div>
    </div>

    <!-- ═══ Seção 4: Códigos Fiscais de Operações (CFOPs) ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header">
            <i class="ri-route-line"></i>
            4. Códigos Fiscais de Operações (CFOPs)
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!! Form::tel('cfop_estadual', 'CFOP Saída Estadual')
                    ->required()
                    ->attrs(['class' => 'form-control cfop text-center fw-bold'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('cfop_outro_estado', 'CFOP Saída Interestadual')
                    ->required()
                    ->attrs(['class' => 'form-control cfop text-center fw-bold'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('cfop_entrada_estadual', 'CFOP Entrada Estadual')
                    ->required()
                    ->attrs(['class' => 'form-control cfop text-center fw-bold'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!! Form::tel('cfop_entrada_outro_estado', 'CFOP Entrada Interestadual')
                    ->required()
                    ->attrs(['class' => 'form-control cfop text-center fw-bold'])
                !!}
            </div>
        </div>
    </div>

    <!-- ═══ Seção 5: Substituição Tributária (ST) & Benefícios ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header">
            <i class="ri-shield-star-line"></i>
            5. Substituição Tributária (ST) & Benefícios
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!! Form::select('modBCST', 'Modalidade BC-ST', App\Models\Produto::modalidadesBCST())
                    ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!! Form::tel('pICMSST', '% ICMS ST')
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!! Form::tel('pMVAST', '% MVA ST')
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!! Form::tel('redBCST', '% Redução BC ST')
                    ->attrs(['class' => 'form-control percentual text-end'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!! Form::tel('codigo_beneficio_fiscal', 'Cód. Benefício')
                    ->attrs(['class' => 'form-control'])
                !!}
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    @if(!isset($not_submit))
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('contador-empresa.padrao-tributacao') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Padrão
            </button>
        </div>
    </div>
    @endif

</div>
