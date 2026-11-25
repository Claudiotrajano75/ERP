@extends('layouts.app', ['title' => 'Demos Solicitadas'])

@section('css')
<style>
    /* ─── Cards de Estatísticas ─── */
    .stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-blue   { background: linear-gradient(135deg,#3b82f6,#2563eb); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
    .stat-amber  { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow: 0 6px 18px rgba(217,119,6,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub   { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon  { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* ─── Filtro ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
    .filter-wrap .form-control,
    .filter-wrap .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    .filter-wrap .form-control:focus,
    .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Grade de botões de ação ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; flex-wrap: nowrap; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; flex-shrink: 0; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-whatsapp { background: #dcfce7; color: #16a34a; }
    .act-whatsapp:hover { box-shadow: 0 4px 12px rgba(22,163,74,.35); }
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Select de status inline ─── */
    .status-select { height: 34px; border-radius: 10px; border: 1.5px solid #dcdce9; font-size: 12px; font-weight: 600; color: #374151; background: #f8f9fc; padding: 0 8px; cursor: pointer; min-width: 140px; }
    .status-select:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); }

    /* ─── Badges (pills) ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .pill-novo    { background: #eff6ff; color: #2563eb; }
    .pill-em      { background: #fffbeb; color: #d97706; }
    .pill-ok      { background: #dcfce7; color: #15803d; }

    /* ─── Estado vazio ─── */
    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-send-plane-fill"></i>
                            Demos Solicitadas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Leads que solicitaram demonstração pela landing page.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ Cards de Estatísticas ═══ --}}
                @php
                    $total      = $kpis['total'] ?? 0;
                    $novos      = $kpis['novos'] ?? 0;
                    $emAnd      = $kpis['em_atendimento'] ?? 0;
                    $concluidos = $kpis['concluidos'] ?? 0;
                @endphp
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de Leads</div>
                                    <div class="st-value">{{ $total }}</div>
                                    <div class="st-sub">Todas as solicitações</div>
                                </div>
                                <div class="st-icon"><i class="ri-group-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Novos</div>
                                    <div class="st-value">{{ $novos }}</div>
                                    <div class="st-sub">Aguardando atendimento</div>
                                </div>
                                <div class="st-icon"><i class="ri-mail-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Em Atendimento</div>
                                    <div class="st-value">{{ $emAnd }}</div>
                                    <div class="st-sub">Em contato com lead</div>
                                </div>
                                <div class="st-icon"><i class="ri-headphone-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Concluídos</div>
                                    <div class="st-value">{{ $concluidos }}</div>
                                    <div class="st-sub">Atendimento finalizado</div>
                                </div>
                                <div class="st-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Filtros de Busca ═══ --}}
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Leads</h5>
                    </div>
                    <div class="mt-3">
                        <form method="GET" action="{{ route('solicitacoes-demo.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5 col-12">
                                    <label>Nome / Empresa / E-mail / Telefone</label>
                                    <input type="text" name="q" value="{{ request('q') }}"
                                           class="form-control"
                                           placeholder="Digite para buscar...">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label>Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="novo"           {{ request('status')=='novo'           ? 'selected':'' }}>Novo</option>
                                        <option value="em_atendimento" {{ request('status')=='em_atendimento' ? 'selected':'' }}>Em Atendimento</option>
                                        <option value="concluido"      {{ request('status')=='concluido'      ? 'selected':'' }}>Concluído</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                            <i class="ri-search-line"></i> Buscar
                                        </button>
                                        <a class="btn btn-light border px-3" href="{{ route('solicitacoes-demo.index') }}" title="Limpar Filtros" style="border-radius:10px;">
                                            <i class="ri-eraser-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ═══ Tabela ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Empresa</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th>Mensagem</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th class="text-end" style="width:260px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="text-muted" style="font-size:12px;">{{ $item->id }}</td>
                                    <td>
                                        <div class="fw-semibold" style="color:#1f2937;">{{ $item->nome }}</div>
                                    </td>
                                    <td>{{ $item->empresa ?: '—' }}</td>
                                    <td>{{ $item->telefone }}</td>
                                    <td style="font-size:12.5px;">{{ $item->email }}</td>
                                    <td style="max-width:160px;font-size:12.5px;color:#64748b;">
                                        <span title="{{ $item->mensagem }}">{{ Str::limit($item->mensagem, 45) ?: '—' }}</span>
                                    </td>
                                    <td>
                                        @if($item->status == 'novo')
                                            <span class="pill pill-novo"><i class="ri-record-circle-line" style="font-size:7px;"></i> Novo</span>
                                        @elseif($item->status == 'em_atendimento')
                                            <span class="pill pill-em"><i class="ri-record-circle-line" style="font-size:7px;"></i> Em Atendimento</span>
                                        @else
                                            <span class="pill pill-ok"><i class="ri-record-circle-line" style="font-size:7px;"></i> Concluído</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;white-space:nowrap;color:#94a3b8;">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="act-group justify-content-end">

                                            {{-- WhatsApp --}}
                                            <a href="{{ $item->whatsapp_link }}" target="_blank"
                                               class="act-btn act-whatsapp" title="Falar no WhatsApp">
                                                <i class="ri-whatsapp-line"></i>
                                            </a>

                                            {{-- Mudar Status --}}
                                            <form method="POST" action="{{ route('solicitacoes-demo.update-status', $item->id) }}" class="m-0">
                                                @csrf @method('PUT')
                                                <select name="status" onchange="this.form.submit()" class="status-select">
                                                    @foreach(\App\Models\SolicitacaoDemonstracao::statusList() as $key => $label)
                                                        <option value="{{ $key }}" {{ $item->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </form>

                                            {{-- Excluir --}}
                                            <form method="POST" action="{{ route('solicitacoes-demo.destroy', $item->id) }}"
                                                  onsubmit="return confirm('Excluir este lead permanentemente?')" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="act-btn act-del" title="Excluir Lead">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma solicitação encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ Footer / Paginação ═══ --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> leads
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>{{-- /card-body --}}
        </div>{{-- /card --}}
    </div>
</div>
@endsection

