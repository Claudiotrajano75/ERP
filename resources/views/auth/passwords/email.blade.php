@extends('layouts.header_auth', ['title' => 'Redefinir Senha'])

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
    </style>
@endsection

@section('content')
    <!-- Logo -->
    <div class="auth-logo">
        <img src="/logo.jpg" alt="Logo">
    </div>

    <h1 class="auth-title text-center">Redefinir Senha</h1>
    <p class="auth-subtitle text-center">Digite seu e-mail para receber o link de recuperação de senha.</p>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: .85rem; border-radius: 10px;">
            <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="form-reset-email">
        @csrf

        {{-- E-mail --}}
        <div class="mb-4">
            <label for="email" class="form-label">E-mail Cadastrado</label>
            <div class="input-icon">
                <span class="input-icon-prepend"><i class="bi bi-envelope"></i></span>
                <input class="form-control @error('email') is-invalid @enderror"
                    type="email" id="email" placeholder="Digite seu e-mail" required name="email"
                    value="{{ old('email') }}" autofocus autocomplete="email">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botão Enviar Link --}}
        <div class="d-grid mb-3">
            <button class="btn btn-primary btn-auth" type="submit" id="btn-submit">
                <span id="btn-text"><i class="bi bi-send me-1"></i> Enviar Link de Redefinição</span>
                <span id="btn-loading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                    Enviando...
                </span>
            </button>
        </div>

        {{-- Voltar para Login --}}
        <div class="text-center pt-2 border-top">
            <p class="text-muted mb-0" style="font-size: .85rem;">
                Lembrou da senha? <a href="{{ route('login') }}" class="auth-link fw-semibold">Voltar para Login</a>
            </p>
        </div>

    </form>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#form-reset-email').on('submit', function() {
                var btn = $('#btn-submit');
                btn.prop('disabled', true);
                $('#btn-text').addClass('d-none');
                $('#btn-loading').removeClass('d-none');
            });
        });
    </script>
@endsection
