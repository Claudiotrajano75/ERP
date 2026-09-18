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

/* ─── Cards dos Dias da Semana ─── */
.dia-card { padding: 12px 16px; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 12px; transition: all 0.2s ease; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
.dia-card:hover { border-color: #4f46e5; background: #f8faff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,0.08); }
.dia-card .form-check-input { width: 2.2em; height: 1.2em; cursor: pointer; }
.dia-card .form-check-input:checked { background-color: #4f46e5; border-color: #4f46e5; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ Seção 1: Colaborador / Funcionário ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-line"></i></span>
            1. Colaborador / Profissional
            <small>escolha o colaborador da escala</small>
        </div>
        <div class="row g-3">
            @isset($funcionarios)
            <div class="col-md-6 col-12 uf-field">
                <label class="form-label" for="inp-funcionario_id"><i class="ri-user-star-line"></i> Selecione o Colaborador</label>
                {!!Form::select('funcionario_id', '', ['' => 'Selecione um profissional'] + $funcionarios->pluck('nome', 'id')->all())
                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-funcionario_id'])
                ->required()
                !!}
                <div class="form-text text-muted fs-11 mt-1">Selecione o profissional para definir os dias de expediente semanal.</div>
            </div>
            @else
            <div class="col-md-6 col-12">
                <input type="hidden" value="{{ $item->funcionario->id }}" name="funcionario_id" id="inp-funcionario_id">
                <div class="p-3 bg-light border rounded-3 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; font-size: 18px; font-weight: 700;">
                        {{ strtoupper(substr($item->funcionario->nome ?? 'C', 0, 2)) }}
                    </div>
                    <div>
                        <span class="fs-11 text-muted text-uppercase fw-bold d-block">Colaborador Vinculado</span>
                        <strong class="text-dark fs-15">{{ $item->funcionario->nome }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ Seção 2: Dias de Expediente / Atendimento ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-calendar-check-line"></i></span>
            2. Dias de Atendimento Semanal
            <small>marque os dias disponíveis</small>
        </div>

        <div class="row g-3">
            @foreach($dias as $key => $d)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="dia-card d-flex align-items-center justify-content-between" onclick="document.getElementById('dia-{{ $key }}').click();">
                    <label class="form-check-label fw-semibold ms-1 mb-0" style="cursor: pointer; font-size: 13.5px; color: #1e293b;" for="dia-{{ $key }}" onclick="event.stopPropagation();">
                        <i class="ri-calendar-line me-1 text-muted"></i> {{ $d }}
                    </label>
                    <div class="form-check form-switch mb-0" onclick="event.stopPropagation();">
                        <input name="dia[]" value="{{ $key }}" type="checkbox" class="form-check-input" id="dia-{{ $key }}" @isset($item) @if(in_array($key, $diasEdit)) checked @endif @endif>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('atendimentos.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ ($formType ?? '') === 'edit' ? 'Salvar Alterações' : 'Salvar Configuração' }}
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(function() {
        // Validação se o funcionário já possui registro cadastrado
        $(document).on("change", "#inp-funcionario_id", function () {
            let val = $(this).val();
            if(val) {
                $.get(path_url + "api/funcionarios/valida-atendimento", { funcionario_id: val })
                .done((success) => {
                    if(success == 1){
                        swal("Alerta", "Esse funcionário já possui dias de atendimento cadastrados!", "warning");
                        $('#inp-funcionario_id').val(null).trigger('change');
                    }
                })
                .fail((err) => {
                    console.log(err);
                });
            }
        });
    });
</script>
@endsection

