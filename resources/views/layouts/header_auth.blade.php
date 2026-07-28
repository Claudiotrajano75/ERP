<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title ?? 'ERP'}}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="/assets/js/config.js"></script>
    <link rel="shortcut icon" href="/logo-sm.png">

    <style>
        :root {
            --primary: #4254ba;
            --primary-dark: #3545a0;
            --primary-light: #6b7fda;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Nunito', 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            background: #fff;
        }

        /* ===== LADO ESQUERDO — ILUSTRAÇÃO ===== */
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, #4254ba 0%, #5b6fcf 40%, #7b8fdf 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before,
        .auth-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
        }

        .auth-left::before {
            width: 400px; height: 400px;
            bottom: -100px; left: -100px;
        }

        .auth-left::after {
            width: 300px; height: 300px;
            top: -80px; right: -50px;
        }

        .auth-illustration {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 420px;
        }

        .auth-illustration .illus-icon {
            font-size: 5rem;
            color: rgba(255,255,255,.9);
            margin-bottom: 1.5rem;
        }

        .auth-illustration h2 {
            color: #fff;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: .75rem;
        }

        .auth-illustration p {
            color: rgba(255,255,255,.82);
            font-size: .95rem;
            line-height: 1.6;
        }

        .auth-features {
            display: flex;
            flex-direction: column;
            gap: .5rem;
            margin-top: 1.5rem;
            text-align: left;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: rgba(255,255,255,.9);
            font-size: .85rem;
        }

        .auth-feature i {
            width: 28px; height: 28px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .8rem;
        }

        /* ===== LADO DIREITO — FORMULÁRIO ===== */
        .auth-right {
            width: 480px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2.5rem;
            background: #fff;
            overflow-y: auto;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .auth-logo img {
            max-width: 180px;
            height: auto;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: .35rem;
        }

        .auth-subtitle {
            font-size: .83rem;
            color: #6B7280;
            margin-bottom: 1.75rem;
        }

        /* ===== FORMULÁRIO ===== */
        .form-label {
            font-size: .82rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            border-radius: 8px;
            border: 1.5px solid #E5E7EB;
            padding: .6rem .85rem;
            font-size: .875rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(66,84,186,.18);
        }

        .form-control:hover {
            border-color: #9CA3AF;
        }

        /* Input com ícone */
        .input-icon {
            position: relative;
        }

        .input-icon .form-control {
            padding-left: 2.2rem;
        }

        .input-icon .input-icon-prepend {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 1rem;
            z-index: 5;
            pointer-events: none;
            transition: color .2s;
        }

        .input-icon:focus-within .input-icon-prepend {
            color: var(--primary);
        }

        /* Toggle senha */
        .input-group-text {
            background: #F9FAFB;
            border: 1.5px solid #E5E7EB;
            border-left: none;
            cursor: pointer;
            border-radius: 0 8px 8px 0;
            color: #6B7280;
            transition: color .2s;
        }

        .input-group-text:hover {
            color: var(--primary);
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: var(--primary);
        }

        /* Botão principal */
        .btn-auth {
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: .9rem;
            padding: .72rem;
            border-radius: 8px;
            width: 100%;
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-auth:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(66,84,186,.3);
        }

        .btn-auth:active {
            transform: scale(.99);
            box-shadow: 0 2px 6px rgba(66,84,186,.2);
        }

        .btn-auth:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-auth .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: .15em;
        }

        /* Links */
        .auth-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            font-size: .82rem;
        }

        .auth-link:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }

        /* Alertas */
        .invalid-feedback {
            font-size: .77rem;
        }

        .is-invalid {
            border-color: #EF4444 !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 .2rem rgba(239,68,68,.18) !important;
        }

        /* Botões demo */
        .btn-demo {
            transition: all .25s ease;
            font-size: .8rem;
        }

        .btn-demo:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,0,0,.15);
        }

        .btn-demo:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Flash messages */
        .auth-flash-container {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            max-width: 500px;
        }

        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255,255,255,.4);
            transform: scale(0);
            animation: ripple-anim .6s ease-out;
            pointer-events: none;
        }

        @keyframes ripple-anim {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Responsivo */
        @media (max-width: 900px) {
            .auth-left  { display: none; }
            .auth-right { width: 100%; }
        }

        @media (max-width: 480px) {
            .auth-right { padding: 1.5rem; }
            .auth-logo img { max-width: 140px; }
        }
    </style>

    @yield('css')

</head>
<body>

    <!-- Flash Messages -->
    <div class="auth-flash-container">
        @if(session()->has('flash_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sucesso!</strong> {{ session()->get('flash_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session()->has('flash_error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro!</strong> {{ session()->get('flash_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <!-- LADO ESQUERDO: Ilustração -->
    <div class="auth-left d-none d-md-flex">
        <div class="auth-illustration">
            <div class="illus-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <h2>Sistema de Gestão ERP</h2>
            <p>Gerencie sua empresa com eficiência: vendas, estoque, finanças e muito mais em um só lugar.</p>
            <div class="auth-features">
                <div class="auth-feature">
                    <i class="bi bi-check2"></i>
                    PDV e gestão de vendas integradas
                </div>
                <div class="auth-feature">
                    <i class="bi bi-check2"></i>
                    Emissão de NF-e / NFC-e / NFS-e
                </div>
                <div class="auth-feature">
                    <i class="bi bi-check2"></i>
                    SPED e gestão fiscal completa
                </div>
                <div class="auth-feature">
                    <i class="bi bi-check2"></i>
                    Relatórios e dashboards em tempo real
                </div>
            </div>
        </div>
    </div>

    <!-- LADO DIREITO: Formulário -->
    <div class="auth-right">
        <div class="auth-form-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="/assets/js/vendor.min.js"></script>
    <script src="/assets/js/app.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    @yield('js')

</body>
</html>
