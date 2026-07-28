@extends('layouts.app', ['title' => 'Ficha do Agendamento'])

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

/* ─── Detail Label ─── */
.detail-label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.detail-value { font-size: 14px; font-weight: 600; color: #1a1a2e; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}

/* ─── Print ─── */
@media print {
    .d-print-none { display: none !important; }
    .print { margin: 15px; }
    .modulo-header-gradient { background: #302b63 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endsection

@section('content')

<div class="mt-3 print text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4 d-print-none">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-eye-line"></i>
                                Detalhes do Agendamento
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualize os serviços agendados, prazos e faturamento no caixa.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('agendamentos.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ FICHA CADASTRAL ═══ -->
                    <div class="row g-4">

                        <!-- Dados do Cliente -->
                        <div class="col-md-6 col-12">
                            <div class="p-3 bg-light border rounded h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h5 class="mb-0 text-dark fw-bold">
                                        <i class="ri-user-line text-success me-1"></i> Cadastro do Cliente
                                    </h5>
                                    @can('clientes_edit')
                                    <a class="btn btn-sm btn-warning text-white d-print-none"
                                       href="{{ route('clientes.edit', [$item->cliente_id]) }}">
                                        <i class="ri-edit-line me-1"></i> Editar Cliente
                                    </a>
                                    @endcan
                                </div>
                                <div class="row g-2 fs-14">
                                    <div class="col-12">
                                        <div class="detail-label">Razão Social / Nome</div>
                                        <strong class="text-dark">{{ $item->cliente->razao_social }}</strong>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="detail-label">CPF / CNPJ</div>
                                        <span class="text-dark">{{ $item->cliente->cpf_cnpj }}</span>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="detail-label">Telefone</div>
                                        <span class="text-dark">{{ $item->cliente->telefone }}</span>
                                    </div>
                                    <div class="col-12 mt-2 border-top pt-2">
                                        <div class="row text-center text-md-start">
                                            <div class="col-md-4 col-6">
                                                <div class="detail-label">Qtd. Serviços</div>
                                                <strong class="text-primary fs-15">{{ sizeof($item->itens) }}</strong>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="detail-label">Desconto</div>
                                                <strong class="text-danger fs-15">R$ {{ __moeda($item->desconto) }}</strong>
                                            </div>
                                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                                                <div class="detail-label">Atendente</div>
                                                <strong class="text-dark">{{ $item->funcionario ? $item->funcionario->nome : 'Nenhum' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edição de Horário -->
                        <div class="col-md-6 col-12">
                            @can('agendamento_edit')
                            <div class="p-3 border rounded bg-light h-100">
                                <h5 class="mb-3 text-dark fw-bold border-bottom pb-2">
                                    <i class="ri-time-line text-warning me-1"></i> Remarcar / Ajustar Horários
                                </h5>
                                <form method="POST" action="{{ route('agendamentos.update', [$item->id]) }}" class="row g-2">
                                    @method('put')
                                    @csrf
                                    <div class="col-6">
                                        {!!Form::tel('inicio', 'Horário de Entrada')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])
                                        ->value(\Carbon\Carbon::parse($item->inicio)->format('H:i')) !!}
                                    </div>
                                    <div class="col-6">
                                        {!!Form::tel('termino', 'Horário de Saída')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])
                                        ->value(\Carbon\Carbon::parse($item->termino)->format('H:i')) !!}
                                    </div>
                                    <div class="col-12 mt-2">
                                        {!!Form::date('data', 'Data do Agendamento')->attrs(['class' => 'form-control date'])
                                        ->value($item->data) !!}
                                    </div>
                                    <div class="col-12 mt-3 d-print-none">
                                        <button class="btn btn-success w-100" type="submit">
                                            <i class="ri-check-line align-middle me-1"></i> Confirmar Alteração
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endcan
                        </div>

                    </div>

                    <!-- ═══ TABELA DE SERVIÇOS ═══ -->
                    <div class="card modulo-inner-card border mt-4 shadow-sm" style="border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fc; border-bottom: 1px solid #eef0f5; padding: 10px 16px;">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="ri-briefcase-line text-primary me-1 fs-18"></i>
                                Serviços Contratados
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Serviço</th>
                                            <th style="width: 200px;">Quantidade</th>
                                            <th style="width: 250px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->itens as $i)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $i->servico->nome }}</td>
                                            <td>{{ number_format($i->quantidade, 2) }}</td>
                                            <td class="fw-bold text-success">R$ {{ __moeda($i->valor) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr class="fw-bold">
                                            <td></td>
                                            <td class="text-success text-uppercase fs-11">Total Geral</td>
                                            <td class="text-success fs-16">R$ {{ __moeda($item->total) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 pt-3 border-top d-print-none">
                        <div>
                            @can('agendamento_delete')
                            <form action="{{ route('agendamentos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-danger btn-delete">
                                    <i class="ri-delete-bin-line align-middle me-1"></i> Remover Agendamento
                                </button>
                            </form>
                            @endcan
                        </div>
                        <div>
                            <form method="post" action="{{ route('agendamentos.update-status', [$item->id]) }}"
                                  id="form-confirm-{{$item->id}}" class="m-0 d-flex gap-1">
                                @method('PUT')
                                @csrf

                                @if($item->nfce_id == null)
                                @can('pdv_create')
                                <a href="{{ route('agendamentos.pdv', [$item->id]) }}" class="btn btn-dark">
                                    <i class="ri-price-tag-3-fill align-middle me-1"></i> Finalizar no PDV
                                </a>
                                @endcan
                                @endif

                                @if($item->status == 0)
                                @can('agendamento_edit')
                                <button type="button" class="btn btn-success btn-confirm">
                                    <i class="ri-check-line align-middle me-1"></i> Marcar como Finalizado
                                </button>
                                @endcan
                                @endif

                                <a href="javascript:window.print()" class="btn btn-primary">
                                    <i class="ri-printer-line align-middle me-1"></i> Imprimir Ficha
                                </a>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
