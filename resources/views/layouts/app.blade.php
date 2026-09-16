<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        @if (session()->has('impersonator_id'))
            <div class="bg-amber-500 text-white px-4 py-2.5 text-sm font-medium shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Você está personificando a clínica <strong>{{ Auth::user()->clinic?->name ?? 'Sem clínica' }}</strong> como <strong>{{ Auth::user()->name }}</strong>.</span>
                </div>
                <form method="POST" action="{{ route('admin.impersonate.leave') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-white text-amber-900 text-xs font-bold px-3 py-1.5 rounded hover:bg-amber-50 transition shadow-sm">
                        Sair da Personificação
                    </button>
                </form>
            </div>
        @endif

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @livewire('app-release-modal')

        @stack('modals')

        @livewireScripts
    </body>
</html>
