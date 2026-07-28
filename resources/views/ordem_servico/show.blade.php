@extends('layouts.app', ['title' => 'Detalhes da Ordem de Serviço'])

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

/* ─── Inner Cards ─── */
.modulo-inner-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-inner-card .card-header { background: #f8f9fc; border-bottom: 1px solid #eef0f5; padding: 10px 16px; }
.modulo-inner-card .card-body { padding: 16px; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-12 text-white-50 text-uppercase fw-semibold d-block mb-1"
                                  style="color: rgba(255,255,255,0.5) !important;">Painel de Acompanhamento</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-survey-line"></i>
                                Ordem de Serviço <strong style="color:#f8bbd0;">#{{ $ordem->codigo_sequencial }}</strong>
                            </h4>
                        </div>
                        <div class="d-inline-flex gap-1 flex-wrap">
                            <a href="{{ route('ordem-servico.alterar-estado', [$ordem->id]) }}"
                               class="btn btn-info btn-sm text-white">
                                <i class="ri-refresh-line align-middle me-1"></i> Alterar Estado
                            </a>
                            <a target="_blank" class="btn btn-primary btn-sm"
                               href="{{ route('ordem-servico.imprimir', $ordem->id) }}">
                                <i class="ri-printer-line align-middle me-1"></i> Imprimir OS
                            </a>
                            @if($ordem->nfe_id == 0)
                            <a class="btn btn-success btn-sm"
                               href="{{ route('ordem-servico.gerar-nfe', $ordem->id) }}">
                                <i class="ri-file-text-line align-middle me-1"></i> Gerar NF-e
                            </a>
                            @endif
                            <a href="{{ route('ordem-servico.index') }}" class="btn btn-light btn-sm px-3 ms-1 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ RESUMO SUPERIOR ═══ -->
                    <div class="modulo-glass-filter p-3 mb-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04);">
                        <div class="row g-3 text-center text-md-start">
                            <div class="col-md-3 col-6">
                                <div class="detail-label">Data de Início</div>
                                <strong class="text-dark fs-14">{{ __data_pt($ordem->data_inicio) }}</strong>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="detail-label">Previsão de Entrega</div>
                                <strong class="text-dark fs-14">{{ __data_pt($ordem->data_entrega) }}</strong>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="detail-label">Valor Total Geral</div>
                                <strong class="text-success fs-16">R$ {{ __moeda($ordem->valor) }}</strong>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="detail-label">Operador / Responsável</div>
                                <strong class="text-dark fs-14">{{ $ordem->usuario->name }}</strong>
                            </div>
                            <div class="col-12 mt-2">
                                <span class="detail-label d-inline-block me-2 align-middle">Estado Atual:</span>
                                @if($ordem->estado == 'pd')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 fs-11">
                                    <i class="ri-time-line me-1"></i>PENDENTE
                                </span>
                                @elseif($ordem->estado == 'ap')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-11">
                                    <i class="ri-check-line me-1"></i>APROVADA
                                </span>
                                @elseif($ordem->estado == 'rp')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fs-11">
                                    <i class="ri-close-line me-1"></i>REPROVADA
                                </span>
                                @elseif($ordem->estado == 'fz')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 fs-11">
                                    <i class="ri-check-double-line me-1"></i>FINALIZADA
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BLOCO DE SERVIÇOS ═══ -->
                    <div class="card modulo-inner-card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="ri-tools-line text-primary me-2 fs-18"></i>
                                Serviços Prestados
                            </h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open()->post()->route('ordem-servico.store-servico') !!}
                            <input type="hidden" value="{{$ordem->id}}" name="ordem_servico_id">
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-4 col-12">
                                    {!! Form::select('servico_id', 'Serviço', [null => 'Selecione'] + $servicos->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2', 'id' => 'inp-servico_id'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    {!! Form::tel('quantidade', 'Quantidade')->attrs(['class' => 'form-control moeda', 'id' => 'inp-quantidade'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    {!! Form::text('valor', 'Valor Unitário (R$)')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    {!! Form::select('status', 'Status Inicial', [0 => 'Pendente', 1 => 'Finalizado'])->attrs(['class' => 'form-select', 'id' => 'inp-status'])->required() !!}
                                </div>
                                <input type="hidden" id="inp-nome" name="nome">
                                <div class="col-md-2 col-6 text-end">
                                    <button type="submit" class="btn btn-success btn-sm w-100 py-2 btn-add-servico">
                                        <i class="ri-add-line align-middle me-1"></i> Adicionar
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}

                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark table-servico">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nome do Serviço</th>
                                            <th style="width: 150px;">Quantidade</th>
                                            <th style="width: 150px;">Status</th>
                                            <th style="width: 180px;">Subtotal</th>
                                            <th class="text-end" style="width: 120px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($ordem)
                                        @forelse($ordem->servicos as $item)
                                        <tr>
                                            <td class="fw-semibold text-dark">
                                                <input readonly type="text" name="servico[]" class="form-control-plaintext text-dark py-0" value="{{ $item->servico->nome }}">
                                            </td>
                                            <td>
                                                <input readonly type="tel" name="servico_quantidade[]" class="form-control-plaintext text-dark py-0" value="{{ $item->quantidade }}">
                                            </td>
                                            <td>
                                                @if($item->status)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Finalizado</span>
                                                @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Pendente</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-dark">
                                                <input readonly type="tel" name="valor[]" class="form-control-plaintext text-dark py-0 fw-bold" value="R$ {{ __moeda($item->subtotal) }}">
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('ordem-servico.deletar-servico', $item->id) }}" method="post" id="form-servico-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modulo-action-group" style="display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center;">
                                                        <a title="Alterar Estado do Serviço" href="{{ route('ordem-servico.alterar-status-servico', $item->id) }}" class="btn btn-sm btn-light">
                                                            <i class="ri-refresh-line"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir Serviço">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Nenhum serviço vinculado a esta OS.</td>
                                        </tr>
                                        @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BLOCO DE PRODUTOS ═══ -->
                    <div class="card modulo-inner-card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="ri-shopping-basket-line text-primary me-2 fs-18"></i>
                                Peças & Produtos Utilizados
                            </h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open()->post()->route('ordem-servico.store-produto') !!}
                            <input type="hidden" value="{{$ordem->id}}" name="ordem_servico_id">
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-5 col-12">
                                    {!! Form::select('produto_id', 'Produto')->attrs(['class' => 'form-select select2', 'id' => 'inp-produto_id'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    {!! Form::tel('quantidade_produto', 'Quantidade')->attrs(['class' => 'form-control moeda', 'id' => 'inp-quantidade_produto'])->required() !!}
                                </div>
                                <div class="col-md-3 col-6">
                                    {!! Form::tel('valor_produto', 'Valor Unitário (R$)')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_produto'])->required() !!}
                                </div>
                                <input type="hidden" id="inp-nome_produto" name="nome_produto">
                                <div class="col-md-2 col-12 text-end">
                                    @if(!isset($not_submit))
                                    <button type="submit" class="btn btn-success btn-sm w-100 py-2 btn-add-produto">
                                        <i class="ri-add-line align-middle me-1"></i> Adicionar
                                    </button>
                                    @endif
                                </div>
                            </div>
                            {!! Form::close() !!}

                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark table-produto">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nome do Produto</th>
                                            <th style="width: 150px;">Quantidade</th>
                                            <th style="width: 180px;">Valor Unitário</th>
                                            <th style="width: 180px;">Subtotal</th>
                                            <th class="text-end" style="width: 120px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($ordem)
                                        @forelse($ordem->itens as $item)
                                        <tr>
                                            <td class="fw-semibold text-dark">
                                                <input readonly type="text" name="produto[]" class="form-control-plaintext text-dark py-0" value="{{ $item->produto->nome }}">
                                            </td>
                                            <td>
                                                <input readonly type="tel" name="produto_quantidade[]" class="form-control-plaintext text-dark py-0" value="{{ $item->quantidade }}">
                                            </td>
                                            <td>
                                                <input readonly type="tel" name="total[]" class="form-control-plaintext text-dark py-0" value="R$ {{ __moeda($item->produto->valor_unitario) }}">
                                            </td>
                                            <td class="fw-bold text-dark">
                                                <input readonly type="tel" name="subtotal[]" class="form-control-plaintext text-dark py-0 fw-bold" value="R$ {{ __moeda($item->subtotal) }}">
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('ordem-servico.deletar-produto', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir Produto">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Nenhum produto/peça vinculado a esta OS.</td>
                                        </tr>
                                        @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BLOCO DE RELATÓRIOS ═══ -->
                    <div class="card modulo-inner-card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="ri-file-list-3-line text-primary me-2 fs-18"></i>
                                Relatórios de Evolução Técnica
                            </h5>
                            <a href="{{ route('ordem-servico.add-relatorio', $ordem->id) }}" class="btn btn-success btn-sm">
                                <i class="ri-add-line me-1"></i> Adicionar Relatório
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data de Registro</th>
                                            <th>Usuário Técnico</th>
                                            <th class="text-end" style="width: 120px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ordem->relatorios as $item)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ __data_pt($item->created_at) }}</td>
                                            <td>{{ $item->usuario->name }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('ordem-servico.delete-relatorio', $item->id) }}" method="post" id="form-relatorio-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modulo-action-group" style="display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center;">
                                                        <a href="{{ route('ordem-servico.edit-relatorio', $item->id) }}" title="Editar Relatório" class="btn btn-warning btn-sm text-white">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir Relatório">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Nenhum relatório técnico adicionado.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BLOCO DE DESCRIÇÃO ═══ -->
                    <div class="card modulo-inner-card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="ri-align-left text-primary me-2 fs-18"></i>
                                Descrição / Problema Relatado Geral
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="bg-light p-3 rounded border text-dark fs-14" style="line-height: 1.6;">
                                {!! $ordem->descricao !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/ordem_servico.js"></script>
@endsection
