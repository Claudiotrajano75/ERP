@extends('layouts.app', ['title' => 'Manutenção #' . $item->numero_sequencial])

@section('css')
<style>
/* ─── Informações e Detalhes ─── */
.manut-info-card { background: #ffffff; border: 1px solid #eef0f6; border-radius: 14px; padding: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); height: 100%; }
.manut-section-title { display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.manut-section-title .f-ico { width: 28px; height: 28px; border-radius: 8px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; }
.manut-section-title small { font-weight: 500; color: #94a3b8; font-size: 11.5px; margin-left: auto; }

.detail-item { margin-bottom: 14px; }
.detail-label { font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; margin-bottom: 3px; }
.detail-value { font-size: 14px; color: #1e293b; font-weight: 600; }

/* ─── Cards de Estatísticas / Valores ─── */
.stat-card { border-radius: 14px; padding: 18px 20px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.07); }
.stat-card .stat-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 40px; opacity: .22; }
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.stat-card .stat-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; opacity: .85; margin-bottom: 4px; }
.stat-card .stat-val   { font-size: 22px; font-weight: 800; line-height: 1; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 12px 14px; border-bottom: 1px solid #e8eaf6; }
.tb-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
.tb-wrap tfoot td { background: #f8f9fc; font-weight: 700; font-size: 13.5px; padding: 12px 14px; border-top: 2px solid #e8eaf6; }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }

/* ─── Anexos ─── */
.anexo-card { border: 1px solid #eef0f5; border-radius: 12px; padding: 14px 18px; transition: all .15s ease; background: #ffffff; }
.anexo-card:hover { background: #f8f9ff; border-color: #c7d2fe; }

@page { size: auto; margin: 0mm; }
@media print { .print { margin: 10px; } }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm print">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-tools-line"></i>
                                Detalhes da Manutenção <strong class="text-primary ms-1">#{{ $item->numero_sequencial }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualize o resumo financeiro, itens, serviços, faturamento e anexos da manutenção.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2 d-print-none">
                            <a class="dash-btn dash-btn-light" href="javascript:window.print()">
                                <i class="ri-printer-line"></i> Imprimir
                            </a>

                            @if($item->conta_pagar_id == 0)
                            <a class="dash-btn dash-btn-success" href="{{ route('manutencao-veiculos.gerar-conta-pagar', $item->id) }}">
                                <i class="ri-file-text-line"></i> Gerar Conta a Pagar
                            </a>
                            @else
                            <a class="dash-btn dash-btn-light" href="{{ route('conta-pagar.edit', $item->conta_pagar_id) }}">
                                <i class="ri-file-list-3-line"></i> Ver Conta a Pagar
                            </a>
                            @endif

                            @can('manutencao_veiculo_edit')
                            <a href="{{ route('manutencao-veiculos.edit', $item->id) }}" class="dash-btn dash-btn-primary">
                                <i class="ri-pencil-line"></i> Editar
                            </a>
                            @endcan

                            <a href="{{ route('manutencao-veiculos.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DOS DETALHES ═══ -->
                <div class="card-body p-4">

                    <!-- ═══ CARDS DE VALORES & INDICADORES ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-blue">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Valor Total</div>
                                <div class="stat-val">R$ {{ __moeda($item->total) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-amber">
                                <i class="ri-discount-percent-line stat-icon"></i>
                                <div class="stat-title">Desconto</div>
                                <div class="stat-val">R$ {{ __moeda($item->desconto) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-green">
                                <i class="ri-add-circle-line stat-icon"></i>
                                <div class="stat-title">Acréscimo</div>
                                <div class="stat-val">R$ {{ __moeda($item->acrescimo) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-purple">
                                <i class="ri-flag-line stat-icon"></i>
                                <div class="stat-title">Status Atual</div>
                                <div class="stat-val fs-16 text-uppercase">
                                    @if($item->estado == 'aguardando')
                                    Aguardando
                                    @elseif($item->estado == 'em_manutencao')
                                    Em manutenção
                                    @else
                                    Finalizado
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ PAINEL DE CONTROLE DE ESTADO DA MANUTENÇÃO ═══ -->
                    <div class="p-3 bg-light rounded-3 border border-light-subtle mb-4 d-print-none">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold fs-13 text-dark">Status Atual:</span>
                                @if($item->estado == 'aguardando')
                                <span class="pill pill-amber"><i class="ri-time-line"></i> Aguardando</span>
                                @elseif($item->estado == 'em_manutencao')
                                <span class="pill pill-info"><i class="ri-tools-line"></i> Em manutenção</span>
                                @else
                                <span class="pill pill-ok"><i class="ri-checkbox-circle-fill"></i> Finalizado</span>
                                @endif
                            </div>
                            <div>
                                <button id="btn-alterar-estado" type="button" class="btn btn-sm btn-outline-dark">
                                    <i class="ri-swap-line"></i> Alterar Status
                                </button>
                            </div>
                        </div>

                        <form class="row g-2 form-alterar-estado mt-3 d-none align-items-end" method="post" action="{{ route('manutencao-veiculos.alterar-estado', [$item->id]) }}">
                            @csrf
                            @method('put')
                            <div class="col-md-4 col-12">
                                <label class="form-label fs-12 fw-bold text-muted">Novo Status</label>
                                {!!Form::select('estado', '', [
                                    'aguardando' => 'Aguardando',
                                    'em_manutencao' => 'Em manutenção',
                                    'finalizado' => 'Finalizado',
                                ])
                                ->value($item->estado)
                                ->attrs(['class' => 'form-select'])
                                !!}
                            </div>
                            <div class="col-md-3 col-12">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="ri-check-line"></i> Confirmar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ═══ GRID DE INFORMAÇÕES: VEÍCULO, CRONOGRAMA & FORNECEDOR ═══ -->
                    <div class="row g-4 mb-4">
                        
                        <!-- Coluna 1: Informações do Veículo e Datas -->
                        <div class="col-lg-6 col-12">
                            <div class="manut-info-card">
                                <div class="manut-section-title">
                                    <span class="f-ico"><i class="ri-truck-line"></i></span>
                                    Veículo e Cronograma
                                    <small>dados operacionais</small>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6 col-6 detail-item">
                                        <div class="detail-label">Veículo / Placa</div>
                                        <div class="detail-value text-primary">{{ $item->veiculo->info }}</div>
                                    </div>
                                    <div class="col-md-6 col-6 detail-item">
                                        <div class="detail-label">Data de Cadastro</div>
                                        <div class="detail-value">{{ __data_pt($item->created_at) }}</div>
                                    </div>
                                    <div class="col-md-6 col-6 detail-item">
                                        <div class="detail-label">Início da Manutenção</div>
                                        <div class="detail-value">{{ __data_pt($item->data_inicio, 0) }}</div>
                                    </div>
                                    <div class="col-md-6 col-6 detail-item">
                                        <div class="detail-label">Fim / Previsão</div>
                                        <div class="detail-value">{{ $item->data_fim ? __data_pt($item->data_fim, 0) : '-' }}</div>
                                    </div>
                                    @if($item->observacao)
                                    <div class="col-12 detail-item mb-0">
                                        <div class="detail-label">Observações</div>
                                        <div class="detail-value fs-13 text-muted">{{ $item->observacao }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Coluna 2: Dados do Fornecedor / Oficina -->
                        <div class="col-lg-6 col-12">
                            <div class="manut-info-card">
                                <div class="manut-section-title">
                                    <span class="f-ico"><i class="ri-store-2-line"></i></span>
                                    Fornecedor / Oficina Mecânica
                                    <small>informações de contato</small>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7 col-12 detail-item">
                                        <div class="detail-label">Razão Social / Nome</div>
                                        <div class="detail-value">{{ $item->fornecedor->info }}</div>
                                    </div>
                                    <div class="col-md-5 col-12 detail-item">
                                        <div class="detail-label">CPF / CNPJ</div>
                                        <div class="detail-value">{{ $item->fornecedor->cpf_cnpj ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6 col-6 detail-item">
                                        <div class="detail-label">Telefone / WhatsApp</div>
                                        <div class="detail-value">{{ $item->fornecedor->telefone ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6 col-6 detail-item">
                                        <div class="detail-label">E-mail</div>
                                        <div class="detail-value">{{ $item->fornecedor->email ?? '-' }}</div>
                                    </div>
                                    <div class="col-12 detail-item mb-0">
                                        <div class="detail-label">Endereço Completo</div>
                                        <div class="detail-value fs-13">
                                            {{ $item->fornecedor->rua ?? '' }}, {{ $item->fornecedor->numero ?? '' }}
                                            @if($item->fornecedor->bairro) - {{ $item->fornecedor->bairro }} @endif
                                            @if($item->fornecedor->cidade) | {{ $item->fornecedor->cidade->info }} @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ TABELA DE SERVIÇOS ═══ -->
                    <div class="manut-info-card mb-4">
                        <div class="manut-section-title">
                            <span class="f-ico"><i class="ri-tools-line"></i></span>
                            Serviços e Mão de Obra Realizados
                            <small>{{ sizeof($item->servicos) }} serviço(s)</small>
                        </div>
                        <div class="tb-wrap">
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
                                            <td><strong class="text-dark">{{ $i->servico->nome }}</strong></td>
                                            <td>{{ __moeda($i->quantidade) }}</td>
                                            <td>R$ {{ __moeda($i->valor_unitario) }}</td>
                                            <td class="fw-bold text-primary">R$ {{ __moeda($i->sub_total) }}</td>
                                            <td>{{ $i->observacao ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">Nenhum serviço registrado nesta manutenção.</td></tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-uppercase fs-12 fw-bold text-muted">Total Serviços</td>
                                            <td colspan="2" class="text-primary fw-bold fs-14">R$ {{ __moeda($item->servicos->sum('sub_total')) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ TABELA DE PRODUTOS / PEÇAS ═══ -->
                    <div class="manut-info-card mb-4">
                        <div class="manut-section-title">
                            <span class="f-ico"><i class="ri-shopping-basket-line"></i></span>
                            Peças e Produtos Utilizados
                            <small>{{ sizeof($item->produtos) }} produto(s)</small>
                        </div>
                        <div class="tb-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Produto / Peça</th>
                                            <th>Quantidade</th>
                                            <th>Valor Unitário</th>
                                            <th>Subtotal</th>
                                            <th>Observação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->produtos as $i)
                                        <tr>
                                            <td><strong class="text-dark">{{ $i->produto->nome }}</strong></td>
                                            <td>{{ __moeda($i->quantidade) }}</td>
                                            <td>R$ {{ __moeda($i->valor_unitario) }}</td>
                                            <td class="fw-bold text-primary">R$ {{ __moeda($i->sub_total) }}</td>
                                            <td>{{ $i->observacao ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">Nenhuma peça registrada nesta manutenção.</td></tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-uppercase fs-12 fw-bold text-muted">Total Produtos</td>
                                            <td colspan="2" class="text-primary fw-bold fs-14">R$ {{ __moeda($item->produtos->sum('sub_total')) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ UPLOAD & LISTA DE ANEXOS ═══ -->
                    <div class="manut-info-card d-print-none">
                        <div class="manut-section-title">
                            <span class="f-ico"><i class="ri-attachment-line"></i></span>
                            Anexos & Comprovantes da Oficina
                            <small>laudos, notas fiscais e recibos de manutenção</small>
                        </div>

                        <form class="row g-3 mb-4 align-items-end" enctype="multipart/form-data" method="post" action="{{ route('manutencao-veiculos.upload', [$item->id]) }}">
                            @csrf
                            <div class="col-md-6 col-12">
                                <label class="form-label fs-12 fw-bold text-muted"><i class="ri-file-upload-line me-1"></i> Selecionar Arquivo (PDF ou Imagens)</label>
                                <input type="file" name="file" class="form-control" accept=".pdf, image/*" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-upload-2-line"></i> Enviar Anexo
                                </button>
                            </div>
                        </form>

                        @if($item->anexos->count() > 0)
                        <div class="row g-3">
                            @foreach($item->anexos as $key => $a)
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="anexo-card d-flex justify-content-between align-items-center">
                                    <div class="overflow-hidden me-2">
                                        <a target="_blank" href="{{ $a->file }}" class="fw-bold text-dark fs-13 d-block text-truncate">
                                            <i class="ri-file-paper-2-line me-1 text-primary"></i> Anexo #{{ $key + 1 }}
                                        </a>
                                        <small class="text-muted fs-11">Clique para visualizar</small>
                                    </div>
                                    <form action="{{ route('manutencao-veiculos.destroy-file', $a->id) }}" method="post" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger border-0" title="Remover Anexo">
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
