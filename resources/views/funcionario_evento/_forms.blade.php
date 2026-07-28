<div class="row g-3 text-dark">
    
    @if(!isset($item))
    <div class="col-md-6 col-12">
        {!! Form::select('funcionario_id', 'Colaborador / Funcionário', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2'])->required() !!}
        <div class="form-text text-muted fs-11 mt-1">Selecione o profissional para o qual deseja configurar os lançamentos de folha.</div>
    </div>
    @endif

    <div class="col-12 mt-3">
        <div class="card border rounded shadow-sm">
            <div class="card-header bg-light border-bottom py-2">
                <h5 class="card-title text-dark mb-0 fs-13"><i class="ri-list-check-2 me-1 align-middle"></i> Grade de Eventos e Valores</h5>
            </div>
            
            <div class="modulo-table-wrap">
                <div class="table-responsive">
                    <table class="table table-centered table-dynamic table-hover mb-0 align-middle text-dark">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Ação</th>
                                <th>Evento de Folha</th>
                                <th>Operação (Condição)</th>
                                <th style="width: 180px;">Valor</th>
                                <th style="width: 180px;">Método Entrada</th>
                                <th style="width: 120px;">Ativo</th>
                            </tr>
                        </thead>
                    <tbody id="body" class="datatable-body">
                        @isset($item)
                        @foreach($item->eventos as $ev)
                        <tr class="dynamic-form">
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-remove" title="Remover Linha">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                            <td>
                                <select required name="evento[]" class="form-select evento">
                                    <option value="">Selecione</option>
                                    @foreach($eventos as $e)
                                    <option @if($e->id == $ev->evento_id) selected @endif value="{{$e->id}}" data-condicao="{{ $e->condicao }}" data-metodo="{{ $e->metodo }}">{{$e->nome}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select required name="condicao[]" class="form-select condicao_chave" readonly>
                                    <option value="">Selecione</option>
                                    <option @if($ev->condicao == "soma") selected @endif value="soma">Soma</option>
                                    <option @if($ev->condicao == "diminui") selected @endif value="diminui">Diminui</option>
                                </select>
                            </td>
                            <td>
                                <input value="{{ __moeda($ev->valor) }}" required type="tel" name="valor[]" class="form-control moeda">
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
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-remove" title="Remover Linha">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                            <td>
                                <select required name="evento[]" class="form-select evento">
                                    <option value="">Selecione</option>
                                    @foreach($eventos as $e)
                                    <option value="{{$e->id}}" data-condicao="{{ $e->condicao }}" data-metodo="{{ $e->metodo }}">{{$e->nome}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select required name="condicao[]" class="form-select condicao_chave" readonly>
                                    <option value="">Selecione</option>
                                    <option value="soma">Soma</option>
                                    <option value="diminui">Diminui</option>
                                </select>
                            </td>
                            <td>
                                <input required type="tel" name="valor[]" class="form-control moeda">
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

            <div class="card-footer bg-light border-top py-2">
                <button type="button" class="btn btn-sm btn-success btn-add" style="border-radius: 8px; font-weight: 600;">
                    <i class="ri-add-line align-middle me-1"></i> Adicionar Novo Evento
                </button>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('funcionario-eventos.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Alterações
                </button>
            </div>
        </div>
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
            title: "Você esta certo?"
            , text: "Deseja remover esse item mesmo?"
            , icon: "warning"
            , buttons: true
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
                        , "Você deve ter ao menos um item na lista"
                        , "warning"
                    );
                }
            }
        })
    })
</script>
@endsection