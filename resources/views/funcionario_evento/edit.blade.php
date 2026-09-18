@extends('layouts.app', ['title' => 'Editar Associação de Eventos'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-pencil-line"></i>
                                Editar Eventos de Folha — <span style="color:#a8b5ff;">{{ $item->nome }}</span>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Altere a grade de proventos e descontos atribuídos a este colaborador.</p>
                        </div>
                        <div>
                            <a href="{{ route('funcionario-eventos.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('funcionario-eventos.update', [$item->id])
                    !!}
                    
                    @include('funcionario_evento._forms')
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection