@extends('layouts.app', ['title' => 'Central de Entrada de Notas (XML / SEFAZ)'])

@section('css')
<style type="text/css">
    input[type="file"] {
        display: none;
    }
    .custom-file-upload {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #c4b5fd;
        border-radius: 12px;
        padding: 50px 20px;
        background: #faf5ff;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .custom-file-upload:hover {
        border-color: #7c3aed;
        background: #f3e8ff;
        transform: translateY(-2px);
    }
    .custom-file-upload i {
        font-size: 54px;
        color: #7c3aed;
        margin-bottom: 12px;
    }

    /* ─── Header Gradiente ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.65) !important; font-weight: 400; }

    /* ─── Nav Tabs Custom ─── */
    .nav-tabs-custom {
        border-bottom: 2px solid #e2e8f0;
        gap: 8px;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 14.5px;
        padding: 12px 20px;
        border-radius: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .nav-tabs-custom .nav-link:hover {
        color: #4338ca;
        border-bottom-color: #cbd5e1;
    }
    .nav-tabs-custom .nav-link.active {
        color: #4f46e5;
        border-bottom-color: #4f46e5;
        background: transparent;
    }

    /* ─── KPI Cards ─── */
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        position: relative;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 15px;
        bottom: 12px;
        font-size: 40px;
        opacity: .12;
    }
    .stat-card .stat-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .stat-card .stat-val { font-size: 22px; font-weight: 800; line-height: 1.2; }
    .stat-card.c-blue .stat-title { color: #0284c7; }
    .stat-card.c-blue .stat-val { color: #0369a1; }
    .stat-card.c-amber .stat-title { color: #d97706; }
    .stat-card.c-amber .stat-val { color: #b45309; }
    .stat-card.c-green .stat-title { color: #16a34a; }
    .stat-card.c-green .stat-val { color: #15803d; }
    .stat-card.c-purple .stat-title { color: #7c3aed; }
    .stat-card.c-purple .stat-val { color: #6d28d9; }

    /* ─── Botões Personalizados ─── */
    .dash-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 14px;
        border: 1px solid transparent;
        transition: all .2s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .dash-btn-primary { background: #4f46e5; color: #fff; }
    .dash-btn-primary:hover { background: #4338ca; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,.3); }
    .dash-btn-success { background: #16a34a; color: #fff; }
    .dash-btn-success:hover { background: #15803d; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,163,74,.3); }
    .dash-btn-light { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .dash-btn-light:hover { background: #e2e8f0; color: #1e293b; }
    .dash-btn-purple { background: #7c3aed; color: #fff; }
    .dash-btn-purple:hover { background: #6d28d9; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(124,58,237,.3); }

    /* ─── Filtro Glass ─── */
    .modulo-glass-filter {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    /* ─── Tabela ─── */
    .tb-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .tb-wrap thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 12px 14px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .tb-wrap tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
    }
    .tb-wrap tbody tr:hover { background: #f8fafc; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-action-icon:hover { transform: translateY(-2px); }

    .pill-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 700;
    }
    .pill-green { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .pill-amber { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .pill-blue  { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .pill-purple{ background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
    .pill-red   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    .empty-state { padding: 48px 20px; text-align: center; }
    .empty-state i { font-size: 48px; color: #cbd5e1; display: block; margin-bottom: 10px; }
    .empty-state p { color: #64748b; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                
                <!-- ═══ CABEÇALHO PRINCIPAL ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-download-cloud-2-line"></i>
                                Central de Entrada de Notas Fiscais (NFe de Compra)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Busque notas emitidas pelos fornecedores na SEFAZ via Certificado A1 ou envie o arquivo XML do seu computador.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('compras.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar para Compras
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-blue">
                                <i class="ri-file-cloud-line stat-icon"></i>
                                <div class="stat-title">Notas na SEFAZ</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-amber">
                                <i class="ri-time-line stat-icon"></i>
                                <div class="stat-title">Pendentes de Entrada</div>
                                <div class="stat-val">{{ $stats['pendentes'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Importadas no Estoque</div>
                                <div class="stat-val">{{ $stats['importadas'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-purple">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Valor Total (R$)</div>
                                <div class="stat-val" style="font-size: 20px;">R$ {{ __moeda($stats['valor']) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ ABAS DE NAVEGAÇÃO ═══ -->
                    <ul class="nav nav-tabs nav-tabs-custom mb-4" id="comprasXmlTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sefaz-tab" data-bs-toggle="tab" data-bs-target="#sefaz-pane" type="button" role="tab">
                                <i class="ri-cloud-line fs-16"></i>
                                Notas Recebidas na SEFAZ (Busca Automática)
                                @if($stats['pendentes'] > 0)
                                    <span class="badge bg-danger rounded-pill ms-1">{{ $stats['pendentes'] }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual-pane" type="button" role="tab">
                                <i class="ri-upload-2-line fs-16"></i>
                                Upload Manual de Arquivo XML
                            </button>
                        </li>
                    </ul>

                    <!-- ═══ CONTEÚDO DAS ABAS ═══ -->
                    <div class="tab-content" id="comprasXmlTabContent">

                        <!-- ─── ABA 1: SEFAZ AUTOMÁTICO ─── -->
                        <div class="tab-pane fade show active" id="sefaz-pane" role="tabpanel">

                            <!-- Barra de Ações Rápidas SEFAZ -->
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <h5 class="mb-0 text-dark fw-bold d-flex align-items-center gap-2">
                                        <i class="ri-radar-line text-primary"></i>
                                        Manifestos e Notas Fiscais Destinadas
                                    </h5>
                                    <small class="text-muted">Consulta realizada diretamente no ambiente da SEFAZ Nacional via Certificado Digital A1.</small>
                                </div>
                                <div>
                                    {!! Form::open()->post()->route('compras.consultar-sefaz')->id('form-consultar-sefaz') !!}
                                    <button type="submit" id="btn-consultar-sefaz" class="dash-btn dash-btn-purple">
                                        <i class="ri-refresh-line"></i> 
                                        <span>Buscar Novas Notas na SEFAZ</span>
                                    </button>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <!-- Filtros da Busca -->
                            <div class="modulo-glass-filter">
                                {!! Form::open()->fill(request()->all())->get()->route('compras.xml') !!}
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fs-12 fw-bold text-muted mb-1"><i class="ri-search-line"></i> Fornecedor ou Chave</label>
                                        {!! Form::text('pesquisa', '')->placeholder('Razão Social, CNPJ ou Chave')->attrs(['class' => 'form-control form-control-sm']) !!}
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <label class="form-label fs-12 fw-bold text-muted mb-1"><i class="ri-calendar-line"></i> Data Inicial</label>
                                        {!! Form::date('start_date', '')->attrs(['class' => 'form-control form-control-sm']) !!}
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <label class="form-label fs-12 fw-bold text-muted mb-1"><i class="ri-calendar-line"></i> Data Final</label>
                                        {!! Form::date('end_date', '')->attrs(['class' => 'form-control form-control-sm']) !!}
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <label class="form-label fs-12 fw-bold text-muted mb-1"><i class="ri-filter-3-line"></i> Status Entrada</label>
                                        {!! Form::select('status', '', [
                                            ''           => 'Todas',
                                            'pendentes'  => '⏳ Pendentes',
                                            'importadas' => '✅ Importadas'
                                        ])->attrs(['class' => 'form-select form-select-sm']) !!}
                                    </div>
                                    <div class="col-md-2 col-6 d-flex gap-2">
                                        <button type="submit" class="dash-btn dash-btn-primary w-100 justify-content-center">
                                            <i class="ri-filter-line"></i> Filtrar
                                        </button>
                                        <a href="{{ route('compras.xml') }}" class="dash-btn dash-btn-light" title="Limpar Filtros">
                                            <i class="ri-eraser-line"></i>
                                        </a>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <!-- Tabela de Notas Fiscais Recebidas -->
                            <div class="tb-wrap mb-3">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 140px;">Ações</th>
                                                <th>Fornecedor / Emitente</th>
                                                <th>Chave de Acesso / N.º</th>
                                                <th>Data Emissão</th>
                                                <th>Valor da Nota</th>
                                                <th>Manifesto SEFAZ</th>
                                                <th>Status Estoque</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data as $item)
                                            <tr>
                                                <!-- Ações -->
                                                <td>
                                                    <div class="d-inline-flex gap-1 align-items-center">
                                                        @if($item->compra_id > 0)
                                                            <a href="{{ route('compras.show', $item->compra_id) }}" class="dash-btn dash-btn-light" style="padding: 4px 8px; font-size: 11px;" title="Ver Compra Realizada">
                                                                <i class="ri-eye-line"></i> Compra #{{ $item->compra_id }}
                                                            </a>
                                                        @else
                                                            <a href="{{ route('compras.importar-dfe', $item->id) }}" class="dash-btn dash-btn-success" style="padding: 4px 10px; font-size: 11.5px;" title="Dar Entrada e Alimentar Estoque">
                                                                <i class="ri-download-line"></i> Dar Entrada
                                                            </a>
                                                        @endif

                                                        <!-- Imprimir DANFE -->
                                                        <a href="{{ route('compras.danfe-dfe', $item->id) }}" target="_blank" class="btn-action-icon btn btn-light text-primary" style="background:#e0f2fe;" title="Visualizar DANFE (PDF)">
                                                            <i class="ri-printer-line"></i>
                                                        </a>

                                                        <!-- Manifestar -->
                                                        <button type="button" class="btn-action-icon btn btn-light text-purple" style="background:#f3e8ff; color:#7c3aed;" title="Manifestar Operação" onclick="abrirModalManifestar('{{ $item->chave }}', '{{ $item->nome }}', '{{ $item->tipo }}')">
                                                            <i class="ri-file-edit-line"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <!-- Fornecedor -->
                                                <td>
                                                    <div class="fw-bold text-dark fs-13">{{ $item->nome }}</div>
                                                    <small class="text-muted font-monospace">{{ $item->documento }}</small>
                                                </td>

                                                <!-- Chave / N.º -->
                                                <td>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="font-monospace fs-11 text-muted" title="{{ $item->chave }}">
                                                            {{ substr($item->chave, 0, 8) }}...{{ substr($item->chave, -8) }}
                                                        </span>
                                                        <button type="button" class="btn btn-sm btn-link p-0 text-muted" title="Copiar Chave Completa" onclick="navigator.clipboard.writeText('{{ $item->chave }}'); toastr.success('Chave copiada!');">
                                                            <i class="ri-file-copy-line fs-13"></i>
                                                        </button>
                                                    </div>
                                                    @if($item->num_prot)
                                                    <small class="text-secondary font-monospace fs-11">Prot: {{ $item->num_prot }}</small>
                                                    @endif
                                                </td>

                                                <!-- Data Emissão -->
                                                <td class="text-muted fs-12 whitespace-nowrap">
                                                    {{ __data_pt($item->data_emissao, 0) }}
                                                </td>

                                                <!-- Valor -->
                                                <td class="whitespace-nowrap">
                                                    <span class="fw-bold text-success fs-13">R$ {{ __moeda($item->valor) }}</span>
                                                </td>

                                                <!-- Manifesto SEFAZ -->
                                                <td>
                                                    @if($item->tipo == 1)
                                                        <span class="pill-status pill-blue" title="Ciência da Emissão Registrada"><i class="ri-eye-line"></i> Ciência</span>
                                                    @elseif($item->tipo == 2)
                                                        <span class="pill-status pill-green" title="Operação Confirmada na SEFAZ"><i class="ri-checkbox-circle-fill"></i> Confirmada</span>
                                                    @elseif($item->tipo == 3)
                                                        <span class="pill-status pill-red" title="Desconhecimento da Operação"><i class="ri-close-circle-fill"></i> Desconhecida</span>
                                                    @elseif($item->tipo == 4)
                                                        <span class="pill-status pill-red" title="Operação Não Realizada"><i class="ri-error-warning-fill"></i> Não Realizada</span>
                                                    @else
                                                        <span class="pill-status pill-amber" title="Aguardando Manifestação"><i class="ri-time-line"></i> Sem Manifesto</span>
                                                    @endif
                                                </td>

                                                <!-- Status Estoque -->
                                                <td>
                                                    @if($item->compra_id > 0)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                            <i class="ri-check-line me-1"></i>Importada
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                                            <i class="ri-time-line me-1"></i>Pendente
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7">
                                                    <div class="empty-state">
                                                        <i class="ri-inbox-2-line"></i>
                                                        <p>Nenhuma nota fiscal encontrada na SEFAZ para os filtros informados.</p>
                                                        <small class="text-muted d-block mt-1">Clique no botão roxo acima <strong>"Buscar Novas Notas na SEFAZ"</strong> para consultar novos documentos.</small>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Paginação -->
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <small class="text-muted">Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> nota(s) retornada(s)</small>
                                </div>
                                <div>
                                    {!! $data->appends(request()->all())->links() !!}
                                </div>
                            </div>

                        </div>

                        <!-- ─── ABA 2: UPLOAD MANUAL ─── -->
                        <div class="tab-pane fade" id="manual-pane" role="tabpanel">
                            <div class="card-body p-4 text-center">
                                <div class="mb-4">
                                    <h5 class="text-dark fw-bold">Importar Arquivo XML do Fornecedor</h5>
                                    <p class="text-muted fs-13">Selecione ou arraste o arquivo <code>.xml</code> salvo no seu computador para dar entrada no estoque.</p>
                                </div>

                                {!! Form::open()->post()->route('compras.store-xml')->multipart()->id('form-xml') !!}
                                <div class="row justify-content-center">
                                    <div class="col-md-7 col-12">
                                        <label for="inp-file" class="custom-file-upload mb-3">
                                            <i class="ri-file-code-line"></i>
                                            <span class="fw-bold text-dark fs-16">Arraste ou clique para selecionar o XML</span>
                                            <span class="text-muted fs-12 mt-1">Formatos aceitos: arquivos no formato <strong>.xml</strong> da NF-e</span>
                                        </label>
                                        
                                        {!! Form::file('file', 'XML')->attrs(['accept' => '.xml', 'id' => 'inp-file']) !!}
                                        
                                        <div class="mt-2">
                                            <span class="text-primary fw-semibold fs-13" id="filename"></span>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL DE MANIFESTAÇÃO SEFAZ ═══ -->
<div class="modal fade" id="modal-manifestar" tabindex="-1" aria-labelledby="modalManifestarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            {!! Form::open()->post()->route('compras.manifestar-dfe') !!}
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title text-white" id="modalManifestarLabel">
                    <i class="ri-file-edit-line me-1"></i> Manifestação do Destinatário (SEFAZ)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="chave" id="manifesto_chave">
                
                <div class="mb-3">
                    <label class="form-label text-muted fs-12 fw-bold">Fornecedor / Emitente</label>
                    <input type="text" id="manifesto_nome" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fs-13 fw-bold">Tipo de Evento</label>
                    <select name="tipo" id="manifesto_tipo" class="form-select" required onchange="toggleJustificativa(this.value)">
                        <option value="1">1 - Ciência da Emissão</option>
                        <option value="2">2 - Confirmação da Operação</option>
                        <option value="3">3 - Desconhecimento da Operação</option>
                        <option value="4">4 - Operação não Realizada</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="div-justificativa">
                    <label class="form-label fs-13 fw-bold text-danger">Justificativa (Mínimo 15 caracteres) *</label>
                    <textarea name="justificativa" id="justificativa" class="form-control" rows="3" placeholder="Informe o motivo da não realização ou desconhecimento..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="dash-btn dash-btn-primary">
                    <i class="ri-send-plane-line"></i> Enviar Manifestação
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    // Upload manual com auto submit
    $('#inp-file').change(function() {
        let filename = $(this).val().split('\\').pop();
        if(filename){
            $('#filename').html('<i class="ri-loader-4-line ri-spin me-1"></i> Carregando: ' + filename + '...');
            setTimeout(() => {
                $('#form-xml').submit();
                $("body").addClass("loading");
            }, 400);
        }
    });

    // Loading ao buscar na SEFAZ
    $('#form-consultar-sefaz').submit(function() {
        let btn = $('#btn-consultar-sefaz');
        btn.prop('disabled', true);
        btn.html('<i class="ri-loader-4-line ri-spin"></i> Consultando SEFAZ...');
        $("body").addClass("loading");
    });

    // Abrir modal de manifestação
    function abrirModalManifestar(chave, nome, tipoAtual) {
        $('#manifesto_chave').val(chave);
        $('#manifesto_nome').val(nome);
        $('#manifesto_tipo').val(tipoAtual > 0 ? tipoAtual : 1);
        toggleJustificativa($('#manifesto_tipo').val());
        $('#modal-manifestar').modal('show');
    }

    function toggleJustificativa(val) {
        if (val == 3 || val == 4) {
            $('#div-justificativa').removeClass('d-none');
            $('#justificativa').prop('required', true);
        } else {
            $('#div-justificativa').addClass('d-none');
            $('#justificativa').prop('required', false);
        }
    }
</script>
@endsection
