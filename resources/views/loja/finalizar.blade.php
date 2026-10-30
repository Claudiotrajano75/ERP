@extends('loja.default', ['title' => 'Pedido Finalizado'])

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <input type="hidden" value="{{ $pedido->transacao_id }}" id="transacao_id">
    <input type="hidden" value="{{ $pedido->status_pagamento }}" id="status">
    <input type="hidden" value="{{ $pedido->tipo_pagamento }}" id="tipo_pagamento">

    <div class="bg-white rounded-3xl border border-slate-100 p-8 sm:p-12 shadow-xl text-center space-y-6">
        
        <!-- Ícone de Sucesso -->
        <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto text-4xl shadow-md shadow-emerald-500/20">
            <i class="ri-checkbox-circle-fill"></i>
        </div>

        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block mb-1">Pedido Registrado com Sucesso</span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Obrigado pela sua compra!
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Número do seu pedido: <strong class="text-slate-800">#{{ $pedido->hash_pedido }}</strong>
            </p>
        </div>

        <!-- Valor Total -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 max-w-sm mx-auto">
            <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold block">Valor Total</span>
            <span class="text-2xl font-black text-slate-900">R$ {{ __moeda($pedido->valor_total) }}</span>
        </div>

        <!-- SEÇÃO: PIX -->
        @if($pedido->tipo_pagamento === 'pix')
            <div class="max-w-md mx-auto pt-4 space-y-5" x-data="{ copiado: false }">
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-800">
                    Abra o app do seu banco e aponte a câmera para o QR Code abaixo para efetuar o pagamento.
                </div>

                @if($pedido->qr_code_base64)
                    <div class="inline-block p-4 rounded-3xl bg-white border border-slate-200 shadow-sm">
                        <img src="data:image/jpeg;base64,{{ $pedido->qr_code_base64 }}"
                             alt="QR Code PIX #{{ $pedido->hash_pedido }}"
                             class="w-56 h-56 mx-auto object-contain">
                    </div>
                @endif

                @if($pedido->qr_code)
                    <div class="space-y-2 text-left">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Código Copia e Cola:</label>
                        <div class="flex gap-2">
                            <input type="text"
                                   id="qrcode_input"
                                   readonly
                                   value="{{ $pedido->qr_code }}"
                                   class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-700 outline-none select-all">
                            <button type="button"
                                    @click="
                                        navigator.clipboard.writeText('{{ $pedido->qr_code }}');
                                        copiado = true;
                                        setTimeout(() => { copiado = false; }, 3000);
                                    "
                                    class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-1 transition-all">
                                <i :class="copiado ? 'ri-check-line text-emerald-400' : 'ri-file-copy-line'"></i>
                                <span x-text="copiado ? 'Copiado!' : 'Copiar'"></span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-center gap-2 text-xs text-slate-400 pt-2">
                    <i class="ri-loader-4-line animate-spin text-indigo-600 text-base"></i>
                    <span>Aguardando a confirmação do pagamento...</span>
                </div>
            </div>

        <!-- SEÇÃO: BOLETO -->
        @elseif($pedido->tipo_pagamento === 'boleto')
            <div class="max-w-md mx-auto pt-4 space-y-4">
                <p class="text-xs text-slate-500">Seu boleto já está disponível. Imprima ou pague pelo código de barras no seu banco.</p>
                @if($pedido->link_boleto)
                    <a href="{{ $pedido->link_boleto }}" target="_blank"
                       class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-xl shadow-amber-600/30 transition-all hover:scale-105">
                        <i class="ri-printer-line text-lg"></i>
                        <span>Visualizar Boleto Bancário</span>
                    </a>
                @endif
            </div>

        <!-- SEÇÃO: DEPÓSITO -->
        @elseif($pedido->tipo_pagamento === 'deposito')
            <div class="max-w-md mx-auto pt-4 text-left space-y-4">
                <div class="p-4 rounded-2xl bg-violet-50 border border-violet-100 text-xs text-violet-900">
                    <strong class="font-bold block mb-1">Dados para Transferência:</strong>
                    {!! $config->dados_deposito !!}
                </div>

                <form method="POST" action="{{ route('loja.enviar-comprovante') }}" enctype="multipart/form-data" class="space-y-3 pt-2">
                    @csrf
                    <input type="hidden" name="link" value="{{ $config->loja_id }}">
                    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Anexar Comprovante de Pagamento:</label>
                    <input type="file" name="file" required accept="image/*, .pdf"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs shadow-md transition-all">
                        Enviar Comprovante
                    </button>
                </form>
            </div>
        @endif

        <!-- Botões de Ação Final -->
        <div class="pt-8 border-t border-slate-100 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('loja.index', ['link' => $config->loja_id]) }}"
               class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors">
                Voltar para a Página Inicial
            </a>
            <a href="{{ route('loja.minha-conta', ['link' => $config->loja_id]) }}#pedidos"
               class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-colors">
                Acompanhar Meus Pedidos
            </a>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript">
    $(function(){
        let status = $('#status').val();
        let tipo_pagamento = $('#tipo_pagamento').val();
        let transacao_id = $('#transacao_id').val();

        if(status != "approved" && tipo_pagamento == 'pix' && transacao_id){
            setInterval(() => {
                $.get('/api/ecommerce/consulta-pix/', { transacao_id: transacao_id })
                .done((res) => {
                    if(res == "approved"){
                        location.reload();
                    }
                });
            }, 3000);
        }
    });
</script>
@endsection
