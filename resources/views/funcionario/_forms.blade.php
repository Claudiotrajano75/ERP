<div class="row g-3 text-dark">
    
    <!-- Seção 1: Identificação Básica -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-user-line text-primary"></i> 1. Identificação do Funcionário
            </h5>
            <div class="row g-3">
                <div class="col-md-5 col-12">
                    <label class="form-label required fw-semibold">Nome Completo</label>
                    {!!Form::text('nome', '')->placeholder('Ex: Carlos Alberto da Silva')->required()->attrs(['class' => 'form-control'])!!}
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label fw-semibold">CPF</label>
                    {!!Form::text('cpf_cnpj', '')->placeholder('000.000.000-00')->attrs(['class' => 'form-control cpf_cnpj'])!!}
                </div>

                <div class="col-md-4 col-6">
                    <label class="form-label fw-semibold">Telefone / WhatsApp</label>
                    {!!Form::tel('telefone', '')->placeholder('(00) 00000-0000')->attrs(['class' => 'form-control fone'])!!}
                </div>
                
                <div class="col-md-3 col-6">
                    <label class="form-label fw-semibold">Código Interno</label>
                    {!!Form::text('codigo', '')->placeholder('Ex: 102')->attrs(['class' => 'form-control'])!!}
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label fw-semibold">Status de Atividade</label>
                    {!!Form::select('status', '', [1 => 'Ativo', 0 => 'Desativado'])->attrs(['class' => 'form-select'])!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 2: Endereço -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-map-pin-line text-primary"></i> 2. Endereço Residencial
            </h5>
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label required fw-semibold">Cidade</label>
                    @isset($item)
                    {!!Form::select('cidade_id', '')
                    ->attrs(['class' => 'select2 form-select'])->options($item != null && $item->cidade ? [$item->cidade_id => $item->cidade->info] : [])
                    ->required()!!}
                    @else
                    {!!Form::select('cidade_id', '')
                    ->attrs(['class' => 'select2 form-select'])
                    ->required()!!}
                    @endisset
                </div>

                <div class="col-md-5 col-12">
                    <label class="form-label fw-semibold">Logradouro / Rua</label>
                    {!!Form::text('rua', '')->placeholder('Ex: Rua das Flores')->attrs(['class' => 'form-control'])!!}
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label fw-semibold">Número</label>
                    {!!Form::tel('numero', '')->placeholder('Ex: 123')->attrs(['class' => 'form-control'])!!}
                </div>

                <div class="col-md-4 col-6">
                    <label class="form-label fw-semibold">Bairro</label>
                    {!!Form::text('bairro', '')->placeholder('Ex: Centro')->attrs(['class' => 'form-control'])!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 3: Permissões de Acesso & Salários -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-wallet-line text-primary"></i> 3. Acesso & Parâmetros Financeiros
            </h5>
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label required fw-semibold">Usuário de Login Associado</label>
                    {!!Form::select('usuario_id', '', ['' => 'Selecione um usuário'] + $usuario->pluck('name', 'id')->all())->attrs(['class' => 'form-select select2'])->required()!!}
                    <div class="form-text text-muted fs-11 mt-1">Conta de usuário vinculada para login no ERP.</div>
                </div>

                <div class="col-md-4 col-6">
                    <label class="form-label fw-semibold">Salário Base Mensal (R$)</label>
                    {!!Form::tel('salario', '')->attrs(['class' => 'form-control moeda', 'placeholder' => '0,00'])
                    ->value(isset($item) ? __moeda($item->salario) : '')!!}
                </div>

                <div class="col-md-4 col-6">
                    <label class="form-label fw-semibold">Comissão de Vendas (%)</label>
                    {!!Form::tel('comissao', '')->attrs(['class' => 'form-control moeda', 'placeholder' => '0,00'])
                    ->value(isset($item) ? __moeda($item->comissao) : '')!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('funcionarios.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
            <i class="ri-save-line me-1"></i> {{ isset($item) ? 'Salvar Alterações' : 'Salvar Funcionário' }}
        </button>
    </div>

</div>
