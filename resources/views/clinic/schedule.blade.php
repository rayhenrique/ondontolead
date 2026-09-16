<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-black text-2xl text-slate-900 tracking-tight leading-tight flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span>{{ __('Grade de Atendimento & Expediente') }}</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Configure os dias e turnos em que sua clínica atende e defina bloqueios pontuais para feriados ou recessos.
                </p>
            </div>
            <div>
                <a href="{{ route('app.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200/80 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition shadow-xs">
                    <span>&larr;</span>
                    <span>Voltar ao Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @livewire('clinic.schedule-manager')
        </div>
    </div>
</x-app-layout>
