@extends('layouts.app', ['title' => 'Horários de Funcionamento'])

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
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }
.horario-entrada { font-weight: 700; color: #2e7d32; }
.horario-saida { font-weight: 700; color: #c62828; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2"><i class="ri-time-line"></i> Horários de Funcionamento</h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Configure o expediente dos profissionais para cada dia da semana.</p>
                    </div>
                    <div>
                        <a href="{{ route('funcionamentos.create') }}" class="btn btn-light btn-sm px-3 text-dark"><i class="ri-add-circle-line align-middle me-1"></i> Novo Horário</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8 col-12">
                            {!!Form::select('funcionario_id', 'Funcionário', $funcionario != null ? [$funcionario->id => $funcionario->nome] : ['' => 'Selecione'])->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-4 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit"><i class="ri-search-line me-1"></i> Filtrar</button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('funcionamentos.index') }}"><i class="ri-eraser-line me-1"></i> Limpar</a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Dia da Semana</th>
                                    <th>Entrada</th>
                                    <th>Saída</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->funcionario->nome }}</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $item->getDiaStr() }}</span></td>
                                    <td class="horario-entrada">{{ \Carbon\Carbon::parse($item->inicio)->format('H:i') }} hs</td>
                                    <td class="horario-saida">{{ \Carbon\Carbon::parse($item->fim)->format('H:i') }} hs</td>
                                </tr>
                                @empty
                                <tr><td colspan="4"><div class="modulo-empty"><i class="ri-inbox-2-line"></i><p>Nenhum horário cadastrado.</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de horários:</span>
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
