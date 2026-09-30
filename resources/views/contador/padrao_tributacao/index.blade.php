@extends('layouts.app', ['title' => 'Padrões de Tributação da Empresa'])

@section('css')
<style>
    /* ─── Cards de Estatísticas ─── */
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
    .stat-icon-purple { background: #faf5ff; color: #7e22ce; }
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
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: 1fr auto;
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
    .pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 700;
    }
    .pill-default {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .pill-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .actions-cell {
        display: flex;
        gap: 6px;
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
        cursor: pointer;
    }
    .btn-act-edit {
        background: #eef2ff;
        color: #4338ca;
        border-color: #c7d2fe;
    }
    .btn-act-edit:hover {
        background: #4338ca;
        color: #fff;
    }
    .btn-act-del {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .btn-act-del:hover {
        background: #dc2626;
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
                            <i class="ri-scales-3-line"></i> Padrões de Tributação
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Empresa: <strong class="text-primary fw-bold">{{ $empresa->nome }}</strong> 
                            ({{ $empresa->cpf_cnpj }}) — {{ $empresa->tributacao }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('contador-empresa.padrao-tributacao.alterar') }}" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line"></i>
                            <span>Aplicar em Lote nos Produtos</span>
                        </a>
                        <a href="{{ route('contador-empresa.padrao-tributacao.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i>
                            <span>Novo Padrão</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Cards de Estatísticas --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-indigo">
                                <i class="ri-file-settings-line"></i>
                            </div>
                            <div>
                                <div class="stat-label">Total de Padrões</div>
                                <div class="stat-value">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-green">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div>
                                <div class="stat-label">Padrão Principal Ativo</div>
                                <div class="stat-value">{{ $stats['padrao'] > 0 ? 'Definido' : 'Pendente' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-purple">
                                <i class="ri-building-line"></i>
                            </div>
                            <div>
                                <div class="stat-label">Regime Tributário</div>
                                <div class="stat-value" style="font-size: 16px;">{{ $empresa->tributacao ?: 'Não informado' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filtro de Busca --}}
                <div class="filter-wrap">
                    {!! Form::open()->fill(request()->all())->get() !!}
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label><i class="ri-search-line me-1"></i>Buscar por Descrição</label>
                            <input type="text" name="descricao" class="form-control" placeholder="Ex: Tributação Geral Simples, ST..." value="{{ request()->descricao }}">
                        </div>
                        <div class="filter-actions">
                            <button class="btn-filter-submit" type="submit">
                                <i class="ri-search-line"></i>
                                <span>Buscar</span>
                            </button>
                            <a class="btn-filter-clear" href="{{ route('contador-empresa.padrao-tributacao') }}">
                                <i class="ri-eraser-line"></i>
                                <span>Limpar</span>
                            </a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                {{-- Tabela de Padrões --}}
                <div class="tb-wrap mb-3">
                    {!! Form::open()->delete()->route('contador-empresa.padrao-tributacao.destroy-select')->id('form-delete-select') !!}
                    <div class="table-responsive">
                        <table class="tb-custom">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" class="form-check-input check-all" id="check-all">
                                    </th>
                                    <th>Descrição</th>
                                    <th>CST/CSOSN</th>
                                    <th>CFOP Estadual</th>
                                    <th>CFOP Interestadual</th>
                                    <th>% ICMS</th>
                                    <th>% PIS / COFINS</th>
                                    <th>% IPI</th>
                                    <th>Padrão</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input check-item" name="item_delete[]" value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $item->descricao }}</strong>
                                        @if($item->ncm)
                                            <br><small class="text-muted">NCM: {{ $item->ncm }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $item->cst_csosn }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $item->cfop_estadual }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $item->cfop_outro_estado }}</span>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold">{{ $item->perc_icms }}%</span>
                                    </td>
                                    <td>
                                        <small>{{ $item->perc_pis }}% / {{ $item->perc_cofins }}%</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->perc_ipi }}%</small>
                                    </td>
                                    <td>
                                        @if($item->padrao == 1)
                                            <span class="pill-badge pill-default"><i class="ri-check-line"></i> Principal</span>
                                        @else
                                            <span class="pill-badge pill-secondary">Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <a class="btn-act btn-act-edit" title="Editar Padrão" href="{{ route('contador-empresa.padrao-tributacao.edit', [$item->id]) }}">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="btn-act btn-act-del btn-delete" title="Excluir" data-form="form-del-{{ $item->id }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="empty-state">
                                            <i class="ri-scales-3-line"></i>
                                            <h6>Nenhum padrão de tributação cadastrado</h6>
                                            <p class="small text-muted mb-0">Cadastre um padrão tributário para agilizar o lançamento fiscal da empresa.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {!! Form::close() !!}

                    {{-- Forms individuais de exclusão --}}
                    @foreach($data as $item)
                        <form id="form-del-{{ $item->id }}" action="{{ route('contador-empresa.padrao-tributacao.destroy', [$item->id]) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach

                    {{-- Rodapé da Tabela --}}
                    <div class="p-3 border-top d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 bg-light bg-opacity-50">
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="btn-delete-selected" style="display: none;">
                                <i class="ri-delete-bin-line me-1"></i> Excluir Selecionados
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function(){
        // Check all
        $('#check-all').on('change', function(){
            $('.check-item').prop('checked', $(this).is(':checked'));
            toggleDeleteBtn();
        });

        $(document).on('change', '.check-item', function(){
            toggleDeleteBtn();
        });

        function toggleDeleteBtn(){
            var count = $('.check-item:checked').length;
            if(count > 0){
                $('#btn-delete-selected').show().text('Excluir ' + count + ' Selecionados');
            } else {
                $('#btn-delete-selected').hide();
            }
        }

        $('#btn-delete-selected').on('click', function(){
            if(confirm('Tem certeza que deseja excluir os padrões selecionados?')){
                $('#form-delete-select').submit();
            }
        });

        $('.btn-delete').on('click', function(){
            var formId = $(this).data('form');
            if(confirm('Deseja realmente excluir este padrão de tributação?')){
                $('#' + formId).submit();
            }
        });
    });
</script>
@endsection
