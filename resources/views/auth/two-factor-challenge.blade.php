<x-guest-layout>
    <div class="min-h-screen relative flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-slate-50">
        
        <!-- Ambient Background Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[450px] bg-gradient-to-tr from-sky-400/10 via-indigo-500/10 to-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <!-- Back to Login Link -->
        <div class="w-full max-w-md mb-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-900 transition group">
                <svg class="w-4 h-4 text-slate-400 group-hover:-translate-x-1 transition transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Voltar para o login</span>
            </a>
        </div>

        <!-- Card Container -->
        <div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-200/60 ring-1 ring-slate-900/5 relative" x-data="{ recovery: false }">
            
            <!-- Brand Logo Header -->
            <div class="flex flex-col items-center text-center mb-6">
                <a href="/" class="flex items-center space-x-3 group mb-4">
                    <div class="w-12 h-12 bg-gradient-to-tr from-sky-600 via-indigo-600 to-emerald-500 rounded-2xl flex items-center justify-center shadow-md shadow-sky-600/15 ring-1 ring-slate-900/5 group-hover:scale-105 transition transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-heading font-black text-2xl tracking-tight text-slate-900 leading-none">
                            OdontoLead <span class="text-sky-600">AI</span>
                        </span>
                        <span class="text-[10px] font-semibold text-emerald-600 tracking-wider uppercase mt-1">Autenticação em Duas Etapas</span>
                    </div>
                </a>

                <h1 class="font-heading text-2xl font-extrabold text-slate-900 tracking-tight">
                    Verificação de Segurança
                </h1>
                
                <p class="text-xs text-slate-500 mt-1.5 font-normal leading-relaxed" x-show="!recovery">
                    Digite o código de 6 dígitos gerado pelo seu aplicativo autenticador (Google Authenticator, Authy, etc.).
                </p>

                <p class="text-xs text-slate-500 mt-1.5 font-normal leading-relaxed" x-cloak x-show="recovery">
                    Digite um dos seus códigos de recuperação de emergência salvos anteriormente.
                </p>
            </div>

            <x-validation-errors class="mb-5" />

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                @csrf

                <div x-show="!recovery">
                    <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 font-mono">
                        Código do Autenticador *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <input id="code" 
                               type="text" 
                               inputmode="numeric" 
                               name="code" 
                               autofocus 
                               x-ref="code" 
                               autocomplete="one-time-code" 
                               placeholder="000 000"
                               class="pl-10 w-full text-center tracking-widest text-lg font-mono font-bold border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50/50 hover:bg-white transition" />
                    </div>
                </div>

                <div x-cloak x-show="recovery">
                    <label for="recovery_code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 font-mono">
                        Código de Recuperação *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        <input id="recovery_code" 
                               type="text" 
                               name="recovery_code" 
                               x-ref="recovery_code" 
                               autocomplete="one-time-code" 
                               placeholder="ex: abcde-12345"
                               class="pl-10 w-full font-mono text-xs sm:text-sm border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50/50 hover:bg-white transition" />
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Confirmar e Entrar</span>
                </button>
            </form>

            <div class="mt-6 text-center border-t border-slate-100 pt-5">
                <button type="button" 
                        class="text-xs font-medium text-sky-600 hover:text-sky-700 transition"
                        x-show="!recovery"
                        x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                    Perdeu o aplicativo? <span class="font-bold underline">Usar código de recuperação</span>
                </button>

                <button type="button" 
                        class="text-xs font-medium text-sky-600 hover:text-sky-700 transition"
                        x-cloak
                        x-show="recovery"
                        x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                    Tem o aplicativo? <span class="font-bold underline">Usar código autenticador</span>
                </button>
            </div>
        </div>
    </div>
</x-guest-layout>
