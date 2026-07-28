<div class="row g-3 text-dark">
    <div class="col-12 alert alert-info border-info-subtle bg-info-subtle text-info p-3 d-flex align-items-center">
        <i class="ri-information-line me-2 fs-18"></i>
        <span>Selecione o funcionário e o período (Mês/Ano) para carregar os proventos e descontos configurados.</span>
    </div>

    <!-- Filtro de Carregamento -->
    <div class="col-md-6 col-12">
        <label class="form-label fw-semibold text-dark">Funcionário</label>
        @isset($item)
        <h4 class="text-primary mt-1">{{ $item->nome }}</h4>
        @else
        <select class="select2 form-select" name="funcionario_id" id="funcionario_id" required>
            <option value="">Selecione o funcionário</option>
            @foreach($funcionarios as $f)
            <option value="{{$f->id}}">{{ $f->nome }} ({{ $f->cpf_cnpj }})</option>
            @endforeach
        </select>
        @endif
    </div>

    <div class="col-md-3 col-6">
        <label class="form-label fw-semibold text-dark">Mês de Competência</label>
        <select class="form-select" name="mes" required>
            @foreach(\App\Models\ApuracaoMensal::mesesApuracao() as $key => $m)
            <option value="{{$m}}" @if($key==$mesAtual) selected @endif>{{ $m }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 col-6">
        <label class="form-label fw-semibold text-dark">Ano de Competência</label>
        <select class="form-select" name="ano" required>
            @foreach(\App\Models\ApuracaoMensal::anosApuracao() as $key => $a)
            <option value="{{$a}}">{{ $a }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabela de Lançamentos de Folha -->
    <div class="col-12 mt-4 func-select">
        <div class="card border rounded shadow-sm">
            <div class="card-header bg-light border-bottom py-2">
                <h5 class="card-title text-dark mb-0 fs-13"><i class="ri-list-check-2 me-1 align-middle"></i> Eventos e Descontos do Funcionário</h5>
            </div>
            
            <div class="modulo-table-wrap">
                <div class="table-responsive">
                    <table class="table table-centered mb-0 align-middle text-dark">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Remover</th>
                                <th>Descrição do Evento</th>
                                <th>Tipo (Soma/Diminui)</th>
                                <th style="width: 180px;">Valor Final</th>
                                <th style="width: 180px;">Método Lançamento</th>
                            </tr>
                        </thead>
                    <tbody id="body" class="datatable-body">
                        <!-- Preenchido dinamicamente via AJAX -->
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Aguardando a seleção do funcionário.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    <!-- Seção de Fechamento de Valores -->
    <div class="col-12 mt-4 border-top pt-4">
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::select('tipo_pagamento', 'Tipo de Pagamento / Canal', ['' => 'Selecione'] + App\Models\ApuracaoMensal::tiposPagamento())->attrs(['class' => 'form-select'])
                ->required()!!}
            </div>

            <div class="col-md-3 col-12">
                {!!Form::tel('valor_total', 'Valor Total Calculado (R$)')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_total'])->required()!!}
                <div class="form-text text-muted fs-11 mt-1">Este valor é somado/descontado automaticamente pelos eventos acima.</div>
            </div>

            <div class="col-md-6 col-12">
                {!!Form::text('observacao', 'Observações / Notas')->attrs(['class' => 'form-control'])!!}
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('apuracao-mensal.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button disabled type="submit" class="btn btn-success px-4" id="btn-save-apuracao">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Apuração
                </button>
            </div>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(function() {
        $('#funcionario_id').val('').change()
    })
    $('#funcionario_id').change(() => {
        $('.datatable-body').html('')
        let funcionario = $('#funcionario_id').val()
        if (funcionario) {
            $.get(path_url + 'apuracao-mensal/get-eventos/' + funcionario)
            .done((html) => {
                console.clear();
                console.log(html)
                if (html == "") {
                    swal("Erro", "Funcionário sem eventos de pagamento cadastrados!", "error")
                } else {
                    $('.datatable-body').html(html)
                    calcTotal()
                }
            }).fail((err) => {
                console.log(err)
            })
        } else {
            $('.datatable-body').html('<tr><td colspan="5" class="text-center text-muted py-3">Aguardando a seleção do funcionário.</td></tr>')
        }
    })

    function calcTotal() {
        console.clear()
        let total = 0
        $('.dynamic-form').each(function() {
            console.log($(this))
            var value = $(this).find('.value').val();
            var condicao = $(this).find('.condicao_chave').val();
            console.log("condicao", condicao)
            if (value) {
                value = convertMoedaToFloat(value)
                if (condicao == "soma") {
                    total += value
                } else {
                    total -= value
                }
            }
        })
        setTimeout(() => {
            $('#inp-valor_total').val(convertFloatToMoeda(total))
            if(total > 0){
                $('#btn-save-apuracao').removeAttr('disabled')
            } else {
                $('#btn-save-apuracao').attr('disabled', 'disabled')
            }
        }, 100)
    }

    $(".datatable-body").on('click', '.btn-delete-row', function () {
        $(this).closest('tr').remove();
        swal("Sucesso", "Evento removido!", "success")
        calcTotal()
    });

    $(document).on("blur", ".value", function () {
        calcTotal()
    });
</script>
@endsection