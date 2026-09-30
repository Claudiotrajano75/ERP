@extends('layouts.app', ['title' => 'Escritórios Contábeis'])

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
        grid-template-columns: 2fr 1.5fr 2fr auto;
        gap: 12px;
        align-items: end;
    }
    @media (max-width: 992px) {
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

    /* ─── Badges e Elementos ─── */
    .pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .pill-muted { background: #f1f5f9; color: #475569; }
    .pill-blue  { background: #eff6ff; color: #1d4ed8; }

    .company-title {
        font-weight: 600;
        color: #0f172a;
        font-size: 13px;
        display: block;
    }
    .company-sub {
        font-size: 12px;
        color: #64748b;
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
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-building-4-line"></i>
                            Escritórios Contábeis
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Listando os cadastros de escritórios vinculados às empresas do sistema
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('contadores.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-user-star-line"></i> Gerenciar Contadores
                        </a>
                        <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ STATS ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-indigo">
                                <i class="ri-building-4-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Total de Escritórios</div>
                                <div class="stat-value">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="ri-map-pin-2-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Exibidos nesta Página</div>
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
                            <label>Razão Social / Nome</label>
                            {!!Form::text('razao_social', '')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Buscar por razão social'])
                            !!}
                        </div>

                        <div class="filter-field">
                            <label>CNPJ</label>
                            {!!Form::tel('cnpj', '')
                            ->attrs(['class' => 'form-control cnpj', 'placeholder' => '00.000.000/0000-00'])
                            !!}
                        </div>

                        <div class="filter-field">
                            <label>Cidade</label>
                            {!!Form::select('cidade_id', '')
                            ->attrs(['class' => 'form-select select2'])
                            ->options($cidade != null ? [$cidade->id => $cidade->info] : [])
                            !!}
                        </div>

                        <div class="filter-actions">
                            <button class="btn-filter-submit" type="submit">
                                <i class="ri-search-line"></i> Filtrar
                            </button>
                            <a id="clear-filter" class="btn-filter-clear" href="{{ route('escritorio-contabils') }}">
                                <i class="ri-eraser-line"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ TABELA ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Razão Social / Fantasia</th>
                                    <th>Documento</th>
                                    <th>Cidade / UF</th>
                                    <th>Telefone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="company-title">{{ $item->razao_social }}</span>
                                        @if($item->nome_fantasia)
                                        <span class="company-sub">{{ $item->nome_fantasia }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->cnpj)
                                        <span class="pill pill-muted"><i class="ri-building-line"></i> {{ $item->cnpj }}</span>
                                        @elseif($item->cpf)
                                        <span class="pill pill-muted"><i class="ri-user-line"></i> {{ $item->cpf }}</span>
                                        @else
                                        <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->cidade)
                                        <span class="pill pill-blue"><i class="ri-map-pin-line"></i> {{ $item->cidade->info }}</span>
                                        @else
                                        <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->telefone)
                                        <span><i class="ri-phone-line text-muted me-1"></i>{{ $item->telefone }}</span>
                                        @else
                                        <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="ri-building-4-line"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">Nenhum escritório contábil encontrado</h6>
                                            <p class="text-muted fs-13 mb-0">Tente ajustar os filtros de pesquisa para encontrar resultados.</p>
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
                            Exibindo {{ $data->firstItem() ?? 0 }} até {{ $data->lastItem() ?? 0 }} de {{ $data->total() }} registros
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
