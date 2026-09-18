@extends('layouts.app', ['title' => 'CTe - Conhecimento de Transporte'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card { border-radius: 14px; padding: 18px 20px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.07); }
.stat-card .stat-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 40px; opacity: .22; }
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card .stat-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; opacity: .85; margin-bottom: 4px; }
.stat-card .stat-val   { font-size: 22px; font-weight: 800; line-height: 1; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 12px 14px; border-bottom: 1px solid #e8eaf6; }
.tb-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
.tb-wrap tbody tr:hover { background: #fbfbfe; }
.tb-wrap tfoot td { background: #f8f9fc; font-weight: 700; font-size: 13.5px; padding: 12px 14px; border-top: 2px solid #e8eaf6; }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-amber { background: #fef3c7; color: #b45309; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-dark { background: #f1f5f9; color: #334155; }

/* ─── Ações ─── */
.act-group { display: inline-flex; gap: 4px; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }
.act-btn { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; border: 1px solid transparent; transition: all .15s ease; text-decoration: none; cursor: pointer; }
.act-btn:hover { transform: translateY(-1px); }
.act-btn-info    { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
.act-btn-info:hover    { background: #0284c7; color: #fff; }
.act-btn-success { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
.act-btn-success:hover { background: #16a34a; color: #fff; }
.act-btn-warn    { background: #fffbeb; color: #b45309; border-color: #fde68a; }
.act-btn-warn:hover    { background: #f59e0b; color: #fff; }
.act-btn-del     { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
.act-btn-del:hover     { background: #dc2626; color: #fff; }
.act-btn-dark    { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
.act-btn-dark:hover    { background: #334155; color: #fff; }

.chave-badge { font-family: monospace; font-size: 11px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 2px 6px; border-radius: 6px; max-width: 140px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
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
                                CTe - Conhecimento de Transporte
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie e emita Conhecimentos de Transporte Eletrônico, consulte status na SEFAZ e imprima DACTE.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @can('cte_create')
                            <a href="{{ route('cte.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Nova CTe
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIS) ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-blue">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Valor Transporte / Frete</div>
                                <div class="stat-val">R$ {{ __moeda($stats['valor_transporte'] ?? $data->sum('valor_transporte')) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-green">
                                <i class="ri-box-3-line stat-icon"></i>
                                <div class="stat-title">Valor Total da Carga</div>
                                <div class="stat-val">R$ {{ __moeda($stats['valor_carga'] ?? $data->sum('valor_carga')) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-purple">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Autorizadas / Aprovadas</div>
                                <div class="stat-val">{{ $stats['aprovado'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-amber">
                                <i class="ri-file-list-3-line stat-icon"></i>
                                <div class="stat-title">Total de Documentos</div>
                                <div class="stat-val">{{ $stats['total_cte'] ?? $data->total() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ FILTROS GLASS PREMIUM ═══ -->
                    <div class="modulo-glass-filter-premium p-3 mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3 col-6">
                                <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Data Início</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Data Fim</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Status de Emissão</label>
                                {!!Form::select('estado', '',
                                [
                                '' => 'Todos os Status',
                                'novo' => 'Nova / Rascunho',
                                'rejeitado' => 'Rejeitadas',
                                'cancelado' => 'Canceladas',
                                'aprovado' => 'Aprovadas / Autorizadas',
                                ])
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>

                            @if(__countLocalAtivo() > 1)
                            <div class="col-md-3 col-6">
                                <label class="form-label fs-11 text-uppercase fw-bold text-muted mb-1">Local / Filial</label>
                                {!!Form::select('local_id', '', ['' => 'Todos os Locais'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'select2 form-select'])
                                !!}
                            </div>
                            @endif

                            <div class="col-md-auto ms-auto d-flex gap-2">
                                <button class="dash-btn dash-btn-primary" type="submit">
                                    <i class="ri-search-line"></i> Filtrar
                                </button>
                                <a class="dash-btn dash-btn-light" href="{{ route('cte.index') }}">
                                    <i class="ri-eraser-line"></i> Limpar
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA DE CTES ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Remetente</th>
                                        <th>Destinatário</th>
                                        @if(__countLocalAtivo() > 1)
                                        <th>Local</th>
                                        @endif
                                        <th>Valor Transporte</th>
                                        <th>Valor Carga</th>
                                        <th>Número / Série</th>
                                        <th>Status Fiscal</th>
                                        <th>Data Emissão</th>
                                        <th>Chave de Acesso</th>
                                        <th>Origem</th>
                                        <th class="text-end" style="width: 220px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <strong class="text-dark d-block">{{ $item->remetente ? $item->remetente->razao_social : '--' }}</strong>
                                            @if($item->remetente && $item->remetente->cpf_cnpj)
                                            <span class="text-muted fs-11">{{ $item->remetente->cpf_cnpj }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark d-block">{{ $item->destinatario ? $item->destinatario->razao_social : '--' }}</span>
                                            @if($item->destinatario && $item->destinatario->cpf_cnpj)
                                            <span class="text-muted fs-11">{{ $item->destinatario->cpf_cnpj }}</span>
                                            @endif
                                        </td>
                                        @if(__countLocalAtivo() > 1)
                                        <td>
                                            <span class="badge bg-light text-secondary border">{{ $item->localizacao->descricao ?? '-' }}</span>
                                        </td>
                                        @endif
                                        <td>
                                            <strong class="text-primary fs-13">R$ {{ __moeda($item->valor_transporte) }}</strong>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">R$ {{ __moeda($item->valor_carga) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold fs-12 text-dark">#{{ $item->numero ?: '--' }}</span>
                                            @if($item->numero_serie)
                                            <small class="text-muted d-block fs-11">Série {{ $item->numero_serie }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->estado == 'aprovado')
                                            <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                            @elseif($item->estado == 'cancelado')
                                            <span class="pill pill-no"><i class="ri-close-circle-fill"></i> Cancelado</span>
                                            @elseif($item->estado == 'rejeitado')
                                            <span class="pill pill-amber"><i class="ri-error-warning-fill"></i> Rejeitado</span>
                                            @else
                                            <span class="pill pill-info"><i class="ri-file-edit-line"></i> Novo</span>
                                            @endif
                                        </td>
                                        <td class="fs-12 text-muted">{{ __data_pt($item->created_at, 1) }}</td>
                                        <td>
                                            @if($item->chave)
                                            <span class="chave-badge" title="{{ $item->chave }}">{{ $item->chave }}</span>
                                            @else
                                            <span class="text-muted fs-11">--</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->api)
                                            <span class="pill pill-dark fs-10 py-0 px-1">API</span>
                                            @else
                                            <span class="pill pill-info fs-10 py-0 px-1">Painel</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('cte.destroy', $item->id) }}" method="post"
                                                  id="form-{{$item->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @if($item->estado == 'cancelado')
                                                    <a class="act-btn act-btn-del" target="_blank" href="{{ route('cte.imprimir-cancela', [$item->id]) }}" title="Imprimir Cancelamento">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                    @endif

                                                    @if($item->estado == 'aprovado')
                                                    <a class="act-btn act-btn-info" target="_blank" href="{{ route('cte.imprimir', [$item->id]) }}" title="Imprimir DACTE">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                    <a class="act-btn act-btn-dark" target="_blank" href="{{ route('cte.download', [$item->id]) }}" title="Download XML">
                                                        <i class="ri-download-2-fill"></i>
                                                    </a>
                                                    <button title="Cancelar CTe" type="button" class="act-btn act-btn-del" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
                                                    <button title="Carta de Correção (CC-e)" type="button" class="act-btn act-btn-warn" onclick="corrigir('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-file-warning-line"></i>
                                                    </button>
                                                    @endif

                                                    @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                    <button type="button" class="act-btn act-btn-dark" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')" title="Informações do Envio">
                                                        <i class="ri-information-line"></i>
                                                    </button>
                                                    @endif

                                                    @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                    @can('cte_edit')
                                                    <a class="act-btn act-btn-warn" href="{{ route('cte.edit', $item->id) }}" title="Editar">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @endcan
                                                    <a target="_blank" title="Visualizar XML Temporário" class="act-btn act-btn-dark" href="{{ route('cte.xml-temp', $item->id) }}">
                                                        <i class="ri-file-code-line"></i>
                                                    </a>
                                                    @can('cte_delete')
                                                    <button type="button" class="act-btn act-btn-del btn-delete" title="Excluir">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endcan
                                                    <button title="Transmitir / Emitir CTe" type="button" class="act-btn act-btn-success" onclick="transmitir('{{$item->id}}')">
                                                        <i class="ri-send-plane-fill"></i>
                                                    </button>
                                                    @endif

                                                    @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                                    <button title="Consultar CTe na SEFAZ" type="button" class="act-btn act-btn-dark" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-file-search-line"></i>
                                                    </button>
                                                    @endif

                                                    <a title="Alterar Estado Fiscal" class="act-btn act-btn-dark" href="{{ route('cte.alterar-estado', $item->id) }}">
                                                        <i class="ri-arrow-up-down-line"></i>
                                                    </a>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ __countLocalAtivo() > 1 ? 11 : 10 }}" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ri-truck-line fs-36 text-secondary d-block mb-2"></i>
                                                <p class="mb-0 fs-13">Nenhum Conhecimento de Transporte Eletrônico encontrado.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="{{ __countLocalAtivo() > 1 ? 3 : 2 }}" class="text-uppercase fs-12 fw-bold text-muted">Soma na Página</td>
                                        <td class="text-primary fw-bold fs-14">R$ {{ __moeda($data->sum('valor_transporte')) }}</td>
                                        <td class="fw-bold fs-14">R$ {{ __moeda($data->sum('valor_carga')) }}</td>
                                        <td colspan="6"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 pt-2">
                        <small class="text-muted fs-12">
                            Mostrando {{ $data->firstItem() ?? 0 }} a {{ $data->lastItem() ?? 0 }} de {{ $data->total() }} registros
                        </small>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modal-cancelar-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-cancelar-label">Cancelar CTe <strong class="ref-numero text-danger"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {!!Form::text('motivo-cancela', 'Motivo do cancelamento')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="dash-btn dash-btn-danger">Cancelar CTe</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corrigir (CC-e) -->
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modal-corrigir-label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-corrigir-label">Carta de Correção CTe <strong class="ref-numero text-primary"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        {!! Form::select('grupo', 'Grupo')
                        ->attrs(['class' => 'form-select'])->required()
                        ->options(App\Models\Cte::gruposCte()) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::text('campo', 'Campo a Corrigir')->required() !!}
                    </div>
                    <div class="col-md-6">
                        {!!Form::text('motivo-corrigir', 'Novo Valor / Motivo da Correção')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="dash-btn dash-btn-primary">Transmitir Correção</button>
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
<script type="text/javascript" src="/js/cte_transmitir.js"></script>
@endsection

