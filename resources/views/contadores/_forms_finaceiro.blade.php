@section('css')
<style type="text/css">
    /* Formulários de Filtro e Cadastro */
    .form-control, .form-select, select, input[type="text"], input[type="tel"], input[type="email"] {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
        background-color: #ffffff !important;
    }

    .form-control:focus, .form-select:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 13px !important;
        margin-bottom: 6px !important;
    }

    /* Botões */
    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
    }

    .btn-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
    }

    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 24px 0 !important;
    }
</style>
@endsection

<div class="row g-3">

    <div class="col-md-2">
        <label class="required">Mês</label>

        <select class="form-select" name="mes" required>
            @foreach(\App\Models\FinanceiroContador::meses() as $key => $m)
            <option value="{{$m}}" @if($key==$mesAtual) selected @endif>{{ ($m) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label class="required">Ano</label>
        <select class="form-select" name="ano" required>
            @foreach(\App\Models\FinanceiroContador::anos() as $key => $a)
            <option @if(date('Y') == $a) selected @endif value="{{$a}}">{{ $a }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        {!!Form::tel('total_venda', 'Total de vendas')
        ->attrs(['class' => 'form-control moeda'])
        ->required()
        ->value(__moeda($data->total))
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('percentual_comissao', '% Comissão')
        ->attrs(['class' => 'form-control percentual'])
        ->required()
        ->value($contador->percentual_comissao)
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('valor_comissao', 'Valor da comissão')
        ->attrs(['class' => 'form-control moeda'])
        ->required()
        ->value(__moeda($data->comissao))
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('tipo_pagamento', 'Tipo de Pagamento', ['' => 'Selecione'] + App\Models\ApuracaoMensal::tiposPagamento())->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('status_pagamento', 'Status de Pagamento', [0 => 'Pendente', 1 => 'Pago'])->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>

    <div class="col-md-6">
        {!!Form::text('observacao', 'Observação')
        !!}
    </div>
    

    <hr class="mt-4">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-5" id="btn-store">
            <i class="ri-save-line"></i> Salvar
        </button>
    </div>
</div>

@section('js')
<script>

    $(document).on("change", "#inp-tipo_pagamento", function () {
        if($(this).val()){
            $('#inp-status_pagamento').val(1).change()
        }else{
            $('#inp-status_pagamento').val(0).change()
        }
    })

</script>
@endsection
