<x-guest-layout>
    <div class="min-h-screen relative flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-slate-50">
        
        <!-- Ambient Background Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[450px] bg-gradient-to-tr from-sky-400/10 via-indigo-500/10 to-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <!-- Card Container -->
        <div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-200/60 ring-1 ring-slate-900/5 relative">
            
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
                        <span class="text-[10px] font-semibold text-emerald-600 tracking-wider uppercase mt-1">Confirmação de Segurança</span>
                    </div>
                </a>

                <h1 class="font-heading text-2xl font-extrabold text-slate-900 tracking-tight">
                    Área Protegida
                </h1>
                <p class="text-xs text-slate-500 mt-1.5 font-normal leading-relaxed">
                    Esta é uma área com configurações sensíveis do sistema. Por favor, confirme sua senha antes de prosseguir.
                </p>
            </div>

            <x-validation-errors class="mb-5" />

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 font-mono">
                        Sua Senha Atual *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               autofocus
                               placeholder="Digite sua senha"
                               class="pl-10 w-full text-xs sm:text-sm border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50/50 hover:bg-white transition" />
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Confirmar Senha</span>
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
