@php
    /* ═══════════════════════════════════════════════════════════════════════════
       CONFIGURAÇÕES RÁPIDAS EDITÁVEIS DA LANDING PAGE
       Altere aqui os dados institucionais, links e informações de contato
       ═══════════════════════════════════════════════════════════════════════════ */
    $brandName     = !empty($config->name) ? $config->name : 'Code Sistemas ERP';
    $brandTagline  = 'Gestão Empresarial Inteligente';
    $logoUrl       = $config ? $config->logo_url : '/logo.jpg';
    $contactEmail  = $config->email ?? 'trajano75@hotmail.com';
    $contactPhone  = $config->telefone ?? '(88) 99710-9630';
    $whatsappNum   = preg_replace('/[^0-9]/', '', $contactPhone) ?: '5588997109630';
    $whatsappLink  = 'https://wa.me/' . (strlen($whatsappNum) <= 11 ? '55' . $whatsappNum : $whatsappNum) . '?text=' . urlencode('Olá! Gostaria de saber mais sobre o sistema ERP.');
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandName }} — Sistema de Gestão Empresarial Completo (ERP)</title>
    <meta name="description" content="Gerencie vendas, financeiro, estoque, emissão de notas fiscais (NF-e, NFC-e, NFS-e), CRM e relatórios em tempo real em um único sistema ERP inteligente.">

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- RemixIcon (Padrão do ERP) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════════════════════
           DESIGN TOKENS — PADRÃO ERP-LAYOUT-MODERNIZATION
           ═══════════════════════════════════════════════════════════════════════ */
        :root {
            --skin-primary: #4f46e5;
            --skin-primary-hover: #4338ca;
            --skin-primary-soft: #eef0ff;
            --skin-bg: #f3f5fa;
            --skin-surface: #ffffff;
            --skin-border: #e9ecf3;
            --skin-text: #1f2937;
            --skin-muted: #64748b;
            --skin-soft: #94a3b8;
            --accent-green: #109f61;
            --accent-green-soft: #dcfce7;
            --accent-amber: #d97706;
            --radius-card: 16px;
            --radius-btn: 10px;
            --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 10px 30px rgba(79, 70, 229, 0.08);
            --shadow-lg: 0 20px 50px rgba(15, 23, 42, 0.09);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--skin-surface);
            color: var(--skin-text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4 {
            font-family: 'Outfit', 'Inter', sans-serif;
            color: var(--skin-text);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section {
            padding: 84px 0;
        }

        .section-bg {
            background-color: var(--skin-bg);
            border-top: 1px solid var(--skin-border);
            border-bottom: 1px solid var(--skin-border);
        }

        .section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 52px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: var(--skin-primary-soft);
            color: var(--skin-primary);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: clamp(28px, 3.6vw, 38px);
            font-weight: 800;
            margin-bottom: 14px;
        }

        .section-subtitle {
            font-size: 16px;
            color: var(--skin-muted);
        }

        /* ─── Botões Padrão ERP ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: var(--radius-btn);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--skin-primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            background: var(--skin-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.32);
        }

        .btn-green {
            background: var(--accent-green);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(16, 159, 97, 0.22);
        }

        .btn-green:hover {
            background: #0d8a53;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: var(--skin-surface);
            color: var(--skin-text);
            border-color: var(--skin-border);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline:hover {
            border-color: var(--skin-primary);
            color: var(--skin-primary);
            background: var(--skin-primary-soft);
        }

        .btn-lg {
            padding: 14px 28px;
            font-size: 15px;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           1. NAVBAR FIXA
           ═══════════════════════════════════════════════════════════════════════ */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--skin-border);
            transition: box-shadow 0.2s ease;
        }

        .navbar.scrolled {
            box-shadow: var(--shadow-sm);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 74px;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 19px;
            color: var(--skin-text);
        }

        .brand img {
            height: 46px;
            max-width: 180px;
            width: auto;
            border-radius: 6px;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: var(--skin-muted);
            transition: color 0.15s ease;
        }

        .nav-links a:hover {
            color: var(--skin-primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mobile-toggle {
            display: none;
            background: var(--skin-bg);
            border: 1px solid var(--skin-border);
            border-radius: 8px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--skin-text);
            cursor: pointer;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           2. HERO SECTION
           ═══════════════════════════════════════════════════════════════════════ */
        .hero {
            padding: 64px 0 56px;
            background: linear-gradient(180deg, #f3f5fa 0%, #ffffff 100%);
            border-bottom: 1px solid var(--skin-border);
            overflow: hidden;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 48px;
            align-items: center;
        }

        .hero-title {
            font-size: clamp(32px, 4.2vw, 48px);
            font-weight: 800;
            margin-bottom: 18px;
            color: var(--skin-text);
        }

        .hero-title span {
            color: var(--skin-primary);
        }

        .hero-desc {
            font-size: 17px;
            color: var(--skin-muted);
            margin-bottom: 28px;
            max-width: 540px;
        }

        .hero-ctas {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 32px;
        }

        .hero-metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--skin-border);
        }

        .hero-metric-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-metric-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--skin-primary-soft);
            color: var(--skin-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .hero-metric-text strong {
            display: block;
            font-size: 15px;
            color: var(--skin-text);
        }

        .hero-metric-text span {
            font-size: 12.5px;
            color: var(--skin-muted);
        }

        /* Mockup Dashboard ERP Realista */
        .mockup-wrap {
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .mockup-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #f8f9fc;
            border-bottom: 1px solid var(--skin-border);
        }

        .mockup-dots {
            display: flex;
            gap: 6px;
        }

        .mockup-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .mockup-dots span:nth-child(1) { background: #fb7185; }
        .mockup-dots span:nth-child(2) { background: #fbbf24; }
        .mockup-dots span:nth-child(3) { background: #24c98a; }

        .mockup-body {
            padding: 18px;
            background: var(--skin-bg);
        }

        .mockup-kpis {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 14px;
        }

        .mk-card {
            background: #fff;
            border: 1px solid var(--skin-border);
            border-radius: 12px;
            padding: 12px;
        }

        .mk-card small {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--skin-muted);
        }

        .mk-card strong {
            display: block;
            font-size: 17px;
            font-family: 'Outfit', sans-serif;
            margin-top: 4px;
        }

        .mk-tag {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            margin-top: 4px;
        }

        .mk-tag-green { background: var(--accent-green-soft); color: var(--accent-green); }
        .mk-tag-indigo { background: var(--skin-primary-soft); color: var(--skin-primary); }

        .mockup-chart-box {
            background: #fff;
            border: 1px solid var(--skin-border);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .mockup-chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 82px;
            padding-top: 10px;
        }

        .chart-bar {
            flex: 1;
            border-radius: 6px 6px 2px 2px;
            background: var(--skin-primary-soft);
            position: relative;
        }

        .chart-bar.active {
            background: linear-gradient(180deg, #6366f1, #4f46e5);
        }

        .mockup-list {
            background: #fff;
            border: 1px solid var(--skin-border);
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mk-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f1f5f9;
        }

        .mk-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .sectors-strip {
            margin-top: 48px;
            padding-top: 26px;
            border-top: 1px solid var(--skin-border);
            text-align: center;
        }

        .sectors-strip p {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--skin-soft);
            margin-bottom: 16px;
        }

        .sectors-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 14px 32px;
        }

        .sector-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--skin-muted);
        }

        .sector-item i {
            color: var(--skin-primary);
            font-size: 18px;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           CARDS PADRÃO ERP (MODULOS, DEPOIMENTOS, RECURSOS, CLIENTES)
           ═══════════════════════════════════════════════════════════════════════ */
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .erp-card {
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: var(--radius-card);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .erp-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: #d7dcf8;
        }

        .card-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--skin-primary-soft);
            color: var(--skin-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .card-icon-green {
            background: var(--accent-green-soft);
            color: var(--accent-green);
        }

        .erp-card h3 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .erp-card p {
            font-size: 14px;
            color: var(--skin-muted);
            margin-bottom: 16px;
            flex-grow: 1;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--skin-primary);
            cursor: pointer;
            background: none;
            border: 0;
            padding: 0;
        }

        .card-link:hover {
            gap: 8px;
        }

        /* Depoimentos */
        .stars {
            color: #f59e0b;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--skin-border);
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .testimonial-author strong {
            display: block;
            font-size: 14px;
        }

        .testimonial-author span {
            font-size: 12.5px;
            color: var(--skin-muted);
        }

        /* ═══════════════════════════════════════════════════════════════════════
           6. COMO FUNCIONA (TIMELINE NUMERADA)
           ═══════════════════════════════════════════════════════════════════════ */
        .step-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--skin-primary);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 15px;
            margin-bottom: 14px;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           7. PLANOS E PREÇOS
           ═══════════════════════════════════════════════════════════════════════ */
        .billing-toggle {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            padding: 6px;
            border-radius: 12px;
            margin-top: 18px;
        }

        .toggle-btn {
            padding: 8px 18px;
            border-radius: 8px;
            border: 0;
            background: transparent;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--skin-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toggle-btn.active {
            background: var(--skin-primary);
            color: #fff;
        }

        .discount-pill {
            background: var(--accent-green-soft);
            color: var(--accent-green);
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 999px;
        }

        .plan-card {
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: var(--radius-card);
            padding: 30px 26px;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .plan-card.popular {
            border: 2px solid var(--skin-primary);
            box-shadow: var(--shadow-md);
        }

        .popular-badge {
            position: absolute;
            top: -13px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--skin-primary);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 14px;
            border-radius: 999px;
        }

        .plan-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .plan-desc {
            font-size: 13.5px;
            color: var(--skin-muted);
            margin-bottom: 20px;
            min-height: 40px;
        }

        .plan-price {
            display: flex;
            align-items: baseline;
            gap: 4px;
            margin-bottom: 22px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--skin-border);
        }

        .plan-price .currency {
            font-size: 16px;
            font-weight: 700;
            color: var(--skin-muted);
        }

        .plan-price .amount {
            font-size: 36px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            color: var(--skin-text);
        }

        .plan-price .period {
            font-size: 13.5px;
            color: var(--skin-muted);
        }

        .plan-features {
            list-style: none;
            margin-bottom: 26px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .plan-features li {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13.5px;
            color: var(--skin-text);
        }

        .plan-features li i {
            color: var(--accent-green);
            font-size: 16px;
            flex-shrink: 0;
        }

        .enterprise-banner {
            margin-top: 28px;
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: var(--radius-card);
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            box-shadow: var(--shadow-sm);
        }

        /* ═══════════════════════════════════════════════════════════════════════
           9. ÁREA DE ACESSO AO SISTEMA
           ═══════════════════════════════════════════════════════════════════════ */
        .access-box {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 55%, #6366f1 100%);
            border-radius: 20px;
            padding: 48px 40px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 28px;
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.25);
        }

        .access-box h2 {
            color: #ffffff;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .access-box p {
            color: rgba(255, 255, 255, 0.88);
            font-size: 15.5px;
            margin-bottom: 18px;
        }

        .security-seals {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .seal-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.92);
            background: rgba(255, 255, 255, 0.14);
            padding: 6px 12px;
            border-radius: 8px;
        }

        .access-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .btn-white {
            background: #ffffff;
            color: var(--skin-primary);
            font-weight: 700;
            padding: 15px 32px;
            font-size: 15.5px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
        }

        .btn-white:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .forgot-link {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.88);
            text-decoration: underline;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           10. SOBRE A PLATAFORMA
           ═══════════════════════════════════════════════════════════════════════ */
        .about-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 44px;
            align-items: center;
        }

        .diff-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 22px;
        }

        .diff-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14.5px;
            font-weight: 500;
        }

        .diff-item i {
            color: var(--accent-green);
            font-size: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .stat-box {
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: var(--radius-card);
            padding: 24px 20px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .stat-box strong {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--skin-primary);
            margin-bottom: 4px;
        }

        .stat-box span {
            font-size: 13px;
            color: var(--skin-muted);
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           11. FAQ (ACORDEÃO)
           ═══════════════════════════════════════════════════════════════════════ */
        .faq-list {
            max-width: 820px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .faq-item {
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: 14px;
            overflow: hidden;
        }

        .faq-question {
            width: 100%;
            background: none;
            border: 0;
            padding: 18px 22px;
            text-align: left;
            font-size: 15.5px;
            font-weight: 600;
            color: var(--skin-text);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            cursor: pointer;
        }

        .faq-question i {
            color: var(--skin-primary);
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        .faq-item.open .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            display: none;
            padding: 0 22px 18px;
            font-size: 14px;
            color: var(--skin-muted);
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }

        .faq-item.open .faq-answer {
            display: block;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           12. CTA FINAL + FORMULÁRIO DE CONTATO
           ═══════════════════════════════════════════════════════════════════════ */
        .contact-wrap {
            background: var(--skin-surface);
            border: 1px solid var(--skin-border);
            border-radius: 20px;
            padding: 42px;
            box-shadow: var(--shadow-md);
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 40px;
            align-items: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--skin-muted);
        }

        .form-control {
            height: 44px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid #dcdce9;
            background: #fcfdfe;
            font-family: inherit;
            font-size: 14px;
            color: var(--skin-text);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        textarea.form-control {
            height: 92px;
            padding: 12px 14px;
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--skin-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
            background: #ffffff;
        }

        .form-feedback {
            display: none;
            margin-top: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
        }

        .form-feedback.success {
            display: block;
            background: var(--accent-green-soft);
            color: #15803d;
        }

        .form-feedback.error {
            display: block;
            background: #fee2e2;
            color: #b91c1c;
        }

        /* ═══════════════════════════════════════════════════════════════════════
           13. RODAPÉ
           ═══════════════════════════════════════════════════════════════════════ */
        .footer {
            background: #f8f9fc;
            border-top: 1px solid var(--skin-border);
            padding: 56px 0 28px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1.2fr;
            gap: 32px;
            margin-bottom: 40px;
        }

        .footer h4 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 14px;
        }

        .footer ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .footer ul a {
            font-size: 13.5px;
            color: var(--skin-muted);
        }

        .footer ul a:hover {
            color: var(--skin-primary);
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid var(--skin-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--skin-muted);
            font-size: 17px;
            transition: all 0.2s ease;
        }

        .social-links a:hover {
            border-color: var(--skin-primary);
            color: var(--skin-primary);
        }

        .footer-bottom {
            border-top: 1px solid var(--skin-border);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 13px;
            color: var(--skin-muted);
        }

        /* Modal Detalhes do Módulo */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: 18px;
            max-width: 500px;
            width: 100%;
            padding: 28px;
            box-shadow: var(--shadow-lg);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 18px;
            right: 18px;
            background: var(--skin-bg);
            border: 0;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            color: var(--skin-muted);
        }

        /* ═══════════════════════════════════════════════════════════════════════
           RESPONSIVIDADE (TABLET & MOBILE)
           ═══════════════════════════════════════════════════════════════════════ */
        @media (max-width: 992px) {
            .hero-grid, .about-grid, .contact-wrap {
                grid-template-columns: 1fr;
            }
            .grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .nav-links, .nav-actions .btn-outline {
                display: none;
            }
            .mobile-toggle {
                display: inline-flex;
            }
            .nav-links.mobile-open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 74px;
                left: 0;
                right: 0;
                background: #fff;
                padding: 20px 24px;
                border-bottom: 1px solid var(--skin-border);
                box-shadow: var(--shadow-md);
                align-items: flex-start;
                gap: 14px;
            }
        }

        @media (max-width: 640px) {
            .grid-4, .grid-3, .grid-2, .form-grid, .mockup-kpis {
                grid-template-columns: 1fr;
            }
            .section {
                padding: 58px 0;
            }
            .contact-wrap, .access-box {
                padding: 26px 20px;
            }
            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- ═══ 1. BARRA DE NAVEGAÇÃO FIXA ═══ -->
    <nav class="navbar" id="navbar">
        <div class="container nav-inner">
            <a href="#inicio" class="brand">
                <img src="{{ $logoUrl }}" alt="Logo {{ $brandName }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline';">
                <span @if($config && $config->logo) style="display: none;" @endif>{{ $brandName }}</span>
            </a>

            <ul class="nav-links" id="nav-links">
                <li><a href="#inicio">Início</a></li>
                <li><a href="#sistemas">Sistemas</a></li>
                <li><a href="#planos">Planos</a></li>
                <li><a href="#clientes">Clientes</a></li>
                <li><a href="#recursos">Recursos</a></li>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>

            <div class="nav-actions">
                <a href="#contato" class="btn btn-outline">Solicitar Demonstração</a>
                @if(Auth::check())
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="ri-dashboard-line"></i> Ir para o Painel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="ri-login-box-line"></i> Entrar no Sistema
                    </a>
                @endif
                <button class="mobile-toggle" id="mobile-toggle" aria-label="Abrir Menu">
                    <i class="ri-menu-line"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- ═══ 2. HERO SECTION ═══ -->
    <section class="hero" id="inicio">
        <div class="container">
            <div class="hero-grid">
                <div>
                    <span class="section-badge">
                        <i class="ri-sparkling-line"></i> Plataforma ERP em Nuvem
                    </span>
                    <h1 class="hero-title">
                        Gerencie toda a sua empresa em um único <span>Sistema ERP Inteligente</span>
                    </h1>
                    <p class="hero-desc">
                        Centralize suas Vendas, Pré-vendas, PDV, Financeiro, Estoque, Emissão Fiscal (NF-e, NFC-e, NFS-e, CT-e, MDF-e), CRM e Relatórios Gerenciais em Tempo Real, com Agilidade e Controle Total do seu Negócio.
                    </p>

                    <div class="hero-ctas">
                        @if(Auth::check())
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                <i class="ri-dashboard-3-line"></i> Acessar Meu Sistema
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="ri-login-circle-line"></i> Entrar no Sistema
                            </a>
                        @endif
                        <a href="#contato" class="btn btn-outline btn-lg">
                            <i class="ri-calendar-check-line"></i> Solicitar Demonstração
                        </a>
                    </div>

                    <div class="hero-metrics">
                        <div class="hero-metric-item">
                            <div class="hero-metric-icon"><i class="ri-building-2-line"></i></div>
                            <div class="hero-metric-text">
                                <strong>+300 empresas</strong>
                                <span>atendidas em operação</span>
                            </div>
                        </div>
                        <div class="hero-metric-item">
                            <div class="hero-metric-icon"><i class="ri-emotion-happy-line"></i></div>
                            <div class="hero-metric-text">
                                <strong>98% de satisfação</strong>
                                <span>suporte humano dedicado</span>
                            </div>
                        </div>
                        <div class="hero-metric-item">
                            <div class="hero-metric-icon"><i class="ri-shield-check-line"></i></div>
                            <div class="hero-metric-text">
                                <strong>Gestão completa</strong>
                                <span>em um só lugar</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mockup Realista do Dashboard ERP -->
                <div class="mockup-wrap" aria-label="Prévia do painel do sistema ERP">
                    <div class="mockup-topbar">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <span style="font-size: 11.5px; font-weight: 600; color: var(--skin-muted);">
                            <i class="ri-lock-line"></i> Painel Gerencial — {{ $brandName }}
                        </span>
                        <span style="font-size: 11px; color: var(--accent-green); font-weight: 700;">● Online</span>
                    </div>
                    <div class="mockup-body">
                        <div class="mockup-kpis">
                            <div class="mk-card">
                                <small>Faturamento Mês</small>
                                <strong>R$ 148.920</strong>
                                <span class="mk-tag mk-tag-green">+18,4%</span>
                            </div>
                            <div class="mk-card">
                                <small>Vendas / PDV</small>
                                <strong>1.284</strong>
                                <span class="mk-tag mk-tag-indigo">Hoje: 64</span>
                            </div>
                            <div class="mk-card">
                                <small>Itens em Estoque</small>
                                <strong>4.510</strong>
                                <span class="mk-tag mk-tag-green">100% Sincronizado</span>
                            </div>
                        </div>

                        <div class="mockup-chart-box">
                            <div class="mockup-chart-header">
                                <span>Fluxo de Caixa &amp; Vendas (Últimos 7 dias)</span>
                                <span style="color: var(--skin-primary);">Receitas vs Despesas</span>
                            </div>
                            <div class="chart-bars">
                                <div class="chart-bar" style="height: 45%;"></div>
                                <div class="chart-bar" style="height: 62%;"></div>
                                <div class="chart-bar active" style="height: 78%;"></div>
                                <div class="chart-bar" style="height: 55%;"></div>
                                <div class="chart-bar active" style="height: 90%;"></div>
                                <div class="chart-bar" style="height: 68%;"></div>
                                <div class="chart-bar active" style="height: 98%;"></div>
                            </div>
                        </div>

                        <div class="mockup-list">
                            <div class="mk-row">
                                <span><i class="ri-file-text-line" style="color: var(--skin-primary);"></i> NF-e #4582 — Autorizada SEFAZ</span>
                                <strong style="color: var(--accent-green);">R$ 3.450,00</strong>
                            </div>
                            <div class="mk-row">
                                <span><i class="ri-archive-line" style="color: var(--skin-primary);"></i> Entrada XML Compra #7104</span>
                                <strong>+120 un. estoque</strong>
                            </div>
                            <div class="mk-row">
                                <span><i class="ri-wallet-3-line" style="color: var(--accent-green);"></i> Recebimento PIX PDV</span>
                                <strong style="color: var(--accent-green);">Confirmado</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faixa de Setores Atendidos -->
            <div class="sectors-strip">
                <p>Solução validada para diversos segmentos de mercado</p>
                <div class="sectors-list">
                    <span class="sector-item"><i class="ri-store-2-line"></i> Varejo &amp; Lojas</span>
                    <span class="sector-item"><i class="ri-truck-line"></i> Distribuidoras</span>
                    <span class="sector-item"><i class="ri-restaurant-line"></i> Bares &amp; Restaurantes</span>
                    <span class="sector-item"><i class="ri-tools-line"></i> Prestadores de Serviços</span>
                    <span class="sector-item"><i class="ri-shopping-bag-3-line"></i> Atacado &amp; Comércio</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 3. PROVA SOCIAL (DEPOIMENTOS) ═══ -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-chat-quote-line"></i> Prova Social</span>
                <h2 class="section-title">Empresas que transformaram sua gestão</h2>
                <p class="section-subtitle">Veja como nossos clientes economizam tempo e eliminam erros operacionais todos os dias.</p>
            </div>

            <div class="grid-3">
                <div class="erp-card">
                    <div class="stars">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                    <p>“Reduzimos o tempo de fechamento mensal em 40%. Antes usávamos planilhas soltas; hoje todas as notas de compra e vendas caem direto no financeiro.”</p>
                    <div class="testimonial-author">
                        <div class="avatar-circle">RM</div>
                        <div>
                            <strong>Junior Tavares</strong>
                            <span>Diretor — Comercial Brasil Ltda</span>
                        </div>
                    </div>
                </div>

                <div class="erp-card">
                    <div class="stars">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                    <p>“Conseguimos organizar estoque, vendas e financeiro em uma única plataforma. A importação de XML de compras economiza horas da nossa equipe.”</p>
                    <div class="testimonial-author">
                        <div class="avatar-circle" style="background: linear-gradient(135deg, #24c98a, #109f61);">CS</div>
                        <div>
                            <strong>Camila Souza</strong>
                            <span>Gerente — Distribuidora Norte</span>
                        </div>
                    </div>
                </div>

                <div class="erp-card">
                    <div class="stars">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                    <p>“O PDV é muito rápido e a emissão de NFC-e e NFS-e funciona sem dor de cabeça. Quando precisamos de ajuda, o suporte resolve na hora.”</p>
                    <div class="testimonial-author">
                        <div class="avatar-circle" style="background: linear-gradient(135deg, #4d94ff, #1d4ed8);">FA</div>
                        <div>
                            <strong>Kevin Souza</strong>
                            <span>Sócio — Villa Rius Food</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 4. SISTEMAS E MÓDULOS DO ERP ═══ -->
    <section class="section section-bg" id="sistemas">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-apps-2-line"></i> Módulos Integrados</span>
                <h2 class="section-title">Um ecossistema completo para gestão do seu negócio</h2>
                <p class="section-subtitle">Todos os setores da sua empresa conversando entre si em tempo real, sem retrabalho.</p>
            </div>

            <div class="grid-4">
                <div class="erp-card">
                    <div class="card-icon"><i class="ri-shopping-cart-2-line"></i></div>
                    <h3>Gestão de Vendas</h3>
                    <p>PDV ágil, pedidos, orçamentos, comissões e histórico completo por cliente ou vendedor.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Gestão de Vendas & PDV', 'Frente de caixa rápido com leitor de código de barras, emissão de NFC-e/SAT, orçamentos convertidos em vendas com 1 clique e cálculo automático de comissões.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon card-icon-green"><i class="ri-money-dollar-circle-line"></i></div>
                    <h3>Financeiro e Caixa</h3>
                    <p>Contas a pagar e receber, fluxo de caixa diário, conciliação bancária e DRE gerencial.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Financeiro e Fluxo de Caixa', 'Controle total de receitas e despesas, emissão de boletos e PIX integrado, alertas de vencimento, plano de contas e fechamento de caixa por operador.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon"><i class="ri-archive-line"></i></div>
                    <h3>Estoque e Compras</h3>
                    <p>Importação automática de XML de fornecedores, estoque mínimo, inventário e multi-filiais.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Estoque e Compras', 'Dê entrada em notas fiscais pelo arquivo XML em segundos: cadastra produtos, atualiza custos, converte unidades de medida e lança no contas a pagar automaticamente.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon"><i class="ri-user-heart-line"></i></div>
                    <h3>Clientes e CRM</h3>
                    <p>Cadastro completo com consulta de CNPJ/CEP, limite de crédito e histórico de compras.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Cadastro de Clientes e CRM', 'Mantenha o relacionamento ativo com seus clientes, acompanhe títulos em aberto, limite de crédito e histórico detalhado de produtos adquiridos e serviços prestados.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon card-icon-green"><i class="ri-file-shield-2-line"></i></div>
                    <h3>Emissão de Notas Fiscais</h3>
                    <p>NF-e, NFC-e, NFS-e Nacional, CT-e e MDF-e integrados direto com a SEFAZ e prefeituras.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Emissão Fiscal Completa', 'Emita NF-e de produtos, NFC-e de consumidor e NFS-e Padrão Nacional de serviços com envio automático de XML/PDF por e-mail e armazenamento seguro.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon"><i class="ri-bar-chart-grouped-line"></i></div>
                    <h3>Relatórios e Dashboards</h3>
                    <p>Gráficos em tempo real, curva ABC de produtos, lucratividade e relatórios em PDF.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Relatórios e Dashboards', 'Tome decisões baseadas em dados reais: relatórios de estoque, vendas por período, margem de lucro, comissões e exportação instantânea em PDF.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon"><i class="ri-team-line"></i></div>
                    <h3>Gestão de Equipes</h3>
                    <p>Controle granular de usuários, cargos, permissões de acesso e registro de auditoria.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Gestão de Equipes e Permissões', 'Defina exatamente o que cada colaborador pode visualizar ou editar no sistema, protegendo informações financeiras sensíveis e registrando logs de ações.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                <div class="erp-card">
                    <div class="card-icon card-icon-green"><i class="ri-links-line"></i></div>
                    <h3>Automações e API</h3>
                    <p>Integração com e-commerce, delivery, WhatsApp, PIX e envio automático para contadores.</p>
                    <button type="button" class="card-link" onclick="openModuleModal('Automações e Integrações', 'Portal exclusivo para seu contador baixar todos os XMLs do mês, além de integrações com loja virtual, cardápio digital, balanças e pagamentos PIX.')">
                        Saiba mais <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 5. RECURSOS EM DESTAQUE ═══ -->
    <section class="section" id="recursos">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-flashlight-line"></i> Benefícios Reais</span>
                <h2 class="section-title">Por que escolher nossa plataforma</h2>
                <p class="section-subtitle">Projetada para dar velocidade ao operacional e clareza estratégica para o gestor.</p>
            </div>

            <div class="grid-3">
                <div class="erp-card">
                    <div class="card-icon"><i class="ri-layout-grid-line"></i></div>
                    <h3>Centralize todas as operações</h3>
                    <p>Chega de usar um sistema para emitir nota, outro para o caixa e planilhas para o estoque. Tudo funciona integrado em um só painel.</p>
                </div>
                <div class="erp-card">
                    <div class="card-icon card-icon-green"><i class="ri-line-chart-line"></i></div>
                    <h3>Relatórios em tempo real</h3>
                    <p>Acompanhe vendas do dia, saldo de estoque e contas a pagar ou receber atualizados no exato segundo em que acontecem.</p>
                </div>
                <div class="erp-card">
                    <div class="card-icon"><i class="ri-shield-check-line"></i></div>
                    <h3>Reduza erros e retrabalho</h3>
                    <p>Importe XMLs de compras, calcule impostos automaticamente e evite digitação manual repetida em diferentes setores.</p>
                </div>
                <div class="erp-card">
                    <div class="card-icon"><i class="ri-stack-line"></i></div>
                    <h3>Controle estoque, vendas e caixa</h3>
                    <p>Cada venda no balcão ou faturamento baixa o estoque e alimenta o financeiro instantaneamente, sem furos de caixa.</p>
                </div>
                <div class="erp-card">
                    <div class="card-icon card-icon-green"><i class="ri-cloud-line"></i></div>
                    <h3>Acesse de qualquer lugar</h3>
                    <p>Sistema 100% web em nuvem com criptografia SSL. Gerencie sua empresa do escritório, de casa ou pelo celular com segurança.</p>
                </div>
                <div class="erp-card">
                    <div class="card-icon"><i class="ri-robot-line"></i></div>
                    <h3>Automatize processos repetitivos</h3>
                    <p>Envio automático de notas para o cliente, geração de duplicatas na compra e fechamento fiscal simplificado para o contador.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 6. COMO FUNCIONA ═══ -->
    <section class="section section-bg">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-route-line"></i> Implantação Rápida</span>
                <h2 class="section-title">Comece a operar em 4 passos simples</h2>
                <p class="section-subtitle">Sem instalações complicadas ou semanas esperando implantação.</p>
            </div>

            <div class="grid-4">
                <div class="erp-card">
                    <span class="step-num">01</span>
                    <h3>Solicite sua conta</h3>
                    <p>Escolha o plano ideal ou solicite uma demonstração guiada com nossa equipe.</p>
                </div>
                <div class="erp-card">
                    <span class="step-num">02</span>
                    <h3>Configure os módulos</h3>
                    <p>Ativamos sua empresa, certificado digital A1 e os módulos necessários para o seu ramo.</p>
                </div>
                <div class="erp-card">
                    <span class="step-num">03</span>
                    <h3>Importe seus dados</h3>
                    <p>Traga seus produtos, clientes e notas fiscais via XML ou planilhas de forma simples.</p>
                </div>
                <div class="erp-card">
                    <span class="step-num" style="background: var(--accent-green);">04</span>
                    <h3>Acompanhe tudo</h3>
                    <p>Opere o caixa, emita notas em segundos e acompanhe os resultados no dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 7. PLANOS E PREÇOS ═══ -->
    <section class="section" id="planos">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-price-tag-3-line"></i> Planos Transparentes</span>
                <h2 class="section-title">Escolha o plano ideal para o seu negócio</h2>
                <p class="section-subtitle">Planos flexíveis que acompanham o crescimento da sua empresa, sem surpresas.</p>

                <div class="billing-toggle">
                    <button type="button" class="toggle-btn active" data-billing="monthly">Cobrança Mensal</button>
                    <button type="button" class="toggle-btn" data-billing="annual">
                        Cobrança Anual <span class="discount-pill">15% OFF</span>
                    </button>
                </div>
            </div>

            <div class="grid-3">
                @if(isset($planos) && $planos->count() > 0)
                    @foreach($planos as $idx => $plano)
                        @php
                            $isPopular = ($planos->count() >= 2 && $idx == 1) || ($planos->count() == 1 && $idx == 0);
                            $valorMensal = (float)$plano->valor;
                            $valorAnualMensal = $valorMensal * 0.85;
                        @endphp
                        <div class="plan-card {{ $isPopular ? 'popular' : '' }}">
                            @if($isPopular)
                                <span class="popular-badge">Mais Popular</span>
                            @endif
                            <div class="plan-name">{{ $plano->nome }}</div>
                            <div class="plan-desc">{{ $plano->descricao ?: 'Solução completa para gerenciar sua operação com agilidade e controle.' }}</div>

                            <div class="plan-price">
                                <span class="currency">R$</span>
                                <span class="amount js-plan-price"
                                      data-monthly="{{ number_format($valorMensal, 2, ',', '.') }}"
                                      data-annual="{{ number_format($valorAnualMensal, 2, ',', '.') }}">
                                    {{ number_format($valorMensal, 2, ',', '.') }}
                                </span>
                                <span class="period js-plan-period">/mês</span>
                            </div>

                            <ul class="plan-features">
                                <li><i class="ri-checkbox-circle-fill"></i> Até {{ $plano->maximo_usuarios ?: 3 }} usuário(s) simultâneos</li>
                                <li><i class="ri-checkbox-circle-fill"></i> Até {{ $plano->maximo_locais ?: 1 }} filial(is) / localização</li>
                                <li><i class="ri-checkbox-circle-fill"></i> {{ $plano->maximo_nfes > 0 ? $plano->maximo_nfes . ' NF-e/mês' : 'Emissão de NF-e integrada' }}</li>
                                <li><i class="ri-checkbox-circle-fill"></i> {{ $plano->maximo_nfces > 0 ? $plano->maximo_nfces . ' NFC-e/mês' : 'PDV e NFC-e rápida' }}</li>
                                <li><i class="ri-checkbox-circle-fill"></i> Financeiro, Estoque e Relatórios</li>
                                <li><i class="ri-checkbox-circle-fill"></i> Suporte técnico especializado</li>
                            </ul>

                            <a href="#contato" class="btn {{ $isPopular ? 'btn-primary' : 'btn-outline' }}" style="width: 100%;"
                               onclick="selectPlanInterest('{{ $plano->nome }}')">
                                {{ $valorMensal > 0 ? 'Assinar agora' : 'Falar com especialista' }}
                            </a>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback caso não existam planos visíveis no banco -->
                    <div class="plan-card">
                        <div class="plan-name">Plano Essencial</div>
                        <div class="plan-desc">Ideal para microempresas e comércios que estão começando a se organizar.</div>
                        <div class="plan-price">
                            <span class="currency">R$</span>
                            <span class="amount js-plan-price" data-monthly="89,90" data-annual="76,40">89,90</span>
                            <span class="period">/mês</span>
                        </div>
                        <ul class="plan-features">
                            <li><i class="ri-checkbox-circle-fill"></i> 1 Empresa / 2 Usuários</li>
                            <li><i class="ri-checkbox-circle-fill"></i> PDV Frente de Caixa e Vendas</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Controle de Estoque e Compras</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Emissão de NF-e e NFC-e</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Contas a Pagar e Receber</li>
                        </ul>
                        <a href="#contato" class="btn btn-outline" style="width: 100%;" onclick="selectPlanInterest('Plano Essencial')">Assinar agora</a>
                    </div>

                    <div class="plan-card popular">
                        <span class="popular-badge">Mais Popular</span>
                        <div class="plan-name">Plano Profissional</div>
                        <div class="plan-desc">Para empresas em crescimento que precisam de gestão completa e automação.</div>
                        <div class="plan-price">
                            <span class="currency">R$</span>
                            <span class="amount js-plan-price" data-monthly="149,90" data-annual="127,40">149,90</span>
                            <span class="period">/mês</span>
                        </div>
                        <ul class="plan-features">
                            <li><i class="ri-checkbox-circle-fill"></i> Até 5 Usuários / 2 Filiais</li>
                            <li><i class="ri-checkbox-circle-fill"></i> NF-e, NFC-e e NFS-e Nacional</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Importação XML de Compras</li>
                            <li><i class="ri-checkbox-circle-fill"></i> CRM, Ordens de Serviço e DRE</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Suporte Prioritário via WhatsApp</li>
                        </ul>
                        <a href="#contato" class="btn btn-primary" style="width: 100%;" onclick="selectPlanInterest('Plano Profissional')">Assinar agora</a>
                    </div>

                    <div class="plan-card">
                        <div class="plan-name">Plano Enterprise</div>
                        <div class="plan-desc">Estrutura robusta para redes de lojas, distribuidoras e alto volume fiscal.</div>
                        <div class="plan-price">
                            <span class="currency">R$</span>
                            <span class="amount js-plan-price" data-monthly="269,90" data-annual="229,40">269,90</span>
                            <span class="period">/mês</span>
                        </div>
                        <ul class="plan-features">
                            <li><i class="ri-checkbox-circle-fill"></i> Usuários e Filiais avançados</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Documentos fiscais ilimitados</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Integração E-commerce e API</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Portal exclusivo do Contador</li>
                            <li><i class="ri-checkbox-circle-fill"></i> Gerente de implantação dedicado</li>
                        </ul>
                        <a href="#contato" class="btn btn-outline" style="width: 100%;" onclick="selectPlanInterest('Plano Enterprise')">Falar com especialista</a>
                    </div>
                @endif
            </div>

            <!-- Plano Personalizado -->
            <div class="enterprise-banner">
                <div>
                    <h3 style="font-size: 18px; margin-bottom: 4px;">Precisa de um Plano Personalizado para sua operação?</h3>
                    <p style="font-size: 14px; color: var(--skin-muted); margin: 0;">Montamos projetos sob medida com múltiplas filiais, integrações dedicadas e treinamento presencial ou remoto.</p>
                </div>
                <a href="#contato" class="btn btn-green" onclick="selectPlanInterest('Plano Personalizado')">
                    <i class="ri-customer-service-2-line"></i> Falar com Especialista
                </a>
            </div>
        </div>
    </section>

    <!-- ═══ 8. CLIENTES E CASOS DE SUCESSO ═══ -->
    <section class="section section-bg" id="clientes">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-award-line"></i> Casos de Sucesso</span>
                <h2 class="section-title">Resultados concretos em diferentes segmentos</h2>
                <p class="section-subtitle">Veja como a plataforma resolve gargalos reais no dia a dia de empresas como a sua.</p>
            </div>

            <div class="grid-3">
                <div class="erp-card">
                    <div class="card-icon"><i class="ri-store-3-line"></i></div>
                    <span style="font-size: 11.5px; font-weight: 700; color: var(--skin-primary); text-transform: uppercase; margin-bottom: 6px;">Varejo &amp; Armarinho</span>
                    <h3>Comercial Brasil Ltda</h3>
                    <p>Organização completa de estoque com milhares de SKUs, conversão de unidades na entrada de XML e PDV integrado ao PIX.</p>
                    <span class="mk-tag mk-tag-green">+35% agilidade no caixa</span>
                </div>

                <div class="erp-card">
                    <div class="card-icon card-icon-green"><i class="ri-truck-line"></i></div>
                    <span style="font-size: 11.5px; font-weight: 700; color: var(--accent-green); text-transform: uppercase; margin-bottom: 6px;">Distribuidora</span>
                    <h3>Dissobel Distribuidora</h3>
                    <p>Controle financeiro rigoroso, gestão de contas a receber, boletos automatizados e relatórios gerenciais de estoque em tempo real.</p>
                    <span class="mk-tag mk-tag-indigo">Zero divergência de estoque</span>
                </div>

                <div class="erp-card">
                    <div class="card-icon"><i class="ri-service-line"></i></div>
                    <span style="font-size: 11.5px; font-weight: 700; color: var(--skin-primary); text-transform: uppercase; margin-bottom: 6px;">Prestação de Serviços</span>
                    <h3>TechService Manutenção</h3>
                    <p>Gestão de clientes, ordens de serviço padronizadas e emissão direta de NFS-e Padrão Nacional em poucos cliques.</p>
                    <span class="mk-tag mk-tag-green">Faturamento 3x mais rápido</span>
                </div>
            </div>

            <div style="text-align: center; margin-top: 34px;">
                <a href="#contato" class="btn btn-outline">Ver mais casos e solicitar apresentação</a>
            </div>
        </div>
    </section>

    <!-- ═══ 9. ÁREA DE ACESSO AO SISTEMA ═══ -->
    <section class="section">
        <div class="container">
            <div class="access-box">
                <div>
                    <h2>Já é cliente? Acesse seu sistema</h2>
                    <p>Entre no painel administrativo da sua empresa para gerenciar vendas, emitir notas e acompanhar seus relatórios.</p>
                    <div class="security-seals">
                        <span class="seal-item"><i class="ri-lock-2-line"></i> Conexão Segura SSL</span>
                        <span class="seal-item"><i class="ri-database-2-line"></i> Dados Criptografados</span>
                        <span class="seal-item"><i class="ri-customer-service-line"></i> Suporte Dedicado</span>
                    </div>
                </div>
                <div class="access-actions">
                    @if(Auth::check())
                        <a href="{{ route('home') }}" class="btn btn-white">
                            <i class="ri-dashboard-line"></i> Ir para o Sistema
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-white">
                            <i class="ri-login-box-line"></i> Entrar no Sistema
                        </a>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu ou precisa recuperar sua senha?</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 10. SOBRE A PLATAFORMA ═══ -->
    <section class="section section-bg" id="sobre">
        <div class="container">
            <div class="about-grid">
                <div>
                    <span class="section-badge"><i class="ri-compass-3-line"></i> Sobre a Plataforma</span>
                    <h2 class="section-title">Tecnologia pensada para simplificar a gestão empresarial</h2>
                    <p style="font-size: 15.5px; color: var(--skin-muted); margin-bottom: 16px;">
                        Nossa missão é entregar aos empreendedores brasileiros uma ferramenta robusta, rápida e intuitiva — que resolva as exigências fiscais do país sem burocratizar o dia a dia da equipe.
                    </p>
                    <div class="diff-list">
                        <div class="diff-item"><i class="ri-check-double-line"></i> Tecnologia web moderna com atualizações contínuas</div>
                        <div class="diff-item"><i class="ri-check-double-line"></i> Suporte humano próximo e resolutivo</div>
                        <div class="diff-item"><i class="ri-check-double-line"></i> Flexibilidade de módulos conforme seu crescimento</div>
                        <div class="diff-item"><i class="ri-check-double-line"></i> Backups automáticos e alta disponibilidade em nuvem</div>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-box">
                        <strong>+300</strong>
                        <span>Empresas atendidas</span>
                    </div>
                    <div class="stat-box">
                        <strong>+15</strong>
                        <span>Módulos integrados</span>
                    </div>
                    <div class="stat-box">
                        <strong>99,9%</strong>
                        <span>Uptime da plataforma</span>
                    </div>
                    <div class="stat-box">
                        <strong>+8 anos</strong>
                        <span>Evolução contínua</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 11. FAQ (PERGUNTAS FREQUENTES) ═══ -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge"><i class="ri-question-answer-line"></i> Dúvidas Frequentes</span>
                <h2 class="section-title">Perguntas Frequentes</h2>
                <p class="section-subtitle">Respostas diretas sobre implantação, planos, segurança e suporte.</p>
            </div>

            <div class="faq-list">
                <div class="faq-item open">
                    <button type="button" class="faq-question">
                        O sistema é adequado para pequenas empresas e MEIs?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        Sim! A plataforma foi desenhada para ser simples desde o primeiro acesso. Pequenas empresas conseguem controlar estoque, caixa e emitir notas fiscais em poucos minutos, sem precisar de equipe de TI.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        Posso personalizar os módulos conforme minha empresa cresce?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        Com certeza. Você pode começar com os módulos essenciais (vendas, estoque e financeiro) e habilitar recursos avançados — como NFS-e, múltiplas filiais, CRM ou integrações — a qualquer momento.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        Existe integração com outras ferramentas e contador?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        Sim. O ERP oferece acesso exclusivo para seu contador baixar XMLs e relatórios fiscais, além de suporte para importação de XML de compras, pagamentos PIX, balanças e loja online.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        Como funciona o suporte técnico?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        Nosso atendimento é feito por especialistas humanos via WhatsApp, chamados e acesso remoto quando necessário, ajudando desde a configuração do certificado digital até dúvidas operacionais.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        Meus dados e notas fiscais estão seguros?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        Sim. Toda a comunicação ocorre via protocolo criptografado (HTTPS/SSL) com backups automáticos diários em nuvem e controle estrito de permissões por usuário.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        Posso solicitar uma demonstração antes de contratar?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        Sim! Basta preencher o formulário abaixo ou chamar nossa equipe no WhatsApp. Apresentamos todas as telas na prática para validar se atende 100% do seu negócio.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ 12. CTA FINAL + FORMULÁRIO DE CONTATO ═══ -->
    <section class="section section-bg" id="contato">
        <div class="container">
            <div class="contact-wrap">
                <div>
                    <span class="section-badge"><i class="ri-rocket-2-line"></i> Dê o Próximo Passo</span>
                    <h2 class="section-title" style="font-size: 32px;">Pronto para transformar a gestão da sua empresa?</h2>
                    <p style="font-size: 15px; color: var(--skin-muted); margin-bottom: 24px;">
                        Preencha seus dados para agendar uma demonstração gratuita ou fale diretamente com um consultor especialista pelo WhatsApp.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 14px;">
                            <i class="ri-phone-line" style="color: var(--skin-primary); font-size: 18px;"></i>
                            <span>{{ $contactPhone }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 14px;">
                            <i class="ri-mail-line" style="color: var(--skin-primary); font-size: 18px;"></i>
                            <span>{{ $contactEmail }}</span>
                        </div>
                    </div>

                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="btn btn-green btn-lg">
                        <i class="ri-whatsapp-line"></i> Falar com Especialista no WhatsApp
                    </a>
                </div>

                <form id="landing-contact-form" action="{{ route('landing.contato') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="lead_nome">Seu Nome *</label>
                            <input type="text" id="lead_nome" name="nome" class="form-control" placeholder="Ex: João Silva" required>
                        </div>
                        <div class="form-group">
                            <label for="lead_email">E-mail *</label>
                            <input type="email" id="lead_email" name="email" class="form-control" placeholder="voce@empresa.com.br" required>
                        </div>
                        <div class="form-group">
                            <label for="lead_empresa">Nome da Empresa</label>
                            <input type="text" id="lead_empresa" name="empresa" class="form-control" placeholder="Sua empresa">
                        </div>
                        <div class="form-group">
                            <label for="lead_telefone">Telefone / WhatsApp *</label>
                            <input type="text" id="lead_telefone" name="telefone" class="form-control" placeholder="(00) 90000-0000" required>
                        </div>
                        <div class="form-group full">
                            <label for="lead_mensagem">Como podemos ajudar?</label>
                            <textarea id="lead_mensagem" name="mensagem" class="form-control" placeholder="Conte um pouco sobre sua empresa ou qual plano tem interesse..."></textarea>
                        </div>
                        <div class="form-group full">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-submit-lead" style="width: 100%;">
                                <i class="ri-send-plane-fill"></i> Solicitar Demonstração Gratuita
                            </button>
                        </div>
                    </div>
                    <div id="form-feedback" class="form-feedback"></div>
                </form>
            </div>
        </div>
    </section>

    <!-- ═══ 13. RODAPÉ PROFISSIONAL ═══ -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="#inicio" class="brand" style="margin-bottom: 12px;">
                        <img src="{{ $logoUrl }}" alt="Logo {{ $brandName }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline';">
                        <span @if($config && $config->logo) style="display: none;" @endif>{{ $brandName }}</span>
                    </a>
                    <p style="font-size: 13.5px; color: var(--skin-muted); max-width: 280px;">
                        {{ $brandTagline }}. Sistema completo em nuvem para emissão fiscal, estoque, vendas e controle financeiro.
                    </p>
                    <div class="social-links">
                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="ri-whatsapp-line"></i></a>
                        <a href="#contato" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
                        <a href="#contato" aria-label="LinkedIn"><i class="ri-linkedin-line"></i></a>
                    </div>
                </div>

                <div>
                    <h4>Plataforma</h4>
                    <ul>
                        <li><a href="#sistemas">Sistemas e Módulos</a></li>
                        <li><a href="#planos">Planos e Preços</a></li>
                        <li><a href="#clientes">Casos de Sucesso</a></li>
                        <li><a href="#recursos">Recursos</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Acesso &amp; Suporte</h4>
                    <ul>
                        <li><a href="{{ route('login') }}">Entrar no Sistema</a></li>
                        <li><a href="#contato">Solicitar Demonstração</a></li>
                        <li><a href="#sobre">Sobre Nós</a></li>
                        <li><a href="#contato">Política de Privacidade</a></li>
                        <li><a href="#contato">Termos de Uso</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Atendimento</h4>
                    <ul>
                        <li><i class="ri-phone-line"></i> {{ $contactPhone }}</li>
                        <li><i class="ri-mail-line"></i> {{ $contactEmail }}</li>
                        <li><i class="ri-time-line"></i> Seg a Sex — 08h às 18h</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} {{ $brandName }}. Todos os direitos reservados.</span>
                <span>Tecnologia ERP para gestão empresarial</span>
            </div>
        </div>
    </footer>

    <!-- Modal de Detalhes de Módulo -->
    <div class="modal-backdrop" id="module-modal">
        <div class="modal-box">
            <button type="button" class="modal-close" onclick="closeModuleModal()" aria-label="Fechar"><i class="ri-close-line"></i></button>
            <span class="section-badge" style="margin-bottom: 10px;"><i class="ri-apps-line"></i> Detalhes do Módulo</span>
            <h3 id="modal-module-title" style="font-size: 22px; margin-bottom: 10px;"></h3>
            <p id="modal-module-desc" style="font-size: 14.5px; color: var(--skin-muted); margin-bottom: 22px;"></p>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModuleModal()">Fechar</button>
                <a href="#contato" class="btn btn-primary" onclick="closeModuleModal()">Solicitar Demonstração</a>
            </div>
        </div>
    </div>

    <script>
        // Navbar sombra ao rolar
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Menu Mobile
        const mobileToggle = document.getElementById('mobile-toggle');
        const navLinks = document.getElementById('nav-links');
        mobileToggle.addEventListener('click', function () {
            navLinks.classList.toggle('mobile-open');
        });
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => navLinks.classList.remove('mobile-open'));
        });

        // Alternância Mensal / Anual nos Planos
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const mode = this.getAttribute('data-billing');
                document.querySelectorAll('.js-plan-price').forEach(el => {
                    el.textContent = el.getAttribute('data-' + mode);
                });
            });
        });

        // Selecionar Plano e rolar para o formulário
        function selectPlanInterest(planName) {
            const msgField = document.getElementById('lead_mensagem');
            if (msgField) {
                msgField.value = 'Tenho interesse no ' + planName + ' e gostaria de mais informações.';
            }
        }

        // FAQ Acordeão
        document.querySelectorAll('.faq-question').forEach(btn => {
            btn.addEventListener('click', function () {
                const item = this.parentElement;
                const isOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
                if (!isOpen) {
                    item.classList.add('open');
                }
            });
        });

        // Modal de Módulos
        function openModuleModal(title, desc) {
            document.getElementById('modal-module-title').textContent = title;
            document.getElementById('modal-module-desc').textContent = desc;
            document.getElementById('module-modal').classList.add('show');
        }
        function closeModuleModal() {
            document.getElementById('module-modal').classList.remove('show');
        }
        document.getElementById('module-modal').addEventListener('click', function (e) {
            if (e.target === this) closeModuleModal();
        });

        // Envio do Formulário via Fetch (sem recarregar a página)
        const contactForm = document.getElementById('landing-contact-form');
        const feedbackEl = document.getElementById('form-feedback');
        const submitBtn = document.getElementById('btn-submit-lead');

        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            feedbackEl.className = 'form-feedback';
            feedbackEl.textContent = '';

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Enviando...';

            try {
                const formData = new FormData(contactForm);
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    feedbackEl.className = 'form-feedback success';
                    feedbackEl.textContent = data.message;
                    contactForm.reset();
                } else {
                    const firstErr = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Verifique os campos preenchidos.');
                    feedbackEl.className = 'form-feedback error';
                    feedbackEl.textContent = firstErr;
                }
            } catch (err) {
                feedbackEl.className = 'form-feedback error';
                feedbackEl.textContent = 'Erro de conexão ao enviar. Tente novamente ou chame no WhatsApp.';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>

