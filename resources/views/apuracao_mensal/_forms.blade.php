<div class="row g-3 text-dark">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm p-3 mb-2" style="background: #f0f4ff; border-radius: 12px;">
            <div class="d-flex align-items-start gap-2">
                <i class="ri-information-line fs-18 text-primary mt-0.5"></i>
                <div class="fs-13 text-dark">
                    Selecione o <strong>funcionário</strong> e o <strong>período de competência</strong> (Mês/Ano) para carregar e calcular os proventos e descontos configurados.
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro de Carregamento -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-user-star-line text-primary"></i> 1. Colaborador e Período de Competência
            </h5>
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label required fw-semibold">Funcionário</label>
                    @isset($item)
                    <h4 class="text-primary mt-1 fw-bold">{{ $item->nome }}</h4>
                    @else
                    <select class="select2 form-select" name="funcionario_id" id="funcionario_id" required>
                        <option value="">Selecione o funcionário...</option>
                        @foreach($funcionarios as $f)
                        <option value="{{$f->id}}">{{ $f->nome }} ({{ $f->cpf_cnpj }})</option>
                        @endforeach
                    </select>
                    @endif
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label required fw-semibold">Mês de Competência</label>
                    <select class="form-select" name="mes" required>
                        @foreach(\App\Models\ApuracaoMensal::mesesApuracao() as $key => $m)
                        <option value="{{$m}}" @if($key==$mesAtual) selected @endif>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label required fw-semibold">Ano de Competência</label>
                    <select class="form-select" name="ano" required>
                        @foreach(\App\Models\ApuracaoMensal::anosApuracao() as $key => $a)
                        <option value="{{$a}}">{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Lançamentos de Folha -->
    <div class="col-12 func-select">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-list-check-2 text-primary"></i> 2. Eventos, Proventos e Descontos Calculados
            </h5>
            
            <div class="tb-wrap">
                <div class="table-responsive">
                    <table class="table table-centered mb-0 align-middle text-dark table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">Ação</th>
                                <th>Descrição do Evento</th>
                                <th>Tipo (Operação)</th>
                                <th style="width: 180px;">Valor Calculado</th>
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
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-wallet-line text-primary"></i> 3. Fechamento e Forma de Pagamento
            </h5>
            <div class="row g-3">
                <div class="col-md-3 col-12">
                    <label class="form-label required fw-semibold">Forma de Pagamento</label>
                    {!!Form::select('tipo_pagamento', '', ['' => 'Selecione'] + App\Models\ApuracaoMensal::tiposPagamento())->attrs(['class' => 'form-select'])
                    ->required()!!}
                </div>

                <div class="col-md-3 col-12">
                    <label class="form-label required fw-semibold">Valor Total Líquido (R$)</label>
                    {!!Form::tel('valor_total', '')->attrs(['class' => 'form-control moeda fs-15 fw-bold text-success', 'id' => 'inp-valor_total', 'placeholder' => '0,00'])->required()!!}
                    <div class="form-text text-muted fs-11 mt-1">Calculado dinamicamente pelos eventos acima.</div>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label fw-semibold">Observações / Notas</label>
                    {!!Form::text('observacao', '')->placeholder('Ex: Pagamento referente a adiantamento ou horas extras...')->attrs(['class' => 'form-control'])!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('apuracao-mensal.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button disabled type="submit" class="dash-btn dash-btn-primary px-5" id="btn-save-apuracao">
            <i class="ri-save-line me-1"></i> Salvar Apuração Mensal
        </button>
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
        let total = 0
        $('.dynamic-form').each(function() {
            var value = $(this).find('.value').val();
            var condicao = $(this).find('.condicao_chave').val();
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