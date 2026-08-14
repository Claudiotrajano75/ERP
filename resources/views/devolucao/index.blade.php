@extends('layouts.app', ['title' => 'Devoluções'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
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
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Botões de Ação do Formulário / Grid ─── */
.modulo-action-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; flex-wrap: nowrap !important; }
.modulo-action-group .btn { padding: 5px 8px; font-size: 12px; border-radius: 6px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">
            
            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-arrow-go-back-line"></i>
                            Painel de Devoluções de Mercadorias
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as notas fiscais de devolução, importando o XML original e configurando os dados de retorno ao fornecedor.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        @can('devolucao_create')
                        <a href="{{ route('devolucao.xml') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-file-upload-line align-middle me-1"></i> Nova Devolução
                        </a>
                        @endcan
                        @if(__isPlanoFiscal())
                        <button id="btn-consulta-sefaz" class="btn btn-dark btn-sm px-3">
                            <i class="ri-refresh-line align-middle me-1"></i> Status SEFAZ
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Devoluções
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-truck-line"></i> Fornecedor</label>
                            {!!Form::select('fornecedor_id', '')->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        @if(__isPlanoFiscal())
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-equalizer-line"></i> Estado NFe</label>
                            {!!Form::select('estado', '', [
                                'novo' => 'Novas',
                                'rejeitado' => 'Rejeitadas',
                                'cancelado' => 'Canceladas',
                                'aprovado' => 'Aprovadas',
                                '' => 'Todos'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-1 col-6">
                            <label class="form-label"><i class="ri-arrow-left-right-line"></i> Tipo</label>
                            {!!Form::select('tpNF', '', [
                                '1' => 'Saída',
                                '0' => 'Entrada',
                                '' => 'Todos'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        @endif
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                            {!!Form::select('local_id', '')->options(['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif
                        <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('devolucao.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap mb-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fornecedor / Cliente</th>
                                    <th>CPF / CNPJ</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Nº Nota</th>
                                    <th>Valor Total (R$)</th>
                                    @if(__isPlanoFiscal())
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    @endif
                                    <th>Data</th>
                                    <th>Emissão</th>
                                    <th>CRT</th>
                                    <th>Tipo</th>
                                    <th class="text-end" style="width: 220px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>{{ $item->numero_sequencial }}</td>
                                    @if($item->cliente)
                                    <td class="fw-semibold text-dark">{{ $item->cliente->razao_social }}</td>
                                    <td class="fw-bold text-muted">{{ $item->cliente->cpf_cnpj }}</td>
                                    @else
                                    <td class="fw-semibold text-dark">{{ $item->fornecedor ? $item->fornecedor->razao_social : '--' }}</td>
                                    <td class="fw-bold text-muted">{{ $item->fornecedor ? $item->fornecedor->cpf_cnpj : '--' }}</td>
                                    @endif
                                    @if(__countLocalAtivo() > 1)
                                    <td class="text-danger fw-bold">{{ $item->localizacao ? $item->localizacao->descricao : '' }}</td>
                                    @endif
                                    <td class="fw-bold">{{ $item->numero ?: '--' }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                    @if(__isPlanoFiscal())
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                        </span>
                                    </td>
                                    @endif
                                    <td>{{ __data_pt($item->created_at) }}</td>
                                    <td>
                                        @if($item->api)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">API</span>
                                        @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Painel</span>
                                        @endif
                                    </td>
                                    <td class="fs-11 text-muted">
                                        @if($item->crt == 1) Simples Nacional
                                        @elseif($item->crt == 2) Simples Exc. Sublimite
                                        @elseif($item->crt == 3) Regime Normal
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tpNF)
                                        <span class="fw-bold text-success">Saída</span>
                                        @else
                                        <span class="fw-bold text-primary">Entrada</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('devolucao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @if($item->estado == 'cancelado')
                                                <a class="btn btn-danger btn-sm text-white" target="_blank" href="{{ route('nfe.imprimir-cancela', [$item->id]) }}" title="Imprimir Cancelamento">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @endif

                                                @if($item->estado == 'aprovado')
                                                <button type="button" onclick="imprimir('{{$item->id}}', '{{$item->numero}}')" class="btn btn-primary btn-sm text-white" title="Imprimir NFe">
                                                    <i class="ri-printer-line"></i>
                                                </button>
                                                @can('nfe_transmitir')
                                                <button title="Cancelar NFe" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                <button title="Corrigir NFe (CC-e)" type="button" class="btn btn-warning btn-sm text-white" onclick="corrigir('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-warning-line"></i>
                                                </button>
                                                @endcan
                                                @endif

                                                @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                <button title="Ver Detalhes do Retorno" type="button" class="btn btn-dark btn-sm text-white" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                    <i class="ri-file-line"></i>
                                                </button>
                                                @endif

                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                @can('devolucao_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('devolucao.edit', $item->id) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @if(__isPlanoFiscal())
                                                <a target="_blank" title="XML Temporário" class="btn btn-light btn-sm" href="{{ route('nfe.xml-temp', $item->id) }}">
                                                    <i class="ri-file-code-line"></i>
                                                </a>
                                                @endif
                                                @can('devolucao_delete')
                                                <button title="Excluir Devolução" type="button" class="btn btn-danger btn-sm btn-delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                                @if(__isPlanoFiscal())
                                                @can('nfe_transmitir')
                                                <button title="Transmitir NFe ao SEFAZ" type="button" class="btn btn-success btn-sm text-white" onclick="transmitir('{{$item->id}}')">
                                                    <i class="ri-send-plane-fill"></i>
                                                </button>
                                                @endcan
                                                @endif
                                                @endif

                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                                <button title="Consultar Protocolo SEFAZ" type="button" class="btn btn-light btn-sm" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-search-line"></i>
                                                </button>
                                                @endif

                                                @if(__isPlanoFiscal())
                                                @can('devolucao_edit')
                                                <a title="Alterar Estado Fiscal" class="btn btn-secondary btn-sm text-white" href="{{ route('nfe.alterar-estado', [$item->id, 'tipo=devolucao']) }}">
                                                    <i class="ri-arrow-up-down-line"></i>
                                                </a>
                                                @endcan
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma devolução cadastrada no período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação & Rodapé de Valores -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark">Total das Devoluções no Grid: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Imprimir NFe -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrintLabel">Imprimir Nota de Devolução <strong class="ref-numero text-primary"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-success w-100 btn-sm" onclick="gerarDanfe('danfe')">
                            <i class="ri-printer-line me-1"></i> DANFE Padrão
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-primary w-100 btn-sm" onclick="gerarDanfe('simples')">
                            <i class="ri-printer-line me-1"></i> DANFE Simples
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-dark w-100 btn-sm" onclick="gerarDanfe('etiqueta')">
                            <i class="ri-printer-line me-1"></i> DANFE Etiqueta
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar NFe -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCancelarLabel">Cancelar NFe <strong class="ref-numero text-danger"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-cancela', 'Motivo da Justificativa (Mínimo 15 caracteres)')->required()->attrs(['class' => 'form-control'])!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm">Confirmar Cancelamento</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corrigir NFe -->
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCorrigirLabel">Emitir Carta de Correção <strong class="ref-numero text-warning"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-corrigir', 'Texto de Correção (Mínimo 15 caracteres)')->required()->attrs(['class' => 'form-control'])!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="btn btn-warning btn-sm">Transmitir Correção</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo Rejeição: " + motivo_rejeicao + "\n\n"
            text += "Chave Acesso: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave Acesso: " + chave + "\n"
            text += "Número Recibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }

    $('#btn-consulta-sefaz').click(() => {
        $.post(path_url + 'api/nfe_painel/consulta-status-sefaz', {
            usuario_id: $('#usuario_id').val(),
            empresa_id: $('#empresa_id').val()
        })
        .done((res) => {
            let msg = "cStat: " + res.cStat
            msg += "\nMotivo: " + res.xMotivo
            msg += "\nAmbiente: " + (res.tpAmb == 2 ? "Homologação" : "Produção")
            msg += "\nVerAplic: " + res.verAplic
            swal("Status SEFAZ", msg, "success")
        })
        .fail((err) => {
            try { swal("Erro", err.responseText, "error") }
            catch { swal("Erro", "Algo deu errado", "error") }
        })
    })
</script>
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>
@endsection
