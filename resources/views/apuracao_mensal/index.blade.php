@extends('layouts.app', ['title' => 'Apuração Mensal'])

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
.modulo-table-wrap tbody tr.clickable { cursor: pointer; }

/* ─── Action Buttons — SEMPRE lado a lado (flex-wrap: nowrap é obrigatório) ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer da Tabela ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            
            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-calculator-line"></i>
                            Apuração Mensal de Salários
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Calcule a folha de pagamento de funcionários considerando salários base, proventos e descontos configurados.</p>
                    </div>
                    <div>
                        @can('apuracao_mensal_create')
                        <a href="{{ route('apuracao-mensal.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Apuração
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- FILTROS GLASS -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::select('funcionario_id', 'Pesquisar funcionário')
                            ->options($funcionario ? [$funcionario->id => $funcionario->nome] : [])
                            ->attrs(['class' => 'form-select select2'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-4 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('apuracao-mensal.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Colaborador / Funcionário</th>
                                    <th>Data de Registro</th>
                                    <th>Mês / Ano Ref.</th>
                                    <th>Valor Final Apurado</th>
                                    <th>Vínculo no Contas a Pagar</th>
                                    <th class="text-end" style="width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->funcionario->nome }}</td>
                                    <td>{{ __data_pt($item->created_at) }}</td>
                                    <td class="fw-bold text-muted">{{ $item->mes }}/{{ $item->ano }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->valor_final) }}</td>
                                    <td>
                                        @if($item->conta_pagar_id == 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-close-line me-1"></i>Pendente de Lançamento
                                        </span>
                                        @else
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                <i class="ri-check-line me-1"></i>Lançado
                                            </span>
                                            <a class="btn btn-xs btn-dark py-0" target="_blank" href="/conta-pagar/{{$item->conta_pagar_id}}/edit">
                                                ver conta
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('apuracao-mensal.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @if(!$item->conta_pagar_id)
                                                @can('conta_pagar_create')
                                                <a title="Gerar Conta a Pagar correspondente" class="btn btn-dark btn-sm text-white" href="{{ route('apuracao-mensal.conta-pagar', [$item->id]) }}">
                                                    <i class="ri-money-dollar-box-line"></i>
                                                </a>
                                                @endcan
                                                @endif
                                                <a class="btn btn-info btn-sm text-white" href="{{ route('apuracao-mensal.show', [$item->id]) }}" title="Visualizar / Imprimir Holerite">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @can('apuracao_mensal_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir Apuração">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma apuração mensal de salários encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modulo-footer">
                    <div></div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection