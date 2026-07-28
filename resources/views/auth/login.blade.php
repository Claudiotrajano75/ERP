@extends('layouts.header_auth', ['title' => 'Login'])

@section('css')
    <style>
        /* Ajustes específicos da tela de login */
        .btn-auth i {
            font-size: 1rem;
        }

        .demo-card {
            background: #F9FAFB;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .demo-card p {
            font-size: .82rem;
            color: #6B7280;
            margin-bottom: .5rem;
        }

        .whatsapp-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: #6B7280;
            text-decoration: none;
            transition: color .2s;
        }

        .whatsapp-link:hover {
            color: #25D366;
        }
    </style>
@endsection

@section('content')
    @php
        $login = isset($_COOKIE['ckLogin']) ? base64_decode($_COOKIE['ckLogin']) : '';
        $pass = isset($_COOKIE['ckPass']) ? base64_decode($_COOKIE['ckPass']) : '';
        $remember = isset($_COOKIE['ckRemember']) ? $_COOKIE['ckRemember'] : '';
    @endphp

    @if (env('APP_ENV') == 'demo')
    <div class="demo-card">
        <p class="mb-2">Clique nos botões abaixo para acessar os usuários pré configurados!</p>
        <div class="row g-2">
            <div class="col-12 col-lg-6">
                <button class="btn btn-success w-100 btn-sm btn-demo"
                    onclick="demoLogin('slym@slym.com', '123456', this)">SUPERADMIN</button>
            </div>
            <div class="col-12 col-lg-6">
                <button class="btn btn-dark w-100 btn-sm btn-demo"
                    onclick="demoLogin('teste@teste.com', '123456', this)">ADMINISTRADOR</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Logo -->
    <div class="auth-logo">
        <img src="/logo.jpg" alt="Logo">
    </div>

    <h1 class="auth-title text-center">Bem-vindo de volta!</h1>
    <p class="auth-subtitle text-center">Faça login na sua conta para continuar.</p>

    <form method="POST" action="{{ route('login') }}" id="form-login">
        @csrf

        {{-- E-mail --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <div class="input-icon">
                <span class="input-icon-prepend"><i class="bi bi-envelope"></i></span>
                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="email" required
                    autocomplete="email" value="{{ old('email') ?: $login }}" placeholder="Digite seu email">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Senha --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Senha</label>
                <a href="{{ route('password.request') }}" class="auth-link">Esqueceu a senha?</a>
            </div>
            <div class="input-group">
                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password"
                    required autocomplete="current-password" value="{{ $pass }}" id="password"
                    placeholder="Digite sua senha">
                <button type="button" class="input-group-text toggle-password" onclick="togglePassword('password', this)" tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Lembrar + Erros --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check mb-0">
                <input name="remember" type="checkbox" {{ $remember ? 'checked' : '' }}
                    class="form-check-input" id="checkbox-signin">
                <label class="form-check-label" for="checkbox-signin" style="font-size:.82rem">Lembrar de mim</label>
            </div>
        </div>

        @if (Session::has('error'))
            <div class="alert alert-danger py-2 mb-3">{{ Session::get('error') }}</div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success py-2 mb-3">{{ Session::get('success') }}</div>
        @endif

        {{-- Botão --}}
        <button class="btn-auth" type="submit" id="btn-login">
            <span id="btn-text"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar</span>
            <span id="btn-loading" class="d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Entrando...
            </span>
        </button>

        {{-- WhatsApp --}}
        <div class="text-center mt-3">
            <a target="_blank" href="https://wa.me/5588997109630{{ env('APP_FONE') }}" class="whatsapp-link">
                <i class="bi bi-whatsapp"></i> Suporte via WhatsApp
            </a>
        </div>

    </form>

    <p class="text-center mt-3 mb-0" style="font-size:.83rem; color:#6B7280">
        Não tem uma conta?
        <a href="{{ route('register') }}" class="auth-link">Inscrever-se</a>
    </p>
@endsection

@section('js')
    <script type="text/javascript">
        function login(email, senha) {
            $('#email').val(email)
            $('#password').val(senha)
            $('#form-login').submit()
        }

        function demoLogin(email, senha, btn) {
            $(btn).prop('disabled', true);
            $(btn).text('Entrando...');
            login(email, senha);
        }

        // Ripple effect
        $(document).on('click', '.btn-auth, .btn-demo', function(e) {
            var btn = $(this);
            if (btn.prop('disabled')) return;

            var rect = btn[0].getBoundingClientRect();
            var size = Math.max(rect.width, rect.height);
            var x = e.clientX - rect.left - size / 2;
            var y = e.clientY - rect.top - size / 2;

            var ripple = $('<span class="ripple"></span>');
            ripple.css({
                width: size + 'px',
                height: size + 'px',
                left: x + 'px',
                top: y + 'px'
            });

            btn.append(ripple);
            setTimeout(function() {
                ripple.remove();
            }, 600);
        });

        // Password toggle
        function togglePassword(fieldId, btn) {
            var input = $('#' + fieldId);
            var icon = $(btn).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        }

        // Loading state on submit
        $(document).ready(function() {
            $('#form-login').on('submit', function() {
                var btn = $('#btn-login');
                btn.prop('disabled', true);
                $('#btn-text').addClass('d-none');
                $('#btn-loading').removeClass('d-none');
            });
        });
    </script>
@endsection
