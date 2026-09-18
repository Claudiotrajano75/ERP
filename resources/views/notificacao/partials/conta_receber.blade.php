<div class="row g-3">
    <div class="col-md-6 col-12">
        <div class="p-3 rounded-3 border bg-light h-100">
            <div class="d-flex align-items-center gap-2 mb-2 text-success">
                <i class="ri-calendar-check-line fs-5"></i>
                <span class="fw-bold fs-14">Vencimento &amp; Valor</span>
            </div>
            <div class="mb-2">
                <span class="text-muted fs-12 d-block">Data de Vencimento:</span>
                <span class="fw-bold fs-14 text-success">{{ __data_pt($item->data_vencimento, 0) }}</span>
            </div>
            <div>
                <span class="text-muted fs-12 d-block">Valor a Receber:</span>
                <span class="fs-18 fw-bold text-dark">R$ {{ __moeda($item->valor_integral) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                    <i class="ri-user-smile-line fs-5"></i>
                    <span class="fw-bold fs-14">Cliente / Origem</span>
                </div>
                <div class="mb-2">
                    <span class="text-muted fs-12 d-block">Cliente:</span>
                    <span class="fw-semibold text-dark">{{ $item->cliente->razao_social ?? ($item->cliente->info ?? 'Consumidor') }}</span>
                </div>
                @if($item->descricao)
                    <div>
                        <span class="text-muted fs-12 d-block">Descrição:</span>
                        <span class="text-secondary fs-13">{{ $item->descricao }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-3 pt-2 border-top">
                <a class="btn btn-success btn-sm w-100" href="{{ route('conta-receber.pay', [$item->id]) }}">
                    <i class="ri-money-dollar-box-line me-1"></i> Acessar / Receber Conta
                </a>
            </div>
        </div>
    </div>
</div>