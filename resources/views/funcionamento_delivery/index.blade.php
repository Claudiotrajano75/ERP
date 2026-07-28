@extends('layouts.app', ['title' => 'Funcionamento Delivery'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    /* Tabela */
    .table-custom thead th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; border-top: none; padding: 14px 16px; }
    .table-custom tbody tr { transition: all 0.2s; border-bottom: 1px solid #eef0f5; }
    .table-custom tbody tr:hover { background-color: #f8fafc; }
    .table-custom tbody td { padding: 14px 16px; vertical-align: middle; color: #1e293b; font-size: 14px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-time-line"></i>
                                Funcionamento Delivery
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Configurar o(s) dia(s) de funcionamento, horário de início e fim.
                            </p>
                        </div>
                        <a href="{{ route('funcionamento-delivery.create') }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-add-circle-fill me-1"></i> Novo Horário
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white p-4">

                    <div class="table-responsive-sm">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Dia</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                <tr>
                                    <td class="fw-medium">{{ $item->getDiaStr() }}</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-13"><i class="ri-time-line me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->inicio)->format('H:i') }}</span></td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-13"><i class="ri-time-line me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->fim)->format('H:i') }}</span></td>
                                    <td>
                                        <form action="{{ route('funcionamento-delivery.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex gap-1 flex-wrap">
                                            @method('delete')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('funcionamento-delivery.edit', [$item->id]) }}" data-bs-toggle="tooltip" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>

                                            @csrf
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="tooltip" title="Excluir">
                                                <i class="ri-delete-bin-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="ri-calendar-todo-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                        <h5 class="text-muted">Nenhum horário configurado</h5>
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
