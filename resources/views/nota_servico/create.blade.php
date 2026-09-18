@extends('layouts.app', ['title' => 'Nova NFSe'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fs-11 text-uppercase fw-bold text-primary d-block mb-1"
                                  style="letter-spacing: 0.5px;">Emissão de Nota Fiscal de Serviço</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-paper-2-line"></i>
                                Nova NFS-e
                            </h4>
                            
                            @isset($reserva)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-2 px-3 py-2 fs-12 d-inline-flex align-items-center gap-1">
                                <i class="ri-calendar-event-line"></i>
                                Serviços vinculados à reserva #{{ $reserva->numero_sequencial }}
                            </span>
                            @endif
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('nota-servico.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('nota-servico.store')
                    !!}
                    
                    @isset($reserva)
                    <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
                    @endif

                    <div>
                        @include('nota_servico._forms')
                    </div>
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/nfse.js"></script>
@isset($reserva)
<script type="text/javascript">
    $(function(){
        setTimeout(() => {
            $('.cliente_id').change()
        }, 200)
    })
</script>
@endif
@endsection

