<section id="faq" class="py-20 bg-slate-50 border-t border-slate-200/80 relative overflow-hidden" x-data="{ active: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="gsap-reveal text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-200">
                Tire Suas Dúvidas
            </span>
            <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Perguntas Frequentes
            </h2>
            <p class="text-base text-slate-600 font-normal">
                Tudo o que você precisa saber sobre o teste grátis, agendamentos e IA.
            </p>
        </div>

        <!-- Accordion List -->
        <div class="gsap-reveal space-y-4">
            
            <!-- Item 1 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition">
                <button @click="active = (active === 1 ? null : 1)" type="button" class="w-full p-6 text-left flex items-center justify-between gap-4 font-heading font-bold text-base text-slate-900 hover:text-sky-600 transition">
                    <span>Como funciona o teste grátis de 14 dias? Preciso de cartão?</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-mono text-sm transition transform" :class="{ 'rotate-180 bg-sky-100 text-sky-700': active === 1 }">
                        &darr;
                    </span>
                </button>
                <div x-show="active === 1" x-cloak x-collapse class="px-6 pb-6 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100">
                    Não! Você cria a conta da sua clínica em menos de 2 minutos <strong>sem precisar cadastrar nenhum cartão de crédito</strong>. Durante os 14 dias você terá acesso irrestrito a todos os recursos da plataforma. Se após o período você decidir não assinar, nada será cobrado.
                </div>
            </div>

            <!-- Item 2 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition">
                <button @click="active = (active === 2 ? null : 2)" type="button" class="w-full p-6 text-left flex items-center justify-between gap-4 font-heading font-bold text-base text-slate-900 hover:text-sky-600 transition">
                    <span>Como o sistema evita que dois pacientes marquem o mesmo horário?</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-mono text-sm transition transform" :class="{ 'rotate-180 bg-sky-100 text-sky-700': active === 2 }">
                        &darr;
                    </span>
                </button>
                <div x-show="active === 2" x-cloak x-collapse class="px-6 pb-6 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100">
                    O OdontoLead AI utiliza transações atômicas com bloqueio pessimista direto na engine do banco de dados (MySQL InnoDB com <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">lockForUpdate</code>) e índice de unicidade. No instante em que um paciente seleciona e confirma um horário, ele é travado. Se outro visitante tentar no mesmo segundo, o sistema informa que a vaga acabou de ser preenchida e oferece os próximos horários livres.
                </div>
            </div>

            <!-- Item 3 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition">
                <button @click="active = (active === 3 ? null : 3)" type="button" class="w-full p-6 text-left flex items-center justify-between gap-4 font-heading font-bold text-base text-slate-900 hover:text-sky-600 transition">
                    <span>Preciso pagar pela API da OpenAI ou do Gemini?</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-mono text-sm transition transform" :class="{ 'rotate-180 bg-sky-100 text-sky-700': active === 3 }">
                        &darr;
                    </span>
                </button>
                <div x-show="active === 3" x-cloak x-collapse class="px-6 pb-6 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100">
                    Não obrigatoriamente. O sistema conta com uma engine inteligente de regras clínicas e questionário determinístico que funciona de forma 100% autônoma e gratuita sem necessidade de chaves. Caso sua clínica queira ativar análise semântica avançada com GPT-4o mini ou Gemini Flash, basta colar sua chave de API própria no painel (modelo BYOK com criptografia AES-256).
                </div>
            </div>

            <!-- Item 4 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition">
                <button @click="active = (active === 4 ? null : 4)" type="button" class="w-full p-6 text-left flex items-center justify-between gap-4 font-heading font-bold text-base text-slate-900 hover:text-sky-600 transition">
                    <span>O sistema substitui a secretária da clínica?</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-mono text-sm transition transform" :class="{ 'rotate-180 bg-sky-100 text-sky-700': active === 4 }">
                        &darr;
                    </span>
                </button>
                <div x-show="active === 4" x-cloak x-collapse class="px-6 pb-6 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100">
                    Não, ele é o melhor aliado dela! Em vez da secretária passar 40 minutos em trocas de mensagens para perguntar onde dói e que dia o paciente pode vir, ela recebe no WhatsApp uma mensagem estruturada com nome, telefone, queixa, nível de urgência avaliado pela IA e o horário reservado. A recepcionista apenas confirma e dá as boas-vindas com foco em acolhimento humano.
                </div>
            </div>

            <!-- Item 5 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition">
                <button @click="active = (active === 5 ? null : 5)" type="button" class="w-full p-6 text-left flex items-center justify-between gap-4 font-heading font-bold text-base text-slate-900 hover:text-sky-600 transition">
                    <span>Como divulgo a página da minha clínica?</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-mono text-sm transition transform" :class="{ 'rotate-180 bg-sky-100 text-sky-700': active === 5 }">
                        &darr;
                    </span>
                </button>
                <div x-show="active === 5" x-cloak x-collapse class="px-6 pb-6 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100">
                    Ao criar a conta, sua clínica recebe um link público direto (ex: <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">odontolead.app/nome-da-sua-clinica</code>). Você pode colocar esse link na bio do Instagram, em anúncios no Google Ads, Meta Ads ou enviar em campanhas de WhatsApp. O design é 100% responsivo e abre com carregamento instantâneo em qualquer celular.
                </div>
            </div>

            <!-- Item 6 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition">
                <button @click="active = (active === 6 ? null : 6)" type="button" class="w-full p-6 text-left flex items-center justify-between gap-4 font-heading font-bold text-base text-slate-900 hover:text-sky-600 transition">
                    <span>Existe contrato de fidelidade ou multa para cancelar?</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-mono text-sm transition transform" :class="{ 'rotate-180 bg-sky-100 text-sky-700': active === 6 }">
                        &darr;
                    </span>
                </button>
                <div x-show="active === 6" x-cloak x-collapse class="px-6 pb-6 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100">
                    Nenhum contrato de fidelidade. As assinaturas são mensais e você pode cancelar quando quiser com apenas um clique, sem burocracia ou multas.
                </div>
            </div>

        </div>

    </div>
</section>
