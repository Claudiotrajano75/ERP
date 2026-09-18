@if(sizeof($notificacoesHoje) > 0)
    <div class="px-3 py-2 bg-light border-bottom text-uppercase fs-11 fw-bold text-muted" style="letter-spacing: 0.5px;">
        Hoje
    </div>
    @foreach($notificacoesHoje as $n)
        @php
            $isUrgente = $n->prioridade == 'urgente' || $n->prioridade == 'alta';
            $isMedia   = $n->prioridade == 'media';
            $iconClass = $isUrgente ? 'ri-error-warning-fill text-danger' : ($isMedia ? 'ri-alert-fill text-warning' : 'ri-notification-3-line text-primary');
            $bgIcon    = $isUrgente ? '#fee2e2' : ($isMedia ? '#fef3c7' : '#e0e7ff');
        @endphp
        <a href="{{ route('notificacao.show', [$n->id]) }}" class="dropdown-item px-3 py-2 border-bottom d-flex align-items-center gap-2 notify-item-custom {{ $n->visualizada ? 'bg-white' : 'bg-light bg-opacity-50' }}">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width:36px;height:36px;background:{{ $bgIcon }};">
                <i class="{{ $iconClass }} fs-18"></i>
            </div>
            <div class="flex-grow-1 text-truncate">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-13 fw-semibold text-dark text-truncate" style="max-width:75%;">{{ $n->titulo }}</span>
                    <small class="text-muted fs-11">{{ __hora_pt($n->created_at) }}</small>
                </div>
                <div class="fs-12 text-muted text-truncate" style="max-width:280px;">
                    {{ $n->descricao_curta }}
                </div>
            </div>
        </a>
    @endforeach
@endif

@if(sizeof($notificacoesOntem) > 0)
    <div class="px-3 py-2 bg-light border-bottom text-uppercase fs-11 fw-bold text-muted" style="letter-spacing: 0.5px;">
        Ontem
    </div>
    @foreach($notificacoesOntem as $n)
        @php
            $isUrgente = $n->prioridade == 'urgente' || $n->prioridade == 'alta';
            $isMedia   = $n->prioridade == 'media';
            $iconClass = $isUrgente ? 'ri-error-warning-fill text-danger' : ($isMedia ? 'ri-alert-fill text-warning' : 'ri-notification-3-line text-primary');
            $bgIcon    = $isUrgente ? '#fee2e2' : ($isMedia ? '#fef3c7' : '#e0e7ff');
        @endphp
        <a href="{{ route('notificacao.show', [$n->id]) }}" class="dropdown-item px-3 py-2 border-bottom d-flex align-items-center gap-2 notify-item-custom {{ $n->visualizada ? 'bg-white' : 'bg-light bg-opacity-50' }}">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width:36px;height:36px;background:{{ $bgIcon }};">
                <i class="{{ $iconClass }} fs-18"></i>
            </div>
            <div class="flex-grow-1 text-truncate">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-13 fw-semibold text-dark text-truncate" style="max-width:75%;">{{ $n->titulo }}</span>
                    <small class="text-muted fs-11">{{ __hora_pt($n->created_at) }}</small>
                </div>
                <div class="fs-12 text-muted text-truncate" style="max-width:280px;">
                    {{ $n->descricao_curta }}
                </div>
            </div>
        </a>
    @endforeach
@endif

@if(sizeof($notificacoesAtrasadas) > 0)
    <div class="px-3 py-2 bg-light border-bottom text-uppercase fs-11 fw-bold text-muted" style="letter-spacing: 0.5px;">
        Anteriores / Atrasadas
    </div>
    @foreach($notificacoesAtrasadas as $n)
        @php
            $isUrgente = $n->prioridade == 'urgente' || $n->prioridade == 'alta';
            $isMedia   = $n->prioridade == 'media';
            $iconClass = $isUrgente ? 'ri-error-warning-fill text-danger' : ($isMedia ? 'ri-alert-fill text-warning' : 'ri-notification-3-line text-primary');
            $bgIcon    = $isUrgente ? '#fee2e2' : ($isMedia ? '#fef3c7' : '#e0e7ff');
        @endphp
        <a href="{{ route('notificacao.show', [$n->id]) }}" class="dropdown-item px-3 py-2 border-bottom d-flex align-items-center gap-2 notify-item-custom {{ $n->visualizada ? 'bg-white' : 'bg-light bg-opacity-50' }}">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width:36px;height:36px;background:{{ $bgIcon }};">
                <i class="{{ $iconClass }} fs-18"></i>
            </div>
            <div class="flex-grow-1 text-truncate">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-13 fw-semibold text-dark text-truncate" style="max-width:75%;">{{ $n->titulo }}</span>
                    <small class="text-muted fs-11">{{ __data_pt($n->created_at, 0) }}</small>
                </div>
                <div class="fs-12 text-muted text-truncate" style="max-width:280px;">
                    {{ $n->descricao_curta }}
                </div>
            </div>
        </a>
    @endforeach
@endif

@if(sizeof($notificacoesHoje) == 0 && sizeof($notificacoesOntem) == 0 && sizeof($notificacoesAtrasadas) == 0)
    <div class="text-center py-4 text-muted">
        <i class="ri-checkbox-circle-line fs-1 text-success d-block mb-1"></i>
        <span class="fs-13">Tudo limpo! Nenhuma notificação pendente.</span>
    </div>
@endif

<style>
.notify-item-custom:hover {
    background-color: #f1f5f9 !important;
    transition: background 0.15s ease;
}
</style>
