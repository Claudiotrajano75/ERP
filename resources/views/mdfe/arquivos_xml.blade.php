@extends('layouts.app', ['title' => 'Arquivos XML MDF-e'])

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
.modulo-glass-filter .form-control,
.modulo-glass-filter .form-select { height: 38px; }
.modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; white-space: nowrap; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-zip-line"></i>
                            Arquivos XML MDF-e
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie e baixe os arquivos XML emitidos e autorizados.
                        </p>
                    </div>
                    <div>
                        @if(count($data) > 0)
                        <form method="get" action="{{ route('mdfe-xml.download') }}" class="d-inline">
                            <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                            <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                            <button class="btn btn-light btn-sm text-dark px-3 fw-bold">
                                <i class="ri-file-zip-line align-middle me-1"></i> Baixar ZIP ({{ count($data) }})
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                @if(request()->start_date || request()->end_date)
                    {{-- ═══ KPI CARDS ═══ --}}
                    <div class="row g-3 mb-4">
                        <!-- Total MDF-e -->
                        <div class="col-md-3 col-6">
                            <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(59,130,246,0.25);">
                                <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-file-list-3-line" style="font-size:22px;color:#fff;"></i>
                                </div>
                                <div>
                                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Total MDF-e</div>
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
                @endif

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
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('mdfe-xml.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ TABELA PREMIUM ═══ --}}
                @if(count($data) > 0)
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>CNPJ do contratante</th>
                                        <th>Início da viagem</th>
                                        <th>Número</th>
                                        <th>Chave</th>
                                        <th>Valor da carga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        @if(file_exists(public_path("xml_mdfe/").$item->chave.".xml"))
                                            <tr>
                                                <td class="fw-semibold">{{ $item->cnpj_contratante }}</td>
                                                <td class="text-muted fs-12">{{ __data_pt($item->data_inicio_viagem, 0) }}</td>
                                                <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1 fs-11">{{ $item->mdfe_numero }}</span></td>
                                                <td class="fs-12 text-muted">{{ $item->chave }}</td>
                                                <td class="fw-bold">R$ {{ __moeda($item->valor_carga) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold text-dark">
                                        <td colspan="4" class="text-end">Total no período:</td>
                                        <td>R$ {{ __moeda($data->sum('valor_carga')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="modulo-empty">
                        <i class="ri-inbox-2-line"></i>
                        @if(request()->start_date || request()->end_date)
                            <p>Nenhum XML encontrado para o período filtrado.</p>
                        @else
                            <p>Filtre por período para buscar os arquivos XML.</p>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
