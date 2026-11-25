@extends('layouts.app', ['title' => 'Demos Solicitadas'])

@section('css')
<style>
    .modulo-header-gradient {
        background: linear-gradient(135deg, #2d1b6e 0%, #4f46e5 60%, #6366f1 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.80) !important; font-weight: 400; }

    .table-custom thead th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; border-top: none; padding: 13px 14px; }
    .table-custom tbody tr { transition: background .15s; border-bottom: 1px solid #eef0f5; }
    .table-custom tbody tr:hover { background-color: #f8fafc; }
    .table-custom tbody td { padding: 13px 14px; vertical-align: middle; color: #1e293b; font-size: 14px; }

    .kpi-card { border-radius: 14px; padding: 20px 22px; color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,.15); }
    .kpi-card .kpi-num { font-size: 2rem; font-weight: 800; line-height: 1; }
    .kpi-card .kpi-label { font-size: .83rem; opacity: .85; margin-top: 5px; }

    .badge-status-novo       { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 999px; padding: 3px 11px; font-size: .77rem; font-weight: 700; white-space:nowrap; }
    .badge-status-em         { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; border-radius: 999px; padding: 3px 11px; font-size: .77rem; font-weight: 700; white-space:nowrap; }
    .badge-status-concluido  { background: #f0fdf4; color: #109f61; border: 1px solid #bbf7d0; border-radius: 999px; padding: 3px 11px; font-size: .77rem; font-weight: 700; white-space:nowrap; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">

    {{-- KPI Cards --}}
    @php
        $total      = $kpis['total'] ?? 0;
        $novos      = $kpis['novos'] ?? 0;
        $emAnd      = $kpis['em_atendimento'] ?? 0;
        $concluidos = $kpis['concluidos'] ?? 0;
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="background:linear-gradient(135deg,#4f46e5,#818cf8);">
                <div class="kpi-num">{{ $total }}</div>
                <div class="kpi-label">Total de Leads</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="background:linear-gradient(135deg,#2563eb,#60a5fa);">
                <div class="kpi-num">{{ $novos }}</div>
                <div class="kpi-label">Novos</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="background:linear-gradient(135deg,#d97706,#fbbf24);">
                <div class="kpi-num">{{ $emAnd }}</div>
                <div class="kpi-label">Em Atendimento</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="background:linear-gradient(135deg,#109f61,#34d399);">
                <div class="kpi-num">{{ $concluidos }}</div>
                <div class="kpi-label">Concluídos</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">

                {{-- Header --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="fa fa-paper-plane"></i>
                                Demos Solicitadas
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Leads que solicitaram demonstração pela landing page
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Filtros --}}
                <div class="card-body bg-white p-4 pb-2">
                    <form method="GET" action="{{ route('solicitacoes-demo.index') }}">
                        <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold text-muted mb-1">BUSCAR</label>
                                <input type="text" name="q" value="{{ request('q') }}"
                                       placeholder="Nome, empresa, e-mail ou telefone..."
                                       class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-muted mb-1">STATUS</label>
                                <select name="status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="novo"           {{ request('status')=='novo'           ? 'selected':'' }}>Novo</option>
                                    <option value="em_atendimento" {{ request('status')=='em_atendimento' ? 'selected':'' }}>Em Atendimento</option>
                                    <option value="concluido"      {{ request('status')=='concluido'      ? 'selected':'' }}>Concluído</option>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i> Buscar
                                </button>
                                <a href="{{ route('solicitacoes-demo.index') }}" class="btn btn-danger ms-1">
                                    <i class="fa fa-eraser me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabela --}}
                <div class="table-responsive px-4 pb-2">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Empresa</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Mensagem</th>
                                <th class="text-center">Status</th>
                                <th>Data</th>
                                <th class="text-center" width="220">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="text-muted small">{{ $item->id }}</td>
                                <td><strong>{{ $item->nome }}</strong></td>
                                <td>{{ $item->empresa ?: '—' }}</td>
                                <td>{{ $item->telefone }}</td>
                                <td class="small">{{ $item->email }}</td>
                                <td class="small text-muted" style="max-width:160px;">
                                    <span title="{{ $item->mensagem }}">{{ Str::limit($item->mensagem, 45) ?: '—' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'novo')
                                        <span class="badge-status-novo">Novo</span>
                                    @elseif($item->status == 'em_atendimento')
                                        <span class="badge-status-em">Em Atendimento</span>
                                    @else
                                        <span class="badge-status-concluido">Concluído</span>
                                    @endif
                                </td>
                                <td class="small text-muted" style="white-space:nowrap;">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    {{-- Ações: linha única com gap --}}
                                    <div class="d-flex align-items-center gap-1 justify-content-center flex-nowrap">

                                        {{-- WhatsApp --}}
                                        <a href="{{ $item->whatsapp_link }}" target="_blank"
                                           class="btn btn-sm fw-semibold"
                                           style="background:#25d366;color:#fff;white-space:nowrap;"
                                           title="Falar no WhatsApp">
                                            <i class="fa fa-whatsapp"></i> WhatsApp
                                        </a>

                                        {{-- Mudar Status --}}
                                        <form method="POST" action="{{ route('solicitacoes-demo.update-status', $item->id) }}" class="m-0">
                                            @csrf @method('PUT')
                                            <select name="status" onchange="this.form.submit()"
                                                    class="form-select form-select-sm"
                                                    style="min-width:130px;cursor:pointer;">
                                                @foreach(\App\Models\SolicitacaoDemonstracao::statusList() as $key => $label)
                                                    <option value="{{ $key }}" {{ $item->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </form>

                                        {{-- Excluir --}}
                                        <form method="POST" action="{{ route('solicitacoes-demo.destroy', $item->id) }}"
                                              onsubmit="return confirm('Excluir este lead permanentemente?')" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fa fa-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:.3;"></i>
                                    <span class="text-muted">Nenhuma solicitação encontrada.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Rodapé paginação --}}
                <div class="card-footer bg-light d-flex align-items-center justify-content-between flex-wrap gap-2 p-3">
                    <span class="small text-muted">{{ $data->total() }} registro(s) encontrado(s)</span>
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>{{-- /card --}}
        </div>
    </div>
</div>
@endsection
