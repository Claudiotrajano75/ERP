@extends('layouts.app', ['title' => 'Agendamentos'])

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

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <input type="hidden" id="agendamentos" value="{{ json_encode($agendamentos) }}">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-calendar-todo-line"></i>
                            Calendário de Agendamentos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Acompanhe as escalas de atendimento, agende novos horários e gerencie a fila de prioridades.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ FILTROS GLASS ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8 col-12">
                            {!!Form::select('funcionario_id', 'Filtrar por Atendente / Profissional', ['' => 'Todos'] + $funcionarios->pluck('nome', 'id')->all())
                            ->id('funcionario')
                            ->attrs(['class' => 'select2 form-select form-select-sm'])!!}
                        </div>
                        <div class="col-md-4 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('agendamentos.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ CALENDÁRIO ═══ -->
                <div class="row">
                    <div class="col-12">
                        <div id="external-events"></div>
                        <div class="border rounded p-2 bg-white shadow-sm">
                            <div id="calendar" class="calendario" style="min-height: 550px;"></div>
                        </div>
                    </div>
                </div>

                <!-- ═══ LEGENDA ═══ -->
                <div class="mt-4 border-top pt-3">
                    <h5 class="fs-12 text-muted text-uppercase fw-semibold mb-2">Legendas de Prioridade & Status</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="px-3 py-1 rounded bg-success-subtle text-success border border-success-subtle fs-12">
                            <i class="ri-checkbox-circle-fill me-1 align-middle"></i> Agendamento Finalizado
                        </div>
                        <div class="px-3 py-1 rounded bg-primary-subtle text-primary border border-primary-subtle fs-12">
                            <i class="ri-information-line me-1 align-middle"></i> Prioridade Baixa
                        </div>
                        <div class="px-3 py-1 rounded bg-warning-subtle text-warning border border-warning-subtle fs-12">
                            <i class="ri-alert-line me-1 align-middle"></i> Prioridade Média
                        </div>
                        <div class="px-3 py-1 rounded bg-danger-subtle text-danger border border-danger-subtle fs-12">
                            <i class="ri-alarm-warning-line me-1 align-middle"></i> Prioridade Alta
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<input type="hidden" id="create_permission" value="@can('agendamento_create') 1 @else 0 @endcan">

@include('modals._agendamento')

@endsection

@section('js')
<script src="/assets/vendor/fullcalendar/main.min.js"></script>
<script src="/js/calendar.js"></script>
<script src="/js/agendamento.js"></script>
@endsection
