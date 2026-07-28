@extends('layouts.app', ['title' => 'Detalhes do Serviço'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }

/* ─── Detail Label ─── */
.detail-label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.detail-value { font-size: 14px; font-weight: 600; color: #1a1a2e; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-eye-line"></i>
                                Detalhes do Serviço #{{ $item->numero_sequencial }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualização completa dos dados do serviço.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @can('servico_edit')
                            <a href="{{ route('servicos.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="ri-pencil-line align-middle me-1"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('servicos.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO ═══ -->
                <div class="card-body p-4">

                    <!-- Informações Básicas -->
                    <div class="row g-4">
                        <div class="col-md-2 col-4 text-center">
                            <img class="rounded-circle border bg-light shadow-sm"
                                 src="{{ $item->img }}"
                                 style="width:80px;height:80px;object-fit:cover;">
                        </div>
                        <div class="col-md-4 col-8">
                            <div class="detail-label">Nome do Serviço</div>
                            <p class="detail-value mb-1">{{ $item->nome }}</p>
                            @if($item->categoria)
                            <span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->categoria->nome }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="detail-label">Código</div>
                            <p class="detail-value mb-0">#{{ $item->numero_sequencial }}</p>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="detail-label">Status</div>
                            <p class="mb-0">
                                @if($item->status)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                    <i class="ri-check-line me-1"></i>Ativo
                                </span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                    <i class="ri-close-line me-1"></i>Inativo
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="detail-label">Cód. Serviço</div>
                            <p class="detail-value mb-0">{{ $item->codigo_servico ?? '-' }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Preços e Duração -->
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="detail-label">Valor de Venda</div>
                            <p class="detail-value mb-0" style="color:#2e7d32;">R$ {{ __moeda($item->valor) }}</p>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Duração</div>
                            <p class="detail-value mb-0">{{ $item->tempo_servico }} {{ $item->unidade_cobranca }}</p>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Comissão</div>
                            <p class="detail-value mb-0">R$ {{ __moeda($item->comissao) }}</p>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Tempo Adicional</div>
                            <p class="detail-value mb-0">{{ $item->tempo_adicional ?? '0' }} min</p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Valor Adicional</div>
                            <p class="detail-value mb-0">R$ {{ __moeda($item->valor_adicional) }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Integrações -->
                    <div class="row g-3">
                        @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                        <div class="col-md-3">
                            <div class="detail-label">Usar em Reservas</div>
                            <p class="mb-0">
                                @if($item->reserva)
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Sim</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">Não</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Padrão Reserva NFSe</div>
                            <p class="mb-0">
                                @if($item->padrao_reserva_nfse)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">Não</span>
                                @endif
                            </p>
                        </div>
                        @endif
                        @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                        <div class="col-md-3">
                            <div class="detail-label">Marketplace</div>
                            <p class="mb-0">
                                @if($item->marketplace)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">Ativo</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">Inativo</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Destaque Marketplace</div>
                            <p class="mb-0">
                                @if($item->destaque_marketplace)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">Não</span>
                                @endif
                            </p>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <div class="detail-label">Tempo Tolerância</div>
                            <p class="detail-value mb-0">{{ $item->tempo_tolerancia ?? '0' }} min</p>
                        </div>
                    </div>

                    @if($item->descricao)
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label mb-2">Descrição do Serviço</div>
                            <p class="mb-0 text-dark">{{ $item->descricao }}</p>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4">

                    <!-- Tributação -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-dark fw-semibold mb-3">
                                <i class="ri-scales-3-line text-primary me-2"></i>
                                Alíquotas Tributárias
                            </h6>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">ISS</div>
                            <p class="detail-value mb-0">{{ $item->aliquota_iss ?? '0' }}%</p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">PIS</div>
                            <p class="detail-value mb-0">{{ $item->aliquota_pis ?? '0' }}%</p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">COFINS</div>
                            <p class="detail-value mb-0">{{ $item->aliquota_cofins ?? '0' }}%</p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">INSS</div>
                            <p class="detail-value mb-0">{{ $item->aliquota_inss ?? '0' }}%</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Datas -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="detail-label">Cód. Tributação Municipal</div>
                            <p class="detail-value mb-0">{{ $item->codigo_tributacao_municipio ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Data de Cadastro</div>
                            <p class="detail-value mb-0">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Última Atualização</div>
                            <p class="detail-value mb-0">{{ $item->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
