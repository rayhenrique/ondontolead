<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80 transition duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="/" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 bg-gradient-to-tr from-sky-600 via-indigo-600 to-emerald-500 rounded-xl flex items-center justify-center shadow-md shadow-sky-600/10 ring-1 ring-slate-900/5 group-hover:scale-105 transition transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-extrabold text-xl tracking-tight text-slate-900 leading-none">
                    OdontoLead <span class="text-sky-600 font-black">AI</span>
                </span>
                <span class="text-[10px] font-semibold text-emerald-600 tracking-wider uppercase mt-1">Recepção Cirúrgica 24/7</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600">
            <a href="#problema" class="hover:text-slate-900 transition">O Gargalo</a>
            <a href="#como-funciona" class="hover:text-slate-900 transition">Como Funciona</a>
            <a href="#calculadora" class="hover:text-slate-900 transition">Calculadora ROI</a>
            <a href="#planos" class="hover:text-slate-900 transition">Planos</a>
            <a href="#faq" class="hover:text-slate-900 transition">Dúvidas</a>
        </nav>

        <!-- CTAs -->
        <div class="hidden md:flex items-center space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                        <span>Acessar Painel</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition px-2 py-2">
                        Entrar
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-sm shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                            <span>Teste Grátis (14 dias)</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                @endauth
            @endif
        </div>

        <!-- Mobile Hamburger Button -->
        <div class="flex items-center md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" aria-label="Abrir Menu">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-3 shadow-lg">
        <nav class="flex flex-col space-y-3 text-sm font-semibold text-slate-700">
            <a @click="mobileMenuOpen = false" href="#problema" class="px-3 py-2 rounded-lg hover:bg-slate-50 transition">O Gargalo</a>
            <a @click="mobileMenuOpen = false" href="#como-funciona" class="px-3 py-2 rounded-lg hover:bg-slate-50 transition">Como Funciona</a>
            <a @click="mobileMenuOpen = false" href="#calculadora" class="px-3 py-2 rounded-lg hover:bg-slate-50 transition">Calculadora ROI</a>
            <a @click="mobileMenuOpen = false" href="#planos" class="px-3 py-2 rounded-lg hover:bg-slate-50 transition">Planos</a>
            <a @click="mobileMenuOpen = false" href="#faq" class="px-3 py-2 rounded-lg hover:bg-slate-50 transition">Dúvidas</a>
        </nav>
        <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full text-center px-4 py-2.5 bg-slate-900 text-white font-bold text-xs rounded-xl shadow-xs">
                        Acessar Painel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 text-slate-700 font-semibold text-xs border border-slate-200 rounded-xl hover:bg-slate-50">
                        Entrar na Conta
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs">
                            Começar Teste Grátis (14 dias)
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</header>
