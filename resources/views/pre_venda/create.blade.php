@extends('front_box.default', ['title' => 'PRÉ VENDA'])
@section('content')

<div class="container-fluid px-0 py-2">
    {!! Form::open()
    ->post()
    ->route('pre-venda.store')
    ->attrs(['id' => 'form-pre-venda']) !!}

    <div class="d-flex align-items-center justify-content-between px-2 mb-2">
        <div>
            <h5 class="mb-0 fw-bold" style="color:#302b63;">
                <i class="ri-list-ordered me-2" style="color:#4facfe;"></i>
                Nova Pré-venda
            </h5>
            <small class="text-muted">Selecione os produtos e finalize a venda.</small>
        </div>
        <a href="{{ route('pre-venda.index') }}" class="btn btn-outline-danger btn-sm px-3 rounded-3">
            <i class="ri-arrow-left-line me-1"></i> Voltar
        </a>
    </div>

    @include('pre_venda._forms')
    {!! Form::close() !!}
</div>

@endsection
