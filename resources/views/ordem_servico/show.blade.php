@extends('layouts.app', ['title' => 'Detalhes da Ordem de Serviço'])

@section('css')
<style>
/* ─── Cards de Detalhes ─── */
.detail-card { background: #ffffff; border: 1px solid #eef0f5; border-radius: 12px; padding: 18px 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
.detail-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; }
.detail-value { font-size: 15px; font-weight: 700; color: #1f2937; margin-bottom: 0; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de Ações ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; }
.act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-view { background: #e0f2fe; color: #0284c7; }
.act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
.act-edit { background: #eef0ff; color: #4f46e5; }
.act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
.act-del { background: #fee2e2; color: #dc2626; }
.act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }

/* ─── Inner Box ─── */
.inner-block-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eef0f6; padding: 14px 20px; background: #fafbfe; }
.inner-block-header h5 { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 0; }
.inner-block-header h5 i { color: #4f46e5; margin-right: 6px; }
.inner-block-body { padding: 20px; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-uppercase fw-bold text-primary d-block mb-1"
                                  style="letter-spacing: 0.5px;">Painel de Acompanhamento</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-survey-line"></i>
                                Ordem de Serviço #{{ $ordem->codigo_sequencial }}
                            </h4>
                        </div>
                        <div class="d-inline-flex gap-2 flex-wrap">
                            <a href="{{ route('ordem-servico.alterar-estado', [$ordem->id]) }}"
                               class="dash-btn dash-btn-light">
                                <i class="ri-refresh-line"></i> Alterar Estado
                            </a>
                            <a target="_blank" class="dash-btn dash-btn-primary"
                               href="{{ route('ordem-servico.imprimir', $ordem->id) }}">
                                <i class="ri-printer-line"></i> Imprimir OS
                            </a>
                            @if($ordem->nfe_id == 0)
                            <a class="dash-btn dash-btn-success"
                               href="{{ route('ordem-servico.gerar-nfe', $ordem->id) }}">
                                <i class="ri-file-text-line"></i> Gerar NF-e
                            </a>
                            @endif
                            <a href="{{ route('ordem-servico.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ RESUMO SUPERIOR ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="detail-card h-100">
                                <div class="detail-label"><i class="ri-user-line me-1"></i> Cliente Solicitante</div>
                                <p class="detail-value text-primary fs-16">{{ $ordem->cliente->razao_social }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-card h-100">
                                <div class="detail-label"><i class="ri-calendar-line me-1"></i> Data Início / Previsão</div>
                                <p class="detail-value">{{ __data_pt($ordem->data_inicio, 1) }}</p>
                                <div class="text-muted fs-12 mt-1">Previsão: <strong>{{ __data_pt($ordem->data_entrega, 1) }}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-card h-100">
                                <div class="detail-label"><i class="ri-money-dollar-circle-line me-1"></i> Valor Total da OS</div>
                                <p class="detail-value text-success fs-18">R$ {{ __moeda($ordem->valor) }}</p>
                                <div class="text-muted fs-12 mt-1">Operador: {{ $ordem->usuario->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detail-card h-100">
                                <div class="detail-label"><i class="ri-toggle-line me-1"></i> Estado da OS</div>
                                <div class="mt-1">
                                    @if($ordem->estado == 'pd')
                                    <span class="pill pill-amber">
                                        <i class="ri-time-line"></i> Pendente
                                    </span>
                                    @elseif($ordem->estado == 'ap')
                                    <span class="pill pill-ok">
                                        <i class="ri-check-line"></i> Aprovada
                                    </span>
                                    @elseif($ordem->estado == 'rp')
                                    <span class="pill pill-no">
                                        <i class="ri-close-line"></i> Reprovada
                                    </span>
                                    @elseif($ordem->estado == 'fz')
                                    <span class="pill pill-info">
                                        <i class="ri-check-double-line"></i> Finalizada
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BLOCO DE SERVIÇOS ═══ -->
                    <div class="tb-wrap mb-4">
                        <div class="inner-block-header">
                            <h5>
                                <i class="ri-tools-line"></i>
                                Serviços Prestados
                            </h5>
                        </div>
                        <div class="inner-block-body">
                            {!! Form::open()->post()->route('ordem-servico.store-servico') !!}
                            <input type="hidden" value="{{$ordem->id}}" name="ordem_servico_id">
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-4 col-12">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-briefcase-line me-1"></i>Serviço</label>
                                    {!! Form::select('servico_id', '', [null => 'Selecione um serviço'] + $servicos->pluck('nome', 'id')->all())->attrs(['class' => 'form-select select2', 'id' => 'inp-servico_id'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-calculator-line me-1"></i>Quantidade</label>
                                    {!! Form::tel('quantidade', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-quantidade'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-money-dollar-circle-line me-1"></i>Valor Unit. (R$)</label>
                                    {!! Form::text('valor', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-toggle-line me-1"></i>Status</label>
                                    {!! Form::select('status', '', [0 => 'Pendente', 1 => 'Finalizado'])->attrs(['class' => 'form-select', 'id' => 'inp-status'])->required() !!}
                                </div>
                                <input type="hidden" id="inp-nome" name="nome">
                                <div class="col-md-2 col-6 text-end">
                                    <button type="submit" class="dash-btn dash-btn-primary w-100 py-2 btn-add-servico">
                                        <i class="ri-add-line"></i> Adicionar
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}

                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 table-servico">
                                    <thead>
                                        <tr>
                                            <th>Nome do Serviço</th>
                                            <th style="width: 130px;">Quantidade</th>
                                            <th style="width: 140px;">Status</th>
                                            <th style="width: 160px;">Subtotal</th>
                                            <th class="text-end" style="width: 100px;">Ações</th>
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
                                                <span class="pill pill-ok">Finalizado</span>
                                                @else
                                                <span class="pill pill-amber">Pendente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input readonly type="tel" name="valor[]" class="form-control-plaintext py-0 fw-bold fs-14 text-success" value="R$ {{ __moeda($item->subtotal) }}">
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('ordem-servico.deletar-servico', $item->id) }}" method="post" id="form-servico-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="act-group">
                                                        <a title="Alterar Estado do Serviço" href="{{ route('ordem-servico.alterar-status-servico', $item->id) }}" class="act-btn act-view">
                                                            <i class="ri-refresh-line"></i>
                                                        </a>
                                                        <button type="button" class="act-btn act-del btn-delete" title="Excluir Serviço">
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
                    <div class="tb-wrap mb-4">
                        <div class="inner-block-header">
                            <h5>
                                <i class="ri-shopping-basket-line"></i>
                                Peças & Produtos Utilizados
                            </h5>
                        </div>
                        <div class="inner-block-body">
                            {!! Form::open()->post()->route('ordem-servico.store-produto') !!}
                            <input type="hidden" value="{{$ordem->id}}" name="ordem_servico_id">
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-5 col-12">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-box-3-line me-1"></i>Produto / Peça</label>
                                    {!! Form::select('produto_id', '')->attrs(['class' => 'form-select select2', 'id' => 'inp-produto_id'])->required() !!}
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-calculator-line me-1"></i>Quantidade</label>
                                    {!! Form::tel('quantidade_produto', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-quantidade_produto'])->required() !!}
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1"><i class="ri-money-dollar-circle-line me-1"></i>Valor Unit. (R$)</label>
                                    {!! Form::tel('valor_produto', '')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_produto'])->required() !!}
                                </div>
                                <input type="hidden" id="inp-nome_produto" name="nome_produto">
                                <div class="col-md-2 col-12 text-end">
                                    @if(!isset($not_submit))
                                    <button type="submit" class="dash-btn dash-btn-primary w-100 py-2 btn-add-produto">
                                        <i class="ri-add-line"></i> Adicionar
                                    </button>
                                    @endif
                                </div>
                            </div>
                            {!! Form::close() !!}

                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 table-produto">
                                    <thead>
                                        <tr>
                                            <th>Nome do Produto</th>
                                            <th style="width: 130px;">Quantidade</th>
                                            <th style="width: 160px;">Valor Unitário</th>
                                            <th style="width: 160px;">Subtotal</th>
                                            <th class="text-end" style="width: 100px;">Ações</th>
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
                                            <td>
                                                <input readonly type="tel" name="subtotal[]" class="form-control-plaintext py-0 fw-bold fs-14 text-success" value="R$ {{ __moeda($item->subtotal) }}">
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('ordem-servico.deletar-produto', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="act-group">
                                                        <button type="button" class="act-btn act-del btn-delete" title="Excluir Produto">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
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
                    <div class="tb-wrap mb-4">
                        <div class="inner-block-header">
                            <h5>
                                <i class="ri-file-list-3-line"></i>
                                Relatórios de Evolução Técnica
                            </h5>
                            <a href="{{ route('ordem-servico.add-relatorio', $ordem->id) }}" class="dash-btn dash-btn-primary btn-sm">
                                <i class="ri-add-line"></i> Adicionar Relatório
                            </a>
                        </div>
                        <div class="inner-block-body p-0">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Data de Registro</th>
                                            <th>Usuário Técnico</th>
                                            <th class="text-end" style="width: 120px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ordem->relatorios as $item)
                                        <tr>
                                            <td class="fw-semibold text-dark"><i class="ri-calendar-line me-1 text-muted"></i>{{ __data_pt($item->created_at) }}</td>
                                            <td><i class="ri-user-line me-1 text-muted"></i>{{ $item->usuario->name }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('ordem-servico.delete-relatorio', $item->id) }}" method="post" id="form-relatorio-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="act-group">
                                                        <a href="{{ route('ordem-servico.edit-relatorio', $item->id) }}" title="Editar Relatório" class="act-btn act-edit">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        <button type="button" class="act-btn act-del btn-delete" title="Excluir Relatório">
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
                    @if($ordem->descricao)
                    <div class="tb-wrap">
                        <div class="inner-block-header">
                            <h5>
                                <i class="ri-align-left"></i>
                                Descrição / Problema Relatado Geral
                            </h5>
                        </div>
                        <div class="inner-block-body">
                            <div class="p-3 bg-light rounded border text-dark fs-14" style="line-height: 1.6;">
                                {!! $ordem->descricao !!}
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/ordem_servico.js"></script>
@endsection

