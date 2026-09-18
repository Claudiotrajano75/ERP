@foreach($dias as $key => $d)
<tr>
	<input type="hidden" name="dia[]" value="{{$key}}">
	<td class="uf-field">
		{!!Form::text('', '')->attrs(['class' => 'form-control bg-light'])->readonly()
		->value($d)
        !!}
	</td>
	<td class="uf-field">
		{!!Form::text('inicio[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
        !!}
	</td>
	<td class="uf-field">
		{!!Form::text('fim[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
        !!}
	</td>
</tr>
@endforeach