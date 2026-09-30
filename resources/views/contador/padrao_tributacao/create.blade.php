@extends('layouts.app', ['title' => 'Novo Padrão de Tributação'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm modulo-form-card">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-add-line"></i> Cadastrar Novo Padrão de Tributação
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Empresa: <strong class="text-primary fw-bold">{{ $empresa->nome }}</strong> ({{ $empresa->tributacao }})
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('contador-empresa.padrao-tributacao') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar aos Padrões
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!! Form::open()->post()->route('contador-empresa.padrao-tributacao.store') !!}
                    @include('contador.padrao_tributacao._forms')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function(){
        if($.fn.mask){
            $('.percentual').mask('000,00', {reverse: true});
            $('.cfop').mask('0000');
            $('.cest').mask('00.000.00');
        }
    });
</script>
@endsection
