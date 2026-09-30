@extends('layouts.app', ['title' => 'Editar Plano'])

@section('content')
<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-vip-diamond-line"></i>
                                Editar Plano
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize os valores, limites operacionais e permissões deste plano de assinatura.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('planos.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('planos.update', [$item->id])
                    ->multipart()
                    !!}

                    @include('planos._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
