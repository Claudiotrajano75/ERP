@extends('layouts.app', ['title' => 'NFe da Empresa'])

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
        grid-template-columns: 1fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }
    @media (max-width: 992px) {
        .filter-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 576px) {
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
    .filter-field .form-control,
    .filter-field .form-select {
        border-radius: 9px;
        border: 1px solid #d1d5db;
        padding: 8px 12px;
        font-size: 13px;
        color: #111827;
        background-color: #fff;
        box-shadow: none;
    }
    .filter-field .form-control:focus,
    .filter-field .form-select:focus {
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
        transition: background .15s ease;
    }
    .btn-filter-submit:hover { background: #3730a3; color: #fff; }
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
        gap: 6px;
        text-decoration: none;
        transition: background .15s ease;
    }
    .btn-filter-clear:hover { background: #e2e8f0; color: #1e293b; }

    /* ─── Tabela ─── */
    .tb-wrap {
        border-radius: 14px;
        border: 1px solid #eef0f5;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 3px rgba(16,24,40,.04);
    }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 1px solid #eef0f5;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .tb-wrap tbody td {
        padding: 13px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .tb-wrap tbody tr:last-child td { border-bottom: 0; }
    .tb-wrap tbody tr:hover td { background-color: #fafbfd; }

    .client-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 13px;
        display: block;
    }
    .client-sub {
        font-size: 11.5px;
        color: #64748b;
    }

    /* ─── Badges (Pills) ─── */
    .pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .pill-green  { background: #ecfdf5; color: #047857; }
    .pill-red    { background: #fef2f2; color: #b91c1c; }
    .pill-amber  { background: #fffbeb; color: #b45309; }
    .pill-blue   { background: #eff6ff; color: #1d4ed8; }
    .pill-muted  { background: #f1f5f9; color: #475569; }

    /* ─── Ações em Linha ─── */
    .act-group {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: flex-end;
    }
    .act-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none;
    }
    .act-btn-xml { color: #4338ca; background: #eef2ff; }
    .act-btn-xml:hover { background: #e0e7ff; color: #3730a3; transform: translateY(-1px); }
    .act-btn-danfe { color: #0f172a; background: #f1f5f9; }
    .act-btn-danfe:hover { background: #e2e8f0; color: #000; transform: translateY(-1px); }

    /* ─── Botões do Header ─── */
    .dash-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        cursor: pointer;
        border: 0;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .dash-btn:hover { transform: translateY(-1px); }
    .dash-btn-light {
        background: #fff !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        box-shadow: 0 1px 2px rgba(16,24,40,.04) !important;
    }
    .dash-btn-light:hover {
        background: #f8fafc !important;
        color: #1e293b !important;
    }
    .dash-btn-zip {
        background: #0f172a !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(15,23,42,.25) !important;
    }
    .dash-btn-zip:hover {
        background: #1e293b !important;
        color: #fff !important;
    }

    /* ─── Footer de Paginação ─── */
    .pagination-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        background: #fff;
        border-top: 1px solid #eef0f5;
    }
    .pagination-info {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    /* ─── Empty State ─── */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
    }
    .empty-state-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 12px;
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
                            <i class="ri-file-text-line"></i> Notas Fiscais Eletrônicas (NFe)
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Consulta, download de XMLs fiscais e DANFEs emitidas pela empresa.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(isset($contXml) && $contXml > 0 && request()->estado == 'aprovado')
                        <a class="dash-btn dash-btn-zip" href="{{ route('contador-empresa-nfe-zip', ['start_date='.request()->start_date, 'end_date='.request()->end_date, 'tpNF='.request()->tpNF]) }}">
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

                {{-- ═══ KPI CARDS (ESTATÍSTICAS) ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-indigo">
                                <i class="ri-file-list-3-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Total de Documentos</div>
                                <div class="stat-value">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-green">
                                <i class="ri-money-dollar-circle-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Soma da Página</div>
                                <div class="stat-value">R$ {{ __moeda($data->sum('total')) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="ri-file-code-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">XMLs Disponíveis</div>
                                <div class="stat-value">{{ $contXml ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ FILTRO ═══ --}}
                <div class="filter-wrap">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label>Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        <div class="filter-field">
                            <label>Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        <div class="filter-field">
                            <label>Tipo de Operação</label>
                            {!!Form::select('tpNF', '', [
                                '' => 'Todos',
                                '1' => 'Saída',
                                '0' => 'Entrada',
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>

                        <div class="filter-field">
                            <label>Estado Fiscal</label>
                            {!!Form::select('estado', '', [
                                '' => 'Todos',
                                'aprovado' => 'Aprovadas',
                                'cancelado' => 'Canceladas',
                                'rejeitado' => 'Rejeitadas',
                                'novo' => 'Novas'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>

                        <div class="filter-actions">
                            <button class="btn-filter-submit" type="submit">
                                <i class="ri-search-line"></i> Filtrar
                            </button>
                            <a id="clear-filter" class="btn-filter-clear" href="{{ route('contador-empresa.nfe') }}">
                                <i class="ri-eraser-line"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ TABELA NFE ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Destinatário / Remetente</th>
                                    <th>Nº NFe</th>
                                    <th>Valor Total</th>
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    <th>Emissão</th>
                                    <th>Canal</th>
                                    <th>Operação</th>
                                    <th class="text-end" width="100">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        @if($item->cliente)
                                            <span class="client-title">{{ $item->cliente->razao_social }}</span>
                                            <span class="client-sub"><i class="ri-user-line me-1"></i>{{ $item->cliente->cpf_cnpj }}</span>
                                        @elseif($item->fornecedor)
                                            <span class="client-title">{{ $item->fornecedor->razao_social }}</span>
                                            <span class="client-sub"><i class="ri-truck-line me-1"></i>{{ $item->fornecedor->cpf_cnpj }}</span>
                                        @else
                                            <span class="text-muted fs-12">Consumidor Não Identificado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark fs-13">{{ $item->numero ?: '—' }}</strong>
                                        <div class="fs-11 text-muted">Série: {{ $item->serie ?: 1 }}</div>
                                    </td>
                                    <td>
                                        <strong class="text-success fs-13">R$ {{ __moeda($item->total) }}</strong>
                                    </td>
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="pill pill-green"><i class="ri-checkbox-circle-line"></i> Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="pill pill-red"><i class="ri-close-circle-line"></i> Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="pill pill-amber"><i class="ri-alert-line"></i> Rejeitado</span>
                                        @else
                                        <span class="pill pill-blue"><i class="ri-file-line"></i> Novo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="pill pill-muted">{{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ __data_pt($item->created_at) }}</span>
                                    </td>
                                    <td>
                                        @if($item->api)
                                        <span class="pill pill-blue">API</span>
                                        @else
                                        <span class="pill pill-muted">Painel</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tpNF)
                                        <span class="pill pill-green">Saída</span>
                                        @else
                                        <span class="pill pill-blue">Entrada</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($item->estado == 'aprovado')
                                        <div class="act-group">
                                            <a class="act-btn act-btn-xml" title="Download XML" href="{{ route('contador-empresa-nfe.download', [$item->id]) }}">
                                                <i class="ri-file-download-line"></i>
                                            </a>
                                            <a target="_blank" class="act-btn act-btn-danfe" title="Visualizar DANFE" href="{{ route('contador-empresa-nfe.danfe', [$item->id]) }}">
                                                <i class="ri-printer-line"></i>
                                            </a>
                                        </div>
                                        @else
                                        <span class="text-muted fs-11">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="ri-file-text-line"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">Nenhuma NFe encontrada</h6>
                                            <p class="text-muted fs-13 mb-0">Tente ajustar os filtros de período e estado para encontrar documentos.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- ═══ PAGINAÇÃO ═══ --}}
                    @if($data->total() > 0)
                    <div class="pagination-footer">
                        <div class="pagination-info">
                            Exibindo {{ $data->firstItem() ?? 0 }} até {{ $data->lastItem() ?? 0 }} de {{ $data->total() }} notas fiscais
                        </div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
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
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\nChave: " + chave;
            swal("", text, "warning");
        } else {
            let text = "Chave: " + chave + "\nRecibo: " + recibo;
            swal("", text, "success");
        }
    }
</script>
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>
@endsection
