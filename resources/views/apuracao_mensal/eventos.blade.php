@foreach($item->eventosAtivos as $ev)
<tr class="datatable-row dynamic-form align-middle">
    <td>
        <button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Remover Linha">
            <i class="ri-delete-bin-line"></i>
        </button>
    </td>

    <td>
        <select required name="evento[]" class="form-select evento select-disabled">
            <option value="{{$ev->evento_id}}" data-condicao="{{ $ev->condicao }}" data-metodo="{{ $ev->metodo }}">{{$ev->evento->nome}}</option>
        </select>
    </td>

    <td>
        <select required name="condicao[]" class="form-select condicao_chave select-disabled" readonly>
            @if($ev->condicao == "soma")
            <option value="soma">Soma</option>
            @else
            <option value="diminui">Diminui</option>
            @endif
        </select>
    </td>

    <td>
        @if($ev->evento->tipo_valor == 'percentual')
        <input @if($ev->metodo == "fixo") readonly @endif value="{{ __moeda($item->salario * ($ev->valor/100)) }}" required type="tel" name="valor[]" class="form-control value">
        @else
        <input @if($ev->metodo == "fixo") readonly @endif value="{{ __moeda($ev->valor) }}" required type="tel" name="valor[]" class="form-control value">
        @endif
    </td>

    <td>
        <select required name="metodo[]" class="form-select metodo select-disabled">
            @if($ev->metodo == "informado")
            <option value="informado">Informado</option>
            @else
            <option value="fixo">Fixo</option>
            @endif
        </select>
    </td>
</tr>
@endforeach