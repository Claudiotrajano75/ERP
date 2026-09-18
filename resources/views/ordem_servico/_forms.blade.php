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

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ SEÇÃO 1: CLIENTE & OPERADOR ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-line"></i></span>
            1. Vínculos de Clientes & Operadores
            <small>cliente atendido e técnico responsável</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-12 uf-field">
                <label class="form-label required" for="cliente_id"><i class="ri-user-search-line"></i> Cliente Solicitante</label>
                {!!Form::select('cliente_id', '')->attrs(['class' => 'select2 form-select', 'id' => 'cliente_id'])->options(isset($item) ? [$item->cliente_id => $item->cliente->razao_social] : [])->required()!!}
            </div>

            <div class="col-md-6 col-12 uf-field">
                <label class="form-label" for="funcionario_id"><i class="ri-user-settings-line"></i> Funcionário Responsável</label>
                {!!Form::select('funcionario_id', '', ['' => 'Selecione um funcionário'] + $funcionario->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2', 'id' => 'funcionario_id'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: PRAZOS E VIGÊNCIA ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-calendar-event-line"></i></span>
            2. Horários & Agendamento
            <small>datas de início e previsão de entrega</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-12 uf-field">
                <label class="form-label required"><i class="ri-calendar-line"></i> Data de Início</label>
                <input required type="text" name="data_inicio" id="datetime-datepicker" class="form-control"
                       value="{{ isset($item) ? $item->data_inicio : '' }}" placeholder="Selecione data e hora de início">
                @if($errors->has('data_inicio'))
                <div class="text-danger mt-1 fs-12">{{ $errors->first('data_inicio') }}</div>
                @endif
            </div>

            <div class="col-md-6 col-12 uf-field">
                <label class="form-label required"><i class="ri-time-line"></i> Previsão de Entrega</label>
                <input required type="text" name="data_entrega" id="datetime-datepicker2" class="form-control"
                       value="{{ isset($item) ? $item->data_entrega : '' }}" placeholder="Selecione data e hora de previsão">
                @if($errors->has('data_entrega'))
                <div class="text-danger mt-1 fs-12">{{ $errors->first('data_entrega') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 3: DESCRIÇÃO ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-file-text-line"></i></span>
            3. Laudo Técnico / Descrição Geral
            <small>problemas relatados e checklist inicial</small>
        </div>
        <div class="row g-3">
            <div class="col-12 uf-field">
                <label class="form-label"><i class="ri-align-left"></i> Descrição / Problemas Relatados</label>
                {!!Form::textarea('descricao', '')
                ->attrs(['rows' => '10', 'class' => 'form-control tiny'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ isset($item) ? route('ordem-servico.show', $item->id) : route('ordem-servico.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i>
                {{ isset($item) ? 'Salvar Alterações' : 'Salvar Ordem de Serviço' }}
            </button>
        </div>
    </div>

</div>

@section('js')
<script src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(function(){
        tinymce.init({ selector: 'textarea.tiny', language: 'pt_BR' });

        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none');
        }, 500);
    });
</script>
@endsection

