@extends('layouts.app', ['title' => 'Manutenção de Veículos'])

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

/* ─── Action Buttons — SEMPRE lado a lado ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer da Tabela ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }

/* ─── KPI Cards ─── */
.widget-icon-box { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.2s ease; }
.widget-icon-box:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.12); }

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-tools-fill"></i>
                            Manutenção de Veículos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie as manutenções, serviços e peças dos veículos.
                        </p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('manutencao_veiculo_create')
                        <a href="{{ route('manutencao-veiculos.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Manutenção
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI CARDS ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Valor Total</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($data->sum('total')) }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-warning mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Desconto Total</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($data->sum('desconto')) }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-discount-percent-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-primary mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Registros</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $data->total() }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-tools-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FILTROS GLASS ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('fornecedor_id', 'Fornecedor')
                            ->attrs(['class' => 'select2'])
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data início')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data fim')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado',
                            [
                            '' => 'Todos',
                            'aguardando' => 'Aguardando',
                            'em_manutencao' => 'Em manutenção',
                            'finalizado' => 'Finalizado',
                            ])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('veiculo_id', 'Veículo')
                            ->attrs(['class' => 'select2'])
                            ->options($veiculo != null ? [$veiculo->id => $veiculo->info] : [])
                            !!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2'])
                            !!}
                        </div>
                        @endif
                        <div class="col-md-2 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-2 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('manutencao-veiculos.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fornecedor</th>
                                    <th>Veículo</th>
                                    <th>Valor</th>
                                    <th>Data Início</th>
                                    <th>Data Fim</th>
                                    <th>Cadastro</th>
                                    <th>Estado</th>
                                    <th class="text-end" style="width: 140px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td><span class="fw-semibold">{{ $item->numero_sequencial }}</span></td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->fornecedor->info }}</span>
                                        @if($item->fornecedor->cpf_cnpj)
                                        <span class="text-muted fs-11">{{ $item->fornecedor->cpf_cnpj }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->veiculo->info }}</td>
                                    <td class="fw-semibold">R$ {{ __moeda($item->total) }}</td>
                                    <td class="fs-12">{{ __data_pt($item->data_inicio, 0) }}</td>
                                    <td class="fs-12">{{ __data_pt($item->data_fim, 0) }}</td>
                                    <td class="fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td>
                                        @if($item->estado == 'aguardando')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                            <i class="ri-time-line me-1"></i>Aguardando
                                        </span>
                                        @elseif($item->estado == 'em_manutencao')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                            <i class="ri-tools-line me-1"></i>Em manutenção
                                        </span>
                                        @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-double-line me-1"></i>Finalizado
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('manutencao-veiculos.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('manutencao_veiculo_view')
                                                <a class="btn btn-info btn-sm text-white"
                                                   href="{{ route('manutencao-veiculos.show', $item->id) }}" title="Visualizar">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @endcan
                                                @can('manutencao_veiculo_edit')
                                                <a class="btn btn-warning btn-sm text-white"
                                                   href="{{ route('manutencao-veiculos.edit', $item->id) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('manutencao_veiculo_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum registro encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Soma</td>
                                    <td class="text-primary fw-bold">R$ {{ __moeda($data->sum('total')) }}</td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Paginação) ═══ -->
                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de registros: <span class="modulo-total-value">{{ $data->total() }}</span></span>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
