@extends('layouts.app', ['title' => 'Novo Pagamento'])
@section('content')

<div class="card mt-1">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="m-0"><i class="ri-cash-line text-primary"></i> Novo Pagamento</h4>
        <a href="{{ route('contadores.financeiro', [$contador->id]) }}" class="btn btn-danger btn-sm px-3">
            <i class="ri-arrow-left-double-fill"></i>Voltar
        </a>
    </div>
    <div class="card-body">
        {!!Form::open()
        ->post()
        ->route('contadores.financeiro-store', [$contador->id])
        !!}
        <div class="pl-lg-4">
            @include('contadores._forms_finaceiro')
        </div>
        {!!Form::close()!!}
    </div>
</div>
@endsection


