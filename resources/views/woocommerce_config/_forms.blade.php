<div class="row g-3">
    
    <div class="col-12 mb-3">
        <h5 class="d-flex align-items-center gap-2 m-0 text-dark border-bottom pb-2">
            <i class="ri-key-2-line text-primary"></i> Credenciais da API REST
        </h5>
        <p class="text-muted fs-13 mt-1 mb-0">Informe a URL da loja e as chaves geradas no painel do WordPress em WooCommerce > Configurações > Avançado > API REST.</p>
    </div>

    <div class="col-md-4">
        {!!Form::text('url', 'URL da Loja (com http:// ou https://)')
        ->required()
        ->attrs(['placeholder' => 'Ex: https://minhaloja.com.br'])
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('consumer_key', 'Consumer Key (Chave do Cliente)')
        ->required()
        ->attrs(['placeholder' => 'ck_...'])
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('consumer_secret', 'Consumer Secret (Segredo do Cliente)')
        ->required()
        ->attrs(['placeholder' => 'cs_...'])
        !!}
    </div>

    <div class="col-12 mt-4 pt-3 border-top text-end">
        <button type="submit" class="btn btn-success px-4 fw-semibold" id="btn-store" style="background-color: #7f54b3; border-color: #7f54b3;">
            <i class="ri-save-3-line me-1"></i> Salvar Configurações
        </button>
    </div>
</div>