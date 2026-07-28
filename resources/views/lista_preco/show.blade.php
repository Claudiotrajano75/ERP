@extends('layouts.app', ['title' => 'Produtos da Lista'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Detail Card ─── */
.modulo-detail-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-detail-card .card-body { background: #fff; }

/* ─── KPI Info Cards ─── */
.modulo-info-card { border: 1px solid #eef0f5 !important; border-radius: 12px; overflow: hidden; transition: all 0.2s ease; background: #fff; padding: 16px; }
.modulo-info-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
.modulo-info-card .info-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #9e9eb8; margin-bottom: 6px; }
.modulo-info-card .info-value { font-size: 14px; font-weight: 700; }

/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Action Buttons ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-lg-12">

            <!-- ═══ CABEÇALHO PREMIUM — RESUMO DA LISTA ═══ -->
            <div class="card border-0 shadow-sm modulo-detail-card mb-4">
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-price-tag-3-line"></i>
                                Tabela: <strong class="text-white ms-1">{{ $item->nome }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualize os preços gerados automaticamente e altere valores pontuais de cada produto nesta tabela.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('lista-preco.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="modulo-info-card shadow-sm">
                                <div class="info-label">Ajuste Baseado Em</div>
                                <div class="info-value">
                                    <span class="badge bg-light text-dark border fs-12 fw-bold px-3 py-2">
                                        {{ $item->ajuste_sobre == 'valor_venda' ? 'Valor de venda' : 'Valor de compra' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="modulo-info-card shadow-sm">
                                <div class="info-label">Tipo de Ajuste</div>
                                <div class="info-value">
                                    @if($item->tipo == 'incremento')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-12 fw-bold px-3 py-2">
                                        <i class="ri-arrow-up-line me-1"></i>Incremento (+)
                                    </span>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-12 fw-bold px-3 py-2">
                                        <i class="ri-arrow-down-line me-1"></i>Redução (-)
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="modulo-info-card shadow-sm">
                                <div class="info-label">Fator de Reajuste</div>
                                <div class="info-value">
                                    <span class="fs-22 fw-bold text-primary">{{ $item->percentual_alteracao }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="modulo-info-card shadow-sm">
                                <div class="info-label">Meio de Pagamento</div>
                                <div class="info-value">
                                    @if($item->tipo_pagamento)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle fs-12 fw-bold px-3 py-2">
                                        <i class="ri-bank-card-line me-1"></i>{{ $item->getTipoPagamento() }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-12 fw-bold px-3 py-2">Qualquer</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TABELA DE PRODUTOS ═══ -->
            <div class="card border-0 shadow-sm modulo-detail-card">
                <div class="card-body p-4">

                    <!-- Filtros Glass -->
                    <div class="modulo-glass-filter p-3 mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6 col-12">
                                {!!Form::text('nome', 'Pesquisar produto por nome')!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <button class="btn btn-primary btn-sm w-100" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                            </div>
                            <div class="col-md-3 col-6">
                                <a class="btn btn-danger btn-sm w-100" href="{{ route('lista-preco.show', [$item->id]) }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- Tabela Premium -->
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Produto</th>
                                        <th>Preço Normal</th>
                                        <th>Preço na Lista</th>
                                        <th class="text-end" style="width: 100px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($produtos as $p)
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $p->produto->nome }}</td>
                                        <td class="text-muted">R$ <span class="fw-medium">{{ __moeda($p->produto->valor_unitario) }}</span></td>
                                        <td class="fw-bold text-success fs-14">R$ {{ __moeda($p->valor) }}</td>
                                        <td class="text-end">
                                            <div class="modulo-action-group">
                                                <button type="button"
                                                    onclick="editValor('{{ $p->id }}', '{{ $p->produto->nome }}', '{{ $p->valor }}')"
                                                    class="btn btn-warning btn-sm text-white" title="Alterar valor manualmente">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="modulo-empty">
                                                <i class="ri-inbox-2-line"></i>
                                                <p>Nenhum produto correspondente na lista.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex align-items-center justify-content-end mt-3">
                        {!! $produtos->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@include('modals._editar_valor_lista')

@endsection

@section('js')
<script type="text/javascript">
    function editValor(id, nome, valor){
        $('#editar_valor_lista').modal('show');
        $('#produto-nome').text(nome);
        $('#item_id').val(id);
        $('#inp-valor').val(convertFloatToMoeda(valor));
    }
</script>
@endsection
