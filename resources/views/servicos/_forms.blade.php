<div class="row g-3 text-dark">

    <!-- ═══ SEÇÃO 1: IDENTIFICAÇÃO & PREÇOS ═══ -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-settings-3-line text-primary me-2 align-middle fs-18"></i>
            1. Identificação & Preços
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('nome', 'Nome do Serviço')->placeholder('Ex: Corte de Cabelo, Consultoria Financeira')->required()->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('valor', 'Valor de Venda (R$)')->attrs(['class' => 'form-control moeda'])->required()
                ->value(isset($item) ? __moeda($item->valor) : '')!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('tempo_servico', 'Duração (Minutos)')->attrs(['class' => 'form-control', 'data-mask' => '00'])->required()!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('comissao', 'Comissão (Opcional R$)')->attrs(['class' => 'form-control moeda'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::select('unidade_cobranca', 'Unidade Cobrança', ['UND' => 'UND', 'HORAS' => 'HORAS', 'MIN' => 'MIN'])->attrs(['class' => 'form-select'])!!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::select('categoria_id', 'Categoria de Serviço', ['' => 'Selecione uma categoria'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])->required()!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::text('tempo_adicional', 'Tempo Adicional (min)')->attrs(['class' => 'form-control', 'data-mask' => '00'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('valor_adicional', 'Valor Adicional (R$)')->attrs(['class' => 'form-control moeda'])
                ->value(isset($item) ? __moeda($item->valor_adicional) : '')!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::text('tempo_tolerancia', 'Tempo Tolerância (min)')->attrs(['class' => 'form-control', 'data-mask' => '00'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('codigo_servico', 'Código do Serviço')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::tel('codigo_tributacao_municipio', 'Cód. Tributação Municipal')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::select('status', 'Status', ['1' => 'Ativo', '0' => 'Inativo'])->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: INTEGRAÇÕES & CANAIS ═══ -->
    @if(__isActivePlan(Auth::user()->empresa, 'Reservas') || __isActivePlan(Auth::user()->empresa, 'Delivery'))
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-global-line text-primary me-2 align-middle fs-18"></i>
            2. Integrações & Canais de Venda
        </h5>
        <div class="row g-3">
            @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
            <div class="col-md-3 col-6">
                {!!Form::select('reserva', 'Usar em Reservas', ['0' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('padrao_reserva_nfse', 'Padrão Reserva NFSe', ['0' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
                <div class="form-text text-muted fs-11 mt-1">Se "Sim", este serviço será o padrão na NFSe de reservas.</div>
            </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
            <div class="col-md-3 col-6">
                {!!Form::select('marketplace', 'Usar no Marketplace', ['0' => 'Não', '1' => 'Sim'])
                ->attrs(['class' => 'form-select'])
                ->value(isset($item) ? $item->marketplace : (isset($marketplace) && $marketplace == 1 ? 1 : 0))!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('destaque_marketplace', 'Destaque no Marketplace', ['0' => 'Não', '1' => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            @if(isset($marketplace) && $marketplace == 1)
            <input type="hidden" name="redirect_marketplace" value="1">
            @endif
            @endif
        </div>
    </div>
    @endif

    <!-- ═══ SEÇÃO 3: DESCRIÇÃO & MÍDIA ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-image-edit-line text-primary me-2 align-middle fs-18"></i>
            3. Detalhes & Imagem de Apresentação
        </h5>
        <div class="row g-3">
            <div class="col-md-9 col-12">
                {!!Form::textarea('descricao', 'Descrição Detalhada do Serviço')
                ->placeholder('Descreva as etapas, materiais inclusos ou observações importantes sobre a execução deste serviço...')
                ->attrs(['rows' => '5', 'class' => 'form-control'])!!}
            </div>

            <div class="col-md-3 col-12">
                <label class="form-label fw-semibold text-dark mb-1">Imagem do Serviço</label>
                <div class="card border shadow-sm">
                    <div class="card-body p-2 text-center">
                        <div class="preview mb-2 bg-light rounded d-flex align-items-center justify-content-center border"
                             style="height: 120px; position: relative; overflow: hidden;">
                            <button type="button" id="btn-remove-imagem"
                                    class="btn btn-danger btn-sm p-1 rounded-circle"
                                    style="position: absolute; top: 5px; right: 5px; z-index: 10; line-height: 1; width: 22px; height: 22px;">×</button>
                            @isset($item)
                            <img id="file-ip-1-preview" src="{{ $item->img }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <img id="file-ip-1-preview" src="/imgs/no-image.png"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <label for="file-ip-1" class="btn btn-primary btn-sm w-100 mb-0">
                            <i class="ri-upload-cloud-line me-1"></i> Selecionar Imagem
                        </label>
                        <input type="file" class="d-none" id="file-ip-1" name="image"
                               accept="image/*" onchange="showPreview(event);">
                    </div>
                </div>
                @if($errors->has('image'))
                <div class="text-danger mt-1 fs-12">{{ $errors->first('image') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 4: CONFIGURAÇÃO FISCAL ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-scales-3-line text-primary me-2 align-middle fs-18"></i>
            4. Configuração Fiscal & Tributária
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::tel('aliquota_iss', 'Alíquota ISS (%)')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('aliquota_pis', 'Alíquota PIS (%)')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('aliquota_cofins', 'Alíquota COFINS (%)')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('aliquota_inss', 'Alíquota INSS (%)')->attrs(['class' => 'form-control percentual text-end'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn {{ $formType === 'edit' ? 'btn-primary' : 'btn-success' }} px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar Serviço' }}
            </button>
        </div>
    </div>

</div>
