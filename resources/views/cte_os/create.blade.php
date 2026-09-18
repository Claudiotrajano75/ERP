@extends('layouts.app', ['title' => 'Nova CTe OS'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Emitir Nova CTe OS
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Preencha os dados dos participantes, percurso, veículo, tomador e valores da prestação de serviço.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('cte-os.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->post()->route('cte-os.store')->multipart()!!}

                    @include('cte_os._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/cte_os.js"></script>
@endsection
