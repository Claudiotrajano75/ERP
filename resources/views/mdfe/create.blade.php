@extends('layouts.app', ['title' => 'Nova MDF-e'])

@section('content')
<div class="mt-3">
    {!!Form::open()
    ->post()
    ->route('mdfe.store')
    ->multipart()
    ->id('form-mdfe')
    !!}

    @include('mdfe._forms')

    {!!Form::close()!!}
</div>
@endsection

@section('js')
@endsection
