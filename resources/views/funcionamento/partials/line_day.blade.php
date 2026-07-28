@foreach($dias as $key => $d)
<tr>
	<input type="hidden" name="dia[]" value="{{$key}}">
	<td>
		{!!Form::text('', '')->attrs(['class' => 'form-control'])->readonly()
		->value($d)
        !!}
	</td>
	<td>
		{!!Form::text('inicio[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
        !!}
	</td>
	<td>
		{!!Form::text('fim[]', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])->required()
        !!}
	</td>
</tr>
@endforeach