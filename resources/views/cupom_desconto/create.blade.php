@extends('layouts.app', ['title' => 'Novo Cupom'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-fill"></i>
                                Novo Cupom
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Crie um novo cupom de desconto para seus clientes.
                            </p>
                        </div>
                        <a href="{{ route('cupom-desconto.index') }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-arrow-left-double-fill me-1"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    {!!Form::open()
                    ->post()
                    ->route('cupom-desconto.store')
                    !!}
                    <div class="pl-lg-4">
                        @include('cupom_desconto._forms')
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
