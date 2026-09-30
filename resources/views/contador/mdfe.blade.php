@extends('layouts.app', ['title' => 'MDFe da Empresa'])

@section('css')
<style>
    /* ─── Cards de Estatística ─── */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #e9ecf3;
        box-shadow: 0 1px 3px rgba(16,24,40,.04);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16,24,40,.08);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .stat-icon-indigo { background: #eef2ff; color: #4338ca; }
    .stat-icon-green  { background: #ecfdf5; color: #047857; }
    .stat-icon-blue   { background: #eff6ff; color: #1d4ed8; }
    .stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        margin-bottom: 2px;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    /* ─── Filtro ─── */
    .filter-wrap {
        background: #fff;
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(16,24,40,.04);
        padding: 18px 20px;
        margin-bottom: 20px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }
    @media (max-width: 768px) {
        .filter-grid { grid-template-columns: 1fr; }
    }
    .filter-field label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #475569;
        margin-bottom: 5px;
        display: block;
    }
    .filter-field .form-control {
        border-radius: 9px;
        border: 1px solid #d1d5db;
        padding: 8px 12px;
        font-size: 13px;
        color: #111827;
        background-color: #fff;
        box-shadow: none;
    }
    .filter-field .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        outline: 0;
    }
    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-filter-submit {
        background: #4338ca;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        border-radius: 9px;
        padding: 8px 16px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-filter-submit:hover {
        background: #3730a3;
        color: #fff;
    }
    .btn-filter-clear {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        border-radius: 9px;
        padding: 8px 14px;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: background .15s, color .15s;
    }
    .btn-filter-clear:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* ─── Tabela ─── */
    .tb-wrap {
        background: #fff;
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(16,24,40,.04);
        overflow: hidden;
    }
    .tb-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .tb-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 12px 14px;
        border-bottom: 1px solid #e9ecf3;
        white-space: nowrap;
    }
    .tb-custom tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background .1s;
    }
    .tb-custom tbody tr:hover {
        background: #f8fafc;
    }
    .tb-custom tbody td {
        padding: 11px 14px;
        color: #334155;
        vertical-align: middle;
    }
    .badge-origem-api {
        background: #ecfdf5;
        color: #047857;
        font-weight: 600;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-origem-painel {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 600;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #bfdbfe;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .chave-code {
        font-family: monospace;
        font-size: 11px;
        color: #475569;
        background: #f8fafc;
        padding: 2px 6px;
        border-radius: 5px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }
    .actions-cell {
        display: flex;
        gap: 5px;
        align-items: center;
        justify-content: flex-end;
    }
    .btn-act {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border: 1px solid transparent;
        transition: all .15s;
        text-decoration: none;
    }
    .btn-act-xml {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .btn-act-xml:hover {
        background: #2563eb;
        color: #fff;
    }
    .btn-act-damdfe {
        background: #f8fafc;
        color: #334155;
        border-color: #cbd5e1;
    }
    .btn-act-damdfe:hover {
        background: #334155;
        color: #fff;
    }
    .btn-zip-download {
        background: #0f172a;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        border-radius: 9px;
        padding: 9px 18px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-zip-download:hover {
        background: #1e293b;
        color: #fff;
    }
    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 44px;
        display: block;
        margin-bottom: 10px;
        color: #cbd5e1;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm modulo-form-card">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-folders-line"></i> Manifestos de Documentos Fiscais (MDF-e)
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Consulta, visualização e exportação de MDF-e emitidos pela empresa.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(isset($contXml) && $contXml > 0)
                        <a class="dash-btn dash-btn-zip" href="{{ route('contador-empresa-mdfe-zip', ['start_date='.request()->start_date, 'end_date='.request()->end_date]) }}">
                            <i class="ri-file-zip-line"></i> Baixar ZIP ({{ $contXml }} XMLs)
                        </a>
                        @endif
                        <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar ao Painel
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Cards de Resumo --}}
                <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon stat-icon-indigo">
                    <i class="ri-file-list-3-line"></i>
                </div>
                <div>
                    <div class="stat-label">Total nesta consulta</div>
                    <div class="stat-value">{{ $data->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="ri-file-code-line"></i>
                </div>
                <div>
                    <div class="stat-label">Arquivos XML Disponíveis</div>
                    <div class="stat-value">{{ $contXml }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtro de Período --}}
    <div class="filter-wrap">
        {!! Form::open()->fill(request()->all())->get() !!}
        <div class="filter-grid">
            <div class="filter-field">
                <label><i class="ri-calendar-line me-1"></i>Data Inicial</label>
                <input type="date" name="start_date" class="form-control" value="{{ request()->start_date }}">
            </div>
            <div class="filter-field">
                <label><i class="ri-calendar-line me-1"></i>Data Final</label>
                <input type="date" name="end_date" class="form-control" value="{{ request()->end_date }}">
            </div>
            <div class="filter-actions">
                <button class="btn-filter-submit" type="submit">
                    <i class="ri-search-line"></i>
                    <span>Filtrar</span>
                </button>
                <a class="btn-filter-clear" href="{{ route('contador-empresa.mdfe') }}">
                    <i class="ri-eraser-line"></i>
                    <span>Limpar</span>
                </a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    {{-- Tabela de MDF-e --}}
    <div class="tb-wrap mb-3">
        <div class="table-responsive">
            <table class="tb-custom">
                <thead>
                    <tr>
                        <th>Início Viagem</th>
                        <th>Criação</th>
                        <th>CNPJ Contratante</th>
                        <th>Estado Fiscal</th>
                        <th>Chave MDF-e</th>
                        <th>Número</th>
                        <th>Veículo Tração</th>
                        <th>Qtd Carga</th>
                        <th>Valor Carga</th>
                        <th>Canal</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>
                            <span class="fw-semibold text-dark">{{ __data_pt($item->data_inicio_viagem, 0) }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ __data_pt($item->created_at, 0) }}</small>
                        </td>
                        <td>
                            <span class="text-secondary fw-semibold">{{ $item->cnpj_contratante ?: '--' }}</span>
                        </td>
                        <td>
                            {!! $item->estadoEmissao($item->estado_emissao) !!}
                        </td>
                        <td>
                            @if($item->chave)
                                <span class="chave-code" title="{{ $item->chave }}">{{ $item->chave }}</span>
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">#{{ $item->mdfe_numero > 0 ? $item->mdfe_numero : '--' }}</span>
                        </td>
                        <td>
                            <span class="text-dark fw-semibold">{{ $item->veiculoTracao ? $item->veiculoTracao->marca . ' - ' . $item->veiculoTracao->placa : '--' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary">{{ $item->quantidade_carga }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">{{ __moeda($item->valor_carga) }}</strong>
                        </td>
                        <td>
                            @if($item->api)
                                <span class="badge-origem-api"><i class="ri-code-s-slash-line"></i> API</span>
                            @else
                                <span class="badge-origem-painel"><i class="ri-window-line"></i> Painel</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a class="btn-act btn-act-xml" title="Download XML" href="{{ route('contador-empresa-mdfe.download', [$item->id]) }}">
                                    <i class="ri-file-download-line"></i>
                                </a>
                                <a target="_blank" class="btn-act btn-act-damdfe" title="Imprimir DAMDFE" href="{{ route('contador-empresa-mdfe.damdfe', [$item->id]) }}">
                                    <i class="ri-printer-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="ri-inbox-line"></i>
                                <h6>Nenhum MDF-e encontrado</h6>
                                <p class="small text-muted mb-0">Tente ajustar o intervalo de datas no filtro acima.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação e Ações em Lote --}}
        <div class="p-3 border-top d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 bg-light bg-opacity-50">
            <div>
                {!! $data->appends(request()->all())->links() !!}
            </div>
            @if($contXml > 0)
            <div class="d-flex align-items-center gap-2">
                <a class="btn-zip-download" href="{{ route('contador-empresa-mdfe-zip', ['start_date='.request()->start_date, 'end_date='.request()->end_date]) }}">
                    <i class="ri-file-zip-line"></i>
                    <span>Baixar Todos XMLs (.ZIP)</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
