@extends('layouts.app', ['title' => 'Plano de Contas'])

@section('css')
<style type="text/css">
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    min-height: 105px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.stat-card .stat-icon {
    position: absolute;
    right: 14px;
    bottom: 8px;
    font-size: 52px;
    opacity: .18;
    line-height: 1;
    pointer-events: none;
}
.stat-card .stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    opacity: .88;
}
.stat-card .stat-value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.1;
}
.stat-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
.stat-blue   { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }

/* ─── Tree Container ─── */
.tree-container {
    border: 1px solid #eef0f5;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
}
.tree-header {
    background: #f8f9fc;
    border-bottom: 1px solid #e8eaf6;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 14px 20px;
}
.tree-row {
    transition: background-color 0.15s ease-in-out;
    padding: 10px 16px;
    border-bottom: 1px solid #f1f3f9;
}
.tree-row:hover {
    background-color: #f8faff;
}
.tree-row:last-child {
    border-bottom: none;
}

/* ─── Grade de Ações ─── */
.act-group { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
.act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 1px solid transparent;
    transition: all .15s ease;
    cursor: pointer;
    text-decoration: none !important;
}
.act-btn:hover { transform: translateY(-1px); }
.act-add   { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
.act-add:hover   { background: #059669; color: #fff; box-shadow: 0 3px 8px rgba(5,150,105,0.3); }
.act-edit  { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover  { background: #4f46e5; color: #fff; box-shadow: 0 3px 8px rgba(79,70,229,0.3); }
.act-del   { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
.act-del:hover   { background: #dc2626; color: #fff; box-shadow: 0 3px 8px rgba(220,38,38,0.3); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">
            
            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-node-tree"></i>
                            Plano de Contas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie as categorias e a estrutura contábil/financeira de receitas, despesas e custos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                @if(isset($stats) && $stats['total'] > 0)
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-12">
                        <div class="stat-card stat-indigo">
                            <div>
                                <div class="stat-label">Total de Contas e Grupos</div>
                                <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                            </div>
                            <i class="ri-node-tree stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-card stat-blue">
                            <div>
                                <div class="stat-label">Grupos Sintéticos (Nível 1)</div>
                                <div class="stat-value mt-1">{{ $stats['nivel_1'] }}</div>
                            </div>
                            <i class="ri-folder-open-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Subcontas Analíticas</div>
                                <div class="stat-value mt-1">{{ $stats['subcontas'] }}</div>
                            </div>
                            <i class="ri-folder-3-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(sizeof($data) > 0)
                <div class="tree-container shadow-none">
                    <div class="tree-header d-flex align-items-center justify-content-between">
                        <span><i class="ri-list-check-2 me-1"></i> Estrutura Hierárquica de Contas</span>
                        <span class="text-muted fs-11 fw-normal">Organizado por Nível Contábil</span>
                    </div>
                    <div class="p-2 bg-white">
                        @foreach($data as $item)
                        <form action="{{ route('plano-contas.destroy', $item->id) }}" method="post" id="form-delete-{{$item->id}}" class="m-0">
                            @method('delete')
                            @csrf
                            
                            <div class="tree-row d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <!-- Lado Esquerdo: Descrição com Recuo e Ícone -->
                                @if($item->grauItem() == 1)
                                <div class="d-flex align-items-center fw-bold fs-14 text-dark">
                                    <i class="ri-folder-open-fill text-primary me-2 fs-18"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="act-group">
                                    <button type="button" class="act-btn act-add" onclick="modalForm('{{$item->id}}', '{{ $item->descricao }}')" title="Adicionar Subconta">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>

                                @elseif($item->grauItem() == 3)
                                <div class="d-flex align-items-center fw-semibold fs-13 text-dark ps-4">
                                    <i class="ri-folder-2-fill text-warning me-2 fs-17"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="act-group">
                                    <button type="button" class="act-btn act-edit" onclick="modalEdit('{{$item->id}}', '{{$item->descricao}}')" title="Editar Conta">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="act-btn act-add" onclick="modalForm('{{$item->id}}', '{{ $item->descricao }}')" title="Adicionar Subconta">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>

                                @elseif($item->grauItem() == 5)
                                <div class="d-flex align-items-center fw-medium fs-13 text-secondary ps-5">
                                    <i class="ri-folder-3-fill text-info me-2 fs-17"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="act-group">
                                    <button type="button" class="act-btn act-edit" onclick="modalEdit('{{$item->id}}', '{{$item->descricao}}')" title="Editar Conta">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="act-btn act-add" onclick="modalForm('{{$item->id}}', '{{ $item->descricao }}')" title="Adicionar Subconta">
                                        <i class="ri-add-line"></i>
                                    </button>
                                    <button type="button" class="act-btn act-del btn-delete" title="Excluir Conta">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>

                                @elseif($item->grauItem() == 8)
                                <div class="d-flex align-items-center fw-normal fs-13 text-muted ps-5 ms-3">
                                    <i class="ri-file-text-line text-muted me-2 fs-15"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="act-group">
                                    <button type="button" class="act-btn act-edit" onclick="modalEdit('{{$item->id}}', '{{$item->descricao}}')" title="Editar Conta">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="act-btn act-del btn-delete" title="Excluir Conta">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </form>
                        @endforeach
                    </div>
                </div>
                @else
                <!-- Estado Vazio / Iniciar Plano de Contas -->
                <div class="modulo-empty py-5">
                    <i class="ri-node-tree text-muted"></i>
                    <h5 class="text-dark mt-2">Nenhum Plano de Contas cadastrado</h5>
                    <p class="text-muted mb-4 fs-13">Gere e inicie a estrutura padrão de contas para gerenciar os lançamentos financeiros do ERP.</p>
                    <form action="{{ route('plano-contas.start') }}" method="post">
                        @csrf
                        <button class="dash-btn dash-btn-primary">
                            <i class="ri-rocket-line"></i> Iniciar Plano de Contas Padrão
                        </button>
                    </form>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

<!-- Modal Unificado de Adicionar / Editar -->
<div class="modal fade" id="modal-form" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <form class="modal-content text-dark" method="post" action="{{ route('plano-contas.store') }}">
            @csrf
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalFormLabel">Nova Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <div class="row g-3">
                    <input type="hidden" id="plano_conta_id" name="plano_conta_id">
                    <input type="hidden" id="edit_id" name="edit_id">
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-12 text-uppercase text-muted" for="descricao">Descrição / Nome da Conta</label>
                        <input required type="text" id="descricao" name="descricao" class="form-control" placeholder="Ex: Receitas de Vendas">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-3 px-4">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="dash-btn dash-btn-primary">
                    <i class="ri-save-line"></i> Salvar Conta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function modalForm(id, parentDesc){
        $('#modal-form').modal('show');
        $('#modalFormLabel').html('<i class="ri-add-circle-line text-success align-middle"></i> Adicionar Subconta em <span class="text-primary ms-1">' + parentDesc + '</span>');
        $('#plano_conta_id').val(id);
        $('#edit_id').val(null);
        $('#descricao').val('');
    }

    function modalEdit(id, descricao){
        $('#modal-form').modal('show');
        $('#modalFormLabel').html('<i class="ri-edit-box-line text-warning align-middle"></i> Editar Conta');
        $('#plano_conta_id').val(null);
        $('#edit_id').val(id);
        $('#descricao').val(descricao);
    }
</script>
@endsection
