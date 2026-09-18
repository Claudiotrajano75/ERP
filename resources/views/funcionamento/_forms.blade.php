<style>
/* ─── Seções do formulário ─── */
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Tabela de Horários no Formulário ─── */
.tb-forms-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-forms-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 12px 16px; border-bottom: 1px solid #e8eaf6; }
.tb-forms-wrap tbody td { padding: 10px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; }
.tb-forms-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ Seção 1: Colaborador / Funcionário ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-line"></i></span>
            1. Colaborador / Profissional
            <small>selecione o colaborador</small>
        </div>
        <div class="row g-3">
            @isset($funcionarios)
            <div class="col-md-6 col-12 uf-field">
                <label class="form-label" for="inp-funcionario_id"><i class="ri-user-star-line"></i> Selecione o Colaborador</label>
                {!!Form::select('funcionario_id', '', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())
                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-funcionario_id'])
                ->required()
                !!}
                <div class="form-text text-muted fs-11 mt-1">Ao selecionar, os dias de atendimento vinculados serão carregados automaticamente na grade abaixo.</div>
            </div>
            @else
            <div class="col-md-6 col-12">
                <input type="hidden" value="{{ $item->id }}" name="funcionario_id" id="inp-funcionario_id">
                <div class="p-3 bg-light border rounded-3 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; font-size: 18px; font-weight: 700;">
                        {{ strtoupper(substr($item->nome ?? 'C', 0, 2)) }}
                    </div>
                    <div>
                        <span class="fs-11 text-muted text-uppercase fw-bold d-block">Colaborador Vinculado</span>
                        <strong class="text-dark fs-15">{{ $item->nome }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ Seção 2: Horários por Dia da Semana ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-calendar-event-line"></i></span>
            2. Horários por Dia da Semana
            <small>defina os horários de entrada e saída</small>
        </div>

        <div class="tb-forms-wrap">
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
                            <td class="uf-field">
                                {!!Form::text('', '')->attrs(['class' => 'form-control bg-light'])->readonly()
                                ->value(\App\Models\DiaSemana::getDiaStr($f->dia_id))
                                !!}
                            </td>
                            <td class="uf-field">
                                {!!Form::text('inicio[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
                                ->value($f->inicioParse)
                                !!}
                            </td>
                            <td class="uf-field">
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
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('funcionamentos.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ ($formType ?? '') === 'edit' ? 'Salvar Alterações' : 'Salvar Horários' }}
            </button>
        </div>
    </div>

</div>

