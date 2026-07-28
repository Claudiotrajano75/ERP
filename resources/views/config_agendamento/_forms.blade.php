<div class="row g-3 text-dark">
    
    <!-- Seção 1: Regras do Calendário -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-calendar-line text-primary me-2 align-middle fs-18"></i> 1. Parâmetros de Grade & Tempo</h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('tempo_descanso_entre_agendamento', 'Tempo descanso entre agendamentos (Minutos)')
                ->required()
                ->attrs(['class' => 'form-control', 'data-mask' => '000'])!!}
                <div class="form-text text-muted fs-11 mt-1">Margem de tempo automático adicionada ao fim de cada atendimento.</div>
            </div>
        </div>
    </div>

    <!-- Seção 2: Integração WhatsApp -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-whatsapp-line text-primary me-2 align-middle fs-18"></i> 2. Token da API do WhatsApp</h5>
        <div class="row g-3">
            <div class="col-md-8 col-12">
                {!!Form::text('token_whatsapp', 'Token WhatsApp')->attrs(['class' => 'form-control', 'placeholder' => 'Informe o token da API de envio'])!!}
                <div class="form-text text-success fs-12 mt-1">
                    <i class="ri-information-line me-1"></i> Para enviar mensagens de alertas automáticos de agendamento acesse: <strong><a href="https://criarwhats.com" target="_blank">https://criarwhats.com</a></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 3: Envio de Mensagem Pela Manhã -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-notification-line text-primary me-2 align-middle fs-18"></i> 3. Lembrete Diário (Período da Manhã)</h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::select('msg_wpp_manha', 'Enviar mensagem de manhã', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('msg_wpp_manha_horario', 'Horário de Envio')->attrs(['class' => 'form-control', 'data-mask' => '00:00', 'placeholder' => '08:00'])!!}
            </div>
            
            <div class="col-12">
                {!!Form::textarea('mensagem_manha', 'Mensagem do Lembrete Diário')->attrs(['rows' => '4', 'class' => 'form-control', 'placeholder' => 'Olá %nome%, confirmado seu agendamento para hoje...'])!!}
                <div class="p-2 bg-light border rounded mt-2 text-dark fs-12">
                    <strong class="text-primary"><i class="ri-code-line me-1"></i> Variáveis disponíveis:</strong>
                    <code class="mx-1">%nome%</code> (Nome do Cliente) | 
                    <code class="mx-1">%data%</code> (Data do Serviço) | 
                    <code class="mx-1">%hora%</code> (Horário) | 
                    <code class="mx-1">%serviços%</code> (Descrição dos Serviços)
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 4: Envio por Antecedência -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-alarm-line text-primary me-2 align-middle fs-18"></i> 4. Alerta com Antecedência (Minutos Antes)</h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::select('msg_wpp_alerta', 'Enviar mensagem antecedência', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('msg_wpp_alerta_minutos_antecedencia', 'Minutos Antecedentes')->attrs(['class' => 'form-control', 'data-mask' => '000', 'placeholder' => '60'])!!}
            </div>

            <div class="col-12">
                {!!Form::textarea('mensagem_alerta', 'Mensagem de Alerta Imediato')->attrs(['rows' => '4', 'class' => 'form-control', 'placeholder' => 'Está quase na hora do seu agendamento %nome%...'])!!}
                <div class="p-2 bg-light border rounded mt-2 text-dark fs-12">
                    <strong class="text-primary"><i class="ri-code-line me-1"></i> Variáveis disponíveis:</strong>
                    <code class="mx-1">%nome%</code> (Nome do Cliente) | 
                    <code class="mx-1">%data%</code> (Data do Serviço) | 
                    <code class="mx-1">%hora%</code> (Horário) | 
                    <code class="mx-1">%serviços%</code> (Descrição dos Serviços)
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-success px-5" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Configuração
            </button>
        </div>
    </div>

</div>
