@extends('layouts.app', ['title' => 'Editar Taxa'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-box-line"></i>
                                Editar Taxa de Cartão
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Altere a porcentagem e demais dados de configuração da taxa de cartão.</p>
                        </div>
                        <div>
                            <a href="{{ route('taxa-cartao.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('taxa-cartao.update', [$item->id])
                    !!}
                    
                    @include('taxa_cartao._forms')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
