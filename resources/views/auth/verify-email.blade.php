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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-heading font-black text-2xl tracking-tight text-slate-900 leading-none">
                            OdontoLead <span class="text-sky-600">AI</span>
                        </span>
                        <span class="text-[10px] font-semibold text-emerald-600 tracking-wider uppercase mt-1">Verificação de Conta</span>
                    </div>
                </a>

                <h1 class="font-heading text-2xl font-extrabold text-slate-900 tracking-tight">
                    Confirme seu E-mail
                </h1>
                <p class="text-xs text-slate-500 mt-2 font-normal leading-relaxed">
                    Antes de continuar, por favor confirme seu endereço de e-mail clicando no link que acabamos de enviar para sua caixa de entrada. Se você não recebeu, podemos enviar outro.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Um novo link de confirmação foi enviado para o endereço de e-mail cadastrado.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reenviar E-mail de Confirmação</span>
                </button>
            </form>

            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100 pt-5 text-xs">
                <a href="{{ route('profile.show') }}" class="text-slate-600 hover:text-slate-900 font-semibold transition">
                    Editar Perfil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition">
                        Encerrar Sessão
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
