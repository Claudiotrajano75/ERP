<div class="row g-3 text-dark">

    <!-- ═══ SEÇÃO 1: CLIENTE & OPERADOR ═══ -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-user-line text-primary me-2 align-middle fs-18"></i>
            1. Vínculos de Clientes & Operadores
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-select'])->options(isset($item) ? [$item->cliente_id => $item->cliente->razao_social] : [])->required()!!}
            </div>

            <div class="col-md-6 col-12">
                {!!Form::select('funcionario_id', 'Funcionário Responsável', ['' => 'Selecione'] + $funcionario->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: PRAZOS E VIGÊNCIA ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-calendar-event-line text-primary me-2 align-middle fs-18"></i>
            2. Horários & Agendamento
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Data de Início</label>
                <input required type="text" name="data_inicio" id="datetime-datepicker" class="form-control"
                       value="{{ isset($item) ? $item->data_inicio : '' }}" placeholder="Selecione data e hora">
                @if($errors->has('data_inicio'))
                <div class="text-danger mt-1 fs-12">Campo data de início é obrigatório.</div>
                @endif
            </div>

            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Previsão de Entrega</label>
                <input required type="text" name="data_entrega" id="datetime-datepicker2" class="form-control"
                       value="{{ isset($item) ? $item->data_entrega : '' }}" placeholder="Selecione data e hora de previsão">
                @if($errors->has('data_entrega'))
                <div class="text-danger mt-1 fs-12">Campo previsão de entrega é obrigatório.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 3: DESCRIÇÃO ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-file-text-line text-primary me-2 align-middle fs-18"></i>
            3. Laudo Técnico / Descrição Geral
        </h5>
        <div class="row g-3">
            <div class="col-12">
                {!!Form::textarea('descricao', 'Descrição / Problemas Relatados')
                ->attrs(['rows' => '10', 'class' => 'form-control tiny'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('ordem-servico.index') }}" class="btn btn-outline-secondary px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn {{ $formType === 'edit' ? 'btn-primary' : 'btn-success' }} px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i>
                {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar Ordem de Serviço' }}
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
