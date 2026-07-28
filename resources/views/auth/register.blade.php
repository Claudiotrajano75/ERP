@extends('layouts.header_auth', ['title' => 'Cadastre-se'])

@section('css')
    <style>
        .form-control,
        .form-control-sm {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:hover,
        .form-control-sm:hover {
            border-color: #adb5bd;
        }

        .form-control:focus,
        .form-control-sm:focus {
            border-color: #4254ba;
            box-shadow: 0 0 0 0.15rem rgba(66, 84, 186, 0.2);
        }

        .btn-submit {
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 84, 186, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(66, 84, 186, 0.25);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-submit .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple-anim 0.6s ease-out;
            pointer-events: none;
        }

        @keyframes ripple-anim {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .input-icon {
            position: relative;
        }

        .input-icon .form-control,
        .input-icon .form-control-sm {
            padding-left: 2.2rem;
        }

        .input-icon .input-icon-prepend {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 1rem;
            z-index: 5;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .input-icon:focus-within .input-icon-prepend {
            color: #4254ba;
        }

        .password-toggle {
            position: absolute;
            right: 0.4rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #adb5bd;
            font-size: 1rem;
            padding: 0.2rem;
            cursor: pointer;
            z-index: 5;
            line-height: 1;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #4254ba;
        }

        .input-icon .form-control-sm.password-with-toggle {
            padding-right: 2.2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center vh-100">
        <div class="row justify-content-center w-100 mx-0">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body px-4 py-2">

                        <!-- Logo -->
                        <div class="text-center">
                            <img src="/logo.jpg" alt="Logo" class="img-fluid" style="max-width: 200px;">
                        </div>

                        <h4 class="text-center mt-0 mb-0">Cadastre-se</h4>
                        <p class="text-muted text-center mb-2 small">Crie sua conta, leva menos de um minuto!</p>

                        <form method="POST" action="{{ route('register') }}" id="form-register">
                            @csrf

                            <div class="mb-2">
                                <label for="name" class="form-label small">Nome</label>
                                <div class="input-icon">
                                    <span class="input-icon-prepend"><i class="ri-user-line"></i></span>
                                    <input class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        type="text" id="name" placeholder="Nome completo" required name="name">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="email" class="form-label small">Email</label>
                                <div class="input-icon">
                                    <span class="input-icon-prepend"><i class="ri-mail-line"></i></span>
                                    <input class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        type="email" id="email" placeholder="Seu melhor email" required name="email"
                                        autocomplete="email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label small">Senha</label>
                                <div class="input-icon">
                                    <span class="input-icon-prepend"><i class="ri-lock-line"></i></span>
                                    <input
                                        class="form-control form-control-sm password-with-toggle @error('password') is-invalid @enderror"
                                        type="password" id="password" placeholder="Crie uma senha" required name="password"
                                        autocomplete="new-password">
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('password', this)" tabindex="-1">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password_confirmation" class="form-label small">Confirmar Senha</label>
                                <div class="input-icon">
                                    <span class="input-icon-prepend"><i class="ri-lock-line"></i></span>
                                    <input
                                        class="form-control form-control-sm password-with-toggle @error('password_confirmation') is-invalid @enderror"
                                        type="password" id="password_confirmation" placeholder="Repita a senha" required
                                        name="password_confirmation" autocomplete="new-password">
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid mt-2">
                                <button class="btn btn-primary btn-sm fw-semibold btn-submit" type="submit"
                                    id="btn-register">
                                    <span id="btn-text"><i class="ri-user-add-line me-1"></i> Cadastrar</span>
                                    <span id="btn-loading" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Cadastrando...
                                    </span>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="text-center mt-1">
                    <p class="text-muted mb-0">Já tem conta? <a href="{{ route('login') }}" class="text-blue"><b>Fazer
                                login</b></a></p>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        // Ripple effect
        $(document).on('click', '.btn-submit', function(e) {
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
                icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        }

        // Loading state on submit
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
