@extends('layouts.app', ['title' => 'Nova Remessa'])

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
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Glass Filter ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Gerar Arquivo de Remessa
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Busque boletos gerados e selecione quais devem constar na nova remessa bancária.</p>
                        </div>
                        <div>
                            <a href="{{ route('remessa-boleto.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- Filtros de Busca de Boletos -->
                    <div class="modulo-glass-filter p-3 mb-4">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3 col-6">
                                {!!Form::date('start_date', 'Vencimento Inicial')!!}
                            </div>
                            <div class="col-md-3 col-6">
                                {!!Form::date('end_date', 'Vencimento Final')!!}
                            </div>
                            <div class="col-md-3 col-12">
                                {!!Form::select('conta_boleto_id', 'Conta de Boleto', ['' => 'Selecione'] + $contasBoleto->pluck('info', 'id')->all())
                                ->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                        <i class="ri-search-line me-1"></i> Filtrar Boletos
                                    </button>
                                    <a class="btn btn-outline-secondary btn-sm px-3" href="{{ route('remessa-boleto.create') }}" title="Limpar Filtro">
                                        <i class="ri-eraser-line me-1"></i> Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- Formulário de Criação -->
                    {!!Form::open()
                    ->post()
                    ->route('remessa-boleto.store')
                    !!}
                    
                    @include('remessa_boletos._forms')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
