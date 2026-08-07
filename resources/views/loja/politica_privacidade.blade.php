@extends('loja.default', ['title' => 'Política de Privacidade'])

@section('content')

{{-- Page Hero --}}
<section class="page-hero">
    <div class="page-hero-content container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('loja.index', ['link='.$config->loja_id]) }}">Home</a></li>
                <li class="breadcrumb-item active">Política de Privacidade</li>
            </ol>
        </nav>
        <h1>Política de Privacidade</h1>
        <p class="badge-count-info">Como tratamos e protegemos os seus dados.</p>
    </div>
</section>

{{-- Conteúdo --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="static-content">
                {!! $config->politica_privacidade !!}
            </div>
        </div>
    </div>
</div>

@endsection
