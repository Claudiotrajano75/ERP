@extends('loja.default', ['title' => 'Política de Privacidade'])

@section('content')

@php
    $breadcrumbs = [
        ['label' => 'Política de Privacidade', 'url' => null]
    ];
@endphp

<x-loja.breadcrumbs :links="$breadcrumbs" :config="$config" />

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <div class="bg-white rounded-3xl border border-slate-100 p-8 sm:p-12 shadow-sm space-y-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1 block">Institucional</span>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Política de Privacidade & Segurança</h1>
            <p class="text-xs text-slate-400 mt-1">Última atualização em {{ date('d/m/Y') }}</p>
        </div>

        <div class="prose max-w-none text-slate-700 leading-relaxed text-sm pt-4 border-t border-slate-100">
            @if(!empty($config->politica_privacidade))
                {!! $config->politica_privacidade !!}
            @else
                <p>Nossa loja tem o compromisso com a privacidade e a segurança dos dados de seus clientes durante todo o processo de navegação e compra no site.</p>
                <p>Os dados cadastrais dos clientes não são vendidos, trocados ou divulgados para terceiros, exceto quando essas informações são necessárias para o processo de entrega ou para cobrança.</p>
            @endif
        </div>
    </div>
</div>

@endsection
