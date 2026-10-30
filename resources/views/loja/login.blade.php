@extends('loja.default', ['title' => 'Acessar Conta'])

@section('content')

@if(isset($carrinho) && $carrinho != [])
    @include('loja.partials.checkout_steps', ['checkoutStep' => 2])
@endif

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-100 p-8 sm:p-10 shadow-xl space-y-6">
        
        <!-- Topo / Logo -->
        <div class="text-center space-y-2">
            @if(!empty($config->logo_img) && $config->logo_img !== '/imgs/no-image.png')
                <img src="{{ $config->logo_img }}" alt="{{ $config->nome }}" class="h-12 w-auto mx-auto object-contain">
            @else
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 font-extrabold text-2xl flex items-center justify-center mx-auto shadow-sm">
                    {{ strtoupper(substr($config->nome, 0, 1)) }}
                </div>
            @endif
            <h2 class="text-2xl font-black text-slate-900 tracking-tight pt-2">Acesse sua Conta</h2>
            <p class="text-xs text-slate-500">Informe seus dados para continuar suas compras com segurança.</p>
        </div>

        <!-- Formulário de Login -->
        <form method="POST" action="{{ route('loja.login-auth') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="link" value="{{ $config->loja_id }}">

            <div>
                <label for="login-email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    E-mail
                </label>
                <div class="relative">
                    <input type="email"
                           name="email"
                           id="login-email"
                           required
                           autocomplete="email"
                           placeholder="seu.email@exemplo.com"
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    <i class="ri-mail-line absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                </div>
            </div>

            <div>
                <label for="login-senha" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Senha
                </label>
                <div class="relative">
                    <input type="password"
                           name="senha"
                           id="login-senha"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    <i class="ri-lock-line absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/25 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="ri-login-box-line text-lg"></i>
                <span>Entrar na Minha Conta</span>
            </button>
        </form>

        <!-- Link para Cadastro -->
        <div class="pt-4 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                Ainda não possui conta?
                <a href="{{ route('loja.cadastro', ['link' => $config->loja_id]) }}" class="font-extrabold text-indigo-600 hover:text-indigo-700 hover:underline ml-1">
                    Cadastre-se grátis
                </a>
            </p>
        </div>
    </div>
</div>

@endsection
