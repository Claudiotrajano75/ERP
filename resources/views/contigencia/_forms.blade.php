<div class="row g-3 text-dark">
    <!-- Seção 1 -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-wifi-off-line text-primary me-2 align-middle fs-18"></i>
            1. Dados da Contingência
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::select('tipo', 'Tipo', ['' => 'Selecione'] + \App\Models\Contigencia::tiposContigencia())
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>

            <div class="col-md-3 col-12">
                {!!Form::select('documento', 'Documento', ['' => 'Selecione', 'NFe' => 'NFe', 'NFCe' => 'NFCe'])
                ->required()
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
            
            <div class="col-md-6 col-12">
                {!!Form::text('motivo', 'Motivo')
                ->required()!!}
            </div>
        </div>
    </div>
</div>

<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('contigencia.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $(document).on("change", "#inp-tipo", function() {
        let tipo = $(this).val()
        $("#inp-documento option").removeAttr('disabled');
        if(tipo == 'OFFLINE'){
            $("#inp-documento option[value='NFe']").attr('disabled', 1);
        }else{
            $("#inp-documento option[value='NFCe']").attr('disabled', 1);
        }
    })
</script>
@endsection