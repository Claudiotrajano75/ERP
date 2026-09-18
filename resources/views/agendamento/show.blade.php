@extends('layouts.app', ['title' => 'Ficha do Agendamento'])

@section('css')
<style>
/* ─── Cards de Detalhes ─── */
.detail-card { background: #ffffff; border: 1px solid #eef0f5; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
.detail-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; }
.detail-value { font-size: 15px; font-weight: 700; color: #1f2937; margin-bottom: 0; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
.tb-wrap tfoot td { background: #f8f9fc; padding: 13px 16px; font-weight: 700; border-top: 1px solid #e8eaf6; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 12.5px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 38px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-amber { background: #fef3c7; color: #b45309; }

/* ─── Print ─── */
@media print {
    .d-print-none { display: none !important; }
    .print { margin: 15px; }
    .modulo-header-gradient { background: #302b63 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endsection

@section('content')

<div class="mt-3 print">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4 d-print-none">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-uppercase fw-bold text-primary d-block mb-1"
                                  style="letter-spacing: 0.5px;">Painel de Acompanhamento</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-calendar-check-line"></i>
                                Detalhes do Agendamento #{{ $item->id }}
                            </h4>
                        </div>
                        <div>
                            <a href="{{ route('agendamentos.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ FICHA CADASTRAL ═══ -->
                    <div class="row g-4">

                        <!-- Dados do Cliente -->
                        <div class="col-md-6 col-12">
                            <div class="detail-card h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h5 class="mb-0 text-dark fw-bold fs-14">
                                        <i class="ri-user-line text-primary me-1"></i> Cadastro do Cliente
                                    </h5>
                                    @can('clientes_edit')
                                    <a class="dash-btn dash-btn-light btn-sm d-print-none"
                                       href="{{ route('clientes.edit', [$item->cliente_id]) }}">
                                        <i class="ri-pencil-line"></i> Editar Cliente
                                    </a>
                                    @endcan
                                </div>
                                <div class="row g-3 fs-14">
                                    <div class="col-12">
                                        <div class="detail-label">Razão Social / Nome</div>
                                        <div class="detail-value text-primary fs-16">{{ $item->cliente->razao_social }}</div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="detail-label">CPF / CNPJ</div>
                                        <span class="text-dark fw-semibold">{{ $item->cliente->cpf_cnpj ?? 'Não informado' }}</span>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="detail-label">Telefone</div>
                                        <span class="text-dark fw-semibold">{{ $item->cliente->telefone ?? 'Não informado' }}</span>
                                    </div>
                                    <div class="col-12 mt-2 border-top pt-3">
                                        <div class="row text-center text-md-start g-2">
                                            <div class="col-md-4 col-6">
                                                <div class="detail-label">Qtd. Serviços</div>
                                                <strong class="text-primary fs-15">{{ sizeof($item->itens) }}</strong>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="detail-label">Desconto</div>
                                                <strong class="text-danger fs-15">R$ {{ __moeda($item->desconto) }}</strong>
                                            </div>
                                            <div class="col-md-4 col-12">
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
                            <div class="detail-card h-100">
                                <h5 class="mb-3 text-dark fw-bold fs-14 border-bottom pb-2">
                                    <i class="ri-time-line text-warning me-1"></i> Remarcar / Ajustar Horários
                                </h5>
                                <form method="POST" action="{{ route('agendamentos.update', [$item->id]) }}" class="row g-3">
                                    @method('put')
                                    @csrf
                                    <div class="col-6 uf-field">
                                        <label class="form-label"><i class="ri-time-line"></i> Horário de Entrada</label>
                                        {!!Form::tel('inicio', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])
                                        ->value(\Carbon\Carbon::parse($item->inicio)->format('H:i')) !!}
                                    </div>
                                    <div class="col-6 uf-field">
                                        <label class="form-label"><i class="ri-time-line"></i> Horário de Saída</label>
                                        {!!Form::tel('termino', '')->attrs(['class' => 'form-control timer', 'placeholder' => '00:00'])
                                        ->value(\Carbon\Carbon::parse($item->termino)->format('H:i')) !!}
                                    </div>
                                    <div class="col-12 uf-field">
                                        <label class="form-label"><i class="ri-calendar-line"></i> Data do Agendamento</label>
                                        {!!Form::date('data', '')->attrs(['class' => 'form-control date'])
                                        ->value($item->data) !!}
                                    </div>
                                    <div class="col-12 mt-3 d-print-none text-end">
                                        <button class="dash-btn dash-btn-primary w-100 py-2" type="submit">
                                            <i class="ri-check-line"></i> Confirmar Alteração de Horário
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endcan
                        </div>

                    </div>

                    <!-- ═══ TABELA DE SERVIÇOS ═══ -->
                    <div class="tb-wrap mt-4">
                        <div class="p-3 border-bottom bg-light">
                            <h5 class="mb-0 text-dark fw-bold fs-14">
                                <i class="ri-briefcase-line text-primary me-1"></i>
                                Serviços Contratados
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
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
                                        <td class="fw-bold text-success fs-14">R$ {{ __moeda($i->valor) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-success text-uppercase fs-12 fw-bold">Total Geral:</td>
                                        <td class="text-success fs-16 fw-bold">R$ {{ __moeda($item->total) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 pt-3 border-top d-print-none">
                        <div>
                            @can('agendamento_delete')
                            <form action="{{ route('agendamentos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                @method('delete')
                                @csrf
                                <button type="button" class="dash-btn dash-btn-danger btn-delete">
                                    <i class="ri-delete-bin-line"></i> Remover Agendamento
                                </button>
                            </form>
                            @endcan
                        </div>
                        <div>
                            <form method="post" action="{{ route('agendamentos.update-status', [$item->id]) }}"
                                  id="form-confirm-{{$item->id}}" class="m-0 d-flex gap-2 flex-wrap">
                                @method('PUT')
                                @csrf

                                @if($item->nfce_id == null)
                                @can('pdv_create')
                                <a href="{{ route('agendamentos.pdv', [$item->id]) }}" class="dash-btn dash-btn-primary">
                                    <i class="ri-price-tag-3-fill"></i> Finalizar no PDV
                                </a>
                                @endcan
                                @endif

                                @if($item->status == 0)
                                @can('agendamento_edit')
                                <button type="button" class="dash-btn dash-btn-success btn-confirm">
                                    <i class="ri-check-line"></i> Marcar como Finalizado
                                </button>
                                @endcan
                                @endif

                                <a href="javascript:window.print()" class="dash-btn dash-btn-light">
                                    <i class="ri-printer-line"></i> Imprimir Ficha
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

