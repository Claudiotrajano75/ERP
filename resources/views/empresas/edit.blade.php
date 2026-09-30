@extends('layouts.app', ['title' => 'Editar Empresa'])

@section('content')
<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-building-line"></i>
                                Editar Cadastro da Empresa
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize os dados cadastrais, certificado digital e parâmetros de emissão da empresa.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('empresas.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO ═══ --}}
                <div class="card-body p-4">

                    @if($infoCertificado)
                    <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #eff6ff;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="ri-shield-keyhole-fill text-primary fs-18"></i>
                            <h6 class="text-primary fw-bold mb-0">Informações do Certificado Digital Ativo</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <span class="d-block text-muted fs-11 text-uppercase fw-semibold">Serial</span>
                                <strong class="text-dark fs-13">{{ $infoCertificado['serial'] }}</strong>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <span class="d-block text-muted fs-11 text-uppercase fw-semibold">Emissão</span>
                                <strong class="text-dark fs-13">{{ $infoCertificado['inicio'] }}</strong>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <span class="d-block text-muted fs-11 text-uppercase fw-semibold">Validade</span>
                                <strong class="text-dark fs-13">{{ $infoCertificado['expiracao'] }}</strong>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <span class="d-block text-muted fs-11 text-uppercase fw-semibold">ID / Tipo</span>
                                <strong class="text-dark fs-13">{{ $infoCertificado['id'] }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif

                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('empresas.update', [$item->id])
                    ->multipart()
                    !!}

                    @include('empresas._forms', ['edit' => true])

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
