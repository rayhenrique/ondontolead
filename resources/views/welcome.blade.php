<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OdontoLead AI — Captação, Triagem com IA e Agendamento para Clínicas Odontológicas</title>
    <meta name="description" content="Micro-SaaS para clínicas odontológicas: triagem inteligente com IA, seleção de horários em tempo real e encaminhamento qualificado para o WhatsApp. Teste 14 dias grátis sem cartão de crédito.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased selection:bg-indigo-500 selection:text-white flex flex-col justify-between">
    <!-- Navbar -->
    <header class="border-b border-slate-800/80 bg-slate-950/70 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 via-indigo-600 to-emerald-400 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 ring-1 ring-white/10">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-xl tracking-tight text-white flex items-center gap-1.5">
                        OdontoLead <span class="text-indigo-400">AI</span>
                    </span>
                    <span class="text-[10px] uppercase font-semibold tracking-wider text-emerald-400">Micro-SaaS Odontológico</span>
                </div>
            </div>

            <nav class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Acessar Painel &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-300 hover:text-white transition px-3 py-2">
                            Entrar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/30 transition flex items-center gap-1.5 ring-1 ring-white/10">
                                <span>Teste Grátis (14 dias)</span>
                                <span>&rarr;</span>
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Main Content / Hero -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="relative pt-16 pb-20 overflow-hidden">
            <!-- Background Glows -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="absolute top-1/3 right-10 w-[350px] h-[350px] bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
                <!-- Free Trial Pill -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/30 text-emerald-300 text-xs font-semibold shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>14 dias grátis • Sem cartão de crédito • Acesso completo</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-tight">
                    Capte mais pacientes com <br class="hidden sm:inline" />
                    <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-emerald-400 bg-clip-text text-transparent">Triagem Inteligente & Agendamento</span>
                </h1>

                <!-- Subtitle -->
                <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-400 leading-relaxed font-normal">
                    Transforme visitantes de tráfego pago em consultas marcadas na sua clínica. Triagem prévia com IA, agenda em tempo real sem conflitos e transbordo direto para o WhatsApp da sua recepção.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-xl shadow-indigo-600/30 transition transform hover:-translate-y-0.5 ring-1 ring-white/20 flex items-center justify-center gap-2">
                        <span>Iniciar Teste Grátis de 14 Dias</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <a href="#recursos" class="w-full sm:w-auto px-6 py-4 bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white font-semibold text-sm rounded-2xl border border-slate-800 transition">
                        Conhecer os Recursos &darr;
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Sem necessidade de cartão</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Setup pronto em 3 minutos</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Cancele quando quiser</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="recursos" class="py-16 border-t border-slate-900 bg-slate-950/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Construído para Consultórios & Clínicas</span>
                    <h2 class="text-3xl font-extrabold text-white">Tudo o que sua clínica precisa para fechar consultas</h2>
                    <p class="text-sm text-slate-400">Diga adeus a leads perdidos no WhatsApp por falta de resposta rápida ou confusão de horários.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-slate-900/60 border border-slate-800/80 p-8 rounded-2xl hover:border-slate-700 transition">
                        <div class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Triagem Clínica com IA</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            O paciente relata a dor e sintomas. Nossa engine de IA (Gemini ou OpenAI via BYOK) avalia a urgência e sugere o procedimento ideal antes mesmo do contato.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-slate-900/60 border border-slate-800/80 p-8 rounded-2xl hover:border-slate-700 transition">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Grade em Tempo Real</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Sua clínica define os dias de atendimento, horários de almoço e feriados bloqueados. Agendamento atômico que impede double-bookings mesmo em picos de tráfego.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-slate-900/60 border border-slate-800/80 p-8 rounded-2xl hover:border-slate-700 transition">
                        <div class="w-12 h-12 bg-purple-500/10 text-purple-400 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Transbordo para WhatsApp</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Ao finalizar, o paciente é direcionado ao WhatsApp da sua recepção com mensagem estruturada contendo nome, urgência, queixa e horário reservado.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trial CTA Banner -->
        <section class="py-16">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-indigo-900/60 via-purple-900/40 to-slate-900 border border-indigo-500/30 p-10 sm:p-12 rounded-3xl text-center space-y-6 shadow-2xl relative overflow-hidden">
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-white">
                        Pronto para modernizar o atendimento da sua clínica?
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto">
                        Crie sua conta agora e comece a usar de forma 100% gratuita por 14 dias. Sem cartão de crédito e sem pegadinhas.
                    </p>
                    <div>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                            <span>Criar Minha Conta Grátis (14 Dias)</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                &copy; {{ date('Y') }} OdontoLead AI. Todos os direitos reservados.
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-slate-400">14 dias grátis para novas clínicas</span>
            </div>
        </div>
    </footer>
</body>
</html>
