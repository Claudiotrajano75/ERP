@extends('layouts.app', ['title' => 'Arquivos XML NFe Entrada'])

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

/* ─── Footer / Actions ─── */
.modulo-actions-lote { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions-lote .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions-lote .btn:hover { transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-zip-line"></i>
                                Arquivos XML NFe Entrada (Compras)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Consulte e baixe em lote os arquivos XML de notas fiscais de entrada do período selecionado.</p>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    
                    <!-- FILTROS GLASS -->
                    <div class="modulo-glass-filter p-3 mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3 col-6">
                                {!!Form::date('start_date', 'Data Inicial')!!}
                            </div>
                            <div class="col-md-3 col-6">
                                {!!Form::date('end_date', 'Data Final')!!}
                            </div>
                            <div class="col-md-4 col-12 ms-auto">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                        <i class="ri-search-line me-1"></i> Pesquisar
                                    </button>
                                    <a class="btn btn-danger btn-sm px-3" href="{{ route('nfe-entrada-xml.index') }}">
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
                                        <th>Fornecedor / Emitente</th>
                                        <th>Número Nota</th>
                                        <th>Chave de Acesso</th>
                                        <th class="text-end">Valor Total (R$)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_geral = 0; @endphp
                                    @forelse($data as $item)
                                    @if(file_exists(public_path("xml_nfe/").$item->chave.".xml"))
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $item->cliente ? $item->cliente->info : '--' }}</td>
                                        <td class="fw-bold">{{ $item->numero }}</td>
                                        <td class="text-muted fs-12">{{ $item->chave }}</td>
                                        <td class="text-end fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                    </tr>
                                    @php $total_geral += $item->total; @endphp
                                    @endif
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="modulo-empty">
                                                <i class="ri-inbox-2-line"></i>
                                                <p>Filtre por período para buscar os arquivos XML das compras.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($total_geral > 0)
                                <tfoot>
                                    <tr class="fw-bold fs-14 table-light border-top">
                                        <td colspan="3">Total das Notas Localizadas</td>
                                        <td class="text-end text-success">R$ {{ __moeda($total_geral) }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- AÇÕES DE LOTE / RODAPÉ -->
                    @if(sizeof($data) > 0)
                    <div class="modulo-actions-lote">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <form method="get" action="{{ route('nfe-xml.download') }}" class="m-0">
                                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                                    <button class="btn btn-dark btn-sm px-4" style="border-radius: 8px;">
                                        <i class="ri-file-zip-line align-middle me-1"></i> Baixar ZIP com XMLs
                                    </button>
                                </form>
                            </div>
                            @if($escritorio != null && $escritorio->email)
                            <div>
                                <form method="get" action="{{ route('nfe-entrada-xml.envio-contador') }}" class="m-0">
                                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                                    <input type="hidden" name="estado" value="{{ request()->estado }}">
                                    <input type="hidden" name="local_id" value="{{ request()->local_id }}">
                                    <button class="btn btn-success btn-sm px-4" style="border-radius: 8px;">
                                        <i class="ri-mail-send-line align-middle me-1"></i> Enviar XMLs ao Contador
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning p-3 mb-0 text-center d-flex align-items-center justify-content-center">
                        <i class="ri-alert-line me-2 fs-18"></i>
                        <span>Por favor, filtre por um período válido para habilitar os botões de exportação/envio dos arquivos XML.</span>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
