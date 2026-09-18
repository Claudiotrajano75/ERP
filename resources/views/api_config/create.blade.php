@extends('layouts.app', ['title' => 'Novo Token API'])

@section('css')
<style>
.permission-card { border: 1px solid #eef0f5; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); height: 100%; background: #ffffff; overflow: hidden; }
.permission-card .card-header { background: #f8fafc; border-bottom: 1px solid #edf2f7; padding: 12px 18px; }
.permission-card .card-header label { font-weight: 700; color: #1e293b; font-size: 13.5px; margin: 0; display: flex; align-items: center; }
.permission-card .card-header label i { margin-right: 8px; color: #4f46e5; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Gerar Novo Token de API
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gere uma chave Bearer de autenticação e defina quais endpoints e ações este token poderá executar.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('config-api.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->post()->route('config-api.store')->id('form')!!}

                    @include('api_config._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/config_api.js"></script>
@endsection
