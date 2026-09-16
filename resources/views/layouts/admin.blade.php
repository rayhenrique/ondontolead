<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-slate-50 text-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'OdontoLead AI — Painel SuperAdmin' }}</title>

    <!-- Typography: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-emerald-500 selection:text-white min-h-screen">
    
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col">

        <!-- Mobile Drawer Backdrop -->
        <div x-show="sidebarOpen" 
             x-cloak 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden">
        </div>

        <!-- Sidebar (Desktop Fixed & Mobile Slide-over) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/90 shadow-sm transition-transform duration-200 ease-in-out flex flex-col justify-between overflow-y-auto">
            
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-tr from-sky-600 via-indigo-600 to-emerald-500 rounded-xl flex items-center justify-center shadow-md shadow-sky-600/15 ring-1 ring-slate-900/5 group-hover:scale-105 transition transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-heading font-black text-lg tracking-tight text-slate-900 leading-none">
                                OdontoLead <span class="text-sky-600 font-extrabold">AI</span>
                            </span>
                            <span class="text-[10px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200/60 uppercase tracking-wider mt-1 w-fit">
                                SuperAdmin
                            </span>
                        </div>
                    </a>

                    <!-- Close button on mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Groups -->
                <nav class="p-4 space-y-6">
                    
                    <div>
                        <span class="text-[10px] font-extrabold tracking-wider text-slate-400 uppercase px-3 block mb-2 font-mono">
                            Gestão Global
                        </span>
                        <div class="space-y-1">
                            <!-- Dashboard -->
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Dashboard Geral</span>
                            </a>

                            <!-- Clínicas / Tenants -->
                            <a href="{{ route('admin.clinics.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.clinics.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.clinics.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>Clínicas (Tenants)</span>
                            </a>

                            <!-- Planos -->
                            <a href="{{ route('admin.plans.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.plans.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.plans.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span>Planos & Preços</span>
                            </a>

                            <!-- Releases / Novidades -->
                            <a href="{{ route('admin.releases.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.releases.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.releases.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                <span>Releases & Novidades</span>
                            </a>

                            <!-- Configurações do Sistema -->
                            <a href="{{ route('admin.settings.edit') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Configurações Globais</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] font-extrabold tracking-wider text-slate-400 uppercase px-3 block mb-2 font-mono">
                            Ambiente & Atalhos
                        </span>
                        <div class="space-y-1">
                            <a href="{{ route('app.dashboard') }}" 
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100/80 border border-sky-200/60 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span>Ver App da Clínica</span>
                                </span>
                                <span class="text-[10px] font-mono font-bold">&rarr;</span>
                            </a>

                            <a href="/" target="_blank" 
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    <span>Landing Page</span>
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                </nav>
            </div>

            <!-- User Info & Logout Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <img class="w-9 h-9 rounded-xl object-cover ring-1 ring-slate-200" 
                             src="{{ Auth::user()?->profile_photo_url }}" 
                             alt="{{ Auth::user()?->name }}">
                        <div class="overflow-hidden">
                            <span class="block text-xs font-bold text-slate-900 truncate">{{ Auth::user()?->name }}</span>
                            <span class="block text-[10px] text-slate-400 truncate">{{ Auth::user()?->email }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" 
                                title="Encerrar Sessão">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="pt-2 border-t border-slate-200/60 text-[10px] text-slate-400 text-center flex items-center justify-between">
                    <span>OdontoLead AI</span>
                    <a href="https://kltecnologia.com" target="_blank" rel="noopener noreferrer" class="hover:text-slate-600 font-semibold underline">KL Tecnologia</a>
                </div>
            </div>

        </aside>

        <!-- Main Column Content (With Left Margin for Fixed Sidebar on Desktop) -->
        <div class="lg:pl-64 flex flex-col flex-1 min-h-screen">
            
            <!-- Mobile Topbar with Hamburger Toggle -->
            <header class="lg:hidden h-16 bg-white border-b border-slate-200/80 px-4 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" aria-label="Abrir Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="font-heading font-extrabold text-base text-slate-900">
                        OdontoLead <span class="text-sky-600">Admin</span>
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-slate-700">Online</span>
                </div>
            </header>

            <!-- Impersonation Alert Banner -->
            @if (session()->has('impersonator_id'))
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white px-6 py-2.5 text-xs font-medium shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-amber-100 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Você está personificando a clínica <strong>{{ Auth::user()->clinic?->name ?? 'Sem clínica' }}</strong> como <strong>{{ Auth::user()->name }}</strong>.</span>
                    </div>
                    <form method="POST" action="{{ route('admin.impersonate.leave') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-white text-amber-900 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-amber-50 transition shadow-xs cursor-pointer">
                            Sair da Personificação
                        </button>
                    </form>
                </div>
            @endif

            <!-- Optional Page Header Slot -->
            @if (isset($header))
                <div class="bg-white border-b border-slate-200/80 py-5 px-4 sm:px-8 shadow-2xs">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </div>
            @endif

            <!-- Main Body Slot -->
            <main class="flex-1 py-8 px-4 sm:px-8 max-w-7xl w-full mx-auto space-y-6">
                {{ $slot }}
            </main>

        </div>

    </div>

    @livewire('app-release-modal')
    @stack('modals')
    @livewireScripts
</body>
</html>
