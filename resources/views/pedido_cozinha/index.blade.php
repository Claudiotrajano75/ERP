@extends('layouts.app', ['title' => 'Controle de pedidos'])

@section('css')
<style type="text/css">
    /* ─── Header Gradiente ─── */
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

    /* ─── Form Card ─── */
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

    /* ─── Cozinha Card Premium ─── */
    .cozinha-card {
        background: #fff;
        border: 1px solid #eef0f5;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }
    .cozinha-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(48, 43, 99, 0.08);
    }
    .cozinha-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cozinha-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #1f2937;
        margin: 0;
    }
    .cozinha-card-body {
        padding: 18px;
        flex-grow: 1;
    }
    .cozinha-card-body h3 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 14px;
        color: #374151;
    }
    .cozinha-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #4b5563;
        font-size: 13px;
    }
    .cozinha-meta-item i {
        font-size: 16px;
        color: #9ca3af;
    }
    .cozinha-badge-canal {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .cozinha-card-footer {
        padding: 12px 18px;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .cozinha-card-footer .btn {
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ─── Alertas / Status ─── */
    .status-badge {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-badge.status-pendente { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .status-badge.status-preparando { background: #fef9c3; color: #ca8a04; border: 1px solid #fde047; }
    
    .preparo-info {
        background: #f0fdf4;
        border: 1px dashed #bbf7d0;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: 12px;
    }
    .preparo-atrasado {
        background: #fef2f2;
        border: 1px dashed #fecaca;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: 12px;
    }

    .modulo-empty { padding: 48px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-fire-line"></i>
                                Controle de Pedidos (Cozinha)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Monitore a preparação dos itens de comandas e delivery em tempo real.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('pedido-cozinha.update-all') }}" class="btn btn-danger btn-sm px-3 d-flex align-items-center gap-1 fw-semibold">
                                <i class="ri-checkbox-multiple-line fs-18"></i> Finalizar Todos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 bg-light">
                    <div class="row g-3 append">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Buscando itens...</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-item" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('pedidos-cardapio.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="/js/controle_pedidos.js"></script>
@endsection

