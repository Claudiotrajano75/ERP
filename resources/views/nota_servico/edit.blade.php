@extends('layouts.app', ['title' => 'Editar NFSe'])

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
                                  style="letter-spacing: 0.5px;">Edição de Nota Fiscal de Serviço</span>
                            <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-line"></i>
                                Editar NFS-e #{{ $item->numero_nfse ?: $item->id }}
                            </h4>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('nota-servico.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('nota-servico.update', [$item->id])
                    !!}
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
@endsection

