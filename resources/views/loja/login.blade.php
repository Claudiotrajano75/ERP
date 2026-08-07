@extends('loja.default', ['title' => 'Acessar Conta'])

@section('content')

{{-- Barra de etapas (fluxo de compra) --}}
@if(isset($carrinho) && $carrinho != [])
@include('loja.partials.checkout_steps', ['checkoutStep' => 2])
@endif

<div class="section py-5 text-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 col-12">

                <div class="content-card luxe-form text-center">

                    <div class="text-center mb-4">
                        @if($config->logo_img)
                            <img src="{{ $config->logo_img }}" alt="{{ $config->nome }}" style="max-height:64px;object-fit:contain;margin:0 auto 18px;">
                        @else
                            <div class="logo-brand justify-content-center mb-3" style="font-size:26px">
                                <div class="logo-icon" style="width:52px;height:52px;font-size:20px">{{ strtoupper(substr($config->nome, 0, 1)) }}</div>
                            </div>
                        @endif
                        <h3 class="fw-bold mb-1" style="font-family:'Roboto',serif;color:var(--luxe-brown)">Acessar Conta</h3>
                        <p class="text-muted fs-13">Identifique-se para continuar suas compras.</p>
                    </div>

                    <form method="post" action="{{ route('loja.login-auth') }}" class="row g-3 text-start">
                        @csrf
                        <input type="hidden" name="link" value="{{ $config->loja_id }}">

                        <div class="col-12">
                            <label class="required">E-mail</label>
                            <input required class="form-control" type="email" name="email" placeholder="nome@exemplo.com">
                        </div>

                        <div class="col-12">
                            <label class="required">Senha</label>
                            <input required class="form-control" type="password" name="senha" placeholder="Sua senha">
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn-luxe w-100">
                                <i class="ri-login-box-line"></i>
                                Entrar
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3" style="border-top:1px solid var(--border-light)">
                        <span class="text-muted fs-13">
                            Ainda não tem cadastro?
                            <a class="fw-bold" style="color:var(--luxe-gold)" href="{{ route('loja.cadastro', ['link='.$config->loja_id])}}">
                                Quero me cadastrar
                            </a>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
