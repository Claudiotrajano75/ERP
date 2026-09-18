<div class="row g-3">
    <div class="col-12">
        <div class="alert alert-danger d-flex align-items-center gap-2 p-3 rounded-3 mb-3 border-0" style="background:#fee2e2;color:#991b1b;">
            <i class="ri-shield-keyhole-fill fs-4"></i>
            <div>
                <strong class="d-block fs-14">Atenção com o Certificado Digital A1</strong>
                <span class="fs-12">O certificado digital está próximo do vencimento ou expirado.</span>
            </div>
        </div>

        <div class="p-3 rounded-3 border bg-light mb-3">
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <span class="text-muted fs-12 d-block">CNPJ / Empresa:</span>
                    <span class="fw-bold text-dark fs-14">{{ $item->nome ?? '' }} ({{ $item->cpf_cnpj ?? '' }})</span>
                </div>
                <div class="col-md-6 col-12">
                    <span class="text-muted fs-12 d-block">Validade do Certificado:</span>
                    <span class="badge bg-danger fs-13 px-2 py-1">{{ $item->validade_formatada ?? 'Expirando em breve' }}</span>
                </div>
            </div>
            <p class="text-muted mt-3 mb-0 fs-13">
                <i class="ri-information-line me-1 text-primary"></i> Para evitar interrupções na emissão de notas fiscais eletrônicas (NF-e, NFC-e, CT-e, MDF-e), faça a renovação do seu certificado digital A1 (.pfx / .p12) e envie o novo arquivo nas configurações da empresa.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('config.index') }}" class="btn btn-primary btn-sm px-3">
                <i class="ri-upload-cloud-line me-1"></i> Atualizar Certificado nas Configurações
            </a>
        </div>
    </div>
</div>
