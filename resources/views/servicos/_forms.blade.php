<style>
/* ─── Seções do formulário ─── */
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field textarea.form-control { height: auto !important; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Upload de Imagem ─── */
.img-upload-box { border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; text-align: center; background: #f8fafc; }
.img-preview-wrap { height: 130px; position: relative; overflow: hidden; border-radius: 10px; background: #fff; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ SEÇÃO 1: IDENTIFICAÇÃO & PREÇOS ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-settings-3-line"></i></span>
            1. Identificação & Preços
            <small>dados cadastrais, precificação e durações</small>
        </div>
        <div class="row g-3">
            <div class="col-md-4 col-12 uf-field">
                <label class="form-label" for="inp-nome"><i class="ri-briefcase-line"></i> Nome do Serviço</label>
                {!!Form::text('nome', '')->placeholder('Ex: Corte de Cabelo, Consultoria Financeira')->required()->attrs(['class' => 'form-control', 'id' => 'inp-nome'])!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label" for="inp-valor"><i class="ri-money-dollar-circle-line"></i> Valor de Venda (R$)</label>
                {!!Form::tel('valor', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor'])->required()
                ->value(isset($item) ? __moeda($item->valor) : '')!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label" for="inp-tempo_servico"><i class="ri-time-line"></i> Duração (Minutos)</label>
                {!!Form::tel('tempo_servico', '')->attrs(['class' => 'form-control', 'data-mask' => '00', 'id' => 'inp-tempo_servico'])->required()!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label" for="inp-comissao"><i class="ri-percent-line"></i> Comissão (R$)</label>
                {!!Form::tel('comissao', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-comissao'])!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-scales-line"></i> Unidade Cobrança</label>
                {!!Form::select('unidade_cobranca', '', ['UND' => 'UND', 'HORAS' => 'HORAS', 'MIN' => 'MIN'])->attrs(['class' => 'form-select'])!!}
            </div>

            <div class="col-md-4 col-12 uf-field">
                <label class="form-label" for="inp-categoria_id"><i class="ri-folder-line"></i> Categoria de Serviço</label>
                {!!Form::select('categoria_id', '', ['' => 'Selecione uma categoria'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2', 'id' => 'inp-categoria_id'])->required()!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-time-line"></i> Tempo Adicional (min)</label>
                {!!Form::text('tempo_adicional', '')->attrs(['class' => 'form-control', 'data-mask' => '00'])!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-price-tag-line"></i> Valor Adicional (R$)</label>
                {!!Form::tel('valor_adicional', '')->attrs(['class' => 'form-control moeda'])
                ->value(isset($item) ? __moeda($item->valor_adicional) : '')!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-hourglass-line"></i> Tolerância (min)</label>
                {!!Form::text('tempo_tolerancia', '')->attrs(['class' => 'form-control', 'data-mask' => '00'])!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-barcode-line"></i> Cód. do Serviço</label>
                {!!Form::tel('codigo_servico', '')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-4 col-12 uf-field">
                <label class="form-label"><i class="ri-building-line"></i> Cód. Tributação Municipal</label>
                {!!Form::tel('codigo_tributacao_municipio', '')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-toggle-line"></i> Status</label>
                {!!Form::select('status', '', ['1' => 'Ativo', '0' => 'Inativo'])->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: INTEGRAÇÕES & CANAIS ═══ -->
    @if(__isActivePlan(Auth::user()->empresa, 'Reservas') || __isActivePlan(Auth::user()->empresa, 'Delivery'))
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-global-line"></i></span>
            2. Integrações & Canais de Venda
            <small>reservas online e delivery/marketplace</small>
        </div>
        <div class="row g-3">
            @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-calendar-check-line"></i> Usar em Reservas</label>
                {!!Form::select('reserva', '', ['0' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-file-text-line"></i> Padrão Reserva NFSe</label>
                {!!Form::select('padrao_reserva_nfse', '', ['0' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
                <div class="form-text text-muted fs-11 mt-1">Se "Sim", este serviço será o padrão na emissão de NFSe de reservas.</div>
            </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-store-2-line"></i> Usar no Marketplace</label>
                {!!Form::select('marketplace', '', ['0' => 'Não', '1' => 'Sim'])
                ->attrs(['class' => 'form-select'])
                ->value(isset($item) ? $item->marketplace : (isset($marketplace) && $marketplace == 1 ? 1 : 0))!!}
            </div>
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-star-line"></i> Destaque no Marketplace</label>
                {!!Form::select('destaque_marketplace', '', ['0' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            @if(isset($marketplace) && $marketplace == 1)
            <input type="hidden" name="redirect_marketplace" value="1">
            @endif
            @endif
        </div>
    </div>
    @endif

    <!-- ═══ SEÇÃO 3: DESCRIÇÃO & MÍDIA ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-image-edit-line"></i></span>
            3. Detalhes & Imagem de Apresentação
            <small>apresentação comercial do serviço</small>
        </div>
        <div class="row g-3">
            <div class="col-md-9 col-12 uf-field">
                <label class="form-label"><i class="ri-file-list-3-line"></i> Descrição Detalhada do Serviço</label>
                {!!Form::textarea('descricao', '')
                ->placeholder('Descreva as etapas, materiais inclusos ou observações importantes sobre a execução deste serviço...')
                ->attrs(['rows' => '5', 'class' => 'form-control'])!!}
            </div>

            <div class="col-md-3 col-12 uf-field">
                <label class="form-label"><i class="ri-image-line"></i> Imagem do Serviço</label>
                <div class="img-upload-box">
                    <div class="img-preview-wrap">
                        <button type="button" id="btn-remove-imagem"
                                class="btn btn-danger btn-sm p-1 rounded-circle"
                                style="position: absolute; top: 6px; right: 6px; z-index: 10; line-height: 1; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;">×</button>
                        @isset($item)
                        <img id="file-ip-1-preview" src="{{ $item->img }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                        <img id="file-ip-1-preview" src="/imgs/no-image.png"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <label for="file-ip-1" class="dash-btn dash-btn-primary w-100 mb-0 py-1 fs-12">
                        <i class="ri-upload-cloud-line"></i> Selecionar Imagem
                    </label>
                    <input type="file" class="d-none" id="file-ip-1" name="image"
                           accept="image/*" onchange="showPreview(event);">
                </div>
                @if($errors->has('image'))
                <div class="text-danger mt-1 fs-12">{{ $errors->first('image') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 4: CONFIGURAÇÃO FISCAL ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-scales-3-line"></i></span>
            4. Configuração Fiscal & Tributária
            <small>alíquotas e tributos para emissão de NFSe</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-percent-line"></i> Alíquota ISS (%)</label>
                {!!Form::tel('aliquota_iss', '')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-percent-line"></i> Alíquota PIS (%)</label>
                {!!Form::tel('aliquota_pis', '')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-percent-line"></i> Alíquota COFINS (%)</label>
                {!!Form::tel('aliquota_cofins', '')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
            <div class="col-md-3 col-6 uf-field">
                <label class="form-label"><i class="ri-percent-line"></i> Alíquota INSS (%)</label>
                {!!Form::tel('aliquota_inss', '')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('servicos.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ ($formType ?? '') === 'edit' ? 'Salvar Alterações' : 'Salvar Serviço' }}
            </button>
        </div>
    </div>

</div>

