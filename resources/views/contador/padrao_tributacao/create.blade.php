@extends('layouts.app', ['title' => 'Novo Padrão de Tributação'])

@section('content')
<div class="container-fluid">
    {{-- Header Moderno --}}
    <div class="modulo-header-gradient mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="modulo-header-icon">
                    <i class="ri-add-line"></i>
                </div>
                <div>
                    <h4 class="modulo-header-title">Cadastrar Novo Padrão de Tributação</h4>
                    <p class="modulo-header-subtitle">
                        Empresa: <strong class="text-white">{{ $empresa->nome }}</strong> ({{ $empresa->tributacao }})
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('contador-empresa.padrao-tributacao') }}" class="dash-btn dash-btn-light">
                    <i class="ri-arrow-left-line"></i>
                    <span>Voltar aos Padrões</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Card de Formulário --}}
    <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
        <div class="card-body p-4">
            {!! Form::open()->post()->route('contador-empresa.padrao-tributacao.store') !!}
                @include('contador.padrao_tributacao._forms')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function(){
        // Inicialização de máscaras se necessário
        if($.fn.mask){
            $('.percentual').mask('000,00', {reverse: true});
            $('.cfop').mask('0000');
            $('.cest').mask('00.000.00');
        }
    });
</script>
@endsection
