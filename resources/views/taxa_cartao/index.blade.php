@extends('layouts.app', ['title' => 'Taxas de Cartão'])

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

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Botões de Ação do Grid ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: wrap; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-percent-line"></i> 
                            Taxas de Cartão
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Configure as taxas de pagamento de cartão e suas bandeiras para cálculo correto dos faturamentos.</p>
                    </div>
                    <div>
                        @can('taxa_pagamento_create')
                        <a href="{{ route('taxa-cartao.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Taxa
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Tipo de Pagamento</th>
                                    <th>Bandeira</th>
                                    <th>Taxa (%)</th>
                                    <th class="text-end" style="width:100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->getTipo() }}</td>
                                    <td>
                                        @if($item->bandeira_cartao)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12">{{ $item->getBandeira() }}</span>
                                        @else
                                        <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-danger">R$ {{ __moeda($item->taxa) }}%</td>
                                    <td class="text-end">
                                        <form action="{{ route('taxa-cartao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @csrf @method('delete')
                                            <div class="modulo-action-group">
                                                @can('taxa_pagamento_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('taxa-cartao.edit', [$item->id]) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('taxa_pagamento_delete')
                                                <button type="button" class="btn btn-danger btn-delete btn-sm" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma taxa de cartão configurada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">{!! $data->appends(request()->all())->links() !!}</div>

            </div>
        </div>
    </div>
</div>
@endsection
