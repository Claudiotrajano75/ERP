@extends('layouts.app', ['title' => 'Perfil do Usuário'])

@section('css')
<style>
/* ── Base do módulo (reutilizado no projeto) ──────────────── */
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
}
.modulo-header-gradient .modulo-title  { color: #fff; font-weight: 700; letter-spacing: -.3px; }
.modulo-header-gradient .modulo-title i{ background: rgba(255,255,255,.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle{ color: rgba(255,255,255,.6) !important; font-weight: 400; }
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

/* ── Hero do perfil ───────────────────────────────────────── */
.profile-hero {
    background: linear-gradient(160deg, #1e1b4b 0%, #312e81 55%, #4338ca 100%);
    padding: 40px 32px 52px;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.profile-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 260px; height: 260px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.profile-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -40px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,.03);
    border-radius: 50%;
}
.profile-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 16px;
}
.profile-avatar {
    width: 100px; height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.25);
    box-shadow: 0 8px 32px rgba(0,0,0,.35);
    transition: transform .3s ease, box-shadow .3s ease;
}
.profile-avatar:hover { transform: scale(1.05); box-shadow: 0 12px 40px rgba(0,0,0,.45); }
.profile-online-dot {
    position: absolute;
    bottom: 6px; right: 6px;
    width: 16px; height: 16px;
    background: #22c55e;
    border-radius: 50%;
    border: 2px solid #312e81;
    animation: pulse-green 2s infinite;
}
@keyframes pulse-green {
    0%,100% { box-shadow: 0 0 0 3px rgba(34,197,94,.25); }
    50%      { box-shadow: 0 0 0 7px rgba(34,197,94,.1); }
}
.profile-name  { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
.profile-email { color: rgba(255,255,255,.65); font-size: 13px; }

/* ── Badges ───────────────────────────────────────────────── */
.role-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18);
    color: #e0e7ff;
    border-radius: 20px; padding: 5px 14px;
    font-size: 12px; font-weight: 600;
    backdrop-filter: blur(4px);
    margin: 4px 2px;
}
.master-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(90deg, #f59e0b, #ef4444);
    color: #fff; border-radius: 20px; padding: 5px 14px;
    font-size: 11px; font-weight: 700;
    animation: glow-badge 3s infinite;
    margin: 4px 0;
}
@keyframes glow-badge {
    0%,100% { box-shadow: 0 3px 10px rgba(239,68,68,.3); }
    50%      { box-shadow: 0 3px 20px rgba(239,68,68,.55); }
}

/* ── Botão editar ─────────────────────────────────────────── */
.profile-edit-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff; border-radius: 8px;
    padding: 8px 20px; font-size: 13px; font-weight: 600;
    text-decoration: none; backdrop-filter: blur(6px);
    transition: all .2s ease; margin-top: 16px;
}
.profile-edit-btn:hover {
    background: rgba(255,255,255,.28); color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0,0,0,.25);
}

