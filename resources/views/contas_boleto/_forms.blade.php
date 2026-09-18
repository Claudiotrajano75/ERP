<div class="text-dark">
    <!-- Seção 1: Dados Bancários -->
    <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-bank-card-line text-primary me-2 align-middle fs-18"></i> 1. Dados Bancários & Convênio</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            {!!Form::select('banco', 'Banco', ['' => 'Selecione'] + \App\Models\ContaBoleto::bancos())->required()
            ->attrs(['class' => 'form-select'])
            !!}
        </div>
        <div class="col-md-2 col-6">
            {!!Form::text('agencia', 'Agência')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 1234'])!!}
        </div>
        <div class="col-md-2 col-6">
            {!!Form::text('conta', 'Conta Corrente')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 12345-6'])!!}
        </div>
        <div class="col-md-2 col-6">
            {!!Form::tel('carteira', 'Carteira')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 17'])!!}
        </div>
        <div class="col-md-2 col-6">
            {!!Form::tel('convenio', 'Convênio')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 1234567'])!!}
        </div>

        <div class="col-md-5">
            {!!Form::text('titular', 'Titular da Conta')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Razão Social / Nome'])!!}
        </div>
        <div class="col-md-3">
            {!!Form::text('documento', 'CPF/CNPJ')->required()
            ->attrs(['class' => 'cpf_cnpj form-control', 'placeholder' => '00.000.000/0000-00'])
            !!}
        </div>
        <div class="col-md-2 col-6">
            {!!Form::select('tipo', 'Layout CNAB', ['Cnab400' => 'Cnab400', 'Cnab240' => 'Cnab240'])->required()
            ->attrs(['class' => 'form-select'])
            !!}
        </div>
        <div class="col-md-2 col-6">
            {!!Form::select('padrao', 'Definir Principal', [0 => 'Não', 1 => 'Sim'])->required()
            ->attrs(['class' => 'form-select'])
            !!}
        </div>
    </div>

    <!-- Seção 2: Endereço -->
    <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i> 2. Endereço do Beneficiário</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6">
            {!!Form::text('cep', 'CEP')->required()
            ->attrs(['class' => 'cep form-control', 'placeholder' => '00000-000'])
            !!}
        </div>
        <div class="col-md-5 col-6">
            {!!Form::text('rua', 'Rua / Logradouro')->required()->attrs(['class' => 'form-control'])!!}
        </div>
        <div class="col-md-2 col-4">
            {!!Form::text('numero', 'Número')->required()->attrs(['class' => 'form-control'])!!}
        </div>
        <div class="col-md-3 col-8">
            {!!Form::text('bairro', 'Bairro')->required()->attrs(['class' => 'form-control'])!!}
        </div>
        <div class="col-md-6 col-12">
            {!!Form::select('cidade_id', 'Cidade')->required()
            ->attrs(['class' => 'form-select select2'])
            ->options(isset($item) && $item->cidade ? [$item->cidade_id => $item->cidade->info] : [])
            !!}
        </div>
    </div>

    <!-- Seção 3: Taxas e Prazos -->
    <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-money-dollar-circle-line text-primary me-2 align-middle fs-18"></i> 3. Regras de Juros, Multa & Prazos</h5>
    <div class="row g-3">
        <div class="col-md-4 col-6">
            {!!Form::tel('juros', 'Taxa de Juros (%)')
            ->attrs(['class' => 'moeda form-control'])
            ->value(isset($item) ? __moeda($item->juros) : '')
            !!}
        </div>
        <div class="col-md-4 col-6">
            {!!Form::tel('multa', 'Taxa de Multa (%)')
            ->attrs(['class' => 'moeda form-control'])
            ->value(isset($item) ? __moeda($item->multa) : '')
            !!}
        </div>
        <div class="col-md-4 col-12">
            {!!Form::tel('juros_apos', 'Cobrar Juros Após (Dias)')
            ->attrs(['data-mask' => '000', 'class' => 'form-control', 'placeholder' => 'Ex: 1'])
            !!}
        </div>
    </div>

    <!-- Rodapé de Ações -->
    <div class="row">
        <div class="col-12 mt-4">
            <hr class="text-muted opacity-25">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('contas-boleto.index') }}" class="dash-btn dash-btn-light">Cancelar</a>
                <button type="submit" class="dash-btn dash-btn-primary" id="btn-store">
                    <i class="ri-save-line"></i> Salvar Configuração
                </button>
            </div>
        </div>
    </div>
</div>