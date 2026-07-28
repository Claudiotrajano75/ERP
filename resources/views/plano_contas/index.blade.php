@extends('layouts.app', ['title' => 'Plano de Contas'])

@section('css')
<style type="text/css">
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

/* ─── Tree Styling ─── */
.tree-row {
    transition: background-color 0.15s ease-in-out;
}
.tree-row:hover {
    background-color: #f8f9fe;
}
.tree-container { border: 1px solid #eef0f5; border-radius: 10px; overflow: hidden; }
.tree-header { background: #f8f9fc; border-bottom: 2px solid #e8eaf6; color: #5a5a7a; font-weight: 700; font-size: 13px; padding: 12px 16px; }

/* ─── Botões de Ação do Grid ─── */
.modulo-action-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
.modulo-action-group .btn { border-radius: 6px; padding: 4px 8px; font-size: 12px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
.modal-header { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border: none; padding: 16px 20px; }
.modal-header .modal-title { color: #fff; font-weight: 700; font-size: 15px; letter-spacing: -0.2px; }
.modal-header .modal-title i { color: #a8b5ff; }
.modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: 0.8; }
.modal-body { padding: 24px 20px; background: #fafbfe; }
.modal-body .form-label { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modal-body .form-control { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; }
.modal-body .form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }
.modal-footer { background: #fff; border-top: 1px solid #f0f2f8; padding: 14px 20px; }
.modal-footer .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 18px; transition: all 0.2s ease; }
.modal-footer .btn-light { background: #f0f2f8; border-color: #f0f2f8; color: #5a5a7a; }
.modal-footer .btn-light:hover { background: #e4e7f0; border-color: #e4e7f0; color: #43435c; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">
            
            <!-- ═══ Cabeçalho Premium ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-node-tree"></i>
                            Plano de Contas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie as categorias estruturadas de receitas, despesas e custos do seu ERP.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                @if(sizeof($data) > 0)
                <div class="tree-container shadow-none">
                    <div class="tree-header">Estrutura Hierárquica de Contas</div>
                    <div class="p-3 bg-white">
                        @foreach($data as $item)
                        <form action="{{ route('plano-contas.destroy', $item->id) }}" method="post" id="form-delete-{{$item->id}}" class="m-0">
                            @method('delete')
                            @csrf
                            
                            <div class="tree-row d-flex align-items-center justify-content-between py-2 border-bottom">
                                <!-- Lado Esquerdo: Descrição com Recuo e Ícone -->
                                @if($item->grauItem() == 1)
                                <div class="d-flex align-items-center fw-bold fs-14 text-dark">
                                    <i class="ri-folder-open-fill text-primary me-2 fs-18"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <!-- Lado Direito: Ações -->
                                <div class="modulo-action-group">
                                    <button type="button" class="btn btn-success btn-sm" onclick="modalForm('{{$item->id}}', '{{ $item->descricao }}')" title="Adicionar Subconta">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>

                                @elseif($item->grauItem() == 3)
                                <div class="d-flex align-items-center fw-semibold fs-13 text-dark ps-4">
                                    <i class="ri-folder-2-fill text-warning me-2 fs-18"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="modulo-action-group">
                                    <button type="button" class="btn btn-warning btn-sm text-white" onclick="modalEdit('{{$item->id}}', '{{$item->descricao}}')" title="Editar Conta">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" onclick="modalForm('{{$item->id}}', '{{ $item->descricao }}')" title="Adicionar Subconta">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>

                                @elseif($item->grauItem() == 5)
                                <div class="d-flex align-items-center fw-medium fs-13 text-secondary ps-5">
                                    <i class="ri-folder-3-fill text-info me-2 fs-18"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="modulo-action-group">
                                    <button type="button" class="btn btn-warning btn-sm text-white" onclick="modalEdit('{{$item->id}}', '{{$item->descricao}}')" title="Editar Conta">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" onclick="modalForm('{{$item->id}}', '{{ $item->descricao }}')" title="Adicionar Subconta">
                                        <i class="ri-add-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Conta">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>

                                @elseif($item->grauItem() == 8)
                                <div class="d-flex align-items-center fw-normal fs-13 text-muted ps-5 ms-3">
                                    <i class="ri-file-text-line text-muted me-2 fs-16"></i>
                                    <span>{{ $item->descricao }}</span>
                                </div>
                                
                                <div class="modulo-action-group">
                                    <button type="button" class="btn btn-warning btn-sm text-white" onclick="modalEdit('{{$item->id}}', '{{$item->descricao}}')" title="Editar Conta">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Conta">
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
                        <button class="btn btn-success px-4 btn-sm">
                            <i class="ri-rocket-line me-1 align-middle"></i>
                            Iniciar Plano de Contas Padrão
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
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Nova Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="row g-3">
                    <input type="hidden" id="plano_conta_id" name="plano_conta_id">
                    <input type="hidden" id="edit_id" name="edit_id">
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="descricao">Descrição / Nome da Conta</label>
                        <input required type="text" id="descricao" name="descricao" class="form-control" placeholder="Ex: Receitas de Vendas">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm px-3">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Conta
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
        $('#modalFormLabel').html('<i class="ri-add-circle-line me-1 text-success align-middle"></i> Adicionar Subconta em <span class="text-primary">' + parentDesc + '</span>');
        $('#plano_conta_id').val(id);
        $('#edit_id').val(null);
        $('#descricao').val('');
    }

    function modalEdit(id, descricao){
        $('#modal-form').modal('show');
        $('#modalFormLabel').html('<i class="ri-edit-box-line me-1 text-warning align-middle"></i> Editar Conta');
        $('#plano_conta_id').val(null);
        $('#edit_id').val(id);
        $('#descricao').val(descricao);
    }
</script>
@endsection
