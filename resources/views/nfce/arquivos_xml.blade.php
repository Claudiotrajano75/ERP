@extends('layouts.app', ['title' => 'Arquivos XML NFCe'])

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
/* ─── Glass Filter ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tfoot td { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 12px; padding: 12px 14px; border-top: 2px solid #e8eaf6 !important; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
/* ─── Chave Truncada ─── */
.chave-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
/* ─── Action Bar ─── */
.modulo-action-bar { margin-top: 20px; padding: 16px 20px; background: #f8f9fc; border-radius: 12px; border: 1px solid #eef0f5; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-action-bar .btn { border-radius: 8px; font-weight: 600; font-size: 13px; transition: all 0.2s ease; }
.modulo-action-bar .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
/* ─── Alert Info ─── */
.modulo-hint { background: rgba(255,193,7,0.08); border: 1px dashed rgba(255,193,7,0.4); border-radius: 10px; padding: 14px 18px; color: #856404; font-size: 13px; display: flex; align-items: center; gap: 10px; }
</style>
@endsection

@section('content')
@php $data = collect($data); @endphp
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-code-line"></i>
                            Arquivos XML — NFCe
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie, faça download e envie os arquivos XML das notas fiscais de consumidor eletrônica ao contador.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- KPI Cards -->
                <div class="row g-3 mb-4">
                    <!-- Card: XMLs Encontrados -->
                    <div class="col-md-4 col-6">
                        <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(59,130,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-file-code-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">XMLs Encontrados</div>
                                <div style="font-size:22px;font-weight:800;letter-spacing:-0.5px;">{{ $data->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Total R$ -->
                    <div class="col-md-4 col-6">
                        <div style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(16,185,129,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-money-dollar-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div style="overflow:hidden;">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Total (R$)</div>
                                <div style="font-size:20px;font-weight:800;letter-spacing:-0.5px;white-space:nowrap;">R$ {{ __moeda($data->sum('total')) }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Período -->
                    <div class="col-md-4 col-6">
                        <div style="background:linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(139,92,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-calendar-check-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Período Filtrado</div>
                                <div style="font-size:14px;font-weight:700;">
                                    {{ request()->start_date ? \Carbon\Carbon::parse(request()->start_date)->format('d/m/Y') : '--' }}
                                    &rarr;
                                    {{ request()->end_date ? \Carbon\Carbon::parse(request()->end_date)->format('d/m/Y') : '--' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado', ['aprovado' => 'Aprovado', 'cancelado' => 'Cancelado'])
                            ->attrs(['class' => 'form-select'])!!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif
                        <div class="col-md-2 col-12 ms-auto">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                @if($data->isNotEmpty())

                <!-- Tabela de XMLs -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Número</th>
                                    <th>Chave de Acesso</th>
                                    <th class="text-end">Valor (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                @if(file_exists(public_path("xml_nfce/").$item->chave.".xml"))
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item->cliente ? $item->cliente->info : 'Consumidor Final' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold">
                                            {{ $item->numero }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-11 font-monospace chave-cell" title="{{ $item->chave }}">
                                            {{ $item->chave }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="modulo-empty">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Nenhum arquivo XML encontrado para o período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold text-muted">Total Geral</td>
                                    <td class="text-end fw-bold text-success fs-14">R$ {{ __moeda($data->sum('total')) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Barra de Ações -->
                <div class="modulo-action-bar">
                    <div>
                        <span class="fw-semibold text-muted fs-13">
                            <i class="ri-information-line me-1 text-info"></i>
                            {{ $data->count() }} arquivo(s) XML disponíveis para exportação
                        </span>
                    </div>
                    <div class="d-inline-flex gap-2 flex-wrap">
                        <form method="get" action="{{ route('nfce-xml.download') }}">
                            <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                            <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                            <input type="hidden" name="estado" value="{{ request()->estado }}">
                            <input type="hidden" name="local_id" value="{{ request()->local_id }}">
                            <button class="btn btn-dark btn-sm px-4">
                                <i class="ri-file-zip-line me-1"></i> Download ZIP
                            </button>
                        </form>
                        @if($escritorio != null && $escritorio->email)
                        <form method="get" action="{{ route('nfce-xml.envio-contador') }}">
                            <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                            <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                            <input type="hidden" name="estado" value="{{ request()->estado }}">
                            <input type="hidden" name="local_id" value="{{ request()->local_id }}">
                            <button class="btn btn-success btn-sm px-4">
                                <i class="ri-mail-send-fill me-1"></i> Enviar ao Contador
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                @else
                <div class="modulo-hint">
                    <i class="ri-filter-3-line fs-20 flex-shrink-0"></i>
                    <span>Selecione um <strong>período</strong> e um <strong>estado</strong> nos filtros acima e clique em <strong>Pesquisar</strong> para visualizar os arquivos XML disponíveis.</span>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
