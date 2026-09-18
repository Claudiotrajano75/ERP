<div class="modal fade" id="event-modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <form class="needs-validation" id="form-event" method="post" action="{{ route('agendamentos.store') }}">
                @csrf
                <div class="modal-header py-3 px-4 modulo-header-gradient">
                    <div>
                        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2 mb-0" id="modal-title">
                            <i class="ri-calendar-event-line"></i>
                            Novo Agendamento
                        </h5>
                        <p class="text-white-50 mb-0 fs-12" style="opacity: 0.8;">
                            Preencha os serviços e selecione o horário ideal para confirmar o agendamento.
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-lg-6 col-12 uf-field">
                                    <label class="form-label fw-semibold fs-12 text-muted mb-1" for="servicos"><i class="ri-briefcase-line me-1"></i>Serviços Desejados</label>
                                    <select class="select2 form-control select2-multiple" name="servicos[]" data-toggle="select2" multiple="multiple" id="servicos">
                                        @foreach ($servicos as $item)
                                        <option value="{{$item->id}}" data-id="{{$item->id}}" data-valor="{{$item->valor}}" data-tempo="{{$item->tempo_servico}}">{{$item->nome}} (R$ {{ __moeda($item->valor) }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 col-12 uf-field">
                                    <label class="form-label fw-semibold fs-12 text-muted mb-1" for="inp-funcionario_id"><i class="ri-user-star-line me-1"></i>Profissional / Atendente (Opcional)</label>
                                    {!!Form::select('funcionario_id', '', ['' => 'Qualquer Atendente Disponível'] + $funcionarios->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2', 'id' => 'inp-funcionario_id']) !!}
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4 mx-auto">
                                    <button type="button" class="dash-btn dash-btn-primary w-100 py-2" id="btn-buscar-horarios">
                                        <i class="ri-search-line me-1"></i> Buscar Horários Disponíveis
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label form-label fw-bold fs-12 text-uppercase text-muted mb-2"><i class="ri-time-line me-1"></i>Horários Disponíveis na Grade</label>
                                <div class="col-12">
                                    <div class="tb-wrap" style="max-height: 260px; overflow-y: auto;">
                                        <table class="table table-centered table-hover align-middle mb-0" id="tabela-novo-agendamento">
                                            <thead>
                                                <tr>
                                                    <th>Atendente</th>
                                                    <th>Horário</th>
                                                    <th>Valor</th>
                                                    <th class="text-end" style="width: 120px;">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted py-4" colspan="4">
                                                        <i class="ri-information-line fs-18 d-block mb-1"></i>
                                                        Selecione os serviços e clique em "Buscar Horários" para consultar a grade.
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-4 g-3 border-top pt-3">
                            <div class="col-lg-6 col-12 uf-field">
                                <label class="form-label required fs-12 fw-semibold text-muted mb-1" for="cliente_id"><i class="ri-user-line me-1"></i>Cliente Solicitante</label>
                                {!!Form::select('cliente_id', '')->attrs(['class' => 'form-select select2', 'id' => 'cliente_id'])->required() !!}
                            </div>

                            <div class="col-lg-3 col-6 uf-field">
                                <label class="form-label fs-12 fw-semibold text-muted mb-1" for="inp-inicio"><i class="ri-time-line me-1"></i>Início</label>
                                {!!Form::tel('inicio', '')->attrs(['class' => 'form-control timer', 'id' => 'inp-inicio', 'placeholder' => '00:00']) !!}
                            </div>

                            <div class="col-lg-3 col-6 uf-field">
                                <label class="form-label fs-12 fw-semibold text-muted mb-1" for="inp-termino"><i class="ri-time-line me-1"></i>Término</label>
                                {!!Form::tel('termino', '')->attrs(['class' => 'form-control timer', 'id' => 'inp-termino', 'placeholder' => '00:00']) !!}
                            </div>

                            <div class="col-lg-3 col-6 uf-field">
                                <label class="form-label fs-12 fw-semibold text-muted mb-1" for="inp-desconto"><i class="ri-discount-percent-line me-1"></i>Desconto (R$)</label>
                                {!!Form::tel('desconto', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-desconto']) !!}
                            </div>

                            <div class="col-lg-3 col-6 uf-field">
                                <label class="form-label required fs-12 fw-semibold text-muted mb-1" for="inp-total"><i class="ri-money-dollar-circle-line me-1"></i>Total (R$)</label>
                                {!!Form::tel('total', '')->attrs(['class' => 'form-control moeda fw-bold text-success', 'id' => 'inp-total'])->required() !!}
                            </div>

                            <div class="col-lg-3 col-6 uf-field">
                                <label class="form-label fs-12 fw-semibold text-muted mb-1" for="inp-prioridade"><i class="ri-flag-line me-1"></i>Prioridade</label>
                                {!!Form::select('prioridade', '',
                                ['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta'])->attrs(['class' => 'form-select', 'id' => 'inp-prioridade']) !!}
                            </div>

                            <div class="col-lg-3 col-6 uf-field">
                                <label class="form-label fs-12 fw-semibold text-muted mb-1" for="inp-observacao"><i class="ri-chat-1-line me-1"></i>Observação</label>
                                {!!Form::text('observacao', '')->attrs(['class' => 'form-control', 'id' => 'inp-observacao', 'placeholder' => 'Notas...']) !!}
                            </div>

                            <input type="hidden" name="funcionario" id="funcionario">
                            <input type="hidden" name="data" id="data">
                        </div>

                    </div>

                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="dash-btn dash-btn-light px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="button" class="dash-btn dash-btn-primary px-4" id="btn-save-event">
                        <i class="ri-save-line"></i> Confirmar Agendamento
                    </button>
                </div>
            </form>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div>

