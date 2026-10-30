@extends('loja.default', ['title' => 'Minha Conta'])

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{ activeTab: window.location.hash === '#pedidos' ? 'pedidos' : 'dados' }">
    
    <!-- Topo do Painel do Cliente -->
    <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-extrabold text-2xl shadow-sm">
                {{ strtoupper(substr($cliente->razao_social, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">{{ $cliente->razao_social }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">{{ $cliente->email }}</p>
            </div>
        </div>

        <a href="{{ route('loja.logoff', ['link' => $config->loja_id]) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors">
            <i class="ri-logout-box-r-line"></i>
            <span>Sair da Conta</span>
        </a>
    </div>

    <!-- Navegação de Abas -->
    <div class="flex border-b border-slate-200 gap-6 mb-8 text-sm font-bold">
        <button type="button"
                @click="activeTab = 'dados'"
                class="pb-3 border-b-2 transition-colors flex items-center gap-2"
                :class="activeTab === 'dados' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
            <i class="ri-user-settings-line text-base"></i>
            <span>Dados Pessoais & Endereços</span>
        </button>
        <button type="button"
                @click="activeTab = 'pedidos'"
                class="pb-3 border-b-2 transition-colors flex items-center gap-2"
                :class="activeTab === 'pedidos' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
            <i class="ri-file-list-3-line text-base"></i>
            <span>Meus Pedidos ({{ count($cliente->pedidos) }})</span>
        </button>
    </div>

    <!-- ABA 1: Dados Pessoais & Endereços -->
    <div x-show="activeTab === 'dados'" class="space-y-8">
        <!-- Form de Dados -->
        <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm">
            <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 mb-6">
                Atualizar Informações de Cadastro
            </h3>

            <form method="POST" action="{{ route('loja.update-cliente', [$cliente->id]) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="link" value="{{ $config->loja_id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome Completo *</label>
                        <input type="text" name="nome" required value="{{ $cliente->razao_social }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail *</label>
                        <input type="email" name="email" required value="{{ $cliente->email }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Telefone / Celular</label>
                        <input type="tel" name="telefone" oninput="maskPhone(this)" value="{{ $cliente->telefone }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nova Senha (opcional)</label>
                        <input type="password" name="senha" placeholder="Deixe em branco para manter a atual" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- Endereços Cadastrados -->
        <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm">
            <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 mb-6">
                Meus Endereços de Entrega
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($cliente->enderecosEcommerce as $end)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 space-y-1 text-xs">
                        <strong class="font-bold text-slate-900 text-sm block">{{ $end->rua }}, {{ $end->numero }}</strong>
                        <p class="text-slate-500">{{ $end->bairro }} - {{ $end->cidade->info ?? '' }}</p>
                        <p class="text-slate-400 font-mono">CEP: {{ $end->cep }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ABA 2: Histórico de Pedidos -->
    <div x-show="activeTab === 'pedidos'" style="display: none;" class="space-y-6">
        @forelse($cliente->pedidos as $p)
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-4">
                <!-- Cabeçalho do Pedido -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400">Pedido</span>
                        <strong class="text-slate-900 font-extrabold text-sm block">#{{ $p->hash_pedido }}</strong>
                        <span class="text-slate-400">{{ __data_pt($p->created_at) }}</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-base font-black text-indigo-600">R$ {{ __moeda($p->valor_total) }}</span>
                        @if($p->status_pagamento === 'approved')
                            <span class="px-3 py-1 rounded-full font-bold text-xs bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                <i class="ri-checkbox-circle-fill"></i> Pago
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full font-bold text-xs bg-amber-100 text-amber-800 flex items-center gap-1">
                                <i class="ri-time-line"></i> Aguardando Pagamento
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Itens -->
                <div class="divide-y divide-slate-50">
                    @foreach($p->itens as $i)
                        <div class="flex items-center justify-between gap-4 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $i->produto->img }}" alt="" class="w-12 h-12 rounded-xl object-cover bg-slate-50 border border-slate-100 flex-shrink-0">
                                <div class="min-w-0">
                                    <h6 class="text-xs font-bold text-slate-800 truncate">{{ $i->produto->nome }}</h6>
                                    <span class="text-[11px] text-slate-400">Qtd: {{ number_format($i->quantidade, 0) }}x R$ {{ __moeda($i->valor_unitario) }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-900">R$ {{ __moeda($i->sub_total) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Ações para Pedido Pendente (PIX ou Boleto) -->
                @if($p->status_pagamento !== 'approved')
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        @if($p->tipo_pagamento === 'pix')
                            <a href="{{ route('loja.nova-chavepix', ['link' => $config->loja_id, 'hash' => $p->hash_pedido]) }}"
                               class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 transition-colors">
                                <i class="ri-qr-code-line"></i>
                                <span>Pagar com PIX</span>
                            </a>
                        @elseif($p->tipo_pagamento === 'boleto' && $p->link_boleto)
                            <a href="{{ $p->link_boleto }}" target="_blank"
                               class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs flex items-center gap-1.5 transition-colors">
                                <i class="ri-printer-line"></i>
                                <span>Imprimir Boleto</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="py-16 px-4 text-center bg-white rounded-3xl border border-slate-100">
                <i class="ri-inbox-line text-4xl text-slate-300 mb-3 block"></i>
                <h4 class="font-bold text-slate-800 text-base mb-1">Você ainda não realizou nenhum pedido</h4>
                <p class="text-xs text-slate-400 mb-4">Seus pedidos e status de entrega aparecerão aqui.</p>
                <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition-colors">
                    Ir para a Loja
                </a>
            </div>
        @endforelse
    </div>
</div>

@endsection
