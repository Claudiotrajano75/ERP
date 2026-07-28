@extends('layouts.app', ['title' => 'Consultar Produto por Código Único'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

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
.modulo-table-wrap tbody tr.clickable { cursor: pointer; }

/* ─── Action Buttons — SEMPRE lado a lado (flex-wrap: nowrap é obrigatório) ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── KPI Cards Premium ─── */
.modulo-kpi-card { border: none !important; border-radius: 12px; overflow: hidden; transition: all 0.25s ease; position: relative; }
.modulo-kpi-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
.modulo-kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important; }
.modulo-kpi-card .kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.modulo-kpi-card .kpi-value { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; }
.modulo-kpi-card .kpi-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; }
.modulo-kpi-blue::before  { background: linear-gradient(90deg, #4facfe, #00f2fe); }
.modulo-kpi-green::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }
.modulo-kpi-orange::before { background: linear-gradient(90deg, #fa709a, #fee140); }
.modulo-kpi-purple::before { background: linear-gradient(90deg, #a18cd1, #fbc2eb); }

/* ─── Form Card (Create/Edit) ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Botões de Ação do Formulário ─── */
.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer da Tabela ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
    .modulo-kpi-card .kpi-value { font-size: 18px; }
}

/* ─── Badge de código único ─── */
.codigo-unico-badge {
    font-family: 'SF Mono', 'Consolas', 'Courier New', monospace;
    font-size: 12px;
    letter-spacing: 0.3px;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border: 1px solid #c7d2fe;
    border-radius: 6px;
    padding: 4px 10px;
    color: #4338ca;
    font-weight: 600;
    display: inline-block;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-search-eye-line"></i>
                            Consultar Código Único
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Consulte a validade, lote ou código individual único gerado para cada unidade de produto.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ FILTROS GLASS ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5 col-12">
                            {!!Form::select('produto_id', 'Pesquisar por produto')
                            ->options($produto != null ? [$produto->id => $produto->nome] : [])
                            !!}
                        </div>
                        <div class="col-md-4 col-12">
                            {!!Form::text('codigo', 'Pesquisar por código')!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('produto-consulta-codigo.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Código Único</th>
                                    <th>Observação</th>
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->produto->nome }}</span>
                                    </td>
                                    <td>
                                        <span class="codigo-unico-badge">{{ $item->codigo }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-13">{{ $item->observacao ?? '--' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="modulo-action-group">
                                            <button type="button" class="btn btn-dark btn-sm" title="Ver Detalhes do Código" onclick="modalData('{{$item->id}}')">
                                                <i class="ri-file-list-3-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum registro encontrado. Realize uma busca acima usando produto ou código.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER ═══ -->
                @if(count($data) > 0)
                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de códigos:</span>
                        <span class="modulo-total-value">{{ count($data) }}</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@include('modals._dados_produto_unico', ['not_submit' => true])

@endsection

@section('js')
<script src="/js/produto_unico.js"></script>
@endsection