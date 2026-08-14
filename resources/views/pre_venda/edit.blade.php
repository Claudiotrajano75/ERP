@extends('front_box.default', ['title' => 'EDITAR PRÉ VENDA'])
@section('content')

    <div class="container-fluid px-0 py-2">
        {!! Form::open()
        ->fill($item)
        ->put()
        ->route('pre-venda.update', [$item->id])
        ->id('form-pre-venda') !!}

        {{-- Banner: deixa claro que a tela é de edição de PRÉ-VENDA --}}
        <div style="background: linear-gradient(135deg, #302b63 0%, #24243e 100%); color:#fff; border-radius:12px; padding:10px 16px; margin-bottom:12px; display:flex; align-items:center; gap:10px; flex-wrap:wrap; box-shadow:0 2px 10px rgba(0,0,0,0.15);">
            <span style="background:rgba(255,255,255,0.15); padding:6px 10px; border-radius:8px; font-size:16px; line-height:1;">
                <i class="ri-pencil-line"></i>
            </span>
            <div style="flex:1; min-width:200px;">
                <strong style="font-size:14px; letter-spacing:-0.2px;">Editando PRÉ-VENDA #{{ $item->codigo }}</strong>
                <div style="font-size:12px; color:rgba(255,255,255,0.65);">Esta pré-venda ainda não foi convertida em venda. Todas as alterações são registradas em auditoria.</div>
            </div>
            <a href="{{ route('pre-venda.index') }}" class="btn btn-light btn-sm" style="border-radius:8px; font-weight:600; color:#302b63;">
                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
            </a>
        </div>

        @include('pre_venda._forms')
        {!! Form::close() !!}
    </div>

@endsection