/* ── Info cards ───────────────────────────────────────────── */
.info-card {
    background: #fff;
    border: 1px solid #eef0f5;
    border-radius: 12px; padding: 20px 22px;
    height: 100%;
    transition: box-shadow .2s, transform .2s;
}
.info-card:hover { box-shadow: 0 4px 20px rgba(67,56,202,.07); transform: translateY(-2px); }
.info-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; margin-bottom: 14px;
}
.ic-purple { background: #ede9fe; color: #7c3aed; }
.ic-blue   { background: #dbeafe; color: #2563eb; }
.ic-amber  { background: #fef3c7; color: #d97706; }
.ic-green  { background: #dcfce7; color: #16a34a; }

.info-label {
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: #94a3b8; margin-bottom: 4px;
}
.info-value { font-size: 14px; font-weight: 600; color: #1e293b; word-break: break-all; }

/* ── Section label ────────────────────────────────────────── */
.section-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    color: #94a3b8;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 8px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 6px;
}
.section-label i { color: #a5b4fc; font-size: 14px; }

/* ── Plano card ───────────────────────────────────────────── */
.plano-card {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px 24px;
}
.plano-card.expired {
    background: linear-gradient(135deg, #fff1f2, #ffe4e6);
    border-color: #fecdd3;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ HEADER ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-user-smile-line"></i>
                                Meu Perfil
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Visualize suas informações de conta, permissões e plano contratado.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ HERO ═══ --}}
                <div class="profile-hero">
                    <div class="profile-avatar-wrap">
                        <img src="{{ $item->img ?? '/imgs/no-image.png' }}"
                             alt="Avatar de {{ $item->name }}"
                             class="profile-avatar">
                        <span class="profile-online-dot" title="Conta ativa"></span>
                    </div>

                    <div class="profile-name">{{ $item->name }}</div>
                    <div class="profile-email">
                        <i class="ri-mail-line me-1"></i>{{ $item->email }}
                    </div>

                    {{-- Badge Master --}}
                    @if($item->email == env('MAILMASTER'))
                    <div class="mt-2">
                        <span class="master-badge">
                            <i class="ri-vip-crown-fill"></i> Super Admin — Master
                        </span>
                    </div>
                    @endif

                    {{-- Roles --}}
                    @if($item->roles && $item->roles->isNotEmpty())
                    <div class="mt-1">
                        @foreach($item->roles as $role)
                        <span class="role-badge">
                            <i class="ri-shield-check-line"></i>
                            {{ $role->description ?? $role->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Empresa --}}
                    @if($item->empresa)
                    <div class="mt-1">
                        <span class="role-badge">
                            <i class="ri-building-line"></i>
                            {{ $item->empresa->empresa->nome ?? '—' }}
                        </span>
                    </div>
                    @endif

                    <a href="{{ route('usuarios.edit', $item->id) }}" class="profile-edit-btn">
                        <i class="ri-pencil-line"></i> Editar Perfil
                    </a>
                </div>

                {{-- ═══ CORPO ═══ --}}
                <div class="card-body p-4">

                    {{-- Informações de Registro --}}
                    <div class="section-label">
                        <i class="ri-id-card-line"></i> Informações de Registro
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-12">
                            <div class="info-card">
                                <div class="info-card-icon ic-purple">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div class="info-label">Nome de Acesso</div>
                                <div class="info-value">{{ $item->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="info-card">
                                <div class="info-card-icon ic-blue">
                                    <i class="ri-mail-open-line"></i>
                                </div>
                                <div class="info-label">E-mail Cadastrado</div>
                                <div class="info-value">{{ $item->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="info-card">
                                <div class="info-card-icon ic-amber">
                                    <i class="ri-calendar-event-line"></i>
                                </div>
                                <div class="info-label">Conta criada em</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    <small class="text-muted fw-normal d-block">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} hs
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Grupos de Acesso --}}
                    @if($item->roles && $item->roles->isNotEmpty())
                    <div class="section-label">
                        <i class="ri-shield-user-line"></i> Grupos de Acesso Atribuídos
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach($item->roles as $role)
                        <div class="col-md-4 col-12">
                            <div class="info-card d-flex align-items-start gap-3">
                                <div class="info-card-icon ic-purple mb-0" style="flex-shrink:0;">
                                    <i class="ri-shield-check-line"></i>
                                </div>
                                <div>
                                    <div class="info-label">Grupo / Role</div>
                                    <div class="info-value">{{ $role->description ?? $role->name }}</div>
                                    <small class="text-muted">{{ $role->name }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Plano & Licenciamento --}}
                    @if($item->empresa && ($item->empresa->empresa->plano ?? null))
                    @php
                        $plano      = $item->empresa->empresa->plano;
                        $expiracao  = \Carbon\Carbon::parse($plano->data_expiracao);
                        $isExpired  = $expiracao->isPast();
                        $diasRestam = $isExpired ? 0 : (int) now()->diffInDays($expiracao);
                    @endphp
                    <div class="section-label">
                        <i class="ri-vip-diamond-line"></i> Plano &amp; Licenciamento
                    </div>
                    <div class="plano-card {{ $isExpired ? 'expired' : '' }}">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4 col-12">
                                <div class="info-label">Plano Ativo</div>
                                <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-success' }} px-3 py-2 mt-1 fs-12">
                                    <i class="ri-vip-crown-line me-1"></i>
                                    {{ $plano->plano->nome ?? '—' }}
                                </span>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="info-label">Data de Expiração</div>
                                <div class="info-value {{ $isExpired ? 'text-danger' : 'text-success' }}">
                                    <i class="ri-calendar-close-line me-1"></i>
                                    {{ __data_pt($plano->data_expiracao, false) }}
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="info-label">Status</div>
                                @if($isExpired)
                                <span class="badge bg-danger px-3 py-2 mt-1">
                                    <i class="ri-error-warning-line me-1"></i> Licença Expirada
                                </span>
                                @else
                                <span class="badge bg-success px-3 py-2 mt-1">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    {{ $diasRestam }} dias restantes
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

