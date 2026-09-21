@php
    $login = isset($_COOKIE['ckLogin']) ? base64_decode($_COOKIE['ckLogin']) : '';
    $pass = isset($_COOKIE['ckPass']) ? base64_decode($_COOKIE['ckPass']) : '';
    $remember = isset($_COOKIE['ckRemember']) ? $_COOKIE['ckRemember'] : '';
@endphp
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso ao Sistema — ERP Gestão Empresarial</title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #3b66f6;
            --primary-hover: #254edb;
            --primary-light: #eff4ff;
            --primary-glow: rgba(59, 102, 246, 0.28);
            --dark-bg: #0b1120;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-400: #94a3b8;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            width: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--dark-bg);
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ── LADO ESQUERDO: APRESENTAÇÃO ERP ── */
        .left-banner {
            flex: 1.15;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem 4.5rem;
            background-image: 
                linear-gradient(135deg, rgba(11, 17, 32, 0.94) 0%, rgba(15, 23, 42, 0.85) 50%, rgba(30, 41, 59, 0.65) 100%),
                url('/login-banner.jpg');
            background-size: cover;
            background-position: center;
            overflow: hidden;
            color: #ffffff;
        }

        .left-banner::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 102, 246, 0.22) 0%, rgba(59, 102, 246, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .banner-header {
            position: relative;
            z-index: 10;
        }

        .banner-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            background: rgba(59, 102, 246, 0.15);
            border: 1px solid rgba(59, 102, 246, 0.4);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #60a5fa;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            backdrop-filter: blur(8px);
        }

        .banner-content {
            position: relative;
            z-index: 10;
            max-width: 600px;
            margin: auto 0;
        }

        .banner-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 1.25rem;
        }

        .banner-title span {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .banner-description {
            font-size: 1.05rem;
            line-height: 1.65;
            color: #cbd5e1;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 102, 246, 0.4);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .icon-blue {
            background: rgba(59, 102, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 102, 246, 0.35);
        }

        .icon-cyan {
            background: rgba(6, 182, 212, 0.2);
            color: #22d3ee;
            border: 1px solid rgba(6, 182, 212, 0.35);
        }

        .feature-info h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.15rem;
        }

        .feature-info p {
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .banner-footer {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background-color: #3b82f6;
            border-radius: 50%;
            box-shadow: 0 0 10px #3b82f6;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* ── LADO DIREITO: FORMULÁRIO DE LOGIN ── */
        .right-form-panel {
            width: 520px;
            flex-shrink: 0;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 3.5rem 2.5rem;
            overflow-y: auto;
            position: relative;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        .brand-header {
            margin-bottom: 2rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #3b66f6 0%, #254edb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 6px 16px var(--primary-glow);
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
            line-height: 1.1;
        }

        .brand-name span {
            color: var(--primary);
        }

        .brand-sub {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            display: block;
        }

        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.4;
        }

        .demo-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
        }

        .demo-card p {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .demo-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .btn-demo {
            padding: 0.45rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-demo-primary {
            background: #3b66f6;
            color: #fff;
        }

        .btn-demo-primary:hover {
            background: #254edb;
        }

        .btn-demo-dark {
            background: #1e293b;
            color: #fff;
        }

        .btn-demo-dark:hover {
            background: #0f172a;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
        }

        .alert-error ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .alert-error li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 1rem 0 2.75rem;
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: #0f172a;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 102, 246, 0.12);
        }

        .form-input:focus ~ .input-icon {
            color: var(--primary);
        }

        .input-box .password-toggle {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .input-box .password-toggle:hover {
            color: #475569;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.25rem 0 1.75rem;
            font-size: 0.84rem;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        .remember-checkbox input[type="checkbox"] {
            width: 17px;
            height: 17px;
            border-radius: 4px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #3b66f6 0%, #254edb 100%);
            border: none;
            border-radius: 0.75rem;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 6px 18px rgba(59, 102, 246, 0.28);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4f75f8 0%, #1e44cc 100%);
            box-shadow: 0 8px 24px rgba(59, 102, 246, 0.38);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .whatsapp-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
            margin-top: 1rem;
        }

        .whatsapp-link:hover {
            color: #25d366;
        }

        .register-text {
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 1.5rem;
        }

        .register-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.25rem;
            transition: color 0.2s;
        }

        .register-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .form-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ── RESPONSIVIDADE ── */
        @media (max-width: 1024px) {
            .left-banner {
                display: none;
            }

            .right-form-panel {
                width: 100%;
                min-height: 100vh;
                padding: 2.5rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .right-form-panel {
                padding: 2rem 1.25rem;
            }

            .login-title {
                font-size: 1.45rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">

        <!-- ══ LADO ESQUERDO: BANNER ERP AZUL ══ -->
        <div class="left-banner">
            
            <div class="banner-header">
                <div class="banner-tag">
                    <i class="bi bi-buildings-fill"></i>
                    Sistema de Gestão Empresarial (ERP)
                </div>
            </div>

            <div class="banner-content">
                <h1 class="banner-title">
                    Controle Total da<br>
                    <span>Sua Empresa</span>
                </h1>
                
                <p class="banner-description">
                    Gerencie vendas, frente de caixa PDV, estoque, financeiro, emissão de NF-e/NFC-e e SPED Fiscal
                    em uma única plataforma inteligente, integrada e segura.
                </p>

                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon icon-blue">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                        <div class="feature-info">
                            <h4>Vendas & PDV</h4>
                            <p>Frente de caixa e faturamento</p>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon icon-cyan">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <div class="feature-info">
                            <h4>Fiscal & SPED</h4>
                            <p>NF-e, NFC-e, MDF-e e CT-e</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="banner-footer">
                <div class="live-dot"></div>
                <span>Servidor ERP Operando em Alta Performance</span>
            </div>

        </div>

        <!-- ══ LADO DIREITO: FORMULÁRIO DE ACESSO ══ -->
        <div class="right-form-panel">
            
            <div class="form-container">

                <div class="brand-header">
                    <a href="{{ route('login') }}" class="brand-badge">
                        <div class="brand-icon">
                            <i class="bi bi-buildings"></i>
                        </div>
                        <div>
                            <div class="brand-name">ERP<span>Gestão</span></div>
                            <span class="brand-sub">Sistema de Gestão Integrada</span>
                        </div>
                    </a>

                    <h2 class="login-title">Acesso ao Sistema</h2>
                    <p class="login-subtitle">Informe suas credenciais para gerenciar sua empresa.</p>
                </div>

                @if (env('APP_ENV') == 'demo')
                <div class="demo-card">
                    <p>Usuários de demonstração pré-configurados:</p>
                    <div class="demo-buttons">
                        <button type="button" class="btn-demo btn-demo-primary" onclick="demoLogin('slym@slym.com', '123456')">
                            SUPERADMIN
                        </button>
                        <button type="button" class="btn-demo btn-demo-dark" onclick="demoLogin('teste@teste.com', '123456')">
                            ADMINISTRADOR
                        </button>
                    </div>
                </div>
                @endif

                @if (Session::has('error'))
                    <div class="alert-error">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                        <span>{{ Session::get('error') }}</span>
                    </div>
                @endif

                @if (Session::has('success'))
                    <div class="alert-success">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        <span>{{ Session::get('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="form-login">
                    @csrf

                    <!-- Campo E-mail -->
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail de acesso</label>
                        <div class="input-box">
                            <i class="bi bi-envelope input-icon"></i>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-input" 
                                required 
                                autofocus
                                placeholder="seu.email@empresa.com.br" 
                                value="{{ old('email') ?: $login }}"
                            >
                        </div>
                    </div>

                    <!-- Campo Senha -->
                    <div class="form-group">
                        <label for="password" class="form-label">Senha de acesso</label>
                        <div class="input-box">
                            <i class="bi bi-lock input-icon"></i>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-input" 
                                required
                                placeholder="••••••••" 
                                value="{{ $pass }}"
                                style="padding-right: 2.75rem;"
                            >
                            <button type="button" class="password-toggle" id="toggle-password-btn" title="Mostrar/ocultar senha">
                                <i class="bi bi-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Ações: Lembrar e Esqueci Senha -->
                    <div class="form-actions">
                        <label class="remember-checkbox" for="remember">
                            <input type="checkbox" name="remember" id="remember" {{ $remember ? 'checked' : '' }}>
                            <span>Lembrar-me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Esqueci minha senha
                            </a>
                        @endif
                    </div>

                    <!-- Botão de Login -->
                    <button type="submit" class="btn-submit" id="btn-submit">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span id="btn-text">ENTRAR NO SISTEMA</span>
                    </button>

                    @if (env('APP_FONE'))
                        <div style="text-align: center;">
                            <a target="_blank" href="https://wa.me/55{{ preg_replace('/\D/', '', env('APP_FONE')) }}" class="whatsapp-link">
                                <i class="bi bi-whatsapp"></i> Suporte via WhatsApp
                            </a>
                        </div>
                    @endif

                </form>

                @if (Route::has('register'))
                    <p class="register-text">
                        Ainda não possui uma conta?
                        <a href="{{ route('register') }}" class="register-link">Criar conta</a>
                    </p>
                @endif

            </div>

            <!-- Rodapé do formulário -->
            <div class="form-footer">
                <span>&copy; {{ date('Y') }} ERP Gestão Empresarial.</span>
                <span>Ambiente Seguro</span>
            </div>

        </div>

    </div>

    <!-- Scripts de Interação -->
    <script>
        function demoLogin(email, senha) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = senha;
            document.getElementById('form-login').submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password-btn');
            const eyeIcon = document.getElementById('eye-icon');

            if (toggleButton && passwordInput && eyeIcon) {
                toggleButton.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            }
        });
    </script>
</body>

</html>
