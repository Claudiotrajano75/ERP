<div style="margin-top: 16px">
	@foreach($data as $item)
	<label class="radio-luxe">
		<input class="radio-frete" type="radio" name="tipo_frete" value="{{ $item['tipo'] }}" data-valor="{{ (float)$item['valor'] }}" data-endereco-id="null">
		<span class="radio-name">{{ $item['tipo'] }}</span>
		@if((float)$item['valor'] == 0)
		<span class="radio-free">Grátis</span>
		@else
		<span class="radio-price">R$ {{ __moeda((float)$item['valor']) }}</span>
		@endif
	</label>
	@endforeach
	@if($config->habilitar_retirada)
	<label class="radio-luxe">
		<input class="radio-frete" type="radio" name="tipo_frete" value="0" data-valor="0">
		<span class="radio-name">Retirar na loja</span>
		<span class="radio-free">Grátis</span>
	</label>
	@endif

	@if($config->frete_gratis_valor > 0 && $config->frete_gratis_valor <= $total)
	<label class="radio-luxe">
		<input class="radio-frete" type="radio" name="tipo_frete" value="gratis" data-valor="0">
		<span class="radio-name">Frete grátis 🎉</span>
		<span class="radio-free">Grátis</span>
	</label>
	@endif
</div>
