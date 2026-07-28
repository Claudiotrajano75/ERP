@extends('layouts.app', ['title' => 'Saldo de Cashback'])
@section('content')

<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark">
                <!-- Cabeçalho -->
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-coins-line me-2 text-warning fs-22"></i>
                                Saldo de Cashback - <strong class="text-primary ms-1">{{ $item->razao_social }}</strong>
                            </h4>
                            <p class="text-muted mb-0 fs-13">Consulte o extrato completo de créditos acumulados e datas de validade/expiração do cliente.</p>
                        </div>
                        <div>
                            <a href="{{ route('clientes.index') }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Corpo do Extrato -->
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead class="table-light">
                                <tr>
                                    <th>Data da Transação</th>
                                    <th>Crédito Gerado</th>
                                    <th>Percentual Aplicado (%)</th>
                                    <th>Valor Total da Venda</th>
                                    <th>Validade (Expiração)</th>
                                    <th>Status do Cashback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->cashBacks as $c)
                                <tr>
                                    <td>{{ __data_pt($c->created_at, 1) }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($c->valor_credito) }}</td>
                                    <td>{{ __moeda($c->valor_percentual) }}%</td>
                                    <td>R$ {{ __moeda($c->valor_venda) }}</td>
                                    <td class="fw-semibold">{{ __data_pt($c->data_expiracao, 0) }}</td>
                                    <td>
                                        @if($c->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Disponível</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Expirado / Utilizado</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Nenhum registro de cashback cadastrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if(sizeof($item->cashBacks) > 0)
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td>Total Acumulado</td>
                                    <td class="text-success fs-15" colspan="5">R$ {{ __moeda($item->cashBacks->sum('valor_credito')) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
