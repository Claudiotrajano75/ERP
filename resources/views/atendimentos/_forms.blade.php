<style>
.dia-card { padding: 11px 14px; background: #fff; border: 1px solid #eef0f5; border-radius: 10px; transition: all 0.15s ease; }
.dia-card:hover { border-color: #302b63; background: #fafbff; }
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
                <div class="form-text text-muted fs-11 mt-1">Selecione o profissional para vincular aos dias de expediente.</div>
            </div>
            @else
            <div class="col-12">
                <input type="hidden" value="{{ $item->funcionario->id }}" name="funcionario_id">
                <div class="p-3 bg-light border rounded shadow-sm d-flex align-items-center">
                    <i class="ri-user-star-line text-success fs-24 me-2"></i>
                    <div>
                        <span class="fs-12 text-muted text-uppercase fw-semibold d-block">Profissional Vinculado</span>
                        <strong class="text-dark fs-16">{{ $item->funcionario->nome }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ Seção 2: Dias de Expediente / Atendimento ═══ -->
    <div class="col-12 mt-4">
        <h5 class="section-header">
            <i class="ri-calendar-check-line"></i>
            2. Dias de Expediente / Atendimento
        </h5>

        <div class="row g-3">
            @foreach($dias as $key => $d)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="dia-card d-flex align-items-center justify-content-between">
                    <label class="form-check-label fw-semibold ms-1 mb-0" style="cursor: pointer;" for="dia-{{ $key }}">
                        {{ $d }}
                    </label>
                    <div class="form-check form-switch mb-0">
                        <input name="dia[]" value="{{ $key }}" type="checkbox" class="form-check-input" id="dia-{{ $key }}" style="cursor: pointer;" @isset($item) @if(in_array($key, $diasEdit)) checked @endif @endif>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('atendimentos.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Configuração
                </button>
            </div>
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
