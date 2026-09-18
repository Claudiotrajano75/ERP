@extends('layouts.app', ['title' => 'Editar Margem de Comissão'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-circle-line"></i>
                                Editar Faixa de Comissão por Margem
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Ajuste os percentuais ou a margem de lucro de referência para apuração de comissões.</p>
                        </div>
                        <div>
                            <a href="{{ route('comissao-margem.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('comissao-margem.update', [$item->id])
                    !!}
                    
                    @include('margem_comissao._forms')
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
