@extends('layouts.app', ['title' => 'Histórico de Alterações - Pré-venda'])

@section('css')
<style>
/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

.modulo-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; letter-spacing: 0.2px; }
.modulo-badge-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
.modulo-badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.modulo-badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.modulo-badge-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
.audit-diff { font-size: 11px; font-family: monospace; color: #475569; background: #f8f9fc; border-radius: 6px; padding: 4px 8px; display: inline-block; max-width: 260px; white-space: pre-wrap; word-break: break-all; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-history-line"></i>
                            Histórico de Alterações &mdash; Pré-venda #{{ $item->codigo }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Auditoria completa das alterações registradas com usuário, data e valores antes/depois.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('pre-venda.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ TABELA DE AUDITORIA ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th style="width:160px;">Data/Hora</th>
                                    <th style="width:160px;">Usuário</th>
                                    <th style="width:180px;">Operação</th>
                                    <th style="width:80px;">Item</th>
                                    <th>Valores Antes</th>
                                    <th>Valores Depois</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditorias as $auditoria)
                                <tr>
                                    <td class="text-muted fs-12">{{ \Carbon\Carbon::parse($auditoria->data_hora)->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $auditoria->usuario ? $auditoria->usuario->name : '--' }}</span>
                                    </td>
                                    <td>
                                        @php
                                        $classe = 'modulo-badge-info';
                                        if (strpos($auditoria->tipo_operacao, 'REMOVE') !== false) { $classe = 'modulo-badge-danger'; }
                                        elseif (strpos($auditoria->tipo_operacao, 'ADD') !== false) { $classe = 'modulo-badge-success'; }
                                        elseif (strpos($auditoria->tipo_operacao, 'UPDATE') !== false) { $classe = 'modulo-badge-warning'; }
                                        @endphp
                                        <span class="modulo-badge {{ $classe }}">
                                            @if(strpos($auditoria->tipo_operacao, 'REMOVE') !== false)
                                            <i class="ri-delete-bin-line"></i>
                                            @elseif(strpos($auditoria->tipo_operacao, 'ADD') !== false)
                                            <i class="ri-add-line"></i>
                                            @else
                                            <i class="ri-edit-line"></i>
                                            @endif
                                            {{ $auditoria->tipo_operacao }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $auditoria->item_id ?? '—' }}</td>
                                    <td>
                                        @if($auditoria->valores_antes)
                                            @php $antes = json_decode($auditoria->valores_antes, true); @endphp
                                            <span class="audit-diff">
                                                @foreach($antes ?? [] as $k => $v){{ $k }}: {{ is_array($v) ? json_encode($v) : $v }}&#10;@endforeach
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($auditoria->valores_depois)
                                            @php $depois = json_decode($auditoria->valores_depois, true); @endphp
                                            <span class="audit-diff">
                                                @foreach($depois ?? [] as $k => $v){{ $k }}: {{ is_array($v) ? json_encode($v) : $v }}&#10;@endforeach
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-history-line"></i>
                                            <p>Nenhuma alteração registrada nesta pré-venda.</p>
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
