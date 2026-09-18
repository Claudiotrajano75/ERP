@extends('layouts.app', ['title' => 'Inutilização ' . ($modelo == '55' ? 'NFe' : 'NFCe')])

@section('css')
<style>
/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    border-radius: 14px;
    padding: 18px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    min-height: 100px;
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
    font-size: 50px;
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
.stat-green  { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.stat-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
.stat-blue   { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }

/* ─── Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
    padding: 20px !important;
    margin-bottom: 22px;
}
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
    color: #4f46e5;
    margin-right: 6px;
}
.modulo-glass-filter-premium label {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 5px;
}
.modulo-glass-filter-premium .form-control {
    height: 40px !important;
    border-radius: 9px !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #334155 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}
.modulo-glass-filter-premium .form-control:focus {
    border-color: #4f46e5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}
.modulo-glass-filter-premium .btn-limpar {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e2e8f0 !important;
    color: #334155 !important;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de Ações Compacta ─── */
.act-group { display: flex; align-items: center; gap: 6px; }
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
.act-del { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
.act-del:hover { background: #dc2626; color: #fff; box-shadow: 0 3px 8px rgba(220,38,38,0.3); }
.act-transmit { background: #dcfce7; color: #16a34a; border-color: #bbf7d0; }
.act-transmit:hover { background: #16a34a; color: #fff; box-shadow: 0 3px 8px rgba(22,163,74,0.3); }

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 16px 36px rgba(0,0,0,0.12); }
.modal-header { background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; }
.modal-header .modal-title { color: #1e293b; font-weight: 700; font-size: 16px; }
.modal-header .modal-title i { color: #4f46e5; }
.modal-body { padding: 24px; background: #ffffff; }
.modal-body label { font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; color: #64748b; margin-bottom: 6px; }
.modal-body .form-control { border-radius: 9px; border: 1px solid #e2e8f0; font-size: 13.5px; height: 40px; }
.modal-body .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
.modal-footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 14px 24px; }
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
                            <i class="ri-forbid-2-line"></i>
                            Inutilização de {{ $modelo == '55' ? 'NFe' : 'NFCe' }}
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Inutilize faixas de numeração não utilizadas junto à SEFAZ de forma oficial.</p>
                    </div>
                    <div>
                        <button class="dash-btn dash-btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cad">
                            <i class="ri-add-circle-line"></i> Nova Inutilização
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA (KPIS) ═══ -->
                @if(isset($stats))
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-indigo">
                            <div>
                                <div class="stat-label">Total Inutilizações</div>
                                <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                            </div>
                            <i class="ri-file-list-3-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-green">
                            <div>
                                <div class="stat-label">Aprovadas SEFAZ</div>
                                <div class="stat-value mt-1">{{ $stats['aprovados'] }}</div>
                            </div>
                            <i class="ri-checkbox-circle-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-amber">
                            <div>
                                <div class="stat-label">Pendentes / Novas</div>
                                <div class="stat-value mt-1">{{ $stats['novos'] }}</div>
                            </div>
                            <i class="ri-time-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card stat-blue">
                            <div>
                                <div class="stat-label">Rejeitadas</div>
                                <div class="stat-value mt-1">{{ $stats['rejeitados'] }}</div>
                            </div>
                            <i class="ri-close-circle-line stat-icon"></i>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ═══ FILTRO DE PESQUISA ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-filter-3-line"></i> Filtrar Inutilizações de Numeração
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-4 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="btn btn-limpar px-3" href="{{ $modelo == '55' ? route('nfe.inutilizar') : route('nfce.inutilizar') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Nº Inicial</th>
                                    <th>Nº Final</th>
                                    <th>Série</th>
                                    <th>Modelo</th>
                                    <th>Estado</th>
                                    <th>Justificativa</th>
                                    <th>Data</th>
                                    <th class="text-end" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold" style="color: #1e293b;">#{{ $item->numero_inicial }}</td>
                                    <td class="fw-bold" style="color: #1e293b;">#{{ $item->numero_final }}</td>
                                    <td class="fw-semibold text-muted">{{ $item->numero_serie }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                            {{ $item->modelo == '55' ? 'NFe' : 'NFCe' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-13" style="max-width: 300px;">{{ $item->justificativa }}</td>
                                    <td class="fs-12 text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                    {{-- Ações no final da grid --}}
                                    <td class="text-end">
                                        <form action="{{ route('nfe-inutilizar.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                            <div class="act-group justify-content-end">
                                                <button title="Transmitir Inutilização ao SEFAZ" type="button" class="act-btn act-transmit" onclick="transmitir('{{$item->id}}')">
                                                    <i class="ri-send-plane-fill"></i>
                                                </button>
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                            @else
                                            <span class="badge bg-light text-muted border fs-11">Concluído</span>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-center text-muted">
                                            <i class="ri-forbid-2-line" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                                            <p class="mb-0 fs-14">Nenhuma inutilização encontrada no período.</p>
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

<!-- ═══ MODAL NOVA INUTILIZAÇÃO ═══ -->
<div class="modal fade" id="modal-cad" tabindex="-1" aria-labelledby="modalCadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="post" action="{{ $modelo == '55' ? route('nfe-inutilizar.store') : route('nfce-inutilizar.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalCadLabel">
                    <i class="ri-forbid-2-line"></i>
                    Nova Inutilização — {{ $modelo == '55' ? 'NFe (Modelo 55)' : 'NFCe (Modelo 65)' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning p-3 mb-4 d-flex align-items-start rounded-3">
                    <i class="ri-alert-line me-2 fs-18 flex-shrink-0"></i>
                    <span class="fs-13">Esta operação é <strong>irreversível</strong>. A faixa de numeração inutilizada será comunicada à SEFAZ e não poderá ser emitida como nota fiscal.</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        {!!Form::tel('numero_inicial', 'Número Inicial')->required()!!}
                    </div>
                    <div class="col-md-4 col-6">
                        {!!Form::tel('numero_final', 'Número Final')->required()!!}
                    </div>
                    <div class="col-md-4 col-6">
                        {!!Form::tel('numero_serie', 'Número da Série')->required()!!}
                    </div>
                    <div class="col-md-12">
                        {!!Form::text('justificativa', 'Justificativa (mínimo de 15 caracteres)')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="dash-btn dash-btn-primary">
                    <i class="ri-save-line"></i> Inutilizar Numeração
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function transmitir(id) {
        console.clear()
        $.post(path_url + "api/nfe_painel/inutilizar", {
            id: id,
        })
        .done((success) => {
            swal("Sucesso", success, "success")
            .then(() => { location.reload() })
        })
        .fail((err) => {
            console.log(err)
            swal("Algo deu errado", err.responseJSON, "error")
            .then(() => { location.reload() })
        })
    }
</script>
@endsection
