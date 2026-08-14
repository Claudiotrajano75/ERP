@extends('layouts.app', ['title' => 'Histórico de Alterações - Pré-venda'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

.modulo-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.2px; }
.modulo-badge-info { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #0d47a1; }
.modulo-badge-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.modulo-badge-danger { background: linear-gradient(135deg, #ffebee, #ffcdd2); color: #b71c1c; }
.modulo-badge-warning { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }

.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.audit-diff { font-size: 11px; font-family: monospace; color: #475569; background: #f8f9fc; border-radius: 6px; padding: 4px 8px; display: inline-block; max-width: 260px; white-space: pre-wrap; word-break: break-all; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-history-line"></i>
                            Histórico de Alterações
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Auditoria da pré-venda #{{ $item->codigo }} — todas as alterações registradas com usuário, data e valores antes/depois.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('pre-venda.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ Tabela de Auditoria ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:150px;">Data/Hora</th>
                                    <th style="width:160px;">Usuário</th>
                                    <th style="width:180px;">Operação</th>
                                    <th style="width:70px;">Item</th>
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
