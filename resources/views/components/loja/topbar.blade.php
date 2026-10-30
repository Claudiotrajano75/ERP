{{-- Topbar promocional e institucional --}}
@props(['config'])

@php
    $freteGratis = ($config->frete_gratis_valor ?? 0) > 0;
@endphp

<div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <!-- Mensagem de destaque / Frete Grátis -->
        <div class="flex items-center gap-2 text-center sm:text-left">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs">
                <i class="ri-truck-line"></i>
            </span>
            @if($freteGratis)
                <span>Frete Grátis para todo o Brasil nas compras acima de <strong class="text-white font-semibold">R$ {{ __moeda($config->frete_gratis_valor) }}</strong></span>
            @else
                <span>Compre com segurança e receba no conforto da sua casa!</span>
            @endif
        </div>

        <!-- Links Rápidos de Atendimento e Conta -->
        <div class="flex items-center gap-4 text-xs">
            @if(!empty($config->link_whatsapp))
                <a href="{{ $config->link_whatsapp }}" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-400 transition-colors flex items-center gap-1">
                    <i class="ri-whatsapp-line text-emerald-400 text-sm"></i>
                    <span class="hidden md:inline">Atendimento</span> WhatsApp
                </a>
            @elseif(!empty($config->telefone))
                <a href="tel:{{ $config->telefone }}" class="hover:text-white transition-colors flex items-center gap-1">
                    <i class="ri-phone-line text-sm"></i>
                    <span>{{ $config->telefone }}</span>
                </a>
            @endif

            <span class="text-slate-700 hidden sm:inline">|</span>

            <a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}" class="hover:text-white transition-colors flex items-center gap-1">
                <i class="ri-user-line text-sm"></i>
                <span>Minha Conta</span>
            </a>

            <span class="text-slate-700 hidden sm:inline">|</span>

            <a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}#pedidos" class="hover:text-white transition-colors flex items-center gap-1">
                <i class="ri-file-list-3-line text-sm"></i>
                <span class="hidden sm:inline">Meus Pedidos</span>
            </a>
        </div>
    </div>
</div>
