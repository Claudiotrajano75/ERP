@extends('layouts.app', ['title' => 'Editar Empresa'])


@section('content')

<div class="card mt-1">
    <div class="card-header">
        <h3>Editar Cadastro da Empresa</h3>
        <div style="text-align: right;" class="">
            <a href="{{ route('empresas.index') }}" class="btn btn-danger btn-sm px-3">
                <i class="ri-arrow-left-double-fill"></i>Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($infoCertificado)
        <div class="col-12 mb-3">
            <div class="card border border-info shadow-sm">
                <div class="card-body bg-info-subtle rounded">
                    <h5 class="text-info fw-bold mb-3"><i class="ri-shield-keyhole-line"></i> Informações do Certificado Digital</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase;">Serial</span>
                            <strong class="text-dark">{{ $infoCertificado['serial'] }}</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase;">Emissão</span>
                            <strong class="text-dark">{{ $infoCertificado['inicio'] }}</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase;">Validade</span>
                            <strong class="text-dark">{{ $infoCertificado['expiracao'] }}</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase;">ID</span>
                            <strong class="text-dark">{{ $infoCertificado['id'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {!!Form::open()->fill($item)
        ->put()
        ->route('empresas.update', [$item->id])
        ->multipart()
        !!}
        <div class="pl-lg-4">
            @include('empresas._forms', ['edit' => true])
        </div>
        {!!Form::close()!!}
    </div>
</div>
@endsection
