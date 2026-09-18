<style>
/* ─── Seções do formulário ─── */
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select, .uf-field .input-group .form-control, .uf-field .input-group .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }
.uf-field .input-group-text, .uf-field .input-group .btn { border-radius: 0 10px 10px 0 !important; }
.uf-field .input-group .form-select { border-radius: 10px 0 0 10px !important; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ Seção 1: Colaborador / Funcionário ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-line"></i></span>
            1. Colaborador / Profissional
            <small>selecione o colaborador da pausa</small>
        </div>
        <div class="row g-3">
            @isset($funcionarios)
            <div class="col-md-6 col-12 uf-field">
                <label class="form-label" for="inp-funcionario_id"><i class="ri-user-star-line"></i> Selecione o Colaborador</label>
                {!!Form::select('funcionario_id', '', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())
                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-funcionario_id'])
                ->required()
                !!}
            </div>
            @else
            <div class="col-md-6 col-12">
                <input type="hidden" value="{{ $item->id }}" name="funcionario_id">
                <div class="p-3 bg-light border rounded-3 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; font-size: 18px; font-weight: 700;">
                        {{ strtoupper(substr($item->funcionario->nome ?? 'C', 0, 2)) }}
                    </div>
                    <div>
                        <span class="fs-11 text-muted text-uppercase fw-bold d-block">Profissional Vinculado</span>
                        <strong class="text-dark fs-15">{{ $item->funcionario->nome }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ Seção 2: Horários & Regras de Intervalo ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-time-line"></i></span>
            2. Horários & Regras de Intervalo
            <small>dia da semana, horário e motivo</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-12 uf-field">
                <label class="form-label"><i class="ri-calendar-line"></i> Dia da Semana</label>
                {!!Form::select('dia', '', App\Models\Interrupcoes::getDias())->attrs(['class' => 'form-select'])->required()!!}
            </div>
            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-time-line"></i> Início</label>
                {!!Form::text('inicio', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()!!}
            </div>
            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-time-line"></i> Término</label>
                {!!Form::text('fim', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()!!}
            </div>
            <div class="col-md-2 col-6 uf-field">
                <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                {!!Form::select('status', '', ['1' => 'Ativo', '0' => 'Inativo'])->attrs(['class' => 'form-select'])->required()!!}
            </div>

            <div class="col-md-3 col-12 uf-field">
                <label class="form-label"><i class="ri-information-line"></i> Motivo da Interrupção</label>
                <div class="input-group">
                    <select required name="motivo" id="motivo" class="form-select">
                        <option value="">Selecione</option>
                        @foreach($motivos as $m)
                        <option @isset($item) @if($item->motivo == $m->motivo) selected @endif @endif value="{{ $m->motivo }}">{{ $m->motivo }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" onclick="novoMotivo()" title="Cadastrar Novo Motivo">
                        <i class="ri-add-line text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('interrupcoes.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ ($formType ?? '') === 'edit' ? 'Salvar Alterações' : 'Salvar Intervalo' }}
            </button>
        </div>
    </div>

</div>

@include('modals._novo_motivo', ['not_submit' => true])

@section('js')
<script type="text/javascript">
    function novoMotivo(){
        $('#modal-novo-motivo').modal('show');
    }

    $(function() {
        $('.btn-salvar-motivo').click(() => {
            let motivo = $('#novo_motivo').val();

            if(motivo.length >= 4){
                let empresa_id = $("#empresa_id").val();

                $.post(path_url + "api/interrupcao/store-motivo", {
                    motivo: motivo,
                    empresa_id: empresa_id
                })
                .done((success) => {
                    $('#novo_motivo').val('');
                    var newOption = new Option(motivo, motivo, false, true);
                    $('#motivo').append(newOption).trigger('change');
                    swal("Sucesso", "Motivo cadastrado!", "success");
                    $('#modal-novo-motivo').modal('hide');
                })
                .fail((err) => {
                    console.log(err);
                    swal("Erro", "Algo deu errado ao cadastrar o motivo.", "error");
                    $('#modal-novo-motivo').modal('hide');
                });
            } else {
                swal("Alerta", "Informe no mínimo 4 caracteres para o motivo", "warning");
            }
        });
    });
</script>
@endsection

