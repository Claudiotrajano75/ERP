@extends('layouts.app', ['title' => 'Cotações'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
/* --- Novo Filtro de Pesquisa Premium --- */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    padding: 20px !important;
    margin-bottom: 24px;
}

/* Título e Header do Filtro */
.filtro-premium-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f3f9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.filtro-premium-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.filtro-premium-title i {
    color: #5572f5;
    margin-right: 6px;
}

/* Customização dos Inputs dentro do Filtro */
.modulo-glass-filter-premium label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c8ca6 !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 4px;
}
.modulo-glass-filter-premium label i {
    font-size: 12px;
    color: #a8a8c0;
}

.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 38px !important;
    border-radius: 8px !important;
    border: 1px solid #dcdce9 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #374151 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}

.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #5572f5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
}

/* Botões do Filtro */
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
}

.modulo-glass-filter-premium .btn-limpar {
    background: #f1f3f9 !important;
    border: 1px solid #e2e5ec !important;
    color: #5a5a7a !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e8ebf3 !important;
    color: #302b63 !important;
}
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.2px; }
.modulo-badge-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.modulo-badge-warning { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }
.modulo-badge-danger { background: linear-gradient(135deg, #fbe9e7, #ffccbc); color: #c62828; }
.modulo-badge-info { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #1565c0; }
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-price-tag-3-line"></i>
                            Painel de Cotações de Compras
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Envie links de cotações para distribuidores parceiros, compare propostas e gere compras automáticas a partir das respostas.</p>
                    </div>
                    <div>
                        @can('cotacao_create')
                        <a href="{{ route('cotacoes.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Cotação
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Cotações
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label"><i class="ri-truck-line"></i> Fornecedor</label>
                            {!!Form::select('fornecedor_id', '', $fornecedor ? [$fornecedor->id => $fornecedor->info] : [])
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-hashtag"></i> Referência</label>
                            {!!Form::text('referencia', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                            {!!Form::select('estado', '', [
                                'novo' => 'Nova',
                                'rejeitada' => 'Rejeitada',
                                'respondida' => 'Respondida',
                                'aprovada' => 'Aprovada',
                                '' => 'Todas'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-1 col-6">
                            <label class="form-label"><i class="ri-shopping-cart-line"></i> Comprado</label>
                            {!!Form::select('gerado_compra', '', [
                                '0' => 'Não',
                                '1' => 'Sim',
                                '' => 'Todas'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('cotacoes.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Distribuidor / Fornecedor</th>
                                <th>CPF / CNPJ</th>
                                <th>Valor Total</th>
                                <th>Estado</th>
                                <th>Status</th>
                                <th>Gerado Compra</th>
                                <th>Criação</th>
                                <th>Data Resposta</th>
                                <th>Referência</th>
                                <th class="text-end" style="width: 150px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $item->fornecedor ? $item->fornecedor->razao_social : "--" }}</td>
                                <td class="fw-bold text-muted">{{ $item->fornecedor ? $item->fornecedor->cpf_cnpj : "--" }}</td>
                                <td class="fw-bold text-success">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    @if($item->estado == 'aprovada')
                                    <span class="modulo-badge modulo-badge-success"><i class="ri-check-line"></i> Aprovada</span>
                                    @elseif($item->estado == 'rejeitada')
                                    <span class="modulo-badge modulo-badge-danger"><i class="ri-close-line"></i> Rejeitada</span>
                                    @elseif($item->estado == 'respondida')
                                    <span class="modulo-badge modulo-badge-info"><i class="ri-chat-1-line"></i> Respondida</span>
                                    @else
                                    <span class="modulo-badge modulo-badge-warning"><i class="ri-time-line"></i> Nova</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status)
                                    <span class="modulo-badge modulo-badge-success"><i class="ri-check-line"></i> Ativa</span>
                                    @else
                                    <span class="modulo-badge modulo-badge-danger"><i class="ri-close-line"></i> Inativa</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->nfe_id)
                                    <span class="modulo-badge modulo-badge-success"><i class="ri-check-line"></i> Sim</span>
                                    @else
                                    <span class="modulo-badge modulo-badge-danger"><i class="ri-close-line"></i> Não</span>
                                    @endif
                                </td>
                                <td>{{ __data_pt($item->created_at) }}</td>
                                <td>{{ $item->data_resposta ? __data_pt($item->data_resposta) : '--' }}</td>
                                <td class="fw-bold">{{ $item->referencia }}</td>
                                <td class="text-end">
                                    <form action="{{ route('cotacoes.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <div class="d-inline-flex gap-1">
                                            @if($item->estado != 'aprovada')
                                            @can('cotacao_edit')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('cotacoes.edit', $item->id) }}" title="Editar Cotação">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @endcan
                                            @can('cotacao_delete')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Cotação">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            @endcan
                                            @endif
                                            
                                            <a title="Copiar Link de Resposta do Fornecedor" target="_blank" class="btn btn-dark btn-sm text-white" href="{{ route('cotacoes.resposta', $item->hash_link) }}">
                                                <i class="ri-external-link-line"></i>
                                            </a>

                                            @if($item->estado == 'respondida' || $item->estado == 'aprovada')
                                            <a title="Ver e Avaliar Resposta" class="btn btn-primary btn-sm text-white" href="{{ route('cotacoes.show', $item->id) }}">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Nenhuma cotação de compra gerada no período.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ═══ Footer ═══ -->
                <div class="modulo-footer">
                    <div>
                        <span class="modulo-total-label">Total Acumulado:</span>
                        <span class="modulo-total-value">R$ {{ __moeda($data->sum('valor_total')) }}</span>
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
