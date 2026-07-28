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

    /* ─── Header Gradiente ─── */
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

    /* ─── Form Card (Create/Edit/Show) ─── */
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-form-card .card-body { background: #fff; }

    /* ─── Premium Table ─── */
    .modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
    .modulo-table-wrap tbody tr { transition: all 0.15s ease; }
    .modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="mt-3 print-layout text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4 d-print-none">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-printer-line"></i>
                                Demonstrativo de Pagamento
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Confira abaixo os proventos, descontos e resumo de apuração salarial do colaborador.</p>
                        </div>
                        <div>
                            <a href="{{ route('apuracao-mensal.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- Cabeçalho do Holerite -->
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4 flex-wrap gap-2">
                        <div>
                            <span class="fs-12 text-muted text-uppercase fw-semibold d-block">Colaborador / Funcionário</span>
                            <h4 class="text-dark fw-bold mb-0">{{ $item->funcionario->nome }}</h4>
                            <span class="fs-12 text-muted">CPF/CNPJ: {{ $item->funcionario->cpf_cnpj ?? '--' }}</span>
                        </div>
                        <div class="text-end">
                            <span class="fs-12 text-muted text-uppercase fw-semibold d-block">Período de Referência</span>
                            <h4 class="text-primary fw-bold mb-0">{{ $item->mes }}/{{ $item->ano }}</h4>
                            <span class="fs-12 text-muted">Forma de Pagamento: {{ $item->forma_pagamento }}</span>
                        </div>
                    </div>

                    <!-- Dados Adicionais -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 col-12">
                            <div class="p-3 border rounded bg-light">
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Data de Emissão / Registro</span>
                                <strong class="text-dark">{{ __data_pt($item->created_at) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="p-3 border rounded bg-light">
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Observações</span>
                                <strong class="text-dark">{{ $item->observacao ?? 'Nenhuma observação informada.' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de Proventos e Descontos -->
                    <div class="modulo-table-wrap mb-4">
                        <table class="table table-centered mb-0 align-middle text-dark">
                            <thead>
                                <tr>
                                    <th>Descrição do Evento de Folha</th>
                                    <th>Tipo</th>
                                    <th class="text-end" style="width: 200px;">Valor (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->eventos as $ev)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $ev->nome }}</td>
                                    <td>
                                        @if($ev->condicao == 'soma')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fs-11">Provento (+)</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 fs-11">Desconto (-)</span>
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

                    <!-- Ações de Impressão -->
                    <div class="d-print-none text-end mt-4">
                        <button onclick="window.print()" class="btn btn-primary px-4" style="border-radius: 8px; font-weight: 600;">
                            <i class="ri-printer-line align-middle me-1"></i> Imprimir Demonstrativo
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection