@forelse($data as $item)
<tr>
	<td>
		<div class="fw-semibold text-dark">
			{{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome != "" ? $item->cliente_nome : "--") }}
		</div>
		<div class="text-muted fs-11">#{{ $item->id }}</div>
	</td>
	<td class="fw-bold text-success">{{ __moeda($item->total) }}</td>
	<td class="text-muted">
		<i class="ri-calendar-line me-1"></i>{{ __data_pt($item->created_at) }}
	</td>
	<td>
		<span class="pdv-pag-badge"><i class="ri-user-line"></i> {{ $item->user ? $item->user->name : '--' }}</span>
	</td>
	<td>
		<form action="{{ route('frontbox.destroy-suspensa', $item->id) }}" method="get" id="form-{{$item->id}}" class="d-flex align-items-center justify-content-center gap-1 mb-0">
			<a class="btn btn-sm btn-success" href="{{ route('frontbox.create', ['venda_suspensa='.$item->id]) }}">
				<i class="ri-play-circle-line me-1"></i>Finalizar
			</a>
			<button type="button" class="btn btn-sm btn-danger btn-delete" title="Remover">
				<i class="ri-delete-bin-line"></i>
			</button>
		</form>
	</td>
</tr>
@empty
<tr>
	<td colspan="5" class="pdv-empty-state">
		<i class="ri-inbox-archive-line"></i>
		Nenhuma venda suspensa encontrada.
	</td>
</tr>
@endforelse
