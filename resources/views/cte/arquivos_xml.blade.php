@extends('layouts.app', ['title' => 'Arquivos XML CTe'])

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
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; white-space: nowrap; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-table-wrap tfoot td { background: #f8f9fc; font-weight: 700; font-size: 13px; padding: 10px 14px; border-top: 2px solid #e8eaf6; }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }

/* ─── KPI Cards (Widget Icon Box) ─── */
.widget-icon-box {
    border-radius: 12px !important;
    overflow: hidden;
}
.widget-icon-box .card-body {
    padding: 1rem 1.25rem !important;
}
.widget-icon-box .avatar-title {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.widget-icon-box-avatar {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

/* Fundos sólidos para KPI Cards */
.widget-icon-box.bg-info    { background: linear-gradient(135deg, #299bf6 0%, #1a7ac8 100%) !important; }
.widget-icon-box.bg-success { background: linear-gradient(135deg, #17a497 0%, #0d8b7a 100%) !important; }
.widget-icon-box.bg-danger  { background: linear-gradient(135deg, #f7473a 0%, #d42f22 100%) !important; }
.widget-icon-box.bg-warning { background: linear-gradient(135deg, #f39c12 0%, #d4880f 100%) !important; color: #fff !important; }
.widget-icon-box .text-white-50  { color: rgba(255,255,255,0.65) !important; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }

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

                {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-zip-line"></i>
                                Arquivos XML CTe
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie e faça download dos arquivos XML dos Conhecimentos de Transporte aprovados.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('cte.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- ═══ KPI CARDS ═══ --}}
                    <div class="row g-3 mb-4">
                        <!-- Total CTe -->
                        <div class="col-md-3 col-6">
                            <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(59,130,246,0.25);">
                                <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-file-list-3-line" style="font-size:22px;color:#fff;"></i>
                                </div>
                                <div>
                                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Total CTe</div>
                                    <div style="font-size:22px;font-weight:800;letter-spacing:-0.5px;">{{ $stats['total'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Com XML -->
                        <div class="col-md-3 col-6">
                            <div style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(16,185,129,0.25);">
                                <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-checkbox-circle-line" style="font-size:22px;color:#fff;"></i>
                                </div>
                                <div>
                                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Com XML</div>
                                    <div style="font-size:22px;font-weight:800;letter-spacing:-0.5px;">{{ $stats['com_xml'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sem XML -->
                        <div class="col-md-3 col-6">
                            <div style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(239,68,68,0.25);">
                                <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-close-circle-line" style="font-size:22px;color:#fff;"></i>
                                </div>
                                <div>
                                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Sem XML</div>
                                    <div style="font-size:22px;font-weight:800;letter-spacing:-0.5px;">{{ $stats['sem_xml'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Valor Total -->
                        <div class="col-md-3 col-6">
                            <div style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(245,158,11,0.25);">
                                <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-money-dollar-circle-line" style="font-size:22px;color:#fff;"></i>
                                </div>
                                <div style="overflow:hidden;">
                                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Valor Total</div>
                                    <div style="font-size:18px;font-weight:800;letter-spacing:-0.5px;white-space:nowrap;">R$ {{ __moeda($stats['valor']) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══ FILTROS GLASS ═══ --}}
                    <div class="modulo-glass-filter p-3 mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3 col-6">
                                {!!Form::date('start_date', 'Data início')!!}
                            </div>
                            <div class="col-md-3 col-6">
                                {!!Form::date('end_date', 'Data fim')!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <button class="btn btn-primary btn-sm w-100" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                            </div>
                            <div class="col-md-2 col-6">
                                <a class="btn btn-danger btn-sm w-100" href="{{ route('cte-xml.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>


                    {{-- ═══ TABELA PREMIUM ═══ --}}
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Remetente</th>
                                        <th>Destinatário</th>
                                        <th>Número</th>
                                        <th>Chave</th>
                                        <th>Valor da Carga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    @if(file_exists(public_path("xml_cte/").$item->chave.".xml"))
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark d-block">{{ $item->remetente ? $item->remetente->info : '--' }}</span>
                                        </td>
                                        <td>{{ $item->destinatario ? $item->destinatario->info : '--' }}</td>
                                        <td><span class="fw-semibold">{{ $item->numero }}</span></td>
                                        <td class="fs-11 text-muted">{{ $item->chave }}</td>
                                        <td class="fw-semibold text-success">R$ {{ __moeda($item->valor_carga) }}</td>
                                    </tr>
                                    @endif
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="modulo-empty">
                                                <i class="ri-folder-open-line"></i>
                                                <p>{{ request()->start_date || request()->end_date ? 'Nenhum arquivo XML encontrado no período.' : 'Filtre por período para buscar os arquivos.' }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($data->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end">Total do período</td>
                                        <td class="text-success fw-bold">R$ {{ __moeda($data->sum('valor_carga')) }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{-- ═══ DOWNLOAD ZIP ═══ --}}
                    @if($data->count() > 0)
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <form method="get" action="{{ route('cte-xml.download') }}">
                            <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                            <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                            <button class="btn btn-dark btn-sm">
                                <i class="ri-file-zip-line me-1"></i> Download Zip ({{ $stats['com_xml'] }} arquivo{{ $stats['com_xml'] != 1 ? 's' : '' }})
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- ═══ FOOTER (Paginação) ═══ --}}
                    <div class="modulo-footer">
                        <div>
                            @if($data->total() > 0)
                            <span class="modulo-total-label">
                                Total de registros: <span class="modulo-total-value">{{ $data->total() }}</span>
                            </span>
                            @endif
                        </div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
