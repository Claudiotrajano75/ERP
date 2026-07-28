<div class="row g-3 text-dark">
    
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-swap-line text-primary me-2 align-middle fs-18"></i> Mapeamento CST/CSOSN e CFOP
        </h5>
        <p class="text-muted fs-13 mb-4">Defina o De/Para dos códigos fiscais de entrada e saída. Ao importar um XML, o sistema usará esse mapeamento para preencher automaticamente a tributação dos produtos.</p>
        
        <div class="row g-3">
            <div class="col-md-5 col-12">
                {!!Form::select('cst_csosn_entrada', 'CST / CSOSN de Entrada (Fornecedor)', ['' => 'Selecione'] + App\Models\Produto::listaCSTCSOSN())
                ->attrs(['class' => 'select2 cst_csosn form-select'])!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('cfop_entrada', 'CFOP Entrada')->attrs(['class' => 'cfop form-control', 'placeholder' => 'Ex: 5102'])!!}
            </div>

            <div class="col-12 mt-2">
                <div class="d-flex align-items-center">
                    <hr class="flex-grow-1 opacity-15">
                    <span class="mx-3 text-muted fs-12 text-uppercase fw-bold">Converte para</span>
                    <hr class="flex-grow-1 opacity-15">
                </div>
            </div>

            <div class="col-md-5 col-12">
                {!!Form::select('cst_csosn_saida', 'CST / CSOSN de Saída (Destino)', ['' => 'Selecione'] + App\Models\Produto::listaCSTCSOSN())
                ->attrs(['class' => 'select2 cst_csosn form-select'])!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('cfop_saida', 'CFOP Saída')->attrs(['class' => 'cfop form-control', 'placeholder' => 'Ex: 1102'])!!}
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('relacao-dados-fornecedor.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Relação
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(document).on("blur", ".cfop", function () {
        let v = $(this).val()
        if(v.length > 0){
            $(".cfop").each(function () {
                $(this).attr('required', 1)
                $(this).prev().addClass('required')
            })
        }else{
            $(".cfop").each(function () {
                $(this).removeAttr('required')
                $(this).prev().removeClass('required')
            })
        }
    });

    $(document).on("change", ".cst_csosn", function () {
        let v = $(this).val()
        if(v){
            $(".cst_csosn").each(function () {
                $(this).attr('required', 1)
                $(this).prev().addClass('required')
            })
        }else{
            $(".cst_csosn").each(function () {
                $(this).removeAttr('required')
                $(this).prev().removeClass('required')
            })
        }
    });
</script>
@endsection