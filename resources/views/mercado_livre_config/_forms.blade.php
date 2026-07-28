<div class="row g-3">
    
    <div class="col-12">
        <h5 class="d-flex align-items-center gap-2 m-0"><i class="ri-shield-keyhole-line"></i> Credenciais de Acesso (App Mercado Livre)</h5>
    </div>

    <div class="col-md-3">
        {!!Form::text('client_id', 'Client ID')
        ->required()
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::text('client_secret', 'Client Secret')
        ->required()
        !!}
    </div>

    <div class="col-md-5">
        {!!Form::text('url', 'Url de Redirecionamento')->required()
        !!}
    </div>

    @if($item)
    <div class="col-12 mt-4">
        <h5 class="d-flex align-items-center gap-2 m-0"><i class="ri-links-line"></i> Status de Conexão e Tokens Ativos</h5>
    </div>
    
    <div class="col-md-6">
        <div class="info-card">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="ri-shield-check-fill text-success fs-20 m-0"></i>
                <span class="text-uppercase fw-bold fs-12 text-muted">Access Token</span>
            </div>
            <strong>{{ $item->access_token ?: '--' }}</strong>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-card">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="ri-refresh-line text-info fs-20 m-0"></i>
                <span class="text-uppercase fw-bold fs-12 text-muted">Refresh Token</span>
            </div>
            <strong>{{ $item->refresh_token ?: '--' }}</strong>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-card">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="ri-user-star-line text-primary fs-20 m-0"></i>
                <span class="text-uppercase fw-bold fs-12 text-muted">User ID</span>
            </div>
            <strong>{{ $item->user_id ?: '--' }}</strong>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-card">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="ri-key-line text-warning fs-20 m-0"></i>
                <span class="text-uppercase fw-bold fs-12 text-muted">Code</span>
            </div>
            <strong>{{ $item->code ?: '--' }}</strong>
        </div>
    </div>
    @endif
  
    <div class="col-12 mt-4 text-end border-top pt-3">
        <button type="submit" class="btn btn-success px-5 fw-bold" id="btn-store">
            <i class="ri-save-3-line"></i> Salvar Configurações
        </button>
    </div>
</div>