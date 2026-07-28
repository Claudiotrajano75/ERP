@extends('layouts.app', ['title' => 'Frete #' . $item->numero_sequencial])

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
                                Frete #{{ $item->numero_sequencial }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualização completa dos dados do frete.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @can('frete_edit')
                            <a href="{{ route('fretes.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="ri-pencil-line align-middle me-1"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('fretes.index') }}" class="btn btn-light btn-sm px-3 text-dark">
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
                        Informações da Viagem
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
                            <div class="detail-label">Data Início Viagem</div>
                            <div class="detail-value">{{ __data_pt($item->data_inicio, 0) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Data Fim Viagem</div>
                            <div class="detail-value">{{ __data_pt($item->data_fim, 0) }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Horário Início</div>
                            <div class="detail-value">{{ $item->horario_inicio ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Horário Fim</div>
                            <div class="detail-value">{{ $item->horario_fim ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Distância</div>
                            <div class="detail-value">{{ $item->distancia_km }} KM</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                @if($item->estado == 'em_carregamento')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                    <i class="ri-loader-4-line me-1"></i>Em carregamento
                                </span>
                                @elseif($item->estado == 'em_viagem')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                    <i class="ri-truck-line me-1"></i>Em viagem
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
                            <div class="detail-label">Valor do Frete</div>
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
                        <div class="col-md-3 col-6">
                            <div class="detail-label">Total Despesas</div>
                            <div class="detail-value text-danger">R$ {{ __moeda($item->total_despesa) }}</div>
                        </div>
                    </div>

                    <!-- ═══ ROTA ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i>
                        Rota
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-6">
                            <div class="detail-label">Cidade de Carregamento</div>
                            <div class="detail-value">{{ $item->cidadeCarregamento->info ?? '-' }}</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="detail-label">Cidade de Descarregamento</div>
                            <div class="detail-value">{{ $item->cidadeDescarregamento->info ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- ═══ ALTERAR ESTADO ═══ -->
                    <div class="d-print-none mb-4">
                        <button id="btn-alterar-estado" class="btn btn-outline-primary btn-sm">
                            <i class="ri-swap-line me-1"></i> Alterar Estado
                        </button>
                        <form class="row form-alterar-estado mt-2 d-none" method="post" action="{{ route('fretes.alterar-estado', [$item->id]) }}">
                            @csrf
                            @method('put')
                            <div class="col-md-3">
                                {!!Form::select('estado', 'Estado',
                                [
                                'em_carregamento' => 'Em carregamento',
                                'em_viagem' => 'Em viagem',
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
                        @if($item->conta_receber_id == 0)
                        <a class="btn btn-success btn-sm" href="{{ route('fretes.gerar-conta-receber', $item->id) }}">
                            <i class="ri-file-text-line me-1"></i> Gerar Conta a Receber
                        </a>
                        @else
                        <a class="btn btn-success btn-sm" href="{{ route('conta-receber.edit', $item->conta_receber_id) }}">
                            <i class="ri-file-text-line me-1"></i> Ver Conta
                        </a>
                        @endif
                    </div>

                    <!-- ═══ DESPESAS ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-coins-line text-primary me-2 align-middle fs-18"></i>
                        Despesas
                    </h5>
                    <div class="modulo-table-wrap mb-4">
                        <div class="table-responsive">
                            <table class="table table-centered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tipo Despesa</th>
                                        <th>Fornecedor</th>
                                        <th>Valor</th>
                                        <th>Observação</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->despesas as $i)
                                    <tr>
                                        <td>{{ $i->tipoDespesaFrete->nome }}</td>
                                        <td>{{ $i->fornecedor ? $i->fornecedor->info : '-' }}</td>
                                        <td class="fw-semibold">R$ {{ __moeda($i->valor) }}</td>
                                        <td>{{ $i->observacao ?? '-' }}</td>
                                        <td>
                                            @if($i->fornecedor)
                                                @if($i->conta_pagar_id)
                                                <a class="btn btn-success btn-sm" href="{{ route('conta-pagar.edit', $i->conta_pagar_id) }}" title="Ver Conta">
                                                    <i class="ri-file-text-line"></i>
                                                </a>
                                                @else
                                                <a class="btn btn-dark btn-sm" href="{{ route('despesa-frete.gerar-conta-pagar', $i->id) }}" title="Gerar Conta">
                                                    <i class="ri-file-text-line"></i>
                                                </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Nenhuma despesa cadastrada.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="fw-bold">Total</td>
                                        <td colspan="3" class="text-primary fw-bold">R$ {{ __moeda($item->total_despesa) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ CLIENTE ═══ -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-user-line text-primary me-2 align-middle fs-18"></i>
                        Dados do Cliente
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="detail-label">Cliente</div>
                            <div class="detail-value">{{ $item->cliente->info }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $item->cliente->email ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Telefone</div>
                            <div class="detail-value">{{ $item->cliente->telefone ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Rua</div>
                            <div class="detail-value">{{ $item->cliente->rua ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Número</div>
                            <div class="detail-value">{{ $item->cliente->numero ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Bairro</div>
                            <div class="detail-value">{{ $item->cliente->bairro ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">CEP</div>
                            <div class="detail-value">{{ $item->cliente->cep ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Cidade</div>
                            <div class="detail-value">{{ $item->cliente->cidade->info ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- ═══ UPLOAD DE ARQUIVOS ═══ -->
                    <div class="d-print-none">
                        <h5 class="text-dark border-bottom pb-2 mb-3">
                            <i class="ri-attachment-line text-primary me-2 align-middle fs-18"></i>
                            Anexos
                        </h5>
                        <form class="row g-3 mb-4" enctype="multipart/form-data" method="post" action="{{ route('fretes.upload', [$item->id]) }}">
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
                                    <form action="{{ route('fretes.destroy-file', $a->id) }}" method="post" class="m-0">
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
