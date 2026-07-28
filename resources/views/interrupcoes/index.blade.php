@extends('layouts.app', ['title' => 'Interrupções e Intervalos'])

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
.modulo-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.2px; }
.modulo-badge-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.modulo-badge-danger { background: linear-gradient(135deg, #fbe9e7, #ffccbc); color: #c62828; }
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }
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
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2"><i class="ri-history-line"></i> Interrupções de Atendimento</h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie intervalos programados dos profissionais para evitar agendamentos.</p>
                    </div>
                    <div>
                        <a href="{{ route('interrupcoes.create') }}" class="btn btn-light btn-sm px-3 text-dark"><i class="ri-add-circle-line align-middle me-1"></i> Nova Interrupção</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8 col-12">
                            {!!Form::select('funcionario_id', 'Funcionário', ['' => 'Todos'] + $funcionarios->pluck('nome', 'id')->all())->id('funcionario')->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-4 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit"><i class="ri-search-line me-1"></i> Filtrar</button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('interrupcoes.index') }}"><i class="ri-eraser-line me-1"></i> Limpar</a>
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
                                    <th>Dia</th>
                                    <th>Início</th>
                                    <th>Término</th>
                                    <th>Motivo</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->funcionario->nome }}</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ \App\Models\DiaSemana::getDiaStr($item->dia_id) }}</span></td>
                                    <td class="fw-medium">{{ $item->inicioParse ?? '--' }}</td>
                                    <td class="fw-medium">{{ $item->finalParse ?? '--' }}</td>
                                    <td class="text-muted">{{ $item->motivo }}</td>
                                    <td>
                                        @if($item->status)
                                        <span class="modulo-badge modulo-badge-success"><i class="ri-check-line"></i> Ativo</span>
                                        @else
                                        <span class="modulo-badge modulo-badge-danger"><i class="ri-close-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('interrupcoes.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @csrf @method('delete')
                                            <div class="modulo-action-group">
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('interrupcoes.edit', [$item->id]) }}" title="Editar"><i class="ri-pencil-line"></i></a>
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Excluir"><i class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7"><div class="modulo-empty"><i class="ri-inbox-2-line"></i><p>Nenhum intervalo cadastrado.</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de intervalos:</span>
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

@section('js')
<script src="/js/delete_selecionados.js"></script>
@endsection
