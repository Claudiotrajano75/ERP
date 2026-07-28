@extends('layouts.app', ['title' => 'Lista de trocas'])

@section('content')
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

/* ─── Botões de Ação do Formulário / Grid ─── */
.modulo-action-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; flex-wrap: nowrap !important; }
.modulo-action-group .btn { padding: 5px 8px; font-size: 12px; border-radius: 6px; }

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
.modal-header { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border: none; padding: 16px 20px; }
.modal-header .modal-title { color: #fff; font-weight: 700; font-size: 15px; letter-spacing: -0.2px; }
.modal-header .modal-title i { color: #a8b5ff; }
.modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: 0.8; }
.modal-body { padding: 24px 20px; background: #fafbfe; }
.modal-body label { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 6px; }
.modal-body .form-control { border-radius: 8px; border: 1px solid #e0e3eb; font-size: 13px; padding: 10px 14px; background: #fff; transition: all 0.15s ease; }
.modal-body .form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }
.modal-divider-ou { display: flex; align-items: center; justify-content: center; margin: 16px 0; color: #8e94a6; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
.modal-divider-ou::before,
.modal-divider-ou::after { content: ""; flex: 1; border-bottom: 1px solid #e8eaee; margin: 0 10px; }
.modal-footer { background: #fff; border-top: 1px solid #f0f2f8; padding: 14px 20px; }
.modal-footer .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 18px; transition: all 0.2s ease; }
.modal-footer .btn-primary { background: #302b63; border-color: #302b63; }
.modal-footer .btn-primary:hover { background: #24204d; border-color: #24204d; transform: translateY(-1px); }
.modal-footer .btn-light { background: #f0f2f8; border-color: #f0f2f8; color: #5a5a7a; }
.modal-footer .btn-light:hover { background: #e4e7f0; border-color: #e4e7f0; color: #43435c; }
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
                            <i class="ri-arrow-go-back-fill"></i>
                            Trocas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as trocas de mercadorias realizadas nas vendas PDV.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        @can('troca_create')
                        <button class="btn btn-light btn-sm px-3 text-dark" data-bs-toggle="modal" data-bs-target="#modal-nova-troca">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Troca
                        </button>
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
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('trocas.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap mb-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Código</th>
                                    <th>Valor Troca (R$)</th>
                                    <th>Valor Venda (R$)</th>
                                    <th>Data Troca</th>
                                    <th>Venda #</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $item->numero_sequencial }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->nfce->cliente ? $item->nfce->cliente->razao_social : '--' }}</span>
                                        <span class="text-muted fs-11">{{ $item->nfce->cliente ? $item->nfce->cliente->cpf_cnpj : '--' }}</span>
                                    </td>
                                    <td class="fw-bold">{{ $item->codigo }}</td>
                                    <td class="fw-bold text-danger">R$ {{ __moeda($item->valor_troca) }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->valor_original) }}</td>
                                    <td class="fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td class="fw-bold">{{ $item->nfce ? $item->nfce->numero_sequencial : '' }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('trocas.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('troca_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                                <a class="btn btn-light btn-sm text-dark" title="Detalhes" href="{{ route('trocas.show', $item->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a target="_blank" class="btn btn-dark btn-sm text-white" title="Imprimir" href="{{ route('trocas.imprimir', $item->id) }}">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma troca encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Troca -->
<div class="modal fade" id="modal-nova-troca" tabindex="-1" aria-labelledby="modalNovaTrocaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="get" action="{{ route('trocas.create') }}" class="w-100">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="modalNovaTrocaLabel">
                        <i class="ri-arrow-go-back-line"></i> Nova Troca
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            {!!Form::text('codigo', 'Código da venda')!!}
                        </div>
                        <div class="col-md-12 text-center p-0">
                            <div class="modal-divider-ou">OU</div>
                        </div>
                        <div class="col-md-12">
                            {!!Form::text('numero_nfce', 'Número NFCe')!!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Procurar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
