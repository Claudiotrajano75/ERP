<div class="modulo-section-card mb-3">
    <div class="card-header">
        <h4><i class="ri-server-line me-2"></i>Credenciais e Servidor</h4>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-2 mb-2">
                {!!Form::text('nome', 'Nome')
                ->required()
                !!}
            </div>

            <div class="col-md-3 mb-2">
                {!!Form::text('host', 'Host')
                ->required()
                !!}
            </div>

            <div class="col-md-3 mb-2">
                {!!Form::text('email', 'Email')
                ->required()
                !!}
            </div>

            <div class="col-md-2 mb-2">
                {!!Form::text('senha', 'Senha')
                ->required()
                !!}
            </div>
            <div class="col-md-2 mb-2">
                {!!Form::text('porta', 'Porta')
                ->required()
                !!}
            </div>

            <div class="col-md-2 mb-2">
                {!!Form::select('cripitografia', 'Criptografia', ['tls' => 'TLS', 'ssl' => 'SSL'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>

            <div class="col-md-2 mb-2">
                {!!Form::select('smtp_auth', 'Autenticação SMTP', ['0' => 'Não', '1' => 'Sim'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-2 mb-2">
                {!!Form::select('smtp_debug', 'SMTP Debug', ['0' => 'Não', '1' => 'Sim'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
            
            <div class="col-md-2 mb-2">
                {!!Form::select('status', 'Status', ['0' => 'Desativado', '1' => 'Ativado'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>

            <div class="col-12 mt-3" style="text-align: right;">
                <button type="submit" class="btn btn-success px-5" id="btn-store">Salvar</button>
            </div>
        </div>
    </div>
</div>


