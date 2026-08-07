<div class="modal fade modal-pdv modal-pdv-modern" id="modal_novo_cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="novoClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="novoClienteLabel">
                        <i class="ri-user-add-line"></i> Novo Cliente
                    </h5>
                    <p class="modulo-header-subtitle">Cadastre um novo cliente de forma rápida</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-id-card-line me-1"></i>CPF/CNPJ</label>
                        {!!Form::text('novo_cpf_cnpj', '')->attrs(['class' => 'form-control cpf_cnpj'])->required()
                        !!}
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="ri-user-3-line me-1"></i>Razão Social</label>
                        {!!Form::text('novo_razao_social', '')->attrs(['class' => 'form-control'])->required()
                        !!}
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="ri-user-2-line me-1"></i>Nome Fantasia</label>
                        {!!Form::text('novo_nome_fantasia', '')->attrs(['class' => 'form-control ignore'])
                        !!}
                    </div>
                    <div class="col-md-1">
                        <label class="form-label"><i class="ri-hashtag me-1"></i>IE</label>
                        {!!Form::text('novo_ie', '')->attrs(['class' => 'form-control ie ignore'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-phone-line me-1"></i>Telefone</label>
                        {!!Form::tel('novo_telefone', '')->attrs(['class' => 'form-control fone'])->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-government-line me-1"></i>Contribuinte</label>
                        {!!Form::select('novo_contribuinte', '', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])
                        !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-user-smile-line me-1"></i>Consumidor Final</label>
                        {!!Form::select('novo_consumidor_final', '', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-toggle-line me-1"></i>Ativo</label>
                        {!!Form::select('novo_status', '', [ 1 => 'Sim', 0 => 'Não'])->attrs(['class' => 'form-select'])->required()
                        !!}
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="ri-mail-line me-1"></i>Email</label>
                        {!! Form::text('novo_email', '')->attrs(['class' => 'form-control ignore'])->type('email') !!}
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><i class="ri-map-pin-line me-1"></i>Cidade</label>
                        {!!Form::select('novo_cidade_id', '')
                        ->attrs(['class' => 'select2 form-select'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="ri-road-line me-1"></i>Rua</label>
                        {!!Form::text('novo_rua', '')->attrs(['class' => 'form-control'])->required()
                        !!}
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><i class="ri-sort-number-asc me-1"></i>Número</label>
                        {!!Form::text('novo_numero', '')->attrs(['class' => 'form-control'])->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-map-pin-2-line me-1"></i>CEP</label>
                        {!!Form::text('novo_cep', '')->attrs(['class' => 'form-control cep'])->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="ri-home-4-line me-1"></i>Bairro</label>
                        {!!Form::text('novo_bairro', '')->attrs(['class' => 'form-control'])->required()
                        !!}
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="ri-home-5-line me-1"></i>Complemento</label>
                        {!!Form::text('novo_complemento', '')->attrs(['class' => 'form-control ignore'])
                        !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success btn-store-cliente">
                    <i class="ri-check-double-line me-1"></i> Salvar Cliente
                </button>
            </div>
        </div>
    </div>
</div>
