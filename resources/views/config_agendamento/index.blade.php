@extends('layouts.app', ['title' => 'Configurações de Agendamento'])
@section('content')

<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark">
                <!-- Cabeçalho -->
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-settings-4-line me-2 text-primary fs-22"></i>
                                Configuração de Agendamento
                            </h4>
                            <p class="text-muted mb-0 fs-13">Configure o tempo de descanso entre os atendimentos e regras de alertas via WhatsApp.</p>
                        </div>
                    </div>
                </div>
                <!-- Corpo do Formulário -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->post()
                    ->route('config-agendamento.store')
                    ->multipart()
                    !!}
                    
                    @include('config_agendamento._forms')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
