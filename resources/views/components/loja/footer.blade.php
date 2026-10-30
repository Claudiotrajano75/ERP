{{-- Rodapé Institucional Completo --}}
@props(['config', 'categorias' => []])

@php
    $cats = collect($categorias);
@endphp

<footer class="bg-slate-900 text-slate-300 border-t border-slate-800 text-sm mt-16">
    <!-- Bloco de Diferenciais / Vantagens da Loja -->
    <div class="border-b border-slate-800 py-10 bg-slate-950/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center sm:text-left">
                <!-- Diferencial 1 -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="ri-truck-line"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm">Entrega Garantida</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Enviamos para todo o Brasil com rastreamento.</p>
                    </div>
                </div>

                <!-- Diferencial 2 -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm">Compra 100% Segura</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Seus dados e pagamentos protegidos por SSL.</p>
                    </div>
                </div>

                <!-- Diferencial 3 -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="ri-bank-card-line"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm">Parcele em até 10x</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Aceitamos os principais cartões e PIX imediato.</p>
                    </div>
                </div>

                <!-- Diferencial 4 -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-violet-500/10 text-violet-400 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="ri-customer-service-2-line"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm">Suporte Ágil</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Equipe pronta para te atender no WhatsApp.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegação Principal do Rodapé -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            
            <!-- Coluna 1 & 2: Sobre a Loja -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}" class="inline-block">
                    @if(!empty($config->logo_img) && $config->logo_img !== '/imgs/no-image.png')
                        <img src="{{ $config->logo_img }}" alt="{{ $config->nome }}" class="h-10 w-auto object-contain brightness-200">
                    @else
                        <span class="text-2xl font-black text-white tracking-tight">{{ $config->nome }}</span>
                    @endif
                </a>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed max-w-sm">
                    {{ $config->descricao_breve ?? 'Sua melhor experiência de compra online. Qualidade, agilidade e total suporte para você e sua família.' }}
                </p>

                <!-- Redes Sociais -->
                <div class="flex items-center gap-2 pt-2">
                    @if(!empty($config->link_instagram))
                        <a href="{{ $config->link_instagram }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-rose-600 text-white flex items-center justify-center transition-colors"
                           title="Instagram">
                            <i class="ri-instagram-line text-lg"></i>
                        </a>
                    @endif
                    @if(!empty($config->link_facebook))
                        <a href="{{ $config->link_facebook }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-blue-600 text-white flex items-center justify-center transition-colors"
                           title="Facebook">
                            <i class="ri-facebook-circle-line text-lg"></i>
                        </a>
                    @endif
                    @if(!empty($config->link_whatsapp))
                        <a href="{{ $config->link_whatsapp }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white flex items-center justify-center transition-colors"
                           title="WhatsApp">
                            <i class="ri-whatsapp-line text-lg"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Coluna 3: Categorias -->
            <div>
                <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Departamentos</h5>
                <ul class="space-y-2 text-xs">
                    @foreach($cats->take(6) as $c)
                        <li>
                            <a href="{{ route('loja.produtos-categoria', [$c->hash_ecommerce, 'link' => $config->loja_id]) }}"
                               class="text-slate-400 hover:text-white transition-colors">
                                {{ $c->nome }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('loja.pesquisa', ['link' => $config->loja_id]) }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                            Ver todos os produtos &rarr;
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Coluna 4: Institucional -->
            <div>
                <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Institucional</h5>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}" class="text-slate-400 hover:text-white transition-colors">Minha Conta</a></li>
                    <li><a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}#pedidos" class="text-slate-400 hover:text-white transition-colors">Meus Pedidos</a></li>
                    <li><a href="{{ route('loja.carrinho', ['link' => $config->loja_id]) }}" class="text-slate-400 hover:text-white transition-colors">Carrinho de Compras</a></li>
                    <li><a href="{{ route('loja.politica-privacidade', ['link' => $config->loja_id]) }}" class="text-slate-400 hover:text-white transition-colors">Políticas de Privacidade</a></li>
                </ul>
            </div>

            <!-- Coluna 5: Atendimento e Dados -->
            <div>
                <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Atendimento</h5>
                <div class="space-y-3 text-xs text-slate-400">
                    @if(!empty($config->telefone))
                        <div class="flex items-center gap-2">
                            <i class="ri-phone-line text-indigo-400 text-sm"></i>
                            <span>{{ $config->telefone }}</span>
                        </div>
                    @endif
                    @if(!empty($config->email))
                        <div class="flex items-center gap-2">
                            <i class="ri-mail-line text-indigo-400 text-sm"></i>
                            <span class="truncate">{{ $config->email }}</span>
                        </div>
                    @endif
                    @if(!empty($config->endereco))
                        <div class="flex items-start gap-2">
                            <i class="ri-map-pin-line text-indigo-400 text-sm mt-0.5"></i>
                            <span>{{ $config->endereco }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formas de Pagamento e Selos de Segurança -->
        <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Pagamentos -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Formas de Pagamento:</span>
                <div class="flex items-center gap-2 text-2xl text-slate-400">
                    <span class="px-2 py-1 bg-slate-800 rounded-lg text-xs font-bold text-emerald-400 flex items-center gap-1">
                        <i class="ri-qr-code-line"></i> PIX
                    </span>
                    <span class="px-2 py-1 bg-slate-800 rounded-lg text-xs font-bold text-white">Cartão de Crédito</span>
                    <span class="px-2 py-1 bg-slate-800 rounded-lg text-xs font-bold text-white">Boleto</span>
                </div>
            </div>

            <!-- Selos -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/80 text-[11px] font-semibold text-slate-300">
                    <i class="ri-lock-2-line text-emerald-400 text-sm"></i>
                    <span>Ambiente Criptografado SSL</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/80 text-[11px] font-semibold text-slate-300">
                    <i class="ri-shield-check-line text-indigo-400 text-sm"></i>
                    <span>Compra 100% Protegida</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="bg-slate-950 py-5 text-center text-xs text-slate-400 border-t border-slate-800/60 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} {{ $config->nome }}. Todos os direitos reservados.</span>
            <span>Plataforma E-commerce ERP</span>
        </div>
    </div>
</footer>
