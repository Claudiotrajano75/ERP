<tr class="" data-estoque-minimo="{{ $item->estoque_minimo }}" data-estoque-atual="{{ $item->estoque ? $item->estoque->quantidade : 0 }}" data-nome-produto="{{ $item->nome }}">
    <input readonly type="hidden" name="key" class="form-control" value="{{ $item->key }}">
    <input readonly type="hidden" name="produto_id[]" class="form-control" value="{{ $item->id }}">
    <td>
        <img src="{{ $item->img }}" class="pdv-item-img" alt="{{ $item->nome }}">
    </td>
    <td class="col-6">
        <input readonly type="text" name="produto_nome[]" class="pdv-item-name" value="{{ $item->nome }}">
    </td>
    <td class="datatable-cell">
        <div class="pdv-qty-group opacity-75">
            <button disabled id="" class="pdv-qty-btn pdv-qty-btn-minus" type="button">-</button>
            <input type="tel" readonly class="pdv-qty-input" name="quantidade[]" value="{{ number_format($quantidade, 3) }}">
            <button disabled class="pdv-qty-btn pdv-qty-btn-plus" type="button">+</button>
        </div>
    </td>
    <td>
        <input readonly type="tel" name="valor_unitario[]" class="pdv-item-value value-unit" value="{{ __moeda($item->valor_unitario) }}">
    </td>
    <td>
        <input readonly type="tel" name="subtotal_item[]" class="pdv-item-subtotal subtotal-item" value="{{ __moeda($subtotal) }}">
    </td>
    <td>
        <button type="button" class="pdv-btn-delete btn-delete-row">
            <i class="ri-delete-bin-line"></i>
        </button>
    </td>
</tr>

