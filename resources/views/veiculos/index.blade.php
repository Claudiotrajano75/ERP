@extends('layouts.app', ['title' => 'Veículos'])

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
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Action Group ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Status Badges ─── */
.badge-status { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }

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
                            <i class="ri-car-line"></i>
                            Veículos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie os veículos cadastrados na plataforma.</p>
                    </div>
                    <div class="d-inline-flex align-items-center gap-2">
                        @can('veiculos_create')
                        <a href="{{ route('veiculos.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-fill align-middle me-1"></i> Novo Veículo
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI Cards — Padrão Inline Sólido ═══ -->
                <div class="row g-3 mb-4">
                    <!-- Card: Cadastrados -->
                    <div class="col-md-4 col-12">
                        <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(59,130,246,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-car-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Total Cadastrados</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Ativos -->
                    <div class="col-md-4 col-6">
                        <div style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(16,185,129,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-checkbox-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Ativos (pág.)</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->where('status', 1)->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Card: Inativos -->
                    <div class="col-md-4 col-6">
                        <div style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);border-radius:12px;padding:20px 18px;color:#fff;display:flex;align-items:center;gap:16px;box-shadow:0 4px 20px rgba(239,68,68,0.25);">
                            <div style="background:rgba(255,255,255,0.18);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-close-circle-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;opacity:0.7;margin-bottom:4px;">Inativos (pág.)</div>
                                <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">{{ $data->where('status', 0)->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::text('placa', 'Pesquisar por Placa')!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-light btn-sm text-danger border-danger-subtle" href="{{ route('veiculos.index') }}" title="Limpar Filtros">
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
                                    <th>Placa</th>
                                    <th>Modelo</th>
                                    <th>Renavam</th>
                                    <th>Proprietário</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Status</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12 fw-bold font-monospace">
                                            {{ $item->placa }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ $item->modelo ?: '--' }}</td>
                                    <td>{{ $item->renavam ?: '--' }}</td>
                                    <td>{{ $item->proprietario_nome ?: '--' }}</td>
                                    <td class="text-nowrap">{{ $item->proprietario_documento ?: '--' }}</td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge badge-status bg-success-subtle text-success border border-success-subtle">
                                            <i class="ri-checkbox-circle-fill"></i> Ativo
                                        </span>
                                        @else
                                        <span class="badge badge-status bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="ri-close-circle-fill"></i> Inativo
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('veiculos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('veiculos_edit')
                                                <a class="btn btn-warning btn-sm text-white" title="Editar Veículo" href="{{ route('veiculos.edit', [$item->id]) }}">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @endcan
                                                
                                                @can('veiculos_delete')
                                                <button type="button" title="Excluir Veículo" class="btn btn-danger btn-sm text-white btn-delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="modulo-empty">
                                            <i class="ri-car-line"></i>
                                            <p>Nenhum veículo encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
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
@endsection
