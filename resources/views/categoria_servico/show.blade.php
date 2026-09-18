@extends('layouts.app', ['title' => 'Detalhes da Categoria de Serviço'])

@section('css')
<style>
/* ─── Cards de Detalhes ─── */
.detail-card { background: #ffffff; border: 1px solid #eef0f6; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
.detail-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; }
.detail-value { font-size: 15px; font-weight: 700; color: #1f2937; margin-bottom: 0; }
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
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
                            Detalhes da Categoria: {{ $item->nome }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Visualização completa dos dados da categoria de serviço #{{ $item->id }}.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('categoria-servico.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                        @can('categoria_servico_edit')
                        <a href="{{ route('categoria-servico.edit', $item->id) }}" class="dash-btn dash-btn-primary">
                            <i class="ri-pencil-line"></i> Editar Categoria
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- ═══ CORPO ═══ -->
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6 col-12">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-folder-line me-1"></i> Nome da Categoria</div>
                            <p class="detail-value fs-18 text-primary">{{ $item->nome }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-store-2-line me-1"></i> Visibilidade Marketplace</div>
                            <p class="detail-value mt-1">
                                @if($item->marketplace)
                                <span class="pill pill-ok">
                                    <i class="ri-check-line"></i> Ativo / Visível
                                </span>
                                @else
                                <span class="pill pill-no">
                                    <i class="ri-close-line"></i> Inativo / Oculto
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="detail-card h-100">
                            <div class="detail-label"><i class="ri-service-line me-1"></i> Serviços Vinculados</div>
                            <p class="detail-value fs-18 text-dark">{{ $item->servicos()->count() }} serviços</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-md-6 col-12">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-calendar-line me-1"></i> Data de Cadastro</div>
                            <p class="detail-value">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="detail-card">
                            <div class="detail-label"><i class="ri-history-line me-1"></i> Última Atualização</div>
                            <p class="detail-value">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

