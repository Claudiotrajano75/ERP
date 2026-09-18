<div class="row g-3">
    <div class="col-md-6 col-12">
        <div class="p-3 rounded-3 border bg-light h-100">
            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                <i class="ri-box-3-line fs-5"></i>
                <span class="fw-bold fs-14">Informações do Item</span>
            </div>
            <div class="mb-2">
                <span class="text-muted fs-12 d-block">Produto:</span>
                <span class="fw-semibold text-dark fs-14">{{ $item->produto->nome ?? 'Produto' }}</span>
            </div>
            <div>
                <span class="text-muted fs-12 d-block">Quantidade Atual em Estoque:</span>
                <span class="badge bg-danger fs-13 px-2 py-1">
                    @if(isset($item->produto) && ($item->produto->unidade == 'UN' || $item->produto->unidade == 'UNID'))
                        {{ number_format($item->quantidade, 0) }} {{ $item->produto->unidade ?? 'UN' }}
                    @else
                        {{ number_format($item->quantidade, 3) }} {{ $item->produto->unidade ?? 'UN' }}
                    @endif
                </span>
                @if(isset($item->produto->estoque_minimo) && $item->produto->estoque_minimo > 0)
                    <span class="text-muted fs-12 ms-2">(Mínimo exigido: {{ number_format($item->produto->estoque_minimo, 0) }})</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="p-3 rounded-3 border bg-light h-100">
            <div class="d-flex align-items-center gap-2 mb-2 text-success">
                <i class="ri-money-dollar-circle-line fs-5"></i>
                <span class="fw-bold fs-14">Precificação</span>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <span class="text-muted fs-12 d-block">Valor de Compra:</span>
                    <span class="fw-bold text-dark fs-14">R$ {{ __moeda($item->produto->valor_compra ?? 0) }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted fs-12 d-block">Valor de Venda:</span>
                    <span class="fw-bold text-dark fs-14">R$ {{ __moeda($item->produto->valor_unitario ?? 0) }}</span>
                </div>
            </div>
            @if(isset($item->produto->id))
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('produtos.edit', $item->produto->id) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="ri-edit-line me-1"></i> Acessar Cadastro do Produto
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>