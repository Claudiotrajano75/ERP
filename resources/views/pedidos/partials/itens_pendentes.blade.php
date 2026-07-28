@forelse($data as $item)
<div class="col-12 col-md-6 col-lg-4">
    <div class="cozinha-card">
        <form method="get" @isset($item->is_cardapio) action="{{ route('pedido-cozinha.update-item', [$item->id])}}" @else action="{{ route('pedidos-delivery.update-item', [$item->id])}}" @endif id="form-{{$item->id}}" class="m-0 flex-grow-1 d-flex flex-column">
            
            {{-- Cabeçalho do Card --}}
            <div class="cozinha-card-header">
                <span class="cozinha-card-title fw-bold text-muted">Item #{{ $item->id }}</span>
                @if(isset($item->is_cardapio))
                <span class="cozinha-badge-canal bg-primary-subtle text-primary border border-primary-subtle px-2 py-0-5">Comanda</span>
                @else
                <span class="cozinha-badge-canal bg-warning-subtle text-warning border border-warning-subtle px-2 py-0-5">Delivery</span>
                @endif
            </div>

            {{-- Corpo do Card --}}
            <div class="cozinha-card-body">
                <h3>{{ $item->produto->nome }}</h3>
                
                @if(isset($item->pedido->comanda) && $item->pedido->comanda != "")
                <div class="cozinha-meta-item">
                    <i class="ri-restaurant-line"></i>
                    <span>Comanda: <strong class="text-danger">#{{ $item->pedido->comanda }}</strong></span>
                </div>
                @else
                <div class="cozinha-meta-item">
                    <i class="ri-file-list-3-line"></i>
                    <span>Pedido ID: <strong class="text-danger">#{{ $item->pedido->id }}</strong></span>
                </div>
                @endif
                
                <div class="cozinha-meta-item">
                    <i class="ri-stack-line"></i>
                    <span>Quantidade: <strong class="text-primary">{{ number_format($item->quantidade, 2) }}</strong></span>
                </div>
                
                <div class="cozinha-meta-item">
                    <i class="ri-time-line"></i>
                    <span>Hora do Pedido: <strong>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</strong></span>
                </div>

                <div class="cozinha-meta-item">
                    <i class="ri-add-circle-line"></i>
                    <span>Adicionais: <strong class="text-primary">{{ sizeof($item->adicionais) > 0 ? $item->getAdicionaisStr() : '--' }}</strong></span>
                </div>

                <div class="cozinha-meta-item">
                    <i class="ri-chat-3-line"></i>
                    <span>Observação: <strong class="text-dark">{{ $item->observacao != '' ? $item->observacao : '--' }}</strong></span>
                </div>

                <div class="cozinha-meta-item">
                    <i class="ri-flag-line"></i>
                    <span>Estado: 
                        @if($item->estado == 'pendente')
                        <span class="status-badge status-pendente">Pendente</span>
                        @elseif($item->estado == 'preparando')
                        <span class="status-badge status-preparando">Preparando</span>
                        @else
                        <span class="status-badge bg-success text-white">{{ strtoupper($item->estado) }}</span>
                        @endif
                    </span>
                </div>

                {{-- Sabores de Pizza --}}
                @if(sizeof($item->pizzas) > 0)
                <div class="cozinha-meta-item mt-2">
                    <i class="ri-slice-line text-danger"></i>
                    <span>Sabores: 
                        @foreach($item->pizzas as $pizza)
                        <strong class="text-danger">{{ $pizza->sabor->nome }}</strong>@if(!$loop->last) | @endif
                        @endforeach
                    </span>
                </div>
                <div class="cozinha-meta-item">
                    <i class="ri-expand-up-down-line text-danger"></i>
                    <span>Tamanho: <strong class="text-danger">{{ $item->tamanho->nome }}</strong></span>
                </div>
                @endif

                {{-- Ponto da Carne --}}
                @if($item->ponto_carne)
                <div class="cozinha-meta-item mt-2">
                    <i class="ri-contrast-drop-2-line text-danger"></i>
                    <span>Ponto da carne: <strong class="text-danger">{{ $item->ponto_carne }}</strong></span>
                </div>
                @endif

                {{-- Contagem do Tempo de Preparo --}}
                @if($item->tempo_preparo > 0)
                @if($item->tempoPreparoRestante() > -1)
                <div class="preparo-info">
                    <div class="fs-12 text-muted fw-bold"><i class="ri-timer-line text-success"></i> Preparando desde: {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i:s') }}</div>
                    <div class="fs-12 fw-bold text-success mt-1">Tempo restante: {{ $item->tempoPreparoRestante() }} min</div>
                </div>
                @else
                <div class="preparo-atrasado">
                    <div class="fs-12 text-muted fw-bold"><i class="ri-timer-warning-line text-danger"></i> Preparando desde: {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i:s') }}</div>
                    <div class="fs-12 fw-bold text-danger mt-1">Atraso na entrega: {{ $item->tempoPreparoRestante()*-1 }} min</div>
                </div>
                @endif
                @endif

                <input type="hidden" name="estado" value="finalizado">
            </div>

            {{-- Rodapé do Card --}}
            <div class="cozinha-card-footer mt-auto">
                @if($item->estado == 'pendente')
                <button type="button" class="btn btn-warning w-100 py-2 d-flex align-items-center justify-content-center gap-1" onclick="openModal()" data-bs-toggle="modal" data-bs-target="#modal-item-{{ $item->id }}">
                    <i class="ri-play-circle-line fs-16"></i> Entrou em preparo
                </button>
                @endif
                <button type="submit" class="btn btn-success w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                    <i class="ri-checkbox-circle-line fs-16"></i> Finalizado
                </button>
            </div>

        </form>
    </div>
</div>

{{-- MODAL TEMPO DE PREPARO INDIVIDUAL --}}
<div class="modal fade text-dark" id="modal-item-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <form action="{{ route('pedido-cozinha.update-item', [$item->id])}}" method="get" class="m-0">
                <div class="modal-header modulo-header-gradient py-3 px-4">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="exampleModalLabel">
                        <i class="ri-timer-line"></i> Tempo de Preparo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row">
                        <input type="hidden" name="estado" value="preparando">
                        <div class="col-md-12">
                            {!!Form::text('tempo_preparo', 'Tempo de preparo (em minutos)')
                            ->attrs(['data-mask' => '000', 'class' => 'form-control'])
                            ->value($item->produto->tempo_preparo)
                            !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-white border-top d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary px-3" style="border-radius: 8px;" data-bs-dismiss="modal">
                        <i class="ri-close-line align-middle me-1"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">
                        <i class="ri-check-line align-middle me-1"></i> Iniciar Preparo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@empty
<div class="col-12">
    <div class="modulo-empty">
        <i class="ri-inbox-2-line"></i>
        <p>Nenhum item pendente de preparação.</p>
    </div>
</div>
@endforelse
