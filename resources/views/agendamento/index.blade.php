@extends('layouts.app', ['title' => 'Agendamentos'])

@section('css')
<style>
/* ─── Cards de Estatísticas ─── */
.stat-card { border-radius: 14px; padding: 18px 20px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.07); transition: transform .2s ease; }
.stat-card:hover { transform: translateY(-2px); }
.stat-card .stat-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 42px; opacity: .22; }
.stat-card.c-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card.c-teal   { background: linear-gradient(135deg, #06b6d4, #0e7490); }
.stat-card.c-amber  { background: linear-gradient(135deg, #f59e0b, #b45309); }
.stat-card.c-green  { background: linear-gradient(135deg, #10b981, #047857); }
.stat-card.c-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.stat-card .stat-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; opacity: .85; margin-bottom: 4px; }
.stat-card .stat-val   { font-size: 22px; font-weight: 800; line-height: 1; }

/* ─── Filtro Padronizado ─── */
.modulo-glass-filter-premium { background: #ffffff; border: 1px solid #e8ecf4; border-radius: 14px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); margin-bottom: 22px; }
.modulo-glass-filter-premium label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 6px; }
.modulo-glass-filter-premium .form-control, .modulo-glass-filter-premium .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; }
.modulo-glass-filter-premium .form-control:focus, .modulo-glass-filter-premium .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Container Calendário ─── */
.calendar-box { border-radius: 14px; border: 1px solid #eef0f5; background: #fff; padding: 18px; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02); }
.fc .fc-toolbar-title { font-size: 18px !important; font-weight: 700 !important; color: #1e293b !important; }
.fc .fc-button-primary { background-color: #4f46e5 !important; border-color: #4f46e5 !important; border-radius: 8px !important; font-weight: 600 !important; }
.fc .fc-button-primary:hover { background-color: #4338ca !important; border-color: #4338ca !important; }
.fc .fc-button-primary:disabled { background-color: #a5b4fc !important; border-color: #a5b4fc !important; }
.fc .fc-daygrid-day.fc-day-today { background-color: #f5f6fe !important; }

/* ─── Badges (Pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }
.pill-purple { background: #f3e8ff; color: #6b21a8; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <input type="hidden" id="agendamentos" value="{{ json_encode($agendamentos) }}">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-uppercase fw-bold text-primary d-block mb-1"
                                  style="letter-spacing: 0.5px;">Gestão de Atendimentos & Escalas</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-calendar-todo-line"></i>
                                Calendário de Agendamentos
                            </h4>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @if(request()->has('funcionario_id') && request()->funcionario_id)
                            <a class="dash-btn dash-btn-light" href="{{ route('agendamentos.index') }}">
                                <i class="ri-filter-off-line"></i> Ver Todos
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ CARDS KPI (ÍNDICE) ═══ -->
                    @if(isset($stats))
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-blue">
                                <i class="ri-calendar-check-line stat-icon"></i>
                                <div class="stat-title">Total Registrados</div>
                                <div class="stat-val">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-teal">
                                <i class="ri-time-line stat-icon"></i>
                                <div class="stat-title">Agendados Hoje</div>
                                <div class="stat-val">{{ $stats['hoje'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-amber">
                                <i class="ri-hourglass-fill stat-icon"></i>
                                <div class="stat-title">Pendentes</div>
                                <div class="stat-val">{{ $stats['pendentes'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card c-green">
                                <i class="ri-checkbox-circle-line stat-icon"></i>
                                <div class="stat-title">Finalizados</div>
                                <div class="stat-val">{{ $stats['finalizados'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ═══ FILTRO DE ATENDENTE ═══ -->
                    <div class="modulo-glass-filter-premium">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-7 col-12">
                                <label for="funcionario"><i class="ri-user-star-line me-1"></i> Filtrar por Atendente / Profissional</label>
                                {!!Form::select('funcionario_id', '', ['' => 'Todos os Atendentes'] + $funcionarios->pluck('nome', 'id')->all())
                                ->id('funcionario')
                                ->attrs(['class' => 'select2 form-select'])!!}
                            </div>
                            <div class="col-md-5 col-12 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Filtrar Agenda
                                    </button>
                                    <a class="dash-btn dash-btn-light px-3" href="{{ route('agendamentos.index') }}">
                                        <i class="ri-eraser-line"></i> Limpar
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
                            <div class="calendar-box">
                                <div id="calendar" class="calendario" style="min-height: 560px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ LEGENDA ═══ -->
                    <div class="mt-4 border-top pt-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="fs-12 text-muted text-uppercase fw-bold"><i class="ri-bookmark-line me-1"></i> Legenda de Cores & Prioridades:</span>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="pill pill-ok">
                                    <i class="ri-checkbox-circle-fill"></i> Finalizado
                                </span>
                                <span class="pill pill-info">
                                    <i class="ri-information-line"></i> Prioridade Baixa
                                </span>
                                <span class="pill pill-amber">
                                    <i class="ri-alert-line"></i> Prioridade Média
                                </span>
                                <span class="pill pill-no">
                                    <i class="ri-alarm-warning-line"></i> Prioridade Alta
                                </span>
                            </div>
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

