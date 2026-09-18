@extends('layouts.app', ['title' => 'MDF-e — Manifesto de Documentos'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.14);
}
.stat-card .stat-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 42px;
    opacity: .22;
}
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.stat-card.c-red    { background: linear-gradient(135deg, #ef4444, #b91c1c); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-teal   { background: linear-gradient(135deg, #06b6d4, #0e7490); }

.stat-card .stat-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    opacity: .85;
    margin-bottom: 4px;
}
.stat-card .stat-val {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
}

/* ─── Botões Dash ─── */
.dash-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 40px;
    padding: 0 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid transparent;
    text-decoration: none;
    transition: all .2s ease;
    cursor: pointer;
}
.dash-btn-primary {
    background: #4f46e5;
    color: #fff;
}
.dash-btn-primary:hover {
    background: #4338ca;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
}
.dash-btn-light {
    background: #f8fafc;
    color: #475569;
    border-color: #e2e8f0;
}
.dash-btn-light:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #cbd5e1;
}

/* ─── Filtro Padronizado ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #e8ecf4;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    margin-bottom: 22px;
}
.modulo-glass-filter-premium label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 6px;
}
.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 40px;
    border-radius: 10px;
    border: 1px solid #dcdce9;
    font-size: 13.5px;
    color: #1f2937;
    background: #fcfdfe;
}
.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}

/* ─── Tabela ─── */
.tb-wrap {
    border-radius: 14px;
    border: 1px solid #eef0f5;
    overflow: hidden;
    background: #fff;
}
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .3px;
    padding: 10px 8px;
    border-bottom: 1px solid #e8eaf6;
    white-space: nowrap;
}
.tb-wrap tbody td {
    padding: 9px 8px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13px;
    color: #374151;
}
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de Ações ─── */
.act-group { display: inline-flex; gap: 4px; align-items: center; justify-content: flex-start; }
.act-btn {
    width: 29px;
    height: 29px;
    border-radius: 6px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13.5px;
    text-decoration: none;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }

