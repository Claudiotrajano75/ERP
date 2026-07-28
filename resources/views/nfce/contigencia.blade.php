@extends('layouts.app', ['title' => 'Contingência NFCe'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #1a0000 0%, #7f1d1d 50%, #450a0a 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #fca5a5; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #fee2e2; border-radius: 12px; overflow: hidden; }
/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #fee2e2; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #fff5f5; color: #991b1b; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #fee2e2; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #fff0f0; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #fff5f5; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
/* ─── Empty State ─── */
.modulo-empty { padding: 52px 20px; text-align: center; }
.modulo-empty i { font-size: 52px; color: #fca5a5; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.modulo-empty .badge-ok { display: inline-flex; align-items: center; gap: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 50px; margin-top: 12px; }
/* ─── Chave truncada ─── */
.chave-cell { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: monospace; }
/* ─── Alerta de contingência ─── */
.alert-contigencia { background: linear-gradient(135deg, rgba(239,68,68,0.08) 0%, rgba(220,38,38,0.04) 100%); border: 1px solid rgba(239,68,68,0.25); border-radius: 12px; padding: 14px 18px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
.alert-contigencia i { font-size: 22px; color: #dc2626; flex-shrink: 0; margin-top: 1px; }
.alert-contigencia .alert-body strong { font-size: 13px; font-weight: 700; color: #991b1b; display: block; margin-bottom: 2px; }
.alert-contigencia .alert-body span { font-size: 12px; color: #7f1d1d; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO — tema vermelho para destacar criticidade -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-alert-line"></i>
                            Contingência NFCe
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">NFCe emitidas em modo de contingência aguardando transmissão ao SEFAZ.</p>
                    </div>
                    @if($data->count() > 0)
                    <div>
                        <span style="background:rgba(255,255,255,0.15);border-radius:8px;padding:6px 14px;color:#fff;font-size:13px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                            <i class="ri-error-warning-line"></i>
                            {{ $data->count() }} pendente(s)
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">

                @if($data->count() > 0)
                <!-- Alerta informativo -->
                <div class="alert-contigencia">
                    <i class="ri-error-warning-fill"></i>
                    <div class="alert-body">
                        <strong>Atenção — Notas em Contingência</strong>
                        <span>As notas abaixo foram emitidas offline e precisam ser transmitidas ao SEFAZ. Transmita o quanto antes para garantir a validade fiscal.</span>
                    </div>
                </div>
                @endif

                <!-- Tabela -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Número</th>
                                    <th>Chave de Acesso</th>
                                    <th>Valor (R$)</th>
                                    <th>Data</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item->cliente ? $item->cliente->info : 'Consumidor Final' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11 fw-bold">
                                            {{ $item->numero ?: '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-11 chave-cell" title="{{ $item->chave }}">
                                            {{ $item->chave }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                    <td class="fs-12 text-muted">{{ __data_pt($item->created_at) }}</td>
                                    <td class="text-end">
                                        @if($item->reenvio_contigencia == 0 && $item->contigencia)
                                        <button title="Transmitir NFCe ao SEFAZ" type="button"
                                            class="btn btn-danger btn-sm text-white"
                                            onclick="transmitirContigencia('{{ $item->id }}')">
                                            <i class="ri-send-plane-fill me-1"></i> Transmitir
                                        </button>
                                        @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-line me-1"></i> Enviado
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-check-double-line" style="color:#4ade80;"></i>
                                            <p>Nenhuma nota em contingência encontrada.</p>
                                            <span class="badge-ok">
                                                <i class="ri-shield-check-line"></i>
                                                Tudo transmitido — SEFAZ em dia!
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/nfce_transmitir.js"></script>
@endsection
