@extends('layouts.app', ['title' => 'Produtos da Empresa'])

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
        grid-template-columns: 2fr 1.5fr auto;
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

    .prod-img {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .prod-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 13px;
        display: block;
    }
    .prod-sub {
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
    .pill-indigo { background: #eef2ff; color: #4338ca; }
    .pill-muted  { background: #f1f5f9; color: #475569; }

    /* ─── Ações em Linha ─── */
    .act-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none;
    }
    .act-btn-view { color: #4338ca; background: #eef2ff; }
    .act-btn-view:hover { background: #e0e7ff; color: #3730a3; transform: translateY(-1px); }

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
                            <i class="ri-box-3-line"></i> Produtos da Empresa
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Consulta dos produtos cadastrados, tributação fiscal (NCM, CFOP), estoque e preços.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar ao Painel
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS (ESTATÍSTICAS) ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-indigo">
                                <i class="ri-box-3-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Total de Produtos</div>
                                <div class="stat-value">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="ri-file-list-3-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Nesta Página</div>
                                <div class="stat-value">{{ $data->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ FILTRO ═══ --}}
                <div class="filter-wrap">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label>Pesquisar por Nome do Produto</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do produto...'])!!}
                        </div>

                        <div class="filter-field">
                            <label>Código de Barras (EAN)</label>
                            {!!Form::tel('codigo_barras', '')->attrs(['class' => 'form-control', 'placeholder' => '789...'])!!}
                        </div>

                        <div class="filter-actions">
                            <button class="btn-filter-submit" type="submit">
                                <i class="ri-search-line"></i> Filtrar
                            </button>
                            <a id="clear-filter" class="btn-filter-clear" href="{{ route('contador-empresa.produtos') }}">
                                <i class="ri-eraser-line"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ TABELA DE PRODUTOS ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="48"></th>
                                    <th>Produto / Cód. Barras</th>
                                    <th>NCM / Unidade</th>
                                    <th>CFOP (Est / Inter)</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    <th>Preço Venda</th>
                                    <th>Preço Compra</th>
                                    <th class="text-end" width="80">Visualizar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <img class="prod-img" src="{{ $item->img }}" alt="{{ $item->nome }}">
                                    </td>
                                    <td>
                                        <span class="prod-name">{{ $item->nome }}</span>
                                        <span class="prod-sub"><i class="ri-barcode-line me-1"></i>{{ $item->codigo_barras ?: 'Sem código' }}</span>
                                    </td>
                                    <td>
                                        <div class="fs-12 fw-semibold text-dark">{{ $item->ncm ?: '—' }}</div>
                                        <div class="fs-11 text-muted">Unidade: {{ $item->unidade ?: 'UN' }}</div>
                                    </td>
                                    <td>
                                        <span class="pill pill-muted">{{ $item->cfop_estadual }}/{{ $item->cfop_outro_estado }}</span>
                                    </td>
                                    <td>
                                        <div class="fs-13 fw-bold text-dark">{{ $item->estoqueAtual() }}</div>
                                        @if($item->gerenciar_estoque)
                                        <span class="fs-11 text-muted">Gerenciado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="pill pill-green"><i class="ri-checkbox-circle-line"></i> Ativo</span>
                                        @else
                                        <span class="pill pill-red"><i class="ri-close-circle-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success fs-13">{{ __moeda($item->valor_unitario) }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-13">{{ __moeda($item->valor_compra) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a class="act-btn act-btn-view" href="{{ route('contador-empresa-produtos.show', [$item->id]) }}" title="Visualizar Detalhes">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="ri-box-3-line"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">Nenhum produto encontrado</h6>
                                            <p class="text-muted fs-13 mb-0">Tente ajustar os filtros de busca para encontrar registros.</p>
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
                            Exibindo {{ $data->firstItem() ?? 0 }} até {{ $data->lastItem() ?? 0 }} de {{ $data->total() }} produtos
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

@include('modals._info_vencimento', ['not_submit' => true])

@endsection

@section('js')
<script type="text/javascript">
    function infoVencimento(id) {
        $.get(path_url + 'api/produtos/info-vencimento/' + id)
        .done((res) => {
            $('.table-infoValidade tbody').html(res)
        })
        .fail((e) => {
            console.log(e)
        })
    }
</script>
@endsection