.act-print { background: #e0f2fe; color: #0284c7; }
.act-print:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
.act-xml { background: #f1f5f9; color: #475569; }
.act-xml:hover { box-shadow: 0 4px 12px rgba(71,85,105,.3); }
.act-send { background: #dcfce7; color: #15803d; }
.act-send:hover { box-shadow: 0 4px 12px rgba(21,128,61,.3); }
.act-edit { background: #eef0ff; color: #4f46e5; }
.act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
.act-cancel { background: #fee2e2; color: #dc2626; }
.act-cancel:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }
.act-info { background: #f3e8ff; color: #7c3aed; }
.act-info:hover { box-shadow: 0 4px 12px rgba(124,58,237,.3); }
.act-state { background: #fef3c7; color: #d97706; }
.act-state:hover { box-shadow: 0 4px 12px rgba(217,119,6,.3); }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }
.pill-purple { background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }

/* ─── Estado Vazio ─── */
.empty-state { padding: 52px 20px; text-align: center; }
.empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
.empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Estilos Modais Premium MDF-e ─── */
.modal-mdfe-header-red {
    background: #ef4444;
    padding: 16px 20px;
}
.modal-mdfe-header-red .modal-title {
    color: #fff;
    font-size: 16px;
    font-weight: 700;
}
.modal-mdfe-header-red .modal-subtitle {
    color: rgba(255,255,255,0.92);
    font-size: 11.5px;
    margin: 3px 0 0;
}

.modal-mdfe-header-amber {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    padding: 16px 20px;
}
.modal-mdfe-header-amber .modal-title {
    color: #fff;
    font-size: 16px;
    font-weight: 700;
}
.modal-mdfe-header-amber .modal-subtitle {
    color: rgba(255,255,255,0.92);
    font-size: 11.5px;
    margin: 3px 0 0;
}

.modal-mdfe-dados-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
}
.modal-mdfe-dados-card .card-label {
    font-size: 10.5px;
    color: #94a3b8;
    font-weight: 500;
    margin-bottom: 2px;
}
.modal-mdfe-dados-card .card-val {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
}
.modal-mdfe-dados-card .card-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 12px;
    display: block;
}

.modal-alert-danger-soft {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.modal-alert-amber-soft {
    background: #fffbeb;
    border: 1px solid #fef3c7;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.modal-alert-yellow {
    background: #fefce8;
    border: 1px solid #fef08a;
    border-radius: 10px;
    padding: 14px 16px;
}
.modal-alert-yellow ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.modal-alert-yellow ul li {
    font-size: 11.5px;
    color: #a16207;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>
@endsection

@section('content')
<input type="hidden" id="empresa_id" value="{{ request()->empresa_id ?? '' }}">
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-road-map-line"></i>
                                MDF-e — Manifesto de Documentos
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie e emita Manifestos Eletrônicos de Documentos Fiscais, consulte autorizações e faça o encerramento do percurso.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2 flex-wrap align-items-center">
                            @can('mdfe_create')
                            <button type="button" class="dash-btn dash-btn-primary" data-bs-toggle="modal" data-bs-target="#modal-importar_documentos">
                                <i class="ri-add-circle-line"></i> Nova MDF-e
                            </button>
                            <button class="dash-btn dash-btn-light" id="btn-importar_nfe"
                                data-bs-toggle="modal" data-bs-target="#modal-importar_nfe">
                                <i class="ri-file-upload-line"></i> Selecionar NFe
                            </button>
                            @endcan
                            <a href="{{ route('mdfe.nao-encerrados') }}" class="dash-btn dash-btn-light">
                                <i class="ri-alert-line"></i> Não Encerrados
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4 row-cols-2 row-cols-md-3 row-cols-xl-5">
                        <div class="col">
                            <div class="stat-card c-blue">
                                <i class="ri-file-list-3-line stat-icon"></i>
                                <div class="stat-title">Total de Manifestos</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Aprovadas / Emitidas</div>
                                <div class="stat-val">{{ $stats['aprovadas'] }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card c-purple">
                                <i class="ri-checkbox-circle-fill stat-icon"></i>
                                <div class="stat-title">Encerrados</div>
                                <div class="stat-val">{{ $stats['encerrados'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card c-red">
                                <i class="ri-close-circle-line stat-icon"></i>
                                <div class="stat-title">Canceladas</div>
                                <div class="stat-val">{{ $stats['canceladas'] }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card c-amber">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Valor Total Cargas (R$)</div>
                                <div class="stat-val" style="font-size: 19px;">R$ {{ __moeda($stats['valor']) }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS PADRONIZADOS ═══ -->
                    <div class="modulo-glass-filter-premium">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            @if(__countLocalAtivo() > 1)
                            <div class="col-md-2 col-6">
                                <label for="start_date"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label for="end_date"><i class="ri-calendar-line me-1"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="estado"><i class="ri-toggle-line me-1"></i> Status</label>
                                {!!Form::select('estado', '',
                                    ['novo'      => 'Novas',
                                     'rejeitado' => 'Rejeitadas',
                                     'cancelado' => 'Canceladas',
                                     'aprovado'  => 'Aprovadas',
                                     'encerrado' => 'Encerradas',
                                     ''          => 'Todos os Status'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label for="local_id"><i class="ri-map-pin-line me-1"></i> Local / Filial</label>
                                {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'select2 form-select'])
                                !!}
                            </div>
                            @else
                            <div class="col-md-3 col-6">
                                <label for="start_date"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="end_date"><i class="ri-calendar-line me-1"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="estado"><i class="ri-toggle-line me-1"></i> Status</label>
                                {!!Form::select('estado', '',
                                    ['novo'      => 'Novas',
                                     'rejeitado' => 'Rejeitadas',
                                     'cancelado' => 'Canceladas',
                                     'aprovado'  => 'Aprovadas',
                                     'encerrado' => 'Encerradas',
                                     ''          => 'Todos os Status'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @endif
                            <div class="col-md col-12 d-flex gap-2">
                                <button class="dash-btn dash-btn-primary flex-grow-1" type="submit" title="Buscar MDF-e">
                                    <i class="ri-search-line"></i> Filtrar
                                </button>
                                <a class="dash-btn dash-btn-light text-nowrap" href="{{ route('mdfe.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA PREMIUM ═══ -->
                    <div class="tb-wrap mb-3">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="min-width: 140px;">Ações</th>
                                        <th class="text-center">N.º MDF-e</th>
                                        <th>Emissão</th>
                                        <th>Situação</th>
                                        <th class="text-nowrap" style="white-space: nowrap;">Placa Veículo</th>
                                        <th class="text-nowrap" style="white-space: nowrap;">Motorista</th>
                                        <th class="text-end text-nowrap" style="white-space: nowrap;">Qtd Carga</th>
                                        <th class="text-end text-nowrap" style="white-space: nowrap;">Valor Carga</th>
                                        <th class="text-center text-nowrap" style="white-space: nowrap;">UF Início</th>
                                        <th class="text-center text-nowrap" style="white-space: nowrap;">UF Fim</th>
                                        <th class="text-nowrap" style="white-space: nowrap;">Percurso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <form action="{{ route('mdfe.destroy', $item->id) }}" method="post"
                                                  id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">

                                                    {{-- Aprovado: imprimir, download, cancelar, encerrar --}}
                                                    @if($item->estado_emissao == 'aprovado')
                                                    <a class="act-btn act-print" target="_blank"
                                                       href="{{ route('mdfe.imprimir', [$item->id]) }}" title="Imprimir DAMDFE">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                    <a class="act-btn act-xml" target="_blank"
                                                       href="{{ route('mdfe.download', [$item->id]) }}" title="Download XML">
                                                        <i class="ri-download-2-line"></i>
                                                    </a>
                                                    <button title="Cancelar MDF-e" type="button" class="act-btn act-cancel"
                                                            onclick="cancelar('{{$item->id}}', '{{$item->mdfe_numero > 0 ? $item->mdfe_numero : $item->numero}}', '{{$item->serie ?? 1}}', '{{ __data_pt($item->created_at, 0) }}', '{{ $item->veiculoTracao ? $item->veiculoTracao->placa : '--' }}', '{{$item->chave}}')">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
                                                    @if(!$item->encerrado)
                                                    <button title="Encerrar MDF-e na SEFAZ" type="button" class="act-btn" style="background-color: #fef3c7; color: #d97706; border-color: #fde68a;"
                                                            onclick="encerrar('{{$item->chave}}', '{{$item->protocolo}}', '{{$item->mdfe_numero > 0 ? $item->mdfe_numero : $item->numero}}', '{{ __data_pt($item->created_at, 0) }}', '{{ $item->veiculoTracao ? $item->veiculoTracao->placa : '--' }}')">
                                                        <i class="ri-stop-circle-line"></i>
                                                    </button>
                                                    @endif
                                                    @endif

                                                    {{-- Aprovado ou Rejeitado: consultar chave --}}
                                                    @if($item->estado_emissao == 'aprovado' || $item->estado_emissao == 'rejeitado')
                                                    <button type="button" title="Consultar Chave / Motivo" class="act-btn act-info"
                                                            onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                        <i class="ri-file-line"></i>
                                                    </button>
                                                    @endif

                                                    {{-- Novo ou Rejeitado: editar, xml temp, excluir, transmitir --}}
                                                    @if($item->estado_emissao == 'novo' || $item->estado_emissao == 'rejeitado')
                                                    @can('mdfe_edit')
                                                    <a class="act-btn act-edit"
                                                       href="{{ route('mdfe.edit', $item->id) }}" title="Editar MDF-e">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan
                                                    <a target="_blank" title="XML Temporário" class="act-btn act-xml"
                                                       href="{{ route('mdfe.xml-temp', $item->id) }}">
                                                        <i class="ri-file-code-line"></i>
                                                    </a>
                                                    @can('mdfe_delete')
                                                    <button type="button" class="act-btn act-cancel btn-delete" title="Excluir MDF-e">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                    <button title="Transmitir MDF-e para SEFAZ" type="button" class="act-btn act-send"
                                                            onclick="transmitir('{{$item->id}}')">
                                                        <i class="ri-send-plane-line"></i>
                                                    </button>
                                                    @endif

                                                    {{-- Aprovado ou Cancelado: consultar --}}
                                                    @if($item->estado_emissao == 'aprovado' || $item->estado_emissao == 'cancelado')
                                                    <button title="Consultar Status MDF-e" type="button" class="act-btn act-xml"
                                                            onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-file-search-line"></i>
                                                    </button>
                                                    @endif

                                                    {{-- Sempre: alterar estado fiscal --}}
                                                    <a title="Alterar Estado Fiscal" class="act-btn act-state"
                                                       href="{{ route('mdfe.alterar-estado', $item->id) }}">
                                                        <i class="ri-arrow-up-down-line"></i>
                                                    </a>

                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            <span class="fw-bold font-monospace fs-13 text-primary">{{ $item->mdfe_numero > 0 ? $item->mdfe_numero : '--' }}</span>
                                        </td>
                                        <td class="text-muted fs-12 whitespace-nowrap">
                                            {{ __data_pt($item->created_at, 0) }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            @if($item->estado_emissao == 'aprovado')
                                                @if($item->encerrado)
                                                    <span class="pill pill-purple" title="MDF-e Encerrada na SEFAZ"><i class="ri-checkbox-circle-fill"></i> Encerrado</span>
                                                @else
                                                    <span class="pill pill-ok" title="MDF-e Autorizada em Viagem"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                                @endif
                                            @elseif($item->estado_emissao == 'cancelado')
                                                <span class="pill pill-no"><i class="ri-close-circle-fill"></i> Cancelado</span>
                                            @elseif($item->estado_emissao == 'rejeitado')
                                                <span class="pill pill-no"><i class="ri-error-warning-fill"></i> Rejeitado</span>
                                            @else
                                                <span class="pill pill-amber"><i class="ri-time-fill"></i> {{ ucfirst($item->estado_emissao) }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap">
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-12 font-monospace fw-bold">{{ $item->veiculoTracao ? $item->veiculoTracao->placa : '--' }}</span>
                                        </td>
                                        <td class="whitespace-nowrap" style="white-space: nowrap; max-width: 170px;">
                                            @if($item->condutor_nome)
                                                <div class="d-flex flex-column" title="{{ $item->condutor_nome }}{{ $item->condutor_cpf ? ' - CPF: ' . $item->condutor_cpf : '' }}">
                                                    <span class="fw-semibold text-dark fs-12 text-truncate" style="max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                                        {{ \Illuminate\Support\Str::limit($item->condutor_nome, 18) }}
                                                    </span>
                                                    @if($item->condutor_cpf)
                                                        <small class="text-muted font-monospace fs-11" style="white-space: nowrap;">{{ $item->condutor_cpf }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted fs-12">--</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap text-end" style="white-space: nowrap;">
                                            <span class="fw-semibold text-dark" style="white-space: nowrap;">{{ $item->quantidade_carga ? number_format($item->quantidade_carga, 2, ',', '.') : '--' }}&nbsp;<small class="text-muted">{{ $item->unidade_medida ?? 'KG' }}</small></span>
                                        </td>
                                        <td class="whitespace-nowrap text-end" style="white-space: nowrap;">
                                            <span class="fw-bold text-success" style="white-space: nowrap;">R$&nbsp;{{ __moeda($item->valor_carga) }}</span>
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">{{ $item->uf_inicio ?? '--' }}</span>
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">{{ $item->uf_fim ?? '--' }}</span>
                                        </td>
                                        <td class="whitespace-nowrap">
                                            @if($item->percurso && $item->percurso->count() > 0)
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-11">
                                                    {{ $item->percurso->pluck('uf')->implode(', ') }}
                                                </span>
                                            @else
                                                <span class="text-muted fs-12">--</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11">
                                            <div class="empty-state">
                                                <i class="ri-inbox-2-line"></i>
                                                <p>Nenhuma MDF-e encontrada para os filtros informados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                        <div></div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL CANCELAR MDF-e — DESIGN PREMIUM ═══ --}}
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

            {{-- Cabeçalho Vermelho --}}
            <div class="modal-mdfe-header-red d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0" id="modalCancelarLabel">
                        <i class="ri-close-circle-line me-1"></i> Cancelar MDF-e <span class="ref-numero"></span>
                    </h5>
                    <p class="modal-subtitle">O cancelamento do Manifesto será transmitido e homologado na SEFAZ</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">

                {{-- Alerta de Atenção Vermelho --}}
                <div class="modal-alert-danger-soft">
                    <i class="ri-alert-line" style="color:#ef4444; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                    <div>
                        <div style="font-size:12px; font-weight:700; color:#dc2626; margin-bottom:2px;">Atenção ao cancelar este Manifesto</div>
                        <p style="font-size:11.5px; color:#ef4444; margin:0; line-height:1.4;">Após o cancelamento autorizado pela SEFAZ, o MDF-e ficará sem validade fiscal e não poderá acobertar a viagem ou transporte.</p>
                    </div>
                </div>

                {{-- Card Dados do MDF-e --}}
                <div class="modal-mdfe-dados-card">
                    <span class="card-title"><i class="ri-file-list-3-line me-1"></i> Dados do Manifesto</span>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card-label">Número MDF-e</div>
                            <div class="card-val text-primary" id="cancela-card-numero">--</div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="card-label">Série</div>
                            <div class="card-val" id="cancela-card-serie">1</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Data de Emissão</div>
                            <div class="card-val" id="cancela-card-data">--</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="card-label">Veículo Tração (Placa)</div>
                            <div class="card-val font-monospace" id="cancela-card-veiculo">--</div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="card-label">Chave de Acesso SEFAZ</div>
                            <div style="font-size:12px; font-weight:700; color:#334155; word-break:break-all;" class="font-monospace" id="cancela-card-chave">--</div>
                        </div>
                    </div>
                </div>

                {{-- Campo Motivo --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">
                        Justificativa do Cancelamento <span class="text-danger">*</span> (mínimo 15 caracteres)
                    </label>
                    <textarea
                        id="inp-motivo-cancela"
                        name="motivo-cancela"
                        class="form-control"
                        rows="3"
                        maxlength="255"
                        style="border-radius:8px; border-color:#cbd5e1; font-size:13px; resize:vertical;"
                        placeholder="Informe a justificativa detalhada para o cancelamento deste MDF-e..."
                        required
                        minlength="15"
                        oninput="mdfeAtualizarContadorCancela(this)"></textarea>
                    <div class="mt-1 d-flex justify-content-between">
                        <span style="font-size:11px; color:#94a3b8;"><span id="cancela-char-count">0</span> de 255 caracteres utilizados</span>
                        <span style="font-size:11px; color:#ef4444; font-weight:600;">Mínimo: 15 caracteres</span>
                    </div>
                </div>

                {{-- Alerta Amarelo Importante --}}
                <div class="modal-alert-yellow">
                    <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <i class="ri-information-line" style="color:#ca8a04;"></i> Regras Importantes da SEFAZ
                    </div>
                    <ul>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> O cancelamento só é permitido se a viagem não tiver sido iniciada;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Prazo padrão de até 24 horas contadas a partir da autorização;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Após homologado na SEFAZ, o cancelamento não poderá ser revertido.</li>
                    </ul>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm px-4 fw-bold"
                    style="background:#ef4444; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(239,68,68,0.25);">
                    <i class="ri-close-circle-line"></i> Confirmar Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL ENCERRAR MDF-e — DESIGN PREMIUM ═══ --}}
<div class="modal fade" id="modal-encerrar" tabindex="-1" aria-labelledby="modalEncerrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">

            {{-- Cabeçalho Amarelo / Dourado / Âmbar --}}
            <div class="modal-mdfe-header-amber d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0" id="modalEncerrarLabel">
                        <i class="ri-stop-circle-line me-1"></i> Encerrar MDF-e na SEFAZ <span class="ref-numero-encerrar"></span>
                    </h5>
                    <p class="modal-subtitle">O encerramento do manifesto liberará os veículos e motoristas para novas viagens</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background:#fff;">

                {{-- Alerta Informativo Âmbar --}}
                <div class="modal-alert-amber-soft">
                    <i class="ri-flag-2-line" style="color:#d97706; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
                    <div>
                        <div style="font-size:12px; font-weight:700; color:#b45309; margin-bottom:2px;">Encerramento de Percurso / Viagem</div>
                        <p style="font-size:11.5px; color:#b45309; margin:0; line-height:1.4;">O encerramento é o ato que finaliza a operação de transporte na SEFAZ, liberando o veículo tração e condutores para novas emissões.</p>
                    </div>
                </div>

                {{-- Card Dados do MDF-e --}}
                <div class="modal-mdfe-dados-card">
                    <span class="card-title"><i class="ri-file-list-3-line me-1"></i> Dados do Manifesto Autorizado</span>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card-label">Número MDF-e</div>
                            <div class="card-val text-primary" id="encerrar-card-numero">--</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Protocolo SEFAZ</div>
                            <div class="card-val font-monospace" id="encerrar-card-protocolo">--</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Data de Emissão</div>
                            <div class="card-val" id="encerrar-card-data">--</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card-label">Veículo Tração (Placa)</div>
                            <div class="card-val font-monospace" id="encerrar-card-veiculo">--</div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="card-label">Chave de Acesso SEFAZ</div>
                            <div style="font-size:12px; font-weight:700; color:#334155; word-break:break-all;" class="font-monospace" id="encerrar-card-chave">--</div>
                        </div>
                    </div>
                </div>

                {{-- Campo Município de Encerramento --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:13px;">
                        <i class="ri-map-pin-line text-warning me-1"></i> Município de Encerramento (Destino Final)
                    </label>
                    <select name="municipio_encerramento" id="municipio_encerramento" class="form-select" style="width: 100%;">
                        <option value="">Selecione o município de encerramento (opcional)</option>
                        @isset($cidades)
                        @foreach($cidades as $c)
                        <option value="{{ $c->id }}">{{ $c->info }}</option>
                        @endforeach
                        @endisset
                    </select>
                    <small class="text-muted fs-11 mt-1 d-block">
                        <i class="ri-information-line me-1"></i> Caso não selecione um município específico, a SEFAZ utilizará o município da empresa emitente ou o destino do percurso.
                    </small>
                </div>

                {{-- Alerta Amarelo Importante --}}
                <div class="modal-alert-yellow">
                    <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <i class="ri-shield-check-line" style="color:#ca8a04;"></i> Orientações SEFAZ
                    </div>
                    <ul>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Encerre o MDF-e apenas após a conclusão da entrega das mercadorias;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> Sem o encerramento, o veículo constará bloqueado para novos manifestos na mesma UF;</li>
                        <li><span style="color:#eab308; font-weight:bold;">•</span> O evento de encerramento é definitivo e irreversível.</li>
                    </ul>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-3 gap-2" style="background:#fff;">
                <button type="button" class="btn btn-sm px-4 fw-medium" data-bs-dismiss="modal"
                    style="background:#f1f5f9; border:none; border-radius:6px; color:#374151;">Fechar</button>
                <button type="button" id="btn-encerrar" class="btn btn-sm px-4 fw-bold text-white"
                    style="background:#d97706; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(217,119,6,0.25);">
                    <i class="ri-stop-circle-line"></i> Confirmar Encerramento
                </button>
            </div>
        </div>
    </div>
</div>

@include('modals._importar_nfe')
@include('modals._importar_documentos')

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n"
            text += "Chave: " + chave + "\n"
            swal("", text, "warning")
        } else {
            let text = "Chave: " + chave + "\n"
            text += "Recibo: " + recibo + "\n"
            swal("", text, "success")
        }
    }
</script>
<script type="text/javascript" src="/js/mdfe.js"></script>
<script type="text/javascript" src="/js/mdfe_transmitir.js"></script>

<script type="text/javascript">
    // ─── Sobrescreve cancelar() com versão premium (card de dados) ───
    function cancelar(id, numero, serie, data, veiculo, chave) {
        IDNFE = id;
        $('.ref-numero').text(numero ? ('Nº ' + numero) : '');
        $('#cancela-card-numero').text(numero || '--');
        $('#cancela-card-serie').text(serie || '1');
        $('#cancela-card-data').text(data || '--');
        $('#cancela-card-veiculo').text(veiculo || '--');
        $('#cancela-card-chave').text(chave || '--');
        $('#inp-motivo-cancela').val('');
        $('#cancela-char-count').text('0');
        $('#modal-cancelar').modal('show');
    }

    // ─── Sobrescreve encerrar() com versão premium (card de dados) ───
    function encerrar(chave, protocolo, numero, data, veiculo) {
        CHAVE_ENCERRAR = chave;
        PROTOCOLO_ENCERRAR = protocolo;
        $('.ref-numero-encerrar').text(numero ? ('Nº ' + numero) : '');
        $('#encerrar-card-numero').text(numero || '--');
        $('#encerrar-card-protocolo').text(protocolo || '--');
        $('#encerrar-card-data').text(data || '--');
        $('#encerrar-card-veiculo').text(veiculo || '--');
        $('#encerrar-card-chave').text(chave || '--');
        $('#modal-encerrar').modal('show');
    }

    function mdfeAtualizarContadorCancela(el) {
        $('#cancela-char-count').text(el.value.length);
    }
</script>
@endsection
