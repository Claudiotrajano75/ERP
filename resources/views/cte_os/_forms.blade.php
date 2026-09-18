<style>
/* ─── Seções do formulário ─── */
.uf-section { margin-bottom: 24px; }
.uf-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13.5px;
    font-weight: 700;
    color: #1f2937;
    border-bottom: 1px solid #eef0f6;
    padding-bottom: 10px;
    margin-bottom: 16px;
}
.uf-section-title .uf-ico {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #eef0ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 11.5px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label, label:not(.form-check-label) {
    display: block;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 4px !important;
}
.uf-field .form-control, .uf-field .form-select, .form-control, .form-select {
    height: 40px;
    border-radius: 10px;
    border: 1px solid #dcdce9;
    font-size: 13px;
    color: #1f2937;
    background: #fcfdfe;
    transition: all .15s ease;
}
.uf-field .form-control:focus, .uf-field .form-select:focus, .form-control:focus, .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}

/* ─── Card de Participante Selecionado ─── */
.participant-preview-card {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 14px 16px;
    margin-top: 12px;
}
.participant-preview-card .preview-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #16a34a;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    border-bottom: 1px solid #dcfce7;
    padding-bottom: 6px;
}
.participant-preview-card .preview-item {
    font-size: 12.5px;
    color: #374151;
    margin-bottom: 3px;
}
.participant-preview-card .preview-item strong {
    color: #15803d;
}

/* ─── Rodapé de ações ─── */
.uf-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    border-top: 1px solid #eef0f6;
    padding-top: 16px;
    margin-top: 24px;
}
</style>

