<style>
/* ─── Seções do formulário ─── */
.uf-section { margin-bottom: 24px; }
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 28px; height: 28px; border-radius: 8px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 11.5px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 12.5px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field textarea.form-control { height: auto !important; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 24px; }
</style>

<div class="row g-4">
    <!-- ═══ SEÇÃO 1: IDENTIFICAÇÃO DO VEÍCULO ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-car-line"></i></span>
            Identificação & Registro do Veículo
            <small>dados básicos do veículo e emplacamento</small>
        </div>
        <div class="row g-3">
            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="placa"><i class="ri-barcode-box-line"></i> Placa</label>
                {!!Form::text('placa', '')->required()->id('placa')
                ->attrs(['class' => 'placa form-control', 'placeholder' => 'ABC1D23'])
                !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="uf"><i class="ri-map-pin-line"></i> UF Emplacamento</label>
                {!!Form::select('uf', '', App\Models\Cidade::estados())->id('uf')
                ->attrs(['class' => 'form-select select2'])->required()
                !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="cor"><i class="ri-palette-line"></i> Cor</label>
                {!!Form::text('cor', '')->required()->id('cor')
                ->attrs(['class' => 'form-control', 'data-mask' => 'AAAAAAAAAA', 'placeholder' => 'Branco, Prata...'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label required" for="marca"><i class="ri-shield-line"></i> Marca / Fabricante</label>
                {!!Form::text('marca', '')->required()->id('marca')
                ->attrs(['class' => 'form-control', 'data-mask' => 'AAAAAAAAAAAAAAAAAAA', 'placeholder' => 'Ex: Scania, Volvo, VW...'])
                !!}
            </div>

            <div class="col-md-3 col-12 uf-field">
                <label class="form-label required" for="modelo"><i class="ri-car-line"></i> Modelo</label>
                {!!Form::text('modelo', '')->required()->id('modelo')
                ->attrs(['class' => 'form-control', 'data-mask' => 'AAAAAAAAAAAAAAAAAAA', 'placeholder' => 'Ex: R450, FH 540, Gol...'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label required" for="rntrc"><i class="ri-file-text-line"></i> RNTRC</label>
                {!!Form::text('rntrc', '')->required()->id('rntrc')
                ->attrs(['class' => 'form-control', 'data-mask' => '00000000', 'placeholder' => '00000000'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label" for="renavam"><i class="ri-file-shield-line"></i> Renavam</label>
                {!!Form::text('renavam', '')->id('renavam')
                ->attrs(['class' => 'form-control', 'data-mask' => '000000000000', 'placeholder' => '000000000000'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label" for="taf"><i class="ri-hashtag"></i> TAF</label>
                {!!Form::text('taf', '')->id('taf')
                ->attrs(['class' => 'form-control', 'data-mask' => '00000000000000', 'placeholder' => '00000000000000'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label" for="numero_registro_estadual"><i class="ri-file-list-line"></i> Nº Registro Estadual</label>
                {!!Form::text('numero_registro_estadual', '')->id('numero_registro_estadual')
                ->attrs(['class' => 'form-control', 'data-mask' => '0000000000000000000000000'])
                !!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: ESPECIFICAÇÕES TÉCNICAS ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-settings-4-line"></i></span>
            Especificações Técnicas & Capacidade de Carga
            <small>características operacionais e de transporte</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label required" for="tipo"><i class="ri-dashboard-line"></i> Tipo de Veículo</label>
                {!!Form::select('tipo', '', ['' => 'Selecione'] + App\Models\Veiculo::tipos())->required()->id('tipo')
                ->attrs(['class' => 'form-select'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label required" for="tipo_carroceria"><i class="ri-truck-line"></i> Tipo de Carroceria</label>
                {!!Form::select('tipo_carroceria', '', ['' => 'Selecione'] + App\Models\Veiculo::tiposCarroceria())->id('tipo_carroceria')
                ->attrs(['class' => 'form-select'])->required()
                !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="tipo_rodado"><i class="ri-record-circle-line"></i> Tipo de Rodado</label>
                {!!Form::select('tipo_rodado', '', ['' => 'Selecione'] + App\Models\Veiculo::tiposRodado())->id('tipo_rodado')
                ->attrs(['class' => 'form-select'])->required()
                !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="tara"><i class="ri-scales-line"></i> Tara (KG)</label>
                {!!Form::tel('tara', '')->required()->id('tara')
                ->attrs(['class' => 'form-control', 'data-mask' => '0000000000', 'placeholder' => '0'])
                !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="capacidade"><i class="ri-inbox-line"></i> Capacidade (KG)</label>
                {!!Form::tel('capacidade', '')->required()->id('capacidade')
                ->attrs(['class' => 'form-control', 'data-mask' => '0000000000', 'placeholder' => '0'])
                !!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 3: PROPRIETÁRIO & MOTORISTA ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-star-line"></i></span>
            Proprietário & Condutor Responsável
            <small>titularidade e motorista vinculado</small>
        </div>
        <div class="row g-3">
            <div class="col-md-4 col-12 uf-field">
                <label class="form-label required" for="proprietario_nome"><i class="ri-user-line"></i> Nome do Proprietário</label>
                {!!Form::tel('proprietario_nome', '')->required()->id('proprietario_nome')
                ->attrs(['class' => 'form-control', 'placeholder' => 'Nome completo ou Razão Social'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label required" for="proprietario_documento"><i class="ri-id-card-line"></i> CPF / CNPJ do Proprietário</label>
                {!!Form::tel('proprietario_documento', '')->required()->id('proprietario_documento')
                ->attrs(['class' => 'cpf_cnpj form-control'])
                !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label required" for="proprietario_ie"><i class="ri-file-paper-line"></i> Inscrição Estadual (IE)</label>
                {!!Form::tel('proprietario_ie', '')->id('proprietario_ie')
                ->attrs(['class' => 'ie_rg form-control'])->required()
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label required" for="proprietario_uf"><i class="ri-map-pin-2-line"></i> UF do Proprietário</label>
                {!!Form::select('proprietario_uf', '', App\Models\Cidade::estados())->id('proprietario_uf')
                ->attrs(['class' => 'form-select select2'])->required()
                !!}
            </div>

            <div class="col-md-4 col-6 uf-field">
                <label class="form-label required" for="proprietario_tp"><i class="ri-user-shared-line"></i> Categoria do Proprietário</label>
                {!!Form::select('proprietario_tp', '', App\Models\Veiculo::tiposProprietario())->id('proprietario_tp')
                ->attrs(['class' => 'form-select'])->required()
                !!}
            </div>

            <div class="col-md-5 col-12 uf-field">
                <label class="form-label" for="funcionario_id"><i class="ri-steering-2-line"></i> Motorista / Funcionário Vinculado</label>
                {!!Form::select('funcionario_id', '', ['' => 'Nenhum motorista vinculado'] + $funcionarios->pluck('nome', 'id')->all())->id('funcionario_id')
                ->attrs(['class' => 'select2 form-select'])
                !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label class="form-label" for="status"><i class="ri-toggle-line"></i> Status de Ativação</label>
                <select name="status" id="status" class="form-select">
                    <option value="1" {{ (isset($item) && $item->status == 1) || !isset($item) ? 'selected' : '' }}>Ativo na Frota</option>
                    <option value="0" {{ isset($item) && $item->status == 0 ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('veiculos.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                <i class="ri-save-line"></i>
                {{ isset($item) ? 'Salvar Alterações do Veículo' : 'Cadastrar Veículo' }}
            </button>
        </div>
    </div>
</div>

