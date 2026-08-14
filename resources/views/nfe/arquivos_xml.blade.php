@extends('layouts.app', ['title' => 'Arquivos XML NFe'])

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

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
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
                            <i class="ri-file-code-line"></i>
                            Arquivos XML das NFe
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Consulte, baixe em ZIP ou envie os arquivos XML das notas fiscais para o contador.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ Filtros de Busca Premium ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Arquivos XML por Período
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <!-- Data Inicial -->
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        <!-- Data Final -->
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>

                        <!-- Estado -->
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-equalizer-line"></i> Estado</label>
                            {!!Form::select('estado', '', [
                                'aprovado' => 'Aprovado',
                                'cancelado' => 'Cancelado'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>

                        @if(__countLocalAtivo() > 1)
                        <!-- Localização -->
                        <div class="col-md-3 col-6">
                            <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                            {!!Form::select('local_id', '')->options(['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif

                        <!-- Botões de Ação -->
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ route('nfe-xml.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                @if(sizeof($data) > 0)
                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap mb-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Nº Nota</th>
                                    <th>Chave de Acesso</th>
                                    <th>Valor (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                @if(file_exists(public_path("xml_nfe/").$item->chave.".xml") || file_exists(public_path("xml_nfe_cancelada/").$item->chave.".xml"))
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->cliente ? $item->cliente->info : '--' }}</td>
                                    <td class="fw-bold">{{ $item->numero }}</td>
                                    <td class="text-muted fs-12 font-monospace">{{ $item->chave }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="3" class="text-end text-dark">Total</td>
                                    <td class="text-success">R$ {{ __moeda($data->sum('total')) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Botões de Download/Envio -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <form method="get" action="{{ route('nfe-xml.download') }}">
                        <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                        <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                        <input type="hidden" name="estado" value="{{ request()->estado }}">
                        <input type="hidden" name="local_id" value="{{ request()->local_id }}">
                        <button class="btn btn-dark btn-sm px-3">
                            <i class="ri-file-zip-line me-1"></i> Baixar ZIP dos XMLs
                        </button>
                    </form>

                    @if($escritorio != null && $escritorio->email)
                    <form method="get" action="{{ route('nfe-xml.envio-contador') }}">
                        <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                        <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                        <input type="hidden" name="estado" value="{{ request()->estado }}">
                        <input type="hidden" name="local_id" value="{{ request()->local_id }}">
                        <button class="btn btn-success btn-sm px-3">
                            <i class="ri-mail-send-fill me-1 text-white"></i> Enviar XMLs ao Contador
                        </button>
                    </form>
                    @endif
                </div>

                @else
                <div class="alert alert-info border-info-subtle bg-info-subtle text-info p-3 d-flex align-items-center">
                    <i class="ri-information-line me-2 fs-18"></i>
                    <span>Filtre por período e estado para buscar os arquivos XML disponíveis.</span>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
