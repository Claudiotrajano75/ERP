<div class="row g-3 text-dark">
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-wifi-off-line text-primary"></i>
                Parâmetros da Contingência
            </h5>
            <div class="row g-3">
                <div class="col-md-3 col-12">
                    {!!Form::select('tipo', 'Tipo de Contingência', ['' => 'Selecione o Tipo'] + \App\Models\Contigencia::tiposContigencia())
                    ->required()
                    ->attrs(['class' => 'form-select', 'id' => 'inp-tipo'])
                    !!}
                </div>

                <div class="col-md-3 col-12">
                    {!!Form::select('documento', 'Tipo de Documento Fiscal', ['' => 'Selecione o Documento', 'NFe' => 'NFe (Nota Grande)', 'NFCe' => 'NFCe (Cupom)'])
                    ->required()
                    ->attrs(['class' => 'form-select', 'id' => 'inp-documento'])
                    !!}
                </div>
                
                <div class="col-md-6 col-12">
                    {!!Form::text('motivo', 'Motivo da Entrada em Contingência')
                    ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Instabilidade técnica temporária no webservice da SEFAZ'])
                    ->required()
                    !!}
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-warning border-0 py-2 px-3 mb-0" style="border-radius: 8px; font-size: 12.5px;">
                        <i class="ri-alert-line me-1"></i>
                        <strong>Atenção:</strong> A contingência deve ser ativada apenas em casos reais de indisponibilidade da SEFAZ. Assim que o serviço normalizar, desative a contingência e transmita os documentos pendentes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-end gap-2 pt-2">
    <a href="{{ route('contigencia.index') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line me-1"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Ativar Contingência
    </button>
</div>

@section('js')
<script type="text/javascript">
    $(document).on("change", "#inp-tipo", function() {
        let tipo = $(this).val();
        $("#inp-documento option").removeAttr('disabled');
        if(tipo == 'OFFLINE'){
            $("#inp-documento option[value='NFe']").attr('disabled', 1);
        }else{
            $("#inp-documento option[value='NFCe']").attr('disabled', 1);
        }
    });
</script>
@endsection