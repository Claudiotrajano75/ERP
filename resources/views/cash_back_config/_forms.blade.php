<div class="row g-3 text-dark">
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-settings-3-line text-primary me-2 align-middle fs-18"></i>
            Parâmetros de CashBack
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::text('valor_percentual', 'Percentual de crédito sobre a venda')
    ->attrs(['class' => 'percentual'])->required()
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('percentual_maximo_venda', 'Percentual máximo por venda')
    ->attrs(['class' => 'percentual'])->required()
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('dias_expiracao', 'Dias expiração')
    ->attrs(['class' => 'percentual', 'data-mask' => '0000'])->required()
                !!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('valor_minimo_venda', 'Valor mínimo de venda')
    ->attrs(['class' => 'moeda'])->required()
    ->value(isset($item) ? __moeda($item->valor_minimo_venda) : '')
                !!}
            </div>

            <div class="col-md-8 col-12">
                {!!Form::text('mensagem_padrao_whatsapp', 'Mensagem padrão do WhatsApp')
    ->attrs(['class' => ''])->required()
                !!}
            </div>

            <div class="col-12">
                <p class="text-muted fs-12 mb-0">
                    <i class="ri-information-line me-1 text-primary"></i>
                    Use <code>{credito}</code> para o valor do crédito, <code>{expiracao}</code> para a data de
                    expiração e <code>{nome}</code> para o nome do cliente.
                    <br><span class="text-muted">Exemplo: <em>O valor do seu CashBack é de {credito}, com validade até
                            {expiracao}, obrigado {nome}</em></span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar Configurações
        </button>
    </div>
</div>