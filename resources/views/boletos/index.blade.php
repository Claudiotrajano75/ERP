@extends('layouts.app', ['title' => 'Boletos'])

@section('css')
<style>
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    border-radius: 12px 12px 0 0 !important; border-bottom: none !important;
}
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

.modulo-kpi-card { border: none !important; border-radius: 12px; overflow: hidden; transition: all 0.25s ease; position: relative; }
.modulo-kpi-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
.modulo-kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important; }
.modulo-kpi-card .kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.modulo-kpi-card .kpi-value { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; }
.modulo-kpi-card .kpi-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; }
.modulo-kpi-blue::before { background: linear-gradient(90deg, #4facfe, #00f2fe); }
.modulo-kpi-green::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }

.modulo-glass-filter {
    background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04);
}
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

.modulo-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.2px; }
.modulo-badge-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.modulo-badge-warning { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }

.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: wrap; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: flex-end; }

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
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2"><i class="ri-file-list-3-line"></i> Boletos Gerados</h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Acompanhe todos os boletos gerados e gerencie arquivos de remessa e retornos bancários.</p>
                    </div>
                    <div>
                        <a href="{{ route('remessa-boleto.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-file-list-2-line align-middle me-1"></i> Remessas
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ KPI Cards ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total Emitido (pág.)</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($data->sum('valor')) }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-barcode-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Boletos (pág.)</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $data->count() }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'form-control select2 form-select-sm'])
                            ->options($cliente != null ? [$cliente->id => $cliente->info] : [])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Vencimento Inicial')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Vencimento Final')->attrs(['class' => 'form-control form-control-sm'])!!}
                        </div>
                        <div class="col-md-3 col-12">
                            {!!Form::select('banco', 'Banco / Carteira', ['' => 'Todos'] + $contasBoleto->pluck('info', 'id')->all())->attrs(['class' => 'form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit"><i class="ri-search-line me-1"></i> Pesquisar</button>
                                <a class="btn btn-outline-secondary btn-sm px-3" href="{{ route('boleto.index') }}"><i class="ri-eraser-line me-1"></i> Limpar</a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Banco</th>
                                    <th>Cliente</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                    <th class="text-end" style="width:130px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-medium text-dark">{{ $item->contaBoleto->banco }}</td>
                                    <td><span class="fw-semibold">{{ $item->contaReceber->cliente->info }}</span></td>
                                    <td>{{ __data_pt($item->vencimento, 0) }}</td>
                                    <td>
                                        @if($item->contaReceber->status)
                                        <span class="modulo-badge modulo-badge-success"><i class="ri-check-line"></i> Recebido</span>
                                        @else
                                        <span class="modulo-badge modulo-badge-warning"><i class="ri-time-line"></i> Pendente</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold" style="color:#2e7d32;">R$ {{ __moeda($item->valor) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('boleto.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete') @csrf
                                            <div class="modulo-action-group">
                                                <a target="_blank" class="btn btn-dark btn-sm text-white" href="{{ route('boleto.print', [$item->id]) }}" title="Imprimir"><i class="ri-printer-line"></i></a>
                                                @if(!$item->contaReceber->status)
                                                @can('conta_receber_edit')
                                                <a title="Receber" href="{{ route('conta-receber.pay', $item->contaReceber->id) }}" class="btn btn-success btn-sm text-white"><i class="ri-money-dollar-box-line"></i></a>
                                                @endcan
                                                @endif
                                                @can('boleto_delete')
                                                <button type="button" class="btn btn-danger btn-delete btn-sm" title="Excluir"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6"><div class="modulo-empty"><i class="ri-inbox-2-line"></i><p>Nenhum boleto gerado ou encontrado.</p></div></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modulo-footer">
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
