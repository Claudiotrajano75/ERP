@extends('layouts.app', ['title' => 'Tamanhos de Pizza'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 16px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .modulo-table-wrap tbody tr:hover td { background: #fafbff; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
    .status-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .status-pill.ativo { background: #dcfce7; color: #16a34a; }
    .status-pill.inativo { background: #fee2e2; color: #dc2626; }
    .info-badge { display: inline-flex; align-items: center; justify-content: center; background: #ede9fe; color: #6d28d9; border-radius: 8px; padding: 3px 10px; font-size: 12px; font-weight: 700; min-width: 36px; }
    .modulo-empty { padding: 60px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- CABEÇALHO PREMIUM --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-pie-chart-2-line"></i>
                                Tamanhos de Pizza
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Defina os tamanhos disponíveis, máximo de sabores e quantidade de fatias.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('tamanhos-pizza.create') }}" class="btn btn-success btn-sm px-3 d-flex align-items-center gap-1 fw-semibold">
                                <i class="ri-add-circle-line fs-18"></i> Novo Tamanho
                            </a>
                        </div>
                    </div>
                </div>

                {{-- TABELA PREMIUM --}}
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th class="text-center">Máx. Sabores</th>
                                    <th class="text-center">Qtd. Fatias</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end" style="width: 110px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->nome }}</td>
                                    <td class="text-center">
                                        <span class="info-badge">{{ $item->maximo_sabores }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="info-badge" style="background:#dbeafe; color:#1d4ed8;">{{ $item->quantidade_pedacos }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status)
                                        <span class="status-pill ativo"><i class="ri-checkbox-circle-fill"></i> Ativo</span>
                                        @else
                                        <span class="status-pill inativo"><i class="ri-close-circle-fill"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('tamanhos-pizza.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline-flex gap-1">
                                            @method('delete')
                                            @csrf
                                            <a class="btn btn-warning btn-sm" href="{{ route('tamanhos-pizza.edit', [$item->id]) }}" title="Editar">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-pie-chart-2-line"></i>
                                            <p>Nenhum tamanho de pizza cadastrado.</p>
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