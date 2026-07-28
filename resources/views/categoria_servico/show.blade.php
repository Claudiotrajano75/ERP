@extends('layouts.app', ['title' => 'Detalhes da Categoria de Serviço'])

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
                                Detalhes da Categoria #{{ $item->id }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualização completa dos dados da categoria de serviço.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @can('categoria_servico_edit')
                            <a href="{{ route('categoria-servico.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="ri-pencil-line align-middle me-1"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('categoria-servico.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO ═══ -->
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-label">Nome da Categoria</div>
                            <p class="detail-value mb-0">{{ $item->nome }}</p>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Marketplace</div>
                            <p class="mb-0">
                                @if($item->marketplace)
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
                        @if($item->imagem)
                        <div class="col-md-3">
                            <div class="detail-label">Imagem</div>
                            <img src="{{ $item->img }}" alt="{{ $item->nome }}"
                                 class="img-thumbnail rounded" style="max-width: 120px; max-height: 120px;">
                        </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="detail-label">Total de Serviços</div>
                            <p class="detail-value mb-0">{{ $item->servicos()->count() }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Data de Cadastro</div>
                            <p class="detail-value mb-0">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Última Atualização</div>
                            <p class="detail-value mb-0">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
