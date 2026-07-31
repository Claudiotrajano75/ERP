@extends('layouts.app', ['title' => 'Editar Usuário'])

@section('css')
<style>
    /* Cabeçalho de Gradiente Premium */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.3) !important;
        color: #fff !important;
        background: transparent !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        padding: 6px 16px !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: #fff !important;
    }
</style>
@endsection

@section('content')
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center modulo-header-gradient">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100">
            <div>
                <h4 class="modulo-title text-white">
                    <i class="ri-user-settings-line"></i> Editar Usuário
                </h4>
                <p class="modulo-subtitle">Atualize os dados de acesso e permissões do usuário.</p>
            </div>
            <a href="{{ route('usuario-super.index') }}" class="btn btn-outline-light btn-sm">
                <i class="ri-arrow-left-line"></i> Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        {!!Form::open()->fill($item)
        ->put()
        ->route('usuario-super.update', [$item->id])
        ->multipart()
        !!}
        <div class="pl-lg-4">
            @include('usuarios_super._forms')
        </div>
        {!!Form::close()!!}
    </div>
</div>
@endsection
