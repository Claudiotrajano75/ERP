@extends('layouts.app', ['title' => 'Créditos de Clientes'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
}
.modulo-header-gradient .modulo-title {
    color: #fff;
    font-weight: 700;
    letter-spacing: -0.3px;
}
.modulo-header-gradient .modulo-title i {
    background: rgba(255,255,255,0.12);
    padding: 8px;
    border-radius: 10px;
    color: #a8b5ff;
}
.modulo-header-gradient .modulo-subtitle {
    color: rgba(255,255,255,0.6) !important;
    font-weight: 400;
}

/* ─── Cards de KPI ─── */
.kpi-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eef0f6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}
.kpi-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #8c8ca6;
    margin-bottom: 4px;
}
.kpi-value {
    font-size: 20px;
    font-weight: 700;
    color: #1e1e38;
    margin: 0;
}
.kpi-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.kpi-green { background: rgba(25, 135, 84, 0.1); color: #198754; }
.kpi-blue { background: rgba(85, 114, 245, 0.1); color: #5572f5; }
.kpi-purple { background: rgba(113, 44, 249, 0.1); color: #712cf9; }
.kpi-orange { background: rgba(253, 126, 20, 0.1); color: #fd7e14; }

/* ─── Filtro Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    padding: 16px 20px !important;
    margin-bottom: 20px;
}
.modulo-glass-filter-premium label {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c8ca6 !important;
    margin-bottom: 6px !important;
}

/* ─── Tabela ─── */
.credito-table {
    margin-bottom: 0;
}
.credito-table thead th {
    background-color: #f8f9fc;
    color: #555577;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e8eaf2;
    padding: 12px 16px;
}
.credito-table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    font-size: 13px;
    color: #33334d;
    border-bottom: 1px solid #f0f2f8;
}
.badge-saldo {
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
}

/* ─── Extrato Modal Timeline ─── */
.timeline-extrato {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
}
.timeline-item {
    position: relative;
    padding-left: 30px;
    padding-bottom: 16px;
    border-left: 2px solid #e9ecef;
}
.timeline-item:last-child {
    border-left: 2px solid transparent;
    padding-bottom: 0;
}
.timeline-marker {
    position: absolute;
    left: -7px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #5572f5;
}
.timeline-marker.entrada { border-color: #198754; background: #198754; }
.timeline-marker.saida { border-color: #dc3545; background: #dc3545; }
</style>
@endsection

@section('content')
<div class="container-fluid mt-3">

    <!-- CABEÇALHO DA PÁGINA -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header modulo-header-gradient py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                        <i class="ri-wallet-3-line"></i>
                        Créditos de Clientes (Vales & Trocas)
                    </h4>
                    <p class="mb-0 modulo-subtitle fs-13">
                        Gerencie saldos em haver, consulte extratos de trocas e utilize créditos em compras no PDV.
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('trocas.index') }}" class="btn btn-light btn-sm px-3 text-dark fw-semibold">
                        <i class="ri-arrow-left-right-line me-1"></i> Ir para Trocas
                    </a>
                    <a href="{{ route('frontbox.create') }}" class="btn btn-primary btn-sm px-3 fw-semibold">
                        <i class="ri-shopping-cart-line me-1"></i> Abrir PDV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- CARDS DE METRICAS (KPIS) -->
    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div>
                    <div class="kpi-title">Total Crédito em Aberto</div>
                    <div class="kpi-value text-success">R$ {{ __moeda($totalCreditoAberto) }}</div>
                </div>
                <div class="kpi-icon-box kpi-green">
                    <i class="ri-wallet-3-line"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div>
                    <div class="kpi-title">Clientes com Saldo</div>
                    <div class="kpi-value text-primary">{{ $totalClientesComSaldo }}</div>
                </div>
                <div class="kpi-icon-box kpi-blue">
                    <i class="ri-user-smile-line"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div>
                    <div class="kpi-title">Total Gerado (Trocas)</div>
                    <div class="kpi-value text-purple">R$ {{ __moeda($totalEntradasCredito) }}</div>
                </div>
                <div class="kpi-icon-box kpi-purple">
                    <i class="ri-arrow-down-circle-line"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div>
                    <div class="kpi-title">Total Utilizado no PDV</div>
                    <div class="kpi-value text-orange">R$ {{ __moeda($totalUtilizadoCredito) }}</div>
                </div>
                <div class="kpi-icon-box kpi-orange">
                    <i class="ri-arrow-up-circle-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTRO DE PESQUISA -->
    <div class="modulo-glass-filter-premium">
        <form method="GET" action="{{ route('creditos-cliente.index') }}" class="row g-3 align-items-end">
            <div class="col-md-6 col-12">
                <label for="pesquisa"><i class="ri-search-line me-1"></i> Buscar Cliente</label>
                <input type="text" name="pesquisa" id="pesquisa" class="form-control" 
                    placeholder="Digite o nome, razão social ou CPF/CNPJ..." value="{{ $pesquisa }}">
            </div>
            <div class="col-md-3 col-6">
                <label for="somente_com_saldo"><i class="ri-filter-3-line me-1"></i> Exibir</label>
                <select name="somente_com_saldo" id="somente_com_saldo" class="form-select">
                    <option value="1" {{ $somente_com_saldo === '1' ? 'selected' : '' }}>Somente com saldo positivo</option>
                    <option value="0" {{ $somente_com_saldo === '0' ? 'selected' : '' }}>Todos os clientes</option>
                </select>
            </div>
            <div class="col-md-3 col-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="ri-search-line me-1"></i> Filtrar
                </button>
                <a href="{{ route('creditos-cliente.index') }}" class="btn btn-light border px-3" title="Limpar Filtro">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- TABELA DE CLIENTES COM CRÉDITO -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table credito-table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>CPF / CNPJ</th>
                            <th>Contato</th>
                            <th>Cidade / UF</th>
                            <th class="text-end">Saldo de Crédito</th>
                            <th class="text-center" style="width: 160px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->razao_social }}</div>
                                @if($item->nome_fantasia && $item->nome_fantasia != $item->razao_social)
                                    <small class="text-muted">{{ $item->nome_fantasia }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $item->cpf_cnpj ?: 'Não informado' }}</span>
                            </td>
                            <td>
                                @if($item->telefone)
                                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $item->telefone) }}" target="_blank" class="text-decoration-none text-success small fw-semibold" title="Abrir WhatsApp">
                                        <i class="ri-whatsapp-line me-1"></i> {{ $item->telefone }}
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $item->cidade ? $item->cidade->nome . ' - ' . $item->cidade->uf : '-' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($item->valor_credito > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle badge-saldo">
                                        R$ {{ __moeda($item->valor_credito) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary badge-saldo">
                                        R$ 0,00
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-ver-extrato" 
                                        data-id="{{ $item->id }}" title="Ver Extrato de Movimentações">
                                        <i class="ri-file-list-3-line me-1"></i> Extrato
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-ajustar-saldo" 
                                        data-id="{{ $item->id }}" data-nome="{{ $item->razao_social }}" data-saldo="{{ __moeda($item->valor_credito) }}"
                                        title="Ajuste Manual de Saldo">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-wallet-line display-4 d-block mb-2 text-secondary"></i>
                                    <h5>Nenhum cliente com saldo de crédito encontrado</h5>
                                    <p class="small text-muted mb-0">Quando uma troca ou devolução gerar saldo, ele aparecerá nesta listagem.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($data->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-end">
                {!! $data->appends(request()->all())->links() !!}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- MODAL EXTRATO DE CRÉDITO -->
<div class="modal fade" id="modalExtrato" tabindex="-1" aria-labelledby="modalExtratoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient text-white">
                <div>
                    <h5 class="modal-title modulo-title mb-0" id="modalExtratoLabel">
                        <i class="ri-file-list-3-line me-1"></i> Extrato de Crédito do Cliente
                    </h5>
                    <small class="modulo-subtitle text-cliente-extrato">-</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block">Saldo de Crédito Atual</span>
                            <h4 class="mb-0 text-success fw-bold text-saldo-extrato">R$ 0,00</h4>
                        </div>
                        <span class="badge bg-success-subtle text-success p-2 rounded-3 fs-13">
                            <i class="ri-checkbox-circle-line me-1"></i> Disponível para compras
                        </span>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3"><i class="ri-history-line me-1"></i> Histórico de Movimentações</h6>
                <div id="loadingExtrato" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="small text-muted mt-2">Carregando movimentações...</p>
                </div>
                <div id="conteudoExtrato">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Data / Hora</th>
                                    <th>Tipo</th>
                                    <th>Descrição da Operação</th>
                                    <th class="text-end">Valor</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaExtratoCorpo">
                                <!-- Preenchido via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL AJUSTE MANUAL DE SALDO -->
<div class="modal fade" id="modalAjuste" tabindex="-1" aria-labelledby="modalAjusteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAjuste" method="POST" action="">
                @csrf
                <div class="modal-header modulo-header-gradient text-white">
                    <h5 class="modal-title modulo-title mb-0" id="modalAjusteLabel">
                        <i class="ri-edit-line me-1"></i> Ajustar Saldo de Crédito
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Cliente</label>
                        <input type="text" class="form-control" id="ajusteClienteNome" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Saldo Atual</label>
                        <input type="text" class="form-control fw-bold text-success" id="ajusteClienteSaldo" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tipo de Ajuste</label>
                        <select name="tipo_ajuste" class="form-select" required>
                            <option value="adicionar">Adicionar Crédito (+)</option>
                            <option value="remover">Remover / Estornar Crédito (-)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Valor do Ajuste (R$)</label>
                        <input type="tel" name="valor" class="form-control moeda" placeholder="0,00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Motivo / Justificativa</label>
                        <textarea name="motivo" class="form-control" rows="2" placeholder="Ex: Acordo comercial, correção de troca, etc." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold">Confirmar Ajuste</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(function () {
    // Abrir Modal de Extrato
    $('.btn-ver-extrato').on('click', function () {
        var id = $(this).data('id');
        $('#modalExtrato').modal('show');
        $('#loadingExtrato').removeClass('d-none');
        $('#conteudoExtrato').addClass('d-none');
        $('#tabelaExtratoCorpo').empty();

        $.get('/creditos-cliente/' + id + '/extrato')
        .done(function (res) {
            $('.text-cliente-extrato').text(res.cliente.razao_social + ' (CPF/CNPJ: ' + (res.cliente.cpf_cnpj || 'N/A') + ')');
            $('.text-saldo-extrato').text('R$ ' + res.cliente.saldo_formatado);

            if (res.movimentacoes && res.movimentacoes.length > 0) {
                var html = '';
                res.movimentacoes.forEach(function (m) {
                    var corValor = m.tipo === 'ENTRADA' ? 'text-success' : 'text-danger';
                    var icone = m.tipo === 'ENTRADA' ? '<i class="ri-arrow-down-line text-success me-1"></i>' : '<i class="ri-arrow-up-line text-danger me-1"></i>';
                    html += '<tr>' +
                        '<td>' + m.data_hora + '</td>' +
                        '<td><span class="badge bg-' + m.tipo_badge + '-subtle text-' + m.tipo_badge + '">' + m.tipo + '</span></td>' +
                        '<td>' + icone + m.descricao + '</td>' +
                        '<td class="text-end fw-bold ' + corValor + '">' + m.valor_formatado + '</td>' +
                    '</tr>';
                });
                $('#tabelaExtratoCorpo').html(html);
            } else {
                $('#tabelaExtratoCorpo').html('<tr><td colspan="4" class="text-center text-muted py-3">Nenhuma movimentação registrada no histórico.</td></tr>');
            }

            $('#loadingExtrato').addClass('d-none');
            $('#conteudoExtrato').removeClass('d-none');
        })
        .fail(function () {
            $('#loadingExtrato').addClass('d-none');
            $('#tabelaExtratoCorpo').html('<tr><td colspan="4" class="text-center text-danger py-3">Erro ao carregar extrato.</td></tr>');
            $('#conteudoExtrato').removeClass('d-none');
        });
    });

    // Abrir Modal de Ajuste
    $('.btn-ajustar-saldo').on('click', function () {
        var id = $(this).data('id');
        var nome = $(this).data('nome');
        var saldo = $(this).data('saldo');

        $('#ajusteClienteNome').val(nome);
        $('#ajusteClienteSaldo').val('R$ ' + saldo);
        $('#formAjuste').attr('action', '/creditos-cliente/' + id + '/ajustar');
        $('#modalAjuste').modal('show');
    });
});
</script>
@endsection
