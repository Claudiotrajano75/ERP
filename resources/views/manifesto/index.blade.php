@extends('layouts.app', ['title' => 'Manifesto do Destinatário (DF-e)'])

@section('css')
<style>
/* ─── Botões de Ação Squircle ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end; }
.act-btn { 
    width: 34px; 
    height: 34px; 
    border-radius: 10px; 
    border: 1px solid transparent; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 15px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: all .2s ease; 
    padding: 0;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.act-edit:hover { background: #e0e7ff; color: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,.2); }
.act-add  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.act-add:hover  { background: #dcfce7; color: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.2); }
.act-del  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover  { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Cards de Estatística (KPIs) ─── */
.stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; bottom: 0;
    width: 4px;
}
.stat-indigo::before  { background: linear-gradient(180deg, #4f46e5, #818cf8); }
.stat-emerald::before { background: linear-gradient(180deg, #059669, #34d399); }
.stat-amber::before   { background: linear-gradient(180deg, #d97706, #fbbf24); }
.stat-cyan::before    { background: linear-gradient(180deg, #0891b2, #38bdf8); }

.stat-indigo .stat-icon  { background: #eef2ff; color: #4f46e5; }
.stat-emerald .stat-icon { background: #ecfdf5; color: #059669; }
.stat-amber .stat-icon   { background: #fffbeb; color: #d97706; }
.stat-cyan .stat-icon    { background: #ecfeff; color: #0891b2; }

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
}
.stat-value {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Avatar do Emitente ─── */
.emit-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

/* ─── Badges de Estado ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-primary { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
.modulo-badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.modulo-badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
.modulo-badge-neutral { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-article-line"></i>
                                Manifesto do Destinatário (DF-e)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Consulte notas fiscais emitidas contra o CNPJ da sua empresa na SEFAZ e registre os eventos de manifestação.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('compras.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-shopping-cart-line"></i> Compras / Entradas
                            </a>
                            <a href="{{ route('manifesto.novaConsulta') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-refresh-line"></i> Consultar SEFAZ em Tempo Real
                            </a>
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
                                    <div class="stat-label">Total de Documentos</div>
                                    <div class="stat-value mt-1">{{ $stats['total'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-file-list-3-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-emerald">
                                <div>
                                    <div class="stat-label">Operações Confirmadas</div>
                                    <div class="stat-value mt-1">{{ $stats['total_confirmadas'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-cyan">
                                <div>
                                    <div class="stat-label">Ciência da Emissão</div>
                                    <div class="stat-value mt-1">{{ $stats['total_ciencia'] }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-eye-line"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card stat-amber">
                                <div>
                                    <div class="stat-label">Valor Total das Notas</div>
                                    <div class="stat-value mt-1">R$ {{ __moeda($stats['valor_total']) }}</div>
                                </div>
                                <div class="stat-icon"><i class="ri-money-dollar-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- ═══ FILTRO DE BUSCA ═══ -->
                    <div class="modulo-glass-filter-premium mb-4">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Documentos Emitidos Contra o CNPJ
                            </h5>
                        </div>

                        <form method="get" action="{{ route('manifesto.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label class="form-label"><i class="ri-checkbox-circle-line"></i> Status / Estado SEFAZ</label>
                                    <select name="tipo" class="form-select">
                                        <option value="" @selected(request('tipo') == '')>Todos os Estados</option>
                                        <option value="1" @selected(request('tipo') == '1')>Ciência da Emissão</option>
                                        <option value="2" @selected(request('tipo') == '2')>Confirmação da Operação</option>
                                        <option value="3" @selected(request('tipo') == '3')>Desconhecimento da Operação</option>
                                        <option value="4" @selected(request('tipo') == '4')>Operação não Realizada</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-12 d-flex gap-2">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('manifesto.index') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Emitente da NFe</th>
                                        <th>CNPJ / CPF</th>
                                        <th>Valor da NFe</th>
                                        <th>Data de Emissão</th>
                                        <th>Protocolo</th>
                                        <th>Chave de Acesso</th>
                                        <th>Estado SEFAZ</th>
                                        <th class="text-end" style="width: 170px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="emit-avatar">
                                                    <i class="ri-building-line"></i>
                                                </div>
                                                <span class="fw-bold text-dark fs-13">{{ $item->nome }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-semibold fs-12">{{ $item->documento }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-13">R$ {{ __moeda($item->valor) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ __data_pt($item->data_emissao) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $item->num_prot ?? '--' }}</span>
                                        </td>
                                        <td>
                                            <code class="text-muted fs-11" title="{{ $item->chave }}">{{ substr($item->chave, 0, 16) }}...{{ substr($item->chave, -8) }}</code>
                                        </td>
                                        <td>
                                            @if($item->tipo == 2)
                                                <span class="modulo-badge modulo-badge-success">
                                                    <i class="ri-check-double-line"></i> {{ $item->estado() }}
                                                </span>
                                            @elseif($item->tipo == 1)
                                                <span class="modulo-badge modulo-badge-primary">
                                                    <i class="ri-eye-line"></i> {{ $item->estado() }}
                                                </span>
                                            @elseif($item->tipo == 3)
                                                <span class="modulo-badge modulo-badge-danger">
                                                    <i class="ri-close-circle-line"></i> {{ $item->estado() }}
                                                </span>
                                            @elseif($item->tipo == 4)
                                                <span class="modulo-badge modulo-badge-warning">
                                                    <i class="ri-error-warning-line"></i> {{ $item->estado() }}
                                                </span>
                                            @else
                                                <span class="modulo-badge modulo-badge-neutral">
                                                    <i class="ri-time-line"></i> {{ $item->estado() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="act-group">
                                                @if($item->tipo == 1 || $item->tipo == 2)
                                                    <a href="{{ route('manifesto.download', [$item->id]) }}" class="act-btn act-add" title="Importar XML da Nota">
                                                        <i class="ri-download-cloud-line"></i>
                                                    </a>
                                                    <a target="_blank" href="{{ route('manifesto.danfe', [$item->id]) }}" class="act-btn act-edit" title="Visualizar DANFE da NFe">
                                                        <i class="ri-printer-line"></i>
                                                    </a>
                                                @endif
                                                @if($item->tipo != 2)
                                                    <button class="dash-btn dash-btn-primary" style="font-size: 11px; padding: 4px 10px; height: 34px;" onclick="setChave('{{$item->chave}}')" title="Transmitir Manifesto à SEFAZ">
                                                        <i class="ri-send-plane-line"></i> Manifestar
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="modulo-empty">
                                                <i class="ri-article-line"></i>
                                                <p>Nenhum documento fiscal emitido contra a empresa localizado.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        {!! $data->appends(request()->all())->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Realizar Manifesto -->
<div class="modal fade" id="modal-evento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content text-dark border-0 shadow" method="post" action="{{ route('manifesto.manifestar') }}">
            @csrf
            <div class="modal-header modulo-header-gradient text-white py-3 px-4">
                <h5 class="modal-title modulo-title d-flex align-items-center gap-2 fs-15 text-white" id="modalEventoLabel">
                    <i class="ri-send-plane-line"></i>
                    Manifestação de Destinatário (DF-e)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="chave" id="chave">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label required fw-semibold">Tipo de Evento / Operação</label>
                        {!! Form::select('tipo', '', [
                            1 => "Ciência da Emissão",
                            2 => "Confirmação da Operação",
                            3 => "Desconhecimento da Operação",
                            4 => "Operação não Realizada"
                        ])->attrs(['class' => 'form-select', 'id' => 'inp-tipo'])->required() !!}
                        <div class="form-text text-muted fs-11 mt-1">Selecione o parecer fiscal da empresa em relação a este documento.</div>
                    </div>

                    <div class="col-12 just d-none">
                        <label class="form-label required fw-semibold">Justificativa do Evento</label>
                        {!! Form::text('justificativa', '')->placeholder('Mínimo 15 caracteres explicando o motivo...')->attrs(['class' => 'form-control', 'id' => 'inp-justificativa']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3 d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="dash-btn dash-btn-primary px-4">
                    <i class="ri-send-plane-fill me-1"></i> Transmitir Manifesto
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function setChave(chave) {
        $('#chave').val(chave);
        $('#modal-evento').modal('show');
    }

    $(document).on("change", "#inp-tipo", function() {
        if ($(this).val() > 2) {
            $('.just').removeClass('d-none');
            $('#inp-justificativa').attr('required', 'required');
        } else {
            $('.just').addClass('d-none');
            $('#inp-justificativa').removeAttr('required');
        }
    });
</script>
@endsection
