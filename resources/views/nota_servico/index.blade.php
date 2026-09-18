@extends('layouts.app', ['title' => 'Notas de Serviço (NFSe)'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card { border-radius: 14px; padding: 18px 20px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.07); transition: transform .2s ease; }
.stat-card:hover { transform: translateY(-2px); }
.stat-card .stat-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 42px; opacity: .22; }
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-teal   { background: linear-gradient(135deg, #06b6d4, #0e7490); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.stat-card .stat-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; opacity: .85; margin-bottom: 4px; }
.stat-card .stat-val   { font-size: 22px; font-weight: 800; line-height: 1; }

/* ─── Filtro Padronizado ─── */
.modulo-glass-filter-premium { background: #ffffff; border: 1px solid #e8ecf4; border-radius: 14px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); margin-bottom: 22px; }
.modulo-glass-filter-premium label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 6px; }
.modulo-glass-filter-premium .form-control, .modulo-glass-filter-premium .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; }
.modulo-glass-filter-premium .form-control:focus, .modulo-glass-filter-premium .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
.tb-wrap tfoot td { background: #f8f9fc; padding: 13px 16px; font-weight: 700; border-top: 1px solid #e8eaf6; }

/* ─── Grade de Ações ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; }
.act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-view { background: #e0f2fe; color: #0284c7; }
.act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
.act-edit { background: #eef0ff; color: #4f46e5; }
.act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
.act-del { background: #fee2e2; color: #dc2626; }
.act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }
.act-send { background: #dcfce7; color: #15803d; }
.act-send:hover { box-shadow: 0 4px 12px rgba(21,128,61,.3); }
.act-query { background: #f1f5f9; color: #475569; }
.act-query:hover { box-shadow: 0 4px 12px rgba(71,85,105,.3); }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }
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
                                <i class="ri-file-paper-2-line"></i>
                                Notas de Serviço (NFS-e)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Emita e gerencie Notas Fiscais de Serviço Eletrônicas, consulte autorizações e imprima DANFSE.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @can('nfse_create')
                            <a href="{{ route('nota-servico.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-circle-line"></i> Nova NFS-e
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-blue">
                                <i class="ri-file-paper-2-line stat-icon"></i>
                                <div class="stat-title">Total de Notas</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Aprovadas / Emitidas</div>
                                <div class="stat-val">{{ $stats['aprovadas'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-amber">
                                <i class="ri-hourglass-fill stat-icon"></i>
                                <div class="stat-title">Pendentes / Novas</div>
                                <div class="stat-val">{{ $stats['novas'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-purple">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Total Aprovado (R$)</div>
                                <div class="stat-val" style="font-size: 19px;">R$ {{ __moeda($stats['total_valor']) }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS PADRONIZADOS ═══ -->
                    <div class="modulo-glass-filter-premium">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3 col-12">
                                <label for="tomador"><i class="ri-user-search-line me-1"></i> Tomador / Razão Social</label>
                                {!!Form::text('tomador', '')->attrs(['class' => 'form-control', 'placeholder' => 'Nome ou razão do tomador...'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label for="start_date"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label for="end_date"><i class="ri-calendar-line me-1"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-12">
                                <label for="estado"><i class="ri-toggle-line me-1"></i> Status</label>
                                {!!Form::select('estado', '',
                                ['' => 'Todos os Estados',
                                'novo' => 'Novas',
                                'rejeitado' => 'Rejeitadas',
                                'cancelado' => 'Canceladas',
                                'aprovado' => 'Aprovadas',
                                'processando' => 'Processando'])
                                ->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-3 col-12 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Pesquisar
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('nota-servico.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i> Limpar
                                    </a>
                                </div>
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
                                        <th>Tomador</th>
                                        <th>CPF/CNPJ</th>
                                        <th>Número</th>
                                        <th>Valor (R$)</th>
                                        <th>Estado</th>
                                        <th>Ambiente</th>
                                        <th>Data Emissão</th>
                                        <th>Chave de Acesso</th>
                                        <th class="text-end" style="width: 170px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $item->razao_social }}</td>
                                        <td class="text-nowrap text-muted">{{ $item->documento }}</td>
                                        
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold">
                                                {{ $item->numero_nfse ?: '--' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-success fs-14">R$ {{ __moeda($item->valor_total) }}</td>
                                        <td>
                                            @if($item->estado == 'aprovado')
                                            <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Aprovado</span>
                                            @elseif($item->estado == 'cancelado')
                                            <span class="pill pill-no"><i class="ri-close-circle-fill"></i> Cancelado</span>
                                            @elseif($item->estado == 'rejeitado')
                                            <span class="pill pill-amber"><i class="ri-error-warning-fill"></i> Rejeitado</span>
                                            @elseif($item->estado == 'processando')
                                            <span class="pill pill-info"><i class="ri-loader-4-line"></i> Processando</span>
                                            @else
                                            <span class="pill pill-info"><i class="ri-time-line"></i> Novo</span>
                                            @endif
                                        </td>
                                        <td class="fs-12 text-muted">
                                            {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                        </td>
                                        <td class="fs-12 text-dark"><i class="ri-calendar-line me-1 text-muted"></i>{{ __data_pt($item->created_at) }}</td>
                                        <td class="fs-11 text-muted" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->chave }}">
                                            {{ $item->chave ?: '--' }}
                                        </td>
                                        
                                        <td class="text-end">
                                            <form action="{{ route('nota-servico.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="act-group">
                                                    @if($item->estado == 'aprovado')
                                                    <a class="act-btn act-view" title="Imprimir NFSe" target="_blank" href="{{ route('nota-servico.imprimir', [$item->id]) }}">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                    <button title="Cancelar NFSe" type="button" class="act-btn act-del" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
                                                    @else
                                                    <a title="Visualizar PDF Temporário" class="act-btn act-view" href="{{ route('nota-servico.preview', [$item->id]) }}">
                                                        <i class="ri-file-ppt-line"></i>
                                                    </a>
                                                    @endif
                                                    
                                                    @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                        @can('nfse_edit')
                                                        <a class="act-btn act-edit" title="Editar" href="{{ route('nota-servico.edit', $item->id) }}">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        @endcan
                                                        
                                                        @can('nfse_delete')
                                                        <button type="button" title="Excluir" class="act-btn act-del btn-delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                        @endcan
                                                        
                                                        <button title="Transmitir NFSe" type="button" class="act-btn act-send" onclick="transmitir('{{$item->id}}')">
                                                            <i class="ri-send-plane-fill"></i>
                                                        </button>
                                                    @endif

                                                    <button title="Consultar NFSe" type="button" class="act-btn act-query" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                        <i class="ri-file-search-line"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="ri-file-paper-2-line fs-24 d-block mb-1"></i>
                                            Nenhuma NFS-e encontrada com os filtros selecionados.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if(sizeof($data) > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold text-muted">Total (página):</td>
                                        <td class="fw-bold text-success fs-15">R$ {{ __moeda($data->sum('valor_total')) }}</td>
                                        <td colspan="5"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header py-3 px-4 modulo-header-gradient">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2 mb-0" id="modalCancelarLabel">
                    <i class="ri-error-warning-fill text-warning"></i>
                    Cancelar NFS-e <strong class="ref-numero"></strong>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12 uf-field">
                        <label class="form-label required" for="inp-motivo-cancela"><i class="ri-chat-1-line me-1"></i>Motivo do Cancelamento</label>
                        {!!Form::text('motivo-cancela', '')->id('inp-motivo-cancela')->attrs(['class' => 'form-control', 'placeholder' => 'Informe o motivo detalhado...'])->required()!!}
                    </div>
                </div>
                <div class="alert alert-warning mt-3 mb-0 d-flex gap-2 align-items-center rounded-3">
                    <i class="ri-information-line fs-20"></i>
                    <span class="fs-12">Esta operação é irreversível. O cancelamento será registrado diretamente no Web Service municipal.</span>
                </div>
            </div>
            <div class="modal-footer border-top px-4 py-3 bg-light d-flex justify-content-end gap-2">
                <button type="button" class="dash-btn dash-btn-light px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Fechar
                </button>
                <button type="button" id="btn-cancelar" class="dash-btn dash-btn-danger px-4">
                    <i class="ri-close-circle-fill"></i> Confirmar Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="/js/nfse_transmitir.js"></script>
@endsection

