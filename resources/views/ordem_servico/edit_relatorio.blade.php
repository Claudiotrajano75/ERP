@extends('layouts.app', ['title' => 'Editar Relatório'])
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
                                <i class="ri-file-edit-line me-2 text-warning fs-22"></i>
                                Editar Relatório de Atendimento (OS #{{ $ordem->codigo_sequencial }})
                            </h4>
                            <p class="text-muted mb-0 fs-13">Altere as informações registradas anteriormente no relatório técnico.</p>
                        </div>
                        <div>
                            <a href="{{ route('ordem-servico.show', $ordem->id) }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Corpo do Formulário -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('ordem-servico.update-relatorio', [$item->id])
                    !!}
                    
                    @include('ordem_servico._forms_relatorio')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
