@section('css')
<style>
    /* ─── Seções do formulário ─── */
    .uf-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        border-bottom: 1px solid #eef0f6;
        padding-bottom: 10px;
        margin-bottom: 16px;
    }
    .uf-section-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 15px;
    }

    /* ─── Campos do Form ─── */
    .uf-field { margin-bottom: 14px; }
    .uf-field label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .uf-field label.required:after { content: " *"; color: #ef4444; font-weight: 700; }
    .uf-field .form-control,
    .uf-field .form-select {
        border-radius: 9px;
        border: 1px solid #d1d5db;
        padding: 9px 12px;
        font-size: 13px;
        color: #111827;
        transition: border-color .15s ease, box-shadow .15s ease;
        background-color: #fff;
    }
    .uf-field .form-control:focus,
    .uf-field .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        outline: 0;
    }

    /* ─── Ações Rodapé ─── */
    .uf-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
        margin-top: 20px;
        border-top: 1px solid #eef0f6;
    }
    .uf-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 20px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        border: 0;
        cursor: pointer;
        transition: transform .1s ease, box-shadow .15s ease;
        text-decoration: none;
    }
    .uf-btn:hover { transform: translateY(-1px); }
    .uf-btn-primary { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; box-shadow: 0 4px 10px rgba(79,70,229,.25); }
    .uf-btn-primary:hover { color: #fff; box-shadow: 0 6px 14px rgba(79,70,229,.35); }
</style>
@endsection

<div class="row g-3">

    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-money-dollar-circle-line"></i> Dados do Lançamento
        </div>
    </div>

    <div class="col-md-2 uf-field">
        <label class="required">Mês</label>
        <select class="form-select" name="mes" id="inp-mes" required>
            @foreach(\App\Models\FinanceiroContador::meses() as $key => $m)
            <option value="{{$m}}" @if($key==$mesAtual) selected @endif>{{ ($m) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 uf-field">
        <label class="required">Ano</label>
        <select class="form-select" name="ano" id="inp-ano" required>
            @foreach(\App\Models\FinanceiroContador::anos() as $key => $a)
            <option @if(date('Y') == $a) selected @endif value="{{$a}}">{{ $a }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('total_venda', 'Total de vendas')
        ->attrs(['class' => 'form-control moeda', 'id' => 'inp-total_venda'])
        ->required()
        ->value(__moeda($data->total))
        !!}
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('percentual_comissao', '% Comissão')
        ->attrs(['class' => 'form-control percentual', 'id' => 'inp-percentual_comissao'])
        ->required()
        ->value($contador->percentual_comissao)
        !!}
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('valor_comissao', 'Valor da comissão')
        ->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_comissao'])
        ->required()
        ->value(__moeda($data->comissao))
        !!}
    </div>

    <div class="col-md-3 uf-field">
        {!!Form::select('tipo_pagamento', 'Tipo de Pagamento', ['' => 'Selecione'] + App\Models\ApuracaoMensal::tiposPagamento())
        ->attrs(['class' => 'form-select', 'id' => 'inp-tipo_pagamento'])
        ->required()
        !!}
    </div>

    <div class="col-md-3 uf-field">
        {!!Form::select('status_pagamento', 'Status de Pagamento', [0 => 'Pendente', 1 => 'Pago'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-status_pagamento'])
        ->required()
        !!}
    </div>

    <div class="col-md-6 uf-field">
        {!!Form::text('observacao', 'Observação')
        ->attrs(['class' => 'form-control', 'id' => 'inp-observacao', 'placeholder' => 'Observações complementares'])
        !!}
    </div>

    <div class="col-12">
        <div class="uf-actions">
            <button type="submit" class="uf-btn uf-btn-primary" id="btn-store">
                <i class="ri-save-line"></i> Salvar Pagamento
            </button>
        </div>
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
