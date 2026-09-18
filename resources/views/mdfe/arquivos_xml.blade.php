@extends('layouts.app', ['title' => 'Arquivos XML — MDF-e'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    transition: transform .2s ease;
}
.stat-card:hover { transform: translateY(-2px); }
.stat-card .stat-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 42px;
    opacity: .22;
}
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }

.stat-card .stat-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    opacity: .85;
    margin-bottom: 4px;
}
.stat-card .stat-val {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
}

/* ─── Filtro Padronizado ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #e8ecf4;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    margin-bottom: 22px;
}
.modulo-glass-filter-premium label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 6px;
}
.modulo-glass-filter-premium .form-control {
    height: 40px;
    border-radius: 10px;
    border: 1px solid #dcdce9;
    font-size: 13.5px;
    color: #1f2937;
    background: #fcfdfe;
}
.modulo-glass-filter-premium .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}

/* ─── Tabela ─── */
.tb-wrap {
    border-radius: 14px;
    border: 1px solid #eef0f5;
    overflow: hidden;
    background: #fff;
}
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th {
    background: #f8f9fc;
    color: #5a5a7a;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8eaf6;
    white-space: nowrap;
}
.tb-wrap tbody td {
    padding: 13px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f8;
    font-size: 13.5px;
    color: #374151;
}
.tb-wrap tbody tr:hover { background: #f5f6fe; }

/* ─── Estado Vazio ─── */
.empty-state { padding: 52px 20px; text-align: center; }
.empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
.empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-zip-line"></i>
                                Arquivos XML — MDF-e
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Pesquise, filtre por período e baixe em lote os arquivos XML dos manifestos autorizados.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            @if(count($data) > 0)
                            <form method="get" action="{{ route('mdfe-xml.download') }}" class="d-inline">
                                <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                                <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                                <button class="dash-btn dash-btn-primary">
                                    <i class="ri-download-2-line"></i> Baixar Arquivos em ZIP ({{ count($data) }})
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS DE ESTATÍSTICAS (KPIs) ═══ -->
                    @if(request()->start_date || request()->end_date)
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-blue">
                                <i class="ri-file-list-3-line stat-icon"></i>
                                <div class="stat-title">Total MDF-e</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Com XML Armazenado</div>
                                <div class="stat-val">{{ $stats['com_xml'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-amber">
                                <i class="ri-close-circle-line stat-icon"></i>
                                <div class="stat-title">Sem Arquivo XML</div>
                                <div class="stat-val">{{ $stats['sem_xml'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card c-purple">
                                <i class="ri-money-dollar-circle-line stat-icon"></i>
                                <div class="stat-title">Valor Total Carga</div>
                                <div class="stat-val" style="font-size: 20px;">R$ {{ __moeda($stats['valor']) }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTROS PADRONIZADOS ═══ -->
                    <div class="modulo-glass-filter-premium">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-6">
                                <label for="start_date"><i class="ri-calendar-line me-1"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])->required()!!}
                            </div>
                            <div class="col-md-4 col-6">
                                <label for="end_date"><i class="ri-calendar-line me-1"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])->required()!!}
                            </div>
                            <div class="col-md-4 col-12 d-flex gap-2">
                                <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Filtrar XMLs
                                </button>
                                <a class="dash-btn dash-btn-light px-3" href="{{ route('mdfe-xml.index') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA PREMIUM ═══ -->
                    @if(count($data) > 0)
                    <div class="tb-wrap mb-3">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>CNPJ do Contratante</th>
                                        <th>Início da Viagem</th>
                                        <th>Nº MDF-e</th>
                                        <th>Chave de Acesso</th>
                                        <th>Valor da Carga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        @if(file_exists(public_path("xml_mdfe/").$item->chave.".xml"))
                                        <tr>
                                            <td class="fw-bold font-monospace">{{ $item->cnpj_contratante }}</td>
                                            <td class="text-muted fs-12">{{ __data_pt($item->data_inicio_viagem, 0) }}</td>
                                            <td><span class="fw-bold font-monospace">{{ $item->mdfe_numero }}</span></td>
                                            <td class="fs-12 text-secondary font-monospace">{{ $item->chave }}</td>
                                            <td class="fw-bold text-success">R$ {{ __moeda($item->valor_carga) }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="4" class="text-end">Total das Cargas no Período:</td>
                                        <td class="text-primary fs-15">R$ {{ __moeda($data->sum('valor_carga')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="ri-inbox-2-line"></i>
                        @if(request()->start_date || request()->end_date)
                            <p>Nenhum arquivo XML encontrado para o período informado.</p>
                        @else
                            <p>Selecione um intervalo de datas acima e clique em "Filtrar XMLs" para listar os arquivos.</p>
                        @endif
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
