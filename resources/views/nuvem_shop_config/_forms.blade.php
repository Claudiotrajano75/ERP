<div class="row g-3">
    
    <div class="col-12 mb-3">
        <h5 class="d-flex align-items-center gap-2 m-0 text-dark border-bottom pb-2">
            <i class="ri-key-2-line text-primary"></i> Credenciais do Aplicativo
        </h5>
        <div class="alert alert-light border text-secondary mt-3 fs-13">
            <i class="ri-information-line text-primary fs-15 align-middle me-1"></i>
            Acesse o painel de parceiros da <strong>Nuvem Shop</strong>, crie um novo aplicativo e copie o <strong>APP ID</strong> e <strong>Client Secret</strong> gerados.
        </div>
    </div>

    <div class="col-md-3">
        {!!Form::text('client_id', 'APP ID')
        ->attrs(['placeholder' => 'Ex: 123456', 'class' => 'form-control font-monospace'])
        ->required()
        !!}
    </div>

    <div class="col-md-5">
        {!!Form::text('client_secret', 'Client Secret')
        ->attrs(['placeholder' => 'Ex: c4b5e...d8f9', 'class' => 'form-control font-monospace'])
        ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('email', 'Email da Conta')
        ->attrs(['type' => 'email', 'placeholder' => 'loja@exemplo.com.br'])
        ->required()
        !!}
    </div>

    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-4 py-2 d-flex align-items-center gap-2" id="btn-store" style="background-color: #0d2b40; border-color: #0d2b40;">
            <i class="ri-save-3-line"></i> Salvar Configurações
        </button>
    </div>
</div>