@extends('layouts.app', ['title' => 'Tributações Padrão'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 10px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 10px 10px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 12px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Action Buttons ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

/* ─── Badges ─── */
.badge { font-weight: 500; font-size: 11px; }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
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
                            <i class="ri-scales-3-line"></i>
                            Padrões de Tributação
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Configure templates tributários pré-definidos para agilizar a emissão fiscal de notas e o cadastro de produtos.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @can('config_produto_fiscal_create')
                        <a href="{{ route('produtopadrao-tributacao.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Padrão
                        </a>
                        @endcan
                        @can('config_produto_fiscal_edit')
                        <a href="{{ route('produtopadrao-tributacao.alterar') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-refresh-line align-middle me-1"></i> Alterar Tributação
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    @can('config_produto_fiscal_delete')
                                    <th style="width: 36px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Descrição</th>
                                    <th>Padrão</th>
                                    <th>NCM</th>
                                    <th>% ICMS</th>
                                    <th>% PIS</th>
                                    <th>% COFINS</th>
                                    <th>% IPI</th>
                                    <th>CST</th>
                                    <th>CST PIS</th>
                                    <th>CST COFINS</th>
                                    <th>CST IPI</th>
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('config_produto_fiscal_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <span class="fw-semibold text-dark d-block fs-13">{{ $item->descricao }}</span>
                                    </td>
                                    <td>
                                        @if($item->padrao)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="ri-check-line me-1"></i>Sim
                                        </span>
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Não</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-12">{{ $item->ncm ?? '--' }}</td>
                                    <td class="fw-semibold">{{ $item->perc_icms }}%</td>
                                    <td>{{ $item->perc_pis }}%</td>
                                    <td>{{ $item->perc_cofins }}%</td>
                                    <td>{{ $item->perc_ipi }}%</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->cst_csosn }}</span></td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->cst_pis }}</span></td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->cst_cofins }}</span></td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->cst_ipi }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('produtopadrao-tributacao.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('config_produto_fiscal_edit')
                                                <a class="btn btn-warning btn-sm text-white"
                                                   href="{{ route('produtopadrao-tributacao.edit', [$item->id]) }}" title="Editar Padrão">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('config_produto_fiscal_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Padrão">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('config_produto_fiscal_delete') ? 13 : 12) }}">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum padrão de tributação cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote + Paginação) ═══ -->
                <div class="modulo-footer">
                    <div>
                        @can('config_produto_fiscal_delete')
                        <form action="{{ route('produtopadrao-tributacao.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionados
                            </button>
                        </form>
                        @endcan
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
