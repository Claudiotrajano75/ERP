<style>
.section-header { font-size: 15px; font-weight: 700; color: #1a1a2e; border-bottom: 2px solid #eef0f5; padding-bottom: 10px; margin-bottom: 18px; }
.section-header i { color: #302b63; font-size: 18px; margin-right: 10px; vertical-align: middle; }
.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }
</style>

<div class="row g-3 text-dark">

    <!-- ═══ Seção 1: Colaborador / Funcionário ═══ -->
    <div class="col-12">
        <h5 class="section-header">
            <i class="ri-user-line"></i>
            1. Colaborador / Funcionário
        </h5>
        <div class="row g-3">
            @isset($funcionarios)
            <div class="col-md-5 col-12">
                {!!Form::select('funcionario_id', 'Funcionário', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())
                ->attrs(['class' => 'select2 form-select'])
                ->required()
                !!}
                <div class="form-text text-muted fs-11 mt-1">Selecione o profissional para configurar a grade horária.</div>
            </div>
            @else
            <div class="col-12">
                <input type="hidden" value="{{ $item->id }}" name="funcionario_id">
                <div class="p-3 bg-light border rounded shadow-sm d-flex align-items-center">
                    <i class="ri-user-star-line text-success fs-24 me-2"></i>
                    <div>
                        <span class="fs-12 text-muted text-uppercase fw-semibold d-block">Profissional Vinculado</span>
                        <strong class="text-dark fs-16">{{ $item->nome }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ Seção 2: Horários por Dia da Semana ═══ -->
    <div class="col-12 mt-4">
        <h5 class="section-header">
            <i class="ri-calendar-event-line"></i>
            2. Horários por Dia da Semana
        </h5>

        <div class="modulo-table-wrap">
            <div class="table-responsive">
                <table class="table table-centered align-middle mb-0" id="table-horarios">
                    <thead>
                        <tr>
                            <th>Dia da Semana</th>
                            <th style="width: 250px;">Horário de Entrada</th>
                            <th style="width: 250px;">Horário de Saída</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($item)
                        @foreach($funcionamento as $key => $f)
                        <tr>
                            <input type="hidden" name="dia[]" value="{{$f->dia_id}}">
                            <td>
                                {!!Form::text('', '')->attrs(['class' => 'form-control'])->readonly()
                                ->value(\App\Models\DiaSemana::getDiaStr($f->dia_id))
                                !!}
                            </td>
                            <td>
                                {!!Form::text('inicio[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
                                ->value($f->inicioParse)
                                !!}
                            </td>
                            <td>
                                {!!Form::text('fim[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
                                ->value($f->finalParse)
                                !!}
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('funcionamentos.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Horários
                </button>
            </div>
        </div>
    </div>

</div>
