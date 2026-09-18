<style>
/* ─── Abas Estilizadas ─── */
.frete-tabs { border-bottom: 2px solid #eef0f6; gap: 8px; margin-bottom: 24px; }
.frete-tabs .nav-link { border: none !important; border-radius: 10px 10px 0 0 !important; color: #64748b; font-weight: 700; font-size: 13px; padding: 12px 20px; transition: all .2s ease; background: transparent; display: inline-flex; align-items: center; gap: 8px; position: relative; }
.frete-tabs .nav-link:hover { color: #4f46e5; background: #f8f9ff; }
.frete-tabs .nav-link.active { color: #4f46e5 !important; background: #ffffff !important; box-shadow: 0 -2px 10px rgba(0,0,0,0.03); }
.frete-tabs .nav-link.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 3px; background: #4f46e5; border-radius: 3px 3px 0 0; }

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

/* ─── Tabela Dinâmica de Despesas ─── */
.tb-dynamic-wrap { border: 1px solid #eef0f6; border-radius: 12px; overflow: hidden; background: #fff; }
.tb-dynamic-wrap table { margin-bottom: 0; }
.tb-dynamic-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; padding: 12px 14px; border-bottom: 1px solid #eef0f6; }
.tb-dynamic-wrap tbody td { padding: 10px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; }
.tb-dynamic-wrap tfoot td { background: #f8f9fc; font-size: 13.5px; padding: 12px 14px; border-top: 2px solid #e8eaf6; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 24px; }
</style>

<div class="row g-4">

    <!-- ═══ BLOCO SUPERIOR: CLIENTE, VEÍCULO & LOCAL ═══ -->
    <div class="col-12">
        <div class="p-3 bg-light rounded-3 border border-light-subtle">
            <div class="row g-3 align-items-end">
                <div class="col-md-5 col-12 uf-field">
                    <label class="form-label required" for="inp-cliente_id"><i class="ri-user-line"></i> Cliente</label>
                    <div class="input-group flex-nowrap">
                        <div>
                            <select required id="inp-cliente_id" name="cliente_id" class="cliente_id form-control">
                                @if(isset($item) && $item->cliente)
                                <option value="{{ $item->cliente_id }}">{{ $item->cliente->razao_social }}</option>
                                @endif
                            </select>
                        </div>
                        @can('clientes_create')
                        <button class="btn btn-dark px-3" data-bs-toggle="modal" data-bs-target="#modal_novo_cliente" type="button" title="Cadastrar Novo Cliente">
                            <i class="ri-add-circle-fill fs-16"></i>
                        </button>
                        @endcan
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
                        <option @isset($item) @if($item->local_id == $local->id) selected @endif @endisset value="{{ $local->id }}">{{ $local->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
                @endif
            </div>
        </div>
    </div>

    <!-- ═══ NAVEGAÇÃO POR ABAS ═══ -->
    <div class="col-12">
        <ul class="nav nav-tabs frete-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dados-iniciais" role="tab" aria-selected="true">
                    <i class="ri-road-map-line"></i>
                    1. Dados da Viagem & Rota
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#despesas" role="tab" aria-selected="false">
                    <i class="ri-coins-line"></i>
                    2. Despesas da Viagem
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ══════════════════════════════════════════════
                 ABA 1: DADOS DA VIAGEM & ROTA
            ══════════════════════════════════════════════ -->
            <div class="tab-pane fade show active" id="dados-iniciais" role="tabpanel">
                
                <!-- Subseção: Informações Financeiras -->
                <div class="uf-section">
                    <div class="uf-section-title">
                        <span class="uf-ico"><i class="ri-money-dollar-circle-line"></i></span>
                        Valores & Status Operacional
                        <small>preço cobrado e estado atual da viagem</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::tel('total', 'Valor Total do Frete (R$)')->required()
                            ->attrs(['class' => 'moeda form-control'])
                            ->value(isset($item) ? __moeda($item->total) : '')
                            !!}
                        </div>
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::tel('desconto', 'Desconto Concedido (R$)')
                            ->attrs(['class' => 'moeda form-control'])
                            ->value(isset($item) ? __moeda($item->desconto) : '')
                            !!}
                        </div>
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::tel('acrescimo', 'Acréscimo / Adicionais (R$)')
                            ->attrs(['class' => 'moeda form-control'])
                            ->value(isset($item) ? __moeda($item->acrescimo) : '')
                            !!}
                        </div>
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::select('estado', 'Status da Viagem', [
                                '' => 'Selecione o Status', 
                                'em_carregamento' => 'Em carregamento', 
                                'em_viagem' => 'Em viagem', 
                                'finalizado' => 'Finalizado'
                            ])
                            ->attrs(['class' => 'form-select'])
                            ->required()
                            !!}
                        </div>
                    </div>
                </div>

                <!-- Subseção: Datas e Horários -->
                <div class="uf-section">
                    <div class="uf-section-title">
                        <span class="uf-ico"><i class="ri-calendar-line"></i></span>
                        Cronograma da Viagem
                        <small>datas previstas de saída e chegada</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::date('data_inicio', 'Data Início da Viagem')->required()->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::time('horario_inicio', 'Horário Previsto de Início')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::date('data_fim', 'Data Final da Viagem')->required()->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6 uf-field">
                            {!!Form::time('horario_fim', 'Horário Previsto de Fim')->attrs(['class' => 'form-control'])!!}
                        </div>
                    </div>
                </div>

                <!-- Subseção: Rota e Observação -->
                <div class="uf-section">
                    <div class="uf-section-title">
                        <span class="uf-ico"><i class="ri-map-pin-line"></i></span>
                        Origem, Destino e Observações
                        <small>itinerário e anotações do frete</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5 col-12 uf-field">
                            {!!Form::select('cidade_carregamento', 'Cidade de Carregamento (Origem)', 
                            ['' => 'Selecione a cidade de origem'] + $cidades->pluck('info', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-5 col-12 uf-field">
                            {!!Form::select('cidade_descarregamento', 'Cidade de Descarregamento (Destino)', 
                            ['' => 'Selecione a cidade de destino'] + $cidades->pluck('info', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-2 col-12 uf-field">
                            {!!Form::tel('distancia_km', 'Distância (KM)')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 450'])!!}
                        </div>
                        <div class="col-12 uf-field">
                            {!!Form::text('observacao', 'Observações Adicionais')->placeholder('Informações complementares sobre carga, condições ou motorista...')->attrs(['class' => 'form-control'])!!}
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════════════════
                 ABA 2: DESPESAS DA VIAGEM
            ══════════════════════════════════════════════ -->
            <div class="tab-pane fade" id="despesas" role="tabpanel">
                <div class="uf-section">
                    <div class="uf-section-title">
                        <span class="uf-ico"><i class="ri-coins-line"></i></span>
                        Lançamento de Custos e Despesas do Frete
                        <small>combustível, pedágios, alimentação, ajudantes, manutenção</small>
                    </div>

                    <div class="tb-dynamic-wrap mb-3">
                        <div class="table-responsive">
                            <table class="table table-dynamic table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="min-width: 200px;">Tipo de Despesa</th>
                                        <th style="min-width: 250px;">Fornecedor / Parceiro</th>
                                        <th style="width: 150px;">Valor (R$)</th>
                                        <th>Observação</th>
                                        <th class="text-center" style="width: 50px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($item) && sizeof($item->despesas) > 0)
                                    @foreach($item->despesas as $d)
                                    <tr class="dynamic-form">
                                        <td>
                                            <select class="select2 form-select" name="tipo_despesa_id[]">
                                                <option value="">Selecione o Tipo</option>
                                                @foreach($tiposDespesas as $t)
                                                <option @if($t->id == $d->tipo_despesa_id) selected @endif value="{{ $t->id }}">{{ $t->nome }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="fornecedor_id ignore form-control" name="fornecedor_id[]">
                                                @if($d->fornecedor)
                                                <option value="{{ $d->fornecedor_id }}">
                                                    {{ $d->fornecedor->info }}
                                                </option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control valor moeda" name="valor_despesa[]" value="{{ __moeda($d->valor) }}" placeholder="0,00">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ignore" name="observacao_despesa[]" value="{{ $d->observacao }}" placeholder="Observação da despesa...">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-tr" title="Remover Despesa">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="dynamic-form">
                                        <td>
                                            <select class="select2 form-select" name="tipo_despesa_id[]">
                                                <option value="">Selecione o Tipo</option>
                                                @foreach($tiposDespesas as $t)
                                                <option value="{{ $t->id }}">{{ $t->nome }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="fornecedor_id ignore form-control" name="fornecedor_id[]">
                                            </select>
                                        </td>
                                        <td>
                                            <input type="tel" class="form-control valor moeda" name="valor_despesa[]" placeholder="0,00">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ignore" name="observacao_despesa[]" placeholder="Observação da despesa...">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-tr" title="Remover Despesa">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-uppercase fs-12 fw-bold text-muted">Total de Despesas</td>
                                        <td class="total-despesa text-danger fw-bold fs-14">
                                            @isset($item)
                                            R$ {{ __moeda($item->total_despesa) }}
                                            @else
                                            R$ 0,00
                                            @endisset
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div>
                        <button type="button" class="dash-btn dash-btn-light btn-add-line">
                            <i class="ri-add-line"></i> Adicionar Mais Uma Despesa
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('fretes.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                <i class="ri-save-line"></i>
                {{ isset($item) ? 'Salvar Alterações do Frete' : 'Salvar e Criar Frete' }}
            </button>
        </div>
    </div>

</div>

