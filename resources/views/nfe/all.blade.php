@extends('layouts.app', ['title' => 'NFe — Todas as Empresas'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-building-line me-2 text-primary fs-22"></i>
                            NFe — Lista Geral de Todas as Empresas
                        </h4>
                        <p class="text-muted mb-0 fs-13">Visualize e gerencie notas fiscais de todas as empresas cadastradas no sistema.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">

                <!-- Filtros -->
                <div class="bg-light-subtle border rounded p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::select('empresa', 'Empresa')
                            ->attrs(['class' => 'select2 form-select'])
                            ->options($empresa != null ? [$empresa->id => $empresa->info] : [])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado', [
                                'novo' => 'Nova',
                                'rejeitado' => 'Rejeitadas',
                                'cancelado' => 'Canceladas',
                                'aprovado' => 'Aprovadas',
                                '' => 'Todos'
                            ])->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('nfe-all') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela -->
                <div class="table-responsive">
                    <table class="table table-centered table-hover align-middle mb-0 text-dark">
                        <thead class="table-light">
                            <tr>
                                <th>Empresa</th>
                                <th>Cliente</th>
                                <th>Nº Nota</th>
                                <th>Valor (R$)</th>
                                <th>Estado</th>
                                <th>Ambiente</th>
                                <th>Cadastro</th>
                                <th>Emissão</th>
                                <th class="text-end" style="min-width: 160px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $item->empresa->nome }}</span>
                                    <span class="text-muted fs-11">{{ $item->empresa->cpf_cnpj }}</span>
                                </td>
                                <td class="fw-semibold text-dark">{{ $item->cliente ? $item->cliente->razao_social : '--' }}</td>
                                <td class="fw-bold">{{ $item->numero ?: '--' }}</td>
                                <td class="fw-bold text-success">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
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
                                    <span class="badge bg-light text-dark border fs-11">{{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}</span>
                                </td>
                                <td class="fs-12">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="fs-12">{{ $item->data_emissao ? __data_pt($item->data_emissao) : '--' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('nfe.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <div class="d-inline-flex gap-1 flex-wrap justify-content-end">
                                            @if($item->estado == 'cancelado')
                                            <a class="btn btn-danger btn-sm text-white" target="_blank" href="{{ route('nfe.imprimir-cancela', [$item->id]) }}" title="Imprimir Cancelamento">
                                                <i class="ri-printer-line"></i>
                                            </a>
                                            @endif
                                            @if($item->estado == 'aprovado')
                                            <a class="btn btn-primary btn-sm text-white" target="_blank" href="{{ route('nfe.imprimir', [$item->id]) }}" title="Imprimir NFe">
                                                <i class="ri-printer-line"></i>
                                            </a>
                                            <button title="Cancelar NFe" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                <i class="ri-close-circle-line"></i>
                                            </button>
                                            <button title="Carta de Correção" type="button" class="btn btn-warning btn-sm text-white" onclick="corrigir('{{$item->id}}', '{{$item->numero}}')">
                                                <i class="ri-file-warning-line"></i>
                                            </button>
                                            @endif
                                            @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                            <button type="button" class="btn btn-dark btn-sm text-white" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                <i class="ri-file-line"></i>
                                            </button>
                                            @endif
                                            @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                            <a target="_blank" title="XML Temporário" class="btn btn-light btn-sm" href="{{ route('nfe.xml-temp', $item->id) }}">
                                                <i class="ri-file-code-line"></i>
                                            </a>
                                            <button title="Transmitir ao SEFAZ" type="button" class="btn btn-success btn-sm text-white" onclick="transmitir('{{$item->id}}')">
                                                <i class="ri-send-plane-fill"></i>
                                            </button>
                                            @endif
                                            @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                            <button title="Consultar Protocolo" type="button" class="btn btn-light btn-sm" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                <i class="ri-file-search-line"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

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
                        {!!Form::text('motivo-cancela', 'Motivo (mín. 15 caracteres)')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm">Cancelar NFe</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corrigir NFe -->
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCorrigirLabel">Carta de Correção (CC-e) <strong class="ref-numero text-warning"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-corrigir', 'Texto de Correção (mín. 15 caracteres)')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="btn btn-warning btn-sm">Transmitir CC-e</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n\nChave: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave: " + chave + "\nRecibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }
</script>
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>
@endsection
