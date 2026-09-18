@extends('layouts.app', ['title' => 'Detalhes da Notificação'])
@section('content')

<div class="card border-0 shadow-sm mt-3 mb-4">
    <div class="card-header modulo-header-gradient py-3 px-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="mb-1 modulo-title d-flex align-items-center gap-2 text-white">
                    @if($item->prioridade == 'urgente' || $item->prioridade == 'alta')
                        <i class="ri-alarm-warning-fill text-warning"></i>
                    @elseif($item->prioridade == 'media')
                        <i class="ri-alert-fill text-warning"></i>
                    @else
                        <i class="ri-notification-3-line text-white"></i>
                    @endif
                    {{ $item->titulo }}
                </h4>
                <p class="text-white-50 mb-0 modulo-subtitle fs-13">
                    Notificação do Sistema • Recebida em {{ __data_pt($item->created_at) }} às {{ __hora_pt($item->created_at) }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($item->prioridade == 'urgente' || $item->prioridade == 'alta')
                    <span class="badge bg-danger text-white px-3 py-2 fs-12 border border-danger border-opacity-50">
                        <i class="ri-flashlight-line me-1"></i> Alta Prioridade
                    </span>
                @elseif($item->prioridade == 'media')
                    <span class="badge bg-warning text-dark px-3 py-2 fs-12 border border-warning border-opacity-50">
                        <i class="ri-time-line me-1"></i> Atenção
                    </span>
                @else
                    <span class="badge bg-info bg-opacity-25 text-white px-3 py-2 fs-12 border border-info border-opacity-50">
                        <i class="ri-information-line me-1"></i> Informativo
                    </span>
                @endif
                <a href="{{ route('home') }}" class="btn btn-sm btn-light border px-3 d-inline-flex align-items-center gap-1 shadow-sm">
                    <i class="ri-arrow-left-line"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        @if($item->descricao_curta)
            <div class="alert alert-light border d-flex align-items-center gap-2 mb-4 p-3 rounded-3 shadow-none" style="background:#f8fafc;">
                <i class="ri-information-fill fs-5 text-primary"></i>
                <div class="fs-13 text-secondary">
                    <strong>Resumo:</strong> {{ $item->descricao_curta }}
                </div>
            </div>
        @endif

        <div class="notificacao-conteudo-box p-3 rounded-3 border bg-white">
            {!! $item->descricao !!}
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-3 mt-4 border-top">
            <div class="text-muted fs-12 d-flex align-items-center gap-2">
                <i class="ri-shield-check-line text-success"></i>
                <span>Status: <strong class="text-dark">Visualizada</strong></span>
            </div>
            <div class="text-muted fs-12">
                <i class="ri-time-line me-1"></i> {{ __data_pt($item->created_at) }} {{ __hora_pt($item->created_at) }}
            </div>
        </div>
    </div>
</div>

<style>
.modulo-header-gradient {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
}
.notificacao-conteudo-box .card {
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.notificacao-conteudo-box h5 {
    font-size: 0.95rem;
    color: #475569;
    margin-bottom: 0.5rem;
}
.notificacao-conteudo-box h5 strong {
    color: #1e293b;
}
</style>

@endsection
