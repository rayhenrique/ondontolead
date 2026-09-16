<footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-slate-800/80 items-start">
            
            <!-- Brand & Description -->
            <div class="md:col-span-6 space-y-4">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-tr from-sky-500 to-emerald-400 rounded-xl flex items-center justify-center text-white shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-heading font-extrabold text-xl text-white tracking-tight">
                        OdontoLead <span class="text-sky-400">AI</span>
                    </span>
                </a>
                <p class="text-xs text-slate-400 leading-relaxed max-w-md">
                    Micro-SaaS multi-tenant para consultórios e clínicas odontológicas. Triagem inteligente de queixas clínicas, agendamento de consultas sem conflito e encaminhamento estruturado para o WhatsApp da recepção.
                </p>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/30 text-emerald-400 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>14 dias grátis sem necessidade de cartão de crédito</span>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="md:col-span-3 space-y-3">
                <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-300">Navegação</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#problema" class="hover:text-white transition">O Gargalo do WhatsApp</a></li>
                    <li><a href="#como-funciona" class="hover:text-white transition">Como Funciona a Triagem</a></li>
                    <li><a href="#calculadora" class="hover:text-white transition">Calculadora de ROI</a></li>
                    <li><a href="#planos" class="hover:text-white transition">Planos e Preços</a></li>
                    <li><a href="#faq" class="hover:text-white transition">Perguntas Frequentes</a></li>
                </ul>
            </div>

            <!-- Access & Legal -->
            <div class="md:col-span-3 space-y-3">
                <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-300">Acesso ao Sistema</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">Entrar no Painel</a></li>
                    <li><a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-bold transition">Criar Conta Grátis (14 dias) &rarr;</a></li>
                    @if (Route::has('terms.show'))
                        <li><a href="{{ route('terms.show') }}" class="hover:text-white transition">Termos de Uso</a></li>
                    @endif
                    @if (Route::has('policy.show'))
                        <li><a href="{{ route('policy.show') }}" class="hover:text-white transition">Política de Privacidade</a></li>
                    @endif
                </ul>
            </div>

        </div>

        <!-- Copyright Note (KL Tecnologia) -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} OdontoLead AI. Todos os direitos reservados. Desenvolvido por <a href="https://kltecnologia.com" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-white font-semibold underline decoration-slate-600 hover:decoration-white transition">KL Tecnologia</a>.
            </div>
            <div class="flex items-center space-x-6">
                <span>Versão de Produção v1.0.0</span>
                <span>•</span>
                <span>Multi-tenant Seguro</span>
            </div>
        </div>

    </div>
</footer>
