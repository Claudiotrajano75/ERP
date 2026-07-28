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
            <div class="col-md-6 col-12">
                {!!Form::select('funcionario_id', 'Funcionário', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())
                ->attrs(['class' => 'select2 form-select'])
                ->required()
                !!}
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

    <!-- ═══ Seção 2: Horários & Regras de Intervalo ═══ -->
    <div class="col-12 mt-4">
        <h5 class="section-header">
            <i class="ri-time-line"></i>
            2. Horários & Regras de Intervalo
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::select('dia', 'Dia da Semana', App\Models\Interrupcoes::getDias())->attrs(['class' => 'form-select'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::text('inicio', 'Horário de Início')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::text('fim', 'Horário de Término')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::select('status', 'Status', ['1' => 'Ativo', '0' => 'Inativo'])->attrs(['class' => 'form-select'])->required()!!}
            </div>

            <div class="col-md-3 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Motivo da Interrupção</label>
                <div class="input-group">
                    <select required name="motivo" id="motivo" class="form-select">
                        <option value="">Selecione</option>
                        @foreach($motivos as $m)
                        <option @isset($item) @if($item->motivo == $m->motivo) selected @endif @endif value="{{ $m->motivo }}">{{ $m->motivo }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center" onclick="novoMotivo()" title="Cadastrar Novo Motivo">
                        <i class="ri-add-line text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('interrupcoes.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Intervalo
                </button>
            </div>
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
