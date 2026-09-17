<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 text-slate-100 selection:bg-emerald-500 selection:text-white">
        <!-- Top Banner / Header -->
        <header class="border-b border-slate-800/80 backdrop-blur-md bg-slate-900/60 sticky top-0 z-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-base sm:text-lg text-white tracking-tight block leading-tight">{{ $clinic->name }}</span>
                        <span class="text-[11px] text-emerald-400 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Atendimento Odontológico Especializado
                        </span>
                    </div>
                </div>

                @php
                    $cleanClinicPhone = preg_replace('/\D/', '', $clinic->whatsapp_number ?? '');
                @endphp
                @if ($cleanClinicPhone)
                    <a href="https://wa.me/55{{ $cleanClinicPhone }}" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-slate-700/80 transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                        </svg>
                        <span>{{ $clinic->whatsapp_number }}</span>
                    </a>
                @endif
            </div>
        </header>

        <!-- Main Container -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10">
            <!-- Hero Introduction -->
            <section class="text-center space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/80 border border-emerald-800/60 text-emerald-400">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Triagem Inteligente & Agendamento Rápido</span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight max-w-2xl mx-auto">
                    Agende sua consulta na <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-200">{{ $clinic->name }}</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-400 max-w-xl mx-auto">
                    Descreva seus sintomas, receba uma avaliação prévia personalizada e garanta seu horário diretamente na agenda sem filas ou espera.
                </p>

                <!-- Value Highlights Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 max-w-2xl mx-auto text-left">
                    <div class="p-3.5 rounded-2xl bg-slate-800/50 border border-slate-700/60 flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Horário Marcado</h4>
                            <p class="text-[11px] text-slate-400">Sem espera na recepção</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-800/50 border border-slate-700/60 flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-teal-500/10 text-teal-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Triagem Prévia</h4>
                            <p class="text-[11px] text-slate-400">Dentista já ciente da queixa</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-800/50 border border-slate-700/60 flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Confirmação WhatsApp</h4>
                            <p class="text-[11px] text-slate-400">Canal direto com a clínica</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Form Wizard Container -->
            <section class="max-w-2xl mx-auto text-slate-900">
                @if ($isPaused)
                    <div class="p-8 bg-slate-800/90 border border-slate-700 rounded-3xl text-center space-y-4 shadow-xl">
                        <div class="w-16 h-16 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Agendamentos Online Temporariamente Pausados</h3>
                        <p class="text-sm text-slate-300 max-w-md mx-auto">
                            A agenda online da clínica <strong>{{ $clinic->name }}</strong> está momentaneamente indisponível para novos agendamentos automáticos.
                        </p>
                        @if ($cleanClinicPhone)
                            <div class="pt-2">
                                <a href="https://wa.me/55{{ $cleanClinicPhone }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition shadow-lg">
                                    <span>Falar com a Clínica no WhatsApp</span>
                                    &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    @livewire('public.clinic-booking-wizard', ['clinic' => $clinic])
                @endif
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-800/60 py-8 mt-12 text-center text-xs text-slate-500">
            <div class="max-w-5xl mx-auto px-4 space-y-2">
                <p>&copy; {{ date('Y') }} {{ $clinic->name }}. Todos os direitos reservados.</p>
                <p class="text-[11px] text-slate-600">
                    Plataforma tecnológica de triagem e agendamento desenvolvida por <span class="text-slate-400 font-semibold">OdontoLead AI</span>.
                </p>
            </div>
        </footer>
    </div>
</x-guest-layout>
