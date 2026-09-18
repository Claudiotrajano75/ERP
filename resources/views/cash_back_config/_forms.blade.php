{{-- ═══ SEÇÃO: REGRAS E PERCENTUAIS ═══ --}}
<div class="card card-secao-fiscal">
    <div class="card-header">
        <h5><i class="ri-percent-line text-primary"></i> Regras de Cálculo e Validade</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::text('valor_percentual', 'Percentual de Crédito gerado na venda')
                ->attrs(['class' => 'form-control percentual', 'placeholder' => 'Ex: 5,00%'])
                ->required()
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('percentual_maximo_venda', 'Limite % máximo de uso por venda')
                ->attrs(['class' => 'form-control percentual', 'placeholder' => 'Ex: 50,00%'])
                ->required()
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('dias_expiracao', 'Dias para Expiração do saldo')
                ->attrs(['class' => 'form-control', 'data-mask' => '0000', 'placeholder' => 'Ex: 30 dias'])
                ->required()
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('valor_minimo_venda', 'Valor mínimo de venda para gerar')
                ->attrs(['class' => 'form-control moeda', 'placeholder' => 'R$ 0,00'])
                ->required()
                ->value(isset($item) ? __moeda($item->valor_minimo_venda) : '')
                !!}
            </div>
        </div>
    </div>
</div>

{{-- ═══ SEÇÃO: NOTIFICAÇÃO VIA WHATSAPP ═══ --}}
<div class="card card-secao-fiscal">
    <div class="card-header">
        <h5><i class="ri-whatsapp-line text-success"></i> Mensagem Automática de Notificação (WhatsApp)</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Texto da Mensagem de Notificação de Crédito</label>
                {!!Form::textarea('mensagem_padrao_whatsapp', '')
                ->attrs(['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Digite a mensagem enviada ao cliente após a compra...'])
                ->required()
                !!}
            </div>

            <div class="col-12">
                <div class="p-3 bg-light rounded-3 border">
                    <span class="fs-12 fw-bold text-dark d-block mb-1">
                        <i class="ri-code-s-slash-line text-primary me-1"></i> Variáveis dinâmicas disponíveis para o texto:
                    </span>
                    <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                        <span><span class="var-badge">{nome}</span> <small class="text-muted">Nome do cliente</small></span>
                        <span class="ms-2"><span class="var-badge">{credito}</span> <small class="text-muted">Valor em R$ gerado</small></span>
                        <span class="ms-2"><span class="var-badge">{expiracao}</span> <small class="text-muted">Data limite de uso</small></span>
                    </div>
                    <div class="mt-2 pt-2 border-top text-muted fs-12">
                        <em>Exemplo: "Olá {nome}, você ganhou {credito} de CashBack na sua compra! Aproveite até {expiracao}."</em>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ BOTÃO SALVAR ═══ --}}
<div class="d-flex align-items-center justify-content-end gap-2 pt-2">
    <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line me-1"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Salvar Configurações
    </button>
</div>