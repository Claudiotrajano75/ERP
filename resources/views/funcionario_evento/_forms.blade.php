<div class="row g-3 text-dark">
    
    @if(!isset($item))
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-2 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-user-line text-primary"></i> 1. Seleção do Funcionário
            </h5>
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label required fw-semibold">Colaborador / Funcionário</label>
                    {!! Form::select('funcionario_id', '', ['' => 'Selecione o profissional'] + $funcionarios->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2'])->required() !!}
                    <div class="form-text text-muted fs-11 mt-1">Selecione o funcionário para o qual deseja configurar a grade de eventos.</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3 flex-wrap gap-2">
                <h5 class="text-dark mb-0 d-flex align-items-center gap-2 fs-14 fw-bold">
                    <i class="ri-list-check-2 text-primary"></i> 2. Grade de Eventos e Valores de Folha
                </h5>
                <button type="button" class="dash-btn dash-btn-primary btn-add" style="font-size: 12px; padding: 6px 14px;">
                    <i class="ri-add-line me-1"></i> Adicionar Linha de Evento
                </button>
            </div>
            
            <div class="tb-wrap">
                <div class="table-responsive">
                    <table class="table table-centered table-dynamic table-hover mb-0 align-middle text-dark">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">Ação</th>
                                <th>Evento de Folha</th>
                                <th>Operação (Condição)</th>
                                <th style="width: 180px;">Valor Base</th>
                                <th style="width: 180px;">Método Entrada</th>
                                <th style="width: 120px;">Ativo</th>
                            </tr>
                        </thead>
                        <tbody id="body" class="datatable-body">
                            @isset($item)
                            @foreach($item->eventos as $ev)
                            <tr class="dynamic-form">
                                <td class="text-center">
                                    <button type="button" class="act-btn act-del btn-remove" title="Remover Linha">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                                <td>
                                    <select required name="evento[]" class="form-select evento">
                                        <option value="">Selecione o evento</option>
                                        @foreach($eventos as $e)
                                        <option @if($e->id == $ev->evento_id) selected @endif value="{{$e->id}}" data-condicao="{{ $e->condicao }}" data-metodo="{{ $e->metodo }}">{{$e->nome}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select required name="condicao[]" class="form-select condicao_chave" readonly>
                                        <option value="">Selecione</option>
                                        <option @if($ev->condicao == "soma") selected @endif value="soma">Soma (Provento)</option>
                                        <option @if($ev->condicao == "diminui") selected @endif value="diminui">Diminui (Desconto)</option>
                                    </select>
                                </td>
                                <td>
                                    <input value="{{ __moeda($ev->valor) }}" required type="tel" name="valor[]" class="form-control moeda" placeholder="0,00">
                                </td>
                                <td>
                                    <select required name="metodo[]" class="form-select metodo">
                                        <option value="">Selecione</option>
                                        <option @if($ev->metodo == "informado") selected @endif value="informado">Informado</option>
                                        <option @if($ev->metodo == "fixo") selected @endif value="fixo">Fixo</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="ativo[]" class="form-select ativo">
                                        <option @if($ev->ativo == 1) selected @endif value="1">Sim</option>
                                        <option @if($ev->ativo == 0) selected @endif value="0">Não</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="datatable-row dynamic-form">
                                <td class="text-center">
                                    <button type="button" class="act-btn act-del btn-remove" title="Remover Linha">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                                <td>
                                    <select required name="evento[]" class="form-select evento">
                                        <option value="">Selecione o evento</option>
                                        @foreach($eventos as $e)
                                        <option value="{{$e->id}}" data-condicao="{{ $e->condicao }}" data-metodo="{{ $e->metodo }}">{{$e->nome}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select required name="condicao[]" class="form-select condicao_chave" readonly>
                                        <option value="">Selecione</option>
                                        <option value="soma">Soma (Provento)</option>
                                        <option value="diminui">Diminui (Desconto)</option>
                                    </select>
                                </td>
                                <td>
                                    <input required type="tel" name="valor[]" class="form-control moeda" placeholder="0,00">
                                </td>
                                <td>
                                    <select required name="metodo[]" class="form-select metodo">
                                        <option value="">Selecione</option>
                                        <option value="informado">Informado</option>
                                        <option value="fixo">Fixo</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="ativo[]" class="form-select ativo">
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                    </select>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-3">
                <button type="button" class="dash-btn dash-btn-light btn-add">
                    <i class="ri-add-line me-1"></i> Adicionar Mais um Evento
                </button>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('funcionario-eventos.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
            <i class="ri-save-line me-1"></i> {{ isset($item) ? 'Salvar Alterações' : 'Salvar Associação de Eventos' }}
        </button>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $('body').on('change', '.evento', function() {
        let value = $(this).val()
        if (value) {
            const condicao = ($('option:selected', this).attr('data-condicao'));
            const metodo = ($('option:selected', this).attr('data-metodo'));
            $(this).closest('tr').find('.condicao_chave').val(condicao)
            $(this).closest('tr').find('.condicao_chave').addClass('select-disabled')
            $(this).closest('tr').find('.metodo').val(metodo)
            $(this).closest('tr').find('.metodo').addClass('select-disabled')
        }
    })
    $(".btn-add").on("click", function() {
        var $table = $(this)
            .closest(".card")
            .find(".table-dynamic");
        console.clear()
        var hasEmpty = false;
        $table.find("input, select").each(function() {
            console.log("val", $(this).val())
            if (($(this).val() == "" || $(this).val() == null)) {
                hasEmpty = true;
            }
        });
        if (hasEmpty) {
            swal(
                "Atenção"
                , "Preencha todos os campos antes de adicionar novos."
                , "warning"
            );
            return;
        }
        console.log($table)
        var $tr = $table.find(".dynamic-form").first();
        console.log($tr)
        var $clone = $tr.clone();
        $clone.show();
        $clone.find("input,select").val("");
        $clone.find(".ativo").val("1");
        $clone.find(".moeda").mask('000000000000000,00', {
            reverse: true
        });
        $table.append($clone);
    });
    $(document).delegate(".btn-remove", "click", function(e) {
        e.preventDefault();
        swal({
            title: "Você tem certeza?"
            , text: "Deseja remover essa linha de evento?"
            , icon: "warning"
            , buttons: ["Cancelar", "Sim, remover"]
        }).then(willDelete => {
            if (willDelete) {
                var trLength = $(this)
                    .closest("tr")
                    .closest("tbody")
                    .find("tr")
                    .not(".dynamic-form-document").length;
                if (!trLength || trLength > 1) {
                    $(this)
                        .closest("tr")
                        .remove();
                } else {
                    swal(
                        "Atenção"
                        , "Você deve manter ao menos um evento na lista"
                        , "warning"
                    );
                }
            }
        })
    })
</script>
@endsection