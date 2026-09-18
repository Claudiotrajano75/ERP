<div class="row g-3">
    <div class="col-12">
        <div class="alert alert-warning d-flex align-items-center gap-2 p-3 rounded-3 mb-3 border-0" style="background:#fef3c7;color:#92400e;">
            <i class="ri-vip-crown-fill fs-4 text-warning"></i>
            <div>
                <strong class="d-block fs-14">Assinatura / Plano Expirando</strong>
                <span class="fs-12">Sua assinatura está próxima da data de vencimento.</span>
            </div>
        </div>

        <div class="p-3 rounded-3 border bg-light mb-3">
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <span class="text-muted fs-12 d-block">Plano Atual:</span>
                    <span class="fw-bold text-dark fs-14">{{ $item->plano->nome ?? 'Assinatura ERP' }}</span>
                </div>
                <div class="col-md-6 col-12">
                    <span class="text-muted fs-12 d-block">Data de Expiração:</span>
                    <span class="badge bg-danger fs-13 px-2 py-1">{{ \Carbon\Carbon::parse($item->data_expiracao)->format('d/m/Y') }}</span>
                </div>
            </div>
            <p class="text-muted mt-3 mb-0 fs-13">
                <i class="ri-checkbox-circle-line me-1 text-success"></i> Renove seu plano para garantir a continuidade dos serviços fiscais, emissão de documentos e sincronização.
            </p>
        </div>

        <div>
            <a href="{{ route('planos.index') }}" class="btn btn-success btn-sm px-3">
                <i class="ri-repeat-line me-1"></i> Renovar Plano Agora
            </a>
        </div>
    </div>
</div>
