@props(['plans' => null])

<section id="planos" class="py-20 bg-white border-t border-slate-200/80 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="gsap-reveal text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Planos & Investimento
            </span>
            <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Preço justo que se paga com <span class="text-emerald-600">1 única consulta</span> a mais
            </h2>
            <p class="text-base text-slate-600 font-normal leading-relaxed">
                Todas as novas clínicas recebem <strong>14 dias de teste grátis completo</strong> sem necessidade de cadastrar cartão de crédito.
            </p>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="gsap-stagger-group grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-stretch">
            
            <!-- Plan 1: Essencial -->
            <div class="gsap-card bg-slate-50/80 rounded-3xl p-8 sm:p-10 border border-slate-200 hover:border-slate-300 transition duration-200 flex flex-col justify-between shadow-sm">
                <div class="space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Para Consultórios em Início</span>
                        <h3 class="font-heading text-2xl font-bold text-slate-900 mt-1">Plano Essencial</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Ideal para dentistas autônomos e pequenos consultórios captando pacientes via Google e Instagram.
                        </p>
                    </div>

                    <div class="py-4 border-y border-slate-200/70 flex items-baseline gap-2">
                        <span class="text-sm font-semibold text-slate-500">R$</span>
                        <span class="text-4xl font-extrabold text-slate-900 font-heading">99,90</span>
                        <span class="text-xs font-medium text-slate-500">/ mês</span>
                    </div>

                    <ul class="space-y-3.5 text-xs text-slate-700">
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Até <strong>100 agendamentos</strong> confirmados / mês</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Landing page com slug exclusivo da sua clínica</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Triagem clínica inteligente e questionário de sintomas</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Grade semanal com prevenção de conflitos de horário</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Transbordo de dados pronto para o WhatsApp</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Suporte técnico via WhatsApp</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-8">
                    <a href="{{ route('register') }}" class="w-full py-3.5 bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs rounded-xl border border-slate-300 shadow-xs transition flex items-center justify-center gap-2">
                        <span>Iniciar 14 Dias Grátis</span>
                        <span>&rarr;</span>
                    </a>
                    <p class="text-[10px] text-center text-slate-400 mt-2">Sem cartão de crédito no cadastro</p>
                </div>
            </div>

            <!-- Plan 2: Profissional (Featured / Glow) -->
            <div class="gsap-card bg-white rounded-3xl p-8 sm:p-10 border-2 border-emerald-500 shadow-xl shadow-emerald-500/10 relative overflow-hidden flex flex-col justify-between ring-2 ring-emerald-500/20">
                <div class="absolute -top-5 right-6">
                    <span class="px-3.5 py-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-extrabold text-[10px] uppercase tracking-wider rounded-full shadow-md">
                        Mais Escolhido
                    </span>
                </div>

                <div class="space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Para Clínicas em Escala</span>
                        <h3 class="font-heading text-2xl font-bold text-slate-900 mt-1">Plano Profissional</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Para clínicas com múltiplos tratamentos, maior verba de tráfego pago e alto fluxo de pacientes.
                        </p>
                    </div>

                    <div class="py-4 border-y border-slate-100 flex items-baseline gap-2">
                        <span class="text-sm font-semibold text-slate-500">R$</span>
                        <span class="text-4xl font-extrabold text-slate-900 font-heading">199,90</span>
                        <span class="text-xs font-medium text-slate-500">/ mês</span>
                    </div>

                    <ul class="space-y-3.5 text-xs text-slate-700">
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Até <strong>300 agendamentos</strong> confirmados / mês (3x mais)</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Tudo incluso no Plano Essencial</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Triagem avançada com BYOK (OpenAI e Gemini com chave própria)</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Painel com prioridade de processamento e webhooks</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Gestão de novidades in-app e suporte prioritário</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-8">
                    <a href="{{ route('register') }}" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/25 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Experimentar Profissional (14 Dias Grátis)</span>
                        <span>&rarr;</span>
                    </a>
                    <p class="text-[10px] text-center text-slate-400 mt-2">Sem taxa de adesão • Cancele quando quiser</p>
                </div>
            </div>

        </div>

        <!-- Security & Guarantee Footer Box -->
        <div class="gsap-reveal mt-12 max-w-4xl mx-auto p-4 bg-slate-50 rounded-2xl border border-slate-200/80 flex flex-wrap items-center justify-center gap-8 text-xs text-slate-500 font-medium">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Pagamento Seguro via Mercado Pago</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>14 Dias Grátis Sem Fidelidade</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Notas Fiscais Automáticas</span>
            </div>
        </div>

    </div>
</section>
