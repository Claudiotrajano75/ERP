<tr class="line-product" data-estoque-minimo="{{ $product->estoque_minimo }}" data-estoque-atual="{{ $product->estoque ? $product->estoque->quantidade : 0 }}" data-nome-produto="{{ $product->nome }}">
    <input readonly type="hidden" name="key" class="form-control" value="{{ $product->key }}">
    <input class="produto_row" readonly type="hidden" name="produto_id[]" class="form-control" value="{{ $product->id }}">
    <td>
        <img src="{{ $product->img }}" class="pdv-item-img" alt="{{ $product->nome }}">
        <input class="variacao_id" type="hidden" name="variacao_id[]" class="form-control" value="{{ $variacao_id }}">
    </td>
    <td>
        <input style="width: 100%" readonly type="text" name="produto_nome[]" class="pdv-item-name" value="{{ $product->nome }}@if($variacao != null) - {{ $variacao->descricao }} @endif">
    </td>
    <td class="datatable-cell">
        <div class="pdv-qty-group">
            <button class="pdv-qty-btn pdv-qty-btn-minus" id="btn-subtrai" type="button">-</button>
            <input type="tel" readonly class="pdv-qty-input qtd_row qtd" name="quantidade[]" value="{{ $qtd }}">
            <button class="pdv-qty-btn pdv-qty-btn-plus" id="btn-incrementa" type="button">+</button>
        </div>
    </td>
    <td>
        <input style="width: 100%" readonly type="tel" name="valor_unitario[]" class="pdv-item-value value-unit" value="{{ __moeda($value_unit) }}">
    </td>
    <td>
        <input style="width: 100%" readonly type="tel" name="subtotal_item[]" class="pdv-item-subtotal subtotal-item" value="{{ __moeda($sub_total) }}">
    </td>
    <td>
        <button type="button" class="pdv-btn-delete btn-delete-row">
            <i class="ri-delete-bin-line"></i>
        </button>
    </td>
</tr>
