@extends('layouts.header_auth', ['title' => 'Esqueci minha senha'])

@section('css')
    <style>
        .form-control {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:hover {
            border-color: #adb5bd;
        }

        .form-control:focus {
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

        .input-icon .form-control {
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
    </style>
@endsection

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center vh-100">
        <div class="row justify-content-center w-100 mx-0">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body px-4 py-3">

                        <!-- Logo -->
                        <div class="text-center mb-3">
                            <img src="/logo5.jpg" alt="Logo" class="img-fluid" style="max-width: 200px;">
                        </div>

                        <h4 class="text-center mt-0 mb-0">Redefinir senha</h4>
                        <p class="text-muted text-center mb-3">Digite seu email para receber o link de redefinição</p>

                        @if (Session::has('error'))
                            <div class="alert alert-danger py-1 mb-2">{{ Session::get('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('reset.pass') }}" id="form-email">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-icon">
                                    <span class="input-icon-prepend"><i class="ri-mail-line"></i></span>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="Digite seu email cadastrado">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary btn-submit" type="submit" id="btn-email">
                                    <span id="btn-text"><i class="ri-send me-1"></i> Redefinir senha</span>
                                    <span id="btn-loading" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Enviando...
                                    </span>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="text-center mt-2">
                    <p class="text-muted mb-0">
                        <a href="{{ route('login') }}" class="text-muted ms-1">
                            <i class="ri-arrow-go-back-fill"></i> Voltar para login
                        </a>
                    </p>
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

        // Loading state on submit
        $(document).ready(function() {
            $('#form-email').on('submit', function() {
                var btn = $('#btn-email');
                btn.prop('disabled', true);
                $('#btn-text').addClass('d-none');
                $('#btn-loading').removeClass('d-none');
            });
        });
    </script>
@endsection