<div class="row g-4">

    <!-- ═══ SEÇÃO 1: DADOS FISCAIS E PARTICIPANTES ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-file-shield-2-line"></i></span>
            Dados Fiscais & Participantes
            <small>tributação, emitente e tomador do serviço</small>
        </div>
        <div class="row g-3">
            @if(__countLocalAtivo() > 1)
            <div class="col-md-3 col-12 uf-field">
                <label for="inp-local_id"><i class="ri-map-pin-line me-1"></i> Local / Filial</label>
                <select id="inp-local_id" required class="select2 form-select" name="local_id">
                    <option value="">Selecione o local</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
            @endif

            <div class="col-md-{{ __countLocalAtivo() > 1 ? '5' : '6' }} col-12 uf-field">
                <label for="natureza_id"><i class="ri-article-line me-1"></i> Natureza de Operação</label>
                {!! Form::select('natureza_id', '', ['' => 'Selecione a natureza'] + $naturezas->pluck('descricao', 'id')->all())
                    ->attrs(['class' => 'select2 form-select class-required'])->required() !!}
            </div>

            <div class="col-md-{{ __countLocalAtivo() > 1 ? '2' : '3' }} col-6 uf-field">
                <label for="cst"><i class="ri-percent-line me-1"></i> CST</label>
                {!! Form::select('cst', '', App\Models\CteOs::getCsts())
                    ->attrs(['class' => 'select2 form-select']) !!}
            </div>

            <div class="col-md-1 col-3 uf-field">
                <label for="perc_icms">% ICMS</label>
                {!! Form::text('perc_icms', '')->required()->attrs(['class' => 'perc form-control class-required', 'placeholder' => '0,00']) !!}
            </div>

            <div class="col-md-1 col-3 uf-field">
                <label for="cfop">CFOP</label>
                {!! Form::tel('cfop', '')->attrs(['class' => 'cfop form-control class-required', 'placeholder' => '0000'])->required() !!}
            </div>

            <div class="col-md-6 col-12 uf-field">
                <label for="remetente_id"><i class="ri-building-line me-1"></i> Emitente da CTe OS</label>
                {!! Form::select('remetente_id', '', ['' => 'Selecione o emitente'] + $clientes->pluck('razao_social', 'id')->all())
                    ->attrs(['class' => 'select2 form-select class-required'])->required()
                    ->value(isset($item) ? $item->emitente_id : null) !!}
                <div class="participant-preview-card div-remetente d-none">
                    <div class="preview-title"><i class="ri-checkbox-circle-fill"></i> Emitente Selecionado</div>
                    <div class="preview-item">Razão Social: <strong id="razao_social_remetente"></strong></div>
                    <div class="preview-item">CNPJ: <strong id="cnpj_remetente"></strong></div>
                    <div class="preview-item">Cidade: <strong id="cidade_remetente"></strong></div>
                </div>
            </div>

            <div class="col-md-6 col-12 uf-field">
                <label for="destinatario_id"><i class="ri-user-shared-line me-1"></i> Tomador do Serviço</label>
                {!! Form::select('destinatario_id', '', ['' => 'Selecione o tomador'] + $clientes->pluck('razao_social', 'id')->all())
                    ->attrs(['class' => 'select2 form-select class-required'])->required()
                    ->value(isset($item) ? $item->tomador_id : null) !!}
                <div class="participant-preview-card div-destinatario d-none">
                    <div class="preview-title"><i class="ri-checkbox-circle-fill"></i> Tomador Selecionado</div>
                    <div class="preview-item">Razão Social: <strong id="razao_social_destinatario"></strong></div>
                    <div class="preview-item">CNPJ: <strong id="cnpj_destinatario"></strong></div>
                    <div class="preview-item">Cidade: <strong id="cidade_destinatario"></strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: INFORMAÇÕES DO VEÍCULO E VALORES ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-truck-line"></i></span>
            Veículo & Valores do Transporte
            <small>veículo, tipo de tomador, modelo e valores financeiros</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-12 uf-field">
                <label for="veiculo_id"><i class="ri-car-line me-1"></i> Veículo</label>
                {!! Form::select('veiculo_id', '', ['' => 'Selecione o veículo'] + $veiculos->pluck('placa', 'id')->all())
                    ->attrs(['class' => 'select2 form-select'])->required() !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label for="tomador"><i class="ri-user-star-line me-1"></i> Tipo Tomador</label>
                {!! Form::select('tomador', '', App\Models\CteOs::tiposTomador())
                    ->attrs(['class' => 'select2 form-select'])->required() !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label for="modal"><i class="ri-compass-3-line me-1"></i> Modal</label>
                {!! Form::select('modal', '', App\Models\CteOs::modals())
                    ->attrs(['class' => 'select2 form-select'])->required() !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label for="valor_transporte"><i class="ri-money-dollar-circle-line me-1"></i> Valor do Serviço</label>
                {!! Form::tel('valor_transporte', '')->attrs(['class' => 'moeda form-control', 'placeholder' => 'R$ 0,00'])->required() !!}
            </div>

            <div class="col-md-3 col-6 uf-field">
                <label for="valor_receber"><i class="ri-hand-coin-line me-1"></i> Valor a Receber</label>
                {!! Form::tel('valor_receber', '')->attrs(['class' => 'moeda form-control', 'placeholder' => 'R$ 0,00'])->required() !!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 3: ITINERÁRIO E PRESTAÇÃO DO SERVIÇO ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-map-pin-range-line"></i></span>
            Itinerário & Dados da Viagem
            <small>municípios de envio, início e fim da prestação</small>
        </div>
        <div class="row g-3">
            <div class="col-md-4 col-12 uf-field">
                <label for="municipio_envio"><i class="ri-map-pin-2-line me-1"></i> Município de Envio</label>
                {!! Form::select('municipio_envio', '', ['' => 'Selecione o município'] + $cidades->pluck('info', 'id')->all())
                    ->attrs(['class' => 'select2 form-select'])->required() !!}
            </div>

            <div class="col-md-4 col-12 uf-field">
                <label for="municipio_inicio"><i class="ri-navigation-line me-1"></i> Município de Início</label>
                {!! Form::select('municipio_inicio', '', ['' => 'Selecione o município'] + $cidades->pluck('info', 'id')->all())
                    ->attrs(['class' => 'select2 form-select'])->required() !!}
            </div>

            <div class="col-md-4 col-12 uf-field">
                <label for="municipio_fim"><i class="ri-flag-line me-1"></i> Município de Fim (Destino)</label>
                {!! Form::select('municipio_fim', '', ['' => 'Selecione o município'] + $cidades->pluck('info', 'id')->all())
                    ->attrs(['class' => 'select2 form-select'])->required() !!}
            </div>

            <div class="col-md-6 col-12 uf-field">
                <label for="descricao_servico"><i class="ri-file-text-line me-1"></i> Descrição do Serviço</label>
                {!! Form::text('descricao_servico', '')->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Transporte de passageiros fretamento evento...'])->required() !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label for="quantidade_carga"><i class="ri-user-line me-1"></i> Qtd Passageiros / Carga</label>
                {!! Form::text('quantidade_carga', '')->attrs(['class' => 'qtd form-control', 'placeholder' => 'Ex: 40'])->required() !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label for="data_viagem"><i class="ri-calendar-line me-1"></i> Data da Viagem</label>
                {!! Form::date('data_viagem', '')->attrs(['class' => 'form-control'])->required() !!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label for="horario_viagem"><i class="ri-time-line me-1"></i> Horário da Viagem</label>
                {!! Form::text('horario_viagem', '')->attrs(['class' => 'form-control', 'data-mask' => '00:00', 'placeholder' => '00:00'])->required() !!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 4: OBSERVAÇÕES ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-chat-1-line"></i></span>
            Informações Adicionais / Observações
            <small>observações que serão impressas no DACTE OS</small>
        </div>
        <div class="row g-3">
            <div class="col-12 uf-field">
                <label for="observacao">Informações de Interesse do Fisco e Contribuinte</label>
                {!! Form::textarea('observacao', '')->attrs(['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Digite observações adicionais para este CTe OS...']) !!}
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('cte-os.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                <i class="ri-save-line"></i>
                {{ isset($item) ? 'Salvar Alterações' : 'Emitir CTe OS' }}
            </button>
        </div>
    </div>
</div>
