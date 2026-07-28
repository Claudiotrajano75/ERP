@extends('layouts.app', ['title' => 'Perfil do Usuário'])
@section('content')

<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card border-0 shadow-sm text-dark">
                <!-- Cabeçalho -->
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-user-smile-line me-2 text-primary fs-22"></i>
                                Meu Perfil de Usuário
                            </h4>
                            <p class="text-muted mb-0 fs-13">Confira as informações da sua conta e vigência do plano contratado.</p>
                        </div>
                        <div>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Corpo do Perfil -->
                <div class="card-body p-4 text-center">
                    
                    <!-- Bloco Principal -->
                    <div class="mb-4">
                        <img src="{{ $item->img ?? '/imgs/no-image.png' }}" class="rounded-circle border bg-light shadow-sm p-1" style="width: 110px; height: 110px; object-fit: cover;">
                        <h4 class="mb-1 mt-3 fw-bold text-dark">{{ $item->name }}</h4>
                        
                        @if($item->empresa)
                        <span class="badge bg-light text-dark border px-2 py-1 fs-12">
                            <i class="ri-building-line text-muted me-1"></i>{{ $item->empresa->empresa->nome }}
                        </span>
                        @endif
                    </div>

                    <!-- Botão de Ação rápida -->
                    <a href="{{ route('usuarios.edit', $item->id) }}" class="btn btn-warning text-white btn-sm mb-4 px-4">
                        <i class="ri-pencil-line me-1"></i> Editar Perfil
                    </a>

                    <!-- Dados Cadastrais -->
                    <div class="bg-light border rounded p-3 text-start mb-2">
                        <h5 class="fs-12 text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2">Informações de Registro</h5>
                        <div class="row g-2 fs-14">
                            <div class="col-md-6 col-12">
                                <span class="text-muted d-block fs-11 text-uppercase">Nome de Acesso</span>
                                <strong class="text-dark">{{ $item->name }}</strong>
                            </div>
                            <div class="col-md-6 col-12">
                                <span class="text-muted d-block fs-11 text-uppercase">E-mail Cadastrado</span>
                                <strong class="text-dark">{{ $item->email }}</strong>
                            </div>
                            <div class="col-12 mt-2">
                                <span class="text-muted d-block fs-11 text-uppercase">Data de Criação da Conta</span>
                                <strong class="text-dark">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }} hs</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Vigência do Plano -->
                    @if($item->empresa && $item->empresa->empresa->plano)
                    <div class="bg-light border rounded p-3 text-start">
                        <h5 class="fs-12 text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2">Plano & Licenciamento</h5>
                        <div class="row g-2 fs-14">
                            <div class="col-md-6 col-12">
                                <span class="text-muted d-block fs-11 text-uppercase">Plano Ativo</span>
                                <span class="badge bg-success text-white px-3 py-1 mt-1 fs-11">
                                    {{ $item->empresa->empresa->plano->plano->nome }}
                                </span>
                            </div>
                            <div class="col-md-6 col-12">
                                <span class="text-muted d-block fs-11 text-uppercase">Data de Expiração</span>
                                <strong class="text-danger d-block mt-1">{{ __data_pt($item->empresa->empresa->plano->data_expiracao, 0) }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
