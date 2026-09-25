@extends('layouts.app', ['title' => 'Minhas Solicitações'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,.6) !important; }
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

/* Stats */
.tk-stat { background:#fff; border:1px solid #eef0f5; border-radius:12px; padding:16px 20px; display:flex; align-items:center; gap:14px; transition: box-shadow .2s, transform .2s; }
.tk-stat:hover { box-shadow:0 4px 16px rgba(67,56,202,.08); transform:translateY(-2px); }
.tk-stat-ico { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.tk-stat-val { font-size:22px; font-weight:700; color:#1e293b; line-height:1; }
.tk-stat-lbl { font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; font-weight:600; }
.ic-slate  { background:#f1f5f9; color:#475569; }
.ic-blue   { background:#dbeafe; color:#2563eb; }
.ic-amber  { background:#fef3c7; color:#d97706; }
.ic-red    { background:#fee2e2; color:#dc2626; }
.ic-green  { background:#dcfce7; color:#16a34a; }

/* Filtro */
.tk-filter-bar { background:#f8faff; border:1px solid #eef0f5; border-radius:12px; padding:18px 20px; }

/* Tabela */
.tk-table-wrap { border-radius:12px; border:1px solid #eef0f5; overflow:hidden; }
.tk-table-wrap table { margin:0; }
.tk-table-wrap thead th { background:#f8f9fc; color:#5a5a7a; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.4px; padding:12px 16px; border-bottom:2px solid #e8eaf6; }
.tk-table-wrap tbody td { padding:13px 16px; vertical-align:middle; border-bottom:1px solid #f0f2f8; font-size:13px; }
.tk-table-wrap tbody tr { transition:background .15s; }
.tk-table-wrap tbody tr:hover { background:#f5f6fe; }
.tk-table-wrap tbody tr:last-child td { border-bottom:none; }

/* Link assunto */
.tk-assunto-link { color:#312e81; font-weight:600; text-decoration:none; }
.tk-assunto-link:hover { color:#4f46e5; text-decoration:underline; }

/* Badges de status */
.tk-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:capitalize; }
.tk-badge-aberto    { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
.tk-badge-respondida{ background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
.tk-badge-aguardando{ background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.tk-badge-resolvido { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }

/* Depto badge */
.tk-depto { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; background:#ede9fe; color:#5b21b6; }

/* Empty */
.tk-empty { padding:52px 20px; text-align:center; }
.tk-empty i { font-size:52px; color:#c7d2fe; display:block; margin-bottom:12px; }
.tk-empty p { color:#94a3b8; font-size:14px; margin:0; }

/* Dash btn */
.dash-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:all .2s ease; border:none; cursor:pointer; }
.dash-btn-success { background:#16a34a; color:#fff; }
.dash-btn-success:hover { background:#15803d; color:#fff; transform:translateY(-1px); box-shadow:0 4px 12px rgba(22,163,74,.3); }
.dash-btn-light { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); color:#fff; backdrop-filter:blur(6px); }
.dash-btn-light:hover { background:rgba(255,255,255,.28); color:#fff; transform:translateY(-1px); }
.dash-btn-primary { background:#4f46e5; color:#fff; }
.dash-btn-primary:hover { background:#4338ca; color:#fff; transform:translateY(-1px); }
.dash-btn-outline { background:#fff; border:1px solid #e2e8f0; color:#475569; }
.dash-btn-outline:hover { background:#f8faff; border-color:#c7d2fe; color:#312e81; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- HEADER --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-customer-service-2-line"></i>
                                Minhas Solicitações
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">Acompanhe e gerencie seus chamados de suporte.</p>
                        </div>
                        <div>
                            <a href="{{ route('ticket.create') }}" class="dash-btn dash-btn-success">
                                <i class="ri-add-circle-line"></i> Novo Chamado
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- STATS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="tk-stat">
                                <div class="tk-stat-ico ic-slate"><i class="ri-ticket-2-line"></i></div>
                                <div>
                                    <div class="tk-stat-val">{{ $data->total() }}</div>
                                    <div class="tk-stat-lbl">Total</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="tk-stat">
                                <div class="tk-stat-ico ic-blue"><i class="ri-inbox-archive-line"></i></div>
                                <div>
                                    <div class="tk-stat-val">{{ $data->where('status','aberto')->count() }}</div>
                                    <div class="tk-stat-lbl">Abertos</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="tk-stat">
                                <div class="tk-stat-ico ic-red"><i class="ri-time-line"></i></div>
                                <div>
                                    <div class="tk-stat-val">{{ $data->where('status','aguardando')->count() }}</div>
                                    <div class="tk-stat-lbl">Aguardando</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="tk-stat">
                                <div class="tk-stat-ico ic-green"><i class="ri-checkbox-circle-line"></i></div>
                                <div>
                                    <div class="tk-stat-val">{{ $data->where('status','resolvido')->count() }}</div>
                                    <div class="tk-stat-lbl">Resolvidos</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FILTROS --}}
                    <div class="tk-filter-bar mb-4">
                        {!! Form::open()->fill(request()->all())->get() !!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3 col-12">
                                {!! Form::text('assunto', 'Assunto') !!}
                            </div>
                            <div class="col-md-2 col-6">
                                {!! Form::date('created_at', 'Data de Criação') !!}
                            </div>
                            <div class="col-md-2 col-6">
                                {!! Form::select('status', 'Status', ['' => 'Todos', 'aberto' => 'Aberto', 'respondida' => 'Respondida', 'aguardando' => 'Aguardando', 'resolvido' => 'Resolvido'])->attrs(['class' => 'form-select']) !!}
                            </div>
                            <div class="col-md-2 col-6">
                                {!! Form::select('departamento', 'Departamento', ['' => 'Todos', 'financeiro' => 'Financeiro', 'suporte' => 'Suporte'])->attrs(['class' => 'form-select']) !!}
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex gap-2">
                                    <button class="dash-btn dash-btn-primary flex-fill" type="submit">
                                        <i class="ri-search-line"></i> Pesquisar
                                    </button>
                                    <a class="dash-btn dash-btn-outline" href="{{ route('ticket.index') }}">
                                        <i class="ri-eraser-fill"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    {{-- TABELA --}}
                    <div class="tk-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Assunto</th>
                                        <th>ID</th>
                                        <th>Departamento</th>
                                        <th>Criado em</th>
                                        <th>Última atividade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('ticket.show', $item->id) }}" class="tk-assunto-link">
                                                <i class="ri-file-text-line me-1 text-muted"></i>
                                                {{ $item->assunto }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-semibold fs-12">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <span class="tk-depto">
                                                <i class="ri-briefcase-line"></i>
                                                {{ ucfirst($item->departamento) }}
                                            </span>
                                        </td>
                                        <td class="text-muted fs-12">{{ __data_pt($item->created_at) }}</td>
                                        <td class="text-muted fs-12">{{ __data_pt($item->updated_at) }}</td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'aberto'     => ['class' => 'tk-badge-aberto',     'icon' => 'ri-inbox-line',            'label' => 'Aberto'],
                                                    'respondida' => ['class' => 'tk-badge-respondida', 'icon' => 'ri-chat-check-line',       'label' => 'Respondida'],
                                                    'aguardando' => ['class' => 'tk-badge-aguardando', 'icon' => 'ri-time-line',             'label' => 'Aguardando'],
                                                    'resolvido'  => ['class' => 'tk-badge-resolvido',  'icon' => 'ri-checkbox-circle-line',  'label' => 'Resolvido'],
                                                ];
                                                $s = $statusMap[$item->status] ?? ['class' => 'tk-badge-aberto', 'icon' => 'ri-question-line', 'label' => $item->status];
                                            @endphp
                                            <span class="tk-badge {{ $s['class'] }}">
                                                <i class="{{ $s['icon'] }}"></i> {{ $s['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="tk-empty">
                                                <i class="ri-customer-service-2-line"></i>
                                                <p>Nenhuma solicitação encontrada.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

