<div class="row g-3">
    <div class="col-md-6 col-12">
        <div class="p-3 rounded-3 border bg-light h-100">
            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                <i class="ri-customer-service-2-line fs-5"></i>
                <span class="fw-bold fs-14">Atendimento / Suporte</span>
            </div>
            <div class="mb-2">
                <span class="text-muted fs-12 d-block">Data de Abertura:</span>
                <span class="fw-semibold text-dark">{{ __data_pt($ticket->created_at ?? date('Y-m-d'), 1) }}</span>
            </div>
            <div>
                <span class="text-muted fs-12 d-block">Departamento:</span>
                <span class="badge bg-primary-subtle text-primary fs-12">{{ $ticket->departamento ?? 'Geral' }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2 text-info">
                    <i class="ri-information-line fs-5"></i>
                    <span class="fw-bold fs-14">Acompanhamento</span>
                </div>
                <p class="text-muted fs-13 mb-0">Clique abaixo para responder ou verificar o andamento deste chamado.</p>
            </div>
            @if(isset($ticket->id))
                <div class="mt-3 pt-2 border-top">
                    <a class="btn btn-primary btn-sm w-100" href="{{ route('ticket.show', [$ticket->id]) }}">
                        <i class="ri-external-link-line me-1"></i> Abrir Chamado / Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>