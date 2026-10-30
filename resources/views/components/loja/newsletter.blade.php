{{-- Seção de Newsletter Moderna --}}
<section class="relative bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 text-white py-16 px-4 overflow-hidden rounded-3xl my-12 max-w-7xl mx-auto shadow-2xl border border-indigo-800/40"
         x-data="{
            email: '',
            subscribed: false,
            submit() {
                if (!this.email || !this.email.includes('@')) return;
                this.subscribed = true;
            }
         }">
    <!-- Detalhes de luz de fundo -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto text-center space-y-6">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-indigo-300 border border-white/10">
            <i class="ri-mail-star-line text-amber-400"></i>
            Clube de Vantagens
        </span>

        <h3 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
            Receba descontos exclusivos e lançamentos em primeira mão.
        </h3>

        <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto leading-relaxed">
            Cadastre o seu e-mail para receber cupons de desconto, ofertas relâmpago e promoções personalizadas diretamente na sua caixa de entrada.
        </p>

        <!-- Formulário ou Mensagem de Sucesso -->
        <div class="max-w-md mx-auto pt-2">
            <template x-if="!subscribed">
                <form @submit.prevent="submit()" class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <input type="email"
                               x-model="email"
                               required
                               placeholder="Digite seu melhor e-mail..."
                               class="w-full px-4 py-3.5 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:bg-white/20 focus:border-white focus:outline-none text-sm transition-all">
                        <i class="ri-mail-line absolute right-4 top-3.5 text-slate-400 text-lg"></i>
                    </div>
                    <button type="submit"
                            class="px-6 py-3.5 rounded-2xl bg-indigo-500 hover:bg-indigo-400 text-white font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 whitespace-nowrap">
                        Cadastrar
                    </button>
                </form>
            </template>

            <template x-if="subscribed">
                <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 flex items-center justify-center gap-2 text-sm font-semibold animate-fade-in">
                    <i class="ri-checkbox-circle-fill text-lg text-emerald-400"></i>
                    <span>Obrigado! Seu e-mail foi cadastrado com sucesso.</span>
                </div>
            </template>
            <p class="text-[11px] text-slate-400 mt-2">Prometemos não enviar spam. Você pode cancelar sua inscrição a qualquer momento.</p>
        </div>
    </div>
</section>
