<div class="text-dark">
    <!-- Bloco 1: Carteira e Layout de Cobrança -->
    <div class="card shadow-none border mb-4">
        <div class="card-header bg-transparent border-bottom py-2.5">
            <h5 class="card-title mb-0 fs-15 text-dark"><i class="ri-bank-card-line text-primary me-2 align-middle fs-18"></i> 1. Dados Gerais da Carteira</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    {!!Form::select('conta_boleto', 'Conta de Boleto', ['' => 'Selecione'] + $contasBoleto->pluck('info', 'id')->all())->required()
                    ->attrs(['class' => 'form-select', 'id' => 'inp-conta_boleto'])
                    ->value($contaPadrao != null ? $contaPadrao->id : null)
                    !!}
                </div>
                <div class="col-md-2 col-6">
                    {!!Form::tel('carteira', 'Carteira')->required()
                    ->attrs(['class' => 'form-control', 'id' => 'inp-carteira'])
                    !!}
                </div>
                <div class="col-md-2 col-6">
                    {!!Form::tel('convenio', 'Convênio')->required()
                    ->attrs(['class' => 'form-control', 'id' => 'inp-convenio'])
                    !!}
                </div>
                <div class="col-md-2 col-6">
                    {!!Form::select('tipo', 'Layout CNAB', ['Cnab400' => 'Cnab400', 'Cnab240' => 'Cnab240'])->required()
                    ->attrs(['class' => 'form-select', 'id' => 'inp-tipo'])
                    !!}
                </div>
                <div class="col-md-2 col-6">
                    {!!Form::select('usar_logo', 'Usar Logo da Empresa', [0 => 'Não', 1 => 'Sim'])->required()
                    ->attrs(['class' => 'form-select'])
                    !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Bloco 2: Contas e Parâmetros dos Boletos -->
    <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-barcode-box-line text-primary me-2 align-middle fs-18"></i> 2. Detalhes das Contas a Receber</h5>
    
    @if(sizeof($contas) > 0)
        @foreach($contas as $index => $conta)
        <div class="card shadow-none border mb-3 overflow-hidden">
            <div class="card-header bg-light border-bottom d-flex align-items-center justify-content-between py-2.5">
                <span class="fw-semibold text-dark">{{ $index + 1 }}. Cliente: <strong class="text-primary">{{ $conta->cliente->info }}</strong></span>
                <span class="fs-14 fw-bold">Valor: <strong class="text-danger">R$ {{ __moeda($conta->valor_integral) }}</strong></span>
            </div>
            <div class="card-body">
                <input type="hidden" name="conta_id[]" value="{{ $conta->id }}">
                
                <div class="row g-3">
                    <!-- Linha 1 -->
                    <div class="col-md-3 col-6">
                        {!!Form::tel('numero[]', 'Número do Boleto')->required()
                        ->attrs(['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!!Form::tel('numero_documento[]', 'Número do Documento')->required()
                        ->attrs(['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!!Form::tel('valor[]', 'Valor')->required()
                        ->value(__moeda($conta->valor_integral))
                        ->attrs(['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!!Form::date('vencimento[]', 'Vencimento')->required()
                        ->value($conta->data_vencimento)
                        !!}
                    </div>

                    <!-- Linha 2 -->
                    <div class="col-md-2 col-6">
                        {!!Form::tel('juros[]', 'Juros (%)')->required()
                        ->attrs(['class' => 'moeda juros form-control'])
                        !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!!Form::tel('juros_apos[]', 'Juros Após (Dias)')->required()
                        ->attrs(['class' => 'juros_apos form-control', 'data-mask' => '000'])
                        !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!!Form::tel('multa[]', 'Multa (%)')->required()
                        ->attrs(['class' => 'moeda multa form-control'])
                        !!}
                    </div>
                    <div class="col-md-2 col-6 div-sicredi">
                        {!!Form::text('posto[]', 'Posto')->required()
                        ->attrs(['class' => 'posto form-control'])
                        !!}
                    </div>
                    <div class="col-md-4 col-12">
                        {!!Form::text('instrucoes[]', 'Instruções')->attrs(['placeholder' => 'Instruções adicionais', 'class' => 'form-control'])!!}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="card shadow-none border mb-3 overflow-hidden">
            <div class="card-header bg-light border-bottom d-flex align-items-center justify-content-between py-2.5">
                <span class="fw-semibold text-dark">Cliente: <strong class="text-primary">{{ $conta->cliente->info }}</strong></span>
                <span class="fs-14 fw-bold">Valor: <strong class="text-danger">R$ {{ __moeda($conta->valor_integral) }}</strong></span>
            </div>
            <div class="card-body">
                <input type="hidden" name="conta_id[]" value="{{ $conta->id }}">
                
                <div class="row g-3">
                    <!-- Linha 1 -->
                    <div class="col-md-3 col-6">
                        {!!Form::tel('numero[]', 'Número do Boleto')->required()
                        ->attrs(['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!!Form::tel('numero_documento[]', 'Número do Documento')->required()
                        ->attrs(['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!!Form::tel('valor[]', 'Valor')->required()
                        ->value(__moeda($conta->valor_integral))
                        ->attrs(['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="col-md-3 col-6">
                        {!!Form::date('vencimento[]', 'Vencimento')->required()
                        ->value($conta->data_vencimento)
                        !!}
                    </div>

                    <!-- Linha 2 -->
                    <div class="col-md-2 col-6">
                        {!!Form::tel('juros[]', 'Juros (%)')->required()
                        ->attrs(['class' => 'moeda juros form-control'])
                        !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!!Form::tel('juros_apos[]', 'Juros Após (Dias)')->required()
                        ->attrs(['class' => 'juros_apos form-control', 'data-mask' => '000'])
                        !!}
                    </div>
                    <div class="col-md-2 col-6">
                        {!!Form::tel('multa[]', 'Multa (%)')->required()
                        ->attrs(['class' => 'moeda multa form-control'])
                        !!}
                    </div>
                    <div class="col-md-2 col-6 div-sicredi">
                        {!!Form::text('posto[]', 'Posto')->required()
                        ->attrs(['class' => 'posto form-control'])
                        !!}
                    </div>
                    <div class="col-md-4 col-12">
                        {!!Form::text('instrucoes[]', 'Instruções')->attrs(['placeholder' => 'Instruções adicionais', 'class' => 'form-control'])!!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Rodapé de Confirmação -->
    <div class="row">
        <div class="col-12 mt-4">
            <hr class="text-muted opacity-25">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('conta-receber.index') }}" class="btn btn-light px-4">Cancelar</a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-barcode-line align-middle me-1"></i> Gerar Boleto(s)
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $(function(){
        setTimeout(() => {
            $('#inp-conta_boleto').change()
        }, 100)
    })

    $('body').on('change', '#inp-conta_boleto', function () {
        let conta_boleto = $(this).val()
        if(conta_boleto){
            $.get(path_url + 'api/conta-boleto', {conta_boleto_id: conta_boleto})
            .done((res) => {
                $('#inp-carteira').val(res.carteira)
                $('#inp-convenio').val(res.convenio)
                $('#inp-tipo').val(res.tipo).change()

                $('.juros').val(convertFloatToMoeda(res.juros))
                $('.multa').val(convertFloatToMoeda(res.multa))
                $('.juros_apos').val(res.juros_apos)

                if(res.banco == 'Sicredi'){
                    $('.div-sicredi').removeClass('d-none')
                    $('.posto').attr('required', 1)
                }else{
                    $('.div-sicredi').addClass('d-none')
                    $('.posto').removeAttr('required')
                }
            })
            .fail((err) => {
                console.log(err)
            })
        }
    })
</script>
@endsection