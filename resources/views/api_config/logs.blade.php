@extends('layouts.app', ['title' => 'Logs da API'])

@section('css')
<style>
/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Badges de Status ─── */
.modulo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.modulo-badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
.modulo-badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

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
                                <i class="ri-survey-line"></i>
                                Histórico e Logs da API
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Monitore chamadas, requisições, status de resposta e ações efetuadas via integrações API.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('config-api.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Tokens de API
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- ═══ FILTRO DE BUSCA ═══ -->
                    <div class="modulo-glass-filter-premium mb-4">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Histórico de Requisições
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-checkbox-circle-line"></i> Status</label>
                                {!!Form::select('status', '', ['' => 'Todos os Status', 'sucesso' => 'Sucesso', 'erro' => 'Erro'])->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-pulse-line"></i> Ação / Tipo</label>
                                {!!Form::select('tipo', '', ['' => 'Todas as Ações'] + App\Models\ApiConfig::acoes())->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-2 col-12">
                                <label class="form-label"><i class="ri-folder-shield-2-line"></i> Módulo / Local</label>
                                {!!Form::select('prefixo', '', ['' => 'Todos os Módulos'] + App\Models\ApiConfig::permissoes())->attrs(['class' => 'form-select select2'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            
                            <div class="col-md-2 col-12 d-flex gap-2">
                                <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                                <a class="dash-btn dash-btn-light px-3" href="{{ route('config-api.logs') }}" title="Limpar Filtros">
                                    <i class="ri-eraser-line"></i>
                                </a>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- ═══ TABELA ═══ -->
                    <div class="tb-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Token Utilizado</th>
                                        <th>Data / Hora</th>
                                        <th>Status</th>
                                        <th>Tipo da Ação</th>
                                        <th>Descrição do Evento</th>
                                        <th>Módulo de Acesso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark fs-12" style="font-family: monospace;">{{ $item->token }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-13">{{ __data_pt($item->created_at) }}</span>
                                        </td>
                                        <td>
                                            @if($item->status == 'sucesso')
                                                <span class="modulo-badge modulo-badge-success">
                                                    <i class="ri-checkbox-circle-fill"></i> Sucesso
                                                </span>
                                            @else
                                                <span class="modulo-badge modulo-badge-danger">
                                                    <i class="ri-close-circle-fill"></i> Falha / Erro
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-semibold">
                                                {{ $item->getTipo() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $item->descricao }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 fs-11">
                                                {{ $item->getPrefixo() }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="modulo-empty">
                                                <i class="ri-survey-line"></i>
                                                <p>Nenhum registro de log encontrado para os filtros selecionados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ═══ FOOTER & PAGINAÇÃO ═══ -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                        <div>
                            <span class="text-muted fs-13">Exibindo <strong>{{ $data->count() }}</strong> registro(s)</span>
                        </div>
                        <div>
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
