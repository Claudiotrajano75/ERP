@extends('layouts.app', ['title' => 'Editar MDF-e'])

@section('content')
<div class="mt-3">
    {!!Form::open()->fill($item)
    ->put()
    ->route('mdfe.update', [$item->id])
    ->multipart()
    ->id('form-mdfe')
    !!}

    @include('mdfe._forms')

    {!!Form::close()!!}
</div>
@endsection

@section('js')
@endsection
