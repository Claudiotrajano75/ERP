@extends('layouts.app', ['title' => 'Ativar Contingência'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-alarm-warning-line"></i>
                                Ativar Modo de Contingência
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Preencha os dados e o motivo legal para ativar a emissão de notas em contingência na SEFAZ.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('contigencia.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->post()->route('contigencia.store')!!}

                    @include('contigencia._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
