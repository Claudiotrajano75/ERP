{{-- ═══ SEÇÃO: CREDENCIAIS E SERVIDOR ═══ --}}
<div class="card card-secao-fiscal">
    <div class="card-header">
        <h5><i class="ri-server-line text-primary"></i> Credenciais e Servidor SMTP</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::text('nome', 'Nome / Identificação')
                ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Financeiro, Suporte'])
                ->required()
                !!}
            </div>
            <div class="col-md-3 col-12">
                {!!Form::text('host', 'Host SMTP')
                ->attrs(['class' => 'form-control', 'placeholder' => 'smtp.exemplo.com.br'])
                ->required()
                !!}
            </div>
            <div class="col-md-4 col-12">
                {!!Form::text('email', 'E-mail do Remetente')
                ->attrs(['class' => 'form-control', 'placeholder' => 'contato@empresa.com.br'])
                ->required()
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::text('porta', 'Porta')
                ->attrs(['class' => 'form-control', 'placeholder' => '587 ou 465'])
                ->required()
                !!}
            </div>
        </div>
    </div>
</div>

{{-- ═══ SEÇÃO: SEGURANÇA E AUTENTICAÇÃO ═══ --}}
<div class="card card-secao-fiscal">
    <div class="card-header">
        <h5><i class="ri-shield-keyhole-line text-primary"></i> Segurança e Autenticação</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4 col-12">
                <label class="form-label">Senha</label>
                <div class="input-group" id="show_hide_password">
                    <input type="password" class="form-control" name="senha" autocomplete="off"
                           value="{{ isset($item) ? $item->senha : '' }}"
                           style="border-radius: 9px 0 0 9px !important;">
                    <a class="input-group-text" style="cursor: pointer; border-radius: 0 9px 9px 0 !important; border-left: none;">
                        <i class="ri-eye-line"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('cripitografia', 'Criptografia', ['tls' => 'TLS', 'ssl' => 'SSL'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::select('smtp_auth', 'Autenticação SMTP', ['0' => 'Não', '1' => 'Sim'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::select('smtp_debug', 'Debug SMTP', ['0' => 'Desligado', '1' => 'Ligado'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
            <div class="col-md-1 col-6">
                {!!Form::select('status', 'Status', ['0' => 'Inativo', '1' => 'Ativo'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
        </div>
    </div>
</div>

{{-- ═══ BOTÃO SALVAR ═══ --}}
<div class="col-12 d-flex justify-content-end pt-2">
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Salvar Configurações
    </button>
</div>
