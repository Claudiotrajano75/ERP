@extends('layouts.app', ['title' => 'NFCe'])
@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
/* ─── Action Buttons ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 8px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }
/* ─── Footer ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-total-value { font-size: 20px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }
/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO GRADIENTE PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-bill-line"></i>
                            NFCe &mdash; Nota Fiscal de Consumidor Eletrônica
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie notas fiscais de consumidor: emissão, transmissão ao SEFAZ, cancelamento e impressão do DANFCE.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('nfce_view')
                        <a href="{{ route('nfce.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova NFCe
                        </a>
                        @endcan
                        <button id="btn-consulta-sefaz" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-refresh-line align-middle me-1"></i> Status SEFAZ
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                @if($contigencia != null)
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-start">
                    <i class="ri-error-warning-line me-2 fs-20 mt-0.5"></i>
                    <div>
                        <strong>Contingência Ativada!</strong>
                        Tipo: <strong>{{ $contigencia->tipo }}</strong> &mdash; Início: <strong>{{ __data_pt($contigencia->created_at) }}</strong>
                    </div>
                </div>
                @endif

                <!-- KPI Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total Emitido (pág.)</h4>
                                        <h3 class="my-2 text-white fs-18">R$ {{ __moeda($data->sum('total')) }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-bill-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Aprovadas (pág.)</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $data->where('estado', 'aprovado')->count() }}</h3>
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
                        <div class="card widget-icon-box text-bg-warning mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Pendentes (pág.)</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $data->where('estado', 'novo')->count() }}</h3>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-time-line"></i>
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
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Canceladas / Rejeit. (pág.)</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $data->whereIn('estado', ['cancelado', 'rejeitado'])->count() }}</h3>
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
                </div>

                <!-- Filtros de Busca -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado', [
                                'novo' => 'Novas',
                                'rejeitado' => 'Rejeitadas',
                                'cancelado' => 'Canceladas',
                                'aprovado' => 'Aprovadas',
                                '' => 'Todos'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-outline-secondary btn-sm px-3" href="{{ route('nfce.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela de NFCe -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Nº Nota</th>
                                    <th>Valor (R$)</th>
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    <th>Cadastro</th>
                                    <th>Emissão</th>
                                    <th class="text-end" style="min-width: 280px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $item->numero_sequencial }}</td>
                                    <!-- PADRÃO: Nome na linha principal + CPF/CNPJ em span abaixo -->
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome != "" ? $item->cliente_nome : "Consumidor Final") }}</span>
                                        <span class="text-muted fs-11">{{ $item->cliente ? $item->cliente->cpf_cnpj : ($item->cliente_cpf_cnpj != "" ? $item->cliente_cpf_cnpj : '--') }}</span>
                                    </td>
                                    @if(__countLocalAtivo() > 1)
                                    <td class="text-danger fw-bold fs-12">{{ $item->localizacao->descricao ?? '' }}</td>
                                    @endif
                                    <td class="fw-bold">{{ $item->numero ?: '--' }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
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
                                        <span class="badge bg-light text-dark border fs-11">
                                            {{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}
                                        </span>
                                    </td>
                                    <td class="fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td class="fs-12">{{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : '--' }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('nfce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group justify-content-end">

                                                @if($item->estado == 'aprovado')
                                                <a class="btn btn-primary btn-sm text-white" title="Imprimir NFCe" target="_blank" href="{{ route('nfce.imprimir', [$item->id]) }}">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @can('nfce_transmitir')
                                                <button title="Cancelar NFCe" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                @endcan
                                                @endif

                                                @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                <button title="Detalhes do Retorno SEFAZ" type="button" class="btn btn-dark btn-sm text-white" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                    <i class="ri-file-line"></i>
                                                </button>
                                                @endif

                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                @can('nfce_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('nfce.edit', $item->id) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                <a title="XML temporário" class="btn btn-light btn-sm" href="{{ route('nfce.xml-temp', $item->id) }}">
                                                    <i class="ri-file-code-line"></i>
                                                </a>
                                                @can('nfce_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                                @can('nfce_transmitir')
                                                <button title="Transmitir NFCe" type="button" class="btn btn-success btn-sm text-white" onclick="transmitir('{{$item->id}}')">
                                                    <i class="ri-send-plane-fill"></i>
                                                </button>
                                                @endcan
                                                @endif

                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                                <button title="Consultar NFCe" type="button" class="btn btn-light btn-sm" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-search-line"></i>
                                                </button>
                                                @endif

                                                @can('nfce_edit')
                                                <a title="Alterar estado fiscal" class="btn btn-secondary btn-sm text-white" href="{{ route('nfce.alterar-estado', $item->id) }}">
                                                    <i class="ri-arrow-up-down-line"></i>
                                                </a>
                                                @endcan

                                                <a class="btn btn-light btn-sm" title="Detalhes" href="{{ route('nfce.show', $item->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a class="btn btn-danger btn-sm text-white" title="DANFCE Temporária" target="_blank" href="{{ route('nfce.danfce-temporaria', [$item->id]) }}">
                                                    <i class="ri-printer-fill"></i>
                                                </a>

                                                @if($item->estado == 'aprovado')
                                                <button title="Enviar Email" type="button" class="btn btn-light btn-sm" onclick="enviarEmail('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-mail-send-line"></i>
                                                </button>
                                                <a title="Download XML" href="{{ route('nfce.download-xml', [$item->id]) }}" class="btn btn-dark btn-sm text-white">
                                                    <i class="ri-download-line"></i>
                                                </a>
                                                @endif

                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ __countLocalAtivo() > 1 ? 10 : 9 }}">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma NFCe encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total NFCes no Grid:</span><br>
                        <span class="modulo-total-value">R$ {{ __moeda($data->sum('total')) }}</span>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar NFCe -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCancelarLabel">Cancelar NFCe <strong class="ref-numero text-danger"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-cancela', 'Motivo da Justificativa (mín. 15 caracteres)')->required()!!}
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

<!-- Modal Enviar Email NFCe -->
<div class="modal fade" id="modal-email" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmailLabel">Enviar NFCe por E-mail <strong class="ref-numero text-primary"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-12">
                        {!!Form::text('email', 'Endereço de E-mail')->required()->type('email')!!}
                    </div>
                    <div class="col-md-4 mt-2">
                        {!!Form::checkbox('danfe', 'Incluir DANFCE')!!}
                    </div>
                    <div class="col-md-4 mt-2">
                        {!!Form::checkbox('xml', 'Incluir XML')!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-enviar-email" class="btn btn-success btn-sm">Enviar E-mail</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n\n"
            text += "Chave: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave: " + chave + "\n"
            text += "Recibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }

    $('#btn-consulta-sefaz').click(() => {
        $.post(path_url + 'api/nfce_painel/consulta-status-sefaz', { 
            empresa_id: $('#empresa_id').val(),
            usuario_id: $('#usuario_id').val(),
        })
        .done((res) => {
            let msg = "cStat: " + res.cStat
            msg += "\nMotivo: " + res.xMotivo
            msg += "\nAmbiente: " + (res.tpAmb == 2 ? "Homologação" : "Produção")
            msg += "\nverAplic: " + res.verAplic
            swal("Status SEFAZ", msg, "success")
        })
        .fail((err) => {
            try { swal("Erro", err.responseText, "error") }
            catch { swal("Erro", "Algo deu errado", "error") }
        })
    })
</script>
<script type="text/javascript" src="/js/nfce_transmitir.js"></script>
@endsection
