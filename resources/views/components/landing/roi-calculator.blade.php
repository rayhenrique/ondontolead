<section id="calculadora" class="py-20 bg-slate-50 relative overflow-hidden" x-data="{
    leads: 120,
    ticket: 1500,
    taxaComum: 0.10,
    taxaOdonto: 0.28,
    formatCurrency(val) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL', maximumFractionDigits: 0 }).format(val);
    },
    get consultasAtuais() {
        return Math.round(this.leads * this.taxaComum);
    },
    get consultasOdonto() {
        return Math.round(this.leads * this.taxaOdonto);
    },
    get consultasExtras() {
        return Math.max(0, this.consultasOdonto - this.consultasAtuais);
    },
    get faturamentoExtraMensal() {
        return this.consultasExtras * this.ticket;
    },
    get faturamentoExtraAnual() {
        return this.faturamentoExtraMensal * 12;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="gsap-reveal text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Simulador de Retorno Financeiro
            </span>
            <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Quanto dinheiro a sua clínica está <span class="text-emerald-600">deixando na mesa</span>?
            </h2>
            <p class="text-base text-slate-600 font-normal leading-relaxed">
                Arraste os seletores abaixo e veja em tempo real o faturamento recuperado ao eliminar o gargalo do atendimento manual.
            </p>
        </div>

        <!-- Calculator Card -->
        <div class="gsap-reveal max-w-4xl mx-auto bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl shadow-slate-200/60 ring-1 ring-slate-900/5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Controls (Left: 7 cols) -->
                <div class="lg:col-span-7 space-y-8">
                    
                    <!-- Control 1: Leads/mês -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Leads recebidos por mês:</span>
                            </label>
                            <span class="font-mono text-base font-extrabold text-sky-600 bg-sky-50 px-3 py-1 rounded-lg border border-sky-100" x-text="leads + ' leads'"></span>
                        </div>
                        <input type="range" min="20" max="500" step="10" x-model="leads" class="w-full h-2.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                        <div class="flex justify-between text-[11px] text-slate-400 font-medium">
                            <span>20 leads</span>
                            <span>250 leads</span>
                            <span>500 leads/mês</span>
                        </div>
                    </div>

                    <!-- Control 2: Ticket Médio -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Ticket médio por procedimento/consulta:</span>
                            </label>
                            <span class="font-mono text-base font-extrabold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100" x-text="formatCurrency(ticket)"></span>
                        </div>
                        <input type="range" min="300" max="5000" step="100" x-model="ticket" class="w-full h-2.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                        <div class="flex justify-between text-[11px] text-slate-400 font-medium">
                            <span>R$ 300</span>
                            <span>R$ 2.500</span>
                            <span>R$ 5.000</span>
                        </div>
                    </div>

                    <!-- Comparison summary mini cards -->
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[11px] font-semibold text-slate-500 block">Consultas Hoje (10% conv.):</span>
                            <span class="text-lg font-bold text-slate-800" x-text="consultasAtuais + ' pacientes'"></span>
                        </div>
                        <div class="p-3.5 bg-emerald-50/70 rounded-xl border border-emerald-200/60">
                            <span class="text-[11px] font-semibold text-emerald-800 block">Com OdontoLead (28% conv.):</span>
                            <span class="text-lg font-bold text-emerald-700" x-text="consultasOdonto + ' pacientes'"></span>
                        </div>
                    </div>

                </div>

                <!-- Result Box (Right: 5 cols) -->
                <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-2xl p-6 sm:p-8 space-y-6 flex flex-col justify-between shadow-xl shadow-slate-900/20">
                    
                    <div class="space-y-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-950/80 px-2.5 py-1 rounded-md border border-emerald-500/30 inline-block">
                            Impacto Financeiro Estimado
                        </span>
                        <p class="text-xs text-slate-300 font-normal">
                            Aumento de <strong class="text-white" x-text="consultasExtras + ' consultas marcadas'"></strong> todo mês.
                        </p>
                    </div>

                    <div class="py-4 border-y border-slate-800 space-y-3">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Receita Adicional por Mês:</span>
                            <div class="text-3xl sm:text-4xl font-black text-emerald-400 font-heading tracking-tight" x-text="formatCurrency(faturamentoExtraMensal)"></div>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Projeção Adicional Anual:</span>
                            <div class="text-lg font-bold text-slate-200" x-text="formatCurrency(faturamentoExtraAnual) + ' / ano'"></div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('register') }}" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                            <span>Começar Teste Gratuito (14 dias)</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <p class="text-[10px] text-center text-slate-400">
                            Sem compromisso • Cancele quando quiser • Sem cartão
                        </p>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
