@extends('layouts.app', ['title' => 'Orçamentos'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Botões de Ação do Grid ─── */
.modulo-action-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; flex-wrap: nowrap !important; }
.modulo-action-group .btn { padding: 5px 8px; font-size: 12px; border-radius: 6px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            Painel de Orçamentos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Crie e gerencie orçamentos de vendas. Converta em nota fiscal quando aprovado pelo cliente.</p>
                    </div>
                    <div>
                        @can('orcamento_create')
                        <a href="{{ route('nfe.create', ['orcamento=1']) }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Orçamento
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- FILTROS GLASS -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-4 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('orcamentos.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Nº</th>
                                    <th>Valor (R$)</th>
                                    <th>Data</th>
                                    <th class="text-end" style="width: 160px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->cliente ? $item->cliente->razao_social : '--' }}</span>
                                        <span class="text-muted fs-11">{{ $item->cliente ? $item->cliente->cpf_cnpj : '' }}</span>
                                    </td>
                                    <td class="fw-bold">{{ $item->numero ? $item->id : '--' }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                    <td class="fs-12">{{ __data_pt($item->created_at, 0) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('orcamentos.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                <a class="btn btn-primary btn-sm text-white" target="_blank" href="{{ route('orcamentos.imprimir', [$item->id]) }}" title="Imprimir Orçamento">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @can('orcamento_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('nfe.edit', $item->id) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('orcamento_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                                @can('nfe_create')
                                                <a title="Gerar Venda (NFe)" class="btn btn-dark btn-sm text-white" href="{{ route('orcamentos.show', $item->id) }}">
                                                    <i class="ri-file-text-line"></i>
                                                </a>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum orçamento encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação & Soma -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark">Total dos Orçamentos no Grid: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
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
