@extends('layouts.app', ['title' => 'Clientes da Empresa'])

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

    .client-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 13.5px;
        display: block;
    }
    .client-sub {
        font-size: 12px;
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
    .pill-blue   { background: #eff6ff; color: #1d4ed8; }
    .pill-muted  { background: #f1f5f9; color: #475569; }

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
                            <i class="ri-user-shared-line"></i> Clientes da Empresa
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Consulta dos clientes cadastrados para a empresa atualmente selecionada.
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
                                <i class="ri-team-fill"></i>
                            </div>
                            <div>
                                <div class="stat-label">Total de Clientes</div>
                                <div class="stat-value">{{ $data->total() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="ri-user-search-fill"></i>
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
                            <label>Razão Social / Nome do Cliente</label>
                            {!!Form::text('razao_social', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome ou razão...'])!!}
                        </div>

                        <div class="filter-field">
                            <label>CPF / CNPJ</label>
                            {!!Form::tel('cpf_cnpj', '')->attrs(['class' => 'form-control cpf_cnpj', 'placeholder' => '000.000.000-00'])!!}
                        </div>

                        <div class="filter-actions">
                            <button class="btn-filter-submit" type="submit">
                                <i class="ri-search-line"></i> Filtrar
                            </button>
                            <a id="clear-filter" class="btn-filter-clear" href="{{ route('contador-empresa.clientes') }}">
                                <i class="ri-eraser-line"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ TABELA DE CLIENTES ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Razão Social / Nome</th>
                                    <th>CPF / CNPJ</th>
                                    <th>Cidade / UF</th>
                                    <th>Endereço</th>
                                    <th>CEP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="client-title">{{ $item->razao_social }}</span>
                                        @if($item->nome_fantasia)
                                        <span class="client-sub">{{ $item->nome_fantasia }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="pill pill-muted"><i class="ri-file-text-line"></i> {{ $item->cpf_cnpj }}</span>
                                    </td>
                                    <td>
                                        @if($item->cidade)
                                        <span class="pill pill-blue"><i class="ri-map-pin-line"></i> {{ $item->cidade->info }}</span>
                                        @else
                                        <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark fs-13">{{ $item->rua ? $item->rua . ', ' . $item->numero : ($item->endereco ?: '—') }}</span>
                                        @if($item->bairro)
                                        <span class="d-block text-muted fs-11">{{ $item->bairro }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ $item->cep ?: '—' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="ri-team-line"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">Nenhum cliente encontrado</h6>
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
                            Exibindo {{ $data->firstItem() ?? 0 }} até {{ $data->lastItem() ?? 0 }} de {{ $data->total() }} clientes
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
