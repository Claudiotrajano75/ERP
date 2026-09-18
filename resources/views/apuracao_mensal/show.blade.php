@extends('layouts.app', ['title' => 'Visualizar Apuração Mensal'])

@section('css')
<style type="text/css">
    @page { size: auto; margin: 0mm; }
    @media print {
        .print-layout {
            margin: 20px;
        }
        .navbar-custom, .leftside-menu, .footer, .d-print-none {
            display: none !important;
        }
    }

    /* ─── Tabela ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="mt-3 print-layout text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
                <div class="card-header modulo-header-gradient py-3 px-4 d-print-none">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-printer-line"></i>
                                Demonstrativo de Pagamento
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Confira abaixo os proventos, descontos e resumo de apuração salarial do colaborador.</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('apuracao-mensal.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                            <button onclick="window.print()" class="dash-btn dash-btn-primary">
                                <i class="ri-printer-line"></i> Imprimir Demonstrativo
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- Cabeçalho do Holerite -->
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4 flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-muted text-uppercase fw-bold d-block">Colaborador / Funcionário</span>
                            <h4 class="text-dark fw-bold mb-0">{{ $item->funcionario->nome }}</h4>
                            <span class="fs-12 text-muted">CPF: {{ $item->funcionario->cpf_cnpj ?? '--' }}</span>
                        </div>
                        <div class="text-end">
                            <span class="fs-11 text-muted text-uppercase fw-bold d-block">Competência</span>
                            <h4 class="text-primary fw-bold mb-0">{{ $item->mes }}/{{ $item->ano }}</h4>
                            <span class="fs-12 text-muted">Forma de Pagamento: <strong>{{ $item->forma_pagamento }}</strong></span>
                        </div>
                    </div>

                    <!-- Dados Adicionais -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 col-12">
                            <div class="p-3 border rounded-3 bg-light">
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Data de Emissão / Registro</span>
                                <strong class="text-dark">{{ __data_pt($item->created_at) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="p-3 border rounded-3 bg-light">
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Observações</span>
                                <strong class="text-dark">{{ $item->observacao ?? 'Nenhuma observação informada.' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de Proventos e Descontos -->
                    <div class="tb-wrap mb-4">
                        <table class="table table-centered mb-0 align-middle text-dark">
                            <thead>
                                <tr>
                                    <th>Descrição do Evento de Folha</th>
                                    <th>Operação</th>
                                    <th class="text-end" style="width: 200px;">Valor Apurado (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->eventos as $ev)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $ev->nome }}</td>
                                    <td>
                                        @if($ev->condicao == 'soma')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fs-11 fw-semibold">Provento (+)</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 fs-11 fw-semibold">Desconto (-)</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold {{ $ev->condicao == 'soma' ? 'text-success' : 'text-danger' }}">
                                        {{ $ev->condicao == 'soma' ? '+' : '-' }} R$ {{ __moeda($ev->valor) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Nenhum evento registrado nesta folha.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light border-top">
                                <tr class="fw-bold fs-15">
                                    <td colspan="2">Valor Líquido Apurado</td>
                                    <td class="text-end text-success">R$ {{ __moeda($item->valor_final) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Assinatura Recibo -->
                    <div class="mt-5 pt-4 border-top text-center d-none d-print-block" style="margin-top: 100px !important;">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <hr class="border-dark opacity-75 mb-1">
                                <span class="fs-12 text-muted">Assinatura do Colaborador</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection