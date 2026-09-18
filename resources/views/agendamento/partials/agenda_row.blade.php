@foreach($horarios as $i)
<tr>
	<td class="fw-semibold text-dark"><i class="ri-user-line me-1 text-muted"></i>{{ $i['funcionario_nome'] }}</td>
	<td><i class="ri-time-line me-1 text-muted"></i><span class="badge bg-light text-dark border">{{ $i['inicio'] }} - {{ $i['fim'] }}</span></td>
	<td class="fw-bold text-success fs-14">R$ {{ __moeda($i['total']) }}</td>
	<td class="text-end">
		<button onclick="escolheHorario('{{ json_encode($i) }}')" type="button" class="dash-btn dash-btn-primary btn-sm px-3 py-1">
			<i class="ri-check-line"></i> Selecionar
		</button>
	</td>
</tr>
@endforeach