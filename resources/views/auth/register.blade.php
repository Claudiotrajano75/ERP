<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Conta — ERP Gestão Empresarial</title>

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

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ── LADO ESQUERDO: BANNER ERP AZUL ── */
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

        /* ── LADO DIREITO: FORMULÁRIO ── */
        .right-form-panel {
            width: 520px;
            flex-shrink: 0;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem 2.5rem;
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
            margin-bottom: 1.25rem;
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

        .auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .auth-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.4;
            margin-bottom: 1.5rem;
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
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.45rem;
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
            height: 46px;
            padding: 0 1rem 0 2.75rem;
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
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

        .form-input.is-invalid {
            border-color: #ef4444;
            background-color: #fff5f5;
        }

        .invalid-feedback {
            font-size: 0.76rem;
            color: #ef4444;
            margin-top: 0.35rem;
            display: block;
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

        .btn-auth {
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
            margin-top: 0.5rem;
        }

        .btn-auth:hover {
            background: linear-gradient(135deg, #4f75f8 0%, #1e44cc 100%);
            box-shadow: 0 8px 24px rgba(59, 102, 246, 0.38);
            transform: translateY(-1px);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .auth-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .auth-footer-nav {
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 1.5rem;
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

            .auth-title {
                font-size: 1.45rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">

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

        <!-- ══ LADO DIREITO: FORMULÁRIO DE CADASTRO ══ -->
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

                    <h1 class="auth-title">Crie sua conta</h1>
                    <p class="auth-subtitle">Cadastre sua empresa e comece a gerenciar em minutos.</p>
                </div>

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

                <form method="POST" action="{{ route('register') }}" id="form-register">
                    @csrf

                    {{-- Nome Completo --}}
                    <div class="form-group">
                        <label for="name" class="form-label">Seu Nome Completo</label>
                        <div class="input-box">
                            <i class="bi bi-person input-icon"></i>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" 
                                placeholder="Ex: Carlos Oliveira" 
                                required 
                                autofocus
                            >
                        </div>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- E-mail --}}
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail corporativo</label>
                        <div class="input-box">
                            <i class="bi bi-envelope input-icon"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" 
                                placeholder="seu.email@empresa.com.br" 
                                required
                            >
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div class="form-group">
                        <label for="password" class="form-label">Senha de acesso</label>
                        <div class="input-box">
                            <i class="bi bi-lock input-icon"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input @error('password') is-invalid @enderror"
                                placeholder="Crie uma senha forte" 
                                required
                                style="padding-right: 2.75rem;"
                            >
                            <button type="button" class="password-toggle" id="toggle-password-btn" title="Mostrar/ocultar senha">
                                <i class="bi bi-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirmar Senha --}}
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <div class="input-box">
                            <i class="bi bi-shield-check input-icon"></i>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-input @error('password_confirmation') is-invalid @enderror"
                                placeholder="Repita a senha" 
                                required
                                style="padding-right: 2.75rem;"
                            >
                            <button type="button" class="password-toggle" id="toggle-password-confirm-btn" title="Mostrar/ocultar senha">
                                <i class="bi bi-eye" id="eye-icon-confirm"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-auth" id="btn-register">
                        <i class="bi bi-person-plus-fill"></i>
                        <span id="btn-text">CRIAR CONTA NO ERP</span>
                    </button>

                    <p class="auth-footer-nav">
                        Já tem uma conta cadastrada?
                        <a href="{{ route('login') }}" class="auth-link">Fazer login</a>
                    </p>

                </form>

            </div>

            <!-- Rodapé do formulário -->
            <div class="form-footer">
                <span>&copy; {{ date('Y') }} ERP Gestão Empresarial.</span>
                <span>Ambiente Seguro</span>
            </div>

        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupToggle(btnId, inputId, iconId) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (btn && input && icon) {
                    btn.addEventListener('click', function () {
                        const isPassword = input.getAttribute('type') === 'password';
                        input.setAttribute('type', isPassword ? 'text' : 'password');
                        if (isPassword) {
                            icon.classList.replace('bi-eye', 'bi-eye-slash');
                        } else {
                            icon.classList.replace('bi-eye-slash', 'bi-eye');
                        }
                    });
                }
            }

            setupToggle('toggle-password-btn', 'password', 'eye-icon');
            setupToggle('toggle-password-confirm-btn', 'password_confirmation', 'eye-icon-confirm');
        });
    </script>
</body>

</html>
