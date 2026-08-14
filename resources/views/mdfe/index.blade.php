@extends('layouts.app', ['title' => 'MDF-e'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* --- Novo Filtro de Pesquisa Premium --- */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    padding: 20px !important;
    margin-bottom: 24px;
}

/* Título e Header do Filtro */
.filtro-premium-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f3f9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.filtro-premium-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.filtro-premium-title i {
    color: #5572f5;
    margin-right: 6px;
}

/* Customização dos Inputs dentro do Filtro */
.modulo-glass-filter-premium label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c8ca6 !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 4px;
}
.modulo-glass-filter-premium label i {
    font-size: 12px;
    color: #a8a8c0;
}

.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 38px !important;
    border-radius: 8px !important;
    border: 1px solid #dcdce9 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #374151 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}

.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #5572f5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
}

/* Botões do Filtro */
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
}

.modulo-glass-filter-premium .btn-limpar {
    background: #f1f3f9 !important;
    border: 1px solid #e2e5ec !important;
    color: #5a5a7a !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e8ebf3 !important;
    color: #302b63 !important;
}
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; white-space: nowrap; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Action Buttons ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-road-map-line"></i>
                            MDF-e
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Manifesto Eletrônico de Documentos Fiscais.
                        </p>
                    </div>
                    <div class="d-inline-flex gap-2 flex-wrap">
                        @can('mdfe_create')
                        <a href="{{ route('mdfe.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova MDF-e
                        </a>
                        <button class="btn btn-light btn-sm px-3 text-dark" id="btn-importar_nfe"
                            data-bs-toggle="modal" data-bs-target="#modal-importar_nfe">
                            <i class="ri-file-upload-line align-middle me-1"></i> Selecionar NFe
                        </button>
                        @endcan
                        <a href="{{ route('mdfe.nao-encerrados') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-alert-line align-middle me-1"></i> Não Encerrados
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">MDF-e no período</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-file-list-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Aprovadas</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['aprovadas'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Transmitidas com sucesso</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-danger mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Canceladas</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['canceladas'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">MDF-e canceladas</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-close-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-warning mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Valor</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($stats['valor']) }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cargas aprovadas</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Filtros de Busca Premium ═══ --}}
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar MDF-e
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-equalizer-line"></i> Estado</label>
                            {!!Form::select('estado', '',
                                ['novo'      => 'Nova',
                                 'rejeitado' => 'Rejeitadas',
                                 'cancelado' => 'Canceladas',
                                 'aprovado'  => 'Aprovadas',
                                 ''          => 'Todos'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                            {!!Form::select('local_id', '', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])
                            !!}
                        </div>
                        @endif
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('mdfe.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ TABELA PREMIUM ═══ --}}
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Início Viagem</th>
                                    <th>Criação</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>CNPJ Contratante</th>
                                    <th>Estado</th>
                                    <th>Nº</th>
                                    <th>Veículo Tração</th>
                                    <th>Qtd Carga</th>
                                    <th>Valor Carga</th>
                                    <th>Origem</th>
                                    <th class="text-end" style="min-width:220px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="text-muted fs-12">{{ __data_pt($item->data_inicio_viagem, 0) }}</td>
                                    <td class="text-muted fs-12">{{ __data_pt($item->created_at, 0) }}</td>
                                    @if(__countLocalAtivo() > 1)
                                    <td>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">
                                            {{ $item->localizacao->descricao }}
                                        </span>
                                    </td>
                                    @endif
                                    <td class="fs-12 text-muted">{{ $item->cnpj_contratante }}</td>
                                    <td>{!! $item->estadoEmissao($item->estado_emissao) !!}</td>
                                    <td><span class="fw-semibold">{{ $item->mdfe_numero > 0 ? $item->mdfe_numero : '--' }}</span></td>
                                    <td class="fw-semibold">{{ $item->veiculoTracao->marca }} — {{ $item->veiculoTracao->placa }}</td>
                                    <td>{{ $item->quantidade_carga }}</td>
                                    <td class="fw-semibold">R$ {{ __moeda($item->valor_carga) }}</td>
                                    <td>
                                        @if($item->api)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">API</span>
                                        @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">Painel</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('mdfe.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">

                                                {{-- Aprovado: imprimir, download, cancelar --}}
                                                @if($item->estado_emissao == 'aprovado')
                                                <a class="btn btn-primary btn-sm text-white" target="_blank"
                                                   href="{{ route('mdfe.imprimir', [$item->id]) }}" title="Imprimir">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                <a class="btn btn-secondary btn-sm text-white" target="_blank"
                                                   href="{{ route('mdfe.download', [$item->id]) }}" title="Download XML">
                                                    <i class="ri-download-2-line"></i>
                                                </a>
                                                <button title="Cancelar MDF-e" type="button" class="btn btn-danger btn-sm"
                                                        onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                @endif

                                                {{-- Aprovado ou Rejeitado: consultar chave --}}
                                                @if($item->estado_emissao == 'aprovado' || $item->estado_emissao == 'rejeitado')
                                                <button type="button" title="Consultar Chave" class="btn btn-dark btn-sm"
                                                        onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                    <i class="ri-file-line"></i>
                                                </button>
                                                @endif

                                                {{-- Novo ou Rejeitado: editar, xml temp, excluir, transmitir --}}
                                                @if($item->estado_emissao == 'novo' || $item->estado_emissao == 'rejeitado')
                                                @can('mdfe_edit')
                                                <a class="btn btn-warning btn-sm text-white"
                                                   href="{{ route('mdfe.edit', $item->id) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                <a target="_blank" title="XML Temporário" class="btn btn-light btn-sm"
                                                   href="{{ route('mdfe.xml-temp', $item->id) }}">
                                                    <i class="ri-file-code-line"></i>
                                                </a>
                                                @can('mdfe_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                                <button title="Transmitir MDF-e" type="button" class="btn btn-success btn-sm"
                                                        onclick="transmitir('{{$item->id}}')">
                                                    <i class="ri-send-plane-line"></i>
                                                </button>
                                                @endif

                                                {{-- Aprovado ou Cancelado: consultar --}}
                                                @if($item->estado_emissao == 'aprovado' || $item->estado_emissao == 'cancelado')
                                                <button title="Consultar MDF-e" type="button" class="btn btn-light btn-sm"
                                                        onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-search-line"></i>
                                                </button>
                                                @endif

                                                {{-- Sempre: alterar estado fiscal --}}
                                                <a title="Alterar Estado Fiscal" class="btn btn-dark btn-sm"
                                                   href="{{ route('mdfe.alterar-estado', $item->id) }}">
                                                    <i class="ri-arrow-up-down-line"></i>
                                                </a>

                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum registro encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ FOOTER (Paginação) ═══ --}}
                <div class="modulo-footer">
                    <div></div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL CANCELAR ═══ --}}
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modal-cancelar-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-cancelar-label">Cancelar MDF-e <strong class="ref-numero"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        {!!Form::text('motivo-cancela', 'Motivo')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger">
                    <i class="ri-close-circle-line align-middle me-1"></i> Cancelar MDF-e
                </button>
            </div>
        </div>
    </div>
</div>

@include('modals._importar_nfe')

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
@endsection
