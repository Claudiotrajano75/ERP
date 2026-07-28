@extends('layouts.app', ['title' => 'Lista de Caixas'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

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

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
.modal-header { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border: none; padding: 16px 20px; }
.modal-header .modal-title { color: #fff; font-weight: 700; font-size: 15px; letter-spacing: -0.2px; }
.modal-header .modal-title i { color: #a8b5ff; }
.modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: 0.8; }
.modal-body { padding: 24px 20px; background: #fafbfe; }
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

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-wallet-3-line"></i>
                            Lista de Caixas
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Histórico completo de caixas abertos e fechados.</p>
                    </div>
                    <div>
                        @if(__isAdmin())
                        <a href="{{ route('caixa.abertos-empresa') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-list-indefinite align-middle me-1"></i> Caixas Abertos
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Caixa (Operador)</th>
                                    <th>Data Abertura</th>
                                    <th>Data Fechamento</th>
                                    <th>Valor Abertura</th>
                                    <th>Valor Fechamento</th>
                                    <th class="text-end" style="width: 120px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->usuario ? $item->usuario->name : '--' }}</td>
                                    <td class="fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td class="fs-12 text-muted">{{ $item->data_fechamento ? __data_pt($item->data_fechamento) : '--' }}</td>
                                    <td class="fw-medium text-success">R$ {{ __moeda($item->valor_abertura) }}</td>
                                    <td class="fw-medium text-danger">R$ {{ $item->data_fechamento ? __moeda($item->valor_fechamento) : '--' }}</td>
                                    <td class="text-end">
                                        <div class="modulo-action-group">
                                            @if($item->status == 0)
                                            <button type="button" onclick="imprimir('{{$item->id}}')" class="btn btn-dark btn-sm" title="Imprimir Relatório">
                                                <i class="ri-printer-line"></i>
                                            </button>
                                            @endif
                                            <a class="btn btn-light btn-sm text-dark" href="{{ route('caixa.show' , $item) }}" title="Visualizar Detalhes">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum caixa encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Imprimir Relatório -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalPrintLabel">
                    <i class="ri-printer-line"></i> Imprimir Relatório
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-6">
                        <button type="button" class="btn btn-success w-100 py-2 btn-sm" onclick="print('a4')">
                            <i class="ri-file-text-line me-1"></i> Modelo A4
                        </button>
                    </div>
                    <div class="col-12 col-lg-6">
                        <button type="button" class="btn btn-primary w-100 py-2 btn-sm" onclick="print('80')">
                            <i class="ri-printer-line me-1"></i> Bobina 80mm
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var ID = 0
    function imprimir(id){
        ID = id
        $('#modal-print').modal('show')
    }

    function print(tipo){
        if(tipo == 'a4'){
            window.open('/caixa/imprimir/'+ID)
        }else{
            window.open('/caixa/imprimir80/'+ID)
        }
        $('#modal-print').modal('hide')
    }
</script>
@endsection
