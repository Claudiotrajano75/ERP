@extends('layouts.app', ['title' => 'NFCe Lista Geral'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-bill-line me-2 text-success fs-22"></i>
                            NFCe - Lista Geral
                        </h4>
                        <p class="text-muted mb-0 fs-13">Visualize todas as NFCes emitidas por todas as empresas do sistema (SuperAdmin).</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- Filtros de Busca -->
                <div class="bg-light-subtle border rounded p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::select('empresa', 'Empresa')
                            ->attrs(['class' => 'select2 form-select'])
                            ->options($empresa != null ? [$empresa->id => $empresa->info] : [])
                            !!}
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
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('nfce-all') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

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
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $item->empresa->nome }}</span>
                                    <span class="text-muted fs-11">{{ $item->empresa->cpf_cnpj }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome ?: '--') }}</span>
                                </td>
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
                                <td class="fs-12">{{ \\Carbon\\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="fs-12">{{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : '--' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('nfce.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <div class="d-inline-flex gap-1 flex-wrap justify-content-end">

                                            @if($item->estado == 'aprovado')
                                            <a class="btn btn-primary btn-sm text-white" title="Imprimir NFCe" target="_blank" href="{{ route('nfce.imprimir', [$item->id]) }}">
                                                <i class="ri-printer-line"></i>
                                            </a>
                                            <button title="Cancelar NFCe" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                <i class="ri-close-circle-line"></i>
                                            </button>
                                            @endif
                                            @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                            <button title="Detalhes do Retorno" type="button" class="btn btn-dark btn-sm text-white" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                <i class="ri-file-line"></i>
                                            </button>
                                            @endif
                                            @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                            <a title="XML temporário" class="btn btn-light btn-sm" href="{{ route('nfce.xml-temp', $item->id) }}">
                                                <i class="ri-file-code-line"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Nenhuma NFCe encontrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark">Total das NFCes no Grid: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
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
</script>
<script type="text/javascript" src="/js/nfce_transmitir.js"></script>
@endsection
