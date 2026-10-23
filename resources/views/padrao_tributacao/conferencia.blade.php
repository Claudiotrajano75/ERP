@extends('layouts.app', ['title' => 'Conferência Fiscal de Produtos por Padrão'])

@section('css')
<style>
    /* ─── Cards de Estatísticas ─── */
    .stat-card {
        border: 0;
        border-radius: 16px;
        padding: 18px 20px;
        height: 100%;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after {
        content: '';
        position: absolute;
        top: -44px;
        right: -44px;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
    }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.28); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.28); }
    .stat-amber  { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow: 0 6px 18px rgba(217,119,6,.28); }
    .stat-red    { background: linear-gradient(135deg,#f43f5e,#e11d48); box-shadow: 0 6px 18px rgba(225,29,72,.28); }

    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.88); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.8); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; }

    /* ─── Grupo de Padrão (Card Acordeão) ─── */
    .padrao-group-card {
        border: 1px solid #eef0f6;
        border-radius: 14px;
        background: #ffffff;
        margin-bottom: 22px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
        transition: box-shadow .2s ease;
    }
    .padrao-group-card:hover {
        box-shadow: 0 4px 14px rgba(0,0,0,.04);
    }
    .padrao-group-header {
        background: #f8fafc;
        border-bottom: 1px solid #eef0f6;
        padding: 16px 20px;
        cursor: pointer;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        transition: background .15s ease;
    }
    .padrao-group-header:hover {
        background: #f1f5f9;
    }
    .padrao-group-header.sem-padrao {
        background: #fff5f5;
        border-bottom-color: #fed7d7;
    }
    .padrao-group-header.sem-padrao:hover {
        background: #fed7d7;
    }
    .padrao-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .padrao-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .padrao-rule-chips {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 6px;
    }
    .rule-chip {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
    }
    .rule-chip strong { color: #1e293b; }

    /* ─── Tabela e Células ─── */
    .tb-wrap {
        border: 0;
        overflow-x: auto;
    }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th {
        background: #fcfdfe;
        color: #64748b;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 11px 12px;
        border-bottom: 1px solid #eef0f6;
        white-space: nowrap;
    }
    .tb-wrap tbody td {
        padding: 11px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12.5px;
        color: #334155;
    }
    .tb-wrap tbody tr:hover { background: #fafbff; }

    /* Destaques de Divergência nas Células */
    .diff-cell {
        background: #fef3c7 !important;
        color: #92400e !important;
        font-weight: 700;
        border-radius: 6px;
        padding: 2px 6px;
        display: inline-block;
    }
    .diff-cell i { font-size: 12px; margin-right: 2px; }

    /* Badges de Status Fiscal */
    .badge-fiscal {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: default;
    }
    .badge-conforme   { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-divergente { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; cursor: pointer; }
    .badge-sem-padrao { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    .act-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
        background: #eef0ff;
        color: #4f46e5;
    }
    .act-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79,70,229,.3);
        color: #4f46e5;
    }

    .btn-toggle-group {
        background: transparent;
        border: 0;
        color: #64748b;
        font-size: 18px;
        cursor: pointer;
        transition: transform .2s ease;
    }
    .btn-toggle-group.collapsed {
        transform: rotate(-90deg);
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-search-line"></i>
                            Conferência Fiscal de Produtos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Auditoria tributária dos produtos agrupados por Padrão de Tributação com detecção automática de divergências fiscais.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('produtopadrao-tributacao.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar para Padrões
                        </a>
                        <a href="{{ route('produtopadrao-tributacao.conferencia-pdf', request()->all()) }}" target="_blank" class="dash-btn dash-btn-light">
                            <i class="ri-file-pdf-line text-danger"></i> Exportar PDF
                        </a>
                        <a href="{{ route('produtopadrao-tributacao.conferencia-excel', request()->all()) }}" class="dash-btn dash-btn-light">
                            <i class="ri-file-excel-line text-success"></i> Exportar Excel
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICAS (KPIS) ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de Produtos</div>
                                    <div class="st-value">{{ $stats['total'] }}</div>
                                    <div class="st-sub">{{ $stats['com_padrao'] }} vinculados a padrões</div>
                                </div>
                                <div class="st-icon"><i class="ri-box-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Em Conformidade</div>
                                    <div class="st-value">{{ $stats['conformes'] }}</div>
                                    <div class="st-sub">100% alinhados com o padrão</div>
                                </div>
                                <div class="st-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-amber">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Com Divergência</div>
                                    <div class="st-value">{{ $stats['divergentes'] }}</div>
                                    <div class="st-sub">diferem das regras do padrão</div>
                                </div>
                                <div class="st-icon"><i class="ri-alert-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Sem Padrão Vinculado</div>
                                    <div class="st-value">{{ $stats['sem_padrao'] }}</div>
                                    <div class="st-sub">produtos sem configuração</div>
                                </div>
                                <div class="st-icon"><i class="ri-error-warning-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ FILTRO DE PESQUISA PREMIUM ═══ -->
                <div class="modulo-glass-filter-premium mb-4">
                    <div class="filtro-premium-header">
                        <span class="filtro-premium-title">
                            <i class="ri-filter-3-line"></i> Filtros de Auditoria e Pesquisa
                        </span>
                        @if(request()->anyFilled(['busca', 'padrao_id', 'categoria_id', 'status', 'filtro_divergencia']))
                        <span class="badge bg-primary-subtle text-primary fs-12 px-2 py-1">
                            Filtros ativos ({{ $totalExibidos }} produtos encontrados)
                        </span>
                        @endif
                    </div>

                    <form method="GET" action="{{ url()->current() }}" id="form-filtro">
                        <div class="row g-3 align-items-end">

                            {{-- Busca por texto --}}
                            <div class="col-12 col-md-3">
                                <label><i class="ri-search-line"></i> Buscar Produto</label>
                                <input type="text" name="busca" class="form-control" placeholder="Nome, código, cód. barras..." value="{{ request('busca') }}">
                            </div>

                            {{-- Padrão de Tributação --}}
                            <div class="col-12 col-md-3">
                                <label><i class="ri-scales-3-line"></i> Padrão de Tributação</label>
                                <select name="padrao_id" class="form-select select2">
                                    <option value="todos">Todos os padrões</option>
                                    <option value="sem_padrao" {{ request('padrao_id') === 'sem_padrao' ? 'selected' : '' }}>[Apenas Sem Padrão]</option>
                                    @foreach($padroesFiltro as $p)
                                    <option value="{{ $p->id }}" {{ request('padrao_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->descricao }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Categoria --}}
                            <div class="col-12 col-md-2">
                                <label><i class="ri-folder-line"></i> Categoria</label>
                                <select name="categoria_id" class="form-select select2">
                                    <option value="">Todas as categorias</option>
                                    @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nome }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filtro de Auditoria / Divergência --}}
                            <div class="col-12 col-md-2">
                                <label><i class="ri-shield-check-line"></i> Auditoria Fiscal</label>
                                <select name="filtro_divergencia" class="form-select">
                                    <option value="todos" {{ request('filtro_divergencia') == 'todos' ? 'selected' : '' }}>Todos os status</option>
                                    <option value="divergentes" {{ request('filtro_divergencia') == 'divergentes' ? 'selected' : '' }}>⚠️ Somente Divergentes</option>
                                    <option value="conformes" {{ request('filtro_divergencia') == 'conformes' ? 'selected' : '' }}>✅ Somente Conformes</option>
                                    <option value="sem_padrao" {{ request('filtro_divergencia') == 'sem_padrao' ? 'selected' : '' }}>⛔ Sem Padrão</option>
                                </select>
                            </div>

                            {{-- Situação (Ativo / Inativo) --}}
                            <div class="col-6 col-md-2">
                                <label><i class="ri-toggle-line"></i> Situação</label>
                                <select name="status" class="form-select">
                                    <option value="todos">Todas</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>

                            {{-- Botões de Ação --}}
                            <div class="col-12 d-flex justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ url()->current() }}" class="dash-btn dash-btn-light">
                                    <i class="ri-eraser-line"></i> Limpar Filtros
                                </a>
                                <button type="submit" class="dash-btn dash-btn-primary">
                                    <i class="ri-filter-line"></i> Aplicar Filtros
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- ═══ BARRA DE CONTROLE DOS GRUPOS ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div class="fs-13 text-muted">
                        Exibindo <strong>{{ $totalExibidos }}</strong> produtos agrupados em <strong>{{ count($gruposPadroes) }}</strong> grupos fiscais.
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="dash-btn dash-btn-light btn-sm" onclick="expandirTodos()">
                            <i class="ri-expand-up-down-line"></i> Expandir Todos
                        </button>
                        <button type="button" class="dash-btn dash-btn-light btn-sm" onclick="recolherTodos()">
                            <i class="ri-collapse-diagonal-line"></i> Recolher Todos
                        </button>
                    </div>
                </div>

                <!-- ═══ GRUPOS POR PADRÃO DE TRIBUTAÇÃO ═══ -->
                @forelse($gruposPadroes as $idx => $grupo)
                <div class="padrao-group-card" id="card-grupo-{{ $idx }}">

                    {{-- Cabeçalho do Grupo --}}
                    <div class="padrao-group-header {{ $grupo['is_sem_padrao'] ? 'sem-padrao' : '' }}" onclick="toggleGrupo({{ $idx }})">
                        <div>
                            <div class="padrao-title-wrap">
                                <h5 class="padrao-title">
                                    @if($grupo['is_sem_padrao'])
                                        <i class="ri-alert-fill text-danger me-1"></i>
                                        {{ $grupo['descricao'] }}
                                    @else
                                        <i class="ri-scales-3-line text-primary me-1"></i>
                                        {{ $grupo['descricao'] }}
                                    @endif
                                </h5>

                                <span class="badge bg-secondary-subtle text-dark fs-12 px-2 py-1">
                                    {{ $grupo['total_produtos'] }} {{ $grupo['total_produtos'] == 1 ? 'produto' : 'produtos' }}
                                </span>

                                @if(!$grupo['is_sem_padrao'])
                                    @if($grupo['total_divergentes'] > 0)
                                        <span class="badge bg-warning-subtle text-warning-emphasis fs-12 px-2 py-1">
                                            <i class="ri-alert-line"></i> {{ $grupo['total_divergentes'] }} divergentes
                                        </span>
                                    @endif
                                    @if($grupo['total_conformes'] > 0)
                                        <span class="badge bg-success-subtle text-success fs-12 px-2 py-1">
                                            <i class="ri-checkbox-circle-line"></i> {{ $grupo['total_conformes'] }} conformes
                                        </span>
                                    @endif
                                @endif
                            </div>

                            @if(!$grupo['is_sem_padrao'])
                            <div class="padrao-rule-chips">
                                <span class="rule-chip">NCM: <strong>{{ $grupo['padrao']->ncm ?: '--' }}</strong></span>
                                @if($grupo['padrao']->cest) <span class="rule-chip">CEST: <strong>{{ $grupo['padrao']->cest }}</strong></span> @endif
                                <span class="rule-chip">CFOP: <strong>{{ $grupo['padrao']->cfop_estadual }} / {{ $grupo['padrao']->cfop_outro_estado }}</strong></span>
                                <span class="rule-chip">CST: <strong>{{ $grupo['padrao']->cst_csosn }}</strong></span>
                                <span class="rule-chip">PIS/COF: <strong>{{ $grupo['padrao']->cst_pis }} / {{ $grupo['padrao']->cst_cofins }}</strong></span>
                                @if($grupo['padrao']->cst_ipi) <span class="rule-chip">IPI: <strong>{{ $grupo['padrao']->cst_ipi }} (cEnq {{ $grupo['padrao']->cEnq ?: '999' }})</strong></span> @endif
                                <span class="rule-chip">ICMS: <strong>{{ number_format((float)$grupo['padrao']->perc_icms, 1) }}%</strong></span>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            @if(!$grupo['is_sem_padrao'])
                            <a href="{{ route('produtopadrao-tributacao.edit', $grupo['padrao_id']) }}" class="act-btn" title="Editar Padrão de Tributação" onclick="event.stopPropagation();" target="_blank">
                                <i class="ri-edit-line"></i>
                            </a>
                            @endif
                            <button type="button" class="btn-toggle-group" id="btn-toggle-{{ $idx }}">
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Corpo / Tabela de Produtos --}}
                    <div class="padrao-group-body" id="body-grupo-{{ $idx }}">
                        <div class="tb-wrap">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">Cód.</th>
                                        <th>Produto</th>
                                        <th>Unid.</th>
                                        <th>NCM</th>
                                        <th>CEST</th>
                                        <th>CFOP (Est/Int)</th>
                                        <th>CST / CSOSN</th>
                                        <th>PIS / COFINS</th>
                                        <th>CST IPI</th>
                                        <th>Alíquotas</th>
                                        <th class="text-center">Conformidade</th>
                                        <th class="text-end" style="width: 70px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($grupo['produtos'] as $prod)
                                        @php
                                            $isDiv   = $prod->analise_status === 'divergente';
                                            $isSem   = $prod->analise_status === 'sem_padrao';
                                            $campos  = $prod->analise_campos ?? [];
                                            $tooltip = !empty($prod->analise_divergencias) ? implode('&#10;', $prod->analise_divergencias) : '';
                                        @endphp
                                        <tr>
                                            {{-- Código / Ref --}}
                                            <td>
                                                <span class="fw-bold text-dark fs-12">#{{ $prod->produto_id }}</span>
                                                @if($prod->referencia)
                                                    <span class="d-block text-muted fs-11">Ref: {{ $prod->referencia }}</span>
                                                @endif
                                            </td>

                                            {{-- Produto --}}
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $prod->produto_nome }}</span>
                                                <div class="d-flex align-items-center gap-2 mt-1 fs-11 text-muted">
                                                    @if($prod->categoria_nome)
                                                        <span><i class="ri-folder-line"></i> {{ $prod->categoria_nome }}</span>
                                                    @endif
                                                    @if($prod->marca_nome)
                                                        <span>• {{ $prod->marca_nome }}</span>
                                                    @endif
                                                    @if($prod->codigo_barras)
                                                        <span>• Cód. Barras: {{ $prod->codigo_barras }}</span>
                                                    @endif
                                                    @if(!$prod->produto_status)
                                                        <span class="badge bg-danger-subtle text-danger px-1 py-0 fs-10">Inativo</span>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Unidade --}}
                                            <td>
                                                <span class="badge bg-light text-secondary border">{{ $prod->unidade ?: 'UN' }}</span>
                                            </td>

                                            {{-- NCM --}}
                                            <td>
                                                @if(isset($campos['ncm']))
                                                    <span class="diff-cell" data-bs-toggle="tooltip" title="Padrão: {{ $grupo['padrao']->ncm ?? '--' }}">
                                                        <i class="ri-alert-line"></i> {{ $prod->prod_ncm ?: '--' }}
                                                    </span>
                                                @else
                                                    <span>{{ $prod->prod_ncm ?: '--' }}</span>
                                                @endif
                                            </td>

                                            {{-- CEST --}}
                                            <td>
                                                @if(isset($campos['cest']))
                                                    <span class="diff-cell" data-bs-toggle="tooltip" title="Padrão: {{ $grupo['padrao']->cest ?? '--' }}">
                                                        <i class="ri-alert-line"></i> {{ $prod->prod_cest ?: '--' }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ $prod->prod_cest ?: '--' }}</span>
                                                @endif
                                            </td>

                                            {{-- CFOP Estadual / Interestadual --}}
                                            <td>
                                                @php $cfopDiff = isset($campos['cfop_estadual']) || isset($campos['cfop_outro_estado']); @endphp
                                                @if($cfopDiff)
                                                    <span class="diff-cell" data-bs-toggle="tooltip" title="Padrão: {{ $grupo['padrao']->cfop_estadual ?? '--' }} / {{ $grupo['padrao']->cfop_outro_estado ?? '--' }}">
                                                        <i class="ri-alert-line"></i> {{ $prod->prod_cfop_estadual }} / {{ $prod->prod_cfop_outro_estado }}
                                                    </span>
                                                @else
                                                    <span>{{ $prod->prod_cfop_estadual }} / {{ $prod->prod_cfop_outro_estado }}</span>
                                                @endif
                                            </td>

                                            {{-- CST / CSOSN --}}
                                            <td>
                                                @if(isset($campos['cst_csosn']))
                                                    <span class="diff-cell" data-bs-toggle="tooltip" title="Padrão: {{ $grupo['padrao']->cst_csosn ?? '--' }}">
                                                        <i class="ri-alert-line"></i> {{ $prod->prod_cst_csosn }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-indigo-subtle text-indigo">{{ $prod->prod_cst_csosn }}</span>
                                                @endif
                                            </td>

                                            {{-- CST PIS / COFINS --}}
                                            <td>
                                                @php $pisCofDiff = isset($campos['cst_pis']) || isset($campos['cst_cofins']); @endphp
                                                @if($pisCofDiff)
                                                    <span class="diff-cell" data-bs-toggle="tooltip" title="Padrão: {{ $grupo['padrao']->cst_pis ?? '--' }} / {{ $grupo['padrao']->cst_cofins ?? '--' }}">
                                                        <i class="ri-alert-line"></i> {{ $prod->prod_cst_pis }} / {{ $prod->prod_cst_cofins }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ $prod->prod_cst_pis }} / {{ $prod->prod_cst_cofins }}</span>
                                                @endif
                                            </td>

                                            {{-- CST IPI --}}
                                            <td>
                                                @if(isset($campos['cst_ipi']))
                                                    <span class="diff-cell" data-bs-toggle="tooltip" title="Padrão: {{ $grupo['padrao']->cst_ipi ?? '--' }}">
                                                        <i class="ri-alert-line"></i> {{ $prod->prod_cst_ipi ?: '--' }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ $prod->prod_cst_ipi ?: '--' }}</span>
                                                @endif
                                            </td>

                                            {{-- Alíquotas --}}
                                            <td>
                                                <div class="fs-11 text-muted">
                                                    <span class="{{ isset($campos['perc_icms']) ? 'text-warning fw-bold' : '' }}">ICMS: {{ number_format((float)$prod->prod_perc_icms, 1) }}%</span> |
                                                    <span class="{{ isset($campos['perc_pis']) ? 'text-warning fw-bold' : '' }}">PIS: {{ number_format((float)$prod->prod_perc_pis, 1) }}%</span> |
                                                    <span class="{{ isset($campos['perc_cofins']) ? 'text-warning fw-bold' : '' }}">COF: {{ number_format((float)$prod->prod_perc_cofins, 1) }}%</span>
                                                </div>
                                            </td>

                                            {{-- Conformidade --}}
                                            <td class="text-center">
                                                @if($isDiv)
                                                    <span class="badge-fiscal badge-divergente" data-bs-toggle="tooltip" data-bs-html="true" title="{!! nl2br(e($tooltip)) !!}">
                                                        <i class="ri-alert-line"></i> Divergente ({{ $prod->total_divergencias }})
                                                    </span>
                                                @elseif($isSem)
                                                    <span class="badge-fiscal badge-sem-padrao">
                                                        <i class="ri-error-warning-line"></i> Sem Padrão
                                                    </span>
                                                @else
                                                    <span class="badge-fiscal badge-conforme">
                                                        <i class="ri-checkbox-circle-line"></i> Conforme
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Ações --}}
                                            <td class="text-end">
                                                <a href="{{ route('produtos.edit', $prod->produto_id) }}" class="act-btn" title="Editar dados do produto" target="_blank">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4 text-muted fs-13">
                                                Nenhum produto encontrado neste padrão com os filtros aplicados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                @empty
                <div class="text-center py-5">
                    <i class="ri-inbox-2-line fs-40 text-muted d-block mb-2"></i>
                    <h5 class="text-muted">Nenhum produto encontrado</h5>
                    <p class="text-muted fs-13">Tente alterar ou limpar os filtros de pesquisa para visualizar os produtos.</p>
                    <a href="{{ url()->current() }}" class="dash-btn dash-btn-light mt-2">
                        <i class="ri-refresh-line"></i> Limpar Filtros
                    </a>
                </div>
                @endforelse

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Inicialização de tooltips do Bootstrap
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Toggle individual de cada grupo
    function toggleGrupo(idx) {
        var body = $('#body-grupo-' + idx);
        var btn = $('#btn-toggle-' + idx);
        body.slideToggle(180, function() {
            if (body.is(':visible')) {
                btn.removeClass('collapsed');
            } else {
                btn.addClass('collapsed');
            }
        });
    }

    // Expandir todos os grupos
    function expandirTodos() {
        $('.padrao-group-body').slideDown(180);
        $('.btn-toggle-group').removeClass('collapsed');
    }

    // Recolher todos os grupos
    function recolherTodos() {
        $('.padrao-group-body').slideUp(180);
        $('.btn-toggle-group').addClass('collapsed');
    }
</script>
@endsection
