@extends('layouts.app', ['title' => 'Funcionários'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
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
.modulo-table-wrap tbody tr.clickable { cursor: pointer; }

/* ─── Action Buttons — SEMPRE lado a lado (flex-wrap: nowrap é obrigatório) ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer da Tabela ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-user-star-line"></i>
                            Quadro de Funcionários
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie o cadastro de colaboradores da empresa, vencimentos salariais, taxas de comissão e atribuição de serviços.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('funcionario_create')
                        <a href="{{ route('funcionarios.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Funcionário
                        </a>
                        <a href="{{ route('comissao.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-wallet-2-line align-middle me-1"></i> Comissões de Vendas
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
                            <i class="ri-search-line"></i> Filtrar Funcionários
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-8 col-12">
                            <label class="form-label"><i class="ri-user-star-line"></i> Pesquisar por Nome</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome do funcionário...'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('funcionarios.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
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
                                    <th>Nome do Colaborador</th>
                                    <th>Salário Base</th>
                                    <th>Percentual de Comissão (%)</th>
                                    <th>Data de Cadastro</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->nome }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->salario) }}</td>
                                    <td class="fw-bold text-primary">{{ __moeda($item->comissao) }}%</td>
                                    <td>{{ __data_pt($item->created_at, 1) }}</td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-line me-1"></i>Ativo
                                        </span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-close-line me-1"></i>Inativo
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('funcionarios.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('funcionario_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('funcionarios.edit', [$item->id]) }}" title="Editar Funcionário">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @can('funcionario_edit')
                                                <a href="{{ route('funcionarios.atribuir', [$item->id]) }}" class="btn btn-dark btn-sm text-white" title="Atribuir Serviços a Este Funcionário">
                                                    <i class="ri-user-settings-line"></i>
                                                </a>
                                                @endcan
                                                @can('funcionario_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Funcionário">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum funcionário cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modulo-footer">
                    <div></div>
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