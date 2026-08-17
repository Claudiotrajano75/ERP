@extends('layouts.app', ['title' => 'Notificação - ' . $item->titulo])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card-body {
        padding: 28px !important;
    }

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

    /* Badges */
    .badge {
        padding: 6px 12px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: none !important;
        border: 1px solid transparent;
    }

    .bg-danger-subtle {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }

    .bg-warning-subtle {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border-color: #fef3c7 !important;
    }

    .bg-info-subtle {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border-color: #bae6fd !important;
    }

    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
    }

    .notification-content {
        line-height: 1.7;
        font-size: 14px;
        color: #334155;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-0 modulo-title">
                                <i class="ri-notification-3-line"></i>
                                {{ $item->titulo }}
                            </h4>
                            <p class="modulo-subtitle">
                                Comunicado do Sistema &nbsp;|&nbsp; Criado em: {{ __data_pt($item->created_at, 1) }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('notificacao-super.index') }}" class="btn btn-light btn-sm text-dark">
                                <i class="ri-arrow-left-line me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DA NOTIFICAÇÃO ═══ -->
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                        <span class="text-muted fs-12">Prioridade:</span>
                        @if($item->prioridade == 'alta')
                            <span class="badge bg-danger-subtle"><i class="ri-alarm-warning-line me-1"></i> Alta</span>
                        @elseif($item->prioridade == 'media')
                            <span class="badge bg-warning-subtle"><i class="ri-alert-line me-1"></i> Média</span>
                        @else
                            <span class="badge bg-info-subtle"><i class="ri-information-line me-1"></i> Baixa</span>
                        @endif

                        @if($item->empresa)
                            <span class="text-muted fs-12 ms-3">Destinado a:</span>
                            <strong class="text-dark fs-12">{{ $item->empresa->nome }}</strong>
                        @endif
                    </div>

                    <div class="notification-content my-4">
                        {!! $item->descricao !!}
                    </div>

                    <hr class="mt-4">
                    <div class="d-flex justify-content-between align-items-center text-muted fs-12">
                        <span><i class="ri-calendar-line me-1"></i> Publicado em {{ __data_pt($item->created_at, 1) }}</span>
                        <a href="{{ route('notificacao-super.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-arrow-left-line me-1"></i> Fechar Notificação
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
