@extends('layouts.app', ['title' => 'Notas de Serviço (NFSe)'])

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

/* ─── Action Group ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Status Badges ─── */
.badge-status { padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">
            
            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-paper-2-line"></i>
                            Notas de Serviço (NFSe)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as emissões de notas fiscais de serviço.</p>
                    </div>
                    <div class="d-inline-flex align-items-center gap-2">
                        @can('nfse_create')
                        <a href="{{ route('nota-servico.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-fill align-middle me-1"></i> Nova NFSe
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI Cards — Padrão Inline Sólido ═══ -->
                <div class="row g-3 mb-4">
                    <!-- Card: Total -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(59,130,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-file-paper-2-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Nesta Página</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Aprovadas -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(16,185,129,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-check-double-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Aprovadas (pág.)</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->where('estado', 'aprovado')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Canceladas / Rejeitadas -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(239,68,68,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-close-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Canc / Rej (pág.)</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->whereIn('estado', ['cancelado', 'rejeitado'])->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Total R$ -->
                    <div class="col-md-3 col-6">
                        <div style="background:linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(139,92,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-money-dollar-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div style="overflow:hidden;">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Total (pág.)</div>
                                <div style="font-size:18px;font-weight:800;letter-spacing:-0.5px;white-space:nowrap;">R$ {{ __moeda($data->sum('valor_total')) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-6">
                            {!!Form::text('tomador', 'Tomador')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data inicial')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data final')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado',
                            ['' => 'Todos',
                            'novo' => 'Novas',
                            'rejeitado' => 'Rejeitadas',
                            'cancelado' => 'Canceladas',
                            'aprovado' => 'Aprovadas',
                            'processando' => 'Processando'])
                            ->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-light btn-sm text-danger border-danger-subtle" href="{{ route('nota-servico.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="modulo-table-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Tomador</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Número</th>
                                    <th>Valor (R$)</th>
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    <th>Data</th>
                                    <th>Chave de Acesso</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->razao_social }}</td>
                                    <td class="text-nowrap">{{ $item->documento }}</td>
                                    
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold">
                                            {{ $item->numero_nfse ?: '--' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->valor_total) }}</td>
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge badge-status bg-success">Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge badge-status bg-danger">Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge badge-status bg-warning">Rejeitado</span>
                                        @else
                                        <span class="badge badge-status bg-info">Novo</span>
                                        @endif
                                    </td>
                                    <td class="fs-12 text-muted">
                                        {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                    </td>
                                    <td class="fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td class="fs-11 text-muted" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->chave }}">
                                        {{ $item->chave }}
                                    </td>
                                    
                                    <td class="text-end">
                                        <form action="{{ route('nota-servico.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @if($item->estado == 'aprovado')
                                                <a class="btn btn-dark btn-sm text-white" title="Imprimir NFSe" target="_blank" href="{{ route('nota-servico.imprimir', [$item->id]) }}">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                <button title="Cancelar NFSe" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                @else
                                                <a title="Visualizar PDF Temporário" class="btn btn-dark btn-sm text-white" href="{{ route('nota-servico.preview', [$item->id]) }}">
                                                    <i class="ri-file-ppt-line"></i>
                                                </a>
                                                @endif
                                                
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('nfse_edit')
                                                    <a class="btn btn-warning btn-sm text-white" title="Editar" href="{{ route('nota-servico.edit', $item->id) }}">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    @endcan
                                                    
                                                    @can('nfse_delete')
                                                    <button type="button" title="Excluir" class="btn btn-danger btn-sm text-white btn-delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                    
                                                    <button title="Transmitir NFSe" type="button" class="btn btn-success btn-sm text-white" onclick="transmitir('{{$item->id}}')">
                                                        <i class="ri-send-plane-fill"></i>
                                                    </button>
                                                @endif

                                                <button title="Consultar NFSe" type="button" class="btn btn-light btn-sm" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-search-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="modulo-empty">
                                            <i class="ri-file-paper-2-line"></i>
                                            <p>Nenhuma NFSe encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if(sizeof($data) > 0)
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold text-muted">Total (página)</td>
                                    <td class="fw-bold text-success fs-14">R$ {{ __moeda($data->sum('valor_total')) }}</td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modulo-header-gradient">
                <h5 class="modal-title text-white" id="modalCancelarLabel">
                    <i class="ri-error-warning-fill me-2 text-warning"></i>
                    Cancelar NFSe <strong class="ref-numero"></strong>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        {!!Form::text('motivo-cancela', 'Motivo do Cancelamento')->required()!!}
                    </div>
                </div>
                <div class="alert alert-warning mt-3 mb-0 d-flex gap-2 align-items-center">
                    <i class="ri-information-line fs-20"></i>
                    <span class="fs-12">Esta operação é irreversível. O cancelamento será registrado na prefeitura.</span>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger px-4">
                    <i class="ri-close-circle-fill me-1"></i> Confirmar Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="/js/nfse_transmitir.js"></script>
@endsection
