@extends('layouts.app', ['title' => 'Reservas'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-calendar-check-fill"></i>
                                Reservas
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie as reservas de acomodações, check-ins e check-outs.
                            </p>
                        </div>
                        @can('reserva_create')
                        <a href="{{ route('reservas.create') }}" class="btn btn-success fw-semibold px-4 py-2">
                            <i class="ri-add-circle-fill me-1"></i> Nova Reserva
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            {!!Form::open()->fill(request()->all())
                            ->get()
                            !!}
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    {!!Form::select('cliente_id', 'Cliente')
                                    ->attrs(['class' => 'select2'])
                                    !!}
                                </div>
                                <div class="col-md-2">
                                    {!!Form::date('start_date', 'Data inicial')->attrs(['class' => 'form-control'])
                                    !!}
                                </div>
                                <div class="col-md-2">
                                    {!!Form::date('end_date', 'Data final')->attrs(['class' => 'form-control'])
                                    !!}
                                </div>
                                <div class="col-md-2">
                                    {!!Form::select('estado', 'Estado',
                                    ['pendente' => 'Pendente',
                                    'iniciado' => 'Iníciado',
                                    'finalizado' => 'Finalizado',
                                    'cancelado' => 'Cancelado',
                                    '' => 'Todos'])
                                    ->attrs(['class' => 'form-select'])
                                    !!}
                                </div>
                                <div class="col-md-auto">
                                    <button class="btn btn-primary" type="submit"> <i class="ri-search-line me-1"></i>Pesquisar</button>
                                    <a id="clear-filter" class="btn btn-danger" href="{{ route('reservas.index') }}"><i class="ri-eraser-fill me-1"></i>Limpar</a>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        @forelse($data as $item)
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-none" style="border-radius: 10px;">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2" style="border-radius: 10px 10px 0 0;">
                                    <strong class="text-success fs-5">#{{ $item->numero_sequencial }}</strong>
                                    <span class="badge bg-{{ $item->colorStatus() }} px-3 py-1 bg-opacity-10 border border-{{ $item->colorStatus() }} border-opacity-25 text-{{ $item->colorStatus() }}">{{ strtoupper($item->estado) }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="text-muted mb-0 fs-13"><i class="ri-calendar-login-line align-middle me-1"></i> Check-in</p>
                                        <h6 class="fw-bold mb-0">{{ __data_pt($item->data_checkin, 0) }}</h6>
                                    </div>
                                    <div class="mb-2">
                                        <p class="text-muted mb-0 fs-13"><i class="ri-calendar-check-line align-middle me-1"></i> Check-out</p>
                                        <h6 class="fw-bold mb-0">{{ __data_pt($item->data_checkout, 0) }}</h6>
                                    </div>
                                    <hr class="my-2 opacity-25">
                                    <div class="mb-2">
                                        <p class="text-muted mb-0 fs-13"><i class="ri-user-line align-middle me-1"></i> Cliente</p>
                                        <h6 class="fw-bold mb-0 text-truncate" title="{{ $item->cliente->info }}">{{ $item->cliente->info }}</h6>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 fs-13"><i class="ri-hotel-bed-line align-middle me-1"></i> Acomodação</p>
                                        <h6 class="fw-bold mb-0 text-truncate" title="{{ $item->acomodacao->info }}">{{ $item->acomodacao->info }}</h6>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0 pb-3" style="border-radius: 0 0 10px 10px;">
                                    <a href="{{ route('reservas.show', [$item->id]) }}" class="btn btn-dark w-100 shadow-sm fw-semibold">
                                        <i class="ri-eye-2-line align-middle me-1"></i> Visualizar Reserva
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="ri-calendar-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <h5 class="text-muted">Nenhuma reserva encontrada</h5>
                        </div>
                        @endforelse
                    </div>
                </div>
                @if($data->hasPages())
                <div class="card-footer bg-light p-3">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
