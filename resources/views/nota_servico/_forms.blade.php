<style>
/* ─── Navegação de Abas ─── */
.nav-tabs-premium { border-bottom: 2px solid #eef0f6; gap: 8px; margin-bottom: 24px; }
.nav-tabs-premium .nav-link { border: none; border-radius: 10px 10px 0 0; padding: 12px 20px; font-weight: 700; font-size: 13px; color: #64748b; background: transparent; transition: all .2s ease; display: inline-flex; align-items: center; gap: 8px; }
.nav-tabs-premium .nav-link:hover { color: #4f46e5; background: #f8f9ff; }
.nav-tabs-premium .nav-link.active { color: #4f46e5; background: #fff; border-bottom: 3px solid #4f46e5; }
.nav-tabs-premium .nav-link i { font-size: 16px; }

/* ─── Seções do formulário ─── */
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 28px; height: 28px; border-radius: 8px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 11.5px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 12.5px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field textarea.form-control { height: auto !important; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 24px; }
</style>

<div class="row">
    <div class="col-12">

        <!-- Abas de Navegação -->
        <ul class="nav nav-tabs nav-tabs-premium" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#tomador" role="tab" aria-selected="true">
                    <i class="ri-user-search-line"></i>
                    1. Dados do Tomador / Cliente
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#servico" role="tab" aria-selected="false">
                    <i class="ri-tools-line"></i>
                    2. Serviço Prestado & Tributação
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ═══ ABA 1: TOMADOR ═══ -->
            <div class="tab-pane fade show active" id="tomador" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12 uf-section">
                        <div class="uf-section-title">
                            <span class="uf-ico"><i class="ri-user-line"></i></span>
                            Identificação do Tomador de Serviço
                            <small>selecione o cliente para autopreenchimento</small>
                        </div>
                        <div class="row g-3">
                            @isset($reserva)
                            <div class="col-md-6 col-12 uf-field">
                                <label class="form-label required" for="cliente_id"><i class="ri-user-line"></i> Cliente Solicitante</label>
                                {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select cliente_id', 'id' => 'cliente_id'])
                                ->options([$reserva->cliente_id => $reserva->cliente->razao_social])
                                !!}
                            </div>
                            @else
                            <div class="col-md-6 col-12 uf-field">
                                <label class="form-label required" for="cliente_id"><i class="ri-user-line"></i> Cliente Solicitante</label>
                                {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select cliente_id', 'id' => 'cliente_id'])
                                ->options(isset($item) ? [$item->cliente_id => $item->cliente->razao_social] : [])
                                !!}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 uf-section">
                        <div class="uf-section-title">
                            <span class="uf-ico"><i class="ri-file-list-3-line"></i></span>
                            Dados Cadastrais & Fiscais do Tomador
                            <small>documentos e inscrição</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4 col-12 uf-field">
                                <label class="form-label required" for="inp-razao_social"><i class="ri-building-line"></i> Razão Social / Nome</label>
                                {!!Form::text('razao_social', '')->attrs(['class' => 'form-control', 'id' => 'inp-razao_social'])->required()
                                ->value(isset($item) ? $item->razao_social : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label required" for="inp-documento"><i class="ri-id-card-line"></i> CPF / CNPJ</label>
                                {!!Form::text('documento', '')->attrs(['class' => 'cpf_cnpj form-control', 'id' => 'inp-documento'])->required()
                                ->value(isset($item) ? $item->documento : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-6 uf-field">
                                <label class="form-label" for="inp-ie"><i class="ri-barcode-box-line"></i> Inscrição Estadual</label>
                                {!!Form::text('ie', '')->attrs(['class' => 'form-control', 'id' => 'inp-ie'])
                                ->value(isset($item) ? $item->ie : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-im"><i class="ri-community-line"></i> Inscrição Municipal</label>
                                {!!Form::text('im', '')->attrs(['class' => 'form-control', 'id' => 'inp-im'])
                                ->value(isset($item) ? $item->im : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-email"><i class="ri-mail-line"></i> E-mail</label>
                                {!!Form::text('email', '')->attrs(['class' => 'form-control', 'id' => 'inp-email'])
                                ->value(isset($item) ? $item->email : '')
                                ->type('email')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-telefone"><i class="ri-phone-line"></i> Telefone</label>
                                {!!Form::tel('telefone', '')->attrs(['class' => 'fone form-control', 'id' => 'inp-telefone'])
                                ->value(isset($item) ? $item->telefone : '')
                                !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 uf-section">
                        <div class="uf-section-title">
                            <span class="uf-ico"><i class="ri-map-pin-line"></i></span>
                            Endereço do Tomador
                            <small>localização para emissão da NFS-e</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-2 col-6 uf-field">
                                <label class="form-label required" for="inp-cep"><i class="ri-map-pin-2-line"></i> CEP</label>
                                {!!Form::text('cep', '')->attrs(['class' => 'cep form-control', 'id' => 'inp-cep'])
                                ->value(isset($item) ? $item->cep : '')->required()
                                !!}
                            </div>

                            <div class="col-md-4 col-12 uf-field">
                                <label class="form-label required" for="inp-rua"><i class="ri-road-map-line"></i> Logradouro / Rua</label>
                                {!!Form::text('rua', '')->attrs(['class' => 'form-control', 'id' => 'inp-rua'])
                                ->value(isset($item) ? $item->rua : '')->required()
                                !!}
                            </div>

                            <div class="col-md-2 col-6 uf-field">
                                <label class="form-label required" for="inp-numero"><i class="ri-hashtag"></i> Número</label>
                                {!!Form::text('numero', '')->attrs(['class' => 'form-control', 'id' => 'inp-numero'])
                                ->value(isset($item) ? $item->numero : '')->required()
                                !!}
                            </div>

                            <div class="col-md-4 col-6 uf-field">
                                <label class="form-label required" for="inp-bairro"><i class="ri-community-line"></i> Bairro</label>
                                {!!Form::text('bairro', '')->attrs(['class' => 'form-control', 'id' => 'inp-bairro'])
                                ->value(isset($item) ? $item->bairro : '')->required()
                                !!}
                            </div>

                            <div class="col-md-4 col-12 uf-field">
                                <label class="form-label required" for="inp-cidade_id"><i class="ri-map-2-line"></i> Município / UF</label>
                                {!!Form::select('cidade_id', '')
                                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])->options(isset($item) ? [$item->cidade_id => $item->cidade->info] : [])
                                ->required()
                                !!}
                            </div>

                            <div class="col-md-8 col-12 uf-field">
                                <label class="form-label" for="inp-complemento"><i class="ri-file-info-line"></i> Complemento</label>
                                {!!Form::text('complemento', '')->attrs(['class' => 'form-control', 'id' => 'inp-complemento', 'placeholder' => 'Apto, Sala, Bloco...'])
                                ->value(isset($item) ? $item->complemento : '')
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ ABA 2: SERVIÇO & TRIBUTAÇÃO ═══ -->
            <div class="tab-pane fade show" id="servico" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12 uf-section">
                        <div class="uf-section-title">
                            <span class="uf-ico"><i class="ri-tools-line"></i></span>
                            Identificação do Serviço & Faturamento
                            <small>descrição e valores do serviço</small>
                        </div>
                        <div class="row g-3">
                            @isset($servicoPadrao)
                            <div class="col-md-6 col-12 uf-field">
                                <label class="form-label required" for="inp-servico_id"><i class="ri-tools-line"></i> Serviço Cadastrado</label>
                                {!!Form::select('servico_id', '')->attrs(['class' => 'select2 form-select servico_id', 'id' => 'inp-servico_id'])
                                ->options([$servicoPadrao->id => $servicoPadrao->nome])->required()
                                !!}
                            </div>
                            @else
                            <div class="col-md-6 col-12 uf-field">
                                <label class="form-label required" for="inp-servico_id"><i class="ri-tools-line"></i> Serviço Cadastrado</label>
                                {!!Form::select('servico_id', '')->attrs(['class' => 'select2 form-select servico_id', 'id' => 'inp-servico_id'])->options(isset($item) ? [$item->servico->servico_id => $item->servico->servico->nome] : [])->required()
                                !!}
                            </div>
                            @endif

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-natureza_operacao"><i class="ri-book-line"></i> Natureza da Operação</label>
                                {!!Form::text('natureza_operacao', '')->attrs(['class' => 'form-control', 'id' => 'inp-natureza_operacao'])
                                ->value(isset($item) ? $item->natureza_operacao : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-gerar_conta_receber"><i class="ri-money-dollar-box-line"></i> Gerar Conta a Receber</label>
                                {!!Form::select('gerar_conta_receber', '', [0 => 'Não', 1 => 'Sim'])
                                ->attrs(['class' => 'form-select', 'id' => 'inp-gerar_conta_receber'])
                                !!}
                            </div>

                            <div class="col-md-3 col-6 div-data_vencimento d-none uf-field">
                                <label class="form-label required" for="inp-data_vencimento"><i class="ri-calendar-line"></i> Data de Vencimento</label>
                                {!!Form::date('data_vencimento', '')->attrs(['class' => 'form-control', 'id' => 'inp-data_vencimento'])
                                !!}
                            </div>

                            <div class="col-12 uf-field">
                                <label class="form-label required" for="inp-discriminacao"><i class="ri-align-left"></i> Discriminação dos Serviços</label>
                                @isset($descricaoServico)
                                {!!Form::text('discriminacao', '')->attrs(['class' => 'form-control', 'id' => 'inp-discriminacao'])
                                ->value($descricaoServico)->required()
                                !!}
                                @else
                                {!!Form::text('discriminacao', '')->attrs(['class' => 'form-control', 'id' => 'inp-discriminacao'])
                                ->value(isset($item) ? $item->servico->discriminacao : '')->required()
                                !!}
                                @endif
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label required" for="inp-valor_servico"><i class="ri-money-dollar-circle-line"></i> Valor do Serviço (R$)</label>
                                @isset($total)
                                {!!Form::tel('valor_servico', '')->attrs(['class' => 'moeda form-control fw-bold text-success', 'id' => 'inp-valor_servico'])
                                ->value(__moeda($total))->required()
                                !!}
                                @else
                                {!!Form::tel('valor_servico', '')->attrs(['class' => 'moeda form-control fw-bold text-success', 'id' => 'inp-valor_servico'])
                                ->value(isset($item) ? __moedaInput($item->servico->valor_servico) : '')->required()
                                !!}
                                @endif
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-codigo_cnae"><i class="ri-code-box-line"></i> Cód. CNAE</label>
                                {!!Form::text('codigo_cnae', '')->attrs(['class' => 'form-control', 'id' => 'inp-codigo_cnae'])
                                ->value(isset($item) ? $item->servico->codigo_cnae : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label required" for="inp-codigo_servico"><i class="ri-barcode-line"></i> Código do Serviço</label>
                                @isset($servicoPadrao)
                                {!!Form::text('codigo_servico', '')->attrs(['class' => 'form-control', 'id' => 'inp-codigo_servico'])
                                ->value($servicoPadrao->codigo_servico)->required()
                                !!}
                                @else
                                {!!Form::text('codigo_servico', '')->attrs(['class' => 'form-control', 'id' => 'inp-codigo_servico'])
                                ->value(isset($item) ? $item->servico->codigo_servico : '')->required()
                                !!}
                                @endif
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-codigo_tributacao_municipio"><i class="ri-government-line"></i> Cód. Tributação Município</label>
                                {!!Form::text('codigo_tributacao_municipio', '')->attrs(['class' => 'form-control', 'id' => 'inp-codigo_tributacao_municipio'])
                                ->value(isset($item) ? $item->servico->codigo_tributacao_municipio : '')
                                !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 uf-section">
                        <div class="uf-section-title">
                            <span class="uf-ico"><i class="ri-scales-3-line"></i></span>
                            Regras de ISS & Local de Prestação
                            <small>retenção e exigibilidade</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label required" for="inp-exigibilidade_iss"><i class="ri-file-shield-line"></i> Exigibilidade ISS</label>
                                {!!Form::select('exigibilidade_iss', '', \App\Models\NotaServico::exigibilidades())->attrs(['class' => 'form-select', 'id' => 'inp-exigibilidade_iss'])
                                ->value(isset($item) ? $item->servico->exigibilidade_iss : '')->required()
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label required" for="inp-iss_retido"><i class="ri-percent-line"></i> ISS Retido</label>
                                {!!Form::select('iss_retido', '', [2 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select', 'id' => 'inp-iss_retido'])
                                ->value(isset($item) ? $item->servico->iss_retido : '')->required()
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label required" for="inp-responsavel_retencao_iss"><i class="ri-user-received-line"></i> Resp. pela Retenção</label>
                                {!!Form::select('responsavel_retencao_iss', '', [1 => 'Tomador', 2 => 'Prestador'])->attrs(['class' => 'form-select', 'id' => 'inp-responsavel_retencao_iss'])
                                ->value(isset($item) ? $item->servico->responsavel_retencao_iss : '')->required()
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-data_competencia"><i class="ri-calendar-event-line"></i> Data de Competência</label>
                                {!!Form::date('data_competencia', '')->attrs(['class' => 'form-control', 'id' => 'inp-data_competencia'])
                                ->value(isset($item) ? $item->servico->data_competencia : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-estado_local_prestacao_servico"><i class="ri-map-pin-line"></i> UF Local de Prestação</label>
                                {!!Form::select('estado_local_prestacao_servico', '', \App\Models\Cidade::estados())->attrs(['class' => 'form-select', 'id' => 'inp-estado_local_prestacao_servico'])
                                ->value(isset($item) ? $item->servico->estado_local_prestacao_servico : '')
                                !!}
                            </div>

                            <div class="col-md-9 col-6 uf-field">
                                <label class="form-label" for="inp-cidade_local_prestacao_servico"><i class="ri-building-4-line"></i> Município de Prestação</label>
                                {!!Form::text('cidade_local_prestacao_servico', '')->attrs(['class' => 'form-control', 'id' => 'inp-cidade_local_prestacao_servico'])
                                ->value(isset($item) ? $item->servico->cidade_local_prestacao_servico : '')
                                !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 uf-section">
                        <div class="uf-section-title">
                            <span class="uf-ico"><i class="ri-percent-line"></i></span>
                            Deduções, Descontos & Alíquotas Federais / Municipais
                            <small>retenções tributárias</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-valor_deducoes"><i class="ri-money-dollar-box-line"></i> Valor Deduções (R$)</label>
                                {!!Form::text('valor_deducoes', '')->attrs(['class' => 'moeda form-control', 'id' => 'inp-valor_deducoes'])
                                ->value(isset($item) ? $item->servico->valor_deducoes : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-desconto_incondicional"><i class="ri-discount-percent-line"></i> Desc. Incondicional (R$)</label>
                                {!!Form::text('desconto_incondicional', '')->attrs(['class' => 'moeda form-control', 'id' => 'inp-desconto_incondicional'])
                                ->value(isset($item) ? $item->servico->desconto_incondicional : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-desconto_condicional"><i class="ri-discount-percent-line"></i> Desc. Condicional (R$)</label>
                                {!!Form::text('desconto_condicional', '')->attrs(['class' => 'moeda form-control', 'id' => 'inp-desconto_condicional'])
                                ->value(isset($item) ? $item->servico->desconto_condicional : '')
                                !!}
                            </div>

                            <div class="col-md-3 col-6 uf-field">
                                <label class="form-label" for="inp-outras_retencoes"><i class="ri-hand-coin-line"></i> Outras Retenções (R$)</label>
                                {!!Form::text('outras_retencoes', '')->attrs(['class' => 'moeda form-control', 'id' => 'inp-outras_retencoes'])
                                ->value(isset($item) ? $item->servico->outras_retencoes : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-4 uf-field">
                                <label class="form-label" for="inp-aliquota_iss"><i class="ri-percent-line"></i> Alíquota ISS (%)</label>
                                {!!Form::text('aliquota_iss', '')->attrs(['class' => 'percentual form-control', 'id' => 'inp-aliquota_iss'])
                                ->value(isset($item) ? $item->servico->aliquota_iss : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-4 uf-field">
                                <label class="form-label" for="inp-aliquota_pis"><i class="ri-percent-line"></i> Alíquota PIS (%)</label>
                                {!!Form::text('aliquota_pis', '')->attrs(['class' => 'percentual form-control', 'id' => 'inp-aliquota_pis'])
                                ->value(isset($item) ? $item->servico->aliquota_pis : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-4 uf-field">
                                <label class="form-label" for="inp-aliquota_cofins"><i class="ri-percent-line"></i> Alíq. COFINS (%)</label>
                                {!!Form::text('aliquota_cofins', '')->attrs(['class' => 'percentual form-control', 'id' => 'inp-aliquota_cofins'])
                                ->value(isset($item) ? $item->servico->aliquota_cofins : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-4 uf-field">
                                <label class="form-label" for="inp-aliquota_inss"><i class="ri-percent-line"></i> Alíquota INSS (%)</label>
                                {!!Form::text('aliquota_inss', '')->attrs(['class' => 'percentual form-control', 'id' => 'inp-aliquota_inss'])
                                ->value(isset($item) ? $item->servico->aliquota_inss : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-4 uf-field">
                                <label class="form-label" for="inp-aliquota_ir"><i class="ri-percent-line"></i> Alíquota IR (%)</label>
                                {!!Form::text('aliquota_ir', '')->attrs(['class' => 'percentual form-control', 'id' => 'inp-aliquota_ir'])
                                ->value(isset($item) ? $item->servico->aliquota_ir : '')
                                !!}
                            </div>

                            <div class="col-md-2 col-4 uf-field">
                                <label class="form-label" for="inp-aliquota_csll"><i class="ri-percent-line"></i> Alíquota CSLL (%)</label>
                                {!!Form::text('aliquota_csll', '')->attrs(['class' => 'percentual form-control', 'id' => 'inp-aliquota_csll'])
                                ->value(isset($item) ? $item->servico->aliquota_csll : '')
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
        <div class="uf-actions">
            <a href="{{ route('nota-servico.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-5 btn-salvar-nfe">
                <i class="ri-save-line"></i>
                {{ isset($item) ? 'Salvar Alterações da NFS-e' : 'Salvar e Gerar NFS-e' }}
            </button>
        </div>

    </div>
</div>

