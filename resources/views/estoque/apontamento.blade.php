@extends('layouts.app', ['title' => 'Apontamento de Produção'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; }
.modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }

/* ─── Estilo do formulário ─── */
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Form Glass ─── */
.form-apontamento-glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.form-apontamento-glass label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.form-apontamento-glass .form-control,
.form-apontamento-glass .form-select { height: 38px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-settings-3-line"></i>
                            Apontamento de Produção
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Registre a baixa de insumos da produção de produtos compostos (com lista técnica).
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('estoque.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total_apontamentos'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Apontamentos realizados</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-file-list-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Quantidade</h4>
                                        <h3 class="my-2 text-white fs-18">{{ number_format($stats['total_quantidade'], 0) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Total de itens produzidos</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-stack-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Hoje</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total_hoje'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Apontamentos de hoje</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-calendar-check-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ Formulário Glass ═══ -->
                <div class="form-apontamento-glass p-3 mb-4">
                    <h5 class="text-dark border-bottom pb-2 mb-3 fs-14 d-flex align-items-center gap-2">
                        <i class="ri-add-circle-line text-primary fs-16"></i>
                        Novo Apontamento
                    </h5>
                    {!!Form::open()
                    ->post()
                    ->route('apontamento.store')
                    ->multipart()
                    !!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6 col-12">
                            {!!Form::select('produto_composto_id', 'Produto (Composto)')
                            ->attrs(['class' => 'form-select'])->required()
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::text('quantidade', 'Quantidade')
                            ->attrs(['class' => 'form-control quantidade'])->required()
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="ri-save-line align-middle me-1"></i> Salvar
                            </button>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="{{ route('estoque.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="ri-close-line align-middle me-1"></i> Cancelar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Histórico Premium ═══ -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="ri-history-line text-primary fs-16"></i>
                    <h5 class="mb-0 fs-14 text-dark">Histórico de Apontamentos</h5>
                </div>

                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Data Apontamento</th>
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->produto->nome }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($item->quantidade, 3, '.', '') }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ __data_pt($item->created_at, 0) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="modulo-action-group">
                                            <a class="btn btn-info btn-sm text-white" 
                                               href="{{ route('apontamento.imprimir', $item->id) }}" 
                                               title="Imprimir comprovante">
                                                <i class="ri-printer-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum apontamento encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ Footer / Paginação ═══ -->
                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de registros:</span>
                        <span class="modulo-total-value">{{ $data->total() }}</span>
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


