<style>
/* ─── Abas Estilizadas ─── */
.manut-tabs { border-bottom: 2px solid #eef0f6; gap: 8px; margin-bottom: 24px; }
.manut-tabs .nav-link { border: none !important; border-radius: 10px 10px 0 0 !important; color: #64748b; font-weight: 700; font-size: 13px; padding: 12px 20px; transition: all .2s ease; background: transparent; display: inline-flex; align-items: center; gap: 8px; position: relative; }
.manut-tabs .nav-link:hover { color: #4f46e5; background: #f8f9ff; }
.manut-tabs .nav-link.active { color: #4f46e5 !important; background: #ffffff !important; box-shadow: 0 -2px 10px rgba(0,0,0,0.03); }
.manut-tabs .nav-link.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 3px; background: #4f46e5; border-radius: 3px 3px 0 0; }

/* ─── Seções do formulário ─── */
.uf-section { margin-bottom: 24px; }
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 28px; height: 28px; border-radius: 8px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 11.5px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label, label { display: block; font-size: 12.5px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select, .form-control, .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus, .form-control:focus, .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Input Group com Select2 ─── */
.input-group.flex-nowrap { display: flex !important; flex-wrap: nowrap !important; align-items: stretch; }
.input-group.flex-nowrap > div { flex: 1 1 auto; min-width: 0; }
.input-group.flex-nowrap .select2-container { width: 100% !important; display: block !important; }
.input-group.flex-nowrap .select2-selection { border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; height: 40px !important; }
.input-group.flex-nowrap .select2-selection__rendered { line-height: 38px !important; }
.input-group.flex-nowrap .btn { flex-shrink: 0; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; height: 40px !important; display: inline-flex; align-items: center; justify-content: center; }

/* ─── Tabela Dinâmica ─── */
.tb-dynamic-wrap { border: 1px solid #eef0f6; border-radius: 12px; overflow: hidden; background: #fff; }
.tb-dynamic-wrap table { margin-bottom: 0; }
.tb-dynamic-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; padding: 12px 14px; border-bottom: 1px solid #eef0f6; }
.tb-dynamic-wrap tbody td { padding: 10px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; }
.tb-dynamic-wrap tfoot td { background: #f8f9fc; font-size: 13.5px; padding: 12px 14px; border-top: 2px solid #e8eaf6; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 24px; }
</style>

<div class="row g-4">

    <!-- ═══ BLOCO SUPERIOR: FORNECEDOR & VEÍCULO ═══ -->
    <div class="col-12">
        <div class="p-3 bg-light rounded-3 border border-light-subtle">
            <div class="row g-3 align-items-end">
                <div class="col-md-5 col-12 uf-field">
                    <label class="form-label required" for="inp-fornecedor_id"><i class="ri-store-2-line"></i> Fornecedor / Oficina</label>
                    <div class="input-group flex-nowrap">
                        <div>
                            <select required id="inp-fornecedor_id" name="fornecedor_id" class="form-control">
                                @if(isset($item) && $item->fornecedor)
                                <option value="{{ $item->fornecedor_id }}">{{ $item->fornecedor->info }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12 uf-field">
                    {!! Form::select('veiculo_id', 'Veículo da Frota', ['' => 'Selecione o Veículo'] + $veiculos->pluck('info', 'id')->all())
                    ->id('veiculo')
                    ->attrs(['class' => 'select2 form-select'])
                    ->required() !!}
                </div>

                @if(__countLocalAtivo() > 1)
                <div class="col-md-3 col-12 uf-field">
                    <label class="form-label required" for="inp-local_id"><i class="ri-map-pin-line"></i> Local / Filial</label>
                    <select id="inp-local_id" required class="select2 form-select" data-toggle="select2" name="local_id">
                        <option value="">Selecione o Local</option>
                        @foreach(__getLocaisAtivoUsuario() as $local)
                        <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══ ABAS DO FORMULÁRIO ═══ -->
    <div class="col-12">
        <ul class="nav nav-tabs manut-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dados-iniciais" role="tab" aria-selected="true">
                    <i class="ri-settings-4-line"></i> Dados Gerais & Cronograma
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#servicos" role="tab" aria-selected="false">
                    <i class="ri-tools-line"></i> Serviços Realizados
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#produtos" role="tab" aria-selected="false">
                    <i class="ri-shopping-basket-line"></i> Peças & Produtos Utilizados
                </a>
            </li>
        </ul>

        <div class="tab-content">
            
            <!-- ═══ ABA 1: DADOS INICIAIS & VALORES ═══ -->
            <div class="tab-pane fade show active" id="dados-iniciais" role="tabpanel">
                <div class="row g-4">
                    
                    <!-- Bloco Cronograma & Status -->
                    <div class="col-12">
                        <div class="uf-section">
                            <div class="uf-section-title">
                                <span class="uf-ico"><i class="ri-calendar-event-line"></i></span>
                                Cronograma e Status da Manutenção
                                <small>datas e situação</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3 col-6 uf-field">
                                    <label class="form-label required" for="inp-data_inicio">Data Início</label>
                                    {!!Form::date('data_inicio', '')
                                    ->attrs(['class' => 'form-control', 'id' => 'inp-data_inicio'])
                                    ->required()!!}
                                </div>
                                <div class="col-md-3 col-6 uf-field">
                                    <label class="form-label" for="inp-data_fim">Data Fim (Previsão / Término)</label>
                                    {!!Form::date('data_fim', '')
                                    ->attrs(['class' => 'form-control', 'id' => 'inp-data_fim'])!!}
                                </div>
                                <div class="col-md-3 col-12 uf-field">
                                    <label class="form-label required" for="inp-estado">Status da Manutenção</label>
                                    {!!Form::select('estado', '', [
                                        'aguardando' => 'Aguardando',
                                        'em_manutencao' => 'Em manutenção',
                                        'finalizado' => 'Finalizado'
                                    ])
                                    ->attrs(['class' => 'form-select', 'id' => 'inp-estado'])
                                    ->required()
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bloco Valores Financeiros -->
                    <div class="col-12">
                        <div class="uf-section">
                            <div class="uf-section-title">
                                <span class="uf-ico"><i class="ri-money-dollar-circle-line"></i></span>
                                Composição de Valores e Descontos
                                <small>calculado automaticamente com serviços e peças</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3 col-6 uf-field">
                                    <label class="form-label" for="inp-desconto">Desconto (R$)</label>
                                    {!!Form::tel('desconto', '')
                                    ->attrs(['class' => 'form-control moeda', 'id' => 'inp-desconto'])
                                    ->value(isset($item) ? __moeda($item->desconto) : '')
                                    !!}
                                </div>
                                <div class="col-md-3 col-6 uf-field">
                                    <label class="form-label" for="inp-acrescimo">Acréscimo (R$)</label>
                                    {!!Form::tel('acrescimo', '')
                                    ->attrs(['class' => 'form-control moeda', 'id' => 'inp-acrescimo'])
                                    ->value(isset($item) ? __moeda($item->acrescimo) : '')
                                    !!}
                                </div>
                                <div class="col-md-3 col-12 uf-field">
                                    <label class="form-label" for="inp-total">Valor Total da Manutenção (R$)</label>
                                    {!!Form::tel('total', '')
                                    ->attrs(['class' => 'form-control moeda fw-bold text-primary', 'id' => 'inp-total'])
                                    ->value(isset($item) ? __moeda($item->total) : '')
                                    ->readonly()
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bloco Observações -->
                    <div class="col-12">
                        <div class="uf-section mb-0">
                            <div class="uf-section-title">
                                <span class="uf-ico"><i class="ri-file-text-line"></i></span>
                                Observações Gerais
                                <small>laudos, anotações de mecânica ou garantia</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 uf-field">
                                    {!!Form::textarea('observacao', '')
                                    ->attrs(['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Informe detalhes adicionais sobre o serviço ou laudo técnico...'])!!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ═══ ABA 2: SERVIÇOS REALIZADOS ═══ -->
            <div class="tab-pane fade" id="servicos" role="tabpanel">
                <div class="uf-section">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div class="uf-section-title border-0 p-0 m-0">
                            <span class="uf-ico"><i class="ri-tools-line"></i></span>
                            Serviços e Mão de Obra
                        </div>
                        <button type="button" class="dash-btn dash-btn-primary btn-add-line-servico">
                            <i class="ri-add-line"></i> Adicionar Serviço
                        </button>
                    </div>

                    <div class="tb-dynamic-wrap">
                        <div class="table-responsive">
                            <table class="table table-dynamic table-servicos table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="min-width: 250px;">Serviço</th>
                                        <th style="width: 140px;">Quantidade</th>
                                        <th style="width: 150px;">Valor Unitário</th>
                                        <th style="width: 150px;">Subtotal</th>
                                        <th>Observação</th>
                                        <th class="text-center" style="width: 60px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($item) && sizeof($item->servicos) > 0)
                                    @foreach($item->servicos as $d)
                                    <tr class="dynamic-form">
                                        <td>
                                            <select class="servico_id form-control" name="servico_id[]">
                                                <option selected value="{{ $d->servico_id }}">
                                                    {{ $d->servico->nome }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control qtd" name="quantidade_servico[]"
                                            value="{{ __moeda($d->quantidade) }}">
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_servico[]" value="{{ __moeda($d->valor_unitario) }}">
                                        </td>
                                        <td>
                                            <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_servico[]" value="{{ __moeda($d->sub_total) }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ignore" name="observacao_servico[]" value="{{ $d->observacao }}" placeholder="Observação...">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-tr" title="Remover Linha">
                                                <i class="ri-delete-bin-line fs-15"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="dynamic-form">
                                        <td>
                                            <select class="servico_id form-control" name="servico_id[]">
                                            </select>
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control qtd" name="quantidade_servico[]" placeholder="Qtd">
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_servico[]" placeholder="R$ 0,00">
                                        </td>
                                        <td>
                                            <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_servico[]" placeholder="R$ 0,00">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ignore" name="observacao_servico[]" placeholder="Observação...">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-tr" title="Remover Linha">
                                                <i class="ri-delete-bin-line fs-15"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="fw-bold text-uppercase fs-12 text-muted">Total de Serviços</td>
                                        <td class="total-servico text-primary fw-bold fs-14" colspan="3">
                                            @isset($item)
                                            R$ {{ __moeda($item->servicos->sum('sub_total')) }}
                                            @else
                                            R$ 0,00
                                            @endisset
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ ABA 3: PRODUTOS & PEÇAS UTILIZADAS ═══ -->
            <div class="tab-pane fade" id="produtos" role="tabpanel">
                <div class="uf-section">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div class="uf-section-title border-0 p-0 m-0">
                            <span class="uf-ico"><i class="ri-shopping-basket-line"></i></span>
                            Peças e Produtos Utilizados
                        </div>
                        <button type="button" class="dash-btn dash-btn-primary btn-add-line-produto">
                            <i class="ri-add-line"></i> Adicionar Produto
                        </button>
                    </div>

                    <div class="tb-dynamic-wrap">
                        <div class="table-responsive">
                            <table class="table table-dynamic table-produtos table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="min-width: 250px;">Produto / Peça</th>
                                        <th style="width: 140px;">Quantidade</th>
                                        <th style="width: 150px;">Valor Unitário</th>
                                        <th style="width: 150px;">Subtotal</th>
                                        <th>Observação</th>
                                        <th class="text-center" style="width: 60px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($item) && sizeof($item->produtos) > 0)
                                    @foreach($item->produtos as $d)
                                    <tr class="dynamic-form">
                                        <td>
                                            <select class="produto_id form-control" name="produto_id[]">
                                                <option selected value="{{ $d->produto_id }}">
                                                    {{ $d->produto->nome }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control qtd" name="quantidade_produto[]"
                                            value="{{ __moeda($d->quantidade) }}">
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_produto[]" value="{{ __moeda($d->valor_unitario) }}">
                                        </td>
                                        <td>
                                            <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_produto[]" value="{{ __moeda($d->sub_total) }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ignore" name="observacao_produto[]" value="{{ $d->observacao }}" placeholder="Observação...">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-tr" title="Remover Linha">
                                                <i class="ri-delete-bin-line fs-15"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="dynamic-form">
                                        <td>
                                            <select class="produto_id form-control" name="produto_id[]">
                                            </select>
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control qtd" name="quantidade_produto[]" placeholder="Qtd">
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control valor_unitario moeda" name="valor_unitario_produto[]" placeholder="R$ 0,00">
                                        </td>
                                        <td>
                                            <input type="tel" readonly class="form-control sub_total moeda" name="sub_total_produto[]" placeholder="R$ 0,00">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ignore" name="observacao_produto[]" placeholder="Observação...">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-tr" title="Remover Linha">
                                                <i class="ri-delete-bin-line fs-15"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="fw-bold text-uppercase fs-12 text-muted">Total de Produtos</td>
                                        <td class="total-produto text-primary fw-bold fs-14" colspan="3">
                                            @isset($item)
                                            R$ {{ __moeda($item->produtos->sum('sub_total')) }}
                                            @else
                                            R$ 0,00
                                            @endisset
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- ═══ BOTÕES DE AÇÃO DO RODAPÉ ═══ -->
<div class="uf-actions">
    <a href="{{ route('manutencao-veiculos.index') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
        <i class="ri-save-line"></i> Salvar Manutenção
    </button>
</div>

