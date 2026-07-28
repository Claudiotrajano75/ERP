@extends('layouts.app', ['title' => 'CTe - Lista Geral'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; white-space: nowrap; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

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

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-truck-line"></i>
                            CTe - Lista Todas as Empresas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Visualize todos os CTe emitidos por todas as empresas.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ FILTROS GLASS ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::select('empresa', 'Empresa')
                            ->attrs(['class' => 'select2'])
                            ->options($empresa != null ? [$empresa->id => $empresa->info] : [])
                            !!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data início')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data fim')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado',
                            ['' => 'Todos',
                            'novo' => 'Nova',
                            'rejeitado' => 'Rejeitadas',
                            'cancelado' => 'Canceladas',
                            'aprovado' => 'Aprovadas'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-1 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i>
                            </button>
                        </div>
                        <div class="col-md-1 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('cte-all') }}">
                                <i class="ri-eraser-line me-1"></i>
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Remetente</th>
                                    <th>Destinatário</th>
                                    <th>Número</th>
                                    <th>Valor Transporte</th>
                                    <th>Valor Carga</th>
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    <th>Data</th>
                                    <th class="text-end" style="width: 180px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->empresa->nome }}</span>
                                        <span class="text-muted fs-11">{{ $item->empresa->cpf_cnpj }}</span>
                                    </td>
                                    <td>{{ $item->remetente ? $item->remetente->razao_social : '--' }}</td>
                                    <td>{{ $item->destinatario ? $item->destinatario->razao_social : '--' }}</td>
                                    <td><span class="fw-semibold">{{ $item->numero ?: '--' }}</span></td>
                                    <td class="fw-semibold">R$ {{ __moeda($item->valor_transporte) }}</td>
                                    <td>R$ {{ __moeda($item->valor_carga) }}</td>
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-double-line me-1"></i>Aprovado
                                        </span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-close-line me-1"></i>Cancelado
                                        </span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                            <i class="ri-error-warning-line me-1"></i>Rejeitado
                                        </span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">
                                            <i class="ri-file-line me-1"></i>Novo
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->ambiente == 2)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Homologação</span>
                                        @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Produção</span>
                                        @endif
                                    </td>
                                    <td class="fs-12">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('cte.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @if($item->estado == 'cancelado')
                                                <a class="btn btn-danger btn-sm text-white" target="_blank" href="{{ route('cte.imprimir-cancela', [$item->id]) }}" title="Imprimir Cancelamento">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @endif
                                                @if($item->estado == 'aprovado')
                                                <a class="btn btn-primary btn-sm text-white" target="_blank" href="{{ route('cte.imprimir', [$item->id]) }}" title="Imprimir DACTE">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                <button title="Cancelar CTe" type="button" class="btn btn-danger btn-sm" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                @endif
                                                @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                <button type="button" class="btn btn-dark btn-sm" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')" title="Informações">
                                                    <i class="ri-file-line"></i>
                                                </button>
                                                @endif
                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                <a target="_blank" title="XML temporário" class="btn btn-light btn-sm" href="{{ route('cte.xml-temp', $item->id) }}">
                                                    <i class="ri-file-line"></i>
                                                </a>
                                                <button title="Transmitir CTe" type="button" class="btn btn-success btn-sm" onclick="transmitir('{{$item->id}}')">
                                                    <i class="ri-send-plane-fill"></i>
                                                </button>
                                                @endif
                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                                <button title="Consultar CTe" type="button" class="btn btn-light btn-sm" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-search-line"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">
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

                <!-- ═══ FOOTER (Paginação) ═══ -->
                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total de registros: <span class="modulo-total-value">{{ $data->total() }}</span></span>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
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
                <h5 class="modal-title" id="modal-cancelar-label">Cancelar CTe <strong class="ref-numero"></strong></h5>
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
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger">Cancelar CTe</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modal-corrigir-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-corrigir-label">Corrigir CTe <strong class="ref-numero"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {!!Form::text('motivo-corrigir', 'Motivo da correção')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="btn btn-warning">Corrigir</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\\n"
            text += "Chave: " + chave + "\\n"
            swal("", text, "warning")
        } else {
            let text = "Chave: " + chave + "\\n"
            text += "Recibo: " + recibo + "\\n"
            swal("", text, "success")
        }
    }
</script>
<script type="text/javascript" src="/js/cte_transmitir.js"></script>
@endsection
