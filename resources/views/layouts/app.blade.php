<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-slate-50 text-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'OdontoLead AI') }}</title>

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
    
    @php
        $currentUser = Auth::user();
        $currentClinic = $currentUser?->clinic;
        if (!$currentClinic && $currentUser?->is_superadmin) {
            $currentClinic = \App\Models\Clinic::query()->first();
        }
        $trialDays = $currentClinic && $currentClinic->subscription_status === 'trial' && $currentClinic->trial_ends_at
            ? max(0, (int) now()->diffInDays($currentClinic->trial_ends_at, false))
            : ($currentClinic && $currentClinic->subscription_status === 'trial' ? 14 : null);
    @endphp

    <div x-data="{ sidebarOpen: false, userMenuOpen: false }" class="min-h-screen flex flex-col">

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
                <div class="h-20 px-5 flex items-center justify-between border-b border-slate-100">
                    <a href="{{ route('app.dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-tr from-sky-600 via-indigo-600 to-emerald-500 rounded-xl flex items-center justify-center shadow-md shadow-sky-600/15 ring-1 ring-slate-900/5 group-hover:scale-105 transition transform shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-heading font-black text-lg tracking-tight text-slate-900 leading-none">
                                OdontoLead <span class="text-sky-600 font-extrabold">AI</span>
                            </span>
                            <span class="text-[11px] font-semibold text-slate-500 truncate max-w-[140px] mt-1" title="{{ $currentClinic?->name ?? 'Portal da Clínica' }}">
                                {{ $currentClinic?->name ?? 'Minha Clínica' }}
                            </span>
                        </div>
                    </a>

                    <!-- Close button on mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Clinic Status Chip in Sidebar -->
                <div class="px-4 py-3 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2 truncate">
                        @if ($currentClinic && $currentClinic->subscription_status === 'trial')
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span class="font-semibold text-emerald-800 truncate">Trial ({{ $trialDays }}d restantes)</span>
                        @elseif ($currentClinic && $currentClinic->subscription_status === 'active')
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="font-semibold text-emerald-800">Plano Ativo</span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span class="font-semibold text-amber-800">Assinatura Pendente</span>
                        @endif
                    </div>
                    @if ($currentClinic && $currentClinic->subscription_status === 'trial')
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded">Grátis</span>
                    @endif
                </div>

                <!-- Navigation Groups -->
                <nav class="p-4 space-y-6">
                    <div>
                        <span class="text-[10px] font-extrabold tracking-wider text-slate-400 uppercase px-3 block mb-2 font-mono">
                            Menu Principal
                        </span>
                        <div class="space-y-1">
                            <!-- Dashboard -->
                            <a href="{{ route('app.dashboard') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('app.dashboard') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('app.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>

                            <!-- Agendamentos & Triagens -->
                            <a href="{{ route('app.appointments.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('app.appointments.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('app.appointments.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Agendamentos & Triagens</span>
                            </a>

                            <!-- Grade Semanal -->
                            <a href="{{ route('app.schedule') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('app.schedule') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('app.schedule') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Grade de Atendimento</span>
                            </a>

                            <!-- Configurações -->
                            <a href="{{ route('app.settings.edit') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('app.settings.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('app.settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Configurações da Clínica</span>
                            </a>

                            <!-- Novidades -->
                            <a href="{{ route('app.releases.index') }}" 
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('app.releases.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 {{ request()->routeIs('app.releases.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    <span>Novidades</span>
                                </div>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ request()->routeIs('app.releases.*') ? 'bg-white/20 text-white' : 'bg-sky-100 text-sky-700' }}">
                                    v1.2.0
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- External Links & Shortcuts -->
                    <div>
                        <span class="text-[10px] font-extrabold tracking-wider text-slate-400 uppercase px-3 block mb-2 font-mono">
                            Canais & Acessos
                        </span>
                        <div class="space-y-1">
                            @if ($currentClinic && $currentClinic->slug)
                                <a href="{{ url('/' . $currentClinic->slug) }}" 
                                   target="_blank" 
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-sky-700 hover:bg-sky-50/70 transition group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-sky-500 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span class="truncate">Página de Agendamento</span>
                                    </div>
                                    <span class="text-[10px] text-sky-600 font-mono">↗</span>
                                </a>
                            @endif

                            @if ($currentUser?->is_superadmin)
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-amber-900 bg-amber-50 hover:bg-amber-100/80 border border-amber-200/70 transition">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span>Painel SuperAdmin</span>
                                    </div>
                                    <span class="text-[10px] bg-amber-200 text-amber-900 px-1.5 py-0.5 rounded uppercase">Root</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Sidebar Bottom: Trial Card & User Profile -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 space-y-4">
                
                @if ($currentClinic && $currentClinic->subscription_status === 'trial')
                    <!-- Mini Trial Card -->
                    <div class="p-3 bg-gradient-to-br from-indigo-50/90 to-sky-50/80 border border-indigo-200/60 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-indigo-950">Trial Gratuito</span>
                            <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md uppercase">14 Dias</span>
                        </div>
                        <p class="text-[11px] text-slate-600 mt-1 leading-snug">
                            Restam <strong class="text-indigo-900 font-bold">{{ $trialDays }} dias</strong> sem necessidade de cartão.
                        </p>
                    </div>
                @endif

                <!-- User Profile Summary -->
                <div class="flex items-center justify-between pt-1">
                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 min-w-0 group">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && $currentUser->profile_photo_url)
                            <img class="h-9 w-9 rounded-full object-cover border border-slate-200 ring-2 ring-emerald-500/20" src="{{ $currentUser->profile_photo_url }}" alt="{{ $currentUser->name }}" />
                        @else
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-slate-800 to-slate-900 text-white font-bold text-xs flex items-center justify-center border border-slate-200 shadow-xs">
                                {{ strtoupper(substr($currentUser->name ?? 'U', 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex flex-col min-w-0 text-left">
                            <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">
                                {{ $currentUser->name }}
                            </span>
                            <span class="text-[10px] text-slate-500 truncate">
                                {{ $currentUser->email }}
                            </span>
                        </div>
                    </a>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Sair da Conta">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper (Offset by sidebar on desktop) -->
        <div class="lg:pl-64 flex flex-col flex-1 min-w-0">

            <!-- Impersonation Alert Banner (if active) -->
            @if (session()->has('impersonator_id'))
                <div class="bg-gradient-to-r from-amber-500/15 via-amber-500/10 to-transparent border-b border-amber-500/20 px-4 sm:px-6 py-2.5 text-xs sm:text-sm font-medium text-amber-900 flex items-center justify-between backdrop-blur-xs">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                        <span>Você está personificando a clínica <strong>{{ $currentClinic?->name ?? 'Sem clínica' }}</strong> como <strong>{{ $currentUser->name }}</strong>.</span>
                    </div>
                    <form method="POST" action="{{ route('admin.impersonate.leave') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition shadow-xs">
                            Sair da Personificação
                        </button>
                    </form>
                </div>
            @endif

            <x-banner />

            <!-- Sticky Top Header -->
            <header class="sticky top-0 z-30 bg-white/85 backdrop-blur-md border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                    
                    <!-- Left: Mobile Hamburger & Page Context -->
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>

                        <div class="flex items-center space-x-2 min-w-0">
                            <span class="font-heading font-bold text-sm sm:text-base text-slate-800 truncate max-w-[120px] sm:max-w-xs">
                                {{ $currentClinic?->name ?? 'OdontoLead AI' }}
                            </span>
                            @if ($currentClinic && $currentClinic->subscription_status === 'trial')
                                <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider shrink-0">
                                    Trial 14 Dias
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Quick Live Status Badges & Profile Dropdown -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        @if ($currentClinic)
                            <!-- WhatsApp Status Pill -->
                            <div class="hidden md:flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100/90 text-slate-600 text-xs font-medium border border-slate-200/60">
                                <span class="w-1.5 h-1.5 rounded-full {{ $currentClinic->whatsapp_number ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span>WhatsApp: <strong>{{ $currentClinic->whatsapp_number ?? 'Não configurado' }}</strong></span>
                            </div>

                            <!-- IA BYOK Pill -->
                            <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold border border-sky-200/60">
                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="uppercase">IA: {{ $currentClinic->ai_provider !== 'none' ? $currentClinic->ai_provider : 'Fallback' }}</span>
                            </div>

                            <!-- Public Booking Link -->
                            @if ($currentClinic->slug)
                                <a href="{{ url('/' . $currentClinic->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 p-2 sm:px-3 sm:py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/80 transition shadow-xs" title="Página Pública">
                                    <span class="hidden sm:inline">Página Pública</span>
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        @endif

                        <!-- Profile Link / Settings -->
                        <a href="{{ route('profile.show') }}" class="p-1.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="Meu Perfil">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Heading (Optional slot) -->
            @if (isset($header))
                <div class="bg-white border-b border-slate-100 py-6 px-4 sm:px-6 lg:px-8 shadow-2xs">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 pb-16">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewire('app-release-modal')

    @stack('modals')

    @livewireScripts
</body>
</html>
