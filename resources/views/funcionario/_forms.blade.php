<div class="row g-3 text-dark">
    
    <!-- Seção 1: Identificação Básica -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-user-line text-primary me-2 align-middle fs-18"></i> 1. Identificação do Funcionário</h5>
        <div class="row g-3">
            <div class="col-md-5 col-12">
                {!!Form::text('nome', 'Nome Completo')->placeholder('Ex: Carlos Alberto')->required()->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('cpf_cnpj', 'CPF/CNPJ')->attrs(['class' => 'form-control cpf_cnpj'])!!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('telefone', 'Telefone')->attrs(['class' => 'form-control fone'])!!}
            </div>
            
            <div class="col-md-3 col-6">
                {!!Form::text('codigo', 'Código de Controle')->placeholder('Ex: 102')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::select('status', 'Status (Contratado)', [1 => 'Ativo', 0 => 'Desativado'])->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- Seção 2: Endereço -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i> 2. Endereço Residencial</h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                @isset($item)
                {!!Form::select('cidade_id', 'Cidade')
                ->attrs(['class' => 'select2 form-select'])->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])
                ->required()!!}
                @else
                {!!Form::select('cidade_id', 'Cidade')
                ->attrs(['class' => 'select2 form-select'])
                ->required()!!}
                @endisset
            </div>

            <div class="col-md-5 col-12">
                {!!Form::text('rua', 'Logradouro / Rua')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-3 col-12">
                {!!Form::tel('numero', 'Número')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::text('bairro', 'Bairro')->attrs(['class' => 'form-control'])!!}
            </div>
        </div>
    </div>

    <!-- Seção 3: Permissões de Acesso & Salários -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-wallet-line text-primary me-2 align-middle fs-18"></i> 3. Acesso & Parâmetros Financeiros</h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::select('usuario_id', 'Usuário de Login Associado', ['' => 'Selecione'] + $usuario->pluck('name', 'id')->all())->attrs(['class' => 'form-select'])->required()!!}
                <div class="form-text text-muted fs-11 mt-1">Conta de usuário utilizada por este funcionário para entrar no sistema.</div>
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('salario', 'Salário Base Mensal (R$)')->attrs(['class' => 'form-control moeda'])
                ->value(isset($item) ? __moeda($item->salario) : '')!!}
            </div>

            <div class="col-md-4 col-6">
                {!!Form::tel('comissao', 'Comissão de Vendas (%)')->attrs(['class' => 'form-control moeda'])
                ->value(isset($item) ? __moeda($item->comissao) : '')!!}
            </div>
        </div>
    </div>
    <!-- Rodapé de Envio -->
    <div class="col-12">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('funcionarios.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Funcionário
                </button>
            </div>
        </div>
    </div>

</div>
