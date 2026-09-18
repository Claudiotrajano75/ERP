@extends('layouts.header_auth', ['title' => 'Cadastre-se'])

@section('css')
    <style>
        .input-icon {
            position: relative;
        }

        .input-icon .form-control {
            padding-left: 2.5rem;
        }

        .input-icon-prepend {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 4;
            pointer-events: none;
        }

        .input-icon:focus-within .input-icon-prepend {
            color: var(--primary, #4254ba);
        }

        .toggle-password {
            cursor: pointer;
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }

        .toggle-password:hover {
            background: #f1f5f9;
            color: #334155;
        }
    </style>
@endsection

@section('content')
    <!-- Logo -->
    <div class="auth-logo">
        <img src="/logo.jpg" alt="Logo">
    </div>

    <h1 class="auth-title text-center">Cadastre-se</h1>
    <p class="auth-subtitle text-center">Crie sua conta e comece em menos de um minuto!</p>

    <form method="POST" action="{{ route('register') }}" id="form-register">
        @csrf

        {{-- Nome --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nome Completo</label>
            <div class="input-icon">
                <span class="input-icon-prepend"><i class="bi bi-person"></i></span>
                <input class="form-control @error('name') is-invalid @enderror"
                    type="text" id="name" placeholder="Digite seu nome completo" required name="name"
                    value="{{ old('name') }}" autofocus>
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- E-mail --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <div class="input-icon">
                <span class="input-icon-prepend"><i class="bi bi-envelope"></i></span>
                <input class="form-control @error('email') is-invalid @enderror"
                    type="email" id="email" placeholder="seuemail@exemplo.com" required name="email"
                    value="{{ old('email') }}" autocomplete="email">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Senha --}}
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <div class="input-group">
                <input class="form-control @error('password') is-invalid @enderror"
                    type="password" id="password" placeholder="Crie uma senha segura" required name="password"
                    autocomplete="new-password">
                <button type="button" class="input-group-text toggle-password" onclick="togglePassword('password', this)" tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirmar Senha --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
            <div class="input-group">
                <input class="form-control @error('password_confirmation') is-invalid @enderror"
                    type="password" id="password_confirmation" placeholder="Repita a senha" required
                    name="password_confirmation" autocomplete="new-password">
                <button type="button" class="input-group-text toggle-password" onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botão de Submissão --}}
        <div class="d-grid mb-3">
            <button class="btn btn-primary btn-auth" type="submit" id="btn-register">
                <span id="btn-text"><i class="bi bi-person-plus me-1"></i> Criar Conta</span>
                <span id="btn-loading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                    Criando conta...
                </span>
            </button>
        </div>

        {{-- Link para Login --}}
        <div class="text-center pt-2 border-top">
            <p class="text-muted mb-0" style="font-size: .85rem;">
                Já tem uma conta? <a href="{{ route('login') }}" class="auth-link fw-semibold">Fazer Login</a>
            </p>
        </div>

    </form>
@endsection

@section('js')
    <script type="text/javascript">
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

        $(document).ready(function() {
            $('#form-register').on('submit', function() {
                var btn = $('#btn-register');
                btn.prop('disabled', true);
                $('#btn-text').addClass('d-none');
                $('#btn-loading').removeClass('d-none');
            });
        });
    </script>
@endsection
