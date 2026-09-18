<div class="row g-3">
    <div class="col-12">
        <div class="alert alert-danger d-flex align-items-center gap-2 p-3 rounded-3 mb-3 border-0" style="background:#fee2e2;color:#991b1b;">
            <i class="ri-close-circle-fill fs-4"></i>
            <div>
                <strong class="d-block fs-14">Documento Fiscal Rejeitado pela SEFAZ</strong>
                <span class="fs-12">A SEFAZ não autorizou a emissão deste documento fiscal. Corrija as inconsistências para retransmitir.</span>
            </div>
        </div>

        <div class="p-3 rounded-3 border bg-light mb-3">
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <span class="text-muted fs-12 d-block">Documento:</span>
                    <span class="fw-bold text-dark fs-14">NF-e Nº {{ $item->numero ?? 'N/A' }} (Série {{ $item->numero_serie ?? '1' }})</span>
                </div>
                <div class="col-md-4 col-12">
                    <span class="text-muted fs-12 d-block">Destinatário:</span>
                    <span class="fw-semibold text-dark fs-14">{{ $item->cliente->razao_social ?? 'Consumidor Final' }}</span>
                </div>
                <div class="col-md-4 col-12">
                    <span class="text-muted fs-12 d-block">Valor Total:</span>
                    <span class="fw-bold text-dark fs-14">R$ {{ __moeda($item->total ?? 0) }}</span>
                </div>
            </div>

            @if(!empty($item->motivo_rejeicao))
                <div class="p-3 bg-white rounded-3 border border-danger border-opacity-25 mt-3">
                    <span class="text-danger fw-bold fs-12 d-block mb-1"><i class="ri-error-warning-line me-1"></i> Retorno da SEFAZ:</span>
                    <span class="text-dark fs-13">{{ $item->motivo_rejeicao }}</span>
                </div>
            @endif
        </div>

        @if(isset($item->id))
            <div>
                <a href="{{ route('nfe.edit', $item->id) }}" class="btn btn-danger btn-sm px-3">
                    <i class="ri-edit-line me-1"></i> Abrir NF-e para Corrigir e Reenviar
                </a>
            </div>
        @endif
    </div>
</div>
