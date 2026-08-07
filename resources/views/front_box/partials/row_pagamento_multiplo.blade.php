<tr>
    <td>
        <span class="pdv-pag-badge"><i class="ri-bank-card-line"></i> {{ $tipo }}</span>
        <input readonly type="hidden" name="tipo_pagamento_row[]" value="{{ $tipo_pagamento_row }}">
    </td>
	<td>
		<input readonly type="date" name="data_vencimento_row[]" class="form-control form-control-sm data_multiplo"
        value="{{ $data_vencimento_row }}">
	</td>
	<td>
		<input readonly type="text" name="valor_integral_row[]" class="form-control form-control-sm valor_integral text-end fw-bold"
        value="{{ $valor_integral_row }}">
	</td>
    <td>
		<input readonly type="text" name="obs_row[]" class="form-control form-control-sm"
        value="{{ $obs_row }}">
	</td>
	<td class="text-center">
		<button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Remover parcela">
			<i class="ri-delete-bin-line"></i>
		</button>
	</td>
</tr>
