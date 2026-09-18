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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --primary: #4254ba;
            --primary-dark: #3545a0;
            --primary-light: #6b7fda;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;
            height: 100vh;
            display: flex;
            background: #fff;
            overflow: hidden;
        }

        /* ===== LADO ESQUERDO — BRANDING ===== */
        .auth-left {
            flex: 1;
            background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        /* Formas geométricas decorativas */
        .auth-left::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
            top: -120px; right: -100px;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,0.10) 0%, transparent 70%);
            bottom: -100px; left: -80px;
        }

        /* Imagem de fundo (banner customizado) — ocupa exatamente 100% do painel esquerdo */
        .auth-bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            z-index: 0;
        }
        .auth-bg-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(15,23,42,0.85) 0%, rgba(30,27,75,0.80) 50%, rgba(49,46,129,0.75) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .auth-illustration {
            position: relative;
            z-index: 2;
            max-width: 460px;
            text-align: left;
        }

        .auth-illustration h2,
        .auth-illustration p,
        .auth-feature {
            text-shadow: 0 2px 12px rgba(0,0,0,0.5);
        }

        .auth-illustration h2 {
            color: #fff;
            font-weight: 800;
            font-size: 2.2rem;
            line-height: 1.2;
            margin-bottom: .75rem;
            letter-spacing: -0.03em;
        }

        .auth-illustration p {
            color: rgba(255,255,255,.7);
            font-size: .95rem;
            line-height: 1.7;
            font-weight: 400;
        }

        .auth-features {
            display: flex;
            flex-direction: column;
            gap: .75rem;
            margin-top: 2rem;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255,255,255,.85);
            font-size: .88rem;
            font-weight: 500;
        }

        .auth-feature i {
            width: 32px; height: 32px;
            background: rgba(99,102,241,0.2);
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .85rem;
            color: #a5b4fc;
        }

        /* Tag decorativa */
        .auth-brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: .75rem;
            font-weight: 700;
            color: #a5b4fc;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        /* ===== LADO DIREITO — FORMULÁRIO ===== */
        .auth-right {
            width: 520px;
            min-width: 520px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 3rem;
            background: #fff;
            overflow-y: auto;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.75rem;
        }

        .auth-logo img {
            max-width: 160px;
            height: auto;
            /* Sem sombra */
        }

        .auth-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: .35rem;
            letter-spacing: -0.03em;
        }

        .auth-subtitle {
            font-size: .82rem;
            color: #94a3b8;
            margin-bottom: 1.75rem;
            font-weight: 500;
        }

        /* ===== FORMULÁRIO ===== */
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 4px !important;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: .65rem .9rem;
            font-size: .875rem;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            color: #1e293b;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(66,84,186,.12);
            background: #fff;
        }

        .form-control:hover {
            border-color: #cbd5e1;
        }

        /* Input com ícone */
        .input-icon {
            position: relative;
        }

        .input-icon .form-control {
            padding-left: 2.4rem;
        }

        .input-icon .input-icon-prepend {
            position: absolute;
            left: .85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
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
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            cursor: pointer;
            border-radius: 0 10px 10px 0;
            color: #94a3b8;
            transition: color .2s;
        }

        .input-group-text:hover {
            color: var(--primary);
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: var(--primary);
        }

        /* Botão principal */
        .btn-auth {
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: .9rem;
            padding: .75rem;
            border-radius: 12px;
            width: 100%;
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(66,84,186,.25);
        }

        .btn-auth:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(66,84,186,.35);
        }

        .btn-auth:active {
            transform: scale(.99);
            box-shadow: 0 2px 8px rgba(66,84,186,.2);
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
            box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important;
        }

        /* Botões demo */
        .btn-demo {
            transition: all .25s ease;
            font-size: .8rem;
            border-radius: 8px;
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

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.25rem 0;
            font-size: .75rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Responsivo */
        @media (max-width: 900px) {
            body { height: auto; min-height: 100vh; overflow-y: auto; }
            .auth-left { display: none; }
            .auth-right { width: 100%; padding: 2rem 1.5rem; }
        }

        @media (max-width: 480px) {
            .auth-right { padding: 1.5rem 1rem; }
            .auth-logo img { max-width: 130px; }
        }

        @media (max-width: 900px) {
            .auth-bg-img, .auth-bg-overlay { display: none; }
        }
    </style>

    @php
        $loginBanner = \App\Models\ConfiguracaoSuper::first();
        $loginBannerUrl = $loginBanner ? $loginBanner->login_banner_url : null;
    @endphp

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

    <!-- LADO ESQUERDO: Branding + Banner customizável -->
    <div class="auth-left d-none d-md-flex">
        <!-- ⬇️ Imagem do software (enviada pela Configuração > Identidade do ERP). -->
        <img src="{{ $loginBannerUrl ?: '/login-banner.jpg' }}" alt="Sistema de Gestão ERP" class="auth-bg-img"
            onerror="this.style.display='none';"
            loading="eager">
        <div class="auth-bg-overlay"></div>

        <div class="auth-illustration">
            <span class="auth-brand-tag">✦ Sistema de Gestão Empresarial</span>
            <h2>Controle total da<br>sua empresa</h2>
            <p>Gerencie vendas, estoque, finanças e fiscal em uma única plataforma inteligente e integrada.</p>
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
