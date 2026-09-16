<x-guest-layout>
    <div class="min-h-screen relative flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-slate-50">
        
        <!-- Subtle Ambient Background Glows (Matching Landing Page Hero) -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[750px] h-[500px] bg-gradient-to-tr from-sky-400/10 via-indigo-500/10 to-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <!-- Back to Home Link -->
        <div class="w-full max-w-xl mb-6 flex items-center justify-between">
            <a href="/" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-900 transition group">
                <svg class="w-4 h-4 text-slate-400 group-hover:-translate-x-1 transition transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Voltar para a página inicial</span>
            </a>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-[11px] font-bold rounded-full shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>14 Dias Grátis sem Cartão</span>
            </div>
        </div>

        <!-- Register Card -->
        <div class="w-full max-w-xl bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-200/60 ring-1 ring-slate-900/5 relative">
            
            <!-- Brand Logo Header -->
            <div class="flex flex-col items-center text-center mb-8">
                <a href="/" class="flex items-center space-x-3 group mb-4">
                    <div class="w-12 h-12 bg-gradient-to-tr from-sky-600 via-indigo-600 to-emerald-500 rounded-2xl flex items-center justify-center shadow-md shadow-sky-600/15 ring-1 ring-slate-900/5 group-hover:scale-105 transition transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-heading font-black text-2xl tracking-tight text-slate-900 leading-none">
                            OdontoLead <span class="text-sky-600">AI</span>
                        </span>
                        <span class="text-[10px] font-semibold text-emerald-600 tracking-wider uppercase mt-1">Recepção Cirúrgica 24/7</span>
                    </div>
                </a>

                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Crie a conta da sua clínica
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md font-normal">
                    Acesso completo e irrestrito por 14 dias para transformar seus leads do WhatsApp em consultas confirmadas.
                </p>
            </div>

            <!-- Validation Errors Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium rounded-2xl">
                    <div class="font-bold flex items-center gap-1.5 mb-1 text-rose-700">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Ajuste os dados abaixo para continuar:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nome do Gestor -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Seu Nome Completo *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Ex: Dra. Ana Paula Silva" class="block w-full pl-10 pr-3.5 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            E-mail Profissional *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="ana@odontosaude.com.br" class="block w-full pl-10 pr-3.5 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nome da Clínica -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="clinic_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nome da Clínica
                            </label>
                            <span class="text-[10px] text-slate-400">Opcional</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <input id="clinic_name" type="text" name="clinic_name" value="{{ old('clinic_name') }}" placeholder="Ex: Clínica Odonto Sorriso" class="block w-full pl-10 pr-3.5 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        </div>
                        <p class="text-[10px] text-slate-400">Gera seu link exclusivo (ex: odontolead.app/sua-clinica).</p>
                    </div>

                    <!-- WhatsApp da Clínica -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="whatsapp_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                WhatsApp da Recepção
                            </label>
                            <span class="text-[10px] text-slate-400">Opcional</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.42-.099.825z"/></svg>
                            </div>
                            <input id="whatsapp_number" type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="11999998888" class="block w-full pl-10 pr-3.5 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        </div>
                        <p class="text-[10px] text-slate-400">Para receber os alertas de consultas confirmadas.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Senha -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Senha de Acesso *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" class="block w-full pl-10 pr-3.5 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        </div>
                    </div>

                    <!-- Confirmação de Senha -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Confirmar Senha *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repita a senha" class="block w-full pl-10 pr-3.5 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Terms and Privacy Policy -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="pt-1">
                        <label for="terms" class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input id="terms" type="checkbox" name="terms" required class="w-4 h-4 mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition" />
                            <span class="text-xs text-slate-600 leading-relaxed">
                                {!! __('Eu concordo com os :terms_of_service e com a :privacy_policy da OdontoLead AI.', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sky-600 hover:text-sky-800 font-semibold">'.__('Termos de Uso').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sky-600 hover:text-sky-800 font-semibold">'.__('Política de Privacidade').'</a>',
                                ]) !!}
                            </span>
                        </label>
                    </div>
                @endif

                <!-- Submit CTA -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                        <span>Iniciar Teste Grátis por 14 Dias</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2">
                        Zero cobrança hoje • Não pedimos cartão de crédito • Cancele quando quiser
                    </p>
                </div>
            </form>

            <!-- Login Alternative -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <span class="text-slate-500">Sua clínica já é cadastrada?</span>
                <a href="{{ route('login') }}" class="font-bold text-sky-600 hover:text-sky-700 inline-flex items-center gap-1 transition">
                    <span>Acessar painel existente</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>

        <!-- Micro-footer -->
        <div class="mt-8 text-center text-[11px] text-slate-400 space-y-1">
            <p>&copy; {{ date('Y') }} OdontoLead AI. Desenvolvido por <a href="https://kltecnologia.com" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-slate-700 font-semibold underline">KL Tecnologia</a>.</p>
            <p>Conexão Criptografada SSL • Multi-tenant Isolado</p>
        </div>

    </div>
</x-guest-layout>
