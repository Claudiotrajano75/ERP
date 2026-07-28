<div class="modal fade" id="event-modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form class="needs-validation" id="form-event" method="post" action="{{ route('agendamentos.store') }}">
                @csrf
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);">
                    <div>
                        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="modal-title">
                            <i class="ri-add-circle-line" style="background: rgba(255,255,255,0.12); padding: 6px; border-radius: 8px; color: #a8b5ff;"></i>
                            Novo Agendamento
                        </h5>
                        <p class="text-white-50 mb-0 fs-13" style="color: rgba(255,255,255,0.6) !important;">
                            Preencha os dados para agendar um novo horário.
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-lg-6 col-12">
                                    <label class="form-label fw-semibold fs-12 text-muted mb-1">Serviços</label>
                                    <select class="select2 form-control select2-multiple" name="servicos[]" data-toggle="select2" multiple="multiple" id="servicos">
                                        @foreach ($servicos as $item)
                                        <option value="{{$item->id}}" data-id="{{$item->id}}" data-valor="{{$item->valor}}" data-tempo="{{$item->tempo_servico}}">{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 col-12">
                                    <label class="form-label fw-semibold fs-12 text-muted mb-1">Funcionário</label>
                                    {!!Form::select('funcionario_id', '')->attrs(['class' => 'form-select']) !!}
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <button type="button" class="btn btn-info w-100" id="btn-buscar-horarios">
                                        Buscar Horários
                                        <i class="ri-search-2-fill"></i>
                                    </button>
                                </div>
                                <div class="col-lg-3"></div>

                            </div>

                            <div class="row">
                                <label class="control-label form-label fw-semibold fs-12 text-muted mb-1 mt-3">Horários disponíveis</label>
                                <div class="table-responsive" style="height: 300px; overflow-y: scroll;">
                                    <table class="table" id="tabela-novo-agendamento">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Atendente</th>
                                                <th>Horário</th>
                                                <th>Valor</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="4">
                                                    Busque os horários para exibir na tabela
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>

                        <div class="row mt-3 g-2">
                            <div class="col-lg-6 col-12">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Cliente</label>
                                {!!Form::select('cliente_id', '')->attrs(['class' => 'form-select'])->required() !!}
                            </div>

                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Início</label>
                                {!!Form::tel('inicio', '')->attrs(['class' => 'form-control timer']) !!}
                            </div>

                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Término</label>
                                {!!Form::tel('termino', '')->attrs(['class' => 'form-control timer']) !!}
                            </div>

                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Desconto</label>
                                {!!Form::tel('desconto', '')->attrs(['class' => 'form-control moeda']) !!}
                            </div>
                            <div class="col-lg-2 col-6">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Total</label>
                                {!!Form::tel('total', '')->attrs(['class' => 'form-control moeda'])->required() !!}
                            </div>

                            <div class="col-lg-10 col-12">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Observação</label>
                                {!!Form::text('observacao', '')->attrs(['class' => 'form-control']) !!}
                            </div>

                            <div class="col-lg-3 col-6">
                                <label class="form-label fw-semibold fs-12 text-muted mb-1">Prioridade</label>
                                {!!Form::select('prioridade', '',
                                ['baixa' => 'Baixa', 'media' => 'Media', 'alta' => 'Alta'])->attrs(['class' => 'form-select']) !!}
                            </div>

                            <input type="hidden" name="funcionario" id="funcionario">
                            <input type="hidden" name="data" id="data">
                        </div>

                    </div>

                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line align-middle me-1"></i> Sair
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-event">
                            <i class="ri-save-line align-middle me-1"></i> Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div>
