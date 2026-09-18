@extends('layouts.app', ['title' => 'Detalhes do Serviço'])

@section('css')
<style>
/* ─── Cards de Detalhes ─── */
.detail-card { background: #ffffff; border: 1px solid #eef0f5; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
.detail-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; }
.detail-value { font-size: 15px; font-weight: 700; color: #1f2937; margin-bottom: 0; }
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-purple { background: #f3e8ff; color: #7e22ce; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-eye-line"></i>
                            Detalhes do Serviço: {{ $item->nome }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Visualização completa das configurações comerciais e tributárias do serviço #{{ $item->numero_sequencial }}.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('servicos.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                        @can('servico_edit')
                        <a href="{{ route('servicos.edit', $item->id) }}" class="dash-btn dash-btn-primary">
                            <i class="ri-pencil-line"></i> Editar Serviço
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- ═══ CORPO ═══ -->
            <div class="card-body p-4">

                <!-- Informações Principais -->
                <div class="row g-4">
                    <div class="col-md-2 col-12 text-center">
                        <img class="rounded-circle border bg-light shadow-sm"
                             src="{{ $item->img }}"
                             style="width:90px;height:90px;object-fit:cover;">
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-briefcase-line me-1"></i> Nome do Serviço</div>
                            <p class="detail-value fs-18 text-primary">{{ $item->nome }}</p>
                            @if($item->categoria)
                            <span class="badge bg-light text-dark border px-2 py-1 fs-11 mt-1"><i class="ri-folder-line me-1"></i>{{ $item->categoria->nome }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-hashtag me-1"></i> Código #</div>
                            <p class="detail-value fs-18 text-dark">#{{ $item->numero_sequencial }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-toggle-line me-1"></i> Status</div>
                            <p class="detail-value mt-1">
                                @if($item->status)
                                <span class="pill pill-ok"><i class="ri-check-line"></i> Ativo</span>
                                @else
                                <span class="pill pill-no"><i class="ri-close-line"></i> Inativo</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-barcode-line me-1"></i> Cód. Serviço</div>
                            <p class="detail-value">{{ $item->codigo_servico ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Preços e Duração -->
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-money-dollar-circle-line me-1"></i> Valor de Venda</div>
                            <p class="detail-value fs-18 text-success">R$ {{ __moeda($item->valor) }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-time-line me-1"></i> Duração</div>
                            <p class="detail-value">{{ $item->tempo_servico }} {{ $item->unidade_cobranca }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-percent-line me-1"></i> Comissão</div>
                            <p class="detail-value">R$ {{ __moeda($item->comissao) }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-time-line me-1"></i> Tempo Adicional</div>
                            <p class="detail-value">{{ $item->tempo_adicional ?? '0' }} min</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-price-tag-line me-1"></i> Valor Adicional</div>
                            <p class="detail-value">R$ {{ __moeda($item->valor_adicional) }}</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Integrações e Canais -->
                <div class="row g-3">
                    @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-calendar-check-line me-1"></i> Reservas Online</div>
                            <p class="detail-value mt-1">
                                @if($item->reserva)
                                <span class="pill pill-info"><i class="ri-check-line"></i> Sim</span>
                                @else
                                <span class="text-muted fs-12">Não</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-file-text-line me-1"></i> Padrão Reserva NFSe</div>
                            <p class="detail-value mt-1">
                                @if($item->padrao_reserva_nfse)
                                <span class="pill pill-ok"><i class="ri-check-line"></i> Sim</span>
                                @else
                                <span class="text-muted fs-12">Não</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-store-2-line me-1"></i> Marketplace / Delivery</div>
                            <p class="detail-value mt-1">
                                @if($item->marketplace)
                                <span class="pill pill-purple"><i class="ri-store-line"></i> Ativo</span>
                                @else
                                <span class="text-muted fs-12">Inativo</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-star-line me-1"></i> Destaque Marketplace</div>
                            <p class="detail-value mt-1">
                                @if($item->destaque_marketplace)
                                <span class="pill pill-ok"><i class="ri-check-line"></i> Sim</span>
                                @else
                                <span class="text-muted fs-12">Não</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-hourglass-line me-1"></i> Tempo Tolerância</div>
                            <p class="detail-value">{{ $item->tempo_tolerancia ?? '0' }} min</p>
                        </div>
                    </div>
                </div>

                @if($item->descricao)
                <hr class="my-4">
                <div class="row">
                    <div class="col-12">
                        <div class="detail-card">
                            <div class="detail-label mb-2"><i class="ri-file-list-3-line me-1"></i> Descrição do Serviço</div>
                            <p class="mb-0 text-dark fs-14" style="line-height: 1.6;">{{ $item->descricao }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <hr class="my-4">

                <!-- Tributação -->
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="text-dark fw-bold mb-2">
                            <i class="ri-scales-3-line text-primary me-2"></i>
                            Alíquotas Tributárias
                        </h6>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label">Alíquota ISS</div>
                            <p class="detail-value">{{ $item->aliquota_iss ?? '0' }}%</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label">Alíquota PIS</div>
                            <p class="detail-value">{{ $item->aliquota_pis ?? '0' }}%</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label">Alíquota COFINS</div>
                            <p class="detail-value">{{ $item->aliquota_cofins ?? '0' }}%</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card">
                            <div class="detail-label">Alíquota INSS</div>
                            <p class="detail-value">{{ $item->aliquota_inss ?? '0' }}%</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Datas e Dados Fiscais -->
                <div class="row g-3">
                    <div class="col-md-4 col-12">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-building-line me-1"></i> Cód. Tributação Municipal</div>
                            <p class="detail-value">{{ $item->codigo_tributacao_municipio ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-calendar-line me-1"></i> Data de Cadastro</div>
                            <p class="detail-value">{{ $item->created_at->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-history-line me-1"></i> Última Atualização</div>
                            <p class="detail-value">{{ $item->updated_at->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

