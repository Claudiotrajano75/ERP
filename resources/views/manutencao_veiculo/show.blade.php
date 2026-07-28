@extends('layouts.app', ['title' => 'Manutenção #' . $item->numero_sequencial])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }

/* ─── Info Details ─── */
.detail-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #8a8aaa; font-weight: 700; margin-bottom: 2px; }
.detail-value { font-size: 14px; color: #2d2d44; font-weight: 600; }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; white-space: nowrap; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-table-wrap tfoot td { background: #f8f9fc; font-weight: 700; font-size: 13px; padding: 10px 14px; border-top: 2px solid #e8eaf6; }

/* ─── Status Badges ─── */
@page { size: auto; margin: 0mm; }
@media print { .print { margin: 10px; } }

/* ─── File Upload ─── */
.file-certificado label { padding: 8px 12px; width: 100%; background: linear-gradient(135deg, #0f0c29, #302b63); color: #FFF; text-transform: uppercase; text-align: center; display: block; cursor: pointer; border-radius: 8px; font-size: 12px; font-weight: 600; transition: all 0.2s ease; }
.file-certificado label:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.2); }

/* ─── Anexos ─── */
.anexo-card { border: 1px solid #eef0f5; border-radius: 10px; padding: 16px; transition: all 0.2s ease; }
.anexo-card:hover { background: #f5f6fe; }

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card print">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-eye-line"></i>
                                Manutenção #{{ $item->numero_sequencial }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualização completa dos dados da manutenção.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @can('manutencao_veiculo_edit')
                            <a href="{{ route('manutencao-veiculos.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="ri-pencil-line align-middle me-1"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('manutencao-veiculos.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO ═══ -->
                <div class="card-body p-4">

                    <!-- ═══ INFO PRINCIPAL ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
                        Informações da Manutenção
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="detail-label"># Sequencial</div>
                            <div class="detail-value"><span class="text-danger">#{{ $item->numero_sequencial }}</span></div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Data de Cadastro</div>
                            <div class="detail-value">{{ __data_pt($item->created_at) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Data de Início</div>
                            <div class="detail-value">{{ __data_pt($item->data_inicio, 0) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Data de Fim</div>
                            <div class="detail-value">{{ __data_pt($item->data_fim, 0) ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                @if($item->estado == 'aguardando')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                    <i class="ri-time-line me-1"></i>Aguardando
                                </span>
                                @elseif($item->estado == 'em_manutencao')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                    <i class="ri-tools-line me-1"></i>Em manutenção
                                </span>
                                @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                    <i class="ri-check-double-line me-1"></i>Finalizado
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ═══ VALORES ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-money-dollar-circle-line text-primary me-2 align-middle fs-18"></i>
                        Valores
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Total</div>
                            <div class="detail-value text-primary fs-16">R$ {{ __moeda($item->total) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Desconto</div>
                            <div class="detail-value">R$ {{ __moeda($item->desconto) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Acréscimo</div>
                            <div class="detail-value">R$ {{ __moeda($item->acrescimo) }}</div>
                        </div>
                    </div>

                    <!-- ═══ ALTERAR ESTADO ═══ -->
                    <div class="d-print-none mb-4">
                        <button id="btn-alterar-estado" class="btn btn-outline-primary btn-sm">
                            <i class="ri-swap-line me-1"></i> Alterar Estado
                        </button>
                        <form class="row form-alterar-estado mt-2 d-none" method="post" action="{{ route('manutencao-veiculos.alterar-estado', [$item->id]) }}">
                            @csrf
                            @method('put')
                            <div class="col-md-3">
                                {!!Form::select('estado', 'Estado',
                                [
                                'aguardando' => 'Aguardando',
                                'em_manutencao' => 'Em manutenção',
                                'finalizado' => 'Finalizado',
                                ])
                                ->value($item->estado)
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-success btn-sm">
                                    <i class="ri-check-line me-1"></i> Alterar
                                </button>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- ═══ AÇÕES ═══ -->
                    <div class="d-flex gap-2 flex-wrap mb-4 d-print-none">
                        <a class="btn btn-primary btn-sm" href="javascript:window.print()">
                            <i class="ri-printer-line me-1"></i> Imprimir
                        </a>
                        @if($item->conta_pagar_id == 0)
                        <a class="btn btn-success btn-sm" href="{{ route('manutencao-veiculos.gerar-conta-pagar', $item->id) }}">
                            <i class="ri-file-text-line me-1"></i> Gerar Conta a Pagar
                        </a>
                        @else
                        <a class="btn btn-success btn-sm" href="{{ route('conta-pagar.edit', $item->conta_pagar_id) }}">
                            <i class="ri-file-text-line me-1"></i> Ver Conta
                        </a>
                        @endif
                    </div>

                    <!-- ═══ SERVIÇOS ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-tools-line text-primary me-2 align-middle fs-18"></i>
                        Serviços Realizados
                    </h5>
                    <div class="modulo-table-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Serviço</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Subtotal</th>
                                        <th>Observação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->servicos as $i)
                                    <tr>
                                        <td>{{ $i->servico->nome }}</td>
                                        <td>{{ __moeda($i->quantidade) }}</td>
                                        <td>R$ {{ __moeda($i->valor_unitario) }}</td>
                                        <td class="fw-semibold">R$ {{ __moeda($i->sub_total) }}</td>
                                        <td>{{ $i->observacao ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Nenhum serviço cadastrado.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="fw-bold">Total</td>
                                        <td colspan="2" class="text-primary fw-bold">R$ {{ __moeda($item->servicos->sum('sub_total')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ PRODUTOS ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-product-hunt-line text-primary me-2 align-middle fs-18"></i>
                        Produtos Utilizados
                    </h5>
                    <div class="modulo-table-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Subtotal</th>
                                        <th>Observação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->produtos as $i)
                                    <tr>
                                        <td>{{ $i->produto->nome }}</td>
                                        <td>{{ __moeda($i->quantidade) }}</td>
                                        <td>R$ {{ __moeda($i->valor_unitario) }}</td>
                                        <td class="fw-semibold">R$ {{ __moeda($i->sub_total) }}</td>
                                        <td>{{ $i->observacao ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Nenhum produto cadastrado.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="fw-bold">Total</td>
                                        <td colspan="2" class="text-primary fw-bold">R$ {{ __moeda($item->produtos->sum('sub_total')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ FORNECEDOR ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-user-line text-primary me-2 align-middle fs-18"></i>
                        Dados do Fornecedor
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="detail-label">Fornecedor</div>
                            <div class="detail-value">{{ $item->fornecedor->info }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $item->fornecedor->email ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Telefone</div>
                            <div class="detail-value">{{ $item->fornecedor->telefone ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Rua</div>
                            <div class="detail-value">{{ $item->fornecedor->rua ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Número</div>
                            <div class="detail-value">{{ $item->fornecedor->numero ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Bairro</div>
                            <div class="detail-value">{{ $item->fornecedor->bairro ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">CEP</div>
                            <div class="detail-value">{{ $item->fornecedor->cep ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Cidade</div>
                            <div class="detail-value">{{ $item->fornecedor->cidade->info ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- ═══ UPLOAD DE ARQUIVOS ═══ -->
                    <div class="d-print-none">
                        <h5 class="text-dark border-bottom pb-2 mb-3">
                            <i class="ri-attachment-line text-primary me-2 align-middle fs-18"></i>
                            Anexos
                        </h5>
                        <form class="row g-3 mb-4" enctype="multipart/form-data" method="post" action="{{ route('manutencao-veiculos.upload', [$item->id]) }}">
                            @csrf
                            <div class="col-md-3 file-certificado">
                                {!! Form::file('file', 'Selecionar arquivo')
                                ->attrs(['accept' => '.pdf, image/*']) !!}
                                <span class="text-danger" id="filename"></span>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="ri-upload-line me-1"></i> Salvar arquivo
                                </button>
                            </div>
                        </form>

                        @if($item->anexos->count() > 0)
                        <div class="row g-3">
                            @foreach($item->anexos as $key => $a)
                            <div class="col-md-4">
                                <div class="anexo-card d-flex justify-content-between align-items-center">
                                    <div>
                                        <a target="_blank" href="{{ $a->file }}" class="fw-semibold text-dark">
                                            <i class="ri-file-line me-1"></i> Anexo {{ $key + 1 }}
                                        </a>
                                    </div>
                                    <form action="{{ route('manutencao-veiculos.destroy-file', $a->id) }}" method="post" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger btn-delete" title="Remover">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('#btn-alterar-estado').click(function() {
        $('.form-alterar-estado').toggleClass('d-none');
    });
</script>
@endsection
