@extends('layouts.app', ['title' => 'CTe OS — Outros Serviços'])

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
    transition: transform .2s ease;
}
.stat-card:hover { transform: translateY(-2px); }
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
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }

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
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8eaf6;
    white-space: nowrap;
}
.tb-wrap tbody td {
    padding: 13px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13.5px;
    color: #374151;
}
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de Ações ─── */
.act-group { display: inline-flex; gap: 5px; align-items: center; justify-content: flex-end; }
.act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
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

/* ─── Estado Vazio ─── */
.empty-state { padding: 52px 20px; text-align: center; }
.empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
.empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-truck-line"></i>
                                CTe OS — Outros Serviços
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie Conhecimentos de Transporte Eletrônico para Outros Serviços (fretamento, passageiros e excesso de bagagem).
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @can('cte_os_create')
                            <a href="{{ route('cte-os.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Nova CTe OS
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-blue">
                                <i class="ri-file-list-3-line stat-icon"></i>
                                <div class="stat-title">Total de Documentos</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Aprovadas / Autorizadas</div>
                                <div class="stat-val">{{ $stats['aprovadas'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-amber">
                                <i class="ri-close-circle-line stat-icon"></i>
                                <div class="stat-title">Canceladas</div>
                                <div class="stat-val">{{ $stats['canceladas'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-purple">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Total Aprovado (R$)</div>
                                <div class="stat-val" style="font-size: 20px;">R$ {{ __moeda($stats['valor']) }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS PADRONIZADOS ═══ -->
                    <div class="modulo-glass-filter-premium">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
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
                                    ['novo' => 'Novas',
                                     'rejeitado' => 'Rejeitadas',
                                     'cancelado' => 'Canceladas',
                                     'aprovado' => 'Aprovadas',
                                     '' => 'Todos os Status'])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            @if(__countLocalAtivo() > 1)
                            <div class="col-md-3 col-6">
                                <label for="local_id"><i class="ri-map-pin-line me-1"></i> Local / Filial</label>
                                {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'select2 form-select'])
                                !!}
                            </div>
                            @endif
                            <div class="col-md-auto ms-auto col-12 d-flex gap-2">
                                <button class="dash-btn dash-btn-primary" type="submit" title="Buscar CTe OS">
                                    <i class="ri-search-line"></i> Filtrar
                                </button>
                                <a class="dash-btn dash-btn-light" href="{{ route('cte-os.index') }}" title="Limpar Filtros">
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
                                        <th>Emitente</th>
                                        <th>Tomador</th>
                                        @if(__countLocalAtivo() > 1)
                                        <th>Local</th>
                                        @endif
                                        <th>Valor Serviço</th>
                                        <th>Valor a Receber</th>
                                        <th>Status SEFAZ</th>
                                        <th>Nº Doc</th>
                                        <th>Data Cadastro</th>
                                        <th>Origem</th>
                                        <th class="text-end" style="min-width:180px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $item->emitente ? $item->emitente->razao_social : '--' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-secondary">{{ $item->tomador_cli ? $item->tomador_cli->razao_social : '--' }}</span>
                                        </td>
                                        @if(__countLocalAtivo() > 1)
                                        <td>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">
                                                {{ $item->localizacao ? $item->localizacao->descricao : '' }}
                                            </span>
                                        </td>
                                        @endif
                                        <td class="fw-bold text-dark">R$ {{ __moeda($item->valor_transporte) }}</td>
                                        <td class="fw-bold text-primary">R$ {{ __moeda($item->valor_receber) }}</td>
                                        <td>
                                            @if($item->estado_emissao == 'aprovado')
                                                <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                            @elseif($item->estado_emissao == 'cancelado')
                                                <span class="pill pill-no"><i class="ri-close-circle-fill"></i> Cancelado</span>
                                            @elseif($item->estado_emissao == 'rejeitado')
                                                <span class="pill pill-no"><i class="ri-error-warning-fill"></i> Rejeitado</span>
                                            @else
                                                <span class="pill pill-amber"><i class="ri-time-fill"></i> {{ ucfirst($item->estado_emissao) }}</span>
                                            @endif
                                        </td>
                                        <td><span class="fw-bold font-monospace">{{ $item->numero ?? '--' }}</span></td>
                                        <td class="text-muted fs-12">{{ __data_pt($item->created_at, 1) }}</td>
                                        <td>
                                            @if($item->api)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">API</span>
                                            @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">Painel</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('cte-os.destroy', $item->id) }}" method="post"
                                                  id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">

                                                    {{-- Cancelado: imprimir cancelamento --}}
                                                    @if($item->estado_emissao == 'cancelado')
                                                    <a class="act-btn act-cancel" target="_blank"
                                                       href="{{ route('cte-os.imprimir-cancela', [$item->id]) }}" title="Imprimir Cancelamento">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                    @endif

                                                    {{-- Aprovado: imprimir, download, cancelar --}}
                                                    @if($item->estado_emissao == 'aprovado')
                                                    <a class="act-btn act-print" target="_blank"
                                                       href="{{ route('cte-os.imprimir', [$item->id]) }}" title="Imprimir DACTE OS">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                    <a class="act-btn act-xml" target="_blank"
                                                       href="{{ route('cte-os.download', [$item->id]) }}" title="Download XML">
                                                        <i class="ri-download-2-line"></i>
                                                    </a>
                                                    <button title="Cancelar CTe OS" type="button" class="act-btn act-cancel"
                                                            onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
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
                                                    @can('cte_os_edit')
                                                    <a class="act-btn act-edit"
                                                       href="{{ route('cte-os.edit', $item->id) }}" title="Editar CTe OS">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan
                                                    <a target="_blank" title="XML Temporário" class="act-btn act-xml"
                                                       href="{{ route('cte-os.xml-temp', $item->id) }}">
                                                        <i class="ri-file-code-line"></i>
                                                    </a>
                                                    @can('cte_os_delete')
                                                    <button type="button" class="act-btn act-cancel btn-delete" title="Excluir CTe OS">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                    <button title="Transmitir CTe OS para SEFAZ" type="button" class="act-btn act-send"
                                                            onclick="transmitir('{{$item->id}}')">
                                                        <i class="ri-send-plane-line"></i>
                                                    </button>
                                                    @endif

                                                    {{-- Aprovado ou Cancelado: consultar --}}
                                                    @if($item->estado_emissao == 'aprovado' || $item->estado_emissao == 'cancelado')
                                                    <button title="Consultar Status CTe" type="button" class="act-btn act-xml"
                                                            onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-file-search-line"></i>
                                                    </button>
                                                    @endif

                                                    {{-- Sempre: alterar estado fiscal --}}
                                                    <a title="Alterar Estado Fiscal" class="act-btn act-state"
                                                       href="{{ route('cte-os.alterar-estado', $item->id) }}">
                                                        <i class="ri-arrow-up-down-line"></i>
                                                    </a>

                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10">
                                            <div class="empty-state">
                                                <i class="ri-inbox-2-line"></i>
                                                <p>Nenhuma CTe OS encontrada para os filtros informados.</p>
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

{{-- ═══ MODAL CANCELAR ═══ --}}
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modal-cancelar-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white" id="modal-cancelar-label"><i class="ri-close-circle-line me-1"></i> Cancelar CTe OS <strong class="ref-numero"></strong></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold fs-13">Justificativa do Cancelamento (mínimo 15 caracteres)</label>
                        {!!Form::text('motivo-cancela', '')->attrs(['class' => 'form-control', 'placeholder' => 'Informe o motivo do cancelamento...'])->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="dash-btn dash-btn-danger">
                    <i class="ri-close-circle-line"></i> Confirmar Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL CORRIGIR ═══ --}}
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modal-corrigir-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modal-corrigir-label"><i class="ri-edit-2-line me-1"></i> Carta de Correção CTe OS <strong class="ref-numero"></strong></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold fs-13">Grupo</label>
                        {!! Form::select('grupo', '', App\Models\Cte::gruposCte())
                        ->attrs(['class' => 'form-select'])->required() !!}
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold fs-13">Campo a Corrigir</label>
                        {!! Form::text('campo', '')->attrs(['class' => 'form-control'])->required() !!}
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold fs-13">Motivo da Correção</label>
                        {!!Form::text('motivo-corrigir', '')->attrs(['class' => 'form-control'])->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="dash-btn dash-btn-primary">
                    <i class="ri-send-plane-line"></i> Transmitir Correção
                </button>
            </div>
        </div>
    </div>
</div>

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
<script type="text/javascript" src="/js/cte_os_transmitir.js"></script>
@endsection
