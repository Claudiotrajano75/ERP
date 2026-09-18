@extends('layouts.app', ['title' => 'Arquivos XML NFe Entrada (Compras)'])

@section('css')
<style>
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; bottom: 0;
    width: 4px;
}
.stat-indigo::before  { background: linear-gradient(180deg, #4f46e5, #818cf8); }
.stat-cyan::before    { background: linear-gradient(180deg, #0891b2, #38bdf8); }
.stat-emerald::before { background: linear-gradient(180deg, #059669, #34d399); }

.stat-indigo .stat-icon  { background: #eef2ff; color: #4f46e5; }
.stat-cyan .stat-icon    { background: #ecfeff; color: #0891b2; }
.stat-emerald .stat-icon { background: #ecfdf5; color: #059669; }

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
}
.stat-value {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar do Fornecedor ─── */
.forn-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-zip-line"></i>
                                Arquivos XML NFe Entrada (Compras)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Consulte, baixe em lote e envie ao contador os arquivos XML das notas fiscais de compra do período.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('compras.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-shopping-cart-line"></i> Compras / Entradas
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-12">
                            <div class="stat-card stat-indigo">
                                <div>
                                    <div class="stat-label">Total no Sistema</div>
                                    <div class="stat-value mt-1">{{ $stats['total_sistema'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-file-code-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-cyan">
                                <div>
                                    <div class="stat-label">Notas no Período</div>
                                    <div class="stat-value mt-1">{{ $stats['total_periodo'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-filter-3-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="stat-card stat-emerald">
                                <div>
                                    <div class="stat-label">Valor Total Filtrado</div>
                                    <div class="stat-value mt-1">R$ {{ __moeda($stats['valor_total']) }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTRO DE BUSCA LIMPO ═══ -->
                    <div class="modulo-glass-filter-premium mb-4">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Arquivos XML por Período
                            </h5>
                        </div>

                        <form method="get" action="{{ route('nfe-entrada-xml.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" required>
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" required>
                                </div>
                                <div class="col-md-4 col-12 d-flex gap-2">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar Arquivos
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('nfe-entrada-xml.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Fornecedor / Emitente</th>
                                        <th>Número da NFe</th>
                                        <th>Chave de Acesso</th>
                                        <th>Status do XML</th>
                                        <th class="text-end">Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_geral = 0; @endphp
                                    @forelse($data as $item)
                                    @php $xmlExiste = file_exists(public_path("xml_nfe/").$item->chave.".xml"); @endphp
                                    @if($xmlExiste)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="forn-avatar">
                                                    <i class="ri-building-line"></i>
                                                </div>
                                                <span class="fw-bold text-dark fs-13">{{ $item->cliente ? $item->cliente->info : '--' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-bold fs-12">Nº {{ $item->numero }}</span>
                                        </td>
                                        <td>
                                            <code class="text-muted fs-11" title="{{ $item->chave }}">{{ substr($item->chave, 0, 16) }}...{{ substr($item->chave, -8) }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                <i class="ri-checkbox-circle-line me-1"></i> XML Disponível
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-success fs-13">R$ {{ __moeda($item->total) }}</td>
                                    </tr>
                                    @php $total_geral += $item->total; @endphp
                                    @endif
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="modulo-empty">
                                                <i class="ri-file-zip-line"></i>
                                                <p>Informe as datas inicial e final para listar os arquivos XML de notas de entrada.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($total_geral > 0)
                                <tfoot>
                                    <tr class="fw-bold fs-14 table-light border-top">
                                        <td colspan="4" class="text-dark">Total das Notas Localizadas no Período:</td>
                                        <td class="text-end text-success fs-15">R$ {{ __moeda($total_geral) }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- ═══ AÇÕES DE LOTE / RODAPÉ ═══ -->
                    @if(sizeof($data) > 0)
                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <form method="get" action="{{ route('nfe-entrada-xml.download') }}" class="m-0">
                                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                                    <button class="dash-btn dash-btn-primary px-4">
                                        <i class="ri-file-zip-line me-1"></i> Baixar ZIP com Arquivos XML
                                    </button>
                                </form>
                            </div>
                            @if($escritorio != null && $escritorio->email)
                            <div>
                                <form method="get" action="{{ route('nfe-entrada-xml.envio-contador') }}" class="m-0">
                                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                                    <input type="hidden" name="estado" value="{{ request()->estado }}">
                                    <input type="hidden" name="local_id" value="{{ request()->local_id }}">
                                    <button class="dash-btn dash-btn-light px-4" style="border-color: #bbf7d0; background: #f0fdf4; color: #16a34a;">
                                        <i class="ri-mail-send-line me-1"></i> Enviar XMLs ao Contador ({{ $escritorio->email }})
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning border-0 shadow-sm p-3 mb-0 text-center d-flex align-items-center justify-content-center" style="background: #fffbeb; border-radius: 12px;">
                        <i class="ri-information-line me-2 fs-18 text-warning"></i>
                        <span class="text-warning-emphasis fs-13">Filtre por um período válido para habilitar as opções de download em ZIP e envio ao contador.</span>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
